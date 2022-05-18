  @extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.service_request.management'))

@section('content')
<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-sm-5">
        <h4 class="card-title mb-0">User Service Request</h4>
      </div><!--col-->

      <div class="col-sm-7 pull-right">
        @include('backend.auth.user.includes.service_requeststaus-buttons')
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.service_request.table.id')</th>
                             <th> @lang('labels.backend.service_request.table.username')</th>
                            <th>@lang('labels.backend.service_request.table.category_name')</th>

                            <th> <!-- @lang('labels.backend.questions.table.es_service_name') -->Service Name</th>
                            <th>Mobile No.</th>

                            <th>@lang('labels.general.actions')</th>
                           </tr>
                          </thead>
                           <tbody>
                            <?php
                            if(isset($service_requests) && count($service_requests)>0) {
                            foreach ($service_requests as $key => $request) {
                            ?>
                              <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ $request->username }}</td>
                                <td>{{ $request->es_category_name }}</td>
                                <td>{{ $request->es_service_name }}</td>
                                <td>{{ $request->mobile_number }}</td>
                                <td>
                                <a href="{{ route('admin.auth.user.show_service_request', $request->id) }}"  class="btn btn-info">
                                        show  request
                                 </a>
                                 </td>
                                </tr>
                                <?php
                                  } } else {
                                ?>
                                <tr><td colspan="6"><center>Not Found Any Service Request Yet</center></td></tr>
                                 <?php    }
                                ?>

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
@endsection
