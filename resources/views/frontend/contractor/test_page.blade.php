@extends('frontend.layouts.app')

@section('content')
   
<div class="header-profile">


<div id="wrapper" class="toggled left-sidebar">
  <!-- Sidebar -->
  @include('frontend.contractor.profile_sidebar')
  <!-- /#sidebar-wrapper -->

 <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid">
      <div class="right-sidebar ">
        <!-- Tab panes -->
        <div class="tab-content">


        <div class="tab-pane active" id="miperfil">
          <div class="edit-profile-section">
            <div class="my-acc">
              <span><img src="http://localhost/buskalo/www/public/img/frontend/account-setting.png">
              <h3>Mi Cuenta</h3></span>
            </div>
            <div class="profile-filled">
              <div class="row">
                <div class="col-md-6">
                  <div class="update-profile">
                    <img src="http://localhost/buskalo/www/public/img/frontend/user-profile.png">
                    <h4>95% Completed</h4>
                     <div class="progress">
                      <div class="progress-bar" style="width:100%"></div>
                    </div> 
                    <p>Completa la informacion de tu  <span class="orange"> Perfil Profesional</span>. Asegurate llenar todos los campos solicitados.</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="update-earning">
                    <div class="media">
                      <img src="http://localhost/buskalo/www/public/img/frontend/save-money.png" alt="John Doe" class="mr-3 mt-3" style="width:60px;">
                      <div class="media-body">
                        <span>Saldo Disponible</span>
                        <h4>$ 893,00 USD </h4>
                        
                      </div>
                    </div>

                    <div class="cart-item">
                      <a href=""> <img src="http://localhost/buskalo/www/public/img/frontend/cart-check.png">Comprar Creditos</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="profile-detail-sec">
                <div class="profile-head">
                  <div class="user-profile-edit">
                    <div class="profile-img">
                      <img src="http://localhost/buskalo/www/public/img/frontend/user.jpg">
                     
                    </div>
                    <div class="user-info">
                       <h3>Javier Perez C.</h3>
                      <span>Ingeniero Electric</span>
                    </div>
                    <button type="btn" data-toggle="modal" data-target=""
                           class="edit-btn"><i class="fa fa-edit"></i>Edit</button>
                  </div>
                  

                  <div class="row mt-5">


                   <!--**********Basic Information Update End Here************-->
                  <div class="col-md-6">
                    <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Basic Information</h3>
                        <button type="btn" data-toggle="modal" data-target="#infoModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Edit</button>
                      </div>
                        <ul class="info-list">
                          <li><h6>Identity No.</h6> <?php echo isset($user->identity_no) && !empty($user->identity_no) ? $user->identity_no : ''; ?></li>

                            <li><h6>Email-Id</h6> <?php echo isset($user->email) && !empty($user->email) ? $user->email : ''; ?></li>

                             <li><h6>Total Employees</h6> <?php echo isset($totalEmployee) && !empty($totalEmployee) ? count($totalEmployee) : '0'; ?></li>

                            <li><h6>User Name</h6> <?php echo isset($user->username) && !empty($user->username) ? $user->username : ''; ?></li>

                            <li><h6>Profile Title</h6> <?php echo isset($user->profile_title) && !empty($user->profile_title) ? $user->profile_title : ''; ?></li>

                            <li><h6>Date Of Birth</h6> <?php echo isset($user->dob) && !empty($user->dob) ? date('dS F Y', strtotime($user->dob)) : ''; ?></li>

                            <li><h6>Address</h6> <?php echo isset($user->address) && !empty($user->address) ? $user->address : ''; ?></li>
                              
                            <li><h6>Office Address</h6> <?php echo isset($user->office_address) && !empty($user->office_address) ? $user->office_address : ''; ?></li>

                            <li><h6>Other Address</h6> <?php echo isset($user->other_address) && !empty($user->other_address) ? $user->other_address : ''; ?></li>

                            <li><h6>Mobile Number</h6> <?php echo isset($user->mobile_number) && !empty($user->mobile_number) ? $user->mobile_number : ''; ?></li>

                            <li><h6>Landline Number</h6> <?php echo isset($user->landline_number) && !empty($user->landline_number) ? $user->landline_number : ''; ?></li>

                            <li><h6>Office Number</h6> <?php echo isset($user->office_number) && !empty($user->office_number) ? $user->office_number : ''; ?></li>
                        </ul>
                      </div>
                    </div>
                      <!--**********Basic Information Update End Here************-->


                      <!--**********Payment Method Update Start Here************-->
                    <div class="col-md-6">
                      <div class="pro-info">
                        <div class="pro-heading">
                          <h3>Payment Methods</h3>
                          <button type="btn" data-toggle="modal" data-target="#paymentMethodModal"
                           class="edit-btn"><i class="fa fa-edit"></i>Edit</button>
                        </div>
                        <div class="meta-list">
                          <h5>Seleccione los metodos de pago que acepta</h5>

                          <?php 
                            if(isset($paymentMethods) && !empty($paymentMethods)) {
                              foreach ($paymentMethods as $k => $v_menthod) {
                          ?>
                              <label class="cust-radio">{{ $v_menthod->name_es }}
                                <input type="radio" <?php  if($v_menthod->id == $paymentMethodId){ ?>  checked="" <?php } ?> value="{{$v_menthod->id}}" name="payment_method_id">
                                <span class="checkmark"></span>
                              </label>
                          <?php      
                              }
                            }

                          ?>
                        </div>
                      </div>
                    </div>
                      <!--**********Payment Method Update End Here************-->



                      <!--**********Social Networks Start Here************-->

                    <div class="col-md-6">
                      <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Social Networks</h3>
                        <button type="btn" data-toggle="modal" data-target="#socialMediaModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Edit</button>
                      </div>
                        <ul class="info-list social-list">
                          <li><h6>Facebook</h6><a href="<?php if(isset($social)){echo $social->facebook_url;}?>" class="orange">+ <?php if(isset($social)){echo $social->facebook_url;}?></a></li>
                          <li><h6>Instagram</h6><a href="<?php if(isset($social)){echo $social->instagram_url;}?>" class="orange">+ <?php if(isset($social)){echo $social->instagram_url;}?></a></li>
                          <li><h6>Linkedin</h6><a href="<?php if(isset($social)){echo $social->linkedin_url;}?>" class="orange">+ <?php if(isset($social)){echo $social->linkedin_url;}?></a></li>
                          <li><h6>Twitter</h6><a href="<?php if(isset($social)){echo $social->twitter_url;}?>" class="orange">+ <?php if(isset($social)){echo $social->twitter_url;}?></a></li>
                          <li><h6>Other Url</h6><a href="<?php if(isset($social)){echo $social->other;}?>" class="orange">+ <?php if(isset($social)){echo $social->other;}?></a></li>
                        </ul>
                      </div>
                  </div>
                  <!--**********Social Networks END Here************-->



                     <!--**********Certificaciones-Curso Start Here************-->
                    <div class="col-md-6">
                      <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Certificaciones - Cursos</h3>
                        <button type="btn" data-toggle="modal" data-target="#certificationModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Editar</button>
                      </div>
                        <!-- <ul class="info-list">
                          <li><h6>Certificacion Title Here</h6><a data-toggle="modal" data-target="#certificationModal"  class="orange">+ Image</a></li>
                        </ul> -->
                        <div class="photo-galley">
                          <?php if(isset($user->cetifications) && !empty($user->cetifications)) 
                            {
                              foreach ($user->cetifications['certification_courses'] as $key_course => $value_course) {
                              
                           ?>


                            <div class="foto-img">
                             <img src="{{ $value_course['file_name'] }}">
                              <a href="{{ $value_course['file_name'] }}" class="orange"></a>
                            </div>
                           <?php   }
                            } else {
                            ?>

                            <div class="foto-img">
                             <img src="">
                              <a href="#" class="orange"><p>+ Agegar Galeria</p></a>
                            </div>
                            <?php 
                              }

                              ?>

                         
                          
                        </div>
                      </div>

                       <!--**********Certificaciones-Curso End Here************-->

                      <!--**********Police REcord Start Here************-->
                      <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Police Record</h3>
                        <button type="btn" data-toggle="modal" data-target="#policeRecordModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Edit</button>
                      </div>
                       <!--  <ul class="info-list">
                         <li><h6>Certificacion Title Here</h6><a  data-toggle="modal" data-target="#policeRecordModal" href="" class="orange">+ Image</a></li>
                       </ul> -->

                       <div class="photo-galley">
                          <?php if(isset($user->cetifications) && !empty($user->cetifications)) 
                            {
                              foreach ($user->cetifications['police_records'] as $key_police => $value_police) {
                              
                           ?>


                            <div class="foto-img">
                             <img src="{{ $value_police['file_name'] }}">
                              <a href="{{ $value_police['file_name'] }}" class="orange"></a>
                            </div>
                           <?php   }
                            } else {
                            ?>

                            <div class="foto-img">
                             <img src="">
                              <a href="#" class="orange"><p>+ Agegar Galeria</p></a>
                            </div>
                            <?php 
                              }

                              ?>

                         
                          
                        </div>
                      </div>
                      <!--**********Police REcord End Here************-->


                      <!--**********Profile description Start Here************-->

                      <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Profesional</h3>
                        <button type="btn" data-toggle="modal" data-target="#profileDescriptionModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Edit</button>
                      </div>
                        <ul class="info-list">
                          <li><h6>Presentacion Breve</h6>
                            <p><?php if(isset($user) && !empty($user->profile_description)){echo $user->profile_description;}?></p></li>
                        </ul>
                      </div>
                    </div>
                      <!--**********Profile description End Here************-->


                      <!--**********Offered Services Start Here************-->
                    <div class="col-md-6">
                      <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Services</h3>
                        <button type="btn" data-toggle="modal" data-target="#serviceModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Edit</button>
                      </div>
                        <ul class="area-list">
                          <select name="services[]" id="services_id">
                          <option>servicios de instalacion electrica</option>
                            <?php foreach ($services as $k_service => $v_service) { ?>
                              <option value="{{$v_service->id}}">{{$v_service->es_name}}</option>
                            <?php } ?>
                          </select>
                          <?php 
                            foreach ($serviceOffered as $key => $value) {
                            ?>
                              <li><img src="{{ url('img/frontend/check1.png') }}"> {{$value->es_name}}</li>
                          <?php  }
                          ?>
                        </ul>
                      </div>
                    </div>
                       <!--**********Offered Services End Here************-->
                    

                      <!--**********User Gallery Start Here************-->

                    <div class="col-md-6">
                      <div class="pro-info">
                        <div class="pro-heading">
                          <h3>Photos and Videos</h3>
                          <button type="btn" data-toggle="modal" data-target="#galleryModal"
                           class="edit-btn"><i class="fa fa-edit"></i>Editar</button>
                        </div>
                        <div class="photo-galley">
                          <?php if(isset($user->gallery) && !empty($user->gallery)) 
                            {
                              foreach ($user->gallery['images'] as $key_images => $value_images) {
                              
                           ?>


                            <div class="foto-img">
                             <img src="{{ $value_images['file_name'] }}">
                              <a href="{{ $value_images['file_name'] }}" class="orange"></a>
                            </div>
                           <?php   }
                            } else {
                            ?>

                            <div class="foto-img">
                             <img src="">
                              <a href="#" class="orange"><p>+ Agegar Galeria</p></a>
                            </div>
                            <?php 
                              }

                              ?>

                         
                          
                        </div>
                      </div>
                    </div>

                  <!--**********User Gallery End Here************-->

                   <!--**********Services Coverage Area Start Here************-->
                    <div class="col-md-6">
                      <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Coverage Area</h3>
                        <button type="btn" data-toggle="modal" data-target="#coverageAreaModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Editar</button>
                      </div>
                        <ul class="area-list">
                          <li><img src="{{ url('img/frontend/check1.png') }}"> Barrio</li>
                          <li><img src="{{ url('img/frontend/check1.png') }}"> Ciudad</li>
                          <li><img src="{{ url('img/frontend/check1.png') }}"> Provincia</li>
                        </ul>
                      </div>
                    </div>
                      <!--**********Services Coverage Area End Here************-->


                  <div class="col-md-6">
                    <div class="pro-info">
                      <div class="pro-heading">
                        <h3>Calificaciones y Resenas</h3>
                        <button type="btn" data-toggle="modal" data-target="#exampleModal"
                         class="edit-btn"><i class="fa fa-edit"></i>Editar</button>
                      </div>
                      <div class="foto-img">
                             <img src="">
                              <a href="#" class="orange"><p>+ Agegar Galeria</p></a>
                            </div>
                      </div>
                    </div>



                    </div>
                  </div>

              </div>
            </div>


          </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>




