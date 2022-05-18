<?php
$socilaLink=DB::table('site_settings')->first();
?>

<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-2 col-md-12 col-sm-12">
        <div class="foot-detail">
          <a href="{{url('/')}}">
            <img src="{{ url('img/frontend/logo-f.png') }}">
        </a>

    </div>
</div>
<div class="col-lg-2 col-md-6 col-sm-6">
    <div class="useful-link">
      <h4 class="foot-head">@lang('labels.frontend.company.profile.customers')</h4>
      <ul>
        <li><a href="{{url('redirect_register')}}">@lang('labels.frontend.company.profile.check_in')</a></li>
        <li><a href="{{url('how-does-it-work/user')}}">@lang('labels.frontend.company.profile.how_does_it_work')</a>
        </li>
        <li><a href="{{url('faq/user')}}">@lang('labels.frontend.company.profile.help_users')</a>
        </li>
    </ul>
</div>
</div>
<div class="col-lg-2 col-md-6 col-sm-6">
    <div class="useful-link">
      <h4 class="foot-head">@lang('labels.frontend.company.profile.professional')</h4>
      <ul>
        <li><a href="{{url('profesional/register')}}">@lang('labels.frontend.company.profile.join_register')</a></li>
        <li><a href="{{url('how-does-it-work/pro')}}">@lang('labels.frontend.company.profile.how_does_it_work')</a>
        </li>
        <li><a href="{{url('faq/pro')}}">@lang('labels.frontend.company.profile.help_profesional')</a>
        </li>

    </ul>
