@extends('backend.layouts.app')

@section('title', __('labels.backend.newsletter.management') . ' | ' . __('labels.backend.newsletter.create'))

@section('content')
{{ html()->form('POST', route('admin.newsletter.postSendMailall'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.newsletter.management')
                        <small class="text-muted"> @lang('labels.backend.newsletter.create')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>
            <div class="form-group row">

                <div class="col-md-10">
                    <?php
                    $maildata=[];
                    $datamail = '';
                    if(isset($mail_id) && count($mail_id) > 0){

                        $datamail= implode(',', $mail_id);
                    } else{

                    }



                     ?>
                     <input type="hidden" name="tomail" value="<?php echo $datamail; ?>">

                    {{-- {{ html()->textarea('tomail')
                                ->class('form-control')
                                ->placeholder(__('To'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->value($maildata)
                                ->autofocus() }}
     --}}            </div><!--col-->
            </div><!--form-group-->
            <div class="form-group row">

                <label class="col-md-2 form-control-label">Subject </label>

                <div class="col-md-10">
                    {{ html()->text('subject')
                                ->class('form-control')
                                ->placeholder(__('subject'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}

                </div><!--col-->
            </div><!--form-group-->

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">

                         <label class="col-md-2 form-control-label">Message </label>

                        <div class="col-md-10">
                            {{ html()->textarea('message')
                                ->class('form-control')
                                ->placeholder(__('message'))
                                ->attribute('maxlength', 191)
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
                    {{ form_cancel(route('admin.cities.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('labels.backend.newsletter.Send')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}
@endsection
