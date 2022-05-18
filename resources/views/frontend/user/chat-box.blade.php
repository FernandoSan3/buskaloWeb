<div id="chat_box" class="chat_box pull-right" style="display: none">
    <div class="row">
        <div class="col-xs-12 col-md-12">
                <div class="panel panel-default chat-bx-container">
                    <div class="panel-heading">
                        <h3 class="panel-title chat-user-title">
                          <div>
                            <span class="u-chat-profile"><img src="{{ url('img/frontend/dummy_user.jpg') }}" class="img-fluid">
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
                            <textarea class="form-control input-sm chat_input" placeholder="Enter message here!"></textarea>
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

<!-- <div id="chat_box" class="chat_box pull-right" style="display: none">
    <div class="row">
        <div class="col-xs-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span> Chat with <i class="chat-user"></i> </h3>
                        <span class="glyphicon glyphicon-remove pull-right close-chat"></span>
                    </div>
                    <div class="panel-body chat-area">

                    </div>
                    <div class="panel-footer">
                        <div class="input-group form-controls">
                            <textarea class="form-control input-sm chat_input" placeholder="Write your message here..."></textarea>
                            <span class="input-group-btn">
                                    <button class="btn btn-primary btn-sm btn-chat" type="button" data-to-user="" disabled>
                                        <i class="glyphicon glyphicon-send"></i>
                                        Send</button>
                                </span>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <input type="hidden" id="to_user_id" value="" />
</div>-->