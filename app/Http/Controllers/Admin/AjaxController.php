<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AjaxController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
    public function get_us_state(Request $request){
		$html= '<option value=""></option>';
		$country	=	$request->country;
		$states = DB::table('us_states')->select('*')->get();
		foreach($states as $state){
			$html .= '<option value="'.$state->state_code.'">';
			$html .= $state->state;
			$html .= '</option>';
		}
		return response()->json(array('msg'=> $html), 200);
	}

	public function get_us_state_profile(Request $request){
		$html= '<option value=""></option>';
		$country	=	$request->country;
		$states = DB::table('us_states')->select('*')->get();
		foreach($states as $state){
			$html .= '<option value="'.$state->state.'">';
			$html .= $state->state;
			$html .= '</option>';
		}
		return response()->json(array('msg'=> $html), 200);
	}
	
	public function get_us_cities_profile(Request $request){
		$html= '';
		$state	=	$request->state;
		$cities = DB::table('us_cities')->select('*')->where('id_state', $state)->get();
		foreach($cities as $city){
			$html .= '<option value="'.$city->city.'">';
			$html .= $city->city;
			$html .= '</option>';
		}
		return response()->json(array('msg'=> $html), 200);
	}

	public function get_us_cities(Request $request){
		$html= '';
		$state	=	$request->state;
		$cities = DB::table('us_cities')->select('*')->where('state_code',$state )->get();
		foreach($cities as $city){
			$html .= '<option value="'.$city->city.'">';
			$html .= $city->city;
			$html .= '</option>';
		}
		return response()->json(array('msg'=> $html), 200);
	}
	
	public function attach_product(Request $request){
		$html= '';
		$challenge_id	=	$request->challenge_id;
		$product_id		=	$request->product_id;
		DB::table('challenge_product_rel')->insert([
            [ 
                'challenge_id' 		            => $challenge_id,
                'product_id' 		            => $product_id,
			]
		]);
		return response()->json(array('msg'=> $html), 200);
	}

	public function unattach_product(Request $request){
		$html= '';
		$challenge_id	=	$request->challenge_id;
		$product_id		=	$request->product_id;
		DB::table('challenge_product_rel')->where('product_id', '=', $product_id)->where('challenge_id', '=', $challenge_id)->delete();
		return response()->json(array('msg'=> $html), 200);
	}

	public function getChallengeMilestone(Request $request){
		$html= '';
		$challenge_id	=	$request->id;
		$timezone = $request->timezone ? $request->timezone : env('DEFAULT_TIMEZONE');
		$milestones = DB::table('challenge_milestones')->select('*')->where('challenge_id',$challenge_id )->get();
		foreach($milestones as $milestone){
			$milestone1 = json_encode($milestone);
			$milestone1 = json_decode($milestone1, true);
			

			$image_url = $milestone->milestone_pic;
			if($image_url && !empty($image_url) && $image_url != null){
				$base_url = url("/");
				$image = '<img src="'.$base_url.$image_url.'" width="40px" height="40px">';
			}else{
				$image = '';
			}

			$milestone_type = @$milestone->milestone_type == 'monthly_milestone' ? "Monthly Milestone" : 'Distance Milestone';

			$specific_date_checkbox = $milestone->specific_date_checkbox ? $milestone->specific_date_checkbox : '';

			$start_date = $milestone->start_date;
            $start_date1 = '';
			if($start_date){
				if(!$specific_date_checkbox){
					$start_date1 = $start_date ? Carbon::createFromFormat('Y-m-d H:i:s', $start_date, 'UTC')->setTimezone($timezone) : '';

					//$orgDate = Carbon\Carbon::createFromFormat('d/m/Y', $orgDate)->format('d-m-Y');
	                $orgDate = \Carbon\Carbon::parse(@$start_date1);

						  	
					$start_date1 = $orgDate->month.'/'.$orgDate->day.'/'.$orgDate->year.' '.$orgDate->hour.':'.$orgDate->minute;

					//$start_date1 = date("m/d/Y H:m", strtotime($start_date1));
				}
			}
			

            $end_date = $milestone->end_date;
            $end_date1 = '';
            if($end_date){
            	if(!$specific_date_checkbox){
	            	$end_date1 = $end_date ? Carbon::createFromFormat('Y-m-d H:i:s', $end_date, 'UTC')->setTimezone($timezone) : '';
	            	//$end_date1 = date("m/d/Y H:m", strtotime($end_date1));

	            	$orgDate = \Carbon\Carbon::parse(@$end_date1);

						  	
					$end_date1 = $orgDate->month.'/'.$orgDate->day.'/'.$orgDate->year.' '.$orgDate->hour.':'.$orgDate->minute;

					//$start_date1 = date("m/d/Y H:m", strtotime($start_date1));
	            }
            }
			

            $specific_date = $milestone->specific_date;
            $specific_date1 = '';
            if($specific_date){
            	if($milestone->specific_date_checkbox){
	            	$specific_date1 = $specific_date ? Carbon::createFromFormat('Y-m-d H:i:s', $specific_date, 'UTC')->setTimezone($timezone) : '';

	            	$orgDate = \Carbon\Carbon::parse(@$specific_date1);

						  	
					$specific_date1 = $orgDate->month.'/'.$orgDate->day.'/'.$orgDate->year.' '.$orgDate->hour.':'.$orgDate->minute;
	            	//$specific_date1 = date("m/d/Y H:m", strtotime($specific_date1));
	            }
            }
			
			$html .= '<tr id="'.$milestone->id.'">';
			$html .= '<td>'.$milestone->id.'</td>';
			$html .= '<td>'.@$milestone->milestone_name.'</td>';
			$html .= '<td>'.@$milestone_type.'</td>';
			$html .= '<td>'.@$milestone->milestone_distance.'</td>';
			$html .= '<td>'.$start_date1.'</td>';
			$html .= '<td>'.$end_date1.'</td>';
			$html .= '<td>'.$specific_date1.'</td>';
			$html .= '<td>'.@$image.'</td>';
			$milestone_type = @$milestone1['milestone_type'] ? $milestone1['milestone_type'] : '';
			$milestone_info = @$milestone1['milestone_info'] ? $milestone1['milestone_info'] : '';
			$start_dateEdit = @$milestone1['start_date'] ? Carbon::createFromFormat('Y-m-d H:i:s', $milestone1['start_date'], 'UTC')->setTimezone($timezone) : '';
			$end_dateEdit = @$milestone1['end_date'] ? Carbon::createFromFormat('Y-m-d H:i:s', $milestone1['end_date'], 'UTC')->setTimezone($timezone) : '';
			$specific_dateEdit = @$milestone1['specific_date'] ? Carbon::createFromFormat('Y-m-d H:i:s', $milestone1['specific_date'], 'UTC')->setTimezone($timezone) : '';
			$milestone_distanceEdit = @$milestone1['milestone_distance'] ? $milestone1['milestone_distance'] : '';
			$specific_date_checkboxEdit = @$milestone1['specific_date_checkbox'] ? $milestone1['specific_date_checkbox'] : '';
			$imgURL = "";

			$html .= '<td><button type="button" onclick="delete_me('.$milestone->id.',this)" class="btn btn-danger">Delete</button><button type="button" onclick="edit_me('.$milestone->id.',`'.$milestone->milestone_name.'`,`'.$milestone_type.'`,`'.$start_date1.'`,`'.$end_date1.'`,`'.$specific_dateEdit.'`,`'.$milestone_distanceEdit.'`,`'.$specific_date_checkboxEdit.'`,`'.$imgURL .'`,`'.$milestone_info .'`,this)" class="btn btn-info" style="margin-left: 10px;">Edit</button></td>';
			$html .= '</tr>';
		}
		return response()->json(array('msg'=> $html), 200);										
	}
	
	
	public function deleteChallengeMilestone(Request $request){
		$html= '';
		$id	=	$request->row_id;
		DB::table('challenge_milestones')->where('id', '=', $id)->delete();
		return response()->json(array('msg'=> "Deleted !"), 200);
										
	}

	public function viewOrderLog(Request $request){
		$html= '';
		$order_id	=	$request->id;
		$logs 		= DB::table('order_log')->select('*')->where('order_id',$order_id )->orderBy('created_at', 'desc')->get();
		foreach($logs as $log){
			$html .= '<tr>';
			$html .= '<td>'.$log->status.'</td>';
			$html .= '<td>'.$log->created_at.'</td>';
			$html .= '</tr>';
		}
		return response()->json(array('msg'=> $html), 200);										
	}

}
