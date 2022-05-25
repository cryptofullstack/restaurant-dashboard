<?php

namespace App\Http\Controllers\Admin;
use App\Slim;
use Validator;
use App\Extra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExtraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.extra');
    }

    public function getAllExtras()
    {
        $extras = Extra::all();

        return response()->json($extras, 200);
    }

    public function insertExtra(Request $request)
    {
        $extra = new Extra;
        $extra->extra_name = $request->extra_name;
        $extra->extra_price = $request->extra_price;
        $extra->save();

        return response()->json(['result' => 'success']);
    }

    public function getSingleExtra($id)
    {
        $extra = Extra::find($id);
        if ($extra) {
            return response()->json(['result' => 'success', 'extra' => $extra]);
        }

        return response()->json(['result' => 'error']);
    }

    public function updateExtra(Request $request)
    {
        $extra = Extra::find($request->extra_id);
        if ($extra) {
            $extra->extra_name = $request->_extra_name;
            $extra->extra_price = $request->_extra_price;
            $extra->save();

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error', 'msg' => 'Extra not found']);
    }

    public function deleteExtra($id)
    {
        $extra = Extra::find($id);
        if ($extra) {
            $extra->delete();

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error', 'msg' => 'Extra can not find']);
    }
}
