<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\OrderFood;
use App\OrderExtra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function viewOrders()
    {
        return view('admin.order');
    }

    public function getAllData()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get();

        return response()->json($orders, 200);
    }

    public function viewSingleOrder($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order_foods = OrderFood::where('order_foods.order_id', $order->id)
            ->join('products', 'products.id', '=', 'order_foods.food_id')
            ->select('products.pro_name', 'products.pro_price', 'order_foods.*')->get();

            foreach ($order_foods as $single_food) {
                $food_extras = OrderExtra::where('order_food_id', $single_food->id)
                ->join('groups', 'groups.id', '=', 'order_extras.group_id')
                ->join('extras', 'extras.id', '=', 'order_extras.extra_id')
                ->select('groups.group_name', 'extras.extra_name', 'extras.extra_price', 'order_extras.*')->get();

                $single_food['extras'] = $food_extras;
            }

            $order['foods'] = $order_foods;

            // dd($order);

            return view('admin.orderDetail', ['order' => $order]);
        }
    }
}
