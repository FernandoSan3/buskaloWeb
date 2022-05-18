<?php $__env->startSection('title', app_name() . ' | ' . __('navs.general.home')); ?>

<?php $__env->startSection('content'); ?>

<div class="inner-slider" >
  <div class="container">
    <h1><?php echo app('translator')->get('labels.frontend.contact.box_title'); ?></h1>
  </div>
</div>

<!-- <div class="contact-sec mx">
  <div class="container">
    <div class="row">
      <div class="col-md-5">
        <div class="contact-detail">
          <h3><?php echo app('translator')->get('labels.frontend.contact.get_in_touch'); ?></h3>

          <ul class="add-list">
            <li><h5><?php echo app('translator')->get('labels.frontend.contact.office'); ?> 1</h5></li>
            <li><i class="fa fa-map-marker"></i><?php echo e(isset($cotactTouch->company_address)?$cotactTouch->company_address:'234, lorem , street 2, USA'); ?> </li>
            <li><i class="fa fa-phone"></i><?php echo e(isset($cotactTouch->company_contact)?$cotactTouch->company_contact:'+1 6478 76657'); ?> </li>
            <li><i class="fa fa-envelope"></i><?php echo e(isset($cotactTouch->company_email)?$cotactTouch->company_email:'buskalo@gmail.com'); ?> </li>
          </ul>


          <ul class="add-list">
            <li><h5><?php echo app('translator')->get('labels.frontend.contact.office'); ?> 2</h5></li>
            <li><i class="fa fa-map-marker"></i> 234, lorem , street 2, USA</li>
            <li><i class="fa fa-phone"></i> +1 6478 76657</li>
            <li><i class="fa fa-envelope"></i> buskalo@gmail.com</li>
          </ul>
        </div>
      </div>
      <div class="col-md-7">
        <div class="contact-map">
           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3558.9484612761516!2d75.7793009!3d26.873378499999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db44a51423625%3A0x6dc1c1e81f96c712!2sWDP%20Infosolutions%20Pvt.%20Ltd.!5e0!3m2!1sen!2sin!4v1589797183558!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
      </div>
    </div>
  </div>
</div> -->

