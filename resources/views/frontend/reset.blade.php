@extends('layouts.login')

@section('content')
			<p class="login-box-msg">Password Reset Form</p>
			@if($errors->any())
				@foreach ($errors->all() as $error)
						 <span class="invalid-feedback" role="alert" style="display:block; margin-bottom:3px;">
							<strong>{{ $error }}</strong>
						</span>
				@endforeach
			@endif
				
				<span class="invalid-feedback" role="alert" style="display:block; margin-bottom:3px;">
					<strong>{{ session('message') }}</strong>
				</span>
				<span class="valid-feedback" role="alert" style="display:block; margin-bottom:3px;">
					<strong>{{ session('success') }}</strong>
				</span>
				<span class="invalid-feedback" role="alert" style="display:block; margin-bottom:3px;">
						<strong>{{ @$message}}</strong>
					</span>

			
			
			
			<!-- Horizontal Form -->
				
              <!-- /.card-header -->
              <!-- form start -->
              <form  method="post" action="{{ route('stylistreset') }}">
			   <input type="hidden" name="_token" value="{{csrf_token()}}">
			   <input type="hidden" name="token" value="<?php echo @$token; ?>">

				<div class="input-group mb-3">
					<input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ @$email}} {{ session('email') }}{{ old('email') }}">
					<div class="input-group-append">
						<div class="input-group-text">
						  <span class="fas fa-envelope"></span>
						</div>
					  </div>
				</div>
				
				<div class="input-group mb-3">
					<input type="password" name="password" class="form-control" id="password" placeholder="Password">
					<div class="input-group-append">
						<div class="input-group-text">
						  <span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
				
				<div class="input-group mb-3">
					<input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password">
					<div class="input-group-append">
						<div class="input-group-text">
						  <span class="fas fa-lock"></span>
						</div>
					</div>
				</div>
                <!-- /.card-body -->
				<div class="row">
					<div class="col-8"> </div>
					<div class="col-4">
					  <input type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">
					</div>
				</div>
				
                <!-- /.card-footer -->
              </form>
            
            <!-- /.card -->
			
			
			@endsection
		