@extends('backend.layouts.app')
 
@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> Show Services</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('admin.services.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>English Name:</strong>
                {{ $services->en_name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
               <strong>Spanish Name:</strong>
                {{ $services->es_name }}
            </div>
        </div>
    </div>
@endsection