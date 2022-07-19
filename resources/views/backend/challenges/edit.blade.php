@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Challenge</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Challenge</li>
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
			  Edit Challenge
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

			@if(Session::has('message'))
				<div class="alert alert-success alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> Alert!</h4>
					{{ Session::get('message')}}
              	</div>
			 @endif 

		 
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
			  <?php //echo '<pre>';print_r($challenge);
			  //print_r($challenge_info); die; ?>
        <form action="{{route('admin.challenges.update',$challenge->id)}}" method="POST" enctype="multipart/form-data">
				@method('PUT')
				<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label for="exampleInputEmail1">Challenge Title</label>
						<input type="text" name="name" value="{{$challenge->name}}" class="form-control" placeholder="Name">
					</div>

					<!-- <div class="form-group">
						<label for="exampleInputEmail1">Type</label>
						<select name="type" class="form-control select2" style="width: 100%;">
							<option value="running" <?php if($challenge->type=='running') echo 'selected' ?> >Running</option>
							<option value="crossfit" <?php if($challenge->type=='crossfit') echo 'selected' ?>>Crossfit</option>
							<option value="walking" <?php if($challenge->type=='walking') echo 'selected' ?>>Walking</option>
							<option value="cycling" <?php if($challenge->type=='cycling') echo 'selected' ?>>Cycling</option>
							<option value="hillwalking" <?php if($challenge->type=='hillwalking') echo 'selected' ?>>Hillwalking</option>
							<option value="trekking" <?php if($challenge->type=='trekking') echo 'selected' ?>>Trekking</option>
							<option value="swimming" <?php if($challenge->type=='swimming') echo 'selected' ?>>Swimming</option>
						</select>
					</div> -->
					<?php
					$orgDate = $challenge->event_start_date;

					$newDate = '';
					if($orgDate){
            $tempDate = explode(' ', $orgDate);
					  $tempDate = explode('-', $tempDate['0']);
					  $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
					  if($stat){
					  	
              $orgDate = \Carbon\Carbon::parse(@$orgDate, 'UTC')->setTimezone($timezone);

					  	//$newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $orgDate);
					  	//$newDate = date("m/d/Y H:m", strtotime($orgDate));
					  	//$newDate = \Carbon\Carbon::createFromTimestamp(strtotime($orgDate))->setTimezone($timezone);
					  	$newDate = $orgDate->month.'/'.$orgDate->day.'/'.$orgDate->year.' '.$orgDate->hour.':'.$orgDate->minute;
					  }
					}
					?>
					<div class="form-group">
						<label>Event Start Date:</label>
						<div class="input-group date" id="event_start_date" data-target-input="nearest">
							<input type="text" value="{{$newDate}}" name="event_start_date" class="form-control datetimepicker-input" data-target="#event_start_date">
							<div class="input-group-append" data-target="#event_start_date" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>
					<?php
					$orgDate = $challenge->event_end_date;

					$newDate = '';

					$disable = '';
					if($orgDate){
            $tempDate = explode(' ', $orgDate);
					  $tempDate = explode('-', $tempDate['0']);
					  $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
					  if($stat){ //echo \Carbon\Carbon::now('UTC')->toDateTimeString() .'>'. $orgDate; echo \Carbon\Carbon::now('UTC')->toDateTimeString() > $orgDate;
					  	if(\Carbon\Carbon::now('UTC')->toDateTimeString() > $orgDate){
                  $disable = 'disabled="disabled"';
					  	}

					  	$orgDate = \Carbon\Carbon::parse(@$orgDate, 'UTC')->setTimezone($timezone);
					  	//$newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $orgDate, 'UTC')->setTimezone($timezone);
					  	//$newDate = date("m/d/Y H:m", strtotime($orgDate));
					  	//$newDate = \Carbon\Carbon::createFromTimestamp(strtotime($orgDate))->setTimezone($timezone)->format('m/d/Y H:m'); 
              $newDate = $orgDate->month.'/'.$orgDate->day.'/'.$orgDate->year.' '.$orgDate->hour.':'.$orgDate->minute;
					  }
					}
					?>
					<div class="form-group">
						<label>Event End Date:</label>
						<div class="input-group date" id="event_end_date" data-target-input="nearest">
							<input type="text" value="{{$newDate}}" name="event_end_date" class="form-control datetimepicker-input" data-target="#event_end_date">
							<div class="input-group-append" data-target="#event_end_date" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>
					<?php
					$orgDate = $challenge->reg_start_date;
					$newDate = date("m/d/Y H:m", strtotime($orgDate));
					?>
					<!-- <div class="form-group">
						<label>Registration Start Date:</label>
						<div class="input-group date" id="reg_start_date" data-target-input="nearest">
							<input type="text" name="reg_start_date" value="{{$newDate}}" class="form-control datetimepicker-input" data-target="#reg_start_date">
							<div class="input-group-append" data-target="#reg_start_date" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div> -->
					<?php
					$orgDate = $challenge->reg_end_date;
					$newDate = date("m/d/Y H:m", strtotime($orgDate));
					?>
					<!-- <div class="form-group">
						<label>Registration End Date:</label>
						<div class="input-group date" id="reg_end_date" data-target-input="nearest">
							<input type="text" name="reg_end_date" value="{{$newDate}}" class="form-control datetimepicker-input" data-target="#reg_end_date">
							<div class="input-group-append" data-target="#reg_end_date" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div> -->


					<!-- <div class="form-group">
						<label for="exampleInputEmail1">Event Info</label>
						<textarea class="form-control" name="description">{{$challenge->description}}</textarea>
					</div> -->
					

					<!-- <div class="form-group">
						<label for="exampleInputEmail1">Allowed No. Of Participents</label>
						<input type="number" value="{{$challenge->allowed_participants}}" name="allowed_participants" class="form-control" placeholder="Number">
					</div> -->

					<!--<div class="form-group">
						<label for="exampleInputEmail1">Google Code</label>
						<textarea class="form-control"  name="google_code" placeholder="Google Code">{{@$challenge_info['google_code']}}</textarea>
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">Facebook Code</label>
						<textarea class="form-control" name="facebook_code"  placeholder="Facebook Code">{{@$challenge_info['facebook_code']}}</textarea>
					</div>-->

					<!-- <div class="form-group">
						<label for="exampleInputEmail1">Challenge Display Name</label>
						<input type="text" name="sub_title" value="{{@$challenge_info['sub_title']}}" class="form-control" id="lname" placeholder="Last Name">
					</div> -->

					<div class="form-group">
						<label for="exampleInputEmail1">Type</label>
						<select name="category" class="form-control select2" style="width: 100%;">
								<option value="individual" <?php if($challenge_info['category']=='individual') echo 'selected' ?> >Individual</option>
								<option value="accumulative" <?php if($challenge_info['category']=='accumulative') echo 'selected' ?> >Accumulative</option>
							</select>
					</div>

					<!-- <div class="form-group">
						<label for="exampleInputEmail1">Challenge Charges</label>
						<select name="price_type" class="form-control select2" style="width: 100%;" id="price_type">
							<option value="free" <?php if(@$challenge->price_type=='free') echo 'selected' ?> >Free</option>
							<option value="paid" <?php if(@$challenge->price_type=='paid') echo 'selected' ?>>Paid</option>
						</select>
					</div> -->

					<?php 
					$style = "display:block;";
					if($challenge->price_type=='free'){
						$style = "display:none;";
					} ?>
					<!-- <div class="form-group" id="fill_price" style="<?php echo $style; ?>">
						<label for="exampleInputEmail1">Challenge Price($)</label>
						<input type="number" name="challenge_price" value="{{@$challenge_info['challenge_price']}}" class="form-control" id="price" placeholder="Price in $">
					</div> -->
					
					<div class="form-group">
					  <label for="exampleInputFile">Challenge Dashboard Pic</label>
					  <input type="file" id="exampleInputFile" name="challenge_pic">
					  <p class="help-block">Upload Challenge Dashboard Pic.</p>
					  @if ($challenge->image)
							<img src="{{ asset(@$challenge->image) }}" width="50px" height="50px">
						@endif
					</div>

					<div class="form-group">
					  <label for="exampleInputFile1">Challenge Details Page Pic</label>
					  <input type="file" id="exampleInputFile1" name="challenge_details_page_pic">
					  <p class="help-block">Upload Challenge Details Pic.</p>
					  @if ($challenge->challenge_details_page_pic)
							<img src="{{ asset(@$challenge->challenge_details_page_pic) }}" width="50px" height="50px">
						@endif
					</div>
					
					<div class="form-group">
						 <div class="form-group">
							<label>Status</label>
							<select name="status" class="form-control select2" style="width: 100%;">
								<option value="1" <?php if($challenge->status=='1') echo 'selected' ?> >Active</option>
								<option value="0" <?php if($challenge->status=='0') echo 'selected' ?>>Inactive</option>
							</select>
						</div>               
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
      @include('backend.challenges.extra', $challenge_info) 
    </section>
    <!-- /.content -->
	<script>
		/*var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();

    today = yyyy + '-' + mm + '-' + dd;
    $('input[name=event_end_date]').attr('min',today);*/

		$(document).ready(function(){

			$(document).on('change', '#milestone_type', function() {
				var val= $("#milestone_type option:selected").val();
				if(val == 'distance_milestone'){
		       $('.distance_milestone').css("display", "");
		       $('.monthly').css("display", "none");
				}else if(val == 'monthly_milestone'){
		       $('.distance_milestone').css("display", "none");
		       $('.monthly').css("display", "");
				}
			  
			});

			$(document).on('change', '#milestone_type_edit', function() {
				var val = $("#milestone_type_edit option:selected").val();
				if(val == 'distance_milestone'){
		       $('.distance_milestone_edit').css("display", "");
		       $('.monthly_edit').css("display", "none");
				}else if(val == 'monthly_milestone'){
		       $('.distance_milestone_edit').css("display", "none");
		       $('.monthly_edit').css("display", "");
				}
			  
			});

			$('#price_type').change(function(){
				$value = $(this).val();
				if($value=="paid"){
					$('#fill_price').show();
				}else{
					$('#fill_price').hide();
					$('#price').val('');
				}
			})
		})
	</script>

@endsection
