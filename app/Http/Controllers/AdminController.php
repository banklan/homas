<?php

namespace App\Http\Controllers;

use App\User;
use App\Admin;
use App\Review;
use App\Enquiry;
use App\Service;
use App\Category;
use App\Location;
use App\Portfolio;
use Carbon\Carbon;
use App\TestClient;
use App\Testimonial;
use App\PortfolioFile;
use App\PasswordResetLog;
use App\AdminNotification;
use Illuminate\Http\Request;
use App\Exports\LocationsExport;
use App\Imports\CategorysImport;
use App\Imports\LocationsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api');
    }

    public function getAllSuperUsers(){
        $admins = Admin::latest()->get();

        return response()->json($admins, 200);
    }

    public function updateSuperUser(Request $request, $id){
        $user = Admin::findOrFail($id);

        $validator = $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.role' => 'required',
            'user.phone' => 'required|max:14',
        ]);

        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->role = $request->user['role'],
            $user->phone = $request->user['phone'],
        ]);

        return response()->json($user, 201);
    }

    public function deleteSuperUser($id){
        $admin = Admin::findOrFail($id);

        $admin->delete();

        return response()->json(['message' => 'Admin Deleted'], 201);
    }

    public function getSuperUser($id){
        $admin = Admin::findOrFail($id);

        return response()->json($admin, 200);
    }

    public function toggleSuperUserStatus($id){
        $admin = Admin::findOrFail($id);

        if($admin->status == 0){
            $admin->update([
                $admin->status = 1
            ]);
        }else{
            $admin->update([
                $admin->status = 0
            ]);
        }

        return response()->json($admin->status, 200);
    }

    public function updateSuperUserPassword(Request $request, $id){
        $user = Admin::findOrFail($id);

        $this->validate($request, [
            'password.password' => 'required|min:5|max:20|confirmed',
            'password.password_confirmation' => 'required'
        ]);

        $new = $request->password['password'];
        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json($user, 200);
    }

    public function createSuperUser(Request $request){
        $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|max:14',
            'user.role' => 'required',
            'user.password' => 'required|min:5|max:30|confirmed',
            'user.password_confirmation' => 'required'
        ]);

        $user = new Admin;
        $user->first_name = $request->user['first_name'];
        $user->last_name = $request->user['last_name'];
        $user->email = $request->user['email'];
        $user->phone = $request->user['phone'];
        $user->role = $request->user['role'];
        $user->password = Hash::make($request->user['password']);
        $user->save();

        //create notification
        $notif = new AdminNotification;
        $notif->model = 'Admin';
        $notif->is_read = false;
        $notif->save();

        return response()->json($user, 201);
    }

    public function getPgntdUsers(){
        $users = User::latest()->paginate(15);

        return response()->json($users, 200);
    }

    public function updateUserStatus($id){
        $user = User::findOrFail($id);

        if($user->status == true){
            $user->update([
                $user->status = false
            ]);
        }else{
            $user->update([
                $user->status = true
            ]);
        }

        return response()->json($user->status, 200);
    }

    public function adminGetUser($id){
        $user = User::with('service')->findOrFail($id);

        return response()->json($user, 200);
    }

    public function updateUser(Request $request, $id){
        $validator = $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.phone' => 'required|max:14',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->phone = $request->user['phone'],
        ]);

        return response()->json($user, 201);
    }

    public function adminDelUser($id){
        $user = User::findOrFail($id);
        $service = Service::where('user_id',  $id)->first();

        $user->delete();
        $service->delete();

        return response()->json(['message' => 'User deleted!'], 200);
    }

    public function updateUserPassword(Request $request, $id){
        $this->validate($request, [
            'password.password' => 'required|min:5|max:20|confirmed',
            'password.password_confirmation' => 'required'
        ]);

        $user = User::findOrFail($id);

        $new = $request->password['password'];
        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json($user, 200);
    }

    public function adminCreateUser(Request $request){
        $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.email' => 'required|email|unique:users,email',
            'user.phone' => 'required|max:14',
            'user.password' => 'required|min:5|max:30|confirmed',
            'user.password_confirmation' => 'required'
        ]);

        $user = new User;
        $user->first_name = $request->user['first_name'];
        $user->last_name = $request->user['last_name'];
        $user->email = $request->user['email'];
        $user->phone = $request->user['phone'];
        $user->password = Hash::make($request->user['password']);
        $user->save();

        // create notif
        $notif = new AdminNotification;
        $notif->model = 'User';
        $notif->is_read = false;
        $notif->save();

        return response()->json($user, 201);
    }

    public function searchForUsers(Request $request){
        $q = $request->q;

        $users = User::where('first_name', 'LIKE', "%".$q."%")
                        ->orWhere('last_name', 'LIKE', "%".$q."%")
                        ->orWhere('email', 'LIKE', "%".$q."%")
                        ->orWhere('phone', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($users, 200);
    }

    public function getPgntdServices(){
        $services = Service::latest()->paginate(15);

        return response()->json($services, 200);
    }

    public function adminDelService($id){
        $serv = Service::findOrFail($id);
        $serv->delete();
        return response()->json(['message' => 'Service deleted'], 200);
    }

    public function adminGetService($id){
        $serv = Service::findOrFail($id);

        return response()->json($serv, 200);
    }

    public function toggleServiceIsApproved($id)
    {
        $rev = Review::findOrFail($id);
        if($rev->is_approved){
            $rev->update([
               $rev->is_approved = false
            ]);
        }else{
            $rev->update([
                $rev->is_approved = true
             ]);
        }

        return response()->json($rev, 200);
    }

    public function getServiceReviews($id){
        $revs = Review::where('service_id', $id)->get();

        return response()->json($revs, 200);
    }

    public function adminUpdateService($id, Request $request){
        $service = Service::findOrFail($id);
        $this->validate($request, [
            'service.category_id' => 'required|integer',
            'service.title' => 'required|min:10|max:200',
            'service.position' => 'max:100',
            'service.description' => 'required|min:20|max:600',
            'service.experience_years' => 'required|integer',
            'service.highlight' => 'max:255',
            'service.keystrength' => 'max:150',
            'service.email' => 'email',
            'service.phone' => 'required|max:16',
            'service.website' => 'max:100',
            'service.address' => 'required|min:5|max:255',
            'service.city' => 'required|min:2|max:50',
            'service.facebook' => 'max:100',
            'service.instagram' => 'max:100',
            'service.twitter' => 'max:100',
        ]);

        $service->update([
            $service->category_id = $request->service['category_id'],
            $service->title = $request->service['title'],
            $service->description = $request->service['description'],
            $service->position = $request->service['position'],
            $service->experience_years = $request->service['experience_years'],
            $service->highlight = $request->service['highlight'],
            $service->keystrength = $request->service['keystrength'],
            $service->email = $request->service['email'],
            $service->phone = $request->service['phone'],
            $service->website = $request->service['website'],
            $service->address = $request->service['address'],
            $service->city = $request->service['city'],
            $service->facebook = $request->service['facebook'],
            $service->instagram = $request->service['instagram'],
            $service->twitter = $request->service['twitter'],
        ]);

        return response()->json($service, 201);
    }

    public function toggleServiceApprovalStatus($id)
    {
        $serv = Service::findOrFail($id);
        If($serv->is_approved){
            $serv->update([
                $serv->is_approved = false
            ]);
        }else{
            $serv->update([
                $serv->is_approved = true
            ]);
        }

        return response()->json($serv->is_approved, 201);
    }

    public function toggleServicePremiumStatus($id)
    {
        $serv = Service::findOrFail($id);
        If($serv->is_premium){
            $serv->update([
                $serv->is_premium = false
            ]);
        }else{
            $serv->update([
                $serv->is_premium = true
            ]);
        }

        return response()->json($serv->is_premium, 201);
    }

    public function toggleServiceIsFeatured($id)
    {
        $serv = Service::findOrFail($id);
        If($serv->is_featured){
            $serv->update([
                $serv->is_featured = false
            ]);
        }else{
            $serv->update([
                $serv->is_featured = true
            ]);
        }

        return response()->json($serv->is_featured, 201);
    }

    public function updateServiceImage(Request $request, $id){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        // check if picture exists for profile, then unlink
        $service = Service::findOrFail($id);
        $old_pic = $service->image;
        if($old_pic){
            $filePath = public_path('/images/services/'.$old_pic);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        // save file in folder...later in s3 when ready to deploy
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 8).".".$ext;

            //save new file in folder
            $file_loc = public_path('/images/services/'.$filename);
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $upload = Image::make($file)->resize(400, 340, function($constraint){
                    $constraint->aspectRatio();
                });
                $upload->sharpen(2)->save($file_loc);
            }
        }

        // save path in db
        $service = Service::findOrFail($id);

        $service->update([
            $service->image = $filename
        ]);

        return response()->json($service, 201);
    }

    public function deleteServiceImage($id)
    {
        $service = Service::findOrFail($id);
        $old_pic = $service->image;
        if($old_pic){
            $filePath = public_path('/images/services/'.$old_pic);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        $service->update([
            $service->image = null
        ]);

        return response()->json($service, 200);
    }

    public function adminSearchForService(Request $request)
    {
        $q = $request->q;
        $servs = Service::where('title', 'LIKE', "%".$q."%")
                        ->orWhere('description', 'LIKE', "%".$q."%")
                        ->orWhere('description', 'LIKE', "%".$q."%")
                        ->orWhere('highlight', 'LIKE', "%".$q."%")
                        ->orWhere('keystrength', 'LIKE', "%".$q."%")
                        ->orWhere('email', 'LIKE', "%".$q."%")
                        ->orWhere('phone', 'LIKE', "%".$q."%")
                        ->orWhere('website', 'LIKE', "%".$q."%")
                        ->orWhere('facebook', 'LIKE', "%".$q."%")
                        ->orWhere('instagram', 'LIKE', "%".$q."%")
                        ->orWhere('twitter', 'LIKE', "%".$q."%")
                        ->orWhereHas('user', function($x) use($q){
                            $x->where('email', 'like', "%".$q."%")
                            ->orWhere('first_name', 'like', "%".$q."%")
                            ->orWhere('last_name', 'like', "%".$q."%")
                            ->orWhere('phone', 'like', "%".$q."%");
                        })->get();

        return response()->json($servs, 200);
    }

    public function getPgntdPortfolios()
    {
        $pfs = Portfolio::latest()->paginate(5);

        return response()->json($pfs, 200);
    }

    public function adminDelPortfolio($id){
        $pf = Portfolio::findOrFail($id);
        // get files and delete, unlink
        $imgs = PortfolioFile::where('portfolio_id', $id)->get();
        foreach ($imgs as $img) {
            $file = $img->file;
            $filePath = public_path('/images/portfolios/'.$file);
            if(file_exists($filePath)){
                unlink($filePath);
            }
            $img->delete();
        }
        $pf->delete();

        return response()->json(['message' => 'Portfolio deleted!'], 200);
    }

    public function adminSearchForPortfolio(Request $request)
    {
        $q = $request->q;
        $pfs = Portfolio::where('title', 'LIKE', "%".$q."%")
                        ->orWhere('detail', 'LIKE', "%".$q."%")
                        ->orWhere('cost', 'LIKE', "%".$q."%")
                        ->orWhereHas('service', function($x) use($q){
                            $x->where('title', 'like', "%".$q."%")
                            ->orWhere('description', 'like', "%".$q."%")
                            ->orWhere('highlight', 'like', "%".$q."%")
                            ->orWhere('keystrength', 'like', "%".$q."%")
                            ->orWhere('email', 'like', "%".$q."%")
                            ->orWhere('phone', 'like', "%".$q."%")
                            ->orWhere('website', 'like', "%".$q."%")
                            ->orWhere('facebook', 'like', "%".$q."%")
                            ->orWhere('twitter', 'like', "%".$q."%")
                            ->orWhere('instagram', 'like', "%".$q."%");
                        })
                        ->get();

        return response()->json($pfs, 200);
    }

    public function getPortfolio($id)
    {
        $pf = Portfolio::with('service')->findOrFail($id);
        return response()->json($pf, 200);
    }

    public function getPortfolioFiles($id){
        $files = PortfolioFile::where('portfolio_id', $id)->get();
        return response()->json($files, 200);
    }

    public function updatePortfolio(Request $request, $id)
    {
        $pf = Portfolio::findOrFail($id);

        $this->validate($request, [
            'portfolio.title' => 'required|min:5|max:200',
            'portfolio.detail' => 'required|min:10|max:600',
            'portfolio.cost' => 'numeric|between:1,100000000000',
        ]);

        $pf->update([
            $pf->title = $request->portfolio['title'],
            $pf->cost = $request->portfolio['cost'] * 100,
            $pf->detail = $request->portfolio['detail'],
        ]);
        return response()->json($pf, 200);
    }

    public function togglePortfolioIsApproved($id)
    {
       $pf = Portfolio::findOrFail($id);

       if($pf->is_approved){
           $pf->update([
               $pf->is_approved = false
           ]);
       }else{
            $pf->update([
                $pf->is_approved = true
            ]);
       }
       return response()->json($pf, 200);
    }

    public function adminDeletePortfolio($id){
        $pf = Portfolio::findOrFail($id);

        $pf->delete();

        return response()->json(['message' => 'Portfolio deleted'], 200);
    }

    public function adminDeletePortfolioFile($id)
    {
        $file = PortfolioFile::findOrFail($id);

        // delete file in storage
        $file_name = $file->file;
        if($file_name){
            $filePath = public_path('/images/portfolios/'.$file_name);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }
        $file->delete();

        return response()->json(['message' => 'Deleted'], 201);
    }

    public function adminGetPgntdReviews()
    {
        $revs = Review::with('service')->latest()->paginate(20);

        return response()->json($revs, 200);
    }

    public function delReview($id)
    {
        $rev = Review::findOrFail($id);

        $rev->delete();
        return response()->json(['message' => 'Deleted!'], 200);
    }

    public function getReview($id)
    {
        $rev = Review::with('service')->findOrFail($id);
        return response()->json($rev, 200);
    }

    public function toggleReviewIsApproved($id)
    {
        $rev = Review::findOrFail($id);
        if($rev->is_approved){
            $rev->update([
                $rev->is_approved = false
            ]);
        }else{
            $rev->update([
                $rev->is_approved = true
            ]);
        }

        return response()->json($rev, 200);
    }

    public function deleteReview($id)
    {
        $rev = Review::findOrFail($id);

        $rev->delete();

        return response()->json(['message' => 'Deleted'], 201);
    }

    public function adminSearchForReviews(Request $request)
    {
        $q = $request->q;
        $revs = Review::with('service')->where('title', 'LIKE', "%".$q."%")
                        ->orWhere('detail', 'LIKE', "%".$q."%")
                        ->orWhereHas('service', function($x) use($q){
                            $x->where('title', 'like', "%".$q."%")
                            ->orWhere('description', 'like', "%".$q."%")
                            ->orWhere('email', 'like', "%".$q."%")
                            ->orWhere('phone', 'like', "%".$q."%")
                            ->orWhere('website', 'like', "%".$q."%")
                            ->orWhere('facebook', 'like', "%".$q."%")
                            ->orWhere('twitter', 'like', "%".$q."%")
                            ->orWhere('instagram', 'like', "%".$q."%");
                        })
                        ->get();

        return response()->json($revs, 200);
    }

    public function getPgntdCategories()
    {
        $cats = Category::latest()->paginate(20);
        return response()->json($cats, 200);
    }

    public function updateCategory(Request $request, $id)
    {
        $cat = Category::findOrFail($id);
        $cat->update([
            $cat->name = $request->cat['name']
        ]);

        return response()->json($cat, 200);
    }

    public function addNewCategory(Request $request)
    {
        $validator = $this->validate($request, [
            'cat.name' => 'required|min:3|max:100|unique:categories,name',
        ]);

        $cat = new Category;
        $cat->name = $request->cat['name'];
        $cat->save();
        return response()->json($cat, 201);
    }

    public function searchForCategories(Request $request)
    {
        $q = $request->q;
        $cats = Category::where('name', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($cats, 200);
    }

    public function delCat($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();
        return response()->json(['message' => 'Deleted!'], 200);
    }

    public function getPgntdLocations()
    {
        $locs = Location::latest()->paginate(20);

        return response()->json($locs, 200);
    }

    public function updateLocation(Request $request, $id)
    {
        $loc = Location::findOrFail($id);
        $this->validate($request, [
            'loc.state' => 'required|min:3|max:80',
        ]);

        $loc->update([
            $loc->state = $request->loc['state']
        ]);

        return response()->json($loc, 200);
    }

    public function delLocation($id)
    {
        $loc = Location::findOrFail($id);
        $loc->delete();
        return response()->json(['message' => 'Deleted!'], 200);
    }

    public function adminSearchForLocations(Request $request)
    {
        $q = $request->q;
        $locs = Location::where('state', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($locs, 200);
    }

    public function toggleLocPopularity($id)
    {
        $loc = Location::findOrFail($id);
        if($loc->is_popular){
            $loc->update([
                $loc->is_popular = false
            ]);
        }else{
            $loc->update([
                $loc->is_popular = true
            ]);
        }

        return response()->json($loc, 200);
    }

    public function getTestClients(){
        $clients = TestClient::all();

        return response()->json($clients, 200);
    }

    public function importLocations(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file');
        Excel::import(new LocationsImport, $path);
        return response()->json(['message' => 'Uploaded'], 200);
    }

    public function importCategories(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file');
        Excel::import(new CategorysImport, $path);
        return response()->json(['message' => 'Uploaded'], 200);
    }

    function updateAdminProfile(Request $request){
        $this->validate($request, [
            'admin.first_name' => 'required|min:3|max:30',
            'admin.last_name' => 'required|min:3|max:30',
            'admin.phone' => 'required|min:8|max:14',
        ]);

        $admin = auth('admin-api')->user();
        $admin->update([
            $admin->first_name = $request->admin['first_name'],
            $admin->last_name = $request->admin['last_name'],
            $admin->phone = $request->admin['phone'],
        ]);

        return response()->json($admin, 200);
    }

    public function confirmCurrentPassword(Request $request){
        $user = auth('admin-api')->user();
        $current = $user->password;
        $check = Hash::check($request->password, $current);

        return response()->json($check, 200);
    }

    public function updateAdminPswd(Request $request){
        $this->validate($request, [
            'password' => 'required|min:5|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = auth('admin-api')->user();
        $new = $request->password;

        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json(['message' => 'Password changed successfully'], 201);
    }

    public function updateAdminProfilePic(Request $request)
    {
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $user = auth('admin-api')->user();
        // check if picture exists for profile, then unlink
        $old_pic = $user->picture;
        if($old_pic){
            $filePath = public_path('/images/profiles/admins/'.$old_pic);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        // save file in folder...later in s3 when ready to deploy
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 6).".".$ext;

            //save new file in folder
            $file_loc = public_path('/images/profiles/admins/'.$filename);
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $upload = Image::make($file)->resize(420, 420, function($constraint){
                    $constraint->aspectRatio();
                });
                $upload->sharpen(2)->save($file_loc);
            }
        }

        // save path in db
        $user->update([
            $user->picture = $filename
        ]);

        return response()->json($user, 201);
    }

    public function getAllUsers()
    {
        $users = User::all();

        return response()->json($users, 200);
    }

    public function getAllServices(){
        $services = Service::all();

        return response()->json($services, 200);
    }

    public function getAllPortfolios(){
        $pfs = Portfolio::all();

        return response()->json($pfs, 200);
    }

    public function getAllReviews(){
        $rev = Review::all();

        return response()->json($rev, 200);
    }

    // public function getWeeksDataForServices(){
    //     $services = Service::selectRaw('COUNT(*) AS services_count')
    //             ->selectRaw('FROM_DAYS(TO_DAYS(created_at::date, \'YYYY-MM-DD\') -MOD(TO_DAYS(created_at::date, \'YYYY-MM-DD\') -1, 7)) AS week_starting')
    //             ->groupBy('week_starting')
    //             ->orderBy('week_starting')
    //             ->take(10)->get();
    //     $services->each->setAppends([]);

    //     return response()->json($services, 200);
    // }

    public function getWeeksDataForServices(){
        $services = Service::selectRaw('Count(*) AS services_count')
                    ->selectRaw("date_part('year', created_at::date) as year")
                    ->selectRaw("date_part('week', created_at::date) AS weekly")
                    ->groupBy('year', 'weekly')
                    ->orderByDesc('year', 'weekly')
                    ->take(10)->get();
        $services->each->setAppends([]);

        return response()->json($services, 200);
    }

    public function getWeeksDataForUsers(){
        $users = User::selectRaw('COUNT(*) AS users_count')
                ->selectRaw('FROM_DAYS(TO_DAYS(created_at::TEXT, \'YYYY-MM-DD\') -MOD(TO_DAYS(created_at::TEXT, \'YYYY-MM-DD\') -1, 7)) AS week_starting')
                ->groupBy('week_starting')
                ->orderBy('week_starting')
                ->take(10)->get();
        $users->each->setAppends([]);

        return response()->json($users, 200);
    }

    public function getPgntdPswdRequests(){
        $reqs = PasswordResetLog::latest()->paginate(10);

        return response()->json($reqs, 200);
    }

    public function delPasswordReq($id){
        $req = PasswordResetLog::findorFail($id);
        $req->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }

    public function searchForpswdResetReqs(Request $request){
        $q = $request->q;
        $reqs = PasswordResetLog::where('email', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($reqs, 200);
    }

    public function getPgntdEnquiries(){
        $enqs = Enquiry::latest()->paginate(20);

        return response()->json($enqs, 200);
    }

    public function getUnreadEnquiries(){
        $enq = Enquiry::where('is_read', false)->count();
        return response()->json($enq, 200);
    }

    public function searchForEnqs(Request $request){
        $q = $request->q;
        $enq = Enquiry::where('email', 'LIKE', "%".$q."%")
                        ->orWhere('fullname', 'LIKE', "%".$q."%")
                        ->orWhere('organization', 'LIKE', "%".$q."%")
                        ->orWhere('phone', 'LIKE', "%".$q."%")
                        ->orWhere('subject', 'LIKE', "%".$q."%")
                        ->orWhere('message', 'LIKE', "%".$q."%")
                        ->get();

        return response()->json($enq, 200);
    }

    public function getEnquiry($id){
        $enq =  Enquiry::findOrFail($id);
        return response()->json($enq, 200);
    }

    public function enquiryWasRead($id){
        $enq = Enquiry::findOrFail($id);
        $enq->update([
            $enq->is_read = true
        ]);

        return response()->json(['message' => 'Enquiry was read!'], 200);
    }

    public function delEnquiry($id){
        $enq = Enquiry::findOrFail($id);

        $enq->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    public function getPopularServices(){
        $servs = Service::all();
        $servs = $servs->sortByDesc('ratings')->values()->take(6);

        return response()->json($servs, 200);
    }

    public function getLatestServices(){
        $servs = Service::latest()->take(5)->get();

        return response()->json($servs, 200);
    }

    public function getPgntdTestimonials(){
        $tests = Testimonial::latest()->paginate(20);

        return response()->json($tests, 200);
    }

    public function delTestimonial($id){
        $test = Testimonial::findOrFail($id);

        $test->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }

    public function searchForTestimonials(Request $request){
        $q = $request->q;
        $tests = Testimonial::where('title', 'LIKE', "%".$q."%")
                        ->orWhere('detail', 'LIKE', "%".$q."%")
                        ->orWhereHas('user', function($x) use($q){
                            $x->where('email', 'like', "%".$q."%")
                            ->orWhere('first_name', 'like', "%".$q."%")
                            ->orWhere('last_name', 'like', "%".$q."%");
                        })
                        ->get();

        return response()->json($tests, 200);
    }

    public function getTestimonial($id){
        $test = Testimonial::findOrFail($id);
        return response()->json($test, 200);
    }

    public function toggleTestimonialConfirm($id, Request $req){
        $test = Testimonial::findOrFail($id);
        if($test->is_confirmed){
            $test->update([
                $test->is_confirmed = false
            ]);
        }else{
            $test->update([
                $test->is_confirmed = true
            ]);
        }
        return response()->json($test->is_confirmed, 200);
    }

    public function toggleTestimonialFeature($id, Request $req){
        $test = Testimonial::findOrFail($id);
        if($test->is_featured){
            $test->update([
                $test->is_featured = false
            ]);
        }else{
            $test->update([
                $test->is_featured = true
            ]);
        }
        return response()->json($test->is_featured, 200);
    }

    public function updateTestimonial($id, Request $req){
        $test = Testimonial::findOrFail($id);
        $this->validate($req, [
            'testimonial.title' => 'required|min:4|max:50',
            'testimonial.detail' => 'required|min:10|max:400',
        ]);
        $test->update([
            $test->title = $req->testimonial['title'],
            $test->detail = $req->testimonial['detail'],
         ]);

         return response()->json($test, 200);
    }

    public function getTestimonialCount(){
        $count = Testimonial::all()->count();
        return response()->json($count, 200);
    }

    public function createBulkLocations(Request $request){
        $locs = $request->locations;

        $data = ['data' => $locs];
        $validator = Validator::make($data, [
            'data.*.name' => 'required|min:3|max:50|unique:locations,state'
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'validation failed'], 500);
        }else{
            foreach($locs as $loc){
                $state = new Location;
                $state->state = $loc['name'];
                $state->save();
            }
            return response()->json(['message' => 'Created successfully'], 201);
        }
    }

    public function createBulkCategories(Request $request){
        $cats = $request->categories;

        $data = ['data' => $cats];
        $validator = Validator::make($data, [
            'data.*.name' => 'required|min:3|max:50|unique:categories,name'
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'validation failed'], 500);
        }else{
            foreach($cats as $cat){
                $categ = new Category;
                $categ->name = $cat['name'];
                $categ->save();
            }
            return response()->json(['message' => 'Categories created successfully'], 201);
        }
    }
}
