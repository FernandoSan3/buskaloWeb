@extends('frontend.layouts.app')
@section('content')
<!-- Sidebar -->
<div class="header-profile">
<!--   <div class="top-header-profile">
    <div class="top-profile-info">
      <div class="media">
        <img src="{{ url('img/frontend/user.jpg') }}" class="pro-img">
        <div class="media-body">
          <h4>Ximena Calle</h4>
          <p class="mb-0">Dispedora de Lorem ipsum</p>
          <p><img src="{{ url('img/frontend/check3.png') }}" class="sm-img pr-1"> Lorem ipsum</p>
        </div>
      </div>
    </div>
  </div> -->  
  <div id="wrapper" class="toggled left-sidebar">
    @include('frontend.contractor.profile_sidebar')
    <!-- /#sidebar-wrapper -->
    <div class="container-fluid">
      <div class="chat-container">
        <div class="messaging content01">
          <div class="top-profile-sec pro-wrapper py-4">
            <p class="star-bx d-inlinne-block"><i class="fa fa-star-o"></i></p>
            <span>@lang('labels.frontend.constructor.profile.chat')</span><!--
            <p>Tienes nuevesSolicitudes</p> -->
          </div>
          <div class="inbox_msg">
            <div class="inbox_people">
              <div class="headind_srch">
                <div class="recent_heading">
                  <h4>@lang('labels.frontend.constructor.profile.chat')</h4>
                </div>
              </div>
              <div class="inbox_chat content01">
                <div class="chat_list active_chat">
                  @if($users->count() > 0)
                  @foreach($users as $user)
                  <a href="javascript:void(0);" class="chat-toggle" data-id="{{ $user->id }}" data-user="{{ $user->username }}">
                    <div class="chat_people" id="users">
                      <div class="chat_img">
                        <?php
                        $user_profile="";
                        $findinfolder="";
                        if(isset($user->profile_picture))
                        { $user_profile=$user->profile_picture;
                        $findinfolder=public_path().'/img/'.$user->profile_picture;
                        }
                        if (file_exists($findinfolder) && !empty($user_profile))
                        {?>
                        <img class="img-responsive" src="{{asset('img')}}/{{$user_profile}}">
                        <?php } else { ?>
                        <img class="img-responsive" src="{{asset('img/frontend/dummy_user.jpg')}}">
                        <?php } ?>
                      </div>
                      <div class="chat_ib">
                        <h5>{{ $user->username }}<span class="chat_date"></span></h5><p>{{$user->last_name}}</p>
                      </div>
                    </div>
                  </a>
                  @endforeach
                  @else
                  <p>@lang('labels.frontend.constructor.profile.no_users')<a href="{{ url('register') }}">@lang('labels.frontend.constructor.profile.register_page')</a></p>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
   @include('frontend.contractor.chat-box')
  <input type="hidden" id="current_user" value="{{ \Auth::user()->id }}" />
  <input type="hidden" id="pusher_app_key" value="{{ env('PUSHER_APP_KEY') }}" />
  <input type="hidden" id="pusher_cluster" value="{{ env('PUSHER_APP_CLUSTER') }}" />
  @stop