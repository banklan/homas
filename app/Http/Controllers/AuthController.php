<?php

namespace App\Http\Controllers;

use App\User;
use App\Service;
use App\AdminNotification;
use App\Mail\WelcomeToHomas;
use Illuminate\Http\Request;
use App\UserEmailConfirmation;
use App\Events\NewUserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 441);
        }

        if(auth('api')->user()->status == 0){
            return response()->json(['error' => 'Unauthorized'], 551);
        }

        $user = auth('api')->user();
        if($user->status == 1){
            $user->update([
                $user->is_loggedin = true
            ]);
            return $this->respondWithToken($token);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = auth('api')->user();
        $user->update([
            $user->is_loggedin = false
        ]);

        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $user = auth('api')->user()->id;
        $service = Service::where('user_id', $user)->first();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $this->guard()->user(),
            'service' => $service,
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function guard(){
        return Auth::Guard('api');
    }

    public function AuthUser()
    {
        return response()->json(auth('api')->user());
    }

    public function register(Request $request){
        $this->validate($request, [
            'user.first_name' => 'required|min:2|max:30',
            'user.last_name' => 'required|min:2|max:30',
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
        $user->status = 0;
        $user->password = Hash::make($request->user['password']);
        $user->save();

        $user->fresh();

        // create admin notif
        // $notif = new AdminNotification;
        // $notif->model = 'User';
        // $notif->is_read = false;
        // $notif->save();

        event(new NewUserRegistered($user));

        if($user){
            $conf = new UserEmailConfirmation;
            $conf->user_id = $user->id;
            $conf->token = bin2hex(random_bytes(80));
            $conf->save();

            $conf->fresh();
            // send welcome email
            Mail::to($user->email)->send(new WelcomeToHomas($user, $conf));
        }

        return response()->json($user, 200);
    }
}
