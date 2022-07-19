@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Participations</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Participations</li>
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
                  <th>Participation ID</th>
                  <th>User Name</th>
                  <th>Challenge Name</th>
                  <th>Date Joined</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				
				@foreach($participations as $participation)
					<tr>
					  <td>{{ $participation->id }}</td>
					  <td>{{ $participation->user_name }}</td>
            <td>{{ $participation->challenge_name }}</td>
            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $participation->created_at, 'UTC')->setTimezone($timezone) }}</td>
					  <td>
						<a href="{{ route('admin.participation.challengesLog',['challenge_id' => $participation->challenge_id,  'user_id' => $participation->user_id] ) }}"><button type="button" class="btn btn-info">View Logs</button></a>
						
					  </td>
					</tr>
				@endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>Participation ID</th>
                  <th>User Name</th>
                  <th>Challenge Name</th>
                  <th>Date Joined</th>
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