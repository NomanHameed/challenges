@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
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
              <form action="{{route('admin.users.index')}}" method="post" style="margin-bottom: 15px;">
                {{ csrf_field() }}
                  {{ method_field('get') }}
                <div class="row">
                  <div class="col-sm-7"></div>
                  <div class="col-sm-4">
                    
                      <input type="text" class="form-control" id="Search" name="Search" value="<?= @$Search; ?>" placeholder="Search">
                      
                    
                  </div>
                  <div class="col-sm-1" style="text-align:center;">
                    <button type="submit" class="btn btn-info">Search</button>
                  </div>
                </div>
                
              </form>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>User ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Gender</th>
                  <th>Date Of Joining</th>
				          <th>Account Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				
				@foreach($users as $user)
					<tr>
					  <td>{{ @$user['id'] }}</td>
					  <td>{{ @$user['name'] }}</td>
            <td>{{ @$user['email'] }}</td>
            <td>{{ @$user['gender'] }}</td>
            <td>{{ $user['created_at'] }}</td>
					  <td>@if (@$user['status']=='1') Active @else Inactive @endif</td>
					  <td>
						<a href="{{ route('admin.users.edit',$user['id']) }}"><button type="button" class="btn btn-info">Edit</button></a>
						
					  </td>
					</tr>
				@endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>User ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Gender</th>
                  <th>Date Of Joining</th>
				          <th>Account Status</th>
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