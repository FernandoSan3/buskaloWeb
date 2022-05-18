<!DOCTYPE html>
<?php if (\Illuminate\Support\Facades\Blade::check('langrtl')): ?>
    <html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="rtl">
<?php else: ?>
    <html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php endif; ?>
  <?php echo $__env->make('frontend.includes.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php if(auth()->guard()->check()): ?>
		  <?php echo $__env->make('frontend.includes.header_cont', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
		 <?php if(auth()->guard()->guest()): ?>

		<?php echo $__env->make('frontend.includes.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

		 <?php endif; ?>
 
  <body>
	<?php
	$route = Route::current();
	$route_name = Route::currentRouteName();
	?>
   
	<?php if($route_name == 'frontend.index' || $route_name == 'frontend.home_page' ): ?>
		<?php echo $__env->make('frontend.includes.slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<?php else: ?>
		<?php echo $__env->make('frontend.includes.innerslider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>	
	<?php endif; ?>
	<?php if($route_name == 'frontend.service_online'): ?>
		<?php echo $__env->make('frontend.includes.service_slider', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<?php endif; ?>
	<?php echo $__env->make('includes.partials.logged-in-as', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	
	<div class="container1">
		<?php if($route_name != 'frontend.redirect_register'): ?>
			<?php echo $__env->make('includes.partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<?php endif; ?>
		<div id="chat-overlay" class=""></div>
	    <audio id="chat-alert-sound" style="display: none">
	        <source src="<?php echo e(asset('sound/facebook_chat.mp3')); ?>" />
	    </audio>
		
		<?php echo $__env->yieldContent('content'); ?>
	</div><!-- container -->
	
	<?php echo $__env->make('frontend.includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
	<?php echo $__env->yieldContent('after-script'); ?>
	<?php echo $__env->make('includes.partials.ga', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </body>
</html><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/resources/views/frontend/layouts/app.blade.php ENDPATH**/ ?>