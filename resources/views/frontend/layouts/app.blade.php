<!DOCTYPE html>
@langrtl
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl
  @include('frontend.includes.head')
        @auth
		  @include('frontend.includes.header_cont')
        @endauth
		 @guest

		@include('frontend.includes.header')

		 @endguest
 
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
	@if($route_name == 'frontend.service_online')
		@include('frontend.includes.service_slider')
	@endif
	@include('includes.partials.logged-in-as')
	
	<div class="container1">
		@if($route_name != 'frontend.redirect_register')
			@include('includes.partials.messages')
		@endif
		<div id="chat-overlay" class=""></div>
	    <audio id="chat-alert-sound" style="display: none">
	        <source src="{{ asset('sound/facebook_chat.mp3') }}" />
	    </audio>
		
		@yield('content')
	</div><!-- container -->
	
	@include('frontend.includes.footer')
     <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jstimezonedetect/1.0.4/jstz.min.js">
        </script>
        <script type="text/javascript">
          $(document).ready(function(){
            var tz = jstz.determine(); // Determines the time zone of the browser client
            var timezone = tz.name(); //'Asia/Kolhata' for Indian Time.
           console.log(timezone);
           document.cookie='fcookie='+timezone; 
          });
        </script>
	@yield('after-script')
	@include('includes.partials.ga')
  </body>
</html>