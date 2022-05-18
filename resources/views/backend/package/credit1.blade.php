@extends('backend.layouts.app')

@section('title', __('Package Credit') . ' | ' . __('Package Credit'))

@section('content')
{{ html()->form('POST', route('admin.company.creditpackage.store'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('Package Credit') 
                        <small class="text-muted"> @lang('Package Credit')</small>
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
                            <input type="hidden" name="user_id" value="{{$id}}">
                        <label class="col-md-2 form-control-label">Transaction ID </label>
                        <div class="col-md-10">
                            {{ html()->text('trans_id')
                                ->class('form-control')
                                ->placeholder(__('Transaction ID'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.package.icon'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Package Name </label>

                        <div class="col-md-10">
                            <select name="package_id" class="form-control packageprice" required="">
                                    <option value="">Select Package</option>
                                @foreach($packages as $package)
                                    <option value="{{$package->id}}">{{$package->es_name}}</option>
                                @endforeach
                            </select>
                        </div><!--col-->
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.package.icon'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Price </label>

                        <div class="col-md-10">
                            {{ html()->text('price')
                                ->class('form-control pprice')
                                ->placeholder(__('validation.attributes.backend.access.package.price'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->readonly()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.company.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
    $('.packageprice').on('change', function()
    {
        var id=$(this).val();
        if(id=='')
        {
            alert('Plaese Select package');
            $('.pprice').val('');
        }
        else
        {
            $.ajax({
                url:"{{url('admin/creditpackage')}}",
                type:'get',
                data:{'id':id},
                success:function(res)
                {
                    $('.pprice').val(res);
                }
            })
        }
    });
</script>
@endsection
