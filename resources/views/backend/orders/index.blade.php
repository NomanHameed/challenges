@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Orders</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Orders</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">

          <div class="card">
            <div class="card-header">
            </div>
			@if(Session::has('message'))
			<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Alert!</h4>
                {{ Session::get('message')}}
              </div>
			 @endif 
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Product Name</th>
                  <th>Order Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				
				@foreach($orders as $order)
          <?php
              switch($order->order_status){
                case 1:
                  $status_name= "New Order";
                  break;
                case 2:
                  $status_name = "Shipped";
                  break;
                case 3:
                  $status_name = "Delivered";
                  break;
                case 4:
                  $status_name = "Cancelled";
                  break;
              }
          ?>
					<tr>
					  <td>{{ $order->id }}</td>
					  <td>{{ $order->product_name }}</td>
            <td>{{ $status_name }}</td>
					  <td>
						<a href="{{ route('admin.orders.edit',$order->id) }}"><button type="button" class="btn btn-info">Edit/View</button></a>
						
					  </td>
					</tr>
				@endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>Order ID</th>
                  <th>Product Name</th>
                  <th>Order Status</th>
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
    <!-- /.content -->
@endsection