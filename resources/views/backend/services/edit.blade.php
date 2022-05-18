@extends('backend.layouts.app')

@section('title', __('labels.backend.services.management') . ' | ' . __('labels.backend.services.create'))

@section('content')
<style type="text/css">
span.removeicon {
       text-align: center;
    background: #c5c5c5;
    position: absolute;
    color: red;
    border-radius: 3px;
    width: 72px;
    font-weight: 600;
}
</style>

{{ html()->form('POST', route('admin.services.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="service_id" value="{{$service->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.services.management') 
                        <small class="text-muted"> @lang('labels.backend.services.update')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                     <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Category </label>   
 
                        <div class="col-md-10">
                            
                             <select name="categorty_id" disabled="true" class="form-control">
                                @if(isset($service->category_id) && $service->category_id >0)
                                @if($categories)
                                @foreach($categories as $key => $category)
                                <option <?php if(isset($service->category_id) && $service->category_id == $category->id ) { ?> selected <?php }  ?> value="{{$category->id}}">{{$category->es_name}} </option>
                                @endforeach
                                @endif 
                                @else
                                <option>No Category Selected</option>
                                @endif 
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->


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
                                ->value($service->en_name)
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
                                ->value($service->es_name)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                   <?php /*  <div class="form-group row">
                        {{-- html()->label(__('validation.attributes.backend.access.roles.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') --}}

                        <label class="col-md-2 form-control-label">Price </label>

                        <div class="col-md-10">
                            {{ html()->text('price')
                                ->class('form-control')
                                ->placeholder(__('Price'))
                                ->attribute('maxlength', 191)
                                ->value($service->price)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    */?>

                     <div class="form-group row">
                        <label class="col-md-2 form-control-label">Old Icon</label>

                        <div class="col-md-10">
                           <?php 
                                $image="";
                                $findinfolder="";
                                  if(isset($service->image))
                                    { $image=$service->image;
                                      $findinfolder=public_path().'/img/'.$service->image;
                                     }
                                if (file_exists($findinfolder) && !empty($image)) 
                                {?>
                                <img class="" style="height: 50px;width: 70px;" src="{{asset('img/')}}/{{$image}}"><br/>
                                <a href="javascript:void(0)"  class="imageremove" data-id="{{$service->id}}"><span class="removeicon">Delete</span></a>
                                <?php } else{ ?>
                                <!-- <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}"> -->
                                <?php } ?>

                        </div><!--col-->
                    </div><!--form-group-->
                   

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Icon</label>

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
                    {{ form_cancel(route('admin.services.index'), __('buttons.general.cancel')) }}
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

    $('.imageremove').click(function()
    {
        var id = $(this).data('id');
        $.ajax({
            url:"{{url('admin/services/removeImage')}}",
            type:"get",
            data:{'id':id},
            success:function(resp)
            {
                window.location.reload();
            }

        });
    });
</script>

@endsection
