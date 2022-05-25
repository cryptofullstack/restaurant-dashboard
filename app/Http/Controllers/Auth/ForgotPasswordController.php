<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserResetPassword;
use App\Mail\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function SendResetCode(Request $request)
    {
        $input = $request->all();
        $user_email = $input['resetEmail'];

        $user = User::where('email', $user_email)->first();
        if ($user) {
            $code = rand(100000, 999999);
            Mail::to($user_email)->send(new PasswordReset($code));

            $delete_my_reset = UserResetPassword::where('user_id', $user->id)->delete();

            $new_reset = new UserResetPassword;
            $new_reset->user_id = $user->id;
            $new_reset->confirmCode = $code;
            $new_reset->save();

            return response()->json(['result' => 'success','user_id' => $user->id, 'sent_email' => $user_email, 'confirmCode' => $code], 200);
        }
        return response()->json(['result' => 'error','msg' => "We can't find a user with that e-mail address."], 401);
    }
}
