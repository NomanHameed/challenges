<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User_challenges extends Model
{
	public function checkChallengeAssign($user_id, $challenge_id){
        $ch = self::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
        $ch = json_encode($ch);
        $ch = json_decode($ch, true);

        $status = 0;
        if($ch){
        	$status = 1;
        }
        return $status;
	}
    
}
