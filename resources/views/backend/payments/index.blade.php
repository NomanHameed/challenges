@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Payments</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Payments</li>
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
                  <th>Payment ID</th>
                  <th>Challenge</th>
                  <th>User</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Stripe ID</th>
                </tr>
                </thead>
                <tbody>
				
				@foreach($payments as $payment)
        <?php if($payment->payment_status==1){$status='Paid';}else{$status='Failed';} ?>
					<tr>
					  <td>{{@$payment->id}}</td>
					  <td>{{@$payment->challenge_name}}</td>
            <td>{{@$payment->user_name}}</td>
            <td>{{@$payment->created_at}}</td>
            <td>{{@$status}}</td>
            <td>{{@$payment->stripe_charge_id}}</td>
					  
					</tr>
				@endforeach
                </tbody>
                <tfoot>
                <tr>
                <th>Payment ID</th>
                  <th>Challenge</th>
                  <th>User</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Stripe ID</th>
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