<?php

namespace App\Http\Controllers;

use File;
use Auth;
use DateTime;
use App\Order;
use App\Cupon;
use App\Badge;
use App\Setting;
use App\Product;
use App\Category;
use App\OrderFood;
use App\OrderExtra;
use App\Notification;
use App\CategoryProduct;
use App\Mail\OrderConfirmEmail;
use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\CategoryResource;

class ApiController extends Controller
{
    public function getCatalogs()
    {
        $categories = Category::orderBy('position', 'ASC')->get();

        $final_catalogs = array();

        foreach ($categories as $category) {
            $category_products = $category->products();
            $catalog = [
                "id" => $category->id,
                "title" => $category->title,
                "body" => $category->description,
                "image" => asset('uploads/category/'.$category->image),
                "position" => $category->position,
            ];
            $data = $category_products;
            $single_catalog = [
                'catalog' => $catalog,
                'data' => $data,
            ];

            array_push($final_catalogs, $single_catalog);
        }

        return response()->json($final_catalogs);
    }

    public function storeInfo()
    {
        $setting = Setting::first();

        return response()->json(['data' => $setting]);
    }

    public function cuponCheck($text)
    {
        $user = Auth::user();
        $couponCount = Cupon::where('cupon_name', $text)->where('user_id', $user->id)->count();
        if($couponCount > 0){
            $coupon = Cupon::where('cupon_name', $text)->where('user_id', $user->id)->first();
            return response()->json(['result' => 'success', 'cupon' => $coupon], 200);
        }else{
            return response()->json(['result' => 'null'], 200);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

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

            return response()->json(['result' => 'success' ,'user' => $user], 200);
        }

        return response()->json(['result' => 'error']);
    }

    public function updateBio(Request $request)
    {
        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->birth = $request->birth;
        $user->save();

        if($user->avatar == "default.png") {
            $user['avatar_url'] = asset('uploads/avatars/default.png');
        } else {
            $user['avatar_url'] = asset('uploads/avatars/'.$user->id.'/'.$user->avatar);
        }

        return response()->json(['result' => 'success' ,'user' => $user], 200);
    }

    public function storeOrder(Request $request)
    {
        $user = Auth::user();

        $order = new Order;
        $order->user_id = $user->id;
        $order->order_city = $request->city;
        $order->order_street = $request->street;
        $order->order_zip = $request->zip;
        $order->order_username = $request->name;
        $order->order_email = $request->email;
        $order->order_phone = $request->phone;
        $order->order_bname = $request->bname;
        $order->is_infom = $request->is_infom;
        $order->order_time = $request->time;
        $order->total_price = $request->total_price;
        $order->payment_method = $request->payment;
        $order->save();

        $order_foods = $request->food;
        foreach ($order_foods as $single_food) {
            $order_food = new OrderFood;
            $order_food->order_id = $order->id;
            $order_food->food_id = $single_food['foodId'];
            $order_food->save();

            foreach ($single_food['foodExtraGroups'] as $single_group) {
                foreach ($single_group['extras'] as $single_extra) {
                    $order_extra = new OrderExtra;
                    $order_extra->order_food_id = $order_food->id;
                    $order_extra->group_id = $single_group['groupId'];
                    $order_extra->extra_id = $single_extra['id'];
                    $order_extra->save();
                }
            }
        }
        
        try {
            Mail::to($order->order_email)->send(new OrderConfirmEmail($order));
        } catch (\Exception $e) {
            $response = null;
        }

        $payment_amount = number_format((float)$order->total_price, 2, '.', '');

        if ($order->payment_method == 0) {
            $payment = Mollie::api()->payments()->create([
                'amount' => [
                    'currency' => 'EUR',
                    'value' => $payment_amount, // You must send the correct number of decimals, thus we enforce the use of strings
                ],
                'description' => 'Payment for my order',
                'redirectUrl' => route('mollietest'),
                'webhookUrl' => route('payment.webhook'),
            ]);

            $order->payment_id = $payment->id;
            $order->save();

            $payment_url = $payment->getCheckoutUrl();

            return response()->json(['result' => 'success', 'order_id' => $order->id, 'checkout_url' => $payment_url], 200);
        }

        return response()->json(['result' => 'success'], 200);
    }

