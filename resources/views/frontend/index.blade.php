@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
<section class="works-sec mx mt-4">
  <div class="container">
      <div class="heading">
        <h2>@lang('labels.frontend.home_page.how_does_it_work')</h2>
        <span class="bottom-border"></span>
      </div>

    <div class="row">
      @foreach($work as $howitiswork)
      <div class="col-md-4">
        <div class="work-inner">
          <div class="process-list">
            <span>1</span>
          </div>
          <div class="process-text">
            <h4>Busca</h4>
            <p>Escribe lo que necesitas y responde unas preguntas de nuestro formulario</p>
            <!-- <h4>{{$howitiswork->search}}</h4>
            <p> {{$howitiswork->search_descriptiom}}</p> -->
        </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="work-inner">
          <div class="process-list">
            <span>2</span>
          </div>
          <div class="process-text">
            <h4>Compara</h4>
            <p>Te enviaremos la información de hasta 3 profesionales que cumplan con tus requerimientos, tendrás información importante que te ayude a decidir.</p>
            <!--  <h4>{{$howitiswork->compare}}</h4>
            <p> {{$howitiswork->compare_description}}</p> -->
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="work-inner wi">
          <div class="process-list">
            <span>3</span>
          </div>
          <div class="process-text">
            <h4>Contrata</h4>
            <p>Puedes llamar o chatear con los profesionales y negociar directamente con ellos.</p>
             <!-- <h4>{{$howitiswork->hire}}</h4>
            <p> {{$howitiswork->hire_description}}</p> -->
          </div>
        </div>
      </div>
    </div>
    <div class="bottom-head">
     <h3>@lang('labels.frontend.home_page.it_was_never_so') <span class="orange">@lang('labels.frontend.home_page.easy')</span> @lang('labels.frontend.home_page.everything_find')</h3>
   </div>
  </div>
  @endforeach
</section>
<section class="more-service-sec mx">
  <div class="container">
    <div class="serv-heading">
      <!-- <h4><img src="{{ url('img/frontend/list.svg') }}">Categorías principales</h4> -->
      <h4><i class="fa fa-briefcase" aria-hidden="true"></i> &nbsp; @lang('labels.frontend.home_page.all_services')</h4>
    </div>
    
    <div class="row">
      @if(isset($categories) && !empty($categories))
      @foreach($categories as $key => $service)    
      <div class="col-md-3 col-sm-6">
         <a href="javascript:void()" data-id='{{$service->id}}' class="getcategory2">
            <div class="left-serv div_show1">
                 <button type="btn" data-toggle="modal" data-target="#serviceCaregoryModal"
                class="edit-btn">
        <div class="more-inner">
         <?php 
              $image="";
              $findinfolder="";
                if(isset($service->image))
                  { 
                    $image=$service->image;
                    $findinfolder=public_path().'/img/'.$service->image;
                   }
              if (file_exists($findinfolder) && !empty($image)) 
              {?>
              <img  src="{{asset('img/')}}/{{$image}}">
              <?php } else{ ?>
              <img class="" style="height: 180px;width: 100%;" src="{{asset('img/frontend/no-image-available.jpg')}}">
              <?php } ?>
           <div class="txt-more">
            <h5>{{$service->es_name}}</h5>
          </div>
        </div>
         </button>
     </div>
 </a>
      </div>
      @endforeach
      @endif
    </div>
     <div class="row">
      <div class="ser_pagination"> 
        {!! $categories->render() !!}
      </div>
    </div>
  </div>
