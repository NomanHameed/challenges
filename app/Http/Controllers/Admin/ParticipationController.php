<?php

namespace App\Http\Controllers\Admin;

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

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\PasswordResetRequest;
use App\Models\Challenge_logs;
use App\Models\Challenge_infos;
use App\Models\Challenges;
use App\Models\User_challenges;
use App\Models\Badges;
use App\Models\Challenge_milestones;
use App\Models\Timezones;
use Carbon\Carbon;
use File;
use CodeToad\Strava\Strava;
use App\Models\Strava_user_credentials;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Notifications\SendMonthlyLogs;
use App\Services\StravaWebhookService;
use Redirect;

class ParticipationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {

        

        //die('Orders will be displayed here !');
        $arr['participations']  =   DB::table('user_challenges')->select('*')->get();
        foreach($arr['participations'] as $participation){
            $user_name = DB::table('users')->where('id',$participation->user_id)->select('name')->get();
            $participation->user_name = $user_name[0]->name;
            $challenge_name = DB::table('challenges')->where('id',$participation->challenge_id)->select('name')->get();
            $participation->challenge_name = $challenge_name[0]->name;
            
        }

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $arr['timezone']  =   $timezone;

        
        return view('backend.participations.index')->with($arr);
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
    public function edit(Request $request, $id)
    {

        $userList           = DB::table('users')->where('user_type', '2')->get()->toArray();
        $data               =   DB::table('user_challenges')->where('id', '=', $id)->get()->toArray();
        $challenge_data     =   DB::table('challenges')->where('id', '=', $data[0]->challenge_id)->get()->toArray();
        $challenge_distance =   DB::table('challenge_infos')->where('challenge_id',  $data[0]->challenge_id)->where('meta_name', 'total_distance')->get()->toArray();
        $user_data          =   DB::table('users')->where('id', '=', $data[0]->user_id)->get()->toArray();
        $log_data           =   DB::table('challenge_logs')->where('user_id', '=', $id)->get()->toArray();
        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $now = Carbon::now($timezone)->toDateTimeString();

        $date = Carbon::parse($now, $timezone)->setTimezone('UTC');

        $event_end_date = $challenge_data[0]->event_end_date;
        $past = 0;
        if($event_end_date<$date){
            $past = 1;
        }

        /*$challengehId = $data[0]->challenge_id

        $challenge = DB::table('challenges')->select('challenges.*')
                    ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
                    ->where([['user_challenges.user_id', '=', $id], ['challenges.id', '=', $challengehId]])
                    ->first();
        $challenge = json_encode($challenge);
        $challenge = json_decode($challenge, true); */


        $arr['challenge_name']      =   $challenge_data[0]->name;
        $arr['participation_id']    =   $id;
        $arr['challenge_image']     =   $challenge_data[0]->image;
        $arr['past']                =   $past;
        $arr['total_distance']      =   @$challenge_distance[0]->meta_value;
        $arr['user_name']           =   $user_data[0]->name;
        $arr['user_image']          =   $user_data[0]->profile_pic;
        $arr['challenge_logs']      =   $log_data;

        $travelled_distance_by_now  =   0;

        foreach($log_data as $log){
            $travelled_distance_by_now = $travelled_distance_by_now + $log->distance_travelled;
        }

        $remaining_distance = $challenge_distance[0]->meta_value - $travelled_distance_by_now;

        $arr['total_distance_travelled']    =   $travelled_distance_by_now;
        $arr['remaining_distance']          =   $remaining_distance;

        $arr['userList']          =   $userList;
        
        return view('backend.participations.edit')->with($arr);
    }

