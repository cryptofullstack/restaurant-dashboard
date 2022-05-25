<?php

namespace App;

use App\ProductGroup;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function groups(){
        $product_groups = ProductGroup::where('product_groups.product_id', $this->id)
        ->join('groups', 'groups.id', '=', 'product_groups.group_id')
        ->select('groups.*', 'product_groups.position')->orderBy('product_groups.position', 'asc')->get();

        $final_product_groups = array();

        foreach ($product_groups as $group) {
            $single = [
                "id" => $group->id,
                "name" => $group->group_name,
                "type" => $group->type,
                "position" => $group->position,
            ];

            $real_group = Group::find($group->id);
            $group_extras = $real_group->extras();
            $single['extras'] = $group_extras;

            array_push($final_product_groups, $single);
        }

        return $final_product_groups;
    }
}
