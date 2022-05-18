@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))
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
          
          <section class="mt-100">
            
            <div class="container"> 
              
              <div class="service-container bg-light">
                <div class="row">
                  <div class="col-md-6 border-right">
                    <div class="ser_name">
                      <h4 class="heading">@lang('labels.frontend.user.account.request_details')</h4>
                      <p><span class="orange">@lang('labels.frontend.user.account.category') : </span> {{ $service_detail->category_name }}</p>
                      <p><span class="orange">@lang('labels.frontend.user.account.service') : </span> {{ $service_detail->service_name }}</p>
                      <p><span class="orange">@lang('labels.frontend.user.account.sub_service') : </span> {{ $service_detail->sub_service_name }}</p>
                      <p><span class="orange">@lang('labels.frontend.user.account.child_sub_service') : </span> {{ $service_detail->child_subservice_name }}</p>
                      <p><span class="orange">@lang('labels.frontend.user.account.address') : </span> {{ $service_detail->location }}</p>
                      {{-- <div class="ser_addr">
                        <p>Address: 121, xyz Street, xyz apartmnet</p>
                        <p>Email: xyz@gmail.com</p>
                      </div> --}}
                    </div>
                  </div>
                  <div class="col-md-6 text-center ">
                    <h5 class="heading orange">@lang('labels.frontend.user.account.who_accepted')</h5>
                    <ul>
                      <li>
                        <div class="row">
                          <div class="col col-sm-2">
                            <p class=""><b> @lang('labels.frontend.user.account.s_no')  </b></p>
                          </div>
                          <div class="col col-sm-4">
                            <p class=""><b> @lang('labels.frontend.user.account.username')  </b></p>
                          </div>
                          <div class="col-auto col-sm-4"><b>@lang('labels.frontend.user.account.action')</b></div>
                        </div>
                      </li>
                      <?php if(isset($service_buyers) && count($service_buyers) > 0){
                      foreach ($service_buyers as $k_buyer => $v_buyer) {
                      ?>
                      
                      <li>
                        <div class="row">
                          <div class="col col-sm-2">
                            <p class="u_name"> {{ $k_buyer+1 }}  </p>
                          </div>
                          <div class="col col-sm-4">
                            <p class="u_name"> {{ $v_buyer->username }}  </p>
                          </div>
                          <div class="col-auto col-sm-4">
                            <a href="{{route('frontend.user.user_chat')}}"><button class="btn btn-or-outline">Chat</button></a>
                             
                             <?php if($service_detail->status == 0 || $service_detail->status == 1 ) { 
                            ?>

                            <a href="{{route('frontend.user.hier_service_provider',['request_id' =>Crypt::encrypt($service_detail->id),'provider_id' => Crypt::encrypt($v_buyer->user_id)])}}"><button class="btn btn-or-outline">@lang('labels.frontend.user.account.hire')</button></a>

                            <?php } ?>
                            
                          </div>
                        </div>
                      </li>
                      
                      <?php   } } else {
                      echo "Not Accepted By Anyone yet";
                      } ?>
                      
                      {{-- <li>
                        <div class="row">
                          <div class="col col-sm-6">
                            <p class="u_name">@lang('labels.frontend.user.account.contractor') 2 </p>
                          </div>
                          <div class="col-auto col-sm-6"><button class="btn btn-or-outline">@lang('labels.frontend.user.account.chat')</button></div>
                        </div>
                      </li>
                      <li>
                        <div class="row">
                          <div class="col col-sm-6">
                            <p class="u_name">@lang('labels.frontend.user.account.contractor') 3 </p>
                          </div>
                          <div class="col-auto col-sm-6"><button class="btn btn-or-outline">@lang('labels.frontend.user.account.chat')</button></div>
                        </div>
                      </li> --}}
                      
                      
                    </ul>
                    <div class="text-center">
                      <?php if(isset($service_buyers) && count($service_buyers) > 0){ ?>
                      <a href="{{route('frontend.user.profile_comprasion',Crypt::encrypt($service_detail->id))}}"><button type="button"  class="btn btn-or-round">@lang('labels.frontend.user.account.profile_compare')</button></a>
                      <?php }else { ?>
                      
                      <button type="button" disabled="" class="btn btn-or-round">@lang('labels.frontend.user.account.profile_compare')</button>
                      <?php } ?>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="service_bx_quiz">
                <ul>
                  <?php if(isset($question_detail) && count($question_detail) > 0) {
                  foreach ($question_detail as $key => $value) {
                  
                  
                  ?>
                  
                  <li>
                    <div class="ser_ques">
                      <span class="ser_number">Q.</span>
                      {{ $value->question_title }}
                    </div>
                    <div class="ser_ans">
                      <?php if($value->question_type == 'radio' || $value->question_type == 'checkbox' || $value->question_type == 'select') { ?>
                      {{ $value->es_option }}
                      
                      <?php } elseif($value->question_type == 'file') { ?>
                      
                      <img src="{{ $value->option_id }}">
                      <?php }else { ?>
                      {{ $value->option_id }}
                      <?php } ?>
                    </div>
                  </li>
                  <?php }  } ?>
                  {{--  <li>
                    <div class="ser_ques">
                      <span class="ser_number">Q.</span>
                      If a red house is made from red bricks, a blue house is made from blue bricks, a pink house is made from pink bricks, and a black house is made from black bricks. What is a greenhouse made from?
                    </div>
                    <div class="ser_ans">
                      Glass
                    </div>
                  </li>
                  <li>
                    <div class="ser_ques">
                      <span class="ser_number">Q.</span>
                      If a red house is made from red bricks, a blue house is made from blue bricks, a pink house is made from pink bricks, and a black house is made from black bricks. What is a greenhouse made from?
                    </div>
                    <div class="ser_ans">
                      Glass
                    </div>
                  </li>
                  <li>
                    <div class="ser_ques">
                      <span class="ser_number">Q.</span>
                      If a red house is made from red bricks, a blue house is made from blue bricks, a pink house is made from pink bricks, and a black house is made from black bricks. What is a greenhouse made from?
                    </div>
                    <div class="ser_ans">
                      Glass
                    </div>
                  </li> --}}
                </ul>
              </div>
            </div>
          </section>
        </div></div></div>
        @endsection