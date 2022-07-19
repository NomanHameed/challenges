@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Page</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Page</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Page
              </h3>
              <!-- tools box -->
              <div class="card-tools">
                <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                 
                <button type="button" class="btn btn-tool btn-sm" data-card-widget="remove" data-toggle="tooltip"
                        title="Remove">
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
			  
				<form action="{{route('admin.pages.update',$page->id)}}" method="POST">
				@method('PUT')
				<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label for="exampleInputEmail1">Page Title</label>
						<input type="text" name="title" value="{{$page->title}}" class="form-control" id="exampleInputEmail1" placeholder="Page Title">
					</div>
					
					  <label for="exampleInputEmail1">Page Content</label>
					<textarea name="content" class="textarea" placeholder="Place some text here"
							  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
							  {{$page->content}}	  
					</textarea>

          <div class="form-group">
						<label for="exampleInputEmail1">SEO Title</label>
						<input type="text" name="seo_title" value="{{@$page->seo_title}}" class="form-control" id="exampleInputEmail1" placeholder="SEO Title">
					</div>
           
          <div class="form-group">
						<label for="exampleInputEmail1">SEO Description</label>
            <textarea name="seo_description" class="form-control">
            {{@$page->seo_description}}		  
					  </textarea>
          </div>

          <div class="form-group">
						<label for="exampleInputEmail1">SEO Tags</label>
						<input type="text" name="seo_tags" class="form-control" value="{{@$page->seo_tags}}" id="exampleInputEmail1" placeholder="SEO Title">
					</div>  

					<div class="row">
						<div class="col-12">
						  <input type="submit" value="Update" class="btn btn-success float-right">
						</div>
					 </div>
				</form> 
				
              </div>
            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->
@endsection