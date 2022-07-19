@extends('layouts.dashboardMain')

@section('style')

@endsection

@section('content')

<div class="page-body-wrapper">
    @include('frontend.menu.sidebar')
    <div class="page-body">
        <div class="p-0-30">

            <div class="dash-inne-main-top">
               <h3 class="site-heading">
                  Manage Device
               </h3>
               <p>Automatically send your activities to your challenge by connecting to one of the apps or devices listed.</p>

               @if(session('message'))
	              
	                  <p><strong>Success!</strong> {{session('message')}} </p>
	                        
	            @endif

	            @if($errors->any())
	              
	                  <p><strong>Error!</strong> {{$errors->first()}} </p>
	                
	            @endif
            </div>
            <div class="manage-device-wrp">
              
		        <?php
		          $clsFitbit = '';
		                  if($fitbit_user){
		                    $clsFitbit = 'connected';
		                  }
		        ?>
		        <div class="manage-device-box {{ $clsFitbit }}">
		            <div class="manage-device-main-box">
		                <figure class="manage-device-img">
		                    <img src="assets/images/Fitbit_logo.png" alt="">
		                </figure>
		            </div>
		            <div class="device-connect-btn">
		            @if($fitbit_user)
		                            <p class="text-center">Status: <b style="color: #bcd433;">Connected</b></p>
		            @endif
		            <a class="theme-btn" href="{{ route('admin.fitbitConnect') }}" style="background-color: #02b0b9 !important;"> Connect with FITBIT </a>
		                <!-- <button class="theme-btn"> Connect </button> -->
		            </div>
		        </div>
		        <?php
		          $clsGarmin = '';
                  if($fitbit_user){
                    $clsGarmin = 'connected';
                  }
		        ?>
		        <div class="manage-device-box {{ $clsGarmin }}">
		            <div class="manage-device-main-box">
		                <figure class="manage-device-img">
		                    <img src="assets/images/5a10a8d39642de34b6b65d0c.png" alt="">
		                </figure>
		            </div>
		            <div class="device-connect-btn">
		            	@if($garmin_user)
                            <p class="text-center">Status: <b style="color: #bcd433;">Connected</b></p>
			            @endif
		                <a class="theme-btn" href="{{ route('admin.garminConnect') }}" style="background-color: #027cc2 !important;"> Connect with GARMIN </a>
		            </div>
		        </div>
		        <?php
		        $clsStrava = '';
                  if($Strava_user){
                      $clsStrava = 'connected';
                  }
		        ?>
		        <div class="manage-device-box {{$clsStrava}}">
		            <div class="manage-device-main-box">
		                <figure class="manage-device-img">
		                    <img src="assets/images/strava-logo.png" alt="">
		                </figure>
		            </div>
		            <div class="device-connect-btn" style="text-align: center;">
		            	
		            	
                        @if($Strava_user)
                            <p>Status: <b style="color: #bcd433;">Connected</b></p>
		            	
			            	<!-- <form action="{{route('frontend.Disconnect')}}" id="disconnect-strava">
				               <button class="theme-btn"> Disconnect </button>
				            </form> -->

			            @else
	                        <!-- <form action="{{route('frontend.stravaAuth')}}">
				               <button class="theme-btn"> <img src="assets/images/btn_strava_connectwith_orange@2x.png" alt=""> </button>
				            </form> -->
			            @endif
			            <a class="theme-btn-custom" href="{{route('admin.stravaConnect')}}"> <img src="assets/images/btn_strava_connectwith_orange@2x.png" alt=""> </a>
		            </div>
		        </div>
		        <!-- <div class="manage-device-box">
		            <div class="manage-device-main-box">
		                <figure class="manage-device-img">
		                    <img src="assets/images/Samsung_logo.png" alt="">
		                </figure>
		            </div>
		            <div class="device-connect-btn">
		                <button class="theme-btn"> Connect </button>
		            </div>
		        </div> -->

                <?php
		        $clsMapMyRun = '';
                  if($MapMyRun_user){
                      $clsMapMyRun = 'connected';
                  }
		        ?>
		        <div class="manage-device-box">
		            <div class="manage-device-main-box">
		                <figure class="manage-device-img">
		                    <img src="assets/images/MapMyRun.png" alt="" style="width: 40%;">
		                </figure>
		            </div>
		            <div class="device-connect-btn" style="text-align: center;">
		            	@if($MapMyRun_user)
                            <p>Status: <b style="color: #bcd433;">Connected</b></p>			            
			            @endif
		                <a class="theme-btn-custom" href="{{route('admin.MapMyRunConnect')}}"> <img src="assets/images/UA-login_btn-medium.png" alt=""> </a>
		            </div>
		        </div>

		        <!-- <div class="manage-device-box">
		            <div class="manage-device-main-box">
		                <figure class="manage-device-img">
		                    <img src="assets/images/MapMyRun.png" alt="">
		                </figure>
		            </div>
		            <div class="device-connect-btn">
		            	<form action="{{route('admin.MapMyRunConnect')}}">
		                    <button class="theme-btn"> Connect </button>
		                </form>
		            </div>
		        </div> -->
             
            </div>

        </div>
    </div>
</div>

@endsection

@section('script')

<script type="text/javascript">
   $(document).ready(function(){   

      $(document).on("click",".challengeView",function(e) {  
         e.preventDefault();
            $(".challengeView").attr("disabled", "disabled"); 
            $(".challengeView").attr("onclick", ""); 
            $(".challengeView").css('cursor', 'default');
             alert("The challenge details page is coming soon!"); 
            return false; 
             
             
       });

      $('form#disconnect-strava').on('click', function(e) {
         e.preventDefault();

         $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure to delete the selected record(s)',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-blue',
                            action: function () {
                                $('form#disconnect-strava').submit();
                            }
                        },
                        cancel: function () {
                           window.location.reload(true);
                        },
                        /*moreButtons: {
                            text: 'something else',
                            action: function () {
                                return false;
                            }
                        },*/
                    }
                });

      //$("#delete-challenge-log-button").click(function(){
         /*var confirm = confirm("Are you sure?"); alert(confirm);
         if (confirm == 'ok'){
            $('form#delete-challenge-log').submit();
         }
         return false;*/
      });
           
        
   }); 

</script>

@endsection