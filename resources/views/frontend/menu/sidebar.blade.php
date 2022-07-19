<div class="sidebar-wrapper">
         <button class="OpenSideBar">
            <span class="material-icons">
               menu_open
               </span>
         </button>
         <div class="sidebar-cont">
            <div class="profile-sidebar p-left-70">
               <div class="user-dp-wrp">

                  <?php
                           
                     $profile_pic = asset('assets/images/dash-user.png'); 
                     $pic = @$user['0']->profile_pic;
                     if($pic){
                         $profile_pic = asset($pic);
                     }
                  ?>

                  <figure class="user-dp-container">
                     <img src="<?php echo $profile_pic; ?>" alt="">
                  </figure>
                  <div class="user-info-wrp-bx">
                     <h4>{{ $user['0']->name}}</h4>
                     <h5><span class="material-icons-outlined"> place</span>{{$address}}</h5>
                  </div>
                  <div class="user-semper-wrp">
                     <div class="semper-bx-main">
                        <div class="left semper">
                           <h5>{{$past_challenge}}<span>Completed</span></h5>
                        </div>
                        <div class="right semper">
                           <h5>{{$current_challenge}}<span>Active</span></h5>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div id="sidebar-menu" class="p-left-70">
               <ul class="nav flex-column flex-nowrap overflow-hidden">
                  <li class="nav-item active">
                     <a class="nav-link text-truncate" href="{{route('frontend.dashboard')}}">
                        <span class="material-icons-outlined"> dashboard_customize</span>
                        <span class="d-sm-inline">Dashboard</span>
                     </a>
                  </li>
                  <!-- <li class="nav-item">
                     <a class="nav-link text-truncate" href="#">
                        <span class="material-icons-outlined">rss_feed</span>
                        <span class="d-sm-inline">Feed</span>
                     </a>
                  </li> -->
                  <li class="nav-item">
                     <a class="nav-link collapsed text-truncate" href="#submenu1" data-toggle="collapse"
                        data-target="#submenu1"><span class="material-icons-outlined">
                           person
                           </span> <span
                           class="d-sm-inline">Profile</span></a>
                     <div class="collapse drp-links" id="submenu1" aria-expanded="false">
                        <ul class="flex-column pl-2 nav">
                           <li class="nav-item"><a class="nav-link py-0" href="{{route('frontend.profile')}}"><span class="material-icons-outlined">
                              account_circle
                              </span> <span>Profile Setting</span></a></li>
                           <!-- <li class="nav-item"><a class="nav-link py-0" href="{{route('frontend.dashboard')}}#myChallenge">
                              <span class="side-menu-icon">
                              <img src="{{asset('/assets/images/distance.png')}}">
                              </span>
                              
                              <span>My Active & Completed Challenges</span></a></li> -->
                           <li class="nav-item"><a class="nav-link py-0" href="{{ route('frontend.manageDevice') }}">
                              
                              <span class="side-menu-icon"><img src="{{asset('/assets/images/smart-watch.png')}}"></span>
                              <span>Manage Device</span></a></li>
                           <!-- <li class="nav-item"><a class="nav-link py-0" href="{{ route('frontend.Achievement') }}">
                              
                              <span class="side-menu-icon"><img src="{{asset('/assets/images/trophy.png')}}"></span>
                              <span>Achievement</span></a></li> -->
                           <!-- <li class="nav-item"><a class="nav-link py-0" href="https://challengeinmotion-knowledgebase.tawk.help/" target="_blank">
                              
                              <span class="side-menu-icon"><img src="{{asset('/assets/images/Help.png')}}"></span>
                              <span>Help</span></a></li> -->
   
                        </ul>
                     </div>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link text-truncate" href="{{route('frontend.dashboard')}}#myChallenge" style="white-space: break-spaces;">
                        <span class="side-menu-icon"><img src="{{asset('/assets/images/distance.png')}}"></span>
                        <span>My Active & Completed Challenges</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link text-truncate" href="{{ route('frontend.Achievement') }}">
                        <span class="side-menu-icon"><img src="{{asset('/assets/images/trophy.png')}}"></span>
                        <span>Achievement</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <a class="nav-link text-truncate" href="https://challengeinmotion-knowledgebase.tawk.help/" target="_blank">
                        <span class="side-menu-icon"><img src="{{asset('/assets/images/Help.png')}}"></span>
                        <span>Help</span>
                     </a>
                  </li>
                  <li class="nav-item">
                     <form action="{{ route('frontend.logout') }}" method="post" class="login-form" id="login_form">
                        {{ csrf_field() }}
                        <a class="nav-link text-truncate" href="javascript:{}" onclick="document.getElementById('login_form').submit();"><span class="material-icons">
                           logout
                           </span>
                        <span class="d-sm-inline">Logout</span></a>
                     </form>
                     
                  </li>
                 
               </ul>
            </div>
         </div>
      </div>