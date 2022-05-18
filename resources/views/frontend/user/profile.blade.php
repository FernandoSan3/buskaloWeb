
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
        <!-- Tab panes -->
        <div class="tab-content">

         
          <div class="tab-pane active" id="perfil">
            <div class="contractor-profile-sec">
              <div class="profile-progress">
                <div class="progress">
                  <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60% @lang('labels.frontend.user.account.complete_profile')</div>
                </div>
              </div>
              <div class="profile-head">
                <div class="media">
                  <div class="profile-img">
                   <?php $pic= 'img/frontend/user.png';
                      if(isset($user) && !empty($user->avatar_location)){$pic= 'img/contractor/profile/'.$user->avatar_location;}?>
                    <img src="{{ url($pic) }}">
                  </div>
                  <div class="media-body row">
                    <div class="contractor-rating col-md-9">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star-half-o"></i>
                    </div>
                    {{-- <div class="contractor-balance col-md-3">
                      <h3>@lang('labels.frontend.user.account.your_balance'): <span>$20</span></h3>                    
                    </div> --}}
                  </div>
                </div>
              </div>

              {{ html()->form('POST', route('frontend.user.update_user_profile'))->attribute('enctype', 'multipart/form-data')->open() }}

              <div class="profile-update mt-5">
                <div class="row">

              <input type="hidden" name="_token" value="{{ csrf_token() }}">

                  <div class="col-md-9">

                    <div class="profile-form-left">

                        <div class="form-row">
                       

                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" value="<?php if(isset($user)){echo $user->username;}?>" name="username" id="inputAddress" placeholder="@lang('labels.frontend.user.account.username')">
                          </div> 
                          
                        </div>

                        <div class="form-group">
                          <input type="text" class="form-control" id="inputAddress2" placeholder="@lang('labels.frontend.user.account.address')" name="address" value="<?php if(isset($user)){echo $user->address;}?>">
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <input type="text" value="<?php if(isset($user)){echo $user->mobile_number;}?>" class="form-control" name="mobile_number" id="inputCity" placeholder="@lang('labels.frontend.user.account.mobile_number')">
                          </div>
                          <div class="form-group col-md-6">
                            <input type="text" class="form-control" id="inputCity" placeholder="@lang('labels.frontend.user.account.landline_number')" name="landline_number" value="<?php if(isset($user)){echo $user->landline_number;}?>">
                          </div>
                        </div>
                        <div class="form-row">
                         
                          <div class="custom-file mb-3">
                            <input type="file" class="custom-file-input" id="customFile" name="avatar_location" placeholder="@lang('labels.frontend.user.account.to_select')">
                            <label class="custom-file-label" for="customFile">@lang('labels.frontend.user.account.upload_profile_here')</label>
                          </div>
                        </div>


                          <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" name="profile_title" value="<?php if(isset($user)){echo $user->profile_title;}?>" class="form-control" placeholder="@lang('labels.frontend.user.account.profile_title')">
                          </div>

                        <div class="form-group col-md-6">
                            <textarea type="textarea" class="form-control"  name="profile_description" id="inputAddress" placeholder="@lang('labels.frontend.user.account.profile_description')"><?php if(isset($user)){echo $user->profile_description;}?></textarea>
                          </div> 
                          
                        </div>

                        </div>
                    </div>

                  <div class="col-md-3">
                    <div class="profile-right">
                      <div class="social-profile">
                        <ul class="social-div">
                          <li><span> <i class="fa fa-facebook"></i></span><input placeholder="@lang('labels.frontend.user.account.facebook_url')" value="<?php if(isset($social)){echo $social->facebook_url;}?>" type="text" name="facebook_url"></li>

                          <li><span> <i class="fa fa-instagram"></i></span><input placeholder="@lang('labels.frontend.user.account.instagram_url')" value="<?php if(isset($social)){echo $social->instagram_url;}?>" type="text" name="instagram_url"></li>

                          <li><span> <i class="fa fa-twitter"></i></span><input placeholder="@lang('labels.frontend.user.account.twitter_url')" value="<?php if(isset($social)){echo $social->twitter_url;}?>" type="text" name="twitter_url"></li>

                          <li><span> <i class="fa fa-linkedin"></i></span><input placeholder="@lang('labels.frontend.user.account.linkedin_url')" value="<?php if(isset($social)){echo $social->linkedin_url;}?>" type="text" name="linkedin_url"></li>

                           <li><span> <i class="fa fa-url"></i></span><input placeholder="@lang('labels.frontend.user.account.other_url')" value="<?php if(isset($social)){echo $social->other;}?>" type="text" name="other_url"></li>
                        </ul>
                      </div>
                    </div>
                  </div>

                <div class="col-md-12 text-center">
                <button type="submit" class="btn opp-btn">@lang('labels.frontend.user.account.update')</button>
                </div>
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
@endsection
