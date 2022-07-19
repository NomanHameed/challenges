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
use CodeToad\Strava\Strava;
use App\Models\Strava_user_credentials;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class StravaLogAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Strava:Log:Add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is use to add new logs!';

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

        $Strava_user = Strava_user_credentials::get();
        $Strava_user1 = json_encode($Strava_user);
        $Strava_user1 = json_decode($Strava_user1, true);
        foreach($Strava_user1 as $key => $val){
            if($val['access_token'])
            {
                $client = new Client();
                
                $access_token = $val['access_token'];
                
                $perPage = 2;
                $dataCount = 1;
                $date = Carbon::now(); // or whatever you're using to set it
                $start = strtotime(Carbon::now()->subDay());
                $end = strtotime(Carbon::now()->toDateTimeString());
                $data = array();

                for($i=0;$i<$dataCount;$i++){
                    $page = $i+1;
                    $Strava = new Strava(config('ct_strava.client_id'), config('ct_strava.client_secret'), config('ct_strava.redirect_uri'), $client);
                    $stravaActivities = $Strava->activities($access_token, $page, $perPage, $end, $start);
                    $stravaActivities = json_encode($stravaActivities);
                    $stravaActivities = json_decode($stravaActivities, true);
                    
                    if($stravaActivities){
                        foreach($stravaActivities as $key=>$value){
                            $data[] = $value;
                        }

                        $dataCount++;
                        continue;
                    }

                }
                
                foreach($data as $key => $v){
                    $user_id = $val['user_id'];
                    $activity = $v['type'];
                    $id = $v['id'];
                    $athlete_id = $v['athlete']['id'];
                    $device_name = 'strava';
                    $start_date_local = $v['start_date'];
                    $startDateTime = Carbon::createFromTimestamp(strtotime($start_date_local))->format('Y-m-d H:i:s');
                    $endTime = gmdate("H:i:s", $v['moving_time']);
                    $distance = $v['distance'];
                    $distance = round($distance/1609.344, 2);

                    $challenge = DB::table('user_challenges')->select('user_challenges.*')
                        ->where('user_challenges.user_id', $user_id)->get();
                    $challenge = json_encode($challenge);
                    $challenge = json_decode($challenge, true);

                    foreach($challenge as $key => $vale){
                        $challenge_logs = DB::table('challenge_logs')->select('challenge_logs.*')
                        ->where([['challenge_logs.user_id', '=', $user_id], ['challenge_logs.participation_id', '=', $vale['challenge_id']], ['challenge_logs.activity_id', '=', $id], ['challenge_logs.device_name', '=', $device_name]])->get();
                        $challenge_logs = json_encode($challenge_logs);
                        $challenge_logs = json_decode($challenge_logs, true);
                        if(!$challenge_logs){
                            DB::table('challenge_logs')->insert([
                                'user_id' => $user_id,
                                'participation_id' => $vale['challenge_id'],
                                'activity' => $activity,
                                'startDateTime' => $startDateTime,
                                'endTime' => $endTime,
                                'device_name' => $device_name,
                                'athlete' => $athlete_id,
                                'activity_id' => $id,
                                'distance_travelled' => $distance
                            ]);
                        }
                    }

                }                

            }
        }

        \Log::info("It is use to add new logs!");

    }
}
