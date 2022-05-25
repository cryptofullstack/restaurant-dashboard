<?php

namespace App\Http\Controllers\Admin;

use App\Slim;
use Validator;
use App\Extra;
use App\Group;
use App\Product;
use App\GroupExtra;
use App\ProductGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.product');
    }

    public function getAllProducts()
    {
        $products = Product::all();

        return response()->json($products, 200);
    }

    public function insertProduct(Request $request)
    {
        if ($request->slim != null) {
            $imageRand = rand(1000, 9999);
            $random_name = $imageRand."_".time();
            $dst = public_path('uploads/products');

            $finish_image = $this->uploadImagetoServer($request, $random_name, $dst);

            if ($finish_image['result'] == "success") {
                $final_name = $finish_image['image'][0]['name'];

                $product = new Product;
                $product->pro_name = $request->pro_name;
                $product->pro_image = $final_name;
                $product->pro_description = $request->pro_description;
                $product->pro_price = $request->pro_price;
                $product->save();

                return response()->json(['result' => 'success']);
            }

            return response()->json(['result' => 'error', 'msg' => 'image uploading error']);
        }

        return response()->json(['result' => 'error', 'msg' => 'image required']);
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

    public function getSingleProduct($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product['image_url'] = asset('uploads/products/'.$product->pro_image);
            return response()->json(['result' => 'success', 'product' => $product]);
        }

        return response()->json(['result' => 'error']);
    }

    public function updateProduct(Request $request)
    {
        $product = Product::find($request->product_id);

        if ($product) {
            $product->pro_name = $request->_pro_name;
            $product->pro_price = $request->_pro_price;
            $product->pro_description = $request->_pro_description;
            $product->save();

            if ($request->slim != null) {
                $imageRand = rand(1000, 9999);
                $random_name = $imageRand."_".time();
                $dst = public_path('uploads/products');

                $finish_image = $this->uploadImagetoServer($request, $random_name, $dst);

                if ($finish_image['result'] == "success") {
                    $final_name = $finish_image['image'][0]['name'];

                    $product->pro_image = $final_name;
                    $product->save();
                }
            }

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error', 'msg' => 'Product can not find']);
    }

    public function viewSingleProductDetail($id)
    {
        $product = Product::find($id);
        if ($product) {
            $extras = Extra::all();
            return view('admin.productDetail', ['product' => $product, 'extras' => $extras]);
        }

        return back();
    }

    public function insertProductGroupExtra(Request $request)
    {
        $product = Product::find($request->product_id);
        if ($product) {
            $group = new Group;
            $group->group_name = $request->group_name;
            $group->type = $request->group_type;
            $group->save();

            $groupPosition = $this->getGroupPosition($product->id);

            $productGroup = new ProductGroup;
            $productGroup->group_id = $group->id;
            $productGroup->product_id = $product->id;
            $productGroup->position = $groupPosition;
            $productGroup->save();

            $selectedExtras = $request->extraIds;

            foreach ($selectedExtras as $key => $singleExtra) {
                $extraPosition = $this->getGroupExtraPosition($productGroup->id);

                $groupExtra = new GroupExtra;
                $groupExtra->product_id = $product->id;
                $groupExtra->group_id = $group->id;
                $groupExtra->extra_id = $singleExtra;
                $groupExtra->position = $extraPosition;
                $groupExtra->save();
            }

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error', 'msg' => 'Product not found']);
    }

    public function getGroupPosition($productId)
    {
        $currentNum = ProductGroup::where('product_id', $productId)->orderBy('position', 'ASC')->count();
        $returnedNum = $currentNum + 1;

        $productGroups = ProductGroup::where('product_id', $productId)->orderBy('position', 'ASC')->get();

        foreach ($productGroups as $key => $singleGroup) {
            $singleGroup->position = $key+1;
            $singleGroup->save();
        }

        return $returnedNum;
    }

    public function getGroupExtraPosition($groupId)
    {
        $currentNum = GroupExtra::where('group_id', $groupId)->orderBy('position', 'ASC')->count();
        $returnedNum = $currentNum + 1;

        $groupExtras = GroupExtra::where('group_id', $groupId)->orderBy('position', 'ASC')->get();

        foreach ($groupExtras as $key => $singleGroupExtra) {
            $singleGroupExtra->position = $key+1;
            $singleGroupExtra->save();
        }

        return $returnedNum;
    }

    public function getProductGroups($id)
    {
        $product = Product::find($id);
        if ($product) {
            $productGroups = ProductGroup::where('product_id', $product->id)
            ->join('groups', 'groups.id', '=', 'product_groups.group_id')
            ->select('groups.group_name', 'groups.type', 'product_groups.*')->orderBy('product_groups.position', 'ASC')->get();

            return response()->json($productGroups, 200);
        }

        return response()->json([], 200);
    }

    public function getSingleGroup($id)
    {
        $productGroup = ProductGroup::find($id);

        if ($productGroup) {
            $group = Group::find($productGroup->group_id);
            if ($group) {
                $groupExtras = GroupExtra::where('group_id', $group->id)->join('extras', 'extras.id', '=', 'group_extras.extra_id')->select('group_extras.*', 'extras.extra_name', 'extras.extra_price')->orderBy('position', 'ASC')->get();

                return response()->json(['result' => 'success', 'extras' => $groupExtras, 'groupname' => $group->group_name]);
            }
        }

        return response()->json(['result' => 'error', 'msg' => 'Group not found']);
    }

    public function deleteProductGroup($id)
    {
        $productGroup = ProductGroup::find($id);

        if ($productGroup) {
            $group = Group::find($productGroup->group_id);
            if ($group) {
                GroupExtra::where('group_id', $group->id)->delete();

                $productId = $productGroup->product_id;

                $productGroup->delete();

                $group->delete();

                $this->getGroupPosition($productId);

                return response()->json(['result' => 'success']);
            }
        }

        return response()->json(['result' => 'error']);
    }

    public function getSingleProductGroup($id)
    {
        $productGroup = ProductGroup::where('product_groups.id', $id)->join('groups', 'groups.id', '=', 'product_groups.group_id')
        ->select('groups.group_name', 'groups.type', 'product_groups.*')->first();

        if ($productGroup) {
            $group = Group::find($productGroup->group_id);
            $product = Product::find($productGroup->product_id);
            $extras = Extra::all();
            $groupExtras = GroupExtra::where('group_id', $group->id)->orderBy('position', 'ASC')->get();

            $groupExtrasIds = array();

            foreach ($groupExtras as $key => $groupExtra) {
                array_push($groupExtrasIds, $groupExtra->extra_id);
            }

            $editFormView = view('admin.components.productGroupEdit', ['group' => $productGroup, 'extras' => $extras, 'groupExtras' => $groupExtrasIds, 'productName' => $product->pro_name])->render();

            return response()->json(['result' => 'success', 'html' => $editFormView]);
        }

        return response()->json(['result' => 'error']);
    }

    public function updateProductGroupExtra(Request $request)
    {
        $productGroup = ProductGroup::find($request->product_group_id);
        if ($productGroup) {
            $group = Group::find($productGroup->group_id);
            if ($group) {
                $group->group_name = $request->_group_name;
                $group->type = $request->_group_type;
                $group->save();

                GroupExtra::where('group_id', $group->id)->delete();

                $selectedExtras = $request->extraIds;

                foreach ($selectedExtras as $key => $singleExtra) {
                    $extraPosition = $this->getGroupExtraPosition($productGroup->id);

                    $groupExtra = new GroupExtra;
                    $groupExtra->product_id = $productGroup->product_id;
                    $groupExtra->group_id = $group->id;
                    $groupExtra->extra_id = $singleExtra;
                    $groupExtra->position = $extraPosition;
                    $groupExtra->save();
                }

                return response()->json(['result' => 'success']);
            }
        }

        return response()->json(['result' => 'error', 'msg' => 'ProductGroup not found']);
    }

    public function setProductGroupOrder(Request $request)
    {
        $new_sort = $request->sort_list;
        $position = 0;
        for ($i=0; $i < count($new_sort); $i++) {
            $productGroup = ProductGroup::find($new_sort[$i]);
            if ($productGroup) {
                $productGroup->position = $position;
                $productGroup->save();
                $position++;
            }
        }

        return $new_sort;
    }

    public function setProductSingleGroupOrder(Request $request)
    {
        $new_sort = $request->sort_list;
        $position = 0;
        for ($i=0; $i < count($new_sort); $i++) {
            $groupExtra = GroupExtra::find($new_sort[$i]);
            if ($groupExtra) {
                $groupExtra->position = $position;
                $groupExtra->save();
                $position++;
            }
        }

        return $new_sort;
    }
}
