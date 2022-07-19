<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DateTime;
use DateTimeZone;
use Redirect;
use Carbon\Carbon;
use File;
use App\Models\User_infos;

use App\Mail\NewChallengeAssigned;
use Illuminate\Support\Facades\Mail;

class ChallengeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addLocations(Request $request)
    {
        $validatedData = $request->validate([
            'total_distance'  => 'required',
        ]);
        $challenge_id  = $request->challenge_id;

        $result = File::exists(storage_path('app/public/challenge/challenge_image/kml'));
        if (!$result) {
            File::makeDirectory(storage_path('app/public/challenge/challenge_image/kml'));
        }

        if ($request->kml_file) {
            $ext    =    $request->kml_file->getClientOriginalExtension();
            $file = date('YmdHis') . rand(1, 99999) . '.' . $ext;
            $request->kml_file->storeAs('public/challenge/challenge_image/kml', $file);

            if ($ext != 'kml') {
                $kml_file =   'storage/challenge/challenge_image/kml/' . $file;
            } else {
                $kml_file   =   'app/public/challenge/challenge_image/kml/' . $file;
            }

            $this->update_challenge_meta($challenge_id, 'kml_file', $kml_file);
        }

        $this->update_challenge_meta($challenge_id, 'kml_type', $request->kml_type);
        $this->update_challenge_meta($challenge_id, 'start_point', $request->start_point);
        $this->update_challenge_meta($challenge_id, 'end_point', $request->end_point);
        $this->update_challenge_meta($challenge_id, 'total_distance', $request->total_distance);

        return redirect()->back()->with('success', 'Location Added Successfully!');
    }

    public function addMilestones(Request $request)
    {
        $validatedData = $request->validate([
            'milestone_name' => 'required',
            //'milestone_distance' => 'required',
        ]);

        if ($request->milestone_pic) {
            $ext    =    $request->milestone_pic->getClientOriginalExtension();
            $file = date('YmdHis') . rand(1, 99999) . '.' . $ext;
            $request->milestone_pic->storeAs('public/challenge/milestone_image', $file);
            $file    =    '/storage/challenge/milestone_image/' . $file;
        } else {
            $file    =    '';
        }

        $timezone = $request->session()->get('timezone');
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

        if ($request->specific_date_checkbox) {
            $specific_date = $this->getTimeStamp($request->specific_date, $timezone);
        } else {
            $specific_date = '';
        }

        $cust_id    =    DB::table('challenge_milestones')->insert(
            [
                'milestone_name'         =>  $request->milestone_name,
                'milestone_distance'    =>  $request->milestone_distance ? $request->milestone_distance : 0,
                'milestone_type'        =>  $request->milestone_type,
                'milestone_info' =>  trim($request->milestone_info,'\'"'),
                'specific_date_checkbox' =>  $request->specific_date_checkbox,
                'specific_date'         =>  $specific_date,
                'start_date'            =>  $this->getTimeStamp($request->start_date, $timezone),
                'end_date'              =>  $this->getTimeStamp($request->end_date, $timezone),
                'milestone_pic'         =>  $file,
                'challenge_id'          =>  $request->challenge_id
            ]
        );
        return redirect()->back()->with('success', 'Milestone Added Successfully!');
    }

    public function editMilestones(Request $request)
    {
        $validatedData = $request->validate([
            'milestone_name' => 'required',
            //'milestone_distance' => 'required',
        ]);
        //print_r($request->all()); die();
        if ($request->milestone_pic) {
            $ext    =   $request->milestone_pic->getClientOriginalExtension();
            $file = date('YmdHis') . rand(1, 99999) . '.' . $ext;
            $request->milestone_pic->storeAs('public/challenge/milestone_image', $file);
            $file   =   '/storage/challenge/milestone_image/' . $file;
        } else {
            $file   =   '';
        }

        $timezone = $request->session()->get('timezone');
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

        if ($request->specific_date_checkbox) {
            $specific_date = $this->getTimeStamp($request->specific_date, $timezone);
        } else {
            $specific_date = '';
        }

        $updateData = array(
            'milestone_name'        =>  $request->milestone_name,
            'milestone_distance'    =>  $request->milestone_distance ? $request->milestone_distance : 0,
            'milestone_type'        =>  $request->milestone_type,
            'specific_date_checkbox' =>  $request->specific_date_checkbox,
            'specific_date'         =>  $specific_date,
            'milestone_info'         =>  trim($request->milestone_info,'\'"'),
            'start_date'         =>  $this->getTimeStamp($request->start_date, $timezone),
            'end_date'         =>  $this->getTimeStamp($request->end_date, $timezone),
            //'milestone_pic'         =>  $file,
            'challenge_id'          =>  $request->challenge_id
        );

        if ($file) {
            $updateData['milestone_pic'] = $file;
        }

        //print_r($specific_date); die();

        $cust_id    =   DB::table('challenge_milestones')->where('id', $request->milestone_id)->update($updateData);
        return redirect()->back()->with('success', 'Milestone Added Successfully!');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $challenges = DB::table('challenges')->select('*')->get();

        $user_infos = User_infos::where([['user_id', '=', auth()->user()->id], ['meta_name', '=', 'timezone']])->first();
        $user_infos = json_encode($user_infos);
        $user_infos = json_decode($user_infos, true);
        $timezone = env('DEFAULT_TIMEZONE');

        if ($user_infos) {
            $timezone = $user_infos['meta_value'];
        }

        $arr['challenges']    =    $challenges;
        $arr['timezone']  =   $timezone;
        return view('backend.challenges.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.challenges.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'                     => 'required',
            /*'type' 		            => 'required',
			'event_start_date' 	    => 'required',
			'event_end_date'        =>'required',
			'reg_start_date' 	    =>'required',*/
            //'reg_end_date' 		    =>'required',
            //'description' 		    =>'required',
            //'allowed_participants' 	=>'required',

        ]);
        if ($request->price_type == 'paid') {
            $validatedData = $request->validate(['challenge_price' => 'required']);
            $price = $request->challenge_price;
        } else {
            $price = '';
        }
        if ($request->challenge_pic) {
            $ext    =    $request->challenge_pic->getClientOriginalExtension();
            $file = date('YmdHis') . rand(1, 99999) . '.' . $ext;
            $request->challenge_pic->storeAs('public/challenge/challenge_image', $file);
            $file    =    '/storage/challenge/challenge_image/' . $file;
        } else {
            $file    =    '';
        }

        if ($request->challenge_details_page_pic) {
            $ext    =   $request->challenge_details_page_pic->getClientOriginalExtension();
            $file = date('YmdHis') . rand(1, 99999) . 'details.' . $ext;
            $request->challenge_details_page_pic->storeAs('public/challenge/challenge_image', $file);
            $file_page_pic   =   '/storage/challenge/challenge_image/' . $file;
        } else {
            $file_page_pic   =   '';
        }

        $timezone = $request->session()->get('timezone');
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

        if ($request->reg_start_date) {
            $reg_start_date = $this->getTimeStamp($request->reg_start_date, $timezone);
        } else {
            $reg_start_date = '';
        }

        if ($request->reg_end_date) {
            $reg_end_date = $this->getTimeStamp($request->reg_end_date, $timezone);
        } else {
            $reg_end_date = '';
        }

        $input = $request->all();
        $now = Carbon::now($timezone)->toDateTimeString();

        $event_start_date = '';
        if (@$input['event_start_date']) {
            $event_start_date = Carbon::parse(@$input['event_start_date'], $timezone)->setTimezone('UTC');
        }

        $event_end_date = '';
        if (@$input['event_end_date']) {
            $event_end_date = Carbon::parse(@$input['event_end_date'], $timezone)->setTimezone('UTC');
        }
        //die();
        $reg_start_date = '';
        if (@$input['reg_start_date']) {
            $reg_start_date = Carbon::parse(@$input['reg_start_date'], $timezone)->setTimezone('UTC');
        }

        $reg_end_date = '';
        if (@$input['reg_end_date']) {
            $reg_end_date = Carbon::parse(@$input['reg_end_date'], $timezone)->setTimezone('UTC');
        }

        //Add To Database

        $infos = [
            'name'                     => $request->name,
            'type'                     => $request->type,
            'event_start_date'         => $event_start_date,
            'event_end_date'        => $event_end_date,
            'reg_start_date'         => $reg_start_date,
            'reg_end_date'             => $reg_end_date,
            'description'             => @$input['description'] ? $input['description'] : '',
            'allowed_participants'     => @$input['allowed_participants'] ? $input['allowed_participants'] : '',
            'image'                    => $file,
            'challenge_details_page_pic' => $file_page_pic,
            'status'                => $request->status,
            'price_type'            => $request->price_type,
            'created_at'            => Carbon::parse($now, $timezone)->setTimezone('UTC')
        ];

        $inserted_id =    DB::table('challenges')->insertGetId($infos);

        $this->update_challenge_meta($inserted_id, 'google_code', $request->google_code);
        $this->update_challenge_meta($inserted_id, 'facebook_code', $request->facebook_code);
        //$this->update_challenge_meta($inserted_id,'sub_title',$request->sub_title);
        $this->update_challenge_meta($inserted_id, 'category', $request->category);
        $this->update_challenge_meta($inserted_id, 'challenge_price', $price);

        $retuen_path = "/admin/challenges/" . $inserted_id . "/edit";
        return redirect($retuen_path)->with('message', 'Challenge Created! Now fill the other details!');
    }

    protected function getTimeStamp($dateTime, $timezone)
    {
        if ($dateTime) {

            /*$newDate = date('Y-m-d H:i:s', strtotime($dateTime));  
            $user_tz = 'UTC';

            $schedule_date = new DateTime($newDate, new DateTimeZone('Asia/Kolkata') );
            $schedule_date->setTimeZone(new DateTimeZone('UTC'));
            $newDate =  $schedule_date->format('Y-m-d H:i:s');*/

            $date = Carbon::parse($dateTime, $timezone)->setTimezone('UTC');
            return $date;
        }

        return;
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
        $data                =    DB::table('challenges')->where('id', '=', $id)->get()->toArray();
        $products           =   DB::table('products')->select('*')->get()->toArray();
        $infos                =    DB::table('challenge_infos')->where('challenge_id', '=', $id)->get()->toArray();
        $attached_product    =    DB::table('challenge_product_rel')->where('challenge_id', '=', $id)->select('product_id')->get()->toArray();
        $user_query         =   "SELECT id,name FROM users WHERE ID IN (SELECT user_id FROM participations WHERE challenge_id = " . $id . ")";
        $participants       =   \DB::select($user_query);

        $product_array    = array();
        if (!empty($attached_product)) {
            foreach ($attached_product as $product) {
                $product_array[] = $product->product_id;
            }
        }

        $only_attached_product = array();
        $only_unattached_product = array();
        foreach ($products as $product) {
            $prd_id = $product->id;
            if (in_array($prd_id, $product_array)) {
                $product->is_attached = 1;
                $only_attached_product[] = $product;
            } else {
                $product->is_attached = 0;
                $only_unattached_product[] = $product;
            }
        }

        $products = array_merge($only_attached_product, $only_unattached_product);

        $user_data    = array();
        foreach ($infos as $info) {
            $user_data[$info->meta_name] = $info->meta_value;
        }



        $user_infos = User_infos::where([['user_id', '=', auth()->user()->id], ['meta_name', '=', 'timezone']])->first();
        $user_infos = json_encode($user_infos);
        $user_infos = json_decode($user_infos, true);
        $timezone = env('DEFAULT_TIMEZONE');

        if ($user_infos) {
            $timezone = $user_infos['meta_value'];
        }


        $arr['timezone']  =   $timezone;

        $arr['challenge']    =    $data[0];
        $arr['challenge_info']    =    $user_data;
        $arr['participants']    =    $participants;
        $arr['challenge_info']['products'] = $products;
        return view('backend.challenges.edit')->with($arr);
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
        $validatedData = $request->validate([
            'name'                     => 'required',
            /*'type' 		            => 'required',
			'event_start_date' 	    => 'required',
			'event_end_date'        =>'required',*/
            //'reg_start_date' 	    =>'required',
            //'reg_end_date' 		    =>'required',
            /*			'description' 		    =>'required',
			'allowed_participants' 	=>'required',*/

        ]);

        $input = $request->all();

        $timezone = $request->session()->get('timezone');
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

        $event_start_date = '';
        if (@$input['event_start_date']) {
            $event_start_date = Carbon::parse(@$input['event_start_date'], $timezone)->setTimezone('UTC');
        }

        $event_end_date = '';
        if (@$input['event_end_date']) {
            $event_end_date = Carbon::parse(@$input['event_end_date'], $timezone)->setTimezone('UTC');
        }




        $array    =    [
            'name'                     => $request->name,
            'type'                     => $request->type,
            'event_start_date'         => $event_start_date,
            'event_end_date'        => $event_end_date,
            'description'             => @$input['description'],
            'allowed_participants'     => @$input['allowed_participants'],
            'status'                => @$input['status'],
            'updated_at'            => date('Y-m-d H:i:s')
        ];
        if ($request->challenge_pic) {
            $ext    =    $request->challenge_pic->getClientOriginalExtension();
            $file = date('YmdHis') . rand(1, 99999) . '.' . $ext;
            $request->challenge_pic->storeAs('public/challenge/challenge_image', $file);
            $array['image']    =    '/storage/challenge/challenge_image/' . $file;
        }

        if ($request->challenge_details_page_pic) {
            $ext    =   $request->challenge_details_page_pic->getClientOriginalExtension();
            $file = date('YmdHis') . rand(1, 99999) . 'details.' . $ext;
            $request->challenge_details_page_pic->storeAs('public/challenge/challenge_image', $file);
            $array['challenge_details_page_pic'] =   '/storage/challenge/challenge_image/' . $file;
        }

        if ($request->reg_start_date) {
            $array['reg_start_date'] = $this->getTimeStamp($request->reg_start_date, $timezone);
        }

        if ($request->reg_end_date) {
            $array['reg_end_date'] = $this->getTimeStamp($request->reg_end_date, $timezone);
        }

        $affected = DB::table('challenges')->where('id', $id)->update($array);
        $this->update_challenge_meta($id, 'google_code', $request->google_code);
        $this->update_challenge_meta($id, 'facebook_code', $request->facebook_code);
        //$this->update_challenge_meta($id,'sub_title',$request->sub_title);
        $this->update_challenge_meta($id, 'category', $request->category);
        return redirect('/admin/challenges')->with('message', 'Challenge details has been updated successfully !');
    }


    protected function update_challenge_meta($id, $meta_name, $meta_value)
    {
        if (!empty($meta_value) || $meta_value != '') {
            $cust_id    =    DB::table('challenge_infos')->updateOrInsert(
                [
                    'challenge_id'         => $id,
                    'meta_name'            => $meta_name,
                ],
                [
                    'meta_value'     => $meta_value,
                ]
            );
        }
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
}

