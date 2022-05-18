@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')

<section class="works-sec serv-online mx">
    <div class="container">
        <div class="heading">
        
            <h2><b>@lang('labels.frontend.work_with_us.work_with_us')</b></h2>
            <!-- <p> ¡Simple! son servicios online que ofreces profesionales para ti.</p> -->
            <span class="bottom-border"></span>
        </div>
        <div class="row">
            <div class="col-lg-12">
              <?php
              if(!empty($work_with_us)) {
                foreach ($work_with_us as  $value) {
                  
                  $constractor_description = $value->description_cons;
                  $company_description = $value->description_comp;
                }
              }else{?>
                  <p>@lang('labels.frontend.work_with_us.cant_find_job')</p>
              <?php }?>
      
                @if($user_group_id==3)
                    {!! isset($constractor_description)?$constractor_description:''!!}
                   
                @elseif($user_group_id==4)
                    {!!isset($company_description)?$company_description:''!!}
                     
                @else
                      {!! isset($constractor_description)?$constractor_description:''!!}
                   
                @endif
            </div>
        </div>
    </div>
</section>   

@endsection



