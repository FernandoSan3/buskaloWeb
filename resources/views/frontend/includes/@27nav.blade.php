
<nav class="navbar sticky-top navbar-expand-lg top-navbar">
  <a href="#menu-toggle" id="menu-toggle" class="navbar-brand-toggle">
    <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
  </a>
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ url('img/frontend/logo.svg') }}"></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@lang('labels.general.toggle_navigation')">
    <span class="navbar-toggler-icon">
             <i class="fa fa-bars"></i></span>
    </button>
    <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          {{-- <a class="nav-link" href="#">Categorías</a> --}}
        </li>
        <li class="nav-item">
        {{--   <a class="nav-link" href="{{route('frontend.service_online')}}">Servicios Online</a> --}}
        </li>
      </ul>
      <ul class="navbar-nav right-nav  ml-auto">
        @if(config('locale.status') && count(config('locale.languages')) > 1)
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLanguageLink" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">{{ strtoupper(app()->getLocale()) }}</a>
          @include('includes.partials.lang')
        </li>
        @endif
        @auth
        <li class="nav-item">{{-- <a href="{{route('frontend.user.dashboard')}}" class="nav-link {{ active_class(Route::is('frontend.user.dashboard')) }}">@lang('navs.frontend.dashboard')</a> --}}</li>
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
          aria-haspopup="true" aria-expanded="false">{{ $logged_in_user->username }}</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
            @can('view backend')
            {{-- <a href="{{ route('admin.dashboard') }}" class="dropdown-item">@lang('navs.frontend.user.administration')</a> --}}
            @endcan
            {{-- <a href="{{ route('frontend.user.account') }}" class="dropdown-item {{ active_class(Route::is('frontend.user.account')) }}">@lang('navs.frontend.user.account')</a> --}}
            <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item">@lang('navs.general.logout')</a>
          </div>
        </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>