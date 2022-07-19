@extends('layouts.dashboardMain')

@section('style')

<style type="text/css">
   .custom-modal .form-group {
       margin-bottom: 0.8rem;
   }

   .multiselect-container>li>a>label {
  padding: 4px 20px 3px 20px;
}
html {  
      scroll-behavior: smooth;  
}

html {  
      scroll-behavior: smooth;  
}

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
               <h5 class="sub-heading mb-40" id="myChallenge">
                  Activity Logging Dashboard
               </h5>
               <p>Manually Log your daily activities directly to the calendar and apply your miles to your active challenges.</p>
            </div>
         </div>
         @if($errors->any())
           
            <p><strong>Error!</strong> {{$errors->first()}} </p>
             
         @endif
         <!-- <div class="map-filter-top p-0-30">
            <div class="filter-bx">
               <span>Filter</span>
               <div class="custom-slect-bx">
                  <select name="" id="" class="form-control">
                     <option value="Week">Week</option>
                     <option value="Month">Month</option>
                     <option value="Year">Year</option>
                  </select>
               </div>
             
            </div>
         </div> -->
         <div class="calander-container p-0-30">
            <!-- <figure><img class="mw-100" src="{{ asset('assets/images/calender-main.png') }}" alt=""></figure> -->
            <div id='calendar'></div>
         </div>
         <div class="activity-stats-wrp-top sec-spacing p-0-30">
            <h5 class="sub-heading mb-40">
               Activity Stats
            </h5>
            <div class="activity-stats-row">
               
               <div class="activity-box-wrp">
                  <div class="activity-top-head d-flex">
                     <span class="icon">
                        <!-- <img src="{{ asset('assets/images/currentweek.png') }}" alt=""> -->
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                     </span>
                     <h5>Current Week</h5>
                  </div>
                  <div class="activity-mile-status">
                     <h6>{{round($weekDistance, 2)}} Miles</h6>
                     <!-- <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> -->
                  </div>
               </div>
               <div class="activity-box-wrp">
                  <div class="activity-top-head d-flex">
                     <span class="icon">
                        <!-- <img src="{{ asset('assets/images/currentmonth.png') }}" alt=""> -->
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>

                     </span>
                     <h5>Current Month</h5>
                  </div>
                  <div class="activity-mile-status">
                     <h6>{{round($monthDistance, 2)}} Miles</h6>
                     <!-- <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> -->
                  </div>
               </div>
               <div class="activity-box-wrp">
                  <div class="activity-top-head d-flex">
                     <span class="icon">
                        <!-- <img src="{{ asset('assets/images/currentyear.png') }}" alt=""> -->
                     <i class="fa fa-calendar-o" aria-hidden="true"></i>

                  </span>
                     <h5>Current Year</h5>
                  </div>
                  <div class="activity-mile-status">
                     <h6>{{round($yearDistance, 2)}} Miles</h6>
                     <!-- <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> -->
                  </div>
               </div>
               <div class="activity-box-wrp" id="myChallenge">
                  <div class="activity-top-head d-flex">
                     <span class="icon">
                        <!-- <img src="{{ asset('assets/images/totalmilage.png') }}" alt=""> -->
                        <i class="fa fa-map-marker" aria-hidden="true"></i>

                     </span>
                     <h5>Total Mileage</h5>
                  </div>
                  <div class="activity-mile-status">
                     <h6>{{round($totalDistancreTravel, 2)}} Miles</h6>
                     <!-- <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> -->
                  </div>
               </div>

               
               <div class="activity-box-wrp">
                  <div class="activity-top-head d-flex">
                     <span class="icon">
                        <!-- <img src="{{ asset('assets/images/miles.png') }}"alt=""> -->
                        <i class="fa fa-map-marker" aria-hidden="true"></i>

                     </span>
                     <h5>Miles Until Next Milestone</h5>
                  </div>
                  <div class="activity-mile-status">
                     <!-- <div class="wrapper circle_progress">
                       <div class="container chart" data-size="300" data-value="73" data-arrow="down"></div>
                     </div> -->

                     <div class="wrapper">
                        <div class="card">
                          <div class="circle">
                            <div class="bar"></div>
                            <div class="box"><span></span></div>
                          </div>
                          
                        </div>
                     </div>
                    
                     <!-- <div class="circle-progress mx-auto mt-4" data-value='{{$badgeComplete}}'>
                        <span class="progress-left">
                                      <span class="progress-bar border-danger"></span>
                        </span>
                        <span class="progress-right">
                                      <span class="progress-bar border-danger"></span>
                        </span>
                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                          <div class="h2">{{round($badgeLimit, 2)}} mi </div>
                        </div>
                      </div> -->
                </div>
               </div>
               <div class="activity-box-wrp" id="myChallenge">
                  <div class="activity-top-head d-flex">
                     <span class="icon">
                        <!-- <img src="{{ asset('assets/images/totalmilage.png') }}" alt=""> -->
                     <i class="fa fa-calendar-o" aria-hidden="true"></i>
                  </span>
                     <h5>Best Day</h5>
                  </div>
                  <div class="activity-mile-status">
                     <h6>{{round($distanceOfTheDay, 2)}} Miles</h6>
                     <!-- <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> -->
                  </div>
               </div>
               <div class="activity-box-wrp" id="myChallenge">
                  <div class="activity-top-head d-flex">
                     <span class="icon">
                        <!-- <img src="{{ asset('assets/images/totalmilage.png') }}" alt=""> -->
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                     </span>
                     <h5>Best Week</h5>
                  </div>
                  <div class="activity-mile-status">
                     <h6>{{round($bestOfWeek, 2)}} Miles</h6>
                     <!-- <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> -->
                  </div>
               </div>
               <div class="activity-box-wrp" id="myChallenge">
                  <div class="activity-top-head d-flex">
                     <span class="icon">
                        <!-- <img src="{{ asset('assets/images/totalmilage.png') }}" alt=""> -->
                        <i class="fa fa-calendar-o" aria-hidden="true"></i>
                     </span>
                     <h5>Best Month</h5>
                  </div>
                  <div class="activity-mile-status">
                     <h6>{{round($bestMonth, 2)}} Miles</h6>
                     <!-- <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div> -->
                  </div>
               </div>
            </div>
         </div>
         <div class="complete_challenge-sec grey-bg sec-spacing  p-0-30" >
            <h5 class="sub-heading mb-40" id="myChallenge">
               Active & Completed Challenges
            </h5>
            <div class="challenge-milestone-row">
               <?php 
               $i = 1;
                   foreach($info as $key => $val){
                     //if($i <= 3){
                     $challenges_distance = $val['challenges_distance']; 
                      $value = $val['challenges'];
                      $event_end_date = $value->event_end_date;

                      $orgDate = $value->event_end_date;

                     $eventEndDate = '';
                     if($event_end_date){
                        $tempDate = explode(' ', $event_end_date);
                        $tempDate = explode('-', $tempDate['0']);
                        $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
                        if($stat){
                           $eventEndDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event_end_date, 'UTC')->setTimezone(admin_timezone());
                           $eventEndDate = date("m/d/Y", strtotime($eventEndDate));
                        }
                     }

                     $eventEndDate = $eventEndDate ? $eventEndDate : 'N/A';

                     $challenge_assign_date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value->event_start_date, 'UTC')->setTimezone($timezone);
                     $challenge_assign_date = date("m/d/Y", strtotime($challenge_assign_date));
                     $challenge_status = $value->challenge_status == '2' ? 'Closed' : 'Open';

                     $completePercentage = $Challenge_infos->challengeComplete($value->id, $user_id, $challenges_distance);
                      //$challengeAssign = $User_challenges->checkChallengeAssign($user_id, $value->id);
                      ?>
                         <div class="milestone-box  milestone-box-wrp">
                           <a class="nav-link challengeView" href="{{route('frontend.challenge_details', $value->id)}}">
                              <figure class="milestone-img-wrp">
                                 @if($value->image)
                                    <img src="{{url($value->image)}}" alt="">
                                 @else
                                    <img src="{{asset('assets/images/fff.jpg')}}" alt="">
                                 @endif
                                 
                                 
                              </figure>
                              <div class="milestone-info-box">
                                 <div class="milestone-name-bx">
                                    <h3>{{$value->name}}</h3>

                                 </div> 
                                 <div class="milestone-info-box ch-info milestoneDownInfo">
                                    <p><strong>Start Date:</strong> {{$value->price_type == 'default' ? 'N/A' : $challenge_assign_date}}</p>
                                
                                    <p> <strong>End Date:</strong> {{$value->price_type == 'default' ? 'N/A' : $eventEndDate}}</p>
                                    <p> <strong>Target:</strong> {{$value->price_type == 'default' ? 'N/A' : $challenges_distance.' mi'}} </p>
                                    <p> <strong>Percentage completion:</strong> {{$value->price_type == 'default' ? 'N/A' : $completePercentage.'%'}}</p>
                                    <p> <strong>Challenge Status:</strong> {{$value->price_type == 'default' ? 'N/A' : $challenge_status}}</p>
                                    

                                    <!-- <div class="progress">
                                       <div class="progress-bar" role="progressbar" style="width: <?= $completePercentage; ?>%" aria-valuenow="<?= $completePercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div> -->
                                 </div>

                              </div>
                           </a>
                             
                         </div>
                      <?php
                     //}
                      $i++;
                   }
                ?>
            </div>
            <!-- <div class="join-race-wrp clearfix mt-5 text-center">
               <a href="{{ route('frontend.my_challenge') }}" class="theme-btn green-btn">  My Challenge </a>
            
            </div> -->
            
         </div>
         {{-- <!-- <div class="dash-badges-sec sec-spacing p-0-30">
            <h5 class="sub-heading mb-30">
               Challenge Badges
            </h5>
            <div class="content-blog">
               <p>
                  
               </p>
            </div>
            <div class="challenge-milestone-row four-box">
               <?php
                    $badges = json_encode($badges);
                    $badges = json_decode($badges, true);
                    if($badges){
                       foreach($badges as $key => $value){ 
                        $badgeAssign = $User_badges->checkBadgeAssign($user_id, $value['id']);
                        $badgesData = $User_badges->getUserBadgesByUserIDAndBadgeId($user_id, $value['id']);

                        $specific_date = @$value['specific_date'];
                        $start_date = \Carbon\Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->startOfDay();
                        $end_date = \Carbon\Carbon::parse($specific_date, 'UTC')->setTimezone($timezone)->endOfDay();
                        ?>
                          <div class="milestone-box" @if($badgeAssign) data-toggle="modal" data-target="#myModal25{{ $value['id'] }}" @endif>
                              <figure class="milestone-img-wrp">
                                 <img class="milestone_main__image"  src="{{url($value['badge_logo'])}}" alt="">
                                 @if($badgeAssign)
                                    <img class="milestone_main__tick" src="{{ asset('assets/images/Blue tickk.png') }}" width="50" height="50">
                                 @endif
                              </figure>
                              <div class="milestone-info-box d-flex justify-content-between">
                                 <div class="milestone-name-bx">
                                    <h3>{{$value['badge_name']}}</h3>
                                 </div>
                              </div>
                           </div>

                           @if($badgeAssign)
                           <div class="modal fade" id="myModal25{{ $value['id'] }}" role="dialog" tabindex="-1" aria-labelledby="myModal25Label" aria-hidden="true" style="left: 10%;">
                              <div class="modal-dialog modal-lg">
                                 <div class="modal-content" style="width:75%;padding: 0;">
                                    <div class="modal-header d-block" style="padding: 0; padding-left: 16px;">
                                       <div class="modal-top">
                                          <h5 class="modal-title" id="badge-modal-title">{{ auth()->user()->name }}</h5>
                                       </div>
                                       <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                          <i class="fa fa-times" aria-hidden="true"></i>
                                       </button>
                                    </div>
                                    <div class="modal-body" id="badge-modal-body">
                                       <figure class="milestone-img-wrp">
                                          <img class="milestone_main__image" id="milestone_main_image_{{ $value['id'] }}" src="{{ url($value['badge_logo']) }}" alt="">
                                          @php
                                             $profile_pic = asset('assets/images/dash-user.png');
                                             if(auth()->user()->profile_pic) {
                                                $profile_pic = asset(auth()->user()->profile_pic);
                                             }
                                          @endphp
                                          <img class="milestone_main__profile" src="{{ $profile_pic }}" alt="">
                                       </figure>

                                       @if($badgesData)
                                          @foreach($badgesData as $key => $badgeData)
                                          <div class="row mt-4">
                                             @if($value['badge_type'] == 'member_since')
                                             <div class="col-md-6">
                                                <p>Badge Achievement Date: {{ \Carbon\Carbon::parse($badgeData['assign_date'])->format('M d, Y')}}</p>
                                             </div>
                                             <div class="col-md-6">
                                                <p>Member since: {{ str_replace('ago', '', auth()->user()->created_at->diffForHumans()) }}</p>
                                             </div>
                                             @endif

                                             @if($value['badge_type'] == 'challenge')
                                             <div class="col-md-6">
                                                <p>Badge Achievement Date: {{ \Carbon\Carbon::parse($badgeData['assign_date'])->format('M d, Y')}}</p>
                                             </div>
                                             <div class="col-md-6">
                                                <p>Challenge(s) Completed: {{ round($User_badges->getTotalDistanceTravel($user_id, $badgeData['challenge_id']), 2) }} mi</p>
                                             </div>
                                             @endif

                                             @if($value['badge_type'] == 'distance' || $badgeData['badge_type'] == 'distance_milestone')
                                                <div class="col-md-6">
                                                   <p>Badge Achievement Date: {{ \Carbon\Carbon::parse($badgeData['assign_date'])->format('M d, Y')}}</p>
                                                </div>
                                                @if($value['badge_type'] == 'distance')
                                                <div class="col-md-6">
                                                   <p>Distance Logged: {{ $value['condition_limit'] }} mi</p>

                                                   @if($User_badges->getTotalDistanceTravel($user_id, $badgeData['challenge_id']) > $value['condition_limit'])
                                                   <p>Number of logs: {{ $User_badges->getTotalNumberofLogs($user_id, $badgeData['challenge_id'], $start_date, $end_date) }}</p>
                                                   @else
                                                   <p>Number of logs: 0</p>
                                                   @endif
                                                </div>
                                                @else
                                                <div class="col-md-6">
                                                   <p>Distance Logged: {{ $User_badges->getTotalChallengeMilestones($badgeData['challenge_id']) }} mi</p>

                                                   @if($User_badges->getTotalDistanceTravel($user_id, $badgeData['challenge_id']) > $User_badges->getTotalChallengeMilestones($badgeData['challenge_id']))
                                                   <p>Number of logs: {{ $User_badges->getTotalNumberofLogs($user_id, $badgeData['challenge_id'], $start_date, $end_date) }}</p>
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
                                          </div>
                                       </div>

                                       <div id="social-links">
                                          <ul class="milestone-info-box d-flex justify-content-between">
                                             <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(route('test.show', $value['id'])); ?>" class="social-button" target="_blank">Facebook</a></li>
                                             <li><a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($value['badge_name']); ?>&amp;url=<?php echo urlencode(route('test.show', $value['id'])); ?>" class="social-button" target="_blank">Twitter</a></li>   
                                          </ul>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endif
                           
                        <?php
 
                       }
                    }else{
                     echo "No data found!";
                    }
                    
               ?>
               
               
            </div>
         </div> --> --}}
      </div>
   </div>

 

