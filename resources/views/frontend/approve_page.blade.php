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
              <ul class="navbar-nav mr-auto">
           
                @include('includes.partials.lang')
              </ul>

              <!-- <ul class="navbar-nav right-nav  ml-auto">
                 <li class="nav-item">
                    <a href="{{ url('/') }}"><img src="{{ url('img/frontend/user-professional.png') }}" style="width: 143px;"></a>
                  </li>
              </ul> -->
            </div>
          </nav>
        </div>
      </header>

  <div class="fst-screen">
    <div class="container">
      <div class="first-screen-content">
         {{ html()->form('POST', route('frontend.reg_type'))->attribute('enctype', 'multipart/form-data')->id('user_selection')->open() }}
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <h2>@lang('¡Genial!')</h2>
        <div class="screen-img">
          <img class="img-fluid" src="{{ url('img/frontend/login-banner.png') }}">
         <h4>@lang('¡Tu solicitud fue enviada! esta será revisada y verificada,<br/>
            en máximo 48 horas recibirás una notificación.')
          </h4>
         
         
        </div>
        <div class="meta-list reg-option">
           <h4>
            @lang('Si eres aceptado/a formarás parte de la RED de los <br/>
              mejores profesionales')
          </h4>
          
        </div>
        <br>
        </form>
      </div>
    </div>
    
    </div>
  </section>
  

  @include('includes.partials.ga')
  @include('frontend.includes.footer')

  </body>


  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
@if(!empty($user)&& $user->user_group_id==4)
  <script type="text/javascript">
     setTimeout(function () {
                    window.location.href="{{url('company_profile/mi-perfil')}}"
                 }, 2500);
             </script>script>
  @elseif(!empty($user)&& $user->user_group_id==3)
<script type="text/javascript">
     setTimeout(function () {
                    window.location.href="{{url('my-profile')}}";
                    
                 }, 2500);
             </script>script>
  @endif
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


