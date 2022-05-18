<!-- <div class="mesgs chat_box" id="chat_box" style="display: none">

    <h3 class="panel-title">
    <span class="glyphicon glyphicon-comment"></span> Chat with <i class="chat-user"></i> 
    </h3>

      <div class="msg_history panel-body chat-area" >-->

    <!--     <div class="incoming_msg">
          <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
          <div class="received_msg">
            <div class="received_withd_msg">
              <p>Test which is a new approach to have all
                solutions</p>
              <span class="time_date"> 11:01 AM    |    June 9</span></div>
          </div>
        </div>

        <div class="outgoing_msg">
          <div class="sent_msg">
            <p>Test which is a new approach to have all
              solutions</p>
            <span class="time_date"> 11:01 AM    |    June 9</span> </div>
        </div> -->
     
  <!--    
      </div>
      <div class="type_msg">
        <div class="input_msg_write"> -->
          <!-- <input type="text" class="write_msg" placeholder="Type a message" /> -->
          <!-- <textarea class="write_msg form-control input-sm chat_input" placeholder="Write your message here..."></textarea> -->
          <!-- <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button> -->
   <!--  <button class="msg_send_btn btn-chat" type="button" data-to-user="" disabled>
            <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
            </button>
        </div>
      </div>
      <input type="hidden" id="to_user_id" value="" />
</div>
 -->

<div id="chat_box" class="chat_box pull-right" style="display: none">
    <div class="row">
        <div class="col-xs-12 col-md-12">
                <div class="panel panel-default chat-bx-container">
                    <div class="panel-heading">
                        <h3 class="panel-title chat-user-title">
                          <div>
                            <span class="u-chat-profile"><img src="{{url('/img/frontend/dummy_user.jpg')}}" class="img-fluid">
                              <span class="fa-online"><i class="fa fa-circle"></i></span></span>
                            </div>
                            <div>
                              <span class="fa fa-times-circle pull-right close-chat"></span><span class="fa fa-minus-circle pull-right addMinimumOrMaximum minimize-chat" style="font-size: 24px;"></span>
                              <span class="glyphicon glyphicon-comment"></span>
                              <span class="chat-user pull-right"></span> <span class="pull-right pr-1">@lang('labels.frontend.constructor.profile.chat_with_user_one') (dos, tres)</span>
                              <p class="text-online">@lang('labels.frontend.constructor.profile.online')</p>
                            </div>
                            </h3>

                       
                    </div>
                    <div class="panel-body chat-area">

                    </div>
                    <div class="panel-footer mt-3">
                        <div class="input-group form-controls">
                            <textarea class="form-control input-sm chat_input" placeholder="Enter text message"></textarea>
                            <span class="input-group-btn chat-span">
                                    <button class="btn btn-sm btn-chat" type="button" data-to-user="" disabled>
                                        @lang('labels.frontend.constructor.profile.send')
                                        </button>
                                </span>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <input type="hidden" id="to_user_id" value="" />
</div>

