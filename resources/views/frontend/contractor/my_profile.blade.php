@extends('frontend.layouts.app')
@section('content')
<div class="header-profile">
  <div class="top-header-profile">
    <div class="top-profile-info">
      <div class="media">

        <?php $pic= 'img/frontend/user.png';
            if(isset($user) && !empty($user->avatar_location)){$pic= 'img/contractor/profile/'.$user->avatar_location;}
            ?>
        <img src="{{ url($pic) }}" id="thumbnil22" class="pro-img">
        <div class="media-body">
          <h4>{{$user->username}}</h4>
         @if($user->approval_status==1 && $user->is_confirm_reg_step==1)
          <p><img src="{{ url('img/frontend/check3.png') }}" class="sm-img pr-1"> @lang('labels.frontend.constructor.profile.verified_profile')</p>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div id="wrapper" class="toggled left-sidebar">
    <!-- Sidebar -->
    @include('frontend.contractor.profile_sidebar')
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="container-fluid">

        <div class="right-sidebar ">
          <!-- Tab panes -->
          <div class="tab-content">

            <div class="top-profile-sec pro-wrapper">

            <?php $pic= 'img/frontend/account-setting.png';?>
              <img src="{{ url($pic) }}" class="img-fluid">
              <span>@lang('labels.frontend.constructor.profile.my_account')</span>
            </div>
            <div class="">
              <div class="row no-gutters">
                <div class="col-md-6 pr-1">
                  <div class="data-info pro-wrapper">
                    <img src="{{ url('img/frontend/user-profile.png') }}" class="img-fluid">

                    <h5>{{$status_bar}}% @lang('labels.frontend.constructor.profile.completed')</h5>
                    <div class="progress-mg">
                      <div class="progress" style="height:30px">
                        <div class="progress-bar" style="width:{{$status_bar}}%;height:30px"></div>
                      </div>
                    </div>
                    <p>@lang('labels.frontend.constructor.profile.profile_information')</p>
                  </div>
                </div>
                <div class="col-md-6 pl-1">
                  <div class="data-info pro-wrapper">
                    <div class="border-bottom pb-3">
                      <img src="{{ url('img/frontend/save-money.png') }}" class="img-fluid">
                      <span class="s_price">
                        <?php if(isset($user) && !empty($user->pro_credit)){echo  number_format($user->pro_credit,2);}else{ echo '0.00';}?>  </span>
                    </div>
                      @if($user->approval_status==1)
                      <a href="{{url('subscription')}}"> <button class="btn or-btn-outline"> <i class="fa fa-shopping-cart pr-1"></i> @lang('labels.frontend.constructor.profile.buy_credits')</button></a>
                      @else
                       <button class="btn or-btn-outline"> <i class="fa fa-shopping-cart pr-1"></i> @lang('labels.frontend.constructor.profile.buy_credits')</button>
                      @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane active" id="miperfil">
              <div class="profile-detail-sec">
                <div class="profile-head">
                  <div class="top-edit">
                    <!-- <button type="btn" data-toggle="modal" data-target="#updateprofileModal" class="edit-btn"><i class="fa fa-edit"></i>Editar</button> -->
                  </div>
                  <div class="profile-sec">
                    <!--<div class="profile-img">
                       <?php $pic//= 'img/frontend/user.png';
                      //if(isset($user) && !empty($user->avatar_location)){$pic= 'img/contractor/profile/'.$user->avatar_location;} ?>
                      <img src="{{ url($pic) }}" class="pro-img" style="height: 131px !important;"> 
                    </div>-->


                    <!--Profile with crop-->
                       <div class="add-profile-img">
                          <input type="file" accept="image/*" class="avatar_location" name="avatar_location" id="imgupload" style="display:none"/>
                            <button type="button" id="OpenImgUpload">
                              <span class="file-block">
                                <div id="imageUploadDivAppend">
                                 <?php $pic= 'img/frontend/user.png';
                                  if(isset($user) && !empty($user->avatar_location)){$pic= 'img/contractor/profile/'.$user->avatar_location;} ?>
                                  <img id="thumbnil" src="{{url($pic)}}"  class="pro-img" style="height: 98px !important; width: 98px !important; padding: 0 !important " alt="image">
                                </div>
                              </span>
                            </button>
                        </div>

                        <!--Profile with crop-->

                    <div class="profile-text" style="margin-top: -10px !important;">
                      <h5 class="mt-0"><?php echo isset($user->username) && !empty($user->username) ? $user->username : ''; ?></h5>
                      <h6><?php echo isset($user->profile_title) && !empty($user->profile_title) ? $user->profile_title : ''; ?></h6>
                    </div>
                  </div>

                   <input type="hidden" id="contractorUserId" name="user_id" value="{{ isset($user) && !empty($user->id) ? $user->id : '0'  }}">
 
                  <div class="row mt-5">
                    <!--**********Basic Information Update End Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.constructor.profile.basic_information')</h3>
                        <button type="btn" data-toggle="modal" data-target="#infoModal"
                        class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.constructor.profile.edit')</button>
                      </div>
                      <ul class="info-list ul-first">
                        <li><h6>@lang('labels.frontend.constructor.profile.identity_no')</h6> <?php echo isset($user->identity_no) && !empty($user->identity_no) ? $user->identity_no : ''; ?></li>

                        <li><h6>@lang('labels.frontend.constructor.profile.email_identification')</h6> <?php echo isset($user->email) && !empty($user->email) ? $user->email : ''; ?></li>

                        <li><h6>@lang('labels.frontend.constructor.profile.total_employees')</h6> <?php echo isset($user->total_employee) && !empty($user->total_employee) ? $user->total_employee : '0'; ?></li>

                        <li><h6>@lang('labels.frontend.constructor.profile.username')</h6> <?php echo isset($user->username) && !empty($user->username) ? $user->username : ''; ?></li>

                        <li><h6>@lang('labels.frontend.constructor.profile.profile_title')</h6> <?php echo isset($user->profile_title) && !empty($user->profile_title) ? $user->profile_title : ''; ?></li>

                        <li><h6>@lang('labels.frontend.constructor.profile.date_of_birth')</h6> <?php echo isset($user->dob) && !empty($user->dob) ? date('dS F Y', strtotime($user->dob)) : ''; ?></li>

                        <li><h6>@lang('labels.frontend.constructor.profile.address')</h6> <?php echo isset($user->address) && !empty($user->address) ? $user->address : ''; ?></li>

                        @if(isset($user->office_address) && !empty($user->office_address))
                          <li><h6>@lang('labels.frontend.constructor.profile.office_address')</h6> <?php echo isset($user->office_address) && !empty($user->office_address) ? $user->office_address : ''; ?></li>
                        @endif
                        @if(isset($user->other_address) && !empty($user->other_address))
                          <li><h6>@lang('labels.frontend.constructor.profile.other_direction')</h6> <?php echo isset($user->other_address) && !empty($user->other_address) ? $user->other_address : ''; ?></li>
                        @endif
                        <li><h6>@lang('labels.frontend.constructor.profile.mobile_phone_number')</h6> <?php echo isset($user->mobile_number) && !empty($user->mobile_number) ? $user->mobile_number : ''; ?></li>
                         @if(isset($user->landline_number) && !empty($user->landline_number))
                          <li><h6>@lang('labels.frontend.constructor.profile.landline_number')</h6> <?php echo isset($user->landline_number) && !empty($user->landline_number) ? $user->landline_number : ''; ?></li>
                          @endif
                         @if(isset($user->office_number) && !empty($user->office_number))
                          <li><h6>@lang('labels.frontend.constructor.profile.office_number')</h6> <?php echo isset($user->office_number) && !empty($user->office_number) ? $user->office_number : ''; ?></li>
                          @endif
                       </ul>
                    </div>
                    <!--**********Basic Information Update End Here************-->
                    <!--**********Payment Method Update Start Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.constructor.profile.payment_methods')</h3>
                        <button type="btn" data-toggle="modal" data-target="#paymentMethodModal"
                        class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.constructor.profile.edit')</button>
                      </div>
                      <div class="meta-list">
                        <h5>@lang('labels.frontend.constructor.profile.select_payment_methods')</h5>
                        <?php
                        if(isset($paymentMethods) && !empty($paymentMethods)) {
                        foreach ($paymentMethods as $k => $v_menthod) {

                          if(in_array($v_menthod->id,$paymentMethodId))
                          {
                        ?>

                        <label class="cust-radio">{{ $v_menthod->name_es }}
                        <input type="checkbox" checked="checked" readonly="readonly" disabled='disabled' value="{{$v_menthod->id}}" name="payment_method_id[]" multiple="">
                        <span class="checkmark"></span>
                        </label>

                        <?php } else { ?>

                        <label class="cust-radio">{{ $v_menthod->name_es }}
                        <input type="checkbox" readonly="readonly" disabled='disabled' value="{{$v_menthod->id}}" name="payment_method_id[]" multiple="">
                        <span class="checkmark"></span>
                        </label>

                       <?php } ?>

                        <?php
                        }
                        }
                        ?>
                      </div>
                    </div>
                    <!--**********Payment Method Update End Here************-->
                    <!--**********Social Networks Start Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.constructor.profile.social_media')</h3>
                        <button type="btn" data-toggle="modal" data-target="#socialMediaModal"
                        class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.constructor.profile.edit')</button>
                      </div>
                      <ul class="info-list">

                      <?php if(isset($social) && !empty($social->facebook_url)) {?>

                      <li><h6>@lang('labels.frontend.constructor.profile.facebook') +</h6><a target="_blank" href="<?php if(isset($social)){echo $social->facebook_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->facebook_url)){echo $social->facebook_url;}?></a></li>

                      <?php } ?>

                      <?php if(isset($social) && !empty($social->instagram_url)){ ?>

                      <li><h6>@lang('labels.frontend.constructor.profile.instagram') +</h6><a target="_blank" href="<?php if(isset($social)){echo $social->instagram_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->instagram_url)){echo $social->instagram_url;}?></a></li>

                      <?php } ?>

                        <?php if(isset($social) && !empty($social->linkedin_url)){ ?>

                       <li><h6>@lang('labels.frontend.constructor.profile.linkedin') +</h6><a target="_blank" href="<?php if(isset($social)){echo $social->linkedin_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->linkedin_url)){echo $social->linkedin_url;}?></a></li>

                       <?php } ?>

                        <?php if(isset($social) && !empty($social->twitter_url)){ ?>

                        <li><h6>@lang('labels.frontend.constructor.profile.twitter') +</h6><a target="_blank" href="<?php if(isset($social)){echo $social->twitter_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->twitter_url)){echo $social->twitter_url;}?></a></li>

                        <?php } ?>

                        <?php if(isset($social) && !empty($social->other)){?>

                        <li><h6>@lang('labels.frontend.constructor.profile.other_url') +</h6><a target="_blank" href="<?php if(isset($social)){echo $social->other;}?>" class="orange"> <?php if(isset($social)  && !empty($social->other)){echo $social->other;}?></a></li>
                        <?php } ?>


                      </ul>
                    </div>
                    <?php
                      if(isset($social)){
                        $facebook_url = $social->facebook_url;
                        $facebook_url = (100*2)/100;
                        $instagram_url = $social->instagram_url;
                        $instagram_url = (100*2)/100;
                        $linkedin_url = $social->linkedin_url;
                        $linkedin_url = (100*2)/100;
                        $twitter_url = $social->twitter_url;
                        $twitter_url = (100*2)/100;
                        $other = $social->other;
                        $other = (100*2)/100;
                      }
                      if(!empty( $facebook_url) && !empty($instagram_url) && !empty($linkedin_url) && !empty($twitter_url) && !empty($other)){
                        $total_social = $facebook_url + $instagram_url + $linkedin_url + $twitter_url + $other;
                      }
                      
                    ?>

                    <!--**********Social Networks END Here************-->
                    <!--**********Certificaciones-Curso Start Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.constructor.profile.certifications_courses')</h3>
                        <button type="btn" data-toggle="modal" data-target="#certificationModal"
                        class="edit-btn"><i class="fa fa-plus"></i>&nbsp;@lang('labels.frontend.constructor.profile.add')</button>
                      </div>
                      <!-- <ul class="info-list">
                        <li><h6>Certificacion Title Here</h6><a data-toggle="modal" data-target="#certificationModal"  class="orange">+ Image</a></li>
                      </ul> -->
                      <div class="photo-galley row">
                        <?php if(isset($user->cetifications) && !empty($user->cetifications))
                        {

                          //dd($user->cetifications);

                         foreach ($user->cetifications['certification_courses'] as $key_course => $value_course) {

                        ?>
                         @if(file_exists(public_path('/img/contractor/certifications/'.$value_course['user_id'].'/'.$value_course['file_name'])))
                        <div class="bg-light col-sm-4 div_show1">
                          <div class="foto-img">
                              <img src="{{ url('/img/contractor/certifications/'.$value_course['user_id'].'/'.$value_course['file_name']) }}">
                            <a href="{{ url('/img/contractor/certifications/'.$value_course['user_id'].'/'.$value_course['file_name']) }}" class="orange"></a>
                            <!--Update Delete Button-->
                             <div class="hover-icons">
                              <ul>
                                <li><a href="#" data-toggle="modal" data-target="#editCertificate" data-filename="{{ url('/img/contractor/certifications/'.$value_course['user_id'].'/'.$value_course['file_name']) }}" data-certid="{{$value_course['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="#" data-toggle="modal" data-target="#deleteCertificate" data-filename="{{ url('/img/contractor/certifications/'.$value_course['user_id'].'/'.$value_course['file_name']) }}" data-certid="{{$value_course['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>
                             <!--Update Delete Button-->
                          </div>
                        </div>
                        @endif
                        <?php   }
                        } else {
                        ?>
                        <div class="bg-light">
                          <div class="foto-img">
                            <img src="">
                            <a href="#" class="orange"><p>+ @lang('labels.frontend.constructor.profile.agegar_galeria')</p></a>
                          </div>
                        </div>
                        <?php
                        }
                        ?>
                        @if(count($user->cetifications['certification_courses'])>3)
                          <a href="javascript:void(0)" id="load1" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.constructor.profile.see_all')</a>
                        @endif
                      </div>
                    </div>
                    <!--**********Certificaciones-Curso End Here************-->
                    <!--**********Police REcord Start Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.constructor.profile.police_record')</h3>
                        <button type="btn" data-toggle="modal" data-target="#policeRecordModal"
                        class="edit-btn"><i class="fa fa-plus"></i>&nbsp;@lang('labels.frontend.constructor.profile.add')</button>
                      </div>
                      <!--  <ul class="info-list">
                        <li><h6>Certificacion Title Here</h6><a  data-toggle="modal" data-target="#policeRecordModal" href="" class="orange">+ Image</a></li>
                      </ul> -->
                      <div class="photo-galley row">
                        <?php if(isset($user->cetifications) && !empty($user->cetifications))
                        {
                        foreach ($user->cetifications['police_records'] as $key_police => $value_police) {

                        ?>
                         @if(file_exists(public_path('/img/contractor/police_records/'.$value_police['user_id'].'/'.$value_police['file_name'])))
                        <div class="bg-light col-sm-4 div_show3">
                          <div class="foto-img">
                            <img src="{{url('/img/contractor/police_records/'.$value_police['user_id'].'/'.$value_police['file_name']) }}">
                            <a href="{{url('/img/contractor/police_records/'.$value_police['user_id'].'/'.$value_police['file_name']) }}" class="orange"></a>

                             <!--Update Delete Button-->
                             <div class="hover-icons">
                              <ul>
                                <li><a href="#" data-toggle="modal" data-target="#editPoliceRecord" data-filename="{{url('/img/contractor/police_records/'.$value_police['user_id'].'/'.$value_police['file_name']) }}" data-polid="{{$value_police['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="#" data-toggle="modal" data-target="#deletePoliceRecord" data-filename="{{url('/img/contractor/police_records/'.$value_police['user_id'].'/'.$value_police['file_name']) }}" data-polid="{{$value_police['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>
                             <!--Update Delete Button-->

                          </div>
                        </div>
                        @endif
                        <?php   }
                        } else {
                        ?>
                        <div class="bg-light">
                          <div class="foto-img">
                            <img src="">
                            <a href="#" class="orange"><p>+ @lang('labels.frontend.constructor.profile.agegar_galeria')</p></a>
                          </div>
                        </div>
                        <?php
                        }
                        ?>
                        @if(count($user->cetifications['police_records'] )>3)
                        <a href="javascript:void(0)" id="load3" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.constructor.profile.see_all')</a>
                        @endif
                      </div>
                    </div>
                    <!--**********Police REcord End Here************-->
                    <!--**********Profile description Start Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.constructor.profile.professional')</h3>
                        <button type="btn" data-toggle="modal" data-target="#profileDescriptionModal"
                        class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.constructor.profile.edit')</button>
                      </div>
                      <ul class="info-list">
                        <li><h6>@lang('labels.frontend.constructor.profile.brief_presentation')</h6>
                          <p><?php if(isset($user) && !empty($user->profile_description)){echo $user->profile_description;}?></p></li>
                      </ul>
                     </div>
                      <!--**********Profile description End Here************-->


                      <!--**********Offered Services Start Here************-->

                      <div class="pro-info col-md-6">
                        <div class="pro-heading">
                          <h3>@lang('labels.frontend.constructor.profile.services')</h3>
                          <button type="btn" data-toggle="modal" data-target="#serviceModal"
                          class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.constructor.profile.edit')</button>
                        </div>
                        <ul class="area-list">
                            <!-- <select name="services[]" id="services_id">
                              <option>servicios de instalacion electrica</option>
                              <?php foreach ($services as $k_service => $v_service) { 
                                if(in_array($v_service->id,$serviceIds)) 
                                  {?>
                              <option value="{{$v_service->id}}" selected="true">{{$v_service->es_name}}</option>
                              <?php } else { ?> 

                              <option value="{{$v_service->id}}">{{$v_service->es_name}}</option> ?> <?php } } ?>
                            </select> -->
                            <?php foreach ($serviceOffered as $key => $value) { ?>
                                <li><img src="{{ url('img/frontend/check1.png') }}"> {{$value->es_name}}</li>
                              <?php  } ?>
                        </ul>
                      </div>

                      <!--**********Offered Services End Here************-->

                      <!--**********Services Coverage Area Start Here************-->
                      <div class="pro-info col-md-6">
                        <div class="pro-heading">
                          <h3>@lang('labels.frontend.constructor.profile.cover_area')</h3>
                          <button type="btn" data-toggle="modal" data-target="#coverageAreaModal"
                          class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.constructor.profile.edit')</button>
                        </div>
                        <ul class="area-list">
                            <?php 
                             if(isset($allUserAreaData) && !empty($allUserAreaData)) {

                              foreach ($allUserAreaData as $key => $value) { ?>
                              <li><img src="{{ url('img/frontend/check1.png') }}"> {{$value['name']}}</li>

                              <?php foreach ($value['cities'] as $key2 => $val2) 
                              { 
                                ?>

                               <li style="margin-left: 10%;">{{'* '.$val2['name']}}</li>
                                
                              <?php } ?>

                            <?php  }  }?>
                        </ul>
                      </div>
                      <!--**********Services Coverage Area End Here************-->
                      <!--**********User Gallery Start Here************-->
                    
                      <div class="pro-info col-md-6">
                        <div class="pro-heading">
                          <h3>@lang('labels.frontend.constructor.profile.photos_and_videos')</h3>
                          <button type="btn" data-toggle="modal" data-target="#galleryModal"
                          class="edit-btn"><i class="fa fa-plus"></i>&nbsp;@lang('labels.frontend.constructor.profile.add')</button>
                        </div>
                        <div class="photo-galley row">
                          <?php if(isset($user->gallery) && !empty($user->gallery))
                          {
                          foreach ($user->gallery['images'] as $key_images => $value_images) {

                          ?>
                            @if(file_exists(public_path('/img/contractor/gallery/images/'.$value_images['user_id'].'/'.$value_images['file_name'])))
                          <div class="bg-light col-sm-4 div_show record_policy">
                            <div class="foto-img">
                              <img src="{{ url('/img/contractor/gallery/images/'.$value_images['user_id'].'/'.$value_images['file_name'])}}">
                              <!--Update Delete Button-->
                             <div class="hover-icons">
                              <ul>
                                <li><a href="#" data-toggle="modal" data-target="#editPhotosVideos" data-filename="{{ url('/img/contractor/gallery/images/'.$value_images['user_id'].'/'.$value_images['file_name'])}}" data-pvid="{{$value_images['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="#" data-toggle="modal" data-target="#deletePhotosVideos" data-filename="{{ url('/img/contractor/gallery/images/'.$value_images['user_id'].'/'.$value_images['file_name'])}}" data-pvid="{{$value_images['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>
                             <!--Update Delete Button-->

                            </div>
                          </div>
                          @endif
                          <?php   }
                          ?> 
                          <?php 
                           foreach ($user->gallery['videos'] as $key_images => $value_videos) {
                            //dd($user->gallery['videos']);
                          ?> 
                           @if(file_exists(public_path('/img/contractor/gallery/videos/'.$value_videos['user_id'].'/'.$value_videos['file_name'])))
                          <div class="bg-light col-sm-4 div_show record_policy">
                            <div class="foto-img">
                              <video controls>
                                <source src="{{ url('/img/contractor/gallery/videos/'.$value_videos['user_id'].'/'.$value_videos['file_name'])}}" type="video/mp4">
                                <source src="">
                              </video>

                              <a href="{{ url('/img/contractor/gallery/videos/'.$value_videos['user_id'].'/'.$value_videos['file_name'])}}" class="orange"></a>

                              <!--Update Delete Button-->
                             
                             <!--Update Delete Button-->
                             <div class="hover-icons">
                              <ul>
                                <li><a href="#" data-toggle="modal" data-target="#editPhotosVideos" data-filename1="{{ url('/img/contractor/gallery/videos/'.$value_videos['user_id'].'/'.$value_videos['file_name'])}}" data-vvid="{{$value_videos['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="#" data-toggle="modal" data-target="#deletePhotosVideos" data-filename1="{{ url('/img/contractor/gallery/videos/'.$value_videos['user_id'].'/'.$value_videos['file_name'])}}" data-vvid="{{$value_videos['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>

                            </div>
                          </div>
                          @endif
                          <?php   }}else {
                          ?> 

                          <div class="bg-light">
                            <div class="foto-img">
                              <img src="">
                              <a href="#" class="orange"><p>+ @lang('labels.frontend.constructor.profile.add_gallery')</p></a>
                            </div>
                          </div>
                          <?php
                          }
                          ?>

                        </div>
                        <?php $image=count($user->gallery['images']);
                        $video=count($user->gallery['videos']);
                        $countdata=$image+$video;
                        ?>

                     <!--   <a href="#" id="load">Load More</a> -->
                        @if($countdata>3)
                          <a href="javascript:void(0)" id="load" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.constructor.profile.see_all')</a>
                        @endif
                        </div>


                      <!--**********User Gallery End Here************-->
                      <!-- <div class="pro-info col-md-6">
                        <div class="pro-heading">
                          <h3>Calificaciones y Resenas</h3>
                          <button type="btn" data-toggle="modal" data-target="#exampleModal"
                          class="edit-btn"><i class="fa fa-edit"></i>Editar</button>
                        </div>
                        <div class="bg-light">
                          <div class="foto-img">
                            <img src="">
                            <a href="#" class="orange"><p>+ Agegar Galeria</p></a>
                          </div>
                        </div>
                      </div> -->
                      <div class="pro-info col-md-6">
                        <div class="pro-heading">
                          <h3>@lang('labels.frontend.constructor.profile.funds_balance')</h3>
                          <!-- <button type="btn" data-toggle="modal" data-target="#balanceModal"
                          class="edit-btn"><i class="fa fa-edit"></i>Editar</button> -->
                        </div>
                        <ul class="info-list">
                          <li><h6>Saldo de la Cuenta</h6> <?php if(isset($user)){echo number_format($user->pro_credit,2);}?></li>
                          <a href="#" class="orange"></a>
                        </ul>
                      </div>
                    </div>

                    <!-- <div class="text-center">
                      <button class="btn sub-btn">Enviar</button>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.constructor.profile.modal_title')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
        <button type="button" class="btn btn-primary">@lang('labels.frontend.constructor.profile.save_changes')</button>
      </div>
    </div>
  </div>
