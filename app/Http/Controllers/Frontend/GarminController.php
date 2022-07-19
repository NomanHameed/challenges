<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Challenge_infos;
use App\Models\Challenge_logs;
use App\Models\FitbitUserCredential;
use App\Models\GarminUserCredential;
use App\Models\Strava_user_credentials;
use App\Services\GarminApiService;
use Carbon\Carbon;
use File;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\OAuth1\Client\Credentials\TokenCredentials;
use Session;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use View;
use App\Models\User_infos;

class GarminController extends Controller
{
	/**
     * View garmin connect page.
     *
    */
    public function garminConnect(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

            return redirect()->guest(route( 'frontend.home' ));

        }

        $arr = array();

        $input = $request->all();

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : config('app.timezone');

        $users = DB::table('users')->select('*')->where('id', auth()->user()->id)->get()->toArray();
        $user_info = DB::table('user_infos')->select('user_infos.*')->where('user_id', auth()->user()->id)->get();
        $user_info = json_encode($user_info);
        $user_info = json_decode($user_info, true);

        $address = '';
        $city = '';
        $state = '';
        $zip_code = '';
        $country = '';
        
        if($user_info){
            foreach($user_info as $key => $value){
            
               if(@$value['meta_name'] =='address'){
                   $address = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'city'){
                   $city = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] =='state'){
                   $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();
                    
                    if($sta){
                        $state = $sta->state_name.' ';
                    }
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'country'){
                   $country = @$value['meta_value'];
               }
               
            }
        }
        $Country_name = $country == 'usa' ? 'United States' : '';
        
        $address = trim($state).', '.$Country_name;

        #Current Week Data Count

        $distancreTravel = DB::table('challenge_logs')->where('user_id', auth()->user()->id)->get();
        $totalDistancreTravel = 0;
        if($distancreTravel){
            foreach($distancreTravel as $key => $value){
                $totalDistancreTravel = $totalDistancreTravel + $value->distance_travelled;
            }
        }

        if(@$input['challenge_status']){
            $challenge = DB::table('challenges')->select('challenges.*')
                         ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where([['user_challenges.user_id', '=', auth()->user()->id], ['user_challenges.status', '=', $input['challenge_status']]])->groupBy('user_challenges.challenge_id')->get()->toArray();

        }else{
            
            $challenge = DB::table('challenges')->select('challenges.*')
                         ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where('user_challenges.user_id', auth()->user()->id)->groupBy('user_challenges.challenge_id')->get()->toArray();
        }

        $Challenge_infos = new Challenge_infos;
        $Challenge_logs = new Challenge_logs;

        $current = 0;
        $past    = 0;

        if($challenge){
            foreach($challenge as $key => $value){

                $event_end_date = Carbon::parse($value->event_end_date, 'UTC')->setTimezone($timezone);
                if($event_end_date <= Carbon::now($timezone)->toDateTimeString()){
                    $past = $past+1;
                }elseif($event_end_date > Carbon::now($timezone)->toDateTimeString()){
                     
                    $current = $current+1;
                }

            }
        }

        $info = array();
        foreach($challenge as $k=>$val){
            $challenges_info = DB::table('challenge_infos')->select('*')->where('challenge_infos.challenge_id', $val->id)->get()->toArray();
            $info[$k]['challenges'] = $val;
            $info[$k]['challenge_info'] = $challenges_info;
        }

        $Strava_user = Strava_user_credentials::where('user_id', auth()->user()->id)->first();
        $Strava_user1 = json_encode($Strava_user);
        $Strava_user1 = json_decode($Strava_user1, true);

        $fitbit_user = FitbitUserCredential::where('user_id', auth()->user()->id)->first();
        $garmin_user = GarminUserCredential::where('user_id', auth()->user()->id)->first();

        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $address;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;
        $arr['Strava_user'] = $Strava_user1;
        $arr['fitbit_user'] = $fitbit_user;
        $arr['garmin_user'] = $garmin_user;

        return view('/frontend/garmin-connect', $arr);
        
    }

    public function garminAuth()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

            return redirect()->guest(route( 'frontend.home' ));

        }

        try {
            $config = array(
                'identifier' => config('services.garmin.client_id'),
                'secret' => config('services.garmin.client_secret'),
                'callback_uri' => config('services.garmin.redirect_url') 
            );

            $server = new GarminApiService($config);

            // Retreive temporary credentials from server 
            $temporaryCredentials = $server->getTemporaryCredentials();

            // Save temporary crendentials in session to use later to retreive authorization token
            session()->put('temporaryCredentials', $temporaryCredentials);

            // Get authorization link 
            $link = $server->getAuthorizationUrl($temporaryCredentials);

            return redirect($link);
            
        } catch (\Exception $e) {
            return redirect()->to(route('admin.garminConnect'))->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function garminCallback(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

            return redirect()->guest(route( 'frontend.home' ));

        }

        $input = $request->all();
        $oauth_token = $input['oauth_token'];
        $oauth_verifier = $input['oauth_verifier'];

        try {
            $config = array(
                'identifier' => config('services.garmin.client_id'),
                'secret' => config('services.garmin.client_secret'),
                'callback_uri' => config('services.garmin.redirect_url') 
            );

            $server = new GarminApiService($config);

            if(session()->get('temporaryCredentials')) {
                // Retrieve the temporary credentials we saved before
                $temporaryCredentials = session()->get('temporaryCredentials');
            } else {
                // Retreive temporary credentials from server 
                $temporaryCredentials = $server->getTemporaryCredentials();
            }
                
            // We will now retrieve token credentials from the server.
            $tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $oauth_token, $oauth_verifier);

            $identifier = $tokenCredentials->getIdentifier();
            $secret = $tokenCredentials->getSecret();

            // Get Garmin user id
            $userId = $server->getUserUid($tokenCredentials);

            $provider = GarminUserCredential::where('provider_id', '=', $userId)->get();
            $provider = json_encode($provider);
            $provider = json_decode($provider, true);

            if($provider) {
                return redirect()->to(route('admin.garminConnect'))->withInput()->withErrors(['error' => 'Device is already registered on other user. Please use another device to connect.']);
            }

            $expires_at = null;
            $expires_at1 = Carbon::createFromTimestamp($expires_at)->format('Y-m-d H:i:s');

            $expires_in = null;
            $expires_in1 = Carbon::createFromTimestamp($expires_in)->format('H:i:s');

            $garmin_user = GarminUserCredential::where([['user_id', '=', auth()->user()->id], ['provider_id', '=', $userId]])->first();

            if($garmin_user) {
                $garmin_user->user_id = auth()->user()->id;
                $garmin_user->provider_id = $userId;
                $garmin_user->token_type = null;
                $garmin_user->refresh_token = null;
                $garmin_user->access_token = null;
                $garmin_user->expires_at = null;
                $garmin_user->expires_in = null;
                $garmin_user->identifier = $identifier;
                $garmin_user->secret = $secret;
                $garmin_user->status = 1;
                $garmin_user->update();
            } else {
                $garmin_user_credential = new GarminUserCredential();
                $garmin_user_credential->user_id = auth()->user()->id;
                $garmin_user_credential->provider_id = $userId;
                $garmin_user_credential->token_type = null;
                $garmin_user_credential->refresh_token = null;
                $garmin_user_credential->access_token = null;
                $garmin_user_credential->expires_at = null;
                $garmin_user_credential->expires_in = null;
                $garmin_user_credential->identifier = $identifier;
                $garmin_user_credential->secret = $secret;
                $garmin_user_credential->status = 1;
                $garmin_user_credential->save();
            }

            return redirect()->to(route('admin.garminConnect'))->withInput()->with(['message' => 'Now you are successfully connected with garmin.']);

        } catch (\Exception $e) {
            return redirect()->to(route('admin.garminConnect'))->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function garminDisconnect()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

            return redirect()->guest(route( 'frontend.home' ));

        }

        try {
            $config = array(
                'identifier' => config('services.garmin.client_id'),
                'secret' => config('services.garmin.client_secret'),
                'callback_uri' => config('services.garmin.redirect_url') 
            );

            $server = new GarminApiService($config);
            $garmin_user = GarminUserCredential::where('user_id', auth()->user()->id)->first();

            if(!$garmin_user) {
                return redirect()->to(route('admin.garminConnect'))->withInput()->withErrors(['error' => 'you are not connected with garmin.']);
            }

            $identifier = $garmin_user->identifier;
            $secret = $garmin_user->secret;

            // recreate tokenCredentials from identifier and secret
            $tokenCredentials = new TokenCredentials();
            $tokenCredentials->setIdentifier($identifier);
            $tokenCredentials->setSecret($secret);

            // Deregistration
            $server->deleteUserAccessToken($tokenCredentials);

            sleep(5);
            
            $str = GarminUserCredential::find($garmin_user->id);
            if($str) {
                $str->delete();
            }

            return redirect()->to(route('admin.garminConnect'))->withInput()->with(['message' => 'Now you are disconnected with garmin.']);

        } catch (\Exception $e) {
            return redirect()->to(route('admin.garminConnect'))->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function garminNotifications(Request $request)
    {
        \Log::info('GarminLogsStart');
        \Log::info($request->all());
        \Log::info('GarminLogsEnd');

        $data = $request->all();
        if(!empty($data)) {
            if(isset($data['activities'])) {
                foreach ($data['activities'] as $key => $activity) {
                    $userId = $activity['userId'];
                    $userAccessToken = $activity['userAccessToken'];
                    $activityId = $activity['activityId'];
                    $activityName = $activity['activityName'];
                    $startTimeInSeconds = $activity['startTimeInSeconds'];
                    $endTime = gmdate("H:i:s", @$activity['durationInSeconds']);
                    
                    // $startTimeOffsetInSeconds = $activity['startTimeOffsetInSeconds'];
                    $activityType = $activity['activityType'];
                    // $deviceName = $activity['deviceName'];
                    $distance = 0;
                    if(@$activity['distanceInMeters']){
                        $distanceInMeters = $activity['distanceInMeters'];
                        $distance = $distanceInMeters/1609; //distance in miles
                    }
                    $device_name = 'garmin';

                    $garmin_user = GarminUserCredential::where('provider_id', $userId)->get();
                    $garmin_user1 = json_encode($garmin_user);
                    $garmin_user1 = json_decode($garmin_user1, true);

                    if(!empty($garmin_user1)) {
                        foreach($garmin_user1 as $key1 => $val) {
                            $user_id = $val['user_id'];

                            $user_infos = User_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
                            $user_infos = json_encode($user_infos);
                            $user_infos = json_decode($user_infos, true);
                            $timezone = env('DEFAULT_TIMEZONE');
                            
                            if($user_infos){
                                $timezone = $user_infos['meta_value'];
                                
                            }


                            $startTime = Carbon::createFromTimestamp($startTimeInSeconds)->format('Y-m-d H:i:s');

                            //$startTime = Carbon::parse($startTime, $timezone)->setTimezone('UTC');

                            $identifier = $val['identifier']; //userAccessToken
                            $secret = $val['secret'];

                            $challenge = DB::table('user_challenges')->select('user_challenges.*')->where('user_challenges.status', 1)->where('user_challenges.user_id', $user_id)->get();
                            $challenge = json_encode($challenge);
                            $challenge = json_decode($challenge, true);
                            
                            $inputData = array();
                            foreach($challenge as $key2 => $vale) {
                                $challenge_logs = DB::table('challenge_logs')->select('challenge_logs.*')->where([['challenge_logs.user_id', '=', $user_id], ['challenge_logs.participation_id', '=', $vale['challenge_id']], ['challenge_logs.activity_id', '=', $activityId], ['challenge_logs.device_name', '=', $device_name]])->get();
                                $challenge_logs = json_encode($challenge_logs);
                                $challenge_logs = json_decode($challenge_logs, true);
                                
                                if(!$challenge_logs) {

                                    $ChallengeInfos = new Challenge_infos();
                                    $check = $ChallengeInfos->checkChallengeMilestone($vale['challenge_id'], round($distance, 2), $user_id, $startTime);
                                    $check = json_decode($check, true);

                                    if($check['status'] != 2){
                                        $inputData1 = array();
                                        $inputData1['user_id'] = $user_id;
                                        $inputData1['participation_id'] = $vale['challenge_id'];
                                        $inputData1['activity'] = $activityType;
                                        $inputData1['startDateTime'] = $startTime;
                                        $inputData1['endTime'] = $endTime;
                                        //$inputData1['calories'] = $calories;
                                        $inputData1['device_name'] = $device_name;
                                        $inputData1['athlete'] = $userId;
                                        $inputData1['activity_id'] = $activityId;
                                        $inputData1['distance_travelled'] = round($distance, 2);

                                        $inputData[] = $inputData1;
                                    }
                                }
                            }
                            if($inputData){
                                DB::table('challenge_logs')->insert($inputData);
                            }
                        }
                    }
                }
            }
        }

        \Log::info($request->all());

        return response('EVENT_RECEIVED', Response::HTTP_OK);
    }

    public function garminDeregistration(Request $request)
    {
        \Log::info($request->all());
        
        $data = $request->all();
        
        $config = array(
            'identifier' => config('services.garmin.client_id'),
            'secret' => config('services.garmin.client_secret'),
            'callback_uri' => config('services.garmin.redirect_url') 
        );

        $server = new GarminApiService($config);

        if(isset($data['deregistrations'])) {
            foreach ($data['deregistrations'] as $key => $value) {
                $userId = $value['userId'];
                $userAccessToken = $value['userAccessToken'];

                $garmin_user = GarminUserCredential::where('provider_id', '=', $userId)->where('identifier', '=', $userAccessToken)->first();
                if($garmin_user) {
                    $str = GarminUserCredential::find($garmin_user->id);
                    $str->delete();
                }
            }
        }

        $res = array("message" => 'Now you are disconnected with garmin.');
        return response()->json($res, 200);
    }
}