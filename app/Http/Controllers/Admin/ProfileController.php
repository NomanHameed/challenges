<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Timezones;
use App\Models\User_infos;

class ProfileController extends Controller
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
    public function index()
    {
    	$timezoneList = Timezones::get();
        $timezoneList = json_encode($timezoneList);
        $timezoneList = json_decode($timezoneList, true);
		$user = Auth::user();
		$id = Auth::id();
		$users = DB::table('users')->select('*')->where('id', $id)->get();

		$user_info = DB::table('user_infos')->select('user_infos.*')->where('user_id', $id)->get();
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

        $arr['my_info']	=	$users[0]; 
        $arr['timezoneList'] = $timezoneList; 
        $arr['timezone'] = $timezone1;
		return view('backend.profile.edit')->with($arr);
    }
	
	public function update(Request $request)
    {
		$validatedData = $request->validate([
			'email' => 'required',
			'name' => 'required'
		]);
		
		$array	=[
					'email' 		=> $request->email, 
					'name' 			=> $request->name, 
					'updated_at'	=> date('Y-m-d H:i:s')
				];
		if(!empty($request->new_password)){
			$validatedData = $request->validate([
				'old_password' => 'required',
				'confirm_password' => 'required|same:new_password'
			]);
			$user = User::where('email', '=', $request->email)->first();
			if (!Hash::check($request->old_password, $user->password)) {
				return redirect('/admin/profile')->with("error", "Old Password did not match !");
			}else{
				$array['password']	=	Hash::make($request->new_password);
			}
		}
		//Upload File
		if($request->prof_pic){			
			$ext	=	$request->prof_pic->getClientOriginalExtension();
			$file = date('YmdHis').rand(1,99999).'.'.$ext;
			$request->prof_pic->storeAs('public/admin/profile_image',$file);
			$array['profile_pic']	=	$file;
			$request->session()->put('profile_pic', $file);
		}
		$user = Auth::user();
		$id = Auth::id();
		//Add To Database
		$cust_id	=	DB::table('users')->where('id', $id)->update($array);

		$User_infos = new User_infos;
        $User_infos->updateMetaValue($id, 'timezone', $request->timezone);
        
		$request->session()->put('name', $request->name);
		return redirect('/admin/profile')->with("message", "Profile has been Updated Successfully !");
    }
}