</div>
<!--///////////////////Basic Information modal start here/////////////////////////-->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.constructor.profile.update_basic_information')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>


      {{ html()->form('POST', route('frontend.contractor.my-profile.update_info'))->attribute('enctype', 'multipart/form-data')->id('myProfileForm')->open() }}


      <div class="modal-body">
        <div class="edit-form">
          <div class="form-edit">
            <!-- Form -->
            <label>*@lang('labels.frontend.constructor.profile.identity_no')</label>
            <input type="text" name="identity_no" required="" value="<?php if(isset($user) && !empty($user->identity_no)){echo $user->identity_no;}?>" id="identityNo">
            <br/><div class="identity-no-msg" style="text-align: center;"></div>
          </div>
          <div class="form-edit">
            <label>*@lang('labels.frontend.constructor.profile.username')</label>
            <input type="text" name="username" required="" value="<?php if(isset($user) && !empty($user->username)){echo $user->username;}?>" id="userName">
            <br/><div class="user-name-msg" style="text-align: center;"></div>
          </div>
          <div class="form-edit">
            <label>@lang('labels.frontend.constructor.profile.profile_title')</label>
            <input type="text" name="profile_title" value="<?php if(isset($user) && !empty($user->profile_title)){echo $user->profile_title;}?>" id="profileTitle">
            <br/><div class="profile-title-msg" style="text-align: center;"></div>
          </div>
          <div class="form-edit">
            <label>*@lang('labels.frontend.constructor.profile.date_of_birth')</label>
            <input type="text" required="" class="form_date" name="dob" value="<?php if(isset($user) && !empty($user->dob)){echo $user->dob;}?>" id="datepicker">
          </div>
          <div class="form-edit">
            <label>*@lang('labels.frontend.constructor.profile.address')</label>
            <input type="text" required="" name="address" value="<?php if(isset($user) && !empty($user->address)){echo $user->address;}?>" id="address">
            <br/><div class="address-msg" style="text-align: center;"></div>
          </div>

          <div class="form-edit">
            <label>@lang('labels.frontend.constructor.profile.office_address')</label>
            <input type="text" name="office_address" value="<?php if(isset($user) && !empty($user->office_address)){echo $user->office_address;}?>" id="office_address">
          </div>
          <div class="form-edit">
            <label>@lang('labels.frontend.constructor.profile.other_direction')</label>
            <input type="text" name="other_address" value="<?php if(isset($user) && !empty($user->other_address)){echo $user->other_address;}?>" id="other_address">
          </div>
          <div class="form-edit">
            <label>*@lang('labels.frontend.constructor.profile.mobile_phone_number')</label>
            <input type="text" required="" name="mobile_number" value="<?php if(isset($user) && !empty($user->mobile_number)){echo $user->mobile_number;}?>" id="mobileNumber">
            <br/><div class="mobile-number-msg" style="text-align: center;"></div>
          </div>
          <div class="form-edit">
            <label>@lang('labels.frontend.constructor.profile.landline_number')</label>
            <input type="text" name="landline_number" value="<?php if(isset($user) && !empty($user->landline_number)){echo $user->landline_number;}?>" id="landlineNumber">
            <br/><div class="landline-number-msg" style="text-align: center;"></div>
          </div>
          <div class="form-edit">
            <label>@lang('labels.frontend.constructor.profile.office_number')</label>
            <input type="text" name="office_number" value="<?php if(isset($user) && !empty($user->office_number)){echo $user->office_number;}?>" id="officeNumber">
             <br/><div class="office-number-msg" style="text-align: center;"></div>
            <!-- End -->
          </div>

          <div class="form-edit">
            <label>*@lang('labels.frontend.constructor.profile.number_of_employees')</label>
            <input type="number" min="1" name="total_employee" required="" value="<?php if(isset($user) && !empty($user->total_employee)){echo $user->total_employee;}?>" id="total_employee">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
        <button type="submit" id="submits-btn" class="btn opp-btn">@lang('labels.frontend.constructor.profile.send')</button>
      </div>
    </div>

    {{ html()->form()->close() }}
  </div>
