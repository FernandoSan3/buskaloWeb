<!DOCTYPE html>
@langrtl
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl
  
  @include('frontend.includes.head')

  <body>
  <?php
  $route = Route::current();
  $route_name = Route::currentRouteName();
  ?>
  @section('title', app_name() . ' | ' . __('navs.general.home'))

  <section class="business-register">
    <header>
      <div class="container">
        <nav class="navbar navbar-expand-lg">
          <a class="navbar-brand" href="{{url('/')}}"><img src="{{ url('img/frontend/logo.svg') }}"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon">
                       <i class="fa fa-bars"></i></span>
            </button>

          <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
               <li class="nav-item">
                <a class="nav-link" href="{{url('home_page')}}">@lang('labels.frontend.company.profile.categories')</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('frontend.service_online')}}">@lang('labels.frontend.company.profile.online_services')</a>
              </li>
               <!-- @if(config('locale.status') && count(config('locale.languages')) > 1)
              <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLanguageLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">{{ strtoupper(app()->getLocale()) }}</a>

                    @include('includes.partials.lang')
               </li>
             @endif -->
            </ul>

            <ul class="navbar-nav right-nav  ml-auto">
              <li class="nav-item">
                <a href="{{ url('/') }}"><img src="{{ url('img/frontend/user-professional.png') }}" style="width: 143px;"></a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
  <!-- <div class="business-header">
    <div class="row no-gutters">
      <div class="col-md-3">
        <div class="user-pro-img">
          <img src="{{ url('img/frontend/user-professional.png') }}">
        </div>
      </div>
      <div class="col-md-4">
        <div class="logo-img inner-logo-pg">
          <img src="{{ url('img/frontend/logo.svg') }}">
        </div>
      </div>
      <div class="col-md-5 curve-img">
        <img class="img-fluid" src="{{ url('img/frontend/bg-curve.png') }}">
      </div>
    </div>
  </div> -->
  </section>
  <div class="header-profile">
    <div id="wrapper">
      <!-- Sidebar -->
     {{--@include('frontend.contractor.profile_sidebar')--}}
      <!-- /#sidebar-wrapper -->

     <!-- Page Content -->
      <div id="page-content-wrapper">
        <div class="container-fluid">
          <div class="right-sidebar sp-both">
            <!-- Tab panes -->
            <div class="tab-content">

             <!--************************************************-->
            @if($errors->any())
              <div class="alert alert-danger err_msg_a" role="alert" style="top: 0;">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>

                  @foreach($errors->all() as $error)
                      {!! $error !!}<br/>
                  @endforeach
              </div>
            @elseif(session()->get('flash_success'))
              <div class="alert alert-success err_msg_a" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>

                  @if(is_array(json_decode(session()->get('flash_success'), true)))
                      {!! implode('', session()->get('flash_success')->all(':message<br/>')) !!}
                  @else
                      {!! session()->get('flash_success') !!}
                  @endif
              </div>
            @elseif(session()->get('flash_warning'))
              <div class="alert alert-warning" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>

                  @if(is_array(json_decode(session()->get('flash_warning'), true)))
                      {!! implode('', session()->get('flash_warning')->all(':message<br/>')) !!}
                  @else
                      {!! session()->get('flash_warning') !!}
                  @endif
              </div>
             @elseif(session()->get('flash_info'))
              <div class="alert alert-info err_msg_a" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>

                  @if(is_array(json_decode(session()->get('flash_info'), true)))
                      {!! implode('', session()->get('flash_info')->all(':message<br/>')) !!}
                  @else
                      {!! session()->get('flash_info') !!}
                  @endif
              </div>
          @elseif(session()->get('flash_danger'))
              <div class="alert alert-danger err_msg_a" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>

                  @if(is_array(json_decode(session()->get('flash_danger'), true)))
                      {!! implode('', session()->get('flash_danger')->all(':message<br/>')) !!}
                  @else
                      {!! session()->get('flash_danger') !!}
                  @endif
              </div>
          @elseif(session()->get('flash_message'))
              <div class="alert alert-info err_msg_a" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>

                  @if(is_array(json_decode(session()->get('flash_message'), true)))
                      {!! implode('', session()->get('flash_message')->all(':message<br/>')) !!}
                  @else
                      {!! session()->get('flash_message') !!}
                  @endif
              </div>
          @endif

            <!--**************************************************-->


            <div class="tab-pane active" id="miperfil">
                <div class="profile-detail-sec">
                    <div class="profile-head">

                      {{ html()->form('POST', route('frontend.profile_completion'))->attribute('enctype', 'multipart/form-data')->id('myCompanyForm')->open() }}
                    
                      <div class="col-md-12 mt-5">

                        <div class="profile-instruction">
                          <h5>@lang('labels.frontend.company.redirect_company.new_era') </h5>
                          <p>@lang('labels.frontend.company.redirect_company.greatest_amount')</p>
                        </div>

                        <div class="pro-info">
                          <div class="pro-heading">
                            <h3>@lang('labels.frontend.company.redirect_company.mandatory_fields')*</h3>
                          </div>
                           <ul class="info-list edit-pro-input">
                              <li><h6>@lang('labels.frontend.company.redirect_company.insert_a_photo_or_company_logo')</h6> 
                              </li>
                            </ul>
                            <input type="hidden" name="avtar_name" id="avtar_name" value="">
                              <div class="add-profile-img">
                              <input type="file" accept="image/*" class="avatar_location" name="avatar_location" id="imgupload" style="display:none"/>

                                <button type="button" id="OpenImgUpload">

                                <span class="file-block">
                                  <div id="imageUploadDivAppend">

                                  <?php $pic= 'img/frontend/add-profile.png';
                                  if(isset($profile) && !empty($profile)){$pic= $profile;} ?>
                                  <img id="thumbnil" src="{{url($pic)}}" class="pro-img" alt="image">
                                </div>
                                </span>
                                 {{-- <p>Inserta una foto o logo de la empresa</p> --}}</button>
                            </div>
                        </div>

                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         <input type="hidden" name="user_id" id="companyUserId" value="{{ isset($userId) && !empty($userId) ? $userId : '3'  }}">

                       <!--**********Basic Information Update End Here************-->
                        <div class="pro-info">
                          <div class="pro-heading">
                            <h3>@lang('labels.frontend.company.redirect_company.informaci??n_general')</h3>
                            <!-- <button type="btn" data-toggle="modal" data-target="#infoModal"
                             class="edit-btn"><i class="fa fa-edit"></i>Edit</button> -->
                          </div> 
                            <ul class="info-list edit-pro-input">
                              <!-- <li><h6>Nombre de la Empresa*</h6> 
                                <input type="text" name="username" id="userName" placeholder="Nombre de la Empresa" class="" required="">
                                <div class="user-name-msg" style="text-align: center;"></div>
                                </li> -->

                                <li><h6>@lang('labels.frontend.company.redirect_company.ruc_o_rise')*</h6> 
                                  {{ html()->text('ruc_no',isset($getUser->ruc_no)?$getUser->ruc_no:'')
                                ->placeholder(__('labels.frontend.company.redirect_company.ruc_o_rise'))
                                ->id('rucNo')
                                ->attribute('maxlength', 13)
                                ->required() }}
                                  <!-- <input type="text" name="ruc_no" placeholder="@lang('labels.frontend.company.redirect_company.ruc_o_rise')" class="" required="" id="rucNo" maxlength="13">
                                  -->
                                 <div class="ruc-no-msg" style="text-align: center;"></div>
                                 </li>

                                 <li><h6>@lang('labels.frontend.company.redirect_company.year_of_incorporation')*</h6>
                                   {{ html()->text('year_of_constitution',isset($getUser->year_of_constitution)?$getUser->year_of_constitution:'')
                                    ->placeholder(__('labels.frontend.company.redirect_company.year_of_incorporation'))
                                    ->id('constYear')
                                    ->attribute('maxlength', 4)
                                    ->required() }}
                                  <!-- <input type="text" name="year_of_constitution" id="constYear" placeholder="@lang('labels.frontend.company.redirect_company.year_of_incorporation')" class="">
                                   -->
                                   <div class="year-msg" style="text-align: center;"></div>
                                   </li>
                                   <li><h6>@lang('labels.frontend.company.profile.username')*</h6>
                                {{ html()->text('username',isset($getUser->username)?$getUser->username:'')
                                     ->placeholder(__('labels.frontend.company.profile.username'))
                                    ->id('username')
                                    ->required()}} 
                                <!-- <input type="text" placeholder="@lang('labels.frontend.company.profile.username')" id="username" name="username" class="" required> -->
                                </li>
                                   <li><h6>@lang('labels.frontend.company.profile.campo_profesion')*</h6>
                                  {{ html()->text('profile_title',isset($getUser->profile_title)?$getUser->profile_title:'')
                                    ->placeholder(__('labels.frontend.company.profile.campo_profesion'))
                                    ->id('profile_title')
                                    ->required()}}
                                </li>
                                <div class="profile_title-msg" style="text-align: center;"></div>
                                   </li>

                                <li><h6>@lang('labels.frontend.company.redirect_company.legal_representative')*</h6> 
                                  {{ html()->text('legal_representative',isset($getUser->legal_representative)?$getUser->legal_representative:'')
                                    ->placeholder(__('labels.frontend.company.redirect_company.legal_representative'))
                                    ->id('legalFrep')
                                    ->required()}}
                                 
                                 <!--  <input type="text" name="legal_representative" placeholder="@lang('labels.frontend.company.redirect_company.legal_representative')" id="legalRep" class="" required="">
                                  -->
                                  <div class="legal-frep-msg" style="text-align: center;"></div>  
                                </li>
                                <li><h6>@lang('labels.frontend.company.profile.identity_no')*</h6>
                                  {{ html()->text('identity_no',isset($getUser->identity_no)?$getUser->identity_no:'')
                                     ->placeholder(__('labels.frontend.company.profile.identity_no'))
                                    ->id('identity_no')
                                    ->attribute('maxlength', 10)
                                    ->required() }}
                                <!--  <input type="text" name="identity_no" placeholder="@lang('labels.frontend.company.profile.identity_no')" id="identity_no" class="" required="">
                                 -->
                                 <div class="identity-no-msg" style="text-align: center;"></div>  
                                </li>
                                <li><h6>@lang('labels.frontend.company.redirect_company.web_address')</h6> 
                                  {{ html()->text('website_address',isset($getUser->website_address)?$getUser->website_address:'')
                                     ->placeholder(__('labels.frontend.company.redirect_company.web_address'))
                                    ->id('webDirection')}}
                               <!--  <input type="text" placeholder="@lang('labels.frontend.company.redirect_company.web_address')" id="webDirection" name="website_address" class=""> -->
                                <div class="webDirection-msg" style="text-align: center;"></div></li>

                                <li><h6>@lang('labels.frontend.company.redirect_company.direction')*</h6>
                                  {{ html()->text('address',isset($getUser->address)?$getUser->address:'')
                                     ->placeholder(__('labels.frontend.company.redirect_company.direction'))
                                    ->id('address')
                                    ->required() }}
                                 <!-- <input type="text" placeholder="@lang('labels.frontend.company.redirect_company.direction')" name="address" class="" required="" id="address">
                                  --> 
                                  <div class="address-msg" style="text-align: center;"></div>
                                  </li>

                                <!-- <li><h6>N??mero de tel??fono m??vil*</h6> 
                                <input type="text" placeholder="N??mero de tel??fono m??vil" name="mobile_number" id= "mobileNumber" class="" required="">
                                 <div class="mobile-number-msg" style="text-align: center;"></div>
                                </li> -->

                                
                                
                                <li><h6>@lang('labels.frontend.company.profile.office_address')</h6> 
                                   {{ html()->text('office_address',isset($getUser->office_address)?$getUser->office_address:'')
                                     ->placeholder(__('labels.frontend.company.profile.office_address'))
                                    ->id('office_address')}}
                                <!-- <input type="text" placeholder="@lang('labels.frontend.company.profile.office_address')" id="office_address" name="office_address" class=""> -->
                                </li>
                                <li><h6>@lang('labels.frontend.company.profile.mobile_phone_number')*</h6> 
                                  {{ html()->text('mobile_number',isset($getUser->mobile_number)?$getUser->mobile_number:'')
                                     ->placeholder(__('labels.frontend.company.profile.mobile_phone_number'))
                                    ->id('mobile_number')
                                    ->maxlength(10)
                                    ->required()}}
                                    <div class="mobile-number-msg" style="text-align: center;"></div>
                                <!-- <input type="text" placeholder="@lang('labels.frontend.company.profile.mobile_phone_number')" id="mobile_number" name="mobile_number" class="" required> -->
                                </li>

                                <li><h6>@lang('labels.frontend.company.profile.landline_number')</h6> 
                                  {{ html()->text('landline_number',isset($getUser->landline_number)?$getUser->landline_number:'')
                                    ->placeholder(__('labels.frontend.company.profile.landline_number'))
                                    ->id('landline_number')
                                    ->attribute('maxlength', 9)}}
                              
                               <!--  <input type="text" placeholder="@lang('labels.frontend.company.profile.landline_number')" id="landline_number" name="landline_number" class=""> -->
                                <div class ="landline-msg" style="text-align: center;"></div>
                              </li>

                                <li><h6>@lang('labels.frontend.company.profile.office_number')</h6> 
                                   {{ html()->text('office_number',isset($getUser->office_number)?$getUser->office_number:'')
                                     ->placeholder(__('labels.frontend.company.profile.office_number'))
                                    ->id('office_number')
                                    ->attribute('maxlength', 10)}}
                                <div class="office-number-msg" style="text-align: center;"></div>
                                <!-- <input type="text" placeholder="@lang('labels.frontend.company.profile.office_number')" id="office_number" name="office_number" class=""> -->
                                </li>
                               
                            </ul>
                          </div>

                          <!--**********Basic Information Update End Here************-->


                          <!--**********Payment Method Update Start Here************-->
                        <!-- <div class="row">
                          <div class="col-md-6 pro-info">
                            <div class="pro-heading">
                              <h3>@lang('labels.frontend.company.redirect_company.payment_methods')</h3>
                             
                            </div>
                            <div class="meta-list">
                              <h5>@lang('labels.frontend.company.redirect_company.select_the_payment_methods_you_accept')</h5>

                              <?php 
                                if(isset($paymentMethods) && !empty($paymentMethods)) {
                                  foreach ($paymentMethods as $k => $v_menthod) {
                              ?>
                                  <label class="cust-radio">{{ $v_menthod->name_es }}
                                <?php
                                    $checked=false;
                                    if(in_array($v_menthod->id,$paymentmethod))
                                    {
                                        $checked=true;
                                    }
                                ?>

                                    {{html()->checkbox('payment_method_id[]', $checked,$v_menthod->id )->class('switch-input') }}
                                    <span class="checkmark"></span>
                                  </label>
                              <?php      
                                  }
                                }

                              ?>
                            </div>
                          </div> -->

                                                             <!--  <input type="checkbox" value="{{$v_menthod->id}}" name="payment_method_id[]" multiple=""> -->


                          <!--**********Payment Method Update End Here************-->


                          <!--**********Social Networks Start Here************-->


                          
                          <div class="pro-info">
                            <div class="pro-heading">
                              <h3>@lang('labels.frontend.company.redirect_company.social_media')</h3>
                              <!-- <button type="btn" data-toggle="modal" data-target="#socialMediaModal"
                               class="edit-btn"><i class="fa fa-edit"></i>Edit</button> -->
                            </div>
                               <ul class="info-list edit-pro-input">
                               <!-- <ul class="info-list"> -->
                                <li><h6>@lang('labels.frontend.company.redirect_company.facebook')</h6>
                                   {{ html()->text('facebook_url',isset($socialnetwork->facebook_url)?$socialnetwork->facebook_url:'')
                                      ->placeholder(__('https://www.facebook.com'))
                                      ->id('facebookUrl') }}
                                  <!-- <input type="text" placeholder="https://www.@lang('labels.frontend.company.redirect_company.facebook').com" name="facebook_url" class="" id="facebookUrl"> -->
                                  <div class="facebook-url-msg" style="text-align: center;"></div>
                                </span></li>
                                <li><h6>@lang('labels.frontend.company.redirect_company.instagram')</h6>
                                  {{ html()->text('instagram_url',isset($socialnetwork->instagram_url)?$socialnetwork->instagram_url:'')
                                    ->placeholder(__('https://www.instagram.com'))
                                    ->id('instagramUrl') }}
                                 <!--  <input type="text" placeholder="https://www.@lang('labels.frontend.company.redirect_company.instagram').com" name="instagram_url" class="" id="instagramUrl"> -->
                                   <div class="instagram-url-msg" style="text-align: center;"></div>
                                </span></li>
                                <li><h6>@lang('labels.frontend.company.redirect_company.linkedin')</h6>
                                  {{ html()->text('linkedin_url',isset($socialnetwork->linkedin_url)?$socialnetwork->linkedin_url:'')
                                    ->placeholder(__('https://www.instagram.com'))
                                    ->id('linkedinUrl') }}
                                 <!--  <input type="text" placeholder="https://www.@lang('labels.frontend.company.redirect_company.linkedin').com" name="linkedin_url" class="" id="linkedinUrl"> -->
                                  <div class="linkedin-url-msg" style="text-align: center;"></div>
                                </span></li>
                                <li><h6>@lang('labels.frontend.company.redirect_company.twitter')</h6>
                                  {{ html()->text('twitter_url',isset($socialnetwork->twitter_url)?$socialnetwork->twitter_url:'')
                                        ->placeholder(__('https://www.twitter.com'))
                                        ->id('twitterUrl') }}
                                 <!--  <input type="text" placeholder="https://www.@lang('labels.frontend.company.redirect_company.twitter').com" name="twitter_url" class="" id="twitterUrl"> -->
                                   <div class="twitter-url-msg" style="text-align: center;"></div>
                                </span></li>
                                <li><h6>@lang('labels.frontend.company.redirect_company.other_url')</h6>
                                  {{ html()->text('other',isset($socialnetwork->other)?$socialnetwork->other:'')
                                ->placeholder(__('https://www.buskalo.com'))
                                ->id('otherUrl') }}
                                <!-- <input type="text" placeholder="https://www.@lang('labels.frontend.company.redirect_company.other_url').com" name="other" class="" id="otherUrl"> -->
                                 <div class="other-url-msg" style="text-align: center;"></div>
                                </span></li>
                              </ul>
                          </div>
                        </div>
                      <!--**********Social Networks END Here************-->

                        <div class="pro-info">
                          <div class="pro-heading">
                            <h3>@lang('labels.frontend.company.redirect_company.business')</h3>
                          </div>
                          <div class="desc-content">
                              <ul class="info-list">
                                <li><h6>@lang('labels.frontend.company.redirect_company.brief_description')</h6></li>
                              </ul>
                              {{ html()->textarea('profile_description' ,isset($getUser->profile_description)?$getUser->profile_description:'')
                                     ->placeholder(__('labels.frontend.company.redirect_company.brief_description'))
                                    ->id('profile_description')
                                    ->required()
                                     }}
                          <!--     <textarea name="profile_description" placeholder="@lang('labels.frontend.company.redirect_company.brief_description')" required></textarea>
                           --></div>
                        </div>

                      <!--**********Offered Services Start Here************-->
                          
                        <div class="row">
                         <div class="col-md-6 pro-info">  
                          <div class="pro-heading">
                            <h3>@lang('labels.frontend.company.redirect_company.Select_all_the_services_you_offer')</h3>
                          </div>    
                          <div id="services" class="form-edit">
                            <ul class="area-list meta-list multi-cities-list ">
                              <select name="services[]" id="multi-select-services" multiple="multiple" required oninvalid="this.setCustomValidity('Llene los campos obligatorios');"  onchange="try{setCustomValidity('')}catch(e){};" >
                                   <?php
                                  if(!empty($combineddata))
                                  {
                                      foreach ($combineddata as $key_com => $val_com) 
                                      { ?>
                                          <option data-serv="{{$val_com['id']}}" class="parent_city parent_option pa_op_{{$val_com['id']}}" value="{{$val_com['id']}}">{{$val_com['name']}}</option>

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
                           <!--**********Offered Services End Here************-->
                         <!--**********Certificaciones-Curso Start Here************-->
                        <div class="col-md-6 pro-info">
                            <div class="pro-heading">
                              <h3>@lang('labels.frontend.company.redirect_company.certifications_courses')</h3>
                            </div>
                            <div class="photo-galley">
                                <div class="cer-div">
                                     <label>@lang('labels.frontend.company.redirect_company.certification_type'):</label>
                                      <input type="radio" name="certification_type" checked="" value="0"> @lang('labels.frontend.company.redirect_company.picture')
                                      <input type="radio" name="certification_type" value="1"> @lang('labels.frontend.company.redirect_company.document')
                                </div>

                                <div class='file_upload' id='f1'>
                                     <input name='certification_courses[]' type='file' style="text-overflow: ellipsis;  overflow: hidden;"/>
                                </div>
                                <div id='file_tools'>
                                      <i class="fa fa-plus-circle" id='add_file' aria-hidden="true">@lang('labels.frontend.company.redirect_company.add')</i>
                                      <i class="fa fa-minus-circle" id='del_file' aria-hidden="true">@lang('labels.frontend.company.redirect_company.get_rid_of')</i>
                                </div>
                            </div>
                           <!--**********Certificaciones-Curso End Here************-->
                          </div>
                        </div>
                         <!--**********Services Coverage Area Start Here************-->

                        <div class="row">
                          <!--**********Services Coverage Area End Here************-->
                          <!--**********User Gallery Start Here************-->
                          <div class="col-md-12 pro-info">
                            <div class="pro-heading">
                              <h3>@lang('labels.frontend.company.redirect_company.photos_and_videos')</h3>
                            </div>
                            <div class="photo-galley">
                              <div class="edit-form col-md-12">
                                <div class="form-edit">    
                                    <label class="col-md-6">@lang('labels.frontend.company.redirect_company.images'):</label>

                                    <div class='file_upload1 col-md-6' id='f1'><input name='images_gallery[]' type='file' style="text-overflow: ellipsis;  overflow: hidden;"/>

                                        <div class="" id='image_file_tools'>
                                          <i class="fa fa-plus-circle" id='addGalleryImage' aria-hidden="true">@lang('labels.frontend.company.redirect_company.add_image')</i>
                                          <i class="fa fa-minus-circle" id='deleteGalleryImage' aria-hidden="true">@lang('labels.frontend.company.redirect_company.get_rid_of')</i>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="form-edit">     
                                  <label class="col-md-6">@lang('labels.frontend.company.redirect_company.videos'):</label>
                                  <div class='file_upload2 col-md-6' id='f2'><input name='videos_gallery[]' type='file' style="text-overflow: ellipsis;  overflow: hidden;" />
                                     <div id='videos_file_tools'>
                                    <i class="fa fa-plus-circle" id='addGalleryVideo' aria-hidden="true">@lang('labels.frontend.company.redirect_company.add_video')</i>
                                    <i class="fa fa-minus-circle" id='deleteGalleryVideo' aria-hidden="true">@lang('labels.frontend.company.redirect_company.get_rid_of')</i>
                                  </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                           <!--**********User Gallery End Here************-->
                        </div>

                        <div class="row">
                          <div class="col-md-12 mb-3">
                            
                           <div class="cer-div">
                              <div class="pro-heading">
                                <h3>@lang('labels.frontend.company.redirect_company.coverage_area')</h3>
                                </div>
                              </div>

                          </div>
                        </div>

                        <div class="row">
                           <div class="col-md-6 pro-info">  
                              <div class="pro-heading">
                                <h3>
                                  <label class="cust-radio" style="margin-bottom: 20px; display: inline-block;">
                                    <input type="checkbox" id="whole_country" value="1" name="whole_country">
                                    <span class="checkmark"></span>
                                  </label>
                                  @lang('labels.frontend.company.redirect_company.the_whole_country'): &nbsp;&nbsp;{{-- <input id="whole_country" type="radio" value="1" name="whole_country"> --}}

                                  {{-- <input type="checkbox" id="whole_country" value="1" name="whole_country"> --}}
                                </h3>
                              </div>
                              <!--   <div class="meta-list">
                                    <label class="cust-radio">Si
                                    <input type="radio" value="1" name="whole_country">
                                    <span class="checkmark"></span>
                                  </label>
                                  <label class="cust-radio">No
                                    <input type="radio" value="2" name="whole_country">
                                    <span class="checkmark"></span>
                                  </label>
                                </div> -->
                        </div>
      
                          <div class="col-md-6 pro-info">  
                            <div class="pro-heading selectCityProv">
                              <h3> @lang('labels.frontend.company.redirect_company.choose_province_and_cities')</h3>
                            </div>    
                            <div id="citiesArea" class="form-edit selectCityProv">
                              <ul class="area-list meta-list multi-cities-list">
                                <select name="proviences[]" id="multi-select-proviences" multiple="multiple" required oninvalid="this.setCustomValidity('Llene los campos obligatorios');"  onchange="try{setCustomValidity('')}catch(e){};" >
                                     <?php
                                    if(!empty($mixdata))
                                    {
                                        foreach ($mixdata as $key => $val) 
                                        { ?>
                                            <option data-prov="{{ $val['id'] }}" class="parent_city_new parent_city parent_prov_{{ $val['id'] }}" value="{{$val['id']}}">{{$val['name']}}</option>

                                          <?php $i=1; foreach ($val['cities'] as $cdata) 
                                          { 
                                          ?>
                                              <option data-prov="{{ $val['id'] }}"  class="child_city  child_prov_{{ $val['id'] }}" value="<?php echo $val['id'].','.$cdata['city_id']?>">{{$cdata['name']}}</option>

                                       <?php $i++;} }
                                    }
                                    ?> 
                                  </select>
                              </ul>
                            </div>
                          </div>
                       </div>

                      <div class="col-md-12 text-center">
                        <button type="submit" id="submit-btn1" class="allowed-submit1 btn opp-btn">@lang('labels.frontend.company.redirect_company.send')</button>
                      </div>
                    </div>

                    {{ html()->form()->close() }}

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
        <!--Modal-->
    <div id="insertimageModal" class="modal" role="dialog">
     <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" style="margin-left: 0%">&times;</button>
            <h4 class="modal-title" style="margin-right: 30%">@lang('labels.frontend.company.redirect_company.crop_insert_image')</h4>
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
          <button class="btn btn-success getProfile crop_image">@lang('labels.frontend.company.redirect_company.crop_image')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('labels.frontend.company.redirect_company.close')</button>
          </div>
        </div>
      </div>
    </div>
   <!--Modal-->
  </div>


{{ script('js/jquery.min.js') }}
{{ script('js/owl.carousel.min.js') }}
{{ script('js/popper.min.js') }}
{{ script('js/bootstrap.min.js') }}
{{ script('js/bootstrap.bunde.js') }}

{{ script('js/jquery-3.5.1.js') }}
{{ script('js/jquery.dataTables.min.js') }}
{{ script('js/dataTables.rowReorder.min.js') }}
{{ script('js/dataTables.responsive.min.js') }}
{{ script('select2/dist/js/select2.min.js') }}
{{ script('js/bootstrap-datetimepicker.js') }}

{{ style('css/bootstrap-multiselect.css') }}
{{ script('js/bootstrap-multiselect.js') }}

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">

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

     var userId = $('#companyUserId').val();
      $.ajax({
        url: "{!! URL::to('insert_company_profile') !!}",
        type:'POST',
        data: {"_token": "{{ csrf_token() }}","image": response,"userid": userId },
        dataType: "json",
        success:function(data)
        {

           $('#insertimageModal').modal('hide');
           $('#thumbnil').attr('src',data.profile+'?'+Math.random());

         }

      })
    });
  });
});  
</script>

