@extends('layouts.frontend')

@section('style')
<style type="text/css">
   #map {
    height: 600px;
    /*width: 1200px;*/
    overflow: hidden;
    float: left;
    border: thin solid #333;
    width: 100% !important;
    }
   #capture {
    height: 400px;
    width: 500px;
    overflow: hidden;
    float: left;
    background-color: #ECECFB;
    border: thin solid #333;
    
    }
    .custom-modal .form-group {
       margin-bottom: 0.8rem;
   }

   #floating-panel {
     /*position: absolute;*/
     top: 10px;
     left: 25%;
     z-index: 5;
     background-color: #fff;
     padding: 5px;
     border: 1px solid #999;
     text-align: center;
     font-family: "Roboto", "sans-serif";
     line-height: 30px;
     padding-left: 10px;
   }

   #floating-panel {
     margin-left: -100px;
   }

   .text-uppercase{
      text-transform: uppercase!important;
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

<div class="banner inner-page-banner ch-details-banner">
      <?php  

      $coordinates = array();
      $innerBanner = '';
      $challengesName = '';
      $challengesCreatedAt = '';
      $category = 'Accumulative';
      $start_point = '';
      $end_point = '';
      $kml = '';
      $kml_type = 'line';
      $kmlButton = '';
      $kmlMap = '';
      $startPoint = array();
      $endPoint = array();
      $markerPoint = array();
      $distance_array = array();
      if($info){
         $innerBanner = @$info['challenges']['challenge_details_page_pic'];
         $challengesName = $info['challenges']['name'];
         $challengesCreatedAt = $info['challenges']['event_start_date']; 
         //print_r($info['challenge_info']);
         if($info['challenge_info'])
         {
            foreach($info['challenge_info'] as $Key=>$value){
               if($value['meta_name'] == 'kml_file'){ 
                  $kmlMa = $value['meta_value'];
                  $kml = storage_path($value['meta_value']);
                  $ext = pathinfo($kml, PATHINFO_EXTENSION);
                  if($ext != 'kml'){
                     $kml = url($value['meta_value']);
                  }elseif($ext == 'kml'){
                     $kmlButton = 1;
                  }
               }
               if($value['meta_name'] == 'kml_type'){
                  $kml_type = $value['meta_value'];
               }
               if($value['meta_name'] == 'category'){
                  $category = $value['meta_value'];
               }
               if($value['meta_name'] == 'start_point'){
                  $start_point = $value['meta_value'];
               }
               if($value['meta_name'] == 'end_point'){
                  $end_point = $value['meta_value'];
               }
            }
         }
         
      }
      $kmlMa = explode('public/', @$kmlMa);
      $kmlMa1 = asset('storage/'.@$kmlMa['1']);
      $ext = pathinfo($kml, PATHINFO_EXTENSION);
      $kmlImg = 'kmlnot';

      if($ext == 'kml'){



      if(file_exists($kml)){

      if($kml_type == 'line'){
      //echo $kml; //die();
      $myXMLData = file_get_contents($kml, true);

      /*$curl_handle=curl_init();
      curl_setopt($curl_handle, CURLOPT_URL, $kml);
      curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
      curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
      $myXMLData = curl_exec($curl_handle); 
      print_r($myXMLData); die();
      curl_close($curl_handle);*/

      $doc   = new DOMDocument();
      $doc->loadXML($myXMLData);
      $placemark = array();
      $coordinates = array();
      $i = 0;
      foreach( $doc->getElementsByTagName( 'Placemark' ) as $place ) { 
         //echo $place->name;
         //$p = $doc->getElementsByTagName('Point');
         //$ls = $doc->getElementsByTagName('LineString');
         
         //echo '<pre>'; print_r($place->previousSibling); echo '</pre>';
         //echo '<pre>'; print_r($ls); echo '</pre>';
          foreach( $doc->getElementsByTagName('coordinates') as $key => $coord ) {  
            //echo $i; echo count((array)$coord->nodeValue); 
            //echo '<pre>'; print_r($coord); echo '</pre>';
            //echo $i .'=='. $key;
            //if($i == $key){
            //if($i == $key){
               //if(count($coordinates) < 2){
                  foreach( explode( ' ', $coord->nodeValue ) as $c ) { 
                        $cord = explode( ',', $c );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = $v;
                                }else{
                                    $val['lng'] = $v;
                                }
                            }

                        }
                        if(!in_array($val, $coordinates)){
                            if($val){
                                $coordinates[] = $val;
                            }
                        }
                        
                  }
               //}
            //}
            /*elseif($i == 3){
                  foreach( explode( ' ', $coord->nodeValue ) as $c ) { 
                        $cord = explode( ',', $c );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = $v;
                                }else{
                                    $val['lng'] = $v;
                                }
                            }

                        }
                        if(!in_array($val, $coordinates)){
                            if($val){
                                $coordinates[] = $val;
                            }
                        }
                        
                  }
            }elseif($i == 4){
            //if(count($coordinates) < 4){
               foreach( explode( ' ', $coord->nodeValue ) as $c ) {
                     $cord = explode( ',', $c );
                     $val = array();
                     foreach($cord as $k => $v){
                         if($v != null && isset($v) && $v != 0){
                             if($k == 1){
                                 $val['lat'] = $v;
                             }else{
                                 $val['lng'] = $v;
                             }
                         }

                     }
                     if(!in_array($val, $coordinates)){
                         if($val){
                             $coordinates[] = $val;
                         }
                     }
                     
               }
            //}

         }*/
     //}
            
            
              $i++;
          }
          
      }

      $coordinates = json_encode($coordinates);
      $coordinates = json_decode($coordinates, true);

      /*echo '<pre>'; print_r($coordinates); echo '</pre>';

      $startPoint = $coordinates['0'];
      $endPoint = $coordinates[count($coordinates)-1];*/

      $c = function($v){
          return array_filter($v) != array();
      };

     /* $first = reset($coordinates);
      $last = end($coordinates);
      $coordinates = array($first, $last);*/

      $coordinates1 = array_filter($coordinates, 'array_filter');
      //echo '<pre>'; print_r($coordinates1); echo '</pre>';

      }elseif($kml_type == 'path'){
         $xml=simplexml_load_file($kml);
         $xml = json_encode($xml);
         $xml = json_decode($xml, true);
         $Folder = @$xml['Document']['Folder'];
         $Placemark = @$xml['Document']['Placemark'];

         if($Folder){

            foreach($Folder as $key=>$val){ //echo '<pre>'; echo $key; print_r($val['Placemark']); echo '</pre>'; echo count($val['Placemark']);
               if($key == 1){
                  $Placemark = $val['Placemark'];
                  foreach($Placemark as $keyPlac=>$valPlac){ 
                  if(count($Placemark) == 2){//echo $keyPlac == count($Placemark)-1 && !$endPoint; print_r($endPoint);
                    if($keyPlac == count($Placemark)-2 && !$startPoint){
                        $Point = @$valPlac['Point']['coordinates'];
                        $cord = explode( ',', @$Point );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }else{
                                    $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }
                            }

                        }
                        if(!in_array($val, $startPoint)){
                            if($val){
                              $startPoint[] = $val;
                            }
                        }      
         
                     }elseif($keyPlac == count($Placemark)-1 && !$endPoint){
                        $Point = @$valPlac['Point']['coordinates']; //print_r($Point);
                        $cord = explode( ',', @$Point );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }else{
                                    $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }
                            }

                        }
                        if(!in_array($val, $endPoint)){
                            if($val){
                              $endPoint[] = $val;
                            }
                        }   
                        
                     }

                  }else{
                     if($keyPlac == 0){
                        $LineString = @$valPlac['LineString']['coordinates']; 
                        $LineString = explode(' ', @$LineString);//print_r($LineString); die(); 
                        foreach($LineString as $key=>$val){
                           if($val){
                              $cord = explode( ',', $val );
                              $val = array();
                              foreach($cord as $k => $v){
                                  if($v != null && isset($v) && $v != 0){
                                      if($k == 1){
                                          $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                      }else{
                                          $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                      }
                                  }

                              }
                              if(!in_array($val, $coordinates)){
                                  if($val){
                                      $coordinates[] = $val;
                                  }
                              }
                           }
                           
                        }
                        

                     }elseif($keyPlac == 1 && !$startPoint){
                        $Point = @$valPlac['Point']['coordinates'];
                        $cord = explode( ',', @$Point );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }else{
                                    $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }
                            }

                        }
                        if(!in_array($val, $startPoint)){
                            if($val){
                              $startPoint[] = $val;
                            }
                        }      
         
                     }elseif($keyPlac == count($Placemark)-1 && !$endPoint){
                        $Point = @$valPlac['Point']['coordinates']; //print_r($Point);
                        $cord = explode( ',', @$Point );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }else{
                                    $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }
                            }

                        }
                        if(!in_array($val, $endPoint)){
                            if($val){
                              $endPoint[] = $val;
                            }
                        }   
                        
                     }else{
                        $Point = @$valPlac['Point']['coordinates'];
                        $cord = explode( ',', @$Point );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }else{
                                    $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }
                            }

                        }
                        if(!in_array($val, $markerPoint)){
                            if($val){
                              $markerPoint[] = $val;
                            }
                        }   
                     }
                    }
                     
                  }
               }elseif($key == 0){
                  $Placemark = $val['Placemark'];
                  foreach($Placemark as $keyPlac=>$valPlac){
                     if($keyPlac == 0){
                            $Point = @$valPlac['Point']['coordinates'];
                            $LineString = @$valPlac['LineString']['coordinates'];

                            $pointLine = '';
                            if($Point){
                                $pointLine = $Point;
                            }else{
                                $pointLine = $LineString;
                            }
                            $cord = explode( ' ', @$pointLine );//print_r($cord); //die(); 
                            $val = array();
                            foreach($cord as $key=>$val){ //print($val);
                               if($val){
                                  $cord1 = explode( ',', $val );
                                  $val1 = array();
                                  foreach($cord1 as $k => $v){
                                      if($v != null && isset($v) && $v != 0){
                                          if($k == 1){
                                              $val1['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                          }else{
                                              $val1['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                          }
                                      }

                                  }  //echo '<pre>'; print_r($val1); echo '</pre>'; 
                                  if(!in_array($val1, $coordinates)){
                                      if($val1){
                                          $coordinates[] = $val1;
                                      }
                                  }
                               }
                               
                            }
                            

                         /*}elseif($keyPlac == 1 && !$startPoint){
                            $Point = @$valPlac['Point']['coordinates'];
                            $cord = explode( ',', @$Point );
                            $val = array();
                            foreach($cord as $k => $v){
                                if($v != null && isset($v) && $v != 0){
                                    if($k == 1){
                                        $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                    }else{
                                        $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                    }
                                }

                            }
                            if(!in_array($val, $startPoint)){
                                if($val){
                                  //$startPoint[] = $val;
                                }
                            }      
             */
                         }elseif($keyPlac == count($Placemark)-1 && !$endPoint){
                            $Point = @$valPlac['Point']['coordinates'];
                            $cord = explode( ',', @$Point );
                            $val = array();
                            foreach($cord as $k => $v){
                                if($v != null && isset($v) && $v != 0){
                                    if($k == 1){
                                        $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                    }else{
                                        $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                    }
                                }

                            }
                            if(!in_array($val, $endPoint)){
                                if($val){
                                  //$endPoint[] = $val;
                                }
                            }   
                            
                         }else{
                            $Point = @$valPlac['Point']['coordinates'];
                            $cord = explode( ',', @$Point );
                            $val = array();
                            foreach($cord as $k => $v){
                                if($v != null && isset($v) && $v != 0){
                                    if($k == 1){
                                        $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                    }else{
                                        $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                    }
                                }

                            }
                            if(!in_array($val, $markerPoint)){
                                if($val){
                                  $markerPoint[] = $val;
                                }
                            }   
                         }
                  //}  
                  }
               }
               
            }
            
         }elseif(@$Placemark){

            foreach($Placemark as $keyPlac=>$valPlac){
                     if($keyPlac == 0){
                        $LineString = @$valPlac['LineString']['coordinates']; 
                        $LineString = explode(' ', $LineString);//print_r($LineString); die(); 
                        foreach($LineString as $key=>$val){
                           if($val){
                              $cord = explode( ',', $val );
                              $val = array();
                              foreach($cord as $k => $v){
                                  if($v != null && isset($v) && $v != 0){
                                      if($k == 1){
                                          $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                      }else{
                                          $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                      }
                                  }

                              }
                              if(!in_array($val, $coordinates)){
                                  if($val){
                                      $coordinates[] = $val;
                                  }
                              }
                           }
                           
                        }
                        

                     }elseif($keyPlac == count($Placemark)-2 && !$startPoint){
                        $Point = @$valPlac['Point']['coordinates'];
                        $cord = explode( ',', $Point );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }else{
                                    $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }
                            }

                        }
                        if(!in_array($val, $startPoint)){
                            if($val){
                              $startPoint[] = $val;
                            }
                        }      
         
                     }elseif($keyPlac == count($Placemark)-1 && !$endPoint){
                        $Point = @$valPlac['Point']['coordinates'];
                        $cord = explode( ',', $Point );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }else{
                                    $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }
                            }

                        }
                        if(!in_array($val, $endPoint)){
                            if($val){
                              $endPoint[] = $val;
                            }
                        }   
                        
                     }else{
                        $Point = @$valPlac['Point']['coordinates'];
                        $cord = explode( ',', $Point );
                        $val = array();
                        foreach($cord as $k => $v){
                            if($v != null && isset($v) && $v != 0){
                                if($k == 1){
                                    $val['lat'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }else{
                                    $val['lng'] = preg_replace('/[ \t]+/', ' ', preg_replace('/[\r\n]+/', "", $v));
                                }
                            }

                        }
                        if(!in_array($val, $markerPoint)){
                            if($val){
                              $markerPoint[] = $val;
                            }
                        }   
                     }
                     
                  }
            
         }

         }
         /*echo "<pre>";
         $Placemark = @$xml['Document'];
         print_r($Placemark); die();*/

      }
      /*echo "<pre>";
         print_r($markerPoint);
         echo "</pre>";*/
         /*die();*/
      $distance_array = $challengeLogList;

      }else{
         

         if($ext == 'jpeg' || $ext == 'png' || $ext == 'jpg'){
            $kmlImg = $kml;

         }
         
      }
      
      //echo $kmlImg;
         
      ?>
      <div class="bg_img inner-banner" style="background-image: url('<?= @$innerBanner; ?>');"></div>
      <div class="banner-container">
         <div class="banner-caption">
            <h1 style="<?= @$innerBanner ? '' : 'color: #000;'; ?>">{{ $challengesName }}
            </h1>
            <div class="tag-banner-wrp">
               <div class="tag-brdr" style="<?= @$innerBanner ? 'color: #fff; border-color: #fff;' : ''; ?>">{{ucfirst($category)}}</div>
            </div>
         </div>
      </div>
   </div>
   <section class="map-sec aftrlgn sec-spacing">
      <div class="container">
         <div class="row">
           
            <div class="col-md-12">
               <div class="challenge-main-wrp aftr-login-map">
                  <figure>
                     @if($kmlImg != 'kmlnot')
                        <img class="mw-100" src="{{ asset($kmlImg) }}" alt="">
                     @else
                     <!-- <div id="floating-panel">
                        <input type="button" value="Toggle Street View" id="toggle" />
                      </div> -->
                        <div class="mw-100" id="map"></div>

                        
                     @endif

                  </figure>
                  
                  <div class="location-mark justify-content-between mt-4">
                     <div style="float: left;">Start Point - <strong class="location"> {{ $start_point ? $start_point : 'N/A' }} </strong></div>
                     <div style="float: right;">End Point - <strong class="location"> {{ $end_point ? $end_point : 'N/A' }}</strong></div>
                  </div>

                  

                  <!-- <div class="activity-details-wrp">
                    <div class="activity-head">
                        <h3>Activity Detail</h3>
                        <span>Donec commodo posuere.</span>
                    </div>
                    <div class="acitivity-info-bx">
                       <div class="form-group">
                           <label for="">Activity Type</label>
                           <input type="text" placeholder="Running" readonly />
                       </div>
                       <div class="form-group">
                           <label for="">Total Distance</label>
                           <input type="text" placeholder="130" readonly />
                      </div>
                      <div class="form-group">
                        <label for="">Completion %</label>
                        <div class="circle-progress mx-auto" data-value='23'>
                           <span class="progress-left">
                                         <span class="progress-bar border-danger"></span>
                           </span>
                           <span class="progress-right">
                                         <span class="progress-bar border-danger"></span>
                           </span>
                           <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                             <div class="h2">23 %</div>
                           </div>
                         </div>
                   </div>
                    </div>
                  </div> -->
               </div>

               

            </div>
            @if($kmlButton)
               <div class="col-md-7" <?= $kmlButton; ?>>
                  <div class="location-mark justify-content-between mt-4" style="float: right;">
                     <div style="text-align:center;"><a href="#" id="enable-streetview" class="btn btn-success text-uppercase">Show me on Street View</a></div>
                  </div>
               </div>
           
            @endif
         </div>

      </div>


   </section>