</section>
<section class="app-sec mx">
  <div class="container">
    <div class="row ">
      <div class="col-md-6 ">
        <div class="mobile-screen">
        </div>
      </div>

        <div class="col-md-6 col-sm-12">
          <div class="app-right">
            <h4>@lang('labels.frontend.home_page.hire_photographer')</h4>
            <p class="download-txt"> @lang('labels.frontend.home_page.see_profile')</p>

            <div class="app-btns">
              <a href="https://apps.apple.com/us/app/búskalo/id1580560610"><img src="{{ url('img/frontend/apple.svg') }}"></a>
              <a href="https://play.google.com/store/apps/details?id=com.wdp.Buskalo"><img src="{{ url('img/frontend/google.svg') }}"></a>
            </div>

           <!--  <a href="#" class="desc-link">@lang('labels.frontend.home_page.download')</a> -->
            
          </div>
      </div>
     </div>
      
  </div>
</section>
<section class="service-sec mx">
  <div class="container">
    <div class="service-list">
      <ul>
       
        @foreach($servicesnotonl as $service)
        <li>
          <a href="javascript:void()" data-id='{{$service->id}}' class="getcategory">
            <div class="left-serv div_show">
             
                 
                  @if(file_exists(public_path('/img/'.$service->image)) && !empty($service->image))
                 <button type="btn" data-toggle="modal" data-target="#serviceModal"
                          class="edit-btn">
                   <img width="40px" height="30px" src="{{url('/img/'.$service->image)}}" onerror="this.src='{{asset('img/noimage.png')}}'">
                  <p>{{$service->es_name}}</p>
                  <div class="right-serv">
                      <img src="{{ url('img/frontend/arrow-right.svg') }}">
                  </div>
                </button>
                @endif
              <!--   <?php if(!empty($service->image!='Null')){?>
                   
                <?php }else{?>
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                <?php }?>
                 -->
                  
            </div>
          </a>
        </li>
        @endforeach
      </ul>
      <div class="btn-div">
        <a href="{{url('/services_online')}}" class="add-catg orange" id="load2">+ @lang('labels.frontend.home_page.all_categories')</a>
      </div>
    </div>
 

    <div class="specific-div mx">
      <div class="form-group row">
        <label class="col-sm-4 col-form-label">¿@lang('labels.frontend.home_page.something_specific')?</label>
        <div class="col-sm-8">
          <div class="specific-input">
            <select id='category_id2' name="category_id" class="form-control categoryAuto categoryAuto1">
                <option>@lang('labels.frontend.home_page.select_categories')</option> 
                @if(isset($mainCatrgory) && !empty($mainCatrgory))
                  @foreach($mainCatrgory as $category)
                    <option value="{{ $category->id }}" data-name="{{$category->es_name}}" data-type="category">{{$category->es_name}}</option>
                  @endforeach
                @endif
              </select>
         <!--  <input type="email" class="form-control" id="inputEmail3" placeholder="@lang('labels.frontend.home_page.enter_search')"> -->
          <button class="btn" data-toggle="modal" data-target="#serviceCaregoryModal"
                          class="edit-btn"><i class="fa fa-plus-circle"></i></button>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
<section class="intro-sec mx">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-6">
        <div class="into-main">
          <h2>@lang('labels.frontend.home_page.tasks')</h2>
          <p>{!! $howitiswork->description !!}</p>
       <!--  <div class="about-btn"><a href="#">@lang('labels.frontend.home_page.buska')</a></div> -->
        </div>
      </div>
      <div class="col-md-6">
        <div class="intro-img">
          <img src="{{url('/img/frontend/work/'.$howitiswork->image)}}">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="review-sec mx">
  <div class="container">
    <div class="review-heading">
      <div class="review-icon">
        <img src="{{ url('img/frontend/review-add.svg') }}">
      </div>
      <div class="review-head">
        <h2>@lang('labels.frontend.home_page.our_desire')</h2>
        <h6>@lang('labels.frontend.home_page.everything_ask') <sapn class="orange">búskalo</sapn> @lang('labels.frontend.home_page.professional_profile')</h6>
      </div>
    </div>

     <div class="row">
            <div id="owl-carousel" class="owl-carousel owl-theme">
                 @foreach($review_datas as $home_review)
                <div class="item">
                    <div class="user-rev">
                        <div class="user-heading">
                          <div class="user-left">
                             <img src="{{ url('img/contractor/profile', $home_review->avatar_location) }}" style="height: 60px; width: 60px;" onerror="this.src='{{asset('img/noimage.png')}}'" onContextMenu="return false;" >
                          </div>
                          <div class="user-right">
                             <h6>{{ number_format($home_review->rating,1)}}</h6>
                             <?php $count= $home_review->rating;?>
                            <p>
                                <?php
                                for ($i=1; $i <6 ; $i++)
                                {
                                   if($i > $count)
                                   {
                                    echo '<i class="fa fa-star"></i>';

                                    }else{
                                        echo '<i class="fa fa-star" style="color:#ffcd1b"></i>';
                                    }
                                }?>
                            </p>
                          </div>
                        </div>
                        <div class="user-content">
                          <p>{{$home_review->review}}</p>
                          <h6>{{$home_review->username}}, {{$home_review->provider_name}} </h6>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
