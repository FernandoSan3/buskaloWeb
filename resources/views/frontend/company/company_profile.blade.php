@extends('frontend.layouts.app')
@section('content')
<div class="header-profile">
    <div class="top-header-profile">
      <div class="top-profile-info">
      <div class="media">
       <?php 
          $pic= 'img/frontend/user.png';

          if(isset($company) && !empty($company->avatar_location)){$pic= 'img/company/profile/'.$company->avatar_location;} 

        ?>
        <img src="{{ url($pic) }}" class="pro-img">
        <div class="media-body">
          <h4>{{$company->username}}</h4>
          @if($company->approval_status==1 && $company->is_confirm_reg_step==1)
            <p><img src="{{url('/img/frontend/check3.png')}}" class="sm-img pr-1">@lang('labels.frontend.company.profile.verified_profile')</p>
          @endif

          <!-- <p class="mb-0">Dispedora de Lorem ipsum</p>
          <p><img src="http://localhost/buskalo/www/public/img/frontend/check3.png" class="sm-img pr-1"> Lorem ipsum</p> -->
        </div>
      </div>
      </div>
    </div>
  <div id="wrapper" class="toggled left-sidebar">
    <!-- Sidebar -->

    @include('frontend.company.profile_sidebar')
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="container-fluid">

        <div class="right-sidebar ">
          <!-- Tab panes -->
          <div class="tab-content">
            
            <div class="top-profile-sec pro-wrapper">
            <img src="{{ url('img/frontend/account-setting.png') }}" class="img-fluid">
              <span>@lang('labels.frontend.company.profile.my_account')</span>
            </div>
            <div class="">
              <div class="row">
                <div class="col-md-6">
                  <div class="data-info pro-wrapper">
                    <img src="{{url('img/frontend/user-profile.png')}}" class="img-fluid">
                    <h5>{{$status_bar}}% @lang('labels.frontend.company.profile.completed')</h5>
                    <div class="progress-mg">
                      <div class="progress" style="height:30px">
                        <div class="progress-bar" style="width:{{$status_bar}}%;height:30px"></div>
                      </div>
                    </div>
                    <p><!-- Completed information --> <span class="theme-clr">@lang('labels.frontend.company.profile.profile_information')</span> <!-- Lorem ipsum dolar sit amet --></p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="data-info pro-wrapper">
                    <div class="border-bottom pb-3">
                      <img src="{{url('img/frontend/save-money.png')}}" class="img-fluid">
                      <span class="s_price"> <?php if(isset($company) && !empty($company->pro_credit)){echo  number_format($company->pro_credit,2);} else{ echo '0.00';}?> </span>
                    </div>
                    <a href="{{url('subscription')}}"><button class="btn or-btn-outline"> <i class="fa fa-shopping-cart pr-1"></i> @lang('labels.frontend.company.profile.buy_credits')</button></a>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane active" id="miperfil">
              <div class="profile-detail-sec">
                <div class="profile-head">
                  <div class="top-edit">

                    <!-- <button type="btn" data-toggle="modal" data-target="#updateprofileModal" class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.company.profile.edit')</button> -->

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
                                  if(isset($company) && !empty($company->avatar_location)){$pic= 'img/company/profile/'.$company->avatar_location;} ?>
                                  <img id="thumbnil" src="{{url($pic)}}"  class="pro-img" style="height: 98px !important; width: 98px !important; padding: 0 !important " alt="image">
                                </div>
                              </span>
                            </button>
                        </div>

                        <!--Profile with crop-->

                    <div class="profile-text" style="margin-top: -10px !important;">
                      <h5 class="mt-0"><?php echo isset($company->username) && !empty($company->username) ? $company->username : ''; ?></h5>
                      <h6><?php echo isset($company->profile_title) && !empty($company->profile_title) ? $company->profile_title : ''; ?></h6>
                    </div>
                  </div>
                  
                   <input type="hidden" id="contractorUserId" name="user_id" value="{{ isset($company) && !empty($company->id) ? $company->id : '0'  }}">
 

                  <div class="row mt-5">
                    <!--**********Basic Information Update End Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.company.profile.basic_information')</h3>
                        <button type="btn" data-toggle="modal" data-target="#infoModal"
                        class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.company.profile.edit')</button>
                      </div>
                      <ul class="info-list ul-first">
                        @if(isset($company->ruc_no) && !empty($company->ruc_no))
                            <li><h6>@lang('labels.frontend.company.redirect_company.ruc_o_rise')</h6> <?php echo isset($company->ruc_no) && !empty($company->ruc_no) ? $company->ruc_no : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->year_of_constitution) && !empty($company->year_of_constitution))
                            <li><h6>@lang('labels.frontend.company.redirect_company.year_of_incorporation')</h6> <?php echo isset($company->year_of_constitution) && !empty($company->year_of_constitution) ? $company->year_of_constitution : ''; ?>
                            </li>
                        @endif
                        @if(isset($company->username) && !empty($company->username))
                            <li><h6> @lang('labels.frontend.company.profile.username')</h6> <?php echo isset($company->username) && !empty($company->username) ? $company->username : ''; ?>
                            </li>
                        @endif
                        @if(isset($company->profile_title) && !empty($company->profile_title))
                            <li><h6>@lang('labels.frontend.company.profile.profile_title')</h6> <?php echo isset($company->profile_title) && !empty($company->profile_title) ? $company->profile_title : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->legal_representative) && !empty($company->legal_representative))
                            <li><h6>@lang('labels.frontend.company.redirect_company.legal_representative')</h6> <?php echo isset($company->legal_representative) && !empty($company->legal_representative) ? $company->legal_representative : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->identity_no) && !empty($company->identity_no))
                            <li><h6>@lang('labels.frontend.company.profile.identity_no')</h6> <?php echo isset($company->identity_no) && !empty($company->identity_no) ? $company->identity_no : ''; ?></li>
                        @endif

                        @if(isset($company->website_address) && !empty($company->website_address))
                            <li><h6>@lang('labels.frontend.company.redirect_company.web_address')</h6> <?php echo isset($company->website_address) && !empty($company->website_address) ? $company->website_address : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->email) && !empty($company->email))
                            <li><h6>@lang('labels.frontend.company.profile.email_identification')</h6> <?php echo isset($company->email) && !empty($company->email) ? $company->email : ''; ?>
                            </li>
                        @endif

                        @if(isset($totalEmployee) && !empty($totalEmployee))
                            <li><h6>@lang('labels.frontend.company.profile.total_employees')</h6> <?php echo isset($totalEmployee) && !empty($totalEmployee) ? count($totalEmployee) : '0'; ?>
                            </li>
                        @endif

                        @if(isset($company->dob) && !empty($company->dob))
                            <li><h6>@lang('labels.frontend.company.profile.date_of_birth')</h6> <?php echo isset($company->dob) && !empty($company->dob) ? date('dS F Y', strtotime($company->dob)) : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->address) && !empty($company->address))
                            <li><h6>@lang('labels.frontend.company.profile.address')</h6> <?php echo isset($company->address) && !empty($company->address) ? $company->address : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->office_address) && !empty($company->office_address))
                            <li><h6>@lang('labels.frontend.company.profile.office_address')</h6> <?php echo isset($company->office_address) && !empty($company->office_address) ? $company->office_address : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->other_address) && !empty($company->other_address))
                            <li><h6>@lang('labels.frontend.company.profile.other_direction')</h6> <?php echo isset($company->other_address) && !empty($company->other_address) ? $company->other_address : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->mobile_number) && !empty($company->mobile_number))
                            <li><h6>@lang('labels.frontend.company.profile.mobile_phone_number')</h6> <?php echo isset($company->mobile_number) && !empty($company->mobile_number) ? $company->mobile_number : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->landline_number) && !empty($company->landline_number))
                            <li><h6>@lang('labels.frontend.company.profile.landline_number') </h6> <?php echo isset($company->landline_number) && !empty($company->landline_number) ? $company->landline_number : ''; ?>
                            </li>
                        @endif

                        @if(isset($company->office_number) && !empty($company->office_number))
                            <li><h6>@lang('labels.frontend.company.profile.office_number')</h6> <?php echo isset($company->office_number) && !empty($company->office_number) ? $company->office_number : ''; ?>
                            </li>
                         @endif
                      </ul>
                    </div>
                    <!--**********Basic Information Update End Here************-->
                    <!--**********Payment Method Update Start Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.company.profile.payment_methods')</h3>
                        <button type="btn" data-toggle="modal" data-target="#paymentMethodModal"
                        class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.company.profile.edit')</button>
                      </div>
                      <div class="meta-list">
                        <h5>@lang('labels.frontend.company.profile.select_payment_methods')</h5>
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
                        <h3>@lang('labels.frontend.company.profile.social_media')</h3>
                        <button type="btn" data-toggle="modal" data-target="#socialMediaModal"
                        class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.company.profile.edit')</button>
                      </div>
                      <ul class="info-list">
                        <li><h6>@lang('labels.frontend.company.profile.facebook') +</h6><a href="<?php if(isset($social)){echo $social->facebook_url;}?>" class="orange"> <?php if(isset($social)){echo $social->facebook_url;}?></a></li>
                        <li><h6>@lang('labels.frontend.company.profile.instagram') +</h6><a href="<?php if(isset($social)){echo $social->instagram_url;}?>" class="orange"><?php if(isset($social)){echo $social->instagram_url;}?></a></li>
                        <li><h6>@lang('labels.frontend.company.profile.linkedin') +</h6><a href="<?php if(isset($social)){echo $social->linkedin_url;}?>" class="orange"> <?php if(isset($social)){echo $social->linkedin_url;}?></a></li>
                        <li><h6>@lang('labels.frontend.company.profile.twitter') +</h6><a href="<?php if(isset($social)){echo $social->twitter_url;}?>" class="orange"> <?php if(isset($social)){echo $social->twitter_url;}?></a></li>
                        <li><h6>@lang('labels.frontend.company.profile.other_url') +</h6><a href="<?php if(isset($social)){echo $social->other;}?>" class="orange"> <?php if(isset($social)){echo $social->other;}?></a></li>
                      </ul>
                    </div>
                    <!--**********Social Networks END Here************-->
                    <!--**********Certificaciones-Curso Start Here************-->
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.company.profile.certifications_courses')</h3>
                        <button type="btn" data-toggle="modal" data-target="#certificationModal"
                        class="edit-btn"><i class="fa fa-plus"></i>&nbsp;@lang('labels.frontend.company.profile.add')</button>
                      </div>
                      <!-- <ul class="info-list">
                        <li><h6>Certificacion Title Here</h6><a data-toggle="modal" data-target="#certificationModal"  class="orange">+ Image</a></li>
                      </ul> -->
                      <div class="photo-galley row">
                        <?php if(isset($company->cetifications) && !empty($company->cetifications))
                        {
                        foreach ($company->cetifications['certification_courses'] as $key_course => $value_course) {

                        ?>
                        <div class="bg-light col-sm-4 div_show1">
                          <div class="foto-img">
                           @if($value_course['file_type'] == 1)
                            <img src="{{ url('img/frontend/file_icon.png') }}" style="width: 53px;">
                             <?php  $documentname = $value_course['file_name'];
                                echo basename($documentname);
                              ?>

                            <a href="{{ $value_course['file_name'] }}" class="orange"></a>
                            <div class="hover-icons">
                              <ul>
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#editCertificate" data-filename="{{ $value_course['file_name'] }}" data-certid="{{$value_course['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#deleteCertificate" data-filename="{{ $value_course['file_name'] }}" data-certid="{{$value_course['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>
                            @else
                           <img src="{{ $value_course['file_name'] }}">

                           <a href="{{ $value_course['file_name'] }}" class="orange"></a>
                            <div class="hover-icons">
                              <ul>
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#editCertificate" data-filename="{{ $value_course['file_name'] }}" data-certid="{{$value_course['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#deleteCertificate" data-filename="{{ $value_course['file_name'] }}" data-certid="{{$value_course['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>
                            @endif
                          </div>
                        </div>
                        <?php   }
                        } else {
                        ?>
                        <div class="bg-light">
                          <div class="foto-img">
                            <img src="">
                            <a href="#" class="orange"><p>+ @lang('labels.frontend.company.profile.agegar_galeria')</p></a>
                          </div>
                        </div>
                        <?php
                        }
                        ?>
                        @if(count($company->cetifications['certification_courses'])>3)
                            <a href="javascript:void(0)" id="load1" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.company.profile.see_all')</a>
                        @endif
                      </div>
                    </div>

                    <!--**********Police REcord Start Here************-->
                   
                    <div class="pro-info col-md-6">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.company.profile.police_record')</h3>
                        <button type="btn" data-toggle="modal" data-target="#policeRecordModal"
                        class="edit-btn"><i class="fa fa-plus"></i>&nbsp;@lang('labels.frontend.company.profile.add')</button>
                      </div>
                      <!-- <ul class="info-list">
                        <li><h6>Certificacion Title Here</h6><a data-toggle="modal" data-target="#certificationModal"  class="orange">+ Image</a></li>
                      </ul> -->
                      <div class="photo-galley row">
                        <?php if(isset($company->cetifications) && !empty($company->cetifications))
                        {
                        foreach ($company->cetifications['police_records'] as $key_police => $value_police) {

                        ?>
                        <div class="bg-light col-sm-4 div_show3">
                          <div class="foto-img">
                            @if($value_police['file_type'] == 1)
                            <img src="{{ url('img/frontend/file_icon.png') }}" style="width: 53px;">
                            <?php  $documentname = $value_police['file_name'];
                                echo basename($documentname);
                              ?>

                            <a href="{{ $value_police['file_name'] }}" class="orange"></a>
                            <div class="hover-icons">
                              <ul>
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#editPoliceRecord" data-filename="{{ $value_police['file_name'] }}" data-certid="{{$value_police['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#deletePoliceRecord" data-filename="{{ $value_police['file_name'] }}" data-polid="{{$value_police['id']}}"></i></a></li>
                              </ul>
                            </div>
                            @else
                           <img src="{{ $value_police['file_name'] }}">

                           <a href="{{ $value_police['file_name'] }}" class="orange"></a>
                            <div class="hover-icons">
                              <ul>
                                <li><a href="#" data-toggle="modal" data-target="#editPoliceRecord" data-filename="{{ $value_police['file_name'] }}" data-polid="{{$value_police['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="javascript:void:(0)" data-toggle="modal" data-target="#deletePoliceRecord" data-filename="{{ $value_police['file_name'] }}" data-polid="{{$value_police['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>
                            @endif
                          </div>
                        </div>
                        <?php   }
                        } else {
                        ?>
                        <div class="bg-light">
                          <div class="foto-img">
                            <img src="">
                            <a href="#" class="orange"><p>+ @lang('labels.frontend.company.profile.agegar_galeria')</p></a>
                          </div>
                        </div>
                        <?php
                        }
                        ?>
                    @if(count($company->cetifications['police_records'])>3)
                        <a href="javascript:void(0)" id="load3" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.company.profile.see_all')</a>
                    @endif
                      </div>
                    </div>

                    <!--**********Police REcord End Here************-->
                    <!--**********Profile description Start Here************-->
                    <div class="pro-info col-md-6">
                        <div class="pro-heading">
                          <h3>@lang('labels.frontend.company.profile.professional')</h3>
                          <button type="btn" data-toggle="modal" data-target="#profileDescriptionModal"
                          class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.company.profile.edit')</button>
                        </div>
                        <ul class="info-list">
                          <li><h6>Breve presentación</h6>
                            <p><?php 
                            if(isset($company) && !empty($company->profile_description)){
                              echo $company->profile_description;
                            }?></p>
                          </li>
                        </ul>
                    </div>
                      <!--**********Profile description End Here************-->

                      <!--**********Offered Services Start Here************-->

                      <div class="pro-info col-md-6">
                        <div class="pro-heading">
                          <h3>@lang('labels.frontend.company.profile.services')</h3>
                          <button type="btn" data-toggle="modal" data-target="#serviceModal"
                          class="edit-btn"><i class="fa fa-plus"></i>&nbsp;@lang('labels.frontend.company.profile.edit')</button>
                        </div>
                        <ul class="area-list">
                            <!-- <select name="services[]" id="services_id">
                              <option>servicios de instalacion electrica</option>
                              <?php foreach ($services as $k_service => $v_service) { ?>
                              <option value="{{$v_service->id}}">{{$v_service->es_name}}</option>
                              <?php } ?>
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
                          <h3>@lang('labels.frontend.company.profile.cover_area')</h3>
                          <button type="btn" data-toggle="modal" data-target="#coverageAreaModal"
                          class="edit-btn"><i class="fa fa-plus"></i>&nbsp;@lang('labels.frontend.company.profile.edit')</button>
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
                          <h3>@lang('labels.frontend.company.profile.photos_and_videos')</h3>
                          <button type="btn" data-toggle="modal" data-target="#galleryModal"
                          class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.company.profile.edit')</button>
                        </div>
                        <div class="photo-galley row">
                          <?php if(isset($company->gallery) && !empty($company->gallery))
                          {
                          foreach ($company->gallery['images'] as $key_images => $value_images) {
                          ?>
                            <div class="bg-light col-sm-4 div_show">
                              <div class="foto-img">
                                <img src="{{ $value_images['file_name'] }}">
                                <a href="{{ $value_images['file_name'] }}" class="orange"></a>
                                <div class="hover-icons">
                                <ul>
                                  <li><a href="#" data-toggle="modal" data-target="#editPhotosVideos" data-filename="{{ $value_images['file_name'] }}" data-pvid="{{$value_images['id']}}"><i class="fa fa-edit"></i></a></li>

                                  <li><a href="#" data-toggle="modal" data-target="#deletePhotosVideos" data-filename="{{ $value_images['file_name'] }}" data-pvid="{{$value_images['id']}}"><i class="fa fa-trash"></i></a></li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <?php   }
                          foreach ($company->gallery['videos'] as $key_vedios => $value_vedios) {

                          ?>
                          <div class="foto-img col-sm-4 div_show">
                              <video width="100%" controls>
                                   <source src="{{ $value_vedios['file_name'] }}" type="video/mp4">
                              </video>
                            <a href="{{ $value_vedios['file_name'] }}" class="orange"></a>
                            <div class="hover-icons hover-icon-2">
                              <ul>
                                <li><a href="#" data-toggle="modal" data-target="#editPhotosVideos" data-filename1="{{ $value_vedios['file_name'] }}" data-vvid="{{$value_vedios['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="#" data-toggle="modal" data-target="#deletePhotosVideos" data-filename1="{{ $value_vedios['file_name'] }}" data-vvid="{{$value_vedios['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>
                           </div>
                         <?php }?>

                          <?php
                          } else {
                          ?>
                          <div class="bg-light">
                            <div class="foto-img">
                              <img src="">
                              <a href="#" class="orange"><p>+ @lang('labels.frontend.company.profile.agegar_galeria')</p></a>
                              <div class="hover-icons">
                              <ul>
                                <li><a href="#" data-toggle="modal" data-target="#editPhotosVideos" data-filename="{{ $value_images['file_name'] }}" data-pvid="{{$value_images['id']}}"><i class="fa fa-edit"></i></a></li>

                                <li><a href="#" data-toggle="modal" data-target="#deletePhotosVideos" data-filename="{{ $value_images['file_name'] }}" data-pvid="{{$value_images['id']}}"><i class="fa fa-trash"></i></a></li>
                              </ul>
                            </div>
                            </div>
                          </div>
                          <?php
                          }
                          ?>
                        </div>
                        @if(count($company->gallery['videos'])>3)
                            <a href="javascript:void(0)" id="load" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.company.profile.see_all')</a>
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
                          <h3>@lang('labels.frontend.company.profile.funds_balance')</h3>
                          <!-- <button type="btn" data-toggle="modal" data-target="javascript::void(0)"
                          class="edit-btn"><i class="fa fa-edit"></i>@lang('labels.frontend.company.profile.edit')</button> -->
                        </div>
                        <ul class="info-list">
                          <li><h6>@lang('labels.frontend.company.profile.account_balance')</h6> 
                            <?php if(isset($company) && !empty($company->pro_credit)){echo  number_format($company->pro_credit,2);} else{ echo '0.00';}?>
                            <!-- @if(isset($bonus) && !empty($bonus->current_balance))
                                $ {{$bonus->current_balance}}
                            @else
                                $ 0
                            @endif -->
                           </li>
                          <a href="#" class="orange"></a>
                        </ul>
                      </div>
                    </div>
                    <!-- <div class="text-center">
                      <button class="btn sub-btn">Submit</button>
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
        <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.company.profile.modal_title')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
        <button type="button" class="btn btn-primary">@lang('labels.frontend.company.profile.save_changes')</button>
      </div>
    </div>
  </div>
