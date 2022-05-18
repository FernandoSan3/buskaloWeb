 <?php if($errors->any()): ?>
    <div class="alert alert-danger err_msg_a" role="alert">
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
<?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/resources/views/includes/partials/messages.blade.php ENDPATH**/ ?>