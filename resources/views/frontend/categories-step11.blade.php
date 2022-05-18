@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')

<div class="step-header">
  <div class="container">
    <div class="step-logo">
      <img src="{{asset('img/frontend/logo.svg')}}">
    </div>
    <div class="media">
      <img class="mr-3" src="{{asset('img/frontend/doc.png')}}">
      <div class="media-body">
        <h5 class="mt-0">¡@lang('labels.frontend.home_page.simple')!</h5>
        <p>@lang('labels.frontend.home_page.questionnaire')</p>
      </div>
    </div>
    <?php 
      if($questions) {

        $total_questions = count($questions);
        $step_count = 0;

      }
    ?>
    <div class="category-modal">
      <ul class="nav nav-tabs">
        <?php
          for ($i=1; $i <= $total_questions ; $i++) { 
              $step_count = $step_count + 1; 
                     ?>
            <li class="nav-item">
              <a class="inactiveLink nav-link <?php if($i == 1){?>active <?php } ?> " id="tab{{$step_count}}" data-title ="question" data-step ="{{$step_count}}" data-toggle="tab" ></a>
            </li>

        <?php  }     ?>
        <li class="nav-item">
          <a class="inactiveLink nav-link" data-toggle="tab" id="tab{{$total_questions+1}}"  data-title ="address" data-step ="{{$total_questions+1}}" ></a>
        </li>
        <li class="nav-item">
          <a class="inactiveLink nav-link" data-toggle="tab" id="tab{{$total_questions+2}}" data-title ="fullname" data-step ="{{$total_questions+2}}" ></a>
        </li>
        <li class="nav-item">
          <a class="inactiveLink nav-link" data-toggle="tab" id="tab{{$total_questions+3}}" data-title ="number" data-step ="{{$total_questions+3}}" ></a>
        </li>
        <li class="nav-item">
          <a class="inactiveLink nav-link" data-toggle="tab" id="tab{{$total_questions+4}}" data-title ="otp" data-step ="{{$total_questions+4}}" ></a>
        </li>
        <li class="nav-item">
          <a class="inactiveLink nav-link" data-toggle="tab" id="tab{{$total_questions+5}}" data-title ="success_page" data-step ="{{$total_questions+5}}" ></a>
        </li>

       <!--  <div class="progress-steps">
          <div class="progress">
            <div class="progress-bar" style="width:50%"></div>
          </div>
        </div> -->
      </ul>
    </div>

  </div>
</div>

