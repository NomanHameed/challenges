@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add New Challenge</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Challenge</li>
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
                Add New Challenge
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
			  
				<form action="{{route('admin.challenges.store')}}" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label for="exampleInputEmail1">Challenge Title</label>
						<input type="text" name="name" class="form-control" id="fname" value="{{ old('name') }}" placeholder="Name">
					</div>

					<!-- <div class="form-group">
						<label for="exampleInputEmail1">Type</label>
						<select name="type" class="form-control select2" style="width: 100%;">
							<option value="running" >Running</option>
              <option value="crossfit" >Crossfit</option>
              <option value="walking" >Walking</option>
              <option value="cycling" >Cycling</option>
              <option value="hillwalking" >Hillwalking</option>
              <option value="treking" >Trekking</option>
              <option value="swimming" >Swimming</option>
						</select>
					</div> -->

					<div class="form-group">
						<label>Event Start Date:</label>
						<div class="input-group date" id="event_start_date" data-target-input="nearest">
							<input type="text" value="{{ old('event_start_date') }}" name="event_start_date" class="form-control datetimepicker-input" data-target="#event_start_date">
							<div class="input-group-append" data-target="#event_start_date" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Event End Date:</label>
						<div class="input-group date" id="event_end_date" data-target-input="nearest">
							<input type="text" value="{{ old('event_end_date') }}" name="event_end_date" class="form-control datetimepicker-input" data-target="#event_end_date">
							<div class="input-group-append" data-target="#event_end_date" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>

					<!-- <div class="form-group">
						<label>Registration Start Date:</label>
						<div class="input-group date" id="reg_start_date" data-target-input="nearest">
							<input type="text" value="{{ old('reg_start_date') }}" name="reg_start_date" class="form-control datetimepicker-input" data-target="#reg_start_date">
							<div class="input-group-append" data-target="#reg_start_date" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label>Registration End Date:</label>
						<div class="input-group date" id="reg_end_date" data-target-input="nearest">
							<input type="text" value="{{ old('reg_end_date') }}" name="reg_end_date" class="form-control datetimepicker-input" data-target="#reg_end_date">
							<div class="input-group-append" data-target="#reg_end_date" data-toggle="datetimepicker">
								<div class="input-group-text"><i class="fa fa-calendar"></i></div>
							</div>
						</div>
					</div>


					<div class="form-group">
						<label for="exampleInputEmail1">Event Info</label>
						<textarea class="form-control" name="description">{{ old('description') }}</textarea>
					</div> -->
					

					<!-- <div class="form-group">
						<label for="exampleInputEmail1">Allowed No. Of Participents</label>
						<input type="number" value="{{ old('allowed_participants') }}" name="allowed_participants" class="form-control" placeholder="Number">
					</div>
 -->
					<div class="form-group">
						<label for="exampleInputEmail1">Google Code</label>
						<textarea class="form-control" name="google_code" placeholder="Google Code">{{ old('google_code') }}</textarea>
					</div>

					<!--<div class="form-group">
						<label for="exampleInputEmail1">Facebook Code</label>
						<textarea class="form-control" name="facebook_code" placeholder="Facebook Code">{{ old('facebook_code') }}</textarea>
					</div>

					<div class="form-group">
						<label for="exampleInputEmail1">Challenge Display Name</label>
						<input type="text" name="sub_title" value="{{ old('sub_title') }}" class="form-control" id="lname" placeholder="Last Name">
					</div>-->

					<div class="form-group">
						<label for="exampleInputEmail1">Type</label>
						<select name="category" class="form-control select2" style="width: 100%;">
							<option value="individual" selected >Individual</option>
							<option value="accumulative" >Accumulative</option>
						</select>
					</div>

					<!-- <div class="form-group">
						<label for="exampleInputEmail1">Challenge Charges</label>
						<select name="price_type" class="form-control select2" style="width: 100%;" id="price_type">
							<option value="free" selected >Free</option>
							<option value="paid" >Paid</option>
						</select>
					</div> -->

					<!-- <div class="form-group" id="fill_price" style="display:none;">
						<label for="exampleInputEmail1">Challenge Price($)</label>
						<input type="number" name="challenge_price" class="form-control" id="price" placeholder="Price in $">
					</div> -->
					
					<div class="form-group">
					  <label for="exampleInputFile">Challenge Dashboard Pic</label>
					  <input type="file" id="exampleInputFile" name="challenge_pic">
					  <p class="help-block">Upload Challenge Dashboard Pic.</p>
					</div>

					<div class="form-group">
					  <label for="exampleInputFile1">Challenge Details Page Pic</label>
					  <input type="file" id="exampleInputFile1" name="challenge_details_page_pic">
					  <p class="help-block">Upload Challenge Details Pic..</p>
					</div>
					
					<div class="form-group">
						 <div class="form-group">
							<label>Status</label>
							<select name="status" class="form-control select2" style="width: 100%;">
								<option value="1" selected >Active</option>
								<option value="0" >Inactive</option>
							</select>
						</div>               
					</div>
					
					<div class="row">
						<div class="col-12">
						  <input type="submit" value="Add Challenge" class="btn btn-success float-right">
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
		$(document).ready(function(){
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