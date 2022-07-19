<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Session;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;
use App\Models\User_infos;
use App\PasswordReset;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\PasswordResetRequest;
use App\Models\Challenge_logs;
use App\Models\Challenge_infos;
use App\Models\Challenges;
use App\Models\User_challenges;
use App\Models\Badges;
use App\Models\Challenge_milestones;
use Carbon\Carbon;
use File;
use CodeToad\Strava\Strava;
use App\Models\Strava_user_credentials;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Notifications\SendMonthlyLogs;
use App\Services\StravaWebhookService;
use Illuminate\Support\Facades\Log;

class StravaController extends Controller
{
    public function StravaSubscription(){
        $id = app(StravaWebhookService::class)->subscribe();

        if ($id) {
            echo "Successfully subscribed ID: {$id}";
        } else {
            echo 'Unable to subscribe';
        }

        //return 0;
    }

    public function StravaView(){
        $id = app(StravaWebhookService::class)->view();

        if ($id) {
            "Subscription ID: $id";
        } else {
            'Error or no subscription found';
        }

        //return 0;
    }

    public function WebhookPostData(Request $request){
        
        $input = $request->all();
    	$aspect_type = $input['aspect_type']; // "create" | "update" | "delete"
	    $event_time = $input['event_time']; // time the event occurred
	    $object_id = $input['object_id']; // activity ID | athlete ID
	    $object_type = $input['object_type']; // "activity" | "athlete"
	    $owner_id = $input['owner_id']; // athlete ID
	    $subscription_id = $input['subscription_id']; // push subscription ID receiving the event
	    $updates = $input['updates']; // activity update: {"title" | "type" | "private": true/false} ; app deauthorization: {"authorized": false}
	    $client = new Client();
        
        if($object_type == 'activity'){
        	$Strava_user = Strava_user_credentials::where('athlete_id', $owner_id)->get();
	        $Strava_user1 = json_encode($Strava_user);
	        $Strava_user1 = json_decode($Strava_user1, true);
	        foreach($Strava_user1 as $key => $val){
	            if($val['access_token'])
	            {
	                $client = new Client();
	                
	                $access_token = $val['access_token'];
	        	
		        	$Strava = new Strava(config('ct_strava.client_id'), config('ct_strava.client_secret'), route('frontend.getToken'), $client);
			        $stravaActivities = $Strava->activity($access_token, $object_id);
			        $stravaActivities = json_encode($stravaActivities);
			        $stravaActivities = json_decode($stravaActivities, true);

			        $user_id = $val['user_id'];
                    $activity = $stravaActivities['type'];
                    $id = $stravaActivities['id'];
                    $athlete_id = $stravaActivities['athlete']['id'];
                    $device_name = 'strava';
                    $start_date_local = $stravaActivities['start_date'];
                    $startDateTime = Carbon::createFromTimestamp(strtotime($start_date_local))->format('Y-m-d H:i:s');
                    $endTime = gmdate("H:i:s", $stravaActivities['moving_time']);
                    $distance = $stravaActivities['distance'];
                    $distance = $distance/1609.344;

                    $challenge = DB::table('user_challenges')->select('user_challenges.*')
                        ->where('user_challenges.status', 1)
                        ->where('user_challenges.user_id', $user_id)->get();
                    $challenge = json_encode($challenge);
                    $challenge = json_decode($challenge, true);
                    
                    $inputData = array();
                    foreach($challenge as $key => $vale){
                        $challenge_logs = DB::table('challenge_logs')->select('challenge_logs.*')
                        ->where([['challenge_logs.user_id', '=', $user_id], ['challenge_logs.participation_id', '=', $vale['challenge_id']], ['challenge_logs.activity_id', '=', $id], ['challenge_logs.device_name', '=', $device_name]])->get();
                        $challenge_logs = json_encode($challenge_logs);
                        $challenge_logs = json_decode($challenge_logs, true);
                        if(!$challenge_logs){

                            $ChallengeInfos = new Challenge_infos();
                            $check = $ChallengeInfos->checkChallengeMilestone($vale['challenge_id'], round($distance, 2), $user_id, $startDateTime);
                            $check = json_decode($check, true);

                            if($check['status'] != 2){
                                $inputData1 = array();
                                $inputData1['user_id'] = $user_id;
                                $inputData1['participation_id'] = $vale['challenge_id'];
                                $inputData1['activity'] = $activity;
                                $inputData1['startDateTime'] = $startDateTime;
                                $inputData1['endTime'] = $endTime;
                                $inputData1['device_name'] = $device_name;
                                $inputData1['athlete'] = $athlete_id;
                                $inputData1['activity_id'] = $id;
                                $inputData1['distance_travelled'] = round($distance, 2);

                                $inputData[] = $inputData1;
                            }
                        }
                    }

                    if($inputData){
                        DB::table('challenge_logs')->insert($inputData);
                    }

		        
                    if($id && $activity && $athlete_id && $start_date_local && $stravaActivities['moving_time'] && $stravaActivities['distance']){
                        Log::channel('strava')->info(json_encode($stravaActivities));	
                    }
		        
		       }
		       
		    }
        }
	    

	    Log::channel('strava')->info(json_encode($request->all()));

	    return response('EVENT_RECEIVED', Response::HTTP_OK);

    }
}