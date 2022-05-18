@extends('backend.layouts.app')

@section('title', __('labels.backend.price_range.management') . ' | ' . __('labels.backend.price_range.create'))

@section('content')
{{ html()->form('POST', route('admin.price_range.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="price_range_id" value="{{$price_range->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.price_range.management') 
                        <small class="text-muted"> @lang('labels.backend.price_range.update')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                   <div class="form-group row">
                       

                        <label class="col-md-2 form-control-label">Start Price</label>

                        <div class="col-md-10">
                            {{ html()->text('start_price')
                                ->class('form-control')
                                ->placeholder('start price')
                                ->value($price_range->start_price)
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                       

                        <label class="col-md-2 form-control-label">End Price</label>

                        <div class="col-md-10">
                            {{ html()->text('end_price')
                                ->class('form-control')
                                ->placeholder('end price')
                                ->value($price_range->end_price)
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Percentage</label>
                        <div class="col-md-10">
                            {{ html()->text('percentage')
                                ->class('form-control')
                                ->placeholder('percentage')
                                ->value($price_range->percentage)
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
                    {{ form_cancel(route('admin.price_range.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}
@endsection
