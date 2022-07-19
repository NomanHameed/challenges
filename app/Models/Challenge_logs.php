<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Challenge_logs extends Model
{
    public function getChallengeCoverage($challenge_id, $distance){
        $challenge = self::where('participation_id', $challenge_id)->get();
        $challenge = json_encode($challenge);
        $challenge = json_decode($challenge, true);
        $coveredDistance = 0;
        
        
        foreach($challenge as $key => $val){
            $coveredDistance = $coveredDistance + $val['distance_travelled'];
        }

        $percent = 0;

        if($distance && $coveredDistance){
            $percent = $coveredDistance / $distance * 100;
        }        

        $com = 'active';
        $comMsg = 'Active';
        $challenge_status = 1;
        if($percent == 100){
            $com = 'chComplete';
            $comMsg = 'Completed';
            $challenge_status = 2;
        }

        DB::table('challenges')->where('id', $challenge_id)->update(['challenge_status' => $challenge_status]);

        return json_encode(array("percentage" => $percent, 'com' => $com, 'comMsg' => $comMsg));


    }

    public function getChallengeCoverageDistance($challenge_id, $category, $id){

        if($category == 'accumulative'){
            $challenge = self::where('participation_id', $challenge_id)->groupBy('created_at')->groupBy('activity_id')->get();
            $challenge = json_encode($challenge);
            $challenge = json_decode($challenge, true);
        }else{
            $challenge = self::where([['participation_id', '=', $challenge_id], ['user_id', '=', $id]])->groupBy('created_at')->groupBy('activity_id')->get();
            $challenge = json_encode($challenge);
            $challenge = json_decode($challenge, true);
        }
        
        $coveredDistance = 0;
        
        
        foreach($challenge as $key => $val){
            $coveredDistance = $coveredDistance + $val['distance_travelled'];
        }

        return $coveredDistance;
    }

    public function monthlyMilesLog($challenge_id, $user_id, $start_date, $end_date, $timezone){
        //echo $start_date.'='.$end_date;
        //echo $start_date;
        //echo $end_date;
        $admin_timezone = admin_timezone(); //echo env('DEFAULT_TIMEZONE');

        // $start_date = $milestone->start_date;
            $start_date1 = '';
            if($start_date){
                //if(!$specific_date_checkbox){
                    //$start_date1 = $start_date ? Carbon::createFromFormat('Y-m-d H:i:s', $start_date, 'UTC')->setTimezone($admin_timezone) : '';
                    //$end_date1 = date("m/d/Y H:m", strtotime($end_date1));

                    $orgDate = \Carbon\Carbon::parse(@$start_date);

                            
                    $start_date1 = $orgDate->year.'-'.$orgDate->month.'-'.$orgDate->day.' '.$orgDate->hour.':'.$orgDate->minute.':'.$orgDate->second;

                    //$start_date1 = date("m/d/Y H:m", strtotime($start_date1));
                //}
            }

            //$end_date = $milestone->end_date;
            $end_date1 = '';
            if($end_date){
                //if(!$specific_date_checkbox){
                   // $end_date1 = $end_date ? Carbon::createFromFormat('Y-m-d H:i:s', $end_date, 'UTC')->setTimezone($admin_timezone) : '';
                    //$end_date1 = date("m/d/Y H:m", strtotime($end_date1));

                    $orgDate = \Carbon\Carbon::parse(@$end_date);

                            
                    $end_date1 = $orgDate->year.'-'.$orgDate->month.'-'.$orgDate->day.' '.$orgDate->hour.':'.$orgDate->minute.':'.$orgDate->second;

                    //$start_date1 = date("m/d/Y H:m", strtotime($start_date1));
                //}
            }
//echo $end_date1;

        //echo $start_date = Carbon::parse($start_date, 'UTC')->setTimezone($admin_timezone)->format('Y-m-d H:i:s');
        //echo $end_date = Carbon::parse($end_date, 'UTC')->setTimezone($admin_timezone)->format('Y-m-d H:i:s');

        //$start_date = Carbon::parse($start_date, $admin_timezone)->setTimezone($timezone)->format('Y-m-d H:i:s');
        //$end_date = Carbon::parse($end_date, $admin_timezone)->setTimezone($timezone)->format('Y-m-d H:i:s');

        //echo $start_date = Carbon::parse($start_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
        //echo $end_date = Carbon::parse($end_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
        $current_month =DB::table('challenge_logs')->select('challenge_logs.*')->where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->whereBetween('startDateTime', [$start_date1, $end_date1])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        
        $monthDistance = 0;
        if($current_month){
            foreach($current_month as $key => $value){
                $monthDistance = $monthDistance + $value->distance_travelled;
            }
        }
        return $monthDistance;
    }

    public function monthlyMilesLogForAccumulative($challenge_id, $user_id, $start_date, $end_date, $timezone){
        //echo $start_date.'='.$end_date;

        $admin_timezone = admin_timezone(); //echo env('DEFAULT_TIMEZONE');
        //$start_date = Carbon::parse($start_date, 'UTC')->setTimezone($admin_timezone)->format('Y-m-d H:i:s');
        //$end_date = Carbon::parse($end_date, 'UTC')->setTimezone($admin_timezone)->format('Y-m-d H:i:s');
        $current_month =DB::table('challenge_logs')->select('challenge_logs.*')->where([['participation_id', '=', $challenge_id]])->whereBetween('startDateTime', [$start_date, $end_date])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        
        $monthDistance = 0;
        if($current_month){
            foreach($current_month as $key => $value){
                $monthDistance = $monthDistance + $value->distance_travelled;
            }
        }
        return $monthDistance;
    }
}