<section class="challenge-milestones-wrp ">
   <div class="container">
      <div class="row">
         <div class="col-sm-12">
            <h5 class="sub-heading mb-30">
               Challenge Milestones
            </h5>
         </div>
      </div>
      <div class="row">
         <div class="col-sm-12">
            <div class="challenge-milestone-row">

               <?php
                  $cp = false;
                  $cpAt = false;
                  $milestone = 0;
                  $milestoneOnSpecificDate = 0;
                  foreach($challenge_milestones as $key => $val){

                     if($val){
                        $specific_date_checkbox = $val['specific_date_checkbox'];
                        $specific_date = $val['specific_date'];
                     }else{
                        $specific_date_checkbox = '';
                        $specific_date = '';
                     }

                     $orgDate = $specific_date;

                     $newDate = '';
                     if($orgDate){
                        $tempDate = explode(' ', $orgDate);
                        $tempDate = explode('-', $tempDate['0']);
                        $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
                        if($stat){

                           $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $orgDate);
                           //$newDate = date("m/d/Y H:m", strtotime($newDate));
                        }
                     }

                     if($specific_date_checkbox && $newDate){
                        $milestoneOnSpecificDate = $val['milestone_distance'];
                        $completePercentageAtSpecificDate = $Challenge_infos->challengeMilestoneCompleteAtSpecificDate($val['challenge_id'], $user_id, $milestoneOnSpecificDate, $newDate, $timezone, $category);
                     }else{
                        $milestone = $milestone + $val['milestone_distance'];
                        $completePercentage = $Challenge_infos->challengeMilestoneComplete($val['challenge_id'], $user_id, $milestone, $category);
                     }
                     
                     /*if($milestoneOnSpecificDate && $specific_date_checkbox && $newDate){
                        //$completePercentage = $Challenge_infos->challengeMilestoneComplete($val['challenge_id'], $user_id, $milestoneOnSpecificDate);
                        
                     }else{ echo $milestone;
                        
                     }*/
                     
                     
                     
                     $sty = '';
                     $gauge_percentage = $completePercentage;

                     $orgDate = $val['specific_date'];

                     $newDate = '';
                     if($orgDate){
                     $tempDate = explode(' ', $orgDate);
                       $tempDate = explode('-', $tempDate['0']);
                       $stat = checkdate($tempDate[1], $tempDate[2], $tempDate[0]);
                       if($stat){

                        $newDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $orgDate, 'UTC')->setTimezone($timezone);
                        $newDate = date('Y-m-d H:i:s', strtotime($newDate));
                       }
                     }

                     //echo $newDate;
                     //echo $completePercentage .'!='. 100;
                     //echo $completePercentageAtSpecificDate .'!='. 100;
               ?>
                  

                  @if(!@$val['specific_date_checkbox'] && !$newDate)  
                     @if($val['milestone_type'] != 'monthly_milestone')
                        @if($completePercentage < 100)
                                            
                        
                        <?php $sty = 'style="opacity:0.3;"color:black;'; //$gauge_percentage = $completePercentage; ?>
                        
                        @endif
                     @endif
                  @endif

                  @if(@$val['specific_date_checkbox'] && $newDate)
                        @if($completePercentageAtSpecificDate < 100)
                           <?php $sty = 'style="opacity:0.3;"';  ?>
                        @endif
                     
                  @endif
               
                  <div class="milestone-box" data-toggle="modal" data-target="#myModal26{{ $val['id'] }}" <?= $sty; ?>>
                     <figure class="milestone-img-wrp">
                        @if($val['milestone_pic'])
                           <img class="milestone_main__image" src="{{ url($val['milestone_pic']) }}" alt="">
                        @else
                           <img class="milestone_main__image"  src="{{asset('assets/images/fff.jpg')}}" alt="">
                        @endif
                        @if($completePercentage == 100)
                           @if($val['milestone_type'] != 'monthly_milestone')
                              @if(!@$val['specific_date_checkbox'] || !$newDate)  
                                                        
                              <img class="milestone_main__tick" src="{{ asset('assets/images/Blue tickk.png') }}" width="50" height="50" ss>
                              <!-- <div class="edit-btn" id="OpenImgUpload"><span class="small material-icons">check_box</span></div> -->
                              <?php $cp = true; ?>
                              @endif
                           @endif
                        @endif

                        @if(@$val['specific_date_checkbox'] && @$newDate)
                        <?php $gauge_percentage = $completePercentageAtSpecificDate; ?>
                             
                             @if($completePercentageAtSpecificDate == 100)

                                 <img class="milestone_main__tick" src="{{ asset('assets/images/Blue tickk.png') }}" width="50" height="50" pp>
                                 <?php $cpAt = true; ?>
                             @endif
                        @endif
                     </figure>
                     <div class="milestone-info-box d-flex justify-content-between">
                        <div class="milestone-name-bx">
                           <h3>{{ $val['milestone_name'] }}</h3>

                        </div>

                        @if($val['milestone_type'] != 'monthly_milestone')
                        <div class="milestone-distance-info">

                           <span>{{ $val['milestone_distance'] }} mi</span>
                        </div>
                        @else
                           <?php
                           if($category == 'individual'){
                              $monthlyMiles = $Challenge_logs->monthlyMilesLog($val['challenge_id'], $user_id, $val['start_date'], $val['end_date'], $timezone);
                           }else{
                              $monthlyMiles = $Challenge_logs->monthlyMilesLogForAccumulative($val['challenge_id'], $user_id, $val['start_date'], $val['end_date'], $timezone);
                           }
                           ?>
                           <div class="milestone-distance-info">
                              <span>{{ round($monthlyMiles, 2) }} mi</span>
                           </div>
                        @endif
                     </div>

                     <div class="milestone-distance-info">
                        <p>{{mb_strimwidth($val['milestone_info'], 0, 200, '...')}}</p>
                     </div>
                     @if($val['milestone_type'] != 'monthly_milestone')
                     <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?= $gauge_percentage; ?>%" aria-valuenow="<?= $gauge_percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                     @endif
                     <?php 
                     $end_date = '';
                      if($val['end_date']){//echo $val['end_date']; echo $admintimezone;
                         $end_date = \Carbon\Carbon::parse($val['end_date'], 'UTC')->setTimezone($admintimezone); 
                      } 
                        
                     ?>
                     @if($end_date && $end_date < $now)
                        <?php 
                           
                           $getMilesLogSubmitStatus = $Monthly_miles_log_submits->getMilesLogSubmitStatus($val['id'], $user_id);
                           
                         ?>
                        @if(!$getMilesLogSubmitStatus)
                        <form action="{{route('frontend.sendMonthlyLogs')}}" method="POST" onsubmit="disableButton({{$val['id']}})" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">   
                        <input type="hidden" name="challenge_id" value="{{$val['challenge_id']}}">
                        <input type="hidden" name="milestone_id" value="{{$val['id']}}">
                        <input type="hidden" name="milestone_name" value="{{$val['milestone_name']}}">
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <input type="hidden" name="milestone_distance" value="{{$val['milestone_distance']}}">
                        <input type="hidden" name="start_date" value="{{$val['start_date']}}">
                        <input type="hidden" name="end_date" value="{{$val['end_date']}}">
                        <input type="submit" value="Submit" class="btn btn-success btn_upp" id="{{$val['id']}}">
                        </form>
                         @else
                           @if(session('message'))
                              <p><strong>Congratulations!</strong> {{session('message')}} </p>
                                     
                           @endif
                         @endif
                     @endif
                  </div>

                  @if($cp || $cpAt)
                  <div class="modal fade achievementModalNew" id="myModal26{{ $val['id'] }}" role="dialog" tabindex="-1" aria-labelledby="myModal26Label" aria-hidden="true" style="left: 10%;">
                     <div class="modal-dialog modal-lg">
                        <div class="modal-content" >
                           <div class="modal-header d-block" style="padding: 0; padding-left: 16px;">
                              <div class="modal-top">
                                 <h5 class="modal-title" id="badge-modal-title">{{ auth()->user()->name }}</h5>
                                 <p style="font-size: 13px;">Share Your Achievement with friends and make sure to tag us for chances to win prizes  <strong>#ChallengeinMotion</strong></p>
                              </div>
                              <button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
                                 <i class="fa fa-times" aria-hidden="true"></i>
                              </button>
                           </div>
                           <div class="modal-body" id="badge-modal-body">
                              <figure class="milestone-img-wrp">
                                 @if($val['milestone_pic'])
                                    <img class="milestone_main__image" src="{{ url($val['milestone_pic']) }}" alt="">
                                 @else
                                    <img class="milestone_main__image"  src="{{asset('assets/images/fff.jpg')}}" alt="">
                                 @endif
                                 @php
                                    $profile_pic = asset('assets/images/dash-user.png');
                                    if(auth()->user()->profile_pic) {
                                       $profile_pic = asset(auth()->user()->profile_pic);
                                    }
                                 @endphp
                                 <img class="milestone_main__profile" src="{{ $profile_pic }}" alt="">
                              </figure>
                              <?php 
                              if($specific_date_checkbox && $newDate){
                                 $milestoneOnSpecificDate = $val['milestone_distance'];
                                 $getTotalNumberofLogs = (new \App\Models\User_badges())->getTotalNumberofLogs($user_id, $val['challenge_id'], $val['start_date'], $val['end_date'], $milestoneOnSpecificDate, $val['id']); 
                              }else{
                                 $getTotalNumberofLogs = (new \App\Models\User_badges())->getTotalNumberofLogs($user_id, $val['challenge_id'], $val['start_date'], $val['end_date'], $milestone, $val['id']); 
                              }
                              
                              if(!empty($getTotalNumberofLogs)){ //print_r($getTotalNumberofLogs); die();
                                 $getTotalNumberofLogs = json_decode($getTotalNumberofLogs, true);
                                 $enddate = $getTotalNumberofLogs['enddate'];
                                 $count = $getTotalNumberofLogs['count'];
                              }else{
                                 $enddate = '';
                                 $count = 0;
                              }
                              

                              ?>
                              <div class="row mt-4">
                                 <div class="col-md-6">
                                    <p>Start Date: {{ \Carbon\Carbon::parse($challengesCreatedAt)->format('M d, Y')}}</p>
                                    <p>End Date: {{ \Carbon\Carbon::parse($enddate)->format('M d, Y')}}</p>
                                 </div>

                                 <div class="col-md-6">
                                    @if($val['milestone_type'] != 'monthly_milestone')
                                    <?php
                                       $distanceLogged = $val['milestone_distance'];
                                    ?>
                                    @else
                                       <?php
                                       if($category == 'individual'){
                                          $monthlyMiles = $Challenge_logs->monthlyMilesLog($val['challenge_id'], $user_id, $val['start_date'], $val['end_date'], $timezone);
                                       }else{
                                          $monthlyMiles = $Challenge_logs->monthlyMilesLogForAccumulative($val['challenge_id'], $user_id, $val['start_date'], $val['end_date'], $timezone);
                                       }
                                       ?>
                                    @endif

                                    <p>Distance Logged: {{ $distanceLogged }} mi</p>

                                    <?php //if((new \App\Models\User_badges())->getTotalDistanceTravel($user_id, $val['challenge_id']) > $distanceLogged) ?>
                                    <p>Number of logs: {{ $count }}</p>
                                    <?php //}else{?>
                                    <!-- <p>Number of logs : 0</p> -->
                                    <?php //}?>
                                 </div>
                              </div>

                              <div class="milestone-info-box d-flex justify-content-between">
                                 <div class="milestone-name-bx">
                                    <h3 id="milestone_name_{{ $val['id'] }}">{{ $val['milestone_name'] }}</h3>

                                    <!-- <p style="margin-top: 1em;"><a href="{{ url($val['milestone_pic']) }}" download>Download Badge</a></p> -->
                                    <!-- <p style="margin-top: 1em;"><a href="{{ url($val['milestone_pic']) }}" download>Download Badge</a></p> -->
                                 </div>
                              </div>

                              <div id="social-links">
                                 <ul class="milestone-info-box d-flex socialIconModal">
                                    <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(route('test.view', $val['id'])); ?>" class="social-button" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
                                    <li><a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($val['milestone_name']); ?>&amp;url=<?php echo urlencode(route('test.view', $val['id'])); ?>" class="social-button" target="_blank"><i class="fa fa-twitter-square" aria-hidden="true"></i>
