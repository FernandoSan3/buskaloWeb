@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')

{{ html()->form('POST', route('admin.contractors.store_contractor_documents'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<input type="hidden" name="user_id" value="{{$user_id}}">



    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Contractor Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.questions.create') --> Add Document</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> Document</label>
                        <div id="append_rows">           
                            <input type="file" class="doc_name document_name" name="doc_name[]" multiple="" accept="application/pdf,image/jpeg,,image/jpg" required="">                            
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
   

</script>
@endsection
