
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
        <div><img  style="width: 20%" src="{{isset($demo->logo)?$demo->logo:url('img/logo/logo-svg.png')}}"></div>
      </div>
    
  	  <div style="display: block; width: 70%; margin: 50px auto;">
        <div style="display: inline-block; width: 100%; border-bottom: solid 2px #ddd; padding-bottom:10px; margin-bottom: 10px;">
          <div style="display: inline-block; float: left; width: 75%; padding-top: 20px;">
            <h4 style="margin: 0; font-weight: 600; font-size: 18px;"> Hola  {{$demo->username}} </h4>
            <p style="margin: 5px 0;">{{$demo->email}}</p>
          </div>
       
          <div style="display: inline-block; float: right;">
            @if(!empty($demo->avatar_location))
                @if($demo->user_group_id==2)
                <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{url('img/user/profile/'.$demo->avatar_location)}}">
                @elseif($demo->user_group_id==3)
                <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{url('img/contractor/profile/'.$demo->avatar_location)}}">
                @else
                <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{url('img/company/profile/'.$demo->avatar_location)}}">
                @endif
            @else
            <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{isset($demo->user_icon)?$demo->user_icon:url('img/logo/logo.jpg')}}">
            @endif
          </div>
        </div>
      </div>
      <div style="display: block; width: 70%; margin: 50px auto; font-size: 15px; font-weight: 500;">
          <p style="text-decoration: none;">
            Un cliente está buscando los servicios que tu ofreces, aprovecha esta
            <a style="color: #e74621 !important; text-decoration: none;">oportunidad</a>.
          </p>
          <br>
          <div class="col-md-4 col-sm-12">
            <div style="background: #f5f5f5; display: inline-block; width: 100%; padding: 10px 0; text-align: center; border-width: 50px;">          
              <div class="text-center">
                <div class="star-div">
                  <div class="tab-bg" >
                    <div class="right-added">
                      <div class="right-header">
                        <div>
                          <table style="width:100%">
                            <tr>
                            <td><p class="divin"><img src="{{asset('img/frontend/dt.png')}}" style="width: 30px; margin-bottom: -12px;"></p>
                            <span class="divin">Detalles del proyecto</span></td>
                            </tr>
                          </table>
                        </div>
                        <br>
                    
                        <div class="booking-list">
                          @if($question)
                            @foreach($question as $key =>$value)
                              <h6 style="margin: 0px 0; font-size: 16px;">{{$value['question']}}<h6>
                              <li style=" color: #e74621">
                                  <h6 style="margin: 5px 0; color: #808080; font-size: 14px; font-weight: 400;">{{$value['option']?$value['option']:'Sin respuesta'}}<h6>
                              </li>
                            @endforeach
                          @endif
                          
                            <h6  style="margin: 0px 0; font-size: 16px;">Ciudad</p> 
                            <li style=" color: #e74621">
                              <h6 style="margin: 5px 0; color: #808080; font-size: 14px; font-weight: 400;">{{$demo->city_name}}<h6>
                            </li>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
           </div>
          </div>
          <div style="display: inline-block;width: 100%; margin-top: 10px; font-size: 15px;">
            <a style=" text-decoration: none;">Abre la</a>
            <a style="color: #e74621; text-decoration: none;">aplicación</a>
            <a style=" text-decoration: none;">, ingresa a tu cuenta </a>
            <img style="width: 40px; height: 40px; margin-bottom: -12px;" src="{{url('img/frontend/pro-btn.png')}}">
            <a style=" text-decoration: none;"> y adquiere esta oportunidad ahora!</a>
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
          <img src="{{url('img/logo/footer-logo.png')}}">
        </div>
      </div>
    </div>
  </body>
</html> 

