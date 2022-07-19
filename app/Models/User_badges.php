<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class User_badges extends Model
{
	public function checkBadgeAssign($user_id, $badge_id){
		
		$ch = self::where([['badge_id', '=', $badge_id], ['user_id', '=', $user_id], ["badge_type", '!=', 'challenge_milestone']])->first();
        $ch = json_encode($ch);
        $ch = json_decode($ch, true);

        $status = 0;
        if($ch){
        	$status = 1;
        }
        
        return $status;
	}

    public function checkBadgeAssignSeenStatus($user_id, $badge_id){
        
        $ch = self::where([['badge_id', '=', $badge_id], ['user_id', '=', $user_id], ["badge_type", '!=', 'challenge_milestone'], ["is_seen", '=', '0']])->first();
        $ch = json_encode($ch);
        $ch = json_decode($ch, true);

        $status = 0;
        if($ch){
            $status = 1;
        }
        
        return $status;
    }

    public function getUserBadgesByUserIDAndBadgeId($user_id, $badge_id)
    {
        $ub = self::where([['user_id', '=', $user_id], ['badge_id', '=', $badge_id], ['badge_type', '!=', 'challenge_milestone']])->get();
        $ub = json_encode($ub);
        $ub = json_decode($ub, true);

        return $ub;
    }

    public function getTotalDistanceTravel($user_id, $challenge_id)
    {
        $distancreTravel = DB::table('challenge_logs')->where('user_id', $user_id)->where('participation_id', $challenge_id)->get();
        $totalDistancreTravelArr = array();
        if($distancreTravel){
            foreach($distancreTravel as $key => $value){
                $distance_travelled = $value->distance_travelled;
                $created_at = $value->created_at;
                $totalDistancreTravelArr[$distance_travelled.$created_at] = $value->distance_travelled;
            }
        }
        $totalDistancreTravel = array_sum($totalDistancreTravelArr);

        return $totalDistancreTravel;
    }

    public function getTotalNumberofLogs($user_id, $challenge_id, $start_date, $end_date, $condition_limit, $badge_id)
    {
        $coveredDistanceForDate = 0;
        $count = 0;
        $enddate = '';

        // $challengeLogForDate = DB::table('challenge_logs')->select('challenge_logs.*')
        //      ->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
        //      ->where([['user_challenges.challenge_id', '=', $challenge_id]])
        //      ->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
        //      ->groupBy('challenge_logs.created_at')
        //      ->groupBy('challenge_logs.activity_id')
        //      ->get();

        $badge = DB::table('badges')->where('id', $badge_id)->first();
        $badge = json_encode($badge);
        $badge = json_decode($badge, true); //print_r($badge); 
        if($badge){
            $specific_date_checkbox = $badge['specific_date_checkbox'];
            $specific_date = $badge['specific_date'];
        }else{
            $specific_date_checkbox = '';
            $specific_date = '';
        }

        $user_infos = User_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
        $user_infos = json_encode($user_infos);
        $user_infos = json_decode($user_infos, true);
        $timezone = env('DEFAULT_TIMEZONE');
        $redirectTime = 0;
        if($user_infos){
            $timezone = $user_infos['meta_value'];
        }

        $orgDate = Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->toDateString();
        //echo $orgDate = Carbon::parse($orgDate, env('DEFAULT_TIMEZONE'))->setTimezone($timezone)->toDateTimeString();
        //echo '<br>';

        $newDate = '';
        if($orgDate){
        $tempDate = explode(' ', $orgDate);
          $tempDate = explode('-', $tempDate['0']);
          $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
          if($stat){
            $newDate = $orgDate;
            //$newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $orgDate);
            //$newDate = date("m/d/Y H:m", strtotime($newDate));
          }
        }

         $start_date = Carbon::parse($newDate)->startOfDay();
         $end_date = Carbon::parse($newDate)->endOfDay(); 
        /*if($badge_id == 47){
            //echo $specific_date_checkbox .'&&'. $newDate;
            echo $challenge_id;
        }*/

        

        if($specific_date_checkbox && $newDate){
            $challengeLogForDate2 = DB::table('challenge_logs')
                ->select('challenge_logs.*')
                //->where([['challenge_logs.participation_id', '=', $challenge_id]])
                ->where([['challenge_logs.user_id', '=', $user_id]])
                //->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
                ->groupBy('challenge_logs.created_at')
                ->groupBy('challenge_logs.activity_id')
                ->orderBy('challenge_logs.created_at')
                ->get(); 
                $challengeLogForDate3 = json_encode($challengeLogForDate2);
                $challengeLogForDate3 = json_decode($challengeLogForDate3, true);
                $sta = 0;
                if($challengeLogForDate3){
                    foreach($challengeLogForDate3 as $ka=>$va){
                         $startDateTime = $va['startDateTime']; 
                        //echo $newDate; echo '==';
                         $startDateTime = Carbon::parse($startDateTime, 'UTC')->setTimezone($timezone)->toDateString(); 
                         //echo '==';
                        //echo $va['distance_travelled']; echo '==';
                        //echo '<br>';
                        
                        if($newDate == $startDateTime){
                           if($coveredDistanceForDate < $condition_limit){
                                $coveredDistanceForDate = $coveredDistanceForDate + $va['distance_travelled'];
                                $count++;
                                $enddate = $va['created_at'];
                            }
                        }
                    }
                }

                $res = array("enddate" => $enddate, "count" => $count);
                //echo $challenge_id.'='.$challengeLogForDate1->count(); //die();
                return json_encode($res);
        }else{
            if($challenge_id){ 

                $challenge_milestones = DB::table('challenge_milestones')->where('id', $badge_id)->first();
                $challenge_milestones = json_encode($challenge_milestones);
                $challenge_milestones = json_decode($challenge_milestones, true); //print_r($challenge_milestones); 
                if($challenge_milestones){
                    $specific_date_checkbox_ch = $challenge_milestones['specific_date_checkbox'];
                    $specific_date_ch = $challenge_milestones['specific_date'];
                }else{
                    $specific_date_checkbox_ch = '';
                    $specific_date_ch = '';
                }

                $orgDate = $specific_date_ch;

                $newDate = '';
                if($orgDate){
                $tempDate = explode(' ', $orgDate);
                  $tempDate = explode('-', $tempDate['0']);
                  $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
                  if($stat){

                    $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $orgDate);
                    //$newDate = date("m/d/Y H:m", strtotime($newDate));
                  }
                }

                $start_date = Carbon::parse($newDate)->startOfDay();
                $end_date = Carbon::parse($newDate)->endOfDay(); 

                /*if($badge_id == 47){
                    echo $specific_date_checkbox .'&&'. $newDate;
                    echo $challenge_id;
                }*/

                if($specific_date_checkbox_ch && $specific_date_ch){
                    $challengeLogForDate1 = DB::table('challenge_logs')
                    ->select('challenge_logs.*')
                    ->where([['challenge_logs.user_id', '=', $user_id]])
                    ->where([['challenge_logs.participation_id', '=', $challenge_id]])
                    ->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
                    ->groupBy('challenge_logs.created_at')
                    ->groupBy('challenge_logs.activity_id')
                    ->orderBy('challenge_logs.created_at')
                    ->get(); //print_r($challengeLogForDate1); die();




                }else{
                    $challengeLogForDate1 = DB::table('challenge_logs')
                    ->select('challenge_logs.*')
                    ->where([['challenge_logs.user_id', '=', $user_id]])
                    ->where([['challenge_logs.participation_id', '=', $challenge_id]])
                    ->groupBy('challenge_logs.created_at')
                    ->groupBy('challenge_logs.activity_id')
                    ->orderBy('challenge_logs.created_at')
                    ->get();

                    /*if($badge_id == 47){ echo $user_id;
                        echo '<pre>'; print_r($challengeLogForDate1); echo '</pre>';
                    }*/
                }
                
                
                
            }else{
                $challengeLogForDate1 = DB::table('challenge_logs')
                ->select('challenge_logs.*')
                ->where([['challenge_logs.user_id', '=', $user_id]])
                //->where([['challenge_logs.participation_id', '=', $challenge_id]])
                //->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
                ->groupBy('challenge_logs.created_at')
                ->groupBy('challenge_logs.activity_id')
                ->orderBy('challenge_logs.created_at')
                ->get(); //print_r($challengeLogForDate1); die();
            }

        }
        
        $count = 0;
        $enddate = '';

        if(@$challengeLogForDate1){
            $challengeLogForDate = json_encode($challengeLogForDate1);
            $challengeLogForDate = json_decode($challengeLogForDate, true);
               
            if($challengeLogForDate){
                foreach($challengeLogForDate as $key => $va){
                    
                    if($coveredDistanceForDate < $condition_limit){
                        $coveredDistanceForDate = $coveredDistanceForDate + $va['distance_travelled'];
                        $count++;
                        $enddate = $va['created_at'];
                    }
                    
                }
            }
        }

        

        $res = array("enddate" => $enddate, "count" => $count);
        //echo $challenge_id.'='.$challengeLogForDate1->count(); //die();
        return json_encode($res);
    }


    public function getTotalChallengeMilestones($challenge_id)
    {
        $coveredDistanceForDate = 0;

        $challengeLogForDate = DB::table('challenge_milestones')
            ->select('challenge_milestones.*')
            ->where([['challenge_milestones.challenge_id', '=', $challenge_id]])
            ->whereNull('challenge_milestones.milestone_type')
            ->get();

        $challengeLogForDate = json_encode($challengeLogForDate);
        $challengeLogForDate = json_decode($challengeLogForDate, true);

        if($challengeLogForDate){
            foreach($challengeLogForDate as $key => $va){
                                
                $coveredDistanceForDate = $coveredDistanceForDate + $va['milestone_distance'];
                
            }
        }

        return $coveredDistanceForDate;
    }
    
}