<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



<!--///////////////////Basic Information modal start here/////////////////////////-->

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Basic Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        {{ html()->form('POST', route('frontend.contractor.my-profile.update_info'))->attribute('enctype', 'multipart/form-data')->open() }}

        <div class="modal-body">   
        <div class="edit-form">     
          <div class="form-edit">
          <!-- Form -->
            <label>Identity No.</label>
            <input type="text" name="identity_no" required="" value="<?php if(isset($user) && !empty($user->identity_no)){echo $user->identity_no;}?>" id="identity_no">
          </div>
          <div class="form-edit">
            <label>User Name</label>
            <input type="text" name="username" required="" value="<?php if(isset($user) && !empty($user->username)){echo $user->username;}?>" id="username">
          </div>
          <div class="form-edit">
             <label>Profile Title</label>
            <input type="text" name="profile_title" required="" value="<?php if(isset($user) && !empty($user->profile_title)){echo $user->profile_title;}?>" id="profile_title">
          </div>
             <div class="form-edit">
              <label>DOB</label>
              <input type="text" required="" class="form_date" name="dob" value="<?php if(isset($user) && !empty($user->dob)){echo $user->dob;}?>" id="datepicker">
            </div>
            <div class="form-edit">
              <label>Address</label>
              <input type="text" required="" name="address" value="<?php if(isset($user) && !empty($user->address)){echo $user->address;}?>" id="address">
            </div>
            <div class="form-edit">
              <label>Office Address.</label>
              <input type="text" required="" name="office_address" value="<?php if(isset($user) && !empty($user->office_address)){echo $user->office_address;}?>" id="office_address">
            </div>
            <div class="form-edit">
              <label>Other Address</label>
              <input type="text" required="" name="other_address" value="<?php if(isset($user) && !empty($user->other_address)){echo $user->other_address;}?>" id="other_address">
            </div>
            <div class="form-edit">
              <label>Mobile Number</label>
              <input type="text" required="" name="mobile_number" value="<?php if(isset($user) && !empty($user->mobile_number)){echo $user->mobile_number;}?>" id="mobile_number">
            </div>
            <div class="form-edit">
              <label>Landline Number</label>
              <input type="text" required="" name="landline_number" value="<?php if(isset($user) && !empty($user->landline_number)){echo $user->landline_number;}?>" id="landline_number">
            </div>
            <div class="form-edit">
              <label>Office Number</label>
              <input type="text" required="" name="office_number" value="<?php if(isset($user) && !empty($user->office_number)){echo $user->office_number;}?>" id="office_number">
            <!-- End -->  
            </div>
          </div>
          </div>
          <div class="modal-footer">
          <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn opp-btn">Save changes</button>
        </div>       
        </div>
        
    {{ html()->form()->close() }}
    </div>
  </div>
