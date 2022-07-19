<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User_challenges;
use App\Models\Badges;
use App\Models\User_badges;
use Carbon\Carbon;
use App\User;
use App\Models\User_infos;
use App\Models\Challenges;
use App\Models\Challenge_milestones;
use App\Models\Challenge_infos;

use App\Mail\ChallengeCompletionUpdateIndividual;
use App\Mail\ChallengeCompletionUpdateAccumulative;
use App\Mail\SuccessfulBadgeAchievement;
use Illuminate\Support\Facades\Mail;

class Challenge_infos extends Model
{
    public function getChallengeDistance($id){
        return self::where([['meta_name', '=', 'total_distance'], ['challenge_id', '=', $id]])->first();
    }

    public function challengeMilestoneCompletePercentage($id, $dis){
        $ch = self::where([['meta_name', '=', 'total_distance'], ['challenge_id', '=', $id]])->first();
        $total_distance = $ch['meta_value'];

        $percent = 0;

        if($dis && $total_distance){
            $percent = $dis / $total_distance * 100;
        } 

        return $percent; 
    }

    public function challengeMilestoneComplete($id, $user_id, $milesDist){ 
        $ch = DB::table('challenge_logs')->where([['participation_id', '=', $id], ['user_id', '=', $user_id]])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $ch = json_encode($ch);
        $ch = json_decode($ch, true);
        //echo '<pre>';print_r($ch); echo '</pre>';
        $dist = 0;
        
        foreach($ch as $key => $val){
            $dist = $dist+$val['distance_travelled'];
        }

        $percent = 0;

        if($dist >= $milesDist){

            $percent = 100;

        }elseif($dist < $milesDist){

            $count1 = $dist / $milesDist;
            $count2 = $count1 * 100;
            $percent = number_format($count2, 0);

            

        }

        return $percent; 
    }

    public function challengeMilestoneCompleteBadgeAssignOnLogAdd($id, $user_id, $milesDist, $distance){ 
        $ch = DB::table('challenge_logs')->where([['participation_id', '=', $id], ['user_id', '=', $user_id]])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $ch = json_encode($ch);
        $ch = json_decode($ch, true);
        //echo '<pre>';print_r($ch); echo '</pre>';
        $dist = 0;
        
        foreach($ch as $key => $val){
            $dist = $dist+$val['distance_travelled'];
        }

        $dist = $dist + $distance;

        $percent = 0;

        if($dist >= $milesDist){

            $percent = 100;

        }elseif($dist < $milesDist){

            $count1 = $dist / $milesDist;
            $count2 = $count1 * 100;
            $percent = number_format($count2, 0);

            

        }

        return $percent; 
    }

    public function challengeMilestoneCompleteBadgeRemoveOnLogRemove($id, $user_id, $milesDist, $distance){ 
        $ch = DB::table('challenge_logs')->where([['participation_id', '=', $id], ['user_id', '=', $user_id]])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $ch = json_encode($ch);
        $ch = json_decode($ch, true);
        //echo '<pre>';print_r($ch); echo '</pre>';
        $dist = 0;
        
        foreach($ch as $key => $val){
            $dist = $dist+$val['distance_travelled'];
        }

        $dist = $dist - $distance;

        $percent = 0;

        if($dist >= $milesDist){

            $percent = 100;

        }elseif($dist < $milesDist){

            $count1 = $dist / $milesDist;
            $count2 = $count1 * 100;
            $percent = number_format($count2, 0);

            

        }

        return $percent; 
    }

    public function challengeComplete($id, $user_id, $milesDist){
        $ch = DB::table('challenge_logs')->where([['participation_id', '=', $id], ['user_id', '=', $user_id]])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $ch = json_encode($ch);
        $ch = json_decode($ch, true);

        $dist = 0;
        
        foreach($ch as $key => $val){
            $dist = $dist+$val['distance_travelled'];
        }

        $percent = 0;
        //echo $dist .'>='. $milesDist;
        if($dist >= $milesDist){

            $percent = 100;

        }elseif($dist < $milesDist){

            $count1 = $dist / $milesDist;
            $count2 = $count1 * 100;
            $percent = number_format($count2, 1);

            

        }

        return $percent; 
    }

    public function challengeMilestoneCompleteAtSpecificDateBadgeAssignOnLogAdd($participation_id, $user_id, $milesDist, $specificDateTime, $timezone, $distance){
         $start_date = Carbon::parse($specificDateTime)->startOfDay();
         $end_date = Carbon::parse($specificDateTime)->endOfDay();

        //\DB::enableQueryLog();
        $ch = DB::table('challenge_logs')->where([['participation_id', '=', $participation_id], ['user_id', '=', $user_id]])->whereBetween('startDateTime', array($start_date, $end_date))->groupBy('created_at')->groupBy('activity_id')->get();
        //print_r($ch);

        //$ch = DB::table('challenge_logs')->where([['participation_id', '=', $id], ['user_id', '=', $user_id]])->whereBetween('startDateTime', array($start_date, $end_date))->groupBy('created_at')->get();

        $ch = json_encode($ch);
        $ch = json_decode($ch, true);
        
        $dist = 0;
        //if($ch){
            foreach($ch as $key => $val){

                $dist = $dist+$val['distance_travelled'];
            }

            $dist = $dist + $distance;
        //}
        

        /*if($milesDist == 20.2){
            //dd(\DB::getQueryLog());
            echo '<pre>'; print_r($ch); echo '</pre>'; 
            echo $dist .'>='. $milesDist;//die();
        }*/
        
  //echo '<pre>'; print_r($ch);

        $percent = 0;
        if($dist >= $milesDist){

            $percent = 100;

        }elseif($dist < $milesDist){

            $count1 = $dist / $milesDist;
            $count2 = $count1 * 100;
            $percent = number_format($count2, 0);

            

        }

        return $percent; 

    }

