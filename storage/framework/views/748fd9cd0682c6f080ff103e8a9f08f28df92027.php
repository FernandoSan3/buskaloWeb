<?php $__env->startSection('title', app_name() . ' | ' . __('navs.general.home')); ?>

<?php $__env->startSection('content'); ?>

<section class="works-sec serv-online mx">
    <div class="container">
        <div class="heading">
        
            <h2><b><?php echo app('translator')->get('labels.frontend.work_with_us.work_with_us'); ?></b></h2>
            <!-- <p> ¡Simple! son servicios online que ofreces profesionales para ti.</p> -->
            <span class="bottom-border"></span>
        </div>
        <div class="row">
            <div class="col-lg-12">
              <?php
              if(!empty($work_with_us)) {
                foreach ($work_with_us as  $value) {
                  
                  $constractor_description = $value->description_cons;
                  $company_description = $value->description_comp;
                }
              }else{?>
                  <p><?php echo app('translator')->get('labels.frontend.work_with_us.cant_find_job'); ?></p>
              <?php }?>
      
                <?php if($user_group_id==3): ?>
                    <?php echo isset($constractor_description)?$constractor_description:''; ?>

                   
                <?php elseif($user_group_id==4): ?>
                    <?php echo isset($company_description)?$company_description:''; ?>

                     
                <?php else: ?>
                      <?php echo isset($constractor_description)?$constractor_description:''; ?>

                   
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>   

<?php $__env->stopSection(); ?>




<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/Buskalo_WEB/resources/views/frontend/work_with_us.blade.php ENDPATH**/ ?>