</a></li>  
                                    <li><a href="{{ url($val['milestone_pic']) }}" download><i class="fa fa-cloud-download" aria-hidden="true"></i>
</a></li>
                                    
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endif
                  
               <?php

                  $cp = false;
                  $cpAt = false;

                  }
               ?>
               
            </div>
         </div>
      </div>
   </div>
</section>

<!--<section class="cupid-product-wrp sec-spacing">
   <div class="container">
      <div class="row">
         <div class="col-sm-12">
            <h5 class="sub-heading mb-30">
               Challenge Badges
            </h5>
            <div class="content-blog">
               <p>
                  Et pharetra pharetra massa massa hac habitasse platea dictumst vestibulum.
               </p>
            </div>
         </div>
         <div class="col-sm-12">
            <div class="cupid-row four-box">

               <?php
                  foreach($challenge_milestone_badge as $key => $val){
                     
               ?>
                  

                  <div class="cupid-product-bx">
                     <figure class="cupid-img-wrp">
                        <img src="{{ url($val['milestone_pic']) }}" alt="">
                     </figure>
                     <figcaption>{{ $val['milestone_name'] }}</figcaption>
                  </div>
               <?php

                  }
               ?>
                <div class="cupid-product-bx">
                  <figure class="cupid-img-wrp">
                     <img src="{{ asset('assets/images/pd-img-1.png') }}" alt="">
                  </figure>
                  <figcaption>Cupid’s 12 Mile Rose Pins.</figcaption>
               </div>
               <div class="cupid-product-bx">
                  <figure class="cupid-img-wrp">
                     <img src="{{ asset('assets/images/pd-img-1.png') }}" alt="">
                  </figure>
                  <figcaption>Cupid’s 12 Roses T-Shirt</figcaption>
               </div>
               <div class="cupid-product-bx">
                  <figure class="cupid-img-wrp">
                     <img src="{{ asset('assets/images/pd-img-1.png') }}" alt="">
                  </figure>
                  <figcaption>Cupid’s 12 Roses Wrist Band</figcaption>
               </div>
               <div class="cupid-product-bx">
                  <figure class="cupid-img-wrp">
                     <img src="{{ asset('assets/images/pd-img-1.png') }}" alt="">
                  </figure>
                  <figcaption>Cupid’s 12 Roses Wrist Band</figcaption>
               </div> 
               
            </div>
         </div>
      </div>
   </div>
