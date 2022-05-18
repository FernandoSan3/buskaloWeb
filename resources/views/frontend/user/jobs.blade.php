@extends('frontend.layouts.app')

@section('content')
   
<div class="header-profile">


<div id="wrapper" class="toggled left-sidebar">
  <!-- Sidebar -->
  @include('frontend.contractor.profile_sidebar')
  <!-- /#sidebar-wrapper -->

 <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid">
      <div class="right-sidebar ">
        <!-- Tab panes -->
        <div class="tab-content">

        <div class="tab-pane active" id="trabajos">
            <div class="side-heading">
                <div class="row">
                  <div class="col-md-4">
                    <div class="head-side">
                      <h3>@lang('labels.frontend.user.account.jobs')</h3>
                    </div> 
                  </div>
                  <div class="col-md-4">
                    <div class="job-btns">
                    <button type="button" class="btn btn-success">@lang('labels.frontend.user.account.accept')</button>
                    <button type="button" class="btn btn-danger">@lang('labels.frontend.user.account.reject')</button>
                    <button type="button" class="btn btn-warning">@lang('labels.frontend.user.account.inprogress')</button>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="search-side">
                      <input type="text" name="" placeholder="@lang('labels.frontend.user.account.search')">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
            <div class="jobs-sec">
              <div class="row">
                <div class="col-md-6">
                  <div class="media job-active p-3">
                    <a href="#" class="">
                    <div class="job-icon">
                      <img src="{{ url('img/frontend/check.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="orange">@lang('labels.frontend.user.account.accepted')</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <div class="user-right">
                        <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                          <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                      </div>
                    </div>
                    </a>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media job-active job-inactive p-3">
                    <a href="#" class="">
                    <div class="job-icon">
                      <img src="{{ url('img/frontend/clocks.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="grey">@lang('labels.frontend.user.account.accepted')</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <div class="user-right">
                        <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                          <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                      </div>
                    </div>
                  </a>
                  </div>
                </div>

               <div class="col-md-6">
                  <div class="media job-active p-3">
                    <a href="#" class="">
                    <div class="job-icon">
                      <img src="{{ url('img/frontend/check.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="orange">@lang('labels.frontend.user.account.accepted')</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <div class="user-right">
                        <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                          <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                      </div>
                    </div>
                    </a>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media job-active job-inactive p-3">
                    <a href="#" class="">
                    <div class="job-icon">
                      <img src="{{ url('img/frontend/clocks.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="grey">@lang('labels.frontend.user.account.accepted')</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <div class="user-right">
                        <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                          <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                      </div>
                    </div>
                  </a>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media job-active p-3">
                    <a href="#" class="">
                    <div class="job-icon">
                      <img src="{{ url('img/frontend/check.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="orange">@lang('labels.frontend.user.account.accepted')</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <div class="user-right">
                        <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                          <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                      </div>
                    </div>
                    </a>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media job-active job-inactive p-3">
                    <a href="#" class="">
                    <div class="job-icon">
                      <img src="{{ url('img/frontend/clocks.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="grey">@lang('labels.frontend.user.account.accepted')</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <div class="user-right">
                        <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                          <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                      </div>
                    </div>
                  </a>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media job-active p-3">
                    <a href="#" class="">
                    <div class="job-icon">
                      <img src="{{ url('img/frontend/check.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="orange">@lang('labels.frontend.user.account.accepted')</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <div class="user-right">
                        <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                          <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                      </div>
                    </div>
                    </a>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="media job-active job-inactive p-3">
                    <a href="#" class="">
                    <div class="job-icon">
                      <img src="{{ url('img/frontend/clocks.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="grey">@lang('labels.frontend.user.account.accepted')</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <div class="user-right">
                        <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                          <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                      </div>
                    </div>
                  </a>
                  </div>
                </div>

                
              </div>
            </div>
          </div>


          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>

@endsection