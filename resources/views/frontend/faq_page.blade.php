@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
<section class="works-sec mx mt-4">
  <div class="container">
    <div class="row">
      <div class="col col-sm-9 m-auto align-self-center">
        <div class="faq_ques_section">
          <div class="card">
            <div class="card-header text-center faq_main_head">
              <span>Preguntas frecuentes</span>
            </div>
            <div class="card-body">
              <div class="flex flex-column mb-5 mt-4 faq-section">
                <div class="row">
                  <div class="col-md-12">
                    <div id="accordion">
                        @foreach($faqLists as  $faqdata)
                        <div class="card">
                          <div class="card-header" id="heading-{{$faqdata->id}}">
                            <h5 class="mb-0">
                              <a role="button" data-toggle="collapse" href="#collapse-{{$faqdata->id}}" aria-expanded="true" aria-controls="collapse-{{$faqdata->id}}">
                                 {{$faqdata->question}}
                              </a>
                            </h5>
                          </div>
                          <div id="collapse-{{$faqdata->id}}" class="collapse" data-parent="#accordion" aria-labelledby="heading-{{$faqdata->id}}">
                            <div class="card-body">
                              {!!$faqdata->answer!!}
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


@endsection

