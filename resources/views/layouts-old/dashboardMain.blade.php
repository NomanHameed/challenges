<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <?php if (isset($badge) && !empty($badge)): ?>
      <!-- Facebook -->
      <meta property="og:url" content="<?php echo $badge['url']; ?>" />
      <meta property="og:type" content="<?php echo $badge['type']; ?>" />
      <meta property="og:title" content="<?php echo $badge['title']; ?>" />
      <meta property="og:description" content="<?php echo $badge['description']; ?>" />
      <meta property="og:image" content="<?php echo $badge['image']; ?>" />
      <!-- Twitter -->
      <meta name="twitter:card" content="summary_large_image"/>
      <meta name="twitter:site" content="@<?php echo $badge['site']; ?>"/>
      <meta name="twitter:creator" content="@<?php echo $badge['creator']; ?>"/>
      <meta name="twitter:title" content="<?php echo $badge['title']; ?>"/>
      <meta name="twitter:description" content="<?php echo $badge['description']; ?>"/>
      <meta name="twitter:image" content="<?php echo $badge['image']; ?>"/>
      <?php else: ?>
      <!-- Facebook -->
      <meta property="og:url" content="https://tracker.challengeinmotion.com/dashboard" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="Your Title" />
      <meta property="og:description" content="Your description" />
      <meta property="og:image" content="https://tracker.challengeinmotion.com/assets/images/Header Logo.png" />
      <!-- Twitter -->
      <meta name="twitter:card" content="summary_large_image"/>
      <meta name="twitter:site" content="@<?php echo 'Challenge In Motion'; ?>"/>
      <meta name="twitter:title" content="<?php echo 'Your Website Title'; ?>"/>
      <meta name="twitter:description" content="<?php echo 'Your Website description'; ?>"/>
      <?php endif; ?>
      
      <meta property="og:image:width" content="150" />
      <meta property="og:image:height" content="150" />
      
      <!-- Bootstrap CSS -->
      <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"> -->
      <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
      <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->

      <!-- custom CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">
      <!-- <link rel="stylesheet" href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css"/> -->
      <link rel="stylesheet" href="{{ asset('assets/css/material-icon.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/hover.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
      

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

      
      
      <!-- From MAin -->

      <!-- Font Awesome -->
      <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
      <!-- DataTables -->
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Tempusdominus Bbootstrap 4 -->
     <!--  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}"> -->
      <!-- iCheck -->
      <!-- <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}"> -->
      <!-- JQVMap -->
      <!-- <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}"> -->
      <!-- Select2 -->
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <!-- Theme style -->
      <!-- <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}"> -->
      <!-- overlayScrollbars -->
      <!-- <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> -->
      <!-- Daterange picker -->
     <!--  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}"> -->

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />

      <!-- summernote -->
      <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
       <!-- Ekko Lightbox -->
      <link rel="stylesheet" href="{{ asset('plugins/ekko-lightbox/ekko-lightbox.css') }}">

      <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css"> -->


      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
      <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
      <link rel="stylesheet" href="//jonthornton.github.io/jquery-timepicker/jquery.timepicker.css">

      <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui-timepicker-addon.css') }}"> -->
      <link rel="stylesheet" href="{{asset('assets/css/bootstrap-multiselect.css')}}">
      <!-- <link rel="stylesheet" href="{{asset('assets/css/bootstrap-multiselect.min.css')}}"> -->



      <title>Challenge In Motion</title>
      @yield('style')
   </head>
   <body>
      @include('layouts.panels.headerDashboard')
      @yield('content')
      @include('layouts.panels.footerDashboard')
      

      <!-- Optional JavaScript -->
      <!-- jQuery first, then Popper.js, then Bootstrap JS -->
      
      <script src="{{ asset('assets/js/jquery-3.2.1.slim.min.js') }}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!--  <script src="{{ asset('assets/js/jquery.min.js') }}"></script> -->
      <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
      <script src="{{ asset('assets/js/popper.min.js') }}"></script>
      <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
      <script src="{{ asset('assets/js/custom.js') }}"></script>
      
      <!-- Bootstrap 4 -->
      <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <!-- ChartJS -->
      <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
      <!-- Sparkline -->
      <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
      <!-- JQVMap -->
      <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
      <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
      <!-- jQuery Knob Chart -->
      <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
      <!-- daterangepicker -->
      <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script> -->
      <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
      <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
      <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script> -->
      <!-- Tempusdominus Bootstrap 4 -->
      <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
      <!-- Summernote -->
      <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
      <!-- overlayScrollbars -->
      <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
      <!-- DataTables -->
      <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
      <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <!-- InputMask -->
      
      <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
      <!-- AdminLTE App -->
      <script src="{{ asset('dist/js/adminlte.js') }}"></script>
      <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
      <!-- <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script> -->
      <!-- AdminLTE for demo purposes -->
      <script src="{{ asset('dist/js/demo.js') }}"></script>
      <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <!-- Ekko Lightbox -->
      <script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
      <!-- Bootstrap Switch -->
      <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
  
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
      
      
      <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script> -->

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

       <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
      
     
      
      <!-- <script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>

       <script src="{{ asset('assets/js/jquery-ui-timepicker-addon.js') }}"></script>

       <script src="{{ asset('assets/js/jquery-ui-sliderAccess.js') }}"></script> -->

      <!-- Updated JavaScript url -->
      <!-- <script src="//jonthornton.github.io/jquery-timepicker/jquery.timepicker.js"></script> -->

      <script type="text/javascript">
         $(document).ready(function() {

          /*$('#startDateTime').datepicker({
            format: 'dd/mm/yyyy HH:mm:ss',
            //endDate: '-18y',
            onDraw: function onDraw() {
              
              $('.datepicker-container').find('.datepicker-select').addClass('browser-default');
              $(".datepicker-container .select-dropdown.dropdown-trigger").remove();
            }
          });*/

          $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
           "order": []
          });
          //Date range picker
          $('#reservationdate').datetimepicker({
            format: 'MM/DD/YYYY HH:mm'
          });
          $('#event_start_date').datetimepicker({
            format: 'MM/DD/YYYY HH:mm'
          });
          $('#event_end_date').datetimepicker({
            format: 'MM/DD/YYYY HH:mm'
          });
          $('#reg_start_date').datetimepicker({
            format: 'MM/DD/YYYY HH:mm'
          });
          $('#reg_end_date').datetimepicker({
            format: 'MM/DD/YYYY HH:mm'
          });
          $('#date_of_birth').datetimepicker({
            format: 'MM/DD/YYYY'
          });
          $('#date_of_birth1').datetimepicker({
            format: 'MM/DD/YYYY'
          });

          $('.datepickeruser').datepicker({
            format: 'dd/mm/yyyy',
            endDate: '-18y',
            onDraw: function onDraw() {
              
              $('.datepicker-container').find('.datepicker-select').addClass('browser-default');
              $(".datepicker-container .select-dropdown.dropdown-trigger").remove();
            }
          });

          /*$('#date_start_time').datetimepicker({
             format:'DD/MM/YYYY HH:mm:ss',
             maxDate: new Date()
          });

          $('#startDateTime').datetimepicker({
             format:'DD/MM/YYYY HH:mm:ss',
             maxDate: new Date()
          });*/

          var today = new Date();

          /*$('.startDate').datepicker({
            format: 'dd/mm/yyyy',
            endDate: new Date(),
            onDraw: function onDraw() {
              
              $('.datepicker-container').find('.datepicker-select').addClass('browser-default');
              $(".datepicker-container .select-dropdown.dropdown-trigger").remove();
            }
          });*/

          //$('.startDateTime').datetimepicker({timeFormat: "hh:mm:ss"});

          $('#startDateTime').datetimepicker({
             format:'DD/MM/YYYY HH:mm:ss',
             maxDate: new Date()
          });
          /*function getFormattedDate(date) {
              var day = date.getDate();
              var month = date.getMonth() + 1;
              var year = date.getFullYear().toString().slice(2);
              return day + '-' + month + '-' + year;
          }*/
          $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });
      </script>
      @yield('script')
   </body>
</html>