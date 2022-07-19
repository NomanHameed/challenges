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
use App\Models\User_badges;
use App\Models\Badges;
use App\Models\Challenge_milestones;
use App\Models\Timezones;
use App\Models\Monthly_miles_log_submits;
use App\Models\FitbitUserCredential;
use Carbon\Carbon;
use File;
use CodeToad\Strava\Strava;
use App\Models\Strava_user_credentials;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
//use App\Notifications\SendMonthlyLogs;
use App\Services\StravaWebhookService;
use App\Models\Mapmyrun_user_credentials;
use App\Models\GarminUserCredential;

use App\Mail\SendMonthlyLogs;
use App\Mail\NewUserRegistration;
use App\Mail\NewUserRegistrationToAdmin;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    
    /*public function __construct()
    {
        if (Auth::check() || Auth::viaRemember()) {
            $user = User::where('id', auth()->user()->id)->first();
            $user = json_encode($user);
            $user = json_decode($user, true);
            print_r($user); die();
            if($user){
                if($user['user_type'] == 2){

                }else{

                    return view::make('/frontend/page-403');

                }
            }

        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }
    } */   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function userRegister(Request $request)
    {

        $timezoneList = Timezones::get();
        $timezoneList = json_encode($timezoneList);
        $timezoneList = json_decode($timezoneList, true);

        return view::make('/frontend/register', ['timezoneList' => $timezoneList]);

    }

    public function forgot_password(Request $request)
    {

        return view::make('/frontend/forgot_password');

    }

    /**
     * Send Reset password mail link to user email.
     *
    */
    public function manageDevice(Request $request)
    {

        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        //echo env('CT_STRAVA_CLIENT_ID'); die();

        $arr = array();

        $input = $request->all();

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
                   $address  = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'city'){
                   $city     = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] =='state'){
                   $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();
                    
                    if($sta){
                        $state    = $sta->state_name.' ';
                    }
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'country'){
                   $country  = @$value['meta_value'];
               }
               
            }
        }
        $Country_name = $country == 'usa' ? 'United States' : '';
        $address = trim($state).', '.$Country_name;

        #Current Week Data Count

        $distancreTravel =DB::table('challenge_logs')->where('user_id', auth()->user()->id)->get();
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

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

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

        $MapMyRun_user = Mapmyrun_user_credentials::where('user_id', auth()->user()->id)->first();
        $MapMyRun_user1 = json_encode($MapMyRun_user);
        $MapMyRun_user1 = json_decode($MapMyRun_user1, true);        

        $fitbit_user = FitbitUserCredential::where('user_id', auth()->user()->id)->first();

        $garmin_user = GarminUserCredential::where('user_id', auth()->user()->id)->first();

        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $address;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;
        $arr['Strava_user'] = $Strava_user1;
        $arr['fitbit_user'] = $fitbit_user;
        $arr['MapMyRun_user'] = $MapMyRun_user1;
        $arr['garmin_user'] = $garmin_user;

        return view('/frontend/manageDevice', $arr);
        
    }

    public function TrophyCase(Request $request){
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

        //echo env('CT_STRAVA_CLIENT_ID'); die();

        $arr = array();

        $input = $request->all();

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
                   $address  = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'city'){
                   $city     = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] =='state'){
                   $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();
                    
                    if($sta){
                        $state    = $sta->state_name.' ';
                    }
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'country'){
                   $country  = @$value['meta_value'];
               }
               
            }
        }

        $Country_name = $country == 'usa' ? 'United States' : '';

        $address = trim($state).', '.$Country_name;

        #Current Week Data Count

        $distancreTravel =DB::table('challenge_logs')->where('user_id', auth()->user()->id)->get();
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

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

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

        $MapMyRun_user = Mapmyrun_user_credentials::where('user_id', auth()->user()->id)->first();
        $MapMyRun_user1 = json_encode($MapMyRun_user);
        $MapMyRun_user1 = json_decode($MapMyRun_user1, true);        

        $fitbit_user = FitbitUserCredential::where('user_id', auth()->user()->id)->first();

        $garmin_user = GarminUserCredential::where('user_id', auth()->user()->id)->first();



        $badges = DB::table('badges')
            ->select('badges.*')
            //->join('user_badges', 'badges.id', '=', 'user_badges.badge_id')
            //->where([["user_badges.user_id", '=', auth()->user()->id]])
            ->orderBy('created_at', 'ASC')
            ->get();  

        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $address;
        $arr['badges'] = $badges;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;
        $arr['Strava_user'] = $Strava_user1;
        $arr['fitbit_user'] = $fitbit_user;
        $arr['MapMyRun_user'] = $MapMyRun_user1;
        $arr['garmin_user'] = $garmin_user;
        $arr['timezone'] = $timezone;
        $arr['user_id'] = auth()->user()->id;
        $arr['User_badges'] = new User_badges();

        return view('/frontend/TrophyCase', $arr);
    }

    public function MapMyRunConnect(Request $request)
    {

        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        //echo env('CT_STRAVA_CLIENT_ID'); die();

        $arr = array();

        $input = $request->all();

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

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
                   $address  = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'city'){
                   $city     = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] =='state'){
                   $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();
                    
                    if($sta){
                        $state    = $sta->state_name.' ';
                    }
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'country'){
                   $country  = @$value['meta_value'];
               }
               
            }
        }

        $Country_name = $country == 'usa' ? 'United States' : '';

        $address = trim($state).', '.$Country_name;

        #Current Week Data Count

        $distancreTravel =DB::table('challenge_logs')->where('user_id', auth()->user()->id)->get();
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

        $Strava_user = Mapmyrun_user_credentials::where('user_id', auth()->user()->id)->first();

        $Strava_user1 = json_encode($Strava_user);
        $Strava_user1 = json_decode($Strava_user1, true);

        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $address;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;
        $arr['Strava_user'] = $Strava_user1;

        return view('/frontend/map-my-run-connect', $arr);
        
    }

    public function stravaConnect(Request $request)
    {

        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        //echo env('CT_STRAVA_CLIENT_ID'); die();

        $arr = array();

        $input = $request->all();

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

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
                   $address  = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'city'){
                   $city     = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] =='state'){
                   $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();
                    
                    if($sta){
                        $state    = $sta->state_name.' ';
                    }
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'country'){
                   $country  = @$value['meta_value'];
               }
               
            }
        }
        $Country_name = $country == 'usa' ? 'United States' : '';

        $address = trim($state).', '.$Country_name;

        #Current Week Data Count

        $distancreTravel =DB::table('challenge_logs')->where('user_id', auth()->user()->id)->get();
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

        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $address;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;
        $arr['Strava_user'] = $Strava_user1;

        return view('/frontend/strava-connect', $arr);
        
    }

    public function stravaAuth()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $client = new Client();

        $Strava = new Strava(config('ct_strava.client_id'), config('ct_strava.client_secret'), config('ct_strava.redirect_uri'), $client);
        return $Strava->authenticate($scope='read_all,profile:read_all,activity:read_all');
    }

    public function Disconnect()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $Strava_user = Strava_user_credentials::where('user_id', auth()->user()->id)->first();
        $Strava_user1 = json_encode($Strava_user);
        $Strava_user1 = json_decode($Strava_user1, true);

        $client = new Client();

        $Strava = new Strava(config('ct_strava.client_id'), config('ct_strava.client_secret'), config('ct_strava.redirect_uri'), $client);
        $Strava->unauthenticate($Strava_user1['access_token']);

        $str = Strava_user_credentials::find($Strava_user1['id']);
        $str->delete();

        return redirect()->to(route('admin.stravaConnect'))->withInput()->with(['message' => 'Now you are disconnected with strava.']);
    }

    public function MapMyRunDisconnect()
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $Strava_user = Mapmyrun_user_credentials::where('user_id', auth()->user()->id)->first();
        $Strava_user1 = json_encode($Strava_user);
        $Strava_user1 = json_decode($Strava_user1, true);

        $client = new Client();

        /*$Strava = new Strava(env('CT_STRAVA_CLIENT_ID'), env('CT_STRAVA_SECRET_ID'), env('CT_STRAVA_REDIRECT_URI'), $client);
        $Strava->unauthenticate($Strava_user1['access_token']);
*/
        $str = Mapmyrun_user_credentials::find($Strava_user1['id']);
        $str->delete();

        return redirect()->to(route('admin.MapMyRunConnect'))->withInput()->with(['message' => 'Now you are disconnected with UnderArmour.']);
    }

    public function webhook(Request $request)
    {
        Log::info($request->input('hub_challenge'));

        return response()->json([
            'hub_challenge' => $request->input('hub_challenge')
        ], 200);
    }


    public function refreshTest(){
        #Current Month Data Count

        $challenges = Challenges::get();
        $challenges = json_encode($challenges);
        $challenges = json_decode($challenges, true);
        foreach($challenges as $key => $value){ 
            $badge_id = $value['id'];
            $event_end_date = $value['event_end_date'];
            $newDate = '';
            if($event_end_date){
            $tempDate = explode(' ', $event_end_date);
              $tempDate = explode('-', $tempDate['0']);
              $stat = checkdate($tempDate[1], $tempDate[2], (int)$tempDate[0]);
              if($stat){ //echo $event_end_date .'<'. Carbon::now('UTC')->toDateTimeString();
                if($event_end_date < Carbon::now('UTC')->toDateTimeString()){
      
                    $challenge = Challenges::where("id", $badge_id)->first();
                    $challenge->status = 2;
                    $challenge->update();

                    $User_challenges = DB::table('user_challenges')->where('challenge_id', $badge_id)->update(['status' => 2]);


                }
              }
            }
        }
        
    }

    public function getToken(Request $request)
    {
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $input = $request->all(); 
        $code = $input['code']; 

        $client = new Client();

       /* echo env('CT_STRAVA_CLIENT_ID');
        echo env('CT_STRAVA_SECRET_ID');
        echo env('CT_STRAVA_REDIRECT_URI');
         die();*/

        $Strava = new Strava(config('ct_strava.client_id'), config('ct_strava.client_secret'), config('ct_strava.redirect_uri'), $client);
        $token = $Strava->token($code);
        
        $expires_at = $token->expires_at;
        $athlete_id = $token->athlete->id;
        
        $expires_at1 = Carbon::createFromTimestamp($expires_at)->format('Y-m-d H:i:s');   
        
        $expires_in = $token->expires_in;
        
        $expires_in1 = Carbon::createFromTimestamp($expires_in)->format('H:i:s');

        $athlete = Strava_user_credentials::where([['athlete_id', '=', $athlete_id]])->get();
        $athlete = json_encode($athlete);
        $athlete = json_decode($athlete, true);
        if($athlete){
            return redirect()->to(route('admin.stravaConnect'))->withInput()->withErrors(['error' => 'Device is already registered on other user. Please use another device to connect.']);
        }
               
        $Strava_user = Strava_user_credentials::where([['user_id', '=', auth()->user()->id], ['athlete_id', '=', $athlete_id]])->first();
        $Strava_user1 = json_encode($Strava_user);
        $Strava_user1 = json_decode($Strava_user1, true);
        if($Strava_user1){

            $Strava_user->user_id = auth()->user()->id;
            $Strava_user->athlete_id = $athlete_id;
            $Strava_user->token_type = $token->token_type;
            $Strava_user->refresh_token = $token->refresh_token;
            $Strava_user->access_token = $token->access_token;
            $Strava_user->expires_at = $expires_at1;
            $Strava_user->expires_in = $expires_in1;
            $Strava_user->status = 1;
            $Strava_user->update();

        }else{

            $Strava_user_credentials = new Strava_user_credentials();
            $Strava_user_credentials->user_id = auth()->user()->id;
            $Strava_user_credentials->token_type = $token->token_type;
            $Strava_user_credentials->athlete_id = $athlete_id;
            $Strava_user_credentials->refresh_token = $token->refresh_token;
            $Strava_user_credentials->access_token = $token->access_token;
            $Strava_user_credentials->expires_at = $expires_at1;
            $Strava_user_credentials->expires_in = $expires_in1;
            $Strava_user_credentials->status = 1;
            $Strava_user_credentials->save();
        }

        return redirect()->to(route('admin.stravaConnect'))->withInput()->with(['message' => 'Now you are successfully connected with strava.']);

      // Store $token->access_token & $token->refresh_token in database
    }

    /**
     * Send Reset password mail link to user email.
     *
    */
    public function resetMail(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            if($validator->fails()){
                return redirect()->to(route('frontend.home'))->withInput()->withErrors($validator); 
            }  
        }

        $user = User::where([['email', '=', $request->email], ['user_type', '=', 2], ['status', '=', 1]])->first();
        
        if($user){
            $passwordReset = PasswordReset::updateOrCreate(
                ['email' => $user->email],
                [
                    'email' => $user->email,
                    'token' => Str::random(60)
                 ]
            ); 

            if ($user && $passwordReset){
                $user->notify(new PasswordResetRequest($passwordReset->token));

                $resp = [
                    'message' => 'SUCCESS',
                    'status'  => intval(Response::HTTP_OK),
                    'data' => 'An email will be sent if the address is recognised'
                ];
                return redirect()->to(route('frontend.home'))->withInput()->with(['message' => 'An email with a password reset link has been sent to the entered email ID. Please access your mailbox and reset your password to login with the new credentials.']); 
            }
        }else{

            $resp = [
                    'message' => 'SUCCESS',
                    'status'  => intval(Response::HTTP_OK),
                    'data' => 'An email will be sent if the address is recognised'
                ];
                return redirect()->to(route('frontend.forgot_password'))->withInput()->withErrors(['error' => 'We could not found an account associated with the entered email ID.']);

        }
    }

    /**
     * Reset password
     * Here password reseton basis of enter new password and confirm new password for reset his password. 
    */
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'password_confirmation' => 'required|same:password',
            'token' => 'required|string'
        ],[
            'password.regex'=>'Your password must contain at least 1 upper case, lower case, numeric, and special character.',
            'password_confirmation.required'=>'The confirm password field is required.',
            'password_confirmation.same'=>"Whoops! it looks like your passwords don't match.",
            ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator); 
        } 

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        if (!$passwordReset){
            return redirect(route('admin.resetPasswordExpire'));
        }
           
        $user = User::where([['email', '=', $passwordReset->email], ['user_type', '=', 2], ['status', '=', 1]])->first();
        
        if(!$user){
            return redirect()->back()->withInput()->withErrors(['error'=>'We can'."'".'t find a user with that e-mail address.']);
        }

        
            
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        //$user->notify(new PasswordResetSuccess($user->first_name.' '.$user->surname,));
        
    return redirect(route('admin.resetPasswordSuccessful'));
    
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset){
            
            return redirect(route('admin.resetPasswordExpire'));
        }
            
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            
            return redirect(route('admin.resetPasswordExpire'));
            
        }
        $DataColl = array("email"=>$passwordReset->email,"token"=>$passwordReset->token);
        return redirect()->route('admin.resetPassword', $DataColl);
        
    }

    /**
     * Reset password form
     * It is use to reset password according to resend link to user email. Here user enter his new password and confirm new password for reset his password. 
    */
    public function resetPassword(Request $request)
    {

        return view('/frontend/reset-password');
    }

    public function resetPasswordExpire(Request $request)
    {
        return view('/frontend/reset-password-expire');
    }
    public function resetPasswordSuccessful(Request $request)
    {
        return view('/frontend/reset-password-successful');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $arr = array();

        $input = $request->all();

        $stateList = DB::table('us_states')->select('*')->get();
        $stateList = json_encode($stateList);
        $stateList = json_decode($stateList, true);
        $citieList = DB::table('us_cities')->select('*')->get();
        $citieList = json_encode($citieList);
        $citieList = json_decode($citieList, true);


        $users = DB::table('users')->select('*')->where('id', auth()->user()->id)->get()->toArray();
        $user_info = DB::table('user_infos')->select('user_infos.*')->where('user_id', auth()->user()->id)->get();
        $user_info = json_encode($user_info);
        $user_info = json_decode($user_info, true);

        $address = '';

        $gender = '';
        $addressVal = '';
        $city = '';
        $country = '';
        $state = '';
        $zip_code = '';
        $dob = '';
        $timezone = '';
        $timezone1 = '';
        $addressVal1 = '';
        $city1 = '';
        $zip_code1 = '';
        
        if($user_info){
            foreach($user_info as $key => $value){
            

               if(@$value['meta_name'] =='address'){
                   $address .= @$value['meta_value'].' ';
                   $addressVal = @$value['meta_value'];
               }elseif(@$value['meta_name'] == 'country'){
                  $address .= @$value['meta_value'].' ';
                  $country = @$value['meta_value'];
               }elseif(@$value['meta_name'] == 'state'){
                   $address .= @$value['meta_value'].' ';
                   $state = @$value['meta_value'];
                   
               }elseif(@$value['meta_name'] == 'city'){
                   $address .= @$value['meta_value'].' ';
                   $city = @$value['meta_value'];
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'];
               }elseif(@$value['meta_name'] == 'gender'){
                   $gender = @$value['meta_value'];
               }elseif(@$value['meta_name'] == 'dob'){
                   $dob = @$value['meta_value'];
               }elseif(@$value['meta_name'] == 'timezone'){
                   $timezone1 = @$value['meta_value'];
               }
               
            }
        }     

        $sta = DB::table('us_states')->where('id', $state)->first();
        $stat = '';
        if($sta){
            //$stat1 = $sta->state_name;
            $stat = $sta->state_name.' ';
        }
        if($addressVal){
            $addressVal1 = $addressVal;
            $addressVal = $addressVal.', ';
        }
        if($city){
            $city1 = $city;
            $city = $city.', ';
        }
        if($zip_code){
            $zip_code1 = $zip_code;
            $zip_code = $zip_code.', ';

        }
        $Country_name = $country == 'usa' ? 'United States' : '';
        $addre = trim($stat).', '.$Country_name;

        $timezoneList = Timezones::get();
        $timezoneList = json_encode($timezoneList);
        $timezoneList = json_decode($timezoneList, true);

        $timezone = $request->session()->get('timezone');
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        
        $challenge = DB::table('challenges')->select('challenges.*')
                        ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where('user_challenges.user_id', auth()->user()->id)->groupBy('challenges.id')->get()->toArray();

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

        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $addre;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;

        $arr['gender']     = $gender;
        $arr['timezoneList']     = $timezoneList;
        $arr['timezone']     = $timezone1;
        $arr['addressVal'] = $addressVal1;
        $arr['city']       = $city1;
        $arr['timezoneEmail']       = $timezone;
        $arr['country']    = $country;
        $arr['state']      = $state;
        $arr['zip_code']   = $zip_code1;
        $arr['dob']        = $dob;
        $arr['stateList']   = $stateList;
        $arr['citieList']        = $citieList;
        $arr['now']        = Carbon::now($timezone)->toDateTimeString();

        return view::make('/frontend/profile', $arr);
    }

    public function profileUpdate(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'first_name'         => 'required',
            'last_name'         => 'required',
            //'email'         => 'required|email',
            //'mobile_number' =>'required',
            //'password'      =>'required',
            //'country'     =>'required',
            //'state'       =>'required',
            'zip_code'          => ['nullable','regex:/^[0-9]{5}(-[0-9]{4})?$/'],
            
        ],[
            'first_name.required' => 'The first name field is required.',
        'last_name.required' => 'The last name field is required.',
        'zip_code.regex' => 'Enter zipcode in valid format.'
            ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator); 
        } 
        $input = $request->all();

        $user = User::where('id', $id)->first();
        $user->first_name = $input['first_name'];
        $user->last_name  = $input['last_name'];
        $user->name       = $input['first_name'].' '.$input['last_name'];
        $user->mobile_number = $input['mobile_number'];
        $user->update();
       
        $User_infos = new User_infos;
        $User_infos->updateMetaValue($id, 'gender', @$input['gender']);
        $User_infos->updateMetaValue($id, 'address', @$input['address']);
        $User_infos->updateMetaValue($id, 'country', @$input['country']);
        $User_infos->updateMetaValue($id, 'state', @$input['state']);
        $User_infos->updateMetaValue($id, 'city', @$input['city']);
        $User_infos->updateMetaValue($id, 'zip_code', @$input['zip_code']);
        $User_infos->updateMetaValue($id, 'dob', @$input['dob']);
        $User_infos->updateMetaValue($id, 'timezone', @$input['timezone']);


        $request->session()->forget('timezone');
        $request->session()->put('timezone', @$input['timezone']);

        return redirect(route('frontend.profile'))->with(['message' => 'User profile updated successfully.']);
    }

    public function changePassword(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'old_password' => 'required|min:8|max:20',
            'password' => 'required|min:8|max:20',
            'password_confirmation' => 'required|same:password',
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator); 
        }  
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->old_password, 'user_type' => 2 ])){
            $user = Auth::user();
            $userChange = User::where('id', $user->id)->first(); 
            $userChange->password =  bcrypt($request->password);   
            $userChange->update();                
            return redirect(route('frontend.profile'))->with(['message' => 'Password changed successfully.']);
            
        }

        return redirect()->back()->withInput()->withErrors(["error" => "Please enter correct old password."]);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function my_challenge(Request $request)
    {
        
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $arr = array();

        $input = $request->all();

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

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
                   $address  = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'city'){
                   $city     = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] =='state'){
                   
                   
                   $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();
                    
                    if($sta){
                        $state    = $sta->state_name.' ';
                    }
                   
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'country'){
                   $country  = @$value['meta_value'];
               }
               
            }
        }

        $Country_name = $country == 'usa' ? 'United States' : '';

        $address = trim($state).', '.$Country_name;

        #Current Week Data Count

        $distancreTravel =DB::table('challenge_logs')->where('user_id', auth()->user()->id)->orderBy('created_at')->get();
        $totalDistancreTravel = 0;
        if($distancreTravel){
            foreach($distancreTravel as $key => $value){
                $totalDistancreTravel = $totalDistancreTravel + $value->distance_travelled;
            }
        }

        


        if(@$input['challenge_status']){
            $challenge = DB::table('challenges')->select('challenges.*')
                         ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where([['user_challenges.user_id', '=', auth()->user()->id], ['user_challenges.status', '=', $input['challenge_status']], ['challenges.price_type', '!=', 'default']])->groupBy('user_challenges.challenge_id')->get()->toArray();

        }else{
            
            $challenge = DB::table('challenges')->select('challenges.*')
                         ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where([['user_challenges.user_id', '=', auth()->user()->id], ['challenges.price_type', '!=', 'default']])->groupBy('user_challenges.challenge_id')->get()->toArray();
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

        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $address;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;
        
        $arr['totalDistancreTravel'] = $totalDistancreTravel;
        $arr['info'] = $info;
        $arr['Challenge_infos'] = $Challenge_infos;
        $arr['Challenge_logs'] = $Challenge_logs;
        $arr['challenge_status'] = @$input['challenge_status'] ? @$input['challenge_status'] : '';

        return view::make('/frontend/my-challenge', $arr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function challenge_list(Request $request)
    {
        
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $arr = array();

        $input = $request->all();


        if(@$input['challenge_status']){
            $challenge = DB::table('challenges')->select('challenges.*')
                         ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where([['user_challenges.user_id', '=', auth()->user()->id], ['user_challenges.status', '=', $input['challenge_status']]])->groupBy('user_challenges.challenge_id')->get()->toArray();

        }else{
            
            $challenge = DB::table('challenges')->select('challenges.*')
                         ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where('user_challenges.user_id', auth()->user()->id)->groupBy('user_challenges.challenge_id')->get()->toArray();
        }

        $Challenge_infos = new Challenge_infos;
        $Challenge_logs = new Challenge_logs;

        

        $info = array();
        foreach($challenge as $k=>$val){
            $challenges_info = DB::table('challenge_infos')->select('*')->where('challenge_infos.challenge_id', $val->id)->get()->toArray();
            $info[$k]['challenges'] = $val;
            $info[$k]['challenge_info'] = $challenges_info;
        }
        
        $arr['info'] = $info;
        $arr['Challenge_infos'] = $Challenge_infos;
        $arr['Challenge_logs'] = $Challenge_logs;
        $arr['challenge_status'] = @$input['challenge_status'] ? @$input['challenge_status'] : '';

        return view::make('/frontend/challenge-list', $arr);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function challenge_details(Request $request, $id)
    {
        
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }
        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $arr = array();

        $input = $request->all();
        
        $challenge = DB::table('challenges')->select('challenges.*')
                    ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
                    ->where([['user_challenges.user_id', '=', auth()->user()->id], ['challenges.id', '=', $id]])
                    ->first();
        $challenge = json_encode($challenge);
        $challenge = json_decode($challenge, true); 

        $Challenge_infos = new Challenge_infos;
        $Challenge_logs = new Challenge_logs;  

        
        $challengeDistance = $Challenge_infos->getChallengeDistance($id);  

        #Current Month Data Count
        /*$current_month =DB::table('challenge_logs')->where([['user_id', '=', auth()->user()->id], ['participation_id', '=', $id]])->whereMonth('startDateTime', date('m'))->whereYear('created_at', date('Y'))->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();*/
        #Current Week Data Count

        //echo Carbon::now()->startOfMonth();
        
        $current_month =DB::table('challenge_logs')->select('challenge_logs.*')->where([['user_id', '=', auth()->user()->id], ['participation_id', '=', $id]])->whereBetween('startDateTime', [Carbon::now()->startOfMonth()->setTimezone($timezone), Carbon::now()->endOfMonth()->setTimezone($timezone)])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $monthDistance = 0;
        if($current_month){
            foreach($current_month as $key => $value){
                $monthDistance = $monthDistance + $value->distance_travelled;
            }
        }
         
        $category = '';
        
        $info = array();
        if($challenge){

            $challenges_info = DB::table('challenge_infos')->select('*')->where('challenge_infos.challenge_id', $id)->get();
            $challenges_info = json_encode($challenges_info);
            $challenges_info = json_decode($challenges_info, true); 
            $info['challenges'] = $challenge;
            $info['challenge_info'] = $challenges_info;

            

            foreach($info['challenge_info'] as $Key=>$value){
               
               if($value['meta_name'] == 'category'){
                  $category = $value['meta_value'];
               }
               
            }

        }

        $coverageDistance = $Challenge_logs->getChallengeCoverageDistance($id, $category, auth()->user()->id); 

        $challenge_milestone_badge = DB::table('challenge_milestones')->select('challenge_milestones.*', 'user_badges.badge_id')
                    ->join('user_badges', 'user_badges.badge_id', '=', 'challenge_milestones.id' )
                    ->where([['user_badges.user_id', '=', auth()->user()->id], ['user_badges.badge_type', '=', 'challenge_milestone'], ['user_badges.challenge_id', '=', $id]])
                    ->groupBy('user_badges.badge_id')
                    ->get();


        $challenge_milestone_badge = json_encode($challenge_milestone_badge);
        $challenge_milestone_badge = json_decode($challenge_milestone_badge, true);

        $ch_miles_id_arr = array();
        foreach ($challenge_milestone_badge as $key => $value) {
            $ch_miles_id_arr[] = $value['id'];
        }
        
        /*$challenge_milestones = DB::table('challenge_milestones')->whereNotIn('challenge_milestones.id', $ch_miles_id_arr)->where('challenge_milestones.challenge_id', $id)->get();
        $challenge_milestones = json_encode($challenge_milestones);
        $challenge_milestones = json_decode($challenge_milestones, true);*/

        $logs = array();

        $challenge_milestones = DB::table('challenge_milestones')->where('challenge_milestones.challenge_id', $id)->orderBy('challenge_milestones.created_at', 'ASC')->get();
        $challenge_milestones = json_encode($challenge_milestones);
        $challenge_milestones = json_decode($challenge_milestones, true);

        $challenge_logs = DB::table('challenges')->select('challenges.name', 'challenge_logs.*')
                         ->join('challenge_logs', 'challenges.id', '=', 'challenge_logs.participation_id')
                         ->where([['challenge_logs.user_id', '=', auth()->user()->id], ['challenge_logs.participation_id', '=', $id]])
                         ->groupBy('challenge_logs.created_at')
                         ->groupBy('challenge_logs.activity_id')
                         ->get();

        $challenge_logs = json_encode($challenge_logs);
        $challenge_logs = json_decode($challenge_logs, true);

        $challengeCoveredLogs = array();
        
        
        if($challenge_logs){
            foreach($challenge_logs as $key=>$value){
                $startDateTime = Carbon::parse($value['startDateTime'], 'UTC')->setTimezone($timezone);

                $start  = new Carbon($startDateTime);
                
                $logs[] = array(
                        "id" => $value['id'], 
                        "title" => round($value['distance_travelled'], 2).' Miles',
                        "name" => $value['name'],
                        "device_name" => $value['device_name'],
                        "athlete" => $value['athlete'],
                        "activity_id" => $value['activity_id'],
                        "activity" => $value['activity'],
                        "calories" => $value['calories'],
                        "distance_travelled" => round($value['distance_travelled'], 2),
                        "endTime" => $value['endTime'],
                        "start" => $start->toDateTimeString(),
                        "className" => 'scheduler_basic_event'
                  );

                

                $idName = $value['name'];

                $challengeCoveredLogs[$idName] = @$challengeCoveredLogs[$idName] + $value['distance_travelled'];
            }
        }

        $userChallenge = DB::table('users')->select('user_challenges.*', 'users.profile_pic')
                    ->join('user_challenges', 'users.id', '=', 'user_challenges.user_id')
                    ->where([['user_challenges.challenge_id', '=', $id]])
                    ->get();
        $userChallenge = json_encode($userChallenge);
        $userChallenge = json_decode($userChallenge, true); 
        $challengeLogList = array();
        $challengeDistanceforUser = '';
        $challengeImagesforUser = '';
        

        if($userChallenge){
            foreach($userChallenge as $key=>$value){
                $totalChallengeLog = 0;
                $challenge_log_by_user = DB::table('challenges')->select('challenges.name', 'challenge_logs.*')
                         ->join('challenge_logs', 'challenges.id', '=', 'challenge_logs.participation_id')
                         ->where([['challenge_logs.user_id', '=', $value['user_id']], ['challenge_logs.participation_id', '=', $id]])
                         ->groupBy('challenge_logs.created_at')
                         ->groupBy('challenge_logs.activity_id')
                         ->get();
                $challenge_log_by_user = json_encode($challenge_log_by_user);
                $challenge_log_by_user = json_decode($challenge_log_by_user, true);
                if($challenge_log_by_user){
                    foreach($challenge_log_by_user as $ke=>$valu){
                        $totalChallengeLog = $totalChallengeLog+$valu['distance_travelled'];
                    }
                }

                $challengeLogList[] = [$totalChallengeLog, asset(@$value['profile_pic'])];

                if(auth()->user()->id == $value['user_id']){
                    $challengeDistanceforUser = $totalChallengeLog;
                    $challengeImagesforUser = asset(@$value['profile_pic']);
                }
            }
            
        }
           //echo $challengeDistanceforUser;
        $adminUser = User::where('user_type', 1)->get();
        $adminuser_infos = User_infos::where([['user_id', '=', $adminUser['0']->id], ['meta_name', '=', 'timezone']])->first();
        $adminuser_infos = json_encode($adminuser_infos);
        $adminuser_infos = json_decode($adminuser_infos, true);
        $admintimezone = env('DEFAULT_TIMEZONE');
        if($adminuser_infos){
            $admintimezone = $adminuser_infos['meta_value'];
        }
        


        $arr['info'] = $info;
        $arr['Challenge_infos'] = $Challenge_infos;
        $arr['Challenge_logs'] = $Challenge_logs;
        $arr['monthDistance'] = $monthDistance;
        $arr['id'] = $id;
        $arr['user_id'] = auth()->user()->id; 
        $arr['challengeDistance'] = @$challengeDistance->meta_value;
        $arr['coverageDistance'] = $coverageDistance;
        $arr['challenge_milestone_badge'] = $challenge_milestone_badge;
        $arr['challenge_milestones'] = $challenge_milestones;
        $arr['challengeLogList'] = $challengeLogList;
        $arr['Monthly_miles_log_submits'] = new Monthly_miles_log_submits();
        $arr['now'] = Carbon::now($timezone)->toDateTimeString();
        $arr['timezone']     = $timezone;
        $arr['admintimezone']     = $admintimezone;
        $arr['challengeDistanceforUser']     = $challengeDistanceforUser;
        $arr['challengeImagesforUser']     = $challengeImagesforUser;
        $arr['logs'] = json_encode($logs);

        return view::make('/frontend/challenge-details', $arr);
    }

    public function sendMonthlyLogs(Request $request){
        $input = $request->all();



        $ch = DB::table('challenge_logs')->where([['participation_id', '=', $input['challenge_id']], ['user_id', '=', $input['user_id']]])->whereBetween('startDateTime', [$input['start_date'], $input['end_date']])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $ch = json_encode($ch);
        $ch = json_decode($ch, true);

        $dist = 0;
        
        foreach($ch as $key => $val){
            $dist = $dist+$val['distance_travelled'];
        }

        $Challenges = Challenges::where('id', $input['challenge_id'])->first();

        $challengeUser = user::where('id', $input['user_id'])->first();
        $user_info = User_infos::where('user_id', $input['user_id'])->get();

        $address = '';
        $city = '';
        $state = '';
        $zip_code = '';
        $country = '';
        
        if($user_info){
            foreach($user_info as $key => $value){
            

               if(@$value['meta_name'] =='address'){
                   $address  = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'city'){
                   $city     = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] =='state'){
                   $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();
                    
                    if($sta){
                        $state    = $sta->state_name.' ';
                    }
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'country'){
                   $country  = @$value['meta_value'];
               }
               
            }
        }

        $Country_name = $country == 'usa' ? 'United States' : '';

        $address = trim($state).', '.$Country_name;

        $Monthly_miles_log_submits = Monthly_miles_log_submits::where([['milestone_id', '=', $input['milestone_id']], ['user_id', '=', $input['user_id']]])->first();
        $Monthly_miles_log_submits1 = json_encode($Monthly_miles_log_submits);
        $Monthly_miles_log_submits1  = json_decode($Monthly_miles_log_submits1, true);
        if($Monthly_miles_log_submits1){
            $Monthly_miles_log_submits->submit_status = 1;
            $Monthly_miles_log_submits->update();
        }else{
            $Monthly_miles_log = new Monthly_miles_log_submits();
            $Monthly_miles_log->milestone_id = $input['milestone_id'];
            $Monthly_miles_log->user_id = $input['user_id'];
            $Monthly_miles_log->submit_status = 1;
            $Monthly_miles_log->save();
        }

        $user = user::where('user_type', 1)->first();
        //$user->notify(new SendMonthlyLogs($challengeUser->name, $address, $challengeUser->mobile_number, $challengeUser->email, $Challenges['name'], $Challenges['type'], round($dist, 2), $input['start_date'], $input['end_date'], $input['milestone_name']));
        Mail::to($user)->send(new SendMonthlyLogs($challengeUser->name, $address, $challengeUser->mobile_number, $challengeUser->email, $Challenges['name'], $Challenges['type'], round($dist, 2), $input['start_date'], $input['end_date'], $input['milestone_name']));

        return redirect(route('frontend.challenge_details', $input['challenge_id']))->with(['message' => 'Your monthly submission is submitted.']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        
        if (Auth::check() || Auth::viaRemember()) {


        }else{

          return redirect()->guest(route( 'frontend.home' ));

        }

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

        $arr = array();

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
                   $address  = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'city'){
                   $city     = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] =='state'){
                   $sta = DB::table('us_states')->where('id', @$value['meta_value'])->first();
                    
                    if($sta){
                        $state    = $sta->state_name.' ';
                    }
               }elseif(@$value['meta_name'] == 'zip_code'){
                   $zip_code = @$value['meta_value'].', ';
               }elseif(@$value['meta_name'] == 'country'){
                   $country  = @$value['meta_value'];
               }
               
            }
        }

        $Country_name = $country == 'usa' ? 'United States' : '';

        $address = trim($state).', '.$Country_name;

        

        #Current Week Data Count
        /*$distancreTravel =DB::table('challenge_logs')->where('user_id', auth()->user()->id)->get();
        $totalDistancreTravel = 0;
        if($distancreTravel){
            foreach($distancreTravel as $key => $value){
                $totalDistancreTravel = $totalDistancreTravel + $value->distance_travelled;
            }
        }*/

        /*$current_year = DB::table('challenge_logs')->select('challenge_logs.*')->where('user_id', auth()->user()->id)
                ->whereYear('startDateTime', $now->year)
                ->groupBy('created_at')
                ->groupBy('challenge_logs.activity_id')
                ->get();*/

        $distancreTravel =DB::table('challenge_logs')->where([['user_id', '=', auth()->user()->id], ['participation_id', '=', 14]])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $totalDistancreTravelArr = array();

        $yearDistance = 0;
        
        if($distancreTravel){
            foreach($distancreTravel as $key => $value){
                //$startDateTime = date('d/m/Y', strtotime($value->startDateTime));
                $startDateTime = Carbon::parse($value->startDateTime)->format('d/m/Y');
                $distance_travelled = $value->distance_travelled;
                $created_at = $value->created_at;
                //$created_at = Carbon::parse($value->created_at)->format('d/m/Y');
                $totalDistancreTravelArr[$created_at] = $value->distance_travelled;

                $yearDistance = $yearDistance + $value->distance_travelled;
                
            }
        }
        //echo '<pre>';print_r($totalDistancreTravelArr);

        $totalDistancreTravel = array_sum($totalDistancreTravelArr);

        //$totalDistancreTravel = $yearDistance;

        $now = Carbon::now()->setTimezone($timezone);
        $startOfWeek = Carbon::now($timezone)->startOfWeek();
        $endOfWeek = Carbon::now($timezone)->endOfWeek();
        
        #Current Week Data Count
        $current_week =DB::table('challenge_logs')->where([['user_id', '=', auth()->user()->id], ['participation_id', '=', 14]])->whereBetween('startDateTime', array($startOfWeek, $endOfWeek))->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        
        $weekDistance = 0;
        if($current_week){
            foreach($current_week as $key => $value){
                $startDateTime = Carbon::parse($value->startDateTime, 'UTC')->setTimezone($timezone);
                if($startDateTime >= $startOfWeek && $startDateTime <= $endOfWeek){
                    $weekDistance = $weekDistance + $value->distance_travelled;
                }
            }
        }

        $firstOfMonth = Carbon::now($timezone)->firstOfMonth();
        $lastOfMonth = Carbon::now($timezone)->lastOfMonth();
        
        #Current Month Data Count
        $current_month =DB::table('challenge_logs')->where([['user_id', '=', auth()->user()->id], ['participation_id', '=', 14]])->whereBetween('startDateTime', array(Carbon::now($timezone)->firstOfMonth(), Carbon::now($timezone)->lastOfMonth()))->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $monthDistance = 0;
        if($current_month){
            foreach($current_month as $key => $value){
                $startDateTime = Carbon::parse($value->startDateTime, 'UTC')->setTimezone($timezone);
                if($startDateTime >= $firstOfMonth && $startDateTime <= $lastOfMonth){
                    $monthDistance = $monthDistance + $value->distance_travelled;
                }
            }
        }
        #Current Year Data Count
        $current_year = DB::table('challenge_logs')->select('challenge_logs.*')->where([['user_id', '=', auth()->user()->id], ['participation_id', '=', 14]])
                //->whereYear('startDateTime', $now->year)
                ->groupBy('created_at')
                ->groupBy('challenge_logs.activity_id')
                ->get();
        //echo '<pre>'; print_r($current_year); echo '</pre>';
                //$totalDistancreTravelArr = array();
        $yearDistance = 0;
        if($current_year){
            foreach($current_year as $key => $value){
                $startDateTime = Carbon::parse($value->startDateTime, 'UTC')->setTimezone($timezone);
                //$totalDistancreTravelArr[$startDateTime] = $value->distance_travelled;
                if($startDateTime->year == '2022'){
                    $yearDistance = $yearDistance + $value->distance_travelled;
                }
                
            }
        }


        
        /*$challenge = DB::table('challenges')->select('challenges.*')
                         ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where('user_challenges.user_id', auth()->user()->id)->groupBy('user_challenges.challenge_id')->get()->toArray();*/
        $challenge = DB::table('challenges')->select('challenges.*', 'user_challenges.created_at as challenge_assign_date', 'user_challenges.status as challenge_status')
                         ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')->where([['user_challenges.user_id', '=', auth()->user()->id]])->groupBy('user_challenges.challenge_id')
                         ->get()->toArray();

        $current = 0;
        $past    = 0;

        if($challenge){
            foreach($challenge as $key => $value){

                $event_end_date = Carbon::parse($value->event_end_date, 'UTC')->setTimezone($timezone);
                if($value->challenge_status == 2){
                     $past = $past+1;
                }else{
                     
                     $current = $current+1;
                }

            }
        }

        $info = array();
        $logs = array();
        foreach($challenge as $k=>$val){
            $challenges_info = DB::table('challenge_infos')->select('*')->where('challenge_infos.challenge_id', $val->id)->get()->toArray();
            $challenges_distance = DB::table('challenge_infos')->select('*')->where([['challenge_infos.meta_name', '=', 'total_distance'], ['challenge_infos.challenge_id', '=', $val->id]])->first();
            $challenges_distance = json_encode($challenges_distance);
            $challenges_distance = json_decode($challenges_distance, true);
            $info[$k]['challenges'] = $val;
            $info[$k]['challenge_info'] = $challenges_info;
            if($challenges_distance){
                $info[$k]['challenges_distance'] = @$challenges_distance['meta_value'] ? @$challenges_distance['meta_value'] : 'N/A';
            }else{
                $info[$k]['challenges_distance'] = 0;
            }
            
            
        }

        $challenge_logs = DB::table('challenges')->select('challenges.name', 'challenge_logs.*')
                         ->join('challenge_logs', 'challenges.id', '=', 'challenge_logs.participation_id')
                         ->where('challenge_logs.user_id', auth()->user()->id)
                         ->groupBy('challenge_logs.created_at')
                         ->groupBy('challenge_logs.activity_id')
                         ->get();

        $challenge_logs = json_encode($challenge_logs);
        $challenge_logs = json_decode($challenge_logs, true);

        //print_r($challenge_logs);

        $challengeCoveredLogs = array();
        
        if($challenge_logs){
            foreach($challenge_logs as $key=>$value){
                $created_at = $value['created_at'];
                $chLogByDate = Challenge_logs::where("created_at", $value['created_at'])->get();
                $chLogByDate = json_encode($chLogByDate);
                $chLogByDate = json_decode($chLogByDate, true);

                
                $user_challenge_status = 1;
                if($chLogByDate){
                    foreach($chLogByDate as $ke => $val){
                        $uc = Challenges::where([['id', '=', $val['participation_id']]])->first();
                        $uc = json_encode($uc);
                        $uc = json_decode($uc, true);
                        $event_end_date = $uc['event_end_date'];
                        
                        if($event_end_date){
                          $tempDate = explode(' ', $event_end_date);
                          $tempDate = explode('-', $tempDate['0']);
                          $stat = checkdate($tempDate[1], $tempDate[2], (int)$tempDate[0]);
                          if($stat){  //echo $val['participation_id']; echo '='; echo $event_end_date .'<'. Carbon::now('UTC')->toDateTimeString(); echo '<br>';
                            if($event_end_date < Carbon::now('UTC')->toDateTimeString()){
                                $user_challenge_status = 2;
                            }
                          }
                        }
                
                    }
                }
                
                
                $date = Carbon::parse($value['startDateTime'], 'UTC')->setTimezone($timezone);
                
                $start  = new Carbon($date);
                
                $logs[] = array(
                        "id" => $value['id'], 
                        "title" => round($value['distance_travelled'], 2).' Miles',
                        "name" => $value['name'],
                        "user_challenge_status" => $user_challenge_status,
                        "device_name" => $value['device_name'],
                        "athlete" => $value['athlete'],
                        "activity_id" => $value['activity_id'],
                        "activity" => $value['activity'],
                        "calories" => $value['calories'],
                        "distance_travelled" => round($value['distance_travelled'], 2),
                        "endTime" => $value['endTime'],
                        "start" => $start->toDateTimeString(),
                        "className" => 'scheduler_basic_event'
                  );

                $idName = $value['id'];

                $challengeCoveredLogs[$idName] = @$challengeCoveredLogs[$idName] + $value['distance_travelled'];
            }
        }

        $logUpdated = array();



        if($logs){
            foreach($logs as $key=>$valu){
                
                $logUpdated[] = $valu;
                
            }
        }
        
        $challenges = DB::table('challenges')
            ->select('challenges.*')
            ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
            ->where([['user_challenges.user_id', '=', auth()->user()->id], ['user_challenges.status', '=', 1]])
            ->get(); //print_r($challenges);
            $ChallengeTotal = array();
        foreach ($challenges as $key => $value) {
            if(@$value->price_type != 'default'){
                $ChallengeTotal[] = $value;
            }

        }

        $badgeLimit = array();
        //print_r($challengeCoveredLogs);
        //foreach ($challengeCoveredLogs as $key => $value) {
            
            $badges = DB::table('badges')
                ->select('badges.*')
                ->where([["badges.badge_type", '=', 'distance'], ["badges.condition_limit", '>', $totalDistancreTravel]])
                ->get();
            $badges = json_encode($badges);
            $badges = json_decode($badges, true);

            if($badges){
                foreach($badges as $key=>$val){
                    $badgeLimit[$val['id']] = $val['condition_limit'] - $totalDistancreTravel;
                }
            }
        //}
        
        asort($badgeLimit);
        //print_r($badgeLimit);

        $badgeLimit = reset($badgeLimit);

        $badges = DB::table('badges')
            ->select('badges.*')
            //->join('user_badges', 'badges.id', '=', 'user_badges.badge_id')
            //->where([["user_badges.user_id", '=', auth()->user()->id]])
            ->get();  

        $LogOfDay = Challenge_logs::select(
                    'challenge_logs.*',
                    DB::raw("DAY(created_at) as day"),
                    DB::raw("MONTHNAME(created_at) as month"),
                    DB::raw("YEAR(created_at) as year")
                )
                ->where('user_id', auth()->user()->id)
                //->whereYear('created_at', date('Y'))
                ->groupBy('created_at')
                ->groupBy('activity_id')
                ->get()
                ->toArray();
        $bestDay = array();
        foreach($LogOfDay as $key => $value){
            $date = Carbon::parse(@$value['startDateTime'], 'UTC')->setTimezone($timezone);
                
                    $start  = new Carbon($date);
                    $strt = $start->toDateTimeString();
                    $strt = Carbon::createFromTimestamp(strtotime($strt))->format('Y-m-d'); 
             $bestDay[$strt] = @$bestDay[$strt] + @$value['distance_travelled'];
        }

        //print_r($bestDay);
        arsort($bestDay); 
        $bestOfDay = 0;
        $i = 1;
        foreach($bestDay as $key => $value){
            if($i == 1){
                $bestOfDay = $value;
            }
            $i++;
        }

        $LogOfWeek = Challenge_logs::select(
                    'challenge_logs.*',
                    DB::raw("WEEK(startDateTime) as week_id"),
                    DB::raw("YEAR(startDateTime) as year")
                )
                ->where('user_id', auth()->user()->id)
                //->whereYear('created_at', date('Y'))
                ->groupBy('created_at')
                ->groupBy('activity_id')
                ->get()
                ->toArray();
        $bestWeek = array();
        foreach($LogOfWeek as $key => $value){
            $date = Carbon::parse(@$value['startDateTime'], 'UTC')->setTimezone($timezone);

            $weekNumber = $date->weekNumberInMonth;
            $startWeek = Carbon::parse(@$value['startDateTime'], 'UTC')->setTimezone($timezone)->startOfWeek();
            $endWeek = Carbon::parse(@$value['startDateTime'], 'UTC')->setTimezone($timezone)->endOfWeek();
             
                    $start  = new Carbon($date);
                    $strt = $start->toDateTimeString();
                    $strt = Carbon::createFromTimestamp(strtotime($strt))->format('Y-m-d'); 
             $bestWeek[$startWeek.$endWeek] = @$bestWeek[$startWeek.$endWeek] + @$value['distance_travelled'];
        }
        
        

        arsort($bestWeek);
        $bestOfWeek = 0;
        $i = 1;
        foreach($bestWeek as $key => $value){
            if($i == 1){
                $bestOfWeek = $value;
            }
            $i++;
        }

        $items = Challenge_logs::select(
                    'challenge_logs.*',
                    DB::raw("MONTHNAME(startDateTime) as month_name"),
                    DB::raw("YEAR(startDateTime) as year")
                )
                ->where('user_id', auth()->user()->id)
                //->whereYear('created_at', date('Y'))
                ->groupBy('created_at')
                ->groupBy('activity_id')
                ->get()
                ->toArray();
        $bestMonth = array();
        foreach($items as $key => $value){
            $date = Carbon::parse(@$value['startDateTime'], 'UTC')->setTimezone($timezone);

            $weekNumber = $date->weekNumberInMonth;
            $startMonth = Carbon::parse(@$value['startDateTime'], 'UTC')->setTimezone($timezone)->startOfMonth();
            $endMonth = Carbon::parse(@$value['startDateTime'], 'UTC')->setTimezone($timezone)->endOfMonth();
             
             $bestMonth[$startMonth.$endMonth] = @$bestMonth[$startMonth.$endMonth] + @$value['distance_travelled'];
        }


        arsort($bestMonth);
        $bestOfMonth = 0;
        $i = 1;
        foreach($bestMonth as $key => $value){
            if($i == 1){
                $bestOfMonth = $value;
            }
            $i++;
        }   
        //echo '<pre>'; print_r($bestOfMonth); echo '</pre>';


        $arr['user'] = $users;
        $arr['user_info'] = $user_info;
        $arr['address'] = $address;
        $arr['past_challenge'] = $past;
        $arr['current_challenge'] = $current;
        $arr['weekDistance'] = $weekDistance;
        $arr['monthDistance'] = $monthDistance;
        $arr['yearDistance'] = $yearDistance;
        $arr['totalDistancreTravel'] = $totalDistancreTravel;
        $arr['info'] = $info;
        $arr['challenges'] = $ChallengeTotal;
        $arr['badges']     = $badges;
        $arr['badgeLimit']     = $badgeLimit;
        $arr['timezone']     = $timezone;
        $arr['logs'] = $logs;
        $arr['distanceOfTheDay'] = $bestOfDay;
        $arr['bestMonth'] = $bestOfMonth;
        $arr['bestOfWeek'] = $bestOfWeek;
        $arr['user_id'] = auth()->user()->id;
        $arr['User_challenges'] = new User_challenges();
        $arr['User_badges'] = new User_badges();
        $arr['Challenge_infos'] = new Challenge_infos();
        //print_r($arr);
        return view::make('/frontend/dashboard', $arr);
    }

    public function add_challenge_log(Request $request){

        $validator = Validator::make($request->all(), [
            //'challenges'     => 'required',
            'activity'     => 'required',
            'startDateTime'     => 'required',
            'distance'     => 'required',
            /*'hour'     => 'required',
            'minute'     => 'required',
            'second'     => 'required',
            'calories'     => 'required'*/
        ]);

        if($validator->fails()){
            return redirect(route('frontend.dashboard'))->withInput()->withErrors($validator); 
        }  

        $timezone = $request->session()->get('timezone');
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

       $input = $request->all();

        $challenges = DB::table('challenges')
            ->select('challenges.*')
            ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
            ->where([['user_challenges.user_id', '=', auth()->user()->id], ['challenges.price_type', '=', 'default']])
            ->first();

           

        

        $startDateTime = Carbon::parse($input['startDateTime'], $timezone)->setTimezone('UTC');
        
        $minute = $input['minute'] ? $input['minute'] : 0;
        $second = $input['second'] ? $input['second'] : 0;
        $hour = $input['hour'] ? $input['hour'] : 0;
         
        $data = array();
        if(@$input['challenges']){
            foreach($input['challenges'] as $key => $val){
                $challengesById = DB::table('challenges')
                    ->select('challenges.*')
                    ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
                    ->where([['user_challenges.user_id', '=', auth()->user()->id], ['challenges.id', '=', $val]])
                    ->first();

                $event_start_date = Carbon::parse($challengesById->event_start_date, 'UTC')->setTimezone($timezone);
                $newDate = '';
                if($event_start_date){
                $tempDate = explode(' ', $event_start_date); //print_r($tempDate);
                  $tempDate = explode('-', $tempDate['0']); //print_r($tempDate);
                  $stat = checkdate((int)$tempDate[1], (int)$tempDate[2], (int)$tempDate[0]);
                  if($stat){ //echo $event_start_date .'>'. $startDateTime; echo $event_start_date > $startDateTime; die();
                    if($event_start_date > $startDateTime){
          
                        //$arr = array("status" => 3, "message" => "The challenge will start on ".$challenge['event_start_date'].".");
                        return redirect(route('frontend.dashboard'))->withInput()->withErrors(['error' => "The log cannot be updated. Please make that the log is added for a date after the start date/time of the challenge."]);

                    }
                  }
                }

                $ChallengeInfos = new Challenge_infos();
                $check = $ChallengeInfos->checkChallengeMilestone($val, $input['distance'], auth()->user()->id, $startDateTime);
                $check = json_decode($check, true);
                if($check['status'] == 2 || $check['status'] == 3){
                    return redirect(route('frontend.dashboard'))->withInput()->withErrors(['error' => $check['message']]);
                }

                

                
                if($check['status'] != 2){
                    
                    $data[] = array(
                            "user_id" => auth()->user()->id,
                            "participation_id" => $val,
                            "activity" => $input['activity'],
                            "startDateTime" => $startDateTime,
                            "endTime" => $hour.':'.$minute.':'.$second,
                            "distance_travelled" => $input['distance'],
                            "calories" => @$input['calories']
                    );
                }
                
            }
        }

        $ChallengeInfos = new Challenge_infos();
        $check = $ChallengeInfos->checkChallengeMilestone($challenges->id, $input['distance'], auth()->user()->id, $startDateTime);
        $check = json_decode($check, true);
        if($check['status'] == 2 || $check['status'] == 3){
            return redirect(route('frontend.dashboard'))->withInput()->withErrors(['error' => $check['message']]);
        }

        
        if($check['status'] != 2){

            $data[]= array(
                    "user_id" => auth()->user()->id,
                    "participation_id" => $challenges->id,
                    "activity" => $input['activity'],
                    "startDateTime" => $startDateTime,
                    "endTime" => $hour.':'.$minute.':'.$second,
                    "distance_travelled" => $input['distance'],
                    "calories" => @$input['calories']
                    );

        }

        //print_r($data); die();
          
        $ch = Challenge_logs::insert($data);

        if($ch){
            if($input['challengeDetails']){
                return redirect(@$input['challengeDetails'])->with(['message' => 'Log added successfully.']);
            }
            return redirect(route('frontend.dashboard'))->with(['message' => 'Log added successfully.']);
        }else{
            return redirect(route('frontend.dashboard'))->withInput()->withErrors(['error' => 'Log is not added successfully.']); 
        }
        
        
    }

    public function update_challenge_log(Request $request){

        $validator = Validator::make($request->all(), [
            'challenge_id'     => 'required',
            'activity'     => 'required',
            'startDateTime'     => 'required',
            'distance'     => 'required',
        ]);

        if($validator->fails()){
            return redirect(route('frontend.dashboard'))->withInput()->withErrors($validator); 
        }  

        $timezone = $request->session()->get('timezone');
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

        $input = $request->all(); 

        // $startDateTime = str_replace('/', '-', $input['startDateTime']);
        // $startDateTime = strtotime($startDateTime);
        // $startDateTime = date('Y-m-d H:i:s', $startDateTime);

        $startDateTime = Carbon::parse($input['startDateTime'], $timezone)->setTimezone('UTC');

        $minute = $input['minute'] ? $input['minute'] : 0;
        $second = $input['second'] ? $input['second'] : 0;
        $hour = $input['hour'] ? $input['hour'] : 0;

        $chLog = Challenge_logs::where("id", $input['challenge_id'])->first();
        $chLog = json_encode($chLog);
        $chLog = json_decode($chLog, true);
        
        //print_r($chLog); die();
        
        $challengesById = DB::table('challenges')
                    ->select('challenges.*')
                    //->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
                    ->where([['challenges.id', '=', $chLog['participation_id']]])
                    ->first();

                    

        $event_start_date = Carbon::parse($challengesById->event_start_date, 'UTC')->setTimezone($timezone);
        $newDate = '';
        if($event_start_date){
        $tempDate = explode(' ', $event_start_date); 
          $tempDate = explode('-', $tempDate['0']); 
          $stat = checkdate((int)$tempDate[1], (int)$tempDate[2], (int)$tempDate[0]);
          if($stat){ //echo $event_start_date .'>'. $startDateTime; echo $event_start_date > $startDateTime; die();
            if($event_start_date > $startDateTime){
  
                //$arr = array("status" => 3, "message" => "The challenge will start on ".$challenge['event_start_date'].".");
                return redirect(route('frontend.dashboard'))->withInput()->withErrors(['error' => "The log cannot be updated. Please make that the log is added for a date after the start date/time of the challenge."]);

            }
          }
        }

        if($chLog){
            $created_at = Carbon::parse($chLog['created_at'], $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
            $chLogByDate = Challenge_logs::where("created_at", $chLog['created_at'])->get();
            $chLogByDate = json_encode($chLogByDate);
            $chLogByDate = json_decode($chLogByDate, true);

            $data = array();

            foreach($chLogByDate as $key => $value){

                $chLogUpdate = Challenge_logs::where("id", $value['id'])->first();
                $distance_travelled = $chLogUpdate->distance_travelled;
                if($input['distance'] > $distance_travelled){
                    $dist  = $input['distance'] - $distance_travelled;

                    $ChallengeInfos = new Challenge_infos();
                    $check = $ChallengeInfos->checkChallengeMilestone($chLogUpdate->participation_id, $dist, auth()->user()->id, $startDateTime);
                    $check = json_decode($check, true);
                    if($check['status'] == 2 || $check['status'] == 3){
                        return redirect(route('frontend.dashboard'))->withInput()->withErrors(['error' => $check['message']]);
                    }

                }elseif($input['distance'] < $distance_travelled){

                    $dist  = $distance_travelled - $input['distance'];

                    $ChallengeInfos = new Challenge_infos();
                    $check = $ChallengeInfos->checkAndActivateChallenge($chLogUpdate->participation_id, $dist, auth()->user()->id, $chLogUpdate->startDateTime);
                    $check = json_decode($check, true);
                    

                }
                
                
                /*$chLogUpdate->user_id = auth()->user()->id;
                $chLogUpdate->activity = $input['activity'];
                $chLogUpdate->startDateTime = $startDateTime;
                $chLogUpdate->endTime = $hour.':'.$minute.':'.$second;
                $chLogUpdate->distance_travelled = $input['distance'];
                $chLogUpdate->calories = @$input['calories'];
                $chLogUpdate->update();*/
            }

                $data["user_id"] = auth()->user()->id; 
                $data["activity"] = $input['activity']; 
                $data["startDateTime"] = $startDateTime; 
                $data["endTime"] = $hour.':'.$minute.':'.$second;
                $data["distance_travelled"] = $input['distance'];
                $data["calories"] = @$input['calories'];

            $ch = Challenge_logs::where("created_at", $chLog['created_at'])->update($data);

        }
        
        if(@$input['challengeDetails']){
            return redirect($input['challengeDetails'])->with(['message' => 'Log added successfully.']);
        }
        

        return redirect(route('frontend.dashboard'))->with(['message' => 'Log updated successfully.']);
        
        
    }

    public function delete_challenge_log(Request $request){

        $validator = Validator::make($request->all(), [
            'challenge_id'     => 'required'
        ]);

        if($validator->fails()){
            return redirect(route('frontend.dashboard'))->withInput()->withErrors($validator); 
        }  

        $input = $request->all();

        $chLog = Challenge_logs::where("id", $input['challenge_id'])->first();
        /*$chLog = json_encode($chLog);
        $chLog = json_decode($chLog, true);*/
        if($chLog){
            $chLogByDate = Challenge_logs::where("created_at", $chLog->created_at)->get();
            $chLogByDate = json_encode($chLogByDate);
            $chLogByDate = json_decode($chLogByDate, true);

            foreach($chLogByDate as $key => $value){
                $chLogDelete = Challenge_logs::find($value['id']);

                //$startDateTime = Carbon::parse($chLogDelete->startDateTime, $timezone)->setTimezone('UTC');

                $ChallengeInfos = new Challenge_infos();
                $check = $ChallengeInfos->checkAndActivateChallenge($chLogDelete->participation_id, $chLogDelete->distance_travelled, auth()->user()->id, $chLogDelete->startDateTime);
                $check = json_decode($check, true);
                
                $chLogDelete->delete();


            }

        }

        if(@$input['challengeDetails']){
            return redirect($input['challengeDetails'])->with(['message' => 'Log deleted successfully.']);
        }
            
        return redirect(route('frontend.dashboard'))->with(['message' => 'Log deleted successfully.']);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        if (Auth::check() || Auth::viaRemember()) {

           return redirect()->guest(route( 'frontend.dashboard' ));

        }else{

          

        }

        $challenge = DB::table('challenges')->select('*')->get()->toArray();
        
        $info = array();
        foreach($challenge as $k=>$val){
            $challenges_info = DB::table('challenge_infos')->select('*')->where('challenge_infos.challenge_id', $val->id)->get()->toArray();
            $info[$k]['challenges'] = $val;
            $info[$k]['challenge_info'] = $challenges_info;
        }
        
        
        //echo '<pre>'; print_r($info); echo '</pre>';

        return view::make('/frontend/home');
    }

    public function uploadProfileFormSubmit(Request $request, $id){

         if($request->profile_pic){
            $ext    =   $request->profile_pic->getClientOriginalExtension();
            $file = date('YmdHis').rand(1,99999).'.'.$ext;
            $request->profile_pic->storeAs('public/challenge/challenge_image',$file);
            $file   =   '/storage/challenge/challenge_image/'.$file;
        }else{
            $file   =   '';
        }


         /*if($request->profile_pic != ''){        
              $path = storage_path().'/uploads/images/';
              
              $result = File::exists(storage_path('app/public/user/profile_image'));
                if (!$result) {
                    File::makeDirectory(storage_path('app/public/user/profile_image'));
                }
                $logo = '';
                $avatarName = 'profile_pic.png';
                if($request->hasFile('profile_pic')){
                    $profilePic = $request->file('profile_pic');
                    $avatarName = time().'profile_pic.'.$profilePic->getClientOriginalExtension();

                    $request->profile_pic->storeAs('user/profile_image', $avatarName);
                    $destinationPath = url('storage/user/profile_image');
                    $logo = 'storage/user/profile_image/'.$avatarName;
                }
              
         }*/

         $user = User::where('id', $id)->first();
              $user->profile_pic = $file;
              $user->update();

              return redirect(route('frontend.profile'))->with(['message' => 'Profile picture uploaded successfully.']);
    }

    public function login(Request $request)
    {  
        
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|min:8|max:20'
        ]);

        if($validator->fails()){
            return redirect()->to(route('frontend.home').'#exampleModal')->withInput()->withErrors($validator); 
        }  
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'user_type' => 2 ], ($request->remember == 'on') ? true : false)){
            $user = Auth::user();
            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->name);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('email', $user->email);  

            $user_infos = User_infos::where([['user_id', '=', $user->id], ['meta_name', '=', 'timezone']])->first();
            $user_infos = json_encode($user_infos);
            $user_infos = json_decode($user_infos, true);
            $timezone = env('DEFAULT_TIMEZONE');
            $redirectTime = 0;
            if($user_infos){
                $timezone = $user_infos['meta_value'];
                if($timezone){
                    $request->session()->put('timezone', $timezone); 
                }else{
                    $redirectTime = 1;
                }
                
            }

            $challenge = Challenges::where('price_type', 'default')->inRandomOrder()->first();

            $User_challenges = User_challenges::where('user_id', $user->id)->first();
            $User_challenges = json_encode($User_challenges);
            $User_challenges = json_decode($User_challenges, true);
            if(!$User_challenges){
                $User_challenges = new User_challenges();
                $User_challenges->challenge_id = $challenge->id;
                $User_challenges->user_id = $user->id;
                $User_challenges->payment_type = 'free';
                $User_challenges->payment_status = 'Success';
                $User_challenges->activate_date = Carbon::now()->toDateTimeString();
                $User_challenges->status = 1;
                $User_challenges->save();
            }

            if($redirectTime){
                return redirect()->route('frontend.profile');
            }else{
                return redirect()->route('frontend.home');
            }

            
            
        }

        return redirect()->to(route('frontend.home'))->withInput()->withErrors(["error" => "The Email Address or Password you have entered didn't match. Please try again."]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->guest(route( 'frontend.home' ));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            'last_name'     => 'required',
            'timezone' => 'required',
            'email'     => 'required|email|unique:users',
            'password' => 'required|required_with:password_confirmation|string|confirmed|min:8|max:20',
            'password_confirmation' => 'required',
        ]);

        if($validator->fails()){
            return redirect()->to(route('frontend.home').'#signup-modal')->withInput()->withErrors($validator); 
        }  
        
        $user = new User;
        $user->name = $request->first_name.' '.$request->last_name;
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->password   = bcrypt($request->password);
        $user->user_type  = 2;
        $user->save();

        $User_infos = new User_infos;
        $User_infos->updateMetaValue($user->id, 'timezone', $request->timezone);

        $challenge = Challenges::where('price_type', 'default')->inRandomOrder()->first();

        $User_challenges = new User_challenges();
        $User_challenges->challenge_id = $challenge->id;
        $User_challenges->user_id = $user->id;
        $User_challenges->payment_type = 'free';
        $User_challenges->payment_status = 'Success';
        $User_challenges->activate_date = Carbon::now()->toDateTimeString();
        $User_challenges->status = 1;
        $User_challenges->save();

        Mail::to($user)->send(new NewUserRegistration($request->first_name.' '.$request->last_name, route('frontend.home')));

        $adminUser = user::where('user_type', 1)->first();

        Mail::to($adminUser)->send(new NewUserRegistrationToAdmin($request->first_name.' '.$request->last_name, route('admin.users.edit', $user->id)));

        if(Auth::attempt(['email' => request('email'), 'password' => request('password'), 'user_type' => 2 ])){
            $user = Auth::user();
            $request->session()->put('id', $user->id);
            $request->session()->put('name', $user->name);
            $request->session()->put('profile_pic', $user->profile_pic);
            $request->session()->put('email', $user->email);  

            $user_infos = User_infos::where([['user_id', '=', $user->id], ['meta_name', '=', 'timezone']])->first();
            $user_infos = json_encode($user_infos);
            $user_infos = json_decode($user_infos, true);
            if($user_infos){
                $request->session()->put('timezone', $user_infos['meta_value']); 
            }

            return redirect()->route('frontend.home');
            
        }

        return redirect()->route('frontend.home');
    }

    public function updateBadgeSeenStatus(Request $request)
    {
        if($request->has('id')) {
            // $badge = DB::table('badges')->where('id', '=', $request->id)->first();
            // if($badge) {
            //     DB::table('badges')->where('id', '=', $badge->id)->update([
            //         'is_seen' => 1
            //     ]);

            //     return response()->json(array('msg'=> "Updated!"), 200);
            // }

            $user_badge = DB::table('user_badges')
                ->where('badge_id', '=', $request->id)
                ->where('user_id', '=', auth()->user()->id)
                ->where('badge_type', '!=', 'challenge_milestone')
                ->first();

            if($user_badge) {
                DB::table('user_badges')
                ->where('id', '=', $user_badge->id)
                ->update([
                    'is_seen' => 1
                ]);

                return response()->json(array('msg'=> "Updated!"), 200);
            }
        }
    }

}
