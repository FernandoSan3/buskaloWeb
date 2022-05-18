@extends('frontend.layouts.app')

@section('content')
   
<div class="header-profile">


<div id="wrapper" class="toggled left-sidebar">
  <!-- Sidebar -->
  @include('frontend.company.profile_sidebar')
  <!-- /#sidebar-wrapper -->

 <!-- Page Content -->
  <div id="page-content-wrapper">
    <div class="container-fluid">
      <div class="right-sidebar ">
        <!-- Tab panes -->
        <div class="tab-content">

         
          <div class="tab-pane active" id="perfil">
            <div class="contractor-profile-sec">
              <div class="profile-progress">
               {{--  <div class="progress">
                  <div class="progress-bar" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">60% Complete Profile</div>
                </div> --}}
              </div>
              <div class="profile-head">
                <div class="media">
                  
                  <div class="media-body row"> Calificación y comentarios
                    
                  </div>
                </div>
              </div>

              {{ html()->form('POST', route('frontend.company.company_profile.store_review_by_company'))->attribute('enctype', 'multipart/form-data')->open() }}

              <div class="profile-update mt-5">
                <div class="row">

              <input type="hidden" name="_token" value="{{ csrf_token() }}">


              <input type="hidden" name="service_request_id" value="{{isset($_GET['serviceId'])?$_GET['serviceId']:''}}">
              <input type="hidden" name="to_user" value="{{isset($_GET['prouserId'])?$_GET['prouserId']:''}}">

                  <div class="col-md-9">
                    <div class="profile-form-left">
                      <div class="form-row">
                        <span class="sm-heading float-left">Precio</span>
                        <div class="form-group col-md-12 rate">
                          <input type="radio" name="price" id="pri_star5"  value="5" />
                          <label for="pri_star5" title="text">5 stars</label>
                          <input type="radio" name="price" id="pri_star4"  value="4" />
                          <label for="pri_star4" title="text">4 stars</label>
                          <input type="radio" name="price" id="pri_star3"  value="3" />
                          <label for="pri_star3" title="text">3 stars</label>
                          <input type="radio" name="price" id="pri_star2"  value="2" />
                          <label for="pri_star2" title="text">2 stars</label>
                          <input type="radio" name="price" id="pri_star1"  value="1" />
                          <label for="pri_star1" title="text">1 star</label>
                         
                        </div>                           
                      </div>

                      <div class="form-row">
                        <span class="sm-heading float-left">Puntualidad</span>
                        <div class="form-group col-md-12 rate">
                          <input type="radio" name="puntuality" id="pun_star5"  value="5" />
                          <label for="pun_star5" title="text">5 stars</label>
                          <input type="radio" name="puntuality" id="pun_star4"  value="4" />
                          <label for="pun_star4" title="text">4 stars</label>
                          <input type="radio" name="puntuality" id="pun_star3"  value="3" />
                          <label for="pun_star3" title="text">3 stars</label>
                          <input type="radio" name="puntuality" id="pun_star2"  value="2" />
                          <label for="pun_star2" title="text">2 stars</label>
                          <input type="radio" name="puntuality" id="pun_star1"  value="1" />
                          <label for="pun_star1" title="text">1 star</label>
                         
                        </div>                           
                      </div>

                      <div class="form-row">
                        <span class="sm-heading float-left">Servicio</span>
                        <div class="form-group col-md-12 rate">
                          <input type="radio" name="service" id="ser_star5"  value="5" />
                          <label for="ser_star5" title="text">5 stars</label>
                          <input type="radio" name="service" id="ser_star4"  value="4" />
                          <label for="ser_star4" title="text">4 stars</label>
                          <input type="radio" name="service" id="ser_star3"  value="3" />
                          <label for="ser_star3" title="text">3 stars</label>
                          <input type="radio" name="service" id="ser_star2"  value="2" />
                          <label for="ser_star2" title="text">2 stars</label>
                          <input type="radio" name="service" id="ser_star1"  value="1" />
                          <label for="ser_star1" title="text">1 star</label>
                         
                        </div>                           
                      </div>


                      <div class="form-row">
                        <span class="sm-heading float-left">Calidad</span>
                        <div class="form-group col-md-12 rate">
                          <input type="radio" name="quality" id="qua_star5"  value="5" />
                          <label for="qua_star5" title="text">5 stars</label>
                          <input type="radio" name="quality" id="qua_star4"  value="4" />
                          <label for="qua_star4" title="text">4 stars</label>
                          <input type="radio" name="quality" id="qua_star3"  value="3" />
                          <label for="qua_star3" title="text">3 stars</label>
                          <input type="radio" name="quality" id="qua_star2"  value="2" />
                          <label for="qua_star2" title="text">2 stars</label>
                          <input type="radio" name="quality" id="qua_star1"  value="1" />
                          <label for="qua_star1" title="text">1 star</label>
                         
                        </div>                           
                      </div>


                      <div class="form-row">
                        <span class="sm-heading float-left">Amabilidad</span>
                        <div class="form-group col-md-12 rate">
                          <input type="radio" name="amiability" id="ami_star5"  value="5" />
                          <label for="ami_star5" title="text">5 stars</label>
                          <input type="radio" name="amiability" id="ami_star4"  value="4" />
                          <label for="ami_star4" title="text">4 stars</label>
                          <input type="radio" name="amiability" id="ami_star3"  value="3" />
                          <label for="ami_star3" title="text">3 stars</label>
                          <input type="radio" name="amiability" id="ami_star2"  value="2" />
                          <label for="ami_star2" title="text">2 stars</label>
                          <input type="radio" name="amiability" id="ami_star1"  value="1" />
                          <label for="ami_star1" title="text">1 star</label>
                         
                        </div>                           
                      </div>
                      

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <textarea type="textarea" class="form-control"  name="review"  placeholder="Agregar un comentario"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>

                  

                <div class="col-md-12 text-center">
                <button type="submit" class="btn opp-btn">Enviar Calificación</button>
                </div>
                </div>

              </div>
            {{ html()->form()->close() }}

            </div>
          </div>

   

       </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
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
