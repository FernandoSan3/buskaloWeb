@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')

<section class="works-sec serv-online mx mt-4">
  <div class="container">
      <div class="heading">
        <h2>@lang('labels.frontend.how_does_it_work.how_does_it_work')</h2>
        <span class="bottom-border"></span>
      </div>
    <div class="row">
      @foreach($how_does_it_work as $howitiswork)
      <!-- <div class="work-inner"> -->
        <div class="col-lg-12">
          {!!$howitiswork->search_descriptiom!!}
        </div>
    </div>
  </div>
  @endforeach
</section>

<!-- <section class="intro-sec mx">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="into-main">
          <h2>@lang('labels.frontend.how_does_it_work.tasks')</h2>
          <p>{!!$howitiswork->description!!}</p>
        <div class="about-btn"><a href="#">@lang('labels.frontend.how_does_it_work.buskalo')</a></div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="intro-img">
          <img src="{{url('/img/frontend/work/'.$howitiswork->image)}}">
        </div>
      </div>
    </div>
  </div>
</section> -->

<!-- <section class="profession-sec">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="prof-left">
          <img src="{{ url('img/frontend/Pro.png') }}">
        </div>
      </div>
      <div class="col-md-6">
        <div class="prof-right">
          <h2>¿@lang('labels.frontend.how_does_it_work.professional')</p>
        <a href="{{url('/')}}"><button class="btn prof-btn">@lang('labels.frontend.how_does_it_work.join_now')</button></a>
        </div>
      </div>
    </div>
  </div>
</section> -->

@endsection


