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
use Illuminate\Support\Facades\Mail;
use App\Mail\ChallengeCompletionUpdateIndividual;
use App\Mail\ChallengeCompletionUpdateAccumulative;
use App\Mail\SuccessfulBadgeAchievement;

class CloseChallengeAfterUserCompleteMiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Close:Challenge:After:User:Complete:Miles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It used to close competition after user complete his miles.';

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

            /*$distancreTravel =DB::table('challenge_logs')->where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->get();

            $distancreTravel = json_encode($distancreTravel);
            $distancreTravel = json_decode($distancreTravel, true);
            //print_r($distancreTravel); //die();
            
            if($distancreTravel){
                foreach($distancreTravel as $key => $value){
                    
                    $totalDistancreTravel = $totalDistancreTravel + $value['distance_travelled'];
                    
                }
            }*/

            $totalDistancreTravel =DB::table('challenge_logs')->where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->sum('challenge_logs.distance_travelled');

            

            $user_infos = user_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
            $user_infos = json_encode($challenges_info);
            $user_infos = json_decode($challenges_info, true);
            
            $timezone = $admin_timezone;
            if($user_infos){
                $timezone = $user_infos['meta_value'];
            }
            
            $challenges = challenges::where('id', $challenge_id)->first();
            $challenges = json_encode($challenges);
            $challenge = json_decode($challenges, true);

            
            
            if($challenge){

                
                if($category == 'individual'){
                               
                        if($totalDistancreTravel >= $total_distance){

                            if($challenge['price_type'] != 'default'){
                                

                                if($ucStatus != 2){
                                    
                                    $userName = user::where('id', $user_id)->first();
                                    //Mail::to($adminUser)->send(new ChallengeCompletionUpdateIndividual($userName->name, $challenge['name'], $total_distance, $challenge['event_start_date'], $challenge['event_end_date']));
                                }

                                $User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                $User_challenges->status = 2;
                                $User_challenges->update();

                            }

                        }else{
                            if($challenge['price_type'] != 'default'){

                                $User_challenges = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $user_id]])->first();
                                $User_challenges->status = 1;
                                $User_challenges->update();

                            }
                        }
                    

                }else{
                    if($challenge['price_type'] != 'default'){
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
                            $User_challenges = User_challenges::where('challenge_id', $challenge_id)->update(["status" => 1]);

                            $Challenges = Challenges::where([['id', '=', $challenge_id]])->first();
                            $Challenges->status = 1;
                            $Challenges->update();
                        }

                    }
                    
                    
                  
                }
                
                
            }
            
        }

        \Log::info("It used to close competition after user complete his miles.");

    }
}
