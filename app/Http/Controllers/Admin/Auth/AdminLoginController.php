<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth;
use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function showAdminLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $getAdmininfo = $this->credentials($request);

        $availablecheck = Auth::guard('admin')->attempt($getAdmininfo);

        if ($availablecheck) {
            $result_array = array('result' => 'success', 'url' => route('admin.dashboard'));
            return response()->json($result_array);
        }

        $result_array = array('result' => 'fail');
        return response()->json($result_array);
    }

    public function logout(Request $request)
    {
        $this->guard('admin')->logout();

        $request->session()->invalidate();

        return redirect('/admin');
    }
}