</section>-->
<section class="milestome-complete-wrp sec-spacing">
   <div class="container">
      <div class="row">
         <div class="col-lg-12 col-md-5 col-sm-12">
            <h5 class="sub-heading mb-30" style="text-align: center;">
               Miles Until Complete
            </h5>
            <div class="miles-unit-complet-bx activity-mile-status">
                <div class="wrapper">
                    <div class="card">
                      <div class="circle">
                        <div class="bar"></div>
                        <div class="box"><span></span></div>
                      </div>
                      
                    </div>
                </div>
               <!-- <div class="circle-progress-new">
                <img src="{{ asset('assets/images/ps.png') }}" alt="">
               </div> -->
                <!-- <div class="circle-progress mx-auto" data-value='{{$coverageDistance}}' style="width: 14%; height: 240px;">
                    
                  <span class="progress-left">
                                <span class="progress-bar border-danger"></span>
                  </span>
                  <span class="progress-right">
                                <span class="progress-bar border-danger"></span>
                  </span>
                  <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                     @if($challengeDistance)
                     <div class="h2" style="line-height: 53px;">{{round($coverageDistance, 2) >= $challengeDistance ? $challengeDistance : round($coverageDistance, 2)}} mi <br>of <br> {{$challengeDistance}} mi</div>
                     @else
                         <div class="h2" style="line-height: 53px;">{{$coverageDistance}} mi</div>
                     @endif
                  </div>
                </div> -->
            </div>
         </div>
         <?php
         //''echo "==".$challengeDistance;
             $progreeData = '';
             if($challengeDistance){
              $progreeData = round($coverageDistance, 2) >= $challengeDistance ? $challengeDistance.' mi' : round($coverageDistance, 2).' mi \n of \n'. $challengeDistance .' mi';
             }else{
                 $progreeData = $coverageDistance.' mi';
             
             }

             $valDist = $challengeDistanceforUser; //echo $challengeDistanceforUser .'>'. $coverageDistance;
             if($challengeDistanceforUser > $challengeDistance){
                $valDist = $challengeDistance;
             }

             $accumulativeDistance = $accuChallengeDistanceforUser; //echo $accuChallengeDistanceforUser .'>'. $challengeDistance;
             if($accuChallengeDistanceforUser > $challengeDistance){
                $accumulativeDistance = $challengeDistance;
             }
         ?>
         <!-- <div class="col-lg-6 col-md-7 col-sm-12">
            <div class="add-log-options">
               <h5 class="sub-heading mb-30">
                  Add Log
               </h5>
               <div class="content-blog">
                  
               </div>
               <div class="addlog-tab">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                     <li class="nav-item">
                       <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Device</a>
                     </li>
                     <li class="nav-item">
                       <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Manual</a>
                     </li>
                     
                   </ul>
                   <div class="tab-content" id="myTabContent">
                     <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="device-tab grey-bg text-center">
                           <h2 class="sub-heading mb-30"> Device</h2>
                           <div class="content-blog">
                              <p>
                                 Donec eget risus quis nisl porttitor ultricies. Phasellus gravida congue bibendum. Integer rhoncus interdum augue non euismod. Praesent metus eros, vestibulum at convallis at, tincidunt at quam. Vivamus id lacinia lorem. Curabitur eget finibus justo, in aliquam neque. Donec erat ligula, mollis id sollicitudin sed, placerat quis nunc. Donec et lectus lorem. Nulla fermentum urna et volutpat laoreet. 
                              </p>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="manual-add-tab">
                           <form action="{{ route('frontend.add_challenge_log') }}" method="post" class="log-add-form" id="log-add-form">
                            {{ csrf_field() }}
                           <div class="activity-details-wrp">
                              <div class="activity-head">
                                  <h3>Input Log</h3>
                                  <span>Donec commodo posuere.</span>
                              </div>
                              <div class="acitivity-info-bx">
                                 <div class="form-group">
                                     <label for="">Activity</label>
                                     <div class="custom-slect-bx">
                                       <input type="hidden" name="challenges[]" value="{{ $id }}">
                                       <input type="hidden" name="challengeDetails" value="{{ route('frontend.challenge_details', $id) }}">
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
                                    <input type="datetime-local" name="startDateTime" id="date_start_timeAdd" class="form-control datetimepicker-input" value="">
                                    
            

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
                                    <input type="number" min="0" step=0.01 class="form-control" name="calories">
                                 </div>
                                 <div class="form-group">
                                    <div class="save-add-log">
                                       <button class="theme-btn green-btn">Save</button>
                                       
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
               </div>
            </div> -->
         </div>
      </div>
   </div>
