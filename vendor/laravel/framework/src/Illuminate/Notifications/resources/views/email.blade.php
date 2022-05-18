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
       <div><img  style="width: 20%" src="{{isset($buskalologo)?$buskalologo:url('img/logo/logo.svg')}}"></div>
    </div>

      <div style="display: block; width: 70%; margin: 50px auto;">
        <div style="display: inline-block; width: 100%; border-bottom: solid 2px #ddd; padding-bottom:10px; margin-bottom: 10px;">
         <div style="display: inline-block; float: left; width: 75%; padding-top: 20px;">
            <h4 style="margin: 0; font-weight: 600; font-size: 18px;">
                @if (! empty($greeting))
                # {{ $greeting }} {{$username}}
                @else
                @if ($level === 'error')
                # @lang('Whoops!')
                @else
                 @lang('Hola ') {{$username}}
                @endif
                @endif
           </h4>
            <p style="margin: 5px 0;">{{$email}}</p>
         </div>
       
         <div style="display: inline-block; float: right;">
            @if(!empty($avatar_location))
               @if($user_group_id==2)
               <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{url('img/user/profile/'.$avatar_location)}}">
               @elseif($user_group_id==3)
                <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{url('img/contractor/profile/'.$avatar_location)}}">
               @else
               <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{url('img/company/profile/'.$avatar_location)}}">
               @endif
            @else
            <img style="width: 80px; height: 80px; border-radius: 50%;" src="{{$user_icon}}">
            @endif
         </div>
       </div>
       <div style="display: inline-block; width: 100%; margin-top: 30px;">
          <h4 style="font-size: 16px; color: #808080; font-weight: 600; margin: 0 0 20px;">
            {{-- Intro Lines --}}
            @foreach ($introLines as $line)
            {!! $line !!}

            @endforeach
          </h4>
          <a href="{{$actionUrl}}" style="text-decoration: none;"><button style="background: rgb(237,82,22); display: block; margin: 0 auto; min-width: 180px; border: solid 1px #e84621; box-shadow: none; height: 50px; border-radius: 40px; color: #fff; font-size: 16px; cursor: pointer;">
            {{-- Action Button --}}
            @isset($actionText)
             {!! $actionText !!}
            @endisset

      </button></a>
          <h4 style="font-size: 16px; color: #808080; font-weight: 600; margin: 20px 0 20px;">
            {{-- Outro Lines --}}
            @foreach ($outroLines as $line)
            {!! $line !!}

            @endforeach
          <!--  Thank you for using our apllication! --></h4>
          <h5 style="font-size: 15px; line-height: 26px; margin: 0; color: #808080;">

            {{-- Salutation --}}
            @if (! empty($salutation))
            {!! $salutation !!}
            @else
            @lang('Gracias por usar nuestra plataforma!')<br>
             Búskalo
            @endif
            </h5>
          <div style="display: inline-block;width: 100%; margin-top: 10px;">
            <p style="font-size: 14px; color: #808080;">
                Si el botón no funciona copie y pegue el link es su URL,si el problema persiste por favor comuníquese con nosotros a <a style="color: #e74621; text-decoration: none;" href="#">soporte@buskalo.com</a>
            </p>
          </div>
       </div>
     </div>
     <div style="width: 100%; display: inline-block; padding: 20px 0; background: #f5f5f5; text-align: center;">
        <div style="margin-top: 10px;">
          <img src="{{url('img/logo/footer-logo.png')}}">
        </div>
     </div>
  </div>

 </body>
</html>
