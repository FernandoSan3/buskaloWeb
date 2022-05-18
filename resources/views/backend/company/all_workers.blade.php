@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.company.management'))

@section('content')
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
              {{--   @include('backend.company.includes.header-buttons') --}}
               <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                  <a href="{{ route('admin.company.index') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="companies list"><i class="fas fa-list"></i></a>

                <a href="{{ route('admin.company.create_worker',$user_id) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="create new worker"><i class="fas fa-plus-circle"></i></a>
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
                            <th>Username</th>
                            <th>Email</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                         if(isset($all_workers) && count($all_workers)>0){?>
                           @foreach($all_workers as $key => $worker)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $worker->username }}</td>
                                <td>{{ $worker->email }}</td>
                                <td>
                                    <form action="{{ route('admin.company.destroy_worker',$worker->id) }}" method="POST">

                                        <a class="btn btn-info" href="{{ route('admin.company.view_worker',$worker->id) }}">Show</a>

                                        <a class="btn btn-primary" href="{{ route('admin.company.edit_worker',$worker->id) }}">Edit</a>

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                              <?php
                                } else {
                            ?>
                            <tr><td colspan="6"><center>No Record found </center></td></tr>
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