    public function challengeMilestoneCompleteAtSpecificDateBadgeRemoveOnLogRemove($participation_id, $user_id, $milesDist, $specificDateTime, $distance){
        $start_date = Carbon::parse($specificDateTime)->startOfDay();
        $end_date = Carbon::parse($specificDateTime)->endOfDay();

        //\DB::enableQueryLog();
        $ch = DB::table('challenge_logs')->where([['participation_id', '=', $participation_id], ['user_id', '=', $user_id]])->whereBetween('startDateTime', array($start_date, $end_date))->groupBy('created_at')->groupBy('activity_id')->get();
        

        //$ch = DB::table('challenge_logs')->where([['participation_id', '=', $id], ['user_id', '=', $user_id]])->whereBetween('startDateTime', array($start_date, $end_date))->groupBy('created_at')->get();

        $ch = json_encode($ch);
        $ch = json_decode($ch, true);
        
        $dist = 0;
        foreach($ch as $key => $val){

            $dist = $dist+$val['distance_travelled'];
        }

        $dist = $dist - $distance;

        /*if($milesDist == 20.2){
            //dd(\DB::getQueryLog());
            echo '<pre>'; print_r($ch); echo '</pre>'; 
            echo $dist .'>='. $milesDist;//die();
        }*/
        
  //echo '<pre>'; print_r($ch);

        $percent = 0;
        if($dist >= $milesDist){

            $percent = 100;

        }elseif($dist < $milesDist){

            $count1 = $dist / $milesDist;
            $count2 = $count1 * 100;
            $percent = number_format($count2, 0);

            

        }

        return $percent; 

    }

    public function challengeMilestoneCompleteAtSpecificDate($participation_id, $user_id, $milesDist, $specificDateTime, $timezone){
        $start_date = Carbon::parse($specificDateTime)->startOfDay();
        $end_date = Carbon::parse($specificDateTime)->endOfDay();

        //\DB::enableQueryLog();
        $ch = DB::table('challenge_logs')->where([['participation_id', '=', $participation_id], ['user_id', '=', $user_id]])->whereBetween('startDateTime', array($start_date, $end_date))->groupBy('created_at')->groupBy('activity_id')->get();
        

        //$ch = DB::table('challenge_logs')->where([['participation_id', '=', $id], ['user_id', '=', $user_id]])->whereBetween('startDateTime', array($start_date, $end_date))->groupBy('created_at')->get();

        $ch = json_encode($ch);
        $ch = json_decode($ch, true);
        
        $dist = 0;
        foreach($ch as $key => $val){

            $dist = $dist+$val['distance_travelled'];
        }

        /*if($milesDist == 20.2){
            //dd(\DB::getQueryLog());
            echo '<pre>'; print_r($ch); echo '</pre>'; 
            echo $dist .'>='. $milesDist;//die();
        }*/
        
  //echo '<pre>'; print_r($ch);

        $percent = 0;
        if($dist >= $milesDist){

            $percent = 100;

        }elseif($dist < $milesDist){

            $count1 = $dist / $milesDist;
            $count2 = $count1 * 100;
            $percent = number_format($count2, 0);

            

        }

        return $percent; 
    }

