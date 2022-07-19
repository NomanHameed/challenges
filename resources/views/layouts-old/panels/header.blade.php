   <header class="site-header">
         <div class="container">
            <div class="row">
               <div class="col-sm-12">
                  <nav class="navbar navbar-expand-lg ">
                     <a class="navbar-brand" href="{{route('frontend.home')}}">
                     <img src="{{ asset('assets/images/Header Logo.png') }}" alt="logo">
                     </a>
                      @if(@auth()->user()->id)
                     <ul class="navbar-nav ml-auto mt-2 mt-lg-0 desktop-menu">
                        <!-- <li class="nav-item active">
                           <a class="nav-link" href="about.html">About Us <span class="sr-only">(current)</span></a>
                        </li> -->
                       
                        <li class="nav-item">
                           <a class="nav-link" href="https://www.challengeinmotion.com/collections/challenges">Challenges</a>
                        </li>
                        
<!--                         <li class="nav-item">
                           <a class="nav-link" href="contact-us.html">                            Contact Us</a>
                        </li> -->
                     </ul>
                     <!-- <ul class="login-area">
                        <li>
                           <div class="loginsignup ">
                              @if(!@auth()->user()->id)
                              <a data-toggle="modal" data-target="#exampleModal" href="#"><span class="material-icons-outlined">
                              lock
                              </span> Login</a> 
                              <span class="sep">/ </span>
                              <a data-toggle="modal" data-target="#signup-modal" href="#" href="#">Sign up</a>
                              @else
                              <form action="{{ route('frontend.logout') }}" method="post" class="login-form" id="login_form">
                                 {{ csrf_field() }}
                                 <a href="javascript:{}" onclick="document.getElementById('login_form').submit();">Logout</a>
                              </form>
                              @endif
                           </div>
                        </li>
                        <li>
                          <a href="challenge-listing.html" class="green-btn join-challenge-btn">Join a Challenge</a>
                        </li>
                     </ul> -->
                     <button class="toggleMenu">
                        <div class="con">
                           <div class="menu-ico move-left close-ico">
                           </div>
                        </div>
                     </button>
                     @endif
                  </nav>
               </div>
            </div>
         </div>
         <div class="site-menu-mobile">
            <nav class="navbar navbar-expand-lg ">
               <a class="navbar-brand" href="index.html">
                  <img src="assets/images/Header Logo.png" alt="logo">
                  </a>
               <ul class="navbar-nav ml-auto mt-2 mt-lg-0 ">
                  <!-- <li class="nav-item active">
                     <a class="nav-link" href="#">About Us <span class="sr-only">(current)</span></a>
                  </li> -->
                  <li class="nav-item">
                     <a class="nav-link" href="https://www.challengeinmotion.com/collections/challenges">Challenges</a>
                  </li>
                  <!-- <li class="nav-item">
                     <a class="nav-link" href="contact-us.html">                            Contact Us</a>
                  </li> -->
               </ul>
               <!-- <ul class="login-area">
                  <li>
                     <div class="loginsignup ">
                        <a data-toggle="modal" data-target="#exampleModal" href="#"><span class="material-icons-outlined">
                        lock
                        </span> Login</a> 
                        <span class="sep">/ </span>
                        <a data-toggle="modal" data-target="#signup-modal" href="#" href="#">Sign up</a>
                     </div>
                  </li>
                  <li>
                     <a href="#" class="green-btn join-challenge-btn">Join a Challenge</a>
                  </li>
               </ul> -->
            </nav>
         </div>
      </header>

   