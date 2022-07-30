<!doctype html>
<html lang="en">
   <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <?php if (isset($badge) && !empty($badge)): ?>
      <!-- Facebook -->
      <meta property="og:title" content="<?php echo $badge['title']; ?>" />
      <meta property="og:description" content="<?php echo $badge['description']; ?>" />
      <meta property="og:url" content="<?php echo $badge['url']; ?>" />
      <meta property="og:site_name" content="ChallengeInMotion"/>
      <meta property="og:image" content="<?php echo $badge['image']; ?>" />
      <meta property="og:image:width" content="150" />
      <meta property="og:image:height" content="150" />
      <meta property="og:type" content="article" />
      <!-- Twitter -->
      <meta name="twitter:card" content="Summary"/>
      <meta name="twitter:site" content="@<?php echo $badge['site']; ?>"/>
      <meta name="twitter:creator" content="@<?php echo $badge['creator']; ?>"/>
      <meta name="twitter:title" content="<?php echo $badge['title']; ?>"/>
      <meta name="twitter:description" content="<?php echo $badge['description']; ?>"/>
      <meta name="twitter:image:src" content="<?php echo $badge['image']; ?>"/>
      <?php else: ?>
      <!-- Facebook -->
      <meta property="og:url" content="https://tracker.challengeinmotion.com/dashboard" />
      <meta property="og:type" content="badge" />
      <meta property="og:title" content="Badge Title" />
      <meta property="og:description" content="#ChallengeInMotion" />
      <meta property="og:image" content="https://tracker.challengeinmotion.com/assets/images/Header.jpg" />
      <meta property="og:image:width" content="150" />
      <meta property="og:image:height" content="150" />
      <!-- Twitter -->
      <meta name="twitter:card" content="Summary"/>
      <meta name="twitter:site" content="@ChallengeInMotion"/>
      <meta name="twitter:title" content="Badge Title"/>
      <meta name="twitter:description" content="#ChallengeInMotion"/>
      <?php endif; ?>
      <!-- custom CSS -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
      <!-- <link rel="stylesheet" href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css"/> -->
       <link rel="stylesheet" href="{{ asset('css/app.css') }}">

       <link rel="stylesheet" href="{{ asset('assets/css/hover.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />


      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />



      <!-- From MAin -->

      <!-- Font Awesome -->
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />

      <!-- summernote -->
      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
       <!-- Ekko Lightbox -->

      <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css"> -->


      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">




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
