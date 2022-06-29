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
                     @lang('labels.backend.work.management')
                    <small class="text-muted"> @lang('labels.backend.work.create')</small>
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
<h3> @lang('labels.backend.work.user') </h3>
  {{ html()->form('POST', route('admin.work.updatework'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<input type="hidden" name="user" value="1">
  <input type="hidden" name="id" value="1">
<ul class="nav nav-mytabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#search" role="tab" aria-controls="home" aria-selected="true"> @lang('labels.backend.work.does_work') </a>
  </li>
 <!--  <li class="nav-item">
    <a class="nav-link" id="history-tab" data-toggle="tab" href="#compare" role="tab" aria-controls="history" aria-selected="false">Compare</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="city-attractions-tab" data-toggle="tab" href="#hire" role="tab" aria-controls="city-attractions" aria-selected="false">Hires</a>
  </li> -->
  <li class="nav-item">
    <a class="nav-link" id="city-attractions-tab" data-toggle="tab" href="#other" role="tab" aria-controls="city-attractions" aria-selected="false"> @lang('labels.backend.work.other') </a>
  </li>
</ul>

<div class="tab-content mytab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="search" role="tabpanel" aria-labelledby="home-tab">

    <!-- <div class="form-group row">
       <label class="col-md-2 form-control-label">Search</label>
       <div class="col-md-10">
          {{ html()->text('search')
            ->class('form-control')
            ->value($how_it_is_work->search)
            ->placeholder('Search')
            ->required()
            ->autofocus() }}
       </div>
     </div> -->

    <div class="form-group row">
     <label class="col-md-2 form-control-label"> @lang('labels.backend.work.description') </label>
      <div class="col-md-10">
         {{ html()->textarea('search_descriptiom')
            ->class('form-control')
            ->value($how_it_is_work->search_descriptiom)
            ->placeholder('Description')
            ->attribute('maxlength', 191)
            ->required()
            ->autofocus() }}
      </div><!--col-->
    </div><!--form-group-->

  </div>

  <div class="tab-pane fade" id="compare" role="tabpanel" aria-labelledby="history-tab">
  
    <div class="form-group row">
      <label class="col-md-2 form-control-label"> @lang('labels.backend.work.compare') </label>
         <div class="col-md-10">
            {{ html()->text('compare')
              ->class('form-control')
              ->value($how_it_is_work->compare)
              ->placeholder('Name')
              ->required()
              ->autofocus() }}
         </div><!--col-->
    </div><!--form-group-->
    <div class="form-group row">
      <label class="col-md-2 form-control-label"> @lang('labels.backend.work.description')</label>
        <div class="col-md-10">
           {{ html()->textarea('compare_description')
                  ->class('form-control')
                  ->value($how_it_is_work->compare_description)
                  ->placeholder('Description')
                  ->required()
                  ->autofocus() }}
        </div><!--col-->
    </div><!--form-group-->

    
  </div>

  <div class="tab-pane fade" id="hire" role="tabpanel" aria-labelledby="history-tab">
      <div class="form-group row">
        <label class="col-md-2 form-control-label"> @lang('labels.backend.work.name') </label>
           <div class="col-md-10">
              {{ html()->text('hire')
                ->class('form-control')
                ->value($how_it_is_work->hire)
                ->placeholder('Name')
                ->required()
                ->autofocus() }}
           </div><!--col-->
      </div><!--form-group-->
      <div class="form-group row">
        <label class="col-md-2 form-control-label">  @lang('labels.backend.work.description') </label>
          <div class="col-md-10">
             {{ html()->textarea('hire_description')
                ->class('form-control')
                ->value($how_it_is_work->hire_description)
                ->placeholder('Description')
                ->required()
                ->autofocus() }}
          </div><!--col-->
      </div><!--form-group-->

      
  </div>

<div class="tab-pane fade" id="other" role="tabpanel" aria-labelledby="history-tab">
      <div class="form-group row">
        <label class="col-md-2 form-control-label"> @lang('labels.backend.work.image') </label>
           <div class="col-md-10">
              {{ html()->file('image')
                ->attribute('maxlength', 191)
                ->autofocus() }}
                <br><br>
                <img src="{{url('/img/frontend/work/'.$how_it_is_work->image)}}" style="height: 100px; width: 100px;">
           </div><!--col-->

      </div><!--form-group-->

      <div class="form-group row">
        <label class="col-md-2 form-control-label"> @lang('labels.backend.work.description') </label>
          <div class="col-md-10">
             {{ html()->textarea('description')
                ->class('form-control')
                ->value($how_it_is_work->description)
                ->placeholder('Description')
                ->required()
                ->autofocus() }}
          </div><!--col-->
      </div><!--form-group-->
  </div>
  
      <div class="card-footer">
        <div class="row">
            <div class="col">
                {{ form_cancel(route('admin.work.index'), __('buttons.general.cancel')) }}
            </div><!--col-->


            <div class="col text-right">
                {{ form_submit(__('buttons.general.crud.update')) }}
            </div><!--col-->
        </div><!--row-->
      </div><!--card-footer-->

  {{ html()->form()->close() }}
</div><!--row-->


<h3> @lang('labels.backend.work.professional') </h3>
  {{ html()->form('POST', route('admin.work.updatework'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
  <input type="hidden" name="profesional" value="2">
  <input type="hidden" name="id" value="2">
<ul class="nav nav-mytabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#search1" role="tab" aria-controls="home" aria-selected="true"> @lang('labels.backend.work.does_work') </a>
  </li>
 <!--  <li class="nav-item">
    <a class="nav-link" id="history-tab" data-toggle="tab" href="#compare1" role="tab" aria-controls="history" aria-selected="false">Compare</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="city-attractions-tab" data-toggle="tab" href="#hire1" role="tab" aria-controls="city-attractions" aria-selected="false">Hires</a>
  </li> -->
  <li class="nav-item">
    <a class="nav-link" id="city-attractions-tab" data-toggle="tab" href="#other1" role="tab" aria-controls="city-attractions" aria-selected="false"> @lang('labels.backend.work.other') </a>
  </li>
</ul>

<div class="tab-content mytab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="search1" role="tabpanel" aria-labelledby="home-tab">

    <!-- <div class="form-group row">
       <label class="col-md-2 form-control-label">Search</label>
       <div class="col-md-10">
          {{ html()->text('search')
            ->class('form-control')
            ->value($how_it_is_work1->search)
            ->placeholder('Search')
            ->required()
            ->autofocus() }}
       </div>
     </div> -->

    <div class="form-group row">
     <label class="col-md-2 form-control-label"> @lang('labels.backend.work.description') </label>
      <div class="col-md-10">
         {{ html()->textarea('search_descriptiom')
            ->class('form-control')
            ->id('search_descriptiom2')
            ->value($how_it_is_work1->search_descriptiom)
            ->placeholder('Description')
            ->attribute('maxlength', 191)
            ->required()
            ->autofocus() }}
      </div><!--col-->
    </div><!--form-group-->

  </div>

  <div class="tab-pane fade" id="compare1" role="tabpanel" aria-labelledby="history-tab">
  
    <div class="form-group row">
      <label class="col-md-2 form-control-label">Compare</label>
         <div class="col-md-10">
            {{ html()->text('compare')
              ->class('form-control')
              ->value($how_it_is_work1->compare)
              ->placeholder('Name')
              ->required()
              ->autofocus() }}
         </div><!--col-->
    </div><!--form-group-->
    <div class="form-group row">
      <label class="col-md-2 form-control-label"> @lang('labels.backend.work.description') </label>
        <div class="col-md-10">
           {{ html()->textarea('compare_description')
                  ->class('form-control')
                  ->value($how_it_is_work->compare_description)
                  ->placeholder('Description')
                  ->required()
                  ->autofocus() }}
        </div><!--col-->
    </div><!--form-group-->

    
  </div>

  <div class="tab-pane fade" id="hire1" role="tabpanel" aria-labelledby="history-tab">
      <div class="form-group row">
        <label class="col-md-2 form-control-label">Name</label>
           <div class="col-md-10">
              {{ html()->text('hire')
                ->class('form-control')
                ->value($how_it_is_work->hire)
                ->placeholder('Name')
                ->required()
                ->autofocus() }}
           </div><!--col-->
      </div><!--form-group-->
      <div class="form-group row">
        <label class="col-md-2 form-control-label"> @lang('labels.backend.work.description') </label>
          <div class="col-md-10">
             {{ html()->textarea('hire_description')
                ->class('form-control')
                ->value($how_it_is_work->hire_description)
                ->placeholder('Description')
                ->required()
                ->autofocus() }}
          </div><!--col-->
      </div><!--form-group-->

      
  </div>

<div class="tab-pane fade" id="other1" role="tabpanel" aria-labelledby="history-tab">
      <div class="form-group row">
        <label class="col-md-2 form-control-label"> @lang('labels.backend.work.image') </label>
           <div class="col-md-10">
              {{ html()->file('image')
                ->attribute('maxlength', 191)
                ->autofocus() }}
                <br><br>
                <img src="{{url('/img/frontend/work/'.$how_it_is_work1->image)}}" style="height: 100px; width: 100px;">
           </div><!--col-->

      </div><!--form-group-->

      <div class="form-group row">
        <label class="col-md-2 form-control-label"> @lang('labels.backend.work.description') </label>
          <div class="col-md-10">
             {{ html()->textarea('description')
                ->class('form-control')
                ->value($how_it_is_work1->description)
                ->placeholder('Description')
                ->required()
                ->autofocus() }}
          </div><!--col-->
      </div><!--form-group-->
  </div>
 <div class="card-footer">
        <div class="row">
            <div class="col">
                {{ form_cancel(route('admin.work.index'), __('buttons.general.cancel')) }}
            </div><!--col-->


            <div class="col text-right">
                {{ form_submit(__('buttons.general.crud.update')) }}
            </div><!--col-->
        </div><!--row-->
      </div><!--card-footer-->
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
    textarea.form-control {
    height: 17pc;
   }
  </style>
   <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
  <script>
        CKEDITOR.replace( 'search_descriptiom' );
        CKEDITOR.replace( 'search_descriptiom2' );
</script>
@endsection