<div class="modal fade custom-modal" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true" style="left: 10%;">
   <div class="modal-dialog modal-lg">
     <div class="modal-content" style="width:75%;padding: 0;">
       <div class="modal-header d-block" style="padding: 0;">
        <!-- <div class="modal-top text-center">
         <h5 class="modal-title">Add Logs</h5>
         
        </div> -->
         <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-times" aria-hidden="true"></i>
         </button>
       </div>
       <div class="modal-body">
         @if($errors->any())
           
            <!-- <p><strong>Error!</strong> {{$errors->first()}} </p> -->
             
         @endif
         <form action="{{ route('frontend.update_challenge_log') }}" method="post" class="login-form" id="update-challenge-log-form">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <input type="hidden" name="challenge_id" id="updateChallenge_id" value="" readonly>
            <div class="manual-add-tab">
               <div class="activity-details-wrp">
                  <div class="activity-head">
                      <h3>Edit Log</h3>
                      <!-- <span>Donec commodo posuere.</span> -->
                  </div>
                  <div class="acitivity-info-bx">
                     <div class="form-group">
                         <label for="">Challenge(s)*</label>
                         <div class="custom-slect-bx">
                           <input type="text" id="viewChallenges" value="">
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity*</label>
                         <div class="custom-slect-bx">
                           <select name="activity" id="viewActivity" class="form-control">
                              <option value="running" >Running</option>
                              <option value="walking" >Walking</option>
                              <option value="cycling" >Cycling</option>
                              <option value="hiking" >Hiking</option>
                              <option value="swim" >Swim</option>
                              <option value="rowing" >Rowing</option>
                              <option value="cross_country_skiing" >Cross Country Skiing</option>
                              <option value="snowshoe" >Snowshoe</option>
                              <option value="elliptical" >Elliptical</option>
                              <option value="treadmill" >Treadmill</option>
                              <option value="stationary_bike" >Stationary Bike</option>
                              <option value="ice_skating" >Ice Skating</option>
                              <option value="skiing" >Skiing</option>
                              <option value="Roller_blade_skates" >Roller Blade/Skates</option>
                              <option value="rucking" >Rucking</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity start date & time* </label>
                        <div class="icon-wrp-input">
                           <!-- <input type="text" name="startDateTime" id="startDateTime" class="form-control" placeholder="09-08-2020  2:19 PM " value="">
                           <span class="icon-input material-icons-outlined">
                           calendar_today
                           </span> -->

                           <div class='input-group date' >
                              <input type='datetime-local' name="startDateTime" id="date_start_time"  class="form-control" max="{{\Carbon\Carbon::now()->toDateTimeString()}}"  value="" />
                              <span class="input-group-addon" >
                                 <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                           </div>

                           <!-- <input type="text" name="startDateTime" id="date_start_time" class="form-control datetimepicker-input startDateTime" value="" readonly> -->

                           <!-- <div class="input-group date" id="startDateTime1" data-target-input="nearest">
                              <input type="text" name="startDateTime" id="date_start_time" class="form-control datetimepicker-input" data-target="#startDateTime1" value="">
                              <div class="input-group-append" data-target="#startDateTime1" data-toggle="datetimepicker">
                                 <div class="input-group-text" style="height: 100%;"><i class="fa fa-calendar"></i></div>
                              </div>
                           </div> -->

                        </div>
                     <div class="form-group">
                        <label for="">Distance In Miles*</label>
                        <input type="number" id="distance" min="0" step=0.01 name="distance">
                     </div>
                     <div class="form-group">
                       <div class="time-ingo-row">
                        <div class="info-time">
                           <label for="">Hours</label>
                           <input type="number" id="hour" min="0" max="24" name="hour">
                        </div>
                        <div class="info-time">
                           <label for="">Minutes</label>
                           <input type="number" min="0" id="minute" max="59" name="minute">
                        </div>
                        <div class="info-time">
                           <label for="">Seconds</label>
                           <input type="number" min="0" max="59" id="second" name="second">
                        </div>
                       </div>
                       
                     </div>

                     <div class="form-group">
                        <label for="">Calories</label>
                        <input type="number" step=0.01 min="0" id="viewCalories" class="form-control" name="calories">
                     </div>
                     
                           <div class="form-group">
                              <div class="save-add-log1">
                                 <button class="theme-btn green-btn">Save</button>
                                 
                              </div>
                           </div>
                        
                     
                    </div>
                    
                 </div>
                  </div>
                </div>
          </form>

          
            <form action="{{ route('frontend.delete_challenge_log') }}" method="post" class="login-form" id="delete-challenge-log">
               {{ csrf_field() }}
               {{ method_field('delete') }}
               <input type="hidden" name="challenge_id" id="deleteChallenge_id" value="">
               <div class="form-group">
                  <div class="save-add-log1" style="text-align: center;">
                     <button class="theme-btn green-btn" id="delete-challenge-log-button" style="width: 75%;">Delete</button>
                  </div>
               </div>
            </form>
         
          
       </div>
       
     </div>
   </div>
