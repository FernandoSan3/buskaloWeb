@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('Payment management'))

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
                    @lang('labels.backend.customerpaymant.management')                    
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                
            </div>
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered dt-responsive  question-table">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.customerpaymant.table.id') </th>
                            <th> @lang('labels.backend.customerpaymant.table.customer_name') </th>
                            <th> @lang('labels.backend.customerpaymant.table.amount') </th>
                            <th> @lang('labels.backend.customerpaymant.table.transaction_id') </th>
                            <th> @lang('labels.backend.customerpaymant.table.payment_status') </th>
                            <th> @lang('labels.backend.customerpaymant.table.created_at') </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($customerpay as $key => $paymetns)
                                <tr>
                                    <td>{{ $key + 1}}</td>
                                    <td>{{ isset($paymetns->username)?$paymetns->username: $paymetns->first_name}}</td>
                                    <td>${{ $paymetns->amount }}</td>
                                    <td>{{ $paymetns->trans_id }}</td>
                                    <td>
                                        @if($paymetns->status=='success')
                                        <button class="btn btn-success"> @lang('labels.backend.customerpaymant.btn-success') </button>
                                        @else
                                        <a onclick="return confirm('Are you sure want to change status?')" href="{{url('admin/customer/payment/update/'. $paymetns->id)}}"> <button class="btn btn-danger"> @lang('labels.backend.customerpaymant.btn-danger') </button></a>
                                        @endif
                                    </td>
                                     <td>{{ $paymetns->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                  
                </div>
            </div><!--col-->
        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                   
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').dataTable({
         "pageLength": 25

         // "bPaginate": true,
         // "bLengthChange": false,
         // "bFilter": true,
         // "bInfo": false,
         // "bAutoWidth": false
          });

       $('input').keyup( function() {
          table.draw();
    } );
});
</script>
@endsection