    public function checkAndActivateChallenge($challenge_id, $distance, $user_id, $start_date_time){
        $challengeInfo = DB::table('challenge_infos')->select('challenge_infos.*')
                         //->join('user_challenges', "challenge_infos.challenge_id", '=', 'user_challenges.challenge_id')
                         ->where([['challenge_infos.challenge_id', '=', $challenge_id]])
                         //->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
                         ->get();
        $challengeInfo = json_encode($challengeInfo);
        $challengeInfo = json_decode($challengeInfo, true);
        
        $total_distance = 0;
        $category = 0;
        if($challengeInfo){
            foreach($challengeInfo as $key => $val){
                if($val['meta_name'] == 'total_distance'){
                    $total_distance = $total_distance + $val['meta_value'];
                                        
                }

                if($val['meta_name'] == 'category'){
                    
                    $category = $val['meta_value'];  

                }
            }
        }
        //echo $user_id; 
        if($category == 'individual'){ //echo $category; echo $challenge_id;
            $challengeLog = DB::table('challenge_logs')->select('challenge_logs.*')
             //->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
             ->where([['challenge_logs.user_id', '=', $user_id],['challenge_logs.participation_id', '=', $challenge_id]])
             ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
             ->get();

            /*$User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
            $User_challenges->status = 2;
            $User_challenges->update();*/

         }else{
            $challengeLog = DB::table('challenge_logs')->select('challenge_logs.*')
             //->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
             ->where([['challenge_logs.participation_id', '=', $challenge_id]])
             ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
             ->get();
         }
        
        $challengeLog = json_encode($challengeLog);
        $challengeLog = json_decode($challengeLog, true);
            //echo '<pre>';print_r($challengeLog); die();
        $coveredDistance = 0;
        if($challengeLog){
            foreach($challengeLog as $key => $val){
                
                    $coveredDistance = $coveredDistance + $val['distance_travelled'];
                
            }

        }

        $coveredDistance = $coveredDistance - $distance;

        /*if($challenge_id == 26){

            var_dump($coveredDistance < $total_distance);
            if($coveredDistance < $total_distance){
                
            }
            
            echo $coveredDistance .'<'. $total_distance; die();
        }*/
        
        if($coveredDistance > $total_distance){

        }else{

        /*if($challenge_id == 26){
            echo $coveredDistance .'<'. $total_distance; die();
        }*/

           $ucha = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
           $ucha->status = 1;
           $ucha->update();

           $ch = Challenges::where([['id', '=', $challenge_id]])->first();
           $ch->status = 1;
           $ch->update();
        }

        $uB = User_badges::where('user_id', $user_id)->get();
        $uB = json_encode($uB);
        $uB = json_decode($uB, true);
        if($uB){
            foreach($uB as $key => $val){
                $badge_type = $val['badge_type'];
                $badge_id = $val['badge_id'];

                

                $condition_limit = @$val['condition_limit'];
                if($badge_type == 'distance'){ 
                    $badg = Badges::where('id', $badge_id)->first();
                    $badg = json_encode($badg);
                    $badg = json_decode($badg, true);
                    if($badg){
                        $condition_limit = $badg['condition_limit'];
                        $user_infos = User_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
                        $user_infos = json_encode($user_infos);
                        $user_infos = json_decode($user_infos, true);
                        $timezone = env('DEFAULT_TIMEZONE');
                        $redirectTime = 0;
                        if($user_infos){
                            $timezone = $user_infos['meta_value'];
                        }

                        $specific_date = $badg['specific_date'];
                        $specific_date = Carbon::parse($specific_date)->format('Y-m-d');

                        $start_date_time = Carbon::parse($start_date_time)->format('Y-m-d');

                        $specific_date_checkbox = @$badg['specific_date_checkbox'] ? @$badg['specific_date_checkbox'] : 0; 

                        $coveredDistanceForDate = 0;

                        /*if($badg['id'] == 18){
                            echo $specific_date.'='.$start_date_time.'<br>';
                        }*/
                        
                         
                        
                        //print_r($badg); echo $badge_type; echo $coveredDistance .'<'. $condition_limit;die();
                        if(!$specific_date_checkbox){
                            if($coveredDistance < $condition_limit){
                                $userB = User_badges::find($val['id']);
                                $userB->delete();
                            }
                        }elseif($specific_date_checkbox && $specific_date == $start_date_time){

                            $start_date = Carbon::parse($specific_date)->startOfDay();
                            $end_date = Carbon::parse($specific_date)->endOfDay();

                            
                            
                            
                                $challengeLogForDate = DB::table('challenge_logs')->select('challenge_logs.*')
                                 //->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
                                 ->where([['challenge_logs.user_id', '=', $user_id]])
                                 ->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
                                 ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
                                 ->get();
                             
                            $challengeLogForDate = json_encode($challengeLogForDate);
                            $challengeLogForDate = json_decode($challengeLogForDate, true);
                     
                            
                            if($challengeLogForDate){
                                foreach($challengeLogForDate as $key => $va){
                                    
                                        $coveredDistanceForDate = $coveredDistanceForDate + $va['distance_travelled'];
                                    
                                }

                            }
                            
                            $coveredDistanceForDate = $coveredDistanceForDate - $distance;
                            /*if($badg['id'] == 18){
                                echo '<pre>'; print_r($challengeLogForDate); echo '</pre>';
                            echo $coveredDistanceForDate.'='.$condition_limit.'<br>';die();
                        }*/
                            if($coveredDistanceForDate < $condition_limit){
                                $userB = User_badges::find($val['id']);
                                $userB->delete();
                            }

                        }
                        
                    }
                    
                }
            }
        }

        $user_badges = user_badges::where([['challenge_id', '=', $challenge_id], ['badge_type', '=', 'challenge_milestone'], ['user_id', '=', $user_id]])->get();
        $user_badges = json_encode($user_badges);
        $user_badges = json_decode($user_badges, true);

       

        $milestone = 0;
        $milestoneOnSpecificDate = 0;

        if($user_badges){ 
            foreach($user_badges as $k => $va){ 

                $badge_id = $va['badge_id'];

                if($badge_id){
                    $ms = Challenge_milestones::where('id', $badge_id)->first();
                    $ms = json_encode($ms);
                    $ms = json_decode($ms, true);
                    if($ms){
                        $v = $ms;
                        if($v){
                            $specific_date_checkbox = $v['specific_date_checkbox'];
                            $specific_date = $v['specific_date'];
                         }else{
                            $specific_date_checkbox = '';
                            $specific_date = '';
                         }

                         $orgDate = $specific_date;

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
                         //echo $specific_date_checkbox .'&&'. $newDate; 
                         if($specific_date_checkbox && $newDate){
                            $milestoneOnSpecificDate = $v['milestone_distance'];
                            $completePercentageAtSpecificDate = $this->challengeMilestoneCompleteAtSpecificDateBadgeRemoveOnLogRemove($challenge_id, $user_id, $milestoneOnSpecificDate, $newDate, $distance);
                            if($completePercentageAtSpecificDate < 100){
                                $ChallengeM = User_badges::find($va['id']);
                                $ChallengeM->delete();
                            }
                         }else{
                            $milestone = $milestone + $v['milestone_distance'];
                            $completePercentage = $this->challengeMilestoneCompleteBadgeRemoveOnLogRemove($challenge_id, $user_id, $milestone, $distance);
                            
                           
                            
                            
                            if($completePercentage < 100){
                                $ChallengeM = User_badges::find($va['id']);
                                $ChallengeM->delete();                           

                            }
                         }

                    }
                }

                
                
            }
        }


        //echo $category; echo count($challengeLog);
        $arr = array("status" => 1, "message" => "Challenges is activated again.");
        return json_encode($arr);
    }

