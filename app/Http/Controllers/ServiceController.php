<?php

namespace App\Http\Controllers;

use App\Service;
use App\AdminNotification;
use Illuminate\Http\Request;
use App\Events\NewServiceCreated;
use Intervention\Image\Facades\Image;

class ServiceController extends Controller
{
    public function getMinFeatured(){
        $servs = Service::where('is_featured', true)->get();

        return response()->json($servs, 200);
    }

    public function getMinPopular(){
        $servs = Service::all();
        return response()->json($servs, 200);
    }

    public function getService($id){
        $service = Service::with('user')->findOrFail($id);

        return response()->json($service, 200);
    }

    public function incrementViewCount($id){
        $service = Service::findOrFail($id);

        $service->update([
            $service->views++
        ]);

        return response()->json($service->views, 200);
    }

    public function getAuthService(){
        $me = auth('api')->user()->id;
        $service = Service::where('user_id', $me)->first();
        if($service){
            return response()->json($service, 200);
        }
    }

    public function CreateService(Request $request){
        $this->validate($request, [
            'service.category_id' => 'required',
            'service.title' => 'required|min:10|max:250|unique:services,title',
            'service.position' => 'max:100',
            'service.description' => 'required|min:20|max:600',
            'service.experience_years' => 'required|integer',
            'service.highlights' => 'max:255',
            'service.keystrength' => 'max:150',
            'service.email' => 'email',
            'service.phone_number' => 'required|max:16',
            'service.website' => 'max:100',
            'service.address' => 'required|min:5|max:255',
            'service.city' => 'required|min:2|max:50',
            'service.facebook' => 'max:100',
            'service.instagram' => 'max:100',
            'service.twitter' => 'max:100',
        ]);

        $user = auth('api')->user()->id;

        $service = new Service;
        $service->category_id = $request->service['category_id'];
        $service->user_id = $user;
        $service->title = $request->service['title'];
        $service->position = $request->service['position'];
        $service->description = $request->service['description'];
        $service->highlight = $request->service['highlights'];
        $service->keystrength = $request->service['keystrength'];
        $service->experience_years = $request->service['experience_years'];
        $service->email = $request->service['email'];
        $service->phone = $request->service['phone_number'];
        $service->address = $request->service['address'];
        $service->city = $request->service['city'];
        $service->location_id = $request->service['location_id'];
        $service->website = $request->service['website'];
        $service->facebook = $request->service['facebook'];
        $service->instagram = $request->service['instagram'];
        $service->twitter = $request->service['twitter'];
        $service->save();

        $service->fresh();

        // create admin notif
        $notif = new AdminNotification;
        $notif->model = 'Service';
        $notif->is_read = false;
        $notif->save();

        // fire event to notify admin
        event(new NewServiceCreated($service));

        return response()->json($service, 201);
    }

    public function AddImageToService(Request $request){
        $service = auth('api')->user()->service;

        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        // save file in folder...later in s3 when ready to deploy
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 8).".".$ext;

            // check if image exists for the service and unlink
            $old_file = $service->image;
            if($old_file){
                $filePath = public_path('/images/services/'.$old_file);
                if(file_exists($filePath)){
                    unlink($filePath);
                }
            }

            //save new file in folder
            $file_loc = public_path('/images/services/'.$filename);
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $upload = Image::make($file)->resize(420, 320, function($constraint){
                    $constraint->aspectRatio();
                });
                $upload->sharpen(4)->save($file_loc);
            }
        }

        // save path in db
        $service->update([
            $service->image = $filename
        ]);

        return response()->json($service, 201);
    }

    public function deleteService($id){
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Service deleted'], 201);
    }

    public function getServicesByLocs($id){
        $services = Service::where('location_id', $id)->with('user')->latest()->paginate(5);

        return response()->json($services, 200);
    }

    public function searchForServices(Request $request){
        $q = $request->q;

        $this->validate($request, [
            'q' => 'required'
        ]);

        $services = Service::where('title', 'LIKE', "%".$q."%")
                            ->orWhere('description', 'LIKE', "%".$q."%")
                            ->orWhere('highlight', 'LIKE', "%".$q."%")
                            ->orWhere('email', 'LIKE', "%".$q."%")
                            ->orWhere('phone', 'LIKE', "%".$q."%")
                            ->orWhere('website', 'LIKE', "%".$q."%")
                            ->with('user')
                            ->with('category')
                            ->get();

        return response()->json($services, 200);
    }

    public function getSimilarServices($id){
        $serv = Service::findOrFail($id);

        $servs = Service::where('id', '!=', $id)->where('location_id', $serv->location_id)->where('category_id', $serv->category_id)->get();
        $servs = $servs->sortByDesc('ratings')->values()->take(4);

        // return response()->json($sorted, 200);
        return response()->json($servs, 200);
    }

    public function getPopularServices(){
        $servs = Service::all();
        $servs = $servs->sortByDesc('ratings')->values()->take(6);

        return response()->json($servs, 200);
    }

    public function getAllServices(){
        $servs = Service::all();

        return response()->json($servs, 200);
    }

    public function getTopServicesForLocation($service, $id){
        $services = Service::where('id', '!=', $service)->where('location_id', $id)->get();
        $services = $services->sortByDesc('ratings')->values()->take(4);

        return response()->json($services, 200);
    }
}
