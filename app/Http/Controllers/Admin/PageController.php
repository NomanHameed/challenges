<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Page;

class PageController extends Controller
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
        $arr['pages']	=	Page::All();
		    return view('backend.pages.index')->with($arr);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Page $page)
    {
		$validatedData = $request->validate([
			'title' => 'required'
		]);
		
		$content	=	filter_input(INPUT_POST,'content',FILTER_UNSAFE_RAW);
		$page->title	          =	$request->title;
    $page->seo_title	      =	$request->seo_title;
    $page->seo_description	=	$request->seo_description;
    $page->seo_tags	        =	$request->seo_tags;
		$page->content	        =	$content;
		$page->save();
		return redirect('/admin/pages')->with("message", " Page has been Added Successfully !");
        
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
        $page_data = DB::table('pages')->select('*')->where('id', $id)->get();		
        $arr['page']	=	$page_data[0]; 
		return view('backend.pages.edit')->with($arr);
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
			'title' => 'required'
		]);
        $array	=	[
				'title'           =>  $request->title, 
				'content'         =>  filter_input(INPUT_POST,'content',FILTER_UNSAFE_RAW),
        'seo_title'	      =>	$request->seo_title,
        'seo_description'	=>  $request->seo_description,
        'seo_tags'	      =>	$request->seo_tags,
			  ];	
		
        $affected = DB::table('pages')
              ->where('id', $id)
              ->update($array);
			  
		return redirect('/admin/pages')->with('message', 'Page has been updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('pages')->where('id', '=', $id)->delete();
		return redirect('/admin/pages')->with('message', 'Page has been deleted successfully !');
    }
}
