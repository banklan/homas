<?php

namespace App\Http\Controllers;

use App\User;
use App\Review;
use App\Service;
use App\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class UserProfileController extends Controller
{
    public function getAuthUserService()
    {
        $user = auth('api')->user()->id;
        $service = Service::where('user_id', $user)->first();

        return response()->json($service, 200);
    }

    public function updateUserProfile(Request $request){
        $user = auth('api')->user();

        $validator = $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.phone' => 'required|max:14',
        ]);

        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->phone = $request->user['phone'],
        ]);

        return response()->json($user, 201);
    }

    public function confirmCurrentPswd(Request $request){
        $user = auth('api')->user();
        $current = $user->password;
        $check = Hash::check($request->password, $current);

        return response()->json($check, 200);
    }

    public function updateAuthUserPswd(Request $request){
        $this->validate($request, [
            'password' => 'required|min:5|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = auth('api')->user();
        $new = $request->password;

        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json(['message' => 'Password changed successfully'], 201);
    }

    public function updateUserProfilePic(Request $request)
    {
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $user = auth('api')->user();
        // check if picture exists for profile, then unlink
        $old_pic = $user->picture;
        if($old_pic){
            // $filePath = public_path('/images/profiles/users/'.$old_pic);
            $oldpath = '/profiles/' . $old_pic;
            if(file_exists($oldpath)){
                // unlink($filePath);
                Storage::disk('s3')->delete($oldpath);
            }
        }

        // save file in folder...later in s3 when ready to deploy
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 6).".".$ext;

            //save new file in folder
            //$file_loc = public_path('/images/profiles/users/'.$filename);
            $file_loc = '/profiles/' . $filename;
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $img = Image::make($file)->resize(420, 420, function($constraint){
                        $constraint->aspectRatio(); })->sharpen(2);
                // $upload->sharpen(2)->save($file_loc);
                $fixedImg = $img->stream();
                Storage::disk('s3')->put($file_loc, $fixedImg->__toString());
            }
        }

        // save path in db
        $user->update([
            $user->picture = $filename
        ]);

        return response()->json($user, 201);
    }

    public function getMyServiceReviews(){
        $user = auth('api')->user()->id;
        $my_service = Service::where('user_id', $user)->first();
        $revs = Review::where('service_id', $my_service->id)->latest()->paginate(20);
        return response()->json($revs, 201);
    }

    public function getAuthTestimonial(){
        $user = auth('api')->user()->id;
        $testim = Testimonial::where('user_id', $user)->first();
        if($testim){
            return response()->json($testim, 200);
        }
    }

    public function updateTestimonial(Request $request){
        $this->validate($request, [
            'testimonial.title' => 'required|min:4|max:50',
            'testimonial.detail' => 'required|min:10|max:400',
        ]);
        $user = auth('api')->user()->id;
        $test = Testimonial::where('user_id', $user)->first();
        $test->update([
            $test->title = $request->testimonial['title'],
            $test->detail = $request->testimonial['detail'],
        ]);
        return response()->json($test, 201);
    }

    public function checkAuthTestimonial(){
        $user = auth('api')->user()->id;
        $test = Testimonial::where('user_id', $user)->count();
        return response()->json($test, 201);
    }

    public function createTestimonial(Request $request){
        $this->validate($request, [
            'testimonial.title' => 'required|min:4|max:50',
            'testimonial.detail' => 'required|min:10|max:400',
        ]);
        $user = auth('api')->user()->id;
        $test = new Testimonial;
        $test->user_id = $user;
        $test->title = $request->testimonial['title'];
        $test->detail = $request->testimonial['detail'];
        $test->save();
        return response()->json($test, 201);
    }
}
