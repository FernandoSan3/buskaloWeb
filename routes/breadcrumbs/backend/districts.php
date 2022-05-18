<?php


Breadcrumbs::for('admin.districts.index', function ($trail) {
    $trail->push(__('menus.backend.districts.management'), route('admin.districts.index'));
});

Breadcrumbs::for('admin.districts.create', function ($trail) {
	$trail->parent('admin.districts.index');
    $trail->push(__('menus.backend.districts.create'), route('admin.districts.create'));
});

Breadcrumbs::for('admin.districts.edit', function ($trail, $id) {
	$trail->parent('admin.districts.index');
    $trail->push(__('menus.backend.districts.edit'), route('admin.districts.edit',$id));
});


