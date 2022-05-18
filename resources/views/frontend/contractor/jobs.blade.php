@extends('frontend.layouts.app')
@section('content')
<div class="header-profile">
   <div class="top-header-profile">
    <div class="top-profile-info">
      <div class="media">

      <?php $pic= 'img/frontend/user.png';
            if(isset($user) && !empty($user->avatar_location)){$pic= 'img/contractor/profile/'.$user->avatar_location;}
            ?>
        <img src="{{ url($pic) }}" class="pro-img">
        <div class="media-body">
          <h4>{{$user->username}}</h4>
          
          <p><img src="{{ url('img/frontend/check3.png') }}" class="sm-img pr-1">@lang('labels.frontend.constructor.profile.verified_profile') </p>

        </div>
      </div>
    </div>
  </div>
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
            <div class="tab-pane active opportunity-tab job-tab" id="job">
              <div class="opportunity-sec">
                <div class="top-profile-sec pro-wrapper py-4">
                  <p class="star-bx d-inlinne-block"><i class="fa fa-star-o"></i></p>
                  <span>@lang('labels.frontend.constructor.profile.jobs')</span><!--
                  <p>Tienes nuevesSolicitudes</p> -->
                </div>
               
                <div class="pro-wrapper">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="nav flex-column nav-pills" id="m-tab" role="tablist">
                        @if(isset($data) && !empty($data) && count($data) > 0)
                        @php $count=1; @endphp

                        @foreach($data as $jobs)

                          <?php 

                          $jobImg= 'img/frontend/star-blank.png';

                          if(isset($jobs['service_image']) && !empty($jobs['service_image'])){$jobImg= $jobs['service_image'];}
                          ?>
                        
                         <a class="nav-link <?php if($count == 1){echo 'active show';} ?>" id="tab-{{$count}}" data-toggle="pill" href="#tab{{$count}}" role="tab">
                          <div class="media opp-list p-{{$count}}">
                            <div class="opp-icon">
                              <i class="fa fa-star-o"></i>
                            </div>
                            <div class="media-body">
                              <h6 class="">{{ $jobs['service_name'] }}</h6>
                              <h5>{{ $jobs['job_status'] }}</h5>
                            </div>
                            <div class="media-right text-center">
                              <p class="mb-0"> {{ date('jS F Y', strtotime($jobs['created_at'])) }}</p>
                              <span class="text-black"><i class="fa fa-circle"></i></span>
                            </div>
                          </div>
                        </a>
                        @php $count++; @endphp
                        @endforeach
                        @else
                        <h3>@lang('labels.frontend.constructor.profile.job_not_found').!</h3>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-7">
                      <div class="tab-content job-tab-content tab-bg" id="">
                        @if(isset($data) && !empty($data) && count($data) > 0)
                        @php $count=1; @endphp
                        @foreach($data as $jobs)
                        
                        <div class="tab-pane fade show <?php if($count == 1){echo 'active show';} ?>" id="tab{{$count}}" role="tabpanel">
                          <div class="ser_name text-left">
                            @if($jobs['job_status']=='Service realizado')
                            <div class="">
                                <select class="form-control">
                                    <option value="Confirm" selected="">Service realizado</option>
                                </select>
                            </div>
                            @elseif($jobs['job_status']=='Aceptado')
                            <div class="">
                                <select class="form-control updateStatus"   data-request="{{$jobs['service_request_id']}}" data-user="{{$jobs['user_id']}}">
                                    <option value="3" selected="">Aceptado</option>
                                     <option value="5">Service realizado</option>
                                </select>
                            </div>
                            @else
                                <div class="">
                                <select class="form-control updateStatus"  data-request="{{$jobs['service_request_id']}}" data-user="{{$jobs['user_id']}}">
                                    <option value="2">Pendiente</option>
                                    <option value="3" >Aceptado</option>
                                    <option value="5">Service realizado</option>
                                </select>
                            </div>
                            @endif
                            <br/>
                            <h4 class="heading mb-3">
                                @if(!empty($jobs['service_name']))
                                    {{ $jobs['service_name'] }}
                                @endif
                            </h4>
                            <div class="ms-border">
                              <div class=""><a href="{{url('contr/chats')}}"><i class="fa fa-comment-o"></i></a> @lang('labels.frontend.constructor.profile.message')</div>
                              <div class=""><i class="fa fa-map-marker"></i> @lang('labels.frontend.constructor.profile.map')</div>
                            </div>
                             <h4 class="heading mb-3">@lang('labels.frontend.constructor.profile.service_request'):</h4>
                             <p><span class="span-heading">@lang('labels.frontend.constructor.profile.category_name')  </span><li> {{ $jobs['category_name'] }}</li></p>
                             @if(!empty($jobs['service_name']))
                                <p><span class="span-heading">@lang('labels.frontend.constructor.profile.service_name')  </span> <li>{{ $jobs['service_name'] }}</li></p>
                                @endif

                            @if(!empty($jobs['subservice_name']))
                                <p><span class="span-heading">@lang('labels.frontend.constructor.profile.subservice_name')  </span><li> {{ $jobs['subservice_name'] }}</li></p>
                            @endif

                            @if(!empty($jobs['child_sub_service_name']))
                                <p><span class="span-heading">@lang('labels.frontend.constructor.profile.child_sub_service_name')  </span><li> {{ $jobs['child_sub_service_name'] }}</li></p>
                            @endif

                            <div class="p-info">
                                <span class="span-heading">@lang('labels.frontend.constructor.profile.customer_information') : </span>
                                <p>
                                    @lang('Fecha de Solicitud'): {{ date('d-m-Y',strtotime($jobs['created_at'])) }}
                                </p>
                                 <p>
                                    @lang('Fecha de compra'): {{ date('d-m-Y',strtotime($jobs['updated_at'])) }}
                                </p>
                                <p>
                                    @lang('labels.frontend.constructor.profile.email'): {{ $jobs['email'] }}
                                </p>
                                <p>
                                    @lang('labels.frontend.constructor.profile.username'): {{ $jobs['username'] }}
                                </p>
                                <p>
                                    @lang('labels.frontend.constructor.profile.city'): {{ $jobs['city_name'] }} 
                                </p>
                                <p>
                                    @lang('labels.frontend.constructor.profile.full_address'): {{ $jobs['location'] }}
                                </p>
                                 <p>
                                    @lang('Celular'): {{ $jobs['mobile_number'] }}
                                </p>
                                  <p>
                                    <span class="span-heading">@lang('labels.frontend.constructor.profile.job_status') :</span>{{$jobs['job_status']}}
                                </p>
                                <p>
                                    <span class="span-heading">@lang('Localización') :</span>{{$jobs['city_name']}}
                                </p>
                            </div>
                            <div class="star-rating">
                              <span><i class="fa fa-star"></i></span>
                              <span><i class="fa fa-star"></i></span>
                              <span><i class="fa fa-star"></i></span>
                              <span><i class="fa fa-star"></i></span>
                              <span><i class="fa fa-star"></i></span>
                              <p>@lang('labels.frontend.constructor.profile.ratings')</p>
                            </div>
                             @if($jobs['job_status']=='Service realizado')
                            <div class="tab-btn text-center">
                              <a href="{{url('rating_review?serviceId='.$jobs['service_request_id'].'&prouserId='.$jobs['user_id'])}}"><button class="btn btn-tab w-85 mb-4">@lang('labels.frontend.constructor.profile.request_rating_and_comments')</button></a>

                              <!--  <a href="{{url('payment/request?serviceId='.$jobs['service_request_id'].'&prouserId='.$jobs['user_id'].'&requestid='.$jobs['asignid'])}}"></a> -->
                          
                                 <button type="button" class="btn btn-tab outline-btn-tab w-85" data-toggle="modal" data-target="#exampleModal_{{$jobs['asignid']}}"> <i class="fa fa-credit-card text-secondary"></i> @lang('labels.frontend.constructor.profile.receive_payment_by_credit_card')</button>
                              

                                <div class="modal fade" id="exampleModal_{{$jobs['asignid']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                  <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                       <form method="post" action="{{url('payment/request/web')}}">
                                        {{csrf_field()}}
                                      <div class="modal-body">
                                       
                                            <input type="hidden" name="service_request_id" value="{{$jobs['service_request_id']}}">
                                            <input type="hidden" name="prouserId" value="{{$jobs['user_id']}}">
                                            <input type="hidden" name="requestid" value="{{$jobs['asignid']}}">
                                            <label>Pago Solicitado</label>
                                            <input type="text" name="amount" class="serviceamount form-control" placeholder="Ingresar cantidad" required="">
                                        
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">OK</button>
                                      </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            @else
                             <div class="tab-btn text-center">
                              <button class="btn btn-tab w-85 mb-4">@lang('labels.frontend.constructor.profile.request_rating_and_comments')</button>
                              <button class="btn btn-tab outline-btn-tab w-85"> <i class="fa fa-credit-card text-secondary"></i> @lang('labels.frontend.constructor.profile.receive_payment_by_credit_card')</button>
                            </div>
                            @endif
                            <!-- <div class="text-center">
                              <p class="my-3 mb-4">@lang('labels.frontend.constructor.profile.accept_term_and_condition').</p>
                            </div> -->
                          </div>
                        </div>
                        @php $count++; @endphp
                        @endforeach
                        @else
                        <h3>@lang('labels.frontend.constructor.profile.job_not_found').!</h3>
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
<script type="text/javascript">
    $('.updateStatus').on('change', function()
    {
        var status= $(this).val();
        var request_id= $(this).attr('data-request');
        var client_user_id= $(this).attr('data-user');
        if(status!='')
        {
            $.ajax({
                url:"{{url('manageRequestStatus')}}",
                type:'get',
                data:{status_type:status,request_id:request_id,client_user_id:client_user_id},
                success:function(result)
                {
                    $('.statusupdate').html('');
                    var json = $.parseJSON(result);
                    if(json.status==1)
                    {
                        window.location.reload();
                        $('.statusupdate').html(json.message);
                    }
                }
            });
        }
    });
</script>
@endsection