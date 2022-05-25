<?php

namespace App\Http\Controllers\Auth;

use File;
use App\User;
use DateTime;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    public function signup(Request $request)
    {
        if ($this->checkEmail($request->email) == false) {
            return response()->json(['result' => 'already', 'data' => 'The email has already been taken.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'gender' => 'required',
            'birth' => 'required',
            'password' => 'required',
            'device_fcm' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 'error', 'message'=>$validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['birth'] = $this->encode_date_format($input['birth']);
        $user = User::create($input);

        if($request->image) {
            $data = $request->image;

            $imageRand = time();
            $random_name = $user->id."_".$imageRand.".png";

            if(!is_dir(public_path('uploads/avatars/'.$user->id))){
                mkdir(public_path('uploads/avatars/'.$user->id));
            }

            $dst = public_path('uploads/avatars/'.$user->id.'/');

            if($user->avatar != 'default.png') {
                if (File::exists($dst . $user->avatar)) {
                    File::delete($dst . $user->avatar);
                }
            }

            $path = $dst.'/'.$random_name;

            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

            file_put_contents($path, $data);

            $user->avatar = $random_name;
            $user->save();

            $user['avatar_url'] = asset('uploads/avatars/'.$user->id.'/'.$user->avatar);
        } else {
            $user['avatar_url'] = asset('uploads/avatars/default.png');
        }
        // return $user;
        $success =  $user->createToken('MyApp')-> accessToken;

        return response()->json(['result' => 'success','token' => $success, 'user' => $user], 200);
    }

    public function checkEmail($email)
    {
        $emailCheckCount = User::where('email', $email)->count();
        if ($emailCheckCount > 0) {
            return false;
        }
        return true;
    }

    public function encode_date_format($date)
    {
        $selectedDate = DateTime::createFromFormat('d/m/Y', $date);
        $finalDate = $selectedDate->format('Y-m-d');
        return $finalDate;
    }
}
