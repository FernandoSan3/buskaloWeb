@extends('backend.layouts.app')

@section('title', __('labels.backend.category.management') . ' | ' . __('labels.backend.category.create'))

@section('content')
{{ html()->form('POST', route('admin.category.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="category_id" value="{{$category->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.category.management') 
                        <small class="text-muted"> @lang('labels.backend.category.update')</small>
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
                         <label class="col-md-2 form-control-label">English Name </label>   

                        <div class="col-md-10">
                            {{ html()->text('en_name')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.roles.name'))
                                ->attribute('maxlength', 191)
                                ->value($category->en_name)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.roles.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Spanish Name </label>

                        <div class="col-md-10">
                            {{ html()->text('es_name')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.roles.name'))
                                ->attribute('maxlength', 191)
                                ->value($category->es_name)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.roles.icon'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Icon </label>

                        <div class="col-md-10">
                            {{ html()->text('icon')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.access.roles.icon'))
                                ->attribute('maxlength', 191)
                                ->autofocus()
                                ->required() 
                                ->value($category->icon)}}
                                <br>
                                <i class="{{$category->icon}}" aria-hidden="true" style="font-size: 35px;"></i>
                        </div><!--col-->
                    </div><!--form-group-->

                  
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Old Image</label>

                        <div class="col-md-10">
                           <?php 
                                $image="";
                                $findinfolder="";
                                  if(isset($category->image))
                                    { $image=$category->image;
                                      $findinfolder=public_path().'/img/'.$category->image;
                                     }
                                if (file_exists($findinfolder) && !empty($image)) 
                                {?>
                                <img class="" style="height: 50px;width: 70px;" src="{{asset('img/')}}/{{$image}}">
                                <?php } else{ ?>
                                <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}">
                                <?php } ?>

                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Image</label>

                        <div class="col-md-10">
                          <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" id="imgupload" onchange="showMyImage(this)"  style="display:none">
                           <div  id="OpenImgUpload" style="cursor: pointer;">
                           <img id="thumbnil" src="{{url('img/frontend/add-image.png')}}" class="img-fluid upload-img-icon" alt="image">
                           </div>
                        </div><!--col-->
                    </div><!--form-group-->



                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.category.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection
