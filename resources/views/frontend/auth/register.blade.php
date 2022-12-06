@extends('frontend.layouts.auth_app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title'))

@section('content')

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

 
<header>
  <div class="container">
    <nav class="navbar navbar-expand-lg">
      <a class="navbar-brand" href="{{url('/')}}"><img src="img/frontend/logo.svg"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
          <i class="fa fa-bars"></i>
        </span>
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
        <!-- 
        <ul class="navbar-nav right-nav ml-auto">
            <li class="nav-item">
              <a href="{{ url('/') }}"><img src="{{ url('img/frontend/user-professional.png') }}" style="width: 143px;"></a>
            </li>
        </ul> -->
      </div>
    </nav>
  </div>
</header>
<div class="login-box inner-page">
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-img-top">
            <img class="log-img" src="{{ url('img/frontend/review-add.svg') }}" alt="Card image cap">
            @if($user_group_id == 3)
              <h5 class="card-title text-center">@lang('labels.frontend.register.registry_constractor')</h5>
            @elseif($user_group_id == 4)
              <h5 class="card-title text-center">@lang('labels.frontend.register.registry_company')</h5>
            @elseif($user_group_id == 2)
              <h5 class="card-title text-center">@lang('labels.frontend.register.registry_user')</h5>
            @endif
          </div>
           
          <div class="card-body">
            {{ html()->form('POST', route('frontend.auth.register.post'))->class('form-signin form-register')->id('register_form')->open() }}
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="{{ url('img/frontend/name.png') }}"></span>
                </div>
                {{ html()->text('username')
                  ->class('form-control')
                  ->placeholder(__('labels.frontend.register.username'))
                  ->attribute('maxlength', 191)
                  ->required()
                }}
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="{{ url('img/frontend/phone.png') }}"></span>
                </div>
                {{ html()->text('mobile_number')
                  ->class('form-control')
                  ->id('mobile_phone_number')
                  ->placeholder(__('labels.frontend.register.mobile_phone_number'))
                  ->attribute('maxlength', 10)
                  ->required() 
                }}
                <div class="mobile-number-msg" style="text-align: center;"></div>
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="{{ url('img/frontend/mail.png') }}"></span>
                </div>
                {{ html()->email('email')
                  ->class('form-control')
                  ->placeholder(__('labels.frontend.register.email'))
                  ->attribute('maxlength', 191)
                  ->id('email6')
                  ->required()
                }}
                <br/>
                 <div class="email-msg" style="text-align: center;"></div>
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="{{ url('img/frontend/pass.png') }}"></span>
                </div>
                {{ html()->password('password')
                  ->class('form-control')
                  ->placeholder(__('labels.frontend.register.password'))
                  ->required() 
                }}
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="{{ url('img/frontend/pass.png') }}"></span>
                </div>
                {{ html()->password('password_confirmation')
                  ->class('form-control')
                  ->placeholder(__('labels.frontend.register.confirm_password'))
                  ->required() 
                }}
              </div>
              
              <input type="hidden" name="approval_status" value="{{ isset($request_data['approval_status']) ?$request_data['approval_status'] : 0}}">
              <input type="hidden" name="user_group_id" value="{{ isset($request_data['user_group_id']) ?$request_data['user_group_id'] : 0}}">
              <!-- PRINT -->
              <?php echo $request_data['user_group_id']?>
              <?php echo $user_group_id ?>
              <!-- <input type="hidden" name="user_group_id" value="{{$user_group_id}}"> -->
              <!-- <input type="hidden" name="user_group_id" value="{{ isset($user_group_id) }}">  -->

              @if($user_group_id==3)
                <input type="checkbox" name="privacy_policy" required="" id="policy" <?php if( isset($request_data['approval_status']) && $request_data['approval_status'] == 1 ){ echo 'checked="checked"'; }?>>
                <a href="{{url('characteristics-conditions')}}" target="_blank"> @lang('labels.frontend.register.privacy_policy') </a>
              @elseif($user_group_id==4)
                <input type="checkbox" name="privacy_policy" required="" id="policy" <?php if( isset($request_data['approval_status']) && $request_data['approval_status'] == 1 ){ echo 'checked="checked"'; }?>>
                <a href="{{url('characteristics-conditions')}}" target="_blank"> @lang('labels.frontend.register.privacy_policy') </a>
              @endif

                  <!-- @php $disabled = 'disabled="disabled"' @endphp
                  @if(isset($request_data['tc_approved']) && $request_data['tc_approved'] == 1)
                      @php $disabled = '' @endphp
                  @endif -->

              <div class="input-group1">
                <label class="col-sm-12 col-form-label">@lang('Recaptcha'):</label>
              </div>
              <div class="input-group1">
                <div class="col-captcha-register">
                  <!-- @if (config('access.captcha.registration')) -->
                  <div class="form-group">
                    <div class="col-md-12">
                      <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}">
                      </div>
                    </div>
                  </div>
                  <!-- @endif -->
                </div>
              </div>

              <div class="reg-div">
                <button class="btn login-btn" type="submit">@lang('labels.frontend.register.check_in')</button>
              </div>

              <div class="social-reg">
                <a href="{{url('login/facebook')}}"><img src="{{ url('img/frontend/fb.png') }}"></a>
                <a href="{{url('login/google')}}"><img src="{{ url('img/frontend/google.png') }}"></a>
              </div>
             
            <!--  <p class="ins-sec">@lang('labels.frontend.register.terms_and_conditions')</p> -->
             
            {{ html()->form()->close() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <script>
    <?php 
    if(isset($request_data['tc_approved']) && $request_data['tc_approved'] == 1)
    {
      ?>
      $(document).ready(function(){
        if($("#policy").prop("checked")){
            var s = "{{$request_data['tc_approved']}}";
            if(s==1){                
                $('#register-btn').prop('disabled', false);
            }
            else{
                // alert("Please accept our T&C"); 
                $('#register-btn').prop('disabled', true);
            }
        }
        else{
            $('#register-btn').prop('disabled', true);
        }
      });
      <?php
    }
    ?>

      // $(document).ready(function(){
      //     $('#policy').click(function(){
      //         // console.log($(this).prop("checked"));
      //         if($(this).prop("checked")){
      //             var s = "{{ isset($request_data['tc_approved']) ? $request_data['tc_approved'] : 0 }}";
      //             if(s==1){                
      //                 $('#register-btn').prop('disabled', false);
      //             }
      //             else{
      //                 alert("Acepte nuestros T&C pasando por el enlace y acepte los términos y condiciones"); 
      //                 $('#register-btn').prop('disabled', true);
      //             }
      //         }
      //         else{
      //             $('#register-btn').prop('disabled', true);
      //         }
      //     });
      // });
</script>

<script src='https://www.google.com/recaptcha/api.js'></script>

<script>
  $(".accpet_tc").on('click', function(){
   
      var form_data = $("#register_form").serialize();

      var url = "{{url('/characteristics-conditions')}}";
      window.location.href = url;
  });
</script>
@endsection
<style type="text/css">
  .err_msg_a{
    top: 50px;
  }
</style>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>


<script type="text/javascript">
  var specialKeys = new Array();
  specialKeys.push(8); //Backspace
  function IsNumeric(e) {
      var keyCode = e.which ? e.which : e.keyCode
      var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
      document.getElementById("error").style.display = ret ? "none" : "inline";
      return ret;
  }
</script>
