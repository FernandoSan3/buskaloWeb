@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title'))

@section('content')

<div class="login-box inner-page">
<div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-img-top">
            <img class="log-img" src="{{ url('img/frontend/review-add.svg') }}" alt="Card image cap">
              <h5 class="card-title text-center">@lang('labels.frontend.otp_verify.verification_code')</h5>
              <p>@lang('labels.frontend.otp_verify.enter_verification_code')</p>
          </div>
           
          <div class="card-body">
           
             {{ html()->form('POST', route('frontend.auth.register.verifyOtp'))->class('form-signin form-register')->open() }}
              
              <input type="hidden" name="userid" value="<?php echo $userid; ?>"> </input>
            
                <div class="otp-div">
                  <input type="text" name="otpvalue[]" class="form-control inputs" required="true" placeholder="0" maxlength="1">
                  <input type="text" name="otpvalue[]" class="form-control inputs" required="true" placeholder="0" maxlength="1">
                  <input type="text" name="otpvalue[]" class="form-control inputs" required="true" placeholder="0" maxlength="1">
                  <input type="text" name="otpvalue[]" class="form-control inputs" required="true" placeholder="0" maxlength="1">
                  
                </div>
             
              <div class="reg-div">
                <button class="btn login-btn" name="submit" type="submit">@lang('labels.frontend.otp_verify.check_and_proceed')</button>
              </div>

              <p class="ins-sec">¿@lang('labels.frontend.otp_verify.you_do_not_receive_the_OTP')? <a href="#" class="reg-link"> Reenviar OTP</a></p>
             
             {{ html()->form()->close() }}

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

@endsection

@push('after-scripts')

@endpush
