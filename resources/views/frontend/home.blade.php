@extends('layouts.frontend')

@section('style')

<style type="text/css">
html {
    background: url('assets/images/Virtual_Background.jpg') no-repeat center center fixed;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
.modal {
    
    top: 20%;
    left: 34%;
    width: 37%;
}

@media (min-width: 992px)
.modal-lg, .modal-xl {
    max-width: 450px;
}

.modal-header.d-block {
    padding-bottom: 15px;
}

.modal-content {
    padding: 15px;
}

.form-control {
    height: 40px !important;
    }
    .custom-modal .form-group {
       margin-bottom: 0.8rem;
   }
   .form-check.btm-psd {
       
       margin-bottom: 15px;
   }
   .btn-dflt {
       height: 40px;
    }


</style>

@endsection

@section('content')
<div class="banner inner-page-banner ch-details-banner">
   
   <div class="modal custom-modal" style="display: block;">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header d-block">
        <div class="modal-top text-center">
         <h5 class="modal-title">Log in to your account</h5>
         <h6>Don’t have an account? <a class="signup-btn" href="{{ route('frontend.userRegister') }}">Sign up</a></h6>
        </div>
       </div>
       <div class="modal-body">
         @if($errors->any())
           
            <p><strong>Error!</strong> {{$errors->first()}} </p>
             
         @endif

         @if(session('message'))
            <p><strong>Success!</strong> {{session('message')}} </p>
                   
         @endif
         <form action="{{ route('frontend.login') }}" method="post" class="login-form" id="login-form">
            {{ csrf_field() }}
            <input type="hidden" name="login" value="login">
            <div class="login-wrp">
               
               <div class="form-group">
                  <label for="exampleInputEmail1">Email ID</label>
                  <input type="email" name="email" id="exampleInputEmail1"  class="form-control" aria-describedby="emailHelp" value="<?php echo Request::old('email'); ?>">
                  <?php if(Request::old('login') == 'login'){ if ($errors->has("email")){  ?>
                     <small class="errorTxt1"> <?php echo $errors->first("email"); ?></small>
                  <?php } } ?>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <div class="pass-wrp">
                     <input class="password form-control" id="password" required name="password" type="password" value="<?php echo Request::old('password'); ?>" />
                     <?php if(Request::old('login') == 'login'){ if ($errors->has("password")){  ?>
                        <small class="errorTxt1"> <?php echo $errors->first("password"); ?></small>
                     <?php }} ?>
                     <div class="hide-show">
                        <span class="far fa-eye" id="toggle-password"></span>
                      </div>
                  </div>
                </div>
                <div class="form-check btm-psd">
                     <label class="checkbx-custom">
                         Remember Me
                         <input type="checkbox" id="remember" name="remember" value="on">
                         <span class="checkmark"></span>
                      </label>
                      <div class="forgot-pad">
                         <a href="{{ route('frontend.forgot_password') }}">Forgot Password?</a>
                      </div>
                 
                </div>
                <button type="submit" class="login-btn btn-dflt green-btn">Login</button>
            </div>
            <!-- <div class="login-options-wrp">
               <div class="or-wrp">
                  <span class="or">or</span>
               </div>
               <a href="#" class="fb-login btn-dflt"> <i class="fa fa-facebook"></i> Login with Facebook</a>
               <a href="#" class="google-login btn-dflt"> <i class="fa fa-google"></i> Login with Google</a>
               
            </div> -->
          </form>
          
       </div>
       
     </div>
   </div>
 </div>
</div>
      @endsection

@section('script')
<script type="text/javascript">
   

   const togglePassword2 = document.querySelector('#toggle-password');
   const password2 = document.querySelector('#password');
 
     togglePassword2.addEventListener('click', function (e) {
       // toggle the type attribute
       const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
       password2.setAttribute('type', type);
       // toggle the eye slash icon
       this.classList.toggle('fa-eye-slash');
   });

</script>

@endsection