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

        <div class="opport-tabs pro-wrapper">
          <ul class="nav nav-pills">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="pill" href="#request">@lang('labels.frontend.project.my_requests')</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="pill" href="#folder">@lang('labels.frontend.project.my_folders')</a>
            </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content opportunity-tab">
              <div class="tab-pane active" id="request">
                <div class="">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="nav flex-column nav-pills" id="m-tab" role="tablist">        


                     <!--  <a class="nav-link active show" id="tab-1" data-toggle="pill" href="#tab1" role="tab" aria-selected="true">
                          <div class="">
                            <div class="media opp-list p-1">
                              <i class="fa fa-star-o"></i>
                              <div class="media-body">
                                <h6 class="">ELECTRICIDAD</h6>
                                <span> 9:15am on Thursday 29th October 2020</span>
                              </div>
                            </div>
                          </div>
                        </a> -->

                        @if(isset($data) && !empty($data) && count($data) > 0)
                        @php $count=1; @endphp
                        @foreach($data as $projects)
                        <?php $setActive="";?>
                        
                        <a class="nav-link <?php if($count == 1){echo 'active show';} ?>" id="tab-{{$count}}" data-toggle="pill" href="#tab{{$count}}" role="tab">
                          <div class="">
                            <div class="media opp-list p-{{$count}}">
                              <!--  <div class="opp-icon">
                                <img src="{{ url($projects['service_image']) }}">
                              </div> -->
                              <i class="fa fa-star-o"></i>
                              <div class="media-body">
                                <h6 class="">{{ $projects['service_name'] }}</h6>
                                <span> {{ date('g:ia \o\n l jS F Y', strtotime($projects['created_at'])) }}</span>
                              </div>
                            </div>
                          </div>
                        </a>
                        @php $count++; @endphp
                        @endforeach
                        @else
                        <h3>@lang('labels.frontend.project.projects_not_found')!</h3>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-6 offset-md-1">
                      <div class="tab-content" id="">
                        @if(isset($data) && !empty($data) && count($data) > 0)
                        @php $count=1; @endphp
                        @foreach($data as $projectss)
                        <?php $setActive="";?>
                        <div class="tab-pane fade show <?php if($count == 1){echo 'active show';} ?>" id="tab{{$count}}" role="tabpanel">
                          <div class="text-center">
                            <div class="star-div">
                              <div class="tab-bg">
                                <span class="star-bx"><i class="fa fa-star-o"></i></span><span class="star-name">@lang('labels.frontend.project.new')</span>
                                <p>{{ date('g:ia \o\n l jS F Y', strtotime($projectss['created_at'])) }}</p>
                                <div class="ser_name">
                                  <h4 class="heading mb-3">@lang('labels.frontend.project.ask_for_details')</h4>

                                    <p><span class="span-heading">@lang('labels.frontend.project.category')  </span> {{ $projectss['category_name'] }}</p>
                                    <p><span class="span-heading">@lang('labels.frontend.project.service')  </span> {{ $projectss['service_name'] }}</p>
                                    <p><span class="span-heading">@lang('labels.frontend.project.sub_service')  </span> {{ $projectss['subservice_name']}}</p>
                                    @if(!empty( $projectss['child_sub_service_name']))
                                    <p><span class="span-heading">@lang('labels.frontend.project.child_sub_service')  </span> {{ $projectss['child_sub_service_name']}}</p>
                                    @endif
          
                                  <!--Question Option-->
                                  <?php if(isset($projectss['question_options']) && count($projectss['question_options']) > 0) {
                                  foreach ($projectss['question_options'] as $key => $value) {
                                  
                                  if($value['question_type'] == 'radio' || $value['question_type'] == 'checkbox' || $value['question_type'] == 'select') { ?>
                                  <p><span class="span-heading">{{ $value['question'] }}  </span> {{ $value['option'] }}</p>
                                  
                                  <?php } elseif($value['question_type'] == 'file') { ?>
                                  
                                  <img src="{{ $value['option_id'] }}">

                                  <?php }
                                     elseif($value['question_type'] == 'text') { ?>
                                    <p><span class="span-heading">{{ $value['question'] }}  </span> {{ $value['option_id'] }}</p>
                                 
                                  <?php }
                                   elseif($value['question_type'] == 'quantity') { ?>
                                    <p><span class="span-heading">{{ $value['question'] }}  </span> {{ $value['option_id'] }}</p>
                                     <?php }
                                      elseif($value['question_type'] == 'date') { ?>
                                    <p><span class="span-heading">{{ $value['question'] }}  </span> {{ $value['option_id'] }}</p>
                                     <?php }
                                     elseif($value['question_type'] == 'date_time') { ?>
                                    <p><span class="span-heading">{{ $value['question'] }}  </span> {{ $value['option_id'] }}</p>
                                     <?php }
                                      elseif($value['question_type'] == 'file') { ?>
                                    <p><span class="span-heading">{{ $value['question'] }}  </span> {{ $value['option_id'] }}</p>
                                     <?php }
                                  else { ?>
                                  <p><span class="span-heading">{{ $value['question'] }}  </span> {{ $value['option_id'] }}</p>
                                  <?php } }  } ?>
                                  <p><span class="span-heading">Localización: </span> {{ $projectss['city_name'] }}</p>
                                  @if(count($projectss['proinfo'])>0)
                                  <h4 class="heading mb-3">@lang('Profesionales:')</h4>
                                    @if(!empty($projectss['proinfo']))
                                        @foreach($projectss['proinfo'] as $pro)
                                        <h6>
                                            <img  class="pro-img" src="{{isset($pro['image'])?$pro['image']:''}}">
                                          
                                              {{isset($pro['prousername'])?$pro['prousername']:''}}
                                              <?php 
                                              $proid= encrypt($pro['id']);
                                              $serviceid=encrypt($value['service_request_id']);

                                              ?>
                                              <span><a href="{{url('/detail/'.$proid.'/'.$serviceid)}}"> Ver info</a></span>
                                         </h6>
                                        @endforeach
                                    @endif
                                    @endif

                                 
                                  <!--Question Option-->
                                  <!-- <p><span class="span-heading">@lang('labels.frontend.project.location') : </span> {{ $projectss['location'] }}</p> -->
                                  
                                </div>
                              </div>
                              
                              <!-- <h4><a href="#"><i class="link-dt">BUY</i></a></h4> -->
                            </div>
                          </div>
                        </div>
                        @php $count++; @endphp
                        @endforeach
                        @else
                        <h3>@lang('labels.frontend.project.opportunities_not_found')!</h3>
                        @endif
                      </div>


                    </div>
                  </div>
                </div>
              </div>


              <div class="tab-pane fade" id="folder">
                <div class="row">
                    <div class="col-md-5">
                      <div class="nav flex-column nav-pills" id="m-tab" role="tablist">                            <a class="nav-link active show" id="tab-1" data-toggle="pill" href="#tab1" role="tab" aria-selected="true">
                          <div class="">
                            <div class="media opp-list folder-list p-1">
                               <div class="opp-icon">
                                <img src="{{ url('img/frontend/folder.png') }}">
                              </div>
                              <div class="media-body">
                                <h6 class="">@lang('labels.frontend.project.house_construction')</h6>
                                <span> 2 @lang('labels.frontend.project.notification')</span>
                              </div>
                            </div>
                          </div>
                        </a>
                        <a class="nav-link" id="tab-2" data-toggle="pill" href="#tab2" role="tab" aria-selected="false">
                          <div class="">
                            <div class="media opp-list folder-list p-2">
                               <div class="opp-icon">
                                 <img src="{{ url('img/frontend/folder.png') }}">
                              </div>
                              <div class="media-body">
                                <h6 class="">@lang('labels.frontend.project.house_construction')</h6>
                                <span> 2 @lang('labels.frontend.project.notification')</span>
                              </div>
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                    <div class="col-md-6 offset-md-1">
                      <div class="tab-content">                                                                
                        <div class="tab-pane fade active show in" id="tab1" role="tabpanel">
                         
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
</div>

@endsection