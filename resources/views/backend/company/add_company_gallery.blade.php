@extends('backend.layouts.app')

@section('title', __('labels.backend.company.management') . ' | ' . __('labels.backend.company.create'))

@section('content')

{{ html()->form('POST', route('admin.company.store_company_gallery'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<input type="hidden" name="user_id" value="{{$user_id}}">

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Company Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.questions.create') --> Add Gallery</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                   

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> Image</label>
                        <div class='file_upload' id='f1'>
                            <input name='images_gallery[]' type='file'/>
                        </div>

                        <div id='image_file_tools'>
                          <i class="fa fa-plus-circle" id='addGalleryImage' aria-hidden="true">Add new image file</i>
                          <i class="fa fa-minus-circle" id='deleteGalleryImage' aria-hidden="true">Delete</i>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> Video</label>
                         <div class='file_upload2' id='f2'>
                            <input name='videos_gallery[]' type='file'/>
                        </div>

                        <div id='videos_file_tools'>
                          <i class="fa fa-plus-circle" id='addGalleryVideo' aria-hidden="true">Add new video File</i>
                          <i class="fa fa-minus-circle" id='deleteGalleryVideo' aria-hidden="true">Delete</i>
                        </div>
                    </div>
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

<script type='text/javascript'>
$(document).ready(function(){
  var counter = 2;
  
  $('#deleteGalleryImage').hide();
  $('#addGalleryImage').click(function(){
     $('#image_file_tools').before('<div class="file_upload1" id="f1'+counter+'"><input name="images_gallery[]" type="file"></div>');
    $('#deleteGalleryImage').fadeIn(0);
  counter++;
  });
  $('#deleteGalleryImage').click(function(){
    if(counter==3){
      $('#deleteGalleryImage').hide();
    }   
    counter--;
    $('#f1'+counter).remove();
  });
});
</script>


<script type='text/javascript'>
$(document).ready(function(){
  var counter = 2;
  $('#deleteGalleryVideo').hide();
  $('#addGalleryVideo').click(function(){
     $('#videos_file_tools').before('<div class="file_upload2" id="f2'+counter+'"><input name="videos_gallery[]" type="file"></div>');
    $('#deleteGalleryVideo').fadeIn(0);
  counter++;
  });
  $('#deleteGalleryVideo').click(function(){
    if(counter==3){
      $('#deleteGalleryVideo').hide();
    }   
    counter--;
    $('#f2'+counter).remove();
  });
});
</script>
@endsection