    public function checkChallengeMilestone($challenge_id, $distance, $user_id, $start_date_time){
        $challengeInfo = DB::table('challenge_infos')->select('challenge_infos.*')
                         //->join('user_challenges', "challenge_infos.challenge_id", '=', 'user_challenges.challenge_id')
                         ->where([['challenge_infos.challenge_id', '=', $challenge_id]])
                         //->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
                         ->get();
        $challengeInfo = json_encode($challengeInfo);
        $challengeInfo = json_decode($challengeInfo, true);
        
        $total_distance = 0;
        $category = 0;
        if($challengeInfo){
            foreach($challengeInfo as $key => $val){
                if($val['meta_name'] == 'total_distance'){
                    $total_distance = $val['meta_value'];
                                        
                }

                if($val['meta_name'] == 'category'){
                    
                    $category = $val['meta_value'];  

                }
            }
        }
        
        if($category == 'individual'){
            $challengeLog = DB::table('challenge_logs')->select('challenge_logs.*')
             ->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
             ->where([['user_challenges.user_id', '=', $user_id],['user_challenges.challenge_id', '=', $challenge_id]])
             ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
             ->get();

            /*$User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
            $User_challenges->status = 2;
            $User_challenges->update();*/

         }else{
            $challengeLog = DB::table('challenge_logs')->select('challenge_logs.*')
             ->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
             ->where([['user_challenges.challenge_id', '=', $challenge_id]])
             ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
             ->get();
         }
        
        $challengeLog = json_encode($challengeLog);
        $challengeLog = json_decode($challengeLog, true);
 
        $coveredDistance = 0;
        if($challengeLog){
            foreach($challengeLog as $key => $val){
                
                    $coveredDistance = $coveredDistance + $val['distance_travelled'];
                
            }

        }

        /*
                        
                        if($category == 'individual'){
                            
                        }else{
                            
                        }*/
        
        $coveredDistance = $coveredDistance + $distance;
        //echo $category; echo $coveredDistance; 
        
        return $this->getTotalDistanceCovered($challenge_id, $distance, $total_distance,$user_id, $coveredDistance, $category, $start_date_time);
    }

