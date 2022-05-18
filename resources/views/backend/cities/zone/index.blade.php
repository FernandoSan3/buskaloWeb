@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.cities.management'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    @lang('labels.backend.cities.management')
                    <small>{{ $polygon_page_title }}</small>
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.cities.zone.includes.header-buttons')
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
                <div class="table-responsive2">
                    <table id="example12" class="table table-striped table-bordered dt-responsive">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.zone.table.id')</th>
                            <th> Area Type</th>
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
                                    <td>{{ $zone->area_type }}</td>
                                    <td>{{ $zone->address }}</td>
                                    <td>
                                       <form action="{{ route('admin.cities.destroy_zone',$zone->id) }}" method="POST">
                                           
                                            <a class="btn btn-secondary" href="{{ route('admin.cities.add_more_polygon',$zone->id) }}">Add More</a>

                                              <a class="btn btn-primary" href="{{ route('admin.cities.remove',$zone->id) }}">Remove existing</a>
   
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
