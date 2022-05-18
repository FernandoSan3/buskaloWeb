@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')

<section class="works-sec serv-online mx">
  <div class="container">
      <div class="heading">
        <img class="serv-img" src="{{ url('img/frontend/ser.jpg') }}">
         <h2>@lang('labels.frontend.service_online.how_the_service_search_works') <b>@lang('labels.frontend.service_online.online')</b></h2>
         <p> ¡@lang('labels.frontend.service_online.simple')! @lang('labels.frontend.service_online.you_offer_professionals')</p>
        <span class="bottom-border"></span> 
      </div>
    <div class="row">
      <div class="col-md-4">
        <div class="work-inner">
          <div class="process-list">
            <span>1</span>
          </div>
          <div class="process-text">
            <p> @lang('labels.frontend.service_online.find_the_online')</p>
        </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="work-inner">
          <div class="process-list">
            <span>2</span>
          </div>
          <div class="process-text">
            <p> @lang('labels.frontend.service_online.compare_our_offer')</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="work-inner wi">
          <div class="process-list">
            <span>3</span>
          </div>  
          <div class="process-text">
            <p> @lang('labels.frontend.service_online.hire_the_professional')</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>   

<section class="more-service-sec mx">
  <div class="container">
    <div class="serv-heading">
      <h4><img src="{{ url('img/frontend/list.svg') }}">@lang('labels.frontend.service_online.main_categories')</h4>
    </div>
    
    <div class="row"> 
     @foreach($servicesonline2 as $service)  
    @if(file_exists(public_path('/img/'.$service->image)) && !empty($service->image)) 
    <div class="col-md-3 col-sm-6">
        <a href="javascript:void()" id='{{$service->id}}' class="getcategory">
            <div class="left-serv div_show">
                <button type="btn" data-toggle="modal" data-target="#serviceModal"
                class="edit-btn">
                    <div class="more-inner">
                        <img src="{{url('/img/'.$service->image)}}">
                        <div class="txt-more">
                            <h5>{{isset($service->es_name)?$service->es_name:$service->en_name}}</h5>
                        </div>
                    </div>
                </button>
            </div>
        </a>
    </div>

      @endif
    @endforeach
        <!-- <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s1.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.languages') </h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s2.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.academic_support') </h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s3.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.couple_theapy')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s4.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.medical_consultations')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s5.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.legal_consultations')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s6.jpg') }}">
              <div class="txt-more">
               <h5>@lang('labels.frontend.service_online.psychological_therapy')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s7.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.dance')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s8.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.courses')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s9.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.music')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s10.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.coaches')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s11.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.accounting_services')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s12.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.cons_pediatric')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s13.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.dance_classes')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s14.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.martial_arts')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s15.jpg') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.nutritionist')</h5>
              </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="more-inner">
              <img src="{{ url('img/online_services/s16.png') }}">
              <div class="txt-more">
                <h5>@lang('labels.frontend.service_online.zoom_ba')</h5>
              </div>
            </div>
          </div>
    </div> -->
    <div class="service-list">
      <div class="btn-div">
        <a href="{{url('services_online')}}" class="add-catg orange">+ @lang('labels.frontend.service_online.all_categories')</a>
      </div>
    </div>
    <div class="specific-div mx">
      <div class="form-group row">
        <label class="col-sm-4 col-form-label">¿@lang('labels.frontend.service_online.something_specific')?</label>
        <div class="col-sm-8">
          <div class="specific-input">
            <select id='category_id3' name="category_id" class="form-control categoryAuto categoryAuto1">
                <option>@lang('labels.frontend.home_page.select_categories')</option> 
                @if(isset($mainCatrgory) && !empty($mainCatrgory))
                  @foreach($mainCatrgory as $category)
                    <option value="{{ $category->id }}" data-name="{{$category->es_name}}" data-type="category">{{$category->es_name}}</option>
                  @endforeach
                @endif
              </select>
         <!--  <input type="email" class="form-control" id="inputEmail3" placeholder="@lang('labels.frontend.service_online.what_you_want')"> -->
          <button class="btn"  data-toggle="modal" data-target="#serviceCaregoryModal"
                          class="edit-btn"><i class="fa fa-plus-circle"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="online-service mx">
  <div class="container">
    <div class="online-sec">
      <div class="row">
        <div class="col-md-12 col-lg-6">
          <div class="left-online">
            <div class="heading">
            <h2>@lang('labels.frontend.service_online.world_changes_rapidly')<span class="orange">¡@lang('labels.frontend.service_online.enjoy_it')!</span></h2>
            <h5>@lang('labels.frontend.service_online.way_of_hiring') <br>@lang('labels.frontend.service_online.services') <b>@lang('labels.frontend.service_online.professionals')</b> @lang('labels.frontend.service_online.safely_and_simply')</h5>

            <ul class="online-list">
              <li>@lang('labels.frontend.service_online.reduce_costs')!</li>
              <li>@lang('labels.frontend.service_online.greater_schedule_flexibility')</li>
              <li>@lang('labels.frontend.service_online.comfort')</li>
              <li>@lang('labels.frontend.service_online.evita_desplazamientos')</li>
              <li>@lang('labels.frontend.service_online.professionals_from_anywhere')</li>
              <li>@lang('labels.frontend.service_online.connect_even')</li>
              <li>@lang('labels.frontend.service_online.anonymity')</li>
            </ul>

            <div class="security-sec">
              <div class="media">
                <img class="mr-3" src="{{ url('img/frontend/shield.png') }}">
                <div class="media-body">
                  <h5 class="mt-0">@lang('labels.frontend.service_online.your_payments_are') <span class="green"> 100%</span> @lang('labels.frontend.service_online.insurance')</h5>
                  <p>@lang('labels.frontend.service_online.card_payment_system') <span class="orange">@lang('labels.frontend.service_online.anti_frude')</span></p>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
        <div class="col-md-12 col-lg-6">
          <div class="right-online">
            <img src="{{ url('img/frontend/el.jpg') }}">
          </div>
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
  $('#mCategoryId').val(this.id);
  $('#selectedValue1').val(this.id);
  
  //alert(this.id);
});
</script>
@endsection



