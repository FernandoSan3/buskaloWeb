@extends('frontend.layouts.app')
@section('content')
<style type="text/css">
li.nav-item .active {
    background-color: #e84620;
    color: #fff;
}
</style>
<div class="header-profile">
  <div class="top-header-profile">
    <div class="top-profile-info">
      <div class="media">

      <?php $pic= 'img/frontend/user.png';
            if(isset($user) && !empty($user->avatar_location)){$pic= 'img/company/profile/'.$user->avatar_location;}
            ?>
        <img src="{{ url($pic) }}" class="pro-img">
        <div class="media-body">
          <h4>{{$user->username}}</h4>
          <!-- <p class="mb-0">{{$user->username}}</p> -->
          <p><img src="{{ url('img/frontend/check3.png') }}" class="sm-img pr-1"> @lang('labels.frontend.constructor.profile.verified_profile')</p>

        </div>
      </div>
    </div>
  </div>
  <div id="wrapper" class="toggled left-sidebar">
    <!-- Sidebar -->
    @include('frontend.company.profile_sidebar')
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="container-fluid">
        <div class="right-sidebar ">
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active opportunity-tab" id="opportunity">
              <div class="opportunity-sec">
                <div class="top-profile-sec pro-wrapper py-4">
                  <p class="star-bx d-inlinne-block"><i class="fa fa-star-o"></i></p>
                  <span>@lang('labels.frontend.constructor.profile.opportunities')</span><!--
                  <p>Tienes nuevesSolicitudes</p> -->
                </div>
                <ul class="nav nav-mytabs" id="myTab" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link opper active" id="Nuevo-tab" data-toggle="tab" href="#Nuevo" role="tab" aria-controls="Nuevo" aria-selected="true">Nuevo</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="Anteriores-tab" data-toggle="tab" href="#Anteriores" role="tab" aria-controls="Anteriores" aria-selected="false">Anteriores</a>
                    </li>
                </ul> 
                <div class="pro-wrapper">
                  <div class="row">
                    <div class="col-md-5">
                        <div class="tab-content" id="">
                            <div class="tab-pane fade show active" id="Nuevo" role="tabpanel" style="height: auto !important;">
                                <div class="nav flex-column nav-pills" id="m-tab" role="tablist">
                                    @if(isset($data) && !empty($data) && count($data) > 0)
                                    @php $count=1; @endphp
                                    @foreach($data as $key=> $opprtunity)
                                    <?php $setActive="";?>
                                     @if($opprtunity['request_not_now']==0)
                                    <a class="nav-link <?php if($count == 1){echo 'active show';} ?>" id="tab-{{$count}}" data-toggle="pill" href="#tab{{$count}}" role="tab">
                                      <div class="">
                                        <div class="media opp-list p-{{$count}}">
                                          <!--  <div class="opp-icon">
                                            <img src="{{ url($opprtunity['service_image']) }}">
                                          </div> -->
                                          <i class="fa fa-star-o"></i>
                                          <div class="media-body">
                                            <h6 class="">{{ $opprtunity['service_name'] }}</h6>
                                            <span> {{ date('g:ia \o\n l jS F Y', strtotime($opprtunity['created_at'])) }}</span>
                                            <p><span>Créditos: {{ number_format($opprtunity['credit'],2)}} </span></p>
                                          </div>
                                        </div>
                                      </div>
                                    </a>
                                    @php $count++; @endphp
                                    @endif
                                    
                                    @endforeach
                                    @else
                                    <h3>@lang('labels.frontend.constructor.profile.opportunities_not_found').!</h3>
                                    @endif
                                </div>
                            </div>
                             <div class="tab-pane fade" id="Anteriores" role="tabpanel" style="height: auto !important;">
                                <div class="nav flex-column nav-pills" id="m-tab" role="tablist">
                                    @if(isset($data) && !empty($data) && count($data) > 0)
                                    @php $count=1; @endphp
                                    @foreach($data as $k=> $opprtunity)
                                    <?php $setActive="";?>
                                    @if($opprtunity['request_not_now']==1)
                                    <a class="nav-link <?php if($count == 1){echo 'active show';} ?>" id="tab-{{$count}}" data-toggle="pill" href="#tab{{$count}}" role="tab">
                                      <div class="">
                                        <div class="media opp-list p-{{$count}}">
                                          <!--  <div class="opp-icon">
                                            <img src="{{ url($opprtunity['service_image']) }}">
                                          </div> -->
                                          <i class="fa fa-star-o"></i>
                                          <div class="media-body">
                                            <h6 class="">{{ $opprtunity['service_name'] }}</h6>
                                            <span> {{ date('g:ia \o\n l jS F Y', strtotime($opprtunity['created_at'])) }}</span>
                                            <p><span>Créditos: {{ number_format($opprtunity['credit'],2)}} </span></p>
                                          </div>
                                        </div>
                                      </div>
                                    </a>
                                    @php $count++; @endphp
                                    @endif 
                                    @endforeach
                                    @else
                                    <h3>@lang('labels.frontend.constructor.profile.opportunities_not_found').!</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 offset-md-1">
                      <div class="tab-content" id="">
                        @if(isset($data) && !empty($data) && count($data) > 0)
                        @php $count=1; @endphp
                        
                        @foreach($data as $opprtunity)
                        <?php $setActive="";?>
                        <div class="tab-pane fade show <?php if($count == 1){echo 'active show';} ?>" id="tab{{$count}}" role="tabpanel">
                          <div class="text-center">
                            <div class="star-div">
                              <div class="tab-bg">
                                <span class="star-bx"><i class="fa fa-star-o"></i></span><span class="star-name">@lang('labels.frontend.constructor.profile.new')</span>
                                <p>{{ date('g:ia \o\n l jS F Y', strtotime($opprtunity['created_at'])) }}</p>
                                <div class="ser_name">
                                  <h4 class="heading mb-3">@lang('labels.frontend.constructor.profile.opportunity_details')</h4>
                                  
                                    <span class="span-heading">@lang('labels.frontend.constructor.profile.category_name')  </span><li> {{ $opprtunity['category_name'] }}</li>
                                
                                @if(!empty($opprtunity['service_name']))
                                    <span class="span-heading">@lang('labels.frontend.constructor.profile.service_name')  </span> <li>{{ $opprtunity['service_name'] }}</li>
                                @endif

                                @if(!empty($opprtunity['subservice_name']))
                                    <p><span class="span-heading">@lang('labels.frontend.constructor.profile.subservice_name')  </span><li> {{ $opprtunity['subservice_name'] }}</li></p>
                                @endif

                                @if(!empty($opprtunity['child_sub_service_name']))
                                    <p><span class="span-heading">@lang('labels.frontend.constructor.profile.child_sub_service_name')  </span><li> {{ $opprtunity['child_sub_service_name'] }}</li></p>
                                @endif
                                  
                                 
                                  <!--Question Option-->
                                  <?php if(isset($opprtunity['question_options']) && count($opprtunity['question_options']) > 0) {
                                  foreach ($opprtunity['question_options'] as $key => $value) {
                                  
                                  if($value['question_type'] == 'radio' || $value['question_type'] == 'checkbox' || $value['question_type'] == 'select') { ?>
                                  <p><span class="span-heading">{{ $value['question'] }}  </span> {{ $value['option'] }}</p>
                                  
                                  <?php } elseif($value['question_type'] == 'file') { ?>
                                  
                                  <img src="{{ $value['option_id'] }}">
                                  <?php }else { ?>
                                  <p><span class="span-heading">{{ $value['question'] }} </span> {{ $value['option_id'] }}</p>
                                  <?php } }  } ?>
                                  <!--Question Option-->
                                  <p><span class="span-heading">@lang('labels.frontend.constructor.profile.location') : </span> {{ $opprtunity['city_name'] }}</p>
                                </div>
                              </div>
                              <h6 class="my-3 mb-4"><input type="checkbox" name="buy" class="check_buy"  <?php if( isset($_GET['approval_status']) && $_GET['approval_status'] == 1 ){ echo 'checked="checked"'; }?>> @lang('labels.frontend.constructor.profile.buying_accept_term_and_condition') <a href="{{url('purchage_terms')}}">  Términos y condiciones</a>.<br/><span class="termsrequires" style="color: red"></span></h6>
                              
                              <div class="tab-btn">
                                 <a href="{{route('frontend.company.company_profile.ignore_opportunity',Crypt::encrypt($opprtunity['service_request_id']))}}"><button class="btn btn-tab outline-btn-tab">@lang('labels.frontend.constructor.profile.ignore')</button></a>
                                <span style="display: none;" class="checktrue"> <a href="{{route('frontend.company.company_profile.buy_opportunity',Crypt::encrypt($opprtunity['id']))}}"><button class="btn btn-tab">@lang('labels.frontend.constructor.profile.buy')</button></a></span>
                                <span>
                                <a href="javascript:void(0)" class="checkcondition"><button class="btn btn-tab">@lang('labels.frontend.constructor.profile.buy')</button></a>
                                </span>
                               
                              </div>
                              <div>
                                
                              </div>
                              <!-- <h4><a href="#"><i class="link-dt">BUY</i></a></h4> -->
                            </div>
                          </div>
                        </div>
                        @php $count++; @endphp
                        @endforeach
                        @else
                        <h3>@lang('labels.frontend.constructor.profile.opportunities_not_found').!</h3>
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
  </div>
</div>
</div>
<script>
    $('.checkcondition').click(function()
    {
       if($(".check_buy").is(':checked'))
       {
            $('.termsrequires').html(' ');
            $(".checktrue").show(); 
            $(".checkcondition").hide(); // checked
        }else
        {   
             $('.termsrequires').html('Terms & condiations fields required.'); // unchecked
        }
       
    });
    $('.check_buy').click(function()
    {
        if($(".check_buy").is(':checked'))
           {
              $('.termsrequires').html(' ');
                $(".checktrue").show(); 
                $(".checkcondition").hide(); // checked
            }else
            {
                 $(".checkcondition").show(); 
                $(".checktrue").hide();
            }
    });

</script>
@endsection
