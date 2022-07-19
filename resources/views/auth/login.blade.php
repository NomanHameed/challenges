@extends('layouts.login')

@section('content')
<p class="login-box-msg">Sign in to start your session</p>

      <form method="POST" action="{{ route('login') }}">
	   @csrf
        <div class="input-group mb-3">
          <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
          
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
		  @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
          @enderror
        </div>
        <div class="input-group mb-3">
          <input id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
			
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
		  @error('password')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                <!--<a href="{{ url('auth/google') }}" style="margin-top: 20px;" class="btn btn-lg btn-success btn-block">
                                      <strong>Login With Google</strong>
                                </a> -->
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
		  <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Sign In') }}
                                </button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1">
        
      </p>
    </div>
@endsection