</div>
</div>
<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="useful-link">
      <h4 class="foot-head">Búskalo</h4>
      <ul>
        <li><a href="{{url('about-us')}}">@lang('labels.frontend.company.profile.about_us')</a></li>
        <li><a href="{{url('work-with-us')}}">@lang('labels.frontend.company.profile.work_with_us')</a></li>
        <li><a href="{{url('contact')}}">@lang('labels.frontend.company.profile.contact_us')</a></li>
        <li><a href="{{url('characteristics-conditions')}}">@lang('labels.frontend.company.profile.terms_and_conditions')</a></li>
           <!--  <li><a href="{{url('faq')}}">@lang('Preguntas más frecuentes
           ')</a>      </li> -->
       </ul>
   </div>
</div>

<div class="col-lg-3 col-md-6 col-sm-6">
    <div class="useful-link">
      <h4 class="foot-head">@lang('labels.frontend.company.profile.follow_us_on')</h4>
      <div class="social-link">
        <a href="{{isset($socilaLink->facebook)?$socilaLink->facebook:'#'}}"><i class="fa fa-facebook"></i></a>
        <a href="{{isset($socilaLink->instagram)?$socilaLink->instagram:'#'}}"><i class="fa fa-instagram"></i></a>
        <!-- <a href="{{isset($socilaLink->twitter)?$socilaLink->twitter:'#'}}"><i class="fa fa-twitter"></i></a> -->
        <a href="{{isset($socilaLink->youtube)?$socilaLink->youtube:'#'}}"><i class="fa fa-youtube"></i></a>
    </div>

    <div class="download-btns">
        <a class="app-btn" href="https://apps.apple.com/us/app/búskalo/id1580560610">
          <div class="app-icon">
            <img src="{{ url('img/frontend/apple.png') }}">
        </div>
        <div class="app-txt">
            <p>@lang('labels.frontend.company.profile.download_on_the')</p>
            <h5 class="appsotre-font">@lang('labels.frontend.company.profile.app_store')</h5>
        </div>
    </a>
    <a class="app-btn" href="https://play.google.com/store/apps/details?id=com.wdp.Buskalo">
      <div class="app-icon">
        <img src="{{ url('img/frontend/play-store.png') }}">
    </div>
    <div class="app-txt">
        <p>@lang('labels.frontend.company.profile.get_it_on')</p>
        <h5>@lang('labels.frontend.company.profile.google_play')</h5>
    </div>
</a>
</div>

</div>
</div>

</div>
</div>
</footer>

<!-- <div class="bottom-footer">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="download-btns">
              <a class="app-btn" href="#">
                <div class="app-icon">
                  <img src="{{-- url('img/frontend/google-play.png') --}}">
                </div>
                <div class="app-txt">
                  <p>Available on the</p>
                  <h5>Google Play</h5>
                </div>
              </a>
              <a class="app-btn" href="#">
                <div class="app-icon">
                  <img src="{{-- url('img/frontend/iphone.png') --}}">
                </div>
                <div class="app-txt">
                  <p>Available on the</p>
                  <h5>App Store</h5>
                </div>
              </a>
            </div> 
      </div>
      <div class="col-md-6">
        <div class="social-link">
          <a href="#"><i class="fa fa-facebook"></i></a>
          <a href="#"><i class="fa fa-instagram"></i></a>
          <a href="#"><i class="fa fa-twitter"></i></a>
          <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
      </div>
    </div>
  </div>
</div> -->




{{ script('js/popper.min.js') }}
{{ script('js/bootstrap.min.js') }}
<!-- {{ script('js/bootstrap.bunde.js') }} -->


{{ script('js/jquery.dataTables.min.js') }}
{{ script('js/dataTables.rowReorder.min.js') }}
{{ script('js/dataTables.responsive.min.js') }}
{{ script('select2/dist/js/select2.min.js') }}
{{ script('js/bootstrap-datetimepicker.js') }}
{{ script('js/owl.carousel.min.js') }}


<script type="text/javascript">

  function showLoader(){
    $('body').append('<div class="site-loader"> <img src="{{url('img/logo/loading.gif')}}"></div>');
    return '';
}
function hideLoader(){  
    $('.site-loader').remove();
    return '';
}


$('#editCertificate').on('show.bs.modal', function (e) {
  var img = $(e.relatedTarget).data('filename');
  var certid = $(e.relatedTarget).data('certid');
      //alert(img);
      $("#thanksCerification").attr("src", img);
      $("#certification_id").val(certid);
      
  });

$('#deleteCertificate').on('show.bs.modal', function (e) {
  var img = $(e.relatedTarget).data('filename');
  var certid = $(e.relatedTarget).data('certid');
      //alert(img);
      $("#thanksCerificationImg").attr("src", img);
      $("#certification_id_del").val(certid);
      
  });

$('#editSlider').on('show.bs.modal', function (e) {
  var img = $(e.relatedTarget).data('filename');
  var userid = $(e.relatedTarget).data('userid');
      //alert(img);
      $("#thanksBanner").attr("src", img);
      $("#userid").val(userid);
      
  });

$('#editPoliceRecord').on('show.bs.modal', function (e) {
  var img = $(e.relatedTarget).data('filename');
  var polRecId = $(e.relatedTarget).data('polid');
      //alert(img);
      $("#thanksPolicerecImg").attr("src", img);
      $("#pol_rec_id").val(polRecId);
      
  });

$('#deletePoliceRecord').on('show.bs.modal', function (e) {
  var img = $(e.relatedTarget).data('filename');
  var polRecId = $(e.relatedTarget).data('polid');
      //alert(img);
      $("#thanksPolicerecImg_del").attr("src", img);
      $("#pol_rec_id_del").val(polRecId);
      
  });


$('#editPhotosVideos').on('show.bs.modal', function (e) {
  var img = $(e.relatedTarget).data('filename');
  var source = $(e.relatedTarget).data('filename1');
  var pvid = $(e.relatedTarget).data('pvid');
  var vvid = $(e.relatedTarget).data('vvid');

  if(img){
    $("#gallId").val(pvid);
    $("#thanksPhotosvideosImg").attr("src", img);
    $(".file_upload2 video").hide();
         //alert(pvid);
         $('#thanksPhotosvideosImg').show();
         $('#thanksPhotosvideosImg1').hide();
         $("#gallId").show();
         $("#videoId").hide();
         $("#gallId11").show();
         $("#videoId22").hide();
     }else if(source){
        $("#videoId").val(vvid);
        $("#thanksPhotosvideosImg1").attr("src", source);
        $(".file_upload2 video")[0].load();
        //alert(vvid);
        $('#thanksPhotosvideosImg1').show();
        $('#thanksPhotosvideosImg').hide();
        $("#gallId").hide();
        $("#videoId").show();
        $("#gallId11").hide();
        $("#videoId22").show();
    }
      //alert(img);
      // $("#thanksPhotosvideosImg").attr("src", img);
      // $("#thanksPhotosvideosImg").attr("src", source);
      // $("#gallId").val(pvid);
      // $("#videoId").val(vvid);

  });



$('#deletePhotosVideos').on('show.bs.modal', function (e) {
  var img = $(e.relatedTarget).data('filename');
  var source = $(e.relatedTarget).data('filename1');
  var pvid = $(e.relatedTarget).data('pvid');
  var vvid = $(e.relatedTarget).data('vvid');
  if(img){
    $("#gallId_del").val(pvid);
    $("#thanksPhotosvideosImg_del").attr("src", img);
    $(".file_upload111 video").hide();
         //alert(img);
         $('#thanksPhotosvideosImg_del').show();
         $('#thanksPhotosvideosImg_del1').hide();
         $("#gallId_del").show();
         $("#vdoId_del").hide();
     }else if(source){
        $("#vdoId_del").val(vvid);
        $("#thanksPhotosvideosImg_del1").attr("src", source);
        $(".file_upload111 video")[0].load();
        //alert(source);
        $('#thanksPhotosvideosImg_del1').show();
        $('#thanksPhotosvideosImg_del').hide();
        $("#gallId_del").hide();
        $("#vdoId_del").show();
    }

});

</script>


<!--image cropper and upload-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">

<!--Contractor-->

@if(isset($logged_in_user->user_group_id) && $logged_in_user->user_group_id==3)

<script>  
    $(document).ready(function(){

       $image_crop = $('#image_demo').croppie({
        enableExif: true,
        viewport: {
          width:200,
          height:200,
      type:'square' //circle
  },
  boundary:{
      width:300,
      height:300
  }    
});

       $('#imgupload').on('change', function(){
        var reader = new FileReader();
        reader.onload = function (event) {
          $image_crop.croppie('bind', {
            url: event.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    reader.readAsDataURL(this.files[0]);
    $('#insertimageModal').modal('show');
});

       $('.crop_image').click(function(event){

        $image_crop.croppie('result', {
          type: 'canvas',
          size: 'viewport'
      }).then(function(response){

       var userId = $('#contractorUserId').val();

       $.ajax({
        url: "{!! URL::to('insert_contractor_profile') !!}",
        type:'POST',
        data: {"_token": "{{ csrf_token() }}","image": response,"userid": userId },
        dataType: "json",
        success:function(data)
        {
         $('#insertimageModal').modal('hide');
         $('#thumbnil').attr('src',data.profile+'?'+Math.random());
         $('#thumbnil22').attr('src',data.profile+'?'+Math.random());
     }

 })
   });
  });
   });  
</script>

<script type="text/javascript">

  $('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });

</script>


<!--Contractor-->

@elseif(isset($logged_in_user->user_group_id) && $logged_in_user->user_group_id==4)

<script>  
    $(document).ready(function(){

       $image_crop = $('#image_com_demo').croppie({
        enableExif: true,
        viewport: {
          width:200,
          height:200,
      type:'square' //circle
  },
  boundary:{
      width:300,
      height:300
  }    
});

       $('#imguploadcom').on('change', function(){
        var reader = new FileReader();
        reader.onload = function (event) {
          $image_crop.croppie('bind', {
            url: event.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    reader.readAsDataURL(this.files[0]);
    $('#insertimageModal').modal('show');
});

       $('.crop_image').click(function(event){

        $image_crop.croppie('result', {
          type: 'canvas',
          size: 'viewport'
      }).then(function(response){

       var userId = $('#contractorUserId').val();

       $.ajax({
        url: "{!! URL::to('insert_contractor_profile') !!}",
        type:'POST',
        data: {"_token": "{{ csrf_token() }}","image": response,"userid": userId },
        dataType: "json",
        success:function(data)
        {
         $('#insertimageModal').modal('hide');
         $('#thumbnil').attr('src',data.profile+'?'+Math.random());
         $('#thumbnil22').attr('src',data.profile+'?'+Math.random());
     }

 })
   });
  });
   });  
</script>

<script type="text/javascript">

  $('#OpenImgUpload').click(function(){ $('#imguploadcom').trigger('click'); });

</script>

@else

<!--user-->

<script>  
    $(document).ready(function(){

       $image_crop = $('#user_image_demo').croppie({
        enableExif: true,
        viewport: {
          width:200,
          height:200,
      type:'square' //circle
  },
  boundary:{
      width:300,
      height:300
  }    
});

       $('#imguploaduser').on('change', function(){
        var reader = new FileReader();
        reader.onload = function (event) {
          $image_crop.croppie('bind', {
            url: event.target.result
        }).then(function(){
            console.log('jQuery bind complete');
        });
    }
    reader.readAsDataURL(this.files[0]);
    $('#UserinsertimageModal').modal('show');
});

       $('.crop_user_image').click(function(event){

        $image_crop.croppie('result', {
          type: 'canvas',
          size: 'viewport'
      }).then(function(response){

       var userId = $('#UserIdProfile').val();

       $.ajax({
        url: "{!! URL::to('insert_user_profile') !!}",
        type:'POST',
        data: {"_token": "{{ csrf_token() }}","image": response,"userid": userId },
        dataType: "json",
        success:function(data)
        {
         $('#UserinsertimageModal').modal('hide');
         $('#userthumbnil').attr('src',data.profile+'?'+Math.random());


     }

 })
   });
  });
   });  
</script>
<script type="text/javascript">

  $('#OpenImgUploadUser').click(function(){ $('#imguploaduser').trigger('click'); });

</script>

<!--user-->

@endif 

<!--image cropper and upload-->





<!--validation-->


<style type="text/css">
  .allowed-submit{opacity: .5;cursor: not-allowed;}
  .valid-input{
    border:1px solid green !important;
}
.invalid-input{
    border:1px solid red !important;
}
.invalid-msg{
    color: red;
}

.extravalid-input{
    border:1px solid green !important;
}
.extrainvalid-input{
    border:1px solid red !important;
}
.extrainvalid-msg{
    color: red;
}
</style>


<script type="text/javascript">
  $(document).ready(function () {

//validation for User Name REQUIRED
$('#userName').on('input', function () 
{
 var userName = $(this).val();
       //var validName = /^[a-zA-Z ]*$/;
       var validName = /^[a-zA-Zñáéíóúü_+\-':"\\|,.\/? ]*$/;
       if (userName.length == 0) 
       {
          $('.user-name-msg').addClass('invalid-msg').text("Se requiere nombre de usuario");
          $(this).addClass('invalid-input').removeClass('valid-input');
      }
      else if (userName.length < 3) 
      {
          $('.user-name-msg').addClass('invalid-msg').text("Se requiere al menos 4 caracteres");
          $(this).addClass('invalid-input').removeClass('valid-input');
      }
      else if (!validName.test(userName)) 
      {
          $('.user-name-msg').addClass('invalid-msg').text('solo se permiten caracteres y espacios en blanco');
          $(this).addClass('invalid-input').removeClass('valid-input');
          
      }
      else 
      {
          $('.user-name-msg').empty();
          $(this).addClass('valid-input').removeClass('invalid-input');
      }
  });


//validation for identityNo REQUIRED
$('#identityNo').on('input', function () 
{
 var identityNo = $(this).val();
 var validName = /^\d{10}$/;
 if (identityNo.length > 10) 
 {
  $('.identity-no-msg').addClass('invalid-msg').text("Para validar debe ser de 10 números");
  $(this).addClass('invalid-input').removeClass('valid-input');
}
else if (identityNo.length > 10) 
{
  $('.identity-no-msg').addClass('invalid-msg').text("Para validar debe ser de 10 números");
  $(this).addClass('invalid-input').removeClass('valid-input');
}
else if (!validName.test(identityNo)) 
{
  $('.identity-no-msg').addClass('invalid-msg').text('solo se permiten números');
  $(this).addClass('invalid-input').removeClass('valid-input');

}
else 
{
  $('.identity-no-msg').empty();
  $(this).addClass('valid-input').removeClass('invalid-input');
}
});


//validation for profile-title
$('#profileTitle').on('input', function () 
{
 var profileTitle = $(this).val();
       //var validName = /^[a-zA-Z ]*$/;
       var validName = /^[a-zA-Zñáéíóúü_+\-':"\\|,.\/? ]*$/;
       if (!validName.test(profileTitle)) 
       {
          $('.profile-title-msg').addClass('extrainvalid-msg').text('solo se permiten caracteres y espacios en blanco');
          $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
          
      }
      else 
      {
          $('.profile-title-msg').empty();
          $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
      }
  });

  //validation for address
  $('#address').on('input', function () 
  {
   var address = $(this).val();
         //var validName = /^[0-9a-zA-Z ]*$/;
         var validName = /^[a-zA-Zñáéíóúü0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/? ]*$/;

         if (address.length == 0) 
         {
          $('.address-msg').addClass('invalid-msg').text("La dirección es necesaria");
          $(this).addClass('invalid-input').removeClass('valid-input');
      }
      if (!validName.test(address)) 
      {
        $('.address-msg').addClass('extrainvalid-msg').text('la dirección debe tener solo caracteres alfanuméricos');
        $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
    }
    else 
    {
        $('.address-msg').empty();
        $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
    }
});


  //validation for Mobile  REQUIRED
  $('#mobileNumber').on('input', function () 
  {

   var mobileNumber = $(this).val();
   var validName = /^\d{10}$/;

   $.ajax({
    url: "{!! URL::to('check_mobile_availability') !!}",
    data:"mobile_number="+mobileNumber,
    type: "GET",
    dataType: "json",
    success:function(data)
    {
       if(data.success==false)
       {

        $('.mobile-number-msg').addClass('invalid-msg').text(data.message);
        $(this).addClass('invalid-input').removeClass('valid-input');
    }

},
error:function (){}
});

   if (mobileNumber.length > 0) 
   {

    $('.mobile-number-msg').addClass('invalid-msg').text("El número móvil debe tener solo 10 dígitos");
    $(this).addClass('invalid-input').removeClass('valid-input');
}
if (!validName.test(mobileNumber)) 
{
    $('.mobile-number-msg').addClass('invalid-msg').text('El número móvil debe tener solo 10 dígitos');
    $(this).addClass('invalid-input').removeClass('valid-input');
}
else 
{
    $('.mobile-number-msg').empty();
    $(this).addClass('valid-input').removeClass('invalid-input');
}
});

    //validation for landline
    $('#landlineNumber').on('input', function () 
    {
     var landlineNumber = $(this).val();
     var validName = /^\d{9}$|^\d{10}$/;
     if (!validName.test(landlineNumber)) 
     {
      $('.landline-number-msg').addClass('extrainvalid-msg').text('El número de oficina debe tener solo de 9 a 10 dígitos');
      $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
  }
  else 
  {
      $('.landline-number-msg').empty();
      $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
  }
});

    $('#mobile_phone_number').keyup('input', function () 
    {
        this.value = this.value.replace(/[^0-9\.]/g,'');
        var mobile = document.getElementById('mobile_phone_number');
        if(mobile.value.length!=10)
        {
           $('.mobile-number-msg').addClass('extrainvalid-msg').text('El número de teléfono debe de constar de 10 dígitos');
       }
       else
       {
           $('.mobile-number-msg').html(' ');
       }

   });
    //  $('#mobile_phone_number').keyup('input', function () 
    // {
    //        var landlineNumber = $(this).val();
    //        var validName = /^\d{10}$/;
    //         if(landlineNumber!='')
    //         {
    //            if (!validName.test(landlineNumber)) 
    //            {

    //               $('.mobile-number-msg').addClass('extrainvalid-msg').text('El número de teléfono debe de constar de 10 dígitos');
    //               $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
    //            }
    //         }
    //        else 
    //        {
    //             $('.mobile-number-msg').html(' ');
    //           $('#mobile_phone_number').removeClass('extrainvalid-input');
    //           $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
    //        }
    //   });

    $('#email').on('input', function () 
    {
     var landlineNumber = $(this).val();
     var validName = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
     if (!validName.test(landlineNumber)) 
     {
      $('.email-msg').addClass('extrainvalid-msg').text('Please include an @ and com');
      $('.email-msg').addClass('extrainvalid-input').removeClass('extravalid-input');
  }
  else 
  {
      $('.email-msg').empty();
      $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
  }
});

     //validation for officeNumber
     $('#officeNumber').on('input', function () 
     {
         var officeNumber = $(this).val();
         var validName = /^\d{9}$|^\d{10}$/;
         if (!validName.test(officeNumber)) 
         {
          $('.office-number-msg').addClass('extrainvalid-msg').text('El número de oficina debe tener solo de 9 a 10 dígitos');
          $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
      }
      else 
      {
          $('.office-number-msg').empty();
          $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
      }
  });

   //validation for url
   var urlregexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;

   $('#facebookUrl').on('input', function () 
   {
     var facebookUrl = $(this).val();
     var validName = urlregexp;
     if (!validName.test(facebookUrl)) 
     {
      $('.facebook-url-msg').addClass('extrainvalid-msg').text('URL invalida');
      $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
  }
  else 
  {
      $('.facebook-url-msg').empty();
      $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
  }
});

   $('#instagramUrl').on('input', function () 
   {
     var instagramUrl = $(this).val();
     var validName = urlregexp;
     if (!validName.test(instagramUrl)) 
     {
      $('.instagram-url-msg').addClass('extrainvalid-msg').text('URL invalida');
      $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
  }
  else 
  {
      $('.instagram-url-msg').empty();
      $(this).addClass('extravalid-input').removeClass('extranvalid-input');
  }
});

   $('#linkedinUrl').on('input', function () 
   {
     var linkedinUrl = $(this).val();
     var validName = urlregexp;
     if (!validName.test(linkedinUrl)) 
     {
      $('.linkedin-url-msg').addClass('extrainvalid-msg').text('URL invalida');
      $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
  }
  else 
  {
      $('.linkedin-url-msg').empty();
      $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
  }
});

   $('#twitterUrl').on('input', function () 
   {
     var twitterUrl = $(this).val();
     var validName = urlregexp;
     if (!validName.test(twitterUrl)) 
     {
      $('.twitter-url-msg').addClass('extrainvalid-msg').text('URL invalida');
      $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
  }
  else 
  {
      $('.twitter-url-msg').empty();
      $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
  }
});

   $('#otherUrl').on('input', function () 
   {
     var otherUrl = $(this).val();
     var validName = urlregexp;
     if (!validName.test(otherUrl)) 
     {
      $('.other-url-msg').addClass('extrainvalid-msg').text('URL invalida');
      $(this).addClass('extrainvalid-input').removeClass('extravalid-input');
  }
  else 
  {
      $('.other-url-msg').empty();
      $(this).addClass('extravalid-input').removeClass('extrainvalid-input');
  }
});



    // validation to submit the form
    // $('input').on('input',function(e){
    //    if($('#myProfileForm').find('.valid-input').length==3){

    //        $('#submits-btn').removeClass('allowed-submit');
    //        $('#submits-btn').removeAttr('disabled');
    //    }
    //   else{
    //        e.preventDefault();
    //        $('#submits-btn').attr('disabled','disabled')

    //       }
    // });

});
</script>

<!--validation-->

<script>
    $(function(){
        $(".editCerti").click(function(){
            var $linkClicked = $(this);
            var postid = $(this).attr("id");
            var postfile = $(this).attr("filename");
            //alert(postfile)
            //   $.ajax({
            //     type:'POST',
            //     url:'addlike.php',
            //     data:'id='+postid,
            //     success:function(data){
            //        if(data=="you already liked this post"){
            //            alert(data);
            //          }
            //        else{
            //           $linkClicked.html(data);
            //           }
            //     }
            // });

        });
    });

</script>


<script>
    var base_url = '{{ url("/") }}';
</script>

{{ script('js/pusher.min.js') }}

@if(isset($logged_in_user->user_group_id) && $logged_in_user->user_group_id==3)

{{ script('js/chat-contractor.js') }}
<style type="text/css">
 #chat-overlay
 {
    top: 209px !important;
}
</style>
@else
<style type="text/css">
 #chat-overlay
 {
    top: 200px !important;
}
</style>

{{ script('js/chat-user.js') }}
@endif 

{{ style('css/bootstrap-multiselect.css') }}
{{ script('js/bootstrap-multiselect.js') }}

<script type="text/javascript">
    $(document).ready(function() {
        $('#multi-select-demo').multiselect();
    });
</script>

<script type="text/javascript">
  $(document).ready(function() {

    $('#multi-select-proviences').multiselect({
      enableCaseInsensitiveFiltering: true,
      filterBehavior: 'text',
      onChange: function(option, checked, select) {

        var value_arr = [];
        var is_province = $(option).hasClass('parent_city');
        var prov_id = $(option).attr('data-prov');

        if(checked == true){

          if(is_province == true) {

            var selected_text = '.child_prov_'+prov_id;
            var total_length = $(selected_text).length;
            $(selected_text).each(function( index ) {
              if(index < total_length/2 ){                    
                value_arr.push($( this ).val()); 
            }
        });                
            
            $('#multi-select-proviences').multiselect('select', value_arr);

        } else {

            var selected_text = '.child_prov_'+prov_id;
            var selected_text_new = 'child_prov_'+prov_id;
            var total_length = $(selected_text).length;

            var cou = 0;
            $('li.active').each(function(){
              if($(this).hasClass('active') == true && $(this).hasClass(selected_text_new) == true ){
                  cou++;
              }  
          });

            if(cou == total_length/2) {

              var selected_text = '.parent_prov_'+prov_id;               
              var total_length = $(selected_text).length;
              $(selected_text).each(function( index ) {
                if(index < total_length/2 ){                    
                  value_arr.push($( this ).val()); 
              }
          });
              $('#multi-select-proviences').multiselect('select', value_arr);
          }

      }
  } else{

      if(is_province == true) {

        var selected_text = '.child_prov_'+prov_id;
        var total_length = $(selected_text).length;
        $(selected_text).each(function( index ) {
          if(index < total_length/2 ){                    
            value_arr.push($( this ).val()); 
        }
    });                
        $('#multi-select-proviences').multiselect('deselect', value_arr);

    } else {


        var selected_child_text = '.child_prov_'+prov_id;
        var total_child_length = $(selected_child_text).length;

        if(total_child_length/2 == 1) {

          var selected_text = '.parent_prov_'+prov_id; 
          var total_length = $(selected_text).length;
          $(selected_text).each(function( index ) {
            if(index < total_length/2 ){                    
              value_arr.push($( this ).val()); 
          }
      });
          $('#multi-select-proviences').multiselect('deselect', value_arr);
      } else{

          var selected_text = '.parent_prov_'+prov_id;
          var cou = 0;
          var selected_child_text_new = 'child_prov_'+prov_id;
          $('li.active').each(function(){
            if($(this).hasClass('active') == true && $(this).hasClass(selected_child_text_new) == true ){
                cou++;
            }  
        });

          if(cou == 0) {

            var total_length = $(selected_text).length;
            $(selected_text).each(function( index ) {
              if(index < total_length/2 ){                    
                value_arr.push($( this ).val()); 
            }
        });
            $('#multi-select-proviences').multiselect('deselect', value_arr);
        }else{
            var total_length = $(selected_text).length;
            $(selected_text).each(function( index ) {
              if(index < total_length/2 ){                    
                value_arr.push($( this ).val()); 
            }
        });
            $('#multi-select-proviences').multiselect('deselect', value_arr);
        }

    }             

}

}

}
});

    $('#multi-select-services').multiselect({
        enableCaseInsensitiveFiltering: true,
        filterBehavior: 'text',
        onChange: function(option, checked, select) {

          var value_arr = [];
          var is_parent = $(option).hasClass('parent_option');
          var parent_id = $(option).attr('data-serv');

          if(checked == true) {
            if(is_parent == true){
              var selected_element = '.ch_op_'+parent_id;
              $(selected_element).show();
              var total_length = $(selected_element).length;
              $(selected_element).each(function( index ) {
                if(index < total_length/2 ){                             
                  value_arr.push($( this ).val()); 
              }
          });

              $('#multi-select-services').multiselect('select', value_arr);


          } else {

          }

      } else{
        if(is_parent == true) {

          var selected_element = '.ch_op_'+parent_id;
          var total_length = $(selected_element).length;
          $(selected_element).each(function( index ) {
            if(index < total_length/2 ){                             
              value_arr.push($( this ).val()); 
          }
      });
          $('#multi-select-services').multiselect('deselect', value_arr);
          $(selected_element).hide();

      } else {
          var selected_element = '.ch_op_'+parent_id;
          var total_length = $(selected_element).length;

          if(total_length/2 == 1) {
            $('#multi-select-services').multiselect('deselect', parent_id);
            $(selected_element).hide();
        }else{

                //var selected_text = '.parent_prov_'+prov_id;
                var cou = 0;
                var selected_child_text_new = 'ch_op_'+parent_id;
                $('li.active').each(function(){
                  if($(this).hasClass('active') == true && $(this).hasClass(selected_child_text_new) == true ){
                      cou++;
                  }  
              });

                if(cou == 0){
                  $('#multi-select-services').multiselect('deselect', parent_id);
                  $(selected_element).hide();
              } else {

              }

          }

      }
  }         

},                
onInitialized: function(select, container) {

    $('.child_option').hide();
}
});
});
</script>


<!--Timer-->
<script type="text/javascript">
  let timerOn = true;

  function timerStart(remaining) 
  {

    var m = Math.floor(remaining / 60);
    var s = remaining % 60;
    
    m = m < 10 ? '0' + m : m;
    s = s < 10 ? '0' + s : s;
    document.getElementById('timer').innerHTML = m + ':' + s;
    remaining -= 1;
    
    if(remaining >= 0 && timerOn) {
      setTimeout(function() {
          timer(remaining);
      }, 1000);
      return;
  }

  if(!timerOn) {
      // Do validate stuff here
      return;
  }

    // Do timeout stuff here
    alert('Timeout for otp');
}

</script>
<!--Timer-->


<script type="text/javascript">
    $(document).ready(function()
    {
      sessionStorage.setItem("serviceId",'<?php echo isset($serviceId) && !empty($serviceId) ? $serviceId : '' ;  ?>'); 
      sessionStorage.setItem("subserviceId",'<?php echo isset($subServiceId) && !empty($subServiceId) ? $subServiceId : '' ;  ?>'); 
      sessionStorage.setItem("childsubserviceId",'<?php echo isset($child_sub_serviceId) && !empty($child_sub_serviceId) ? $child_sub_serviceId : '' ;  ?>');

      sessionStorage.setItem('firstQuestID','<?php echo isset($firstQuestID) && !empty($firstQuestID) ? $firstQuestID : '' ;  ?>');

      var selected_type= '<?php echo isset($selected_type) && !empty($selected_type) ? $selected_type : '' ;  ?>';

      if(selected_type=='service')
      {

        sessionStorage.setItem("subservicesArray", '<?php echo isset($allSubServices) && !empty($allSubServices) ? $allSubServices : '' ;  ?>');
        sessionStorage.setItem("servicename",'<?php echo isset($servicename) && !empty($servicename) ? $servicename : '' ;  ?>');

    }
    if(selected_type=='sub_service')
    {



    }
});
</script>

<script type="text/javascript">
    $(document).ready(function(){
  // For Search Box IN Category select box of homepage
  $(".categoryAuto").select2();

//To get Selected Value And Selected Type From list

$('.categoryAuto').on('change', function (e) 
{
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    var typeSelected= $(this).find(':selected').data('type');

    $('#selectedType').val(typeSelected);
    $('#selectedValue').val(valueSelected);
});
$('.categoryAuto1').on('change', function (e) 
{
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    var typeSelected= $(this).find(':selected').data('type');

    $('#selectedType').val(typeSelected);
    $('#mCategoryId2').val(valueSelected);
});
});
</script>


<!-- Script for dynamic search in category selectbox of homepage -->
<script type="text/javascript">
    function fectchDynamicData(e)
    {
     var searchstring=e.value;
     var CSRF_TOKEN = '<?php echo csrf_token() ?>';
     
         // Fetch data
         $.ajax({
            url: '{!! URL::to("autoCompleteSearch") !!}',  
            type: 'post',
            dataType: "json",
            data: {
             _token: CSRF_TOKEN,
             search: searchstring,
         },
         success: function( data ) 
         {
              //alert(data.message);
              var allDataArray=JSON.stringify(data.allData);
              var itrateData=JSON.parse(allDataArray);
              var kk=0;
              itrateData.forEach((datas) => 
              {
                      // console.log('id: ' + datas.id);
                      //console.log('en_name: ' + datas.en_name);
                      if ($(".categoryAuto option:contains("+datas.es_name+")").length == 0 )
                      {
                        //alert("option doesn't exist!");
                        console.log('<option value="'+datas.id+'" data-name="'+datas.es_name+'" data-type="'+datas.type+'">'+datas.es_name+'</option>');

                        $('.categoryAuto').append('<option value="'+datas.id+'" data-name="'+datas.es_name+'" data-type="'+datas.type+'">'+datas.es_name+'</option>');
                        //$('.categoryAuto').select2().trigger('change');
                        //$('.categoryAuto').select2('reload');

                    }
                    kk++;
                });

          }
      });

     }
 </script>
 <!-- Script for dynamic search in category selectbox of homepage -->



 <script type='text/javascript'>
    $(document).ready(function(){
      var counter = 2;
      $('#del_file').hide();
      $('#add_file').click(function(){

    //$('#file_tools').before('<div class="file_upload" id="f'+counter+'"><input name="certification_courses[]" type="file">'+counter+'</div>');
    $('#file_tools').before('<div class="file_upload" id="f'+counter+'"><input name="certification_courses[]" type="file"></div>');
    $('#del_file').fadeIn(0);
    counter++;
});
      $('#del_file').click(function(){
        if(counter==3){
          $('#del_file').hide();
      }   
      counter--;
      $('#f'+counter).remove();
  });
  });
</script>


<script type='text/javascript'>
    $(document).ready(function(){
      var counter = 2;
      $('#poldel_file').hide();
      $('#poladd_file').click(function(){
       $('#pol_file_tools').before('<div class="file_upload" id="f'+counter+'"><input name="police_records[]" type="file"></div>');
       $('#poldel_file').fadeIn(0);
       counter++;
   });
      $('#poldel_file').click(function(){
        if(counter==3){
          $('#poldel_file').hide();
      }   
      counter--;
      $('#f'+counter).remove();
  });
  });
</script>


<script type='text/javascript'>
    $(document).ready(function(){

      $('#inWholeCountryTrue').click(function(){
       $('#proviencesArea').hide();
       $('#citiesArea').hide();
   });


      $('#inWholeCountryFalse').click(function(){
       $('#proviencesArea').show();
       $('#citiesArea').show();
   });

  });
</script>



<script type='text/javascript'>
    $(document).ready(function(){
      var counter = 2;

      $('#deleteGalleryImage').hide();
      $('#addGalleryImage').click(function(){
       $('#image_file_tools').before('<div class="file_upload1" id="f1'+counter+'"><input name="images_gallery[]" type="file"></div>');
       $('#deleteGalleryImage').fadeIn(0);
       counter++;
   });
      $('#deleteGalleryImage').click(function(){
        if(counter==3){
          $('#deleteGalleryImage').hide();
      }   
      counter--;
      $('#f1'+counter).remove();
  });
  });
</script>


<script type='text/javascript'>
    $(document).ready(function(){
      var counter = 2;
      $('#deleteGalleryVideo').hide();
      $('#addGalleryVideo').click(function(){
       $('#videos_file_tools').before('<div class="file_upload2" id="f2'+counter+'"><input name="videos_gallery[]" type="file"></div>');
       $('#deleteGalleryVideo').fadeIn(0);
       counter++;
   });
      $('#deleteGalleryVideo').click(function(){
        if(counter==3){
          $('#deleteGalleryVideo').hide();
      }   
      counter--;
      $('#f2'+counter).remove();
  });
  });
</script>

<!-- <script type="text/javascript" src="../js/bootstrap-datetimepicker.js" charset="UTF-8"></script> -->
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.form_date').datetimepicker({
        //language:  'fr',
        format: 'dd-mm-yyyy',
        weekStart: 1,
        todayBtn:  0,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.form_time').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });
</script>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuL7xhKMuwdiSATLR02YbUWv-8o0b5_H8&libraries=places&sensor=false"></script> 
<script>
  var input = document.getElementById('address');

  var autocomplete = new google.maps.places.Autocomplete(input);
  google.maps.event.addListener(autocomplete, 'place_changed', function(){
   var place = autocomplete.getPlace();
});
</script>


<script type="text/javascript">
  $(document).ready(function() { 
    // Initialize select2
    $("#selUser").select2();
    $("#selCity").select2();
    //$(".services_id").select2();
});

  
  $('#search_service').click(function() {
    var selected_option_value = $('#selUser').val();
    var selected_option = $('#selUser').find('option:selected');
    var selected_city_value = $('#selCity').val();
    if(selected_option_value == '' || selected_option_value == 0 || selected_city_value == '' || selected_city_value == 0 ) {

      if(selected_option_value == '' || selected_option_value == 0) {
        alert('please select any service');
    }

    if(selected_city_value == '' || selected_city_value == 0) {
        alert('please select any City');
    }

} else {

  $('#city_id').val(selected_city_value);
  var is_service = selected_option.data('service');
  if(is_service == 'Yes') {
    var service_id = selected_option_value;
    var subservice_id = 0;
} else{
    var service_id = selected_option.data('service_id');
    var subservice_id = selected_option_value;
}
      //alert(service_id);
      //alert(subservice_id);
      var pageURL = $(location).attr("href");    
      var url = pageURL+"category_step/"+service_id+"/"+subservice_id+"/"+selected_city_value;
      //alert(url);
      $(location).attr('href',url); 
       // var url = "Page2.htm?name=" + encodeURIComponent(name) + "&technology=" + encodeURIComponent(tech);
   }    

});
</script>


<script>
    jQuery("#carousel").owlCarousel({
      autoplay: true,
      lazyLoad: true,
      loop: true,
      margin: 20,
   /*
  animateOut: 'fadeOut',
  animateIn: 'fadeIn',
  */
  responsiveClass: true,
  autoHeight: true,
  autoplayTimeout: 7000,
  smartSpeed: 800,
  nav: true,
  responsive: {
    0: {
      items: 1
  },

  600: {
      items: 1
  },

  767: {
      items: 2
  },

  991: {
      items: 3
  },

  1024: {
      items: 4
  },

  1366: {
      items: 4
  }
}
});
</script>


<script type="text/javascript">
  $(".inputs").keyup(function () 
  {
    if (this.value.length == this.maxLength) {
      var $next = $(this).next('.inputs');
      if ($next.length)
          $(this).next('.inputs').focus();
      else
          $(this).blur();
  }
})
</script>


<script>
  $(function(){
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    $(window).resize(function(e) {
      if($(window).width()<=768){
        $("#wrapper").removeClass("toggled");
    }else{
        $("#wrapper").addClass("toggled");
    }
});
});

</script>
<script type="text/javascript">
 $(document).ready(function() {
  $('.progress .progress-bar').css("width",
    function() {
        return $(this).attr("aria-valuenow") + "%";
    }
    )
});

</script>

<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>

<script type="text/javascript">
    $(document).ready(function() {
      var table = $('#example,#example1,#example2,#example3').DataTable( {
          rowReorder: 
          {
              selector: 'td:nth-child(2)'
          },
          responsive: true
      } );
  } );
</script>



<script type="text/javascript">

   function submitMultistepForm()
   {

      // alert("hello");
      // $('#submit_form').trigger('click');
      // return true;

      var otp = $('#otpcode').val();
    //alert(otp);

    var digit_1 =  $('#otp1').val();
    var digit_2 =  $('#otp2').val();
    var digit_3 =  $('#otp3').val();
    var digit_4 =  $('#otp4').val();

    if(digit_1 != '' && digit_2 != '' && digit_3 != '' && digit_4 != '') {  


      if(digit_1+digit_2+digit_3+digit_4 == otp) {
        //alert('y1');
        $('#submit_form').trigger('click');
    } else {
        //alert('y2');
        alert('please enter correct otp')
    }
} else {
  alert('please enter otp');
  return false;

}

}
</script>



<!--GET NEXT QUESTION DYNAMICALLY USING CAT,SERVICE,SUBSERVICE,CHILDSERVICE ID -->

<script type="text/javascript">

function getNextQuestionsByOptionIdRelated(firstOptionID)
{
    $('.form-btn').show();
    var firstQuestID = sessionStorage.getItem('firstQuestID');
    var categoryId = '<?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>';
    var serviceId = sessionStorage.getItem('serviceId');
    var subserviceId = sessionStorage.getItem('subserviceId');
    var childsubserviceId = sessionStorage.getItem('childsubserviceId');
    var questiontype = sessionStorage.getItem('questiontype');
    var nextquestion = sessionStorage.getItem('nextquestion');
    var currentdata = sessionStorage.getItem('currentdata');

      //alert('firstQuestID:-> '+firstQuestID);
      //alert('firstOptionID:-> '+firstOptionID);

          //Start Ajax
          $.ajax({
            type: "GET",  
            url: '{!! URL::to("ajax_get_next_questions_option") !!}',  
            data:'categoryId='+categoryId+'&serviceId='+serviceId+'&subserviceId='+subserviceId+'&childsubserviceId='+childsubserviceId+'&firstQuestID='+firstQuestID+'&optionID='+firstOptionID+'&questiontype='+questiontype+'&nextquestion='+nextquestion,
            dataType: "json", 
            success: function(data)     
            {
               if(data.success == true) 
               {
                         //alert(data.message);
                         //nextQuestionData

                         var ref_this = $("ul.nav-tabs li a.active");
                         var step_new = ref_this.data("step") + 1;

                         
                         $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');
                         $('#multi-step-dataHere').html('');
                         $('#multi-step-dataHere').append('<div class="tab-pane" id="step'+step_new+'"><div class="pro-heading" id="QuestionArea'+step_new+'"><h3 class="modal-title" id="question_step'+step_new+'" ></h3></div><div class="row"><div class="col-md-8"><div class="pro-info"><input type="hidden" name="questions[]" id="questionId'+step_new+'"></input><div class="meta-list" id="optionArea'+step_new+'"></div></div><div style="overflow:auto;"><div  class="form-btn"><button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >Anterior</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)"  >Siguiente</button></div></div></div></div></div>');

                         $('#summary').append('<div id="sideQuestioName'+step_new+'"></div>');
                         $('#summary').append('<div id="sideOptionName'+step_new+'"></div>');

                         sessionStorage.setItem("firstQuestID", JSON.stringify(data.nextQuestionData.id));

                         sessionStorage.setItem("questionData"+step_new, JSON.stringify(data.nextQuestionData));
                         sessionStorage.setItem("firstOptionData"+step_new, JSON.stringify(data.nextQuestionData.options));

                         sessionStorage.setItem("questionname"+step_new, data.questionname);
                         sessionStorage.setItem("questionoptionname"+step_new, data.questionoptionname);
                         sessionStorage.setItem("nextquestion",data.nextdata);
                         sessionStorage.setItem("questiontype",data.dependent);
                         sessionStorage.setItem("currentdata",data.currentdata);


                     }else 
                     {


                      sessionStorage.setItem("firstQuestID","");

                      sessionStorage.setItem("questionData"+step_new, "");
                      sessionStorage.setItem("firstOptionData"+step_new, "");

                      sessionStorage.setItem("questionname"+step_new,  "");
                      sessionStorage.setItem("questionoptionname"+step_new,  "");

                        //alert(data.message);
                    }
                }  
            });
      //End Ajax     

  }

  function getNextQuestionsByOptionId1(firstOptionID)
  {
    $('.form-btn').show();
    var firstQuestID = sessionStorage.getItem('firstQuestID');
    var categoryId = '<?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>';
    var serviceId = sessionStorage.getItem('serviceId');
    var subserviceId = sessionStorage.getItem('subserviceId');
    var childsubserviceId = sessionStorage.getItem('childsubserviceId');

    var nextquestion = '<?php echo isset($nextdata) && !empty($nextdata) ? $nextdata : '' ;  ?>';
    var questiontype = '<?php echo isset($dependent) && !empty($dependent) ? $dependent : '' ;  ?>';
    var currentdata = '<?php echo isset($currentdata) && !empty($currentdata) ? $currentdata : '' ;  ?>';
    if(nextquestion=='')
    {
        var nextquestion = sessionStorage.getItem('nextquestion');
    }
    if(questiontype=='')
    {
        var questiontype = sessionStorage.getItem('questiontype');
    }
    if(currentdata=='')
    {
        var currentdata = sessionStorage.getItem('currentdata');
    }


      //alert('firstQuestID:-> '+firstQuestID);
      //alert('firstOptionID:-> '+firstOptionID);

          //Start Ajax
          $.ajax({
            type: "GET",  
            url: '{!! URL::to("ajax_get_next_questions") !!}',  
            data:'categoryId='+categoryId+'&serviceId='+serviceId+'&subserviceId='+subserviceId+'&childsubserviceId='+childsubserviceId+'&firstQuestID='+firstQuestID+'&firstOptionID='+firstOptionID+'&questiontype='+questiontype+'&nextquestion='+nextquestion+'&predata='+currentdata,
            dataType: "json", 
            success: function(data) 
            {
               if(data.success == true) 
               {
                         //alert(data.message);
                         //nextQuestionData

                         var ref_this = $("ul.nav-tabs li a.active");
                         var step_new = ref_this.data("step") + 1;

                         
                         $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');

                         $('#multi-step-dataHere').append('<div class="tab-pane" id="step'+step_new+'"><div class="pro-heading" id="QuestionArea'+step_new+'"><h3 class="modal-title" id="question_step'+step_new+'" ></h3></div><div class="row"><div class="col-md-8"><div class="pro-info"><input type="hidden" name="questions[]" id="questionId'+step_new+'"></input><div class="meta-list" id="optionArea'+step_new+'"></div></div><div style="overflow:auto;"><div  class="form-btn"><button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >Anterior</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)"  >Siguiente</button></div></div></div></div></div>');

                         $('#summary').append('<div id="sideQuestioName'+step_new+'"></div>');
                         $('#summary').append('<div id="sideOptionName'+step_new+'"></div>');

                         sessionStorage.setItem("firstQuestID", JSON.stringify(data.nextQuestionData.id));

                         sessionStorage.setItem("questionData"+step_new, JSON.stringify(data.nextQuestionData));
                         sessionStorage.setItem("firstOptionData"+step_new, JSON.stringify(data.nextQuestionData.options));

                         sessionStorage.setItem("questionname"+step_new, data.questionname);
                         sessionStorage.setItem("questionoptionname"+step_new, data.questionoptionname);
                         sessionStorage.setItem("questiontype",data.dependent);
                         sessionStorage.setItem("nextquestion", data.nextdata);
                         sessionStorage.setItem('currentdata',data.currentdata);



                     }else 
                     {
                      sessionStorage.setItem("firstQuestID","");

                      sessionStorage.setItem("questionData"+step_new, "");
                      sessionStorage.setItem("firstOptionData"+step_new, "");

                      sessionStorage.setItem("questionname"+step_new,  "");
                      sessionStorage.setItem("questionoptionname"+step_new,  "");

                       //alert(data.message);
                   }
               }  
           });
      //End Ajax     
    }

  var counter1=0;
  var countertext1=0;
  var counterdate1=0;
  var counterdatetime1=0;
  var counterquantity1=0;
  var counterfile1=0;
  var counternext1=0;
function getNextQuestionsByOptionId(firstQuestID,firstOptionID,textformdata,qnumber)
{
    $('.form-btn').show();
    var firstQuestID = firstQuestID;//sessionStorage.getItem('firstQuestID');
    var categoryId = '<?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>';
    var serviceId = sessionStorage.getItem('serviceId');
    var subserviceId = sessionStorage.getItem('subserviceId');
    var childsubserviceId = sessionStorage.getItem('childsubserviceId');

    var nextquestion = sessionStorage.getItem('nextquestion');
    var questiontype = sessionStorage.getItem('questiontype');
    var currentdata = sessionStorage.getItem('currentdata');


    if(textformdata==undefined)
    {

    }
    else
    {
        var nextquestion =textformdata;
    }
      //alert('firstQuestID:-> '+firstQuestID);
      //alert('firstOptionID:-> '+firstOptionID);

          //Start Ajax
          $.ajax({
            type: "GET",  
            url: '{!! URL::to("ajax_get_next_questions") !!}',  
            data:'categoryId='+categoryId+'&serviceId='+serviceId+'&subserviceId='+subserviceId+'&childsubserviceId='+childsubserviceId+'&firstQuestID='+firstQuestID+'&firstOptionID='+firstOptionID+'&questiontype='+questiontype+'&nextquestion='+nextquestion+'&predata='+currentdata,
            dataType: "json", 
            success: function(data) 
            {
                if(data.success == true) 
                {

                    var ref_this = $("ul.nav-tabs li a.active");
                    var step_new = ref_this.data("step") + 1;
                    if(data.dependent=='Yes')
                    {
                        $('.addressTab').remove();
                    }
                    if(data.dependent=='No' && data.nextdata==0)
                    {
                        $('#step'+step_new).remove();
                        $( "#step"+step_new).not( document.getElementById("step"+step_new )).remove();
                    }
                    if(data.nextdata==0)
                    {
                        sessionStorage.setItem("nextquestion", data.nextdata);
                        if(textformdata!='' && qnumber==1)
                        {
                            if(countertext1==0)
                            {
                                $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                countertext1++; 
                            }
                        }
                        else
                        {

                        }
                        if(textformdata!='' && qnumber==2)
                        {
                            if(counterdate1==0)
                            {
                                $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                counterdate1++; 
                            }
                        }else
                        {

                        }
                        if(textformdata!='' && qnumber==3)
                        {
                            if(counterdatetime1==0)
                            {
                                $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                counterdatetime1++; 
                            }
                        }else
                        {

                        }
                        if(textformdata=='0' && qnumber==2)
                        {
                            if(counterdate1==0)
                            {
                                $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                counterdate1++; 
                            }
                        }else
                        {

                        }

                        if(textformdata=='')
                        {
                            $('.form-btn').show();
                            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                            $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address1" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
                            return true; exit();
                        }
                        if(textformdata==0)
                        {
                            $('.form-btn').show();
                            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                            $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address2" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
                        }

                        if(textformdata==undefined)
                        {
                            $('.form-btn').show();
                            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                            $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address3" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
                            return true; exit();
                        }
                        if(textformdata!='')
                        {
                            if(counternext1==0)
                            {
                                $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address4" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
                                counternext1++;
                                return true; exit();

                            }
                        }
                    }
                    else
                    {
                       
                        if(textformdata==undefined)
                        {
                            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');
                        }
                    }
                    if(data.nextdata!=0)
                    {  
                        $('#multi-step-dataHere').append('<div class="tab-pane" id="step'+step_new+'"><div class="pro-heading" id="QuestionArea'+step_new+'"><h3 class="modal-title" id="question_step'+step_new+'" ></h3></div><div class="row"><div class="col-md-8"><div class="pro-info"><input type="hidden" name="questions[]" id="questionId'+step_new+'"></input><div class="meta-list" id="optionArea'+step_new+'"></div></div><div style="overflow:auto;"><div  class="form-btn"><button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >Anterior</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)"  >Siguiente</button></div></div></div></div></div>');
                    }

                        $('#summary').append('<div id="sideQuestioName'+step_new+'"></div>');
                        $('#summary').append('<div id="sideOptionName'+step_new+'"></div>');

                        sessionStorage.setItem("firstQuestID", JSON.stringify(data.nextQuestionData.id));

                        sessionStorage.setItem("questionData"+step_new, JSON.stringify(data.nextQuestionData));
                        sessionStorage.setItem("firstOptionData"+step_new, JSON.stringify(data.nextQuestionData.options));

                        sessionStorage.setItem("questionname"+step_new, data.questionname);
                        sessionStorage.setItem("questionoptionname"+step_new, data.questionoptionname);
                        sessionStorage.setItem("questiontype",data.dependent);
                        sessionStorage.setItem("nextquestion", data.nextdata);
                        sessionStorage.setItem("currentdata", data.currentdata);
                }else 
                {
                  sessionStorage.setItem("firstQuestID","");

                  sessionStorage.setItem("questionData"+step_new, "");
                  sessionStorage.setItem("firstOptionData"+step_new, "");

                  sessionStorage.setItem("questionname"+step_new,  "");
                  sessionStorage.setItem("questionoptionname"+step_new,  "");

                       //alert(data.message);
                }
            }  
       });
      //End Ajax     
}

function getNextQuestionsByOptionIdCheck(firstOptionID,checkmulti)
{
    $('.form-btn').show();
    $('.pre-btn1').attr('data-check',firstOptionID);
    var firstQuestID = checkmulti;//sessionStorage.getItem('firstQuestID');
    var categoryId = '<?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>';
    var serviceId = sessionStorage.getItem('serviceId');
    var subserviceId = sessionStorage.getItem('subserviceId');
    var childsubserviceId = sessionStorage.getItem('childsubserviceId');

    var nextquestion = sessionStorage.getItem('nextquestion');
    var questiontype = sessionStorage.getItem('questiontype');
    var currentdata = sessionStorage.getItem('currentdata');


          //Start Ajax
          $.ajax({
            type: "GET",  
            url: '{!! URL::to("ajax_get_next_questions_multipal") !!}',  
            data:'categoryId='+categoryId+'&serviceId='+serviceId+'&subserviceId='+subserviceId+'&childsubserviceId='+childsubserviceId+'&firstQuestID='+firstQuestID+'&firstOptionID='+firstOptionID+'&questiontype='+questiontype+'&nextquestion='+nextquestion+'&predata='+currentdata,
            dataType: "json", 
            success: function(data) 
            {
                if(data.success == true) 
                {
                         //alert(data.message);
                         //nextQuestionData

                         var ref_this = $("ul.nav-tabs li a.active");
                         var step_new = ref_this.data("step") + 1;

                         if(data.nextdata==0)
                         {
                             sessionStorage.setItem("nextquestion", data.nextdata);
                            if(textformdata!='' && qnumber==1)
                            {
                                if(countertext1==0)
                                {
                                 $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                 countertext1++; 
                             }
                         }else
                         {

                         }

                     }
                     else
                     {
                        $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');

                    }
                    if(data.nextdata!=0)
                    {  
                        $('#multi-step-dataHere').append('<div class="tab-pane" id="step'+step_new+'"><div class="pro-heading" id="QuestionArea'+step_new+'"><h3 class="modal-title" id="question_step'+step_new+'" ></h3></div><div class="row"><div class="col-md-8"><div class="pro-info"><input type="hidden" name="questions[]" id="questionId'+step_new+'"></input><div class="meta-list" id="optionArea'+step_new+'"></div></div><div style="overflow:auto;"><div  class="form-btn"><button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >Anterior</button><button type="button" class="btn pre-btn" data-check='+firstOptionID+' onclick="nextbtnn(this)"  >Siguiente</button></div></div></div></div></div>');
                    }

                    $('#summary').append('<div id="sideQuestioName'+step_new+'"></div>');
                    $('#summary').append('<div id="sideOptionName'+step_new+'"></div>');

                    sessionStorage.setItem("firstQuestID", JSON.stringify(data.nextQuestionData.id));

                    sessionStorage.setItem("questionData"+step_new, JSON.stringify(data.nextQuestionData));
                    sessionStorage.setItem("firstOptionData"+step_new, JSON.stringify(data.nextQuestionData.options));

                    sessionStorage.setItem("questionname"+step_new, data.questionname);
                    sessionStorage.setItem("questionoptionname"+step_new, data.questionoptionname);
                    sessionStorage.setItem("questiontype",data.dependent);
                    sessionStorage.setItem("nextquestion", data.nextdata);
                    sessionStorage.setItem("currentdata", data.currentdata);




                }else 
                {
                  sessionStorage.setItem("firstQuestID","");

                  sessionStorage.setItem("questionData"+step_new, "");
                  sessionStorage.setItem("firstOptionData"+step_new, "");

                  sessionStorage.setItem("questionname"+step_new,  "");
                  sessionStorage.setItem("questionoptionname"+step_new,  "");

                       //alert(data.message);
                   }
               }  
           });
      //End Ajax     
}

  var counter=0;
  var countertext=0;
  var counterdate=0;
  var counterdatetime=0;
  var counterquantity=0;
  var counterfile=0;
  var counternext=0;
     function getNextQuestionsByOptionIdfield(firstOptionID,textformdata,qnumber)
    {
        $('.form-btn').show();
        var firstQuestID = firstOptionID;//sessionStorage.getItem('firstQuestID');
        var categoryId = '<?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>';
        var serviceId = sessionStorage.getItem('serviceId');
        var subserviceId = sessionStorage.getItem('subserviceId');
        var childsubserviceId = sessionStorage.getItem('childsubserviceId');

        var nextquestion = sessionStorage.getItem('nextquestion');
        var questiontype = sessionStorage.getItem('questiontype');
        var currentdata = firstOptionID;


        if(textformdata==undefined)
        {

        }
        else
        {
            var nextquestion =textformdata;
        }
        //alert('firstQuestID:-> '+firstQuestID);
        //alert('firstOptionID:-> '+firstOptionID);

          //Start Ajax
          $.ajax({
            type: "GET",  
            url: '{!! URL::to("ajax_get_next_questions") !!}',  
            data:'categoryId='+categoryId+'&serviceId='+serviceId+'&subserviceId='+subserviceId+'&childsubserviceId='+childsubserviceId+'&firstQuestID='+firstQuestID+'&firstOptionID='+firstOptionID+'&questiontype='+questiontype+'&nextquestion='+nextquestion+'&predata='+currentdata,
            dataType: "json", 
            success: function(data) 
            {
                if(data.success == true) 
                {
                    //alert(data.message);
                    //nextQuestionData
                    var ref_this = $("ul.nav-tabs li a.active");
                    var step_new = ref_this.data("step") + 1;

                    if(data.nextdata==0)
                    {
                        sessionStorage.setItem("nextquestion", data.nextdata);
                        if(textformdata!='' && qnumber==1)
                        {
                            if(countertext==0)
                            { $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                countertext++; 
                            }
                        }else
                        {

                        }
                        if(textformdata!='' && qnumber==2)
                        {
                            if(counterdate==0)
                            { $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                counterdate++; 
                            }
                        }else
                        {

                        }
                        if(textformdata!='' && qnumber==3)
                        {
                            if(counterdatetime==0)
                            { $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                counterdatetime++; 
                            }
                        }else
                        {
                        }
                        if(textformdata=='0' && qnumber==2)
                        {
                            if(counterdate==0)
                            { $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                counterdate++; 
                            }
                        }else
                        {

                        }
                        if(textformdata=='')
                        { $('.form-btn').show();
                            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                            $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address4" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
                        }
                        if(textformdata==0)
                        { $('.form-btn').show();
                            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                            $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address5" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
                        }

                        if(textformdata==undefined)
                        { $('.form-btn').show();
                            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                            $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address6" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
                        }
                        if(textformdata!='')
                        {
                            if(counternext==0)
                            { 

                                $('.form-btn').show();
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');
                                $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address7" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
                                counternext++;
                                return true; exit();

                            }
                        }
                    }
                    else
                    {
                        if(textformdata!=''&& qnumber==1)
                        {
                            if(countertext==0)
                            {
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');
                                 countertext++; 
                            }
                        }else
                        {

                        }
                        if(textformdata!=''&& qnumber==2)
                        {
                            if(counterdate==0)
                            {
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');
                                counterdate++; 
                            }
                        }else
                        {
                        }
                        if(textformdata!='' && qnumber==3)
                        {
                            if(counterdatetime==0)
                            {
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');
                                counterdatetime++; 
                            }else
                            {

                            }
                        }
                        if(textformdata!='' && qnumber==4)
                        {
                            if(counterquantity==0)
                            {
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');
                                counterquantity++; 
                            }else
                            {

                            }
                        }
                        if(textformdata!='' && qnumber==5)
                        {
                            if(counterfile==0)
                            {
                                $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');
                                counterfile++; 
                            }else
                            {

                            }
                        }
                        if(textformdata==undefined)
                        {

                            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="question-title" data-step ="'+step_new+'" ></a></li>');
                        }
                    }

                    if(data.nextdata!=0)
                    {  
                        $('#multi-step-dataHere').append('<div class="tab-pane" id="step'+step_new+'"><div class="pro-heading" id="QuestionArea'+step_new+'"><h3 class="modal-title" id="question_step'+step_new+'" ></h3></div><div class="row"><div class="col-md-8"><div class="pro-info"><input type="hidden" name="questions[]" id="questionId'+step_new+'"></input><div class="meta-list" id="optionArea'+step_new+'"></div></div><div style="overflow:auto;"><div  class="form-btn"><button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >Anterior</button><button type="button" class="btn pre-btn"  onclick="nextbtnn(this)"  >Siguiente</button></div></div></div></div></div>');
                    }

                        $('#summary').append('<div id="sideQuestioName'+step_new+'"></div>');
                        $('#summary').append('<div id="sideOptionName'+step_new+'"></div>');

                       
                        sessionStorage.setItem("firstQuestID", JSON.stringify(data.nextQuestionData.id));

                         sessionStorage.setItem("questionData"+step_new, JSON.stringify(data.nextQuestionData));
                         
                        sessionStorage.setItem("firstOptionData"+step_new, JSON.stringify(data.nextQuestionData.options));
                       
                        sessionStorage.setItem("questionname"+step_new, data.questionname);
                        sessionStorage.setItem("questionoptionname"+step_new, data.questionoptionname);
                        sessionStorage.setItem("questiontype",data.dependent);
                        sessionStorage.setItem("nextquestion", data.nextdata);
                        sessionStorage.setItem("currentdata", data.currentdata);
                }else 
                {
                  sessionStorage.setItem("firstQuestID","");

                  sessionStorage.setItem("questionData"+step_new, "");
                  sessionStorage.setItem("firstOptionData"+step_new, "");

                  sessionStorage.setItem("questionname"+step_new,  "");
                  sessionStorage.setItem("questionoptionname"+step_new,  "");

                                       //alert(data.message);
                }
            }  
       });
        //End Ajax     
    }

 var qtype=1;
  function mergeNextQuestionArray()
  {

      var ref_this = $("ul.nav-tabs li a.active");
      var step_new = ref_this.data("step") + 1;

         // alert("merge data on step55"+step_new);

         var questionData = sessionStorage.getItem('questionData'+step_new);
         var firstOptionData = sessionStorage.getItem('firstOptionData'+step_new);
         var nextquestionId = sessionStorage.getItem('nextquestion');
        if(firstOptionData !='undefined')
        {
            var quesOptDataa=JSON.parse(firstOptionData);
        }
        if(questionData !='undefined')
        {
            var question=JSON.parse(questionData);
        }
        

         var questionname = sessionStorage.getItem("questionname"+step_new);
         var optionname =  sessionStorage.getItem("questionoptionname"+step_new);

        if(nextquestionId==0)
        {
           $('.form-btn').show();
        }

         if (!$.trim(questionData))
         {   
             // alert("blank question array");
              $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="address-title" data-step ="'+step_new+'" ></a></li>');

              $('#multi-step-dataHere').append('<div class="tab-pane addressTab"  id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> ¡@lang("labels.frontend.home_page.perfect")!</h2><p>@lang("labels.frontend.home_page.finalize")</p></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png')?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.address_where")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="address8" id="address" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="addressError"></p></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)"  >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn pre-btn1" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');
              return true; exit();
          }

          $('#QuestionArea'+step_new).html('');
          $('#QuestionArea'+step_new).append('<h3 class="modal-title" id="question_step'+step_new+'" >'+question.es_title+'</h3>');
          $('#questionId'+step_new).val(question.id);

          $('#sideQuestioName'+step_new).replaceWith('<li><h6>@lang("labels.frontend.home_page.question")</h6><p>'+questionname+'</p></li>');
          if(optionname!='')
          {
            $('#sideOptionName'+step_new).replaceWith('<li><h6>@lang("labels.frontend.home_page.option")</h6><p>'+optionname+'</p></li>');
        }



        var pp=0;

           // if(question.question_type=='select')
           //    {
           //        var selectBox = '<label class="cust-radio"><select name="optionsdata['+quest.id+']" onChange="getNextQuestionsByOptionId(this.value)" id="questionTypeSelect"><option>@lang("labels.frontend.home_page.select_option")</option></select></label>';
           //        $("#optionArea"+step_new).append(selectBox);
           //    }
           if(question.question_type=='text')
           {

                if(nextquestionId!=0)
                {
                    var selectBox = '<input type="text" name="text" class="form-control"    id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+','+qtype+')" >';
                    $("#optionArea"+step_new).html(selectBox);
                    // $("#optionArea"+step_new).append(selectBox);
                    qtype++;
               }
               else
               {
                var selectBox = '<input type="text" name="text" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+',1)" >';
                $("#optionArea"+step_new).html(selectBox);
                    //$("#optionArea"+step_new).append(selectBox);
                }
            }
            if(question.question_type=='date_time')
            {
               if(nextquestionId!=0)
               {
                    var selectBox = '<input type="text" name="date_time" placeholder="dd-mm-yyyy hh:ii:ss" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',3)" >';
                    $("#optionArea"+step_new).html('');
                    $("#optionArea"+step_new).append(selectBox);
                }else
                {
                   var selectBox = '<input type="text" name="date_time" placeholder="dd-mm-yyyy hh:ii:ss" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+',3)" >';
                   $("#optionArea"+step_new).html('');
                   $("#optionArea"+step_new).append(selectBox);
                }
            }
            if(question.question_type=='file')
            {
                var selectBox = '<input type="file" name="fileName" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',5)" >';
                $("#optionArea"+step_new).html('');
                $("#optionArea"+step_new).append(selectBox);
            }
            if(question.question_type=='date')
            {
                var selectBox = '<input type="text" name="date" placeholder="dd-mm-yyyy" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',2)" >';
                $("#optionArea"+step_new).html('');
                $("#optionArea"+step_new).append(selectBox);
            }
            if(question.question_type=='quantity')
            {
                if(nextquestionId!=0)
                {
                   var selectBox = '<input type="number" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',4)" >';
                   $("#optionArea"+step_new).html('');
                   $("#optionArea"+step_new).append(selectBox);
               }else
               {
                    var selectBox = '<input type="number" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+',4)" >';
                    $("#optionArea"+step_new).html('');
                    $("#optionArea"+step_new).append(selectBox);
                }
            }

if(quesOptDataa)
{
    if(question.question_type=='checkbox' ||question.question_type=='radio')
    {
       $('#optionArea'+step_new).html('');
   }
           // alert(question.question_type);
           quesOptDataa.forEach((quest) => 
           {
                   //console.log('id: ' + quest.id);
                   //console.log('en_option: ' + quest.en_option);
                   if(question.question_type=='checkbox')
                   {
                     var optionText='<label class="cust-radio">'+quest.es_option+'<input type="checkbox" value="'+quest.id+'" class="question_option'+pp+'" name="optionsdata1[]['+question.id+']" onClick="getNextQuestionsByOptionIdCheck('+quest.id+','+question.id+')"><span class="checkmark"></span></label>';

                     $('#optionArea'+step_new).append(optionText);
                 }
                 if(question.question_type=='radio')
                 {
                    var optionText = '<label class="cust-radio">'+quest.es_option+'<input type="radio" value="'+quest.id+'" class="question_option'+pp+'" onClick="getNextQuestionsByOptionId('+question.id+','+quest.id+')" name="optionsdata['+question.id+']"><span class="checkmark"></span></label>';
                    $('#optionArea'+step_new).append(optionText);
                }
                //  if(question.question_type=='radio')
                // {
                //     var optionText = '<option value="'+quest.id+'">'+quest.es_option+'</option>';
                //     $("#optionArea"+step_new).html('');
                //     $('#questionTypeSelect').append(optionText);
                // }
                
                pp++;
            });

           if(question.question_type=='textarea')
           {
            var optionText = '<label class="cust-radio"><textarea name="optionsdata['+question.id+']" placeholder="@lang("labels.frontend.home_page.enter_here")"></textarea></label>';
            $('#optionArea'+step_new).append(optionText);
        }
    }
}

</script>

<!--GET NEXT QUESTION DYNAMICALLY USING CAT,SERVICE,SUBSERVICE,CHILDSERVICE ID -->



<!--GET FIRST QUESTION DYNAMICALLY USING CAT,SERVICE,SUBSERVICE,CHILDSERVICE ID -->

<script type="text/javascript">
  function getQuestionsById(childsubserviceId)
  {
    sessionStorage.setItem("childsubserviceId", childsubserviceId);

    var categoryId = '<?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>';
    var serviceId = sessionStorage.getItem('serviceId');
    var subserviceId = sessionStorage.getItem('subserviceId');
    var childsubserviceId = sessionStorage.getItem('childsubserviceId');
    var nextquestion = sessionStorage.getItem('nextquestion');
    var questiontype = sessionStorage.getItem('questiontype');
    var currentdata = sessionStorage.getItem('currentdata');

    //alert('childsubserviceId ->'+childsubserviceId);

          //Start Ajax
          $.ajax({
            type: "GET",  
            url: '{!! URL::to("ajax_get_questions") !!}',  
            data:'categoryId='+categoryId+'&serviceId='+serviceId+'&subserviceId='+subserviceId+'&childsubserviceId='+childsubserviceId+'&questiontype='+questiontype+'&nextquestion='+nextquestion+'&predata='+currentdata,
            dataType: "json", 
            success: function(data) 
            {
               if(data.success == true) 
               {
                        // alert(data.message);
                        sessionStorage.setItem("firstQuestID", JSON.stringify(data.nextQuestionData.id));
                        sessionStorage.setItem("questionData", JSON.stringify(data.nextQuestionData));
                        sessionStorage.setItem("firstOptionData", JSON.stringify(data.nextQuestionData.options));
                        sessionStorage.setItem("childsubservicename",data.childsubservicename);
                        sessionStorage.setItem("questiontype",data.dependent);
                        sessionStorage.setItem("nextquestion",data.nextdata);
                        sessionStorage.setItem("currentdata",data.currentdata);
                    }else 
                    {
                        sessionStorage.setItem("firstQuestID","");
                        sessionStorage.setItem("questionData","");
                        sessionStorage.setItem("firstOptionData","");
                        sessionStorage.setItem("childsubservicename","");
                        alert(data.message);
                    }
                }  
            });
      //End Ajax
  }


  function mergeFisrtQuestionArray()
  {
    //alert("merge data on step 4");

   var questionData = sessionStorage.getItem('questionData');
   var firstOptionData = sessionStorage.getItem('firstOptionData');
   var childsubservicename = sessionStorage.getItem('childsubservicename');
   var nextquestionId = sessionStorage.getItem('nextquestion');
   var quesOptDataa=JSON.parse(firstOptionData);
   var question=JSON.parse(questionData);
   //console.log(question.options);

   if(quesOptDataa)
   {

    $('#sideChildSubServiceName').replaceWith('<li><h6>Sub-servicio 2</h6><p>'+childsubservicename+'</p></li>');
    $('#QuestionArea').append('<h3 class="modal-title" id="question_step1" >'+question.es_title+'</h3>');
    $('#questionId').val(question.id);

    var m=0;
    quesOptDataa.forEach((quest) => 
    {
        if(question.question_type=='checkbox')
        {
         var optionText='<label class="cust-radio">'+quest.es_option+'<input type="checkbox" value="'+quest.id+'" class="question_option'+m+'" name="optionsdata1[]['+question.id+']" onClick="getNextQuestionsByOptionIdCheck('+quest.id+','+question.id+')"><span class="checkmark"></span></label>';

         $('#optionArea').append(optionText);
     }

     m++;
 });
}
var p=0;
question.options.forEach((ops) => 
{ 
    var optionText='<label class="cust-radio">'+ops.es_option+'<input type="radio" value="'+ops.id+'" class="question_option'+p+'" name="optionsdata['+question.id+']" onClick="getNextQuestionsByOptionId1('+ops.id+')"><span class="checkmark"></span></label>';
    $('#optionArea').append(optionText);
    p++;
});
var ref_this = $("ul.nav-tabs li a.active");
var step_new = ref_this.data("step") + 1;

    if(question.question_type=='text')
    {
        if(nextquestionId!=0)
        {
            var selectBox = '<input type="text" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionId('+question.id+','+nextquestionId+',1)" >';
            $('#optionArea').append(selectBox);
        }
        else
        {
            var selectBox = '<input type="text" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionId('+question.id+',1)" >';
            $('#optionArea').append(selectBox);
        }

    }
    if(question.question_type=='date_time')
    {
        if(nextquestionId!=0)
        {
            var selectBox = '<input type="text" name="quantity" placeholder="dd-mm-yyyy hh:ii:ss" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',3)" >';
            $('#optionArea').append(selectBox);
        }else
        {
           var selectBox = '<input type="text" name="quantity" placeholder="dd-mm-yyyy hh:ii:ss" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+',3)" >';
           $('#optionArea').append(selectBox);
       }
   }
   if(question.question_type=='file')
   {
    var selectBox = '<input type="file" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',5)" >';
    $('#optionArea').append(selectBox);
}
if(question.question_type=='date')
{
    var selectBox = '<input type="text" name="quantity" placeholder="dd-mm-yyyy" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',2)" >';
    $('#optionArea').append(selectBox);
}
if(question.question_type=='quantity')
{
    if(nextquestionId!=0)
    {
       var selectBox = '<input type="number" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',4)" >';
       $('#optionArea').append(selectBox);
   }else
   {
    var selectBox = '<input type="number" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+',4)" >';
    $('#optionArea').append(selectBox);
}

}

}


