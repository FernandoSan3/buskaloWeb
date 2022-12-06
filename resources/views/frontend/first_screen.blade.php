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

{{--<section class="business-register">
     <div class="business-header">
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
    </div>
    <div class="container">
     <div class="business-content">
      {{ html()->form('POST', route('frontend.reg_type'))->attribute('enctype', 'multipart/form-data')->open() }}
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <h5>Bienvenido(a) a la Red de los mejores profesionales del Ecuador</h5>

        <p>Te ayudaremos a que tu negocio crezca</p>
        <p>¡Comencemos!</p>

        <div style="text-align: left; margin-bottom: 5%"><b>te vas a registrar como :</b></div>
        <div class="meta-list reg-option">
        
          <label class="cust-radio">Empresa
            <input type="radio" checked="checked" name="user_group_id" value="4">
            <span class="checkmark"></span>
          </label>

          <label class="cust-radio">Profesional Independiente
            <input type="radio" name="user_group_id" value="3">
            <span class="checkmark"></span>
          </label>
        </div>

        <div class="reg-div">
          <button class="btn login-btn" type="submit">Siguiente</button>
        </div>
        </form>

      </div> --}}

      <header>
        <div class="container">
          <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="{{url('/')}}"><img src="{{url('img/frontend/logo.svg')}}"></a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                  <i class="fa fa-bars"></i>
                </span>
              </button>

            <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                @include('includes.partials.lang')
              </ul>
            </div>
          </nav>
        </div>
      </header>


  <!-- <div class="fst-header">
    <div class="container">
      <div class="inner-fst">
      <div class="row">
        <div class="col-md-6 fst-logo">
          <a href="{{ url('/') }}"><img src="{{ url('img/frontend/logo-icon.png') }}"></a>
        </div>
        <div class="col-md-6 fst-user">
          <a href="{{ url('/') }}"><img src="{{ url('img/frontend/user-professional.png') }}"></a>
        </div>
      </div>
    </div>
    </div>
  </div> -->

  <div class="fst-screen">
    <div class="container">
      <div class="first-screen-content">
         {{ html()->form('POST', route('frontend.reg_type'))->attribute('enctype', 'multipart/form-data')->id('user_selection')->open() }}
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <h2>@lang('labels.frontend.index.welcome')</h2>
        <!-- <h3> @lang('labels.frontend.index.you_are_in_the_red')</h3> -->
        <div class="screen-img">
          <img class="img-fluid" src="{{ url('img/frontend/login-banner.png') }}">
         <!--  <h4>Te ayudaremos a que tu negocio crezca. ¡Comencemos!</h4> -->
         <h4>@lang('Gracias por tu interés de formar parte de los,<br/> mejores profesionales del país.')</h4>
         <!-- <h4>A un paso de conseguirlo todo</h4> -->
        </div>
        <div class="meta-list reg-option">
         <p>@lang('labels.frontend.index.you_want_to_apply_as')</p>
         <!--  <p>Empieza tu registro como:</p> -->
          <label class="cust-radio">@lang('labels.frontend.index.business')
            <input type="radio" checked="checked" name="user_group_id" value="4">            
            <span class="checkmark"></span>
          </label>
          <!-- Profesional Independiente -->
          <label class="cust-radio">@lang('labels.frontend.index.professional_independent')
            <input type="radio" name="user_group_id" value="3">
            <span class="checkmark"></span>
          </label>
        </div>
        <br>
        
        <div class="reg-div">
          {{-- <button class="btn login-btn" type="submit">Siguiente</button> --}}
          <button class="btn login-btn" type="submit" id="nextScreen1">@lang('labels.frontend.index.continue')</button>

          <!-- <center><p style="font-size: 16px;">Todo lo que registras acepta nuestros terminos condiciones</p></center> -->
        </div>

        </form>
      </div>
    </div>
    
    </div>
  </section>
  

  @include('includes.partials.ga')
  @include('frontend.includes.footer')

  </body>
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  <script type="text/javascript">
  
  $('#nextScreen').click(function(){
      
    var user_group_id = $('input[name=user_group_id]:checked', '#user_selection').val();
    var url_ay = '{!! URL::to("redirect_register") !!}';    
    $.ajax({
      type: "GET",
      url: '{!! URL::to("reg_type_new") !!}',
      data:{user_group_id:user_group_id},
      success: function(data){
        window.location.href =  url_ay;
      }
    });   
      
  })
  
  </script>
</html>