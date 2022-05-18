@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.company.management'))

@section('content')
<style>
tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>

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
                    <!-- @lang('labels.backend.company.management') -->
                    Company Management
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.company.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table  id="example" class="table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>username</th>
                            <th>@lang('labels.backend.access.users.table.email')</th>
                            <th>Mobile</th>

                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $key => $user)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile_number }}</td>

                                <td class="btn-td">
                                    <div class="dropdown">
                                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                      </button>
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                        <a class="dropdown-item" href="{{ route('admin.company.show',$user->id) }}">show</a>

                                        <a class="dropdown-item" href="{{ route('admin.company.edit',$user->id) }}">Edit</a>


                                        <a class="dropdown-item" href="{{ route('admin.company.edit_payment_method',$user->id) }}">Edit Payment Method</a>


                                        {{-- <a class="dropdown-item" href="{{ route('admin.contractors.add_contractor_documents',$user->id) }}">Add Documents</a>
                                        <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_documents',$user->id) }}">All Documents</a> --}}
                                        {{-- <a class="dropdown-item" href="{{ route('admin.contractors.add_contractor_certificate',$user->id) }}">Add Certificates</a> --}}

                                        <a class="dropdown-item" href="{{ route('admin.company.all_company_certificates',$user->id) }}">All Certificates</a>

                                        {{-- <a class="dropdown-item" href="{{ route('admin.contractors.add_contractor_gallery',$user->id) }}">Add Gallery</a> --}}
                                        <a class="dropdown-item" href="{{ route('admin.company.all_company_images_gallery',$user->id) }}">Image Gallery</a>

                                        <a class="dropdown-item" href="{{ route('admin.company.all_company_videos_gallery',$user->id) }}">Video Gallery</a>

                                        <a class="dropdown-item" href="{{ route('admin.company.all_company_police_records',$user->id) }}">All Police Records</a>

                                        <a class="dropdown-item" href="{{ route('admin.company.show_services_offered',$user->id) }}"> Services</a>

                                        {{--<a class="dropdown-item" href="{{ route('admin.company.add_services_offered',$user->id) }}">Add Services</a>

                                         <a class="dropdown-item" href="{{ route('admin.company.edit_services_offered',$user->id) }}">Edit Services</a>--}}

                                        <a class="dropdown-item" href="{{ route('admin.company.edit_coverage_area',$user->id) }}">Edit Coverage Area</a>
                                         <a class="dropdown-item" href="{{ route('admin.company.all_workers',$user->id) }}">All worker</a>
                                        <a class="dropdown-item" href="{{ route('admin.company.destroy',$user->id) }}">Delete Company</a>
                                         <a class="dropdown-item" href="{{ route('admin.company.creditpackage',$user->id) }}">Credit Package</a>

                                        <a class="dropdown-item" href="{{ route('admin.company.serviceRequests',$user->id) }}">Service Request<span class="badge" style="background-color: #007bff;color: white;
                                       ">{{ $user->total_service_requests}}

                                        </span></a>
                                        <a class="dropdown-item" href="{{ route('admin.company.payment',$user->id) }}">Payment Information</a>
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
