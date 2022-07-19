<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		
		$total_users	        =	DB::table('users')->where('user_type', '=', 2)->count();
		$total_challenges	    =	DB::table('challenges')->count();
        $total_participations	=	DB::table('participations')->count();
		$total_payments	        =	DB::table('payments')->where('payment_status',1)->sum('amount');

        $total_badges	        =	DB::table('badges')->count();
		$total_products	        =	DB::table('products')->count();
        $total_orders	        =	DB::table('orders')->count();
		$data = array(
				'user_count'            =>  $total_users,
				'total_challenges'      =>  $total_challenges,
				'total_participations'  =>  $total_participations,
                'total_payments'        =>  $total_payments,
                
				'total_products'        =>  $total_products,
                'total_badges'          =>  $total_badges,
                'total_orders'          =>  $total_orders,
				'name'                  => Session::get('name'),
			);
        return view('home')->with($data);
    }
}
