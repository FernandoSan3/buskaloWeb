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
                     @lang('labels.backend.other.applications_management')
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
                            <th> @lang('labels.backend.access.users.table.id') </th>
                            <th> @lang('labels.backend.access.users.table.username') </th>
                            <th> @lang('labels.backend.access.users.table.email') </th>
                            <th> @lang('labels.backend.access.users.table.mobile') </th>
                            <th> @lang('labels.general.actions.actions') </th>
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
                                        @lang('labels.general.actions.actions')
                                      </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if($user->user_group_id==3)
                                            <a class="dropdown-item" href="{{ route('admin.contractors.show',$user->id) }}"> @lang('labels.general.actions.show_profile') </a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.edit',$user->id) }}"> @lang('labels.general.actions.edit') </a>
                                             <a class="dropdown-item" href="{{ route('admin.contractors.edit_payment_method',$user->id) }}">  @lang('labels.general.actions.edit_payment_method') </a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_certificates',$user->id) }}"> @lang('labels.general.actions.all_contractor_certificates') </a>
                                            <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_images_gallery',$user->id) }}"> @lang('labels.general.actions.all_contractor_images_gallery') </a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_videos_gallery',$user->id) }}"> @lang('labels.general.actions.all_contractor_videos_gallery') </a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_police_records',$user->id) }}"> @lang('labels.general.actions.all_contractor_police_records') </a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.show_services_offered',$user->id) }}"> @lang('labels.general.actions.show_services_offered') </a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.edit_coverage_area',$user->id) }}"> @lang('labels.general.actions.edit_coverage_area') </a>
                                            <a class="dropdown-item" href="{{ route('admin.contractors.destroy',$user->id) }}"> @lang('labels.general.actions.destroy') </a>

                                            <a class="dropdown-item" href="{{ route('admin.contractors.serviceRequests',$user->id) }}"> @lang('labels.general.actions.serviceRequests') <span class="badge" style="background-color: #007bff;color: white;
                                            ">{{ $user->total_service_requests}}
                                            </span></a>
                                        @else
                                            <a class="dropdown-item" href="{{ route('admin.company.show',$user->id) }}">  @lang('labels.general.actions.show_profile') </a>
                                            <a class="dropdown-item" href="{{ route('admin.company.edit',$user->id) }}">  @lang('labels.general.actions.edit') </a>
                                             <a class="dropdown-item" href="{{ route('admin.company.edit_payment_method',$user->id) }}">  @lang('labels.general.actions.edit_payment_method') </a>
                                            <a class="dropdown-item" href="{{ route('admin.company.all_company_certificates',$user->id) }}"> @lang('labels.general.actions.all_contractor_certificates') </a>
                                            <a class="dropdown-item" href="{{ route('admin.company.all_company_images_gallery',$user->id) }}"> @lang('labels.general.actions.all_contractor_images_gallery') </a>

                                            <a class="dropdown-item" href="{{ route('admin.company.all_company_videos_gallery',$user->id) }}"> @lang('labels.general.actions.all_contractor_videos_gallery') </a>

                                            <a class="dropdown-item" href="{{ route('admin.company.all_company_police_records',$user->id) }}"> @lang('labels.general.actions.all_contractor_police_records') </a>

                                            <a class="dropdown-item" href="{{ route('admin.company.show_services_offered',$user->id) }}"> @lang('labels.general.actions.show_services_offered') </a>

                                            <a class="dropdown-item" href="{{ route('admin.company.edit_coverage_area',$user->id) }}"> @lang('labels.general.actions.edit_coverage_area') </a>
                                            <a class="dropdown-item" href="{{ route('admin.company.destroy',$user->id) }}"> @lang('labels.general.actions.destroyC')</a>
                                            <a class="dropdown-item" href="{{ route('admin.company.serviceRequests',$user->id) }}"> @lang('labels.general.actions.serviceRequests') <span class="badge" style="background-color: #007bff;color: white;">{{ $user->total_service_requests}}
                                            </span></a>
                                        @endif

                                            <a class="dropdown-item" href="{{ route('admin.aplicacions.accept',$user->id) }}"> @lang('labels.general.actions.accept') </a>

                                            <a class="dropdown-item" href="{{ route('admin.aplicacions.decline',$user->id) }}"> @lang('labels.general.actions.reject') </a>
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