    public function transferOwnership(Request $request){

        DB::table('user_challenges')->where("id",  $request->participation_id)->update([
            
                'user_id'                  =>   $request->new_user,
            
        ]);

        return redirect()->back()->with('success', 'Ownership transfered.');  

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

    public function challengesLog(Request $request, $challenge_id, $user_id)
    {
        $user_infos = User_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
        $user_infos = json_encode($user_infos);
        $user_infos = json_decode($user_infos, true);
        $timezone = env('DEFAULT_TIMEZONE');
        if($user_infos){
            $timezone = $user_infos['meta_value'];
        }

        $userList           = DB::table('users')->where('user_type', '2')->get()->toArray();
        $data               =   DB::table('user_challenges')->where('id', '=', $user_id)->get()->toArray();
        $challenge_data     =   DB::table('challenges')->where('id', '=', $challenge_id)->first();
        $challenge_distance =   DB::table('challenge_infos')->where('challenge_id',  $challenge_id)->where('meta_name', 'total_distance')->get()->toArray();
        $user_data          =   DB::table('users')->where('id', '=', $user_id)->get()->toArray();
        //$log_data           =   DB::table('challenge_logs')->where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get()->toArray();

        

        $date = Carbon::now($timezone)->toDateTimeString();
         
        $event_end_date = $challenge_data->event_end_date;
        $past = 0;
        if($event_end_date<$date){
            $past = 1;
        }

        /*$challenge = DB::table('challenges')->select('challenges.*')
                    ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
                    ->where([['user_challenges.user_id', '=', $user_id], ['challenges.id', '=', $challenge_id]])
                    ->first();
        $challenge = json_encode($challenge);
        $challenge = json_decode($challenge, true);*/ 

        $Challenge_infos = new Challenge_infos;
        $Challenge_logs = new Challenge_logs; 

        $challengeDistance = $Challenge_infos->getChallengeDistance($challenge_id); 

        /*$current_month =DB::table('challenge_logs')->select('challenge_logs.*')->where([['user_id', '=', $user_id], ['participation_id', '=', $challenge_id]])->whereBetween('startDateTime', [Carbon::now($timezone)->startOfMonth(), Carbon::now($timezone)->endOfMonth()])->groupBy('created_at')->groupBy('challenge_logs.activity_id')->get();
        $monthDistance = 0;
        if($current_month){
            foreach($current_month as $key => $value){
                $monthDistance = $monthDistance + $value->distance_travelled;
            }
        } */

        $category = '';
        
        $info = array();
        //if($challenge){

            $challenges_info = DB::table('challenge_infos')->select('*')->where('challenge_infos.challenge_id', $challenge_id)->get();
            $challenges_info = json_encode($challenges_info);
            $challenges_info = json_decode($challenges_info, true); 
            //$info['challenges'] = $challenge;
            $info['challenge_info'] = $challenges_info;

            

            foreach($info['challenge_info'] as $Key=>$value){
               
               if($value['meta_name'] == 'category'){
                  $category = $value['meta_value'];
               }
               
            }

        //}

        $coverageDistance = $Challenge_logs->getChallengeCoverageDistance($challenge_id, $category, $user_id); 

        $logs = array();

        $challenge_logs = DB::table('challenges')->select('challenges.name', 'challenge_logs.*')
                         ->join('challenge_logs', 'challenges.id', '=', 'challenge_logs.participation_id')
                         ->where([['challenge_logs.user_id', '=', $user_id], ['challenge_logs.participation_id', '=', $challenge_id]])
                         ->groupBy('challenge_logs.created_at')
                         ->groupBy('challenge_logs.activity_id')
                         ->get();

        $challenge_logs = json_encode($challenge_logs);
        $challenge_logs = json_decode($challenge_logs, true);

        //echo '<pre>';print_r($challenge_logs);  echo '</pre>';

        //$challengeCoveredLogs = array();
        
        $travelled_distance_by_now  =   0;
        
        if($challenge_logs){
            foreach($challenge_logs as $key=>$value){

                $travelled_distance_by_now = $travelled_distance_by_now + $value['distance_travelled'];

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

                

                //$idName = $value['name'];

                //$challengeCoveredLogs[$idName] = @$challengeCoveredLogs[$idName] + $value['distance_travelled'];
            }
        }


        //echo $travelled_distance_by_now;
        /*$userChallenge = DB::table('users')->select('user_challenges.*', 'users.profile_pic')
                    ->join('user_challenges', 'users.id', '=', 'user_challenges.user_id')
                    ->where([['user_challenges.challenge_id', '=', $challenge_id]])
                    ->get();
        $userChallenge = json_encode($userChallenge);
        $userChallenge = json_decode($userChallenge, true); */
        //$challengeLogList = array();
        

       /* if($userChallenge){
            foreach($userChallenge as $key=>$value){
                $totalChallengeLog = 0;
                $challenge_log_by_user = DB::table('challenges')->select('challenges.name', 'challenge_logs.*')
                         ->join('challenge_logs', 'challenges.id', '=', 'challenge_logs.participation_id')
                         ->where([['challenge_logs.user_id', '=', $value['user_id']], ['challenge_logs.participation_id', '=', $challenge_id]])
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
            }
            
        }*/




        $arr['challenge_name']      =   $challenge_data->name;
        $arr['participation_id']    =   $challenge_id;
        $arr['challenge_image']     =   $challenge_data->image;
        $arr['price_type']     =   $challenge_data->price_type;
        $arr['past']                =   $past;
        $arr['total_distance']      =   @$challenge_distance[0]->meta_value;
        $arr['user_name']           =   $user_data[0]->name;
        $arr['user_image']          =   $user_data[0]->profile_pic;
        $arr['challenge_logs']      =   $challenge_logs;

        //echo $challenge_distance[0]->meta_value;
        $remaining_distance = @$challenge_distance[0]->meta_value - $travelled_distance_by_now;

        $arr['total_distance_travelled']    =   $travelled_distance_by_now;
        $arr['remaining_distance']          =   $remaining_distance;

        $arr['userList']          =   $userList;

        //$arr['info'] = $info;
        $arr['Challenge_infos'] = $Challenge_infos;
        $arr['Challenge_logs'] = $Challenge_logs;
        //$arr['monthDistance'] = $monthDistance;
        $arr['id'] = $challenge_id;
        $arr['user_id'] = $user_id; 
        //$arr['challengeDistance'] = @$challengeDistance->meta_value;
        //$arr['coverageDistance'] = $coverageDistance;
        //$arr['challengeLogList'] = $challengeLogList;
        $arr['timezone'] = $timezone;
        $arr['now'] = Carbon::now($timezone)->toDateTimeString();
        $arr['logs'] = json_encode($logs);
        
        return view('backend.participations.edit')->with($arr);
    }

    public function add_challenge_log(Request $request, $challenge_id, $user_id){

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
            return Redirect::back()->withInput()->withErrors($validator); 
        }  

       $input = $request->all();

        $challenges = DB::table('challenges')
            ->select('challenges.*')
            ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
            ->where([['user_challenges.user_id', '=', $user_id], ['challenges.price_type', '=', 'default']])
            ->first();

        $user_infos = User_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
        $user_infos = json_encode($user_infos);
        $user_infos = json_decode($user_infos, true);
        $timezone = '';
        if($user_infos){
            $timezone = $user_infos['meta_value'];
        }
           

        $startDateTime = Carbon::parse($input['startDateTime']);

        if(Carbon::now($timezone)->toDateTimeString() < $startDateTime){
          
            //$arr = array("status" => 3, "message" => "The challenge will start on ".$challenge['event_start_date'].".");
            return Redirect::back()->withInput()->withErrors(['error' => "The log of future date or time can't be added."]);

        }

        $challengesById = DB::table('challenges')
                    ->select('challenges.*')
                    ->join('user_challenges', 'challenges.id', '=', 'user_challenges.challenge_id')
                    ->where([['user_challenges.user_id', '=', $user_id], ['challenges.id', '=', $challenge_id]])
                    ->first();

        $event_start_date = Carbon::parse($challengesById->event_start_date, 'UTC')->setTimezone(admin_timezone());

        $event_end_date = Carbon::parse($challengesById->event_end_date, 'UTC')->setTimezone(admin_timezone());

        $newDate = '';
        if($event_start_date){
        $tempDate = explode(' ', $event_start_date); //print_r($tempDate);
          $tempDate = explode('-', $tempDate['0']); //print_r($tempDate);
          $stat = checkdate((int)$tempDate[1], (int)$tempDate[2], (int)$tempDate[0]);
          if($stat){ //echo $event_start_date .'>'. $startDateTime; echo $event_start_date > $startDateTime; die();
            if($event_start_date > $startDateTime){
  
                //$arr = array("status" => 3, "message" => "The challenge will start on ".$challenge['event_start_date'].".");
                return Redirect::back()->withInput()->withErrors(['error' => "The log cannot be updated. Please make that the log is added for a date after the start date/time of the challenge."]);

            }
          }
        }

        $newDate = '';
        if($event_end_date){
        $tempDate = explode(' ', $event_end_date); //print_r($tempDate);
          $tempDate = explode('-', $tempDate['0']); //print_r($tempDate);
          $stat = checkdate((int)$tempDate[1], (int)$tempDate[2], (int)$tempDate[0]);
          if($stat){ //echo $event_start_date .'>'. $startDateTime; echo $event_start_date > $startDateTime; die();
            if($event_end_date < $startDateTime){
  
                //$arr = array("status" => 3, "message" => "The challenge will start on ".$challenge['event_start_date'].".");
                return Redirect::back()->withInput()->withErrors(['error' => "The log cannot be updated. Please make that the log is added for a date before the end date/time of the challenge."]);

            }
          }
        }


        $minute = $input['minute'] ? $input['minute'] : 0;
        $second = $input['second'] ? $input['second'] : 0;
        $hour = $input['hour'] ? $input['hour'] : 0;
         
        $data = array();
        
        $ChallengeInfos = new Challenge_infos();
        $check = $ChallengeInfos->checkChallengeMilestone($challenge_id, $input['distance'], $user_id, $startDateTime);
        $check = json_decode($check, true);
        if($check['status'] == 2 || $check['status'] == 3){
            return Redirect::back()->withInput()->withErrors(['error' => $check['message']]);
        }

        
        if($check['status'] != 2){
            
            $data[] = array(
                    "user_id" => $user_id,
                    "participation_id" => $challenge_id,
                    "activity" => $input['activity'],
                    "startDateTime" => $startDateTime,
                    "endTime" => $hour.':'.$minute.':'.$second,
                    "distance_travelled" => $input['distance'],
                    "calories" => @$input['calories']
            );
        }
                
            
        if($challenge_id != $challenges->id){
            $ChallengeInfos = new Challenge_infos();
            $check = $ChallengeInfos->checkChallengeMilestone($challenges->id, $input['distance'], $user_id, $startDateTime);
            $check = json_decode($check, true);
            if($check['status'] == 2 || $check['status'] == 3){
                return Redirect::back()->withInput()->withErrors(['error' => $check['message']]);
            }

            
            if($check['status'] != 2){

                $data[]= array(
                        "user_id" => $user_id,
                        "participation_id" => $challenges->id,
                        "activity" => $input['activity'],
                        "startDateTime" => $startDateTime,
                        "endTime" => $hour.':'.$minute.':'.$second,
                        "distance_travelled" => $input['distance'],
                        "calories" => @$input['calories']
                        );

            }
        }

        //print_r($data); die();
          
        $ch = Challenge_logs::insert($data);

        if($ch){
            if($input['challengeDetails']){
                return redirect(@$input['challengeDetails'])->with(['message' => 'Log added successfully.']);
            }
            return Redirect::back()->with(['message' => 'Log added successfully.']);
        }else{
            return Redirect::back()->withInput()->withErrors(['error' => 'Log is not added successfully.']); 
        }
        
        
    }

