<?php

Breadcrumbs::for('admin.questions.index', function ($trail) {
    $trail->push(__('menus.backend.questions.management'), route('admin.questions.index'));
});

Breadcrumbs::for('admin.questions.create', function ($trail) {
	$trail->parent('admin.questions.index');
    $trail->push(__('menus.backend.questions.create'), route('admin.questions.create'));
});

Breadcrumbs::for('admin.questions.edit', function ($trail, $id) {
	$trail->parent('admin.questions.index');
    $trail->push(__('menus.backend.questions.edit'), route('admin.questions.edit',$id));
});

Breadcrumbs::for('admin.questions.show', function ($trail, $id) {
	$trail->parent('admin.questions.index');
    $trail->push(__('menus.backend.questions.view'), route('admin.questions.show',$id));
});