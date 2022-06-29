@extends('backend.layouts.app')
{{--@section('title', __('labels.backend.terms_and_condition.management'))--}}
{{--@section('title', app_name() . ' | '. __('labels.backend.terms_and_condition.management'))--}}


@section('title', __('labels.backend.terms_and_condition.management') . ' | ' . __('labels.backend.terms_and_condition.create'))
@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                     @lang('labels.backend.terms_and_condition.management')
                    <small class="text-muted"> @lang('labels.backend.terms_and_condition.create')</small>
                </h4>
            </div><!--col-->
        </div><!--row-->

        <br>
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

        {{ html()->form('POST', route('admin.terms_and_condition.updateTermAndCondition'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

        <ul class="nav nav-mytabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="Constractor-tab" data-toggle="tab" href="#Constractor" role="tab" aria-controls="Constractor" aria-selected="true"> @lang('labels.backend.terms_and_condition.constractor')</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="Company-tab" data-toggle="tab" href="#Company" role="tab" aria-controls="Company" aria-selected="false"> @lang('labels.backend.terms_and_condition.company') </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="User-tab" data-toggle="tab" href="#User" role="tab" aria-controls="User" aria-selected="false"> @lang('labels.backend.terms_and_condition.user') </a>
          </li>
           <li class="nav-item">
            <a class="nav-link" id="Purchase-tab" data-toggle="tab" href="#Purchase" role="tab" aria-controls="Purchase" aria-selected="false"> @lang('labels.backend.terms_and_condition.purchase') </a>
          </li>
        </ul>

        <div class="tab-content mytab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="Constractor" role="tabpanel" aria-labelledby="Constractor-tab">

             <div class="form-group row">
               <label class="col-md-2 form-control-label"> @lang('labels.backend.terms_and_condition.description') </label>
               <div class="col-md-10">
                 {{ html()->textarea('description_cons')
                        ->class('form-control')
                        ->value($terms_and_condition->description_cons)
                        ->placeholder('Description')
                        ->required()
                        ->autofocus() }}
               </div><!--col-->
             </div><!--form-group-->

            <div class="card-footer">
              <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.terms_and_condition.index'), __('buttons.general.cancel')) }}
                </div><!--col-->


                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
              </div><!--row-->
            </div><!--card-footer-->

           </div>

           <div class="tab-pane fade" id="Company" role="tabpanel" aria-labelledby="Company-tab">

            <div class="form-group row">
               <label class="col-md-2 form-control-label"> @lang('labels.backend.terms_and_condition.company_description')</label>
               <div class="col-md-10">
                 {{ html()->textarea('description_comp')
                        ->class('form-control')
                        ->value($terms_and_condition->description_comp)
                        ->placeholder('Description')
                        ->required()
                        ->autofocus() }}
              </div><!--col-->
            </div><!--form-group-->

              <div class="card-footer">
                <div class="row">
                  <div class="col">
                          {{ form_cancel(route('admin.terms_and_condition.index'), __('buttons.general.cancel')) }}
                      </div><!--col-->


                      <div class="col text-right">
                          {{ form_submit(__('buttons.general.crud.update')) }}
                      </div><!--col-->
                  </div><!--row-->
              </div><!--card-footer-->
          </div>
           <div class="tab-pane fade" id="User" role="tabpanel" aria-labelledby="User-tab">

            <div class="form-group row">
               <label class="col-md-2 form-control-label"> @lang('labels.backend.terms_and_condition.user_description') </label>
               <div class="col-md-10">
                 {{ html()->textarea('description_user')
                      ->class('form-control')
                      ->value($terms_and_condition->description_user)
                      ->placeholder('User Description')
                      ->required()
                      ->autofocus() }}
               </div><!--col-->
             </div><!--form-group-->

            <div class="card-footer">
              <div class="row">
                <div class="col">
                        {{ form_cancel(route('admin.terms_and_condition.index'), __('buttons.general.cancel')) }}
                    </div><!--col-->


                    <div class="col text-right">
                        {{ form_submit(__('buttons.general.crud.update')) }}
                    </div><!--col-->
                </div><!--row-->
            </div><!--card-footer-->
          </div>
          <div class="tab-pane fade" id="Purchase" role="tabpanel" aria-labelledby="Purchase-tab">

            <div class="form-group row">
               <label class="col-md-2 form-control-label"> @lang('labels.backend.terms_and_condition.purchase_description') </label>
               <div class="col-md-10">
                 {{ html()->textarea('description_purchase')
                      ->class('form-control')
                      ->value($terms_and_condition->description_purchase)
                      ->placeholder('Purchase Description')
                      ->required()
                      ->autofocus() }}
               </div><!--col-->
             </div><!--form-group-->

            <div class="card-footer">
              <div class="row">
                <div class="col">
                        {{ form_cancel(route('admin.terms_and_condition.index'), __('buttons.general.cancel')) }}
                    </div><!--col-->


                    <div class="col text-right">
                        {{ form_submit(__('buttons.general.crud.update')) }}
                    </div><!--col-->
                </div><!--row-->
            </div><!--card-footer-->
          </div>
        </div>
    </div>
</div>
<html>
        <head>
                <meta charset="utf-8">
                <title>CKEditor</title>
                <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
        </head>
        <body>
                
                <script>
                        CKEDITOR.replace( 'description_cons' );
                        CKEDITOR.replace( 'description_comp' );
                        CKEDITOR.replace( 'description_purchase' );
                        CKEDITOR.replace( 'description_user' );
                </script>
        </body>
</html>

@endsection