</script>

<!--GET FIRST QUESTION DYNAMICALLY USING CAT,SERVICE,SUBSERVICE,CHILDSERVICE ID -->




<!---Get CHILD SERVICE accordingly SUBSERVICE ID Onchange IN STEP 2-->

<script>

    function getSubserviceIdForChild(subserviceId)
    {

        $('.pre-btn').attr('data-check',subserviceId);
         $('.form-btn').show();
        sessionStorage.setItem("subserviceId", subserviceId);
        //alert('subserviceId ->'+subserviceId);
         //Start Ajax
        $.ajax({
        type: "GET",  
        url: '{!! URL::to("ajax_get_childservice") !!}',  
        data:'subserviceId='+subserviceId,
        dataType: "json", 
        success: function(data) 
        {

            if(data.success == true) 
            {
                        //console.log(data.childservices);
                        //alert(data.message);

                        if(data.message=='foundChildservice')
                        {
                            sessionStorage.setItem("childservicesArray", JSON.stringify(data.childservices));
                            sessionStorage.setItem("subservicename",data.subservicename);
                            sessionStorage.setItem("isFoundChild","1");
                        }
                        if(data.message=='foundQuestionReplcaeChildservice')
                        {
                            sessionStorage.setItem("firstQuestID", JSON.stringify(data.questionData.id));
                            sessionStorage.setItem("questionData", JSON.stringify(data.questionData));
                            sessionStorage.setItem("firstOptionData", JSON.stringify(data.questionData.options));
                            sessionStorage.setItem("subservicename",data.subservicename);
                            sessionStorage.setItem("childsubservicename","");
                            sessionStorage.setItem("isFoundChild","0");
                            sessionStorage.setItem("questiontype",data.dependent);
                            sessionStorage.setItem("nextquestion",data.nextdata);
                            sessionStorage.setItem("currentdata",data.currentdata);
                        }
                    }else 
                    {
                        alert(data.message);
                    }
                }  
            });
      //End Ajax
  }

    function mergeChildServiceArray()
    {
        $('.form-btn').show();
        //alert("merge data on step 3");
        var isFoundChild = sessionStorage.getItem('isFoundChild');
        var nextquestionId = sessionStorage.getItem('nextquestion');
        // If found child then show child tab else show question tab
        if(isFoundChild=="1")
        {
            var subservicename = sessionStorage.getItem('subservicename');
            var childservices = sessionStorage.getItem('childservicesArray');
            var chDattaa=JSON.parse(childservices);
            if(chDattaa)
            {
                $('#sideSubServiceName').replaceWith('<li><h6>Sub-servicio</h6><p>'+subservicename+'</p></li>');

                //console.log(dattaa);
                var j=0;
                chDattaa.forEach((itemarr) => 
                {
                    // console.log('id: ' + itemarr.id);
                   // console.log('en_name: ' + itemarr.en_name);
                   var chLoopData='<label class="cust-radio">'+itemarr.es_name+'<input type="radio" value="'+itemarr.id+'" class="childservice_class" onClick="getQuestionsById('+itemarr.id+')"  name="getchildservice_id" id="chsr_id'+j+'" data-childservicename ="'+itemarr.es_name+'"><span class="checkmark"></span></label>';

                   $('#childServicesArrayAppend').append(chLoopData);
                   j++;
               });
            }
        }
    
        if(isFoundChild==0)
        {
            $('#QuestionArea').empty();
            $('#optionArea').empty();

            var questionData = sessionStorage.getItem('questionData');
            var firstOptionData = sessionStorage.getItem('firstOptionData');
            var childsubservicename = sessionStorage.getItem('childsubservicename');
            var subservicename = sessionStorage.getItem('subservicename');

            var quesOptDataa=JSON.parse(firstOptionData);
            var question=JSON.parse(questionData);
            if(quesOptDataa)
            {

                $('#sideSubServiceName').replaceWith('<li><h6>Sub servicio</h6><p>'+subservicename+'</p></li>');

                $('#QuestionArea').append('<h3 class="modal-title" id="question_step1" >'+question.es_title+'</h3>');
                $('#questionId').val(question.id);

                 var m=0;
                quesOptDataa.forEach((quest) => 
                {
                    if(question.question_type=='checkbox')
                    {
                        var optionText='<label class="cust-radio">'+quest.es_option+'<input type="checkbox" value="'+quest.id+'" class="question_option'+m+'" name="optionsdata1[]['+question.id+']" onClick="getNextQuestionsByOptionIdCheck('+quest.id+','+question.id+')"><span class="checkmark"></span></label>';

                        $('#optionArea').append(optionText);
                        $('.form-btn').hide();
                    }
                    if(question.question_type=='radio')
                    {
                        var optionText='<label class="cust-radio">'+quest.es_option+'<input type="radio" value="'+quest.id+'" class="question_option'+m+'" name="optionsdata['+question.id+']" onClick="getNextQuestionsByOptionId('+question.id+','+quest.id+')"><span class="checkmark"></span></label>';

                        $('#optionArea').append(optionText);
                    }
                    m++;
                });

                if(question.question_type=='text')
                {
                    if(nextquestionId!=0)
                    {
                    var selectBox = '<input type="text" name="text" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',1)" >';
                    $("#optionArea").html(selectBox);
                       // $("#optionArea"+step_new).append(selectBox);
                   }
                   else
                   {
                    var selectBox = '<input type="text" name="text" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+',1)" >';
                    $("#optionArea").html(selectBox);
                        //$("#optionArea"+step_new).append(selectBox);
                    }
                }
                if(question.question_type=='date_time')
                {
                   if(nextquestionId!=0)
                   {
                        var selectBox = '<input type="text" name="date_time" placeholder="dd-mm-yyyy hh:ii:ss" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',3)" >';
                        $("#optionArea").html('');
                        $("#optionArea").append(selectBox);
                    }else
                    {
                       var selectBox = '<input type="text" name="date_time" placeholder="dd-mm-yyyy hh:ii:ss" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+',3)" >';
                       $("#optionArea").html('');
                       $("#optionArea").append(selectBox);
                    }
                }
                if(question.question_type=='file')
                {
                    var selectBox = '<input type="file" name="fileName" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',5)" >';
                    $("#optionArea").html('');
                    $("#optionArea").append(selectBox);
                }
                if(question.question_type=='date')
                {
                    var selectBox = '<input type="text" name="date" placeholder="dd-mm-yyyy" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',2)" >';
                    $("#optionArea").html('');
                    $("#optionArea").append(selectBox);
                }
                if(question.question_type=='quantity')
                {
                    if(nextquestionId!=0)
                    {
                       var selectBox = '<input type="number" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+','+nextquestionId+',4)" >';
                       $("#optionArea").html('');
                       $("#optionArea").append(selectBox);
                   }else
                   {
                        var selectBox = '<input type="number" name="quantity" class="form-control" id="questionTypeSelect3" onkeydown="getNextQuestionsByOptionIdfield('+question.id+',4)" >';
                        $("#optionArea").html('');
                        $("#optionArea").append(selectBox);
                    }
                }
            }
        }
    }
