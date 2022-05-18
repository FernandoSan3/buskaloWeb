@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.questions.management'))

@section('content')
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
                @include('backend.contractors.includes.all-contractors-button')
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
                    <table id="example" class="table table-striped table-bordered">
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
                                <td>@include('backend.contractors.includes.actions', ['user' => $user])</td>
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
@endsection
