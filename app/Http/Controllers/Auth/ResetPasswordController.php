<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserResetPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function passwordreset(Request $request)
    {
        $input = $request->all();
        $user_id = $input['userId'];
        $confirm_code = $input['confirmCode'];

        $user = User::find($user_id);
        if ($user) {
            $check_reset_count = UserResetPassword::where('user_id', $user->id)->where('confirmCode', $confirm_code)->count();
            if ($check_reset_count == 0) {
                return response()->json(['result' => 'error','msg' => "this code Expired"], 401);
            }

            $user->password = bcrypt($input['newPassword']);
            $user->save();

            UserResetPassword::where('user_id', $user->id)->delete();

            if($user->avatar == "default.png") {
                $user['avatar_url'] = asset('uploads/avatars/default.png');
            } else {
                $user['avatar_url'] = asset('uploads/avatars/'.$user->id.'/'.$user->avatar);
            }

            $success =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['result' => 'success','token' => $success, 'user' => $user], 200);
        }

        return response()->json(['result' => 'error','msg' => "Invalide Email"], 401);
    }
}
