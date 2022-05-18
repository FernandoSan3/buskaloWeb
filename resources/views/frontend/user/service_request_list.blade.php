@extends('frontend.layouts.app')

@section('content')
   
<div class="header-profile">


<div id="wrapper" class="toggled left-sidebar">
  <!-- Sidebar -->
  @include('frontend.user.profile_sidebar')
  <!-- /#sidebar-wrapper -->

 <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid">
      <div class="right-sidebar ">
        <!-- Tab panes -->
        <div class="tab-content">

          <div class="tab-pane active" id="documentacion">
            <div class="side-heading">
              <div class="row">
                <div class="col-md-8">
                  <div class="head-side">
                    <h3>@lang('labels.frontend.user.account.documentation')</h3>
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
            <table id="example2" class="display nowrap contact-table" style="width:100%">
                <thead>
                    <tr>
                        <th>@lang('labels.frontend.user.account.s_no')</th>
                        <th>@lang('labels.frontend.user.account.service_name')</th>
                        <th>@lang('labels.frontend.user.account.mobile_number')</th>
                        <th>@lang('labels.frontend.user.account.location')</th>
                        {{-- <th>Archivo</th> --}}
                        <th>@lang('labels.frontend.user.account.action')</th>
                    </tr>
                </thead>
                <tbody>
                  <?php

                    if(count($service_details) > 0) {
                      foreach ($service_details as $key => $service_detail) {
                  ?>
                      
                        <tr>
                            <td>{{ $key=1 }}</td>
                            <td>{{ $service_detail->service_name }}</td>
                            <td>{{ $service_detail->mobile_number }}</td>
                            <td>{{ $service_detail->location }}</td>
                           {{--  <td><button type="button" class="btn sele-btn">Seleccionar</button></td> --}}
                            <td><div class="edit-btns">
                              <a href="{{route('frontend.user.service_details')}}"><i class="fa fa-eye"></i></a> {{-- <i class="fa fa-pencil-square-o"></i> --}}</div>
                            </td>
                        </tr>
                  <?php      
                      }
                    } 

                  ?>
                    
                </tbody>
              </table>
           {{--  <div class="col-md-12 text-center">
              <button type="submit" class="btn opp-btn">@lang('labels.frontend.user.account.to_accept')</button>
            </div> --}}
          </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>

@endsection