@extends('frontend.layouts.app')
      
@section('title', app_name() . ' | ' . __('navs.general.home'))
@section('content')
  <section class="mt-100">
    
    <div class="container">
    
      <div class="service-container bg-light">
        <div class="row">
          <div class="col-md-6 border-right">
            <div class="ser_name">
              <h4 class="heading">Request Details</h4> 

              <p><span class="orange">Category :</span> Nutricionista</p> 
              <p><span class="orange">Service :</span> Nutricionista</p> 
              <p><span class="orange">Sub Service :</span> Nutricionista</p> 
              <p><span class="orange">Child Sub Service :</span> Nutricionista</p> 
              <p><span class="orange">Address :</span> 121, xyz Street, xyz apartmnet</p>
              {{-- <div class="ser_addr">
                <p>Address: 121, xyz Street, xyz apartmnet</p>
                <p>Email: xyz@gmail.com</p>
              </div> --}}
            </div>
          </div>
          <div class="col-md-6 text-center ">
            <h5 class="heading orange">Who Accepted</h5>
            <ul>
              <li>
                <div class="row">
                  <div class="col col-sm-6">
                    <p class="u_name"> Contractor 1  </p> 
                  </div>
                  <div class="col-auto col-sm-6"><button class="btn btn-or-outline">Chat</button></div>
                </div>
              </li>
              <li>
                <div class="row">
                  <div class="col col-sm-6">
                    <p class="u_name">Contractor 2 </p>
                  </div>
                  <div class="col-auto col-sm-6"><button class="btn btn-or-outline">Chat</button></div>
                </div>
              </li>
              <li>
                <div class="row">
                  <div class="col col-sm-6">
                    <p class="u_name">Contractor 3 </p>
                  </div>
                  <div class="col-auto col-sm-6"><button class="btn btn-or-outline">Chat</button></div>
                </div>
              </li>
              
            
            </ul>
            <div class="text-center">
              <button type="button" class="btn btn-or-round">Profile Compare</button>
              
            </div>
          </div>
        </div>
      </div>
      <div class="service_bx_quiz">
        <ul>
          <li>
            <div class="ser_ques">
              <span class="ser_number">Q.</span>
              If a red house is made from red bricks, a blue house is made from blue bricks, a pink house is made from pink bricks, and a black house is made from black bricks. What is a greenhouse made from?
            </div>
            <div class="ser_ans">
              Glass
            </div>
          </li>
          <li>
            <div class="ser_ques">
              <span class="ser_number">Q.</span>
              If a red house is made from red bricks, a blue house is made from blue bricks, a pink house is made from pink bricks, and a black house is made from black bricks. What is a greenhouse made from?
            </div>
            <div class="ser_ans">
              Glass
            </div>
          </li>
          <li>
            <div class="ser_ques">
              <span class="ser_number">Q.</span>
              If a red house is made from red bricks, a blue house is made from blue bricks, a pink house is made from pink bricks, and a black house is made from black bricks. What is a greenhouse made from?
            </div>
            <div class="ser_ans">
              Glass
            </div>
          </li>
          <li>
            <div class="ser_ques">
              <span class="ser_number">Q.</span>
              If a red house is made from red bricks, a blue house is made from blue bricks, a pink house is made from pink bricks, and a black house is made from black bricks. What is a greenhouse made from?
            </div>
            <div class="ser_ans">
              Glass
            </div>
          </li>
        </ul>
      </div>
    </div>
  </section>
    
@endsection