    public function update_challenge_log(Request $request, $user_id){

        $validator = Validator::make($request->all(), [
            'challenge_id'     => 'required',
            'activity'     => 'required',
            'startDateTime'     => 'required',
            'distance'     => 'required',
        ]);

        if($validator->fails()){
            return redirect(route('frontend.dashboard'))->withInput()->withErrors($validator); 
        }  

       $input = $request->all();

        $user_infos = User_infos::where([['user_id', '=', $user_id], ['meta_name', '=', 'timezone']])->first();
        $user_infos = json_encode($user_infos);
        $user_infos = json_decode($user_infos, true);
        $timezone = '';
        if($user_infos){
            $timezone = $user_infos['meta_value'];
        }
           

        $startDateTime = Carbon::parse($input['startDateTime'], $timezone)->setTimezone('UTC');

        $minute = $input['minute'] ? $input['minute'] : 0;
        $second = $input['second'] ? $input['second'] : 0;
        $hour = $input['hour'] ? $input['hour'] : 0;

        $chLog = Challenge_logs::where("id", $input['challenge_id'])->first();
        $chLog = json_encode($chLog);
        $chLog = json_decode($chLog, true);
        if($chLog){
            $chLogByDate = Challenge_logs::where("created_at", $chLog['created_at'])->get();
            $chLogByDate = json_encode($chLogByDate);
            $chLogByDate = json_decode($chLogByDate, true);

            foreach($chLogByDate as $key => $value){
                $chLogUpdate = Challenge_logs::where("id", $value['id'])->first();
                $chLogUpdate->user_id = $user_id;
                $chLogUpdate->activity = $input['activity'];
                $chLogUpdate->startDateTime = $startDateTime;
                $chLogUpdate->endTime = $hour.':'.$minute.':'.$second;
                $chLogUpdate->distance_travelled = $input['distance'];
                $chLogUpdate->calories = @$input['calories'];
                $chLogUpdate->update();
            }

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
        
        if($chLog){
           
            $chLogByDate = Challenge_logs::where("created_at", $chLog->created_at)->get();
            $chLogByDate = json_encode($chLogByDate);
            $chLogByDate = json_decode($chLogByDate, true);

            foreach($chLogByDate as $key => $value){
                $chLogDelete = Challenge_logs::find($value['id']);
                $chLogDelete->delete();
            }

        }

        if(@$input['challengeDetails']){
            return redirect($input['challengeDetails'])->with(['message' => 'Log deleted successfully.']);
        }
            
        return redirect(route('frontend.dashboard'))->with(['message' => 'Log deleted successfully.']);
        
    }
}