</section>

<section class="milestome-complete-wrp sec-spacing">
   <div class="container">
      <div class="row">
         <div class="page-body">
            <div class="calander-container p-0-30">
               <div id='calendar'></div>
            </div>
         </div>
         
      </div>
   </div>
</section>

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
                           <input type="text" id="viewChallenges" value="" readonly>
                        </div>
                     </div>
                     <div class="form-group">
                         <label for="">Activity*</label>
                         <div class="custom-slect-bx">
                           <input type="hidden" name="challengeDetails" value="{{ route('frontend.challenge_details', $id) }}">
                           <select name="activity" id="viewActivity" class="form-control" disabled="disabled">
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
                              <input type='datetime-local' name="startDateTime" id="date_start_time"  class="form-control" max="{{\Carbon\Carbon::now()->toDateTimeString()}}"  value="" readonly/>
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
                        <input type="number" id="distance" min="0" step=0.01 name="distance" readonly>
                     </div>
                     <div class="form-group">
                       <div class="time-ingo-row">
                        <div class="info-time">
                           <label for="">Hours</label>
                           <input type="number" id="hour" min="0" max="24" name="hour" readonly>
                        </div>
                        <div class="info-time">
                           <label for="">Minutes</label>
                           <input type="number" min="0" id="minute" max="59" name="minute" readonly>
                        </div>
                        <div class="info-time">
                           <label for="">Seconds</label>
                           <input type="number" min="0" max="59" id="second" name="second" readonly>
                        </div>
                       </div>
                       
                     </div>

                     <div class="form-group">
                        <label for="">Calories</label>
                        <input type="number" step=0.01 min="0" id="viewCalories" class="form-control" name="calories" readonly>
                     </div>
                     
                           <!-- <div class="form-group">
                              <div class="save-add-log1">
                                 <button class="theme-btn green-btn">Save</button>
                                 
                              </div>
                           </div> -->
                        
                     
                    </div>
                    
                 </div>
                  </div>
                </div>
          </form>

          
            <!-- <form action="{{ route('frontend.delete_challenge_log') }}" method="post" class="login-form" id="delete-challenge-log">
               {{ csrf_field() }}
               {{ method_field('delete') }}
               <input type="hidden" name="challenge_id" id="deleteChallenge_id" value="">
               <div class="form-group">
                  <div class="save-add-log1" style="text-align: center;">
                     <input type="hidden" name="challengeDetails" value="{{ route('frontend.challenge_details', $id) }}">
                     <button class="theme-btn green-btn" id="delete-challenge-log-button" style="width: 75%;">Delete</button>
                  </div>
               </div>
            </form> -->
         
          
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

          
            <!-- <form action="{{ route('frontend.delete_challenge_log') }}" method="post" class="login-form" id="delete-strava-challenge-log">
               {{ csrf_field() }}
               {{ method_field('delete') }}
               <input type="hidden" name="challenge_id" id="deleteStravaChallenge_id" value="">
               <div class="form-group">
                  <div class="save-add-log1" style="text-align: center;">
                     <button class="theme-btn green-btn" id="delete-challenge-log-button" style="width: 75%;">Delete</button>
                  </div>
               </div>
            </form> -->
         
          
       </div>
       
     </div>
   </div>
