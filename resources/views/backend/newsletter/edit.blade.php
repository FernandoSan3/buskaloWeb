@extends('backend.layouts.app')

@section('title', __('labels.backend.cities.management') . ' | ' . __('labels.backend.cities.create'))

@section('content')
{{ html()->form('POST', route('admin.sitesetting.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="sitesetting_id" value="{{$sitesetting->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.cities.management')
                        <small class="text-muted"> @lang('labels.backend.cities.update')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="form-group row">

                <label class="col-md-2 form-control-label">Province </label>

                <div class="col-md-10">

                     <select name="province_id" disabled="" class="form-control">
                        @if($provinces)
                        @foreach($provinces as $key => $province)
                        <option <?php if(isset($city->province_id) && $city->province_id == $province->id) { ?> selected <?php } ?> value="{{$province->id}}">{{$province->name}}</option>
                        @endforeach
                        @endif
                     </select>
                </div><!--col-->
            </div><!--form-group-->

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                         <label class="col-md-2 form-control-label">City Name </label>

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.roles.name'))
                                ->attribute('maxlength', 191)
                                ->value($city->name)
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
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}
@endsection
