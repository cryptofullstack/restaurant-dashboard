<?php

namespace App\Http\Controllers\Admin;

use App\Slim;
use Validator;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $setting = Setting::first();

        return view('admin.settings', ['setting' => $setting]);
    }

    public function updateBasic(Request $request)
    {
        $setting = Setting::first();

        $setting->store_name = $request->store_name;
        $setting->business_name = $request->business_name;
        $setting->phone = $request->phone;
        $setting->deliver_cost = $request->deliver_cost;
        $setting->save();

        return response()->json(['result' => 'success']);
    }

    public function updateLocation(Request $request)
    {
        $setting = Setting::first();

        $setting->address = $request->store_address;
        $setting->latitude = $request->latitude;
        $setting->longitude = $request->longitude;
        $setting->save();

        return response()->json(['result' => 'success']);
    }

    public function updateTime(Request $request)
    {
        $setting = Setting::first();


        $setting->minimal_delivery_time = $request->minimal_delivery_time;

        // Monday
        if($request->mon_opened){
            $setting->mon_opened = 1;
            $setting->mon_open = $request->mon_open;
            $setting->mon_close = $request->mon_close;
        }else{ $setting->mon_opened = 0; }

        // Tuesday
        if($request->tue_opened){
            $setting->tue_opened = 1;
            $setting->tue_open = $request->tue_open;
            $setting->tue_close = $request->tue_close;
        }else{ $setting->tue_opened = 0; }

        // Wednesday
        if($request->wed_opened){
            $setting->wed_opened = 1;
            $setting->wed_open = $request->wed_open;
            $setting->wed_close = $request->wed_close;
        }else{ $setting->wed_opened = 0; }

        // Thursday
        if($request->thu_opened){
            $setting->thu_opened = 1;
            $setting->thu_open = $request->thu_open;
            $setting->thu_close = $request->thu_close;
        }else{ $setting->thu_opened = 0; }

        // Friday
        if($request->fri_opened){
            $setting->fri_opened = 1;
            $setting->fri_open = $request->fri_open;
            $setting->fri_close = $request->fri_close;
        }else{ $setting->fri_opened = 0; }

        // Saturday
        if($request->sat_opened){
            $setting->sat_opened = 1;
            $setting->sat_open = $request->sat_open;
            $setting->sat_close = $request->sat_close;
        }else{ $setting->sat_opened = 0; }

        // Sunday
        if($request->sun_opened){
            $setting->sun_opened = 1;
            $setting->sun_open = $request->sun_open;
            $setting->sun_close = $request->sun_close;
        }else{ $setting->sun_opened = 0; }

        // $setting->open_time = $request->store_open_time;
        // $setting->close_time = $request->store_close_time;
        // if ($request->store_open_saturday) {
        //     $setting->is_open_sat = 1;
        //     $setting->sat_open_time = $request->saturday_open_time;
        //     $setting->sat_close_time = $request->saturday_close_time;
        // } else {
        //     $setting->is_open_sat = 0;
        // }
        // if ($request->store_open_sunday) {
        //     $setting->is_open_sun = 1;
        //     $setting->sun_open_time = $request->sunday_open_time;
        //     $setting->sun_close_time = $request->sunday_close_time;
        // } else {
        //     $setting->is_open_sun = 0;
        // }
        $setting->save();

        return response()->json(['result' => 'success']);
    }

    public function updateMainImg(Request $request)
    {
        $setting = Setting::first();

        $setting->home_header = $request->store_main_image;
        $setting->save();

        return response()->json(['result' => 'success']);
    }

    public function updateDescription(Request $request)
    {
        $setting = Setting::first();

        $setting->description = $request->store_description;
        $setting->save();

        return response()->json(['result' => 'success']);
    }

    public function uploadMainPhoto(Request $request)
    {
        if ($request->slim != null) {
            $imageRand = rand(1000, 9999);
            $random_name = $imageRand."_".time();
            $dst = public_path('uploads/store');

            $finish_image = $this->uploadImagetoServer($request, $random_name, $dst);

            if ($finish_image['result'] == "success") {
                $final_name = $finish_image['image'][0]['name'];
                $image_url = asset('uploads/store/'.$final_name);

                return response()->json(['result' => 'success', 'image_url' => $image_url, 'image_name' => $final_name]);
            }
        }

        return response()->json(['result' => 'error']);
    }

    protected function uploadImagetoServer($imgdata, $name, $path)
    {
        $files = array();
        $result = array();
        $rules = [
            'file' => 'image',
            'slim[]' => 'image'
        ];

        $validator = Validator::make($imgdata->all(), $rules);
        $errors = $validator->errors();

        if($validator->fails()){
            $result = array('result' => 'fail', 'reson' => 'validator');
            return $result;
        }

        // Get posted data
        $images = Slim::getImages();

        // No image found under the supplied input name
        if ($images == false) {
            $result = array('result' => 'fail', 'reson' => 'image');
            return $result;
        } else {
            foreach ($images as $image) {
                // save output data if set
                if (isset($image['output']['data'])) {
                    // Save the file
                    $origine_name = $image['input']['name'];
                    $file_type = pathinfo($origine_name, PATHINFO_EXTENSION);
                    $finalName = $name.".".$file_type;

                    // We'll use the output crop data
                    $data = $image['output']['data'];
                    $output = Slim::saveFile($data, $finalName, $path, false);
                    array_push($files, $output);
                    $result = array('result' => 'success', 'image' => $files);
                    return $result;
                }
                // save input data if set
                if (isset ($image['input']['data'])) {
                    // Save the file
                    $origine_name = $image['input']['name'];
                    $file_type = pathinfo($origine_name, PATHINFO_EXTENSION);
                    $finalName = $name.".".$file_type;

                    $data = $image['input']['data'];
                    $input = Slim::saveFile($data, $finalName, $path, false);
                    array_push($files, $output);

                    $result = array('result' => 'success', 'image' => $files);
                    return $result;
                }
            }
        }
    }
}
