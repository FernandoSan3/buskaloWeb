@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))
@section('content')
  <section class="mt-100">

    <div class="container">


      <div class="service-container bg-light">
        <div class="row">
          <div class="col-md-12 border-right">
            <div class="ser_name">

              <h4 class="heading">Job Details</h4> <span style="margin-left: 93%;"><a href="{{route('frontend.company.company_profile.jobs')}}">Back </a></span>

              <p><span class="orange">Category : </span> {{ $data['category_name'] }}</p>
              <p><span class="orange">Service : </span> {{ $data['service_name'] }}</p>
              <p><span class="orange">Sub Service : </span> {{ $data['subservice_name'] }}</p>
              <p><span class="orange">Child Sub Service : </span> {{ $data['child_sub_service_name'] }}</p>
               <p><span class="orange">request Date : </span> {{ date('g:ia \o\n l jS F Y', strtotime($data['created_at'])) }} </p>


               <div class="ser_addr">
                <p>Email: {{ $data['email'] }}</p>
                <p>Username: {{ $data['username'] }}</p>
                <p>City: {{ $data['city_name'] }}</p>
                <p>Full Address: {{ $data['location'] }}</p>

                 <h6><span class="orange">Job Status :</span> {{ $data['job_status'] }}</h6>

              </div>

            </div>
          </div>

        </div>
      </div>
      <div class="service_bx_quiz">
        <ul>
          <?php if(isset($data['question_options']) && count($data['question_options']) > 0) {
            foreach ($data['question_options'] as $key => $value) {

          ?>

          <li>
            <div class="ser_ques">
              <span class="ser_number">Q.</span>
              {{ $value['question'] }}
            </div>
            <div class="ser_ans">
              <?php if($value['question_type'] == 'radio' || $value['question_type'] == 'checkbox' || $value['question_type'] == 'select') { ?>

               {{ $value['option'] }}

              <?php } elseif($value['question_type'] == 'file') { ?>

                <img src="{{ $value['option_id'] }}">

              <?php }else { ?>
                   {{ $value['option_id'] }}
              <?php } ?>

            </div>
          </li>
          <?php }  } ?>

        </ul>
      </div>

    </div>
  </section>

@endsection