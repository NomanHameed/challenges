<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Badges;
use Carbon\Carbon;

class BadgeController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
    //$this->middleware('is_subadmin:page');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr['badges']	=	DB::table('badges')->select('*')->get();
		return view('backend.badges.index')->with($arr);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.badges.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $badge_type = @$input['badge_type'];

        $requestCondition = array(
            'badge_name'    => 'required',
            'badge_info'    => 'required',
            'badge_logo'    => 'required',
            'badge_type'    => 'required',
            'condition_limit'    => 'required');
            
        if($badge_type == 'member_since'){
            $requestCondition['badge_condition'] = 'required';
        }

        $validatedData = $request->validate($requestCondition);

        if($request->badge_logo){
			$ext	=	$request->badge_logo->getClientOriginalExtension();
			$file = date('YmdHis').rand(1,99999).'.'.$ext;
			$request->badge_logo->storeAs('public/badges',$file);
			$file	=	'/storage/badges/'.$file;
		}else{
			$file	=	'';
		}

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $now = Carbon::now($timezone)->toDateTimeString();

        if($request->specific_date_checkbox){
            $specific_date = Carbon::parse($request->specific_date, $timezone)->setTimezone('UTC');
        }else{
            $specific_date = '';
        }

        //Add To Database
		$cust_id =	DB::table('badges')->insert([
            [ 
                'badge_name' 	    => $request->badge_name,
                'badge_info' 	    => $request->badge_info,
                'badge_logo'	    => $file,
                'badge_type' 	    => $request->badge_type,
                'badge_condition'   => $request->badge_condition,
                'condition_limit' 	=> $request->condition_limit,
                'specific_date_checkbox'   => $request->specific_date_checkbox,
                'specific_date'     => $specific_date,
                'created_at'        => Carbon::parse($now, $timezone)->setTimezone('UTC')
            ]
        ]);
        return redirect('/admin/badges')->with("message", " Badge has been Added Successfully !");
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
        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

        $data		        =	DB::table('badges')->where('id', '=', $id)->get()->toArray();
        $arr['badge']		=	$data[0];
        $specific_date_checkbox = $data[0]->specific_date_checkbox ? $data[0]->specific_date_checkbox : '';
        $specific_date = '';
        if($specific_date_checkbox){
            $specific_date = $data[0]->specific_date ? $data[0]->specific_date : '';
            $specific_date = Carbon::parse($specific_date)->format('m/d/Y');
        }

        $arr['specific_date']  =   $specific_date;
        
        return view('backend.badges.edit')->with($arr);
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

        $input = $request->all();

        $badge_type = @$input['badge_type'];

        $requestCondition = array(
            'badge_name'    => 'required',
            'badge_info'    => 'required',
            'badge_type'    => 'required',
            'condition_limit'    => 'required');
            
        if($badge_type == 'member_since'){
            $requestCondition['badge_condition'] = 'required';
        }
        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $now = Carbon::now($timezone)->toDateTimeString();

        if($request->specific_date_checkbox){
            $specific_date = Carbon::parse($request->specific_date, $timezone)->setTimezone('UTC');
        }else{
            $specific_date = '';
        }


        $validatedData = $request->validate($requestCondition);
		$array	=	[
                'badge_name' 	    => $request->badge_name,
                'badge_info' 	    => $request->badge_info,
                'badge_type'        => $request->badge_type,
                'badge_condition' 	=> $request->badge_condition,
                'condition_limit' 	=> $request->condition_limit,
                'specific_date_checkbox'   => $request->specific_date_checkbox,
                'specific_date'     => $specific_date,

				'updated_at'        => Carbon::parse($now, $timezone)->setTimezone('UTC')
			  ];	
		
		if($request->badge_logo){
			$ext	=	$request->badge_logo->getClientOriginalExtension();
			$file = date('YmdHis').rand(1,99999).'.'.$ext;
			$request->badge_logo->storeAs('public/badges',$file);
			$array['badge_logo']	=	'/storage/badges/'.$file;
		}
        $affected = DB::table('badges')->where('id', $id)->update($array);
        return redirect('/admin/badges')->with('message', 'Badge details has been updated successfully !');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id){
           $chLog = Badges::find($id);
           $chLog->delete();
       }
            
        return redirect(route('admin.badges.index'))->with(['message' => 'Badge deleted successfully.']);
    }
}