</div>

<div class="modal fade custom-modal" id="viewStravaModal" tabindex="-1" aria-labelledby="viewStravaModalLabel" aria-hidden="true" style="left: 10%;">
   <div class="modal-dialog modal-lg">
     <div class="modal-content" style="width:75%;padding: 0;">
       <div class="modal-header d-block" style="padding: 0;">
        <!-- <div class="modal-top text-center">
         <h5 class="modal-title">Add Logs</h5>
         
        </div> -->
         <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-times" aria-hidden="true"></i>
         </button>
       </div>
       <div class="modal-body">
         @if($errors->any())
           
            <!-- <p><strong>Error!</strong> {{$errors->first()}} </p> -->
             
         @endif
         <form action="" method="post" class="login-form" id="update-strava-challenge-log-form">
           
            <div class="manual-add-tab">
               <div class="activity-details-wrp">
                  <div class="activity-head">
                      <h3>View Log</h3>
                      <!-- <span>Donec commodo posuere.</span> -->
                  </div>
                  <div class="acitivity-info-bx">
                     <div class="form-group">
                         <label for="">Device Type*</label>
                         <div class="custom-slect-bx">
                           <input type="text" id="stravaChallenges" value="" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity*</label>
                         <div class="custom-slect-bx">
                           <input type="text" id="stravaActivity" value="" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity start date & time* </label>
                        <div class="icon-wrp-input">
                           <!-- <input type="text" name="startDateTime" id="startDateTime" class="form-control" placeholder="09-08-2020  2:19 PM " value="">
                           <span class="icon-input material-icons-outlined">
                           calendar_today
                           </span> -->

                           <div class='input-group date' >
                              <input type='datetime-local'  id="strava_date_start_time"  class="form-control" value="" readonly/>
                              
                           </div>

                           <!-- <input type="text" name="startDateTime" id="date_start_time" class="form-control datetimepicker-input startDateTime" value="" readonly> -->

                           <!-- <div class="input-group date" id="startDateTime1" data-target-input="nearest">
                              <input type="text" name="startDateTime" id="date_start_time" class="form-control datetimepicker-input" data-target="#startDateTime1" value="">
                              <div class="input-group-append" data-target="#startDateTime1" data-toggle="datetimepicker">
                                 <div class="input-group-text" style="height: 100%;"><i class="fa fa-calendar"></i></div>
                              </div>
                           </div> -->

                        </div>
                     <div class="form-group">
                        <label for="">Distance In Miles*</label>
                        <input type="number" id="stravaDistance" min="0" step=0.01 readonly>
                     </div>
                     <div class="form-group">
                       <div class="time-ingo-row">
                        <div class="info-time">
                           <label for="">Hours</label>
                           <input type="number" id="stravaHour" min="0" max="24" readonly>
                        </div>
                        <div class="info-time">
                           <label for="">Minutes</label>
                           <input type="number" min="0" id="stravaMinute" max="59" readonly>
                        </div>
                        <div class="info-time">
                           <label for="">Seconds</label>
                           <input type="number" min="0" max="59" id="stravaSecond" readonly>
                        </div>
                       </div>
                       
                     </div>

                     <div class="form-group">
                        <label for="">Calories</label>
                        <input type="number" step=0.01 min="0" id="stravaCaloriee" class="form-control" readonly>
                     </div>
                     
                        
                        
                     
                    </div>
                    
                 </div>
                  </div>
                </div>
          </form>

          
            <form action="{{ route('frontend.delete_challenge_log') }}" method="post" class="login-form" id="delete-strava-challenge-log">
               {{ csrf_field() }}
               {{ method_field('delete') }}
               <input type="hidden" name="challenge_id" id="deleteStravaChallenge_id" value="">
               <div class="form-group">
                  <div class="save-add-log1" style="text-align: center;">
                     <button class="theme-btn green-btn" id="delete-challenge-log-button" style="width: 75%;">Delete</button>
                  </div>
               </div>
            </form>
         
          
       </div>
       
     </div>
   </div>
