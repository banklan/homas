<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\PasswordRequestReset;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetRequestEmail;
use App\Mail\PasswordHasBeenReset;
use App\PasswordResetLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PasswordRecoveryController extends Controller
{
    public function passwordRecoveryRequest(Request $request){
        $this->validate($request, [
            'email' => 'required|email'
        ]);

        // fetch account and generate link
        $email = $request->email;
        $user = User::where('email', $email)->first();

        // if user found, send mail with link and save in db
        if($user){
            $token = bin2hex(random_bytes(80));

            //check to see if user already have a request, delete if yes
            $req = PasswordRequestReset::where('email', $email)->first();
            if($req){
                $req->delete();
            }

            $recovery = new PasswordRequestReset;
            $recovery->email = $email;
            $recovery->token = $token;
            $recovery->save();

            Mail::to($email)->send(new PasswordResetRequestEmail($user, $recovery));

            return response()->json($recovery, 200);
        }else{
            return response()->json(['error' => 'User not found'], 501);
        }
    }

    public function ConfirmPasswordRequestToken(Request $request){
        $token = $request->token;
        $confirm = PasswordRequestReset::where('token', $token)->first();
        if($confirm){
            //check if token has expired
            if($confirm->expiry > Carbon::now()){
                return response()->json(['message' => 'confirmed'], 200);
            }else{
                return response()->json(['message' => 'token expired'], 423);
            }
        }else{
            //token not valid
            return response()->json(['message' => 'invalid token'], 422);
        }
    }

    public function ResetPassword(Request $request){
        $this->validate($request, [
            'pswd.password' => 'required|min:5|max:30|confirmed',
            'pswd.password_confirmation' => 'required'
        ]);

        $resetRequest = PasswordRequestReset::where('token', $request->token)->first();
        $new_pswd = $request->pswd['password'];
        if($resetRequest){
            $user = User::where('email', $resetRequest->email)->first();
            $user->update([
                $user->password = Hash::make($new_pswd)
            ]);
        }

        Mail::to($user->email)->send(new PasswordHasBeenReset($user));

        // log in reset log table
        $log = new PasswordResetLog;
        $log->user_id = $user->id;
        $log->email = $user->email;
        $log->request_date = $resetRequest->created_at;
        $log->reset_date = Carbon::now();
        $log->request_ip = $request->ip();
        $log->save();

        // delete reset request
        $resetRequest->delete();

        return response()->json(['message' => 'Updated'], 200);
    }
}