</section>
   
</div>
<section class="profession-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-lg-6 col-sm-12">
        <div class="prof-left">
          <img src="{{ url('img/frontend/Pro.png') }}">
        </div>
      </div>
      <div class="col-md-8 col-lg-6 col-sm-12">
        <div class="prof-right">
          <h2>¿@lang('labels.frontend.home_page.are_you_professional')<br>
                @lang('labels.frontend.home_page.in_what_you_do_and_offer')?</h2>
        <p>@lang('labels.frontend.home_page.find_service')<br>
            @lang('labels.frontend.home_page.high_quality')<br>
            @lang('labels.frontend.home_page.collaborators')</p>
        <a href="{{url('/profesional/register')}}"><button class="btn prof-btn">            @lang('labels.frontend.home_page.join_now')</button></a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="partner-sec">
  <div class="container">
    <div class="owl-slider">
      <div id="carousel" class="">
        <div class="owl-item">
          <img src="{{ url('img/frontend/p1.jpg') }}">
          <p>@lang('labels.frontend.home_page.multinational_corporation')</p>
        </div>
        <div class="item">
          <img src="{{ url('img/frontend/p2.jpg') }}">
          <p>@lang('labels.frontend.home_page.multinational_aerospace')</p>
        </div>
        <div class="item">
          <img src="{{ url('img/frontend/p3.jpg') }}">
          <p>@lang('labels.frontend.home_page.airbus_subsidiary')</p>
        </div>
        <div class="item">
          <img src="{{ url('img/frontend/p4.jpg') }}">
          <p>@lang('labels.frontend.home_page.pioneering_company')</p>
        </div>
          <div class="item">
          <img src="{{ url('img/frontend/p1.jpg') }}">
          <p>@lang('labels.frontend.home_page.operating_mainly')</p>
        </div>
        <div class="item">
          <img src="{{ url('img/frontend/p2.jpg') }}">
          <p>@lang('labels.frontend.home_page.defense')</p>
        </div>
        <div class="item">
          <img src="{{ url('img/frontend/p3.jpg') }}">
          <p>@lang('labels.frontend.home_page.additive')</p>
        </div>
        <div class="item">
          <img src="{{ url('img/frontend/p4.jpg') }}">
          <p>B Medical System is a pioneering company in the medical equipment industry.</p>
        </div>
      </div>
    </div>
  </div>
