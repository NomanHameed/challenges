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
use App\Models\Mapmyrun_user_credentials;

class MapMyRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MapMyRun:Accesstoken:By:Referesh:Token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is use to generate new access token by refresh token!';

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
        
        $mmrCAll = Mapmyrun_user_credentials::get();
        $mmrCAll = json_encode($mmrCAll);
        $mmrCAll = json_decode($mmrCAll, true);
        if($mmrCAll){
            foreach($mmrCAll as $key => $val){
                
                if(Carbon::now()->toDateTimeString() > $val['expires_in']){
                    $user_infos = User_infos::where([['user_id', '=', $val['user_id']], ['meta_name', '=', 'timezone']])->first();
                    $user_infos = json_encode($user_infos);
                    $user_infos = json_decode($user_infos, true);
                    $timezone = env('DEFAULT_TIMEZONE');
                    if($user_infos){
                        $timezone = $user_infos['meta_value'];
                    }
                    $mmrC = Mapmyrun_user_credentials::where('user_id', $val['user_id'])->first();
                    $mmrC1 = json_encode($mmrC);
                    $mmrC1 = json_decode($mmrC1, true);
                    if($mmrC1){

                     
                     $refresh_token = $mmrC1['refresh_token'];

                     $url="https://www.mapmyfitness.com/v7.1/oauth2/access_token/";

                    $postfields = "grant_type=refresh_token&client_id=svflww2l4l6blqhp57ywbolklhg4auda&client_secret=cxmtyi7yk5beg4dshregy2gsim4zcu7pawyufs3mjjfmtcyb4ulneugcbyhwnzgz&refresh_token=".$refresh_token;

                    $headers = array('Content-Type: ' . 'application/x-www-form-urlencoded');

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $json = curl_exec ($ch);
                    $json = json_decode($json, true);

                    
                    
                    if(!@$json['error']){
                        $expires_in = $json['expires_in'];
                        $value = $expires_in;
                        $dt = Carbon::now();
                        $days = $dt->diffInDays($dt->copy()->addSeconds($value));
                        
                        $newDateTime = Carbon::now('UTC')->addDays($days);

                        
                            
                        $mmrC->user_id = $val['user_id'];
                        //$mmrC->athlete_id = $val['user_id'];
                        $mmrC->token_type = $json['token_type'];
                        $mmrC->access_token = $json['access_token'];
                        $mmrC->refresh_token = $json['refresh_token'];
                        $mmrC->expires_in = $newDateTime;
                        $mmrC->status = 1;
                        $mmrC->update();
                        
                        \Log::info("MapMyRun token generated: ".$val['user_id']);
                    }

                    $responsecode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    }
                }
            }
        }     

        \Log::info("MapMyRun token CRON job");
    }
}
