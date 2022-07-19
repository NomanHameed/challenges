@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Witness User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Edit User</li>
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
                Edit Witness User
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
			  
				<form action="{{route('admin.users.update',$users->id)}}" method="POST" enctype="multipart/form-data">
				@method('PUT')
				<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label for="exampleInputEmail1">First Name</label>
						<input type="text" name="fname" class="form-control" id="fname" placeholder="First Name" value="{{$users->first_name}}">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Last Name</label>
						<input type="text" name="lname" class="form-control" id="lname" placeholder="Last Name" value="{{$users->last_name}}">
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Address</label>
						<input type="text" value="{{@$user_info['address']}}" name="address" class="form-control" id="address" placeholder="Address">
					</div>

					<div class="form-group">
						<label>Country</label>
						<select name="country" class="form-control select2" id="countries" style="width: 100%;">
						<option value="usa" selected >United States</option>		
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
                <option value="{{$val['city']}}" <?= $val['city'] == @$user_info['city'] ? 'selected=selected' : ''; ?>>{{$val['city']}}</option>
             <?php
               }
            ?>
						</select>
					</div>
					
					<div class="form-group">
						<label for="exampleInputEmail1">Zip Code</label>
						<input type="text" name="zip_code" class="form-control" placeholder="Zip Code" value="{{@$user_info['zip_code']}}">
					</div>

					<div class="form-group">
						<label>Date Of Brith:</label>
						<?php
						$orgDate = @$user_info['dob'];
                //die('hii');
						$newDate = '';
						if($orgDate){
	            //$tempDate = explode(' ', $orgDate);
						  $tempDate = explode('/', $orgDate);//dd($tempDate);
						  $stat = checkdate(@$tempDate[1], @$tempDate[0], @$tempDate[2]);
						  if($stat){
	              //$orgDate = \Carbon\Carbon::parse(@$orgDate);
	              $orgDate = Carbon\Carbon::createFromFormat('d/m/Y', $orgDate)->format('d-m-Y');
	              $orgDate = \Carbon\Carbon::parse(@$orgDate);

						  	//$newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $orgDate);
						  	//$newDate = date("m/d/Y H:m", strtotime($orgDate));
						  	//$newDate = \Carbon\Carbon::createFromTimestamp(strtotime($orgDate))->setTimezone($timezone);
						  	$newDate = $orgDate->month.'/'.$orgDate->day.'/'.$orgDate->year.' '.$orgDate->hour.':'.$orgDate->minute;
						  }
						}
						?>
						<div class="input-group date" id="date_of_birth" data-target-input="nearest">
							<input type="text" value="{{@$newDate}}" name="dob" class="form-control datetimepicker-input" data-target="#date_of_birth">
							<div class="input-group-append" data-target="#date_of_birth" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>

					<div class="form-group">
					<label>Gender:</label>
					
						<div class="form-check">
							<input class="form-check-input" type="radio" value="male" name="gender" <?php if(@$user_info['gender']=='male'){ echo "checked"; } ?>>
							<label class="form-check-label">Male</label>
						</div>
						
						<div class="form-check">
							<input class="form-check-input" type="radio" value="female" name="gender" <?php if(@$user_info['gender']=='female'){ echo "checked"; } ?>>
							<label class="form-check-label">Female</label>
						</div>
						
						<div class="form-check">
							<input class="form-check-input" type="radio" value="others" name="gender" <?php if(@$user_info['gender']=='others'){ echo "checked"; } ?>>
							<label class="form-check-label">No Preference</label>
						</div>
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">Email</label>
						<input type="email" name="email" class="form-control" id="Email" placeholder="Email" value="{{$users->email}}">
					</div>
					
					<div class="form-group">
						<label for="exampleInputEmail1">Mobile Number</label>
						<input type="text" name="mobile_number" class="form-control" placeholder="Mobile Number" value="{{$users->mobile_number}}">
					</div>
					
					<div class="form-group">
						<label for="exampleInputEmail1">New Password</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="Password" value="">
					</div>
					
					<div class="form-group">
					  <label for="exampleInputFile">Profile Pic</label>
					  <input type="file" id="exampleInputFile" name="prof_pic">
					  <p class="help-block">Upload your profile pic.</p>			
						@if ($users->profile_pic)
							<img src="{{ asset(@$users->profile_pic) }}" width="40px" height="40px">
						@endif
					</div>
					
					<div class="form-group">
						 <div class="form-group">
							<label>Account Status</label>
							<select name="status" class="form-control select2" style="width: 100%;">
								<option value="1" @if ($users->status=='1') selected @endif >Activate</option>
								<option value="0" @if ($users->status=='0') selected @endif >Deactivate</option>
							</select>
						</div>               
					</div>		
							  
					<div class="row">
						<div class="col-12">
						  <input type="submit" value="Save Details" class="btn btn-success float-right">
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
	  @include('backend.users.extra', $user_info) 
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