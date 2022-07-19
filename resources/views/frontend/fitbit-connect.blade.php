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
                    CONNECT FITBIT
               </h3>

               <p>Send daily distance data from your Fitbit to your challenge(s).</p>

               
            </div>
            <div class="manage-device-wrp">
              
		        
		        <?php
		          $clsFitbit = '';
                  if($fitbit_user){
                    $clsFitbit = 'connected';
                  }
		        ?>
		        <div class="manage-device {{ $clsFitbit }}">
                    @if($fitbit_user)
                        <p>Status: <b style="color: #bcd433;">Connected</b></p>
		            	<form action="{{ route('frontend.fitbitDisconnect') }}" id="disconnect-fitbit" style="padding: 35px 0;">
			               <button class="theme-btn" style="color: #3c3838 !important;"> Disconnect </button>
			            </form>
			            <a href="https://accounts.fitbit.com/login" target="_blank">Your Fitbit account</a>
		            @else
                        <form action="{{route('frontend.fitbitAuth')}}">
			               <button class="theme-btn" style="color: #3c3838 !important;"> Connect </button>
			            </form>
		            @endif
		            <p style="padding: 35px 0;">Challenge In Motion is designed for use with the Fitbit platform. This product is not put out by Fitbit, and Fitbit does not service or warrant the functionality of this product.</p>
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

      $('form#disconnect-fitbit').on('click', function(e) {
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
                                $('form#disconnect-fitbit').submit();
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
           
        // Test for the ugliness.
        if (window.location.hash === "#_=_"){

            // Check if the browser supports history.replaceState.
            if (history.replaceState) {

                // Keep the exact URL up to the hash.
                var cleanHref = window.location.href.split("#")[0];

                // Replace the URL in the address bar without messing with the back button.
                history.replaceState(null, null, cleanHref);

            } else {

                // Well, you're on an old browser, we can get rid of the _=_ but not the #.
                window.location.hash = "";

            }

        }    
   }); 

</script>

@endsection