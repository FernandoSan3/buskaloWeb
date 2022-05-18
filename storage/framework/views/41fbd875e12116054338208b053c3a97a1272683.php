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
    
  <?php $__env->startSection('title', app_name() . ' | ' . __('navs.general.home')); ?>



      <header>
        <div class="container">
          <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('img/frontend/logo.svg')); ?>"></a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">
                  <i class="fa fa-bars"></i>
                </span>
              </button>

            <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
               <!--   <li class="nav-item">
                  <a class="nav-link" href="<?php echo e(url('home_page')); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.categories'); ?></a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo e(route('frontend.service_online')); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.online_services'); ?></a>
                </li> -->
               <!--   <?php if(config('locale.status') && count(config('locale.languages')) > 1): ?>
                  <li class="nav-item dropdown">
                      <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLanguageLink" data-toggle="dropdown"
                         aria-haspopup="true" aria-expanded="false"><?php echo e(strtoupper(app()->getLocale())); ?></a>
                  </li>
               <?php endif; ?> -->
                <?php echo $__env->make('includes.partials.lang', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              </ul>

             <!--  <ul class="navbar-nav right-nav  ml-auto">
                 <li class="nav-item">
                    <a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('img/frontend/user-professional.png')); ?>" style="width: 143px;"></a>
                  </li>
              </ul> -->
            </div>
          </nav>
        </div>
      </header>


  <!-- <div class="fst-header">
    <div class="container">
      <div class="inner-fst">
      <div class="row">
        <div class="col-md-6 fst-logo">
          <a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('img/frontend/logo-icon.png')); ?>"></a>
        </div>
        <div class="col-md-6 fst-user">
          <a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('img/frontend/user-professional.png')); ?>"></a>
        </div>
      </div>
    </div>
    </div>
  </div> -->

  <div class="fst-screen">
    <div class="container">
      <div class="first-screen-content">
         <?php echo e(html()->form('POST', route('frontend.reg_type'))->attribute('enctype', 'multipart/form-data')->id('user_selection')->open()); ?>

          <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

        <h2><?php echo app('translator')->get('labels.frontend.index.welcome'); ?></h2>
        <!-- <h3> <?php echo app('translator')->get('labels.frontend.index.you_are_in_the_red'); ?></h3> -->
        <div class="screen-img">
          <img class="img-fluid" src="<?php echo e(url('img/frontend/login-banner.png')); ?>">
         <!--  <h4>Te ayudaremos a que tu negocio crezca. ¡Comencemos!</h4> -->
         <h4><?php echo app('translator')->get('Gracias por tu interés de formar parte de los,<br/> mejores profesionales del país.'); ?></h4>
         <!-- <h4>A un paso de conseguirlo todo</h4> -->
        </div>
        <div class="meta-list reg-option">
         <!--  <p>Empieza tu registro como:</p> -->
         <p><?php echo app('translator')->get('labels.frontend.index.you_want_to_apply_as'); ?></p>
          <label class="cust-radio"><?php echo app('translator')->get('labels.frontend.index.business'); ?>
            <input type="radio" checked="checked" name="user_group_id" value="4">            
            <span class="checkmark"></span>
          </label>

          <label class="cust-radio"><!-- Profesional Independiente --><?php echo app('translator')->get('labels.frontend.index.professional_independent'); ?>
            <input type="radio" name="user_group_id" value="3">
            <span class="checkmark"></span>
          </label>
        </div>
        <br>
        
        <div class="reg-div">
          
          <button class="btn login-btn" type="submit" id="nextScreen1"><?php echo app('translator')->get('labels.frontend.index.continue'); ?></button>

       <!--    <center><p style="font-size: 16px;">Todo lo que registras acepta nuestros terminos condiciones</p></center> -->
        </div>

        </form>
      </div>
    </div>
    
    </div>
  </section>
  

  <?php echo $__env->make('includes.partials.ga', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('frontend.includes.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  </body>
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
  <script type="text/javascript">
  
  $('#nextScreen').click(function(){
      
    var user_group_id = $('input[name=user_group_id]:checked', '#user_selection').val();
    var url_ay = '<?php echo URL::to("redirect_register"); ?>';    
    $.ajax({
      type: "GET",
      url: '<?php echo URL::to("reg_type_new"); ?>',
      data:{user_group_id:user_group_id},
      success: function(data){
        window.location.href =  url_ay;
      }
    });   
      
  })
  
  </script>
</html>


<?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/Buskalo_WEB/resources/views/frontend/first_screen.blade.php ENDPATH**/ ?>