<div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-edit"></i>
              Additional Informations
            </h3>
          </div>
          <div class="card-body">
            <h4>Informations</h4>
            <div class="row">
              <div class="col-5 col-sm-3">
                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                  <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Venue</a>
                  <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-service" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Milestones</a>
                  <!-- <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Products</a>
				  <a class="nav-link" id="vert-tabs-participants-tab" data-toggle="pill" href="#vert-tabs-participants" role="tab" aria-controls="vert-tabs-participants" aria-selected="false">Participants</a> -->
                 
                </div>
              </div>
              <div class="col-7 col-sm-9">
                <div class="tab-content" id="vert-tabs-tabContent">
				
				<!--- Tab for adding Venue------------------->
                  <div class="tab-pane text-left fade  active show" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">              
				  <div class="card card-secondary">
					<div class="card-header">
						<h3 class="card-title">Add start and end point</h3>
					</div>
						<div class="card-body">
							@if(Session::has('success'))
								<div class="alert alert-success alert-dismissible">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<h4><i class="icon fa fa-check"></i> Alert!</h4>
									{{ Session::get('success')}}
								</div>
							@endif 
							
							<form action="{{route('addlocation')}}" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="_token" value="{{csrf_token()}}">	
								<input type="hidden" name="challenge_id" value="{{$challenge->id}}">
							
								<div class="form-group">
									<label for="exampleInputFile">KML File</label>
									<input type="file" id="exampleInputFile" name="kml_file">
									<p class="help-block">Upload KML File.</p>
									@if (@$challenge_info['kml_file'])
											<a href="{{ asset(@$challenge_info['kml_file']) }}" target="_blank">view/download</a>
											<!--<div id="map"></div>-->
											<style>
												#map {
													height: 360px;
													width: 900px;
													overflow: hidden;
													float: left;
													border: thin solid #333;
													}
											</style>
											<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAESvkKxPXKAxJNRdp2k1L17Fy-L-Y91zI&callback=initMap"></script>
											<script>
												var map;
												var src = "{{ asset(@$challenge_info['kml_file']) }}";
												
												function initMap() {
													var map = new google.maps.Map(document.getElementById('map'), {
														zoom: 12,
														center: {
															lat: 23.27413,
															lng: 77.42063
														}
													});
													var georssLayer = new google.maps.KmlLayer({
														url: src,
													});
													google.maps.event.addListener(georssLayer, 'status_changed', function() {
														console.log(georssLayer.getStatus());
													})
													georssLayer.setMap(map);
												};

											</script>
										@endif
								</div>

								<div class="form-group">
									<label>Start Point</label>
									<input type="text" name="start_point" class="form-control" value="{{@$challenge_info['start_point']}}">
								</div>

								<div class="form-group">
									<label>End Point</label>
									<input type="text" name="end_point" class="form-control" value="{{@$challenge_info['end_point']}}">
								</div>

								<div class="form-group">
									<label>Total Distance In Miles</label>
									<input type="number" name="total_distance" class="form-control" placeholder="Distance in miles" value="{{@$challenge_info['total_distance']}}">
								</div>
								
								<div class="row">
									<div class="col-12">
									<input type="submit" value="Save/Update" class="btn btn-success float-right">
									</div>
								</div>
							</form>
   
						 </div>
					</div>
                  </div>
				  <!--- Tab for adding Milestones------------------->
                  <div class="tab-pane fade" id="vert-tabs-service" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                      <div class="card card-secondary">
						  <div class="card-header">
							<h3 class="card-title">Milestones</h3>
							<h3 class="card-title" style="float:right">
								<button type="button" id="view_milestones" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">
									View All Milestones
								</button>
							</h3>
						  </div>
						  <div class="card-body">
								@if(Session::has('success'))
									<div class="alert alert-success alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<h4><i class="icon fa fa-check"></i> Alert!</h4>
										{{ Session::get('success')}}
									</div>
								@endif 
								<form action="{{route('addmilestone')}}" method="POST" enctype="multipart/form-data">
									<input type="hidden" name="_token" value="{{csrf_token()}}">	
									<input type="hidden" name="challenge_id" value="{{$challenge->id}}">
								
									<div class="form-group">
										<label>Milestone Name</label>
										<input type="text" name="milestone_name" class="form-control" placeholder="Name" value="">
									</div>

									<div class="form-group">
										<label>Milestone Type</label>
										<select name="milestone_type" id="milestone_type" class="form-control">
											<option value="distance_milestone">Distance Milestone</option>
											<option value="monthly_milestone">Monthly Milestone</option>
										</select>
									</div>
									
									<div class="form-group distance_milestone">
										<label>Milestone Distance</label>
										<input type="text" name="milestone_distance" class="form-control" placeholder="Distance" value="">
									</div>

									<div class="row monthly" style="display:none">
										<div class="col-sm-6">
											<div class="form-group">
													<label>Start date</label>
													<div class="input-group date" id="start_date" data-target-input="nearest">
														<input type="text" name="start_date" value="" class="form-control datetimepicker-input" data-target="#start_date">
														<div class="input-group-append" data-target="#start_date" data-toggle="datetimepicker">
															<div class="input-group-text"><i class="fa fa-calendar"></i></div>
														</div>
													</div>
												</div>
											</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label>End Date</label>
												<div class="input-group date" id="end_date" data-target-input="nearest">
													<input type="text" name="end_date" value="" class="form-control datetimepicker-input" data-target="#end_date">
													<div class="input-group-append" data-target="#end_date" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group distance_milestone">
										<label>Add Specific Date: <input type="checkbox" name="specific_date_checkbox" class="" value="1" onchange="valueChanged()" style="width: 50px;height: 35px;"></label>
										
                    <div class="input-group date specific_date" id="specific_date" data-target-input="nearest" style="display:none;">
											<input type="text" name="specific_date" value="" class="form-control datetimepicker-input" data-target="#specific_date">
											<div class="input-group-append" data-target="#specific_date" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i></div>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="exampleInputFile">Milestone Pic</label>
										<input type="file" id="exampleInputFile" name="milestone_pic">
										<p class="help-block">Upload Milestone pic.</p>
									</div>
									
									<div class="row">
										<div class="col-12">
										<input type="submit" value="Add" class="btn btn-success float-right">
										</div>
									</div>
								</form>
   
						 	</div>
					</div>
                  
                  </div>
                  <!---Tab for Profile Information---->
				  <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
						 <div class="card card-secondary">
						  <div class="card-header">
							<h3 class="card-title">Attach Products</h3>
						  </div>
						  <div class="card-body">
						  <section class="content">
								  <div class="row">
									<div class="col-12">

									  <div class="card">
										<div class="card-header">
										</div>
										<!-- /.card-header -->
										<div class="card-body">
										  <table id="example1" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th>Product Name</th>
											  <th>Image</th>
											  <th>Price</th>
											  <th>Action</th>
											</tr>
											</thead>
											<tbody>
											@foreach($challenge_info['products'] as $product)
												<tr>
													<td>{{$product->name}}{{$product->is_attached}}</td>
													<td><img src="{{ asset(@$product->product_image) }}" width="50px" height="50px"></td>
													<td>{{$product->price}}$</td>
													<td>
														<a href=""><button type="button" class="btn btn-info">Edit</button></a>
														<?php if($product->is_attached==1){?>
															<button type="button" id="{{$product->id}}" class="btn btn-success remove_it product">Attached !</button>
														<?php }else{ ?>
															<button type="button" id="{{$product->id}}" class="btn btn-info attach_it product">Attach</button>
														<?php } ?>
														
														
													</td>
												</tr>
											@endforeach
											</tbody>
											<tfoot>
											<tr>
											  <th>Product Name</th>
											  <th>Image</th>
											  <th>Price</th>
											  <th>Action</th>
											</tr>
											</tfoot>
										  </table>
										</div>
										<!-- /.card-body -->
									  </div>
									  <!-- /.card -->
									</div>
									<!-- /.col -->
								  </div>
								  <!-- /.row -->
								</section>
						  </div>
					</div>                 
                   </div>




				    <!---Tab for Participants---->
				  <div class="tab-pane fade" id="vert-tabs-participants" role="tabpanel" aria-labelledby="vert-tabs-participants-tab">
						 <div class="card card-secondary">
						  <div class="card-header">
							<h3 class="card-title">Participants</h3>
						  </div>
						  <div class="card-body">
						  <section class="content">
								  <div class="row">
									<div class="col-12">

									  <div class="card">
										<div class="card-header">
										</div>
										<!-- /.card-header -->
										<div class="card-body">
										  <table id="example1" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th>Name</th>
											  <th>status</th>
											  <th>Action</th>
											</tr>
											</thead>
											<tbody>
											@foreach($participants as $participant)
												<tr>
													<td>{{$participant->name}}</td>
													<td>Pending</td>
													<td>
														<a href="{{ route('admin.users.edit',$participant->id) }}"><button type="button" class="btn btn-info">view User</button></a>
													</td>
												</tr>
											@endforeach
											</tbody>
											<tfoot>
											<tr>
											  <th>Name</th>
											  <th>status</th>
											  <th>Action</th>
											</tr>
											</tfoot>
										  </table>
										</div>
										<!-- /.card-body -->
									  </div>
									  <!-- /.card -->
									</div>
									<!-- /.col -->
								  </div>
								  <!-- /.row -->
								</section>
						  </div>
					</div>                 
                   </div>
                  
				  
				  
                </div>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
		
		
		
		<div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Time Table List</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <section class="content">
				  <div class="row">
					<div class="col-12">

					  <div class="card">
						<div class="card-header">
						</div>
						<!-- /.card-header -->
						<div class="card-body">
						  <table id="example1" class="table table-bordered table-striped">
							<thead>
							<tr>
							  <th>ID</th>
							  <th>Name</th>
							  <th>Type</th>
							  <th>Distance</th>
							  <th>Start Date</th>
							  <th>End Date</th>
							  <th>Specific Date</th>
							  <th>Image</th>
							  <th>Action</th>
							</tr>
							</thead>
							<tbody id="time_table_content">
								<tr >
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
							<tfoot>
							<tr>
							  <th>ID</th>
							  <th>Name</th>
							  <th>Type</th>
							  <th>Distance</th>
							  <th>Start Date</th>
							  <th>End Date</th>
							  <th>Specific Date</th>
							  <th>Image</th>
							  <th>Action</th>
							</tr>
							</tfoot>
						  </table>
						</div>
						<!-- /.card-body -->
					  </div>
					  <!-- /.card -->
					</div>
					<!-- /.col -->
				  </div>
				  <!-- /.row -->
				</section>
   
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->

    <div class="modal fade" id="modal-edit-milestone">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Update Milestone</h4>
              <button type="button" class="close-Mile-Model" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <section class="content">
							  <div class="row">
								<div class="col-12">

								  <div class="card">
									<div class="card-header">
									</div>
									<!-- /.card-header -->
									<div class="card-body">
									  <form id="editmilestoneForm" action="{{route('editmilestone')}}" method="POST" enctype="multipart/form-data">
									  	@method('PUT')
											<input type="hidden" name="_token" value="{{csrf_token()}}">	
											<input type="hidden" name="challenge_id" value="{{$challenge->id}}">
											<input type="hidden" name="milestone_id" value="">
										
											<div class="form-group">
												<label>Milestone Name</label>
												<input type="text" name="milestone_name" class="form-control" placeholder="Name" value="">
											</div>

											<div class="form-group">
												<label>Milestone Type</label>
												<select name="milestone_type" id="milestone_type_edit" class="form-control">
													<option value="distance_milestone">Distance Milestone</option>
													<option value="monthly_milestone">Monthly Milestone</option>
												</select>
											</div>
											
											<div class="form-group distance_milestone_edit">
												<label>Milestone Distance</label>
												<input type="text" name="milestone_distance" class="form-control" placeholder="Distance" value="">
											</div>

											<div class="row monthly_edit" style="display:none">
												<div class="col-sm-6">
													<div class="form-group">
															<label>Start date</label>
															<div class="input-group date" id="start_date_edit" data-target-input="nearest">
																<input type="text" name="start_date" value="" class="form-control datetimepicker-input" data-target="#start_date_edit">
																<div class="input-group-append" data-target="#start_date_edit" data-toggle="datetimepicker">
																	<div class="input-group-text"><i class="fa fa-calendar"></i></div>
																</div>
															</div>
														</div>
													</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label>End Date</label>
														<div class="input-group date" id="end_date_edit" data-target-input="nearest">
															<input type="text" name="end_date" value="" class="form-control datetimepicker-input" data-target="#end_date_edit">
															<div class="input-group-append" data-target="#end_date_edit" data-toggle="datetimepicker">
																<div class="input-group-text"><i class="fa fa-calendar"></i></div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="form-group distance_milestone_edit">
												<label>Add Specific Date: <input type="checkbox" name="specific_date_checkbox" class="" value="1" onchange="valueChanged()" style="width: 50px;height: 35px;"></label>
												
		                    <div class="input-group date specific_date" id="specific_date_edit" data-target-input="nearest" style="display:none;">
													<input type="text" name="specific_date" value="" class="form-control datetimepicker-input" data-target="#specific_date_edit">
													<div class="input-group-append" data-target="#specific_date_edit" data-toggle="datetimepicker">
														<div class="input-group-text"><i class="fa fa-calendar"></i></div>
													</div>
												</div>
											</div>

											<div class="form-group">
												<label for="exampleInputFile">Milestone Pic</label>
												<input type="file" id="exampleInputFile" name="milestone_pic">
												<p class="help-block showMSPic">Upload Milestone pic.</p>
											</div>
											
											<div class="row">
												<div class="col-12">
												<input type="submit" value="Update" class="btn btn-success float-right">
												</div>
											</div>
										</form>
									</div>
									<!-- /.card-body -->
								  </div>
								  <!-- /.card -->
								</div>
								<!-- /.col -->
							  </div>
							  <!-- /.row -->
							</section>
   
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->
	  
	  
	  <div class="modal fade" id="modal-img">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header"  style="text-align:center;">
              <h4 class="modal-title">Upload Form</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
             <section class="content">
				<form action="" method="POST" enctype="multipart/form-data">
				{{csrf_field()}}
				<input type="hidden" name="provider_id" value="{{@$provider->id}}">
				  <div class="form-group">
					  <label for="exampleInputFile">Images</label>
					  <input type="file" id="image-upload" name="image_upload[]" multiple>
					  <p class="help-block">Upload your Images.</p>	
					</div>		
							  
					<div class="row">
						<div class="col-12">
						  <input type="submit" value="Upload" class="btn btn-success float-right">
						</div>
					 </div>
				</form>
			</section>  
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->
	  
	  <script>

	$(document).ready(function(){



		


   	var optVal = $('input[name=specific_date_checkbox]:checked').val();
 		if(optVal){
        $('.specific_date').css("display", "");
   		}else{
   			$('.specific_date').css("display", "none");
   		}

   	
  });

  function valueChanged()
    {
        if($('input[name=specific_date_checkbox]').is(":checked"))   
            $('.specific_date').css("display", "");
        else
            $('.specific_date').css("display", "none");
    }
	
	$('.product').on('click', function() {
		var button = $(this);
		var id = button.attr('id');

		if(button.hasClass('attach_it')){
		  $.ajax({
				   type:'POST',
				   url:'/admin/ajax/attach_product',
				   data: {
					"_token": "{{ csrf_token() }}",
					"product_id": id,
					"challenge_id": "{{$challenge->id}}",
					},
				   success:function(data) {
					button.removeClass('attach_it');
					button.addClass('remove_it');	
					button.removeClass('btn-info');
					button.addClass('btn-success');	
					button.html('Attached !');								  
				   }
				});
		}else{
			$.ajax({
				   type:'POST',
				   url:'/admin/ajax/unattach_product',
				   data: {
					"_token": "{{ csrf_token() }}",
					"product_id": id,
					"challenge_id": "{{$challenge->id}}",
					},
				   success:function(data) {
					button.removeClass('remove_it');
					button.addClass('attach_it');	
					button.removeClass('btn-success');
					button.addClass('btn-info');	
					button.html('Attach');								  
				   }
				});
		}
	});


	  $('#view_milestones').on('click', function() {
		  $.ajax({
				   type:'POST',
				   url:'/admin/ajax/get_challenge_milestone',
				   data: {
					"_token": "{{ csrf_token() }}",
					"id": "{{@$challenge->id}}",
					"timezone": "{{@$timezone}}"
					},
				   success:function(data) {
					  $("#time_table_content").html(data.msg);				  
				   }
				});
	});
	
	function delete_me(id, el){
		$.ajax({
		   type:'POST',
		   url:'/admin/ajax/delete_challenge_milestone',
		   data: {
			"_token": "{{ csrf_token() }}",
			"row_id": id
			},
		   success:function(data) {
			  $(el).parent().parent().parent().find('tr').hide();				  
		   }
		});
	}

	function edit_me(id, milestone_name, milestone_type, start_date, end_date, specific_date, milestone_distance, specific_date_checkbox, milestone_pic, el){
		$('#modal-edit-milestone').addClass('show');
		$('#modal-edit-milestone').css('padding-right', '15px');
		$('#modal-edit-milestone').css('display', 'block');
		$('#modal-xl').addClass('hide');
		$('input[name=milestone_id]').val(id);
		$('input[name=milestone_name]').val(milestone_name);
		$('input[name=milestone_distance]').val(milestone_distance);
		$('input[name=start_date]').val(start_date);
		$('input[name=end_date]').val(end_date);
		$('input[name=specific_date_checkbox]').val(specific_date_checkbox);
		$('input[name=specific_date]').val(specific_date);
		$('select[name=milestone_type] option[value='+milestone_type+']').attr('selected','selected');
		$('.showMSPic').html('<img src="'+milestone_pic+'" width="50" height="50">');
		if(milestone_type == 'distance_milestone'){
			if(specific_date_checkbox && specific_date){
				$('.distance_milestone_edit').css('display', '');
			  $('.monthly_edit').css('display', 'none');
			}
			
		}else if(milestone_type == 'monthly_milestone'){
      $('.monthly_edit').css('display', '');
			$('.distance_milestone_edit').css('display', 'none');
		}
		

		$('#modal-xl').removeClass('show');
	}
	
	$(function () {

		$(document).on('click', '.close-Mile-Model', function(){
        $('#modal-edit-milestone').addClass('hide');
        $('#modal-edit-milestone').removeClass('show');
				$('#modal-edit-milestone').css('padding-right', '');
				$('#modal-edit-milestone').css('display', '');
		});

		$(document).on('click', '[data-toggle="lightbox"]', function(event) {
		  event.preventDefault();
		  $(this).ekkoLightbox({
			alwaysShowClose: true,
			onContentLoaded: function(){
				$('button.dlt').click(function(){
					var img_id	=	$(this).attr('id');
					$.ajax({
					   type:'POST',
					   url:'/admin/ajax/delete_salon_image',
					   data: {
						"_token": "{{ csrf_token() }}",
						"img_id": img_id
						},
					   success:function(data) {
						  location.reload();			  
					   }
					});
				})
			}
		  });
		});
	});
	
	
	
	
	  </script>