

@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')

{{ html()->form('POST', route('admin.contractors.update_services_offered'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}


<input type="hidden" name="user_id" value="{{$user_id}}">

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Contractor Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.questions.create') --> Edit Service</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Services</label>
                        <div id="services" class="col-md-10">
                            <ul class="area-list meta-list multi-cities-list ">
                              <select name="services[]" id="multi-select-services" multiple="multiple">
                                   <?php
                                  if(!empty($combinedData))
                                  {
                                      foreach ($combinedData as $key_com => $val_com)
                                      { ?>
                                          <option data-serv="{{$val_com['id']}}" class="parent_city parent_option pa_op_{{$val_com['id']}}" value="{{$val_com['id']}}"  @if(in_array($val_com['id'],$service_ids)) selected @endif>{{$val_com['name']}}</option>

                                        <?php $i=1; foreach ($val_com['subservices'] as $sdata)
                                        {
                                        ?>
                                            <option data-serv="{{$val_com['id']}}" class="child_city child_option ch_op_{{$val_com['id']}}" value="<?php echo $val_com['id'].','.$sdata['sub_service_id']?>" @if(in_array($sdata['sub_service_id'],$sub_service_ids))  selected @endif>{{$sdata['name']}}</option>

                                     <?php $i++;} }
                                  }
                                  ?>
                                </select>
                            </ul>
                        </div>
                    </div>

                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.contractors.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
$(document).ready(function() {
    $('#multi-select-services').multiselect({
        enableCaseInsensitiveFiltering: true,
        filterBehavior: 'text',
        onChange: function(option, checked, select) {

            var value_arr = [];
            var is_parent = $(option).hasClass('parent_option');
            var parent_id = $(option).attr('data-serv');

            if(checked == true) {
                if(is_parent == true){
                    var selected_element = '.ch_op_'+parent_id;
                    $(selected_element).show();
              var total_length = $(selected_element).length;
              $(selected_element).each(function( index ) {
                if(index < total_length/2 ){
                  value_arr.push($( this ).val());
                }
              });

              $('#multi-select-services').multiselect('select', value_arr);


                } else {

                }

            } else{
                if(is_parent == true) {

                    var selected_element = '.ch_op_'+parent_id;
              var total_length = $(selected_element).length;
              $(selected_element).each(function( index ) {
                if(index < total_length/2 ){
                  value_arr.push($( this ).val());
                }
              });
              $('#multi-select-services').multiselect('deselect', value_arr);
                    $(selected_element).hide();

                } else {
              var selected_element = '.ch_op_'+parent_id;
              var total_length = $(selected_element).length;

              if(total_length/2 == 1) {
                $('#multi-select-services').multiselect('deselect', parent_id);
                $(selected_element).hide();
              }else{

                //var selected_text = '.parent_prov_'+prov_id;
                var cou = 0;
                var selected_child_text_new = 'ch_op_'+parent_id;
                $('li.active').each(function(){
                  if($(this).hasClass('active') == true && $(this).hasClass(selected_child_text_new) == true ){
                      cou++;
                  }
                });

                if(cou == 0){
                  $('#multi-select-services').multiselect('deselect', parent_id);
                  $(selected_element).hide();
                } else {

                }

              }

                }
            }

        },
        onInitialized: function(select, container) {

            //$('.child_option').hide();
        }
    });});

</script>
@endsection
