<?php 
  
  $requestCount = array();
  
  $requestCount = 0;

?>
<div id="side-toggle-wrapper" >
 <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrow-circle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-arrow-circle-right fa-w-16 fa-3x"><path fill="currentColor" d="M256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm-28.9 143.6l75.5 72.4H120c-13.3 0-24 10.7-24 24v16c0 13.3 10.7 24 24 24h182.6l-75.5 72.4c-9.7 9.3-9.9 24.8-.4 34.3l11 10.9c9.4 9.4 24.6 9.4 33.9 0L404.3 273c9.4-9.4 9.4-24.6 0-33.9L271.6 106.3c-9.4-9.4-24.6-9.4-33.9 0l-11 10.9c-9.5 9.6-9.3 25.1.4 34.4z" class=""></path></svg>
</div>
<div id="sidebar-wrapper">
  <ul class="nav nav-tabs sidebar-nav">
      {{--  <li class="nav-item">
        <a class="nav-link" href="{{route('frontend.user.dashboard')}}">Homepage</a>
       </li> --}}


        <?php 
        $directoryURI = $_SERVER['REQUEST_URI'];
        $path = parse_url($directoryURI, PHP_URL_PATH);
        $components = explode('/', $path);
        if(isset($components[1]) && !empty($components[1]))
       {
         $first_part = $components[1];
       }
       elseif(isset($components[2]) && !empty($components[2]))
       {
         $first_part = $components[2];
       }
       elseif(isset($components[3]) && !empty($components[3]))
       {
         $first_part = $components[3];
       }
       else
       {
        $first_part = $components[0];

       }
        
        //$sec_part = $components[4];
        ?>

        <li class="nav-item <?php if ($first_part=="projects") {echo "active"; } else  {echo "noactive";}?>">
        <a class="nav-link" href="{{route('frontend.user.projects')}}"><i class="fa fa-folder-o"></i> @lang('labels.frontend.review.project')</a>
      </li>

       <!--chat-->
       <li class="nav-item <?php if ($first_part=="usr") {echo "active"; } else  {echo "noactive";}?>">
       <a class="nav-link" href="{{route('frontend.user.usr.chats')}}"><i class="fa fa-comment-o"></i> @lang('labels.frontend.review.chat')<span>  </span></a>
       </li>

       <!--My Profile-->
       <li class="nav-item <?php if ($first_part=="dashboard") {echo "active"; } else  {echo "noactive";}?>">
       <a class="nav-link" href="{{route('frontend.user.dashboard')}}"><i class="fa fa-user-o"></i> @lang('labels.frontend.review.my_account')<span>  </span></a>
       </li>

   

      <!-- <li class="nav-item">
        <a class="nav-link" href="{{route('frontend.user.all-requests')}}"><i class="fa fa-star-o"></i>All Request</a>
        <a href="#" class="side-counter"><span><?php echo isset($requestCount) && !empty($requestCount['allRequestCount']) ? $requestCount['allRequestCount'] : '0'; ?></span></a>
      </li> -->



       <li class="nav-item">
       <a href="{{ route('frontend.auth.logout') }}" class="nav-link"><i class="fa fa-sign-out"></i> @lang('labels.frontend.review.log_out')</a>
      </li>


      
    </ul>
  </div>



<script>
$(document).ready(function(){
  $("#side-toggle-wrapper").click(function(){
    $("#sidebar-wrapper").toggle();
  });
});

</script>


