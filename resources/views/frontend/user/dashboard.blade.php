@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

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

        <div class="tab-pane active" id="miperfil">
            <div class="profile-detail-sec">
                <div class="profile-head">
                  <div class="media">
                    <div class="add-profile-img" style="z-index: 2 !important;">
                       <input type="file" accept="image/*" class="avatar_location_user" name="avatar_location" id="imguploaduser" style="display:none"/>
                              <button type="button" id="OpenImgUploadUser">
                                <span class="file-block">
                                  
                                   <?php $pic= 'img/frontend/user.png';
                                     if(isset($user) && !empty($user->avatar_location)){$pic= 'img/user/profile/'.$user->avatar_location;} ?>
                                  <img id="userthumbnil" src="{{url($pic)}}"  class="pro-img" alt="image">
                                </span>
                              </button>
                              </div>
                      <div class="media-body">
                        <h5 class="mt-0">{{ $user->username }}</h5>
                         <ul class="info-list"><li style="list-style:none;"><?php echo isset($user->identity_no) && !empty($user->identity_no) ? $user->identity_no : ''; ?></li></ul>
                      </div>
                    </div>

                   <input type="hidden" id="UserIdProfile" name="user_id" value="{{ isset($user) && !empty($user->id) ? $user->id : '0'  }}">


                  <div class="col-md-12 mt-5">

                   <!--**********Basic Information Update End Here************-->

                    <div class="pro-info">
                      <div class="pro-heading">
                        <h3>@lang('labels.frontend.project.information')</h3>
                        <button type="btn" data-toggle="modal" data-target="#infoModal"
                         class="edit-btn"><i class="fa fa-edit"></i>&nbsp;@lang('labels.frontend.project.edit')</button>
                      </div>
                        <ul class="info-list">
                          <li><h6>@lang('labels.frontend.project.identity_card_no')</h6> <?php echo isset($user->identity_no) && !empty($user->identity_no) ? $user->identity_no : ''; ?></li>

                            <li><h6>@lang('labels.frontend.project.email_identification')</h6> <?php echo isset($user->email) && !empty($user->email) ? $user->email : ''; ?></li>

                            <li><h6>@lang('labels.frontend.project.username')</h6> <?php echo isset($user->username) && !empty($user->username) ? $user->username : ''; ?></li>
                          
                            <li><h6>@lang('labels.frontend.project.date_of_birth')</h6> <?php echo isset($user->dob) && !empty($user->dob) ? date('dS F Y', strtotime($user->dob)) : ''; ?></li>

                            <li><h6>@lang('labels.frontend.project.address') 1 (@lang('labels.frontend.project.home'))</h6> <?php echo isset($user->address) && !empty($user->address) ? $user->address : ''; ?></li>
                              
                            <li><h6>@lang('labels.frontend.project.address') 2 (@lang('labels.frontend.project.office'))</h6> <?php echo isset($user->office_address) && !empty($user->office_address) ? $user->office_address : ''; ?></li>

                            <li><h6>@lang('labels.frontend.project.address') 3 (@lang('labels.frontend.project.others'))</h6> <?php echo isset($user->other_address) && !empty($user->other_address) ? $user->other_address : ''; ?></li>

                            <li><h6>@lang('labels.frontend.project.contact_number') 1 (@lang('labels.frontend.project.mobile'))</h6> <?php echo isset($user->mobile_number) && !empty($user->mobile_number) ? $user->mobile_number : ''; ?></li>

                            <li><h6>@lang('labels.frontend.project.contact_number') 2 (@lang('labels.frontend.project.home'))</h6> <?php echo isset($user->landline_number) && !empty($user->landline_number) ? $user->landline_number : ''; ?></li>

                            <li><h6>@lang('labels.frontend.project.contact_number') 3 (@lang('labels.frontend.project.office'))</h6> <?php echo isset($user->office_number) && !empty($user->office_number) ? $user->office_number : ''; ?></li>
                        </ul>
                      </div>

                      <!--**********Basic Information Update End Here************-->

                    
                      <!--**********Social Networks Start Here************-->


                      <!-- <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Social Networks</h3>
                        <button type="btn" data-toggle="modal" data-target="#socialMediaModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Editar</button>
                      </div>
                        <ul class="info-list">
                          <li><h6>Facebook</h6><a href="<?php if(isset($social)){echo $social->facebook_url;}?>" class="orange">+ <?php if(isset($social)){echo $social->facebook_url;}?></a></li>
                          <li><h6>Instagram</h6><a href="<?php if(isset($social)){echo $social->instagram_url;}?>" class="orange">+ <?php if(isset($social)){echo $social->instagram_url;}?></a></li>
                          <li><h6>Linkedin</h6><a href="<?php if(isset($social)){echo $social->linkedin_url;}?>" class="orange">+ <?php if(isset($social)){echo $social->linkedin_url;}?></a></li>
                          <li><h6>Twitter</h6><a href="<?php if(isset($social)){echo $social->twitter_url;}?>" class="orange">+ <?php if(isset($social)){echo $social->twitter_url;}?></a></li>
                          <li><h6>Other Url</h6><a href="<?php if(isset($social)){echo $social->other;}?>" class="orange">+ <?php if(isset($social)){echo $social->other;}?></a></li>
                        </ul>
                      </div>
 -->
                  <!--**********Social Networks END Here************-->


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
</div>