</div>

<!--///////////////////Basic Information modal End here/////////////////////////-->



<!--///////////////////Payment Method ModalStart Here/////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="paymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Payment Method</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
        <div class="modal-body">        
          <div class="edit-form">
            <div class="form-radio">
              <?php 
                if(isset($paymentMethods) && !empty($paymentMethods)) {
                  //echo "<pre>"; print_r($paymentMethodId); die;
                  foreach ($paymentMethods as $k => $v_menthod) {
              ?>
                  <label class="cust-radio">{{ $v_menthod->name_es }}
                    <input type="radio" <?php  if($v_menthod->id == $paymentMethodId){ ?>  checked="" <?php } ?> value="{{$v_menthod->id}}" name="payment_method_id">
                    <span class="checkmark"></span>
                  </label>
              <?php      
                  }
                }
              ?>
                         
            </div>
          </div>       
        </div>
        <div class="modal-footer">
          <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn opp-btn">Save changes</button>
        </div>
     {{ html()->form()->close() }}
    </div>
  </div>
</div>

<!--///////////////////Payment Method Modal End Here/////////////////////////-->



<!--///////////////////Social Networks Modal End Here/////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="socialMediaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Update Social Networks</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}

        <div class="modal-body">        
          <div class="edit-form">
            <div class="form-edit">
              <label>Facebook</label>
              <input type="text" name="facebook_url" required="" value="<?php if(isset($social) && !empty($social->facebook_url)){echo $social->facebook_url;}?>" id="facebook_url">
            </div>
            <div class="form-edit">
              <label>Instagram</label>
              <input type="text" name="instagram_url" required="" value="<?php if(isset($social) && !empty($social->instagram_url)){echo $social->instagram_url;}?>" id="instagram_url">
            </div>
            <div class="form-edit">
              <label>Linkedin</label>
              <input type="text" name="linkedin_url" required="" value="<?php if(isset($social) && !empty($social->linkedin_url)){echo $social->linkedin_url;}?>" id="linkedin_url">
            </div>
            <div class="form-edit">
              <label>Twitter</label>
              <input type="text" name="twitter_url" required="" value="<?php if(isset($social) && !empty($social->twitter_url)){echo $social->twitter_url;}?>" id="twitter_url">
            </div>
            <div class="form-edit">
              <label>Other Url</label>
              <input type="text" name="other" required="" value="<?php if(isset($social) && !empty($social->other)){echo $social->other;}?>" id="other">           
            </div>
          </div>
          </div>       
          <div class="modal-footer">
            <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
            <button type="submit" class="btn opp-btn">Save changes</button>
          </div>

        </div>
        
    {{ html()->form()->close() }}
    </div>
  </div>
