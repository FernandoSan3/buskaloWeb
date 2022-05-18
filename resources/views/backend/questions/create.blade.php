@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')
{{ html()->form('POST', route('admin.questions.store'))->class('form-horizontal')->id('question-form')->open() }}
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
                        <label class="col-md-2 form-control-label">Select Category </label>   

                        <div class="col-md-6">                            
                             <select name="category_id" id="category_id" class="form-control" onChange="getServices(this.value);">
                                @if($categories)
                                @foreach($categories as $key => $category)
                                <option value="{{$category->id}}">{{$category->es_name}} </option>
                                @endforeach
                                @endif 
                             </select>   
                        </div><!--col-->
                    </div><!--form-group-->

                    
                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Service </label>   

                        <div class="col-md-6" id="service-list">
                             <select name="services_id" id="services_id" class="form-control" onChange="getSubservices(this.value);">
                                
                                <option value="">No Option Available </option>
                               
                             </select>   
                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Sub Service </label>   

                        <div class="col-md-6" id="subservice-list">
                            <select name="sub_services_id" id="sub_services_id" class="form-control" onChange="getChildSubservices(this.value);">
                               <option value="">No Option Available </option>
                            </select>
                               
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Child Sub Service </label>   

                        <div class="col-md-6" id="childsubservice-list">
                            <select name="child_sub_services_id" id="child_sub_services_id" class="form-control" >
                                <option value="">No Option Available </option>
                            </select> 
                            
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">                        
                        <label class="col-md-2 form-control-label">Question Order </label>
                        <div class="col-md-6" >
                            <select name="question_order" class="form-control">
                               <option value="1">1</option>
                               <option value="2">2</option>
                               <option value="3">3</option>
                               <option value="4">4</option>
                               <option value="5">5</option>
                               <option value="6">6</option>
                               <option value="7">7</option>
                               <option value="8">8</option>
                               <option value="9">9</option>
                               <option value="10">10</option>
                           </select>                             
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label"> Dependency<!-- Is Related --> </label>   

                        <div class="col-md-6" >
                            <input type="radio" id="yes_related" name="is_related" value="Yes"> Yes
                             <input type="radio" id="no_related" name="is_related" checked="checked" value="No" class="ml-5"> No
                             
                        </div><!--col-->
                    </div>

                    <div class="form-group row" id="related_question_div">                        
                        <label class="col-md-2 form-control-label">Select Related Question </label>

                        <div class="col-md-6" id="related_question_list">
                            
                            <!--  <select name="related_question_id" id="related_question_id" class="form-control"  >
                             </select>    -->
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row" id="related_option_div">                        
                        <label class="col-md-2 form-control-label">Select Related Option</label>
                        <div class="col-md-6" id="related_option_list">                            
                            <!--  <select name="related_option_id" class="form-control"  >
                                <option value="0">Please Select</option>
                             </select>  -->  
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">                        
                        <label class="col-md-2 form-control-label">Select Question Type </label>
                        <div class="col-md-6" >
                            <select name="question_type" id="question-type-list" class="form-control" required >
                               <!-- <option value="textarea">Text Box</option> -->
                                <option value="">Select Question Type</option> 
                               <option value="radio">Single Selection</option>
                               <option value="checkbox">Multiple Selection</option> 
                               <!-- <option value="select">Dropdown Selection</option>  -->
                               <option value="file">File Upload</option> 
                               <!-- <option value="date">Date Selection</option>  -->
                               <option value="date_time">Date Time Selection</option> 
                               <option value="quantity">Quantity</option> 
                               <option value="date">Date</option> 
                               <option value="text">Text</option> 
                            </select>   
                        </div><!--col-->
                    </div><!--form-group-->

                    
                    <div class="form-group row" id="question_div" >
                        
                         <label class="col-md-2 form-control-label">Question </label>   

                        <div class="col-md-10">                            
                            <input type="text" name="en_title"  placeholder="Write Question in English" value="" class="form-control">   
                            <input type="text" name="es_title"  placeholder="Write Question in Spanish" value="" class="form-control">   
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row" id="option_div">
                        
                        <label class="col-md-2 form-control-label">Options </label>   

                        <div class="col-md-10" id="append_rows">   
                            <div style="margin-bottom: 10px !important;" id="row_1">                                
                                <input type="text" name="ans[en][0]"  placeholder="write option in English" value="" class="ans form-control">   
                                <input type="text" name="ans[es][0]"  placeholder="write option in Spanish" value="" class="form-control">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{-- <select name="ans[price][0]" id="" class="form-control"  >
                                            @if($price_ranges)
                                            @foreach($price_ranges as $ke => $range)
                                            <option value="{{$range->id}}">{{$range->start_price}} - {{$range->end_price }} </option>
                                            @endforeach
                                            @endif 
                                        </select> --}}
                                        <input type="text" name="ans[price][0]"  placeholder="Amount" value="" class="form-control"> 
                                    </div>
                                    <div class="col-md-4"><input type="text" name="ans[factor][0]"   placeholder="Percentage %" value="" class="form-control"> </div>

                                    <!-- <div class="col-md-4">
                                         <input type="text" name="ans[quantity][0]"  placeholder="quantity" value="" class="form-control">
                                    </div> -->
                                </div>
                            </div>                         
                        </div><!--col-->
                        
                        <div class="bottom-btn"><button type="button" class="add-mpre-btn" id="add_more"><i class="fa fa-plus"></i> Add More</button></div>
                    
                    </div><!--form-group-->

                    

                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.questions.index'), __('buttons.general.cancel')) }}
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
        
        $("#question_div").show();
        $("#option_div").hide();

        $("#related_question_div").hide();
        $("#related_option_div").hide();

        var category_id = $("#category_id").val();
        getServices(category_id);
       
    });

    function getServices(val) {           
        $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/questions/getServices") !!}',
            data:'category_id='+val,
            success: function(data){
                $("#service-list").html(data.html);
                var is_related = $('input[name=is_related]:checked', '#question-form').val();
                
                if(is_related == 'Yes') {

                    $("#no_related").prop("checked", true);
                    $("#related_question_div").hide();
                    $("#related_option_div").hide();
                }
                
                var services_id = $("#services_id").val();               
                getSubservices(services_id);                 
            }
        });
    }

    function getSubservices(val) {            
        $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/questions/getSubservices") !!}',
            data:'services_id='+val,
            success: function(data){
                $("#subservice-list").html(data.html);
                var is_related = $('input[name=is_related]:checked', '#question-form').val();
                
                if(is_related == 'Yes') {

                    $("#no_related").prop("checked", true);
                    $("#related_question_div").hide();
                    $("#related_option_div").hide();
                }
                var subservices_id = $("#sub_services_id").val();
                getChildSubservices(subservices_id);
            }
        });
    }

    function getChildSubservices(val) {        
        $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/questions/getChildSubservices") !!}',
            data:'sub_services_id='+val,
            success: function(data){
                
                $("#childsubservice-list").html(data.html);
                var is_related = $('input[name=is_related]:checked', '#question-form').val();
                
                if(is_related == 'Yes') {

                    $("#no_related").prop("checked", true);
                    $("#related_question_div").hide();
                    $("#related_option_div").hide();
                }                
                
            }
        });
    }

    $('#add_more').click(function(){ 
       
        var added_input = $(".ans").length; 
              
        $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/questions/getOptionView") !!}',
            data:'added_input='+added_input,
            success: function(data){                
                $('#append_rows').append(data.html);
            }
        });
    });

    function removeRow($this) {

        $($this).parent('div').remove();
    }

    
    $('input[type=radio][name=is_related]').change(function() {
        if (this.value == 'Yes') {           
            var service_id = $('#services_id').val(); 
            var sub_service_id = $('#sub_services_id').val();
            getRelatedQuestions(service_id,sub_service_id);
        }
        else if (this.value == 'No') {            
            $("#related_question_div").hide();
            $("#related_option_div").hide();
        }
    });

    

    function getRelatedQuestions(service_id, sub_services_id) {         
        $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/questions/getRelatedQuestions") !!}',
            data:{services_id:service_id,sub_services_id:sub_services_id},
            success: function(data){
                if(data.html!='')
                {
                    $("#related_question_div").show();
                    $("#related_question_list").html(data.html);

                }
                else
                {
                    $("#related_question_div").hide();
                }
               
                var question_id = $('#related_question_id').val();               
                getRelatedOptions(question_id);
               
            }
        });
    }

    

    function getRelatedOptions(question_id) {          
        $.ajax({
            type: "GET",
            url: '{!! URL::to("admin/questions/getRelatedOptions") !!}',
            data:{question_id:question_id},
            success: function(data){
               if(data.html!='')
               {
                    $("#related_option_div").show();
                    $("#related_option_list").html(data.html); 
                }
                else
                {
                    $("#related_option_div").hide();
                    $("#related_question_div").hide(); 
                }
                             
               
            }
        });
    }

    $('#question-type-list').change(function() {

       var question_type = $('#question-type-list').val();
       if(question_type == 'radio' || question_type == 'checkbox' || question_type == 'select'){
            $("#question_div").show();
            $("#option_div").show();
       } else {
            $("#question_div").show();
            $("#option_div").hide();
       }
       
    });
</script>

@endsection
