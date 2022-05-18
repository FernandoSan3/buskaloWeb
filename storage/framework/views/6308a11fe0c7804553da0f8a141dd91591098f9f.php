<?php $__env->startSection('title', app_name() . ' | ' . __('labels.frontend.auth.login_box_title')); ?>

<?php $__env->startSection('content'); ?>

<header>
      <div class="container">
        <nav class="navbar navbar-expand-lg">
          <a class="navbar-brand" href="<?php echo e(url('/')); ?>"><img src="img/frontend/logo.svg"></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
              <i class="fa fa-bars"></i></span>
          </button>

          <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent">
           <!--  <ul class="navbar-nav mr-auto">
               <li class="nav-item">
                <a class="nav-link" href="<?php echo e(url('home_page')); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.categories'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('frontend.service_online')); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.online_services'); ?></a>
              </li>

               <?php if(config('locale.status') && count(config('locale.languages')) > 1): ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLanguageLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><?php echo e(strtoupper(app()->getLocale())); ?></a>

                    <?php echo $__env->make('includes.partials.lang', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </li>
             <?php endif; ?>
            </ul> -->

            <!-- <ul class="navbar-nav right-nav  ml-auto">
               <li class="nav-item">
                  <a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('img/frontend/user-professional.png')); ?>" style="width: 143px;"></a>
                </li>
            </ul> -->
        </div>
    </nav>
  </div>
</header>
 <!-- <div class="business-header">
      <div class="row no-gutters">
        <div class="col-md-4">
          <div class="user-pro-img">
            <img src="<?php echo e(url('img/frontend/user-professional.png')); ?>">
          </div>
        </div>
        <div class="col-md-8">
          <img src="<?php echo e(url('img/frontend/logo.svg')); ?>">
        </div>
      </div>
  </div>  -->


<div class="login-box inner-page">
<div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-img-top">
            <img class="log-img" src="<?php echo e(url('img/frontend/user.svg')); ?>" alt="Card image cap">
              <!-- <h5 class="card-title text-center"> iniciar sesión</h5> -->
              <p style="font-size: 18px;"> ¡<?php echo app('translator')->get('labels.frontend.login.we_are_ready_to_start'); ?>!</p>

          </div>
           
          <div class="card-body">

           <?php echo e(html()->form('POST', route('frontend.auth.login.post'))->class('form-signin')->id('form_id')->open()); ?>


            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="<?php echo e(url('img/frontend/mail.png')); ?>"></span>
                </div>
                    

                    <input type="email" name="email" class="form-control" placeholder="<?php echo app('translator')->get('labels.frontend.login.email'); ?>" autocomplete="off" required="" maxlength="191"></input>
              </div>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="<?php echo e(url('img/frontend/pass.png')); ?>"></span>
                </div>
                    

                      <input type="password" name="password" class="form-control" placeholder="<?php echo app('translator')->get('labels.frontend.login.password'); ?>" autocomplete="off" required=""></input>
                </div>
              

              <div class="custom-control custom-checkbox rem-div">

                <input type="checkbox" value="1" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1"><?php echo app('translator')->get('labels.frontend.login.remember_me'); ?></label>

                <!-- <a class="forgot-cls" href="<?php echo e(route('frontend.auth.password.reset')); ?>">¿Olvidaste tu contraseña?</a> -->
                <a class="forgot-cls" href="<?php echo e(route('frontend.auth.password.reset')); ?>"><?php echo app('translator')->get('labels.frontend.login.i_forgot_my_password'); ?></a>

              </div>

              <!-- <button class="btn login-btn" type="submit">iniciar sesión</button> -->
              <button class="btn login-btn" type="submit"><?php echo app('translator')->get('labels.frontend.login.pay_in'); ?></button>

                <?php if(config('access.captcha.login')): ?>
                    <div class="row">
                        <div class="col">
                            <?php echo app('captcha')->render(); ?>
                            <?php echo e(html()->hidden('captcha_status', 'true')); ?>

                        </div><!--col-->
                    </div><!--row-->
                <?php endif; ?>

              <p class="ins-sec"><?php echo app('translator')->get('labels.frontend.login.dont_have_an_account'); ?>, <!-- <a href="<?php echo e(url('/')); ?>" class="reg-link"> -->
              <a href="#" class="reg-link" data-toggle="modal" data-target="#exampleModal">
                <?php echo app('translator')->get('labels.frontend.login.sign_up'); ?>
                </a></p>
             
            <?php echo e(html()->form()->close()); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> <?php echo app('translator')->get('labels.frontend.login.sign_up'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="meta-list reg-option">
         <?php echo e(html()->form('POST', route('frontend.reg_type'))->attribute('enctype', 'multipart/form-data')->open()); ?>

          <label class="cust-radio">Empresa
            <input type="radio" checked="checked" name="user_group_id" value="4">
            <span class="checkmark"></span>
          </label>

          <label class="cust-radio">Profesional Independiente
            <input type="radio" name="user_group_id" value="3">
            <span class="checkmark"></span>
          </label>
          <label class="cust-radio">Usuarios
            <input type="radio" name="user_group_id" value="2">
            <span class="checkmark"></span>
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('labels.frontend.login.pay_in'); ?></button>
      </div>
       </form>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('after-scripts'); ?>
    <?php if(config('access.captcha.login')): ?>
        <?php echo app('captcha')->renderFooterJS(); ?>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.auth_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/Buskalo_WEB/resources/views/frontend/auth/login.blade.php ENDPATH**/ ?>