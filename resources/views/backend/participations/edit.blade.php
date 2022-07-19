@extends('layouts.admin')

@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1> Participation Log</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Log</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              
              <form action="{{route('admin.users.edit', $user_id)}}">
                <button type="submit" class="btn btn-info" style="float:right">
                  <i class="fas fa-arrow-alt-circle-left"></i>&nbsp Go Back
                </button>&nbsp
              </form>
              @if(!$total_distance == 0 && $past)
              <h3 class="card-title" style="float:right">
                <button type="button" id="view_milestones" class="btn btn-default" data-toggle="modal" data-target="#modal-xl">
                  Transfer Ownership 
                </button>
              </h3>
              @endif
              <!-- tools box -->
              <div class="card-tools">
                <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" data-toggle="tooltip"
                        title="Collapse">
                 
                
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
        
              <!---------widget area------------------->
              <div class="col-md-12">
            <!-- Widget: user widget style 1 -->
            <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-info">
                <h3 class="widget-user-username">Challenge:- {{$challenge_name}}</h3>
                <h5 class="widget-user-desc">User:- {{$user_name}}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" src="{{ asset(@$user_image) }}" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      @if($price_type != 'default')
                        <h5 class="description-header">{{$total_distance}} Miles</h5>
                      @else
                        <h5 class="description-header">N/A</h5>
                      @endif
                      
                      <span class="description-text">Total Distance</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">{{round($total_distance_travelled, 2)}} Miles</h5>
                      <span class="description-text">Completed Distance</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      @if($price_type != 'default')
                        <h5 class="description-header">{{$remaining_distance}} Miles</h5>
                      @else
                        <h5 class="description-header">N/A</h5>
                      @endif
                      <span class="description-text">Remaining Distance</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.widget-user -->

              @if($errors->any())
               
                <p><strong>Error!</strong> {{$errors->first()}} </p>
                 
             @endif

              <div class="calander-container p-0-30">
                  <!-- <figure><img class="mw-100" src="{{ asset('assets/images/calender-main.png') }}" alt=""></figure> -->
                  <div id='calendar'></div>
               </div>


            <div class="timeline">
              <!-- timeline time label -->

              @foreach($challenge_logs as $log) 
              <?php  //print_r($log); die();
              $new_time = '';
              $new_date= '';
              $startDateTime = @$log['startDateTime'];
              if($startDateTime){
                $tempDate = explode(' ', $startDateTime);
                $tempDate = explode('-', $tempDate['0']);
                $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
                if($stat){
                  $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $startDateTime, 'UTC')->setTimezone($timezone);
                 
                  $date = new DateTime($newDate);
                  $new_date= $date->format('dS F Y');  
                  $new_time= $date->format('h:i a');
                }
              }
                  
                  
              ?>  
              <div class="time-label">
                <span class="bg-red">{{$new_date}}</span>
              </div>
              <!-- /.timeline-label -->

              <!-- timeline item -->
              <div>
                <i class="fas fa-user bg-green"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fas fa-clock"></i> {{@$new_time}}</span>
                  <h3 class="timeline-header no-border"><a href="#">{{$user_name}}</a> completed {{@$log['distance_travelled']}} Miles</h3>
                </div>
              </div>
              <!-- END timeline item -->
              @endforeach
              
              <div>
                <i class="fas fa-clock bg-gray"></i>
              </div>
            </div>
          </div>

              <!--------widget area ends----------------->
        
              </div>
            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Time Table List</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <section class="content">
          <div class="row">
          <div class="col-12">

            <div class="card">
            <div class="card-header">
            </div>
            <!-- /.card-header -->

            <form action="{{route('transferOwnership')}}" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{csrf_token()}}">
              <input type="hidden" name="participation_id" value="{{$participation_id}}">  
              <div class="card-body">
                <div class="form-group">
                  <div class="form-group">
                    <label>User Type</label>
                    <select name="user_type" id="user_type" class="form-control select2" style="width: 100%;">
                      <option value="new_user">New User</option>
                      <option value="existing_user" >Existing User</option>
                    </select>
                  </div>               
                </div>
              </div>

              <div class="card-body new_user" style="display:none;">
                <div class="form-group">
                  <div class="form-group">
                    <label for="new_user">New User</label>
                    <select class="form-control select2" id="new_user" name="new_user">
                        <option value="" selected="selected">Select User</option>
                        <?php 
                          foreach($userList as $key => $value){
                        ?>
                          <option value="{{$value->id}}">{{$value->first_name." ".$value->last_name}}</option>
                        <?php
                          }
                         ?>
                    </select>
                  </div>               
                </div>
              </div>

              

              <div class="row">
                <div class="col-12">
                  <input type="submit" value="Submit" class="btn btn-success float-right">
                </div>
              </div>

            </form>

            
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          </div>
          <!-- /.row -->
        </section>
   
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->

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
           
            <p><strong>Error!</strong> {{$errors->first()}} </p>
             
         @endif
         <form action="{{ route('admin.participation.update_challenge_log', $user_id) }}" method="post" class="login-form" id="update-challenge-log-form">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <input type="hidden" name="challengeDetails" value="{{ route('admin.participation.challengesLog', ['challenge_id' => $id, 'user_id' => $user_id]) }}">
            <input type="hidden" name="challenge_id" id="updateChallenge_id" value="" readonly>
            <div class="manual-add-tab">
               <div class="activity-details-wrp">
                  <div class="activity-head">
                      <h3>Edit Log</h3>
                      <!-- <span>Donec commodo posuere.</span> -->
                  </div>
                  <div class="acitivity-info-bx">
                     <!-- <div class="form-group">
                         <label for="">Challenge(s)</label>
                         <div class="custom-slect-bx">
                           <input type="text" id="viewChallenges" value="">
                        </div>
                     </div> -->
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

          
            <form action="{{ route('admin.participation.delete_challenge_log') }}" method="post" class="login-form" id="delete-challenge-log">
               {{ csrf_field() }}
               {{ method_field('delete') }}
               <input type="hidden" name="challengeDetails" value="{{ route('admin.participation.challengesLog', ['challenge_id' => $id, 'user_id' => $user_id]) }}">
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
           
            <p><strong>Error!</strong> {{$errors->first()}} </p>
             
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

          
            <form action="{{ route('admin.participation.delete_challenge_log') }}" method="post" class="login-form" id="delete-strava-challenge-log">
               {{ csrf_field() }}
               {{ method_field('delete') }}
               <input type="hidden" name="challengeDetails" value="{{ route('admin.participation.challengesLog', ['challenge_id' => $id, 'user_id' => $user_id]) }}">
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
           
            <p><strong>Error!</strong> {{$errors->first()}} </p>
             
         @endif
         <form action="{{ route('admin.participation.add_challenge_log', ['challenge_id' => $id, 'user_id' => $user_id]) }}" method="post" class="login-form" id="login-form">
            {{ csrf_field() }}
            <input type="hidden" name="challengeDetails" value="{{ route('admin.participation.challengesLog', ['challenge_id' => $id, 'user_id' => $user_id]) }}">
            <div class="manual-add-tab">
               <div class="activity-details-wrp">
                  <div class="activity-head">
                      <h3>Input Log</h3>
                      <!-- <span>Donec commodo posuere.</span> -->
                  </div>
                  <div class="acitivity-info-bx">
                     
                     <div class="form-group">
                         <label for="">Activity</label>
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
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity start date & time </label>
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
                        <label for="">Distance In Miles</label>
                        <input type="number" min="0" step=0.01 name="distance">
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
                           <button class="theme-btn green-btn">Save</button>
                           
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
    
      <script type="text/javascript">
        $(document).ready(function(){
          $('#user_type').on('change', function () {
            var val = $(this).find('option').filter(':selected').val(); 
            if(val == "existing_user"){
              $('.new_user').css("display", "");
            }else if(val == "new_user"){
              $('.new_user').css("display", "none");
              window.location.href = "<?= URL::route('admin.users.create') ?>";
            }else{
              $('.new_user').css("display", "none");
            }
          });
        });
        
      </script>

      <script type="text/javascript">
         $(document).ready(function () {

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
/*document.getElementById("date_start_time").setAttribute("max", today);
document.getElementById("startDateTimeMax").setAttribute("max", today);*/


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

var data = `<?= $logs; ?>`;
data = JSON.parse(data);
  
var calendar = $('#calendar').fullCalendar({
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
                     var device_name = event.device_name; 
                     var athlete = event.athlete;
                     var activity_id = event.activity_id;
                     var activity = event.activity; 
                     var calories = event.calories; 
                     var endTime = event.endTime; 
                     var distance_travelled = event.distance_travelled; 
                     var start = event.start._i; 
                     var start1 = start.split(' ');
                     var start2 = start1['1'].split(':');

                     //console.log(start1);

                     var endTime1 = '';
                     if(endTime){ //console.log(endTime);
                        var endTime1 = endTime.split(':');
                     }
                        if(event){
                            /*$('#activityView').val(activity);
                            $('#challenge').val(name);
                            $('#date_start_time').val(start);
                            $('#distance').val(distance_travelled);
                            $('#endTime').val(endTime);
                            $('#calories').val(calories);*/
                            if(device_name == 'strava' || device_name == 'fitbit' || device_name == 'Mapmyrun' || device_name == 'garmin'){
                              $('#viewStravaModal').modal();
                              $('#viewStravaModal').css('opacity', 1);

                              $('#deleteStravaChallenge_id').val(id);
                              

                              $('#stravaChallenges').val(device_name);
                              $('#stravaActivity').val(activity);
                              $('#strava_date_start_time').val(start1['0']+'T'+start2['0']+':'+start2['1']);
                              $('#stravaDistance').val(distance_travelled);
                              $('#stravaHour').val(endTime1['0']);
                              $('#stravaMinute').val(endTime1['1']);
                              $('#stravaSecond').val(endTime1['2']);
                              $('#stravaCaloriee').val(calories);
                            }else{
                              $('#viewModal').modal();
                              $('#viewModal').css('opacity', 1);

                              $('#deleteChallenge_id').val(id);
                              $('#updateChallenge_id').val(id);

                              $('#viewChallenges').val(name);
                              $('#viewActivity option[value="'+activity+'"]').attr('selected', 'selected');
                              $('input[name=startDateTime]').val(start1['0']+'T'+start2['0']+':'+start2['1']);
                              $('#distance').val(distance_travelled);
                              $('#hour').val(endTime1['0']);
                              $('#minute').val(endTime1['1']);
                              $('#second').val(endTime1['2']);
                              $('#viewCalories').val(calories);
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
  
</script>
@endsection