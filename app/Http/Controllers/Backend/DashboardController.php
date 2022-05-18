<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use DateTime;
/**
 * Class DashboardController.
 */
date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('backend.dashboard');
    }

    public function paymentHistory()
    {
        $paymentHistoty=DB::table('payment_history')
                            ->leftjoin('users','users.id','=','payment_history.user_id')
                            ->leftjoin('package','package.id','=','payment_history.package_id')
                            ->select('payment_history.*','users.username','users.first_name','package.en_name')
                            ->orderBy('id','DESC')->get();
        return view('backend.payment.payment_history', compact('paymentHistoty'));
    }

    public function customerPayment()
    {
        $customerpay=DB::table('user_payment_history')
            ->leftjoin('users','users.id','=','user_payment_history.user_id')
            ->select('user_payment_history.*','users.username','users.first_name')
            ->orderBy('user_payment_history.id','desc')->get();
         return view('backend.customer.customer_payment_history', compact('customerpay'));
    }

    public function  customerPaymentUpdate($id=null)
    {
        $customerpay=DB::table('user_payment_history')->where('id',$id)->update(['status'=>'success','trans_id'=>rand(1111,9999)]);
         return redirect()->route('admin.customer.payment')->with('success',' Paymet status update successfully.');
    }

    public function faqRequest()
    {
        $faqlist=DB::table('faq')->get();
        return view('backend.faq.index', compact('faqlist'));
    }

    public function faqCreate()
    {
        return view('backend.faq.create');
    }

    public function faqStore(Request $request)
    {
        $status= isset($request->status)?$request->status:'0';
        $faqdata=array('question_type'=>$request->question_type,'question'=>$request->question,'answer'=>$request->answer,'status'=>$status);
        DB::table('faq')->insert($faqdata);
         return redirect()->route('admin.faqs')->with('success',' Faq Add  successfully.');
    }
    public function faqEdit($id=null)
    {
       $faqedit= DB::table('faq')->where('id',$id)->first();
      return view('backend.faq.edit',compact('faqedit'));
    }

    public function faqUpdate(Request $request)
    {
        $status=0;
        if(isset($request->status) && !empty($request->status))
        {
            $status=1;
        }
            $faqupdate=array('question_type'=>$request->question_type,'question'=>$request->question,'answer'=>$request->answer,'status'=>$status);
       $faqedit= DB::table('faq')->where('id',$request->faq_id)->update($faqupdate);
       return redirect()->route('admin.faqs')->with('success',' Faq Update  successfully.');
    }

     public function faqDelete(Request $request,$id=null)
    {
       $faqedit= DB::table('faq')->where('id',$id)->delete();
       return redirect()->route('admin.faqs')->with('success',' Faq delete  successfully.');
    }

    public function refundRequest()
    {

        $refunddata=DB::table('refund_requests')->orderBy('id','desc')->get();
        return view('backend.refund.index',compact('refunddata'));
    }
    public function refundRequestAccept($id=null)
    {

        $refunddata=DB::table('refund_requests')->where('id',$id)->first();
        if($refunddata->refund_status=='Processed')
        {
             return redirect()->route('admin.refund')->withFlashDanger('Refund request has already been processed');
        }

        $server_application_code = env('API_LOGIN_DEV');
        $server_app_key = env('API_KEY_DEV') ;
        $date = new DateTime();
        $unix_timestamp = $date->getTimestamp();
        // $unix_timestamp = "1546543146";
        $uniq_token_string = $server_app_key.$unix_timestamp;
        $uniq_token_hash = hash('sha256', $uniq_token_string);
        $auth_token = base64_encode($server_application_code.";".$unix_timestamp.";".$uniq_token_hash);
       $fields = array(
                'transaction' =>array('id' =>$refunddata->transaction_id),
                //'order'=>array('amount'=>(float)$refunddata->pro_amount),
                'more_info'=> true
            );

             //echo "<pre>"; print_r(json_encode( $fields));exit;
         $API_ACCESS_KEY = $auth_token;
          $headers = array
          (
            'Auth-Token:'.$API_ACCESS_KEY,
            'Content-Type: application/json'
          );
           // print_r($headers);exit;

          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://ccapi-stg.paymentez.com/v2/transaction/refund/');
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields));
          // Execute post
          $result = curl_exec($ch);
       // echo '<pre>'; print_r($result);die;
          sleep(5);
          if ($result === FALSE) {
              die('Curl failed: ' . curl_error($ch));
          }
          // Close connection
          curl_close($ch);
          //return $result;  
          $result=json_decode($result);
          //echo '<pre>'; print_r($result);die;
        if(isset($result->error) && !empty($result->error))
        {
            return redirect()->route('admin.refund')->withFlashDanger('Amount is not valid');
        }
        if($result->status=='success')
        {

            $usercredit=DB::table('payment_history')
                ->leftjoin('users','users.id','=','payment_history.user_id')
                ->select('users.pro_credit','payment_history.credits','payment_history.user_id')
                ->where('trans_id',$refunddata->transaction_id)->first();
                $newcredit= $usercredit->pro_credit-$usercredit->credits;
                DB::table('users')->where('id', $usercredit->user_id)->update(['pro_credit'=> $newcredit]);
            DB::table('refund_requests')->where('id',$id)->update(['refund_status'=>'processed','refund_date'=>date('Y-m-d H:i:s')]);
            $userdata= DB::table('users')->where('id', '=', $refunddata->user_id)->first();
            $image='';
            if($userdata->user_group_id==3)
            {
                $image= url('img/contractor/profile/'.$userdata->avatar_location);
            }
            if($userdata->user_group_id==4)
            {
                $image= url('img/company/profile/'.$userdata->avatar_location);
            }

            $trandId=isset($refunddata->transaction_id)?$refunddata->transaction_id:0;
             $data = array(
            'username'=>$userdata->username,
            'receiver'=>$userdata->email,
            'message'=>'Estimado '.$userdata->username.', tu solicitud de reembolso para el numero de transacción ' . $trandId. ' ha sido aprobada.',
            'submsg'=>'',
            'logo'=>url('img/logo/logo-svg.png'),
            'footer_logo'=>url('img/logo/footer-logo.png'),
            'user_icon'=>$image,
            );
            $email=$userdata->email;
            Mail::send('frontend.mail.refund_accept_reject',  ['data' => $data], function($message) use ($email){
                 $message->to($email)->subject(__('Solicitud de reembolso aprovada', ['app_name' => app_name()]));
            });
            return redirect()->route('admin.refund')->with('success','Pago realizado con éxito');
        }else
        {
            return redirect()->route('admin.refund')->withFlashDanger('Refund Request failed again try.');
        }
        
    }
    public function refundRequestReject($id=null)
    {

        $refunddata=DB::table('refund_requests')->where('id',$id)->first();
            DB::table('refund_requests')->where('id', '=', $id)->update(['refund_status'=>'rejected']);
            $userdata= DB::table('users')->where('id', '=', $refunddata->user_id)->first();
            $image='';
            if($userdata->user_group_id==3)
            {
                $image= url('img/contractor/profile/'.$userdata->avatar_location);
            }
            if($userdata->user_group_id==4)
            {
                $image= url('img/company/profile/'.$userdata->avatar_location);
            }

             $data = array(
            'username'=>$userdata->username,
            'receiver'=>$userdata->email,
            'message'=>'Estimado ' . $userdata->username.', luego de evaluar su solicitud de reembolso le informamos que esta ha sido rechazada. Revise si el número de transacción es correcto y vuelva a intentarlo, si la razón es otra recibirá un correo con los detalles del porque no fue aprobada.',//'Luego de evaluar su solicitud de reembolso le informamos que ha sido rechazada. Revise si el número de transacción es correcto y vuelve a intentar.',
            'submsg'=>'',
            'logo'=>url('img/logo/logo-svg.png'),
            'footer_logo'=>url('img/logo/footer-logo.png'),
            'user_icon'=>$image,
            );
        $email=$userdata->email;
        Mail::send('frontend.mail.refund_accept_reject',  ['data' => $data], function($message) use ($email){
             $message->to($email)->subject(__('Solicitud de reembolso rechazada', ['app_name' => app_name()]));
        });
         return redirect()->route('admin.refund')->with('success','Request was rejected');
    }
    

}   
