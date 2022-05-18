<div id="side-toggle-wrapper" >
 <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrow-circle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-arrow-circle-right fa-w-16 fa-3x"><path fill="currentColor" d="M256 8c137 0 248 111 248 248S393 504 256 504 8 393 8 256 119 8 256 8zm-28.9 143.6l75.5 72.4H120c-13.3 0-24 10.7-24 24v16c0 13.3 10.7 24 24 24h182.6l-75.5 72.4c-9.7 9.3-9.9 24.8-.4 34.3l11 10.9c9.4 9.4 24.6 9.4 33.9 0L404.3 273c9.4-9.4 9.4-24.6 0-33.9L271.6 106.3c-9.4-9.4-24.6-9.4-33.9 0l-11 10.9c-9.5 9.6-9.3 25.1.4 34.4z" class=""></path></svg>
</div>


<div id="sidebar-wrapper">
     <ul class="nav nav-tabs sidebar-nav"> 
      <!--  <li class="nav-item">
        <a class="nav-link" href="{{route('frontend.contractor.co-dashoard')}}"><i class="fa fa-home"></i> Homepage</a>
          {{-- <a href="#" class="side-counter"><span>2</span></a> --}}
       </li> -->
        <?php 
        $segment1 =  Request::segment(1);
         $first_part=$segment1;
        ?>

       <!--Opportunity-->
       <li class="nav-item <?php if ($first_part=="opportunities") {echo "active"; } else  {echo "noactive";}?>">
       <a class="nav-link" href="{{route('frontend.contractor.opportunities')}}"><i class="fa fa-star-o"></i> @lang('labels.frontend.constructor.profile.opportunities')<span>  </span></a>
      </li>

       <!--Jobs-->
       <li class="nav-item <?php if ($first_part=="jobs") {echo "active"; } else  {echo "noactive";}?>">
        <a class="nav-link" href="{{route('frontend.contractor.jobs')}}"><i class="fa fa-folder-o"></i>@lang('labels.frontend.constructor.profile.jobs')<span>  </span></a>
      </li>

   
        <!--chat-->
       <li class="nav-item <?php if ($first_part=="contr") {echo "active"; } else  {echo "noactive";}?>">
       <a class="nav-link" href="{{route('frontend.contractor.contr.chats')}}"><i class="fa fa-comment-o"></i>@lang('labels.frontend.constructor.profile.chat')<span>  </span></a>
       </li>

        <!--My profile show-->
       <li class="nav-item <?php if ($first_part=="mi-perfil") {echo "active"; } else  {echo "noactive";}?>">
        <a class="nav-link" href="{{route('frontend.contractor.mi-perfil')}}"><i class="fa fa-user-o"></i> @lang('labels.frontend.constructor.profile.my_profile')</a>
       </li>

       <!--My profile Edit-->
       <li class="nav-item <?php if ($first_part=="my-profile") {echo "active"; } else  {echo "noactive";}?>">
        <a class="nav-link" href="{{route('frontend.contractor.my-profile')}}"><i class="fa fa-cog"></i>@lang('labels.frontend.constructor.profile.my_account') </a>
       </li>


       <li class="nav-item">
       <a href="{{ route('frontend.auth.logout') }}" class="nav-link"><i class="fa fa-sign-out"></i>@lang('labels.frontend.constructor.profile.log_out')</a>
      </li>

     
     <!--  <li class="nav-item">
        <a class="nav-link" href="{{route('frontend.contractor.services')}}"> <i class="fa fa-tag"></i> Services offered</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('frontend.contractor.credits')}}">
          <i class="fa fa-credit-card"></i> Credits</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('frontend.contractor.documents')}}">
          <i class="fa fa-file-o"></i> Documentation</a>
      </li>  -->

       <!-- <li class="nav-item">
        <a class="nav-link" href="{{--route('frontend.contractor.workers')--}}">Workers</a>
      </li> -->

    </ul>
  </div>
  <div class="left-sidebar-show">
     <i class="fa fa-angle-right"></i>
  </div>




 <script>
$(document).ready(function(){
  $("#side-toggle-wrapper").click(function(){
    $("#sidebar-wrapper").toggle();
  });
});

</script>
