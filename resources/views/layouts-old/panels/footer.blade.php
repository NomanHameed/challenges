@if(@auth()->user()->id)
<footer class="site-footer">
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-md-4 col-sm-5">
                  <div class="ftr-bx logo-ftr">
                     <figure class="logo-ftr">
                        <img src="{{ asset('assets/images/Footer Logo.png') }}" alt="">
                     </figure>
                     <!-- <p>
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                     </p> -->
                     <div class="ftr-social-links">
                        <ul>
                           <!-- <li>
                              <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i>
                              </a>
                           </li>
                           <li>
                              <a href="#"> <i class="fa fa-twitter" aria-hidden="true"></i>
                              </a>
                           </li>
                           <li>
                              <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i>
                              </a>
                           </li>
                           <li>
                              <a href="#"> <i class="fa fa-youtube-play" aria-hidden="true"></i>
                              </a>
                           </li> -->
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-3 col-sm-4">
                  <div class="ftr-bx">
                     <!-- <h3>Popular Pages</h3> -->
                     <ul class="ftr-links">
                        <!-- <li><a href="#">Virtual Run Events
                           </a>
                        </li>
                        <li>
                           <a href="#">  Ambassador Program</a>
                        </li>
                        <li>
                           <a href="#"> 
                           Virtual Running </a>
                        </li>
                        <li>
                           <a href="#"> Virtual Races With Medals</a>
                        </li>
                        <li>
                           <a href="#"> Virtual Turkey Trot Run </a>
                        </li>
                        <li>
                           <a href="#"> Americas Turkey Trots </a>
                        </li> -->
                     </ul>
                  </div>
               </div>
               <div class="col-lg-2 col-md-2 col-sm-3">
                  <div class="ftr-bx">
                     <h3>Links</h3>
                     <ul class="ftr-links">
                        <li><a href="https://www.challengeinmotion.com/pages/about-us-1">About Us
                           </a>
                        </li>
                        <li>
                           <a href="https://www.challengeinmotion.com/collections/challenges">  Challenges</a>
                        </li>
                        <li>
                           <a href="https://www.challengeinmotion.com/pages/contact-us"> 
                           Contact Us</a>
                        </li>
                        <li>
                           <a href="https://www.challengeinmotion.com/a/frequently-asked-questions">  FAQ</a>
                        </li>
                        <li>
                           <a href="https://www.challengeinmotion.com/policies/privacy-policy#"> Privacy Policy </a>
                        </li>
                        <li>
                           <a href="https://www.challengeinmotion.com/policies/terms-of-service">  Terms of service </a>
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="col-lg-3 col-md-3 col-sm-12">
                  <div class="ftr-bx">
                     <h3>Contact us</h3>
                     <address>
                        <ul>
                           <li>
                              <a href="#"><span class="material-icons-outlined">
place
</span>Challenge In Motion Charlotte, NC</a>
                           </li>
                           <li>
                              <a href="mailto:contact@challengeinmotion.com"><span class="material-icons">
                              alternate_email
                              </span>
                              contact@challengeinmotion.com
                              </a>
                           </li>
                        </ul>
                     </address>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-sm-12">
                  <div class="copyright text-center">
                     <p>
                        <strong>Challenge In Motion © 2021.</strong> All rights reserved.
                     </p>
                  </div>
               </div>
            </div>
         </div>
      </footer>
@endif


 <!-- Modal Reset password -->
 <div class="modal fade custom-modal" id="reset-password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
       <div class="modal-header d-block">
        <div class="modal-top text-center">
         <h5 class="modal-title">Reset Password</h5>
        </div>
         <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-times" aria-hidden="true"></i>
         </button>
       </div>
       <div class="modal-body">
         <form>
            <div class="login-wrp">
              
                <div class="form-group">
                  <label for="password">New Password</label>
                  <div class="pass-wrp">
                     <input class="password form-control" required name="login[password]" type="password" placeholder="****************************" />
                     <div class="hide-show">
                        <span class="hide-pass"></span>
                      </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="password">Confirm Password</label>
                  <div class="pass-wrp mb-45">
                     <input class="password form-control" required name="login[password]" type="password" placeholder="****************************" />
                     <div class="hide-show">
                        <span class="hide-pass"></span>
                      </div>
                  </div>
                </div>

              
                <button type="submit" class="login-btn btn-dflt green-btn">Save</button>
            </div>
            
          </form>
          
       </div>
       
     </div>
   </div>
 </div>
