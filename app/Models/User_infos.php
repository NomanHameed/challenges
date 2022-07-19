<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_infos extends Model
{
    public function updateMetaValue($id, $metaName, $metaValue){
        $User_infosGender = self::where([['user_id', '=', $id], ['meta_name', '=', $metaName]])->first();
        $User_infosGender1 = json_encode($User_infosGender);
        $User_infosGender1 = json_decode($User_infosGender1, true);
        if($User_infosGender1){
            $User_infosGender->meta_value = $metaValue;
            $User_infosGender->update();
        }else{
            $usr = new self();
            $usr->user_id = $id;
            $usr->meta_name = $metaName;
            $usr->meta_value = $metaValue;
            $usr->save();
        }
        
    }
}
