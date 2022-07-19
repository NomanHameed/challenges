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
use Redirect;
use App\Models\Mapmyrun_user_credentials;


class MapMyRunController extends Controller
{
    public function MapMyRunAuth(){
        $client_key = config('services.MapMyRun.client_key');
        $client_secret = config('services.MapMyRun.client_secret');
        $redirect_uri = config('services.MapMyRun.redirect_uri');

        $redirectUrl = $redirect_uri;
        $auth = "https://www.mapmyfitness.com/v7.1/oauth2/uacf/authorize/?client_id=".$client_key."&response_type=code&redirect_uri=".$redirectUrl;
        return Redirect::to($auth);
        
        
    }

    public function MapMyRunRedirect(Request $request){

        $user_infos = User_infos::where([['user_id', '=', auth()->user()->id], ['meta_name', '=', 'timezone']])->first();
        $user_infos = json_encode($user_infos);
        $user_infos = json_decode($user_infos, true);
        $timezone = env('DEFAULT_TIMEZONE');
        if($user_infos){
            $timezone = $user_infos['meta_value'];
        }

         $input = $request->all();
         $code = $input['code'];

        $client_key = config('services.MapMyRun.client_key');
        $client_secret = config('services.MapMyRun.client_secret');
        $redirect_uri = config('services.MapMyRun.redirect_uri');

         if(!$code){
            return redirect()->to(route('admin.MapMyRunConnect'))->withInput()->withErrors(['error' => 'Device is already registered on other user. Please use another device to connect.']);
         }

         $url="https://www.mapmyfitness.com/v7.1/oauth2/access_token/";

        $postfields = "grant_type=authorization_code&client_id=".$client_key."&client_secret=".$client_secret."&code=".$code;

        $headers = array('Content-Type: ' . 'application/x-www-form-urlencoded');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $json = curl_exec ($ch);
        $json = json_decode($json, true);

        $athlete = Mapmyrun_user_credentials::where([['athlete_id', '=', $json['user_id']]])->get();
        $athlete = json_encode($athlete);
        $athlete = json_decode($athlete, true);
        if($athlete){
            return redirect()->to(route('admin.MapMyRunConnect'))->withInput()->withErrors(['error' => 'Device is already registered on other user. Please use another device to connect.']);
        }

        $expires_in = $json['expires_in'];
        $value = $expires_in;
        $dt = Carbon::now();
        $days = $dt->diffInDays($dt->copy()->addSeconds($value));
        
        $newDateTime = Carbon::now('UTC')->addDays($days);

        $mmrC = Mapmyrun_user_credentials::where('user_id', auth()->user()->id)->first();
        $mmrC1 = json_encode($mmrC);
        $mmrC1 = json_decode($mmrC1, true);
        if($mmrC1){
            
            $mmrC->user_id = auth()->user()->id;
            $mmrC->athlete_id = $json['user_id'];
            $mmrC->token_type = $json['token_type'];
            $mmrC->access_token = $json['access_token'];
            $mmrC->refresh_token = $json['refresh_token'];
            $mmrC->expires_in = $newDateTime;
            $mmrC->status = 1;
            $mmrC->update();
        }else{
            $mapmyrun_user_credentials = new Mapmyrun_user_credentials();
            $mapmyrun_user_credentials->user_id = auth()->user()->id;
            $mapmyrun_user_credentials->athlete_id = $json['user_id'];
            $mapmyrun_user_credentials->token_type = $json['token_type'];
            $mapmyrun_user_credentials->access_token = $json['access_token'];
            $mapmyrun_user_credentials->refresh_token = $json['refresh_token'];
            $mapmyrun_user_credentials->expires_in = $newDateTime;
            $mapmyrun_user_credentials->status = 1;
            $mapmyrun_user_credentials->save();
        }
        $responsecode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return redirect()->to(route('admin.MapMyRunConnect'))->withInput()->with(['message' => 'Now you are successfully connected with UnderArmour.']);
    }

    public function MapMyRunSubscribe(Request $request){
       $input = $request->all();

       Log::info(json_encode($input));

        Log::info('Start MapMyRun log...');
       if($input){
           $type = $input['0']['type'];
           $object_id = $input['0']['object_id'];
           $href = $input['0']['_links']['workout']['0']['href'];
           $athlete_id = $input['0']['_links']['user']['0']['id'];

           $client_key = config('services.MapMyRun.client_key');
            $client_secret = config('services.MapMyRun.client_secret');
            $redirect_uri = config('services.MapMyRun.redirect_uri');
           
            if($type == 'application.workouts'){

                $mmrC = Mapmyrun_user_credentials::where('athlete_id', $athlete_id)->first();
                $mmrC1 = json_encode($mmrC);
                $mmrC1 = json_decode($mmrC1, true);
                if($mmrC1){
                    $user_id = $mmrC1['user_id'];
                    $url="https://oauth2-api.mapmyapi.com".$href;

                    //$postfields = "grant_type=authorization_code&client_id=svflww2l4l6blqhp57ywbolklhg4auda&client_secret=cxmtyi7yk5beg4dshregy2gsim4zcu7pawyufs3mjjfmtcyb4ulneugcbyhwnzgz&code=".$code;

                    $headers = array('Content-Type: application/x-www-form-urlencoded','Api-Key: '.$client_key ,'Authorization: Bearer '.$mmrC1['access_token']);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                    //curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $json = curl_exec ($ch);
                    $json = json_decode($json, true);

                    if($json && !@$json['error'] && !@$json['oauth2_error']){
                        //$activity_link = $json['_links']['activity_type']['0']['href'];
                        $activity = @$json['name'];
                        $id = $object_id;
                        $athlete_id = $athlete_id;
                        $device_name = 'Mapmyrun';
                        $start_datetime = @$json['start_datetime'];
                        $startDateTime = Carbon::createFromTimestamp(strtotime($start_datetime))->format('Y-m-d H:i:s');
                        $endTime = gmdate("H:i:s", @$json['aggregates']['active_time_total']);
                        $distance = @$json['aggregates']['distance_total'];
                        $distance = $distance/1609.344;

                        $challenge = DB::table('user_challenges')->select('user_challenges.*')
                            ->where('user_challenges.status', 1)
                            ->where('user_challenges.user_id', $user_id)->get();
                        $challenge = json_encode($challenge);
                        $challenge = json_decode($challenge, true);

                        Log::info(json_encode($challenge));
                        
                        $inputData = array();
                        foreach($challenge as $key => $vale){
                            $challenge_logs = DB::table('challenge_logs')->select('challenge_logs.*')
                            ->where([['challenge_logs.user_id', '=', $user_id], ['challenge_logs.participation_id', '=', $vale['challenge_id']], ['challenge_logs.activity_id', '=', $id], ['challenge_logs.device_name', '=', $device_name]])->get();
                            $challenge_logs = json_encode($challenge_logs);
                            $challenge_logs = json_decode($challenge_logs, true);
                            Log::info(json_encode($challenge_logs));
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
                        

                    
                        
                    }

                    \Log::info(print_r($json, true));
                    $responsecode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                }

            }


        }
        Log::info('End MapMyRun log...');
       $res = array("status" => 202);

       return response()->json($res, 202);

    }

    public function MapMyRunSubscribeGet(Request $request){
       $input = $request->all();
       //$collect = $request->collect();
       $query = $request->query();

       //\Log::info(print_r($input, true));
       //\Log::info(print_r($collect, true));
       //\Log::info(print_r($query, true));

       $res = array("status" => 202);

       return response()->json($res, 202);
    }

    
}