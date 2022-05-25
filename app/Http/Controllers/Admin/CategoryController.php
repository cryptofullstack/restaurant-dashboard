<?php

namespace App\Http\Controllers\Admin;

use App\Slim;
use Validator;
use App\Product;
use App\Category;
use App\CategoryProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.category');
    }

    public function getAllCategories()
    {
        $categories = Category::orderBy('position', 'ASC')->get();

        return response()->json($categories, 200);
    }

    public function insertCategory(Request $request)
    {
        if ($request->slim != null) {
            $imageRand = rand(1000, 9999);
            $random_name = $imageRand."_".time();
            $dst = public_path('uploads/category');

            $finish_image = $this->uploadImagetoServer($request, $random_name, $dst);

            if ($finish_image['result'] == "success") {
                $final_name = $finish_image['image'][0]['name'];

                $categoryPosition = $this->getCategoryPosition();

                $category = new Category;
                $category->title = $request->cat_title;
                $category->image = $final_name;
                $category->description = $request->cat_description;
                $category->position = $categoryPosition;
                $category->save();

                return response()->json(['result' => 'success']);
            }

            return response()->json(['result' => 'error', 'msg' => 'image uploading error']);
        }

        return response()->json(['result' => 'error', 'msg' => 'image required']);
    }

    public function getSingleCategory($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category['image_url'] = asset('uploads/category/'.$category->image);
            return response()->json(['result' => 'success', 'category' => $category]);
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

    protected function getCategoryPosition()
    {
        $categories = Category::orderBy('position', 'ASC')->get();

        $lastPosition = 0;

        foreach ($categories as $key => $category) {
            $category->position = $key;
            $category->save();

            $lastPosition = $key;
        }

        $final_position = $lastPosition+1;

        return $final_position;
    }

    public function updateCategory(Request $request)
    {
        $category = Category::find($request->category_id);

        if ($category) {
            $category->title = $request->_cat_title;
            $category->description = $request->_cat_description;
            $category->save();

            if ($request->slim != null) {
                $imageRand = rand(1000, 9999);
                $random_name = $imageRand."_".time();
                $dst = public_path('uploads/category');

                $finish_image = $this->uploadImagetoServer($request, $random_name, $dst);

                if ($finish_image['result'] == "success") {
                    $final_name = $finish_image['image'][0]['name'];

                    $category->image = $final_name;
                    $category->save();
                }
            }

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();

            $this->getCategoryPosition();

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error', 'msg' => 'Category can not find']);
    }

    public function setCategoryOrder(Request $request)
    {
        $new_sort = $request->sort_list;
        $position = 0;
        for ($i=0; $i < count($new_sort); $i++) {
            $category = Category::find($new_sort[$i]);
            if ($category) {
                $category->position = $position;
                $category->save();
                $position++;
            }
        }

        return $new_sort;
    }

    public function viewSingleCategoryDetail($id)
    {
        $category = Category::find($id);
        if ($category) {
            return view('admin.categoryDetail', ['category' => $category]);
        }

        return back();
    }

    public function getAllCategoryProducts($id)
    {
        $category = Category::find($id);
        if ($category) {
            $categoryProducts = CategoryProduct::where('category_id', $category->id)->join('products', 'products.id', '=', 'category_products.product_id')
            ->select('products.pro_name', 'products.pro_image', 'products.pro_description', 'products.pro_price', 'category_products.*')->orderBy('category_products.position', 'ASC')->get();

            return response()->json($categoryProducts, 200);
        }

        return response()->json([], 200);
    }

    public function getAllCategoryUnProducts($id)
    {
        $category = Category::find($id);

        if ($category) {
            $categoryProducts = CategoryProduct::where('category_id', $category->id)->orderBy('position', 'ASC')->get();

            $catProductIds = array();

            foreach ($categoryProducts as $key => $categoryProduct) {
                array_push($catProductIds, $categoryProduct->product_id);
            }

            $allProducts = Product::all();

            $finalProducts = array();

            foreach ($allProducts as $key => $product) {
                if (!in_array($product->id, $catProductIds)) {
                    array_push($finalProducts, $product);
                }
            }

            $renderedHtml = view('admin.components.addCategoryPro', ['products' => $finalProducts])->render();

            return response()->json(['result' => 'success', 'html' => $renderedHtml]);
        }

        return response()->json(['result' => 'error']);
    }

    public function insertCategoryProducts(Request $request)
    {
        $category = Category::find($request->category_id);
        if ($category) {
            $selectedProducts = $request->productIds;

            foreach ($selectedProducts as $key => $productId) {
                $getPosition = $this->getProductPosition($category->id);

                $categoryProduct = new CategoryProduct;
                $categoryProduct->category_id = $category->id;
                $categoryProduct->product_id = $productId;
                $categoryProduct->position = $getPosition;
                $categoryProduct->save();
            }

            return response()->json(['result' => 'success']);
        }
        return response()->json(['result' => 'error']);
    }

    public function getProductPosition($id)
    {
        $totalCatProsCount = CategoryProduct::where('category_id', $id)->count();
        $returnedPosition = $totalCatProsCount+1;

        $totalCatPros = CategoryProduct::where('category_id', $id)->orderBy('position', 'ASC')->get();

        foreach ($totalCatPros as $key => $singlePro) {
            $singlePro->position = $key+1;
            $singlePro->save();
        }

        return $returnedPosition;
    }

    public function removeCatProduct($id)
    {
        $categoryProduct = CategoryProduct::find($id);
        if ($categoryProduct) {
            $categoryId = $categoryProduct->category_id;
            $categoryProduct->delete();

            $this->getProductPosition($categoryId);

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }

    public function setCategoryProductsOrder(Request $request)
    {
        $new_sort = $request->sort_list;
        $position = 1;
        for ($i=0; $i < count($new_sort); $i++) {
            $catProduct = CategoryProduct::find($new_sort[$i]);
            if ($catProduct) {
                $catProduct->position = $position;
                $catProduct->save();
                $position++;
            }
        }

        return $new_sort;
    }
}
