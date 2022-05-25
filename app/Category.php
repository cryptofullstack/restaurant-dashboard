<?php

namespace App;

use App\Product;
use App\CategoryProduct;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function products(){
        $category_products = CategoryProduct::where('category_products.category_id', $this->id)
        ->join('products', 'products.id', '=', 'category_products.product_id')
        ->select('products.*', 'category_products.position')->orderBy('category_products.position', 'asc')->get();

        $final_category_products = array();

        foreach ($category_products as $product) {
            $single = [
                "id" => $product->id,
                "title" => $product->pro_name,
                "description" => $product->pro_description,
                "price" => $product->pro_price,
                "image" => asset('uploads/products/'.$product->pro_image),
                "position" => $product->position,
            ];

            $real_product = Product::find($product->id);

            $product_groups = $real_product->groups();

            $single['groups'] = $product_groups;

            array_push($final_category_products, $single);
        }

        return $final_category_products;
    }
}