</div>

<div class="modal fade custom-modal" id="viewModalWhenClosedDevice" tabindex="-1" aria-labelledby="viewModalWhenClosedLabel" aria-hidden="true" style="left: 10%;">
   <div class="modal-dialog modal-lg">
     <div class="modal-content" style="width:75%;padding: 0;">
       <div class="modal-header d-block" style="padding: 0;">
        <!-- <div class="modal-top text-center">
         <h5 class="modal-title">Add Logs</h5>
         
        </div> -->
         <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-times" aria-hidden="true"></i>
         </button>
       </div>
       <div class="modal-body">
         @if($errors->any())
           
            <!-- <p><strong>Error!</strong> {{$errors->first()}} </p> -->
             
         @endif
         
            <div class="manual-add-tab">
               <div class="activity-details-wrp">
                  <div class="activity-head">
                      <h3>View Log</h3>
                      <!-- <span>Donec commodo posuere.</span> -->
                  </div>
                  <div class="acitivity-info-bx">
                     <div class="form-group">
                         <label for="">Device Type*</label>
                         <div class="custom-slect-bx">
                           <input type="text" id="stravaChallengesWhenClosedDevice" value="" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity*</label>
                         <div class="custom-slect-bx">
                           <input type="text" id="stravaActivityWhenClosedDevice" value="" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity start date & time* </label>
                        <div class="icon-wrp-input">
                           <!-- <input type="text" name="startDateTime" id="startDateTime" class="form-control" placeholder="09-08-2020  2:19 PM " value="">
                           <span class="icon-input material-icons-outlined">
                           calendar_today
                           </span> -->

                           <div class='input-group date' >
                              <input type='datetime-local'  id="strava_date_start_timeWhenClosedDevice"  class="form-control" value="" readonly/>
                              
                           </div>

                           <!-- <input type="text" name="startDateTime" id="date_start_time" class="form-control datetimepicker-input startDateTime" value="" readonly> -->

                           <!-- <div class="input-group date" id="startDateTime1" data-target-input="nearest">
                              <input type="text" name="startDateTime" id="date_start_time" class="form-control datetimepicker-input" data-target="#startDateTime1" value="">
                              <div class="input-group-append" data-target="#startDateTime1" data-toggle="datetimepicker">
                                 <div class="input-group-text" style="height: 100%;"><i class="fa fa-calendar"></i></div>
                              </div>
                           </div> -->

                        </div>
                     <div class="form-group">
                        <label for="">Distance In Miles*</label>
                        <input type="number" id="stravaDistanceWhenClosedDevice" min="0" step=0.01 readonly>
                     </div>
                     <div class="form-group">
                       <div class="time-ingo-row">
                        <div class="info-time">
                           <label for="">Hours</label>
                           <input type="number" id="stravaHourWhenClosedDevice" min="0" max="24" readonly>
                        </div>
                        <div class="info-time">
                           <label for="">Minutes</label>
                           <input type="number" min="0" id="stravaMinuteWhenClosedDevice" max="59" readonly>
                        </div>
                        <div class="info-time">
                           <label for="">Seconds</label>
                           <input type="number" min="0" max="59" id="stravaSecondWhenClosedDevice" readonly>
                        </div>
                       </div>
                       
                     </div>

                     <div class="form-group">
                        <label for="">Calories</label>
                        <input type="number" step=0.01 min="0" id="stravaCalorieeWhenClosedDevice" class="form-control" readonly>
                     </div>

                     <div class="form-group">
                        <h6 class="messageDisableDevice"></h6>
                     </div>
                     
                        
                        
                     
                    </div>
                    
                 </div>
                  </div>
                </div>
          
         
          
       </div>
       
     </div>
   </div>
