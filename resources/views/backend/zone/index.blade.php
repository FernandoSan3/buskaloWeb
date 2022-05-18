@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.zone.management'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    @lang('labels.backend.zone.management')
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.zone.includes.header-buttons')
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
                    <table id="example12" class="table table-striped table-bordered dt-responsive">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.zone.table.id')</th>
                            <th> @lang('labels.backend.zone.table.title')</th>
                            <th> @lang('labels.backend.zone.table.address')</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($zones as $key => $zone)
                                <tr>
                                    <td>{{ $zones->firstItem() + $key }}</td>
                                    <td>{{ $zone->title }}</td>
                                    <td>{{ $zone->address }}</td>
                                    <td>
                                       <form action="{{ route('admin.zone.destroy',$zone->id) }}" method="POST">
                                            
                                            <a class="btn btn-primary" href="{{ route('admin.zone.remove',$zone->id) }}">Remove existing</a>
                                           
                                            <a class="btn btn-secondary" href="{{ route('admin.zone.edit',$zone->id) }}">Add More</a>
   
                                            @csrf
                                            @method('DELETE')
                              
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $zones->render() !!}
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
