@extends('backend.layouts.app')

@section('title', __('labels.backend.banner.management') . ' | ' . __('labels.backend.banner.create'))

@section('content')

{{ html()->form('POST', route('admin.banner.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="category_id" value="{{$banner->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.banner.management') 
                        <small class="text-muted"> @lang('labels.backend.banner.update')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>
            

            <div class="row mt-4">
                <div class="col">
                <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label"> @lang('labels.backend.banner.table.category') </label>   
 
                        <div class="col-md-10">
                            
                             <select name="category_id" disabled="true" class="form-control">
                                @if($category)
                                <option value="{{$category->id}}">{{$category->es_name}} </option>
                                @endif
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->
                  
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> @lang('labels.backend.banner.old_image') </label>

                        <div class="col-md-10">
                          <div class="image-view-list">
                            <ul>
                             <li>
                           <?php 
                                $image="";
                                $findinfolder="";
                                  if(isset($banner->banner_name))
                                    { $image=$banner->banner_name;
                                      $findinfolder=public_path().'/bannerimage/'.$banner->banner_name;
                                     }
                                if (file_exists($findinfolder) && !empty($image)) 
                                {?>
                                <img  src="{{asset('bannerimage/')}}/{{$image}}"><br/>
                               <a href="javascript:void(0)"  class="imageremove" data-id="{{$banner->id}}">
                                    <span class="removeicon">&times;</span></a>
                                <?php } else{ ?>
                               <!--  <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}"> -->
                                <?php } ?>
                            </li>
                        </ul>
                    </div>

                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> @lang('labels.backend.banner.image') </label>

                        <div class="col-md-10">
                          <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" id="imgupload" onchange="showMyImage(this)"  style="display:none">
                           <div  id="OpenImgUpload" style="cursor: pointer;">
                           <img id="thumbnil" src="{{url('img/frontend/add-image.png')}}" class="img-fluid upload-img-icon" alt="image">
                           </div>
                        </div><!--col-->
                    </div><!--form-group-->
                    @if(($banner)!= null)
                    <div class="form-group row">
                       <label class="col-md-2 form-control-label"> @lang('labels.backend.banner.image_view')  </label>
                        <div class="col-md-10">
                          <div class="image-view-list">
                            <ul>
                        @foreach($banner as $images) 
                        <li>
                           <?php 
                                $image="";
                                $findinfolder="";
                                  if(isset($banner->image))
                                    { $image=$banner->image;
                                      $findinfolder=public_path().'/bannerimage/'.$banner->banner_name;
                                     }
                                if (file_exists($findinfolder) && !empty($images)) 
                                {?>
                                <img src="{{asset('bannerimage/')}}/{{$images->banner_name}}"><br/>
                               <a href="javascript:void(0)"  class="imageremove2" data-id="{{$images->id}}">
                                    <span class="removeicon">&times;</span>
                                </a>
                            <?php } ?>
                        </li>
                        @endforeach
                        </ul>
                    </div>
                        </div><!--col-->
                    </div><!--form-group-->
                    @endif


                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.banner.index'), __('buttons.general.cancel')) }}
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
            url:"{{url('admin/banner/removeImage')}}",
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
