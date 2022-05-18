@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.passwords.reset_password_box_title'))

@section('content')
    <div class="row justify-content-center">
        <div class="col col-sm-8 align-self-center">
            <div class="card">
                <div class="card-header">
                    <strong>
                        @lang('labels.frontend.passwords.reset_password_box_title')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">
                    {{ html()->form('POST', route('frontend.forgot_password.reset'))->class('form-horizontal')->open() }}

        
                        {{ html()->hidden('token', $token) }}
                        {{ html()->hidden('emailId', $emailId) }}


                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.password'))->for('password') }}

                                    {{ html()->password('password')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.frontend.password'))
                                    ->required()
                                    ->autofocus() }}
                    

                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">

                                     {{ html()->label(__('validation.attributes.frontend.password_confirmation'))->for('password_confirmation') }}
                                    
                                    {{ html()->password('password_confirmation')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.frontend.password_confirmation'))
                                    ->required()
                                    ->autofocus() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                      
                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit(__('labels.frontend.contact.button')) }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}
                </div><!--card-body-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->
@endsection
<style type="text/css">

.alert-danger {
    z-index: 100;
}
</style>
@push('after-scripts')

@endpush