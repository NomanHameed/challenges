@extends('layouts.frontend')

@section('style')

@endsection

@section('content')

<div class="banner inner-page-banner">
 <div class="bg_img inner-banner" style="background-image: url('assets/images/listing-page-banner.png');"></div>
 <div class="banner-container">
    <div class="banner-caption">
       <h1>Challenge
       </h1>
    </div>
 </div>
</div>
<section class="about-info-sec sec-spacing">
 <div class="container">
    
    <div class="row">
       <div class="col-sm-12">
          <div class="page-info-blog text-grey text-center">
             <p>
                Experience the thrill of your first Virtual Running Event! We offer 5k Virtual Run Events for those who are just starting out or experienced runners who love running 5k’s, we offer 10k Virtual Races, and more in 2020.  New this year is long distant solo and relay virtual running challenges.

               
             </p>
             <p>
               <strong class="d-block black-text"> The Virtual Run Challenge Events are for runners of all ages and skill levels.</strong> 
                To learn more about any of our events select an event below.
             </p>
             
          </div>
       </div>
    </div>
 </div>
</section>

<section class="challenge-main-wrp">
 <div class="container">
    <div class="row">
       <div class="col-sm-12">
          <div class="filter-area d-flex">
             <div class="filter-wrp-bx">
                <button class="filter-btn">Filter</button>
             </div>
             <div class="filter-tags-box ">
                <div class="filter-tag">
                   Distance
                   <span class="material-icons">
                      close
                      </span>
                </div>
                <div class="filter-tag">
                   Distance
                   <span class="material-icons">
                      close
                      </span>
                </div>
                <div class="filter-tag">
                   Distance
                   <span class="material-icons">
                      close
                      </span>
                </div>
                <div class="filter-tag">
                   Distance
                   <span class="material-icons">
                      close
                      </span>
                </div>
             </div> 
          </div> 
             <div class="challenge-wrp-bx">
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

	            <div class="challenge-bx">
                   <figure class="img-wrp">
                      <a href="{{ route('frontend.challenge_details', $value->id) }}">
                      <img src="{{ url($value->image) }}" alt=""></a>
                   </figure>
                   <h3 class="challenge-heading">
                      {{$value->name}}
                   </h3>
                   <div class="challenge-top-bx d-flex">
                         <div class="tag-brdr">Accumulative</div>
                         <div class="price-ch"><span>$50</span></div>
                   </div>
                   <p>
                      {{ Str::words($value->description, 50) }}
                   </p>
                   <div class="btm-btn-chl">
                      <a class="l-more grey-btn" href="{{ route('frontend.challenge_details', $value->id) }}">Learn More</a>
                      <a class="r-now blue-btn" href="#">Register Now</a>
                   </div>
                </div>

	            <?php
	                        }
	                        $i++;
	                    }
	                }else{
	                  echo 'No data found';
	                }
	               ?>
                <div class="challenge-bx">
                   <figure class="img-wrp">
                      <a href="challenge-details.html">
                      <img src="{{ asset('assets/images/listing-bx6.png') }}" alt=""></a>
                   </figure>
                   <h3 class="challenge-heading">
                      My Best Year Challenge 2021
                   </h3>
                   <div class="challenge-top-bx d-flex">
                         <div class="tag-brdr">Accumulative</div>
                         <div class="price-ch"><span>$50</span></div>
                   </div>
                   <p>
                      Consequat semper viverra nam libero justo laoreet sit amet.
                   </p>
                   <div class="btm-btn-chl">
                      <a class="l-more grey-btn" href="#">Learn More</a>
                      <a class="r-now blue-btn" href="#">Register Now</a>
                   </div>
                </div>
                <div class="challenge-bx">
                   <figure class="img-wrp">
                      <a href="challenge-details.html">
                      <img src="{{ asset('assets/images/listing-bx8.png') }}" alt=""></a>
                   </figure>
                   <h3 class="challenge-heading">
                      Runpoly Challenge
                      (Run or Walk 100+ Miles) <br>
                   </h3>
                   <div class="challenge-top-bx d-flex">
                         <div class="tag-brdr">Accumulative</div>
                         <div class="price-ch"><span>$50</span></div>
                   </div>
                   <p>
                      Consequat semper viverra nam libero justo laoreet sit amet.
                   </p>
                   <div class="btm-btn-chl">
                      <a class="l-more grey-btn" href="#">Learn More</a>
                      <a class="r-now blue-btn" href="#">Register Now</a>
                   </div>
                </div>
                <div class="challenge-bx">
                   <figure class="img-wrp">
                      <a href="challenge-details.html">
                      <img src="{{ asset('assets/images/listing-bx9.png') }}" alt=""></a>
                   </figure>
                   <h3 class="challenge-heading">
                      My Best Year Challenge 2021
                   </h3>
                   <div class="challenge-top-bx d-flex">
                         <div class="tag-brdr">Accumulative</div>
                         <div class="price-ch"><span>$50</span></div>
                   </div>
                   <p>
                      Consequat semper viverra nam libero justo laoreet sit amet.
                   </p>
                   <div class="btm-btn-chl">
                      <a class="l-more grey-btn" href="#">Learn More</a>
                      <a class="r-now blue-btn" href="#">Register Now</a>
                   </div>
                </div>
                <div class="challenge-bx">
                   <figure class="img-wrp">
                      <a href="challenge-details.html">
                      <img src="{{ asset('assets/images/listing-bx4.png') }}" alt=""></a>
                   </figure>
                   <h3 class="challenge-heading">
                      My Best Year Challenge 2021
                   </h3>
                   <div class="challenge-top-bx d-flex">
                         <div class="tag-brdr">Accumulative</div>
                         <div class="price-ch"><span>$50</span></div>
                   </div>
                   <p>
                      Consequat semper viverra nam libero justo laoreet sit amet.
                   </p>
                   <div class="btm-btn-chl">
                      <a class="l-more grey-btn" href="#">Learn More</a>
                      <a class="r-now blue-btn" href="#">Register Now</a>
                   </div>
                </div>
                <div class="challenge-bx">
                   <figure class="img-wrp">
                      <a href="challenge-details.html">
                      <img src="{{ asset('assets/images/listing-bx5.png') }}" alt=""></a>
                   </figure>
                   <h3 class="challenge-heading">
                      My Best Year Challenge 2021
                   </h3>
                   <div class="challenge-top-bx d-flex">
                         <div class="tag-brdr">Accumulative</div>
                         <div class="price-ch"><span>$50</span></div>
                   </div>
                   <p>
                      Consequat semper viverra nam libero justo laoreet sit amet.
                   </p>
                   <div class="btm-btn-chl">
                      <a class="l-more grey-btn" href="#">Learn More</a>
                      <a class="r-now blue-btn" href="#">Register Now</a>
                   </div>
                </div>
                <div class="challenge-bx">
                   <figure class="img-wrp">
                      <a href="challenge-details.html">
                      <img src="{{ asset('assets/images/listing-bx6.png') }}" alt=""></a>
                   </figure>
                   <h3 class="challenge-heading">
                      My Best Year Challenge 2021
                   </h3>
                   <div class="challenge-top-bx d-flex">
                         <div class="tag-brdr">Accumulative</div>
                         <div class="price-ch"><span>$50</span></div>
                   </div>
                   <p>
                      Consequat semper viverra nam libero justo laoreet sit amet.
                   </p>
                   <div class="btm-btn-chl">
                      <a class="l-more grey-btn" href="#">Learn More</a>
                      <a class="r-now blue-btn" href="#">Register Now</a>
                   </div>
                </div>
             </div>
             <div class="loadmore-sec text-center">
                <button class="load-more green-btn">
                   View More
                </button>
             </div>
          
       </div>
    </div>
 </div>
</section>

 @endsection

@section('script')

@endsection