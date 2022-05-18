<?php


Breadcrumbs::for('admin.subservices.index', function ($trail) {
    $trail->push(__('menus.backend.subservices.management'), route('admin.subservices.index'));
});

Breadcrumbs::for('admin.subservices.create', function ($trail) {
	$trail->parent('admin.subservices.index');
    $trail->push(__('menus.backend.subservices.create'), route('admin.subservices.create'));
});

Breadcrumbs::for('admin.subservices.edit', function ($trail, $id) {
	$trail->parent('admin.subservices.index');
    $trail->push(__('menus.backend.subservices.edit'), route('admin.subservices.edit',$id));
});