</section>
       
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content cuiddadddd">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Seleccione su ciudad</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            {{ html()->form('POST', route('frontend.step_one'))->attribute('enctype', 'multipart/form-data')->class('banner-form')->open() }}
            <div class="modal-body">
                <div class="edit-form">
                    <div class="form-edit">
                        <label>Ciudades</label>
                        <div id="services" class="form-edit">
                        <ul class="area-list meta-list multi-cities-list ">
                          <select id='selcity' name="city_id" class="form-control" {{-- style='width: 200px;' --}}  required oninvalid="this.setCustomValidity('Llene los campos obligatorios');"  onchange="try{setCustomValidity('')}catch(e){};">

                          <option value=''>Seleccione una</option> 
                          @if(isset($cities) && !empty($cities))
                            @foreach($cities as $city)
                              <option  value='{{ $city->id }}'>{{$city->name}}</option>                   
                            @endforeach
                          @endif
                        </select>
                        <input type="hidden" name="selected_type" id="selectedType" value="service"></input>
                        <input type="hidden" name="selected_value" id="selectedValue1" value=""></input>
                        <input type="hidden" name="category_id" id="mCategoryId" value="" ></input>


                        </ul>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn close-btn" data-dismiss="modal">Cerrar</button>
                  <!-- <button type="submit" id="search_service" class="btn book-btn"><img src="{{ url('img/frontend/search.png') }}"></button> -->
                  <button type="submit" id="search_service1" class="btn opp-btn"><img src="{{ url('img/frontend/search.png') }}"></button>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>
<div class="modal fade" id="serviceCaregoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content cuiddadddd">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Seleccione su ciudad</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            {{ html()->form('POST', route('frontend.step_one'))->attribute('enctype', 'multipart/form-data')->class('banner-form')->open() }}
            <div class="modal-body">
                <div class="edit-form">
                    <div class="form-edit">
                        <label>Ciudades</label>
                        <div id="services" class="form-edit">
                        <ul class="area-list meta-list multi-cities-list ">
                          <select id='selcity' name="city_id" class="form-control" {{-- style='width: 200px;' --}}  required oninvalid="this.setCustomValidity('Llene los campos obligatorios');"  onchange="try{setCustomValidity('')}catch(e){};">

                          <option value=''>Seleccione una</option> 
                          @if(isset($cities) && !empty($cities))
                            @foreach($cities as $city)
                              <option  value='{{ $city->id }}'>{{$city->name}}</option>                   
                            @endforeach
                          @endif
                        </select>
                        <input type="hidden" name="selected_type" id="selectedType" value="category"></input>
                        <input type="hidden" name="selected_value" id="selectedValue12" value=""></input>
                        <input type="hidden" name="category_id" id="mCategoryId2" value="" ></input>


                        </ul>
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn close-btn" data-dismiss="modal">Cerrar</button>
                  <button type="submit" id="search_service1" class="btn opp-btn"><img src="{{ url('img/frontend/search.png') }}"></button>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>

<style type="text/css">
  .div_show { display:none; }
  .div_show1 button.edit-btn {
    background: transparent;
    border: 0;
    font-size: 16px;
    font-weight: 600;
    text-align: right;
    cursor: pointer;
    width: 100%;
    display: flex;
    align-items: center;
}
</style>
<script type="text/javascript">

$(function(){
    $(".div_show").slice(0, 16).show(); // select the first ten
    $("#load").click(function(e){ // click event for load more
        e.preventDefault();
        $(".div_show:hidden").slice(0, 16).show(); // select next 10 hidden divs and show them
        if($(".div_show:hidden").length < 0){ // check if any hidden divs still exist
            alert("No more divs"); // alert if there are none left
        }
    });
});

$('.getcategory').click(function() {
  //$('#category_id').val($(this).data('id'));
  $('#mCategoryId').val($(this).data('id'));
  $('#selectedValue1').val($(this).data('id'));
  
  //alert(this.id);
});
$('.getcategory2').click(function() {
  $('#mCategoryId2').val($(this).data('id'));
  //$('#mCategoryId2').val(this.id);
  $('#selectedValue12').val($(this).data('id'));
  
  //alert(this.id);
});
</script>
    

    <script type="text/javascript">
        $(document).ready(function() {
 
  $('#owl-carousel').owlCarousel({
        items : 3,               
        //loop:true,
   
        nav:true,
  });
 
});
    </script>
@endsection
@section('after-script')
{{ script('js/owl.carousel.min.js') }}

@endsection

