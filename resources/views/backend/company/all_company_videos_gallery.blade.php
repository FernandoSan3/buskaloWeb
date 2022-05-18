@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.company.management'))

@section('content')
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
                {{-- @include('backend.contractors.includes.header-buttons') --}}
                 <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                     <a href="{{ route('admin.company.index') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="companies list"><i class="fas fa-list"></i></a>

                    <a href="{{ route('admin.company.add_company_gallery',$userId) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
                </div>
            </div><!--col-->
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
                            <th>Id</th>
                            <th>Document</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                       <?php


                       if(isset($gallery) && count($gallery) > 0) { ?>
                        @foreach($gallery as $key => $document)

                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>

                                    <video width="150" height="100px"  controls >
                                        <source src="{{ $document['file_name'] }}" type="video/mp4">
                                    </video>
                                </td>
                                <td>
                                    <a href="{{route('admin.company.delete_gallery_video',$document['id'])}}"><button type="button"  class="notification_b btn btn-danger">Delete</button></a>
                                </td>

                            </tr>
                        @endforeach
                        <?php } else { ?>
                       <tr><td colspan="6"><center>No Video found </center></td></tr>
                        <?php } ?>

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
