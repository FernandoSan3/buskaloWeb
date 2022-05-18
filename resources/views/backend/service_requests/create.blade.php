@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')
{{ html()->form('POST', route('admin.questions.store'))->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.questions.management')
                        <small class="text-muted">
                        @lang('labels.backend.questions.create')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                    
                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Service </label>   

                        <div class="col-md-10">
                            
                             <select name="services_id" id="services_id" class="form-control" onChange="getSubservices(this.value);">
                                @if($services)
                                @foreach($services as $ke => $service)
                                <option value="{{$service->id}}">{{$service->es_name}} ( {{$service->en_name }} )</option>
                                @endforeach
                                @endif 
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Sub Service </label>   

                        <div class="col-md-10" id="subservice-list">
                            
                             <select name="sub_services_id" class="form-control"  >
                                @if($subservices)
                                @foreach($subservices as $key => $subservice)
                                <option value="{{$subservice->id}}">{{$subservice->es_name}} ( {{$subservice->en_name }} )</option>
                                @endforeach
                                @endif 
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->
                    
                    <div class="form-group row">
                        
                         <label class="col-md-2 form-control-label">Title </label>   

                        <div class="col-md-10">                            
                            <input type="text" name="en_title" required="" placeholder="Write in English" value="" class="form-control">   
                            <input type="text" name="es_title" required="" placeholder="Write in Spanish" value="" class="form-control">   
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Options </label>   

                        <div class="col-md-10" id="append_rows">   
                            <div id="row_1">                                
                                <input type="text" name="ans[en][0]" required="" placeholder="write in English" value="" class="ans form-control">   
                                <input type="text" name="ans[es][0]" required="" placeholder="write in Spanish" value="" class="form-control">   
                            </div>                         
                        </div><!--col-->
                        
                        <div><button type="button" id="add_more"> Add More</button></div>
                    
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
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
    $('#add_more').click(function(){
       
        var added_input = $(".ans").length; 
        var input = '<div id="row_'+added_input+'"><input type="text" name="ans[en]['+added_input+']" value="" class="ans form-control" required="" placeholder="write in English"> <input type="text" name="ans[es]['+added_input+']" required="" placeholder="write in Spanish" value="" class="form-control"> <button type="button" onclick="removeRow(this);"> Remove row</button></div>';
        $('#append_rows').append(input);
    });

    function removeRow($this) {

        $($this).parent('div').remove();
    }

    $(document).ready(function(){
        var services_id = $("#services_id").val();
        getSubservices(services_id);
    })

    function getSubservices(val) {        
        $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/questions/getSubservices") !!}',
            data:'services_id='+val,
            success: function(data){
                $("#subservice-list").html(data.html);
               
            }
        });
    }
</script>
@endsection
