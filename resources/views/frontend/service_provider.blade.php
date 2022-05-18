
<!DOCTYPE html>
@langrtl
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl
  @include('frontend.includes.head')

  <body>
  <?php
  $route = Route::current();
  $route_name = Route::currentRouteName();
  ?>
    
  @section('title', app_name() . ' | ' . __('navs.general.home'))

<div class="user-table">
  <div class="container-fluid">
  <table id="example" class="table table-striped table-bordered " style="width:100%">
    <tr class="user-igm">
        <th class="logo-igm"><img class="img-fluid img-responsive" src="http://localhost/buskalo/www/public/img/frontend/logo.svg"></th>
        <td><img class="img-fluid img-responsive" src="http://localhost/buskalo/www/public/img/frontend/u1.png"></td>
         <td><img class="img-fluid img-responsive" src="http://localhost/buskalo/www/public/img/frontend/u2.png"></td>
         <td><img class="img-fluid img-responsive" src="http://localhost/buskalo/www/public/img/frontend/u3.png"></td>
    </tr>
    <tbody>
      <tr>
        <th>Tipo de Profesional o empresa</th>
          <td>Ingeniero Eléctrico(Independiente)</td>
          <td>Ingeniero Eléctrico(Independiente)</td>
          <td>Compañía de servicios elétricos</td>
      </tr>
      <tr>
        <th>Ano de constitucion o experiencia</th>
        <td>4 años</td>
        <td>16 años</td>
        <td>Desde 2010 (10 años)</td>
      </tr>
      <tr>
         <th>Número de empleados</th>
         <td>2</td>
        <td>4</td>
        <td>22</td>
      </tr>
      <tr>
         <th>Dirección</th>
         <td>Avenida del Bombero 124</td>
        <td>La Atarazana Mz 304 Villa 19</td>
        <td>Francisco de Orellana 234</td>
      </tr>
      <tr>
         <th>Pagina Web</th>
         <td>www.mendozaelectric.com</td>
        <td></td>
        <td>www.electroshock.com</td>
      </tr>
      <tr>
         <th>Métodos de pago</th>
         <td>Efectivo, cheque</td>
        <td>Efectivo</td>
        <td>Efectivo, cheque, tarjeta de crédito, débito, transferencias bancarias</td>
      </tr>
      <tr>
         <th>Redes sociales disponibles</th>
         <td>Twitter, Facebook</td>
        <td>Facebook, Instagram</td>
        <td>Facebook, Linkedin</td>
      </tr>
      <tr>
         <th>Certificaciones</th>
         <td>ESPE 2018 Certificado de energia sustentable</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
         <th>Servicios</th>
         <td>- Instalación Residencial y Comercial <br> - Domótica</td>
        <td>- Instalación Residencial y Comercial <br> 
          - Medidores trifasicos <br>
          - Alambrado público <br>
        - Suspervisión de construcciones</td>
        <td> - Servicio Comercial e Industrial</td>
      </tr>
      <tr>
         <th>Calificación Global</th>
         <td>
          <div class="star-list">
            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i>
          </div>
         </td>
        <td><div class="star-list">
            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
          </div></td>
        <td><div class="star-list">
            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i>
          </div></td>
      </tr>
      <tr>
         <th>Calificaciones</th>
         <td>
           <ul class="rating-list">
             <li><span>Precio</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
             <li><span>Puntualidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
             <li><span>Servicio</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </div>
             </li>
             <li><span>Calidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
             <li><span>Amabilidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
           </ul>
         </td>
        <td>
           <ul class="rating-list">
             <li><span>Precio</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </div>
             </li>
             <li><span>Puntualidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </div>
             </li>
             <li><span>Servicio</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </div>
             </li>
             <li><span>Calidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
              </div>
             </li>
             <li><span>Amabilidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
           </ul>
         </td>
        <td>
           <ul class="rating-list">
             <li><span>Precio</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
             <li><span>Puntualidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
             <li><span>Servicio</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
             <li><span>Calidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
             <li><span>Amabilidad</span> 
              <div class="star-list">
                <i class="fa fa-star"></i>
                <i class="fa fa-star"></i>
                <i class="fa fa-star-o"></i>
                <i class="fa fa-star-o"></i>
              </div>
             </li>
           </ul>
         </td>
      </tr>
      <tr>
         <th>Numero de resenas</th>
         <td>12</td>
        <td>9</td>
        <td>34</td>
      </tr>
    </tbody>
  </table>
</div>
</div>
  
  @include('frontend.includes.footer')
  @include('includes.partials.ga')

  </body>
</html>