</div>

<div class="modal fade custom-modal" id="viewModalWhenClosed" tabindex="-1" aria-labelledby="viewModalWhenClosedLabel" aria-hidden="true" style="left: 10%;">
   <div class="modal-dialog modal-lg">
     <div class="modal-content" style="width:75%;padding: 0;">
       <div class="modal-header d-block" style="padding: 0;">
        <!-- <div class="modal-top text-center">
         <h5 class="modal-title">Add Logs</h5>
         
        </div> -->
         <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-times" aria-hidden="true"></i>
         </button>
       </div>
       <div class="modal-body">
         @if($errors->any())
           
            <!-- <p><strong>Error!</strong> {{$errors->first()}} </p> -->
             
         @endif
         
            <div class="manual-add-tab">
               <div class="activity-details-wrp">
                  <div class="activity-head">
                      <h3>View Log</h3>
                      <!-- <span>Donec commodo posuere.</span> -->
                  </div>
                  <div class="acitivity-info-bx">
                     <div class="form-group">
                         <label for="">Challenge(s)*</label>
                         <div class="custom-slect-bx">
                           <input type="text" id="ChallengesWhenClosed" value="" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity*</label>
                         <div class="custom-slect-bx">
                           <input type="text" id="stravaActivityWhenClosed" value="" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity start date & time* </label>
                        <div class="icon-wrp-input">
                           <!-- <input type="text" name="startDateTime" id="startDateTime" class="form-control" placeholder="09-08-2020  2:19 PM " value="">
                           <span class="icon-input material-icons-outlined">
                           calendar_today
                           </span> -->

                           <div class='input-group date' >
                              <input type='datetime-local'  id="strava_date_start_timeWhenClosed"  class="form-control" value="" readonly/>
                              
                           </div>

                           <!-- <input type="text" name="startDateTime" id="date_start_time" class="form-control datetimepicker-input startDateTime" value="" readonly> -->

                           <!-- <div class="input-group date" id="startDateTime1" data-target-input="nearest">
                              <input type="text" name="startDateTime" id="date_start_time" class="form-control datetimepicker-input" data-target="#startDateTime1" value="">
                              <div class="input-group-append" data-target="#startDateTime1" data-toggle="datetimepicker">
                                 <div class="input-group-text" style="height: 100%;"><i class="fa fa-calendar"></i></div>
                              </div>
                           </div> -->

                        </div>
                     <div class="form-group">
                        <label for="">Distance In Miles*</label>
                        <input type="number" id="stravaDistanceWhenClosed" min="0" step=0.01 readonly>
                     </div>
                     <div class="form-group">
                       <div class="time-ingo-row">
                        <div class="info-time">
                           <label for="">Hours</label>
                           <input type="number" id="stravaHourWhenClosed" min="0" max="24" readonly>
                        </div>
                        <div class="info-time">
                           <label for="">Minutes</label>
                           <input type="number" min="0" id="stravaMinuteWhenClosed" max="59" readonly>
                        </div>
                        <div class="info-time">
                           <label for="">Seconds</label>
                           <input type="number" min="0" max="59" id="stravaSecondWhenClosed" readonly>
                        </div>
                       </div>
                       
                     </div>

                     <div class="form-group">
                        <label for="">Calories</label>
                        <input type="number" step=0.01 min="0" id="stravaCalorieeWhenClosed" class="form-control" readonly>
                     </div>

                     <div class="form-group">
                        <h6 class="messageDisable"></h6>
                     </div>
                     
                        
                        
                     
                    </div>
                    
                 </div>
                  </div>
                </div>
          
         
          
       </div>
       
     </div>
   </div>
