
@extends('frontend.layouts.app')

@section('content')
   
<div class="header-profile">
  <div id="wrapper" class="toggled left-sidebar">
    <!-- Sidebar -->
    @include('frontend.user.profile_sidebar')
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="container-fluid">
        <div class="right-sidebar ">
          <div class="user-table pb-4">
            <div class="container-fluid"> 
              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                	<tr>
                		<td class="logo-igm"><img class="img-fluid img-responsive" src="http://localhost/buskalo/www/public/img/frontend/logo.svg"></td>
                		<td><b>@lang('labels.frontend.user.account.type_of_professional_or_company')</b></td>
                		{{-- <td><b>Ano de constitucion o experiencia</b></td> --}}
                		<td><b>@lang('labels.frontend.user.account.number_of_employees')</b></td>
                		<td><b>@lang('labels.frontend.user.account.direction')</b></td>
                		<td><b>@lang('labels.frontend.user.account.web_page')</b></td>
                		<td><b>@lang('labels.frontend.user.account.payment_methods')</b></td>
                		<td><b>@lang('labels.frontend.user.account.available_social_networks')</b></td>
                		{{-- <td><b>Certificaciones</b></td> --}}
                		<td><b>@lang('labels.frontend.user.account.services')</b></td>
                		<td><b>@lang('labels.frontend.user.account.global_rating')</b></td>
                		<td><b>@lang('labels.frontend.user.account.ratings')</b></td>
                		<td><b>@lang('labels.frontend.user.account.number_of_reviews')</b></td>
                	</tr>
  
                    <?php 
                      if(isset($service_buyers) && count($service_buyers) > 0) { 
                        foreach ($service_buyers as $key => $value) {

                    ?>
                      <tr>
                        <td>
                            <?php 

                            if($value->user_group_id == 3){
                              $user_type_path = '/img/contractor/profile/';
                            }else {
                              $user_type_path = '/img/company/profile/';
                            }
                             
                            $profile_pic="";
                            $findinfolder="";
                              if(isset($value->avatar_location) && !empty($value->avatar_location))
                              { 
                                $profile_pic=$value->avatar_location;
                                if($value->user_group_id == 3){

                                  $findinfolder=public_path().$user_type_path.$value->avatar_location;
                                }else {
                                  $findinfolder=public_path().$user_type_path.$value->avatar_location;         
                                }

                                
                              }
                              if (file_exists($findinfolder) && !empty($profile_pic))
                              {
                                if($value->user_group_id == 3){
                              ?>
                              <img  height="70",width="60" src="{{asset('img/contractor/profile')}}/{{$profile_pic}}">
                              <?php 
                                } elseif($value->user_group_id == 4){ 
                              ?>
                              <img  height="70",width="60" src="{{asset('img/company/profile')}}/{{$profile_pic}}">
                              <?php } ?>
                                                                               
                            <?php } else { ?>
                            <img  height="70",width="60" src="{{asset('img/frontend/user.png')}}">
                          <?php } ?>

                        </td>
                        <td>{{-- Ingeniero Eléctrico(Independiente)  --}}
              
                          <?php 
                            if($value->user_group_id == 3){
                              echo $value->profile_title.' (Independiente)';
                            }else {
                              echo $value->username;
                            }  
                          ?>

                        </td>
                        {{-- <td>4 años</td> --}}
                        <td>{{ $value->total_workers }}</td>
                        <td>{{ $value->address }}</td>
                        <td>{{ $value->website_address }}</td>
                        <td>{{-- Efectivo, cheque --}}
                          <?php 
                            if(isset($value->user_payment_methods) && !empty($value->user_payment_methods)) {
                            
                              foreach ($value->user_payment_methods as $ke => $val) {
                               
                              echo $val->name_es; if(count($value->user_payment_methods) != $ke+1 ){ echo ", ";}else{echo " ";}
                              }

                            }
                          ?>
                      </td>
                      <td>{{-- Twitter, Facebook --}}
             

                        <?php 

                          if(!empty($value->facebook_url))
                          {
                             echo $value->facebook_url.'&nbsp;&nbsp;';             
                          } 

                          if(!empty($value->instagram_url))
                          {
                             echo $value->instagram_url.'&nbsp;&nbsp;';            
                          }

                          if(!empty($value->snap_chat_url))
                          {
                             echo $value->snap_chat_url.'&nbsp;&nbsp;';            
                          }

                          if(!empty($value->linkedin_url))
                          {
                             echo $value->linkedin_url.'&nbsp;&nbsp;';            
                          }

                         
                        ?>

                    </td>

                    {{-- <td>ESPE 2018 Certificado de energia sustentable</td> --}}
                     <td>- @lang('labels.frontend.user.account.residential_and_commercial_installation') <br> - @lang('labels.frontend.user.account.home_automation')
                      <?php 
                        if(isset($value->services_offered) && !empty($value->services_offered)) {
                         
                          foreach ($value->services_offered as $k => $v) {
                           
                          echo $v->es_name; if(count($value->services_offered) != $k+1 ){ echo "<br> - ";}else{echo " ";}
                          }

                        }
                      ?>

                     </td>
                      <td>
                        <div class="star-list">
                          <?php if($value->is_rated == 'Yes'){

                              for ($i=1; $i <6 ; $i++) { 
                                if($i > $value->average_rating){
                                  echo '<i class="fa fa-star-o"></i>';
                                }else{
                                  echo '<i class="fa fa-star"></i>';
                                }
                              }

                          } else{
                            echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> ( @lang("labels.frontend.user.account.number_of_reviews") )';
                          } 
                          ?>
                        </div>
                       </td>
                       <td>
                         <ul class="rating-list">
                           <li><span>@lang('labels.frontend.user.account.price')</span> 
                            <div class="star-list">
                              <?php if($value->is_rated == 'Yes'){

                                  for ($i=1; $i <6 ; $i++) { 
                                    if($i > $value->average_price_rating){
                                      echo '<i class="fa fa-star-o"></i>';
                                    }else{
                                      echo '<i class="fa fa-star"></i>';
                                    }
                                  }

                              } else{
                                echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                              } 
                              ?>
                            </div>
                           </li>
                           <li><span>@lang('labels.frontend.user.account.puntuality')</span> 
                            <div class="star-list">
                              <?php if($value->is_rated == 'Yes'){

                                  for ($i=1; $i <6 ; $i++) { 
                                    if($i > $value->average_puntuality_rating){
                                      echo '<i class="fa fa-star-o"></i>';
                                    }else{
                                      echo '<i class="fa fa-star"></i>';
                                    }
                                  }

                              } else{
                                echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                              } 
                              ?>
                            </div>
                           </li>
                           <li><span>@lang('labels.frontend.user.account.service')</span> 
                            <div class="star-list">
                              <?php if($value->is_rated == 'Yes'){

                                  for ($i=1; $i <6 ; $i++) { 
                                    if($i > $value->average_service_rating){
                                      echo '<i class="fa fa-star-o"></i>';
                                    }else{
                                      echo '<i class="fa fa-star"></i>';
                                    }
                                  }

                              } else{
                                echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                              } 
                              ?>
                            </div>
                           </li>
                           <li><span>@lang('labels.frontend.user.account.quality')</span> 
                            <div class="star-list">
                              <?php if($value->is_rated == 'Yes'){

                                  for ($i=1; $i <6 ; $i++) { 
                                    if($i > $value->average_quality_rating){
                                      echo '<i class="fa fa-star-o"></i>';
                                    }else{
                                      echo '<i class="fa fa-star"></i>';
                                    }
                                  }

                              } else{
                                echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                              } 
                              ?>
                            </div>
                           </li>
                           <li><span>@lang('labels.frontend.user.account.amiability')</span> 
                            <div class="star-list">
                              <?php if($value->is_rated == 'Yes'){

                                  for ($i=1; $i <6 ; $i++) { 
                                    if($i > $value->average_amiability_rating){
                                      echo '<i class="fa fa-star-o"></i>';
                                    }else{
                                      echo '<i class="fa fa-star"></i>';
                                    }
                                  }

                              } else{
                                echo '<i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>';
                              } 
                              ?>
                            </div>
                           </li>
                         </ul>
                       </td>

                       <td>
                          <?php 
                           if($value->is_rated == 'Yes'){
                              echo $value->total_rate_count;
                           } else{
                             echo 0;
                           }
                          ?>
                       </td>
                    </tr>

                   <?php } }  ?>
  
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>    
</div>
<script type="text/javascript">
      setTimeout(function(){
        $("table").each(function() {
              var $this = $(this);
              var newrows = [];
              $this.find("tr").each(function(){
                  var i = 0;
                  $(this).find("td").each(function(){
                      i++;
                      if(newrows[i] === undefined) { newrows[i] = $("<tr></tr>"); }
                      newrows[i].append($(this));
                  });
              });
              $this.find("tr").remove();
              $.each(newrows, function(){
                  $this.append(this);
              });
          });
      });
    </script>
    <style type="text/css">
  .fa{
    color: #ffa534;
  }
</style>
  
@endsection