</div>


@endsection
<!-- https://maps.googleapis.com/maps/api/js?key=AIzaSyB41DRUbKWJHPxaFjMAwdrzWzbVKartNGg&callback=initMap&v=weekly&channel=2 -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAESvkKxPXKAxJNRdp2k1L17Fy-L-Y91zI&libraries=geometry&v=weekly&channel=2"></script>
 <script type="text/javascript" src="{{ asset('assets/js/distance_calculations.js') }}"></script>

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>

<?php 
$count1 = 0;
if($challengeDistance){
    $count1 = $coverageDistance / $challengeDistance;
}

$count2 = $count1 * 100;
$count = number_format($count2, 1); 

$showCount = 0;
if($count){
    $showCount = $count/100;
}
?>

<script>
   var per = "<?php echo $showCount; ?>";
   var val = "<?php echo $progreeData; ?>"; //console.log(val);
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
        $(this).parent().find("span").text(val);
      });
      $(".js .bar").circleProgress({
        value: 0.70
      });
      
    </script>

<script>

   function disableButton(id) {
        var btn = document.getElementById(id);
        btn.disabled = true;
        btn.innerText = 'Sending...'
    }

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
   //document.getElementById("date_start_timeAdd").setAttribute("max", today);

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



$(document).ready(function () {
   var SITEURL = "{{ url('/') }}";
  
$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var data = `<?= $logs; ?>`;
data = JSON.parse(data);
  
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
                    viewRender: function(view, element) {
                        //note: this is a hack, i don't know why the view title keep showing "undefined" text in it.
                        //probably bugs in jquery fullcalendar
                        $('.fc-center')[0].children[0].innerText = view.title.replace(new RegExp("undefinedundefined", 'g'), "");

                     },
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end, allDay) { 
                        /*var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD hh:mm:ss"); console.log(start);
                        var start1 = start.split(' ');
                        $("#startDateTimeMax").val(start1['0']+'T'+start1['1']);
                        $('#exampleModal').modal();
                        $('#exampleModal').css('opacity', 1);*/
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

                     //console.log(start1);
                     var endTime1 = '';
                     if(endTime){
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
                              $('#strava_date_start_time').val(start1['0']+'T'+start1['1']);
                              $('#stravaDistance').val(distance_travelled);
                              if(endTime1){
                                 $('#stravaHour').val(endTime1['0']);
                                 $('#stravaMinute').val(endTime1['1']);
                                 $('#stravaSecond').val(endTime1['2']);
                              }
                              $('#stravaCaloriee').val(calories);
                            }else{

                               $('#viewModal').modal();
                               $('#viewModal').css('opacity', 1);

                               $('#deleteChallenge_id').val(id);
                               $('#updateChallenge_id').val(id);

                               $('#viewChallenges').val(name);
                               $('#viewActivity option[value='+activity+']').attr('selected', 'selected');
                               $('input[name=startDateTime]').val(start1['0']+'T'+start1['1']);
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

    const jsObject = JSON.parse('<?php echo json_encode($coordinates); ?>'); //console.log(jsObject);
    var distance_array = <?php echo json_encode($distance_array); ?>;
    var startPoint = JSON.parse('<?php echo json_encode($startPoint); ?>');
    var endPoint = JSON.parse('<?php echo json_encode($endPoint); ?>');
    var markerPoint = JSON.parse('<?php echo json_encode($markerPoint); ?>');
    var kmlType = "<?php echo $kml_type; ?>";
    

   var map;
   //var src = 'https://developers.google.com/maps/documentation/javascript/examples/kml/westcampus.kml';
   var src = "<?= $kmlMa1; ?>"

   function initMap() {
     map = new google.maps.Map(document.getElementById('map'), {
       center: new google.maps.LatLng(-19.257753, 146.823688),
       zoom: 20,
       mapTypeId: 'terrain'
     });

     /*var kmlLayer = new google.maps.KmlLayer(src, {
       suppressInfoWindows: true,
       preserveViewport: false,
       map: map
     });*/

     

     var flightPlanCoordinates = jsObject;
        
        var bounds = new google.maps.LatLngBounds();

        var polLine = [];

        for (var i=0; i<flightPlanCoordinates.length; i++) {
            if(flightPlanCoordinates[i]){
                var lat = parseFloat(flightPlanCoordinates[i]['lat']);
                var lng = parseFloat(flightPlanCoordinates[i]['lng']);
                var latlng = {"lng" : lng, "lat": lat}; //console.log(latlng);
                polLine[i] = latlng;
                bounds.extend(latlng);

                var end = flightPlanCoordinates.length-1;
                if(kmlType == 'line'){
                  if(i == 0){
                  
                     createMarkerStart(map, latlng, " Start", '<?= env('MAP_START_POINT', "https://tracker.challengeinmotion.com/assets/images/start.png",); ?>');
                   }else if(i == end){
                     
                     createMarkerEnd(map, latlng, " End", '<?= env('MAP_END_POINT', "https://tracker.challengeinmotion.com/assets/images/finish.png"); ?>');
                   }else{

                     
                     createMarkerMiddle(map, latlng, " Middle", 'https://tracker.challengeinmotion.com/assets/images/middleImg.png');
                   }
                }

                
            }
            
        }

         if(kmlType == 'path'){
             if(startPoint){
               for (var i=0; i<startPoint.length; i++) {
                  if(startPoint[i]){
                     var lat = parseFloat(startPoint[i]['lat']);
                     var lng = parseFloat(startPoint[i]['lng']);
                     var latlng = {"lng" : lng, "lat": lat}; //console.log(latlng);
                     createMarkerStart(map, latlng, " Start", '<?= env('MAP_START_POINT', "https://tracker.challengeinmotion.com/assets/images/start.png",); ?>');
                  }
               }
               
             }

            if(endPoint){
               
               for (var i=0; i<endPoint.length; i++) {
                  if(endPoint[i]){
                     var lat = parseFloat(endPoint[i]['lat']);
                     var lng = parseFloat(endPoint[i]['lng']);
                     var latlng = {"lng" : lng, "lat": lat}; //console.log(latlng);
                     createMarkerEnd(map, latlng, " End", '<?= env('MAP_END_POINT', "https://tracker.challengeinmotion.com/assets/images/finish.png"); ?>');
                  }
               }
                
             }
           console.log(markerPoint);
             if(markerPoint){
               for (var i=0; i<markerPoint.length; i++) {
                  if(markerPoint[i]){
                     var lat = parseFloat(markerPoint[i]['lat']);
                     var lng = parseFloat(markerPoint[i]['lng']);
                     var latlng = {"lng" : lng, "lat": lat}; //console.log(latlng);
                     createMarkerMiddle(map, latlng, " Middle", 'https://tracker.challengeinmotion.com/assets/images/middleImg.png');
                  }
               }
               
             }
         }
          
        map.fitBounds(bounds);

        var flightPath = new google.maps.Polyline({
            path: polLine,
            geodesic: true,
             strokeColor: "#002b49",
             strokeOpacity: 1.0,
             strokeWeight: 8,
        });

        

        //now add marker to each entry by adding the old ones
        var daily_distance_array = distance_array;
        var newDistance = 0;

        var accumulativeDistance = "<?= $accumulativeDistance; ?>"; //console.log(accumulativeDistance);

        var accumulativeCategory = "<?= $category; ?>";
        var accumulativeImage = "<?= $challengeImagesforUser; ?>";

        var actualAtEnd = 0;
        
         if(accumulativeCategory == 'accumulative'){
            var name = "<?= $userName; ?>";
            
            var msg = "<?= $userName; ?>"+"\nYou're at "+accumulativeDistance+" Miles.";
            createMarker(map, flightPath.GetPointAtDistance(1609.344*accumulativeDistance), msg, '<?= env('MAP_USERS_POINT', "https://tracker.challengeinmotion.com/assets/images/users.png"); ?>', name, accumulativeDistance);
         }else{
            for (let i = 0; i < daily_distance_array.length; i++) {
               var distance = daily_distance_array[i]['0'];
               var image = daily_distance_array[i]['1'];
               var name = daily_distance_array[i]['2'];

                var challengeDist = "{{$challengeDistance}}";
               
               var actualDist = distance;
                if(distance >= challengeDist){
                     actualDist = challengeDist;
                }

                actualAtEnd = actualDist;

                //console.log(actualDist);

                

              // console.log(flightPath.GetPointAtDistance(1000*distance));
               var msg = name+"\nYou're at "+actualDist+" Miles.";
               createMarker(map, flightPath.GetPointAtDistance(1609.344*actualDist), msg, '<?= env('MAP_USERS_POINT', "https://tracker.challengeinmotion.com/assets/images/users.png"); ?>', name, actualDist);
            }
         }

         flightPath.setMap(map);


   }



   $(document).ready(function(){
      
      $('#enable-streetview').on('click', function(e){
         e.preventDefault();
         var text = $(this).text();
         if(text == 'Show me on Street View'){ //alert(text);
            $(this).text('Close Street View');
            /*$('#map').css('display', 'none');
            $('#mapStreet').css('display', '');*/

            initMapForStreet();

            

         }

         if(text == 'Close Street View'){ //alert(text);
            $(this).text('Show me on Street View');
            /*$('#map').css('display', '');
            $('#mapStreet').css('display', 'none');*/

            initMap();
         }
      });
   });

   function initMapForStreet() {
     map = new google.maps.Map(document.getElementById('map'), {
       center: new google.maps.LatLng(-19.257753, 146.823688),
       zoom: 10,
       mapTypeId: 'terrain'
     });

     var kmlLayer = new google.maps.KmlLayer(src, {
       suppressInfoWindows: true,
       preserveViewport: false,
       map: map
     });

     var flightPlanCoordinates = jsObject;
        
        var bounds = new google.maps.LatLngBounds();

        var polLine = [];

        for (var i=0; i<flightPlanCoordinates.length; i++) {
            if(flightPlanCoordinates[i]){
                var lat = parseFloat(flightPlanCoordinates[i]['lat']);
                var lng = parseFloat(flightPlanCoordinates[i]['lng']);
                var latlng = {"lng" : lng, "lat": lat}; //console.log(latlng);
                polLine[i] = latlng;
                bounds.extend(latlng);
            }
            
        }
          
        map.fitBounds(bounds);

        var flightPath = new google.maps.Polyline({
            path: polLine,
            geodesic: true,
             strokeColor: "#1a68fa",
             strokeOpacity: 1.0,
             strokeWeight: 8,
        });

        

        //now add marker to each entry by adding the old ones
           var distance = "<?= $valDist; ?>";

            //console.log(flightPath.GetPointAtDistance(1000*distance));

            //const fenway = { lat: 25.90461986793459, lng: 84.89271994062771 };

            var panoOptions = {
              position: flightPath.GetPointAtDistance(1609.344*distance),
              /*addressControlOptions: {
                  position: google.maps.ControlPosition.LEFT_TOP
              },*/
              /*linksControl: false,
               panControl: false,*/
               pov: {
                  heading: 5,
                  pitch: -85
               },
               zoomControlOptions: {
                 style: google.maps.ControlPosition.LEFT_TOP
               },
               enableCloseButton: false
            }
            const panorama = new google.maps.StreetViewPanorama(
                document.getElementById("map"), 
                panoOptions
              );

            map.setStreetView(panorama);
            
            //showStreetView(map, flightPath.GetPointAtDistance(1000*distance));

            //now add marker to each entry by adding the old ones
            var daily_distance_array = distance_array;
            var newDistance = 0;
           
            
               //var image = "<?= $challengeImagesforUser; ?>";
               var image = '<?= env('MAP_USERS_POINT', "https://tracker.challengeinmotion.com/assets/images/users.png"); ?>';

               //console.log(flightPath.GetPointAtDistance(1000*distance));
               
               //createMarker(map, flightPath.GetPointAtDistance(1000*distance),distance+" km", image);

               var icon = {
                   url: image, // url
                   scaledSize: new google.maps.Size(50, 50), // scaled size
                   origin: new google.maps.Point(0, 0),
                   // The anchor for this image is the base of the flagpole at (0, 32).
                   anchor: new google.maps.Point(36, 36),
               };

               var msg = "<?= $userName; ?>"+"\nYou're at "+distance+" Miles.";

               var marker = new google.maps.Marker({
                      position:flightPath.GetPointAtDistance(1609.344*distance-50),
                      map:panorama,
                      title: msg,
                      map: panorama, // your code doesn't have a 'map' variable
                      icon: icon
               });
                    
               var heading = google.maps.geometry.spherical.computeHeading(panorama.getPosition(), flightPath.GetPointAtDistance(1609.344*distance));

               panorama.setPov({
                  heading: heading,
                  pitch: -1,
                  zoom: -5
               });
            
        

   }
</script>
<!-- <script async
 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAESvkKxPXKAxJNRdp2k1L17Fy-L-Y91zI&callback=initMap">
 </script> -->


 <script>
    /*const jsObject = JSON.parse('<?php echo json_encode($coordinates); ?>'); //console.log(jsObject);
    var distance_array = <?php echo json_encode($distance_array); ?>;*/
</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAESvkKxPXKAxJNRdp2k1L17Fy-L-Y91zI&callback=initMap&libraries=geometry"></script>
<script type="text/javascript" src="{{ asset('assets/js/distance_calculations.js') }}"></script> -->
 <script type="text/javascript"> 
    /*var map;
    function initialize() {
        var myLatLng = new google.maps.LatLng(23.2186176, 78.7994157);
        var mapOptions = {
            //zoom: 15,
            //center: myLatLng,
            streetViewControl: true,
            zoom: 3,
    center: { lat: 0, lng: -180 },
    mapTypeId: "terrain",
           // mapTypeId: 'satellite',
           // mapTypeId: google.maps.MapTypeId.terrain
        };

        //document.getElementById("toggle").addEventListener("click", toggleStreetView);

        var map = new google.maps.Map(document.getElementById('map-old'), mapOptions);

        map.setMapTypeId('terrain');*/
        /*var flightPlanCoordinates = [
            { lng: 77.4206315, lat: 23.2741369 },
            { lng: 78.0523454, lat: 23.4607136 },
            { lng: 78.4917985, lat: 23.3901479 },
            { lng: 78.7994157, lat: 23.2186176 },
            { lng: 79.2608415, lat: 23.3699793 },
            { lng: 79.6343766, lat: 23.2337615 },
            { lng: 79.9969255, lat: 23.1782254 },
        ];*/
        


        /*var flightPlanCoordinates = jsObject;
        //console.log(flightPlanCoordinates);

        var bounds = new google.maps.LatLngBounds();

        var polLine = [];

        for (var i=0; i<flightPlanCoordinates.length; i++) {
            if(flightPlanCoordinates[i]){
                var lat = parseFloat(flightPlanCoordinates[i]['lat']);
                var lng = parseFloat(flightPlanCoordinates[i]['lng']);
                var latlng = {"lng" : lng, "lat": lat}; //console.log(latlng);
                polLine[i] = latlng;
                bounds.extend(latlng);
            }
            
        }
          //console.log(polLine);
        map.fitBounds(bounds);

        var flightPath = new google.maps.Polyline({
            path: polLine,
            geodesic: true,
             strokeColor: "#1a68fa",
             strokeOpacity: 1.0,
             strokeWeight: 8,
        });
*/
        // We get the map's default panorama and set up some defaults.
        // Note that we don't yet set it visible.
        /*panorama = map.getStreetView(); // TODO fix type
        panorama.setPosition(polLine['1']);
        panorama.setVisible(true);
        panorama.setPov(
           {
            heading: 265,
            pitch: 0,
          }
        );*/

        //now add marker to each entry by adding the old ones
        /*var daily_distance_array = distance_array;
        var newDistance = 0;
        //daily_distance_array.forEach(function(distance) {
         for (let i = 0; i < daily_distance_array.length; i++) {
            var distance = daily_distance_array[i]['0'];
            var image = daily_distance_array[i]['1'];
            //newDistance= distance+newDistance;
            createMarker(map, flightPath.GetPointAtDistance(1000*distance),distance+" km", image);
         }

        //Add marker to start and end points
        //createMarker(map,flightPath.getPath().getAt(0),"Start", '');
        //createMarker(map,flightPath.getPath().getAt(flightPath.getPath().getLength()-1),"Destination", '');
        flightPath.setMap(map);

        

    }*/

   /*function toggleStreetView() {
     const toggle = panorama.getVisible();

     if (toggle == false) {
       panorama.setVisible(true);
     } else {
       panorama.setVisible(false);
     }
   }*/


    //const image = "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png";
    

    



    /*function showStreetView(map, latlng){

      const fenway = { lat: 25.90461986793459, lng: 84.89271994062771 };

      const panorama = new google.maps.StreetViewPanorama(
          document.getElementById("mapStreet"),
          {
            position: fenway,
            pov: {
              heading: 34,
              pitch: 10,
            },
          }
        );

      map.setStreetView(panorama);


    }*/

         // Create info window
         var infowindow = new google.maps.InfoWindow({
             maxWidth: 350,
             pixelOffset: new google.maps.Size(-10,-25)
         });

         var infoFn = function(latlng, name, distance){
             return function (e) {
                 var content = '<div>' +
                     '<p> Name: ' + name + '</p>' +
                     '<p> Distance: ' + distance + ' Miles</p>' +
                     
                     '</div>';

                 infowindow.setContent(content);
                 infowindow.open(map);
                 infowindow.setPosition(latlng);
             }
         };

        //Now initialize the Main map with all the markers
        google.maps.event.addDomListener(window, 'load', initMap, infoFn);

        function createMarker(map, latlng, title, image, name, distance){

         


      var icon = {
             url: image, // url
             scaledSize: new google.maps.Size(50, 50), // scaled size
             // This marker is 20 pixels wide by 32 pixels high.
            // size: new google.maps.Size(20, 32),
             // The origin for this image is (0, 0).
             origin: new google.maps.Point(0, 0),
             // The anchor for this image is the base of the flagpole at (0, 32).
             anchor: new google.maps.Point(25, 50),
         };
        var marker = new google.maps.Marker({
                position:latlng,
                map:map,
                title: title,
                icon: icon
        });

        marker.setMap(map);

       let fn = infoFn(latlng, name, distance);
       google.maps.event.addListener(marker, 'click', fn);

    }

    function createMarkerStart(map, latlng, title, image){

      var icon = {
             url: image, // url
             scaledSize: new google.maps.Size(50, 50), // scaled size
             // This marker is 20 pixels wide by 32 pixels high.
            // size: new google.maps.Size(20, 32),
             // The origin for this image is (0, 0).
             origin: new google.maps.Point(0, 0),
             // The anchor for this image is the base of the flagpole at (0, 32).
             anchor: new google.maps.Point(25, 50),
         };
        var marker = new google.maps.Marker({
                position:latlng,
                map:map,
                title: "You at "+title,
                icon: icon
        });
    }

    function createMarkerEnd(map, latlng, title, image){

      
      var icon = {
             url: image, // url
             scaledSize: new google.maps.Size(50, 50), // scaled size
             // This marker is 20 pixels wide by 32 pixels high.
            // size: new google.maps.Size(20, 32),
             // The origin for this image is (0, 0).
             origin: new google.maps.Point(0, 0),
             // The anchor for this image is the base of the flagpole at (0, 32).
             anchor: new google.maps.Point(25, 50),
         };
        var marker = new google.maps.Marker({
                position:latlng,
                map:map,
                title: "You at "+title,
                icon: icon
        });

        
    }

    function createMarkerMiddle(map, latlng, title, image){

      
      var icon = {
             url: image, // url
             scaledSize: new google.maps.Size(50, 50), // scaled size
             // This marker is 20 pixels wide by 32 pixels high.
            // size: new google.maps.Size(20, 32),
             // The origin for this image is (0, 0).
             origin: new google.maps.Point(0, 0),
             // The anchor for this image is the base of the flagpole at (0, 32).
             anchor: new google.maps.Point(25, 50),
         };
        var marker = new google.maps.Marker({
                position:latlng,
                map:map,
                title: "You at "+title,
                icon: icon
        });

        
    }
     </script>

<script type="text/javascript">
         $(document).ready(function() {

            

          
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

          

          /*$('#startDateTime').datetimepicker({
             format:'DD/MM/YYYY HH:mm:ss',
             maxDate: new Date()
          });*/

          //$('.startDateTime').datetimepicker({timeFormat: "hh:mm:ss"});
          
          
        });

         // $("document").ready(function() {
         //    $(".milestone-box").trigger('click');
         // });
      </script>

@endsection