<section class="categ-step-section">
  <div class="container">
    <div class="" id="serv-step1">
      <form id="regForm" method="post" action="{{ route('frontend.store_service_request') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{$user_id }}">
        <input type="hidden" name="service_id" value="1">
        <div class="tab-content">
          <?php 
            foreach ($questions as $key => $value) { 
          ?>
          <div class="tab-pane <?php if($key == 0) { ?> active <?php } ?>" id="step{{$key+1}}">
            <div class="pro-heading">
              <h3 class="modal-title" id="question_step{{$key+1}}" >{{$value->es_title}}</h3>
              <input type="hidden" name="question[{{$key}}][question_id]" value="{{$value->id}}">
            </div>
            
            <div class="row">
              <div class="col-md-8">
              
                <div class="pro-info">
                  <div class="meta-list">
                    <?php  foreach ($value->option as $ke => $val) { ?>
                     
                    <label class="cust-radio">{{$val->en_option}}
                      <input type="radio" checked="checked" value="{{$val->id}}" class="question_option{{$key+1}}" name="question[{{$key}}][option_id]">
                      <span class="checkmark"></span>
                    </label>
                    <?php } ?>
                    
                  </div>
                </div>

                <div style="overflow:auto;">
                  <div  class="form-btn">
                    <?php if($key != 0) { ?><button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >@lang('labels.frontend.home_page.previous')</button><?php } ?>
                    <button type="button" class="btn pre-btn"  onclick="nextbtnn(this)"  >@lang('labels.frontend.home_page.next')</button>
                  </div>
                </div>
              </div>
            
              <div class="col-md-4">
                <div class="right-added">
                  <div class="right-header">
                    <div class="media head-detail">
                      <img class="mr-3" src="{{asset('img/frontend/dt.png')}}">
                      <div class="media-body">
                        <p>@lang('labels.frontend.home_page.details_of_your_project')</p>
                      </div>
                    </div>

                    <div class="booking-list" >
                      <ul id="summary{{$key+1}}">
                        <li>
                          <h6>@lang('labels.frontend.home_page.interior/exterior')</h6>
                          <p>@lang('labels.frontend.home_page.interior')</p>
                        </li>
                        
                      </ul>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <?php } ?>

          <div class="tab-pane fade"  id="step{{$total_questions+1}}">
            <div class="mid-steps">
              <div class="mid-heading">
                <h2 class="modal-title"> ¡@lang('labels.frontend.home_page.perfect')!</h2>
                <p>@lang('labels.frontend.home_page.finalize')</p>
              </div>

              <div class="mid-data-fill">
                <div class="mid-head">
                  <div class="media">
                    <img class="mr-3" src="{{asset('img/frontend/shield.png')}}">
                    <div class="media-body">
                      <h5 class="mt-0">@lang('labels.frontend.home_page.address_where')</h5>
                    </div>
                  </div>
                </div>
                <div class="form-detail">
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <input type="text" name="address" id="address" placeholder="@lang('labels.frontend.home_page.write_here')">
                    </div>
                  </div>
                </div>
              </div>

              <div  class="form-btn mid-btn">
                <button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang('labels.frontend.home_page.previous')</button>
                <button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang('labels.frontend.home_page.next')</button>
              </div>
            </div>
          </div>

          <div class="tab-pane fade"  id="step{{$total_questions+2}}">
            <div class="mid-steps">
              <div class="mid-heading">
                <h2 class="modal-title"> @lang('labels.frontend.home_page.identify_yourself')</h2>
              </div>
              <div class="mid-data-fill">
                <div class="mid-head">
                  <div class="media">
                    <img class="mr-3" src="{{asset('img/frontend/shield.png')}}">
                    <div class="media-body">
                      <h5 class="mt-0">@lang('labels.frontend.home_page.enter_your_full_name')</h5>
                    </div>
                  </div>
                </div>
                <div class="form-detail">
                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <input type="text" name="username" id="username" placeholder="Escribir Aquí">
                    </div>
                  </div>
                </div>
              </div>

              <div  class="form-btn mid-btn">
                <button type="button" class="btn next-btn" onclick="prevbtnn(this)" >@lang('labels.frontend.home_page.previous')</button>
                <button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang('labels.frontend.home_page.next')</button>
              </div>
            </div>
          </div>

          <div class="tab-pane fade"  id="step{{$total_questions+3}}">
            <div class="mid-steps">
              <div class="mid-heading">
                <h2 class="modal-title"> @lang('labels.frontend.home_page.sms_code') <br>@lang('labels.frontend.home_page.validate_your_request')</h2>
              </div>
              <div class="mid-data-fill">
                <div class="mid-head">
                  <div class="media">
                    <img class="mr-3" src="{{asset('img/frontend/shield.png')}}">
                    <div class="media-body">
                      <h5 class="mt-0">@lang('labels.frontend.home_page.enter_your_cell_phone_number')</h5>
                    </div>
                  </div>
                </div>
                <div class="form-detail">
                    <div class="input-group form-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="country-code">+593</span>
                      </div>
                      <input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="@lang('labels.frontend.home_page.write_here')">
                    </div>
                </div>
              </div>

              <div  class="form-btn mid-btn">
                <button type="button" class="btn next-btn" onclick="prevbtnn(this)" >@lang('labels.frontend.home_page.previous')</button>
                <button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang('labels.frontend.home_page.next')</button>
              </div>
           </div>
          </div>
          
          <div class="tab-pane fade"   id="step{{$total_questions+4}}">
            <div class="mid-steps">
              <div class="mid-heading">
                <h2 class="modal-title">¡@lang('labels.frontend.home_page.almost_ready')!</h2>
              </div>
              <div class="mid-data-fill">
                <div class="mid-head">
                  <div class="media">
                    <img class="mr-3" src="{{asset('img/frontend/shield.png')}}">
                    <div class="media-body">
                      <h5 class="mt-0">@lang('labels.frontend.home_page.enter_the_security_code_sent_to') +593<sup>*******</sup>9854</h5>
                    </div>
                  </div>
                </div>
                <div class="form-detail">
                    <div class="form-group code-num">
                      <input type="text" name="otpvalue[]" id="otp1" class="form-control inputs"  placeholder="0" maxlength="1">
                      <input type="text" name="otpvalue[]" id="otp2" class="form-control inputs"  placeholder="0" maxlength="1">
                      <input type="text" name="otpvalue[]" id="otp3" class="form-control inputs"  placeholder="0" maxlength="1">
                      <input type="text" name="otpvalue[]" id="otp4" class="form-control inputs"  placeholder="0" maxlength="1">
                      <span class="code-counter">0:45 @lang('labels.frontend.home_page.seconds')</span>
                    </div>
                </div>
              </div>

              <div  class="form-btn mid-btn">
                <!-- <button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >Validar</button> -->
                <button type="button" class="btn pre-btn"  id="sub_mit">@lang('labels.frontend.home_page.submit')</button> 
                <button type="submit" class="btn pre-btn" id="submit_form" style="display: none;" >@lang('labels.frontend.home_page.submit')</button>
              </div>
            </div>
          </div>

          <div class="tab-pane fade"  id="step{{$total_questions+5}}">
            <div class="step-final-div mid-steps">
              <div class="media">
                <img class="" src="{{asset('img/frontend/shield.png')}}">
                <div class="media-body">
                    <h3 class="mt-0">¡@lang('labels.frontend.home_page.congratulations')!</h3>
                    <p><b>@lang('labels.frontend.home_page.your_request_has_been_approved'),</b><br> @lang('labels.frontend.home_page.receive_information')</p>
                   <p>¡@lang('labels.frontend.home_page.welcome_to_the_new_age')!</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
  </div>
</section>
@endsection

