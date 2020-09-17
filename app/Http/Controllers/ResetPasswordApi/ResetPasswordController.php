<?php

namespace App\Http\Controllers\ResetPasswordApi;

use App\Http\Controllers\utilitiesController;
use App\PAM;
use App\Reset_Passwrod;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Mail;

class ResetPasswordController extends utilitiesController {
    public function __construct() {

        if (!defined("COOKIE_FILE")) {
            $path = storage_path('app/cookie.txt');
            define("COOKIE_FILE", $path);
        }

    }

    public function ShowResetPasswordForm($token) {
        $user = Reset_Passwrod::where('token', $token)->first();
        if ($user) {
            return view('admin.reset-password.reset_password', compact('user'));
        } else {
            return view('error');
        }

    }


    public function ResetPassword(Request $request) {
        $currentx = new Carbon();

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
        ]);

        $password = bcrypt($request->input('password'));

        $token = $request->input('token');

        $user = Reset_Passwrod::where('token', $token)->first();


        if($user && isset($user->email)) {
            $email = $user->email;
            $expired_token = $user->token_expired;
        }
        $new_password = $password;

        $data = array('email' => $email, 'new_password' => $new_password,
            'from' => env('MAIL_USERNAME'),
            'from_name' => 'Khotwh');

        $userExists = User::where('email', $email)->first();

        if ($userExists) {
            $userExists->password = $new_password;
            Mail::send('admin.reset-password.send-pass-email', $data, function ($message) use ($data) {
                $message->to($data['email'])->from($data['from'], $data['from_name'])->subject('Khotwh Account Password Reset');
            });
        }

        Reset_Passwrod::where('email', $email)->update(['token' => md5(rand(111, 999))]);
        $userExists->save();

        return view('admin.reset-password.after-reset_password');
    }

}
