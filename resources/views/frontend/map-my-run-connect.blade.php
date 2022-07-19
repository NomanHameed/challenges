@extends('layouts.dashboardMain')

@section('style')

@endsection

@section('content')

<div class="page-body-wrapper">
    @include('frontend.menu.sidebar')
    <div class="page-body">
        <div class="p-0-30">

            <div class="dash-inne-main-top">
            	@if(session('message'))
	              
	                  <p><strong>Success!</strong> {{session('message')}} </p>
	                        
	            @endif

	            @if($errors->any())
	              
	                  <p><strong>Error!</strong> {{$errors->first()}} </p>
	                
	            @endif
               <h3 class="site-heading">

                  CONNECT STRAVA

                  CONNECT UNDERARMOUR

               </h3>
               <p>Send daily distance data from your UnderArmour to your challenge(s).</p>

               
            </div>
            <div class="manage-device-wrp">
              
		        
		        <?php
		        $clsStrava = '';
                  if($Strava_user){
                      $clsStrava = 'connected';
                  }
		        ?>
		        <div class="manage-device {{$clsStrava}}">

                    
                    <!-- <form action="{{route('admin.MapMyRunAuth')}}">
		               <button class="theme-btn" style="color: #3c3838 !important;"> Connect </button>
		            </form> -->
		           

                    @if($Strava_user)
                        <p>Status: <b style="color: #bcd433;">Connected</b></p>
                        <form action="{{route('admin.MapMyRunDisconnect')}}" id="disconnect-strava" style="padding: 35px 0;">
                           <button class="theme-btn" style="color: #3c3838 !important;"> Disconnect </button>
                        </form>
                        <a href="https://www.mapmyrun.com/auth/login/?next=%2F" target="_blank">Your UnderArmour account</a>

                    @else
                        <form action="{{route('admin.MapMyRunAuth')}}">
    		               <button class="theme-btn" style="color: #3c3838 !important;"> Connect </button>
    		            </form>
		            @endif

		            <p style="padding: 35px 0;">Challenge In Motion is designed for use with the UnderArmour platform. This product is not put out by UnderArmour, and UnderArmour does not service or warrant the functionality of this product.</p>
		        </div>
		        
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
