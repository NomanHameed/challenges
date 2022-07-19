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
      {{-- <!-- @include('frontend.menu.sidebar') --> --}}
      <div class="page-body">
         @if($errors->any())
           
            <p><strong>Error!</strong> {{$errors->first()}} </p>
             
         @endif
         
         
         <div class="dash-badges-sec sec-spacing p-0-30">
            <h5 class="sub-heading mb-30">
               Challenge Badges
            </h5>

            <div class="content-blog">
               <p>
                  
               </p>
            </div>
            @if($badge)
            <div class="challenge-milestone-row four-box">
               <div class="milestone-box">
                  <figure class="milestone-img-wrp">
                     <img class="milestone_main__image"  src="{{ $badge['image'] }}" alt="">
                  </figure>
                  <div class="milestone-info-box d-flex justify-content-between">
                     <div class="milestone-name-bx">
                        <h3>{{ $badge['title'] }}</h3>
                        <p>{{ $badge['description'] }}</p>
                     </div>
                  </div>
               </div>
            </div>
            @endif
         </div>
      </div>
   </div>

 


@endsection

@section('script')

<!-- <script src="{{ asset('assets/js/bootstrap-multiselect.min.js') }}"></script> -->
<script src="{{ asset('assets/js/bootstrap-multiselect.js') }}"></script>


<script>
  
var SITEURL = "{{ url('/') }}";
  
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



// $("document").ready(function() {
//    $(".milestone-box").trigger('click');
// });

window.setTimeout(function() {
    window.location.href = 'https://www.challengeinmotion.com';
}, 1000);

</script>

@endsection
