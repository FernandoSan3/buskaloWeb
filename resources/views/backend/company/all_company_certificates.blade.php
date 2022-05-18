@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.company.management'))

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
                    <!-- @lang('labels.backend.contractors.management') -->
                    Company Management
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
               {{--  @include('backend.contractors.includes.header-buttons') --}}

                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">

                    <a href="{{ route('admin.company.index') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="companies list"><i class="fas fa-list"></i></a>

                    <a href="{{ route('admin.company.add_company_certificate',$user_id) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
                </div>
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Is Verified</th>
                            <th>Document</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                     <?php
                             if(isset($cetifications) && count($cetifications)>0) {?>
                        @foreach($cetifications as $key => $document)

                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                <?php
                                    if($document['is_verified'] == 1) {
                                ?>
                                        <a href="{{route('admin.company.verify_certificate',$document['id'])}}"><button type="button"  class="notification_b btn btn-success">Verified</button></a>
                                <?php
                                    } else {
                                ?>
                                        <a href="{{route('admin.company.verify_certificate',$document['id'])}}"><button type="button" id="" class="notification_b btn btn-danger">Not Verified</button></a>
                                <?php }
                                ?>



                                </td>
                                <td>
                                    <?php
                                        if($document['file_type'] == 0){
                                    ?>

                                        <a href="{{ $document['file_name'] }}"><img style="height: 50px;width: 70px;" src="{{ $document['file_name'] }}"></a>
                                    <?php   } else {
                                    ?>
                                        <a href="{{ $document['file_name'] }}"> <img style="height: 50px;width: 70px;" src="{{ url('img/frontend/file_icon.png') }}"> </a>
                                    <?php
                                       }
                                    ?>
                                </td>
                                <td>
                                    <a href="{{route('admin.company.delete_certificate',$document['id'])}}"><button type="button"  class="notification_b btn btn-danger">Delete</button></a>
                                </td>

                            </tr>
                        @endforeach
                         <?php
                                } else {
                            ?>
                            <tr><td colspan="6"><center>No Certificates  found </center></td></tr>
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
<style>
.close:not(:disabled):not(.disabled) {
    cursor: pointer;
    margin-top: -25px;
}
</style>
@endsection
