@extends('backend.layouts.app')

{{--@section('title', __('labels.backend.sitesetting.management'))--}}
{{--@section('title', app_name() . ' | '. __('labels.backend.sitesetting.management'))--}}


@section('title', __('labels.backend.sitesetting.management') . ' | ' . __('labels.backend.sitesetting.create'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.sitesetting.management')
                        <small class="text-muted"> @lang('labels.backend.sitesetting.create')</small>
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

  {{ html()->form('POST', route('admin.site_setting.updatesitesetting'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<ul class="nav nav-mytabs" id="myTab" role="tablist">
  <!--  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">GET IN TOUCH</a>
  </li> -->
  <li class="nav-item">
    <a class="nav-link active" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="history" aria-selected="false">Social</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="city-attractions-tab" data-toggle="tab" href="#city-attractions" role="tab" aria-controls="city-attractions" aria-selected="false">Other</a>
  </li>
   <li class="nav-item">
    <a class="nav-link" id="city-attractions-tab" data-toggle="tab" href="#freecredit" role="tab" aria-controls="city-attractions" aria-selected="false">Free Credits</a>
  </li>
</ul>

<div class="tab-content mytab-content" id="myTabContent">
  <div class="tab-pane fade  " id="home" role="tabpanel" aria-labelledby="home-tab">

     <div class="form-group row">
       <label class="col-md-2 form-control-label">Address</label>
       <div class="col-md-10">
          {{ html()->text('address')
            ->class('form-control')
            ->value($sitesetting->company_address)
            ->placeholder('Address')
            ->attribute('maxlength', 191)
            ->required()
            ->autofocus() }}
       </div><!--col-->
     </div><!--form-group-->
     <div class="form-group row">
       <label class="col-md-2 form-control-label">Email</label>
       <div class="col-md-10">
         {{ html()->email('email')
                ->class('form-control')
                ->value($sitesetting->company_email)
                ->placeholder('Email')
                ->attribute('maxlength', 191)
                ->required()
                ->autofocus() }}
       </div><!--col-->
     </div><!--form-group-->

    <div class="form-group row">
      <label class="col-md-2 form-control-label">Contact No.</label>
      <div class="col-md-10">
          {{ html()->text('contact')
              ->class('form-control')
              ->value($sitesetting->company_contact)
              ->placeholder('Contact')
              ->attribute('maxlength', 191)
              ->required()
              ->autofocus() }}
      </div><!--col-->
    </div><!--form-group-->

   <!--  <div class="form-group row">
      <label class="col-md-2 form-control-label">Copyright Text</label>
      <div class="col-md-10">
            {{ html()->textarea('copyrighttext')
             ->class('form-control')
             ->value($sitesetting->copyright_text)
             ->placeholder('Copyright Text')
             ->attribute('maxlength', 191)
             ->required()
             ->autofocus() }}
      </div>
    </div>

    <div class="form-group row">
     <label class="col-md-2 form-control-label">Footer Text </label>
     <div class="col-md-10">
         {{ html()->textarea('footertext')
            ->class('form-control')
            ->value($sitesetting->footer_text)
            ->placeholder('Footer Text')
            ->attribute('maxlength', 191)
            ->required()
            ->autofocus() }}
         </div>
    </div> -->

    <div class="card-footer">
      <div class="row">
        <div class="col">
            {{ form_cancel(route('admin.site_setting.index'), __('buttons.general.cancel')) }}
        </div><!--col-->


        <div class="col text-right">
            {{ form_submit(__('buttons.general.crud.update')) }}
        </div><!--col-->
      </div><!--row-->
    </div><!--card-footer-->

   </div>

  <div class="tab-pane fade show active" id="history" role="tabpanel" aria-labelledby="history-tab">

 <div class="form-group row">
    <label class="col-md-2 form-control-label">Facebook URL</label>
       <div class="col-md-10">
            {{ html()->text('facebookurl')
                ->class('form-control')
                ->value($sitesetting->facebook)
                ->placeholder('Facebook URL')
                ->attribute('maxlength', 191)
               
                ->autofocus() }}
            </div><!--col-->
        </div><!--form-group-->

 <div class="form-group row">
    <label class="col-md-2 form-control-label">Linkedin URL</label>
        <div class="col-md-10">
            {{ html()->text('linkedinurl')
                ->value($sitesetting->linkedin)
                ->class('form-control')
                ->placeholder('Linkedin URL')
                ->attribute('maxlength', 191)
                
                ->autofocus() }}
          </div><!--col-->
  </div><!--form-group-->

<div class="form-group row">
   <label class="col-md-2 form-control-label">Twitter URL</label>
       <div class="col-md-10">
            {{ html()->text('twitterurl')
                ->value($sitesetting->twitter)
                 ->class('form-control')
                ->placeholder('Twitter URL')
                ->attribute('maxlength', 191)
                
                ->autofocus() }}
        </div><!--col-->
  </div><!--form-group-->

  <div class="form-group row">
   <label class="col-md-2 form-control-label">Instagram URL</label>
       <div class="col-md-10">
            {{ html()->text('instagram')
                ->value($sitesetting->instagram)
                 ->class('form-control')
                ->placeholder('Instagram URL')
                ->attribute('maxlength', 191)
                ->autofocus() }}
        </div><!--col-->
  </div><!--form-group-->

  <div class="form-group row">
      <label class="col-md-2 form-control-label">Google URL </label>
         <div class="col-md-10">
            {{ html()->text('googleurl')
                ->value($sitesetting->google)
                ->class('form-control')
                ->placeholder('Google URL')
                ->attribute('maxlength', 191)
                ->autofocus() }}
          </div><!--col-->
    </div><!--form-group-->

      <div class="form-group row">
      <label class="col-md-2 form-control-label">You Tube URL </label>
         <div class="col-md-10">
            {{ html()->text('youtube')
                ->value($sitesetting->youtube)
                ->class('form-control')
                ->placeholder('Youtube URL')
                ->attribute('maxlength', 191)
                ->autofocus() }}
          </div><!--col-->
    </div><!--form-group-->

<div class="card-footer">
  <div class="row">
        <div class="col">
                {{ form_cancel(route('admin.site_setting.index'), __('buttons.general.cancel')) }}
            </div><!--col-->


            <div class="col text-right">
                {{ form_submit(__('buttons.general.crud.update')) }}
            </div><!--col-->
        </div><!--row-->
    </div><!--card-footer-->

  </div>
<div class="tab-pane fade" id="city-attractions" role="tabpanel" aria-labelledby="city-attractions-tab">
   <div class="form-group row">
      <label class="col-md-2 form-control-label">Logo</label>
          <div class="col-md-10">
              <input type="file" name="logo_image" accept="image/jpg, image/jpeg">
          </div><!--col-->
   </div><!--form-group-->

    <div class="form-group row">
      <label class="col-md-2 form-control-label">Terms </label>
          <div class="col-md-10">
              {{ html()->text('terms')
                  ->value($sitesetting->terms)
                  ->class('form-control')
                  ->placeholder('Terms')
                  ->attribute('maxlength', 191)
                  ->autofocus() }}
           </div><!--col-->
    </div><!--form-group-->

    <div class="form-group row">
   <label class="col-md-2 form-control-label">Disclaimer </label>
       <div class="col-md-10">
            {{ html()->text('disclaimer')
                ->value($sitesetting->disclaimer)
                ->class('form-control')
                ->placeholder('Disclaimer')
                ->attribute('maxlength', 191)
                ->autofocus() }}
         </div><!--col-->
    </div><!--form-group-->

    <div class="card-footer">
        <div class="row">
                 <div class="col">
                      {{ form_cancel(route('admin.site_setting.index'), __('buttons.general.cancel')) }}
                  </div><!--col-->

                  <div class="col text-right">
                      {{ form_submit(__('buttons.general.crud.update')) }}
                  </div><!--col-->
        </div><!--row-->
    </div><!--card-footer-->
</div>
  <div class="tab-pane fade" id="freecredit" role="tabpanel" aria-labelledby="city-attractions-tab">
     <div class="form-group row">
        <label class="col-md-2 form-control-label">Free Credit</label>
            <div class="col-md-10">
                {{ html()->text('free_credit')
                ->value($sitesetting->free_credit)
                ->class('form-control')
                ->placeholder('Free Credit')
                ->attribute('maxlength', 191)
                ->autofocus() }}
            </div><!--col-->
     </div><!--form-group-->
     <div class="card-footer">
        <div class="row">
                 <div class="col">
                      {{ form_cancel(route('admin.site_setting.index'), __('buttons.general.cancel')) }}
                  </div><!--col-->

                  <div class="col text-right">
                      {{ form_submit(__('buttons.general.crud.update')) }}
                  </div><!--col-->
        </div><!--row-->
    </div><!--card-footer-->
  </div>

        {{ html()->form()->close() }}

        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">

                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">

                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
<style>
.close:not(:disabled):not(.disabled) {
    cursor: pointer;
    margin-top: -25px;
}
</style>
@endsection
