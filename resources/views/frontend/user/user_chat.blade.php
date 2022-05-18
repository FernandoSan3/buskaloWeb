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
            <div class="tab-pane active" id="trabajos">
              <div class="side-heading">
                <div class="row">
                  <div class="col-md-4">
                    <div class="head-side">
                        <h3>@lang('labels.frontend.user.account.message')</h3>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="job-btns">

                       {{--  <a class="link-dt" href="{{route('frontend.user.all-rejected-requests')}}"><button type="button" class="btn btn-danger">@lang('labels.frontend.user.account.reject')</button></a>

                        <a class="link-dt" href="{{route('frontend.user.all-inprogress-requests')}}"><button type="button" class="btn btn-warning">@lang('labels.frontend.user.account.inprogress')</button></a>

                        <a class="link-dt" href="{{route('frontend.user.all-accepted-requests')}}"><button type="button" class="btn btn-success">@lang('labels.frontend.user.account.accept')</button></a> --}}
                                           
                    </div>
                  </div>

                  <div class="col-md-4">
                      {{-- <div class="search-side">
                        <input type="text" data-s_type ='all' id="search_text" onkeyup="getSearchRequest(this.value, 'all')" name="search_text"  placeholder="Search">
                        <i class="fa fa-search"></i>
                      </div> --}}
                  </div>
                </div>
              </div>
              <div class="jobs-sec">
                <div class="mensajes-sec mensajes-chatbox">
                  <div class="row">
                    <div class="col-md-12 col-xl-12 chat">
                      <div class="card">
                        <div class="card-header msg_head">
                          <div class="d-flex bd-highlight">
                            <div class="img_cont">
                              <img src="{{ url('img/frontend/user.jpg') }}" class="rounded-circle user_img">
                              <span class="online_icon"></span>
                            </div>
                            <div class="user_info">
                              <span>Chat with Khalid</span>
                              <p>1767 Messages</p>
                            </div>
                            <div class="video_cam">
                              <span><i class="fa fa-video-camera"></i></span>
                              <span><i class="fa fa-phone"></i></span>
                            </div>
                          </div>
                          <span id="action_menu_btn"><i class="fa fa-ellipsis-v"></i></span>
                          <div class="action_menu">
                            <ul>
                              <li><i class="fas fa-user-circle"></i> View profile</li>
                              <li><i class="fas fa-users"></i> Add to close friends</li>
                              <li><i class="fas fa-plus"></i> Add to group</li>
                              <li><i class="fas fa-ban"></i> Block</li>
                            </ul>
                          </div>
                        </div>
                        <div class="card-body msg_card_body">
                          <div class="d-flex justify-content-start mb-4">
                            <div class="img_cont_msg">
                              <img src="{{ url('img/frontend/user.jpg') }}" class="rounded-circle user_img_msg">
                            </div>
                            <div class="msg_cotainer">
                              Hi, how are you samim?
                              <span class="msg_time">8:40 AM, Today</span>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end mb-4">
                            <div class="msg_cotainer_send">
                              Hi Khalid i am good tnx how about you?
                              <span class="msg_time_send">8:55 AM, Today</span>
                            </div>
                            <div class="img_cont_msg">
                              <img src="{{ url('img/frontend/user.jpg') }}" class="rounded-circle user_img_msg">
                            </div>
                          </div>

                          <div class="d-flex justify-content-start mb-4">
                            <div class="img_cont_msg">
                              <img src="{{ url('img/frontend/user.jpg') }}" class="rounded-circle user_img_msg">
                            </div>
                            <div class="msg_cotainer">
                              I am good too, thank you for your chat template
                              <span class="msg_time">9:00 AM, Today</span>
                            </div>
                          </div>
                          
                          <div class="d-flex justify-content-end mb-4">
                            <div class="msg_cotainer_send">
                              You are welcome
                              <span class="msg_time_send">9:05 AM, Today</span>
                            </div>
                            <div class="img_cont_msg">
                              <img src="{{ url('img/frontend/user.jpg') }}" class="rounded-circle user_img_msg">
                            </div>
                          </div>

                          <div class="d-flex justify-content-start mb-4">
                            <div class="img_cont_msg">
                              <img src="{{ url('img/frontend/user.jpg') }}" class="rounded-circle user_img_msg">
                            </div>
                            <div class="msg_cotainer">
                              I am looking for your next templates
                              <span class="msg_time">9:07 AM, Today</span>
                            </div>
                          </div>

                          <div class="d-flex justify-content-end mb-4">
                            <div class="msg_cotainer_send">
                              Ok, thank you have a good day
                              <span class="msg_time_send">9:10 AM, Today</span>
                            </div>
                            <div class="img_cont_msg">
                              <img src="{{ url('img/frontend/user.jpg') }}" class="rounded-circle user_img_msg">
                            </div>
                          </div>
                          <div class="d-flex justify-content-start mb-4">
                            <div class="img_cont_msg">
                              <img src="{{ url('img/frontend/user.jpg') }}" class="rounded-circle user_img_msg">
                            </div>
                            <div class="msg_cotainer">
                              Bye, see you
                              <span class="msg_time">9:12 AM, Today</span>
                            </div>
                          </div>
                        </div>
                        <div class="card-footer">
                          <div class="input-group">
                            <div class="input-group-append">
                              <span class="input-group-text attach_btn"><i class="fa fa-paperclip"></i></span>
                            </div>
                            <textarea name="" class="form-control type_msg" placeholder="Type your message..."></textarea>
                            <div class="input-group-append">
                              <span class="input-group-text send_btn"><i class="fa fa-location-arrow"></i></span>
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
</div>


@endsection