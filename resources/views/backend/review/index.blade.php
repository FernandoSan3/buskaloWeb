@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.review.management'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    @lang('labels.backend.review.management')
                </h4>
            </div><!--col-->

           {{-- <div class="col-sm-7 pull-right">
                @include('backend.service_requests.includes.header-buttons')
            </div><!--col-->--}}
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="example">
                        <thead>
                         <tr>
                            <th> @lang('labels.backend.review.table.id')</th>
                             <th> @lang('labels.backend.review.table.User Name')</th>
                             <th> @lang('labels.backend.review.table.Provider Name')</th>
                             <th> @lang('labels.backend.review.table.Mobile Number')</th>
                              <th>@lang('Approval Status')</th>
                             <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(!empty($review_datas))
                           
                            @foreach ($review_datas as $key => $request)
                            
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $request->username }}</td>
                                    <td>{{ $request->provider_name }}</td>
                                    <td>{{ $request->mobile_number }}</td>
                                    <td>
                                     @if($request->admin_appovel==0)
                                        <a href="{{url('admin/review/status/'.$request->id)}}"><button class="btn btn-danger"> Inactive</button></a>
                                        @else
                                         <a href="{{url('admin/review/status/'.$request->id)}}"><button class="btn btn-success"> Active</button></a>
                                        @endif
                                    </td>

                                     <td>
                                       <form action="{{ route('admin.questions.destroy',$request->id) }}" method="POST">

                                            <a class="btn btn-info" href="{{ route('admin.review.show',$request->id) }}">Show</a>
                                            @csrf
                                            @method('DELETE')

                                        </form>                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                 {!! $review_datas->render() !!}

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
