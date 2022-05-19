@extends('frontend.layouts.auth_app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.login_box_title'))

@section('content')

<header>
      <div class="container">
        <nav class="navbar navbar-expand-lg">
          <a class="navbar-brand" href="{{url('/')}}"><img src="img/frontend/logo.svg"></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
              <i class="fa fa-bars"></i></span>
          </button>

          <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent">
           <!--  <ul class="navbar-nav mr-auto">
               <li class="nav-item">
                <a class="nav-link" href="{{url('home_page')}}">@lang('labels.frontend.company.profile.categories')</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('frontend.service_online')}}">@lang('labels.frontend.company.profile.online_services')</a>
              </li>

               @if(config('locale.status') && count(config('locale.languages')) > 1)
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLanguageLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">{{ strtoupper(app()->getLocale()) }}</a>

                    @include('includes.partials.lang')
                </li>
             @endif
            </ul> -->

            <!-- <ul class="navbar-nav right-nav  ml-auto">
               <li class="nav-item">
                  <a href="{{ url('/') }}"><img src="{{ url('img/frontend/user-professional.png') }}" style="width: 143px;"></a>
                </li>
            </ul> -->
        </div>
    </nav>
  </div>
</header>
 <!-- <div class="business-header">
      <div class="row no-gutters">
        <div class="col-md-4">
          <div class="user-pro-img">
            <img src="{{ url('img/frontend/user-professional.png') }}">
          </div>
        </div>
        <div class="col-md-8">
          <img src="{{ url('img/frontend/logo.svg') }}">
        </div>
      </div>
  </div>  -->


<div class="login-box inner-page">
<div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-img-top">
            <img class="log-img" src="{{ url('img/frontend/user.svg') }}" alt="Card image cap">
              <!-- <h5 class="card-title text-center"> iniciar sesión</h5> -->
              <p style="font-size: 18px;"> ¡@lang('labels.frontend.login.we_are_ready_to_start')!</p>

          </div>
           
          <div class="card-body">

           {{ html()->form('POST', route('frontend.auth.login.post'))->class('form-signin')->id('form_id')->open() }}

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="{{ url('img/frontend/mail.png') }}"></span>
                </div>
                    {{-- html()->email('email')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.email'))
                    ->attribute('maxlength', 191)
                    ->required() --}}

                    <input type="email" name="email" class="form-control" placeholder="@lang('labels.frontend.login.email')" autocomplete="off" required="" maxlength="191"></input>
              </div>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="{{ url('img/frontend/pass.png') }}"></span>
                </div>
                    {{-- html()->password('password')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.password'))
                    ->required() --}}

                      <input type="password" name="password" class="form-control" placeholder="@lang('labels.frontend.login.password')" autocomplete="off" required=""></input>
                </div>
              

              <div class="custom-control custom-checkbox rem-div">

                <input type="checkbox" value="1" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">@lang('labels.frontend.login.remember_me')</label>

                <!-- <a class="forgot-cls" href="{{ route('frontend.auth.password.reset') }}">¿Olvidaste tu contraseña?</a> -->
                <a class="forgot-cls" href="{{ route('frontend.auth.password.reset') }}">@lang('labels.frontend.login.i_forgot_my_password')</a>

              </div>

              <!-- <button class="btn login-btn" type="submit">iniciar sesión</button> -->
              <button class="btn login-btn" type="submit">@lang('labels.frontend.login.pay_in')</button>

                <!-- @if(config('access.captcha.login'))
                    <div class="row">
                        <div class="col">
                            @captcha
                            {{ html()->hidden('captcha_status', 'true') }}
                        </div>
                    </div>  
                @endif -->

                <!-- </div>col -->
                <!-- </div>  row -->

              <p class="ins-sec">@lang('labels.frontend.login.dont_have_an_account'), <!-- <a href="{{ url('/') }}" class="reg-link"> -->
              <a href="#" class="reg-link" data-toggle="modal" data-target="#exampleModal">
                @lang('labels.frontend.login.sign_up')
                </a></p>
             
            {{ html()->form()->close() }}
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> @lang('labels.frontend.login.sign_up')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="meta-list reg-option">
         {{ html()->form('POST', route('frontend.reg_type'))->attribute('enctype', 'multipart/form-data')->open() }}
          <label class="cust-radio">Empresa
            <input type="radio" checked="checked" name="user_group_id" value="4">
            <span class="checkmark"></span>
          </label>

          <label class="cust-radio">Profesional Independiente
            <input type="radio" name="user_group_id" value="3">
            <span class="checkmark"></span>
          </label>
          <label class="cust-radio">Usuarios
            <input type="radio" name="user_group_id" value="2">
            <span class="checkmark"></span>
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
        <button type="submit" class="btn btn-primary">@lang('labels.frontend.login.pay_in')</button>
      </div>
       </form>
    </div>
  </div>
</div>
@endsection

<!-- @push('after-scripts')
    @if(config('access.captcha.login'))
        @captchaScripts
    @endif
@endpush -->
