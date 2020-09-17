<?php

namespace App\Http\Controllers\ResetPasswordApi;

use App\Http\Controllers\Controller;
use App\Reset_Passwrod;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Mail;
use Response;

class ResetPasswordApiController extends Controller {

    public function resetPasswordApi(Request $request) {
        $lang = app('request')->header('lang');

        if ($request->input('email') != null) {
            $email = $request->input('email');

            $userExists = DB::table('users')->where('email', $email)->first();
            if (!$userExists) {
                if ($lang == 'en') {
                    return response()->json(['errors' => [ 'messages' => 'Invalid email' ]], 400);
                } else {
                    return response()->json(['errors' => [ 'messages' => 'خطأ في البريد' ]], 400);
                }
            }


            try {
                $current = Carbon::now();
                $reset_password = new Reset_Passwrod;
                $reset_password->email = $email;
                $reset_password->token = md5(rand(000, 999));
                $reset_password->created_at = $current;
                $reset_password->token_expired = $current->addMinutes(30);
                $reset_password->save();
                $email = $reset_password->email;
                $token = $reset_password->token;
                $url = url('/reset/password/' . $token);

                $data = array('email' => $email, 'url' => $url,
                    'from' => env('MAIL_USERNAME'),
                    'from_name' => $userExists->name);

                $email = $data['email'];
                Mail::send('admin.reset-password.email', $data, function ($message) use ($data) {
                    $message->to($data['email'])->from($data['from'], $data['from_name'])->subject('khotwh Password Reset');
                });
                if ($lang == 'en') {
                    return Response::json(['Status' => 'Success', 'message' => 'Success.'], 200);
                } else {
                    return Response::json(['Status' => 'Success', 'message' => 'تمت العمليه بنجاح.'], 200);
                }
            } catch (\Exception $exception) {
                return response()->json(['errors' => $exception->getMessage()],500);
            }
        } else {
            return Response::json(['Status' => 'Erorr', 'message' => 'Bad Request'], 400);

        }

    }

}
