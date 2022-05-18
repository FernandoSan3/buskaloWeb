@extends('frontend.layouts.app')
@section('content')
<div class="header-profile">
  <div class="top-header-profile">
    <div class="top-profile-info">
      <div class="media">
         <?php $pic= 'img/frontend/user.png';
            if(isset($user) && !empty($user->avatar_location)){$pic= 'img/contractor/profile/'.$user->avatar_location;}
            ?>
        <img src="{{ url($pic) }}" class="pro-img">
        <div class="media-body">
           <h4>{{$user->username}}</h4>
          <p class="mb-0">{{$user->username}}</p>
          <p><img src="{{ url('img/frontend/check3.png') }}" class="sm-img pr-1"> Perfil Verificado</p>
        </div>
      </div>
    </div>
  </div>
  <div id="wrapper" class="toggled left-sidebar">
    <!-- Sidebar -->
    @include('frontend.contractor.profile_sidebar')
    <!-- /#sidebar-wrapper -->
    <!-- Page Content -->
    <div id="page-content-wrapper">
      <div class="container">
        <div class="comp-profile">
          <div class="top-profile-sec pro-wrapper py-4">
            <p class="star-bx d-inlinne-block mb-0"><i class="fa fa-star-o"></i></p>
            <span>Mi Perfil</span>
          </div>
        </div>
        <div class="pro-wrapper">
          <div class="prof-inner">
            <div class="row">
              <div class="col-md-6">
                <div class="cprof-detail">
                  <div class="prof-head">
                    <p><img src="{{ url('img/frontend/check3.png') }}">Perfil Verificado</p>
                    <h2><?php echo isset($user->username) && !empty($user->username) ? $user->username : ''; ?></h2>
                  </div>
                  <ul class="prof-list">
                    <li><h6>identity no</h6>: <?php echo isset($user->identity_no) && !empty($user->identity_no) ? $user->identity_no : ''; ?></li>
                    <li><h6>Profile Title</h6>: <?php echo isset($user->profile_title) && !empty($user->profile_title) ? $user->profile_title : ''; ?></li>
                    <li><h6>Empleados</h6>: <?php echo isset($user->total_employee) && !empty($user->total_employee) ? $user->total_employee : '0'; ?> </li>
                    <li><h6>Direccion</h6>: <?php echo isset($user->address) && !empty($user->address) ? $user->address : ''; ?> / <?php echo isset($user->office_address) && !empty($user->office_address) ? $user->office_address : ''; ?> / <?php echo isset($user->other_address) && !empty($user->other_address) ? $user->other_address : ''; ?></li>
                    <li><h6>Contactos</h6>: <?php echo isset($user->mobile_number) && !empty($user->mobile_number) ? $user->mobile_number : ''; ?> / <?php echo isset($user->landline_number) && !empty($user->landline_number) ? $user->landline_number : ''; ?> / <?php echo isset($user->office_number) && !empty($user->office_number) ? $user->office_number : ''; ?></li>
                    <li><h6>Direccion Web</h6>:<?php echo isset($user->website_address) && !empty($user->website_address) ? $user->website_address : ''; ?></li>
                  </ul>
                </div>
              </div>
              <div class="col-md-6">
                <div class="cprof-img">
                  <?php $pic= 'img/frontend/user.png';
                  if(isset($user) && !empty($user->avatar_location)){$pic= 'img/contractor/profile/'.$user->avatar_location;}
                  ?>
                  <img src="{{ url($pic) }}" class="cprof-img">
                </div>
              </div>
            </div>
          </div>
          <div class="build-sec ">
            <div class="build-inner media">
              <img src="{{ url('img/frontend/building.jpg') }}" class="mr-3">
              <div class="media-body">
                <p><?php echo isset($user->profile_description) && !empty($user->profile_description) ? $user->profile_description : 'Description will show here'; ?></p>
              </div>
              <div class="mark-div"><img src="{{ url('img/frontend/shapes.png') }}"></div>
            </div>
          </div>
          <section class="comp-service mx">
            <div class="heading">
              <h4 class="orange">Servicio:</h4>
              <!-- <h1>Electricidad</h1> -->
            </div>
            <ul class="serv-list">
              <?php if(isset($serviceOffered) && count($serviceOffered) >0)
              {
              foreach ($serviceOffered as $key => $value) { ?>
              <li><i class="fa fa-check"></i> {{$value->es_name}}</li>
              <?php  } }?>
            </ul>
            <div class="prof-service mt-5">
              <div class="row">
                <div class="col-md-4">
                  <ul class="list pl-0">
                    <div class="list-head">
                      <img src="{{ url('img/frontend/comp1.png') }}"> <h3>Metodos de pago</h3>
                    </div>
                    <?php
                    if(isset($paymentMethods) && !empty($paymentMethods)) {
                    foreach ($paymentMethods as $k => $v_menthod) {
                    if(in_array($v_menthod->id,$paymentMethodId))
                    {
                    ?>
                    <li><i class="fa fa-check"></i>{{ $v_menthod->name_es }}</li>
                    <?php } } } ?>
                  </ul>
                </div>
                <div class="col-md-4">
                  <ul class="list list-mid">
                    <div class="list-head">
                      <img src="{{ url('img/frontend/comp2.png') }}"> <h3>Redes Sociales</h3>
                    </div>
                    

                    

                    <li><?php if(isset($social) && !empty($social->facebook_url)){echo '<h6>Facebook</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->facebook_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->facebook_url)){echo $social->facebook_url;}?></a></li>

                    <li><?php if(isset($social) && !empty($social->instagram_url)){echo '<h6>Instagram</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->instagram_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->instagram_url)){echo $social->instagram_url;}?></a></li>

                     <li><?php if(isset($social) && !empty($social->linkedin_url)){echo '<h6>Linkedin</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->linkedin_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->linkedin_url)){echo $social->linkedin_url;}?></a></li>

                      <li><?php if(isset($social) && !empty($social->twitter_url)){echo '<h6>Twitter</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->twitter_url;}?>" class="orange"> <?php if(isset($social)  && !empty($social->twitter_url)){echo $social->twitter_url;}?></a></li>

                      <li><?php if(isset($social) && !empty($social->other)){echo '<h6>Other Url</h6>';}?><a target="_blank" href="<?php if(isset($social)){echo $social->other;}?>" class="orange"> <?php if(isset($social)  && !empty($social->other)){echo $social->other;}?></a></li>

                                   
                  </ul>
                </div>
                <div class="col-md-4">
                  <ul class="list border-0">
                    <div class="list-head">
                      <img src="{{ url('img/frontend/comp3.png') }}"> <h3>Area de cobertura</h3>
                    </div>
                    <?php
                    if(isset($allUserAreaData) && !empty($allUserAreaData)) {
                     foreach ($allUserAreaData as $key => $value) { ?>
                              <li><i class="fa fa-check"></i>{{$value['name']}}</li>

                              <?php foreach ($value['cities'] as $key2 => $val2) 
                              { ?>

                               <li style="margin-left: 10%;">{{'* '.$val2['name']}}</li>
                                
                              <?php } ?>

                        <?php  } } ?>
                    
                  </ul>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection