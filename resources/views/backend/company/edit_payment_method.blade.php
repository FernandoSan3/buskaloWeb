@extends('backend.layouts.app')

@section('title', __('labels.backend.comapny.management') . ' | ' . __('labels.backend.company.create'))

@section('content')

{{ html()->form('POST', route('admin.company.update_payment_method'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<input type="hidden" name="user_id" value="{{$userId}}">
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Company Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.questions.create') --> Edit Payment Method</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">
                    <div id="citiesArea" class="form-group row">
                        <label class="col-md-2 form-control-label"> <label>Select Payment Methods</label></label>
                        <div >
                         <select name="payment_method_id[]" id="cities" multiple="">
                          <?php 
                            if(isset($paymentMethods) && !empty($paymentMethods)) {
                              foreach ($paymentMethods as $k => $v_menthod) {
                          ?>
                            <option <?php if(in_array($v_menthod->id, $user_pay_menthod_ids)){ ?> selected <?php } ?> value="{{$v_menthod->id}}">{{$v_menthod->name_es}}</option>
                          <?php } } ?>
                        </select>                         
                        </div>
                    </div>


                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.company.index'), __('buttons.general.cancel')) }}
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
       
    });

   
</script>

<style>
.close:not(:disabled):not(.disabled) {
    cursor: pointer;
    margin-top: -25px;
}
</style>

@endsection