    public function getTotalDistanceCovered($challenge_id, $distance, $total_distance,$user_id, $coveredDistance, $category, $start_date_time){    

        $user_infos = User_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
        $user_infos = json_encode($user_infos);
        $user_infos = json_decode($user_infos, true); 
        $timezone = env('DEFAULT_TIMEZONE'); 
        if($user_infos){ 
            $timezone = $user_infos['meta_value'];
        }  

        $User_challenges_check = User_challenges::where([['user_id', '=', $user_id], ['challenge_id', '=', $challenge_id]])->first();
        $User_challenges_check = json_encode($User_challenges_check);
        $User_challenges_check = json_decode($User_challenges_check, true); 
        $timezone = env('DEFAULT_TIMEZONE'); 
        if($User_challenges_check){ 
            $sta = $User_challenges_check['status'];
            if($sta == 2){
                $arr = array("status" => 3, "message" => "The challenge is closed.");
                return json_encode($arr);
            }
        }   

        $challenge = Challenges::where('id', $challenge_id)->first();
        $challenge = json_encode($challenge);
        $challenge = json_decode($challenge, true);

        if($challenge['status'] == 2){
            $arr = array("status" => 3, "message" => "The challenge is closed.");
            return json_encode($arr);
        }
        
        $event_end_date = Carbon::parse($challenge['event_end_date'], 'UTC')->setTimezone($timezone);
        $newDate = '';
        if($event_end_date){
        $tempDate = explode(' ', $event_end_date);
          $tempDate = explode('-', $tempDate['0']);
          $stat = checkdate($tempDate[1], $tempDate[2], (int)$tempDate[0]);
          if($stat){
            if($event_end_date < Carbon::now($timezone)->toDateTimeString()){
  
                $arr = array("status" => 3, "message" => "The ".$event_end_date." challenge closed date has beed passed.");
                return json_encode($arr);

            }
          }
        }

        $event_start_date = Carbon::parse($challenge['event_start_date'], 'UTC')->setTimezone($timezone);
        $newDate = '';
        if($event_start_date){
        $tempDate = explode(' ', $event_start_date);
          $tempDate = explode('-', $tempDate['0']);
          $stat = checkdate($tempDate[1], $tempDate[2], (int)$tempDate[0]);
          if($stat){ //echo $event_start_date .'>'. Carbon::now($timezone)->toDateTimeString();
            if($event_start_date > Carbon::now($timezone)->toDateTimeString()){
  
                $arr = array("status" => 3, "message" => "The challenge will start on ".$event_start_date.".");
                return json_encode($arr);

            }
          }
        }

        $badges = Badges::get();
        $badges = json_encode($badges);
        $badges = json_decode($badges, true);

        $Challenge_milestones = Challenge_milestones::where('challenge_id', $challenge_id)->get();
        $Challenge_milestones = json_encode($Challenge_milestones);
        $Challenge_milestones = json_decode($Challenge_milestones, true);

        $milestone = 0;
        $milestoneOnSpecificDate = 0;

        if($Challenge_milestones){ //echo '<pre>'; print_r($Challenge_milestones); echo '</pre>'; 
            foreach($Challenge_milestones as $k => $v){ 

                if($v){
                        $specific_date_checkbox = $v['specific_date_checkbox'];
                        $specific_date = $v['specific_date'];
                     }else{
                        $specific_date_checkbox = '';
                        $specific_date = '';
                     }

                     $orgDate = $specific_date;

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
                     //echo $specific_date_checkbox .'&&'. $newDate; 
                     if($specific_date_checkbox && $newDate){
                        $milestoneOnSpecificDate = $v['milestone_distance'];
                        $completePercentageAtSpecificDate = $this->challengeMilestoneCompleteAtSpecificDateBadgeAssignOnLogAdd($challenge_id, $user_id, $milestoneOnSpecificDate, $newDate, $timezone, $distance);
                        //print_r($completePercentageAtSpecificDate); echo $newDate; die;
                        if($completePercentageAtSpecificDate >= 100){
                            $this->assignBadgeForChallengeMilestonese($challenge_id, $v['id'], $user_id, "challenge_milestone", $timezone);
                        }
                     }else{
                        $milestone = $milestone + $v['milestone_distance'];
                        $completePercentage = $this->challengeMilestoneCompleteBadgeAssignOnLogAdd($challenge_id, $user_id, $milestone, $distance);
                        
                        /*if($v['id'] == 26){
                            echo $completePercentage.'='.$milestone;die();
                        }*/
                        
                        
                        if($completePercentage >= 100){ 
                            $this->assignBadgeForChallengeMilestonese($challenge_id, $v['id'], $user_id, "challenge_milestone", $timezone);

                        }
                     }
                
            }
        }

        //echo $coveredDistance; 

//echo $coveredDistance .'=='. $total_distance; 
        /*if($coveredDistance > $total_distance){
            $arr = array("status" => 3, "message" => "Please enter distance less than and equal to challenge distance.");
            return json_encode($arr);
        }else*/
       
        //if($coveredDistance >= $total_distance){

                
                
                

                foreach($badges as $key => $val){
                  
                  $badge_condition = $val['badge_condition'];
                  $condition_limit = $val['condition_limit'];
                      
                  if($val['badge_type'] == 'member_since'){

                    

                    $this->memberSinceBadgesAssign($challenge_id, $val['id'], $user_id, $badge_condition, $condition_limit, $val['badge_type'], $timezone);
                      

                  }elseif($val['badge_type'] == 'distance'){ //echo $val['badge_type']; 

                    $distancreTravel =DB::table('challenge_logs')->where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->groupBy('created_at')->groupBy('activity_id')->get();
                    $totalDistancreTravelArr = 0;
                    
                    if($distancreTravel){
                        foreach($distancreTravel as $key => $value){
                            
                           /* $startDateTime = Carbon::parse($value->startDateTime)->format('d/m/Y');
                            $distance_travelled = $value->distance_travelled;
                            $created_at = $value->created_at;*/
                            
                            $totalDistancreTravelArr = $totalDistancreTravelArr + $value->distance_travelled;
                            
                        }
                    }

                   // $totalDistancreTravel = array_sum($totalDistancreTravelArr);

                    $totalDistancreTravel = $totalDistancreTravelArr;

                    $totalDistancreTravel = $totalDistancreTravel + $distance;

                    if($challenge_id == 26){
                        //die($totalDistancreTravel.'=='.$total_distance);
                    }

                    $user_infos = User_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
                    $user_infos = json_encode($user_infos);
                    $user_infos = json_decode($user_infos, true);
                    $timezone = env('DEFAULT_TIMEZONE');
                    $redirectTime = 0;
                    if($user_infos){
                        $timezone = $user_infos['meta_value'];
                    }

                    $specific_date = $val['specific_date'];
                    $specific_date = Carbon::parse($specific_date)->setTimezone('UTC')->format('Y-m-d');

                    $start_date_time = Carbon::parse($start_date_time)->setTimezone('UTC')->format('Y-m-d');
                     
                    $specific_date_checkbox = @$val['specific_date_checkbox'] ? @$val['specific_date_checkbox'] : 0; 

                    
                    //die();
                    $coveredDistanceForDate = 0;
                    if(!$specific_date_checkbox){
                       
                    if($category == 'individual'){//echo $category; echo $challenge['price_type']; die();

                        
                         $this->distanceBadgesAssign($challenge_id, $val['id'], $user_id, $totalDistancreTravel, $condition_limit, $val['badge_type'], $coveredDistanceForDate, $timezone);
                            
                            
                               
                            if($totalDistancreTravel >= $total_distance){
                                    
                    
                           

                                $UserChallenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                $UserChallenges = json_encode($UserChallenges);
                                $UserChallenges = json_decode($UserChallenges, true);
                                $ucStatus = $UserChallenges['status'];

                                

                                if($challenge['price_type'] != 'default'){
                                    

                                    if($ucStatus != 2){
                                        $adminUser = user::where('user_type', 1)->first();
                                        $userName = user::where('id', $user_id)->first();
                                        Mail::to($adminUser)->send(new ChallengeCompletionUpdateIndividual($userName->name, $challenge['name'], $total_distance, $challenge['event_start_date'], $challenge['event_end_date']));
                                    }

                                               /*if($challenge_id == 26){

                        echo $totalDistancreTravel .'=='. $total_distance; die();
                    }*/

                                    /*     echo $ucStatus;
                                    if($challenge_id == 26){
                                        echo $totalDistancreTravel .'>='. $total_distance;die();
                                    }*/


                                    $User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                    $User_challenges->status = 2;
                                    $User_challenges->update();

                                }

                            }
                        

                    }else{
                        if($challenge['price_type'] != 'default'){
                            $distancreTravelAccu =DB::table('challenge_logs')->where('participation_id', $challenge_id)->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
                            $totaldistancreTravelAccuArr = 0;
                            
                            if($distancreTravelAccu){
                                foreach($distancreTravelAccu as $key => $value){
                                    
                                   /* $startDateTime = Carbon::parse($value->startDateTime)->format('d/m/Y');
                                    $distance_travelled = $value->distance_travelled;
                                    $created_at = $value->created_at;*/
                                    
                                    $totaldistancreTravelAccuArr = $totaldistancreTravelAccuArr + $value->distance_travelled;
                                    
                                }
                            }

                            //$totalDistancreTravelAccu = array_sum($totaldistancreTravelAccuArr);
                            $totalDistancreTravelAccu = $totaldistancreTravelAccuArr;

                            $totalDistancreTravelAccu = $totalDistancreTravelAccu + $distance;

                            $this->distanceBadgesAssign($challenge_id, $val['id'], $user_id, $totalDistancreTravelAccu, $condition_limit, $val['badge_type'], $coveredDistanceForDate, $timezone);

                            if($totalDistancreTravelAccu >= $total_distance){

                                

                                $UserChallenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                $UserChallenges = json_encode($UserChallenges);
                                $UserChallenges = json_decode($UserChallenges, true);
                                $ucStatus = $UserChallenges['status'];

                                if($ucStatus != 2){
                                    $adminUser = user::where('user_type', 1)->first();
                                    Mail::to($adminUser)->send(new ChallengeCompletionUpdateAccumulative($challenge['name'], $total_distance, $challenge['event_start_date'], $challenge['event_end_date']));
                                }

                                $User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                $User_challenges->status = 2;
                                $User_challenges->update();

                                $Challenges = Challenges::where([['id', '=', $challenge_id]])->first();
                                $Challenges->status = 2;
                                $Challenges->update();
                            }

                        }
                        
                        
                      
                    }

                        //if(!$specific_date_checkbox){ //echo $specific_date .'<='. Carbon::now($timezone)->toDateTimeString(); die();
                            
                        //}
                    
                    }elseif($specific_date_checkbox && $specific_date == $start_date_time){ 
                        /*if($val['id'] == 18){
                            echo $specific_date_checkbox .'&&'. $specific_date .'=='. $start_date_time;  echo '<br>';
                        }*/
                        
                        $start_date = Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->startOfDay();
                        $end_date = Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->endOfDay();
                        
                        /*if($val['id'] == 18){
                            echo $start_date; echo $end_date; echo '<br>'; die();
                        
                        }*/
                        //if($category == 'individual'){
                            $challengeLogForDate = DB::table('challenge_logs')->select('challenge_logs.*')
                             //->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
                             ->where([['challenge_logs.user_id', '=', $user_id]])
                             ->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
                             ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
                             ->get();
                         /*}else{
                            $challengeLogForDate = DB::table('challenge_logs')->select('challenge_logs.*')
                             ->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
                             ->where([['user_challenges.challenge_id', '=', $challenge_id]])
                             ->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
                             ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
                             ->get();
                         }*/
                        
                        $challengeLogForDate = json_encode($challengeLogForDate);
                        $challengeLogForDate = json_decode($challengeLogForDate, true);
                 
                        
                        if($challengeLogForDate){
                            foreach($challengeLogForDate as $key => $va){
                                
                                    $coveredDistanceForDate = $coveredDistanceForDate + $va['distance_travelled'];
                                
                            }

                        }
                        
                        $coveredDistanceForDate = $coveredDistanceForDate + $distance;
                        //echo $coveredDistanceForDate.'>='.$condition_limit; die();
                        $this->distanceBadgesAssignOnSpecificDate($challenge_id, $val['id'], $user_id, $coveredDistanceForDate, $condition_limit, $val['badge_type'], $coveredDistanceForDate, $timezone);
                        
                    }
                    /*elseif($totalDistancreTravel >= $condition_limit){


                        
                        if(!$specific_date_checkbox){ //echo $specific_date .'<='. Carbon::now($timezone)->toDateTimeString(); die();
                            $this->distanceBadgesAssign($challenge_id, $val['id'], $user_id, $totalDistancreTravel, $condition_limit, $val['badge_type'], $coveredDistanceForDate, $timezone);
                        }
                        //}
                    }*/
                      

                  }elseif($val['badge_type'] == 'challenge'){

                       $this->challengeBadgesAssign($challenge_id, $val['id'], $user_id, $condition_limit, $val['badge_type'], $timezone);
                      
                  }
                }
                $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
                return json_encode($arr);
            
            
        /*}else{

            

            foreach($badges as $key => $val){
              
              $badge_condition = $val['badge_condition'];
              $condition_limit = $val['condition_limit'];

              if($val['badge_type'] == 'member_since'){

                  $this->memberSinceBadgesAssign($challenge_id, $val['id'], $user_id, $badge_condition, $condition_limit, $val['badge_type'], $timezone);
                  

              }elseif($val['badge_type'] == 'distance'){

                    $specific_date_checkbox = @$val['specific_date_checkbox'];
                        $coveredDistanceForDate = 0;
                        if($specific_date_checkbox){
                            $specific_date = $val['specific_date'];
                            $start_date = Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->startOfDay();
                            $end_date = Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->endOfDay();
                            if($category == 'individual'){
                                $challengeLogForDate = DB::table('challenge_logs')->select('challenge_logs.*')
                                 ->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
                                 ->where([['user_challenges.user_id', '=', $user_id]])
                                 ->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
                                 ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
                                 ->get();
                             }else{
                                $challengeLogForDate = DB::table('challenge_logs')->select('challenge_logs.*')
                                 ->join('user_challenges', "challenge_logs.participation_id", '=', 'user_challenges.challenge_id')
                                 ->where([['user_challenges.challenge_id', '=', $challenge_id]])
                                 ->whereBetween('challenge_logs.startDateTime', array($start_date, $end_date))
                                 ->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
                                 ->get();
                             }
                            
                            $challengeLogForDate = json_encode($challengeLogForDate);
                            $challengeLogForDate = json_decode($challengeLogForDate, true);
                     
                            
                            if($challengeLogForDate){
                                foreach($challengeLogForDate as $key => $va){
                                    
                                        $coveredDistanceForDate = $coveredDistanceForDate + $va['distance_travelled'];
                                    
                                }
                                $coveredDistanceForDate = $coveredDistanceForDate + $distance;

                            }
                            //echo $coveredDistanceForDate; die();
                            $this->distanceBadgesAssignOnSpecificDate($challenge_id, $val['id'], $user_id, $coveredDistance, $condition_limit, $val['badge_type'], $coveredDistanceForDate, $timezone);
                        }else{
                            $this->distanceBadgesAssign($challenge_id, $val['id'], $user_id, $coveredDistance, $condition_limit, $val['badge_type'], $coveredDistanceForDate, $timezone);
                        }

                  
                  

              }elseif($val['badge_type'] == 'challenge'){

                   $this->challengeBadgesAssign($challenge_id, $val['id'], $user_id, $condition_limit, $val['badge_type'], $timezone);
                  
              }
            }
            $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
            return json_encode($arr);
        }*/
    }

