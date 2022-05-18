@extends('backend.layouts.app')

{{--@section('title', __('labels.backend.about.management'))--}}
{{--@section('title', app_name() . ' | '. __('labels.backend.about.management'))--}}


@section('title', __('labels.backend.about.management') . ' | ' . __('labels.backend.about.create'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.about.management')
                        <small class="text-muted"> @lang('labels.backend.about.create')</small>
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

  {{ html()->form('POST', route('admin.about_us.updateabout'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<ul class="nav nav-mytabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="Constractor-tab" data-toggle="tab" href="#Constractor" role="tab" aria-controls="Constractor" aria-selected="true"></a>
  </li>
  <!-- <li class="nav-item">
    <a class="nav-link" id="Company-tab" data-toggle="tab" href="#Company" role="tab" aria-controls="Company" aria-selected="false">Company</a>
  </li> -->
</ul>

<div class="tab-content mytab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="Constractor" role="tabpanel" aria-labelledby="Constractor-tab">
     

     <div class="form-group row">
       <label class="col-md-2 form-control-label">Description</label>
       <div class="col-md-10">
         {{ html()->textarea('description_cons')
                ->class('form-control')
                ->value($about->description_cons)
                ->placeholder('Description')
                ->required()
                ->autofocus() }}
       </div><!--col-->
     </div><!--form-group-->

    <div class="card-footer">
      <div class="row">
        <div class="col">
            {{ form_cancel(route('admin.about_us.index'), __('buttons.general.cancel')) }}
        </div><!--col-->


        <div class="col text-right">
            {{ form_submit(__('buttons.general.crud.update')) }}
        </div><!--col-->
      </div><!--row-->
    </div><!--card-footer-->

   </div>

   <div class="tab-pane fade" id="Company" role="tabpanel" aria-labelledby="Company-tab">

    <div class="form-group row">
       <label class="col-md-2 form-control-label">Description</label>
       <div class="col-md-10">
         {{ html()->textarea('description_comp')
                ->class('form-control')
                ->value($about->description_comp)
                ->placeholder('Description')
                ->required()
                ->autofocus() }}
       </div><!--col-->
     </div><!--form-group-->

    <div class="card-footer">
      <div class="row">
        <div class="col">
                {{ form_cancel(route('admin.about_us.index'), __('buttons.general.cancel')) }}
            </div><!--col-->


            <div class="col text-right">
                {{ form_submit(__('buttons.general.crud.update')) }}
            </div><!--col-->
        </div><!--row-->
    </div><!--card-footer-->
  </div>
  <style>
    textarea.form-control {
    height: 17pc;
}
  </style>
<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
  <script>
        CKEDITOR.replace( 'description_cons' );
        CKEDITOR.replace( 'description_comp' );
</script>

@endsection
