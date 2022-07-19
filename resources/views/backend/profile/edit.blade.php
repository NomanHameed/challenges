@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active">Edit Profile</li>
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
			 
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
			  
				<form action="" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label for="exampleInputEmail1">Display Name</label>
						<input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Full Name" value="{{$my_info->name}}">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Email</label>
						<input type="text" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" value="{{$my_info->email}}">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Timezone*</label>
            <select name="timezone" id="timezone" class="form-control">
              <option value="">Select Timezone</option>
                  <?php
                  foreach($timezoneList as $key => $val){
                  ?>
                      <option value="{{$val['zone_name']}}" <?= $val['zone_name'] == $timezone ? 'selected=selected' : ''; ?>>{{$val['zone_name']}}</option>
                  <?php
                  }
                ?>
              </select>
					</div>
          <div class="form-group">
						<label for="exampleInputEmail1">Old Password</label>
						<input type="password" name="old_password" class="form-control" id="exampleInputEmail1" placeholder="Password">
					</div>
          <div class="form-group">
						<label for="exampleInputEmail1">New Password</label>
						<input type="password" name="new_password" class="form-control" id="exampleInputEmail1" placeholder="Password">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Confirm Password</label>
						<input type="password" name="confirm_password" class="form-control" id="exampleInputEmail1" placeholder="Password">
					</div>
					
					<div class="form-group">
					  <label for="exampleInputFile">Profile Pic</label>
					  <input type="file" id="exampleInputFile" name="prof_pic">
					  <p class="help-block">Upload your profile pic.</p>
					  <img src="{{ asset('storage/admin/profile_image/'.$my_info->profile_pic) }}" width="80px" height="80px">
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