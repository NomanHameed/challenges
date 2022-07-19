@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Site Options</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active">Change Options</li>
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
            @if (count($errors) > 0)
				<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-ban"></i> Alert!</h5>
					<ul>
						@foreach ($errors->all() as $error)
						  <li>{{ $error }}</li>
					   @endforeach
					</ul>
                </div>
		  @endif
		  
		  
		  @if(Session::has('message'))
			<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                {{ Session::get('message')}}
              </div>
			 @endif 

       @if(Session::has('error'))
			<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                {{ Session::get('error')}}
              </div>
			 @endif
			 <?php //print_r($my_info); ?>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
			  
				<form action="" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{csrf_token()}}">

          <div class="form-group">
					  <label for="exampleInputFile">Site Logo</label>
					  <input type="file" id="exampleInputFile" name="site_logo">
					  <p class="help-block">Upload Site Logo.</p>
            <?php if(isset($my_ifo['site_logo']) && $my_ifo['site_logo'] !=''){ ?>
                <img src="{{ asset('storage/admin/site_logo/'.@$my_info['site_logo']) }}" width="80px" height="80px">
            <?php }?>
					  
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">Contact Details</label>
						<textarea name="contact_details" class="form-control" >{{$my_info['contact_details']}}</textarea>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Facebook Page Link</label>
						<input type="text" name="facebook_link" class="form-control" value="{{@$my_info['facebook_link']}}" placeholder="link" >
					</div>
          <div class="form-group">
						<label for="exampleInputEmail1">SEO Title</label>
						<input type="text" name="seo_title" class="form-control" value="{{@$my_info['seo_title']}}" placeholder="Page Title">
					</div>

          <div class="form-group">
						<label for="exampleInputEmail1">SEO Description</label>
            <textarea name="seo_description" class="form-control">
            {{@$my_info['seo_description']}}
					  </textarea>
          </div>

          <div class="form-group">
						<label for="exampleInputEmail1">Google Analytics</label>
            <textarea name="google_analytics" class="form-control">
            {{@$my_info['google_analytics']}}
					  </textarea>
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