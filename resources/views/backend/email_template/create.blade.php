@extends('backend.layouts.app')

@section('title', __('labels.backend.email_template.management') . ' | ' . __('labels.backend.email_template.create'))

@section('content')

{{ html()->form('POST', route('admin.email_template.store'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.email_template.management') 
                        <small class="text-muted"> @lang('labels.backend.email_template.create')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Slug</label>   
                        <div class="col-md-10">
                            {{ html()->text('slug')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.email_template.slug'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Subject</label>   
                        <div class="col-md-10">
                            {{ html()->text('subject')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.email_template.subject'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                     <div class="form-group row">
                        <label class="col-md-2 form-control-label">Mail Content</label>   
                        <div class="col-md-10">
                            {{ html()->textarea('mail_content')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.email_template.mail_template'))
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.email_template.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}
@endsection
