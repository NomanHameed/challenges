@extends('layouts.dashboardMain')

@section('style')

<style type="text/css">
   /* Container needed to position the button. Adjust the width as needed */


/* Make the image responsive */
.imageContainer img {
  width: 100%;
  height: auto;
}

/* Style the button and place it in the middle of the container/image */
.imageContainer .edit-btn {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(101%, -89%);
  -ms-transform: translate(101%, -89%);
  
  color: #83a846;
  font-size: 16px;
  padding: 0;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

/*.imageContainer .edit-btn:hover {
  background-color: black;
  color: white;
}*/
</style>

@endsection

@section('content')

    <div class="page-body-wrapper">
      @include('frontend.menu.sidebar')
      <div class="page-body">
         <div class="p-0-30">
            @if(session('message'))
              
                  <p><strong>Success!</strong> {{session('message')}} </p>
                        
            @endif

            @if($errors->any())
              
                  <p><strong>Error!</strong> {{$errors->first()}} </p>
                
            @endif

            @if(!$timezone)
               <p><strong>Error!</strong> Please set your timezone. </p>
            @endif
            
            <div class="dash-inne-main-top">
               <h3 class="site-heading">
                  Profile
               </h3>
            </div>
            <div class="dash-profile-wrp">
               <div class="dash-profile-main-container">
                  <div class="profile-mainbox-top">
                     <div class="media">
                       <div>
                        <form id="uploadProfileFormSubmit" action="{{route('frontend.uploadProfileFormSubmit', $user['0']->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                        <figure class="user-profile-image imageContainer">
                           <?php
                           $user1 = json_encode($user);
                           $user1 = json_decode($user1, true);
                           $profile_pic = asset('assets/images/dash-user.png'); 
                           $pic = @$user1['0']['profile_pic'];
                           if($pic){
                               $profile_pic = asset($pic);
                           }
                           ?>
                           <input type="file" name="profile_pic" id="imgupload" style="display:none"/> 
                           <img  src="<?php echo $profile_pic; ?>" alt="">
                           <div class="edit-btn" id="OpenImgUpload"><span class="small material-icons">edit</span></div>
                        </figure>
                        </form>
                       </div>
                        <div class="media-body">
                           <h6>{{$user['0']->name}}</h6>
                           <a href="mailto:{{$user['0']->email}}">{{$user['0']->email}}</a>
                        </div>
                     </div>
                  </div>
                  <div class="profile-form-inner">
                   
   
                     <form id="" action="{{route('frontend.profileUpdate', $user['0']->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                     {{ csrf_field() }}
                     {{ method_field('put') }}
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>First Name</label>
                                 <input type="text" class="form-control" name="first_name" value="{{$user['0']->first_name}}">
                                 
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Last Name *</label>
                                 <input type="text" class="form-control" name="last_name" value="{{$user['0']->last_name}}">
                              </div>
                           </div>
   
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Phone Number</label>
                                 <input type="text" class="form-control" name="mobile_number" value="{{$user['0']->mobile_number}}">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                              <label>Gender:</label>
                                 <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="male" <?= $gender == 'male' ? 'checked=""' : ''; ?>>
                                    <label class="form-check-label">Male</label>
                                 </div>
                                 <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="female" <?= $gender == 'female' ? 'checked=""' : ''; ?>>
                                    <label class="form-check-label">Female</label>
                                 </div>
                                 <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="others" <?= $gender == 'others' ? 'checked=""' : ''; ?>>
                                    <label class="form-check-label">No Preference</label>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label>Address</label>
                                 
                                 <input type="text" class="form-control" name="address" value="{{$addressVal}}">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Country</label>
                                 
                                 <div class="custom-slect-bx">
                                    <select name="country" class="form-control">
                                       <option value="">Select Country</option>
                                       <option value="usa" <?= $country == 'usa' ? 'selected=""' : ''; ?>>United States</option>   
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>State</label>
                                <div class="custom-slect-bx">
                                   <select name="state" id="states" class="form-control">
                                       <option value="">Select State</option>
                                       <?php
                                         foreach($stateList as $key => $val){
                                       ?>
                                          <option value="{{$val['id']}}" <?= $val['id'] == $state ? 'selected=selected' : ''; ?>>{{$val['state_name']}}</option>
                                       <?php
                                         }
                                      ?>
                                   </select>
                                </div>
                              </div>
                             </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>City</label>
                                 <div class="custom-slect-bx">
                                    <select name="city" id="cities" class="form-control">
                                        <option value="">Select City</option>
                                        <?php
                                         foreach($citieList as $key => $val){
                                       ?>
                                          <option value="{{$val['city']}}" <?= $val['city'] == $city ? 'selected=selected' : ''; ?>>{{$val['city']}}</option>
                                       <?php
                                         }
                                      ?>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Zip Code</label>
                                 <input type="text" class="form-control" name="zip_code" value="{{$zip_code}}">
                              </div>
                           </div>
   
                        </div>
                      
   
   
   
                        <div class="row">
                           <div class="col-lg-6 col-md-12">
                              <div class="form-group">
                                 <label>Date of birth</label>

                              
                                 <input type="text" name="dob" class="form-control datetimepicker-input datepickeruser" data-target="#dob" value="{{$dob}}">
                                 
                              </div>
                           </div>
                           <div class="col-lg-6 col-md-12">
                              <div class="form-group">
                                 <label>Timezone</label>
                                 <select name="timezone" id="timezone" class="form-control">
                                    <option value="">Select Timezone</option>
                                    <?php
                                         foreach($timezoneList as $key => $val){
                                       ?>
                                          <option value="{{$val['zone_name']}}" <?= $val['zone_name'] == $timezone ? 'selected=selected' : ''; ?>>{{$val['zone_name']}}</option>
                                       <?php
                                         }
                                      ?>
                                 </select>
                              </div>
                           </div>
                        </div>                           
                        
                        <div class="row">
                           <div class="col-sm-12">
                              <div class="save-profile-changes mt-3">
                                 <button class="theme-btn green-btn"> Save </button>
                              </div>
                           </div>
                        </div>
                     </form>
   
   
   
                    
                  </div>
               </div>
               <div class="custom-panel email-address-panel">
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <h4>My Email Address</h4>
                        <p></p>
                     </div>
                     <div class="panel-body">
                        <div class="media">
                           <div class="mail-icon-custm">
                              <span class="material-icons-outlined">
                                 email
                                 </span>
                           </div>
                           <div class="media-body">
                             <a href="mailto:{{$user['0']->email}}">{{$user['0']->email}}</a>
                             <div class="time-mail-bx">
                             	<?php
                                     
									  $datetime1 = strtotime($user['0']->created_at);
                             $created_at = \Carbon\Carbon::parse($user['0']->created_at, 'UTC')->setTimezone($timezoneEmail)->format('m/d/Y');
									  $datetime2 = strtotime($now);
									 
									  // Calculates the difference between DateTime objects
									  $interval = abs($datetime2 - $datetime1);
									 
									 $years = floor( $interval / (365*60*60*24));

									 $months = floor(($interval - $years * 365*60*60*24)
                                 / (30*60*60*24));
									  		
                             	?>
                                <!-- <span> {{ !$years ? $months. ' month ago' : $years. ' year ago'}}</span> -->
                                <span>{{$created_at}}</span>
                                <span class="primary-email-bx">Primary email address</span>
                             </div>
                           </div>
                         </div>
                     </div>
                   </div>
               </div>


               <div class="custom-panel newpass-panel">
                  <div class="panel panel-default">
                     <div class="panel-heading">
                        <h4>Password</h4>
                        <p>Set a strong password to prevent unauthorized access to your account.</p>
                     </div>
                     <div class="panel-body">
                        <form id="challengeAddForm" action="{{route('frontend.changePassword', $user['0']->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        {{ csrf_field() }}
                        {{ method_field('put') }}
                           <div class="change-pass-new-pass">
                              <div class="row">
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label for="password">Old Password</label>
                                       <div class="pass-wrp">
                                          <input class="password form-control" required="" name="old_password" id="old_password" type="password" placeholder="" value="<?php echo Request::old('old_password') ? Request::old('old_password') : ''; ?>">
                                          <input class="password form-control" required="" name="email" type="hidden" value="{{$user['0']->email}}">
                                          <div class="hide-show">
                                             <span class="far fa-eye" id="old-toggle"></span>
                                           </div>
                                       </div>
                                     </div>
                                 </div>
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label for="password">New Password</label>
                                       <div class="pass-wrp">
                                          <input class="password form-control" required="" name="password" id="password" type="password" placeholder="" value="<?php echo Request::old('password_confirmation') ? Request::old('password_confirmation') : ''; ?>">
                                          <div class="hide-show">
                                             <span class="far fa-eye" id="toggle-password"></span>
                                           </div>
                                       </div>
                                     </div>
                                 </div>
   
                                 <div class="col-sm-12">
                                    <div class="form-group">
                                       <label for="password">Confirm New Password</label>
                                       <div class="pass-wrp">
                                          <input class="password form-control" required="" name="password_confirmation" id="password_confirmation" type="password" placeholder="" value="<?php echo Request::old('password_confirmation') ? Request::old('password_confirmation') : ''; ?>">
                                          <div class="hide-show">
                                             <span class="far fa-eye" id="toggle-password_confirmation"></span>
                                           </div>
                                       </div>
                                     </div>
                                 </div>
                                 <div class="save-new-pass-bx">
                                    <div class="col-sm-12">
                                       <div class="save-profile-changes mt-3">
                                          <button class="theme-btn green-btn">  Save </button>                                       
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                   </div>
               </div>


            </div>

         </div>
      </div>
   </div>

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>

<script>

   $(function() {   

      $('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });

         $('#imgupload').change(function(){ 
               
       let reader = new FileReader();
    
       reader.onload = (e) => { 
         

         if(e.target.result){ 
            $("#uploadProfileFormSubmit").submit();
         }
    
         $('#preview-image-before-upload').attr('src', e.target.result); 
       }
    
       var img = reader.readAsDataURL(this.files[0]); 
       
      
      });
           // Bootstrap DateTimePicker v4
           /*$('#dob').datetimepicker({
                 format: 'DD/MM/YYYY'
           });*/
        });   

      const togglePassword1 = document.querySelector('#old-toggle');
      const password1 = document.querySelector('#old_password');
    
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


      $('#countries').on('change', function() {
      var country =  $(this).val(); 
      if(country=='usa'){
         var call_url   =  '/admin/ajax/get_us_state_profile';
      }else{
         $("#states").html('');
         $("#cities").html('');
      }
        $.ajax({
               type:'POST',
               url:call_url,
               data: {
               "_token": "{{ csrf_token() }}",
               "country": country
               },
               success:function(data) {
                 $("#states").html(data.msg);              
               }
            });
   });
   
   
   $('#states').on('change', function() {
      var state   =  $(this).val();
      var country =  $('#countries').val();              
      //if(country=='usa'){
         var call_url   =  '/admin/ajax/get_us_cities_profile';
      /*}else{
         $("#cities").html('');
         $("#states").html('');
      }*/
                  
      $.ajax({
            type:'POST',
            url:call_url,
            data: {
            "_token": "{{ csrf_token() }}",
            "state": state
            },
            success:function(data) { 
               $("#cities").html('');
              $("#cities").html(data.msg); 
            }
      });
   });

   /*$(function() {
      $('.input-group-text').click(function(){
         $('#date_of_birth1').datetimepicker({
            format: 'MM/DD/YYYY'
         });
      });
   });*/
  </script>
@endsection