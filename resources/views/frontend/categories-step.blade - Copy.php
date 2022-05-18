@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')





<section class="all-category-sec inner-page mx">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="category-modal">
          <div class="" id="serv-step1">
          <!--  
                <div class="step-header">
                  <h4 class="step-title">Vamos a encontrarte</h4>
                </div>
 -->
                <div class="modal-body">
                  <form id="regForm" method="post" action="{{ route('frontend.store_service_request') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" value="{{$user_id }}">
                    <input type="hidden" name="service_id" value="1">

                    <!-- Circles which indicates the steps of the form: -->
                  <!--   <div class="step-process">
                      <span class="step active"></span>
                      <span class="step"></span>
                      <span class="step"></span>
                      <span class="step"></span>
                    </div> -->

                    <ul class="nav nav-tabs">
                      <li class="nav-item">
                        <a class="nav-link active" id="tab_1"  data-step ="1" >Step1</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="tab_2"  data-step ="2"  >Step2</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="tab_3"  data-step ="3" >Step3</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="tab_4"  data-step ="4" >Step4</a>
                      </li>

                      <div class="progress-steps">
                        <div class="progress">
                          <div class="progress-bar" style="width:25%"></div>
                        </div>
                      </div>

                    </ul>

                    <div class="tab-content">
                     <div class="tab-pane container active" id="step1">
                        <div class="pro-info">
                         <div class="pro-heading">
                            <h3>Select Subservices</h3>
                           
                          </div>
                        <div class="meta-list">
                            <label class="cust-radio">sub service 1
                            <input type="radio" value="1" checked="checked" name="subservice_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">sub service 2
                            <input type="radio" value="2" name="subservice_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">sub service 3
                            <input type="radio" value="3" name="subservice_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">sub service 4
                            <input type="radio" value="4" name="subservice_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">sub service 5
                            <input type="radio" value="5" name="subservice_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">sub service 6
                            <input type="radio" value="6" name="subservice_id">
                            <span class="checkmark"></span>
                          </label>
                        </div>
                      </div>
                      </div>
                      <div class="tab-pane container fade" id="step2">
                        <div class="pro-info">
                         <div class="pro-heading">
                            <h3>¿Con qué tipo de proyecto necesitas ayuda?</h3>
                            <input type="hidden" name="question_id" value="1">
                          </div>
                        <div class="meta-list">
                            <label class="cust-radio">Montaje de muebles
                            <input type="radio" value="1" checked="checked" name="option_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">Instalación de electrodomésticos
                            <input type="radio" value="2" name="option_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">Instalación de ventilador de techo
                            <input type="radio" value="3" name="option_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">Reparación de puertas
                            <input type="radio" value="4" name="option_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">Instalación de iluminación interior.
                            <input type="radio" value="5" name="option_id">
                            <span class="checkmark"></span>
                          </label>
                          <label class="cust-radio">Arte colgando
                            <input type="radio" value="6" name="option_id">
                            <span class="checkmark"></span>
                          </label>
                        </div>
                      </div>
                      </div>


                      <div class="tab-pane container fade" id="step3">
                         <div class="pro-heading">
                            <h3>Donde deseas el servicio?</h3>
                          </div>
                        <div class="serv-location">
                          <input type="text" name="location" id="location" value="<?php if(isset($user_detail->address)) { echo $user_detail->address; }  ?>" required="" placeholder="enter your address">

                         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3558.9484612761516!2d75.7793009!3d26.873378499999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x396db44a51423625%3A0x6dc1c1e81f96c712!2sWDP%20Infosolutions%20Pvt.%20Ltd.!5e0!3m2!1sen!2sin!4v1589797183558!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                        </div>
                      </div>
                      <div class="tab-pane container fade" id="step4">
                        <div class="pro-heading">
                            <h3>Confirmemos tu Servicio</h3>
                          </div>
                        <div class="form-detail">
                          <div class="form-row">
                            <div class="form-group col-md-4">
                              <input type="text" name="username" id="username" value="<?php if(isset($user_detail->address)) { echo $user_detail->address; }  ?>" required="" placeholder="Nombres y Apellidos">
                            </div>
                            <div class="form-group col-md-4">
                            <input type="email" name="email" value="<?php if(isset($user_detail->email)) { echo $user_detail->email; }  ?>" required="" id="email" placeholder="E-mail">
                            </div>
                            <div class="form-group col-md-4">
                              <input type="text" name="mobile_number" value="<?php if(isset($user_detail->mobile_number)) { echo $user_detail->mobile_number; }  ?>" required="" id="mobile_number" placeholder="Telefono">
                            </div>
                            <div class="form-group col-md-12">
                              <textarea name="description" id="description"  required="" placeholder="Comentario sobre tu servicio"></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
  
                    <div style="overflow:auto;">
                      <div  class="form-btn">
                        <!-- <button type="button" class="btn next-btn" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                        <button type="button" class="btn pre-btn" id="nextBtn" onclick="nextPrev(1)">Next</button>
 -->            
                        <button type="button" class="btn next-btn" id="prevBtn" >Previous</button>
                        <button type="button" class="btn pre-btn" id="nextBtn" >Next</button>
                        <button type="button" class="btn pre-btn"  id="sub_mit">Submit</button> 
                        <button type="submit" class="btn pre-btn" id="submit_form" style="display: none;" >Submit</button>
                      </div>
                    </div>
                    
                  </form>
                </div>

             
          </div>


        </div>
      </div>
      
      </div>
  </div>
</section>

@endsection

