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
                     @lang('labels.backend.contractor.management_n')
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                 @include('backend.contractors.includes.create-contractors-button')
                @include('backend.contractors.includes.deleted-contractors-buttons')
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
                            <th> @lang('labels.backend.access.users.table.profession') </th>
                            <th> @lang('labels.backend.access.users.table.coins') </th>
                            <th> @lang('labels.general.actions1.actions') </th>                            
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($users as $key => $user)

                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile_number }}</td>
                                <td>{{ $user->profile_title }}</td>
                                <td>{{ $user->pro_credit }}</td>

                                <td class="btn-td">
                                    <div class="dropdown">
                                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('labels.general.actions1.actions')
                                      </button>
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                      <a class="dropdown-item" href="{{ route('admin.contractors.show',$user->id) }}"> @lang('labels.general.actions1.show_profile') </a>

                                        <a class="dropdown-item" href="{{ route('admin.contractors.edit',$user->id) }}"> @lang('labels.general.actions1.edit') </a>


                                        <a class="dropdown-item" href="{{ route('admin.contractors.edit_payment_method',$user->id) }}">  @lang('labels.general.actions1.edit_payment_method') </a>

                                        <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_certificates',$user->id) }}"> @lang('labels.general.actions1.all_contractor_certificates') </a>

                                        {{-- <a class="dropdown-item" href="{{ route('admin.contractors.add_contractor_gallery',$user->id) }}">Add Gallery</a> --}}
                                        <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_images_gallery',$user->id) }}"> @lang('labels.general.actions1.all_contractor_images_gallery') </a>

                                        <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_videos_gallery',$user->id) }}"> @lang('labels.general.actions1.all_contractor_videos_gallery') </a>

                                        <a class="dropdown-item" href="{{ route('admin.contractors.all_contractor_police_records',$user->id) }}"> @lang('labels.general.actions1.all_contractor_police_records') </a>

                                         <a class="dropdown-item" href="{{ route('admin.contractors.show_services_offered',$user->id) }}"> @lang('labels.general.actions1.show_services_offered') </a>

                                        {{--<a class="dropdown-item" href="{{ route('admin.contractors.add_services_offered',$user->id) }}">Add Services</a>
                                        <a class="dropdown-item" href="{{ route('admin.contractors.edit_services_offered',$user->id) }}">Edit Services</a>--}}

                                        <a class="dropdown-item" href="{{ route('admin.contractors.edit_coverage_area',$user->id) }}"> @lang('labels.general.actions1.edit_coverage_area') </a>
                                        <a class="dropdown-item" href="{{ route('admin.contractors.ratings_reviews',$user->id) }}"> @lang('labels.general.actions1.ratings_reviews') </a>
                                        <a class="dropdown-item" href="{{ route('admin.contractors.destroy',$user->id) }}"> @lang('labels.general.actions1.destroy') </a>
                                        <a class="dropdown-item" href="{{ route('admin.contractors.creditpackage',$user->id) }}"> @lang('labels.general.actions1.creditpackage') </a>

                                        <a class="dropdown-item" href="{{ route('admin.contractors.serviceRequests',$user->id) }}"> @lang('labels.general.actions1.serviceRequests') <!-- Service Request --><span class="badge" style="background-color: #007bff;color: white;
                                       ">{{ $user->total_service_requests}}
                                        </span></a>
                                        <a class="dropdown-item" href="{{ route('admin.contractors.payment',$user->id) }}"> @lang('labels.general.actions1.payment_info')</a>
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
