@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.questions.management'))

@section('content')
<style type="text/css">
    .click-zoom input[type=checkbox] {
  display: none
}

.click-zoom img {
  transition: transform 0.25s ease;
  cursor: zoom-in;
  position: absolute;
}

.click-zoom input[type=checkbox]:checked~img {
  transform: scale(4);
  cursor: zoom-out
}
</style>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    <!-- @lang('labels.backend.contractors.management') -->
                    Contractor Management
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
               {{--  @include('backend.contractors.includes.header-buttons') --}}

                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">

                    <a href="{{ route('admin.contractors.add_contractor_gallery',$userId) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.create_new')"><i class="fas fa-plus-circle"></i></a>
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
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Document</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($gallery) && count($gallery) > 0) { ?>
                        @foreach($gallery as $key => $document)

                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    <?php
                                        if($document['file_type'] == 0){
                                    ?>
                                        <a href="{{ $document['file_name'] }}" target="_blank"><img style="height: 50px;width: 70px;" src="{{ $document['file_name'] }}"></a>
                                    <?php   } else {
                                    ?>
                                        <a href="{{ $document['file_name'] }}" target="_blank"> <img style="height: 50px;width: 70px;" src="{{ url('img/frontend/file_icon.png') }}"> </a>
                                    <?php
                                       }
                                    ?>


                                </td>

                                <td>
                                    <a href="{{route('admin.contractors.delete_gallery_image',$document['id'])}}"><button type="button"  class="notification_b btn btn-danger">Delete</button></a>
                                </td>

                            </tr>
                        @endforeach
                        <?php
                                } else {
                            ?>
                            <tr><td colspan="6"><center>No Images found </center></td></tr>
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
