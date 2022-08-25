@extends('backend.layouts.app')

@section('title', __('labels.backend.banner.management') . ' | ' . __('labels.backend.banner.create'))

@section('content')
{{ html()->form('POST', route('admin.banner.store'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.banner.management') 
                        <small class="text-muted"> @lang('labels.backend.banner.create')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                <div class="form-group row">
                        <label class="col-md-2 form-control-label">@lang('labels.backend.banner.table.select_category') </label>   

                        <div class="col-md-10">
                            
                             <select name="category_id" id="category_id" class="form-control" onChange="getServices(this.value);">
                                <option value=" "> @lang('labels.backend.banner.table.select_category')</option>
                                @if($categories)
                                @foreach($categories as $key => $category)
                                <option value="{{$category->id}}">{{$category->es_name}} </option>
                                @endforeach
                                @endif 
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> @lang('labels.backend.banner.table.image')</label>

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
                    {{ form_cancel(route('admin.banner.index'), __('buttons.general.cancel')) }}
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
