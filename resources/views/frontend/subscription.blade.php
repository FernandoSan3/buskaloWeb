@extends('frontend.layouts.app')
@section('content')
<div class="header-profile">
  <div class="top-header-profile">
    <div class="top-profile-info">
      <div class="media"> 

      <?php $pic= 'img/frontend/user.png';
      if($userdata->user_group_id==3){

        if(isset($userdata) && !empty($userdata->avatar_location)){$pic= 'img/contractor/profile/'.$userdata->avatar_location;}
            ?>

          <img src="{{ url($pic) }}" id="thumbnil22" class="pro-img">
          <?php }else{
            

          if(isset($userdata) && !empty($userdata->avatar_location)){$pic= 'img/company/profile/'.$userdata->avatar_location;}
          ?>

          <img src="{{ url($pic) }}" id="thumbnil22" class="pro-img">
        <?php }?>

         <div class="media-body">
          <h4>{{$userdata->username}}</h4>
         @if($userdata->approval_status==1 && $userdata->is_confirm_reg_step==1)
            <p><img src="{{ url('img/frontend/check3.png') }}" class="sm-img pr-1">@lang('labels.frontend.subscription.verified_profile')</p>
          @endif
        </div>
      </div>
    </div>
  </div>  
  <div id="wrapper" class="toggled left-sidebar">
    <?php 
      if($userdata->user_group_id==3){

    ?>
    <!-- Sidebar -->
    @include('frontend.contractor.profile_sidebar')
  <?php }else{ ?>

    @include('frontend.company.profile_sidebar')

  <?php }?>
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
              <span> @lang('labels.frontend.subscription.my_account')</span></div>
            </div>
            <div class="">
              <div class="row no-gutters">
                <div class="col-md-12 ">
                  <div class="data-info pro-wrapper">
                    <div class="border-bottom pb-3">
                      <img src="{{ url('img/frontend/save-money.png') }}" class="img-fluid">
                      <span class="s_price"> <?php if(isset($userdata) && !empty($userdata->pro_credit)){echo  number_format($userdata->pro_credit,2);} else { echo '0.00';}?>  </span>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>   

            <div class="tab-pane active" id="miperfil">
              <div class="profile-detail-sec">
                <div class="profile-head">
                  <div class="top-edit">
                      <div class="container">
                        <div class="row">
                          <?php foreach ($package as $key => $value) { ?>
                            <div class="col-md-6 col-lg-3 mb-3">
                               <div class="box-1">
                                 <div class="rate-box">
                                   <h5>$<?php echo $value['price'] ?></h5>
                                 </div>
                                 <h6><?php echo $value['es_name'] ?></h6>
                                     <p>Paquete de<br>Créditos <br><strong>$<?php echo  $value['price'] ?></strong></p>
                                     <p>Cantidad de<br><strong><?php echo  $value['credit'] ?></strong> Créditos </p>
                                     <p>Descuento<br><strong><?php echo  $value['discount'] ?></strong></p>
                                  <a href="{{ url('payment/'.$value['id'].'/'.$value['es_name'].'/'.$userdata->id)}}" class="btn user_login gr-btn">
                                    <div class="cart-icon box-cart">
                                    <img src="{{ url('img/logo/cart2.png') }}" >
                                     <p>@lang('labels.frontend.subscription.buy_credites')</p>
                                 </div>
                               </a>
                               </div>
                            </div>

                            <?php 
                          } ?>

                          </div>
                          <div class="img-txt">
                            <h2><img src="{{ url('img/logo/check-img.png') }}" >
                            iTu @lang('labels.frontend.subscription.purchase') 100% @lang('labels.frontend.subscription.safe')!</h2>
                            <h5>@lang('labels.frontend.subscription.paying_online')<br>
                            100% @lang('labels.frontend.subscription.safe_and_guaranteed')</h5>
                            <h6><a href="{{url('review-payment-security-policies')}}">@lang('labels.frontend.subscription.review_payment_security_policies')</a></h6>
                            <?php
                              if($userdata->user_group_id==3){

                             ?>
                            <a href="{{url('my-profile')}}"><button class="btn-regrecia">@lang('labels.frontend.subscription.back_to_my_account')</button></a>

                           <?php }else{?>

                            <a href="{{url('company_profile')}}"><button class="btn-regrecia">@lang('labels.frontend.subscription.back_to_my_account')</button></a>
                          <?php }?>
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
.border-bottom.pb-3 {
  border-bottom: 0px solid #dee2e6!important;
}
</style>

@endsection
