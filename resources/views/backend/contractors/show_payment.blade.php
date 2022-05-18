@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')

    <div class="card">
        <div class="card-body">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Contractor Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.questions.create') --> Show payment</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>
            
            <div class="row mt-4">
                <div class="col">
                    <div id="citiesArea" class="form-group row">
                        <label class="col-md-2 form-control-label"> <label>Total Amount</label></label>
                        <div >
                    ${{$paymentinfo->pro_credit}}                       
                        </div>
                    </div>


                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->


    </div><!--card-->


<script type="text/javascript">

    $(document).ready(function() {
       
    });

   
</script>
@endsection