</div>

<div class="modal fade custom-modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="left: 10%;">
   <div class="modal-dialog modal-lg">
     <div class="modal-content" style="width:75%;padding: 0;">
       <div class="modal-header d-block" style="padding: 0;">
        <!-- <div class="modal-top text-center">
         <h5 class="modal-title">Add Logs</h5>
         
        </div> -->
         <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-times" aria-hidden="true"></i>
         </button>
       </div>
       <div class="modal-body">
         @if($errors->any())
           
            <!-- <p><strong>Error!</strong> {{$errors->first()}} </p> -->
             
         @endif
         <form action="{{ route('frontend.add_challenge_log') }}" method="post" class="login-form" id="add-challenge-log-form">
            {{ csrf_field() }}
            <div class="manual-add-tab">
               <div class="activity-details-wrp">
                  <div class="activity-head">
                      <h3>Activity Log</h3>
                      <!-- <span>Donec commodo posuere.</span> -->
                  </div>
                  <div class="acitivity-info-bx">
                     <div class="form-group">
                         <label for="">Challenge(s)*</label>
                         <div class="custom-slect-bx">
                           <input type="hidden" name="challengeDetails" value="">
                           <select name="challenges[]" id="challenges" multiple="multiple">
                              <?php
                                 foreach($challenges as $key => $val){
                              ?>
                                    <option value="{{$val->id}}" >{{$val->name}}</option>
                              <?php

                                 }
                              ?>
                           </select>
                        </div>
                        <label>Note: Please make sure to select a challenge before logging your miles.</label>
                     </div>
                     <div class="form-group">
                         <label for="">Activity*</label>
                         <div class="custom-slect-bx">
                           <select name="activity" id="activity" class="form-control">
                              <option value="running" >Running</option>
                              <option value="walking" >Walking</option>
                              <option value="cycling" >Cycling</option>
                              <option value="hiking" >Hiking</option>
                              <option value="swim" >Swim</option>
                              <option value="rowing" >Rowing</option>
                              <option value="cross_country_skiing" >Cross Country Skiing</option>
                              <option value="snowshoe" >Snowshoe</option>
                              <option value="elliptical" >Elliptical</option>
                              <option value="treadmill" >Treadmill</option>
                              <option value="stationary_bike" >Stationary Bike</option>
                              <option value="ice_skating" >Ice Skating</option>
                              <option value="skiing" >Skiing</option>
                              <option value="Roller_blade_skates" >Roller Blade/Skates</option>
                              <option value="rucking" >Rucking</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity start date & time* </label>
                        <div class="icon-wrp-input">
                           <!-- <input type="text" name="startDateTime" id="startDateTime" class="form-control" placeholder="09-08-2020  2:19 PM " value="">
                           <span class="icon-input material-icons-outlined">
                           calendar_today
                           </span> -->

                           <div class='input-group date' >
                              <input type='datetime-local' name="startDateTime" id="startDateTimeMax" max="{{\Carbon\Carbon::now()->toDateTimeString()}}"  class="form-control" value="" />
                              <span class="input-group-addon" >
                                 <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                           </div>
                           <!-- <input type="text" name="startDateTime" class="form-control datetimepicker-input startDateTime" value="" readonly> -->
                           

                           <!-- <div class="input-group date" id="startDateTime" data-target-input="nearest">
                              <input type="text" name="startDateTime" class="form-control datetimepicker-input" data-target="#startDateTime" value="">
                              <div class="input-group-append" data-target="#startDateTime" data-toggle="datetimepicker">
                                 <div class="input-group-text" style="height: 100%;"><i class="fa fa-calendar"></i></div>
                              </div>
                           </div> -->

                        </div>
                     <div class="form-group">
                        <label for="">Distance In Miles*</label>
                        <input type="number" value="0" min="0" step=0.01 name="distance" id="addDistance">
                     </div>
                     <div class="form-group">
                       <div class="time-ingo-row">
                        <div class="info-time">
                           <label for="">Hours</label>
                           <input type="number" min="0" max="24" name="hour">
                        </div>
                        <div class="info-time">
                           <label for="">Minutes</label>
                           <input type="number" min="0" max="59" name="minute">
                        </div>
                        <div class="info-time">
                           <label for="">Seconds</label>
                           <input type="number" min="0" max="59" name="second">
                        </div>
                       </div>
                       
                     </div>

                     <div class="form-group">
                        <label for="">Calories</label>
                        <input type="number" step=0.01 min="0" class="form-control" name="calories">
                     </div>
                     <div class="form-group">
                        <div class="save-add-log1">
                           <button class="theme-btn green-btn" id="add-challenge-log-form-button">Save</button>
                           
                        </div>
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

