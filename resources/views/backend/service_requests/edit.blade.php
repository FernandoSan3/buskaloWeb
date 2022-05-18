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
                        
                         <label class="col-md-2 form-control-label">Title </label>   

                        <div class="col-md-10">                            
                            <input type="text" name="en_title" required="" placeholder="Write in English" value="{{ $question_details->en_title }}" class="form-control">   
                            <input type="text" name="es_title" required="" placeholder="Write in Spanish" value="{{ $question_details->es_title }}" class="form-control">   
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Options </label>   

                        <div class="col-md-10" id="append_rows"> 
                        <?php 
                            if(isset($question_details->options) && !empty($question_details->options)) 
                            {  
                                foreach ($question_details->options as $key => $value) 
                                {
                                   
                        ?>
                            <div id="row_{{$key+1}}">                                
                                
                                <input type="text" name="ans[en][{{$key}}]" required="" value="{{$value->en_option}}" placeholder="write in English"  class="ans form-control">   
                                <input type="text" name="ans[es][{{$key}}]" required="" placeholder="write in Spanish" value="{{$value->es_option}}" class="form-control"><br>

                                <?php if($key !=0) { ?> 
                                    <button type="button" onclick="removeRow(this);"> Remove row</button>  
                                <?php } ?>
                            </div>
                        <?php
                                }
                            }

                        ?>  
                                                     
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
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
    $('#add_more').click(function(){
       
        var added_input = $(".ans").length; 
        var input = '<div id="row_'+added_input+'" ><input type="text" name="ans[en]['+added_input+']" value="" class="ans form-control" required="" placeholder="write in English"> <input type="text" name="ans[es]['+added_input+']" required="" placeholder="write in Spanish" value="" class="form-control"> <button type="button" onclick="removeRow(this);"> Remove row</button></div>';
        $('#append_rows').append(input);
    });

    function removeRow($this) {

        $($this).parent('div').remove();
    }
</script>
@endsection
