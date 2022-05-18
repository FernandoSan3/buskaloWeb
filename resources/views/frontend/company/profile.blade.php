@extends('frontend.layouts.app')

@section('content')

<section class="inner-page">
  <div class="comp-profile mx">
    <div class="container">
      <div class="prof-inner">
        <div class="row">
          <div class="col-md-6">
            <div class="cprof-detail">
              <div class="prof-head">
                <p><img src="{{ url('img/frontend/check3.png') }}">Perfil Verificado</p>
                <h2><?php echo isset($company->username) && !empty($company->username) ? $company->username : ''; ?></h2>
              </div>
              <ul class="prof-list">
                <li><h6>RUC</h6>: <?php echo isset($company->ruc_no) && !empty($company->ruc_no) ? $company->ruc_no : ''; ?></li>
                <li><h6>Ano de Contitucion</h6>: <?php echo isset($company->year_of_constitution) && !empty($company->year_of_constitution) ? $company->year_of_constitution : ''; ?></li>
                {{-- <li><h6>Empleados</h6>: 25</li> --}}
                <li><h6>Direccion</h6>: <?php echo isset($company->address) && !empty($company->address) ? $company->address : ''; ?></li>
                <li><h6>Contactos</h6>: <?php echo isset($company->mobile_number) && !empty($company->mobile_number) ? $company->mobile_number : ''; ?></li>
                <li><h6>Direccion Web</h6>: <?php echo isset($company->website_address) && !empty($company->website_address) ? $company->website_address : ''; ?></li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="cprof-img">
              <img src="{{ url('img/frontend/prof-img.png') }}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="build-sec ">
    <div class="container">
      <div class="build-inner media p-4">
        <img src="{{ url('img/frontend/building.jpg') }}" class="mr-3">
        <div class="media-body">
          <p><?php echo isset($company->profile_description) && !empty($company->profile_description) ? $company->profile_description : ''; ?></p>
        </div>
        {{-- <div class="mark-div"><img src="{{ url('img/frontend/shapes.png') }}"></div> --}}
      </div>
    </div>
  </div>

  <section class="comp-service mx">
    <div class="container">
      <div class="heading">
        <h4 class="orange">Servicio:</h4>
        <h1>Electricidad</h1>
      </div>
      <ul class="serv-list">
        <li><i class="fa fa-check"></i>Instalacion de tomacorrientes</li>
        <li><i class="fa fa-check"></i> Puntos de luz</li>
        <li><i class="fa fa-check"></i>Distribucion de Suministros</li>
        <li><i class="fa fa-check"></i> Supervicion de construccones</li>
        <li><i class="fa fa-check"></i>Conexiones generales</li>
        <li><i class="fa fa-check"></i> Diseno de planos electricos</li>
      </ul>

      <div class="prof-service mt-5">
        <div class="row">
          <div class="col-md-4">
            <ul class="list">
              <div class="list-head">
                <img src="{{ url('img/frontend/comp1.png') }}"> <h3>Metodos de pago</h3>
              </div>
              <li><i class="fa fa-check"></i>Efectivo</li>
              <li><i class="fa fa-check"></i>Cheque</li>
              <li><i class="fa fa-check"></i>Trasferencia Bancaria</li>
              <li><i class="fa fa-check"></i> Tarejta de credito</li>
              <li><i class="fa fa-check"></i>Tarejta de dedito</li>
              <li><i class="fa fa-check"></i>Otros</li>
            </ul>
          </div>
          <div class="col-md-4">
            <ul class="list list-mid">
              <div class="list-head">
                <img src="{{ url('img/frontend/comp2.png') }}"> <h3>Redes Sociales</h3>
              </div>
              <li><h6>Facebook</h6><span><?php if(isset($social)){echo $social->facebook_url;}?></span></li>
              <li><h6>Instagram</h6><span><?php if(isset($social)){echo $social->instagram_url;}?></span></li>
              <li><h6>Linkedin</h6><span><?php if(isset($social)){echo $social->linkedin_url;}?></span></li>
              <li><h6>Twitter</h6><span><?php if(isset($social)){echo $social->twitter_url;}?></span></li>
              
              <li><h6>Otros</h6><span><?php if(isset($social)){echo $social->other;}?></span></li>
            </ul>
          </div>
          <div class="col-md-4">
            <ul class="list">
              <div class="list-head">
                <img src="{{ url('img/frontend/comp3.png') }}"> <h3>Area de cobertura</h3>
              </div>
              <li><i class="fa fa-check"></i>Barrio</li>
              <li><i class="fa fa-check"></i>Ciudad</li>
              <li><i class="fa fa-check"></i>Canton</li>
              <li><i class="fa fa-check"></i>Provincia</li>
              <li><i class="fa fa-check"></i>Nacional</li>
            </ul>
          </div>
        </div>
    </div>

    </div> 
  </section>

  <section class="innereview-sec mx">
    <div class="container">
      <div class="rev-heading">
          <h6>4.7 <sup><i class="fa fa-star"></i></sup></h6>
        <p>4 Clientes opinaron acerca de este Perfil</p>
      </div>

        <div class="row">
          <div class="col-md-6">
            <div class="media p-3 company-review">
              <img src="{{ url('img/frontend/user-icon.png') }}" class="mr-3 mt-3" style="width:60px;">
              <div class="media-body">
                <p><p>Encontrar nuevos clientes no siempre es fácil. Búskalo ofrece una solución que ahorra tiempo en el desarrollo empresarial. Es una empresa joven y dinámica y ¡un socio de confianza! ¡Definitivamente recomiendo la esta Plataforma innovadora a todos los profesionales!</p>
                <h6>Janeth Bautista, Abogado</h6></p>
                <div class="user-right">
                  <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="media p-3 company-review">
              <img src="{{ url('img/frontend/user-icon.png') }}" class="mr-3 mt-3" style="width:60px;">
              <div class="media-body">
                <p><p>Encontrar nuevos clientes no siempre es fácil. Búskalo ofrece una solución que ahorra tiempo en el desarrollo empresarial. Es una empresa joven y dinámica y ¡un socio de confianza! ¡Definitivamente recomiendo la esta Plataforma innovadora a todos los profesionales!</p>
                <h6>Janeth Bautista, Abogado</h6></p>
                <div class="user-right">
                  <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="media p-3 company-review">
              <img src="{{ url('img/frontend/user-icon.png') }}" class="mr-3 mt-3" style="width:60px;">
              <div class="media-body">
                <p><p>Encontrar nuevos clientes no siempre es fácil. Búskalo ofrece una solución que ahorra tiempo en el desarrollo empresarial. Es una empresa joven y dinámica y ¡un socio de confianza! ¡Definitivamente recomiendo la esta Plataforma innovadora a todos los profesionales!</p>
                <h6>Janeth Bautista, Abogado</h6></p>
                <div class="user-right">
                  <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="media p-3 company-review">
              <img src="{{ url('img/frontend/user-icon.png') }}" class="mr-3 mt-3" style="width:60px;">
              <div class="media-body">
                <p><p>Encontrar nuevos clientes no siempre es fácil. Búskalo ofrece una solución que ahorra tiempo en el desarrollo empresarial. Es una empresa joven y dinámica y ¡un socio de confianza! ¡Definitivamente recomiendo la esta Plataforma innovadora a todos los profesionales!</p>
                <h6>Janeth Bautista, Abogado</h6></p>
                <div class="user-right">
                  <p><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                    <i class="fa fa-star"></i><i class="fa fa-star"></i></p>
                </div>
              </div>
            </div>
          </div>

        </div>

    </div>
  </section>

  <div class="certificate-sec">
    <div class="container">
      <div class="chead">
        <h3><img src="{{ url('img/frontend/certificate.png') }}">Certificaiones</h3>
      </div>
      <div class="cer-list">
        <div class="row">
          <div class="col-md-3">
            <div class="cer-img">
              <img src="{{ url('img/frontend/s5.jpg') }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="cer-img">
              <img src="{{ url('img/frontend/s5.jpg') }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="cer-img">
              <img src="{{ url('img/frontend/s5.jpg') }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="cer-img">
              <img src="{{ url('img/frontend/s5.jpg') }}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="certificate-sec mx">
    <div class="container">
      <div class="chead">
        <h3><img src="{{ url('img/frontend/certificate.png') }}">Galeria</h3>
      </div>
      <div class="cer-list">
        <div class="row">
          <div class="col-md-3">
            <div class="cer-img">
              <img src="{{ url('img/frontend/s5.jpg') }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="cer-img">
              <img src="{{ url('img/frontend/s5.jpg') }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="cer-img">
              <img src="{{ url('img/frontend/s5.jpg') }}">
            </div>
          </div>
          <div class="col-md-3">
            <div class="cer-img">
              <img src="{{ url('img/frontend/s5.jpg') }}">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