</script>

<!---Get CHILD SERVICE accordingly SUBSERVICE ID Onchange IN STEP 2-->


<!---Get subservice accordingly Service ID Onchange IN STEP 1-->

<script type="text/javascript">

    $(document).ready(function() {

      $('.stepOneServiceClass').change(function()
      {
        $('.form-btn').show();
        var serviceId = $( this ).val();
        sessionStorage.setItem("serviceId", serviceId);
        var categoryId = '<?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>';
    //alert('serviceId ->'+serviceId);
    // alert('categoryId ->'+categoryId);

      //Start Ajax
      $.ajax({
        type: "GET",  
        url: '{!! URL::to("ajax_get_subservice") !!}',  
        data:'serviceId='+serviceId+'&categoryId='+categoryId,
        dataType: "json", 
        success: function(data) 
        {

           if(data.success == true) 
           {
                         //console.log(data.subservices);
                         //alert(data.message);
                         sessionStorage.setItem("subservicesArray", JSON.stringify(data.subservices));
                         sessionStorage.setItem("servicename",data.servicename);
                     }else 
                     {
                        //alert(data.message);
                    }
                }  
            });

      //End Ajax

  });
  });



    function mergeSubServiceArray()
    {
    //alert("merge data on step 2");
    var subservices = sessionStorage.getItem('subservicesArray');
    var servicename = sessionStorage.getItem('servicename');
    var dattaa=JSON.parse(subservices);
    //console.log(dattaa);
    if(dattaa)
    {
     $('#sideServiceName').replaceWith('<li><h6>Servicio</h6><p>'+servicename+'</p></li>');
     var i=0;
     dattaa.forEach((item) => 
     {
            // console.log('id: ' + item.id);
            // console.log('en_name: ' + item.en_name);
            var loopData='<label class="cust-radio">'+item.es_name+'<input type="radio" value="'+item.id+'" class="subservice_class" onClick="getSubserviceIdForChild('+item.id+')" name="getsubservice_id" id="sbsr_id'+i+'" data-subservicename ="'+item.es_name+'"><span class="checkmark"></span></label>';
            $('#appendSubserviceArray').append(loopData);

            i++;
        });

 }

}