<!--///////////////////Basic Information modal start here/////////////////////////-->

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.frontend.project.information')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        

         {{ html()->form('POST', route('frontend.user.profile.update_info'))->attribute('enctype', 'multipart/form-data')->id('myUserProfileForm')->open() }}

        <div class="modal-body">   
        <div class="edit-form">     
          <div class="form-edit">
          <!-- Form -->
            <label>*@lang('labels.frontend.project.identity_card_no')</label>

             <input type="text" name="identity_no" required="" value="<?php if(isset($user) && !empty($user->identity_no)){echo $user->identity_no;}?>" id="identityNo">
            <br/>
            <div class="identity-no-msg" style="text-align: center;"></div>

          </div>
          <div class="form-edit">
            <label>*@lang('labels.frontend.project.username')</label>

             <input type="text" name="username" required="" value="<?php if(isset($user) && !empty($user->username)){echo $user->username;}?>" id="userName">
            <br/><div class="user-name-msg" style="text-align: center;"></div>

          </div>
             <div class="form-edit">
              <label>*@lang('labels.frontend.project.date_of_birth')</label>
              <input type="text" required="" class="form_date" name="dob" value="<?php if(isset($user) && !empty($user->dob)){echo $user->dob;}?>" id="datepicker">
            </div>
            <div class="form-edit">
              <label>*@lang('labels.frontend.project.address')</label>
              <input type="text" required="" name="address" value="<?php if(isset($user) && !empty($user->address)){echo $user->address;}?>" id="address">
            <br/><div class="address-msg" style="text-align: center;"></div>
            </div>
            <div class="form-edit">
              <label>@lang('labels.frontend.project.office_address')</label>
              <input type="text" name="office_address" value="<?php if(isset($user) && !empty($user->office_address)){echo $user->office_address;}?>" id="office_address">
            </div>
            <div class="form-edit">
              <label>@lang('labels.frontend.project.other_address')</label>
              <input type="text" name="other_address" value="<?php if(isset($user) && !empty($user->other_address)){echo $user->other_address;}?>" id="other_address">
            </div>
            <div class="form-edit">
              <label>@lang('labels.frontend.project.mobile')</label>
               <input type="text" name="mobile_number" value="<?php if(isset($user) && !empty($user->mobile_number)){echo $user->mobile_number;}?>" id="mobileNumber">
            <br/><div class="mobile-number-msg" style="text-align: center;"></div>

            </div>
            <div class="form-edit">
              <label>@lang('labels.frontend.project.landline_phone_number')</label>
             <input type="text" name="landline_number" value="<?php if(isset($user) && !empty($user->landline_number)){echo $user->landline_number;}?>" id="landlineNumber">
            <br/><div class="landline-number-msg" style="text-align: center;"></div>
            </div>
            <div class="form-edit">
              <label>@lang('labels.frontend.project.office_number')</label>
             <input type="text" name="office_number" value="<?php if(isset($user) && !empty($user->office_number)){echo $user->office_number;}?>" id="officeNumber">
             <br/><div class="office-number-msg" style="text-align: center;"></div>
            <!-- End -->  
            </div>
          </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.project.close')</button>
          <button type="submit" id="submitsu-btn" class="alloweds-submit btn opp-btn">@lang('labels.frontend.project.send')</button>

        </div>       
        </div>
        
    {{ html()->form()->close() }}
    </div>
  </div>
