@extends('frontend.layouts.auth_app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.characteristics_conditions'))

@section('content')

    @include('frontend.contractor.characteristics-conditions')
    <!-- @if($role_type =='traveler')
        @include('frontend.traveler.characteristics.view')
    @endif -->
   
  
@endsection
