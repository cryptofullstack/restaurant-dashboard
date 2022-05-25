<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Order;
use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        return view('admin.user');
    }

    public function getAllUsers()
    {
        $users = User::all();

        return response()->json($users, 200);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }

    public function paymentStatus(Request $request)
    {
        if (! $request->has('id')) {
            return;
        }

        $payment = Mollie::api()->payments()->get($request->id);

        if($payment->isPaid()) {
            $order = Order::where('payment_id', $request->id)->first();
            if ($order) {
                $order->is_payed = 1;
                $order->save();
            }
        }
    }
}
