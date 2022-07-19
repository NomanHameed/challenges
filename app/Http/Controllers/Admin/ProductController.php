<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
        $products = DB::table('products')->select('*')->get();
        $arr['products']	=	$products;
		return view('backend.products.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.products.create');
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
			'name' 		    => 'required',
			'description' 	=> 'required',
			'price'         =>'required',

		]);
        //Upload File
		if($request->product_image){
			$ext	=	$request->product_image->getClientOriginalExtension();
			$file = date('YmdHis').rand(1,99999).'.'.$ext;
			$request->product_image->storeAs('public/product/product_image',$file);
			$file	=	'/storage/product/product_image/'.$file;
		}else{
			$file	=	'';
		}
        $cust_id	=	DB::table('products')->insert([
            [
                'name' 			    => $request->name, 
                'description' 		=> $request->description, 
                'price' 		    => $request->price,                 
                'product_image'		=> $file,
                'created_at'		=> date('Y-m-d H:i:s')
            ]
        ]);
        $inserted_id = DB::getPdo()->lastInsertId();
        if($request->attr_name){
            $total_attr = count($request->attr_name);
            for($a=0; $a<$total_attr; $a++){
                $attr_type = $request->attr_type[$a];
                $attr_name = $request->attr_name[$a];
                $attr_value = $request->attr_value[$a];
                $this->add_attribute($inserted_id, $attr_type, $attr_name, $attr_value);
            }
        }
        return redirect('/admin/products')->with("message", " Product has been Added Successfully !");
    }

    protected function add_attribute($product_id, $attr_type, $attr_name, $attr_value){
			$cust_id	=	DB::table('product_attributes')->insert(
						[
							'product_id' 		=> $product_id, 
							'attr_type' 	=> $attr_type,
                            'attr_name' 	=> $attr_name,
                            'attr_value' 	=> $attr_value,
						]
					);
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
        $data		=	DB::table('products')->where('id', '=', $id)->get()->toArray();
        $attributes	=	DB::table('product_attributes')->where('product_id', '=', $id)->get()->toArray();
		$arr['product']		            =	$data[0];
        $arr['product_attributes']		=	$attributes;
       return view('backend.products.edit')->with($arr);
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
			'name' 		    => 'required',
			'description' 	=> 'required',
			'price'         =>'required',

		]);
        $array	=	[
            'name' 			    => $request->name, 
            'description' 		=> $request->description, 
            'price' 		    => $request->price,   
            'updated_at'		=> date('Y-m-d H:i:s')
          ];	
        if($request->product_image){
			$ext	=	$request->product_image->getClientOriginalExtension();
			$file = date('YmdHis').rand(1,99999).'.'.$ext;
			$request->product_image->storeAs('public/user/profile_image',$file);
			$array['product_image']	=	'/storage/user/profile_image/'.$file;
		}
        DB::table('products')->where('id', $id)->update($array);

        if($request->attr_name){
            $attr_names = $request->attr_name;
            $attr_names = array_filter($attr_names);
            DB::table('product_attributes')->where('product_id', '=', $id)->delete();
            foreach($attr_names as $key=>$value){
                $attr_type = $request->attr_type[$key];
                $attr_name = $request->attr_name[$key];
                $attr_value = $request->attr_value[$key];
                $this->add_attribute($id, $attr_type, $attr_name, $attr_value);
            }
        }
        return redirect('/admin/products')->with('message', 'Product details has been updated successfully !');
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
