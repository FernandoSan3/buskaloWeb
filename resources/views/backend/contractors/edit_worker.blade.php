@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')

{{ html()->form('POST', route('admin.contractors.update_worker'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<input type="hidden" name="worker_id" value="{{$worker_id}}">



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
                          <label class="col-md-2 form-control-label">Username</label>

                            <div class="col-md-10">
                                {{ html()->text('username')
                                    ->class('form-control')
                                    ->placeholder('username')
                                    ->attribute('maxlength', 191)
                                    ->value($worker_details->username)
                                    ->required()
                                    ->autofocus() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        
                        
                        <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label"> Mobile Number</label>

                            <div class="col-md-10">
                                {{ html()->text('mobile_number')
                                    ->class('form-control')
                                    ->placeholder('mobile number')
                                    ->value($worker_details->mobile_number)
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
                                    ->value($worker_details->address)
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Profile Picture</label>
                            <div class="col-md-10">
                                <input type="file" name="profile_pic" accept="image/jpg, image/jpeg" >
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Document</label>
                            <div id="append_rows">           
                                <input type="file" class="doc_name document_name" name="doc_name[]" multiple="" accept="application/pdf,image/jpeg" >
                                
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
   

</script>
@endsection
