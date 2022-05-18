@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.view'))



@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
               <h4 class="card-title mb-0">
                    Contractor Management
                    <small class="text-muted">
                    Services Offered</small>
                </h4>
            </div><!--col-->
             <div class="col-sm-7 pull-right">
                     @include('backend.contractors.includes.add_service_button')
                  </div>
        </div><!--row-->


        <div class="row mt-4 mb-4">
            <div class="col">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-expanded="true"><i class="fas fa-user"></i> @lang('labels.backend.access.users.tabs.titles.overview')</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="overview" role="tabpanel" aria-expanded="true">
                        @include('backend.contractors.show.tabs.edit_offered_service')
                    </div><!--tab-->
                </div><!--tab-content-->
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->

    <div class="card-footer">
        <div class="row">
            <div class="col">

            </div><!--col-->
        </div><!--row-->
    </div><!--card-footer-->
</div><!--card-->
@endsection