<div class="contact-form-sec mx">
  <div class="container">
    <div class="contact-from">
      <div class="contact_logo">
        <img src="<?php echo e(url('/img/frontend/logo.svg')); ?>">
      </div>
      <h3><?php echo app('translator')->get('labels.frontend.contact.contact_form'); ?></h3>

       <?php echo e(html()->form('POST', route('frontend.contact.send'))->open()); ?>

        <div class="form-group row">
          <label class="col-sm-4 col-form-label"><?php echo app('translator')->get('validation.attributes.frontend.contact_name'); ?>:</label>
          <div class="col-sm-8">
            <?php echo e(html()->text('name',null)
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.contact_name'))
                ->attribute('maxlength', 191)
                ->required()
                ->autofocus()); ?>

          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label"><?php echo app('translator')->get('validation.attributes.frontend.contact_email'); ?>:</label>
          <div class="col-sm-8">
            <?php echo e(html()->email('email',null)
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.contact_email'))
                ->attribute('maxlength', 191)
                ->required()); ?>

          </div>
        </div>
        <!-- <div class="form-group row">
          <label class="col-sm-4 col-form-label">Telèfono:</label>
          <div class="col-sm-8">
            <?php echo e(html()->text('phone')
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.phone'))
                ->attribute('maxlength', 191)
                ->required()); ?>

          </div>
        </div> -->
        <div class="form-group row">
          <label class="col-sm-4 col-form-label"><?php echo app('translator')->get('labels.frontend.contact.User_professional'); ?>:</label>
          <div class="col-sm-8">
            <select class="form-control" name="user_type" required="">
                <option value="">Selecciona</option>
                <option value="User">User</option>
                <option value="Professional">Professional</option>
              
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label">Asunto:</label>
          <div class="col-sm-8">
            <?php echo e(html()->text('address', null)
            ->class('form-control')
            ->placeholder(__('validation.attributes.frontend.address'))
            ->attribute('maxlength', 200)
            ->required()); ?>

          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label"><?php echo app('translator')->get('validation.attributes.frontend.contact_message'); ?>:</label>
          <div class="col-sm-8">
            <?php echo e(html()->textarea('message')
              ->class('form-control')
              ->placeholder(__('validation.attributes.frontend.contact_message'))
              ->attribute('rows', 3)
              ->required()); ?>

          </div>
        </div>

        <div class="form-check">
          <input class="form-check-input" name="subscribe" type="checkbox" value="1" id="defaultCheck1">
          <label class="form-check-label" for="defaultCheck1">
            Suscríbete a nuestro boletín de noticias y ofertas
          </label>
        </div>
        <div class="form-group row">
          <label class="col-sm-4 col-form-label"><?php echo app('translator')->get('Recaptcha'); ?>:</label>

          <div class="col-sm-8">
            <?php if(config('access.captcha.contact')): ?>
            <div class="form-group">
              <div class="col-md-12">
                <div class="g-recaptcha" data-sitekey="<?php echo e(env('GOOGLE_RECAPTCHA_KEY')); ?>">
                </div>
              </div><!--col-md-6-->
            </div><!--form-group-->
            <?php endif; ?>
          </div>
        </div>

        

        <div class="form-group row">
          <label class="col-sm-4 col-form-label"></label>
           <button type="submit" class="btn btn-envi">Enviar</button>
         </div>


        
        
        <!-- <div class="form-row">
           <div class="form-group col-md-6">

            <?php echo e(html()->text('name',null)
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.name'))
                ->attribute('maxlength', 191)
                ->required()
                ->autofocus()); ?>

          </div>

        <div class="form-group col-md-6">
          <?php echo e(html()->email('email',null)
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.email'))
                ->attribute('maxlength', 191)
                ->required()); ?>

          </div>
         <div class="form-group col-md-6">
             <?php echo e(html()->text('phone')
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.phone'))
                ->attribute('maxlength', 191)
                ->required()); ?>

        </div>
        <div class="form-group col-md-6">
          <?php echo e(html()->text('address')
            ->class('form-control')
            ->placeholder(__('validation.attributes.frontend.address'))
            ->attribute('maxlength', 200)
            ->required()); ?>

        </div>

        <div class="form-group col-md-12">
            <?php echo e(html()->textarea('message')
              ->class('form-control')
              ->placeholder(__('validation.attributes.frontend.message'))
              ->attribute('rows', 3)
              ->required()); ?>

        </div>
         <div class="form-group col-md-12">
           <button type="submit" class="btn btn-send"><?php echo app('translator')->get('labels.frontend.contact.send'); ?> </button>
         </div>
      </div> -->
        <?php echo e(html()->form()->close()); ?>


       <?php echo e(html()->form('POST', route('frontend.newsletter.subscribe'))->open()); ?>


      <!-- <div class="form-row">

         <div class="form-group col-md-4">

            <?php echo e(html()->text('email', null)
                ->class('form-control')
                ->placeholder(__('validation.attributes.frontend.email'))
                ->attribute('maxlength', 191)
                ->required()
                ->autofocus()); ?>


          </div>
          <div class="form-group col-md-4">
            <select name="user_type" class="form-control" required="">
                <option value="">Select subscribers type</option>
                <option value="User">User</option>
                <option value="Pro">Contractors/Professional</option>
            </select>

          </div>

          <div class="form-group col-md-2" style="margin-top: -17px;">
           <button type="submit" class="btn btn-send"><?php echo app('translator')->get('labels.frontend.contact.subscribe'); ?> </button>
          </div>
      </div> -->

 <?php echo e(html()->form()->close()); ?>


       </div>

    </div>
  </div>
</div>
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/resources/views/frontend/contact-us.blade.php ENDPATH**/ ?>