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

class RefrshTokenGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Strava:Referesh:Token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is use to generate strava new access token by refresh token!';

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
        $i = 0;
        foreach($Strava_user1 as $key => $val){
            if(Carbon::now()->toDateTimeString() > $val['expires_at'])
            {
                $client = new Client();

                $Strava = new Strava(config('ct_strava.client_id'), config('ct_strava.client_secret'), config('ct_strava.redirect_uri'), $client);
                $refresh = $Strava->refreshToken($val['refresh_token']);
                
                $expires_at = $refresh->expires_at;
        
                $expires_at1 = Carbon::createFromTimestamp($expires_at)->format('Y-m-d H:i:s');   
                
                $expires_in = $refresh->expires_in;
                
                $expires_in1 = Carbon::createFromTimestamp($expires_in)->format('H:i:s');

                
                Strava_user_credentials::where('user_id', $val['user_id'])->update([
                  'access_token' => $refresh->access_token,
                  'expires_at' => $expires_at1,
                  'expires_in' => $expires_in1,
                  'refresh_token' => $refresh->refresh_token
                ]);

                $i++;

            }
        }

        \Log::info("Strava token CRON job ".$i);
    }
}
