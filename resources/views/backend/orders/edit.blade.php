@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Order</li>
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
               Order ID: {{$order_id}}
              </h3>
              <div style="float:right;">
                <button type="button" class="btn btn-info" id="view_log" data-toggle="modal" data-target="#modal-xl" >
                  <i class="fa fa-history"></i>&nbsp View Log
                </button>
                <!--
                <button type="button" class="btn btn-info" id="send_invoice" >
                  <i class="fas fa-envelope-square"></i>&nbsp Send Invoice
                </button>-->
              </div>
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
			  
				<form action="{{route('admin.orders.update',$order_id)}}" method="POST">
				@method('PUT')
				<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label for="exampleInputEmail1">Product Name</label>
						<input type="text" name="name" disabled value="{{$product_name}}" class="form-control" id="exampleInputEmail1" placeholder="Page Title">
					</div>
					<!--
					<div class="form-group">
						<label for="exampleInputEmail1">Product Price ($)</label>
						<input type="number" value="{{@$product->price}}" name="price" class="form-control"  placeholder="Price in dollar">
					</div>-->
					
          <div class="form-group">
					  <label for="exampleInputFile">Product Image</label>			
						@if ($product_image)
							<img src="{{ asset(@$product_image) }}" width="200px" height="200px">
						@endif
					</div>

          <div class="form-group">
						<label for="exampleInputEmail1">Associated Challenge</label>
						<input type="text" name="challenge_name" disabled value="{{$associated_challenge}}" class="form-control" id="exampleInputEmail1" placeholder="Page Title">
					</div>

          <div class="form-group">
						<label for="exampleInputEmail1">User Name</label>
						<input type="text" name="name" disabled value="{{$user_name}}" class="form-control" id="exampleInputEmail1" placeholder="Page Title">
					</div>

          <div class="form-group">
						<label for="exampleInputEmail1">Order Date</label>
						<input type="text" name="name" disabled value="{{$order_date}}" class="form-control" id="exampleInputEmail1" placeholder="Page Title">
					</div>

          <div class="form-group">
						<label for="exampleInputEmail1">Tracking Info </label>
						<textarea class="form-control"  name="tracking_info">{{@$tracking_info}}</textarea>
					</div>

          <div class="form-group">
						<label for="exampleInputEmail1">Order Info </label>
						<textarea class="form-control"  name="order_info">{{@$order_info}}</textarea>
					</div>

          <div class="form-group">
						 <div class="form-group">
							<label>Order Status</label>
							<select name="status" class="form-control select2" style="width: 100%;">
								<option value="1" <?php if($status=='1') echo 'selected' ?> >New Order</option>
								<option value="2" <?php if($status=='2') echo 'selected' ?>>Shipped</option>
                <option value="3" <?php if($status=='3') echo 'selected' ?>>Delivered</option>
                <option value="4" <?php if($status=='4') echo 'selected' ?>>Cancelled</option>
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
    </section>



    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Order Log</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <section class="content">
				  <div class="row">
					<div class="col-12">

					  <div class="card">
            <!--<div class="card-header"></div>
						 /.card-header -->
						<div class="card-body">
						  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Status</th>
                  <th>Date</th>
                </tr>
                </thead>

                <tbody id="log_content">
                  <tr >
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>

                <tfoot>
                <tr>
                <th>Status</th>
                  <th>Date</th>
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

	<script>
  $('#view_log').on('click', function() {
		  $.ajax({
				   type:'POST',
				   url:'/admin/ajax/view_order_log',
				   data: {
					"_token": "{{ csrf_token() }}",
					"id": "{{$order_id}}"
					},
				   success:function(data) {
					  $("#log_content").html(data.msg);				  
				   }
				});
	});
	</script>
    <!-- /.content -->
@endsection