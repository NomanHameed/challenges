<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

use App\Mail\OrderEmail;
use App\Mail\InvoiceEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


class OrderController extends Controller
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
        //die('Orders will be displayed here !');
        $arr['orders']	=	DB::table('orders')->select('*')->get();
        foreach($arr['orders'] as $payment){
            $product_name = DB::table('products')->where('id',$payment->product_id)->select('name')->get();
            $payment->product_name = $product_name[0]->name;
        }
		return view('backend.orders.index')->with($arr);
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
        $order_data  = $this->get_order_data($id);       
        return view('backend.orders.edit')->with($order_data);
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

        $timezone = $request->session()->get('timezone'); 
        $timezone = $timezone ? $timezone : env('DEFAULT_TIMEZONE');
        $now = Carbon::now($timezone)->toDateTimeString();

        $array	=	[
                'order_status' 		    => $request->status,
                'updated_at'		    => Carbon::parse($now, $timezone)->setTimezone('UTC')
        ];
        if($request->tracking_info){
			$array['tracking_info']	=	 $request->tracking_info;
		}
        if($request->order_info){
			$array['order_info']	=	 $request->order_info;
		}
        
        $affected = DB::table('orders')->where('id', $id)->update($array);   
        $this->add_order_log($id,$request->status, $timezone);
        $this->send_order_email($id,$request->status);

        return redirect('/admin/orders')->with('message', 'Order details has been updated successfully !');
        
    }

    public function get_order_data($id){
        $order	=	DB::table('orders')->select('*')->where('id',$id)->get();

        $product_data	        =	DB::table('products')->select('*')->where('id',$order[0]->product_id)->get();
        $user_data	            =	DB::table('users')->select('*')->where('id',$order[0]->user_id)->get();
        $challenge_id	        =	DB::table('participations')->select('*')->where('id',$order[0]->participation_id)->get();
        $challenge_data         =   DB::table('challenges')->select('*')->where('id',$challenge_id[0]->challenge_id)->get();

        $order_data['product_name']         =  $product_data[0]->name;
        $order_data['product_image']        =  $product_data[0]->product_image;
        $order_data['product_id']           =  $product_data[0]->id;

        $order_data['associated_challenge'] =  $challenge_data[0]->name;
        $order_data['user_name']            =  $user_data[0]->name;
        $order_data['user_email']           =  $user_data[0]->email;
        $order_data['order_date']           =  $order[0]->created_at;
        $order_data['order_id']             =  $order[0]->id;
        $order_data['order_info']           =  $order[0]->order_info;
        $order_data['tracking_info']        =  $order[0]->tracking_info;
        $order_data['status']               =  $order[0]->order_status;

        return $order_data;
    }

    public function get_status_name($status){
        switch ($status){
            case 1:
                $status_name = "New Order";
                break;
            case 2:
                $status_name = "Shipped";
                break;
            case 3:
                $status_name = "Delivered";
                break;
            case 4:
                $status_name = "Cancelled";
                break;
        }
        return $status_name;
    }

    public function add_order_log($order_id, $status, $timezone){

        $now = Carbon::now($timezone)->toDateTimeString();
        $status_name = $this->get_status_name($status);
        $array	=	[
            'status' 		    => $status_name,
            'order_id' 		    => $order_id,
            'created_at'		=> Carbon::parse($now, $timezone)->setTimezone('UTC')
        ];
        DB::table('order_log')->insert($array);
            
    }

    public function send_order_email($order_id, $order_status){
        //$this->send_invoice($order_id);
        $order_data                    =     $this->get_order_data($order_id);
        $order_data['status_name']     =     $this->get_status_name($order_status);
        //$order_data['attachment']     =     'order_1.pdf';

        Mail::to($order_data['user_email'])->send(new OrderEmail($order_data));
    }

    public function send_invoice($order_id){
        $order_data                    =     $this->get_order_data($order_id);

        $client = new Buyer([
            'name'          => 'Virtual Challenge',
            'phone'         => '(520) 318-9486',
            'custom_fields' => [
                'note'        => 'IDDQD',
                'business id' => '365#GG',
            ],
        ]);

        $customer = new Buyer([
            'name'          => $order_data['user_name'],
            'custom_fields' => [
                'email' => $order_data['user_email'],
            ],
        ]);

        $item = (new InvoiceItem())->title($order_data['product_name'])->pricePerUnit(0);

        $invoice = Invoice::make()
        ->series('Challenge:'.$order_data['associated_challenge'])
        //->sequence(667)
        ->serialNumberFormat('{SERIES}')
        ->buyer($customer)
        ->seller($client)
        ->filename('order_'.$order_id)
        ->currencySymbol('$')
        ->currencyCode('USD')
        ->addItem($item)
        ->save('public');
        
        //echo $link = $invoice->url();
        //return $invoice->stream();

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
