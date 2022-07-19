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
                  My Challenge
               </h3>
            </div>
            <div class="my-challenge-inner-wrp">
               <div class="my-challlenge-table">
                  <div class="challenge-filter-top">
                     <form action="{{route('frontend.my_challenge')}}" method="post" class="my-challenge-form" id="my-challenge-form">
                      {{ csrf_field() }}
                        {{ method_field('get') }}
                     <div class="custom-slect-bx">
                        <select name="challenge_status" id="challenge_status" class="form-control" onchange="document.getElementById('my-challenge-form').submit();">
                           <option value="" selected="">All Challenges</option>
                           <option value="2" <?= @$challenge_status == 2 ? 'selected=""' : '' ?>>Completed</option>
                           <option value="1" <?= @$challenge_status == 1 ? 'selected=""' : '' ?>>Active</option>
                        </select>
                     </div>
                     </form>
                  </div>
                  <div class="table-responsive">
                     <table class="table">
                        <thead>
                           @if($info)
                           <tr>

                              <th>Chalenge Name</th>
                              <th>Percentage</th>
                              <th>Status</th>
                              <th>Distance</th>
                              <th>Start Date</th>
                              <th>End Date</th>
                              <th>Action</th>
                           </tr>
                           @endif
                        </thead>
                        <tbody>

                           <?php 
                           if($info){
                              $i = 1;
                               foreach($info as $key => $val){
                                 if($i <= 3){
                                  $value = $val['challenges'];

                                  $distance = $Challenge_infos->getChallengeDistance($value->id);

                                  
                                  $coveredDistance = $Challenge_logs->getChallengeCoverage($value->id, $distance->meta_value);
                                  $coveredDistance = json_decode($coveredDistance, true);

                                  

                                  ?>
                                     
                                     <tr>
                                       <td>
                                          {{$value->name}}
                                       </td>
                                       <td>
                                          {{round($coveredDistance['percentage'], 2)}}%
                                       </td>
                                       <td>
                                          <span class="challenge-status-btn {{$coveredDistance['com']}}">
                                             {{$coveredDistance['comMsg']}}
                                          </span>
                                       </td>
                                       <td>
                                          {{$distance->meta_value}} miles
                                       </td>
                                       <td>
                                          {{\Carbon\Carbon::parse($value->event_start_date)->format('j F, Y')}}
                                       </td>
                                       <td>
                                          {{\Carbon\Carbon::parse($value->event_end_date)->format('j F, Y')}}
                                       </td>
                                       <td>
                                          <a class="nav-link challengeView" href="{{route('frontend.challenge_details', $value->id)}}">View</a>
                                       </td>
                                    </tr>
                                  <?php
                                 }
                                  $i++;
                               }
                           }else{
                              echo 'No data found';
                           }
                           ?>

                           
                        </tbody>
                     </table>

                     
                  </div>
                  <div class="table-pagination">
                     <ul class="pagination">
                        <li class="page-item Previous"><a class="page-link" href="#">
                           <span class="material-icons">
                              arrow_back
                              </span>

                        </a></li>
                       
                        <li class="page-item Next"><a class="page-link" href="#">

                           <span class="material-icons">
                              arrow_forward
                              </span>
                        </a></li>
                      </ul>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>

   @endsection

@section('script')

<script type="text/javascript">
   $(document).ready(function(){   

      /*$(document).on("click",".challengeView",function(e) {  
         e.preventDefault();
            $(".challengeView").attr("disabled", "disabled"); 
            $(".challengeView").attr("onclick", ""); 
            $(".challengeView").css('cursor', 'default');
             alert("The challenge details page is coming soon!"); 
            return false; 
             
             
       });*/
           
        
   }); 

</script>

@endsection