@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Badges</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Badges</li>
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
                  <th>Badge ID</th>
                  <th>Badge Name</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				
				@foreach($badges as $badge)
					<tr>
					  <td>{{ $badge->id }}</td>
					  <td>{{ $badge->badge_name }}</td>
					  <td style="display:flex !important;"> 
						<a href="{{ route('admin.badges.edit',$badge->id) }}" style="margin-right: 6px;"><button type="button" class="btn btn-info">Edit</button></a>
						
						<form id="badgeDelete" action="{{route('admin.badges.destroy',$badge->id)}}" method="POST" enctype="multipart/form-data">
							@method('DELETE')
							<input type="hidden" name="_token" value="{{csrf_token()}}">
              <button type="button" onclick="$(this).parent().find('form').submit();" class="btn btn-danger">Delete</button>
						</form>
					  </td>
					</tr>
				@endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>Badge ID</th>
                  <th>Badge Name</th>
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