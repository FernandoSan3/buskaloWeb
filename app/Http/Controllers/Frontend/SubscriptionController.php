<?php
  
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId= auth()->user()->id;

        $userdata=DB::table('users')->select('users.is_confirm_reg_step','users.approval_status','users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee','users.pro_credit')->where('id',$userId)->first();
           
            $package = Package::where('deleted_at',NULL)->orderBy('id','asc')->get()->toArray();

        return view('frontend.subscription', compact('userdata', 'userId', 'package'));
    }

     public function payment($id = null, $slug = null, $userId=null){

         $userId= auth()->user()->id;

         $userdata=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userId)->first();

         $package = Package::where('deleted_at',NULL)->where('id',$id)->first(); 

            $taxamount=number_format($package->price/1.12,2);
            $vat=($taxamount*12)/100;
            $vatamount= number_format($vat,2);
        return view('frontend.payment', compact('userdata', 'package','taxamount','vatamount'));

    }
    
    public function paymentStore(Request $request)
    {
        $creditPak=DB::table('package')->where('id',$request->packageid)->first();
        $insert['user_id']=auth()->user()->id;
        $insert['trans_id']=$request->response['transaction']['id'];
        $insert['amount']=$creditPak->price;
        $insert['payment_type']='online';
        $insert['credits']=$creditPak->credit;
        $insert['package_id']=$request->packageid;
        $insert['status']='success';

        $newCredit= auth()->user()->pro_credit+$creditPak->credit;
        DB::table('users')->where('id',auth()->user()->id)->update(['pro_credit'=>$newCredit]);
        DB::table('payment_history')->insert($insert);
        
         $userdata= DB::table('users')->where('id',auth()->user()->id)->first();
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
            'message'=>'Tu compra de recarga de monedas ha sido exitosa.',
            'total'=>$creditPak->price,
            'credit'=>$creditPak->credit,
            'packagename'=>$creditPak->es_name,
            'transactionID'=>$request->response['transaction']['id'],
            'authorization'=>$request->response['transaction']['authorization_code'],
            'date'=>date('d-m-Y H:i:s'),
            'logo'=>url('img/logo/logo-svg.png'),
            'footer_logo'=>url('img/logo/footer-logo.png'),
            'user_icon'=>$image,
            );
        $email=$userdata->email;
        Mail::send('frontend.mail.creditadd',  ['data' => $data], function($message) use ($email){
             $message->to($email)->subject(__('Has adquirido un paquete de recarga', ['app_name' => app_name()]));
        });
         echo 'success';
    }


     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  
}  