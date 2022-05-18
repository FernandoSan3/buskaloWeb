@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.category.management'))

@section('content')
<div class="card">
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    {{-- @lang('labels.backend.category.management') --}}
                    Categorías Principales                    
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.email_template.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example12" class="table table-striped table-bordered dt-responsive  question-table">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.services.table.id') </th>
                            <th> @lang('labels.backend.services.table.en_name') </th>
                            <th> @lang('labels.backend.services.table.es_name') </th>
                            <th>Image</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        
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
