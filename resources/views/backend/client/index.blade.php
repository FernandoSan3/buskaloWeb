@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.client.management'))

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
                    @lang('labels.backend.client.management')
                </h4>
            </div>
            <!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.client.includes.header-buttons')
            </div>
            <!--col-->
        </div>
        <!--row-->

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

                                <td class="btn-td">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            @lang('labels.general.actions1.actions')
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            <a class="dropdown-item" href="{{ route('admin.client.show',$user->id) }}"> @lang('labels.general.actions1.show_profile') </a>
                                            <a class="dropdown-item" href="{{ route('admin.client.edit',$user->id) }}"> @lang('labels.general.actions1.edit') </a>
                                            <a class="dropdown-item" href="{{ route('admin.client.destroy',$user->id) }}"> @lang('labels.general.actions1.destroyCl') </a>
                                            
                                                </span></a>
                                        </div>
                                    </div>

                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--col-->
        </div>
        <!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">

                </div>
            </div>
            <!--col-->

            <div class="col-5">
                <div class="float-right">

                </div>
            </div>
            <!--col-->
        </div>
        <!--row-->
    </div>
    <!--card-body-->
</div>
<!--card-->
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

        $('input').keyup(function() {
            table.draw();
        });
    });
</script>

<style>
    .close:not(:disabled):not(.disabled) {
        cursor: pointer;
        margin-top: -25px;
    }
</style>

@endsection