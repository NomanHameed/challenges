<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use \stdClass;
use Carbon\Carbon;
use App\Models\User_challenges;
use App\Models\Challenge_logs;
use Validator;

use App\Mail\NewUserRegistration;
use App\Mail\NewUserRegistrationToAdmin;
use App\Mail\NewChallengeAssigned;
use Illuminate\Support\Facades\Mail;


class UsersController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		//$this->middleware('is_subadmin:witness_user');
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	$input = $request->all();
    	$Search = @$input['Search'] ? @$input['Search'] : '';
    	$timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');

    	if($Search){
            $users = DB::table('users')
			->leftjoin('user_infos', 'users.id', '=', 'user_infos.user_id')
			->select('user_infos.*', 'users.*')
			->where([['users.user_type', '=', '2'], ['users.email', 'like', '%' .$input['Search'].'%' ]])
			->orWhere([['users.user_type', '=', '2'], ['users.name', 'like', '%' .$input['Search'].'%' ]])
			->orderBy('users.created_at', 'DESC')
			->get();
    	}else{
    		$users = DB::table('users')
			->leftjoin('user_infos', 'users.id', '=', 'user_infos.user_id')
			->select('user_infos.*', 'users.*')->where('user_type', '2')->orderBy('users.created_at', 'DESC')->get();
    	}
		$new_user = array();
		//echo '<pre>';
		foreach($users as $user){
			$new_user[$user->id]['name']= @$user->name;
			$new_user[$user->id]['status']= @$user->status;
			$new_user[$user->id]['created_at']= Carbon::parse(@$user->created_at, 'UTC')->setTimezone($timezone);
			$new_user[$user->id]['id']= $user->id;
			$new_user[$user->id]['email']= @$user->email;
			$new_user[$user->id][@$user->meta_name]= @$user->meta_value;
		}
		
		//print_r($new_user); die;
        $arr['users']	=	$new_user;
        $arr['Search']	=	$Search;
		return view('backend.users.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    	$stateList = DB::table('us_states')->select('*')->get();
        $stateList = json_encode($stateList);
        $stateList = json_decode($stateList, true);
        $citieList = DB::table('us_cities')->select('*')->get();
        $citieList = json_encode($citieList);
        $citieList = json_decode($citieList, true);

        $arr = array();
        $arr['stateList']   = $stateList;
        $arr['citieList']   = $citieList;

        return view('backend.users.create', $arr);
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
			'fname' 		=> 'required',
			'lname' 		=> 'required',
			'email' 		=> 'required|email',
			//'mobile_number' =>'required',
			'password' 		=>'required',
			//'country' 	=>'required',
			//'state' 		=>'required',
			'zip_code'          => ['nullable','regex:/^[0-9]{5}(-[0-9]{4})?$/'],

		],
    [
        'fname.required' => 'The first name field is required.',
        'lname.required' => 'The last name field is required.',
        'zip_code.regex' => 'Enter zipcode in valid format.'
    ]);
		$user = DB::table('users')->select('*')
		->where('email',$request->email)
		//->orWhere('mobile_number',$request->mobile_number)
		->get()->toArray();
		if(!empty($user)){
			 return redirect('/admin/users/create')->with("alert", " Provided Email OR Mobile Number already exists !");
		}
		//Upload File
		if($request->prof_pic){
			$ext	=	$request->prof_pic->getClientOriginalExtension();
			$file = date('YmdHis').rand(1,99999).'.'.$ext;
			$request->prof_pic->storeAs('public/user/profile_image',$file);
			$file	=	'/storage/user/profile_image/'.$file;
		}else{
			$file	=	'';
		}

		$timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $now = Carbon::now($timezone)->toDateTimeString();
		//Add To Database
		$cust_id	=	DB::table('users')->insert([
							[
								'email' 			=> $request->email, 
								'name' 				=> $request->fname.' '.$request->lname, 
								'first_name' 		=> $request->fname, 
								'last_name' 		=> $request->lname, 
								'mobile_number' 	=> $request->mobile_number, 
								'password' 			=> Hash::make($request->password),
								'status'			=> $request->status,
								'user_type' 		=> '2',
								'profile_pic'		=> $file,
								'created_at'		=> Carbon::parse($now, $timezone)->setTimezone('UTC')
							]
						]);
		$inserted_id = DB::getPdo()->lastInsertId();

		$addedUser = user::where('id', $inserted_id)->first();

		Mail::to($addedUser)->send(new NewUserRegistration($request->fname.' '.$request->lname, route('frontend.home')));

        $adminUser = user::where('user_type', 1)->first();

        Mail::to($adminUser)->send(new NewUserRegistrationToAdmin($request->fname.' '.$request->lname, route('admin.users.edit', $inserted_id)));

		$this->update_user_meta($inserted_id,'country',$request->country, $timezone);
		$this->update_user_meta($inserted_id,'state',$request->state, $timezone);
		$this->update_user_meta($inserted_id,'city',$request->city, $timezone);
		if($request->address){
			$this->update_user_meta($inserted_id,'address',$request->address, $timezone);
		}
		if($request->zip_code){
			$this->update_user_meta($inserted_id,'zip_code',$request->zip_code, $timezone);
		}
		if($request->dob){
			$this->update_user_meta($inserted_id,'dob',$request->dob, $timezone);
		}
		if($request->gender){
			$this->update_user_meta($inserted_id,'gender',$request->gender, $timezone);
		}
		return redirect('/admin/users')->with("message", " User has been Added Successfully !");
	}
	
	protected function add_user_meta($user_id, $meta_name, $meta_value){
		if(!empty($meta_value)){
			$cust_id	=	DB::table('user_infos')->insert(
							[
								'user_id' 		=> $user_id, 
								'meta_name' 	=> $meta_name, 
								'meta_value' 	=> $meta_value,
								'created_at' 	=> date('Y-m-d H:i:s'),
								'updated_at'	=> date('Y-m-d H:i:s')
							]
						);
		}
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
		$data		=	DB::table('users')->where('id', '=', $id)->get()->toArray();
		$infos		=	DB::table('user_infos')->where('user_id', '=', $id)->get()->toArray();
		$user_data	= array();
		foreach($infos as $info){
			$user_data[$info->meta_name] = $info->meta_value;
		}
		$arr['users']		=	$data[0];
		$arr['user_info']	=	$user_data;

		$count = 0;
		$arr['participations'] = array();
		$participations = DB::table('user_challenges')->where('user_id', '=', $id)->get()->toArray();
		foreach($participations as $participation){
			$challenge_name = DB::table('challenges')->where('id', '=', $participation->challenge_id)->get()->toArray();
			$arr['participations'][$count]['name'] = $challenge_name[0]->name;
			$arr['participations'][$count]['challenge_id'] = $challenge_name[0]->id;
			$arr['participations'][$count]['id'] = $participation->id;
			$count++;
		}
		$arr['products'] = array();

		$usrCh = User_challenges::where([['user_id', '=', $id]])->get();
    	$usrCh = json_encode($usrCh);
    	$usrCh = json_decode($usrCh, true);

        $chId = array();
    	foreach($usrCh as $key => $val){
            $chId[] = $val['challenge_id'];
    	}

		$challenges = DB::table('challenges')->select('challenges.*')
                         //->where([['challenges.event_end_date', '>=', Carbon::now()->toDateTimeString()]])
                         ->whereNotIn('id', $chId)
                         ->get();
        $arr['challenges'] = $challenges;

		$count = 0;
		$orders = DB::table('orders')->where('user_id', '=', $id)->get()->toArray();
		foreach($orders as $order){
			$products = DB::table('products')->where('id', '=', $order->product_id)->get()->toArray();
			$arr['products'][$count]['name'] = $products[0]->name;
			$arr['products'][$count]['image'] = $products[0]->product_image;
			$arr['products'][$count]['id'] = $order->id;
			$count++;
		}

		$stateList = DB::table('us_states')->select('*')->get();
        $stateList = json_encode($stateList);
        $stateList = json_decode($stateList, true);
        $citieList = DB::table('us_cities')->select('*')->get();
        $citieList = json_encode($citieList);
        $citieList = json_decode($citieList, true);

        $arr['stateList']   = $stateList;
        $arr['citieList']   = $citieList;

		
       return view('backend.users.edit')->with($arr);
    }
	
	//Function for uploading multiple images
	public function uploadImages(Request $request){
		$timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $now = Carbon::now($timezone)->toDateTimeString();

		if ($request->hasFile('image_upload')) {
        $images = $request->file('image_upload');
			$count=0;
			foreach ($images as $image) {				
				$ext	=	$image->getClientOriginalExtension();
				$file = date('YmdHis').rand(1,99999).'.'.$ext;
				$image->storeAs('public/customer/personal_images',$file);
				$arr[$count]['customer_id']	= $request->customer_id;
				$arr[$count]['image']		= $file;
				$arr[$count]['created_at']	= Carbon::parse($now, $timezone)->setTimezone('UTC');
				$arr[$count]['updated_at']	= Carbon::parse($now, $timezone)->setTimezone('UTC');
				$count++;
			}
			DB::table('customer_personal_images')->insert($arr);
		}
		return redirect()->back()->with('success', 'Image Uploaded'); 
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
			'fname' => 'required',
			'lname' => 'required',
			'email' => 'required|email',
			//'mobile_number' =>'required',
			'zip_code'          => ['nullable','regex:/^[0-9]{5}(-[0-9]{4})?$/'],
		],
    [
        'fname.required' => 'The first name field is required.',
        'lname.required' => 'The last name field is required.',
        'zip_code.regex' => 'Enter zipcode in valid format.'
    ]);

		$timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $now = Carbon::now($timezone)->toDateTimeString();

		$array	=	[
				'email' 		=> $request->email, 
				'name' 			=> $request->fname.' '.$request->lname, 
				'first_name' 	=> $request->fname, 
				'last_name' 	=> $request->lname, 
				'status'		=> $request->status,
				'mobile_number' => $request->mobile_number, 
				'updated_at'	=> Carbon::parse($now, $timezone)->setTimezone('UTC')
				
			  ];	
		if(!empty($request->password)){
			 $array['password']	=	Hash::make($request->password);
		}
		if($request->prof_pic){
			$ext	=	$request->prof_pic->getClientOriginalExtension();
			$file = date('YmdHis').rand(1,99999).'.'.$ext;
			$request->prof_pic->storeAs('public/user/profile_image',$file);
			$array['profile_pic']	=	'/storage/user/profile_image/'.$file;
		}
        $affected = DB::table('users')
              ->where('id', $id)
              ->update($array);

		if($request->address){
			$this->update_user_meta($id,'address',$request->address, $timezone);
		}
		if($request->zip_code){
			$this->update_user_meta($id,'zip_code',$request->zip_code, $timezone);
		}
		if($request->dob){
			$this->update_user_meta($id,'dob',$request->dob, $timezone);
		}
		if($request->gender){
			$this->update_user_meta($id,'gender',$request->gender, $timezone);
		}			
		$this->update_user_meta($id,'country',$request->country, $timezone);
		$this->update_user_meta($id,'state',$request->state, $timezone);
		$this->update_user_meta($id,'city',$request->city, $timezone);
		return redirect('/admin/users')->with('message', 'User details has been updated successfully !');
    }

	protected function update_user_meta($id, $meta_name, $meta_value,$timezone){
		$now = Carbon::now($timezone)->toDateTimeString();
		if(!empty($meta_value) || $meta_value!=''){
			$cust_id	=	DB::table('user_infos')->updateOrInsert(
						[
							'user_id' 		=> $id, 
							'meta_name'		=> $meta_name,
						],
						[
							'meta_value' 	=> $meta_value,
							'updated_at'	=> Carbon::parse($now, $timezone)->setTimezone('UTC')
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
        DB::table('users')->where('id', '=', $id)->delete();
        DB::table('customer_info')->where('user_id', '=', $id)->delete();
		return redirect('/admin/customers')->with('message', 'Customer has been deleted successfully !');
    }

    public function assignChallengeToUser(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'challenges'     => 'required'
        ]);

        if($validator->fails()){
            return redirect(route('admin.users.edit',$id))->withInput()->withErrors($validator); 
        }  

       $input = $request->all();
       $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $now = Carbon::now($timezone)->toDateTimeString();
         
        $data = array();
        foreach($input['challenges'] as $key => $val){
        	$usrCh = User_challenges::where([['challenge_id', '=', $val], ['user_id', '=', $id], ['status', '=', 1]])->first();
        	$usrCh = json_encode($usrCh);
        	$usrCh = json_decode($usrCh, true);
        	if(!$usrCh){
        		$User_challenges = new User_challenges();
	            $User_challenges->challenge_id = $val;
	            $User_challenges->user_id = $id;
	            $User_challenges->payment_type = '';
	            $User_challenges->payment_status = 'Success';
	            $User_challenges->activate_date = Carbon::parse($now, $timezone)->setTimezone('UTC');
	            $User_challenges->status = 1;
	            $User_challenges->save();
        	}

        }

        $addedUser = user::where('id', $id)->first();

		Mail::to($addedUser)->send(new NewChallengeAssigned($addedUser->name, route('frontend.dashboard')));
        
        return redirect(route('admin.users.edit',$id))->with(['message' => 'Challenges assigned successfully.']);
        
        
    }

    public function unassignChallengeToUser(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'challenge_id'     => 'required'
        ]);

        if($validator->fails()){
            return redirect(route('admin.users.edit',$id))->withInput()->withErrors($validator); 
        }  

       $input = $request->all();
       $challenge_id = $input['challenge_id'];

       $challenge_logs = Challenge_logs::where([['user_id', '=', $id], ['participation_id', '=', $challenge_id]])->get();
       $challenge_logs = json_encode($challenge_logs);
       $challenge_logs = json_decode($challenge_logs, true);

       //print_r($challenge_logs); die();

       if(!$challenge_logs){ //echo $challenge_id; echo $id;
       	$usrCh = User_challenges::where([['challenge_id', '=', $challenge_id], ['user_id', '=', $id]])->first(); 
       	$userFind = User_challenges::find($usrCh->id);
       	$userFind->delete();
       	return redirect(route('admin.users.edit',$id))->with(['message' => 'Challenges unassigned successfully.']);

       }
        
        return redirect(route('admin.users.edit',$id))->with(['message' => 'Log is added.']);
        
        
    }


}