</div>


<!--///////////////////Social Networks Modal End Here/////////////////////////-->


<!--///////////////////certification Courses modal start here/////////////////////////-->


<div class="modal fade" id="certificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Certificatets</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}

      <div class="modal-body">
            <div class="cer-div">
            <label>Certification Type:</label>
              <input type="radio" name="certification_type" checked="" value="0"> Image
              <input type="radio" name="certification_type" value="1"> Document
            </div>

            <div class='file_upload' id='f1'><input name='certification_courses[]' type='file'/></div>
            <div id='file_tools'>
              <i class="fa fa-plus-circle" id='add_file' aria-hidden="true">Add new file</i>
              <i class="fa fa-minus-circle" id='del_file' aria-hidden="true">Delete</i>
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
        <button type="submit" class="btn opp-btn">Save changes</button>
      </div>
      {{ html()->form()->close() }}
    </div>
  </div>
</div>

<!--///////////////////certification Courses modal End here/////////////////////////-->


<!--///////////////////Police Record modal start here/////////////////////////-->


<div class="modal fade" id="policeRecordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Police Record</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}

      <div class="modal-body">
            <div class="cer-div">
            <label>Record Type:</label>
              <input type="radio" name="record_type" checked="" value="0"> Image
              <input type="radio" name="record_type" value="1"> Document
            </div>

            <div class='file_upload' id='f1'><input name='police_records[]' type='file'/></div>
            <div id='pol_file_tools'>
              <i class="fa fa-plus-circle" id='poladd_file' aria-hidden="true">Add new file</i>
              <i class="fa fa-minus-circle" id='poldel_file' aria-hidden="true">Delete</i>
            </div>
            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
        <button type="submit" class="btn opp-btn">Save changes</button>
      </div>
      {{ html()->form()->close() }}
    </div>
  </div>
