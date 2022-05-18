@extends('backend.layouts.app')

@section('title', __('Faq Management') . ' | ' . __('Faq create'))

@section('content')
{{ html()->form('POST', route('admin.faq.store'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('Faq Management') 
                        <small class="text-muted"> @lang('Faq create')</small>
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
                         <label class="col-md-2 form-control-label">Question </label>   

                        <div class="col-md-10">
                            {{ html()->text('question')
                                ->class('form-control')
                                ->placeholder(__('Enter Question'))
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                  
                    <div class="form-group row">
                        {{-- html()->label(__('Answer'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Answer </label>

                        <div class="col-md-10">
                            {{ html()->text('answer')
                                ->class('form-control')
                                ->placeholder(__('Enter Answer'))
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{-- html()->label(__('Status'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Status </label>

                        <div class="col-md-2">
                            {{ html()->checkbox('status')
                                ->autofocus() }}
                        </div><!--col-->
                    </div>
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.faqs'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}
<script type="text/javascript">
  
    $(document).ready(function(){
        //alert();

    });

   
</script>

<script>

    $('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });

    function showMyImage(fileInput) {

        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var imageType = /image.*/;
            if (!file.type.match(imageType)) {
                continue;
            }
            var img=document.getElementById("thumbnil");
            img.file = file;
            var reader = new FileReader();
            reader.onload = (function(aImg) {
                return function(e) {
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
