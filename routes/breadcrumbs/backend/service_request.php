<?php

Breadcrumbs::for('admin.service_request.index', function ($trail) {
    $trail->push(__('menus.backend.service_request.management'), route('admin.service_request.index'));
});

// Breadcrumbs::for('admin.questions.create', function ($trail) {
// 	$trail->parent('admin.questions.index');
//     $trail->push(__('menus.backend.questions.create'), route('admin.questions.create'));
// });

// Breadcrumbs::for('admin.questions.edit', function ($trail, $id) {
// 	$trail->parent('admin.questions.index');
//     $trail->push(__('menus.backend.questions.edit'), route('admin.questions.edit',$id));
// });

// Breadcrumbs::for('admin.questions.show', function ($trail, $id) {
// 	$trail->parent('admin.questions.index');
//     $trail->push(__('menus.backend.questions.view'), route('admin.questions.show',$id));
// });