</div>
<!--///////////////////Basic Information modal start here/////////////////////////-->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.company.profile.update_basic_information')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>

      {{ html()->form('POST', route('frontend.company.company_profile.update_basicinfo'))->attribute('enctype', 'multipart/form-data')->open() }}
      <div class="modal-body">
        <div class="edit-form">
           <div class="form-edit">
            <!-- Form -->
            <label>* @lang('labels.frontend.company.redirect_company.ruc_o_rise')</label>
            <input type="text" name="ruc_no" value="<?php if(isset($company) && !empty($company->ruc_no)){echo $company->ruc_no;}?>" class="" required="" id="rucNo">
           
          </div>

           <div class="form-edit">
            <!-- Form -->
            <label>* @lang('labels.frontend.company.redirect_company.year_of_incorporation')</label>
            <input type="text" name="year_of_constitution" required="" value="<?php if(isset($company) && !empty($company->year_of_constitution)){echo $company->year_of_constitution;}?>" id="year_of_constitution">
          </div>
          <div class="form-edit">
            <label>*@lang('labels.frontend.company.profile.username')</label>
            <input type="text" name="username" required="" value="<?php if(isset($company) && !empty($company->username)){echo $company->username;}?>" id="username">
          </div>
          <div class="form-edit">
            <label>@lang('labels.frontend.company.profile.campo_profesion')</label>
            <input type="text" name="profile_title" value="<?php if(isset($company) && !empty($company->profile_title)){echo $company->profile_title;}?>" id="profile">
          </div>

           <div class="form-edit">
            <!-- Form -->
            <label>* @lang('labels.frontend.company.redirect_company.legal_representative')</label>
            <input type="text" name="legal_representative" required="" value="<?php if(isset($company) && !empty($company->legal_representative)){echo $company->legal_representative;}?>" id="legal_representative">
          </div>

          <div class="form-edit">
            <!-- Form -->
            <label>@lang('labels.frontend.company.profile.identity_no')</label>
            <input type="text" name="identity_no" value="<?php if(isset($company) && !empty($company->identity_no)){echo $company->identity_no;}?>" id="identity_no" maxlength="10">
          </div>

           <div class="form-edit">
            <!-- Form -->
            <label>@lang('labels.frontend.company.redirect_company.web_address')</label>
            <input type="text" name="website_address" value="<?php if(isset($company) && !empty($company->website_address)){echo $company->website_address;}?>" id="website_address">
          </div>

          
         <!--  <div class="form-edit">
            <label>*@lang('labels.frontend.company.profile.profile_title')</label>
            <input type="text" name="profile_title" required="" value="<?php if(isset($company) && !empty($company->profile_title)){echo $company->profile_title;}?>" id="profile_title">
          </div>
          <div class="form-edit">
            <label>*@lang('labels.frontend.company.profile.date_of_birth')</label>
            <input type="text" required="" class="form_date" name="dob" value="<?php if(isset($company) && !empty($company->dob)){echo $company->dob;}?>" id="datepicker">
          </div> -->
          <div class="form-edit">
            <label>*@lang('labels.frontend.company.profile.address')</label>
            <input type="text" required="" name="address" value="<?php if(isset($company) && !empty($company->address)){echo $company->address;}?>" id="address">
          </div>
          <div class="form-edit">
            <label>@lang('labels.frontend.company.profile.office_address')</label>
            <input type="text" name="office_address" value="<?php if(isset($company) && !empty($company->office_address)){echo $company->office_address;}?>" id="office_address">
          </div>
          <!-- <div class="form-edit">
            <label>@lang('labels.frontend.company.profile.other_direction')</label>
            <input type="text" name="other_address" value="<?php if(isset($company) && !empty($company->other_address)){echo $company->other_address;}?>" id="other_address">
          </div> -->
          <div class="form-edit">
            <label>*@lang('labels.frontend.company.profile.mobile_phone_number')</label>
            <input type="text" required="" name="mobile_number" value="<?php if(isset($company) && !empty($company->mobile_number)){echo $company->mobile_number;}?>" id="mobile_phone_number" maxlength="10">
          </div>
          <div class="mobile-number-msg" style="text-align: center;"></div>
          <div class="form-edit">
            <label>@lang('labels.frontend.company.profile.landline_number')</label>
            <input type="text" name="landline_number" value="<?php if(isset($company) && !empty($company->landline_number)){echo $company->landline_number;}?>" id="landlineNumber">
            <br/><div class="landline-number-msg" style="text-align: center;"></div>
          </div>
          <div class="form-edit">
            <label>@lang('labels.frontend.company.profile.office_number')</label>
            <input type="text"  name="office_number" value="<?php if(isset($company) && !empty($company->office_number)){echo $company->office_number;}?>" id="office_number">
            <!-- End -->
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
        <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
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
      <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.company.profile.update_pago_method')</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
    </div>

    {{ html()->form('POST', route('frontend.company.company_profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
    <div class="modal-body">
      <div class="edit-form">
                <div class="form-radio">
                <?php
                  if(isset($paymentMethods) && !empty($paymentMethods)) {
                  //echo "<pre>"; print_r($paymentMethodId); die;
                  foreach ($paymentMethods as $k => $v_menthod)
                  {
                   if(in_array($v_menthod->id,$paymentMethodId))
                          {
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
                          <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                          <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
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
                        <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.company.profile.update_social_networks')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                  </div>

                  {{ html()->form('POST', route('frontend.company.company_profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
                    <div class="modal-body">
                          <div class="edit-form">
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.company.profile.facebook')</label>
            <input type="text" name="facebook_url"  value="<?php if(isset($social) && !empty($social->facebook_url)){echo $social->facebook_url;}?>" id="facebook_url">
                                </div>
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.company.profile.instagram')</label>
            <input type="text" name="instagram_url"  value="<?php if(isset($social) && !empty($social->instagram_url)){echo $social->instagram_url;}?>" id="instagram_url">
                                </div>
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.company.profile.linkedin')</label>
            <input type="text" name="linkedin_url"  value="<?php if(isset($social) && !empty($social->linkedin_url)){echo $social->linkedin_url;}?>" id="linkedin_url">
                                </div>
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.company.profile.twitter')</label>
            <input type="text" name="twitter_url"  value="<?php if(isset($social) && !empty($social->twitter_url)){echo $social->twitter_url;}?>" id="twitter_url">
                                </div>
                                <div class="form-edit">
                                      <label>@lang('labels.frontend.company.profile.other_url')</label>
            <input type="text" name="other"  value="<?php if(isset($social) && !empty($social->other)){echo $social->other;}?>" id="other">
                                </div>
                          </div>
                      </div>
                      <div class="modal-footer">
                            <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                            <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.company.profile.cargar_certified')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.company.company_profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">
                  <div class="cer-div">
                      <label>@lang('labels.frontend.company.profile.type_of_certification'):</label>
                        <input type="radio" name="certification_type" checked="" value="0"> @lang('labels.frontend.company.profile.image')
                        <input type="radio" name="certification_type" value="1"> @lang('labels.frontend.company.profile.document')
                  </div>
                  <div class='file_upload' id='f1'><input name='certification_courses[]' type='file'/></div>
                  <div id='file_tools'>
                        <i class="fa fa-plus-circle" id='add_file' aria-hidden="true">@lang('labels.frontend.company.profile.add_new_archive')</i>
                        <i class="fa fa-minus-circle" id='del_file' aria-hidden="true">@lang('labels.frontend.company.profile.erase')</i>
                  </div>
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>
<!--///////////////////certification Courses modal End here/////////////////////////-->
<!--///////////////////Police Record modal start here/////////////////////////-->
<div class="modal fade" id="policeRecordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.company.profile.upload_police_record')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.company.company_profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">
                        <div class="cer-div">
                            <label>@lang('labels.frontend.company.profile.type_of_register'):</label>
                              <input type="radio" name="record_type" checked="" value="0"> @lang('labels.frontend.company.profile.image') 
                              <input type="radio" name="record_type" value="1"> @lang('labels.frontend.company.profile.document')
                        </div>
                        <div class='file_upload' id='f1'><input name='police_records[]' type='file'/></div>
                        <div id='pol_file_tools'>
                              <i class="fa fa-plus-circle" id='poladd_file' aria-hidden="true">@lang('labels.frontend.company.profile.add_new_archive')</i>
                              <i class="fa fa-minus-circle" id='poldel_file' aria-hidden="true">@lang('labels.frontend.company.profile.erase')</i>
                        </div>

              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
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
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.company.profile.profile_description')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{ html()->form('POST', route('frontend.company.company_profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
                <div class="modal-body">
                      <div class="edit-form">
                            <div class="form-edit">
                                <label>@lang('labels.frontend.company.profile.profile_description')</label>
                                <textarea name="profile_description"><?php if(isset($company) && !empty($company->profile_description)){echo $company->profile_description;}?>
                                </textarea>
                            </div>
                      </div>
                </div>
                <div class="modal-footer">
                      <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                      <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
                </div>
        </form>
    </div>
  </div>
</div>
<!--///////////////////Profile Description modal end here/////////////////////////-->
<div class="modal fade" id="updateprofileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.company.profile.edit_profile_photo') </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
                {{ html()->form('POST', route('frontend.company.company_profile.update_profile_picture'))->attribute('enctype', 'multipart/form-data')->open() }}
                <div class="modal-body">
                      <div class="edit-form">
                            <div class="form-edit">
                                  <label>@lang('labels.frontend.company.profile.profile_picture')</label>
                                    <input type="file" class="" id="customFile" name="avatar_location" placeholder="Seleccionar">
                            </div>
                      </div>
                </div>
                <div class="modal-footer">
                      <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                      <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
                </div>
        </form> 
    </div>
  </div>
</div>
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
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.company.profile.selection_of_services_offered')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
          </div>

            {{ html()->form('POST', route('frontend.company.company_profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
            <div class="modal-body">
                  <div class="edit-form">
                        <div class="form-edit">
                              <label>@lang('labels.frontend.company.profile.select_services')</label>
                            <!-- <select name="services[]" class="services_id" id="services_id" multiple="">
                            <?php foreach ($services as $k_service => $v_service) { ?>
                            <option value="{{$v_service->id}}" <?php if(in_array($v_service->id, $serviceIds)) {?> selected <?php } ?> >{{$v_service->es_name}}</option>
                            <?php } ?>
                            </select> -->

                            <div id="services" class="form-edit">
                            <ul class="area-list meta-list multi-cities-list ">
                              <select name="services[]" id="multi-select-services" multiple="multiple" >
                                   <?php
                                  if(!empty($combineddata))
                                  {
                                      foreach ($combineddata as $key_com => $val_com)
                                      { ?>
                                          <option data-serv="{{$val_com['id']}}" class="parent_city parent_option parent_option pa_op_{{$val_com['id']}}" value="{{$val_com['id']}}" value="{{$val_com['id']}}">{{$val_com['name']}}</option>

                                        <?php $i=1; foreach ($val_com['subservices'] as $sdata)
                                        {
                                        ?>
                                            <option data-serv="{{$val_com['id']}}" class="child_city child_option ch_op_{{$val_com['id']}}" value="<?php echo $val_com['id'].','.$sdata['sub_service_id']?>">{{$sdata['name']}}</option>

                                     <?php $i++;} }
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
                  <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
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
          {{ html()->form('POST', route('frontend.company.company_profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
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
                                  foreach ($mixdata as $key => $val)
                                  { ?>
                                      <option data-prov="{{ $val['id'] }}" class="parent_city parent_prov_{{ $val['id'] }}" value="{{$val['id']}}">{{$val['name']}}</option>

                                    <?php $i=1; foreach ($val['cities'] as $cdata)
                                    {
                                    ?>
                                       <option data-prov="{{ $val['id'] }}" class="child_city child_prov_{{ $val['id'] }}" value="<?php echo $val['id'].','.$cdata['city_id']?>">{{$cdata['name']}}</option>

                                 <?php $i++;} }
                              }
                              ?>
                            </select>
                        </ul>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
                <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.close')</button>
                <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.save_changes')</button>
          </div>
           {{ html()->form()->close() }}
    </div>
  </div>
</div>

<div class="modal fade" id="deleteCertificate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿@lang('labels.frontend.company.profile.are_you_sure_you_want_to_delete')?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.company.company_profile.delete_certificate_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">

                    <img src="#" alt="bank_chalan" id="thanksCerificationImg" style="height: 100px;" />
                    <input type="hidden" name="certification_id" id="certification_id_del" value=""> 
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.cancel')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.want_to_delete')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>

<div class="modal fade" id="deletePoliceRecord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿@lang('labels.frontend.company.profile.are_you_sure_you_want_to_delete')?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.company.company_profile.delete_police_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">

                    <img src="#" alt="bank_chalan" id="thanksPolicerecImg_del" style="height: 100px;" />
                    <input type="hidden" name="polRecId" id="pol_rec_id_del" value=""> 
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.cancel')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.want_to_delete')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>

<div class="modal fade" id="editCertificate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.company.profile.edit_certificates')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.company.company_profile.update_certificate_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">
                        <img src="#" alt="bank_chalan" id="thanksCerification" style="height: 100px;" />
                        <input type="hidden" name="certification_id" id="certification_id" value=""> 


                        <div class='file_upload' id='f1'>
                         <br/><input name='certification_courses_img'  type='file'/>
                        </div>
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.to_close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.send')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>

<div class="modal fade" id="editPoliceRecord" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.company.profile.update_police_record')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.company.company_profile.update_policerec_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">


                <img src="#" alt="bank_chalan" id="thanksPolicerecImg" style="height: 100px;" />
                <input type="hidden" name="polRecId" id="pol_rec_id" value=""> 


                <div class='file_upload' id='f1'>
                 <br/><input name='police_record_img'  type='file'/>
                </div>
                      
              </div>
              <div class="modal-footer">
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.to_close')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.send')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>

<div class="modal fade" id="deletePhotosVideos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿@lang('labels.frontend.company.profile.are_you_sure_you_want_to_delete')?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.company.company_profile.delete_photovideos_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">
                @if($company->gallery['images'])
                   <div class='file_upload' id='f1'>
                    <img src="#" alt="PhotosvideosImg" id="thanksPhotosvideosImg_del" style="height: 100px;" />
                    <input type="text" name="gall_id" id="gallId_del" value="">
                    </div> 
                    @endif
                    @if($company->gallery['videos'])
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
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.cancel')</button>
                    <button type="submit" class="btn opp-btn">@lang('labels.frontend.company.profile.want_to_delete')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>

<div class="modal fade" id="editPhotosVideos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.company.profile.update_police_record')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.company.company_profile.update_photovideos_image'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">

                  @if($company->gallery['images'])
                    <div class='file_upload' id='f1'>
                     <br/><input name='gallery_image'  type='file' id="gallId11" value=""/>
                     <input type="hidden" name="gall_id" id="gallId" value="">
                     <img src="#" alt="PhotosvideosImg" id="thanksPhotosvideosImg" style="height: 100px;" />
                    </div>
                  @endif
                  @if($company->gallery['videos'])
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
                    <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.company.profile.to_close')</button>
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
          {{ html()->form('POST', route('frontend.company.company_profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
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
@endsection
@section('after-script')
<
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">
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
<style type="text/css">
  .div_show { display:none; }
  .div_show1 { display:none; }
  .div_show3 { display:none; }
</style>

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

$('#mobile_phone_number').keyup('input', function () 
    {
        this.value = this.value.replace(/[^0-9\.]/g,'');
        var mobile = document.getElementById('mobile_phone_number');
        if(mobile.value.length!=10)
        {
             $('.mobile-number-msg').addClass('extrainvalid-msg').text('El número de teléfono debe de constar de 10 dígitos');
        }
        else
        {
             $('.mobile-number-msg').html(' ');
        }

     }); 
</script>




<script>  
$(document).ready(function(){

 $image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:200,
      height:200,
      type:'square' //circle
    },
    boundary:{
      width:300,
      height:300
    }    
  });

  $('#imgupload').on('change', function(){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind', {
        url: event.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
    $('#insertimageModal').modal('show');
  });

  $('.crop_image').click(function(event){

    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){

     var userId = $('#contractorUserId').val();

      $.ajax({
        url: "{!! URL::to('insert_company_profile') !!}",
        type:'POST',
        data: {"_token": "{{ csrf_token() }}","image": response,"userid": userId },
        dataType: "json",
        success:function(data)
        {
           $('#insertimageModal').modal('hide');
           $('#thumbnil').attr('src',data.profile+'?'+Math.random());
           $('#thumbnil22').attr('src',data.profile+'?'+Math.random());
         }

      })
    });
  });
});  
</script>
<script type="text/javascript">

  $('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });

</script>
@endsection