<?php 

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

if(!function_exists('admin_timezone')){
	function admin_timezone(){
         $user = DB::table('users')->where([['user_type', '=', 1]])->first();
         $user = json_encode($user);
         $user = json_decode($user, true);
         $timezone = env('DEFAULT_TIMEZONE');
         if($user){
         	$user_infos = DB::table('user_infos')->where([['user_id', '=', $user['id']], ['meta_name', '=', 'timezone']])->first();
         	$user_infos = json_encode($user_infos);
            $user_infos = json_decode($user_infos, true);
            $timezone = $user_infos['meta_value'];
         }

         return $timezone;
	}
}


if(!function_exists('challenge_meta')){
	function challenge_meta($challenge_id, $meta_key) {
		$challenge_meta_value = DB::table('challenge_infos')->select('challenge_infos.meta_value')
				->where([['challenge_infos.challenge_id', '=', $challenge_id], ['challenge_infos.meta_name', '=', $meta_key]])
				->first();
         	return $challenge_meta_value->meta_value;
	}
}
?>
