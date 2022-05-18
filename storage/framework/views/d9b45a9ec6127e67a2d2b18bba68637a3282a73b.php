<?php $__env->startSection('title', app_name() . ' | ' . __('navs.general.home')); ?>

<?php $__env->startSection('content'); ?>

<section class="works-sec serv-online mx term&conditions">
    <div class="container">
        <div class="heading">
            <span class="bottom-border"></span>
        </div>
        <div class="row" style="text-align: justify; font-size: 16px;">
            <div class="col-lg-12">
                <?php if($user_group_id==3): ?>
                <?php echo $term_n_condition->description_cons; ?>


                <?php elseif($user_group_id==4): ?>
                <?php echo $term_n_condition->description_comp; ?>

                <?php else: ?>
                <?php echo $term_n_condition->description_user; ?>

                <?php endif; ?>
                <!-- <div class="btns-div">
                    <?php if($user = Auth::user()): ?>
                    <button class="btn login-btn acceptbtn"> Aceptar </button>
                    <?php else: ?>
                    <button class="btn login-btn acceptbtnwthout"> Aceptar </button>
                    <?php endif; ?>
                </div> -->
            </div>
        </div>
    </div>
</section>
  

<script> 
    $(document).ready(function() { 
        $(".acceptbtn").click(function() { 
            //if($(".term-ckeck").is(':checked')){
                sessionStorage.setItem("tc",1);
                window.location.href = "<?php echo e(url('account')); ?>";
            //} 
            // else{ 
            //     sessionStorage.setItem("tc",0);
            //     alert("Check box is Unchecked"); 
            // } 
        }); 
        
        $(".acceptbtnwthout").click(function() { 
           // if($(".term-ckeck").is(':checked')){
                //const queryString = window.location.search;
                var queryParams = new URLSearchParams(window.location.search);
                queryParams.set("approval_status", "1");
                sessionStorage.setItem("tc",1);
                window.location.href = "<?php echo e(url('register/')); ?>"+"?"+queryParams+'&tc_approved=1';
            // } else { 
            //     sessionStorage.setItem("tc",0);
            //     alert("Check box is Unchecked"); 
            // }
        }); 
    });         
</script>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/resources/views/frontend/contractor/characteristics-conditions.blade.php ENDPATH**/ ?>