<!--validation-->

<style type="text/css">
  .allowed-submit{opacity: .5;cursor: not-allowed;}
  .valid-input{
    border:1px solid green !important;
  }
  .invalid-input{
    border:1px solid red !important;
  }
  .invalid-msg{
    color: red;
  }
   .extravalid-input{
    border:1px solid green !important;
  }
  .extrainvalid-input{
    border:1px solid red !important;
  }
  .extrainvalid-msg{
    color: red;
  }
</style>


<script type="text/javascript">
  $(document).ready(function () {
  
//validation for User Name REQUIRED
// $('#userName').on('input', function () 
// {
//        var userName = $(this).val();
//        //var validName = /^[a-zA-Z ]*$/;
//        var validName = /^[a-zA-Z??????????????_+\-':"\\|,.\/? ]*$/;
//        if (userName.length == 0) 
//        {
//           $('.user-name-msg').addClass('invalid-msg').text("Username is required");
//           $(this).addClass('invalid-input').removeClass('valid-input');
//        }
//         else if (userName.length <= 3) 
//        {
//           $('.user-name-msg').addClass('invalid-msg').text("Atleast 4 caharacter required");
//           $(this).addClass('invalid-input').removeClass('valid-input');
//        }
//        else if (!validName.test(userName)) 
//        {
//           $('.user-name-msg').addClass('invalid-msg').text('only characters & Whitespace are allowed');
//           $(this).addClass('invalid-input').removeClass('valid-input');
          
//        }
//        else 
//        {
//           $('.user-name-msg').empty();
//           $(this).addClass('valid-input').removeClass('invalid-input');
//        }
//   });





//validation for Ruc No REQUIRED
$('#rucNo').on('input', function () 
{
       var rucNo = $(this).val();
       var validName = /^\d{13}$/;
       if (rucNo.length == 0) 
       {
          $('.ruc-no-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.ruc_is_not_required')');
          $(this).addClass('invalid-input').removeClass('valid-input');
       }
   
       else if (!validName.test(rucNo)) 
       {'@lang('labels.frontend.company.redirect_company.close')' 
          $('.ruc-no-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.ruc_digit_required')');
          $(this).addClass('invalid-input').removeClass('valid-input');
          
       }
       else 
       {
          $('.ruc-no-msg').empty();
          $(this).addClass('valid-input').removeClass('invalid-input');
       }
  });

//constYear 
$('#constYear').on('input', function () 
{
       var constYear = $(this).val();
       var validName = /(?:(?:19|20)[0-9]{2})/;
       if (!validName.test(constYear)) 
       {
          $('.year-msg').addClass('extrainvalid-msg').text('@lang('labels.frontend.company.redirect_company.please_enter_valid_year')');
          $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
          
       }
       else 
       {
          $('.year-msg').empty();
          $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
       }
  });
//profile_title
$('#profile_title').on('input', function (){
  var profile_title = $(this).val();
  var validName = /^[a-zA-Z??????????????_+\-':"\\|,.\/? ]*$/;
  if (profile_title.length == 0) 
  {
    $('.profile_title-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.profile_title_is_required')');
    $(this).addClass('invalid-input').removeClass('valid-input');
  }
  else if (profile_title.length <= 3)
  {
    $('.profile_title-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.atleast_4_character_required')');
    $(this).addClass('invalid-input').removeClass('valid-input');
  }
  else if (!validName.test(profile_title)) 
  {
    $('.profile_title-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.only_characters_allowed')');
    $(this).addClass('invalid-input').removeClass('valid-input');
  }
  else 
  {
    $('.profile_title-msg').empty();
    $(this).addClass('valid-input').removeClass('invalid-input');
  }
});
        

//validation for legal frep REQUIRED
$('#legalFrep').on('input', function ()
{
        var legalFrep = $(this).val();
        var validName = /^[a-zA-Z??????????????_+\-':"\\|,.\/? ]*$/;
        if (legalFrep.length == 0) 
        {
          $('.legal-frep-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.legal_rep_is_required')');
          $(this).addClass('invalid-input').removeClass('valid-input');
        }
        else if (legalFrep.length <= 3)
        {
          $('.legal-frep-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.atleast_4_character_required')');
          $(this).addClass('invalid-input').removeClass('valid-input');
        }
        else if (!validName.test(legalFrep)) 
        {
          $('.legal-frep-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.only_characters_allowed')');
          $(this).addClass('invalid-input').removeClass('valid-input');
        }
        else 
        {
          $('.legal-frep-msg').empty();
          $(this).addClass('valid-input').removeClass('invalid-input');
        }
});  

// validatio identification_type REQUIRED
$('#identity_no').on('input', function (
){
  var identity_no = $(this).val();
  var validName = /^[0-9]*$/;
  // var validName = /^\d{9}\-\d{1}$/;

  if (identity_no.length == 0) 
  {
    $('.identity-no-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.identity_no_is_required')');
    $(this).addClass('invalid-input').removeClass('valid-input');
  }
  else if (!validName.test(identity_no)) 
  {
    $('.identity-no-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.identity_no_must_have_numeric')');
    $(this).addClass('invalid-input').removeClass('valid-input');
  }
  else 
  {
    $('.identity-no-msg').empty();
    $(this).addClass('valid-input').removeClass('invalid-input');
  }
});


  //validation for address REQUIRED
  $('#address').on('input', function () 
  {
         var address = $(this).val();
         //var validName = /^[0-9a-zA-Z ]*$/;
        var validName = /^[a-zA-Z??????????????0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/? ]*$/;
          if (address.length == 0) 
         {
            $('.address-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.address_is_required')');
            $(this).addClass('invalid-input').removeClass('valid-input');
         }
         else if (!validName.test(address)) 
         {
            $('.address-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.address_must_have_alphanumeric')');
            $(this).addClass('invalid-input').removeClass('valid-input');
         }
         else 
         {
            $('.address-msg').empty();
            $(this).addClass('valid-input').removeClass('invalid-input');
         }
    });

// validating landline
$('#landline_number').on('input', function () 
{
  var landline = $(this).val();
  var validName = /^[0-9]*$/;
    if (!validName.test(landline)) 
  {
    $('.landline-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.landline_must_have_numeric')');
    $(this).addClass('invalid-input').removeClass('valid-input');
  }
  else 
  {
    $('.landline-msg').empty();
    $(this).addClass('valid-input').removeClass('invalid-input');
  }
  });
  
// validating mobile
$('#office_number').on('input', function () 
{
  var mobile = $(this).val();
  var validName = /^[0-9]*$/;
  if (!validName.test(mobile)) 
  {
    $('.office-number-msg').addClass('invalid-msg').text('@lang('labels.frontend.company.redirect_company.mobile_must_have_numeric')');
    $(this).addClass('invalid-input').removeClass('valid-input');
  }
  else 
  {
    $('.office-number-msg').empty();
    $(this).addClass('valid-input').removeClass('invalid-input');
  }
  });



  //validation for Mobile  REQUIRED
  // $('#mobileNumber').on('input', function () 
  // {
  //        var mobileNumber = $(this).val();
  //        var validName = /^\d{10}$/;

  //            $.ajax({
  //                   url: "{!! URL::to('check_mobile_availability') !!}",
  //                   data:"mobile_number="+mobileNumber,
  //                   type: "GET",
  //                   dataType: "json",
  //                   success:function(data)
  //                   {
  //                      if(data.success==false)
  //                      {

  //                       $('.mobile-number-msg').addClass('invalid-msg').text(data.message);
  //                       $(this).addClass('invalid-input').removeClass('valid-input');
  //                      }
                      
  //                   },
  //                   error:function (){}
  //               });

  //        if (mobileNumber.length == 0) 
  //        {

  //           $('.mobile-number-msg').addClass('invalid-msg').text("Mobile No is required");
  //           $(this).addClass('invalid-input').removeClass('valid-input');
  //        }
  //        if (!validName.test(mobileNumber)) 
  //        {
  //           $('.mobile-number-msg').addClass('invalid-msg').text('Mobile No must have 10 digit only');
  //           $(this).addClass('invalid-input').removeClass('valid-input');
  //        }
  //        else 
  //        {
  //           $('.mobile-number-msg').empty();
  //           $(this).addClass('valid-input').removeClass('invalid-input');
  //        }
  //   });

 

   //validation for url
    var urlregexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
    $('#webDirection').keyup('input', function ()  
    {
       var val= $("#webDirection").val();
       var mobile = document.getElementById('webDirection');

        if(mobile.value.length==0)
        {
             $('.webDirection-msg').removeClass('extrainvalid-msg');
             $('.webDirection-msg').html(' ');
             return false;
        }
        var dd=$("#webDirection").val();
        //alert(dd);
        if(urlregexp.test(dd)){
             $('.webDirection-msg').removeClass('extrainvalid-msg');
             $('.webDirection-msg').html(' ');
       // alert("valid URL");
        } else {
             $('.webDirection-msg').addClass('extrainvalid-msg').text('@lang('labels.frontend.company.redirect_company.invalid_url')');
            //alert("invalid URL");
        }
    });
    // $('#webDirection').on('input', function () 
    //   {
    //          var webDirection = $(this).val();
    //          var validName = urlregexp;

    //          if (!validName.test(webDirection)) 
    //          {
    //             // console.log(validName);
    //             $('.webDirection-msg').addClass('extrainvalid-msg').text('@lang('labels.frontend.company.redirect_company.invalid_url')');
    //             //$(this).addClass('extrainvalid-input').removeClass('extravalid-input');
    //          }
    //          else 
    //          {
    //             $('.extrainvalid-msg').html(' ');
    //             //$(this).addClass('extravalid-input').removeClass('extrainvalid-input');
    //          }
    //     });
    $('#facebookUrl').on('input', function () 
    {
           var facebookUrl = $(this).val();
           var validName = urlregexp;
           if (!validName.test(facebookUrl)) 
           {
              $('.facebook-url-msg').addClass('extrainvalid-msg').text('@lang('labels.frontend.company.redirect_company.invalid_url')');
              $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
           }
           else 
           {
              $('.facebook-url-msg').empty();
              $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
           }
      });

      $('#instagramUrl').on('input', function () 
    {
           var instagramUrl = $(this).val();
           var validName = urlregexp;
           if (!validName.test(instagramUrl)) 
           {
              $('.instagram-url-msg').addClass('extrainvalid-msg').text('@lang('labels.frontend.company.redirect_company.invalid_url')');
              $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
           }
           else 
           {
              $('.instagram-url-msg').empty();
              $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
           }
      });

        $('#linkedinUrl').on('input', function () 
    {
           var linkedinUrl = $(this).val();
           var validName = urlregexp;
           if (!validName.test(linkedinUrl)) 
           {
              $('.linkedin-url-msg').addClass('extrainvalid-msg').text('@lang('labels.frontend.company.redirect_company.invalid_url')');
              $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
           }
           else 
           {
              $('.linkedin-url-msg').empty();
              $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
           }
      });

          $('#twitterUrl').on('input', function () 
    {
           var twitterUrl = $(this).val();
           var validName = urlregexp;
           if (!validName.test(twitterUrl)) 
           {
              $('.twitter-url-msg').addClass('extrainvalid-msg').text('@lang('labels.frontend.company.redirect_company.invalid_url')');
              $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
           }
           else 
           {
              $('.twitter-url-msg').empty();
              $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
           }
      });

        $('#otherUrl').on('input', function () 
    {
           var otherUrl = $(this).val();
           var validName = urlregexp;
           if (!validName.test(otherUrl)) 
           {
              $('.other-url-msg').addClass('extrainvalid-msg').text('@lang('labels.frontend.company.redirect_company.invalid_url')');
              $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
           }
           else 
           {
              $('.other-url-msg').empty();
              $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
           }
      });


  


// validation to submit the form
$('input').on('input',function(e){
   if($('#myCompanyForm').find('.valid-input').length==3){

       $('#submit-btn').removeClass('allowed-submit');
       $('#submit-btn').removeAttr('disabled');
   }
  else{
       e.preventDefault();
       $('#submit-btn').attr('disabled','disabled')
       
      }
});

});
</script>

<!--validation-->

</script>
<script type="text/javascript">
  $(document).ready(function() {

    //$('.hide_child').hide();

    $('#multi-select-proviences').multiselect({
      enableCaseInsensitiveFiltering: true,
      filterBehavior: 'text',     
      onChange: function(option, checked, select) {
              
        var value_arr = [];
        //var is_province = $(option).hasClass('parent_city');
        var is_province = $(option).hasClass('parent_city_new');
        var prov_id = $(option).attr('data-prov');
              
        if(checked == true){

          if(is_province == true) {

            var selected_text = '.child_prov_'+prov_id;
            var total_length = $(selected_text).length;
            //$(selected_text).removeClass('hide_child');
            $(selected_text).each(function( index ) {
              if(index < total_length/2 ){                    
                value_arr.push($( this ).val()); 
              }
            });                
            
            $('#multi-select-proviences').multiselect('select', value_arr);

          } else {

            var selected_text = '.child_prov_'+prov_id;
            var selected_text_new = 'child_prov_'+prov_id;
            var total_length = $(selected_text).length;
           
            var cou = 0;
            $('li.active').each(function(){
              if($(this).hasClass('active') == true && $(this).hasClass(selected_text_new) == true ){
                  cou++;
              }  
            });

            if(cou == total_length/2) {

              var selected_text = '.parent_prov_'+prov_id;               
              var total_length = $(selected_text).length;
              $(selected_text).each(function( index ) {
                if(index < total_length/2 ){                    
                  value_arr.push($( this ).val()); 
                }
              });
              $('#multi-select-proviences').multiselect('select', value_arr);
            }



          }
        } else{

          if(is_province == true) {
                
            var selected_text = '.child_prov_'+prov_id;
            var total_length = $(selected_text).length;
            $(selected_text).each(function( index ) {
              if(index < total_length/2 ){                    
                value_arr.push($( this ).val()); 
              }
            });                
            $('#multi-select-proviences').multiselect('deselect', value_arr);

          } else {
                  

            var selected_child_text = '.child_prov_'+prov_id;
            var total_child_length = $(selected_child_text).length;
                  
            if(total_child_length/2 == 1) {
              
              var selected_text = '.parent_prov_'+prov_id; 
              var total_length = $(selected_text).length;
              $(selected_text).each(function( index ) {
                if(index < total_length/2 ){                    
                  value_arr.push($( this ).val()); 
                }
              });
              $('#multi-select-proviences').multiselect('deselect', value_arr);
            } else{
                    
              var selected_text = '.parent_prov_'+prov_id;
              var cou = 0;
              var selected_child_text_new = 'child_prov_'+prov_id;
              $('li.active').each(function(){
                if($(this).hasClass('active') == true && $(this).hasClass(selected_child_text_new) == true ){
                    cou++;
                }  
              });

              if(cou == 0) {

                var total_length = $(selected_text).length;
                $(selected_text).each(function( index ) {
                  if(index < total_length/2 ){                    
                    value_arr.push($( this ).val()); 
                  }
                });
                $('#multi-select-proviences').multiselect('deselect', value_arr);
              }else{
                var total_length = $(selected_text).length;
                $(selected_text).each(function( index ) {
                  if(index < total_length/2 ){                    
                    value_arr.push($( this ).val()); 
                  }
                });
                $('#multi-select-proviences').multiselect('deselect', value_arr);
              }

            }             

          }

        }
           
      }
    });
                 
    $('#multi-select-services').multiselect({
        enableCaseInsensitiveFiltering: true,
        filterBehavior: 'text',
        onChange: function(option, checked, select) {
        	
          var value_arr = [];
        	var is_parent = $(option).hasClass('parent_option');
        	var parent_id = $(option).attr('data-serv');

        	if(checked == true) {
        		if(is_parent == true){
        			var selected_element = '.ch_op_'+parent_id;
        			$(selected_element).show();
              var total_length = $(selected_element).length;
              $(selected_element).each(function( index ) {
                if(index < total_length/2 ){                             
                  value_arr.push($( this ).val()); 
                }
              });

              $('#multi-select-services').multiselect('select', value_arr);


        		} else {

        		}

        	} else{
        		if(is_parent == true) {

        			var selected_element = '.ch_op_'+parent_id;
              var total_length = $(selected_element).length;
              $(selected_element).each(function( index ) {
                if(index < total_length/2 ){                             
                  value_arr.push($( this ).val()); 
                }
              });
              $('#multi-select-services').multiselect('deselect', value_arr);
        			$(selected_element).hide();

        		} else {
              var selected_element = '.ch_op_'+parent_id;
              var total_length = $(selected_element).length;
              
              if(total_length/2 == 1) {                
                $('#multi-select-services').multiselect('deselect', parent_id);
                $(selected_element).hide();
              }else{

                //var selected_text = '.parent_prov_'+prov_id;
                var cou = 0;
                var selected_child_text_new = 'ch_op_'+parent_id;
                $('li.active').each(function(){
                  if($(this).hasClass('active') == true && $(this).hasClass(selected_child_text_new) == true ){
                      cou++;
                  }  
                });

                if(cou == 0){
                  $('#multi-select-services').multiselect('deselect', parent_id);
                  $(selected_element).hide();
                } else {

                }

              }

        		}
        	}        	
        	
        },                
        onInitialized: function(select, container) {
           
            $('.child_option').hide();
        }
    });
  });
</script>

<script type='text/javascript'>
$(document).ready(function(){

  var counter = 2;
  $('#del_file').hide();
  $('#add_file').click(function(){

    //$('#file_tools').before('<div class="file_upload" id="f'+counter+'"><input name="certification_courses[]" type="file">'+counter+'</div>');
     $('#file_tools').before('<div class="file_upload" id="f'+counter+'"><input name="certification_courses[]" type="file"></div>');
    $('#del_file').fadeIn(0);
  counter++;
  });
  $('#del_file').click(function(){
    if(counter==3){
      $('#del_file').hide();
    }   
    counter--;
    $('#f'+counter).remove();
  });
});
</script>

<script type='text/javascript'>
$(document).ready(function(){
  var counter = 2;
  $('#deleteGalleryImage').hide();
  $('#addGalleryImage').click(function(){
     $('#image_file_tools').before('<div class="file_upload1" id="f1'+counter+'"><input name="images_gallery[]" type="file"></div>');
    $('#deleteGalleryImage').fadeIn(0);
  counter++;
  });
  $('#deleteGalleryImage').click(function(){
    if(counter==3){
      $('#deleteGalleryImage').hide();
    }   
    counter--;
    $('#f1'+counter).remove();
  });
});
</script>


<script type='text/javascript'>
$(document).ready(function(){
  var counter = 2;
  $('#deleteGalleryVideo').hide();
  $('#addGalleryVideo').click(function(){
     $('#videos_file_tools').before('<div class="file_upload2" id="f2'+counter+'"><input name="videos_gallery[]" type="file"></div>');
    $('#deleteGalleryVideo').fadeIn(0);
  counter++;
  });
  $('#deleteGalleryVideo').click(function(){
    if(counter==3){
      $('#deleteGalleryVideo').hide();
    }   
    counter--;
    $('#f2'+counter).remove();
  });
});

$('#mobile_number').keyup('input', function () 
    {
        this.value = this.value.replace(/[^0-9\.]/g,'');
        var mobile = document.getElementById('mobile_number');
        if(mobile.value.length!=10)
        {
             $('.mobile-number-msg').addClass('extrainvalid-msg').text('El n??mero de tel??fono debe de constar de 10 d??gitos');
        }
        else
        {
             $('.mobile-number-msg').html(' ');
        }

     }); 
</script>



<script>
function showMyImage(fileInput) {
var files = fileInput.files;
for (var i = 0; i < files.length; i++) {
var file = files[i];
var imageType = /image.*/;
if (!file.type.match(imageType)) {
continue;
}
var img=document.getElementById("thumbnil");
img.file = file;
var reader = new FileReader();
reader.onload = (function(aImg) {
return function(e) {
aImg.src = e.target.result;
};
})(img);
reader.readAsDataURL(file);
}
}
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#multi-select-demo').multiselect();
    });
</script>

<script type="text/javascript">

  $('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });

</script>

<script type="text/javascript">
  $('#whole_country').change(function(){
    //alert('whole_country');
        
        if(this.checked) {
          
             $('.selectCityProv').hide();
              $('#multi-select-proviences').attr("disabled", true);
              $('#multi-select-proviences').attr('required',false);
              $('#multi-select-proviences').attr('oninvalid',false);
              $('#multi-select-proviences').attr('onChange',false);
        }else {
          
           $('.selectCityProv').show();
            $('#multi-select-proviences').attr("disabled", false);
            $('#multi-select-proviences').attr('required',true);
            $('#multi-select-proviences').attr('oninvalid',true);
            $('#multi-select-proviences').attr('onChange',true);
        }
    });
</script>


<style type="text/css">
.alert-danger
{
  z-index: 10 !important;
}

.alert-success
{
  z-index: 10 !important;
}

button.multiselect.dropdown-toggle.btn.btn-default {
    white-space: normal !important;
}

</style>

