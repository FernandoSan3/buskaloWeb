<html>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="https://cdn.paymentez.com/checkout/1.0.1/paymentez-checkout.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="header-profile">
    <div id="wrapper" class="toggled left-sidebar">
      <div class="site-loader paymentsuccess" style="display:none">
            <img id="loading-image" src="{{url('img/logo/loading.gif')}}" alt="Loading..." />
        </div>
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="right-sidebar ">
                    <style>
                        .panel {
                          margin: 0 auto;
                          background-color: #F5F5F7;
                          border: 1px solid #ddd;
                          padding: 20px;
                          display: block;
                          width: 80%;
                          border-radius: 6px;
                          box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
                        }

                        .btn {
                          background: rgb(140, 197, 65); /* Old browsers */
                          background: -moz-linear-gradient(top, rgba(140, 197, 65, 1) 0%, rgba(20, 167, 81, 1) 100%); /* FF3.6-15 */
                          background: -webkit-linear-gradient(top, rgba(140, 197, 65, 1) 0%, rgba(20, 167, 81, 1) 100%); /* Chrome10-25,Safari5.1-6 */
                          background: linear-gradient(to bottom, rgba(140, 197, 65, 1) 0%, rgba(20, 167, 81, 1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
                          filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#44afe7', endColorstr='#3198df', GradientType=0);
                          color: #fff;
                          display: block;
                          width: 100%;
                          border: 1px solid rgba(46, 86, 153, 0.0980392);
                          border-bottom-color: rgba(46, 86, 153, 0.4);
                          border-top: 0;
                          border-radius: 4px;
                          font-size: 17px;
                          text-shadow: rgba(46, 86, 153, 0.298039) 0px -1px 0px;
                          line-height: 34px;
                          -webkit-font-smoothing: antialiased;
                          font-weight: bold;
                          display: block;
                          margin-top: 20px;
                        }

                        .btn:hover {
                          cursor: pointer;
                        }
                    </style>
                        <p class="js-paymentez-checkout"></p>
                        <div id="response1"></div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<script>

  var paymentezCheckout = new PaymentezCheckout.modal({
      client_app_code: 'POLUXSOFTWARE-EC-CLIENT', // Client Credentials Provied by Paymentez
      client_app_key: 'FUrqCFttTwEEXeKzBF1KmehGN0SX9m', // Client Credentials Provied by Paymentez
      locale: 'es', // User's preferred language (es, en, pt). English will be used by default.
      env_mode: 'prod', // `prod`, `stg` to change environment. Default is `stg`
      onOpen: function() {
          console.log('modal open');
      },    
      onClose: function() { 
          console.log('modal closed');
      },
      onResponse: function(response) {

        if(response.transaction.status=='success')
        {
             var proid=<?php echo $prouserId;?>;
             var userid=<?php echo $userid;?>;
             var serviceid=<?php echo $serviceId;?>;
            $.ajax({
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:"{{url('service/payment/web')}}",
                type:"post",
                data: {'response':response,'proid':proid,'userid':userid,'serviceId':serviceid},
                beforeSend: function(xhr){
                 $('.paymentsuccess').show();
              },
                 success:function(res)
                 {  
                    $('.paymentsuccess').hide();
                    alert('Gracias por su compra, su transacción fue procesada exitosamente');
                     // if(res=='success')
                     // {
                        window.location.href="{{url('dashboard')}}";
                     // }
                 }
            });
        }
        else
        {
          alert('Lo sentimos, su pago no ha sido procesado. Por favor intente nuevamente');
            window.location.href="{{url('dashboard')}}";
        }
         // The callback to invoke when the Checkout process is completed

          /*
            In Case of an error, this will be the response.
            response = {
              "error": {
                "type": "Server Error",
                "help": "Try Again Later",
                "description": "Sorry, there was a problem loading Checkout."
              }
            }

            When the User completes all the Flow in the Checkout, this will be the response.
            response = {
               "transaction": {
                  "status": "success", // success, failure or pending
                  "payment_date": "2017-09-26T21:03:04",
                  "amount": 99.0,
                  "authorization_code": "148177",
                  "installments": 1,
                  "dev_reference": "referencia",
                  "message": "Operation Successful",
                  "carrier_code": "6",
                  "id": "CI-490", // transaction_id
                  "status_detail": 3 // for the status detail please refer to: https://paymentez.github.io/api-doc/#status-details
               },
               "card": {
                  "bin": "453254",
                  "status": "valid",
                  "token": "",
                  "expiry_year": "2020",
                  "expiry_month": "9",
                  "transaction_reference": "CI-490",
                  "type": "vi",
                  "number": "8311"
              }
           }

          */
          console.log('modal response');
          document.getElementById('response').innerHTML = JSON.stringify(response);      
          console.log(JSON.stringify(response));      
      }
  });

  var btnOpenCheckout = document.querySelector('.js-paymentez-checkout');
  btnOpenCheckout.addEventListener('click', function(){
    // Open Checkout with further options:
    paymentezCheckout.open({
      user_id: '007',
      user_email: 'admin@buskalo.com', //optional        
      user_phone: '0996313765', //optional
      order_description: 'PRUEBAS',
       order_amount: <?php echo $payamount;?>,
      //order_vat: 42.75,
      order_reference: '12345',
      //order_installments_type: 3, // optional: For Colombia an Brazil to show installments should be 0, For Ecuador the valid values are: https://paymentez.github.io/api-doc/#payment-methods-cards-debit-with-token-installments-type
      //order_taxable_amount: 356.25, // optional: Only available for Ecuador. The taxable amount, if it is zero, it is calculated on the total. Format: Decimal with two fraction digits.
      //order_tax_percentage: 0 // optional: Only available for Ecuador. The tax percentage to be applied to this order.
    });
  });

  // Close Checkout on page navigation:
  window.addEventListener('popstate', function() {
    paymentezCheckout.close();
  });
$(document).ready(function()
{
  $('.js-paymentez-checkout').click();
});
</script>
</html>