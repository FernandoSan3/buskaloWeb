<?php


Breadcrumbs::for('admin.cities.index', function ($trail) {
    $trail->push(__('menus.backend.cities.management'), route('admin.cities.index'));
});

Breadcrumbs::for('admin.cities.create', function ($trail) {
	$trail->parent('admin.cities.index');
    $trail->push(__('menus.backend.cities.create'), route('admin.cities.create'));
});

Breadcrumbs::for('admin.cities.edit', function ($trail, $id) {
	$trail->parent('admin.cities.index');
    $trail->push(__('menus.backend.cities.edit'), route('admin.cities.edit',$id));
});


