<?php

Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard'));
});

require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';
require __DIR__.'/services.php';
require __DIR__.'/subservices.php';
require __DIR__.'/questions.php';
require __DIR__.'/contractors.php';
require __DIR__.'/cities.php';
require __DIR__.'/districts.php';

