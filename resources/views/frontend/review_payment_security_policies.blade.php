@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')

<section class="works-sec serv-online mx">
  <div class="container">
      <div class="heading">
        
         <h2><b>@lang('labels.frontend.payment.payment_security')</b></h2>
         <!-- <p> ¡Simple! son servicios online que ofreces profesionales para ti.</p> -->
        <span class="bottom-border"></span>
      </div>
    <div class="row">
      <?php
      if(!empty($review_payment_security_policies)) {
        foreach ($review_payment_security_policies as  $value) {
          
          $constractor_description = $value->description_cons;
          $company_description = $value->description_comp;
        }
      }else{?>
          <p>@lang('labels.frontend.payment.payment_security_not_found')</p>
      <?php }
      
          if('user_group_id'==3){?>
            <p><?php echo $constractor_description?></p>
          <?php   
          }else{ ?>
            <p> <?php echo $company_description?></p>
          <?php }
       ?>
    </div>
  </div>
</section>   

@endsection



