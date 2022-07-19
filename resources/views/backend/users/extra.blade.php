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
                  <a class="nav-link active" id="vert-tabs-home-tab" data-toggle="pill" href="#vert-tabs-home" role="tab" aria-controls="vert-tabs-home" aria-selected="true">Participations</a>
                  <a class="nav-link" id="assign-challenges-to-user-tab" data-toggle="pill" href="#assign-challenges-to-user" role="tab" aria-controls="vert-tabs-home" aria-selected="false">Assign Challenges to User</a>
                  <!-- <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-service" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Badges Earned</a>
                  <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Product Purchased</a> -->
                 
                </div>
              </div>
              <div class="col-7 col-sm-9">
                <div class="tab-content" id="vert-tabs-tabContent">

                	<!--- Tab for adding Time Table------------------->
                <div class="tab-pane text-left fade  active show" id="vert-tabs-home" role="tabpanel" aria-labelledby="vert-tabs-home-tab">
								  <div class="card card-secondary">
										  <div class="card-header">
											<h3 class="card-title">Challenge List</h3>
											
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
															  <th>Action</th>
															</tr>
															</thead>
															<tbody>
															@foreach($participations as $participation)
																<tr>
																	<td>{{$participation['name']}}</td>
																	<td style="display: flex !important;">
																		<a href="{{ route('admin.participation.challengesLog',['challenge_id' => $participation['challenge_id'],  'user_id' => $users->id] ) }}" style="margin-right: 30px;"><button type="button" class="btn btn-info">View Log</button></a>

																		<form action="{{ route('admin.unassignChallengeToUser', $users->id) }}" method="post" class="login-form" id="unassignChallengeToUser-form" style="float: left; margin-right: 21px;">
														          {{ csrf_field() }}
														          <input type="hidden" name="challenge_id" value="{{$participation['challenge_id']}}">
															        <button type="submit" class="btn btn-info">Unassign Challenge</button>
															                      
														        </form>
																		
																	</td>
																</tr>
															@endforeach	
															</tbody>
															<tfoot>
															<tr>
															  <th>Name</th>
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
				
				<!--- Tab for adding Time Table------------------->
                <div class="tab-pane text-left fade show" id="assign-challenges-to-user" role="tabpanel" aria-labelledby="assign-challenges-to-user-tab">
								  <div class="card card-secondary">
										  <div class="card-header">
											<h3 class="card-title">Challenge List</h3>
											
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
														  <form action="{{ route('admin.assignChallengeToUser', $users->id) }}" method="post" class="login-form" id="login-form">
										            {{ csrf_field() }}
										            <div class="manual-add-tab">
										               <div class="activity-details-wrp">
										                  <div class="activity-head">
										                      <h3>Assign Challenges to user</h3>
										                      <!-- <span>Donec commodo posuere.</span> -->
										                  </div>
										                  <div class="acitivity-info-bx">
										                     <div class="form-group">
										                         <label for="">Challenges</label>
										                         <div class="custom-slect-bx">

										                           <select name="challenges[]" id="challenges" class="form-control" multiple>
										                              <?php
										                                 foreach($challenges as $key => $val){
										                              ?>
										                                    <option value="{{$val->id}}" >{{$val->name}}</option>
										                              <?php

										                                 }
										                              ?>
										                           </select>
										                        </div>
										                     </div>
										                     <div class="form-group">
											                     <div class="form-group">
											                        <div class="save-add-log1">
											                           <button class="theme-btn green-btn">Save</button>
											                        </div>
											                     </div>
										                    </div>
										                    
										                 </div>
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
									</div>
                </div>
				  <!--- Tab for adding services------------------->
                  <div class="tab-pane fade" id="vert-tabs-service" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                      <div class="card card-secondary">
						  <div class="card-header">
							<h3 class="card-title">Badges Earned</h3>
							<h3 class="card-title" style="float:right"><a href="">Add New</a></h3>
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
											  <th>Action</th>
											</tr>
											</thead>
											<tbody>
												
												<tr>
													<td>Badge 1</td>
													<td>
														<a href="{{ route('admin.badges.edit',1) }}"><button type="button" class="btn btn-info">View</button></a>
														
													</td>
												</tr>
												
											</tbody>
											<tfoot>
											<tr>
											  <th>Name</th>
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
                  <!---Tab for Profile Information---->
				  <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
						 <div class="card card-secondary">
						  <div class="card-header">
							<h3 class="card-title">Product Purchased</h3>
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
											  <th>Image</th>
											  <th>Action</th>
											</tr>
											</thead>
											<tbody>
											@foreach($products as $product)
												<tr>
													<td>{{$product['name']}}</td>
													<td>	<img src="{{ asset(@$product['image']) }}" width="40px" height="40px"></td>
													<td>
														<a href="{{ route('admin.orders.edit',$product['id']) }}"><button type="button" class="btn btn-info">View Order</button></a>
														
													</td>
												</tr>
											@endforeach	
											</tbody>
											<tfoot>
											<tr>
											  <th>Name</th>
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
							  <th>Day</th>
							  <th>Work Start</th>
							  <th>Work End</th>
							  <th>Break Start</th>
							  <th>Break End</th>
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
								</tr>
							</tbody>
							<tfoot>
							<tr>
							  <th>Day</th>
							  <th>Work Start</th>
							  <th>Work End</th>
							  <th>Break Start</th>
							  <th>Break End</th>
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
		var state	=	$(this).val();
		var country	=	$('#countries').val();					
		if(country=='usa'){
			var call_url	=	'/admin/ajax/get_us_cities';
		}else{
			$("#cities").html('');
			$("#states").html('');
		}
						
		$.ajax({
			   type:'POST',
			   url:call_url,
			   data: {
				"_token": "{{ csrf_token() }}",
				"state": state
				},
			   success:function(data) {
				  $("#cities").html(data.msg); 
			   }
		});
	});
	
	
	  $('#view_time_table').on('click', function() {
		  $.ajax({
				   type:'POST',
				   url:'/admin/ajax/get_provider_timetable',
				   data: {
					"_token": "{{ csrf_token() }}",
					"id": "{{@$provider->id}}"
					},
				   success:function(data) {
					  $("#time_table_content").html(data.msg);				  
				   }
				});
	});
	
	function delete_me(id, el){
		$.ajax({
		   type:'POST',
		   url:'/admin/ajax/delete_provider_timetable',
		   data: {
			"_token": "{{ csrf_token() }}",
			"row_id": id
			},
		   success:function(data) {
			  $(el).parent().parent().parent().find('tr').hide();				  
		   }
		});
	}
	
	$(function () {
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
	})
	
	
	
	
	  </script>