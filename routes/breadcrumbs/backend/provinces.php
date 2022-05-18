<?php


Breadcrumbs::for('admin.provinces.index', function ($trail) {
    $trail->push(__('menus.backend.provinces.management'), route('admin.provinces.index'));
});

Breadcrumbs::for('admin.provinces.create', function ($trail) {
	$trail->parent('admin.provinces.index');
    $trail->push(__('menus.backend.provinces.create'), route('admin.provinces.create'));
});

Breadcrumbs::for('admin.provinces.edit', function ($trail, $id) {
	$trail->parent('admin.provinces.index');
    $trail->push(__('menus.backend.provinces.edit'), route('admin.provinces.edit',$id));
});