</script>

<!---Get subservice accordingly Service ID Onchange IN STEP 1-->








<script type="text/javascript">

  function nextbtnn($this) 
  {    

    var check_validation = false;
    var ref_this = $("ul.nav-tabs li a.active");

    var title = ref_this.data("title");
    var check = $('.pre-btn').data("check");
    var step = ref_this.data("step");
    var isFoundChild = sessionStorage.getItem('isFoundChild');
    if (title=='address-title') 
    {
        value= $('#address').val();
        if(value=='')
        {
            $('.addressError').addClass('addressfiled').html('Llene los campos obligatorios');
            return false;
        }
            $('.addressError').removeClass('addressfiled').html('');
    }

    if (title=='username-title')
    {
        value= $('#username').val();
        if(value=='')
        {
            $('#userError').addClass('userfield').html('Llene los campos obligatorios');
            return false;
        }
            $('#userError').removeClass('userfield').html('');
    }
    if(title=='mobileNumber-title')
    {
        value= $('#mobile_number').val();
        if(value=='')
        {
            $('.mobileError').addClass('mobilefiled').html('Llene los campos obligatorios');
            return false;
        }
            $('.mobileError').removeClass('mobilefiled').html('');
    } 
    if (title=='email-title')
    {
        value= $('#emailMultiform').val();
        if(value=='')
        {
            $('.emailError').addClass('emailfiled').html('Llene los campos obligatorios');
            return false;
        }
            $('.emailError').removeClass('emailfiled').html('');
    } 
    if(isFoundChild==0  && step==2)
    {
        //alert("found 0");
        var step_new = ref_this.data("step") + 2;
    }
    else
    {
        var step_new = ref_this.data("step") + 1;
    }

    $('.pre-btn').removeAttr("data-check");
 

  
  var current_step = '#step'+step;
  var next_step = '#step'+step_new;

  var current_tab = '#tab'+step;
  var next_tab = '#tab'+step_new;    


      if(title == 'services-title') //Step 2
      {    $('.form-btn').hide();
         $('#appendSubserviceArray').empty();
         mergeSubServiceArray();
         check_validation = true;
     }

      if (title=='subservices-title') //Step 3
      {$('.form-btn').hide();
          $('#childServicesArrayAppend').empty();
          mergeChildServiceArray();
          check_validation = true;
      }

       if (title=='childservices-title') //Step 4
       {$('.form-btn').hide();
        $('#QuestionArea').empty();
        $('#optionArea').empty();

        mergeFisrtQuestionArray();
        check_validation = true;
    }

      if (title=='question-title') //Step 5 till end questionare
      {
        $('.form-btn').hide();
          mergeNextQuestionArray();
          check_validation = true;
      }

     //******* USERNAME TAB START HERE ******
     if (title=='address-title') 
     { 
        $('.form-btn').show();
          //alert("username tab");

          $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="username-title" data-step ="'+step_new+'" ></a></li>');
          $('#multi-step-dataHere').append('<div class="tab-pane usernameTab" id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> @lang("labels.frontend.home_page.identify_yourself")</h2></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png');?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.enter_your_full_name")</h5></div></div></div><div class="form-detail"><div class="form-row"><div class="form-group col-md-12"><input type="text" name="username"  id="username" placeholder="@lang("labels.frontend.home_page.write_here")"></div></div><p id="userError"></p></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)" >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');

          check_validation = true;


      }
      //******* USERNAME TAB END HERE ******-->


        //******* MOBILE NUMBER TAB START HERE ******
        if (title=='username-title') 
        { $('.form-btn').show();
            //alert("mobile number tab");

            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="mobileNumber-title" data-step ="'+step_new+'" ></a></li>');

            $('#multi-step-dataHere').append( '<div class="tab-pane mobileNumberTab" id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title"> @lang("labels.frontend.home_page.share_your_phone_number")</div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png'); ?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.enter_your_cell_phone_number")</h5></div></div></div><div class="form-detail"><div class="input-group form-group mb-3"><div class="input-group-prepend"><span class="input-group-text" id="country-code">+593</span></div><input type="text" name="mobile_number" id="mobile_number" class="form-control" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="mobileError"></p></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" onclick="prevbtnn(this)" >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn" onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');


            check_validation = true;
            
        }
           //******* MOBILE NUMBER TAB END HERE ******


            //******* EMAIL TAB START HERE ******
            if (title=='mobileNumber-title') 
            { $('.form-btn').show();
            //alert("email tab");

            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="email-title" data-step ="'+step_new+'" ></a></li>');

            $('#multi-step-dataHere').append('<div class="tab-pane emailIdTab" id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title">@lang("labels.frontend.home_page.we_will_send_you_a_code_to_validate_your_request")</div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png'); ?>"><div class="media-body"><h5 class="mt-0">@lang("labels.frontend.home_page.enter_your_email")</h5></div></div></div><div class="form-detail"><div class="input-group form-group mb-3"><input type="email" name="email" id="emailMultiform" class="form-control" placeholder="@lang("labels.frontend.home_page.write_here")"></div><p class="emailError"></p></div></div><div  class="form-btn mid-btn"><button type="button" class="btn next-btn" id="email_next" onclick="prevbtnn(this)" >@lang("labels.frontend.home_page.previous")</button><button type="button" class="btn pre-btn"  onclick="nextbtnn(this)" >@lang("labels.frontend.home_page.next")</button></div></div></div>');

            check_validation = true;
        }
           //******* EMAIL TAB END HERE ******



          //******* OTP VERIFY TAB START HERE ******
          if (title=='email-title') 
          {
            $('.form-btn').show();
          //Send Otp to mail
          var email = $('#emailMultiform').val();
          if(email == '') 
          {
              alert('please enter email');
          } else 
          {
              var check_email =  true;
              if(check_email == true) 
              {
                //alert("true");
                $.ajax({
                    type: "GET",
                    url: '{!! URL::to("send_otp_mail") !!}',
                    data:'email='+email,
                    success: function(data){
                      if(data.success == true) 
                      {
                        $('#otpcode').val(data.otpcode);
                        alert(data.message);
                        var append_number = '.otp_mobile_number';        
                        var append_number_html = "<h5 class='mt-0'>@lang('labels.frontend.home_page.enter_the_security_code_sent_to')" +' ' + email+"</h5>";
                        $(append_number).append(append_number_html);

                        timerStart(240);

                        check_validation = true;  
                        //alert(check_validation);
                        $(current_step).removeClass('active show');
                        $(current_tab).removeClass('active show');

                        $(next_step).addClass('active show');
                        $(next_tab).addClass('active show');   

                    } else 
                    {
                        alert(data.message);
                    }
                }
            });
            } else {
                alert('please enter valid email');
            } 
        }

          //End send OTP to mail 

            //alert("otp tab");

            $('#multi-step-application').append('<li class="nav-item"><a class="inactiveLink nav-link" data-toggle="tab" id="tab'+step_new+'" data-title ="otpverify-title" data-step ="'+step_new+'" ></a></li>');

            $('#multi-step-dataHere').append( '<div class="tab-pane otpVerifyTab" id="step'+step_new+'"><div class="mid-steps"><div class="mid-heading"><h2 class="modal-title">@lang("labels.frontend.home_page.almost_ready")</h2></div><div class="mid-data-fill"><div class="mid-head"><div class="media"><img class="mr-3" src="<?php echo asset('img/frontend/shield.png');?>"><div class="media-body otp_mobile_number"></div></div></div><div class="form-detail"><div class="form-group code-num"><input type="text" name="otpvalue[]" id="otp1" class="form-control inputs"  placeholder="0" maxlength="1"><input type="text" name="otpvalue[]" id="otp2" class="form-control inputs"  placeholder="0" maxlength="1"><input type="text" name="otpvalue[]" id="otp3" class="form-control inputs"  placeholder="0" maxlength="1"><input type="text" name="otpvalue[]" id="otp4" class="form-control inputs"  placeholder="0" maxlength="1"><div>Tiempo restante = <span id="timer"></span></div></div></div></div><div  class="form-btn mid-btn"><button type="button" class="btn pre-btn"  id="sub_mit" onClick="submitMultistepForm()">@lang("labels.frontend.home_page.submit")</button> <button type="submit" class="btn pre-btn" id="submit_form" style="display: none;" >@lang("labels.frontend.home_page.submit")</button></div></div></div>');


            check_validation = true;
        }
           //******* OTP VERIFY TAB END HERE ******


    //alert(check_validation);
    if(check_validation == true) {

      $(current_step).removeClass('active show');
      $(current_tab).removeClass('active show');

      $(next_step).addClass('active show');
      $(next_tab).addClass('active show');      
  }

}

