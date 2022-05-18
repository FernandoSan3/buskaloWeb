@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.districts.management'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                   @lang('labels.backend.districts.management')
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.districts.includes.header-buttons')
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
                            <th>@lang('labels.backend.districts.table.id')</th>
                            <th>@lang('labels.backend.districts.table.city_name')</th>
                            <th>@lang('labels.backend.districts.table.name')</th>
                            <th>zipcode</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($districts as $key => $district)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{ $district->city_name }}</td>
                                    <td>{{ $district->name }}</td>
                                    <td>{{ $district->zipcode }}</td>
                                    <td>
                                        <form action="{{ route('admin.districts.destroy',$district->id) }}" method="POST">
   
                                            
                                            <a class="btn btn-primary" href="{{ route('admin.districts.edit',$district->id) }}">Edit</a>
   
                                            @csrf
                                            @method('DELETE')
                              
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
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
@endsection