    public function assignBadgeForChallengeMilestonese($challenge_id, $badge_id, $user_id, $badge_type, $timezone){

        $badge = User_badges::where([['badge_type', '=', $badge_type], ['badge_id', '=', @$badge_id], ['user_id', '=', $user_id]])->get();
        $badge = json_encode($badge);
        $badge = json_decode($badge, true);  
        
        if(!$badge){
            $User_badges = new User_badges();
            $User_badges->challenge_id = $challenge_id;
            $User_badges->badge_id = @$badge_id;
            $User_badges->user_id = $user_id;
            $User_badges->badge_type = @$badge_type;
            $User_badges->assign_date = Carbon::now($timezone)->toDateTimeString();
            $User_badges->status = 1;
            $User_badges->save();

            $adminUser = user::where('id', $user_id)->first();
            $badge_name = Challenge_milestones::where('id', $badge_id)->first();
            Mail::to($adminUser)->send(new SuccessfulBadgeAchievement($adminUser->name, $badge_name->milestone_name, route('frontend.challenge_details', $challenge_id)));
        }
        $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
            return json_encode($arr);

    }

    public function memberSinceBadgesAssign($challenge_id, $badge_id, $user_id, $badge_condition, $condition_limit, $badge_type, $timezone){

        $user = User::where('id', $user_id)->first();
        $user = json_encode($user);
        $user = json_decode($user, true); 

        if($user){
            $created_at = $user['created_at'];

            if($badge_condition == 'month'){
                $calculateMonth = $this->calculateMonth($created_at, $timezone);
                if($calculateMonth >= $condition_limit){
                    $this->assignBadgeForMemberSince($badge_id, $user_id, $badge_type, $timezone);
                    
                }

            }elseif($badge_condition == 'year'){
                $calculateYear = $this->calculateYear($created_at, $timezone);
                if($calculateYear >= $condition_limit){
                    $this->assignBadgeForMemberSince($badge_id, $user_id, $badge_type, $timezone);
                    
                }

            }
        }

        $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
            return json_encode($arr);
        

    }

