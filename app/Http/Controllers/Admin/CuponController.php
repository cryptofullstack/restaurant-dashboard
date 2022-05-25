<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Cupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CuponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.cupon');
    }

    public function getAllCupons()
    {
        $cupons = Cupon::join('users', 'cupons.user_id', '=', 'users.id')->select('users.name as user_name', 'cupons.*')->get();

        return response()->json($cupons, 200);
    }

    public function getAllCuponUsers()
    {
        $users = User::all();

        return response()->json($users, 200);
    }

    public function storeCupon(Request $request)
    {
        $cupon_user_ids = $request->cupon_users;

        foreach ($cupon_user_ids as $cupon_user_id) {
            $cupon_user = User::find($cupon_user_id);

            if ($cupon_user) {
                $cupon = new Cupon;
                $cupon->user_id = $cupon_user->id;
                $cupon->cupon_name = $request->cupon_name;
                $cupon->percent = $request->cupon_percent;
                $cupon->save();
            }
        }

        return response()->json(['result' => 'success']);
    }

    public function deleteCupon($id)
    {
        $cupon = Cupon::find($id);

        if ($cupon) {
            $cupon->delete();

            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }
}
