@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('Refund Management'))

@section('content')

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
                    Refund Management                 
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
               {{-- @include('backend.faq.includes.header-buttons')--}}
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered dt-responsive  question-table">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.services.table.id') </th>
                            <th> @lang('User Name') </th>
                            <th> @lang('Email') </th>
                            <th> @lang('Reason') </th>
                            <th> @lang('Amount $') </th>
                            <th> @lang('Transaction Id') </th>
                            <th>Status</th>
                            <th>Refund Date</th>
                            <th>Payment Date</th>
                            <th>Action</th>
                            <!-- <th>@lang('labels.general.actions')</th> -->
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($refunddata as $key => $refund)
                                <tr>
                                    <td>{{$key+1 }}</td>
                                    <td>{{ $refund->name }}</td>
                                    <td>{{ $refund->email }}</td>
                                    <td>{{ $refund->refund_resion }}</td>
                                    <td>{{ $refund->pro_amount}}</td>
                                    <td>{{ $refund->transaction_id}}</td>
                                    <td>{{$refund->refund_status}}</td>
                                    <td>{{isset($refund->refund_date)?$refund->refund_date:'--'}}</td>
                                    <td>{{$refund->payment_date}}</td>
                                   <td class="btn-td">
                                    <div class="dropdown">
                                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                      </button>
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('admin.refund.accept',$refund->id) }}">Accept</a>
                                        @if($refund->refund_status!='Processed')
                                         <a class="dropdown-item" href="{{ route('admin.refund.reject',$refund->id) }}">Reject</a>
                                        @endif
                                      </div>
                                    </div>

                                </td>
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

<style>
.close:not(:disabled):not(.disabled) {
    cursor: pointer;
    margin-top: -25px;
}
</style>

@endsection