    public function distanceBadgesAssign($challenge_id, $badge_id, $user_id, $coveredDistance, $condition_limit, $badge_type, $coveredDistanceForDate, $timezone){
        
        if($coveredDistance >= $condition_limit){ 
            $this->assignBadge($challenge_id, $badge_id, $user_id, $badge_type, $timezone);
            
        }
        

        $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
            return json_encode($arr);

    }

    public function distanceBadgesAssignOnSpecificDate($challenge_id, $badge_id, $user_id, $coveredDistance, $condition_limit, $badge_type, $coveredDistanceForDate, $timezone){
        if(@$coveredDistanceForDate){
            if($coveredDistanceForDate >= $condition_limit){ 
                $this->assignBadge($challenge_id, $badge_id, $user_id, $badge_type, $timezone);
                
            }
        }

        $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
            return json_encode($arr);

    }

    public function challengeBadgesAssign($challenge_id, $badge_id, $user_id, $condition_limit, $badge_type, $timezone){
        $User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id], ['status', '=', 2]])->get();
        $User_challenges = json_encode($User_challenges);
        $User_challenges = json_decode($User_challenges, true);  
        if(count($User_challenges) >= $condition_limit){
            $this->assignBadge($challenge_id, $badge_id, $user_id, $badge_type, $timezone);            
            
        }

        $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
            return json_encode($arr);

    }

    public function assignBadge($challenge_id, $badge_id, $user_id, $badge_type, $timezone){

        $badge = User_badges::where([['badge_id', '=', @$badge_id], ['user_id', '=', $user_id], ['badge_type', '!=', 'challenge_milestone']])->first();
        $badge = json_encode($badge);
        $badge = json_decode($badge, true);

        
        
        if(!$badge){
            $User_badges = new User_badges();
            $User_badges->challenge_id = 0;
            $User_badges->badge_id = @$badge_id;
            $User_badges->user_id = $user_id;
            $User_badges->badge_type = @$badge_type;
            $User_badges->assign_date = Carbon::now($timezone)->toDateTimeString();
            $User_badges->status = 1;
            $User_badges->save();

            $adminUser = user::where('id', $user_id)->first();
            $badge_name = Badges::where('id', $badge_id)->first();
            Mail::to($adminUser)->send(new SuccessfulBadgeAchievement($adminUser->name, $badge_name->badge_name, route('frontend.Achievement')));
        }else{
            $User_badges = User_badges::where([['id', '=', $badge['id']], ['status', '=', 1]])->first();
            $User_badges->challenge_id = 0;
            $User_badges->badge_id = @$badge_id;
            $User_badges->user_id = $user_id;
            $User_badges->badge_type = @$badge_type;
            $User_badges->assign_date = Carbon::now($timezone)->toDateTimeString();
            $User_badges->status = 1;
            $User_badges->is_seen = 0;
            $User_badges->update();

            /*$adminUser = user::where('id', $user_id)->first();
            $badge_name = Badges::where('id', $badge_id)->first();
            Mail::to($adminUser)->send(new SuccessfulBadgeAchievement($adminUser->name, $badge_name->badge_name, route('frontend.Achievement')));*/
        }
        $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
            return json_encode($arr);

    }

    public function assignBadgeForMemberSince($badge_id, $user_id, $badge_type, $timezone){

        $badge = User_badges::where([['badge_type', '=', $badge_type], ['badge_id', '=', @$badge_id], ['user_id', '=', $user_id], ['badge_type', '!=', 'challenge_milestone']])->first();
        $badge = json_encode($badge);
        $badge = json_decode($badge, true);  
        
        if(!$badge){
            $User_badges = new User_badges();
            $User_badges->challenge_id = 0;
            $User_badges->badge_id = @$badge_id;
            $User_badges->user_id = $user_id;
            $User_badges->badge_type = @$badge_type;
            $User_badges->assign_date = Carbon::now($timezone)->toDateTimeString();
            $User_badges->status = 1;
            $User_badges->save();

            $adminUser = user::where('id', $user_id)->first();
            $badge_name = Badges::where('id', $badge_id)->first();
            Mail::to($adminUser)->send(new SuccessfulBadgeAchievement($adminUser->name, $badge_name->badge_name, route('frontend.Achievement')));
        }else{
            $User_badges = User_badges::where([['id', '=', $badge['id']], ['status', '=', 1]])->first();
            $User_badges->challenge_id = 0;
            $User_badges->badge_id = @$badge_id;
            $User_badges->user_id = $user_id;
            $User_badges->badge_type = @$badge_type;
            $User_badges->assign_date = Carbon::now($timezone)->toDateTimeString();
            $User_badges->status = 1;
            $User_badges->is_seen = 0;
            $User_badges->update();

            /*$adminUser = user::where('id', $user_id)->first();
            $badge_name = Badges::where('id', $badge_id)->first();
            Mail::to($adminUser)->send(new SuccessfulBadgeAchievement($adminUser->name, $badge_name->badge_name, route('frontend.Achievement')));*/
        }
        $arr = array("status" => 1, "message" => "Badges will assign if conditions matches.");
            return json_encode($arr);

    }

    public function calculateMonth($start, $timezone){
        $dbDate = \Carbon\Carbon::parse($start, 'UTC')->setTimezone($timezone);
        $diffYears = \Carbon\Carbon::now($timezone)->diffInMonths($dbDate);

        return $diffYears;
    }

    public function calculateYear($start, $timezone){

        $dbDate = \Carbon\Carbon::parse($start, 'UTC')->setTimezone($timezone);
        $diffYears = \Carbon\Carbon::now($timezone)->diffInYears($dbDate);

        return $diffYears;

    }

    
}
