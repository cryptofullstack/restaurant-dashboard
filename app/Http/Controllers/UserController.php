<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\CategoryProduct;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function details(Request $request)
    {
        $user = $request->user();
        if($user->avatar == "default.png") {
            $user['avatar_url'] = asset('uploads/avatars/default.png');
        } else {
            $user['avatar_url'] = asset('uploads/avatars/'.$user->id.'/'.$user->avatar);
        }

        return response()->json(['result' => 'success' ,'user' => $user], 200);
    }
}
