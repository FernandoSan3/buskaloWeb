@extends('frontend.layouts.app')

@section('content')
  <section class="works-sec serv-online mx term&conditions">
    <div class="container">
        <div class="heading">
            <span class="bottom-border"></span>
        </div>
        <div class="row">
          <div class="col-sm-12 col-md-8 col-lg-8 mx-auto">
            <div class="card card-signin my-5">
              <div class="card-img-top">
                  <h5 class="card-title text-center">USERS REFUND</h5>
              </div>
               
              <div class="card-body">
                <form class="form-signin user-form" method="post" action="{{url('refund/request')}}">
                  @csrf()
                  <div class="form-group">
                    <label class="col-md-12">Nombre:</label>
                    <div class="col-md-12">
                       <input type="text" name="name" class="form-control" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">Nombre de la empresa o profesional:</label>
                    <div class="col-md-12">
                       <input type="text" name="pro_company" class="form-control" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">Describa el motivo por el que solicita la devolución:</label>
                    <div class="col-md-12">
                       <input type="text" name="refund_resion" class="form-control" required="">
                    </div>
                  </div>
                  <!-- <div class="form-group">
                    <label class="col-md-12">Cantidad solicitada:</label>
                    <div class="col-md-12">
                      <input type="text" name="amount" class="form-control" required="">
                    </div>
                  </div> -->
                  <div class="form-group">
                    <label class="col-md-12">Id de Transaccion:</label>
                    <div class="col-md-12">
                       <input type="text" name="transaction_id" class="form-control" required="">
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="col-md-12">Fecha del pago:</label>
                    <div class="col-md-12">
                       <input type="date" name="payment_date" class="form-control" required="">
                    </div>
                  </div>
                  <div class="form-group">
                      <label class="col-md-12">Cantidad solicitada:</label>
                      <div class="custom-control custom-checkbox rem-div">
                        <input type="checkbox" class="custom-control-input22" name="amount_total"> Pago Total
                        <input type="checkbox" class="custom-control-input2" name="amount_parcial"> Pago Parcial
                      </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-12">Correo electrónico:</label>
                    <div class="col-md-12">
                       <input type="email" name="email" class="form-control" required="">
                    </div>
                  </div>

                  <button class="btn login-btn" type="submit">ENVIAR</button>
                </form>
              </div>
            </div>
          </div>
        </div>
     </div>
</section>

@endsection