</div>

<!--///////////////////Basic Information modal End here/////////////////////////-->


<!--///////////////////Social Networks Modal End Here/////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="socialMediaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Social Networks</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        {{ html()->form('POST', route('frontend.user.profile.update_info'))->attribute('enctype', 'multipart/form-data')->open() }}

        <div class="modal-body">        
          <div class="edit-form">
            <div class="form-edit">
              <label>Facebook</label>
              <input type="text" name="facebook_url" required="" value="<?php if(isset($social) && !empty($social->facebook_url)){echo $social->facebook_url;}?>" id="facebook_url">
            </div>
            <div class="form-edit">
              <label>Instagram</label>
              <input type="text" name="instagram_url" required="" value="<?php if(isset($social) && !empty($social->instagram_url)){echo $social->instagram_url;}?>" id="instagram_url">
            </div>
            <div class="form-edit">
              <label>Linkedin</label>
              <input type="text" name="linkedin_url" required="" value="<?php if(isset($social) && !empty($social->linkedin_url)){echo $social->linkedin_url;}?>" id="linkedin_url">
            </div>
            <div class="form-edit">
              <label>Twitter</label>
              <input type="text" name="twitter_url" required="" value="<?php if(isset($social) && !empty($social->twitter_url)){echo $social->twitter_url;}?>" id="twitter_url">
            </div>
            <div class="form-edit">
              <label>Other Url</label>
              <input type="text" name="other" required="" value="<?php if(isset($social) && !empty($social->other)){echo $social->other;}?>" id="other">           
            </div>
          </div>
          </div>       
          <div class="modal-footer">
            <button type="button" class="btn close-btn" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn opp-btn">Enviar</button>
          </div>

        </div>
        
    {{ html()->form()->close() }}
    </div>
  </div>
</div>


<!--///////////////////Social Networks Modal End Here/////////////////////////-->


<div class="modal fade" id="updateprofileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Profile Description</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
                {{-- html()->form('POST', route('frontend.user.update_user_profile_picture'))->attribute('enctype', 'multipart/form-data')->open() --}}
                <div class="modal-body">        
                      <div class="edit-form">
                            <div class="form-edit">
                                  <label>Profile Picture</label>              
                                    <input type="file" class="" id="customFile" name="avatar_location" placeholder="Seleccionar">
                            </div>
                      </div>       
                </div>
                <div class="modal-footer">
                      <button type="button" class="btn close-btn" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn opp-btn">Enviar</button>
                </div>
        <!-- </form> -->
    </div>
  </div>
</div>


<!--Profile Picture Modal-->

   <div id="UserinsertimageModal" class="modal" role="dialog">
           <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" style="margin-left: 0%">&times;</button>
                  <h4 class="modal-title" style="margin-right: 30%">Recortar e insertar imagen</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-8 text-center">
                      <div id="user_image_demo" style="width:455px; margin-top:30px"></div>
                    </div>
                    <div class="col-md-4" style="padding-top:30px;">
                  <br />
                  <br />
                  <br/>
                      
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                <button class="btn btn-success getUserProfile crop_user_image">Delimitar imagen</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
 <!--Profile Picture Modal-->

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