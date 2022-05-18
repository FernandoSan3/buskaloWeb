<section class="slider-sec">
  <div id="demo" class="carousel slide" data-ride="carousel">

  <!-- Indicators -->
  <ul class="carousel-indicators">
    <li data-target="#demo" data-slide-to="0" class="active"></li>
    <li data-target="#demo" data-slide-to="1"></li>
    <li data-target="#demo" data-slide-to="2"></li>
    <li data-target="#demo" data-slide-to="3"></li>
  </ul>
  
  <!-- The slideshow -->
  <div class="carousel-inner">
    <div class="carousel-item active" style="background-image: url({{ url('img/frontend/slider-img.jpg') }})">
     <!--  <img src="{{ url('img/frontend/slider-img.jpg') }}" alt="Los Angeles"> -->
    </div>
    <div class="carousel-item"  style="background-image: url({{ url('img/frontend/slide2.jpg') }})">
     <!--  <img src="{{ url('img/frontend/slide2.jpg') }}" alt="Chicago"> -->
    </div>
    <div class="carousel-item"  style="background-image: url({{ url('img/frontend/slide3.jpg') }})">
      <!-- <img src="{{ url('img/frontend/slide3.jpg') }}" alt="New York"> -->
    </div>
    <div class="carousel-item"  style="background-image: url({{ url('img/frontend/slide4.jpg') }})">
      <!-- <img src="{{ url('img/frontend/slide4.jpg') }}" alt="New York"> -->
    </div>
    <div class="carousel-caption">
      <h3><span>“</span>@lang('labels.frontend.home_page.we_make_your_life')  @lang('labels.frontend.home_page.easier')  <span class="orange">@lang('labels.frontend.home_page.online_service')</span><b> @lang('labels.frontend.home_page.online')</b><span>”</span></h3>
      <div class="row">
        <div class="col-md-3">
          <div class="carousel-right">
            <img src="{{ url('img/frontend/user1.png') }}">
          </div>
        </div>
        <div class="col-md-9">
          
          <ul>
            <li>@lang('labels.frontend.home_page.service_answer_and_questions')</li>
            <li>@lang('labels.frontend.home_page.service_receive_information')</li>
            <li>@lang('labels.frontend.home_page.service_best_one')</li>
          </ul>
        </div>
        
      </div>

      {{ html()->form('POST', route('frontend.step_one'))->attribute('enctype', 'multipart/form-data')->class('banner-form')->open() }}

        <div class="form-row">
          <div class="col-lg-7 col-md-6 col-sm-12">
            <div class="form-group">
              <select id='category_id' name="category_id" class="form-control categoryAuto" required oninvalid="this.setCustomValidity('Llene los campos obligatorios');"  onchange="try{setCustomValidity('')}catch(e){};">
                <option value="">@lang('labels.frontend.home_page.select_categories')</option> 
                @if(isset($mainCatrgory1) && !empty($mainCatrgory1))
                  @foreach($mainCatrgory1 as $category)
                    <option value="{{ $category['id'] }}" data-name="{{$category['es_name']}}" data-type="{{$category['servicetype']}}">{{$category['es_name']}}</option>
                  @endforeach
                @endif
              </select>
            </div>
          </div>

          <div class="col-lg-5 col-md-6 col-sm-12">
            <div class="form-group loc-input">
              <select id='selcity' name="city_id" class="form-control" {{-- style='width: 200px;' --}} required oninvalid="this.setCustomValidity('Llene los campos obligatorios');"  onchange="try{setCustomValidity('')}catch(e){};">
                <option value=''>@lang('labels.frontend.home_page.select_cities')</option> 
                @if(isset($cities) && !empty($cities))
                  @foreach($cities as $city)
                    <option  value='{{ $city->id }}'>{{$city->name}}</option>
                  @endforeach
                @endif
              </select>

              <input type="hidden" name="selected_type" id="selectedType" value=""></input>
              <input type="hidden" name="selected_value" id="selectedValue" value=""></input>

              <button type="submit" id="search_service1" class="btn book-btn"><img src="{{ url('img/frontend/search.png') }}"></button>
            </div>
          </div>
        </div>

       {{ html()->form()->close() }}

    </div>
  </div>
  
  <!-- Left and right controls -->
  <a class="carousel-control-prev" href="#demo" data-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#demo" data-slide="next">
    <span class="carousel-control-next-icon"></span>
  </a>
</div>
</section>
