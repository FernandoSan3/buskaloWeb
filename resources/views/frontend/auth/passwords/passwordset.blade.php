@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.passwords.reset_password_box_title'))

@section('content')
    <div class="my-5 py-4">
        <div class="col-sm-6 align-self-center mx-auto">
            <div class="card">
                <div class="card-header">
                    <strong>
                        @lang('labels.frontend.reset.password_set')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ html()->form('POST', route('frontend.auth.password.set.post'))->open() }}
                    <input type="hidden" name="email" value="{{isset($getemail)?$getemail:''}}">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('labels.frontend.reset.password'))->for('email') }}

                                    {{ html()->password('password')
                                        ->class('form-control')
                                        ->placeholder(__('labels.frontend.reset.password_here'))
                                        ->attribute('maxlength', 191)
                                        ->required()
                                        ->autofocus() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit(__('labels.frontend.reset.submit_password_reset_link')) }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-6 -->
    </div><!-- row -->
@endsection
