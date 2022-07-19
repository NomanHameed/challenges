@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Add User</li>
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
                Add New User
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
			@if(Session::has('alert'))
			<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fas fa-ban"></i> Alert!</h4>
                {{ Session::get('alert')}}
              </div>
			 @endif 
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
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
			  
				<form action="{{route('admin.users.store')}}" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label for="exampleInputEmail1">First Name</label>
						<input type="text" name="fname" class="form-control" id="fname" placeholder="First Name">
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">Last Name</label>
						<input type="text" name="lname" class="form-control" id="lname" placeholder="Last Name">
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">Address</label>
						<input type="text" name="address" class="form-control" id="address" placeholder="Address">
					</div>

					<div class="form-group">
						<label>Country</label>
						<select name="country" class="form-control select2" id="countries" style="width: 100%;">
						<option value="" selected ></option>
						<option value="usa">United States</option>		
						</select>
					</div>
					
					<div class="form-group">
						<label>State</label>
						<select name="state" id="states" class="form-control select2" style="width: 100%;">
						<?php
               foreach($stateList as $key => $val){
             ?>
                <option value="{{$val['id']}}" <?= $val['id'] == @$user_info['state'] ? 'selected=selected' : ''; ?>>{{$val['state_name']}}</option>
             <?php
               }
            ?>
						</select>
					</div>

					<div class="form-group">
						<label>City</label>
						<select name="city" id="cities" class="form-control select2" style="width: 100%;">
						<?php
               foreach($citieList as $key => $val){
             ?>
                <option value="{{$val['city']}}">{{$val['city']}}</option>
             <?php
               }
            ?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="exampleInputEmail1">Zip Code</label>
						<input type="text" name="zip_code" class="form-control" placeholder="Zip Code">
					</div>

					<div class="form-group">
						<label>Date Of Brith:</label>
						<div class="input-group date" id="date_of_birth" data-target-input="nearest">
							<input type="text" name="dob" class="form-control datetimepicker-input" data-target="#date_of_birth">
							<div class="input-group-append" data-target="#date_of_birth" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>

					<div class="form-group">
					<label>Gender:</label>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="gender" value="male" checked="">
							<label class="form-check-label">Male</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="gender" value="female">
							<label class="form-check-label">Female</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="gender" value="others">
							<label class="form-check-label">No Preference</label>
						</div>
					</div>

		

					<div class="form-group">
						<label for="exampleInputEmail1">Email</label>
						<input type="email" name="email" class="form-control" id="Email" placeholder="Email">
					</div>
					
					<div class="form-group">
						<label for="exampleInputEmail1">Mobile Number</label>
						<input type="number" name="mobile_number" class="form-control" placeholder="Mobile Number">
					</div>
								
					<div class="form-group">
						<label for="exampleInputEmail1">Password</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="Password">
					</div>
					
					<div class="form-group">
					  <label for="exampleInputFile">Profile Pic</label>
					  <input type="file" id="exampleInputFile" name="prof_pic">
					  <p class="help-block">Upload your profile pic.</p>
					</div>
					
					<div class="form-group">
						  <label>Account Status</label>
						  <select class="form-control" name="status">
							<option value="1">Activate</option>
							<option value="0">Deactivate</option>
						  </select>
					</div>
							  
					<div class="row">
						<div class="col-12">
						  <input type="submit" value="Add User" class="btn btn-success float-right">
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
    <script>
	  	$('#countries').on('change', function() {
		var country	=	$(this).val();	
		if(country=='usa'){
			var call_url	=	'/admin/ajax/get_us_state';
		}else{
			$("#states").html('');
			$("#cities").html('');
		}
		  $.ajax({
				   type:'POST',
				   url:call_url,
				   data: {
					"_token": "{{ csrf_token() }}",
					"country": country
					},
				   success:function(data) {
					  $("#states").html(data.msg);				  
				   }
				});
	});
	
	
	$('#states').on('change', function() {
      var state   =  $(this).val();
      var country =  $('#countries').val();              
      //if(country=='usa'){
         var call_url   =  '/admin/ajax/get_us_cities_profile';
      /*}else{
         $("#cities").html('');
         $("#states").html('');
      }*/
                  
      $.ajax({
            type:'POST',
            url:call_url,
            data: {
            "_token": "{{ csrf_token() }}",
            "state": state
            },
            success:function(data) { 
               $("#cities").html('');
              $("#cities").html(data.msg); 
            }
      });
   });
  </script>
@endsection