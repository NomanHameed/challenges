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
    
    top: 2.5%;
    left: 31%;
    width: 40%;
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

.form-group label{
    padding-left: 0px;
    margin-bottom: 0;
}

.form-check.btm-psd {
    
    margin-bottom: 15px;
}

.custom-modal .form-group {
    margin-bottom: 0.8rem;
}

.form-control {
    height: 40px !important;
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
         <h5 class="modal-title">Sign Up to your account</h5>
         <h6>Already have an account?  <a class="signup-btn" href="{{ route('frontend.home') }}">Login</a></h6>
        </div>
       </div>
       <div class="modal-body">
         @if($errors->any())
           
            <p><strong>Error!</strong> {{$errors->first()}} </p>
             
         @endif
         <form action="{{ route('frontend.register') }}" method="post" class="login-form" id="signup-form">
            {{ csrf_field() }}
            <input type="hidden" name="register" value="register">
            <div class="login-wrp">
               <!-- <div class="login-options-wrp">
                
                 <div class="login-opt-top d-flex">
                  <a href="#" class="google-login btn-dflt"> <span class="icon"><img src="{{ asset('assets/images/google.png') }}" alt=""></span> Login with Google</a>
                  <a href="#" class="fb-login btn-dflt"> <i class="fa fa-facebook"></i> Login with Facebook</a>
                  
                 </div>
                  <div class="or-wrp">
                     <span class="or">or</span>
                  </div>
               </div> -->

               <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                          <label for="">First Name*</label>
                          <input type="text" name="first_name" class="form-control" value="<?php echo Request::old('first_name'); ?>">
                          <?php if(Request::old('register') == 'register'){ if ($errors->has("first_name")){  ?>
                             <small class="errorTxt1"> <?php echo $errors->first("first_name"); ?></small>
                          <?php } } ?>
                        </div>
                    </div>
                  <div class="col-sm-6">
                      <div class="form-group">
                          <label for="">Last Name*</label>
                          <input type="text" name="last_name" class="form-control" value="<?php echo Request::old('last_name'); ?>">
                          <?php if(Request::old('register') == 'register'){ if ($errors->has("last_name")){  ?>
                             <small class="errorTxt1"> <?php echo $errors->first("last_name"); ?></small>
                          <?php } } ?>
                        </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-sm-6">
                        <div class="form-group">
                      <label for="exampleInputEmail1">Email Address*</label>
                      <input type="email" name="email" class="form-control" aria-describedby="emailHelp" value="<?php echo Request::old('email'); ?>">
                      <?php if(Request::old('register') == 'register'){ if ($errors->has("email")){  ?>
                         <small class="errorTxt1"> <?php echo $errors->first("email"); ?></small>
                      <?php } } ?>
                    </div>
                  </div>
                  <div class="col-sm-6">
                        <div class="form-group">
                      <label for="exampleInputEmail1">Timezone*</label>
                      <select name="timezone" id="timezone" class="form-control">
                        <option value="">Select Timezone</option>
                            <?php
                            foreach($timezoneList as $key => $val){
                            ?>
                                <option value="{{$val['zone_name']}}">{{$val['zone_name']}}</option>
                            <?php
                            }
                          ?>
                        </select>
                      <?php if(Request::old('register') == 'register'){ if ($errors->has("email")){  ?>
                         <small class="errorTxt1"> <?php echo $errors->first("email"); ?></small>
                      <?php } } ?>
                    </div>
                  </div>
                  
                </div>

               <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                          <label for="password">Password</label>
                          <div class="pass-wrp">
                             <input class="password form-control" name="password" id="reg-password" required type="password" value="<?php echo Request::old('password'); ?>" />
                             <?php if(Request::old('register') == 'register'){ if ($errors->has("password")){  ?>
                                <small class="errorTxt1"> <?php echo $errors->first("password"); ?></small>
                             <?php } } ?>
                             <div class="hide-show">
                                <span class="far fa-eye" id="reg-toggle-password"></span>
                              </div>
                          </div>
                        </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                          <label for="password">Confirm Password*</label>
                          <div class="pass-wrp">
                             <input class="password form-control" name="password_confirmation" id="password_confirmation" required type="password" placeholder="" value="<?php echo Request::old('password_confirmation'); ?>" />
                             <?php if(Request::old('register') == 'register'){ if ($errors->has("password_confirmation")){  ?>
                                <small class="errorTxt1"> <?php echo $errors->first("password_confirmation"); ?></small>
                             <?php } } ?>
                             <div class="hide-show">
                                <span class="far fa-eye" id="toggle-password_confirmation"></span>
                              </div>
                          </div>
                        </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12">
                        <div class="form-check btm-psd">
                         <label class="checkbx-custom">
                            I accept <a href="#">Privacy Policy</a> and <a href="#">Terms & Conditions</a>
                             <input type="checkbox" checked="" required="required">
                             <span class="checkmark"></span>
                          </label>
                        
                     
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-12">
                    <button type="submit"  class="login-btn btn-dflt green-btn">Sign Up</button>
                      </div>
                  </div>
                

                
                
            </div>
            
          </form>
          
       </div>
       
     </div>
   </div>
 </div>
</div>
      @endsection

@section('script')
<script type="text/javascript">
   const togglePassword1 = document.querySelector('#reg-toggle-password');
   const password1 = document.querySelector('#reg-password');
 
     togglePassword1.addEventListener('click', function (e) {
       // toggle the type attribute
       const type = password1.getAttribute('type') === 'password' ? 'text' : 'password';
       password1.setAttribute('type', type);
       // toggle the eye slash icon
       this.classList.toggle('fa-eye-slash');
   });

   const togglePassword = document.querySelector('#toggle-password_confirmation');
   const password = document.querySelector('#password_confirmation');
 
     togglePassword.addEventListener('click', function (e) {
       // toggle the type attribute
       const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
       password.setAttribute('type', type);
       // toggle the eye slash icon
       this.classList.toggle('fa-eye-slash');
   });
</script>

@endsection