@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Badge</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Badge</li>
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
                Edit Badge
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
				<form action="{{route('admin.badges.update',$badge->id)}}" method="POST" enctype="multipart/form-data">
				@method('PUT')
				<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label for="exampleInputEmail1">Badge Name</label>
						<input type="text" name="badge_name" value="{{$badge->badge_name}}" class="form-control" id="exampleInputEmail1" placeholder="Page Title">
					</div>

          <div class="form-group">
						 <div class="form-group">
							<label>Badge Condition</label>
							<select name="badge_type" id="badge_type" class="form-control select2" style="width: 100%;">
								<option value="challenge" <?php if(@$badge->badge_type=='challenge') echo 'selected' ?> >Total Challenge Completed</option>
								<option value="distance" <?php if(@$badge->badge_type=='distance') echo 'selected' ?> >Total Miles Covered</option>
                <option value="member_since" <?php if(@$badge->badge_type=='member_since') echo 'selected' ?>>Member Since</option>
							</select>
						</div>               
					</div>

          <div class="form-group badge-condition-container" style="display: none;">
             <div class="form-group">
              <label>Badge Condition</label>
              <select name="badge_condition" id="badge_condition" class="form-control select2" style="width: 100%; ">
                <option value="month" <?php if(@$badge->badge_condition=='month') echo 'selected' ?>>Month</option>
                <option value="year" <?php if(@$badge->badge_condition=='year') echo 'selected' ?>>Year</option>
              </select>
            </div>               
          </div>

          <div class="form-group distance" style="display:none;">
            <label>Add Specific Date: <input type="checkbox" name="specific_date_checkbox" class="" value="1" onchange="valueChanged()" style="width: 50px;height: 35px;" <?= @$badge->specific_date_checkbox ? 'checked="checked"' : ''; ?>></label>
            
            <div class="input-group date specific_date" id="specific_date" data-target-input="nearest" style="<?= @$badge->specific_date_checkbox ? 'display:"";' : 'display:none;'; ?>;">
              <input type="text" name="specific_date" value="<?= @$specific_date; ?>" class="form-control datetimepicker-input" data-target="#specific_date">
              <div class="input-group-append" data-target="#specific_date" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>

          <div class="form-group">
						<label for="exampleInputEmail1">Badge Condition Limit</label>
						<input type="number" name="condition_limit" class="form-control condition_limit"  value="{{@$badge->condition_limit}}" placeholder="Number">
					</div>

          <div class="form-group">
            <label for="exampleInputEmail1">Badge Information</label>
            <textarea class="form-control" name="badge_info">{{$badge->badge_info}}</textarea>
          </div>
					
				
					
          <div class="form-group">
					  <label for="exampleInputFile">Badge Logo</label>
					  <input type="file" id="exampleInputFile" name="badge_logo">
					  <p class="help-block">Upload Badge Logo.</p>			
						@if ($badge->badge_logo)
							<img src="{{ asset(@$badge->badge_logo) }}" width="50px" height="50px">
						@endif
					</div>
					
          
          


					<div class="row">
						<div class="col-12">
						  <input type="submit" value="Update Badge" class="btn btn-success float-right">
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

    function valueChanged()
    {
        if($('input[name=specific_date_checkbox]').is(":checked"))   
            $('.specific_date').css("display", "");
        else
            $('.specific_date').css("display", "none");
    }

    /*$("#repeater").createRepeater({
            //showFirstItemToDefault: true,
        });*/

   $(document).ready(function(){
    var optVal = $('select[name=badge_type]').find('option:selected').val();
    if(optVal == 'member_since'){
      $('.badge-condition-container').css("display", "");
    }else{
      $('.badge-condition-container').css("display", "none");
    }

    if(optVal == 'distance'){
        $('.distance').css("display", "");
        $('.condition_limit').attr('type', 'number');
        $('.condition_limit').attr('min', '0');
        $('.condition_limit').attr('step', '0.01');
        $('.condition_limit').val("{{@$badge->condition_limit}}");
      }else{
        $('.distance').css("display", "none");
        $('.condition_limit').attr('type', 'number');
        $('.condition_limit').removeAttr('min');
        $('.condition_limit').removeAttr('step');
        var condition_limit = $('.condition_limit').val(); 
        $('.condition_limit').val(Math.round(condition_limit));

      }

    $('select[name=badge_type]').on('change', function () {

      var optVal = $(this).find('option').filter(':selected').val(); 
      if(optVal == 'member_since'){
        $('.badge-condition-container').css("display", "");
      }else{
        $('.badge-condition-container').css("display", "none");
      }

      if(optVal == 'distance'){
        $('.distance').css("display", "");
        $('.condition_limit').attr('type', 'number');
        $('.condition_limit').attr('min', '0');
        $('.condition_limit').attr('step', '0.01');
        $('.condition_limit').val("{{@$badge->condition_limit}}");
      }else{
        $('.distance').css("display", "none");
        $('.condition_limit').attr('type', 'test');
        $('.condition_limit').removeAttr('min');
        $('.condition_limit').removeAttr('step');
        var condition_limit = $('.condition_limit').val(); 
        $('.condition_limit').val(''); 
        $('.condition_limit').val(Math.round(condition_limit));

      }
      
    });
   });
  </script>
@endsection