</div>

<!--///////////////////Police Record modal end here/////////////////////////-->



<!--///////////////////Profile Description Modal start here/////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="profileDescriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Profile Description</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
        <div class="modal-body">        
          <div class="edit-form">
            <div class="form-edit">
              <label>Profile Description</label>              
              <textarea name="profile_description"><?php if(isset($user) && !empty($user->profile_description)){echo $user->profile_description;}?></textarea>
            </div>
          </div>       
        </div>
        <div class="modal-footer">
          <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn opp-btn">Save changes</button>
        </div>
    </form>
    </div>
  </div>
</div>
<!--///////////////////Profile Description modal end here/////////////////////////-->


<!--///////////////////services offered modal start here/////////////////////////-->

<!-- Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Offered serfvices selection</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
        <div class="modal-body">        
          <div class="edit-form">
            <div class="form-edit">
              <label>Select Service</label>
              <select name="services[]" class="services_id" id="services_id" multiple="">
              <?php foreach ($services as $k_service => $v_service) { ?>
                <option value="{{$v_service->id}}" <?php if(in_array($v_service->id, $serviceIds)) {?> selected <?php } ?> >{{$v_service->es_name}}</option>
              <?php } ?>
            </select>            
            </div>
          </div>       
        </div>
        <div class="modal-footer">
          <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
          <button type="submit" class="btn opp-btn">Save changes</button>
        </div>
     {{ html()->form()->close() }}
    </div>
  </div>
