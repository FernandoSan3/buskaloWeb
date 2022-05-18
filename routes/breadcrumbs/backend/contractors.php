<?php

Breadcrumbs::for('admin.contractors.index', function ($trail) {
    $trail->push('Contractor Management', route('admin.contractors.index'));
});

Breadcrumbs::for('admin.contractors.create', function ($trail) {
	$trail->parent('admin.contractors.index');
    $trail->push(' Create Contractor', route('admin.contractors.create'));
});


Breadcrumbs::for('admin.contractors.create_worker', function ($trail, $id) {
	$trail->parent('admin.contractors.index');
    $trail->push('Create Worker', route('admin.contractors.create_worker',$id));
});

Breadcrumbs::for('admin.contractors.edit', function ($trail, $id) {
	$trail->parent('admin.contractors.index');
    $trail->push('Update Contractor', route('admin.contractors.edit',$id));
});

Breadcrumbs::for('admin.contractors.add_services_offered', function ($trail, $id) {
	$trail->parent('admin.contractors.index');
    $trail->push('Add Services', route('admin.contractors.add_services_offered',$id));
});

Breadcrumbs::for('admin.contractors.add_contractor_documents', function ($trail, $id) {
	$trail->parent('admin.contractors.index');
    $trail->push('Add Document', route('admin.contractors.add_contractor_documents',$id));
});

Breadcrumbs::for('admin.contractors.all_contractor_documents', function ($trail, $id) {
    $trail->parent('admin.contractors.index');
    $trail->push('Add Documents', route('admin.contractors.all_contractor_documents',$id));
});

Breadcrumbs::for('admin.contractors.all_workers', function ($trail, $id) {
    $trail->parent('admin.contractors.index');
    $trail->push('All Worker', route('admin.contractors.all_workers',$id));
});

Breadcrumbs::for('admin.contractors.view_worker', function ($trail, $id) {
    $trail->parent('admin.contractors.index');
    $trail->push('View Worker', route('admin.contractors.view_worker',$id));
});

Breadcrumbs::for('admin.contractors.edit_worker', function ($trail, $id) {
	$trail->parent('admin.contractors.index');
    $trail->push('Edit Worker', route('admin.contractors.edit_worker',$id));
});

