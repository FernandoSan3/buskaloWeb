@extends('backend.layouts.app')

@section('title', __('labels.backend.childsubservices.management') . ' | ' . __('labels.backend.subservices.update'))


@section('content')
{{ html()->form('POST', route('admin.childsubservices.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="child_sub_service_id" value="{{$child_sub_service->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.childsubservices.management') 
                        <small class="text-muted"> @lang('labels.backend.childsubservices.update')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                     <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Category </label>   
 
                        <div class="col-md-10">
                            
                             <select name="category_id" disabled="true" class="form-control">
                                 @if(isset($child_sub_service->category_id) && $child_sub_service->category_id >0)
                                @if($categories)
                                @foreach($categories as $key => $category)
                                <option  <?php if(isset($child_sub_service->category_id) && $child_sub_service->category_id == $category->id ) { ?> selected <?php }  ?> value="{{$category->id}}">{{$category->es_name}} </option>
                               
                                @endforeach
                                @endif
                                @else
                                <option>No Category Selected</option>
                                @endif 
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Service </label>   

                        <div class="col-md-10" id="service-list">
                            
                             <select name="services_id" disabled="true" id="services_id" class="form-control">
                                @if(isset($child_sub_service->category_id) && $child_sub_service->category_id >0)
                                @if($services)
                                @foreach($services as $key => $service)
                                <option <?php if(isset($child_sub_service->services_id) && $child_sub_service->services_id == $service->id ) { ?> selected <?php }  ?> value="{{$service->id}}">{{$service->es_name}} ( {{$service->en_name }} )</option>
                                @endforeach
                                @endif
                                 @else
                                <option>No Service Selected</option>
                                @endif  
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->

                     <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select SubService </label>   

                        <div class="col-md-10" id="sub-service-list">
                            
                             <select name="sub_services_id" disabled="true" id="sub_services_id" class="form-control">
                                @if(isset($child_sub_service->sub_services_id) && $child_sub_service->sub_services_id >0)
                                @if($subservices)
                                @foreach($subservices as $key => $subservice)
                                <option <?php if(isset($child_sub_service->sub_services_id) && $child_sub_service->sub_services_id == $subservice->id ) { ?> selected <?php }  ?> value="{{$subservice->id}}">{{$subservice->es_name}} </option>
                                @endforeach
                                @endif
                                 @else
                                <option>No Sub Service Selected</option>
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
                                ->value($child_sub_service->en_name)
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
                                ->value($child_sub_service->es_name)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">                      

                        <label class="col-md-2 form-control-label">Percentage</label>

                        <div class="col-md-10">
                            {{ html()->text('percentage')
                                ->class('form-control')
                                ->placeholder('Percentage')
                                ->value($child_sub_service->percentage)
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <!-- <div class="form-group row">
                        <label class="col-md-2 form-control-label">Old Icon</label>

                        <div class="col-md-10">
                           <?php 
                                $image="";
                                $findinfolder="";
                                  if(isset($child_sub_service->image))
                                    { $image=$child_sub_service->image;
                                      $findinfolder=public_path().'/img/'.$child_sub_service->image;
                                     }
                                if (file_exists($findinfolder) && !empty($image)) 
                                {?>
                                <img class="" style="height: 50px;width: 70px;" src="{{asset('img/')}}/{{$image}}">
                                <?php } else{ ?>
                                <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}">
                                <?php } ?>
                        </div>
                    </div> -->

                   

                     <!-- <div class="form-group row">
                        <label class="col-md-2 form-control-label">Icon</label>

                        <div class="col-md-10">
                             <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" id="imgupload" onchange="showMyImage(this)"  style="display:none">
                           <div  id="OpenImgUpload" style="cursor: pointer;">
                           <img id="thumbnil" src="{{url('img/frontend/add-image.png')}}" class="img-fluid upload-img-icon" alt="image">
                           </div>
                        </div>
                    </div> -->



                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.childsubservices.index'), __('buttons.general.cancel')) }}
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
@endsection
