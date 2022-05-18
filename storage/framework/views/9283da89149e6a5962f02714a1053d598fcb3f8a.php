<?php $__env->startSection('title', app_name() . ' | ' . __('navs.general.home')); ?>

<?php $__env->startSection('content'); ?>

<section class="works-sec serv-online mx mt-4">
  <div class="container">
      <div class="heading">
        <h2><?php echo app('translator')->get('labels.frontend.how_does_it_work.how_does_it_work'); ?></h2>
        <span class="bottom-border"></span>
      </div>
    <div class="row">
      <?php $__currentLoopData = $how_does_it_work; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $howitiswork): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <!-- <div class="work-inner"> -->
        <div class="col-lg-12">
          <?php echo $howitiswork->search_descriptiom; ?>

        </div>
    </div>
  </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</section>

<!-- <section class="intro-sec mx">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="into-main">
          <h2><?php echo app('translator')->get('labels.frontend.how_does_it_work.tasks'); ?></h2>
          <p><?php echo $howitiswork->description; ?></p>
        <div class="about-btn"><a href="#"><?php echo app('translator')->get('labels.frontend.how_does_it_work.buskalo'); ?></a></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="intro-img">
          <img src="<?php echo e(url('/img/frontend/work/'.$howitiswork->image)); ?>">
        </div>
      </div>
    </div>
  </div>
</section> -->

<!-- <section class="profession-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="prof-left">
          <img src="<?php echo e(url('img/frontend/Pro.png')); ?>">
        </div>
      </div>
      <div class="col-md-6">
        <div class="prof-right">
          <h2>¿<?php echo app('translator')->get('labels.frontend.how_does_it_work.professional'); ?></p>
        <a href="<?php echo e(url('/')); ?>"><button class="btn prof-btn"><?php echo app('translator')->get('labels.frontend.how_does_it_work.join_now'); ?></button></a>
        </div>
      </div>
    </div>
  </div>
</section> -->

<?php $__env->stopSection(); ?>



<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/resources/views/frontend/how_does_it_work.blade.php ENDPATH**/ ?>