</div>
</div>
<!--///////////////////Basic Information modal End here/////////////////////////-->
<!--///////////////////Payment Method ModalStart Here/////////////////////////-->
<!-- Modal -->
<div class="modal fade" id="paymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.constructor.profile.update_pago_method')</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
    </div>

    {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="modal-body">
      <div class="edit-form">
                <div class="form-radio">
                <?php
                  if(isset($paymentMethods) && !empty($paymentMethods)) {
                  //echo "<pre>"; print_r($paymentMethodId); die;
                  foreach ($paymentMethods as $k => $v_menthod)
                  {
                   if(in_array($v_menthod->id,$paymentMethodId))
                          { //die;
                        ?>

                    <label class="cust-radio">{{ $v_menthod->name_es }}
                    <input type="checkbox" value="{{$v_menthod->id}}" checked="checked" name="payment_method_id[]" multiple="">
                    <span class="checkmark"></span>
                    </label>

                    <?php } else { ?>

                    <label class="cust-radio">{{ $v_menthod->name_es }}
                    <input type="checkbox" value="{{$v_menthod->id}}" name="payment_method_id[]" multiple="">
                    <span class="checkmark"></span>
                    </label>

                       <?php } ?>

                  <?php
                  }
                  }
                ?>

                </div>
              </div>
                    </div>
                    <div class="modal-footer">
                          <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
                          <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.save_changes')</button>
                    </div>
                 {{ html()->form()->close() }}
            </div>
      </div>
</div>
<!--///////////////////Payment Method Modal End Here/////////////////////////-->
<!--///////////////////Social Networks Modal End Here/////////////////////////-->
<!-- Modal -->
<div class="modal fade" id="socialMediaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.constructor.profile.update_social_networks')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                  </div>

                    {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
                    <div class="modal-body">
                          <div class="edit-form">
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.constructor.profile.facebook')</label>
            <input type="text" name="facebook_url"  value="<?php if(isset($social) && !empty($social->facebook_url)){echo $social->facebook_url;}?>" id="facebook_url">
                                </div>
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.constructor.profile.instagram')</label>
            <input type="text" name="instagram_url"  value="<?php if(isset($social) && !empty($social->instagram_url)){echo $social->instagram_url;}?>" id="instagram_url">
                                </div>
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.constructor.profile.linkedin')</label>
            <input type="text" name="linkedin_url"  value="<?php if(isset($social) && !empty($social->linkedin_url)){echo $social->linkedin_url;}?>" id="linkedin_url">
                                </div>
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.constructor.profile.twitter')</label>
            <input type="text" name="twitter_url"  value="<?php if(isset($social) && !empty($social->twitter_url)){echo $social->twitter_url;}?>" id="twitter_url">
                                </div>
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.constructor.profile.other_url')</label>
            <input type="text" name="other" value="<?php if(isset($social) && !empty($social->other)){echo $social->other;}?>" id="other">
                                </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                            <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
                            <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.save_changes')</button>
                      </div>
                </div>

            {{ html()->form()->close() }}
        </div>
  </div>
</div>
<!--///////////////////Social Networks Modal End Here/////////////////////////-->

<!--///////////////////certification Courses modal start here/////////////////////////-->
<div class="modal fade" id="certificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.constructor.profile.cargar_certified')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">
                        <div class="cer-div">
                            <label>@lang('labels.frontend.constructor.profile.cargar_certified'):</label>
                              <input type="radio" name="certification_type" checked="" value="0"> @lang('labels.frontend.constructor.profile.image')
                              <input type="radio" name="certification_type" value="1"> @lang('labels.frontend.constructor.profile.document')
                        </div>
                        <div class='file_upload' id='f1'><input name='certification_courses[]' type='file'/></div>
                        <div id='file_tools'>
                              <i class="fa fa-plus-circle" id='add_file' aria-hidden="true">@lang('labels.frontend.constructor.profile.add_new_archive')</i>
                              <i class="fa fa-minus-circle" id='del_file' aria-hidden="true">@lang('labels.frontend.constructor.profile.erase')</i>
                        </div>

              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.save_changes')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>
<!--///////////////////certification Courses modal End here/////////////////////////-->



<!--///////////////////UPDATEcertification Courses modal start here/////////////////////////-->
<div class="modal fade" id="editCertificate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.constructor.profile.edit_certificates')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.update_certificate_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">


                        <img src="#" alt="bank_chalan" id="thanksCerification" style="height: 100px;" />
                        <input type="hidden" name="certification_id" id="certification_id" value=""> 


                        <div class='file_upload' id='f1'>
                         <br/><input name='certification_courses_img'  type='file'/>
                        </div>
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.to_close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.send')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>


<div class="modal fade" id="deleteCertificate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿@lang('labels.frontend.constructor.profile.are_you_sure_you_want_to_delete')?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.delete_certificate_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">

                    <img src="#" alt="bank_chalan" id="thanksCerificationImg" style="height: 100px;" />
                    <input type="hidden" name="certification_id" id="certification_id_del" value=""> 
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.cancel')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.want_to_delete')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>
<!--///////////////////UPDATE certification Courses modal End here/////////////////////////-->




<!--///////////////////UPDATE DELETE POLICE RECORD modal START here/////////////////////////-->
<div class="modal fade" id="editPoliceRecord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.constructor.profile.update_police_record')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.update_policerec_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">


                <img src="#" alt="bank_chalan" id="thanksPolicerecImg" style="height: 100px;" />
                <input type="hidden" name="polRecId" id="pol_rec_id" value=""> 


                <div class='file_upload' id='f1'>
                 <br/><input name='police_record_img'  type='file'/>
                </div>
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.to_close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.send')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>

<div class="modal fade" id="deletePoliceRecord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿@lang('labels.frontend.constructor.profile.are_you_sure_you_want_to_delete')?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.delete_police_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">

                    <img src="#" alt="bank_chalan" id="thanksPolicerecImg_del" style="height: 100px;" />
                    <input type="hidden" name="polRecId" id="pol_rec_id_del" value=""> 
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.cancel')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.want_to_delete')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>
<!--///////////////////UPDATE DELETE POLICE RECORD modal End here/////////////////////////-->