    public function checkPaymentStatus($id)
    {
        $order = Order::find($id);

        if ($order) {
            $payment_id = $order->payment_id;

            $payment = Mollie::api()->payments()->get($payment_id);

            if ($payment->isPaid()) {
                $order->is_payed = 1;
                $order->save();
                return response()->json(['result' => 'success', 'msg' => 'Uw betaling is verwerkt!']);
            } elseif ($payment->isFailed()) {
                return response()->json(['result' => 'error', 'msg' => 'Uw betaling is mislukt']);
            } elseif ($payment->isExpired()) {
                return response()->json(['result' => 'error', 'msg' => 'Expired']);
            } elseif ($payment->isCanceled()) {
                return response()->json(['result' => 'error', 'msg' => 'Canceled']);
            }

            return response()->json(['result' => 'pending']);
        }

        return response()->json(['result' => 'error', 'msg' => 'Can not find Order']);
    }

    public function getPushHistory()
    {
        $user = Auth::user();
        $histories = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $total_date_array = array();
        $final_array = array();
        $notification_array = array();
        $date = "";
        foreach ($histories as $history) {
            $current_date = $this->decode_date_format($history->created_at);;
            if($date != $current_date) {
                array_push($total_date_array, $current_date);
                $date = $current_date;
            }
        }

        foreach ($total_date_array as $singledate) {
            $final_sub_array = array();
            foreach ($histories as $history) {
                $current_date = $this->decode_date_format($history->created_at);
                $current_time = $this->decode_time_format($history->created_at);
                if($singledate == $current_date) {
                    $final_sub_array[] = array(
                        'msg_title' => $history->push_title,
                        'msg_text' => $history->push_body,
                        'msg_time' => $current_time,
                    );
                }
            }

            $final_array[] = array(
                'date' => $singledate,
                'data' => $final_sub_array
            );
        }

        $budge_number = 0;
        $current_badge_count = Badge::where('user_id', $user->id)->count();
        if ($current_badge_count > 0) {
            $current_badge = Badge::where('user_id', $user->id)->first();

            $budge_number = $current_badge->badge_num;
        }

        $notification_array['badge_num'] = $budge_number;
        $notification_array['notification'] = $final_array;

        return response()->json(['data' => $notification_array], 200);
    }

    public function setPushHistoryBadge()
    {
        $user = Auth::user();
        $histories = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $total_date_array = array();
        $final_array = array();
        $notification_array = array();
        $date = "";
        foreach ($histories as $history) {
            $current_date = $this->decode_date_format($history->created_at);;
            if($date != $current_date) {
                array_push($total_date_array, $current_date);
                $date = $current_date;
            }
        }

        foreach ($total_date_array as $singledate) {
            $final_sub_array = array();
            foreach ($histories as $history) {
                $current_date = $this->decode_date_format($history->created_at);
                $current_time = $this->decode_time_format($history->created_at);
                if($singledate == $current_date) {
                    $final_sub_array[] = array(
                        'msg_title' => $history->push_title,
                        'msg_text' => $history->push_body,
                        'msg_time' => $current_time,
                    );
                }
            }

            $final_array[] = array(
                'date' => $singledate,
                'data' => $final_sub_array
            );
        }

        $budge_number = 0;
        $current_badge = Badge::where('user_id', $user->id)->first();
        if ($current_badge) {
            $current_badge->badge_num = 0;
            $current_badge->save();
        }

        $notification_array['badge_num'] = $budge_number;
        $notification_array['notification'] = $final_array;

        return response()->json(['data' => $notification_array], 200);
    }

    public function decode_date_format($date)
    {
        $selectedDate = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        $finalDate = $selectedDate->format('d/m/Y');
        return $finalDate;
    }

    public function encode_date_format($date)
    {
        $selectedDate = DateTime::createFromFormat('d/m/Y', $date);
        $finalDate = $selectedDate->format('Y-m-d');
        return $finalDate;
    }

    public function decode_time_format($time)
    {
        $selectedDate = DateTime::createFromFormat('Y-m-d H:i:s', $time);
        $finalTime = $selectedDate->format('H:i');
        return $finalTime;
    }
}
