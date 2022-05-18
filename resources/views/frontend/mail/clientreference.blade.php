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

 <body style="font-family: lato; padding: 0; margin: 0;">
  <div style="display: block; width: 100%; margin: 0; background: #fff;">
    <div style="background: #f5f5f5; display: inline-block; width: 100%; padding: 10px 0; text-align: center;">
       <div><img  style="width: 20%" src="{{isset($user['logo'])?$user['logo']:url('img/logo/logo-svg.png')}}"></div>
    </div>
    </div>

      <div style="display: block; width: 70%; margin: 50px auto;">
        <div style="display: inline-block; width: 100%; border-bottom: solid 2px #ddd; padding-bottom:10px; margin-bottom: 10px;">
         <div style="display: inline-block; float: left; width: 75%; padding-top: 20px;">
            <h4 style="margin: 0; font-weight: 600; font-size: 18px;">Hola {{$user['username']}}</h4>
            <p style="margin: 5px 0;">{{$user['email']}}</p>
         </div>
         <div style="display: inline-block; float: right;">
            @if(!empty($user['avatar_location']))
               <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{isset($user['avatar_location'])?$user['avatar_location']:url('img/logo/logo.jpg')}}">
            @endif
         </div>
       </div>
       <div style="display: inline-block; width: 100%; margin-top: 30px;">
         <h3 style="color: #000; ">Obtén Refrencias de Clientes Anteriores.</h3>
         <h4 style="color: #808080; line-height: 23px;font-size: 18px;">Las evaluaciones positivas aumentarán considerablemente tus
          <br>posibilidades de ser contratado.</h4>
          <h5 style="color: #808080; line-height: 23px; font-size: 18px; font-weight: 500; padding-left: 120px;">Los profesionales que ofrecen servicios profesionales de<span style="color: #000; font-weight: 600;"> Electricidad</span>
           <br> en tu área tienen un promedio de <span style="color: #000; font-weight: 600">3 evaluaciones.</span></h5>
     </div>
     <div class="client-box" style="background-color: #f1f1f1; padding: 10px 0px">
       <h5 style="color: #000; line-height: 23px; font-size: 18px; font-weight: 600; padding-left: 4em;">Ingresa las direcciones de correo electrónico de tus clientes.</h5>
     
    <!--  <div class="txt-box2" style="width: 90%; margin: 20px 10px 20px 70px;">
            <input type="text" id="fname" name="firstname" placeholder="Cliente 1" style="width: 90%;
            padding: 16px; border: 1px solid #ccc;  resize: vertical;outline: none;">
      </div> -->
      
      <div class="plus-icon" style="text-align: center;">
      <a href="{{url('mail')}}"  style="text-decoration: none;"><h5 style="color: #000; font-weight: 600; font-size: 18px;"><span style="background-color: #fb3100;
      width: 60px; height: 60px;color: #fff;display: inline-block; line-height: 60px; border-radius: 50px;font-size: 55px; vertical-align: middle; margin-right: 10px;">+</span>haga clic para agregar correo electrónico</h5></a>
    </div>
     <h5 style="color: #000; line-height: 23px; font-size: 18px; font-weight: 600; padding-left: 4em;">Escribe un mensaje para enviar a tus clientes anteriores.</h5>
  
  <div class="txt-box" style="width: 90%; margin: 20px 10px 20px 70px;">
     <!--  <textarea id="subject" name="subject" placeholder="Your Message" style="height:100px; width: 91.1%;
    padding: 12px;border: 1px solid #ccc; border-radius: 4px; resize: vertical; outline: none;">
    </textarea> -->
      </div>
  </div>
  <div class="button" style="margin: 30px 0px;">
    <button style="background: #fc3100; display: block; margin: 0 auto; min-width: 180px; border: solid 1px #fc3100; box-shadow: none; height: 55px; border-radius: 40px; color: #fff; font-size: 16px;">Enviar Mensaje</button>
  </div>

</div>
<div style="width: 100%; display: inline-block; padding: 40px 0; background: #f5f5f5; text-align: center;">
        
      </div>
 </body>
</html>