function prevbtnn($this) {    

    var ref_this = $("ul.nav-tabs li a.active");
    var title = ref_this.data("title");
    //alert(title);
    if(title=='subservices-title')
    {
        $('#summary').children().last().remove();
    }
    else
    {
        $('#summary').children().last().remove();
        $('#summary').children().last().remove();
    }

    if(title == 'question' || title == 'address') 
    {
      var append_id = '#summary';
      $(append_id).children().last().remove();

  }

  var step = ref_this.data("step");

  var isFoundChild = sessionStorage.getItem('isFoundChild');

  if(isFoundChild=="0" && step==4)
  {
    var step_new = ref_this.data("step") - 2;
}
else
{
 var step_new = ref_this.data("step") - 1;
}


var current_step = '#step'+step;
var prev_step = '#step'+step_new;

var current_tab = '#tab'+step;
var prev_tab = '#tab'+step_new;


$(current_step).removeClass('active show');
$(current_tab).removeClass('active show');

$(prev_step).addClass('active show');
$(prev_tab).addClass('active show');    

}

function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!regex.test(email)) {
      return false;
  }else{
      return true;
  }
}

function sendOtp(email) {  
    var success = 'true';      
    $.ajax({
      type: "GET",
      url: '{!! URL::to("send_otp_mail") !!}',
      data:'email='+email,
      success: function(data){
        alert('in success');
        if(data.success == true) {
          $('#otpcode').val(data.otpcode);
          alert(data.message);
          return success;             
      } else {
          alert(data.message);
          success = 'false';
          return success;             
      }
  }
});
}
</script>
<script>
    $(document).ready(function(){
      $(".left-sidebar-show").click(function(){
        $("#sidebar-wrapper").toggle();
    });
  });
</script>

<script>


   $("#owl-demo").owlCarousel({
       items : 5,
       itemsDesktop : [1199,4],
       itemsDesktopSmall : [980,3],
       itemsTablet: [768,2],
       itemsTabletSmall: true,
       itemsMobile : [479,1],
       singleItem : false,

    //Basic Speeds
    slideSpeed : 200,
    paginationSpeed : 800,
    rewindSpeed : 1000,

    //Autoplay
    autoPlay : false,
    stopOnHover : false,

    // Navigation
    navigation : false,
    navigationText : ["prev","next"],
    rewindNav : true,
    scrollPerPage : false,

    //Pagination
    pagination : true,
    paginationNumbers: false,
});


</script>

<style type="text/css">
  .tab-pane
  {
    height: 1500px !important;
}

button.close {

    margin-top: -26px;
}
</style>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-591XW14LWG"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-591XW14LWG');
</script> 
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window,document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '2739848256313762'); 
fbq('track', 'PageView');
</script>
<noscript>
<img height="1" width="1" 
src="https://www.facebook.com/tr?id=2739848256313762&ev=PageView
&noscript=1"/>
</noscript>
