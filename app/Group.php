<?php

namespace App;

use App\GroupExtra;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public function extras(){
        $group_extras = GroupExtra::where('group_extras.group_id', $this->id)
        ->join('extras', 'extras.id', '=', 'group_extras.extra_id')
        ->select('extras.*', 'group_extras.position')->orderBy('group_extras.position', 'asc')->get();

        $final_group_extras = array();

        foreach ($group_extras as $extra) {
            $single = [
                "id" => $extra->id,
                "name" => $extra->extra_name,
                "price" => $extra->extra_price,
                "position" => $extra->position
            ];

            array_push($final_group_extras, $single);
        }

        return $final_group_extras;
    }
}
