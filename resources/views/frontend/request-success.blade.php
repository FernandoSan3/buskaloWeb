@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')


<section class="categ-step-section">
  <div class="container">
    <div class="" id="serv-step1">
        <div class="tab-content">
          <div class="fade active show"  id="step">
            <div class="step-final-div mid-steps">
              <div class="media">
                <img class="" src="{{asset('img/frontend/shield.png')}}">
                <div class="media-body">
                    <h3 class="mt-0">¡@lang('labels.frontend.request_success.congratulations')!</h3>
                    <p><b>@lang('labels.frontend.request_success.your_request_has_been_approved')</p>

                   <p>¡@lang('labels.frontend.request_success.welcome_to_the_new_age')!</p>
                </div>
              </div>
            </div>
          </div>

        </div>
          
      </form>
    </div>
      
  </div>

  </div>
</section>


@endsection

