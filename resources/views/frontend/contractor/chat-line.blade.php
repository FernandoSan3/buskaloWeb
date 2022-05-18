
@if($message->from_user == $logged_in_user->id)
 
    <div class="row msg_container base_sent" data-message-id="{{ $message->id }}">
        
        <div class="col-md-10 col-10">
            <div class="messages msg_sent">
                <p>{!! $message->content !!}</p>
                <time datetime="{{ date("Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{ $message->fromUser->name }} • {{ $message->created_at->diffForHumans() }}</time>
            </div>
        </div>
        <div class="col-md-2 col-2 avatar">
          <?php 
            //user_profile = user_profile_sent
            //findinfolder = findinfolderSent
            $user_profile_sent="";
            $findinfolderSent="";
              if(isset($message->from_user_profile))
              { $user_profile_sent=$message->from_user_profile;
                  $findinfolderSent=public_path().'/img/'.$message->from_user_profile;
                 }
            if (file_exists($findinfolderSent) && !empty($user_profile_sent)) 
            {?>
            <img  class="img-responsive" src="{{asset('img')}}/{{$user_profile_sent}}">
            <?php } else { ?>
            <img  src="{{asset('img/frontend/dummy_user.jpg')}}">
            <?php } ?>
            {{-- <img src="https://ptetutorials.com/images/user-profile.png" class="img-responsive"> --}}
        </div>
    </div>
     
 
@else
 
   <div class="row msg_container base_receive" data-message-id="{{ $message->id }}">
        <div class="col-md-2 col-2 avatar">
          <?php 
            //user_profile = user_profile_sent
            //findinfolder = findinfolderSent
            $user_profile_sent="";
            $findinfolderSent="";
              if(isset($message->from_user_profile))
              { $user_profile_sent=$message->from_user_profile;
                  $findinfolderSent=public_path().'/img/'.$message->from_user_profile;
                 }
            if (file_exists($findinfolderSent) && !empty($user_profile_sent)) 
            {?>
            <img class="img-responsive" src="{{asset('img')}}/{{$user_profile_sent}}">
            <?php } else { ?>
            <img  src="{{asset('img/frontend/dummy_user.jpg')}}">
            <?php } ?>
            {{-- <img src="https://ptetutorials.com/images/user-profile.png" class=" img-responsive "> --}}
        </div>
        <div class="col-md-10 col-10">
            <div class="messages msg_receive text-left">
                <p>{!! $message->content !!}</p>
                <time datetime="{{ date("Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{ $message->fromUser->name }} • {{ $message->created_at->diffForHumans() }}</time>
            </div>
        </div>
    </div> 

    
@endif


<!-- @if($message->from_user == \Auth::user()->id)
 
    <div class="row msg_container base_sent" data-message-id="{{ $message->id }}">
        <div class="col-md-10 col-xs-10">
            <div class="messages msg_sent text-right">
                <p>{!! $message->content !!}</p>
                <time datetime="{{ date("Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{ $message->fromUser->name }} • {{ $message->created_at->diffForHumans() }}</time>
            </div>
        </div>
        <div class="col-md-2 col-xs-2 avatar">
            <img src="{{ url('images/user-avatar.png') }}" width="50" height="50" class="img-responsive">
        </div>
    </div>
 
@else
 
    <div class="row msg_container base_receive" data-message-id="{{ $message->id }}">
        <div class="col-md-2 col-xs-2 avatar">
            <img src="{{ url('images/user-avatar.png') }}" width="50" height="50" class=" img-responsive ">
        </div>
        <div class="col-md-10 col-xs-10">
            <div class="messages msg_receive text-left">
                <p>{!! $message->content !!}</p>
                <time datetime="{{ date("Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{ $message->fromUser->name }} • {{ $message->created_at->diffForHumans() }}</time>
            </div>
        </div>
    </div>
 
@endif -->