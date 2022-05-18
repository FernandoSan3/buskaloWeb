<?php $__env->startSection('title', app_name() . ' | ' . __('navs.general.home')); ?>

<?php $__env->startSection('content'); ?>
<section class="works-sec mx mt-4">
  <div class="container">
    <div class="row">
      <div class="col col-sm-9 m-auto align-self-center">
        <div class="faq_ques_section">
          <div class="card">
            <div class="card-header text-center faq_main_head">
              <span>Preguntas frecuentes</span>
            </div>
            <div class="card-body">
              <div class="flex flex-column mb-5 mt-4 faq-section">
                <div class="row">
                  <div class="col-md-12">
                    <div id="accordion">
                        <?php $__currentLoopData = $faqLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faqdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card">
                          <div class="card-header" id="heading-<?php echo e($faqdata->id); ?>">
                            <h5 class="mb-0">
                              <a role="button" data-toggle="collapse" href="#collapse-<?php echo e($faqdata->id); ?>" aria-expanded="true" aria-controls="collapse-<?php echo e($faqdata->id); ?>">
                                 <?php echo e($faqdata->question); ?>

                              </a>
                            </h5>
                          </div>
                          <div id="collapse-<?php echo e($faqdata->id); ?>" class="collapse" data-parent="#accordion" aria-labelledby="heading-<?php echo e($faqdata->id); ?>">
                            <div class="card-body">
                              <?php echo $faqdata->answer; ?>

                            </div>
                          </div>
                        </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<?php $__env->stopSection(); ?>


<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/resources/views/frontend/faq_page.blade.php ENDPATH**/ ?>