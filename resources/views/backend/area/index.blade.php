@extends('backend.layouts.app')

{{--@section('title', __('labels.backend.sitesetting.management'))--}}
{{--@section('title', app_name() . ' | '. __('labels.backend.sitesetting.management'))--}}


@section('title', __('labels.backend.area_management.management') . ' | ' . __('labels.backend.area_management.create'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.area_management.management')
                        <small class="text-muted"> @lang('labels.backend.area_management.create')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

        <hr>
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                  {{-- @lang(labels.backend.Site.setting) --}}
                </h4>
            </div><!--col-->

        </div><!--row-->


<div class="tab-content mytab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

   {{ html()->form('POST', route('admin.area.update_areatype_percent'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
           @foreach ($area_details as $key=>  $value)

              <div class="form-group row">
                   <label class="col-md-2 form-control-label">Low Resources Area 1
              </label>

            <div class="col-md-10">
                {{ html()->number('low_resources_area_1')
                    ->class('form-control')
                    ->value($value->low_resources_area_1)
                    ->placeholder('low_resources_area_1')
                    ->attribute('maxlength', 191)
                    ->required()
                    ->autofocus()
                }}
             </div><!--col-->
        </div><!--form-group-->

          <div class="form-group row">
             <label class="col-md-2 form-control-label">Low Resources Area 2
              </label>

            <div class="col-md-10">
                {{ html()->number('low_resources_area_2')
                    ->class('form-control')
                    ->value($value->low_resources_area_2)
                    ->placeholder('low_resources_area_2')
                    ->attribute('maxlength', 191)
                    ->required()
                    ->autofocus()
                }}
            </div><!--col-->


        </div><!--fo-->
           <div class="form-group row">
              <label class="col-md-2 form-control-label">Avg Resource Type
              </label>

                 <div class="col-md-10">

                {{ html()->number('avg_resources_type')
                    ->class('form-control')
                    ->value($value->avg_resources_type)
                    ->placeholder($value->avg_resources_type)
                    ->attribute('maxlength', 191)
                    ->required()
                    ->autofocus()
                }}
             </div><!--col-->
         </div><!--form-group-->

        <div class="form-group row">
            <label class="col-md-2 form-control-label">High Resource Type 1
              </label>

           <div class="col-md-10">

                {{ html()->number('high_resources_type_1')
                    ->class('form-control')
                    ->value($value->high_resources_type_1)
                    ->placeholder($value->avg_resources_type)
                    ->attribute('maxlength', 191)
                    ->required()
                    ->autofocus()
                }}
          </div><!--col-->
      </div><!--form-group-->

         <div class="form-group row">
             <label class="col-md-2 form-control-label">High Resource Type 2
              </label>

           <div class="col-md-10">
                {{ html()->number('high_resources_type_2')
                    ->class('form-control')
                    ->value($value->high_resources_type_2)
                    ->placeholder($value->avg_resources_type)
                    ->attribute('maxlength', 191)
                    ->required()
                    ->autofocus()
                }}

            </div><!--col-->
       </div><!--form-group-->
      <input type="hidden" name="area_type_id" value="<?php echo $value->id;?>">

      @endforeach
          <div class="card-footer">
            <div class="row">
               {{--  <div class="col">
                    {{ form_cancel(route('admin.area.index'), __('buttons.general.cancel')) }}
                </div> --}}

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
         {{ html()->form()->close() }}

   </div>


  </div>
</div><!--row-->

    </div><!--card-body-->
</div><!--card-->
@endsection
