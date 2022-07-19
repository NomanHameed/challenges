@extends('layouts.dashboardMain')

@section('style')
<style type="text/css">
.milestone-img-wrp .milestone_main__profile {
   position: absolute;
   right: 10px;
   z-index: 100;
   height: 45px;
   width: 45px;
   border-radius: 50%;
   object-fit: cover;
   bottom: 10px;
}
</style>

@endsection

@section('content')

<div class="page-body-wrapper">
    @include('frontend.menu.sidebar')
    <div class="page-body">
        <div class="p-0-30">

            <div class="dash-inne-main-top">
               <h3 class="site-heading">
                  Achievement
               </h3>
               <p>This is a collection of digital badges that you have 
                  earned.  Each earned digital badge will be full color and highlighted with a green 
                  checkmark.  New badges to be added each month to keep you motivated. Once earned 
                  you can share your accomplishment by clicking on the badge and selecting to share or 
                  download.  Remember to tag us #ChallengeinMotion for your chance to be featured and 
                  also win prizes. </p>

               @if(session('message'))
	              
	                  <p><strong>Success!</strong> {{session('message')}} </p>
	                        
	            @endif

	            @if($errors->any())
	              
	                  <p><strong>Error!</strong> {{$errors->first()}} </p>
	                
	            @endif
            </div>
            
            <div class="dash-badges-sec sec-spacing p-0-30">
            
            <div class="challenge-milestone-row four-box">
            	<?php
                    $badges = json_encode($badges);
                    $badges = json_decode($badges, true);
                    if($badges){
                       foreach($badges as $key => $value){ 
                        $badgeAssign = $User_badges->checkBadgeAssign($user_id, $value['id']);
                        $badgeAssignSeenStatus = $User_badges->checkBadgeAssignSeenStatus($user_id, $value['id']);
                        $badgesData = $User_badges->getUserBadgesByUserIDAndBadgeId($user_id, $value['id']);

                        $specific_date = @$value['specific_date'];
                        $start_date = \Carbon\Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->startOfDay();
                        $end_date = \Carbon\Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->endOfDay();
                        ?>
                          <div class="milestone-box @if($badgeAssign && $badgeAssignSeenStatus) trigger-milestone-box @endif" @if($badgeAssign && $badgeAssignSeenStatus) onclick="triggerMileStoneBox('{{ $value['id'] }}');" @endif @if($badgeAssign) data-toggle="modal" data-target="#myModal25{{ $value['id'] }}" @endif @if(!$badgeAssign) style="opacity:0.3;" @endif>
                              <figure class="milestone-img-wrp">
                                 @if($value['badge_logo'])
                                 <img class="milestone_main__image"  src="{{url($value['badge_logo'])}}" alt="">
                                 @else
                                 <img class="milestone_main__image"  src="{{asset('assets/images/fff.jpg')}}" alt="">
                                 @endif
                                 @if($badgeAssign)
                                    <img class="milestone_main__tick" src="{{ asset('assets/images/Blue tickk.png') }}" width="50" height="50">
                                 @endif
                              </figure>

                              @if($badgeAssign)
                           <div class="modal fade achievementModalNew" id="myModal25{{ $value['id'] }}" role="dialog" tabindex="-1" aria-labelledby="myModal25Label" aria-hidden="true" style="left: 10%;">
                              <div class="modal-dialog modal-lg ">
                                 <div class="modal-content">
                                    <div class="modal-header d-block" style="padding: 0; padding-left: 16px;">
                                       <div class="modal-top">
                                          <h5 class="modal-title" id="badge-modal-title">{{ auth()->user()->name }}</h5>
                                          <p style="font-size: 13px;">Share Your Achievement with friends and make sure to tag us for chances to win prizes  <strong>#ChallengeinMotion</strong></p>
                                       </div>
                                       <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                          <i class="fa fa-times" aria-hidden="true"></i>
                                       </button>
                                    </div>
                                    <div class="modal-body" id="badge-modal-body">
                                       <figure class="milestone-img-wrp">
                                          
                                          @if($value['badge_logo'])
                                          <img class="milestone_main__image" id="milestone_main_image_{{ $value['id'] }}" src="{{ url($value['badge_logo']) }}" alt="">
                                          @else
                                          <img class="milestone_main__image" id="milestone_main_image_{{ $value['id'] }}" src="{{asset('assets/images/fff.jpg')}}" alt="">
                                          @endif
                                          @php
                                             $profile_pic = asset('assets/images/dash-user.png');
                                             if(auth()->user()->profile_pic) {
                                                $profile_pic = asset(auth()->user()->profile_pic);
                                             }
                                          @endphp
                                          <img class="milestone_main__profile" src="{{ $profile_pic }}" alt="">
                                       </figure>
                                    <?php //print_r($badgesData); ?>
                                    @if($badgesData)
                                       @foreach($badgesData as $key => $badgeData)
                                          <div class="row mt-4">
                                             @if($badgeData['badge_type'] == 'member_since')
                                             <div class="col-md-6">
                                                <p>Badge Achievement Date: {{ \Carbon\Carbon::parse($badgeData['assign_date'])->format('M d, Y')}}</p>
                                             </div>
                                             <div class="col-md-6">
                                                <p>Member since: {{ str_replace('ago', '', auth()->user()->created_at->diffForHumans()) }}</p>
                                             </div>
                                             @endif

                                             @if($badgeData['badge_type'] == 'challenge')
                                             <div class="col-md-6">
                                                <p>Badge Achievement Date: {{ \Carbon\Carbon::parse($badgeData['assign_date'])->format('M d, Y')}}</p>
                                             </div>
                                             <div class="col-md-6">
                                                <p>Challenge(s) Completed: {{ round($User_badges->getTotalDistanceTravel($user_id, $badgeData['challenge_id']), 2) }} mi</p>
                                             </div>
                                             @endif

                                             @if($value['badge_type'] == 'distance' || $badgeData['badge_type'] == 'distance')
                                                <div class="col-md-6">
                                                   <p>Badge Achievement Date : {{ \Carbon\Carbon::parse($badgeData['assign_date'])->format('M d, Y')}}</p>
                                                </div>
                                                @if($badgeData['badge_type'] == 'distance')
                                                <div class="col-md-6">
                                                   <p>Distance Logged: {{ $value['condition_limit'] }} mi</p>
                                                    <?php 
                                                      $getTotalNumberofLogs = (new \App\Models\User_badges())->getTotalNumberofLogs($badgeData['user_id'], $badgeData['challenge_id'], $start_date, $end_date, $value['condition_limit'], $badgeData['badge_id']); 
                                                      if(!empty($getTotalNumberofLogs)){ //print_r($getTotalNumberofLogs); die();
                                                         $getTotalNumberofLogs = json_decode($getTotalNumberofLogs, true);
                                                         $enddate = $getTotalNumberofLogs['enddate'];
                                                         $count = $getTotalNumberofLogs['count'];
                                                      }else{
                                                         $enddate = '';
                                                         $count = 0;
                                                      }
                                                      

                                                      ?>
                                                   
                                                   <p>Number of logs: {{ $count }}</p>
                                                   
                                                </div>
                                                @else
                                                <div class="col-md-6">
                                                   <p>Distance Logged: {{ $User_badges->getTotalChallengeMilestones($badgeData['challenge_id']) }} mi</p>

                                                   @if($User_badges->getTotalDistanceTravel($user_id, $badgeData['challenge_id']) > $User_badges->getTotalChallengeMilestones($badgeData['challenge_id']))

                                                   <?php 
                                                   $getTotalNumberofLogs = (new \App\Models\User_badges())->getTotalNumberofLogs($user_id, $badgeData['challenge_id'], $start_date, $end_date, $value['condition_limit'], $badgeData['badge_id']); 
                                                   if(!empty($getTotalNumberofLogs)){ //print_r($getTotalNumberofLogs); die();
                                                      $getTotalNumberofLogs = json_decode($getTotalNumberofLogs, true);
                                                      $enddate = $getTotalNumberofLogs['enddate'];
                                                      $count = $getTotalNumberofLogs['count'];
                                                   }else{
                                                      $enddate = '';
                                                      $count = 0;
                                                   }
                                                   

                                                   ?>
                                                   <p>Number of logs: {{ $count }}</p>
                                                   @else
                                                   <p>Number of logs: 0</p>
                                                   @endif
                                                </div>
                                                @endif
                                             @endif

                                          </div>
                                          @endforeach
                                       @endif
                                       <div class="milestone-info-box d-flex justify-content-between">
                                          <div class="milestone-name-bx">
                                             <h3 id="milestone_badge_name_{{ $value['id'] }}">{{ $value['badge_name'] }}</h3>
                                             <!-- <p style="margin-top: 1em;"><a href="{{ url($value['badge_logo']) }}" download>Download Badge</a></p> -->
                                          </div>
                                       </div>

                                       <div id="social-links">
                                          <ul class="milestone-info-box d-flex socialIconModal">
                                             <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(route('test.show', $value['id'])); ?>" class="social-button" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
                                             <li><a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($value['badge_name']); ?>&amp;url=<?php echo urlencode(route('test.show', $value['id'])); ?>" class="social-button" target="_blank"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li> 
                                             <li><a href="{{ url($value['badge_logo']) }}" download><i class="fa fa-cloud-download" aria-hidden="true"></i></a></li>
                                          </ul>
                                       </div>

                                       </div>
                                    </div>
                                 </div>
                              </div>
                              @endif
                           

                              <div class="milestone-info-box d-flex justify-content-between">
                                 <div class="milestone-name-bx">
                                    <h3>{{$value['badge_name']}}</h3>
                                 </div>
                              </div>

                              <div class="milestone-distance-info">
                                 <p>{{mb_strimwidth($value['badge_info'], 0, 200, '...')}}</p>
                              </div>

                              
                           </div>

                           

                                         
                             
                           
                        <?php
 
                       }
                    }else{
                     echo "No data found!";
                    }
                    
               ?>

              
		        <?php
		          $clsFitbit = '';
		                  if($fitbit_user){
		                    $clsFitbit = 'connected';
		                  }
		        ?>
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

   $("document").ready(function() {
      $(".trigger-milestone-box").trigger('click');
   }); 

   function triggerMileStoneBox(id) {
      if(id != "") {
         $.ajax({
            type:'POST',
            url:'{{ route("frontend.updateBadgeSeenStatus") }}',
            data:{'id': id,'_token': $('meta[name="csrf-token"]').attr('content')},
            dataType:'json',
            success:function(response){
                console.log(response);
            }
        });
      }
   } 

</script>

@endsection