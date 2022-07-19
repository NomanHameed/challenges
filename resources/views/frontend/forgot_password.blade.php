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
         <h5 class="modal-title">Forgot Password</h5>
         <h6>
            You will receive a Password reset link<br> on your entered registered email ID.
         </h6>
        </div>
       </div>
       <div class="modal-body">
         @if($errors->any())
           
            <p><strong>Error!</strong> {{$errors->first()}} </p>
             
         @endif
         <form action="{{ route('admin.resetMail') }}" method="post" class="login-form" id="resetmail-form">
            {{ csrf_field() }}
            <div class="login-wrp">
              
                <div class="form-group">
                  <label for="">Email ID</label>
                  
                     <input class="form-control" type="email" name="email"/>
                     
                 
                </div>

                

              
                <button type="submit" class="login-btn btn-dflt green-btn">Send</button>
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

   const togglePassword2 = document.querySelector('#toggle-password');
   const password2 = document.querySelector('#password');
 
     togglePassword2.addEventListener('click', function (e) {
       // toggle the type attribute
       const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
       password2.setAttribute('type', type);
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