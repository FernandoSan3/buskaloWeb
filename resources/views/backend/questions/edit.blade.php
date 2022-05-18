@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')
{{ html()->form('POST', route('admin.questions.update'))->class('form-horizontal')->open() }}
<input type="hidden" name="question_id" value="{{$question_details->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.questions.management')
                        <small class="text-muted">
                        @lang('labels.backend.questions.update')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                    <div class="form-group row">                        
                        <label class="col-md-2 form-control-label">Select Category </label>   

                        <div class="col-md-6">                            
                             <select name="category_id" id="category_id" class="form-control" disabled="true">
                                @if(count($categories) > 0  && $question_details->category_id > 0)
                                @foreach($categories as $key => $category)
                                <option <?php if(isset($question_details->category_id) && $question_details->category_id == $category->id ) { ?> selected <?php }  ?> value="{{$category->id}}">{{$category->es_name}} </option>
                                @endforeach
                                @else
                                <option value="">No Option Available </option>
                                @endif  
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->


                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Service </label>   

                        <div class="col-md-6" id="service-list">                             
                            <select name="services_id" id="services_id" class="form-control" disabled="true">
                                @if(count($services)>0  && $question_details->services_id > 0)
                                @foreach($services as $ke => $service)
                                <option <?php if(isset($question_details->services_id) && $question_details->services_id == $service->id ) { ?> selected <?php }  ?> value="{{$service->id}}">{{$service->es_name}} </option>
                                @endforeach
                                @else
                                <option value="">No Option Available </option>
                                @endif  
                            </select> 
                                
                             </select>   
                        </div><!--col-->
                    </div>


                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Sub Service </label>   

                        <div class="col-md-6" id="subservice-list">
                           <select name="sub_services_id" id="sub_services_id" class="form-control" disabled="true">
                                
                                @if(count($subservices)>0 && $question_details->sub_services_id > 0)
                                @foreach($subservices as $key => $subservice)

                                <option <?php if(isset($question_details->sub_services_id) && $question_details->sub_services_id == $subservice->id ) { ?> selected <?php }  ?> value="{{$subservice->id}}">{{$subservice->es_name}} </option>
                                @endforeach
                                @else
                                <option value="">No Option Available </option>
                                @endif 
                             </select>
                               
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Child Sub Service </label>   

                        <div class="col-md-6" id="childsubservice-list">
                            <select name="child_sub_services_id" disabled="true" id="child_sub_services_id" class="form-control"  >
                                @if(count($childsubservices)>0 && $question_details->child_sub_service_id > 0)
                                @foreach($childsubservices as $key => $childsubservice)
                                <option <?php if(isset($question_details->child_sub_service_id) && $question_details->child_sub_service_id == $childsubservice->id ) { ?> selected <?php }  ?> value="{{$childsubservice->id}}">{{$childsubservice->es_name}}</option>
                                @endforeach
                                @else
                                <option value="">No Option Selected </option>
                                @endif 
                             </select>  
                            
                        </div><!--col-->
                    </div><!--form-group-->
                    
                    
                    
                    <div class="form-group row">
                        
                         <label class="col-md-2 form-control-label">Title </label>   

                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-6">                            
                                    <input type="text" name="en_title" required="" placeholder="Write in English" value="{{ $question_details->en_title }}" class="form-control">
                                </div>
                                <div class="col-md-6">    
                                    <input type="text" name="es_title" required="" placeholder="Write in Spanish" value="{{ $question_details->es_title }}" class="form-control">
                                </div>
                            </div>   
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">                        
                        <label class="col-md-2 form-control-label">Question Order </label>
                        <div class="col-md-6" >
                            <select name="question_order" class="form-control">
                               <option <?php if($question_details->question_order == 1){?> selected <?php } ?> value="1">1</option>
                               <option <?php if($question_details->question_order == 2){?> selected <?php } ?> value="2">2</option>
                               <option <?php if($question_details->question_order == 3){?> selected <?php } ?> value="3">3</option>
                               <option <?php if($question_details->question_order == 4){?> selected <?php } ?> value="4">4</option>
                               <option <?php if($question_details->question_order == 5){?> selected <?php } ?> value="5">5</option>
                               <option <?php if($question_details->question_order == 6){?> selected <?php } ?> value="6">6</option>
                               <option <?php if($question_details->question_order == 7){?> selected <?php } ?> value="7">7</option>
                               <option <?php if($question_details->question_order == 8){?> selected <?php } ?> value="8">8</option>
                               <option <?php if($question_details->question_order == 9){?> selected <?php } ?> value="9">9</option>
                               <option <?php if($question_details->question_order == 10){?> selected <?php } ?> value="10">10</option>
                           </select>                             
                        </div><!--col-->
                    </div><!--form-group-->

                    <?php
                        if(isset($question_details->options) && count($question_details->options) > 0) 
                        {

                     ?>
                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Options </label>   
                       
                        <div class="col-md-10" id="append_rows"> 
                        <?php 
                              
                                foreach ($question_details->options as $key => $value) 
                                { 
                                   
                        ?>
                            <div style="margin-bottom: 10px !important;" id="row_{{$key+1}}">                                
                                
                                <input type="text" name="ans[en][{{$key}}]" required="" value="{{$value->en_option}}" placeholder="write in English"  class="ans form-control">   
                                <input type="text" name="ans[es][{{$key}}]" required="" placeholder="write in Spanish" value="{{$value->es_option}}" class="form-control">

                                 <div class="row">
                                    <div class="col-md-4">
                                        
                                         <input type="text" name="ans[price][{{ $key }}]"  placeholder="Amount" value="{{$value->price}}" class="form-control"> 

                                    </div>
                                    <div class="col-md-4"><input type="text" name="ans[factor][{{$key}}]"  placeholder="Percentage %" value="{{$value->factor}}" class="form-control"></div>

                                    <!--  <div class="col-md-4"><input type="text" name="ans[quantity][{{$key}}]"  placeholder="quantity" value="{{$value->quantity}}" class="form-control">
                                     </div> -->
                                </div>
                                

                                <?php if($key !=0) { ?> 
                                    <button type="button" onclick="removeRow(this);"> Remove row</button>  
                                <?php } ?>
                            </div>
                        <?php
                                }
                            

                        ?>  
                                                     
                        </div><!--col-->
                        
                        
                        <div class="bottom-btn"><button type="button" id="add_more"> <i class="fa fa-plus"></i> Add More</button></div>
                    
                    </div><!--form-group-->
                    <?php } ?>
                </div><!--col-->
             </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.questions.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
    $('#add_more').click(function(){
       
        var added_input = $(".ans").length; 
        // var input = '<div style="margin-bottom: 10px !important;" id="row_'+added_input+'" ><input type="text" name="ans[en]['+added_input+']" value="" class="ans form-control" required="" placeholder="write in English"> <input type="text" name="ans[es]['+added_input+']" required="" placeholder="write in Spanish" value="" class="form-control"> <button type="button" onclick="removeRow(this);"> Remove row</button></div>';
        // $('#append_rows').append(input);

         $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/questions/getOptionView") !!}',
            data:'added_input='+added_input,
            success: function(data){
                //alert('hello');
                //$("#append_rows").html(data.html);
                $('#append_rows').append(data.html);
            }
        });
         
    });

    function removeRow($this) {

        $($this).parent('div').remove();
    }
</script>
@endsection
