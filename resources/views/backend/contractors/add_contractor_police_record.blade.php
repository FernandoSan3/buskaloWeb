@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')

{{ html()->form('POST', route('admin.contractors.store_contractor_police_records'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<input type="hidden" name="user_id" value="{{$user_id}}">

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Contractor Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.questions.create') --> Add Police Record</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Record Type:</label>
                        <div id="">           
                            
                            <input type="radio" name="record_type" checked="" value="0"> Image
                            <input type="radio" name="record_type" value="1"> Document                       
                        </div>
                    </div>



                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> Document</label>
                        <div class='file_upload' id='f1'>           
                          
                            <input name='police_records[]' type='file'/>                           
                        </div>

                        <div  id='file_tools'>
                          <i class="fa fa-plus-circle" id='add_file' aria-hidden="true">Add new file</i>
                          <i class="fa fa-minus-circle" id='del_file' aria-hidden="true">Delete</i>
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
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
    $(document).ready(function(){
        var counter = 2;
        $('#del_file').hide();
        $('#add_file').click(function(){
   
            $('#file_tools').before('<div class="file_upload" id="f'+counter+'"><input name="police_records[]" type="file"></div>');
            $('#del_file').fadeIn(0);
            counter++;
        });

        $('#del_file').click(function(){
            if(counter==3){
              $('#del_file').hide();
            }   
            counter--;
            $('#f'+counter).remove();
        });
    });

</script>
@endsection
