<?php

namespace App\Http\Controllers\Auth;

use Auth;
use File;
use App\User;
use DateTime;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only('email', 'password'));
    }

    public function login(Request $request)
    {
        $getUserinfo = $this->credentials($request);
        $availablecheck = Auth::attempt($getUserinfo);
        if($availablecheck){
            $user = Auth::user();
            $user->device_fcm = $request->device_fcm;
            $user->save();

            if($user->avatar == "default.png") {
                $user['avatar_url'] = asset('uploads/avatars/default.png');
            } else {
                $user['avatar_url'] = asset('uploads/avatars/'.$user->id.'/'.$user->avatar);
            }

            $success =  $user->createToken('MyApp')-> accessToken;
            return response()->json(['result' => 'success','token' => $success, 'user' => $user], 200);
        }
        else{
            return response()->json(['result' => 'error', 'message'=>'Credential not match'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['result' => 'success','message' => 'Successfully logged out']);
    }
}
