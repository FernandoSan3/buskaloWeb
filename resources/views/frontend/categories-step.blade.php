@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')

<div class="category-bg">
    <!--  <div class="step-header"> -->
    <div class="container">
        @if(count($bannerImages)>0)
           <div class="category-slider-section">
                <div id="demo1" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        @foreach($bannerImages as $k=> $banner)
                        <li data-target="#demo1" data-slide-to="{{$k}}" @if($k==0) class="active" @endif ></li>
                        @endforeach
                        <!-- <li data-target="#demo1" data-slide-to="1"></li>
                        <li data-target="#demo1" data-slide-to="2"></li>
                        <li data-target="#demo1" data-slide-to="3"></li> -->
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        @foreach($bannerImages as $b=> $banner)
                        <div class="carousel-item @if($b==0)active @endif" style="background-image: url({{ url('bannerimage/'.$banner->banner_name) }})">
                        <!--  <img src="{{ url('img/frontend/slide1.jpg') }}" alt="Los Angeles"> -->
                        </div>
                        @endforeach
                       <?php /* <div class="carousel-item" style="background-image: url({{ url('img/frontend/slide2.jpg') }})">
                        <!-- <img src="{{ url('img/frontend/slide2.jpg') }}" alt="Chicago"> -->
                        </div>
                        <div class="carousel-item"  style="background-image: url({{ url('img/frontend/slide3.jpg') }})">
                        <!--  <img src="{{ url('img/frontend/slide3.jpg') }}" alt="New York"> -->
                        </div>
                        <div class="carousel-item"  style="background-image: url({{ url('img/frontend/slide4.jpg') }})">
                        <!-- <img src="{{ url('img/frontend/slide4.jpg') }}" alt="New York"> -->
                        </div>
                        */ ?>
                    </div>
                </div>
            </div>
        @endif
        <!-- <div class="step-logo">
      <img src="{{asset('img/frontend/logo.svg')}}">
        </div> -->

        <div class="media">
          <img class="mr-3" src="{{asset('img/frontend/doc.png')}}">
          <div class="media-body">
            <h5 class="mt-0">¡@lang('labels.frontend.home_page.simple')!</h5>
            <p>@lang('labels.frontend.home_page.complete_the')</p>
          </div>
        </div>
      
        <div class="category-modal">
            <ul class="nav nav-tabs" id="multi-step-application">
                <li class="nav-item">
                  <a class="inactiveLink nav-link <?php if($selected_type == "category"){ echo "active"; } ?>" id="tab1" data-title ="services-title" data-step ="1" data-toggle="tab" ></a>
                </li>


                <li class="nav-item">
                  <a class="inactiveLink nav-link <?php if($selected_type == "service"){ echo "active"; } ?>" data-toggle="tab" id="tab2"  data-title ="subservices-title" data-step ="2" ></a>
                </li>


                 <li class="nav-item">
                  <a class="inactiveLink nav-link <?php if($selected_type == "sub_service" && isset($allChildServices) && count($allChildServices) >0 ){ echo "active"; } ?>" data-toggle="tab" id="tab3"  data-title ="childservices-title" data-step ="3" ></a>
                </li>

                <li class="nav-item">
                  <a class="inactiveLink nav-link <?php if(($selected_type == "sub_service" && empty($allChildServices)) || ($selected_type == "child_sub_service")) { echo "active"; } ?>" data-toggle="tab" id="tab4" data-title ="question-title" data-step ="4" ></a>
                </li>
            </ul>
        </div>
    </div>

    <img src="{{asset('loading-gif.gif')}}" id="loading_gifs" style="display: none;" >

    <section class="categ-step-section">
      <div class="container">
        <div class="" id="serv-step1">
        
          <form id="regForm" method="post" action="{{ route('frontend.store_service_request') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="user_id" value="{{$user_id }}">
            <input type="hidden" id="getcity_id" name="getcity_id" value="<?php echo isset($city_id) && !empty($city_id) ? $city_id : '' ;  ?>">
            <input type="hidden" id="otpcode" name="otpcode" value="">
            <input type="hidden" id="getcategory_id" name="getcategory_id" value="<?php echo isset($category_id) && !empty($category_id) ? $category_id : '' ;  ?>">
            
            <div class="tab-content" id="multi-step-dataHere">


             <!---* SELECT SERVICE ACCORDINGLY CATEGORY ID.STEP 1 START HERE *-->
              <div class="tab-pane <?php if($selected_type == "category"){ echo "active"; } ?>" id="step1">
                <div class="pro-heading">
                  <h3>@lang('labels.frontend.home_page.services')</h3>
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <div class="pro-info">
                      <div class="meta-list">

                      @php $i=0; $serviceIdc=0; @endphp

                      @php if(isset($serviceId)) $serviceIdc=$serviceId; @endphp

                      @if(isset($allServices) && count($allServices) >0 || $serviceIdc)
                        @foreach ($allServices as $key => $getservices) 
                          <label class="cust-radio">{{$getservices->es_name}}
                          <input type="radio" value="{{$getservices->id}}" class="stepOneServiceClass" <?php if($getservices->id == $serviceIdc){ echo "checked='checked' ";} else{ echo " "; } ?> name="getservice_id" id="ssr_id{{$i}}" data-servicename ="{{$getservices->es_name}}">
                          <span class="checkmark"></span>
                         </label>
                         @php $i++ @endphp
                        @endforeach
                     @endif
                        
                      </div>
                    </div>
                    <div style="overflow:auto;">
                      <div  class="form-btn">
                   
                        <button type="button" class="btn pre-btn" onClick="nextbtnn(this)">@lang('labels.frontend.home_page.next')</button>
                      </div>
                    </div>
                  </div>           
                </div>
              </div>
              <!---* SELECT SERVICE ACCORDINGLY CATEGORY ID.STEP 1 END HERE *-->

              <!---** SELECT SUBSERVICE ACCORDINGLY SERVICE ID.STEP 2 START HERE **-->
              <div class="tab-pane <?php if($selected_type == "service"){ echo "active"; } ?>" id="step2">
                <div class="pro-heading">
                  <h3>@lang('labels.frontend.home_page.sub_services')</h3>
                </div>

                <div class="row">
                  <div class="col-md-8">
                    <div class="pro-info">
                      <div class="meta-list" id="appendSubserviceArray">
                      @php $j=0; $subServiceIdc=0; @endphp
                        @php if(isset($subServiceId)) $subServiceIdc=$subServiceId; @endphp
                          @if(isset($allSubServices) && count($allSubServices) >0 || $subServiceIdc)
                              @foreach ($allSubServices as $key => $getSubservices) 
                               <label class="cust-radio">{{$getSubservices->es_name}}
                               <input type="radio" value="{{$getSubservices->id}}" class="subservice_class" onClick="getSubserviceIdForChild({{$getSubservices->id}})" name="getsubservice_id" id="sbsr_id{{$j}}" <?php if($getSubservices->id == $subServiceIdc){ echo "checked='checked' ";} else{ echo " "; } ?> data-subservicename ="{{$getSubservices->es_name}}">
                               <span class="checkmark"></span>
                               </label>
                              @php $j++ @endphp
                          @endforeach
                        @endif
                        <!--  SubServices Array will be append here -->

                      </div>
                    </div>
                    <div style="overflow:auto;">
                      <div  class="form-btn">
                       <button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >@lang('labels.frontend.home_page.previous')</button>
                        <button type="button" class="btn pre-btn"  onclick="nextbtnn(this)">@lang('labels.frontend.home_page.next')</button>
                      </div>
                    </div>
                  </div>           
                </div>
              </div>
              <!---SELECT SUBSERVICE ACCORDINGLY SERVICE ID.STEP 2 END HERE -->

              <!---*** SELECT CHILD ACCORDINGLY SUB SERVICE ID.STEP 3 START HERE ***-->
              <div class="tab-pane <?php if($selected_type == "sub_service" && isset($allChildServices) && count($allChildServices) >0 ){ echo "active"; } ?>" id="step3">
                <div class="pro-heading">
                  <h3>@lang('labels.frontend.home_page.child_services')</h3>
                </div>

                <div class="row">
                  <div class="col-md-8">
                    <div class="pro-info">
                      <div class="meta-list" id="childServicesArrayAppend">

                         @php $k=0; $child_sub_serviceIdc=0; @endphp

                        @php if(isset($child_sub_serviceId)) $child_sub_serviceIdc=$child_sub_serviceId; @endphp

                          @if(isset($allChildServices) && count($allChildServices) >0 || $child_sub_serviceIdc)
                              @foreach ($allChildServices as $key => $getchildSubservices) 
                          
                               <label class="cust-radio">{{$getchildSubservices->es_name}}<input type="radio" value="{{$getchildSubservices->id}}" class="childservice_class" onClick="getQuestionsById({{$getchildSubservices->id}})" name="getchildservice_id" id="chsr_id{{$k}}" <?php if($getchildSubservices->id == $child_sub_serviceIdc){ echo "checked='checked' ";} else{ echo " "; } ?> data-childservicename ="{{$getchildSubservices->es_name}}">
                               <span class="checkmark"></span>
                               </label>
                               @php $k++ @endphp
                              @endforeach
                           @endif

                        <!--  ChildServices Array will be append here -->
                      </div>
                    </div>
                    <div style="overflow:auto;">
                      <div  class="form-btn">
                       <button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >@lang('labels.frontend.home_page.previous')</button>
                        <button type="button" class="btn pre-btn"  onclick="nextbtnn(this)">@lang('labels.frontend.home_page.next')</button>
                      </div>
                    </div>
                  </div>           
                </div>
              </div>
              <!--*** SELECT CHILD ACCORDINGLY SUB SERVICE ID.STEP 3 END HERE ***-->

               <!--**** QUESTIONARIE.STEP 4 Start HERE ****-->

                <!-- <div class="tab-pane <?php if($selected_type == "child_sub_service"){ echo "active"; } ?>" id="step4"> -->

              <div class="tab-pane <?php if(($selected_type == "sub_service" && isset($allChildServices) && empty($allChildServices)) || ($selected_type == "child_sub_service")) { echo "active"; } ?>" id="step4">

                <div class="pro-heading" id="QuestionArea">
                  <h3 class="modal-title" id="question_step1" >
                  <?php if(isset($questionArr->es_title)){ echo $questionArr->es_title;} else{ echo " "; } ?>
                  </h3>
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <div class="pro-info">
                      <input type="hidden" name="questions[]" id="questionId" value="<?php if(isset($questionArr->id)){ echo $questionArr->id;} else{ echo " "; } ?>"/>
                      <div class="meta-list" id="optionArea">

                       @if(isset($questionArr) && !empty($questionArr))

                          @php $s=0; @endphp
                          @foreach($questionArr->options as $optionss)
                           @if($questionArr->question_type=='checkbox')
                              <label class="cust-radio">{{$optionss->es_option}}<input type="checkbox" value="{{$optionss->id}}" class="question_option{{$s}}" name="optionsdata[{{$questionArr->id}}]" onClick="getNextQuestionsByOptionId1({{$optionss->id}})">
                              <span class="checkmark"></span>
                              </label>
                           @endif
                           @if($questionArr->question_type=='radio')
                              <label class="cust-radio">{{$optionss->es_option}}<input type="radio" value="{{$optionss->id}}" class="question_option{{$s}}" name="optionsdata[{{$questionArr->id}}]" onClick="getNextQuestionsByOptionId1({{$optionss->id}})">
                              <span class="checkmark"></span>
                              </label>
                           @endif
                           @if($questionArr->question_type=='text')
                              <label class="cust-radio">{{$optionss->es_option}}<input type="text" class="question_option{{$s}}" name="optionsdata[{{$questionArr->id}}]" onClick="getNextQuestionsByOptionId1({{$optionss->id}})">
                              <span class="checkmark"></span>
                              </label>
                           @endif
                           @if($questionArr->question_type=='file')
                              <label class="cust-radio">{{$optionss->es_option}}<input type="file" class="question_option{{$s}}" name="optionsdata[{{$questionArr->id}}]" onClick="getNextQuestionsByOptionId1({{$optionss->id}})">
                              <span class="checkmark"></span>
                              </label>
                           @endif
                           @if($questionArr->question_type=='date_time')
                              <label class="cust-radio">{{$optionss->es_option}}<input type="date" class="question_option{{$s}}" name="optionsdata[{{$questionArr->id}}]" onClick="getNextQuestionsByOptionId1({{$optionss->id}})">
                              <span class="checkmark"></span>
                              </label>
                           @endif
                          @php $s++ @endphp

                          @endforeach

                        @endif

                      
                        <!--  Options Array will be append here -->
                      </div>
                    </div>
                    <div style="overflow:auto;">
                      <div  class="form-btn">
                       <button type="button" class="btn next-btn"  onclick="prevbtnn(this)">@lang('labels.frontend.home_page.previous')</button>
                        <button type="button" class="btn pre-btn"  onclick="nextbtnn(this)">@lang('labels.frontend.home_page.next')</button>
                      </div>
                    </div>
                  </div>           
                </div>
              </div>

              <!--**** QUESTIONARIE.STEP 4 END HERE ****-->

        
              <!--Short Description-->

               <div class="right-added">
                  <div class="right-header">
                    <div class="media head-detail">
                      <img class="mr-3" src="{{asset('img/frontend/dt.png')}}">
                      <div class="media-body">
                        <p>@lang('labels.frontend.home_page.details_of_your_project')</p>
                      </div>
                    </div>

                    <div class="booking-list" >
                      <ul id="summary">

                      <?php
                      if($getCategoryData)
                      {?>

                         <li>
                          <h6>@lang('labels.frontend.home_page.category_name')</h6>
                          <p>{{$getCategoryData->es_name}}</p>
                        </li>

                     <?php } ?>

                        <?php
                      if($getCityData)
                      {?>

                         <li>
                          <h6>@lang('labels.frontend.home_page.city_name')</h6>
                          <p>{{$getCityData->name}}</p>
                        </li>

                     <?php } ?>

                      <div id="sideServiceName">
                      @if(isset($servicename) && !empty($servicename))
                        <li>
                          <h6>@lang('labels.frontend.home_page.service_name')</h6>
                          <p>{{isset($servicename) && !empty($servicename) ? $servicename : ''}}</p>
                        </li>
                        @endif
                      </div>

                      <div id="sideSubServiceName">
                      @if(isset($subservicename) && !empty($subservicename))
                         <li>
                          <h6>@lang('labels.frontend.home_page.sub_service')</h6>
                          <p>{{isset($subservicename) && !empty($subservicename) ? $subservicename : ''}}</p>
                        </li>
                        @endif
                      </div>

                       <div id="sideChildSubServiceName">
                        @if(isset($childsubservicename) && !empty($childsubservicename))
                        <li>
                          <h6>@lang('labels.frontend.home_page.child_sub_service')</h6>
                          <p>{{isset($childsubservicename) && !empty($childsubservicename) ? $childsubservicename : ''}}</p>
                        </li>
                        @endif
                      </div>

                      </ul>
                    </div>

                  </div>
                </div>


                 <!--Short Description-->

            </div>
              
          </form>
        </div>
          
      </div>
    </section>
</div>
<style type="text/css">
  body{
    background: #f5f5f5 !important;
  }
</style>
<script type="text/javascript">
     $('.form-btn').hide();
</script>
@endsection

