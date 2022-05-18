<!-- <div class="container">
  <div class="fst-header">
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
  </div>
</div> -->

      <div class="container">
        <nav class="navbar navbar-expand-lg">
           <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ url('img/frontend/logo.svg') }}"></a>

          <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@lang('labels.general.toggle_navigation')">

            <span class="navbar-toggler-icon">
                     <i class="fa fa-bars"></i></span>
          </button>

          <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

              <li class="nav-item">
                <a class="nav-link" href="{{url('home_page')}}">@lang('labels.frontend.company.profile.categories')</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('frontend.service_online')}}">@lang('labels.frontend.company.profile.online_services')</a>
              </li>
            </ul>

            <ul class="navbar-nav right-nav ml-auto">

            <!--  @if(config('locale.status') && count(config('locale.languages')) > 1)
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLanguageLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">{{ strtoupper(app()->getLocale()) }}</a>

                    @include('includes.partials.lang')
                </li>
                
             @endif -->

             @auth

               @if($logged_in_user->user_group_id==3)
                 <li class="nav-item"><a href="{{route('frontend.contractor.mi-perfil')}}" class="nav-link {{ active_class(Route::is('frontend.user.dashboard')) }}">
                 @lang('labels.frontend.company.profile.my_profile')</a></li>
               @else
                 <li class="nav-item"><a href="{{route('frontend.user.dashboard')}}" class="nav-link {{ active_class(Route::is('frontend.user.dashboard')) }}">
                  @lang('labels.frontend.company.profile.my_profile')<!-- @lang('navs.frontend.dashboard') --></a></li>
               @endif
              
            @endauth

            @guest
                <li class="nav-item"><a href="{{route('frontend.auth.login')}}" class="nav-link {{ active_class(Route::is('frontend.auth.login')) }}">@lang('navs.frontend.login')</a></li>

                @if(config('access.registration'))
                <li class="nav-item">
                  <span class="btn get-join my-2 my-sm-0"><a href="{{route('frontend.index')}}" class="nav-link {{ active_class(Route::is('frontend.index')) }}">@lang('navs.frontend.join')</a></span>
                </li>
                @endif
                @else
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">{{ $logged_in_user->name }}</a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                        @can('view backend')
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">@lang('navs.frontend.user.administration')</a>
                        @endcan


                         @if($logged_in_user->user_group_id==3)
              
                            <a href="{{ route('frontend.contractor.mi-perfil') }}" class="dropdown-item {{ active_class(Route::is('frontend.contractor.mi-perfil')) }}">Mi Perfil</a>

                          @elseif($logged_in_user->user_group_id==2)
              
                            <a href="{{ route('frontend.user.dashboard') }}" class="dropdown-item {{ active_class(Route::is('frontend.user.dashboard')) }}">@lang('labels.frontend.company.profile.my_profile')</a>
                         @else

                            <a href="{{ route('frontend.user.account') }}" class="dropdown-item {{ active_class(Route::is('frontend.user.account')) }}">@lang('navs.frontend.user.account')</a>
                        
                        @endif

                        <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item">@lang('navs.general.logout')</a>
                    </div>
                </li>
            @endguest
            </ul>
          </div>
    </nav>
  </div>