<?php $logs = json_encode($logs); ?>
      @endsection

@section('script')

<script src="{{ asset('assets/js/bootstrap-multiselect.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>
<script>
   var per = "<?php echo $badgeComplete/100; ?>";
   var val = "<?php echo round($badgeLimit, 2); ?>";
      /*let options = {
        startAngle: -1.55,
        size: 150,
        value: per,
        fill: {gradient: ['#a445b2', '#fa4299']}
      }*/
      let options = {
        startAngle: -1.55,
        size: 150,
        value: per,
        fill: {gradient: ['#003b63', '#003b63']}
      }
      $(".circle .bar").circleProgress(options).on('circle-animation-progress',
      function(event, progress, stepValue){
        $(this).parent().find("span").text(val + " mi");
      });
      $(".js .bar").circleProgress({
        value: 0.70
      });
      
    </script>


<script>




$(document).ready(function () {

   $('#challenges').multiselect({
        
        includeSelectAllOption: true
    });

   var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();
 if(dd<10){
        dd='0'+dd
    } 
    if(mm<10){
        mm='0'+mm
    } 

today = yyyy+'-'+mm+'-'+dd+'T23:59:59';
document.getElementById("date_start_time").setAttribute("max", today);
document.getElementById("startDateTimeMax").setAttribute("max", today);



   $('#add-challenge-log-form-button').on('click', function(e) {
         e.preventDefault();

         var miles = $('#addDistance').val();
         if(miles == '' || miles == 0){
            alert("Distance should not be Empty or Zero.");
            return false;
         }
         //console.log(miles);
         var selectedData = '';
         //$('select[name="challenges"] option:selected').on('change', function(){  
            $("#challenges option:selected").each(function () {
               var $this = $(this);
               if ($this.length) {
                var selText = $this.text();
                if(selectedData){
                  selectedData = selectedData+', '+selText;
                }else{
                  selectedData = selText;
                }
                
               }

            });
         //});

         //var msg = 'Are you sure you want to add your Mile(s) i.e. '+miles+' in default Challenge i.e. Legacy Challenge?';
         var msg = 'Hello '+"{{ $user['0']->name}}"+' Please confirm that you have not selected a challenge to apply your '+miles+' miles?  Miles will not be applied to any active challenge.';
         if(selectedData){
            //var msg = 'Are you sure you want to add your Mile(s) i.e. '+miles+' in the challenge(s) i.e. '+selectedData+'?';
            var msg = 'Congratulations '+"{{ $user['0']->name}}"+' your miles will be applied to '+selectedData;
         }

         //console.log(selectedData);

         $.confirm({
                    title: 'Confirm Submission',
                    content: msg,
                    icon: 'fa fa-exclamation-triangle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    
                    opacity: 0.5,
                    buttons: {
                        //confirmButtonClass: 'btn-blue',
                        //cancelButtonClass: 'btn-red',
                        'confirm': {
                            text: 'CONFIRM',
                            btnClass: 'green-confirm-btn',
                            action: function () {
                                $('form#add-challenge-log-form').submit();
                            }
                        },
                        'cancel': {
                            text: 'CANCEL',
                            btnClass: 'btn-red',
                            action: function () {
                                //$('form#add-challenge-log-form').submit();
                                window.location.reload(true);
                            }
                        },
                        /*function () {
                           text: 'CANCEL',
                           btnClass: 'btn-red',
                           window.location.reload(true);
                        },*/
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

   $('form#delete-challenge-log').on('click', function(e) {
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
                                $('form#delete-challenge-log').submit();
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

   $('form#delete-strava-challenge-log').on('click', function(e) {
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
                                $('form#delete-strava-challenge-log').submit();
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

   
var SITEURL = "{{ url('/') }}";
  
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var data = `<?= $logs; ?>`; //console.log(data);
data = JSON.parse(data); //console.log(data);
 
var calendar = $('#calendar').fullCalendar({
                 timeZone: '{{$timezone}}',
                 defaultView: 'month',
            
         header: {
            left: 'prev,next today',
            center: 'title',
            right: 'listYear,month,basicWeek,basicDay'
         },
                    eventSources: [data],
                    events: '',
                    displayEventTime: false,
                    editable: true,
                    eventRender: function (event, element, view) {
                        if (event.allDay === 'true') {
                                event.allDay = true;
                        } else {
                                event.allDay = false;
                        }
                    },
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end, allDay) {
                        var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD hh:mm"); 
                        var start1 = start.split(' ');
                        $("#startDateTimeMax").val(start1['0']+'T'+start1['1']);
                        $('#exampleModal').modal();
                        $('#exampleModal').css('opacity', 1);
                        /*var title = prompt('Event Title:');
                        if (title) {
                            var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                            var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                            $.ajax({
                                url: SITEURL + "/fullcalenderAjax",
                                data: {
                                    title: title,
                                    start: start,
                                    end: end,
                                    type: 'add'
                                },
                                type: "POST",
                                success: function (data) {
                                    displayMessage("Event Created Successfully");
  
                                    calendar.fullCalendar('renderEvent',
                                        {
                                            id: data.id,
                                            title: title,
                                            start: start,
                                            end: end,
                                            allDay: allDay
                                        },true);
  
                                    calendar.fullCalendar('unselect');
                                }
                            });
                        }*/
                    },
                    eventDrop: function (event, delta) {
                        var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                        var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
  
                        /*$.ajax({
                            url: SITEURL + '/fullcalenderAjax',
                            data: {
                                title: event.title,
                                start: start,
                                end: end,
                                id: event.id,
                                type: 'update'
                            },
                            type: "POST",
                            success: function (response) {
                                displayMessage("Event Updated Successfully");
                            }
                        });*/
                    },
                    eventClick: function (event) { 
                     var id = event.id; 
                     var name = event.name;
                     var user_challenge_status = event.user_challenge_status;
                     var device_name = event.device_name; 
                     var athlete = event.athlete;
                     var activity_id = event.activity_id;
                     var activity = event.activity; 
                     var calories = event.calories; 
                     var endTime = event.endTime; 
                     var distance_travelled = event.distance_travelled; 
                     var start = event.start._i;  //console.log(start);
                     var start1 = start.split(' ');
                     var start2 = start1['1'].split(':');
                     
                     var endTime1 = '';
                     //console.log(start1);
                     if(endTime){
                        var endTime1 = endTime.split(':');
                     }
                     
                        if(event){ //console.log(event);
                            /*$('#activityView').val(activity);
                            $('#challenge').val(name);
                            $('#date_start_time').val(start);
                            $('#distance').val(distance_travelled);
                            $('#endTime').val(endTime);
                            $('#calories').val(calories);*/
                            if(device_name == 'strava' || device_name == 'fitbit' || device_name == 'Mapmyrun' || device_name == 'garmin'){
                              if(user_challenge_status == 2){
                                 $('#stravaHourWhenClosedDevice').val('');
                                 $('#stravaMinuteWhenClosedDevice').val('');
                                 $('#stravaSecondWhenClosedDevice').val('');

                                 $('#viewModalWhenClosedDevice').modal();
                                 $('#viewModalWhenClosedDevice').css('opacity', 1);

                                 $('#deleteStravaChallenge_idWhenClosedDevice').val(id);
                                 

                                 $('#stravaChallengesWhenClosedDevice').val(device_name);
                                 $('#stravaActivityWhenClosedDevice').val(activity);
                                 $('#strava_date_start_timeWhenClosedDevice').val(start1['0']+'T'+start2['0']+':'+start2['1']);
                                 $('#stravaDistanceWhenClosedDevice').val(distance_travelled);
                                 if(endTime1){
                                    $('#stravaHourWhenClosedDevice').val(endTime1['0']);
                                    $('#stravaMinuteWhenClosedDevice').val(endTime1['1']);
                                    $('#stravaSecondWhenClosedDevice').val(endTime1['2']);
                                 }
                                 $('#stravaCalorieeWhenClosedDevice').val(calories);
                                 $('.messageDisableDevice').text('The log could not be edited. One of the selected challenge has been completed.');

                              }else{
                                 $('#stravaHour').val('');
                                 $('#stravaMinute').val('');
                                 $('#stravaSecond').val('');

                                 $('#viewStravaModal').modal();
                                 $('#viewStravaModal').css('opacity', 1);

                                 $('#deleteStravaChallenge_id').val(id);
                                 

                                 $('#stravaChallenges').val(device_name);
                                 $('#stravaActivity').val(activity);
                                 $('#strava_date_start_time').val(start1['0']+'T'+start2['0']+':'+start2['1']);
                                 $('#stravaDistance').val(distance_travelled);
                                 if(endTime1){
                                    $('#stravaHour').val(endTime1['0']);
                                    $('#stravaMinute').val(endTime1['1']);
                                    $('#stravaSecond').val(endTime1['2']);
                                 }
                                 $('#stravaCaloriee').val(calories);

                              }
                              
                            }else{
                              if(user_challenge_status == 2){
                                 $('#stravaHourWhenClosed').val('');
                                 $('#stravaMinuteWhenClosed').val('');
                                 $('#stravaSecondWhenClosed').val('');

                                 $('#viewModalWhenClosed').modal();
                                 $('#viewModalWhenClosed').css('opacity', 1);

                                 $('#deleteStravaChallenge_idWhenClosed').val(id);
                                 

                                 $('#ChallengesWhenClosed').val(name);
                                 $('#stravaActivityWhenClosed').val(activity);
                                 $('#strava_date_start_timeWhenClosed').val(start1['0']+'T'+start2['0']+':'+start2['1']);
                                 $('#stravaDistanceWhenClosed').val(distance_travelled);
                                 if(endTime1){
                                    $('#stravaHourWhenClosed').val(endTime1['0']);
                                    $('#stravaMinuteWhenClosed').val(endTime1['1']);
                                    $('#stravaSecondWhenClosed').val(endTime1['2']);
                                 }
                                 $('#stravaCalorieeWhenClosed').val(calories);
                                 $('.messageDisable').text('The log could not be edited. One of the selected challenge has been completed.');

                              }else{
                                 $('#viewModal').modal();
                                 $('#viewModal').css('opacity', 1);

                                 $('#deleteChallenge_id').val(id);
                                 $('#updateChallenge_id').val(id);

                                 $('#viewChallenges').val(name);
                                 $('#viewActivity option[value='+activity+']').attr('selected', 'selected');
                                 $('input[name=startDateTime]').val(start1['0']+'T'+start2['0']+':'+start2['1']);
                                 $('#distance').val(distance_travelled);
                                 $('#hour').val(endTime1['0']);
                                 $('#minute').val(endTime1['1']);
                                 $('#second').val(endTime1['2']);
                                 $('#viewCalories').val(calories);
                              }
                            }
                            
                        }
                           
                           
                        /*var deleteMsg = confirm("Do you really want to delete?");
                        if (deleteMsg) {
                            $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                        id: event.id,
                                        type: 'delete'
                                },
                                success: function (response) {
                                    calendar.fullCalendar('removeEvents', event.id);
                                    displayMessage("Event Deleted Successfully");
                                }
                            });
                        }*/
                    }
 
                });
 
});
 
function displayMessage(message) {
    toastr.success(message, 'Event');
} 

// $("document").ready(function() {
//    $(".milestone-box").trigger('click');
// });  
</script>

@endsection
