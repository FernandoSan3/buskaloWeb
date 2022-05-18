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

        <div class="tab-pane active request-tabs" id="trabajos">
            <div class="side-heading">
                <div class="row">
                  <div class="col-md-4">
                    <div class="head-side">
                      <h3>@lang('labels.frontend.user.account.all_completed_requests')</h3>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="job-btns">

                    {{--   <a class="link-dt" href="{{route('frontend.user.all-rejected-requests')}}"><button type="button" class="btn btn-danger">@lang('labels.frontend.user.account.reject')</button></a>

                      <a class="link-dt" href="{{route('frontend.user.all-inprogress-requests')}}"><button type="button" class="btn btn-warning">@lang('labels.frontend.user.account.inprogress')</button></a>

                      <a class="link-dt" href="{{route('frontend.user.all-accepted-requests')}}"><button type="button" class="btn btn-success">@lang('labels.frontend.user.account.all')</button></a> --}}
                                         
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="search-side">
                      <input type="text" data-s_type ='4' id="search_text" onkeyup="getSearchRequest(this.value, 4)" name="search_text"  placeholder="@lang('labels.frontend.user.account.search')">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
            <div class="jobs-sec">
              <div class="loader-div">
                <img id="loader-img" src="{{ url('img/frontend/giphy.gif') }}">
              </div>
              <div class="row" id="append_data">


              <?php 
              if(!empty($data) && count($data) > 0)
              {
              foreach($data as $dataaa)
              {
              	?>
                <div class="col-md-6">
                  <div class="media opp-list p-3">
                    <div class="opp-icon">
                      <img src="{{ url('img/frontend/clocks.png') }}">
                    </div>
                    <div class="media-body">
                      <h6 class="orange">{{$dataaa->category_name}}</h6>
                      <h4>@lang('labels.frontend.user.account.installation_requirement')</h4>
                      <span>{{ timezone()->convertToLocal($logged_in_user->created_at) }} ({{ $logged_in_user->created_at->diffForHumans() }})

                      </span>
                      <a class="link-dt" href="{{route('frontend.user.service_details',Crypt::encrypt($dataaa->id))}}">@lang('labels.frontend.user.account.see_info')</a>
                    </div>
                  </div>
                </div>
               <?php } } else { ?>
                <h1><center>@lang('labels.frontend.user.account.no_record_found')!</center></h1>
               <?php }?>

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript"> 
  
  $(document).ready(function(){

    $('#loader-img').hide();
  });

    function getSearchRequest(val, request_status) { 
       $('#loader-img').show();

        $.ajax({
            type: "GET",
            url: '{!! URL::to("searchRequest") !!}',
            data:{search:val,request_status:request_status},
            success: function(data){
                $("#append_data").html(data.html);
                 $('#loader-img').hide();
                             
            }
        });
    }
</script>
@endsection