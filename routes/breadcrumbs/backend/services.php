<?php


Breadcrumbs::for('admin.services.index', function ($trail) {
    $trail->push(__('menus.backend.services.management'), route('admin.services.index'));
});

Breadcrumbs::for('admin.services.create', function ($trail) {
	$trail->parent('admin.services.index');
    $trail->push(__('menus.backend.services.create'), route('admin.services.create'));
});

Breadcrumbs::for('admin.services.edit', function ($trail, $id) {
	$trail->parent('admin.services.index');
    $trail->push(__('menus.backend.services.edit'), route('admin.services.edit',$id));
});


