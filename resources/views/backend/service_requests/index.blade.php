@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.service_request.management'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    @lang('labels.backend.service_request.management')
                </h4>
            </div><!--col-->

           <div class="col-sm-7 pull-right">
                @include('backend.service_requests.includes.header-buttons')
            </div>
        </div><!--row-->

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

       <div class="row mt-4">
            <div class="col">
                <form method="get" action="{{url('admin/service_request')}}">
                    <label>From Date</label>
                    <input type="date" name="from">
                     <label>To Date</label>
                    <input type="date" name="to">
                    <input type="submit" name="submit" value="Submit">
                    
                </form>
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                         <th> @lang('labels.backend.questions.table.id')</th>
                             <th>Username</th>
                            <th>Category Name</th>

                            <th> <!-- @lang('labels.backend.questions.table.es_service_name') -->Service Name</th>
                            <th>Mobile No.</th>
                            <th>City</th>
                            <th>Request's date</th>

                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($service_requests as $key => $request)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ $request->username }}</td>
                                    <td>{{ $request->es_category_name }}</td>

                                    <td>{{ $request->es_service_name }}</td>
                                    <td>{{ $request->mobile_number }}</td>
                                    <td>{{ $request->cityname }}</td>
                                    <td>{{ date('d-m-Y',strtotime($request->created_at)) }}</td>

                                    <td>
                                        <form action="{{ route('admin.service_request.destroy',$request->id) }}" method="POST">

                                            <a class="btn btn-info" href="{{ route('admin.service_request.show',$request->id) }}">Show</a>

                                            <!-- <a class="btn btn-primary" href="{{ route('admin.questions.edit',$request->id) }}">Edit</a> -->

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                        <br/>
                                         <a class="btn btn-info" href="{{ route('admin.service_request.forward',$request->id) }}">Forward</a>
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
@endsection
