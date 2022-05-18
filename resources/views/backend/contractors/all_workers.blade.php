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
                @include('backend.contractors.includes.header-buttons')
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
                            <th>Username</th>>
                            <th>Email</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($all_workers) && !empty($all_workers))
                        @foreach($all_workers as $key => $worker)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $worker->username }}</td>
                                <td>{{ $worker->email }}</td>
                                <td>
                                    <form action="{{ route('admin.contractors.destroy_worker',$worker->id) }}" method="POST">

                                        <a class="btn btn-info" href="{{ route('admin.contractors.view_worker',$worker->id) }}">Show</a>

                                        <a class="btn btn-primary" href="{{ route('admin.contractors.edit_worker',$worker->id) }}">Edit</a>

                                        @csrf
                                        @method('DELETE')
                          
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @endif
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
