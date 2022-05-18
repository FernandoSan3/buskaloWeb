      <?php
       $useragent = $_SERVER['HTTP_USER_AGENT'];
         preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4));

            $iPod = strpos($useragent,"iPod");
            $iPhone = strpos($useragent,"iPhone");
            $iPad = strpos($useragent,"iPad");
            $android = strpos($useragent,"Android");
            $server = strpos($useragent,"Mac");
            $url ='';
            
       
         ?>



      <div class="container">
    <nav class="navbar navbar-expand-lg ">
           <a class="navbar-brand" href="<?php echo e(url('/')); ?>"><img src="<?php echo e(url('img/frontend/logo.svg')); ?>"></a>

          <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <!-- <li class="nav-item">
                <a class="nav-link" href="<?php echo e(url('home_page')); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.categories'); ?></a>
              </li> -->
              <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('frontend.service_online')); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.online_services'); ?></a>
              </li>
            </ul>
            <ul class="navbar-nav right-nav ml-auto">
             <?php if(auth()->guard()->check()): ?>
                <?php if($logged_in_user->user_group_id==4): ?>
                   <li class="nav-item"><a href="<?php echo e(route('frontend.company.company_profile.my-profile')); ?>" class="nav-link <?php echo e(active_class(Route::is('frontend.user.dashboard'))); ?>"><?php echo app('translator')->get('labels.frontend.constructor.profile.my_account'); ?></a></li>
                 <?php endif; ?>
            <?php endif; ?>

            </ul>
          </div>
    </nav>

        <nav class="navbar navbar-expand-lg ">
           
           <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent1" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo app('translator')->get('labels.general.toggle_navigation'); ?>">

              <span class="navbar-toggler-icon ">
                <i class="fa fa-bars"></i>
              </span>

            </button>

          <div class="collapse navbar-collapse navbar-right" id="navbarSupportedContent1">
           <!--  <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="<?php echo e(url('home_page')); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.categories'); ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('frontend.service_online')); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.online_services'); ?></a>
              </li>
            </ul>
  -->
            <ul class="navbar-nav right-nav ml-auto">
<!-- 
             <?php if(config('locale.status') && count(config('locale.languages')) > 1): ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownLanguageLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><?php echo e(strtoupper(app()->getLocale())); ?></a>

                    <?php echo $__env->make('includes.partials.lang', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </li>
            <?php endif; ?>
 -->
             <?php if(auth()->guard()->check()): ?>

               <?php if($logged_in_user->user_group_id==3): ?>
                 <li class="nav-item"><a href="<?php echo e(route('frontend.contractor.mi-perfil')); ?>" class="nav-link <?php echo e(active_class(Route::is('frontend.user.dashboard'))); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.my_profile'); ?></a></li>
               <?php else: ?>
                <!--  <li class="nav-item"><a href="<?php echo e(route('frontend.user.dashboard')); ?>" class="nav-link <?php echo e(active_class(Route::is('frontend.user.dashboard'))); ?>"><?php echo app('translator')->get('navs.frontend.dashboard'); ?></a></li> -->
               <?php endif; ?>
              
            <?php endif; ?>

            <?php if(auth()->guard()->guest()): ?>
                <li class="nav-item"><a href="<?php echo e(route('frontend.auth.login')); ?>" class="nav-link <?php echo e(active_class(Route::is('frontend.auth.login'))); ?>"><?php echo app('translator')->get('navs.frontend.login'); ?></a></li>

                <?php if(config('access.registration')): ?>
                      <li class="nav-item">
                      <span class="btn get-join my-2 my-sm-0"><a href="<?php echo e(route('frontend.index')); ?>" class="nav-link <?php echo e(active_class(Route::is('frontend.index'))); ?>"><?php echo app('translator')->get('navs.frontend.join'); ?></a></span>
                    </li>
                    <?php
                        if($iPad||$iPhone||$iPod)
                        {
                        ?>
                            <li class="nav-item"><span class=""><a href="https://apps.apple.com/us/app/búskalo/id1580560610" class="nav-link btn pre-btn"> <?php echo app('translator')->get('Descargar App Movil'); ?></a></span></li>
                         <?php
                        }
                        else if($android)
                        {
                        ?>
                            <li class="nav-item"><span class=""><a href="https://play.google.com/store/apps/details?id=com.wdp.Buskalo"class="nav-link btn pre-btn"> <?php echo app('translator')->get('Descargar App Movil'); ?></a></span></li>
                         <?php   
                        } 
                        else if($server)
                        {
                        ?>
                            <li class="nav-item"><span class=""><a href="https://apps.apple.com/us/app/búskalo/id1580560610" class="nav-link btn pre-btn"> <?php echo app('translator')->get('Descargar App Movil'); ?></a></span></li>
                             <?php
                        }
                        else
                        {
                        ?>
                            <li class="nav-item"><span class=""><a href="https://play.google.com/store/apps/details?id=com.wdp.Buskalo"class="nav-link btn pre-btn"> <?php echo app('translator')->get('Descargar App Movil'); ?></a></span></li>
                             <?php
                        }
                        ?>
                <?php endif; ?>
                <?php else: ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"><?php echo e($logged_in_user->name); ?></a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view backend')): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="dropdown-item"><?php echo app('translator')->get('navs.frontend.user.administration'); ?></a>
                        <?php endif; ?>


                        <?php if($logged_in_user->user_group_id==3): ?>
              
                            <a href="<?php echo e(route('frontend.contractor.mi-perfil')); ?>" class="dropdown-item <?php echo e(active_class(Route::is('frontend.contractor.mi-perfil'))); ?>">Mi Perfil</a>

                          <?php elseif($logged_in_user->user_group_id==2): ?>
              
                            <a href="<?php echo e(route('frontend.user.dashboard')); ?>" class="dropdown-item <?php echo e(active_class(Route::is('frontend.user.dashboard'))); ?>"><?php echo app('translator')->get('labels.frontend.company.profile.my_profile'); ?></a>
                        <?php else: ?>

                        <a href="<?php echo e(route('frontend.company.company_profile.mi-perfil')); ?>" class="dropdown-item <?php echo e(active_class(Route::is('frontend.user.account'))); ?>"><?php echo app('translator')->get('labels.frontend.constructor.profile.my_profile'); ?></a>
                        
                        <?php endif; ?>

                        <a href="<?php echo e(route('frontend.auth.logout')); ?>" class="dropdown-item"><?php echo app('translator')->get('navs.general.logout'); ?></a>
                    </div>
                </li>
            <?php endif; ?>
            </ul>
          </div>
    </nav>
  </div><?php /**PATH /Applications/MAMP/htdocs/Buskalo_WEB/Buskalo_WEB/resources/views/frontend/includes/nav.blade.php ENDPATH**/ ?>