<!--///////////////////UPDATE DELETE Photos Videos modal START here/////////////////////////-->
<div class="modal fade" id="editPhotosVideos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.constructor.profile.update_police_record')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.update_photovideos_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">

                  @if($user->gallery['images'])
                    <div class='file_upload' id='f1'>
                     <br/><input name='gallery_image'  type='file' id="gallId11" value=""/>
                     <input type="hidden" name="gall_id" id="gallId" value="">
                     <img src="#" alt="PhotosvideosImg" id="thanksPhotosvideosImg" style="height: 100px;" />
                    </div>
                  @endif
                  @if($user->gallery['videos'])
                    <div class='file_upload2' id='f2'>
                     <br/><input name='gallery_video'  type='file' id="videoId22" value=""/>
                     <input type="hidden" name="video_id" id="videoId" value="">
                     <video controls>
                      <source src="#" type="video/mp4" id="thanksPhotosvideosImg1"  style="height: 100px;">
                      <source src="">
                    </video>
                    </div>
                  @endif
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.to_close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.send')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>


<div class="modal fade" id="deletePhotosVideos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿@lang('labels.frontend.constructor.profile.are_you_sure_you_want_to_delete')?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.delete_photovideos_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">
                @if($user->gallery['images'])
                   <div class='file_upload' id='f1'>
                    <img src="#" alt="PhotosvideosImg" id="thanksPhotosvideosImg_del" style="height: 100px;" />
                    <input type="text" name="gall_id" id="gallId_del" value="" readonly="">
                    </div> 
                    @endif
                    @if($user->gallery['videos'])
                    <div class='file_upload111' id='f2'>
                      <video controls>
                        <source src="#" type="video/mp4" id="thanksPhotosvideosImg_del1"  style="height: 100px;">
                        <source src="">
                        <input type="text" name="video_id" id="vdoId_del" value="">
                      </video>
                    </div>
                    @endif
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.cancel')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.want_to_delete')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>
<!--///////////////////UPDATE DELETE Photos Videos modal End here/////////////////////////-->


