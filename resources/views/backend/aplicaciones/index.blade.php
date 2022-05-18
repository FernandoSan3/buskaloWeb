@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.contractor.management'))

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
                     @lang('Aplicaciones Management')
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                
            </div>
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example" class="table">
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
                                        @if($user->user_group_id==3)
                                            <a class="dropdown-item" href="{{ route('admin.contractors.show',$user->id) }}">Show Profile</a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.edit',$user->id) }}">Edit</a>
                                             <a class="dropdown-item" href="{{ route('admin.contractors.edit_payment_method',$user->id) }}">Edit Payment Method</a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_certificates',$user->id) }}">All Certificates</a>
                                            <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_images_gallery',$user->id) }}">Image Gallery</a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_videos_gallery',$user->id) }}">Video Gallery</a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_police_records',$user->id) }}">All Police Records</a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.show_services_offered',$user->id) }}"> Services</a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.edit_coverage_area',$user->id) }}">Edit Coverage Area</a>
                                            <a class="dropdown-item" href="{{ route('admin.contractors.destroy',$user->id) }}">Delete Contractor</a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.serviceRequests',$user->id) }}">Servicio Solicitudes<!-- Service Request --><span class="badge" style="background-color: #007bff;color: white;
                                            ">{{ $user->total_service_requests}}
                                            </span></a>
                                        @else
                                            <a class="dropdown-item" href="{{ route('admin.company.show',$user->id) }}">show</a>
                                            <a class="dropdown-item" href="{{ route('admin.company.edit',$user->id) }}">Edit</a>
                                             <a class="dropdown-item" href="{{ route('admin.company.edit_payment_method',$user->id) }}">Edit Payment Method</a>
                                            <a class="dropdown-item" href="{{ route('admin.company.all_company_certificates',$user->id) }}">All Certificates</a>
                                            <a class="dropdown-item" href="{{ route('admin.company.all_company_images_gallery',$user->id) }}">Image Gallery</a>

                                            <a class="dropdown-item" href="{{ route('admin.company.all_company_videos_gallery',$user->id) }}">Video Gallery</a>

                                            <a class="dropdown-item" href="{{ route('admin.company.all_company_police_records',$user->id) }}">All Police Records</a>

                                            <a class="dropdown-item" href="{{ route('admin.company.show_services_offered',$user->id) }}"> Services</a>

                                            <a class="dropdown-item" href="{{ route('admin.company.edit_coverage_area',$user->id) }}">Edit Coverage Area</a>
                                            <a class="dropdown-item" href="{{ route('admin.company.destroy',$user->id) }}">Delete Company</a>
                                            <a class="dropdown-item" href="{{ route('admin.company.serviceRequests',$user->id) }}">Service Request<span class="badge" style="background-color: #007bff;color: white;">{{ $user->total_service_requests}}
                                            </span></a>
                                        @endif

                                            <a class="dropdown-item" href="{{ route('admin.aplicacions.accept',$user->id) }}">Accept</a>

                                            <a class="dropdown-item" href="{{ route('admin.aplicacions.decline',$user->id) }}">Reject</a>
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