</div>

<!--///////////////////services offered modal end here/////////////////////////-->


<!--///////////////////Coverage Area modal start here/////////////////////////-->

<div class="modal fade" id="coverageAreaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Coverage Area</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}
      <div class="modal-body">
          <div class="edit-form">
            <div class="cer-div">
            <label>Service Area In Whole Country:</label>
              <input type="radio" id="inWholeCountryTrue" name="whole_country" value="1"> true
              <input type="radio" id="inWholeCountryFalse" name="whole_country" value="0"> fasle
            </div>
           
             <div id="proviencesArea" class="form-edit">
              <label>Select Provinces</label>
              <select name="proviences[]" id="proviences" multiple="">
              <?php foreach ($provinces as $k_provinces => $province) { ?>
                <option value="{{$province->id}}">{{$province->name}}</option>
              <?php } ?>
            </select>
            </div>            
            

              <div id="citiesArea" class="form-edit">
              <label>Select Cities</label>
              <select name="cities[]" id="cities" multiple="">
              <?php foreach ($cities as $k_cities => $city) { ?>
                <option value="{{$city->id}}">{{$city->name}}</option>
              <?php } ?>
            </select>  
            </div>  
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
        <button type="submit" class="btn opp-btn">Save changes</button>
      </div>
       {{ html()->form()->close() }}
    </div>
  </div>
</div>

<!--///////////////////Coverage Area modal end here/////////////////////////-->



<!--///////////////////Gallery start here/////////////////////////-->


<div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload Photos And Videos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      {{ html()->form('POST', route('frontend.contractor.my-profile.update_other_info'))->attribute('enctype', 'multipart/form-data')->open() }}

      <div class="modal-body">
        <div class="edit-form">
        <div class="form-edit">    
            <label>Images:</label>
            <div class='file_upload1' id='f1'><input name='images_gallery[]' type='file'/></div>
        </div>
            <div id='image_file_tools'>
              <i class="fa fa-plus-circle" id='addGalleryImage' aria-hidden="true">Add new image file</i>
              <i class="fa fa-minus-circle" id='deleteGalleryImage' aria-hidden="true">Delete</i>
            </div>

              <br/>
          <div class="form-edit">     
            <label>Videos:</label>
            <div class='file_upload2' id='f2'><input name='videos_gallery[]' type='file'/></div>
          </div>

            <div id='videos_file_tools'>
              <i class="fa fa-plus-circle" id='addGalleryVideo' aria-hidden="true">Add new video File</i>
              <i class="fa fa-minus-circle" id='deleteGalleryVideo' aria-hidden="true">Delete</i>
            </div>
         

      </div>
      <div class="modal-footer">
        <button type="button" class="btn close-btn" data-dismiss="modal">Close</button>
        <button type="submit" class="btn opp-btn">Save changes</button>
      </div>
      {{ html()->form()->close() }}
    </div>
  </div>
</div>

<!--///////////////////Gallery modal end here/////////////////////////-->


<style type="text/css">
.alert-danger
{
  z-index: 10 !important;
}

.alert-success
{
  z-index: 10 !important;
}
</style>
@endsection