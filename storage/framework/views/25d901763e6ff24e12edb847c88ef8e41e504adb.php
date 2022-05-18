<!DOCTYPE html>
<?php if (\Illuminate\Support\Facades\Blade::check('langrtl')): ?>
    <html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="rtl">
<?php else: ?>
    <html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php endif; ?>
  <?php echo $__env->make('frontend.includes.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  

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
	<?php echo $__env->make('includes.partials.logged-in-as', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	
	<div class="container1">
		<?php if($route_name != 'frontend.redirect_register'): ?>
			<?php echo $__env->make('includes.partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<?php endif; ?>
		
		<?php echo $__env->yieldContent('content'); ?>
	</div><!-- container -->
	
	<?php echo $__env->make('frontend.includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('after-script'); ?>
	<?php echo $__env->make('includes.partials.ga', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </body>
</html><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/resources/views/frontend/layouts/auth_app.blade.php ENDPATH**/ ?>