<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Challenge_infos;
use App\Models\Challenge_logs;
use App\Models\FitbitUserCredential;
use App\Models\Strava_user_credentials;
use App\Services\FitbitConnectionService;
use Carbon\Carbon;
use File;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Namelivia\Fitbit\Laravel\FitbitManager;
use Session;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use View;

class FitbitController extends Controller
{
    /**
     * View fitbit connect page.
     *
     */
    public function fitbitConnect(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {
        } else {

            return redirect()->guest(route('frontend.home'));
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

        if ($user_info) {
            foreach ($user_info as $key => $value) {

                if (@$value['meta_name'] == 'address') {
                    $address = @$value['meta_value'] . ', ';
                } elseif (@$value['meta_name'] == 'city') {
                    $city = @$value['meta_value'] . ', ';
                } elseif (@$value['meta_name'] == 'state') {
                    $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();

                    if ($sta) {
                        $state = $sta->state_name . ' ';
                    }
                } elseif (@$value['meta_name'] == 'zip_code') {
                    $zip_code = @$value['meta_value'] . ', ';
                } elseif (@$value['meta_name'] == 'country') {
                    $country = @$value['meta_value'];
                }
            }
        }
        $Country_name = $country == 'usa' ? 'United States' : '';

        $address = trim($state) . ', ' . $Country_name;

        #Current Week Data Count

        $distancreTravel = DB::table('challenge_logs')->where('user_id', auth()->user()->id)->get();
        $totalDistancreTravel = 0;
        if ($distancreTravel) {
            foreach ($distancreTravel as $key => $value) {
                $totalDistancreTravel = $totalDistancreTravel + $value->distance_travelled;
            }
        }

        if (@$input['challenge_status']) {
            $challenge = DB::table('challenges')->select('challenges.*')
                ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where([['user_challenges.user_id', '=', auth()->user()->id], ['user_challenges.status', '=', $input['challenge_status']]])->groupBy('user_challenges.challenge_id')->get()->toArray();
        } else {

            $challenge = DB::table('challenges')->select('challenges.*')
                ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where('user_challenges.user_id', auth()->user()->id)->groupBy('user_challenges.challenge_id')->get()->toArray();
        }

        $Challenge_infos = new Challenge_infos;
        $Challenge_logs = new Challenge_logs;

        $current = 0;
        $past    = 0;

        if ($challenge) {
            foreach ($challenge as $key => $value) {

                $event_end_date = Carbon::parse($value->event_end_date, 'UTC')->setTimezone($timezone);
                if ($event_end_date <= Carbon::now($timezone)->toDateTimeString()) {
                    $past = $past + 1;
                } elseif ($event_end_date > Carbon::now($timezone)->toDateTimeString()) {

                    $current = $current + 1;
                }
            }
        }

        $info = array();
        foreach ($challenge as $k => $val) {
            $challenges_info = DB::table('challenge_infos')->select('*')->where('challenge_infos.challenge_id', $val->id)->get()->toArray();
            $info[$k]['challenges'] = $val;
            $info[$k]['challenge_info'] = $challenges_info;
        }

        $Strava_user = Strava_user_credentials::where('user_id', auth()->user()->id)->first();
        $Strava_user1 = json_encode($Strava_user);
        $Strava_user1 = json_decode($Strava_user1, true);

        $fitbit_user = FitbitUserCredential::where('user_id', auth()->user()->id)->first();

        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $address;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;
        $arr['Strava_user'] = $Strava_user1;
        $arr['fitbit_user'] = $fitbit_user;

        return view('/frontend/fitbit-connect', $arr);
    }

    public function fitbitAuth(FitbitManager $fitbit)
    {
        if (Auth::check() || Auth::viaRemember()) {
        } else {

            return redirect()->guest(route('frontend.home'));
        }

        $authCode = config('services.fitbit.auth_code');
        if (!empty($authCode)) {
            $fitbit->setAuthorizationCode($authCode);
        }

        return redirect($fitbit->getAuthUri());
    }

    public function getFitbitToken(Request $request, FitbitManager $fitbit)
    {
        if (Auth::check() || Auth::viaRemember()) {
        } else {

            return redirect()->guest(route('frontend.home'));
        }

        $requestUrl = config('services.fitbit.api_base_url') . '/oauth2/token';
        $clientId = config('fitbit.connections.main.client_id');
        $clientSecret = config('fitbit.connections.main.client_secret');
        $redirect_uri = config('fitbit.connections.main.redirect_url');

        $fitbitConnection = new FitbitConnectionService();
        $input = $request->all();
        $code = $input['code'];

        try {
            $token_response = $fitbitConnection->getToken($requestUrl, $clientId, $clientSecret, $code, $redirect_uri);
        } catch (\Exception $e) {
            return redirect()->to(route('admin.fitbitConnect'))->withInput()->withErrors(['error' => $e->getMessage()]);
        }

        $token = json_decode($token_response, true);
        if (isset($token['errors'])) {
            return redirect()->to(route('admin.fitbitConnect'))->withInput()->withErrors(['error' => 'Authorization code invalid: ' . $code . '.']);
        }

        if (isset($token['user_id'])) {
            // $expires_at = null;
            // $expires_at1 = Carbon::createFromTimestamp($expires_at)->format('Y-m-d H:i:s');

            $expires_at = $token['expires_in'] / 3600; // 28800/3600
            $expires_at1 = Carbon::now()->addHours($expires_at);

            $expires_in = $token['expires_in'];
            $expires_in1 = Carbon::createFromTimestamp($expires_in)->format('H:i:s');

            $provider_id = $token['user_id'];
            $provider = FitbitUserCredential::where([['provider_id', '=', $provider_id]])->get();
            $provider = json_encode($provider);
            $provider = json_decode($provider, true);

            if ($provider) {
                return redirect()->to(route('admin.fitbitConnect'))->withInput()->withErrors(['error' => 'Device is already registered on other user. Please use another device to connect.']);
            }

            // Create fitbit subscription code
            $createSubscriptionUrl = config('services.fitbit.api_base_url') . '/1/user/' . $token['user_id'] . '/activities/apiSubscriptions/' . auth()->user()->id . '.json';

            try {
                $subscription_response = $fitbitConnection->createSubscription($createSubscriptionUrl, $token['access_token']);
            } catch (\Exception $e) {
                return redirect()->to(route('admin.fitbitConnect'))->withInput()->withErrors(['error' => $e->getMessage()]);
            }

            $subscription_response_decode = json_decode($subscription_response, true);
            if (isset($subscription_response_decode['errors'])) {
                return redirect()->to(route('admin.fitbitConnect'))->withInput()->withErrors(['error' => 'Invalid parameter subscriberId: null.']);
            }
            // --------------------------- //

            $fitbit_user = FitbitUserCredential::where([['user_id', '=', auth()->user()->id], ['provider_id', '=', $provider_id]])->first();

            if ($fitbit_user) {
                $fitbit_user->user_id = auth()->user()->id;
                $fitbit_user->provider_id = $provider_id;
                $fitbit_user->token_type = $token['token_type'];
                $fitbit_user->refresh_token = $token['refresh_token'];
                $fitbit_user->access_token = $token['access_token'];
                $fitbit_user->expires_at = $expires_at1;
                $fitbit_user->expires_in = $expires_in1;
                $fitbit_user->scope = $token['scope'];
                $fitbit_user->subscription = $subscription_response;
                $fitbit_user->status = 1;
                $fitbit_user->update();
            } else {
                $fitbit_user_credential = new FitbitUserCredential();
                $fitbit_user_credential->user_id = auth()->user()->id;
                $fitbit_user_credential->provider_id = $provider_id;
                $fitbit_user_credential->token_type = $token['token_type'];
                $fitbit_user_credential->refresh_token = $token['refresh_token'];
                $fitbit_user_credential->access_token = $token['access_token'];
                $fitbit_user_credential->expires_at = $expires_at1;
                $fitbit_user_credential->expires_in = $expires_in1;
                $fitbit_user_credential->scope = $token['scope'];
                $fitbit_user_credential->subscription = $subscription_response;
                $fitbit_user_credential->status = 1;
                $fitbit_user_credential->save();
            }

            return redirect()->to(route('admin.fitbitConnect'))->withInput()->with(['message' => 'Now you are successfully connected with fitbit.']);
        } else {
            return redirect()->to(route('admin.fitbitConnect'))->withInput()->withErrors(['error' => 'Something went wrong. Please try again!']);
        }
    }

    public function fitbitDisconnect()
    {
        if (Auth::check() || Auth::viaRemember()) {
        } else {

            return redirect()->guest(route('frontend.home'));
        }

        $requestUrl = config('services.fitbit.api_base_url') . '/oauth2/revoke';
        $clientId = config('fitbit.connections.main.client_id');
        $clientSecret = config('fitbit.connections.main.client_secret');

        $fitbit_user = FitbitUserCredential::where('user_id', auth()->user()->id)->first();
        $access_token = $fitbit_user->access_token;
        $userId = $fitbit_user->provider_id;

        try {
            $fitbitConnection = new FitbitConnectionService();

            // Delete fitbit subscription code
            $deleteSubscriptionUrl = config('services.fitbit.api_base_url') . '/1/user/' . $userId . '/activities/apiSubscriptions/' . auth()->user()->id . '.json';
            $subscription_response = $fitbitConnection->deleteSubscription($deleteSubscriptionUrl, $access_token);
            // --------------------------- //

            $token_response = $fitbitConnection->revokeToken($requestUrl, $clientId, $clientSecret, $access_token);
        } catch (\Exception $e) {
            return redirect()->to(route('admin.fitbitConnect'))->withInput()->withErrors(['error' => $e->getMessage()]);
        }

        $str = FitbitUserCredential::find($fitbit_user->id);
        $str->delete();

        return redirect()->to(route('admin.fitbitConnect'))->withInput()->with(['message' => 'Now you are disconnected with fitbit.']);
    }

    public function fitbitNotifications(Request $request)
    {
        if ($request->isMethod('get')) {
            if ($request->verify == config('services.fitbit.verification_code')) {
                return response()->noContent();
            } else {
                abort(404);
            }
        }

        \Log::info('FITBIT LOG...');
        \Log::info($request->all());
        \Log::info('FITBIT LOG END...');

        $fitbitConnection = new FitbitConnectionService();
        $logs = $request->all();

        $requestUrl = config('services.fitbit.api_base_url') . '/oauth2/token';
        $clientId = config('fitbit.connections.main.client_id');
        $clientSecret = config('fitbit.connections.main.client_secret');

        if (!empty($logs)) {
            foreach ($logs as $key => $log) {
                $collectionType = $log['collectionType'];
                $date = $log['date'];
                $date = Carbon::parse($date)->subDays(2)->format('Y-m-d');
                $ownerId = $log['ownerId']; //provider Id
                $ownerType = $log['ownerType'];
                $subscriptionId = $log['subscriptionId']; //logged in user Id

                if ($collectionType == 'activities') {
                    $fitbit_user = FitbitUserCredential::where('provider_id', $ownerId)->get();
                    $fitbit_user1 = json_encode($fitbit_user);
                    $fitbit_user1 = json_decode($fitbit_user1, true);

                    if (!empty($fitbit_user1)) {
                        foreach ($fitbit_user1 as $key => $val) {
                            if ($val['access_token']) {
                                $user_id = $val['user_id'];

                                if (Carbon::now()->toDateTimeString() > $val['expires_at']) {
                                    $token_response = $fitbitConnection->refreshToken($requestUrl, $clientId, $clientSecret, $val['refresh_token']);
                                    $token = json_decode($token_response, true);

                                    if (isset($token['user_id'])) {
                                        $expires_at = $token['expires_in'] / 3600; // 28800/3600
                                        $expires_at1 = Carbon::now()->addHours($expires_at);

                                        $expires_in = $token['expires_in'];
                                        $expires_in1 = Carbon::createFromTimestamp($expires_in)->format('H:i:s');

                                        FitbitUserCredential::where('id', $val['id'])->update([
                                            'refresh_token' => $token['refresh_token'],
                                            'access_token' => $token['access_token'],
                                            'expires_at' => $expires_at1,
                                            'expires_in' => $expires_in1,
                                        ]);

                                        \Log::info($token['user_id'] . " fitbit access token generated.");

                                    } else {

                                        if (isset($token['errors'])) {
                                            if ($token['errors'][0]['errorType'] == "invalid_grant") {
                                                $deleteSubscriptionUrl = config('services.fitbit.api_base_url') . '/1/user/' . $val['provider_id'] . '/activities/apiSubscriptions/' . $val['user_id'] . '.json';
                                                $subscription_response = $fitbitConnection->deleteSubscription($deleteSubscriptionUrl, $val['access_token']);
                                                $token_response = $fitbitConnection->revokeToken($requestUrl, $clientId, $clientSecret, $val['access_token']);
                                                $str = FitbitUserCredential::find($val['id']);
                                                $str->delete();
                                            }
                                        }
                                    }

                                    $access_token = $token['access_token'];
                                    
                                } else {
                                    $access_token = $val['access_token'];
                                }

                                //$access_token = $val['access_token'];

                                $requestUrl = config('services.fitbit.api_base_url') . '/1/user/' . $ownerId . '/activities/list.json?afterDate=' . $date . '&sort=desc&offset=0&limit=1';
                                $activity_response = $fitbitConnection->getActivityLogList($requestUrl, $access_token);
                                $activity_response = json_decode($activity_response, true);

                                \Log::info('start...');
                                \Log::info($activity_response);
                                \Log::info('end...');

                                if (isset($activity_response['activities'])) {
                                    foreach ($activity_response['activities'] as $key => $fitbit_activity) {
                                        $activityName = $fitbit_activity['activityName'];
                                        $calories = $fitbit_activity['calories'];
                                        $originalDuration = Carbon::createFromTimestampMs($fitbit_activity['originalDuration'])->format('H:i:s');

                                        $distance = 0;
                                        if (@$fitbit_activity['distance']) {
                                            $distance = $fitbit_activity['distance'];
                                        }

                                        if (@$fitbit_activity['distanceUnit']) {
                                            $distanceUnit = $fitbit_activity['distanceUnit'];
                                            if ($distanceUnit == 'Kilometer') {
                                                $distance = $distance / 1.609344; //Miles
                                            }
                                        }

                                        $logId = $fitbit_activity['logId'];
                                        $startTime = $fitbit_activity['startTime'];
                                        $startTime = Carbon::createFromTimestamp(strtotime($startTime))->format('Y-m-d H:i:s');

                                        if ($distance == 0) {
                                            if (@$fitbit_activity['steps']) {
                                                $steps = $fitbit_activity['steps'];
                                                $distance = $steps / 1312.33595801; //KM
                                                $distance = $distance / 1.609344; //Miles
                                            }
                                        }

                                        $device_name = 'fitbit';

                                        $challenge = DB::table('user_challenges')->select('user_challenges.*')->where('user_challenges.status', 1)->where('user_challenges.user_id', $user_id)->get();
                                        $challenge = json_encode($challenge);
                                        $challenge = json_decode($challenge, true);

                                        $inputData = array();
                                        foreach ($challenge as $key => $vale) {
                                            $challenge_logs = DB::table('challenge_logs')->select('challenge_logs.*')->where([['challenge_logs.user_id', '=', $user_id], ['challenge_logs.participation_id', '=', $vale['challenge_id']], ['challenge_logs.activity_id', '=', $logId], ['challenge_logs.device_name', '=', $device_name]])->get();
                                            $challenge_logs = json_encode($challenge_logs);
                                            $challenge_logs = json_decode($challenge_logs, true);
                                            if (!$challenge_logs) {

                                                $ChallengeInfos = new Challenge_infos();
                                                $check = $ChallengeInfos->checkChallengeMilestone($vale['challenge_id'], round($distance, 2), $user_id, $startTime);
                                                $check = json_decode($check, true);

                                                if ($check['status'] != 2) {
                                                    $inputData1 = array();
                                                    $inputData1['user_id'] = $user_id;
                                                    $inputData1['participation_id'] = $vale['challenge_id'];
                                                    $inputData1['activity'] = $activityName;
                                                    $inputData1['startDateTime'] = $startTime;
                                                    $inputData1['endTime'] = $originalDuration;
                                                    $inputData1['calories'] = $calories;
                                                    $inputData1['device_name'] = $device_name;
                                                    $inputData1['athlete'] = $ownerId;
                                                    $inputData1['activity_id'] = $logId;
                                                    $inputData1['distance_travelled'] = round($distance, 2);

                                                    $inputData[] = $inputData1;
                                                }
                                            }
                                        }
                                        if ($inputData) {
                                            DB::table('challenge_logs')->insert($inputData);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        // \Log::info($request->all());

        return response('EVENT_RECEIVED', Response::HTTP_OK);
    }
}

