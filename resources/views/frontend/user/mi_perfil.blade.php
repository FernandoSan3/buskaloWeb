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
  <div id="wrapper1" class="toggled1">
    <!-- Sidebar -->
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapperhh">
      <div class="container">
        <div class="comp-profile">
          <div class="top-profile-sec pro-wrapper py-4">
            <p class="star-bx d-inlinne-block mb-0"><i class="fa fa-star-o"></i></p>
            <span>@lang('labels.frontend.constructor.profile.my_profile')</span>
          </div>
        </div>
        <div class="mi-section">
          <div class="mi-slider">
             <div class="foto-img">
             <?php 
             $bannerpic= 'img/frontend/banner2.png';

              if(isset($user) && !empty($user->banner)){$bannerpic= 'img/contractor/banner/'.$user->banner;}
              ?>
                <img src="{{ url($bannerpic) }}" id="thumbnil">
              </div>
          </div>

          <div class="mi-prof-img">
             <div class="prof-head">
                <p><img src="{{ url('img/frontend/check3.png') }}" >@lang('labels.frontend.constructor.profile.verified_profile')</p>
                <?php $pic= 'img/frontend/user.png';
                if(isset($user) && !empty($user->avatar_location)){$pic= 'img/contractor/profile/'.$user->avatar_location;}
                ?>
            <div class="user-profile-pic">
              <img src="{{ url($pic) }}" id="thumbnil">
            </div>
          </div>
        </div>
        <div class="">
          <div class="step1-prof">
          <div class="prof-inner" style="background:transparent;border:0;padding: 0;">
            <div class="row">
              <div class="col-md-6">
                <div class="cprof-detail">
                  <div class="prof-head">
                    <!-- <p><img src="{{ url('img/frontend/check3.png') }}">Perfil Verificado</p>
                    <h2><?php //echo isset($user->username) && !empty($user->username) ? $user->username : ''; ?></h2> -->
                    <h2><?php echo isset($user->username) && !empty($user->username) ? $user->username : ''; ?></h2>
                    @if(isset($user->profile_title) && !empty($user->profile_title))
                    <h6><b>Campo/Profesi??n: <?php echo isset($user->profile_title) && !empty($user->profile_title) ? $user->profile_title : ''; ?></b></h6>
                    @endif
                  </div>
                 
                </div>
              </div>
              <div class="col-md-6"> 
                <ul class="prof-list">
                  
                    <li><h6>@lang('labels.frontend.constructor.profile.identity_no')</h6>: <?php echo isset($user->identity_no) && !empty($user->identity_no) ? $user->identity_no : ''; ?></li>
                    <li><h6>@lang('labels.frontend.constructor.profile.username')</h6>: <?php echo isset($user->username) && !empty($user->username) ? $user->username : ''; ?></li>
                    <li><h6>@lang('labels.frontend.constructor.profile.profile_title')</h6>: <?php echo isset($user->profile_title) && !empty($user->profile_title) ? $user->profile_title : ''; ?></li>
                    <li><h6>@lang('labels.frontend.constructor.profile.date_of_birth')</h6>: <?php echo isset($user->dob) && !empty($user->dob) ? $user->dob : ''; ?></li>
                    
                    <!-- <li><h6>@lang('labels.frontend.constructor.profile.total_employees')</h6>: <?php echo isset($user->total_employee) && !empty($user->total_employee) ? $user->total_employee : '0'; ?> </li> -->
                    <li><h6>@lang('labels.frontend.constructor.profile.address')</h6>: <?php echo isset($user->address) && !empty($user->address) ? $user->address : ''; ?> / <?php echo isset($user->office_address) && !empty($user->office_address) ? $user->office_address : ''; ?> / <?php echo isset($user->other_address) && !empty($user->other_address) ? $user->other_address : ''; ?></li>
                    <li><h6>@lang('labels.frontend.constructor.profile.mobile_phone_number')</h6>: <?php echo isset($user->mobile_number) && !empty($user->mobile_number) ? $user->mobile_number : ''; ?> / <?php echo isset($user->landline_number) && !empty($user->landline_number) ? $user->landline_number : ''; ?> / <?php echo isset($user->office_number) && !empty($user->office_number) ? $user->office_number : ''; ?></li>
                      @if(isset($user->website_address) && !empty($user->website_address))
                      <li><h6>@lang('labels.frontend.constructor.profile.website_address')</h6>:<?php echo isset($user->website_address) && !empty($user->website_address) ? $user->website_address : ''; ?></li>
                      @endif
                  </ul>
              </div>
            </div>
          </div>

          <div class="build-sec ">
            <div class="row">
              <div class="col-md-8">
                <div class="build-inner media">
                  <div class="media-body">
                 
                    <p><?php echo isset($user->profile_description) && !empty($user->profile_description) ? $user->profile_description : 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap into electronic typesetting, remaining essentially unchanged.'; ?></p>
                  </div>
                  <div class="mark-div"><img src="{{ url('img/frontend/shapes.png') }}"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="rev-heading">    
                  <?php
                    $totaldata=0;
                    $usercount=0;
                    if(count($review_datas)>0)
                    {
                      foreach($review_datas as $totalreiew)
                      {
                        $totaldata+=$totalreiew->rating;
                        $usercount++;
                      }
                    }
                  ?>
                   <?php
                   if(count($review_datas)>0)
                    {
                      $count=$totaldata/$usercount;
                      if($count!=0)
                      {
                        for ($i=1; $i <6 ; $i++)
                        {
                           if($i > $count)
                           {
                            echo '<i class="fa fa-star"></i>';

                            }else{
                                echo '<i class="fa fa-star" style="color:#ffcd1b"></i>';
                            }
                        }
                         /*for($i=1;$i<=$count;$i++)
                          {
                            ?>
                            <i class="fa fa-star"></i>
                            <?php
                          }*/
                      }
                    }
                    ?>
                    @if(count($review_datas)>0)
                      <h6>{{number_format($totaldata/$usercount,2)}}</h6>
                    @else
                      <h6>0</h6>
                    @endif
                  <p>@if($usercount==1)
                    {{$usercount}} cliente
                    @elseif($usercount>1)
                    {{$usercount}} clientes
                    @else
                     {{$usercount}} cliente
                    @endif
                    ha
                   @lang('labels.frontend.constructor.profile.clients_said') <br><!-- @lang('labels.frontend.constructor.profile.about_this_profile') --></p>
                 <!--  <a class="orange" href="{{url('review')}}">@lang('labels.frontend.constructor.profile.go_to_reviews')</a>
 -->                </div>
              </div>
          </div>
        </div>
        </div>
            <section class="comp-service ">
              <div class="prof-service">
                <div class="row">
                  <div class="col-md-6 mb-5">
                    <ul class="list">
                      <div class="list-head">
                        <img src="{{ url('img/frontend/comp4.png') }}"> <h3>@lang('labels.frontend.constructor.profile.professional_services')</h3>
                      </div>
                      <?php if(isset($serviceOffered) && count($serviceOffered) >0)
                        {
                          foreach ($serviceOffered as $key => $value) { ?>
                          <li><i class="fa fa-check"></i> {{$value->es_name}}</li>
                      <?php  } }?>
                    </ul>
                  </div>

                  <div class="col-md-6 mb-5">
                    <ul class="list border-0 pr-0">
                      <div class="list-head">
                        <img src="{{ url('img/frontend/comp2.png') }}"> <h3>@lang('labels.frontend.constructor.profile.social_media')</h3>
                      </div>
                      <li><?php if(isset($social) && !empty($social->facebook_url)){echo '<h6>Facebook</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->facebook_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->facebook_url)){echo $social->facebook_url;}?></a></li>

                      <li><?php if(isset($social) && !empty($social->instagram_url)){echo '<h6>Instagram</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->instagram_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->instagram_url)){echo $social->instagram_url;}?></a></li>

                       <li><?php if(isset($social) && !empty($social->linkedin_url)){echo '<h6>Linkedin</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->linkedin_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->linkedin_url)){echo $social->linkedin_url;}?></a></li>

                        <li><?php if(isset($social) && !empty($social->twitter_url)){echo '<h6>Twitter</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->twitter_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->twitter_url)){echo $social->twitter_url;}?></a></li>

                        <li><?php if(isset($social) && !empty($social->other)){echo '<h6>Other Url</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->other;}?>" class="orange"> <?php if(isset($social)  && !empty($social->other)){echo $social->other;}?></a></li>
                    </ul>
                  </div>

                  <div class="col-md-6 mb-5">
                    <ul class="list ">
                      <div class="list-head">
                        <img src="{{ url('img/frontend/comp3.png') }}"> <h3>@lang('labels.frontend.constructor.profile.coverage_area')</h3>
                      </div>
                      <?php
                      if(isset($allUserAreaData) && !empty($allUserAreaData)) {
                       foreach ($allUserAreaData as $key => $value) { ?>
                          <li><i class="fa fa-check"></i>{{$value['name']}}</li>

                          <?php foreach ($value['cities'] as $key2 => $val2) 
                          { ?>

                           <li style="margin-left: 10%;">{{'* '.$val2['name']}}</li>
                            
                          <?php } ?>

                      <?php  } } ?>
                      
                    </ul>
                  </div>

                  <div class="col-md-6 mb-5">
                    <ul class="list border-0 ">
                      <div class="list-head">
                        <img src="{{ url('img/frontend/comp1.png') }}"> <h3>@lang('labels.frontend.constructor.profile.payment_methods')</h3>
                      </div>
                      <?php
                      if(isset($paymentMethods) && !empty($paymentMethods)) {
                      foreach ($paymentMethods as $k => $v_menthod) {
                      if(in_array($v_menthod->id,$paymentMethodId))
                      {
                      ?>
                      <li><i class="fa fa-check"></i>{{ $v_menthod->name_es }}</li>
                      <?php } } } ?>
                    </ul>
                  </div>
                </div>
              </div>
            </section>

            <div class="step1-prof">
              <div class="certificate-sec">
                <div class="container">
                  <div class="chead">
                    <h3><img src="{{ url('img/frontend/cer-icon.png') }} ">@lang('labels.frontend.constructor.profile.police_record')</h3>
                  </div>
                  <div class="cer-list">
                    <div class="row">
                     <?php if(isset($user->cetifications) && !empty($user->cetifications))
                        {
                        foreach ($user->cetifications['police_records'] as $key_course => $value_police) {

                        ?>
                         @if(file_exists(public_path('/img/contractor/police_records/'.$value_police['user_id'].'/'.$value_police['file_name'])))
                            <div class="col-md-4 div_show3">
                              <div class="cer-img">
                                <img src="{{url('/img/contractor/police_records/'.$value_police['user_id'].'/'.$value_police['file_name']) }}">
                              </div>
                            </div>
                            @endif

                        <?php   }
                        } 
                        ?>

                    </div>
                  </div>
                  @if(count($user->cetifications['police_records'])>3)
                  <a href="javascript:void(0)" id="load3" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.constructor.profile.see_all')</a>
                  @endif

                </div>
              </div>
            </div>

            <div class="step1-prof">
              <div class="certificate-sec">
                <div class="container">
                  <div class="chead">
                    <h3><img src="{{ url('img/frontend/cer-icon.png') }} ">
                    @lang('labels.frontend.constructor.profile.recognitions_certificates')</h3>
                  </div>
                  <div class="cer-list">
                    <div class="row">
                     <?php if(isset($user->cetifications) && !empty($user->cetifications))
                        {
                        foreach ($user->cetifications['certification_courses'] as $key_course => $value_course) {

                        ?> 
                            @if(file_exists(public_path('/img/contractor/certifications/'.$value_course['user_id'].'/'.$value_course['file_name'])))
                            <div class="col-md-4 div_show">
                              <div class="cer-img">
                                <img src="{{ url('/img/contractor/certifications/'.$value_course['user_id'].'/'.$value_course['file_name']) }}">
                              </div>
                            </div>
                            @endif

                        <?php   }
                        } 
                        ?>

                    </div>
                  </div>
                  @if(count($user->cetifications['certification_courses'])>3)
                  <a href="javascript:void(0)" id="load" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.constructor.profile.see_all')</a>
                  @endif
                </div>
              </div>
            </div>

             <div class="step1-prof">
              <div class="certificate-sec">
                <div class="container">
                  <div class="chead">
                    <h3><img src="{{ url('img/frontend/cer-icon.png') }} ">
                    @lang('labels.frontend.constructor.profile.project_portfolio')</h3>
                  </div>
                  <div class="cer-list">
                    <div class="row">
                     <?php if(isset($user->gallery) && !empty($user->gallery))
                        {
                        foreach ($user->gallery['images'] as $key_images => $value_images) {
                        ?>
                          @if(file_exists(public_path('/img/contractor/gallery/images/'.$value_images['user_id'].'/'.$value_images['file_name'])))
                            <div class="col-md-4 div_show4">
                              <div class="cer-img">
                                <img src="{{ url('/img/contractor/gallery/images/'.$value_images['user_id'].'/'.$value_images['file_name'])}}">
                              </div>
                            </div>
                            @endif

                        <?php   }
                           foreach ($user->gallery['videos'] as $key_images => $value_videos) {?>
                            @if(file_exists(public_path('/img/contractor/gallery/videos/'.$value_videos['user_id'].'/'.$value_videos['file_name'])))
                            <div class="col-md-4 div_show4 record_policy">
                              <div class="cer-img">
                               <video controls style="width: 100%; height: 195px;">
                                  <source src="{{ url('/img/contractor/gallery/videos/'.$value_videos['user_id'].'/'.$value_videos['file_name'])}}" type="video/mp4">
                                  <source src="">
                                </video>
                              </div>
                            </div>
                            @endif
                        <?php } 
                          ?>
                          
                          <?php }?>
                    </div>
                  </div>
                  <?php $image=count($user->gallery['images']);
                        $video=count($user->gallery['videos']);
                        $countdata=$image+$video;
                        ?>
                  @if($countdata>3)
                  <a href="javascript:void(0)" id="load4" class="orange text-right w-100 d-block see_image">@lang('labels.frontend.constructor.profile.see_all')</a>
                  @endif
                </div>
              </div>
            </div>

            <div class="step1-prof">
              <div class="container">
                <div class="chead">
                  <h3><img src="{{ url('img/frontend/star.png') }}">@lang('labels.frontend.constructor.profile.reviews_and_ratings')</h3>
                </div>

                <div class="row">
                  @forelse($review_datas as $review)
                  <div class="col-md-4 div_show2">
                    <div class="p-3 company-review">
                      <div class="user-main mb-3">
                        @if(!empty($review->avatar_location))
                        <img src="{{ url('img/user/profile', $review->avatar_location) }}" class="mr-3">
                        @else
                        <img src="{{url('img/logo/logo.jpeg')}}" class="mr-3">
                        @endif

                        <div class="user-right">
                           <?php $count= $review->rating;?>
                           <p>
                            <?php
                            for ($i=1; $i <6 ; $i++)
                            {
                                if($i > $review->rating)
                                {
                                    echo '<i class="fa fa-star"></i>';
                                }else{
                                    echo '<i class="fa fa-star" style="color:#ffcd1b"></i>';
                                }
                            }
                            ?>
                          </p>
                        </div>
                      </div>
                      <div class="media-body">
                        <p>{{$review->review}}</p>
                        <h6>{{$review->username}},{{$review->provider_name}}</h6>
                      </div>
                    </div>
                  </div>
                  @empty
                  @endforelse
                </div>
                @if(count($review_datas)>3)
                <a href="javascript:void(0)" class="orange text-right w-100 d-block see_image" id="load2">@lang('labels.frontend.constructor.profile.see_all')</a>
                @endif
              </div>

            </div>
            <div class="col-md-12 row">
                @if($proservice->hire_status!=1)
                <form method="POST" action="{{url('hireproorcompany')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="request_id" value="{{$serviceId}}">
                    <input type="hidden" name="pro_user_id" value="{{$userId}}">
                    <div class="col-md-4">
                        <button class="btn login-btn" type="submit" id="nextScreen">Contratar</button>
                    </div>
                </form>
                @endif
                 <div class="col-md-4">
                   <a href="{{url('/projects')}}"> <button class="btn login-btn" type="button" id="nextScreen">Atr??s</button></a>
                </div>
                
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

</div>


<!--///////////////////UPDATEcertification Courses modal start here/////////////////////////-->
<div class="modal fade" id="editSlider" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
        <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('labels.frontend.constructor.profile.edit_banner')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
              </div>
              {{ html()->form('POST', route('frontend.contractor.my-profile.update_banner'))->attribute('enctype', 'multipart/form-data')->open() }}
              <div class="modal-body">
                <div class="add-profile-img">
                  <input type="file" accept="image/*" class="avatar_location" name="banner_img" id="imgupload2" style="display:none"/>
                    <button type="button" id="OpenImgUpload">
                    <span class="file-block">
                      <div id="imageUploadDivAppend">
                       <?php $pic= 'img/frontend/user.png';
                         if(isset($user) && !empty($user->banner)){$pic= 'img/contractor/banner/'.$user->banner;} ?>
                      <img id="thumbnil11" src="{{url($pic)}}"  class="pro-img1" style="height: 76px !important;" alt="image">
                      </div>
                    </span>
                    </button>
                </div>
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="user_id" id="companyUserId1" value="{{ isset($userId) && !empty($userId) ? $userId : '3'  }}">
              </div>
              
              <div class="modal-footer">
                  <button type="button" class="btn close-btn" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
                  <button type="submit" class="btn opp-btn">@lang('labels.frontend.constructor.profile.send')</button>
              </div>
              {{ html()->form()->close() }}
        </div>
  </div>
</div>
<!--///////////////////UPDATE certification Courses modal End here/////////////////////////-->
  

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
        <button class="btn btn-success getProfile crop_image">@lang('labels.frontend.constructor.profile.crop_image')</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">@lang('labels.frontend.constructor.profile.close')</button>
        </div>
      </div>
   </div>
</div>

{{ script('js/jquery.min.js') }}

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">
<style>
  #insertimageModal .modal-dialog {
    max-width: 95%;
    margin: 1.75rem auto;
  }
  #insertimageModal button.close {
      position: absolute;
      right: 20px;
      top: 15px;
  }
  .foto-img img {
    margin-bottom: 0;
}
</style>
<script>  
$(document).ready(function(){

 $image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:1170,
      height:320,
      type:'square' //circle
    },
    boundary:{
      width:1230,
      height:350
    }    
  });

  $('#imgupload2').on('change', function(){
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

     var userId = $('#companyUserId1').val();

      $.ajax({
        url: "{!! URL::to('insert_constractor_banner') !!}",
        type:'POST',
        beforeSend:showLoader,
        data: {"_token": "{{ csrf_token() }}","image": response,"userid": userId },
        dataType: "json",
        success:function(data)
        {
          hideLoader();

          $('#insertimageModal').modal('hide');
          $('.foto-img img').attr('src',data.banner+'?'+Math.random());
          $('#thumbnil11').attr('src',data.banner+'?'+Math.random());
          $('#thumbnil').attr('src',data.banner+'?'+Math.random());

         },
         error:hideLoader
      })
    });
  });

});  
</script>
<style type="text/css">
  .div_show { display:none; }
  .div_show1 { display:none; }
  .div_show2 { display:none; }
  .div_show3 { display:none; }
  .div_show4 { display:none; }

  span.file-block {
    width: 303px !important;
    }

  .add-profile-img button span {

    border-radius: 0%;
    }

  .pro-img1 {
    border-radius: 0%;
}
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
    $(".div_show1").slice(0, 1).show(); // select the first ten
    $("#load1").click(function(e){ // click event for load more
        e.preventDefault();
        $(".div_show1:hidden").slice(0, 1).show(); // select next 10 hidden divs and show them
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
<script type="text/javascript">
$(function(){
    $(".div_show4").slice(0, 3).show(); // select the first ten
    $("#load4").click(function(e){ // click event for load more
        e.preventDefault();
        $(".div_show4:hidden").slice(0, 3).show(); // select next 10 hidden divs and show them
        if($(".div_show4:hidden").length < 0){ // check if any hidden divs still exist
            alert("No more divs"); // alert if there are none left
        }
    });   
});
</script>
<script type="text/javascript">

  $('#OpenImgUpload').click(function(){ $('#imgupload2').trigger('click'); });

</script>

<!-- <script>
  function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#thanksBanner').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#imgupload").change(function(){
        readURL(this);
    });
</script>
 -->
@endsection

