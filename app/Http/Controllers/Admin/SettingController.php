<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
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
		$user = Auth::user();
		$id = Auth::id();
		$settings = DB::table('site_options')->select('*')->get();	
        //echo '<pre>';
        $data= array();
        foreach($settings as $setting){
            $data[$setting->option_name] = $setting->option_value;
        }	
        $arr['my_info']	=	$data; 
		return view('backend.settings.edit')->with($arr);
    }
	
	public function update(Request $request)
    {
		
		$this->update_setting('contact_details',$request->contact_details);
        $this->update_setting('facebook_link',$request->facebook_link);
        $this->update_setting('seo_title',$request->seo_title);
        $this->update_setting('seo_description',$request->seo_description);
        $this->update_setting('google_analytics',$request->google_analytics);
		//Upload File
		if($request->site_logo){			
			$ext	=	$request->site_logo->getClientOriginalExtension();
			$file = date('YmdHis').rand(1,99999).'.'.$ext;
			$request->site_logo->storeAs('public/admin/site_logo',$file);
			$site_logo	=	$file;
            $this->update_setting('site_logo',$site_logo);
		}	
		return redirect('/admin/settings')->with("message", "Fields has been Updated Successfully !");
    }

    protected function update_setting($meta_name, $meta_value){
		if(!empty($meta_value) || $meta_value!=''){
			$cust_id	=	DB::table('site_options')->updateOrInsert(
						[
							'option_name'		    => $meta_name,
						],
						[
							'option_value' 	=> $meta_value,
						]
					);
		}
	}
}
