<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\AnAdminHasLoggedIn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin-api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('admin-api')->attempt($credentials)) {
            //create a table and log in failed attempts, when count is >2, lock user
            return response()->json(['error' => 'Unauthorized'], 441);
        }

        if(auth('admin-api')->user()->status == 0){
            return response()->json(['error' => 'Unauthorized'], 501);
        }

        $admin = auth('admin-api')->user();
        if($admin->status == 1){
            $admin->update([
                $admin->is_loggedin = true
            ]);

            $super_user = 'hozulinks@gmail.com';
            $now = date('d/m/Y, h:ma');
            // send email to superuser
            Mail::to($super_user)->send(new AnAdminHasLoggedIn($admin, $now));

            return $this->respondWithToken($token);
        }
    }

    protected function respondWithToken($token)
    {
        $user = auth('admin-api')->user()->id;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $this->guard()->user(),
            'expires_in' => auth('admin-api')->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        $admin = auth('admin-api')->user();
        $admin->update([
            $admin->is_loggedin = false
        ]);

        auth('admin-api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function guard()
    {
        return Auth::guard('admin-api');
    }
}
