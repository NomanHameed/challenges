<?php

namespace App\Console\Commands;

use App\Models\FitbitUserCredential;
use App\Services\FitbitConnectionService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FitbitRefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Fitbit:Referesh:Token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It is used to generate new fitbit access token by refresh token!';

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
        $requestUrl = config('services.fitbit.api_base_url') . '/oauth2/token';
        $clientId = config('fitbit.connections.main.client_id');
        $clientSecret = config('fitbit.connections.main.client_secret');

        $fitbitConnection = new FitbitConnectionService();
        $fitbit_user = FitbitUserCredential::where('provider_id', '5L97PJ')->get();
        $fitbit_user1 = json_encode($fitbit_user);
        $fitbit_user1 = json_decode($fitbit_user1, true);



        if(!empty($fitbit_user1)) {
            foreach($fitbit_user1 as $key => $val) {
                if(Carbon::now()->toDateTimeString() > $val['expires_at']) {
                    $token_response = $fitbitConnection->refreshToken($requestUrl, $clientId, $clientSecret, $val['refresh_token']);
                    $token = json_decode($token_response, true);
		     
                    if ($token['errors'][0]['errorType'] == "invalid_grant") {
                    	            $deleteSubscriptionUrl = config('services.fitbit.api_base_url') . '/1/user/' . $val['provider_id'] . '/activities/apiSubscriptions/' . $val['user_id'] . '.json';
                    	            $subscription_response = $fitbitConnection->deleteSubscription($deleteSubscriptionUrl, $val['access_token']);
                    	            $token_response = $fitbitConnection->revokeToken($requestUrl, $clientId, $clientSecret, $val['access_token']);
				    $str = FitbitUserCredential::find($val['id']);
				    $str->delete();
                    }
                    
                    if(isset($token['user_id'])) {
                        $expires_at = $token['expires_in']/3600; // 28800/3600
                        $expires_at1 = Carbon::now()->addHours($expires_at);
                        
                        $expires_in = $token['expires_in'];
                        $expires_in1 = Carbon::createFromTimestamp($expires_in)->format('H:i:s');

                        FitbitUserCredential::where('id', $val['id'])->update([
                            'refresh_token' => $token['refresh_token'],
                            'access_token' => $token['access_token'],
                            'expires_at' => $expires_at1,
                            'expires_in' => $expires_in1,
                        ]);
                        
                        \Log::info($token['user_id']." fitbit access token generated.");
                    }
                }
            }
        }

        \Log::info("Fitbit token CRON job.");
    }
}
