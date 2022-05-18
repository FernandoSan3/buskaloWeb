@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')

  

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



