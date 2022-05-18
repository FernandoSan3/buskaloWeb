<?php $__env->startSection('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title')); ?>

<?php $__env->startSection('content'); ?>

 <?php if($errors->any()): ?>
    <div class="alert alert-danger err_msg_a" role="alert" style="top: 0;">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $error; ?><br/>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php elseif(session()->get('flash_success')): ?>
    <div class="alert alert-success err_msg_a" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <?php if(is_array(json_decode(session()->get('flash_success'), true))): ?>
            <?php echo implode('', session()->get('flash_success')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo session()->get('flash_success'); ?>

        <?php endif; ?>
    </div>
<?php elseif(session()->get('flash_warning')): ?>
    <div class="alert alert-warning" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <?php if(is_array(json_decode(session()->get('flash_warning'), true))): ?>
            <?php echo implode('', session()->get('flash_warning')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo session()->get('flash_warning'); ?>

        <?php endif; ?>
    </div>
<?php elseif(session()->get('flash_info')): ?>
    <div class="alert alert-info err_msg_a" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <?php if(is_array(json_decode(session()->get('flash_info'), true))): ?>
            <?php echo implode('', session()->get('flash_info')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo session()->get('flash_info'); ?>

        <?php endif; ?>
    </div>
<?php elseif(session()->get('flash_danger')): ?>
    <div class="alert alert-danger err_msg_a" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <?php if(is_array(json_decode(session()->get('flash_danger'), true))): ?>
            <?php echo implode('', session()->get('flash_danger')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo session()->get('flash_danger'); ?>

        <?php endif; ?>
    </div>
<?php elseif(session()->get('flash_message')): ?>
    <div class="alert alert-info err_msg_a" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>

        <?php if(is_array(json_decode(session()->get('flash_message'), true))): ?>
            <?php echo implode('', session()->get('flash_message')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo session()->get('flash_message'); ?>

        <?php endif; ?>
    </div>
<?php endif; ?>

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
<!-- 
            <ul class="navbar-nav right-nav ml-auto">
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
      <div class="col-md-3">
        <div class="user-pro-img">
          <img src="<?php echo e(url('img/frontend/user-professional.png')); ?>">
        </div>
      </div>
      <div class="col-md-4">
        <div class="logo-img">
          <img src="<?php echo e(url('img/frontend/logo.svg')); ?>">
        </div>
      </div>
      <div class="col-md-5 curve-img">
        <img class="img-fluid" src="<?php echo e(url('img/frontend/bg-curve.png')); ?>">
      </div>
    </div>
  </div>
 -->
<div class="login-box inner-page">
<div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-img-top">
            <img class="log-img" src="<?php echo e(url('img/frontend/review-add.svg')); ?>" alt="Card image cap">
          
            <?php if($user_group_id == 3): ?>
              <h5 class="card-title text-center"><?php echo app('translator')->get('labels.frontend.register.registry_constractor'); ?></h5>
            <?php elseif($user_group_id == 4): ?>
             <h5 class="card-title text-center"><?php echo app('translator')->get('labels.frontend.register.registry_company'); ?></h5>
             <?php else: ?>
                <h5 class="card-title text-center"><?php echo app('translator')->get('labels.frontend.register.registry_user'); ?></h5>
            <?php endif; ?>
            
          </div>
           
          <div class="card-body">
          <?php echo e(html()->form('POST', route('frontend.auth.register.post'))->class('form-signin form-register')->id('register_form')->open()); ?>


              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="<?php echo e(url('img/frontend/name.png')); ?>"></span>
                </div>
                    <?php echo e(html()->text('username')
                    ->class('form-control')
                    ->placeholder(__('labels.frontend.register.username'))
                    ->attribute('maxlength', 191)
                    ->required()); ?>

              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="<?php echo e(url('img/frontend/phone.png')); ?>"></span>
                </div>
               <!--  <input type="text" id="text1" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" name="mobile_number" maxlength="10" class="form-control" /> -->
            <?php echo e(html()->text('mobile_number')
                ->class('form-control')
                ->id('mobile_phone_number')
                ->placeholder(__('labels.frontend.register.mobile_phone_number'))
                ->attribute('maxlength', 10)
                ->required()); ?>

               <!--  <span id="error" style="color: Red; display: none">* Input digits (0 - 9)</span> -->
                <div class="mobile-number-msg" style="text-align: center;"></div>
              </div>

              
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="<?php echo e(url('img/frontend/mail.png')); ?>"></span>
                </div>
                <?php echo e(html()->email('email')
                ->class('form-control')
                ->placeholder(__('labels.frontend.register.email'))
                ->attribute('maxlength', 191)
                ->id('email6')
                ->required()); ?>

                <br/>
                 <div class="email-msg" style="text-align: center;"></div>
              </div>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="<?php echo e(url('img/frontend/pass.png')); ?>"></span>
                </div>
                <?php echo e(html()->password('password')
                ->class('form-control')
                ->placeholder(__('labels.frontend.register.password'))
                ->required()); ?>

              </div>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><img src="<?php echo e(url('img/frontend/pass.png')); ?>"></span>
                </div>
                <?php echo e(html()->password('password_confirmation')
                ->class('form-control')
                ->placeholder(__('labels.frontend.register.confirm_password'))
                ->required()); ?>

              </div>

              
               <input type="hidden" name="approval_status" value="<?php echo e(isset($request_data['approval_status']) ?$request_data['approval_status'] : 0); ?>">

                <input type="hidden" name="user_group_id" value="<?php echo e(isset($user_group_id) && !empty($user_group_id) ? $user_group_id : '2'); ?>"> 

               <input type="checkbox" name="privacy_policy" required="" id="policy" <?php if( isset($request_data['approval_status']) && $request_data['approval_status'] == 1 ){ echo 'checked="checked"'; }?>>
              
            <?php if($user_group_id==3): ?>
               <a href="<?php echo e(url('characteristics-conditions')); ?>" target="_blank">  <?php echo app('translator')->get('labels.frontend.register.privacy_policy'); ?> </a>
            <?php else: ?>
            <a href="<?php echo e(url('characteristics-conditions')); ?>" target="_blank">    <?php echo app('translator')->get('labels.frontend.register.privacy_policy'); ?> </a>
            <?php endif; ?>

                <!-- <?php $disabled = 'disabled="disabled"' ?>
                <?php if(isset($request_data['tc_approved']) && $request_data['tc_approved'] == 1): ?>
                    <?php $disabled = '' ?>
                <?php endif; ?> -->

               <div class="reg-div">
                <button class="btn login-btn" type="submit"><?php echo app('translator')->get('labels.frontend.register.check_in'); ?></button>
              </div>

              <div class="social-reg">
                <a href="<?php echo e(url('login/facebook')); ?>"><img src="<?php echo e(url('img/frontend/fb.png')); ?>"></a>
                <a href="<?php echo e(url('login/google')); ?>"><img src="<?php echo e(url('img/frontend/google.png')); ?>"></a>
              </div>
             
            <!--  <p class="ins-sec"><?php echo app('translator')->get('labels.frontend.register.terms_and_conditions'); ?></p> -->
             
            <?php echo e(html()->form()->close()); ?>

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <script>
        <?php //echo $request_data['tc_approved'];
        if(isset($request_data['tc_approved']) && $request_data['tc_approved'] == 1)
        {
            ?>
            $(document).ready(function(){
                if($("#policy").prop("checked")){
                    var s = "<?php echo e($request_data['tc_approved']); ?>";
                    if(s==1){                
                        $('#register-btn').prop('disabled', false);
                    }
                    else{
                        // alert("Please accept our T&C"); 
                        $('#register-btn').prop('disabled', true);
                    }
                }
                else{
                    $('#register-btn').prop('disabled', true);
                }
            });
            <?php
        }
        ?>

        // $(document).ready(function(){
        //     $('#policy').click(function(){
        //         // console.log($(this).prop("checked"));
        //         if($(this).prop("checked")){
        //             var s = "<?php echo e(isset($request_data['tc_approved']) ? $request_data['tc_approved'] : 0); ?>";
        //             if(s==1){                
        //                 $('#register-btn').prop('disabled', false);
        //             }
        //             else{
        //                 alert("Acepte nuestros T&C pasando por el enlace y acepte los términos y condiciones"); 
        //                 $('#register-btn').prop('disabled', true);
        //             }
        //         }
        //         else{
        //             $('#register-btn').prop('disabled', true);
        //         }
        //     });
        // });
    </script>
  <script>
  $(".accpet_tc").on('click', function(){
   
      var form_data = $("#register_form").serialize();

      var url = "<?php echo e(url('/characteristics-conditions')); ?>";
      window.location.href = url;
  });
</script>

<?php $__env->stopSection(); ?>
<style type="text/css">
  .err_msg_a{
    top: 50px;
  }
</style>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>

 <!-- <script> 
        $(window).ready(function() {
        $("#register_form").on("keypress", function (event) {
            console.log("aaya");
            var keyPressed = event.keyCode || event.which;
            if (keyPressed === 13) {
                alert("You pressed the Enter key!!");
                event.preventDefault();
                return false;
            }
        });
        });
  
    </script>
 -->
    <script type="text/javascript">
        var specialKeys = new Array();
        specialKeys.push(8); //Backspace
        function IsNumeric(e) {
            var keyCode = e.which ? e.which : e.keyCode
            var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
            document.getElementById("error").style.display = ret ? "none" : "inline";
            return ret;
        }
    </script>

<?php $__env->startPush('after-scripts'); ?>
    <?php if(config('access.captcha.registration')): ?>
        <?php echo app('captcha')->renderFooterJS(); ?>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.auth_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/Buskalo_WEB/resources/views/frontend/auth/register.blade.php ENDPATH**/ ?>