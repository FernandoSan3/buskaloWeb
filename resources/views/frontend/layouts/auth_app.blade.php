<!DOCTYPE html>
@langrtl
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl
  @include('frontend.includes.head')
  {{-- @include('frontend.includes.header') --}}

  <body>
	<?php
	$route = Route::current();
	$route_name = Route::currentRouteName();
	?>
   
	@if($route_name == 'frontend.index' || $route_name == 'frontend.home_page' )
		@include('frontend.includes.slider')
	@else
		@include('frontend.includes.innerslider')	
	@endif
	@include('includes.partials.logged-in-as')
	
	<div class="container1">
		@if($route_name != 'frontend.redirect_register')
			@include('includes.partials.messages')
		@endif
		
		@yield('content')
	</div><!-- container -->
	
	@include('frontend.includes.footer')
    @yield('after-script')
	@include('includes.partials.ga')
  </body>
</html>