<!--///////////////////Police Record modal start here/////////////////////////-->
<div class="modal fade" id="policeRecordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.constructor.profile.upload_police_record')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">
                        <div class="cer-div">
                            <label>@lang('labels.frontend.constructor.profile.type_of_register'):</label>
                              <input type="radio" name="record_type" checked="" value="0"> @lang('labels.frontend.constructor.profile.image') 
                              <input type="radio" name="record_type" value="1"> @lang('labels.frontend.constructor.profile.document')
                        </div>
                        <div class='file_upload' id='f1'><input name='police_records[]' type='file'/></div>
                        <div id='pol_file_tools'>
                              <i class="fa fa-plus-circle" id='poladd_file' aria-hidden="true">@lang('labels.frontend.constructor.profile.add_new_archive')</i>
                              <i class="fa fa-minus-circle" id='poldel_file' aria-hidden="true">@lang('labels.frontend.constructor.profile.erase')</i>
                        </div>

              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.save_changes')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>
<!--///////////////////Police Record modal end here/////////////////////////-->
<!--///////////////////Profile Description Modal start here/////////////////////////-->
<!-- Modal -->
<div class="modal fade" id="profileDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.constructor.profile.profile_description')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
                {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
                <div class="modal-body">
                      <div class="edit-form">
                            <div class="form-edit">
                                  <label>@lang('labels.frontend.constructor.profile.profile_description')</label>
                                    <textarea name="profile_description"><?php if(isset($user) && !empty($user->profile_description)){echo $user->profile_description;}?>
                                    </textarea>
                            </div>
                      </div>
                </div>
                <div class="modal-footer">
                      <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
                      <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.save_changes')</button>
                </div>
        </form>
    </div>
  </div>
</div>
<!--///////////////////Profile Description modal end here/////////////////////////-->

<!-- ////////////////Fund balance Modal Start ////////////////-->

<div class="modal fade" id="balanceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.constructor.profile.fund_balance')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
                {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
                <div class="modal-body">
                      <div class="edit-form">
                            <div class="form-edit">
                                  <label>@lang('labels.frontend.constructor.profile.account_balance')</label>
                                  <input type="number" name="current_balance" value="<?php if(isset($bonus) && !empty($bonus->current_balance)){echo $bonus->current_balance;}?>">
                            </div>
                      </div>
                </div>
                <div class="modal-footer">
                      <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
                      <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.send')</button>
                </div>
        </form>
    </div>
  </div>
</div>

<!-- Fund balance Modal End  -->

<!-- 
<div class="modal fade" id="updateprofileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Profile Description</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
                {{ html()->form('POST', route('frontend.contractor.my-profile.update_profile_picture'))->attribute('enctype', 'multipart/form-data')->open() }}
                <div class="modal-body">
                      <div class="edit-form">
                            <div class="form-edit">
                                  <label>Profile Picture</label>
                                    <input type="file" class="" id="customFile" name="avatar_location" placeholder="Seleccionar">
                            </div>
                      </div>
                </div>
                <div class="modal-footer">
                      <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn opp-btn">Enviar</button>
                </div>
        </form>
    </div>
  </div>
</div> -->


<!--Profile Picture Modal-->

   <div id="insertimageModal" class="modal" role="dialog">
           <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" style="margin-left: 0%">&times;</button>
                  <h4 class="modal-title" style="margin-right: 30%">@lang('labels.frontend.constructor.profile.crop_and_insert_image')</h4>
                </div>
                <div class="modal-body">

                  <div class="row">
                    <div class="col-md-8 text-center">
                      <div id="image_demo" style="width:455px; margin-top:30px"></div>
                    </div>
                    <div class="col-md-4" style="padding-top:30px;">
                  <br />
                  <br />
                  <br/>
                      
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                <button class="btn btn-success getProfile crop_image">@lang('labels.frontend.constructor.profile.clip_image')</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
                </div>
              </div>
            </div>
    </div>
 <!--Profile Picture Modal-->



<!--///////////////////services offered modal start here/////////////////////////-->
<!-- Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
          <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.constructor.profile.selection_of_services_offered')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>

            {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
            <div class="modal-body">
                  <div class="edit-form">
                        <div class="form-edit">
                            <label>@lang('labels.frontend.company.profile.select_services')</label>

                            <div id="services" class="form-edit">
                            <ul class="area-list meta-list multi-cities-list ">

                              <select name="services[]" id="multi-select-services" multiple="multiple" >
                                   <?php
                                  if(!empty($combineddata))
                                  {
                                      foreach ($combineddata as $key_com => $val_com)
                                      {  

                                          if(in_array($val_com['id'],$serviceIds))
                                          {
                                        ?>

                                        <option data-serv="{{$val_com['id']}}" class="parent_city parent_option parent_option pa_op_{{$val_com['id']}}" value="{{$val_com['id']}}" selected="true" value="{{$val_com['id']}}">{{$val_com['name']}}</option>

                                        <?php $i=1; foreach ($val_com['subservices'] as $sdata)
                                        { ?>
                                            <option data-serv="{{$val_com['id']}}" class="child_city child_option ch_op_{{$val_com['id']}}" value="<?php echo $val_com['id'].','.$sdata['sub_service_id']?>">{{$sdata['name']}}</option>

                                        <?php $i++;} ?>
                                           
                                        <?php }else { ?>


                                        <option data-serv="{{$val_com['id']}}" class="parent_city parent_option parent_option pa_op_{{$val_com['id']}}" value="{{$val_com['id']}}" value="{{$val_com['id']}}">{{$val_com['name']}}</option>

                                        <?php $i=1; foreach ($val_com['subservices'] as $sdata)
                                        { ?>
                                            <option data-serv="{{$val_com['id']}}" class="child_city child_option ch_op_{{$val_com['id']}}" value="<?php echo $val_com['id'].','.$sdata['sub_service_id']?>">{{$sdata['name']}}</option>

                                        <?php $i++;} ?>


                                      <?php } 

                                    }
                                  }
                                  ?>
                                </select>
                            </ul>
                          </div>
                        </div>
                  </div>
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                  <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.send')</button>
            </div>
         {{ html()->form()->close() }}
    </div>
  </div>
</div>
<!--///////////////////services offered modal end here/////////////////////////-->
<!--///////////////////Coverage Area modal start here/////////////////////////-->
<div class="modal fade" id="coverageAreaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
          <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.company.profile.coverage_area')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
          <div class="modal-body">
                  <div class="edit-form">
                        <div class="cer-div">
                            <label>@lang('labels.frontend.company.profile.service_area_nationwide'):</label>

                              <input type="checkbox" id="inWholeCountryFalse" value="1" name="whole_country">
                        </div>

                      <label>@lang('labels.frontend.company.profile.service_area_in_the_provinces'):</label>

                      <div id="citiesArea" class="form-edit selectCityProv">
                    <ul class="area-list meta-list multi-cities-list">
                      <select name="proviences[]" id="multi-select-proviences" multiple="multiple">

                           <?php
                          if(!empty($mixdata))
                          {
                           // dd($mixdata);
                              foreach ($mixdata as $key => $val)
                              { 
                                if(in_array($val['id'],$serviceAreaIds)) { ?>


                                  <option data-prov="{{ $val['id'] }}" class="parent_city parent_prov_{{ $val['id'] }}" selected="true" value="{{$val['id']}}">{{$val['name']}}</option>

                                  <?php $i=1; foreach ($val['cities'] as $cdata)
                                  {
                                  ?>
                                  <option data-prov="{{ $val['id'] }}" class="child_city child_prov_{{ $val['id'] }}" value="<?php echo $val['id'].','.$cdata['city_id']?>">{{$cdata['name']}}</option>

                                  <?php $i++;}  } else {  ?>

                                  <option data-prov="{{ $val['id'] }}" class="parent_city parent_prov_{{ $val['id'] }}" value="{{$val['id']}}">{{$val['name']}}</option>

                                  <?php $i=1; foreach ($val['cities'] as $cdata)
                                  {
                                  ?>
                                  <option data-prov="{{ $val['id'] }}" class="child_city child_prov_{{ $val['id'] }}" value="<?php echo $val['id'].','.$cdata['city_id']?>">{{$cdata['name']}}</option>

                                  <?php $i++;}  
                                }
                              }
                            }
                          ?>
                        </select>
                    </ul>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
                <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.send')</button>
          </div>
           {{ html()->form()->close() }}
    </div>
  </div>
</div>
<!--///////////////////Coverage Area modal end here/////////////////////////-->
<!--///////////////////Gallery start here/////////////////////////-->
<div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
          <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.company.profile.upload_photos_and_videos')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>
          {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
          <div class="modal-body">
                <div class="edit-form">
                    <div class="form-edit">
                            <label>@lang('labels.frontend.company.profile.images'):</label>
                            <div class='file_upload1' id='f1'><input name='images_gallery[]' type='file'/></div>
                    </div>
                      <div id='image_file_tools'>
                            <i class="fa fa-plus-circle" id='addGalleryImage' aria-hidden="true">@lang('labels.frontend.company.profile.add_new_image_file')</i>
                            <i class="fa fa-minus-circle" id='deleteGalleryImage' aria-hidden="true">@lang('labels.frontend.company.profile.erase')</i>
                      </div>
                        <br/>
                      <div class="form-edit">
                            <label>@lang('labels.frontend.company.profile.videos'):</label>
                            <div class='file_upload2' id='f2'><input name='videos_gallery[]' type='file'/></div>
                      </div>
                      <div id='videos_file_tools'>
                            <i class="fa fa-plus-circle" id='addGalleryVideo' aria-hidden="true">@lang('labels.frontend.company.profile.add_new_video_file')</i>
                            <i class="fa fa-minus-circle" id='deleteGalleryVideo' aria-hidden="true">@lang('labels.frontend.company.profile.erase')</i>
                      </div>

              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.to_close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>
</div>
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>


<style type="text/css">
  .div_show { display:none; }
  .div_show1 { display:none; }
  .div_show2 { display:none; }
  .div_show3 { display:none; }
</style>
<script type="text/javascript">
$(function(){
    $(".div_show").slice(0, 3).show(); // select the first ten
    $("#load").click(function(e){ // click event for load more
        e.preventDefault();
        $(".div_show:hidden").slice(0, 3).show(); // select next 10 hidden divs and show them
        if($(".div_show:hidden").length < 0){ // check if any hidden divs still exist
            alert("No more divs"); // alert if there are none left
        }
    });
});
</script>
<script type="text/javascript">
$(function(){
    $(".div_show1").slice(0, 3).show(); // select the first ten
    $("#load1").click(function(e){ // click event for load more
        e.preventDefault();
        $(".div_show1:hidden").slice(0, 3).show(); // select next 10 hidden divs and show them
        if($(".div_show1:hidden").length < 0){ // check if any hidden divs still exist
            alert("No more divs"); // alert if there are none left
        }
    });
});
</script>
<script type="text/javascript">
$(function(){
    $(".div_show2").slice(0, 3).show(); // select the first ten
    $("#load2").click(function(e){ // click event for load more
        e.preventDefault();
        $(".div_show2:hidden").slice(0, 3).show(); // select next 10 hidden divs and show them
        if($(".div_show2:hidden").length < 0){ // check if any hidden divs still exist
            alert("No more divs"); // alert if there are none left
        }
    });
});
</script>
<script type="text/javascript">
$(function(){
    $(".div_show3").slice(0, 3).show(); // select the first ten
    $("#load3").click(function(e){ // click event for load more
        e.preventDefault();
        $(".div_show3:hidden").slice(0, 3).show(); // select next 10 hidden divs and show them
        if($(".div_show3:hidden").length < 0){ // check if any hidden divs still exist
            alert("No more divs"); // alert if there are none left
        }
    });
});
</script>

<!--///////////////////Gallery modal end here/////////////////////////-->


<style type="text/css">
.alert-danger
{
  z-index: 10 !important;
}
.alert-success
{
  z-index: 10 !important;
}
</style>

<script type="text/javascript">
  $maximumPoints  = 100;
        if ( is_user_logged_in() ) {                 
             if($twitterHandle!=""){
                $hasCompletedTwitterHandle = 10;

             }
             if($linkedinHandle!=""){
                $hasCompletedLinkedinHandle = 10;

             }
             if($googleplusHandle!=""){
                $hasCompletedGoogleplusHandle = 10;

             }
             if($website_url!=""){
                $hasCompletedWebsite_url = 10;

             }

             $percentage = ($hasCompletedTwitterHandle+$hasCompletedLinkedinHandle+$hasCompletedGoogleplusHandle+$hasCompletedWebsite_url)*$maximumPoints/100;
             echo "Your percentage of profile completenes is".$percentage."%";
             echo "<div style='width:100px; background-color:white; height:30px; border:1px solid #000;'><div style='width:".$percentage."px; background-color:red; height:30px;'></div></div>";
        } 
</script>

@endsection
