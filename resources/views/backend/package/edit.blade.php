@extends('backend.layouts.app')

@section('title', __('labels.backend.package.management') . ' | ' . __('labels.backend.package.create'))

@section('content')
{{ html()->form('POST', route('admin.package.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="package_id" value="{{$package->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.package.management') 
                        <small class="text-muted"> @lang('labels.backend.package.create')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.roles.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}
                         <label class="col-md-2 form-control-label">Package Name(En) </label>   

                        <div class="col-md-10">
                            {{ html()->text('en_name')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.package.package_en_name'))
                                ->attribute('maxlength', 191)
                                ->value($package->en_name)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.roles.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Package Name(Es) </label>

                        <div class="col-md-10">
                            {{ html()->text('es_name')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.package.package_es_name'))
                                ->attribute('maxlength', 191)
                                ->value($package->es_name)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.roles.icon'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Price </label>

                        <div class="col-md-10">
                            <input class="form-control" type="number" name="price" id="price" placeholder="Price" maxlength="191" autofocus="" required="" value="{{isset($package->price)?$package->price:''}}" step="any">
                           <!--  {{ html()->number('price')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.package.price'))
                                ->attribute('maxlength', 191)
                                ->autofocus()
                                ->required() 
                                ->value($package->price)}} -->
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.roles.icon'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Credit </label>

                        <div class="col-md-10">
                            {{ html()->number('credit')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.package.credit'))
                                ->attribute('maxlength', 191)
                                ->autofocus()
                                ->required() 
                                ->value($package->credit)}}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.roles.icon'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Discount </label>

                        <div class="col-md-10">
                            {{ html()->number('discount')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.package.discount'))
                                ->attribute('maxlength', 191)
                                ->autofocus()
                                ->required() 
                                ->value($package->discount)}}
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.package.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

@endsection
