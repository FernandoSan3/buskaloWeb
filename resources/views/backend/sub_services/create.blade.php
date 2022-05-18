@extends('backend.layouts.app')

@section('title', __('labels.backend.subservices.management') . ' | ' . __('labels.backend.subservices.create'))

@section('content')
{{ html()->form('POST', route('admin.subservices.store'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.subservices.management') 
                        <small class="text-muted"> @lang('labels.backend.subservices.create')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                   <div class="form-group row">
                        <label class="col-md-2 form-control-label">Select Category </label>   

                        <div class="col-md-10">
                            
                             <select name="category_id" id="category_id" class="form-control" onChange="getServices(this.value);">
                                <option value=" ">Select Category</option>
                                @if($categories)
                                @foreach($categories as $key => $category)
                                <option value="{{$category->id}}">{{$category->es_name}} </option>
                                @endforeach
                                @endif 
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->


                    <div class="services">
                        <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label">Select Service </label>   

                            <div class="col-md-10" id="service-list">
                                
                                 <select name="services_id" id="services_id" class="form-control">
                                    <option value=" ">Select Services</option>
                                    @if($services)
                                    @foreach($services as $key => $service)
                                    <option value="{{$service->id}}"> {{$service->es_name}}</option>
                                    @endforeach
                                    @endif 
                                 </select>   
                            </div><!--col-->
                        </div><!--form-group-->
                    </div>

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
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">                       
                        <label class="col-md-2 form-control-label">Price</label>

                        <div class="col-md-10">
                            {{ html()->text('price')
                                ->class('form-control')
                                ->placeholder('price')
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                  <?php /*  <div class="form-group row">                       
                        <label class="col-md-2 form-control-label">Percentage</label>

                        <div class="col-md-10">
                            {{ html()->text('percentage')
                                ->class('form-control')
                                ->placeholder('%')
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">                       
                        <label class="col-md-2 form-control-label">Quantity</label>

                        <div class="col-md-10">
                            {{ html()->number('quantity')
                                ->class('form-control')
                                ->placeholder('Quantity')
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    */?>

                   

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Icon</label>

                        <div class="col-md-10">
                           <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" id="imgupload" onchange="showMyImage(this)" style="display:none">
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
                    {{ form_cancel(route('admin.subservices.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<style type="text/css">
    .services{
        display: none;
    }
</style>
<script type="text/javascript">

    $(document).ready(function(){
        var category_id = $('#category_id').val();
        //getServices(category_id);

    })

    function getServices(val) {            
        $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/subservices/getServices") !!}',
            data:'category_id='+val,
            success: function(data){
                var a = data.html;
                var b = a.search('option');
                $("#service-list").html(data.html);
                //alert(b);
                if(b!='-1'){
                    $(".services").show();
                }else{
                    $(".services").hide();
                }
                             
            }
        });
    }
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
