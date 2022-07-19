<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User_challenges;
use App\Models\Badges;
use App\Models\User_badges;
use Carbon\Carbon;
use App\User;
use App\Models\User_infos;
use App\Models\Challenges;
use App\Models\Challenge_infos;
use App\Models\Challenge_logs;
use Helper;
use App\Mail\ChallengeCompletionUpdateIndividual;
use App\Mail\ChallengeCompletionUpdateAccumulative;
use App\Mail\SuccessfulBadgeAchievement;

class CloseCompetitionAfterEndDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Close:Competition:AfterEndDate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It used to close competition after end date.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $admin_timezone = admin_timezone();
        $adminUser = user::where('user_type', 1)->first();
        
        $user_challenges = user_challenges::get();
        $total_rows = $user_challenges->count();

        $cron_job_checks = DB::table('cron_job_checks')->select('cron_job_checks.*')
                         ->where([['command_name', '=', 'Close:Competition:AfterEndDate']])
                         ->first();
        $cron_job_checks = json_encode($cron_job_checks);
        $cron_job_checks = json_decode($cron_job_checks, true);
        
        // Set a block size
        $block_size   = $cron_job_checks['block_size'];

        // Init starting offset
        $block_offset = $cron_job_checks['offset'];

        //for($block = $block_offset; $block < $total_rows; $block = $block + $block_size) {

        $user_challenges = user_challenges::skip($block_offset)->take($block_size)->get();
        //$total_rows = $user_challenges->count();
        $user_challenges = json_encode($user_challenges);
        $user_challenges = json_decode($user_challenges, true);
        foreach($user_challenges as $key => $value){ 

            $challenge_id = $value['challenge_id'];
            $user_id = $value['user_id'];
            $ucStatus = $value['status'];
            $total_distance = 0;
            $category = 0;
            $totalDistancreTravel = 0;

            $challengeInfo = DB::table('challenge_infos')->select('challenge_infos.*')
                         //->join('user_challenges', "challenge_infos.challenge_id", '=', 'user_challenges.challenge_id')
                         ->where([['challenge_infos.challenge_id', '=', $challenge_id]])
                         //->groupBy('challenge_logs.created_at')->groupBy('challenge_logs.activity_id')
                         ->get();
            $challengeInfo = json_encode($challengeInfo);
            $challengeInfo = json_decode($challengeInfo, true);
            
            
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

            /*$distancreTravel =DB::table('challenge_logs')->where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->groupBy('created_at')->groupBy('activity_id')->get();

            $distancreTravel = json_encode($distancreTravel);
            $distancreTravel = json_decode($distancreTravel, true);
            
            if($distancreTravel){
                foreach($distancreTravel as $key => $value){
                    
                    $totalDistancreTravel = $totalDistancreTravel + $value['distance_travelled'];
                    
                }
            }*/

            $totalDistancreTravel =DB::table('challenge_logs')->where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->sum('challenge_logs.distance_travelled');

            

            $user_infos = user_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
            $user_infos = json_encode($user_infos);
            $user_infos = json_decode($user_infos, true);
            
            $timezone = $admin_timezone;
            if($user_infos){
                $timezone = $user_infos['meta_value'];
            }
            
            $challenges = challenges::where('id', $challenge_id)->first();
            $challenges = json_encode($challenges);
            $challenges = json_decode($challenges, true);
            
            if($challenges){

                
                $event_end_date = $challenges['event_end_date'];
                $newDate = '';
                if($event_end_date){
                    $tempDate = explode(' ', $event_end_date);
                    $tempDate = explode('-', $tempDate['0']);
                    $stat = checkdate($tempDate[1], $tempDate[2], (int)$tempDate[0]);
                    if($stat){ 
                        $event_end_date = Carbon::parse($event_end_date, 'UTC')->setTimezone($admin_timezone);
                        if($event_end_date < Carbon::now($timezone)->toDateTimeString()){
      
                            $challenge = Challenges::where("id", $challenge_id)->first();
                            $challenge->status = 2;
                            $challenge->update();

                            //$User_challenges1 = DB::table('user_challenges')->where('challenge_id', $challenge_id)->update(['status' => 2]);

                            $User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                            $User_challenges->status = 2;
                            $User_challenges->update();


                        }elseif($event_end_date > Carbon::now($timezone)->toDateTimeString()){

                            $challenge_logs = Challenge_logs::where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->get();
                            $challenge_logs = json_encode($challenge_logs);
                            $challenge_logs = json_decode($challenge_logs, true); 
                            if(!$challenge_logs){

                                $challenge = Challenges::where("id", $challenge_id)->first();
                                $challenge->status = 1;
                                $challenge->update();

                                $User_challenges2 = DB::table('user_challenges')->where([['user_id', '=', $user_id], ['challenge_id', '=', $challenge_id]])->update(['status' => 1]);


                            }

                            //open challenge when date extend or challenge miles not completed

                            if($category == 'individual'){
                               
                                if($totalDistancreTravel >= $total_distance){

                                    if($challenges['price_type'] != 'default'){
                                

                                        if($ucStatus != 2){
                                            
                                            $userName = user::where('id', $user_id)->first();
                                            //Mail::to($adminUser)->send(new ChallengeCompletionUpdateIndividual($userName->name, $challenge['name'], $total_distance, $challenge['event_start_date'], $challenge['event_end_date']));
                                        }

                                        $User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                        $User_challenges->status = 2;
                                        $User_challenges->update();

                                    }

                                }else{
                                    if($challenges['price_type'] != 'default'){

                                        $User_challenges3 = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                        $User_challenges3->status = 1;
                                        $User_challenges3->update();

                                    }
                                }
                                

                            }else{
                                if($challenges['price_type'] != 'default'){
                                    /*$distancreTravelAccu =DB::table('challenge_logs')->where('participation_id', $challenge_id)->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
                                    $distancreTravelAccu = json_encode($distancreTravelAccu);
                                    $distancreTravelAccu = json_decode($distancreTravelAccu, true);
                                    $totaldistancreTravelAccuArr = 0;
                                    
                                    if($distancreTravelAccu){
                                        foreach($distancreTravelAccu as $key => $value){
                                            
                                            $totaldistancreTravelAccuArr = $totaldistancreTravelAccuArr + $value['distance_travelled'];
                                            
                                        }
                                    }*/

                                    $totalDistancreTravelAccu = DB::table('challenge_logs')->where([['participation_id', '=', $challenge_id]])->sum('challenge_logs.distance_travelled');

                                    if($totalDistancreTravelAccu >= $total_distance){

                                        if($ucStatus != 2){
                                            //$adminUser = user::where('user_type', 1)->first();
                                           // Mail::to($adminUser)->send(new ChallengeCompletionUpdateAccumulative($challenge['name'], $total_distance, $challenge['event_start_date'], $challenge['event_end_date']));
                                        }

                                        $User_challenges = User_challenges::where('challenge_id', $challenge_id)->update(["status" => 2]);

                                        $Challenges = Challenges::where([['id', '=', $challenge_id]])->first();
                                        $Challenges->status = 2;
                                        $Challenges->update();
                                    }else{
                                        $User_challenges4 = User_challenges::where('challenge_id', $challenge_id)->update(["status" => 1]);

                                        $ChallengeStatus = Challenges::where([['id', '=', $challenge_id]])->first();
                                        $ChallengeStatus->status = 1;
                                        $ChallengeStatus->update();
                                    }

                                }
                                
                                
                              
                            }
                            

                        }
                    }else{
                        //open challenge when date extend or challenge miles not completed

                            if($category == 'individual'){
                               
                                if($totalDistancreTravel >= $total_distance){

                                    if($challenges['price_type'] != 'default'){
                                

                                        if($ucStatus != 2){
                                            
                                            $userName = user::where('id', $user_id)->first();
                                            //Mail::to($adminUser)->send(new ChallengeCompletionUpdateIndividual($userName->name, $challenge['name'], $total_distance, $challenge['event_start_date'], $challenge['event_end_date']));
                                        }

                                        $User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                        $User_challenges->status = 2;
                                        $User_challenges->update();

                                    }

                                }else{
                                    if($challenges['price_type'] != 'default'){

                                        $User_challenges3 = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                        $User_challenges3->status = 1;
                                        $User_challenges3->update();

                                    }
                                }
                                

                            }else{
                                if($challenges['price_type'] != 'default'){
                                    /*$distancreTravelAccu =DB::table('challenge_logs')->where('participation_id', $challenge_id)->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
                                    $distancreTravelAccu = json_encode($distancreTravelAccu);
                                    $distancreTravelAccu = json_decode($distancreTravelAccu, true);
                                    $totaldistancreTravelAccuArr = 0;
                                    
                                    if($distancreTravelAccu){
                                        foreach($distancreTravelAccu as $key => $value){
                                            
                                            $totaldistancreTravelAccuArr = $totaldistancreTravelAccuArr + $value['distance_travelled'];
                                            
                                        }
                                    }

                                    $totalDistancreTravelAccu = $totaldistancreTravelAccuArr;*/

                                    $totalDistancreTravelAccu = DB::table('challenge_logs')->where([['participation_id', '=', $challenge_id]])->sum('challenge_logs.distance_travelled');


                                    if($totalDistancreTravelAccu >= $total_distance){

                                        if($ucStatus != 2){
                                            //$adminUser = user::where('user_type', 1)->first();
                                           // Mail::to($adminUser)->send(new ChallengeCompletionUpdateAccumulative($challenge['name'], $total_distance, $challenge['event_start_date'], $challenge['event_end_date']));
                                        }

                                        $User_challenges = User_challenges::where('challenge_id', $challenge_id)->update(["status" => 2]);

                                        $Challenges = Challenges::where([['id', '=', $challenge_id]])->first();
                                        $Challenges->status = 2;
                                        $Challenges->update();
                                    }else{
                                        $User_challenges4 = User_challenges::where('challenge_id', $challenge_id)->update(["status" => 1]);

                                        $ChallengeStatus = Challenges::where([['id', '=', $challenge_id]])->first();
                                        $ChallengeStatus->status = 1;
                                        $ChallengeStatus->update();
                                    }

                                }
                                
                                
                              
                            }
                    }
                }
                
                
            }
            
        }
         // Update block offset, so offset increments by block size (300)  
          $block_offset = $block_offset + $block_size;

          
          $pageData = '';

          if($block_offset >= $total_rows){
            $pageData = array("total" => $total_rows, "offset" => 0);

          }else{
            $pageData = array("total" => $total_rows, "offset" => $block_offset);
          }

        $cron_job_checks = DB::table('cron_job_checks')
                         ->where([['command_name', '=', 'Close:Competition:AfterEndDate']])
                         ->update($pageData);

       // }

        \Log::info($block_offset. "close competition after end date");

    }
}
