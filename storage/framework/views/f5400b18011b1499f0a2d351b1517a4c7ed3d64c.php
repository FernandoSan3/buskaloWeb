<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownLanguageLink">
    <?php $__currentLoopData = array_keys(config('locale.languages')); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($lang != app()->getLocale()): ?>
            <small><a href="<?php echo e(url('/lang/'.$lang)); ?>" class="dropdown-item pt-1 pb-1"><?php echo app('translator')->get('menus.language-picker.langs.'.$lang); ?></a></small>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/resources/views/includes/partials/lang.blade.php ENDPATH**/ ?>