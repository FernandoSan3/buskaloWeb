@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')

{{ html()->form('POST', route('admin.contractors.store_worker'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<input type="hidden" name="user_id" value="{{$user_id}}">



    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Contractor Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.questions.create') --> Create Worker</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                    
                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.first_name'))->class('col-md-2 form-control-label')->for('first_name') }}

                            <div class="col-md-10">
                                {{ html()->text('first_name')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.backend.access.users.first_name'))
                                    ->attribute('maxlength', 191)
                                    ->required()
                                    ->autofocus() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.last_name'))->class('col-md-2 form-control-label')->for('last_name') }}

                            <div class="col-md-10">
                                {{ html()->text('last_name')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.backend.access.users.last_name'))
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.email'))->class('col-md-2 form-control-label')->for('email') }}

                            <div class="col-md-10">
                                {{ html()->email('email')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.backend.access.users.email'))
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.password'))->class('col-md-2 form-control-label')->for('password') }}

                            <div class="col-md-10">
                                {{ html()->password('password')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.backend.access.users.password'))
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.password_confirmation'))->class('col-md-2 form-control-label')->for('password_confirmation') }}

                            <div class="col-md-10">
                                {{ html()->password('password_confirmation')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.backend.access.users.password_confirmation'))
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        
                        <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label"> Mobile Number</label>

                            <div class="col-md-10">
                                {{ html()->text('mobile_number')
                                    ->class('form-control')
                                    ->placeholder('mobile number')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Address</label>
                            <div class="col-md-10">
                                {{ html()->text('address')
                                    ->class('form-control')
                                    ->placeholder('address')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Profile Picture</label>
                            <div class="col-md-10">
                                <input type="file" name="profile_pic" accept="image/jpg, image/jpeg" required="">
                            </div>
                        </div>



                        

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Document</label>
                            <div id="append_rows">                                
                                <div id="row_0">                            
                                    <select name="doc[doc_type][0]" id="doc_type_0" onchange="check_type(0)" class="doc_type doc">
                                        <option >Please select</option>
                                        <option value="1">PA</option>
                                        <option value="2">AC</option>
                                        <option value="3">LI</option>
                                    </select>
                                </div>
                                
                                <input type="file" class="doc_name document_name" name="doc[doc_name][0]" accept="application/pdf,image/jpeg" required="">
                                
                            </div>
                            

                            <div><button type="button" id="add_more"> Add More</button></div>
                        </div>
                

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
       
        var added_input = $(".doc").length; 
        

        var input = '<div id="row_'+added_input+'"><select name="doc[doc_type]['+added_input+']" id="doc_type_'+added_input+'" onchange="check_type('+added_input+')" class="doc_type doc"><option value="">Please select</option><option value="1">PA</option><option value="2">AC</option><option value="3">LI</option></select><input type="file" class="doc_name document_name" name="doc[doc_name]['+added_input+']" accept="application/pdf,image/jpeg" required=""><button type="button" onclick="removeRow(this);"> Remove row</button></div>';
            $('#append_rows').append(input);

            
    });

   function check_type($row_num){

        var sel_id = '#doc_type_'+$row_num;
        var selected_option = $(sel_id).val();
        var added_input = $(".doc").length;
        var ind;
        for (ind = 0; ind < added_input; ind++) {
            var selected_id = '#doc_type_'+ind;        
            if(selected_id != sel_id) {           
                $("#doc_type_"+ind+" option[value='"+selected_option+"']").remove();
            }     
        }     

    }

    function removeRow($this) {

        $($this).parent('div').remove();
    }

</script>
@endsection
