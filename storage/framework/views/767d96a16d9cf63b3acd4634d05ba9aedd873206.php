
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title></title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet"> 
    
  </head>

  <style>
    
    div.parent{ 
      flex: 1;
      display:table;
      width:100%;
      align-items: center;

    }
    div.text{ 
      vertical-align:middle;
      display:table-cell;
    }
    div.img img{ 
      width: 30px; /* you can change width */
      vertical-align:middle;
      display:table-cell;
    }
    .divin{
      display:inline-block;
    }
        
  </style>

  <body style="font-family: lato; padding: 0; margin: 0;">
    <div style="display: block; width: 100%; margin: 0; background: #fff;">
      <div style="background: #f5f5f5; display: inline-block; width: 100%; padding: 10px 0; text-align: center;">
        <div><img  style="width: 20%" src="<?php echo e(isset($demo->logo)?$demo->logo:url('img/logo/logo-svg.png')); ?>"></div>
      </div>
    
  	  <div style="display: block; width: 70%; margin: 50px auto;">
        <div style="display: inline-block; width: 100%; border-bottom: solid 2px #ddd; padding-bottom:10px; margin-bottom: 10px;">
          <div style="display: inline-block; width: 75%; padding-top: 20px;">
            <h4 style="margin: 0; font-weight: 600; font-size: 18px;"> Hola  <?php echo e($demo->username); ?> </h4>
            <p style="margin: 5px 0;"><?php echo e($demo->email); ?></p>
          </div>
       
          <div style="display: inline-block;">
            <?php if(!empty($demo->avatar_location)): ?>
                <?php if($demo->user_group_id==2): ?>
                <img style="width: 80px; height: 80px; border-radius: 50%;" src="<?php echo e(url('img/user/profile/'.$demo->avatar_location)); ?>">
                <?php elseif($demo->user_group_id==3): ?>
                <img style="width: 80px; height: 80px; border-radius: 50%;" src="<?php echo e(url('img/contractor/profile/'.$demo->avatar_location)); ?>">
                <?php else: ?>
                <img style="width: 80px; height: 80px; border-radius: 50%;" src="<?php echo e(url('img/company/profile/'.$demo->avatar_location)); ?>">
                <?php endif; ?>
            <?php else: ?>
            <img style="width: 80px; height: 80px; border-radius: 50%;" src="<?php echo e(isset($demo->user_icon)?$demo->user_icon:url('img/logo/logo.jpg')); ?>">
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div style="display: block; width: 70%; margin: 50px auto; font-size: 15px; font-weight: 500;">
        <div class="text-center">
          <p align="center" style="text-decoration: none;">
            Un cliente está buscando los servicios que tu ofreces, aprovecha esta
            <a style="color: #e74621 !important; text-decoration: none;">oportunidad</a>.
          </p>
        </div>
          <br>
          <div class="col-md-4 col-sm-12">
            <div align="center"  style="margin: 0px 10% 0px 10%; background: #f5f5f5; display: inline-block; width: 80%; padding: 10px 0; text-align: center; border-radius: 8%">          
                <div class="star-div">
                  <div class="tab-bg" >
                    <div class="right-added">
                      <div class="right-header">
                        <div>
                          <table align="center" style="width:100%">
                            <tr>
                            <td>
                              <p class="divin"><img src="<?php echo e(asset('img/frontend/dt.png')); ?>" style="width: 30px; margin-bottom: -12px;"></p>
                              <!-- <span class="divin">Detalles del proyecto</span> -->
                              <h6 style="margin: 0px 0px 0px 10px; font-size: 25px;">Detalles del proyecto<h6>
                            </td>
                            </tr>
                          </table>
                        </div>
                        <br>
                    
                        <div align="left" class="booking-list">
                          <?php if($question): ?>
                            <?php $__currentLoopData = $question; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <h6 style="margin: 0px 0px 0px 18%; font-size: 16px;"><?php echo e($value['question']); ?><h6>
                              <li style="margin: 0px 0px 0px 18%; color: #e74621">
                                <h6 style="color: #808080; font-size: 14px; font-weight: 400;"><?php echo e($value['option']?$value['option']:'Sin respuesta'); ?><h6>
                              </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endif; ?>
                          <h6 style="margin: 0px 0px 0px 18%; font-size: 16px;">Ciudad<h6>
                          <li style="margin: 0px 0px 0px 18%; color: #e74621">
                            <h6 style="color: #808080; font-size: 14px; font-weight: 400;"><?php echo e($demo->city_name); ?><h6>
                          </li>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
           </div>
          </div>
          <div class="text-center">
            <div align="center" style="display: inline-block;width: 100%; margin-top: 10px; font-size: 15px;">
              <a style=" text-decoration: none;">Abre la</a>
              <a style="color: #e74621; text-decoration: none;">aplicación</a>
              <a style=" text-decoration: none;">, ingresa a tu cuenta </a>
              <img style="width: 40px; height: 40px; margin-bottom: -12px;" src="<?php echo e(url('img/frontend/pro-btn.png')); ?>">
              <a style=" text-decoration: none;"> y adquiere esta oportunidad ahora!</a>
            </div>
          </div>
          <br>
          <div class="text-center">
            <h5 align="center" style="display: inline-block;width: 100%; font-size: 15px; color: #808080; font-weight: 500; align-self: center;">
              Búskalo te ayuda a hacer crecer tu negocio.
            <br>
            </h5>
          </div>
        
      </div>
      <div style="display: inline-block; width: 100%; padding: 20px 0; background: #f5f5f5; text-align: center;">
        <div style="margin-top: 10px;">
          <img src="<?php echo e(url('img/logo/footer-logo.png')); ?>">
        </div>
      </div>
    </div>
  </body>
</html> 

<?php /**PATH /Applications/MAMP/htdocs/prueba_Buskalo_WEB/resources/views/frontend/mail/new_opportunity.blade.php ENDPATH**/ ?>