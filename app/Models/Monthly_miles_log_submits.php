<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Monthly_miles_log_submits extends Model
{
    public function getMilesLogSubmitStatus($milestone_id, $user_id){
    	$Monthly_miles_log_submits = self::where([['milestone_id', '=', $milestone_id], ['user_id', '=', $user_id]])->first();
        $Monthly_miles_log_submits1 = json_encode($Monthly_miles_log_submits);
        $Monthly_miles_log_submits1  = json_decode($Monthly_miles_log_submits1, true);

        $status = 0;

        if($Monthly_miles_log_submits1){
        	$status = 1;
        }

        return $status;
    }
}