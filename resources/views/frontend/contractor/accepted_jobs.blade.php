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


        <div class="tab-pane active" id="opportunity">
            <div class="opportunity-sec">
              <div class="side-heading">
                <div class="row">
                  <div class="col-md-8">
                    <div class="head-side">
                      <h3>Opportunidades</h3>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="search-side">
                      <input type="text" name="" placeholder="Search">
                      <i class="fa fa-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">

              @if(isset($data) && !empty($data) && count($data) > 0)

                @foreach($data as $opprtunity)

                <div class="col-md-6">
                  <div class="media opp-list p-3">
                    <div class="opp-icon">
                      <img src="{{ url($opprtunity['service_image']) }}">
                    </div>
                    <div class="media-body">
                      <h6 class="orange">{{ $opprtunity['service_name'] }}</h6>
                      <h4>{{ $opprtunity['location'] }}</h4>
                      <span><a href="{{route('frontend.contractor.opportunity_details',Crypt::encrypt($opprtunity['id']))}}"><i class="link-dt">See Info</i></a></span>
                    </div>
                  </div>
                </div>

                @endforeach
                @else
                  <h3>Opportunities not found.!</h3>
                @endif

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
