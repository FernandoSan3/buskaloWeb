<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\App;
use App\Models\Services;
use App\Models\Bonus;
use App\Models\UserDevices;
use App\Models\Settings;
use App\Models\Workers;
use App\Models\WorkersDocument;
use App\Models\MobileSession;
use App\Models\SocialNetworks;
use App\Models\DocumentTypes;
use DB, Redirect, Response, Session;
use Hash, File;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use App\Mail\WelcomeNewUser;
use App\Mail\ServiceRequestOtp;
use App\Mail\forgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

/**
 * Class ApiController.
 */
class ApiController extends Controller
{

        
        /**
         * SIGNIN API START HERE
         *
         * @return \Illuminate\Http\Response
         */
        
        public function signIn(Request $request)
        {
            $access_token=123456;


            $email = isset($request->email) && !empty($request->email) ? $request->email : '' ;
            $password = isset($request->password) && !empty($request->password) ? $request->password : '';
            $lang = isset($request->lang) && !empty($request->lang) ? $request->lang : 'en';
            $device_id = isset($request->device_id) && !empty($request->device_id) ? $request->device_id : '';
            $device_type = isset($request->device_type) && !empty($request->device_type) ? $request->device_type : '';
            $resultArray = [];
            App::setLocale($lang);

            if(!empty($email) && !empty($password) && !empty($device_id) && !empty($device_type))
            {

              $users_count_var="";
              if(Auth::attempt(['email' => $email, 'password' => $password, 'user_group_id' =>[2,3,4]]))
                { 
                    //$users_count_var = Auth::user();
                     $users_count_var = DB::table('users')->select('id','username','email','active','user_group_id','created_at','updated_at')->whereRaw("(email = '".$email."')")->first(); 
                }
                else
                {
                  $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid login credential.');
                    echo json_encode($resultArray); exit;
                } 

                if($users_count_var->active==0)
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Your account is not approved please contact to ADMIN');
                    echo json_encode($resultArray); exit;
                }
                
            if(isset($users_count_var) && $users_count_var->active==1 && !empty($users_count_var))
            {
                $settingEntity = DB::table('settings')->select('app_language')->whereRaw("(user_id = '".$users_count_var->id."')")->first();  

                  if($settingEntity)
                  {
                    $users_count_var->lang =$settingEntity->app_language;
                  }else
                  {
                    $users_count_var->lang = 0;
                  }
                $check_auth = $this->checkToken($access_token,$users_count_var->id);
                if($check_auth['status']!=1)
                {
                  echo json_encode($check_auth); exit;
                }
                else
                {
                    /*-------------------*/
                    if(isset($users_count_var->id) && !empty($users_count_var->id))
                    {
                        $userdevice= array('user_id' => $users_count_var->id ,'device_id'=>$device_id,'device_type'=>$device_type);
                        DB::table('user_devices')->insert($userdevice);

                        $users_count_var->device_id =$device_id;

                    }

                    /*-------------------*/ 
                    $resultArray['status']='1'; 
                    $resultArray['userData'] = $users_count_var;     
                    $resultArray['message']=trans('apimessage.Successfully login.');
                    $resultArray['session_key']=$check_auth['Data']['randnumber'];
                    echo json_encode($resultArray); exit;
                }
            }
            else
            {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid login credential.');
            echo json_encode($resultArray); exit;
            }
            }
            else
            {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters send.');
            echo json_encode($resultArray); exit;
            }
        }
         /*
          * SIGN IN API END HERE
          */
/* ------------------------------------------------------------------------------------------------ */
    /**
     * SIGNUP API START HERE
     *
     * @return \Illuminate\Http\Response
     */
        public function validateMobile($mobile)
        {
            $this->autoRender = false;
            return preg_match('/^[0-9]{7,12}+$/', $mobile);
        }

                // Function to generate OTP 
        public function sendOTP($n) 
        { 
            // Take a generator string which consist of 
            // all numeric digits 
            $generator = "1357902468"; 
            $result = ""; 
            for ($i = 1; $i <= $n; $i++) 
            { 
                $result .= substr($generator, (rand()%(strlen($generator))), 1); 
            } 
            // Return result 
            return $result; 
       } 
  

        public function signUp(Request $request) 
        {

            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'user_type' => 'required',
                'device_id' => 'required',
                'device_type' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;;       
            }
       
        $access_token=123456;

        $type = isset($request->user_type) && !empty($request->user_type) ? $request->user_type : '' ;

        $username = isset($request->username) && !empty($request->username) ? $request->username : '' ;

        $email = isset($request->email) && !empty($request->email) ? $request->email : '' ;

        $password = isset($request->password) && !empty($request->password) ? $request->password : '';

        $lang = isset($request->lang) && !empty($request->lang) ? $request->lang : 'en';

        App::setLocale($lang);

        $device_id = isset($request->device_id) && !empty($request->device_id) ? $request->device_id : '';
        $device_type = isset($request->device_type) && !empty($request->device_type) ? $request->device_type : '';

        if(!empty($username) && !empty($email) && !empty($password) &&  !empty($device_id) && !empty($device_type) && !empty($type))
         {
      
        $emailexist = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null )")->first();
       
        if($emailexist)
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Email Already Exist.');
            echo json_encode($resultArray); exit;
        }


        $registerArr['uuid'] = Uuid::uuid4()->toString();
        $registerArr['username'] = $username;
        $registerArr['email'] = $email;
        $registerArr['active'] = 1;
        $registerArr['confirmed'] = 1;
        $registerArr['is_verified'] = 1;
        $registerArr['password'] = Hash::make($password);
        $registerArr['user_group_id'] = $type;
        $registerArr['confirmation_code'] = md5(uniqid(mt_rand(), 1));
        $registerArr['created_at'] = Carbon::now()->toDateTimeString();
        $registerArr['updated_at'] = Carbon::now()->toDateTimeString();
        $registerArr['remember_token'] = Hash::make('secret');
 
        if($userId = DB::table('users')->insertGetId($registerArr)) 
        {
            $userdevice['user_id'] = $userId;
            $userdevice['device_id'] = $device_id;
            $userdevice['device_type'] = $device_type;
            DB::table('user_devices')->insert($userdevice);

            $socialNetw['user_id'] = $userId;
            $socialNetw['created_at'] = Carbon::now()->toDateTimeString();
            DB::table('social_networks')->insert($socialNetw);

            $check_auth = $this->checkToken($access_token,$userId);
            if($check_auth['status']!=1)
            {
             echo json_encode($check_auth); exit;
            }
            else
            {
                  $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at')->whereRaw("(id = '".$userId."')")->first();
              
                  $settingEntity = DB::table('settings')->whereRaw("(user_id = '".$userId."')")->first();
                  if($settingEntity)
                  {
                    $users_count_var->lang = $settingEntity->app_language;
                  }else
                  {
                    $users_count_var->lang = '0';
                  }

                $resultArray['status']='1';   
                $resultArray['userData'] = $users_count_var;     
                $resultArray['message']=trans('apimessage.Your account has been created successfully.');
                $resultArray['session_key']=$check_auth['Data']['randnumber'];
                echo json_encode($resultArray); exit;
                 
            }
                
        } else {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Sorry some problem occurs please try again.');
            echo json_encode($resultArray); exit;
        }

        }
        else
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;
        }
      }
        /*
        * SIGNUP API END HERE
        */
 /* ------------------------------------------------------------------------------------------------ */

         /*
          * VerifyOtpCode API START HERE
          */

            public function verifyOtpCode(Request $request)
            {
                $access_token=123456;
                $lang = !empty($request->lang) ? $request->lang : 'en';
                $userid = !empty($request->userid) ? $request->userid : '';
                $otp_code = !empty($request->otp_code) ? $request->otp_code : '';
                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'userid' => 'required',
                'otp_code' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                }

                $getuser = DB::table('users')->where('id',$userid)->first();
                if($getuser)
                {
                    if($getuser->is_verified == 1)
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Account alreday verified!.');
                        echo json_encode($resultArray); exit;
                    }

                    $updateOtp['active'] = 1;
                    $updateOtp['is_verified'] =1;
                    $updateOtp['confirmed'] = 1;
                    $updateOtp['updated_at'] = Carbon::now()->toDateTimeString();
                    if(DB::table('users')->where('id',$getuser->id)->update($updateOtp))
                    {

                        $addBonus['user_id'] = $getuser->id;
                        $addBonus['transaction_date'] = Carbon::now()->toDateTimeString();
                        $addBonus['debit'] = '20';
                        $addBonus['credit'] = '0';
                        $addBonus['current_balance'] = '20';
                        $addBonus['updated_at'] = Carbon::now()->toDateTimeString();
                        $addBonusToContractor = DB::table('bonus')->insert($addBonus);
            
                        $resultArray['status']='1';
                        $resultArray['message']=trans('apimessage.Your profile is activated successfully, and you have recived bonus of 20 credits.!');
                        echo json_encode($resultArray); exit;   
                    }
                    
                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;  
                }

            }

         /*
          * VerifyOtpCode API START HERE
          */
/* ------------------------------------------------------------------------------------------------ */
        
         /*
          * ************ USER REQUEST ALL API START FROM HERE *********************
          *
          * SERVICE LIST API START HERE
          */
        public function getAllServiceList(Request $request)
        {
            $access_token=123456;
            $serviceArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            App::setLocale($lang);
             //$services = Services::all();
             $services = DB::table('services')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(deleted_at IS null)")
                            ->get(); 
            foreach($services as $service) 
            {
                if($lang=='es'){$name=$service->es_name;}else{$name=$service->en_name;}
               $image= url('/img/'.$service->image);
               array_push($serviceArray,array('id'=>$service->id, 'name'=>$name,'price'=>$service->price,'image'=>$image,'status'=>$service->status,'created_at'=>$service->created_at,'updated_at'=>$service->updated_at));

            }
            if($services)
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('apimessage.service list found successfully.!');
                $resultArray['data']=$serviceArray;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.You are not authorize to access this module.');
                echo json_encode($resultArray); exit;
            }
        }
         /*
          * SERVICE LIST API END HERE
          */

    /* ------------------------------------------------------------------------------------------- */
        
         /*
          * ************ GET QUESTIONS LIST BY CATEGORY ID *********************
          *
          * QUESTIONS LIST API START HERE
          */

         public function getQuestionnaireByCategoryId(Request $request)
         {
                $access_token=123456;
                $allData=array();
                $arr=array();
                $arr2=array();
                $service_id = !empty($request->service_id) ? $request->service_id : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                    App::setLocale($lang);

                    $validator = Validator::make($request->all(), [
                    'service_id' => 'required',
                    ]);

                    if($validator->fails())
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    }  

                if(!empty($service_id))
                {

                             $serviceEntity = DB::table('services')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(id = '".$service_id."' AND deleted_at IS null )")
                            ->first();
                                $service_name="";
                                if($lang=='en')
                                 {
                                   $service_name=$serviceEntity->en_name;  
                                 }else
                                 {
                                  $service_name=$serviceEntity->es_name;
                                 }


                             $questionEntity = DB::table('questions')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(services_id = '".$service_id."' AND deleted_at IS null )")
                            ->get();

                            
                            foreach ($questionEntity as $question) 
                            {
                                $arr['question_id'] =  isset($question) && !empty($question->id) ? (string)$question->id : '' ;
                                 if($lang=='en')
                                 {
                                   $arr['question']=isset($question) && !empty($question->en_title) ? (string)$question->en_title : '' ; 
                                 }else
                                 {
                                 $arr['question']=isset($question) && !empty($question->es_title) ? (string)$question->es_title : '' ; 
                                 }
                                 $arr['status'] =  isset($question) && !empty($question->status) ? $question->status : '' ;
                                $arr['created_at'] =  isset($question) && !empty($question->created_at) ? $question->created_at : '' ;


                             $questionOptionEntity = DB::table('question_options')
                            ->select('id','en_option','es_option','created_at','status')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                            ->get();
                            $options=array();
                            foreach ($questionOptionEntity as $option) 
                            {

                                $arr2['option_id'] =  isset($option) && !empty($option->id) ? $option->id : '' ;
                                 if($lang=='en')
                                     {
                                        $arr2['option'] =  isset($option) && !empty($option->en_option) ? $option->en_option : '' ;  
                                     }else
                                     {
                                         $arr2['option'] =  isset($option) && !empty($option->es_option) ? $option->es_option : '' ;  
                                     }

                                $arr2['status'] =  isset($option) && !empty($option->status) ? $option->status : '' ;

                                $arr2['created_at'] =  isset($option) && !empty($option->created_at) ? $option->created_at : '' ;

                             array_push($options, $arr2);

                            }
                              
                               $arr['options']=$options ;
                                array_push($allData, $arr);


                            }

                                $resultArray['status']='1';
                                $resultArray['message']=trans('Data found Successfully.!');
                                $resultArray['data']=$allData;
                                echo json_encode($resultArray); exit; 

                }
                else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    }    

         }



          /*
          * ************ GET QUESTIONS LIST BY CATEGORY ID *********************
          *
          * QUESTIONS LIST API END HERE
          */

    /* ------------------------------------------------------------------------------------------- */


         /*
          * ************ SEND REQUEST BY USER *********************
          *
          * SEND REQUEST BY USER API START HERE
          */


         public function sendApplicationRequest(Request $request)
         {


                $access_token=123456;
                $service_id = !empty($request->service_id) ? $request->service_id : '' ;
                $ques_options = !empty($request->ques_options) ? $request->ques_options : '' ;

                //$ques_options=[{"question_id":"1","option_id":"2"},{"question_id":"1","option_id":"4"},{"question_id":"2","option_id":"1"}];

                $service_location = !empty($request->service_location) ? $request->service_location : '' ;
                $username = !empty($request->username) ? $request->username : '' ;
                $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                $email = !empty($request->email) ? $request->email : '' ;
                //$otp = !empty($request->otp) ? $request->otp : '1111' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;



                $variable_fields_data = json_decode($ques_options);
                $queOptionData = json_decode( json_encode($variable_fields_data), true);
           
                    App::setLocale($lang);

                    $validator = Validator::make($request->all(), [
                    'service_id' => 'required',
                    'ques_options' => 'required',
                    'service_location' => 'required',
                    'username' => 'required',
                    'mobile_number' => 'required',
                    'email' => 'required',
                    //'otp' => 'required',
                    ]);

                    if($validator->fails())
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    } 


                            
                             $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            //->whereRaw("(mobile_number = '".$mobile_number."' AND deleted_at IS null )")
                            ->whereRaw("(email = '".$email."')")
                            ->first(); 
                            $userid="";


                            if(!empty($userEntity))
                            {  
                               $userid= $userEntity->id;
                            }
                            else
                            {

                                $password='buskalo@11';
                                $registerArr['uuid'] = Uuid::uuid4()->toString();
                                $registerArr['username'] = $username;
                                $registerArr['mobile_number'] = $mobile_number;
                                $registerArr['email'] = $email;
                                // $registerArr['verify_code'] = $otp;
                                $registerArr['is_verified'] = 1;
                                $registerArr['address'] = $service_location;
                                $registerArr['active'] = 1;
                                $registerArr['confirmed'] = 1;
                                $registerArr['password'] = Hash::make($password);
                                $registerArr['user_group_id'] = 2;
                                $registerArr['confirmation_code'] = md5(uniqid(mt_rand(), 1));
                                $registerArr['created_at'] = Carbon::now()->toDateTimeString();
                                $registerArr['updated_at'] = Carbon::now()->toDateTimeString();
                                $registerArr['remember_token'] = Hash::make('secret');
                                $userId = DB::table('users')->insertGetId($registerArr);
                                if(!empty($userId)) 
                                {
                                    //Start Send Mail to new User

                                    $objDemo = new \stdClass();
                                    $objDemo->password = $password;
                                    $objDemo->message = 'Thank You for your service request in buskalo, we create your account with default password please login with this password.';
                                    $objDemo->sender = 'Buskalo';
                                    $objDemo->receiver = $email;

                                    Mail::to($email)->send(new WelcomeNewUser($objDemo));

                                    //End send Mail

                                    $userid= $userId;
                                }

                            }

                            $digits = 4;
                            $otpcode= rand(pow(10, $digits-1), pow(10, $digits)-1);

                            $serviceReqData['user_id'] =  $userid;
                            $serviceReqData['service_id'] =  $service_id;
                            $serviceReqData['location'] =  $service_location;
                            $serviceReqData['username'] =  $username;
                            $serviceReqData['mobile_number'] =  $mobile_number;
                            $serviceReqData['email'] =  $email;
                            $serviceReqData['otp'] =  $otpcode;
                            //$serviceReqData['mobile_verify'] =  1;
                            $serviceReqData['created_at'] = Carbon::now()->toDateTimeString();
                            $serviceReqData['updated_at'] = Carbon::now()->toDateTimeString();
                            $serviceRequestId=DB::table('service_request')->insertGetId($serviceReqData);

                            if(!empty($serviceRequestId))
                            {
                                   //Start Send OTP ON Mail to User

                                    $objDemo = new \stdClass();
                                    $objDemo->otpcode = 'Your unique code is: '.$otpcode;
                                    $objDemo->message = 'Thank You for your service request in buskalo, please use this otp for complete your request.';
                                    $objDemo->sender = 'Buskalo';
                                    $objDemo->receiver = $email;
                                    Mail::to($email)->send(new ServiceRequestOtp($objDemo));
                                    //End send Mail
                            }


                            foreach($queOptionData as $key => $value) 
                            {
                                
                            $QuestOptions['service_request_id'] = $serviceRequestId;
                            $QuestOptions['question_id'] = $value['question_id']; 
                            $QuestOptions['option_id'] =  $value['option_id'];
                            $saveQuestOptions = DB::table('service_request_questions')->insert($QuestOptions);
                             }// end foreach

                       
                $resultArray['status']='1';  

                 $resultArray['message']=trans('Congratulations! Unique OTP send on your email, please enter for further process.');
                 $resultArray['request_id']=$serviceRequestId;
                echo json_encode($resultArray); exit;


         }




            public function verifyServiceRequestOTP(Request $request)
            {
               
                $otp = isset($request->otp) && !empty($request->otp) ? $request->otp : '' ;
                $request_id = isset($request->request_id) && !empty($request->request_id) ? $request->request_id : '' ;

                $servicereq = DB::table('service_request')
                ->whereRaw("(id = '".$request_id."')")
                ->first(); 

                  if(!empty($servicereq))
                        {  
                           if($servicereq->mobile_verify==1)
                           {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('Your OTP already used for this service.');
                            echo json_encode($resultArray); exit; 
                           }
                           if($servicereq->otp==$otp AND $servicereq->mobile_verify==0)
                           {

                                $update_Arr['mobile_verify'] = 1;
                                $update_Arr['updated_at'] = Carbon::now()->toDateTimeString();                  
                                if(DB::table('service_request')->where('id', $request_id)->update($update_Arr))
                                {


                                    $getData=array();
                                    $getData = DB::table('service_request')->select('id','user_id','service_id','location','username','mobile_number','email','mobile_verify AS otp_verified', 'status','created_at','updated_at')->whereRaw("(id = '".$servicereq->id."')")->first();

                                    $getquestionData = DB::table('service_request_questions')->select('question_id','option_id')->whereRaw("(service_request_id = '".$servicereq->id."')")->get();

                                    $getData->questions_options = $getquestionData;


                                    $resultArray['status']='1';     
    $resultArray['message']=trans('Congratulations!
    Your application has been approved;
    Within 24 hours you will receive the information
    up to 3 professionals who meet
    with your requirements and who are interested
    to help you.
    Welcome to the new era!.');
                                    $resultArray['data'] = $getData;
                                    echo json_encode($resultArray); exit;
                                }
                                else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('apimessage.something went wrong.');
                                    echo json_encode($resultArray); exit;
                                }


                           }
                           else
                           {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('Invalid OTP.');
                                echo json_encode($resultArray); exit; 
                           }
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('Requested id not exist in our database.');
                            echo json_encode($resultArray); exit; 
                        }

            }





          /*
          * ************ SEND REQUEST BY USER *********************
          *
          * SEND REQUEST BY USER API END HERE
          */

 /* ------------------------------------------------------------------------------------------------ */
  
         /*
          * UPDATE PASSWORD API START HERE
          */
            public function updatePassword(Request $request)
            {

                $access_token=123456;
                $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
                $new_password = !empty($request->new_password) ? $request->new_password : '' ;
                $conf_password = !empty($request->conf_password) ? $request->conf_password : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'userid' => 'required',
                'new_password' => 'required',
                'conf_password' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                }
                
                if(!empty($userid) && !empty($new_password) && !empty($conf_password))
                {
                    
                $user_arr = DB::table('users')->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();

                if(!empty($user_arr))
                {
                    $check_auth = $this->checkToken($access_token, $user_arr->id);
                    if($check_auth['status']!=1)
                    {
                    echo json_encode($check_auth); exit;
                    }
                    else
                    {   
                    if($new_password === $conf_password)
                    {
                        if(strlen(trim($new_password)) < 6)
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.Password must be greater than 6 characters');
                            echo json_encode($resultArray); exit;
                        }
                        else
                            {
                                
                                
                                $updatePassword_Arr['password'] = Hash::make($new_password);
                                $updatePassword_Arr['updated_at'] = Carbon::now()->toDateTimeString();                  
                                if(DB::table('users')->where('id', $user_arr->id)->update($updatePassword_Arr))
                                {
                                    $resultArray['status']='1';     
                                    $resultArray['message']=trans('apimessage.Password update successfully.');
                                    echo json_encode($resultArray); exit;
                                }
                                else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('apimessage.Password not update.');
                                    echo json_encode($resultArray); exit;
                                }
                            }
                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Do not match new password and confirm password.');
                        echo json_encode($resultArray); exit;
                    }
                    }
                }
                else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;
                    }
                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;
                }
            }

         /*
          * UPDATE PASSWORD API END HERE
          */

 /* ------------------------------------------------------------------------------------------------ */


      /* --------------------forgot Password Api Start-------------------- */
      /*
       * FORGOT PASSWORD API START HERE
       */

        public function forgotPassword(Request $request)
        {    
        $access_token = 123456;
        $email = !empty($request->email) ? $request->email : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang);
        
        if(isset($email) && !empty($email))
        {
        $user_arr = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null )")->first();
        if(!empty($user_arr))
        {
        $check_auth = $this->checkToken($access_token,$user_arr->id);
        if($check_auth['status']!=1)
        {
        return json_encode($check_auth);
        }
        else
        {    
        $username = isset($user_arr->username) && !empty($user_arr->username) ? $user_arr->username:'';

        // $confirm_code = md5(uniqid(mt_rand(0,999), true));
        // $token = Hash::make($confirm_code);
       
        $token = $token = bin2hex(random_bytes(20));

        $password_resets = DB::table('password_resets')->where('email',$email)->first();
        $lastInsertedToken="";
        if(!empty($password_resets))
        {
           
        $updateToken['token'] = $token; 
        $updateToken['is_updated'] = "0";
        $updateToken['created_at'] = Carbon::now();   
        DB::table('password_resets')->where('email', $email)->update($updateToken);  

        $getLast = DB::table('password_resets')
        ->where('email',$email)
        ->where('token',$token)
        ->first();
        $lastInsertedToken=$getLast->token;

        }
        else
        {
            
        $insertToken['email'] = $email;    
        $insertToken['token'] = $token;
        $insertToken['is_updated'] = "0";
        $insertToken['created_at'] = Carbon::now();  

        $lastId=DB::table('password_resets')->where('email', $email)->insert($insertToken);
        $getnew = DB::table('password_resets')->where('id',$lastId)->first();
        $lastInsertedToken=$getnew->token;
        }

                $encryptedEmailId = Crypt::encrypt($email);
                //Start Send Forgot password ON Mail to User
                $objDemo = new \stdClass();
                $objDemo->message = 'Hello '.$username.', We have received a password reset request for your account, please click on this link for password reset request.';
                $objDemo->link = 'http://design.wdptechnologies.com/buskalo/public/forgot_password/'.$encryptedEmailId.'/'.$lastInsertedToken;
                $objDemo->sender = 'Buskalo';
                $objDemo->receiver = $email;
                Mail::to($email)->send(new forgotPasswordMail($objDemo));
                //End send Mail

            $resultArray['status']='1';
            $resultArray['message']='Hello '.$username.', We have received a password reset request for your account, please check your email for password Reset.';
            return json_encode($resultArray);

        }
        }
        else
        {
        $resultArray['status']='0';
        $resultArray['message']='Invalid Email.';
        return json_encode($resultArray);
        }
        }
        else
        {
        $resultArray['status']='0';
        $resultArray['message']='Invalid parameter.';
        return json_encode($resultArray);
        }
        
        }
        

 /* ------------------------------------------------------------------------------------------------ */


        // public function updateForgotPassword(Request $request)
        // {    
        // error_reporting(0);

        // $access_token = 123456;

        // $email = !empty($request->email) ? $request->email : '' ;
        // $token = !empty($request->token) ? $request->token : '' ;
        // $password = !empty($request->password) ? $request->password : '' ;
        // $confirm_password = !empty($request->confirm_password) ? $request->confirm_password : '' ;

        
        // if(isset($email) && !empty($email) && isset($token) && !empty($token) && isset($password) && !empty($password) && isset($confirm_password) && !empty($confirm_password))
        // {
        //     $user_arr = DB::table('users')->where('email',$email)->first();
        //     if(!empty($user_arr))
        //     {
        //     $ChkTokn  = DB::table('password_resets')
        //     ->where('email',$email)
        //     ->where('token',$token)
        //     ->first();

        //     if($ChkTokn->token==$token)
        //     {

        //         if($ChkTokn->email==$email)
        //         {

        //         if($ChkTokn->is_updated=='1')
        //         {
        //         $resultArray['status']='0';
        //         $resultArray['message']='Please Request for another token, this token is already used.';
        //         return json_encode($resultArray);
        //         }
        //         else
        //         {
        //         $check_auth = $this->checkToken($access_token);
        //         if($check_auth['status']!=1)
        //         {
        //         return json_encode($check_auth);
        //         }
        //         else
        //         {
        //             if($password===$confirm_password) 
        //             {   

        //             $username = isset($user_arr->username) && !empty($user_arr->username) ? $user_arr->username:'';

        //             $updatePass['password'] = trim(Hash::make($password));
        //             $updatePass['updated_at'] = Carbon::now()->toDateTimeString();

        //             $gd=DB::table('users')->where('id',$user_arr->id)->update($updatePass);

        //                 if($gd==1 OR $gd==true)
        //                 {
        //                 $updateUser_arr['is_updated'] = "1";
        //                 $updateUser_arr['updated_at'] = Carbon::now()->toDateTimeString();

        //                 DB::table('password_resets')
        //                 ->where('token',$ChkTokn->token)
        //                 ->where('email',$ChkTokn->email)
        //                 ->update($updateUser_arr);
        //                 }

        //             $resultArray['status']='1';
        //             $resultArray['message']='Your Password Updated successfully.';
        //             return json_encode($resultArray);

        //             }
        //             else
        //             {
        //             $resultArray['status']='0';
        //             $resultArray['message']='Password and confirm password does not similar.';
        //             return json_encode($resultArray);
        //             }

        //         }

        //         }

        //         }
        //         else
        //         {

        //         $resultArray['status']='0';
        //         $resultArray['message']='Invalid email.';
        //         return json_encode($resultArray);

        //         }

        //     }
        //     else
        //     {

        //     $resultArray['status']='0';
        //     $resultArray['message']='Invalid Token.';
        //     return json_encode($resultArray);

        //     }

        //     }
        //     else
        //     {
        //     $resultArray['status']='0';
        //     $resultArray['message']='Invalid mail.';
        //     return json_encode($resultArray);
        //     }
        // }
        // else
        // {
        // $resultArray['status']='0';
        // $resultArray['message']='Invalid parameter.';
        // return json_encode($resultArray);
        // }
        
        // }

 /* ------------------------------------------------------------------------------------------------ */


          /*
           * FORGOT PASSWORD API END HERE
           */

         /* --------------------forgot Password Api End-------------------- */
         /*
          * UPDATE PROFILE API START HERE
          */
            public function updateProfile(Request $request)
            {
                $access_token=123456;
                $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
                $user_group_id = isset($request->user_group_id) && !empty($request->user_group_id) ? $request->user_group_id : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);

                //Update User Profile Start Here
                   
                     if(!empty($user_group_id) && $user_group_id==2)
                    {
                        $profile_pic = !empty($request->profile_pic) ? $request->profile_pic : '' ;
                        $identity_no = !empty($request->identity_no) ? $request->identity_no : '' ;
                        $dob = !empty($request->dob) ? $request->dob : '' ;
                        $address = !empty($request->address) ? $request->address : '' ;
                        $office_address = !empty($request->office_address) ? $request->office_address : '' ;
                        $other_address = !empty($request->other_address) ? $request->other_address : '' ;
                        $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                        $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
                        $office_number = !empty($request->office_number) ? $request->office_number : '' ;
                        $email = !empty($request->email) ? $request->email : '' ;


                        $validator = Validator::make($request->all(), [
                            'userid' => 'required',
                            'mobile_number' => 'required',
                            'address' => 'required',
                            'email' => 'required',
                            
                        ]);

                        if($validator->fails())
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.Invalid parameters.');
                            echo json_encode($resultArray); exit;      
                        }  


                            $mobileexist = DB::table('users')->whereRaw("(mobile_number = '".$mobile_number."' AND deleted_at IS null )")->where('id', '!=' , $userid)->first();

                            if(!empty($mobileexist))
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('Mobile Number Already Exist.');
                                echo json_encode($resultArray); exit;
                            }

                            if(!empty($userid) && !empty($session_key)) 
                            {
                                $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                                if($check_auth['status']!=1)
                                {
                                 echo json_encode($check_auth); exit;
                                }
                                else
                                {
                                     $userEntity = DB::table('users')
                                    ->whereRaw("(active=1)")
                                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                                    ->first();  

                                    if(!empty($userEntity))
                                    {

                                        $profile = $userEntity->avatar_location;

                                        if(isset($_FILES['profile_pic']['name']) && !empty($_FILES['profile_pic']['name']))
                                            {
                                                $extq = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                                $filename = $userid.'.'.$extq;

                                                $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                                $fmove = move_uploaded_file($_FILES['profile_pic']['tmp_name'],public_path() . '/img/user/profile/'.$filename);
                                               
                                                 $profile = $filename;
                                            }
                                           
                                        $userData['avatar_location'] =  $profile;
                                        $userData['identity_no'] =  $identity_no;
                                        $userData['dob'] =  $dob;
                                        $userData['address'] =  $address;
                                        $userData['office_address'] =  $office_address;
                                        $userData['other_address'] =  $other_address;
                                        $userData['mobile_number'] =  $mobile_number;
                                        $userData['landline_number'] =  $landline_number;
                                        $userData['office_number'] =  $office_number;
                                        

                                        DB::table('users')->where('id',$userEntity->id)->update($userData);
                                            
                                             
                                           $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.dob','users.address','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','created_at','updated_at')->where('id',$userEntity->id)->first();


                                            $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';

                                            $users_count_var->email = isset($users_count_var->email) && !empty($users_count_var->email) ? $users_count_var->email : '';

                                            $users_count_var->username = isset($users_count_var->username) && !empty($users_count_var->username) ? $users_count_var->username : '';

                                            $users_count_var->address = isset($users_count_var->address) && !empty($users_count_var->address) ? $users_count_var->address : '';

                                            $users_count_var->dob = isset($users_count_var->dob) && !empty($users_count_var->dob) ? $users_count_var->dob : '';

                                            $users_count_var->office_address = isset($users_count_var->office_address) && !empty($users_count_var->office_address) ? $users_count_var->office_address : '';
                                            $users_count_var->other_address = isset($users_count_var->other_address) && !empty($users_count_var->other_address) ? $users_count_var->other_address : '';

                                            $users_count_var->mobile_number = isset($users_count_var->mobile_number) && !empty($users_count_var->mobile_number) ? $users_count_var->mobile_number : '';

                                            $users_count_var->landline_number = isset($users_count_var->landline_number) && !empty($users_count_var->landline_number) ? $users_count_var->landline_number : '';
                                            $users_count_var->office_number = isset($users_count_var->office_number) && !empty($users_count_var->office_number) ? $users_count_var->office_number : '';

                                           
                                            $settingEntity = DB::table('settings')->where('user_id',$userEntity->id)->first(); 

                                            if($settingEntity)
                                            {
                                                $users_count_var->lang =$settingEntity->app_language;
                                            }else
                                            {
                                                $users_count_var->lang =0;
                                            }
                                            if(!empty($users_count_var->avatar_location))
                                            {
                                             $path='/img/user/profile/';
                                             $users_count_var->avatar_location = url($path.$users_count_var->avatar_location);
                                            }
                                            else
                                            {
                                             $users_count_var->avatar_location ="";
                                            }

                                          
                                            $resultArray['status']='1'; 
                                            $resultArray['userData'] = $users_count_var;        
                                            $resultArray['message']=trans('apimessage.Profile updated successfully.');
                                            $resultArray['session_key']=$session_key;
                                            echo json_encode($resultArray); exit;

                                    }
                                    else 
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('apimessage.Invalid user.');
                                        echo json_encode($resultArray); exit;
                                    }
                                }
                           }
                            else 
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid user.');
                                echo json_encode($resultArray); exit;
                            }

                    }
             //Update User Profile End Here


                    
             // Contractor Profile Update here

             if(!empty($user_group_id) && $user_group_id==3)
                    {
                        $profile_pic = !empty($request->profile_pic) ? $request->profile_pic : '' ;
                        $identity_no = !empty($request->identity_no) ? $request->identity_no : '' ;
                        $dob = !empty($request->dob) ? $request->dob : '' ;
                        $address = !empty($request->address) ? $request->address : '' ;
                        $office_address = !empty($request->office_address) ? $request->office_address : '' ;
                        $other_address = !empty($request->other_address) ? $request->other_address : '' ;
                        $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                        $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
                        $office_number = !empty($request->office_number) ? $request->office_number : '' ;
                         //Multiple
                        $service_offered=!empty($request->service_offered) ? $request->service_offered : '' ;
                        //$service_offered=[{"service_id":"1","zip_code":"302020","id":""},{"service_id":"2","zip_code":"305404","id":""}];

                        //Multiple
                        $payment_method=!empty($request->payment_method) ? $request->payment_method : '' ;
                        //$payment_method=[{"method_id":"1","edit_id":""},{"method_id":"2","edit_id":""}];

                        //Multiple
                        $images_gallery=!empty($request->images_gallery) ? $request->images_gallery : '' ;
                        $videos_gallery=!empty($request->videos_gallery) ? $request->videos_gallery : '' ;

                        //Multiple
                        $documents=!empty($request->documents) ? $request->documents : '' ;
                        //$documents=[{"document_id":"1","edit_id":""},{"document_id":"2","edit_id":""}];


                        $facebook_url=!empty($request->facebook_url) ? $request->facebook_url : '' ;
                        $instagram_url=!empty($request->instagram_url) ? $request->instagram_url : '' ;
                        $linkedin_url=!empty($request->linkedin_url) ? $request->linkedin_url : '' ;
                        $twitter_url=!empty($request->twitter_url) ? $request->twitter_url : '' ;
                        $other_url=!empty($request->other_url) ? $request->other_url : '' ;
                        $profile_description=!empty($request->profile_description) ? $request->profile_description : '' ;


                        $validator = Validator::make($request->all(), [
                            'userid' => 'required',
                            'mobile_number' => 'required',
                        ]);

                        if($validator->fails())
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.Invalid parameters.');
                            echo json_encode($resultArray); exit;      
                        }  

                        
                           $mobileexist = DB::table('users')->whereRaw("(mobile_number = '".$mobile_number."' AND deleted_at IS null )")->where('id', '!=' , $userid)->first();

                            if(!empty($mobileexist))
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('Mobile Number Already Exist.');
                                echo json_encode($resultArray); exit;
                            }

                            if(!empty($userid) && !empty($session_key)) 
                            {
                                $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                                if($check_auth['status']!=1)
                                {
                                //  echo json_encode($check_auth); exit;
                                // }
                                // else
                                // {
                                     $userEntity = DB::table('users')
                                    ->whereRaw("(active=1)")
                                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                                    ->first();  

                                    if(!empty($userEntity))
                                    {

                                        $profile = $userEntity->avatar_location;

                                        if(isset($_FILES['profile_pic']['name']) && !empty($_FILES['profile_pic']['name']))
                                            {
                                                $extq = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                                $filename = $userid.'.'.$extq;

                                                $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                                $fmove = move_uploaded_file($_FILES['profile_pic']['tmp_name'],public_path() . '/img/contractor/profile/'.$filename);
                                               
                                                 $profile = $filename;
                                            }



                                            // Add Gallery Images
                                              
                                              if(!empty($images_gallery))
                                              {
                                                     $fileNames = array_filter($_FILES['images_gallery']['name']);
                                                     $targetDir = public_path() . '/img/contractor/gallery/images/'; 
                                                     $allowTypes = array('jpg','png','jpeg'); 
                                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 
                                                     if(!empty($fileNames))
                                                    {
                                                      foreach($_FILES['images_gallery']['name'] as $key=>$val) 
                                                      {

                                                         $fileName = rand(0000,9999).basename($_FILES['images_gallery']['name'][$key]); 
                                                         $targetFilePath = $targetDir . $fileName;

                                                            // Check whether file type is valid 
                                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                                            if(in_array($fileType, $allowTypes))
                                                            { 
                                                                // Upload file to server 
                                                                if(move_uploaded_file($_FILES["images_gallery"]["tmp_name"][$key], $targetFilePath))
                                                                { 
                                                                    $insert['file_name'] = $fileName;
                                                                    $insert['file_type'] = $fileType;
                                                                    $insert['user_id'] = $userid;
                                                                    $insert['status'] = 1;
                                                                    $insert['created_at'] = Carbon::now();  
                                                                    DB::table('users_images_gallery')->insertGetId($insert); 
                                                                }else
                                                                { 
                                                                     $errorUpload .= 'File not uploaded.'; 
                                                                } 
                                                            }else
                                                            { 
                                                                $errorUploadType .='Video Type Allowed Only (.jpg,.png,.jpeg).';
                                                            }  
                                                       }
                                                    }  
                                                    else
                                                    {
                                                            $errorMsg .='Please select file.';
                                                    }   
                                              }


                                               if(!empty($errorUpload))
                                                {
                                                    $resultArray['status']='0'; 
                                                    $resultArray['message']=$errorUpload;
                                                    echo json_encode($resultArray); exit;
                                                }
                                                 if(!empty($errorUploadType))
                                                {
                                                    $resultArray['status']='0'; 
                                                    $resultArray['message']=$errorUploadType;
                                                    echo json_encode($resultArray); exit;
                                                }

                                                if(!empty($errorMsg))
                                                {
                                                    $resultArray['status']='0'; 
                                                    $resultArray['message']=$errorMsg;
                                                    echo json_encode($resultArray); exit;
                                                }

                                              // Add Gallery Images


                                              // Add Gallery Videos


                                                if(!empty($videos_gallery))
                                              {
                                                     $fileNames = array_filter($_FILES['videos_gallery']['name']);
                                                     $targetDir = public_path() . '/img/contractor/gallery/videos/'; 
                                                     $allowTypes = array("webm", "mp4", "ogv"); 
                                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 
                                                     if(!empty($fileNames))
                                                    {
                                                      foreach($_FILES['videos_gallery']['name'] as $key=>$val) 
                                                      {

                                                         $fileName = rand(0000,9999).basename($_FILES['videos_gallery']['name'][$key]); 
                                                         $targetFilePath = $targetDir . $fileName;

                                                            // Check whether file type is valid 
                                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                                            if(in_array($fileType, $allowTypes))
                                                            { 
                                                                // Upload file to server 
                                                                if(move_uploaded_file($_FILES["videos_gallery"]["tmp_name"][$key], $targetFilePath))
                                                                { 
                                                                    $insert['file_name'] = $fileName;
                                                                    $insert['file_type'] = $fileType;
                                                                    $insert['user_id'] = $userid;
                                                                    $insert['status'] = 1;
                                                                    $insert['created_at'] = Carbon::now();  
                                                                    DB::table('users_videos_gallery')->insertGetId($insert); 
                                                                }else
                                                                { 
                                                                     $errorUpload .= 'Video not uploaded.'; 
                                                                } 
                                                            }else
                                                            { 
                                                                $errorUploadType .='Video Type Allowed Only (.webm,.mp4,.ogv).';
                                                            }  
                                                       }
                                                    }  
                                                    else
                                                    {
                                                            $errorMsg .='Please select Video file.';
                                                    }   
                                              }


                                                if(!empty($errorUpload))
                                                {
                                                    $resultArray['status']='0'; 
                                                    $resultArray['message']=$errorUpload;
                                                    echo json_encode($resultArray); exit;
                                                }
                                                 if(!empty($errorUploadType))
                                                {
                                                    $resultArray['status']='0'; 
                                                    $resultArray['message']=$errorUploadType;
                                                    echo json_encode($resultArray); exit;
                                                }

                                                if(!empty($errorMsg))
                                                {
                                                    $resultArray['status']='0'; 
                                                    $resultArray['message']=$errorMsg;
                                                    echo json_encode($resultArray); exit;
                                                }



                                        // Add Gallery Videos

                                           
                                        //Update In user Table

                                        $userData['avatar_location'] =  $profile;
                                        $userData['identity_no'] =  $identity_no;
                                        $userData['dob'] =  $dob;
                                        $userData['address'] =  $address;
                                        $userData['office_address'] =  $office_address;
                                        $userData['other_address'] =  $other_address;
                                        $userData['mobile_number'] =  $mobile_number;
                                        $userData['landline_number'] =  $landline_number;
                                        $userData['office_number'] =  $office_number;
                                        $userData['profile_description'] =  $profile_description;
                                        $userData['updated_at'] = Carbon::now()->toDateTimeString();
                                        DB::table('users')->where('id',$userEntity->id)->update($userData);

                                        //Update In Social Url Table
                                        if(!empty($facebook_url) || !empty($instagram_url) || !empty($linkedin_url)|| !empty($twitter_url)|| !empty($other_url))

                                        {
                                            $socialData['facebook_url'] =  $facebook_url;
                                            $socialData['instagram_url'] =  $instagram_url;
                                            $socialData['linkedin_url'] =  $linkedin_url;
                                            $socialData['twitter_url'] =  $twitter_url;
                                            $socialData['other'] =  $other_url;
                                            $socialData['updated_at'] = Carbon::now()->toDateTimeString();

                                             $socialEntity = DB::table('social_networks')
                                            ->whereRaw("(user_id = '".$userid."' AND deleted_at IS null )")
                                            ->first();
                                            if(!empty($socialEntity) && $socialEntity->user_id==$userid)
                                            {
                                                DB::table('social_networks')->where('user_id',$socialEntity->user_id)->update($socialData);
                                            }
                                            else
                                            {
                                                $socialData['created_at'] = Carbon::now()->toDateTimeString();
                                                $socialData['user_id'] =  $userid;
                                                $insId=DB::table('social_networks')->insertGetId($socialData);
                                            }  

                                        }
                                        
                                        ///End social


                                        //Service Offerd
                                        if(!empty($service_offered))
                                        {

                                        $fields_data = json_decode($service_offered);
                                        $serviceOfferedData = json_decode( json_encode($fields_data), true);


                                       foreach($serviceOfferedData as $key => $value) 
                                         {
                                            if(!empty($value['id']))
                                            {
                                          
                                            $serv['service_id'] = $value['service_id']; 
                                            $serv['zipcode'] =  $value['zip_code'];
                                            $serv['updated_at'] = Carbon::now()->toDateTimeString();
                                             DB::table('services_offered')->where('id',$value['id'])->update($serv);

                                            }else
                                            {
                                                $getData = DB::table('services_offered')->select('id','user_id','service_id')->whereRaw("(user_id = '".$userid."')")->whereRaw("(service_id = '".$value['service_id']."')")->first();

                                                if(!empty($getData) && $getData->service_id==$value['service_id'])
                                                {
                                                    //already exist;
                                                }
                                                else
                                                {
                                                    $serv['user_id'] = $userid;
                                                    $serv['service_id'] = $value['service_id']; 
                                                    $serv['zipcode'] =  $value['zip_code'];
                                                    $serv['created_at'] = Carbon::now()->toDateTimeString();
                                                    $saveserv = DB::table('services_offered')->insert($serv);
                                                }
                                               
                                            }
                                         }
                                      }
                                        //End Service Offered

                                     //Payment Method

                                      if(!empty($payment_method))
                                        {

                                        $fields_data = json_decode($payment_method);
                                        $paymentMeData = json_decode( json_encode($fields_data), true);

                                       foreach($paymentMeData as $key => $value) 
                                         {
                                            if(!empty($value['edit_id']))
                                            {
                                                $pm['payment_method_id'] = $value['method_id']; 
                                                $pm['updated_at'] = Carbon::now()->toDateTimeString();
                                                 DB::table('user_payment_methods')->where('id',$value['edit_id'])->update($pm);

                                            }else
                                            {
                                                 $getData = DB::table('user_payment_methods')->select('id','user_id','payment_method_id','status')->whereRaw("(user_id = '".$userid."')")->whereRaw("(payment_method_id = '".$value['method_id']."')")->first();

                                                if(!empty($getData) && $getData->payment_method_id==$value['method_id'])
                                                {
                                                    //already exist;
                                                }
                                                else
                                                {
                                                    $paym['user_id'] = $userid;
                                                    $paym['payment_method_id'] = $value['method_id']; 
                                                    $paym['status'] =  1;
                                                    $paym['created_at'] = Carbon::now()->toDateTimeString();
                                                    $savepaym = DB::table('user_payment_methods')->insert($paym);
                                                }
                                               
                                            }
                                         }
                                      }
                                         //End Payment Method


                                         //documents

                                      if(!empty($documents))
                                        {

                                        $fields_data = json_decode($documents);
                                        $documentData = json_decode( json_encode($fields_data), true);

                                       foreach($documentData as $key => $value) 
                                         {
                                            if(!empty($value['edit_id']))
                                            {
                                                $doc['payment_method_id'] = $value['method_id']; 
                                                $doc['updated_at'] = Carbon::now()->toDateTimeString();
                                                 DB::table('user_payment_methods')->where('id',$value['edit_id'])->update($doc);

                                            }else
                                            {
                                                 $getData = DB::table('user_payment_methods')->select('id','user_id','payment_method_id','status')->whereRaw("(user_id = '".$userid."')")->whereRaw("(payment_method_id = '".$value['method_id']."')")->first();

                                                if(!empty($getData) && $getData->payment_method_id==$value['method_id'])
                                                {
                                                    //already exist;
                                                }
                                                else
                                                {
                                                    $paym['user_id'] = $userid;
                                                    $paym['payment_method_id'] = $value['method_id']; 
                                                    $paym['status'] =  1;
                                                    $paym['created_at'] = Carbon::now()->toDateTimeString();
                                                    $savepaym = DB::table('user_payment_methods')->insert($paym);
                                                }
                                               
                                            }
                                         }
                                      }
                                         //End documents


                                  


                                         $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at')->where('id',$userEntity->id)->first();

                                            $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';

                                            $users_count_var->email = isset($users_count_var->email) && !empty($users_count_var->email) ? $users_count_var->email : '';

                                            $users_count_var->username = isset($users_count_var->username) && !empty($users_count_var->username) ? $users_count_var->username : '';

                                            $users_count_var->address = isset($users_count_var->address) && !empty($users_count_var->address) ? $users_count_var->address : '';

                                            $users_count_var->dob = isset($users_count_var->dob) && !empty($users_count_var->dob) ? $users_count_var->dob : '';

                                            $users_count_var->office_address = isset($users_count_var->office_address) && !empty($users_count_var->office_address) ? $users_count_var->office_address : '';

                                            $users_count_var->other_address = isset($users_count_var->other_address) && !empty($users_count_var->other_address) ? $users_count_var->other_address : '';

                                            $users_count_var->mobile_number = isset($users_count_var->mobile_number) && !empty($users_count_var->mobile_number) ? $users_count_var->mobile_number : '';


                                            $users_count_var->mobile_number = isset($users_count_var->mobile_number) && !empty($users_count_var->mobile_number) ? $users_count_var->mobile_number : '';

                                            $users_count_var->landline_number = isset($users_count_var->landline_number) && !empty($users_count_var->landline_number) ? $users_count_var->landline_number : '';

                                            $users_count_var->office_number = isset($users_count_var->office_number) && !empty($users_count_var->office_number) ? $users_count_var->office_number : '';

                                            $users_count_var->profile_description = isset($users_count_var->profile_description) && !empty($users_count_var->profile_description) ? $users_count_var->profile_description : '';

                                           
                                            $settingEntity = DB::table('settings')->where('user_id',$userEntity->id)->first(); 

                                            if($settingEntity)
                                            {
                                                $users_count_var->lang =$settingEntity->app_language;
                                            }else
                                            {
                                                $users_count_var->lang =0;
                                            }
                                            if(!empty($users_count_var->avatar_location))
                                            {
                                             $path='/img/contractor/profile/';
                                             $users_count_var->avatar_location = url($path.$users_count_var->avatar_location);
                                            }
                                            else
                                            {
                                             $users_count_var->avatar_location ="";
                                            }

                                    ///////////////////////Social Data/////////////////

                                        $datasocial=array();
                                        $socialData=DB::table('social_networks')->where('user_id',$userEntity->id)->whereRaw("(status = 1 AND deleted_at IS null )")->first(); 
                                        if(!empty($socialData))
                                        {
                                        
                                    $datasocial['facebook_url'] = isset($socialData->facebook_url) && !empty($socialData->facebook_url) ? $socialData->facebook_url : '';

                                    $datasocial['insta_url'] = isset($socialData->instagram_url) && !empty($socialData->instagram_url) ? $socialData->instagram_url : '';

                                    $datasocial['linkedin_url'] = isset($socialData->linkedin_url) && !empty($socialData->linkedin_url) ? $socialData->linkedin_url : '';
                                    $datasocial['twitter_url'] = isset($socialData->twitter_url) && !empty($socialData->twitter_url) ? $socialData->twitter_url : '';
                                    $datasocial['other_url'] = isset($socialData->other) && !empty($socialData->other) ? $socialData->other : '';
                                    $datasocial['created_at'] = isset($socialData->created_at) && !empty($socialData->created_at) ? $socialData->created_at : '';

                                        }

                                            if(!empty($datasocial))
                                             {
                                               $users_count_var->social_url[] = $datasocial;
                                             }
                                             else
                                             {
                                                $users_count_var->social_url=[];
                                             }

                                      ///////////////////////Social Data/////////////////

                                          
                                     ///////////////////////services offered/////////////////

                                          $srname="";
                                          if($lang=='es')
                                            {$srname='services.es_name AS service_name';}
                                         else{$srname='services.en_name AS service_name';}


                                          $servicesOffered = DB::table('services_offered')
                                        ->leftjoin('services', 'services_offered.service_id', '=', 'services.id')
                                        ->select('services_offered.id','services_offered.user_id','services_offered.service_id',$srname,'services_offered.zipcode','services_offered.created_at')
                                        ->where('services_offered.user_id',$userEntity->id)->whereRaw("(services_offered.deleted_at IS null )")->get()->toArray();

                                             if(!empty($servicesOffered))
                                             {
                                               $users_count_var->services_offered = $servicesOffered;
                                             }
                                             else
                                             {
                                                $users_count_var->services_offered=[];
                                             }

                                        ///////////////////////services offered/////////////////


                                         ///////////////////////Payment Methods/////////////////

                                              $usersPayMethod=DB::table('user_payment_methods')->select('id','user_id','payment_method_id','status','created_at')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($usersPayMethod))
                                             {
                                               $users_count_var->payment_methods = $usersPayMethod;
                                             }
                                             else
                                             {
                                                $users_count_var->payment_methods=[];
                                             }

                                             ///////////////////////Payment Methods/////////////////


                                             ///////////////////////Gallery/////////////////

                                              $allImages=DB::table('users_images_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($allImages))
                                             {
                                               $users_count_var->gallery['images'] = $allImages;
                                             }
                                             else
                                             {
                                                $users_count_var->gallery['images']=[];
                                             }


                                             $allVideos=DB::table('users_videos_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($allVideos))
                                             {
                                               $users_count_var->gallery['videos'] = $allVideos;
                                             }
                                             else
                                             {
                                                $users_count_var->gallery['videos']=[];
                                             }

                                             ///////////////////////Gallery/////////////////


                                            ///////////////////////Total Employees/////////////////

                                              $totalEmployee=DB::table('workers')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                              if(!empty($totalEmployee))
                                             {
                                               $users_count_var->total_employee = count($totalEmployee);
                                             }
                                             else
                                             {
                                                $users_count_var->total_employee="";
                                             }
                                         
                                          ///////////////////////Total Employees/////////////////

                                            $resultArray['status']='1'; 
                                            $resultArray['userData'] = $users_count_var;        
                                            $resultArray['message']=trans('apimessage.Profile updated successfully.');
                                            $resultArray['session_key']=$session_key;
                                            echo json_encode($resultArray); exit;

                                    }
                                    else 
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('apimessage.Invalid user.');
                                        echo json_encode($resultArray); exit;
                                    }
                                }
                           }
                            else 
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid user.');
                                echo json_encode($resultArray); exit;
                            }

                    }

            // Contractor Profile End Here

            }
         /*
          * UPDATE PROFILE API END HERE
          */
 /* ------------------------------------------------------------------------------------------------ */

         /*
          * DOCUMENT TYPES LIST API START HERE
          */
        public function getDocumentTypes(Request $request)
        {
            $access_token=123456;
            $docArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            App::setLocale($lang);
            $documents = DocumentTypes::all();
            foreach($documents as $doc) 
            {
               
            array_push($docArray,array('id'=>$doc->id, 'type_en'=>$doc->type_en,'type_es'=>$doc->type_es,'status'=>$doc->status,'created_at'=>$doc->created_at,'updated_at'=>$doc->updated_at));
            }
            if($documents)
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('apimessage.document types list found successfully.!');
                $resultArray['data']=$docArray;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.You are not authorize to access this module.');
                echo json_encode($resultArray); exit;
            }
        }
         /*
          * DOCUMENT TYPES API END HERE
          */



          /*
          * PAYMENTS TYPES LIST API START HERE
          */
        public function getPaymentMethods(Request $request)
        {
            $access_token=123456;
            $paymentsArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            App::setLocale($lang);
           
             $payment_methods = DB::table('payment_methods')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(deleted_at IS null)")
                            ->get(); 
            foreach($payment_methods as $payments) 
            {
                if($lang=='es'){$name=$payments->name_es;}else{$name=$payments->name_en;}
              
               array_push($paymentsArray,array('id'=>$payments->id, 'name'=>$name,'status'=>$payments->status,'created_at'=>$payments->created_at,'updated_at'=>$payments->updated_at));

            }
            if($payment_methods)
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('payment methods list found successfully.!');
                $resultArray['data']=$paymentsArray;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.You are not authorize to access this module.');
                echo json_encode($resultArray); exit;
            }
        }
         /*
          * PAYMENTS TYPES API END HERE
          */
 /* ------------------------------------------------------------------------------------------------ */
         /*
          * ADD CONTACTS IN CONTRACTOR PROFILE API START HERE
          */
            public function createWorkerProfile(Request $request)
            {

                $access_token=123456;
                //Contractor ID
                $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $first_name = !empty($request->first_name) ? $request->first_name : '' ;
                $last_name = !empty($request->last_name) ? $request->last_name : '' ;
                $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                $email = !empty($request->email) ? $request->email : '' ;
                $password = !empty($request->password) ? $request->password : '' ;
                $profile_pic = !empty($request->profile_pic) ? $request->profile_pic : '' ;
                $address = !empty($request->address) ? $request->address : '' ;
                ///Documents
                //$documents = !empty($request->documents) ? $request->documents : '' ;
               
                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                App::setLocale($lang);



                $validator = Validator::make($request->all(), [
                'userid' => 'required',
                'session_key'=>'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_number' => 'required',
                'email' => 'required',
                'password' => 'required',
                'address' => 'required',
                //'documents'=>'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                }  

                    if(!empty($userid) && !empty($session_key)) 
                    {
                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                        if($check_auth['status']!=1)
                        {
                         echo json_encode($check_auth); exit;
                        }else
                        {

                            $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(confirmed=1)")
                            ->whereRaw("(is_verified=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first(); 


                             $workerEmailEntity = DB::table('workers')
                            ->whereRaw("(email = '".$email."')")
                            ->whereRaw("(user_id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($workerEmailEntity))
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('Email Already Exist.');
                                echo json_encode($resultArray); exit;      
                            } 

                            $workerMobileEntity = DB::table('workers')
                            ->whereRaw("(mobile_number = '".$mobile_number."')")
                            ->whereRaw("(user_id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($workerMobileEntity))
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('Mobile Number Already Exist.');
                                echo json_encode($resultArray); exit;      
                            } 

                            if(!empty($userEntity))
                            {
                                $profile="";
                                if(isset($_FILES['profile_pic']['name']) && !empty($_FILES['profile_pic']['name']))
                                {
                                    $extq = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                    $filename = mt_rand(1000,9999).'.'.$extq;
                                    $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                 
                                    $fmove = move_uploaded_file($_FILES['profile_pic']['tmp_name'],public_path() . '/img/worker/profile/'.$filename);
                                    
                                     $profile = $filename;
                                }
                                $workerData['profile_pic'] =  $profile;
                                $workerData['first_name'] =  $first_name;
                                $workerData['last_name'] =  $last_name;
                                $workerData['mobile_number'] =  $mobile_number;
                                $workerData['email'] =  $email;
                                $workerData['password'] = Hash::make($password);
                                $workerData['address'] =  $address;
                                $workerData['user_id'] =  $userEntity->id;
                                $workerData['created_at'] = Carbon::now()->toDateTimeString();
                                $workerData['updated_at'] = Carbon::now()->toDateTimeString();
                                $workerId=DB::table('workers')->insertGetId($workerData);


                                // if(!empty($workerId) && !empty($documents))
                                // {
                                    
                                //     foreach ($documents as $value) 
                                //     {
                                         
                                //     }
                                // }

                                $workerEntity = DB::table('workers')->select('id', 'user_id', 'email', 'password', 'first_name', 'last_name', 'profile_pic', 'mobile_number', 'address', 'status', 'created_at', 'updated_at')
                                ->whereRaw("(id = '".$workerId."' AND deleted_at IS null )")
                                ->first();
                                
                                $path='/img/worker/profile/';
                                $workerEntity->profile_pic = url($path.$workerEntity->profile_pic);

                                $resultArray['status']='1';   
                                $resultArray['userData'] = $workerEntity;     
                                $resultArray['message']=trans('apimessage.Worker profile has been created successfully.');
                                $resultArray['session_key']=$session_key;
                                echo json_encode($resultArray); exit;

                            }else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid parameters.');
                                echo json_encode($resultArray); exit;
                            }

                        }
                    }
                    else 
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid user.');
                        echo json_encode($resultArray); exit;
                    }
                

            }

         /*
          * ADD CONTACTS IN CONTRACTOR PROFILE API END HERE
          */



         /*
          * CHECK AUTHENTICATION API START HERE
          */

        public function checkToken($access_token,$user_id='',$session_key='',$lang='')
        {
            $token=123456;
            if($access_token!=$token)
            {
                $resultArray['status']='0';
                $resultArray['message']=__('Invalid token!');
                return $resultArray;
                die;
            }
            else
            {
                if($user_id!='')
                {
                    $user_arr = DB::table('users')->where('id',trim($user_id))->where('active',1)->first();
                    if($session_key=='')
                    {
                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $session_key=substr(str_shuffle($chars),0,8);
                    $checkuser = DB::table('mobile_session')->where('user_id',trim($user_id))->first();
                    
                    if($checkuser)
                    {
                        $update_arr= array('session_key' => $session_key);
                        DB::table('mobile_session')->where('id',$checkuser->id)->update($update_arr);
                        
                    }
                    else
                    {
                        $mobile['user_id'] = $user_id; 
                        $mobile['session_key'] = $session_key;
                        $savemobile = DB::table('mobile_session')->insert($mobile);
                                       
                    }
                    $resultArray['status']='1';
                    $resultArray['Data']['randnumber']=$session_key;
                    return ($resultArray);
                    }
                    else
                    {
                    
                    $checkuser = DB::table('mobile_session')->where('user_id',trim($user_id))->where('session_key',$session_key)->first();

                    if($checkuser)
                    {
                    $resultArray['status']='1';
                    $resultArray['Data']['randnumber']=$session_key;
                    return ($resultArray); die;
                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=__('Invalid session.');
                        return ($resultArray); die;
                    }
                    }
                }
                else
                {
                    $resultArray['status']='1';
                    $resultArray['Data']['message']='';
                    return ($resultArray); die;
                }   
            }
        }

         /*
          * CHECK AUTHENTICATION API END HERE
          */




         public function createFolder(Request $request)
         {
             $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'name' => 'required',
                'session_key' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;       
            }

            $access_token=123456;
            $user_id = isset($request->user_id) && !empty($request->user_id) ? $request->user_id : '' ;
            $name = isset($request->name) && !empty($request->name) ? $request->name : '' ;
            $lang = isset($request->lang) && !empty($request->lang) ? $request->lang : 'en';
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            App::setLocale($lang);

            if(!empty($user_id) && !empty($session_key))
                {
                    $user_arr = DB::table('users')->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")->first();

                if(!empty($user_arr))
                {
                    $check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);
                    if($check_auth['status']!=1)
                    {
                    echo json_encode($check_auth); exit;
                    }
                    else
                    {
                        $insert['user_id'] = $user_id;    
                        $insert['name'] = $name;
                        $insert['status'] = 1;
                        $insert['created_at'] = Carbon::now();  
                        $lastId=DB::table('folders')->insertGetId($insert);


                         $folderEntity = DB::table('folders')
                        ->select('id','user_id','name','created_at','status')
                        ->whereRaw("(id = '".$lastId."')")
                        ->first();
                        if($folderEntity)
                        {
                            $resultArray['status']='1';
                            $resultArray['data']=$folderEntity;
                            $resultArray['message']='Your folder created successfully.';
                            return json_encode($resultArray);
                        }
                    }
                     
                 }
                 else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;
                    }       
                }

                 else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;
                }

       
         }






         public function folderList(Request $request)
         {
             $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'session_key' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;     
            }

            $access_token=123456;
            $user_id = isset($request->user_id) && !empty($request->user_id) ? $request->user_id : '' ;
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            $lang = isset($request->lang) && !empty($request->lang) ? $request->lang : 'en';
            App::setLocale($lang);

            if(!empty($user_id) && !empty($session_key))
                {
                    $user_arr = DB::table('users')->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")->first();

                if(!empty($user_arr))
                {
                     $check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);
                    if($check_auth['status']!=1)
                    {
                    echo json_encode($check_auth); exit;
                    }
                    else
                    {
                         $folderEntity = DB::table('folders')
                        ->select('id','user_id','name','created_at','status')
                        ->whereRaw("(user_id = '".$user_id."')")
                        ->get()->toArray();

                        if($folderEntity)
                        {
                            $resultArray['status']='1';
                            $resultArray['data']=$folderEntity;
                            $resultArray['message']='Your folder list found successfully.';
                            return json_encode($resultArray);
                        }
                        else
                        {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('data not found.');
                        echo json_encode($resultArray); exit;
                        }
                    }
                     
                 }
                 else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;
                    }       
                }

                 else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;
                }

       
         }



         public function getProfileById(Request $request)
         {
                $access_token=123456;
                $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);


                  $validator = Validator::make($request->all(), [
                            'userid' => 'required',
                            'session_key' => 'required',
                        ]);

                        if($validator->fails())
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.Invalid parameters.');
                            echo json_encode($resultArray); exit;      
                        } 

                        if(!empty($userid) && !empty($session_key)) 
                            {

                                $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                                if($check_auth['status']!=1)
                                {
                                 echo json_encode($check_auth); exit;
                                }
                                else
                                {
                                     $userEntity = DB::table('users')
                                    ->whereRaw("(active=1)")
                                    //->whereRaw("(is_verified=1)")
                                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                                    ->first();

                                    if(!empty($userEntity))
                                    {
                                        //Normal user 
                                        if($userEntity->user_group_id==2)
                                        {
                                            $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','created_at','updated_at')->where('id',$userEntity->id)->first();


                                            $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';

                                            $users_count_var->email = isset($users_count_var->email) && !empty($users_count_var->email) ? $users_count_var->email : '';

                                            $users_count_var->username = isset($users_count_var->username) && !empty($users_count_var->username) ? $users_count_var->username : '';

                                            $users_count_var->address = isset($users_count_var->address) && !empty($users_count_var->address) ? $users_count_var->address : '';

                                            $users_count_var->dob = isset($users_count_var->dob) && !empty($users_count_var->dob) ? $users_count_var->dob : '';

                                            $users_count_var->office_address = isset($users_count_var->office_address) && !empty($users_count_var->office_address) ? $users_count_var->office_address : '';
                                            $users_count_var->other_address = isset($users_count_var->other_address) && !empty($users_count_var->other_address) ? $users_count_var->other_address : '';

                                            $users_count_var->mobile_number = isset($users_count_var->mobile_number) && !empty($users_count_var->mobile_number) ? $users_count_var->mobile_number : '';

                                            $users_count_var->landline_number = isset($users_count_var->landline_number) && !empty($users_count_var->landline_number) ? $users_count_var->landline_number : '';
                                            $users_count_var->office_number = isset($users_count_var->office_number) && !empty($users_count_var->office_number) ? $users_count_var->office_number : '';



                                             $settingEntity = DB::table('settings')->where('user_id',$userEntity->id)->first(); 

                                            if($settingEntity)
                                            {
                                                $users_count_var->lang =$settingEntity->app_language;
                                            }else
                                            {
                                                $users_count_var->lang =0;
                                            }
                                            if(!empty($users_count_var->avatar_location))
                                            {
                                             $path='/img/user/profile/';
                                             $users_count_var->avatar_location = url($path.$users_count_var->avatar_location);
                                            }
                                            else
                                            {
                                             $users_count_var->avatar_location ="";
                                            }

                                          
                                            $resultArray['status']='1'; 
                                            $resultArray['userData'] = $users_count_var;        
                                            $resultArray['message']=trans('data found successfully.');
                                            $resultArray['session_key']=$session_key;
                                            echo json_encode($resultArray); exit;

                                        }//End Normal user


                                        //start Contractor
                                        else if($userEntity->user_group_id==3)
                                        {

                                         $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at')->where('id',$userEntity->id)->first();


                                            $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';

                                            $users_count_var->email = isset($users_count_var->email) && !empty($users_count_var->email) ? $users_count_var->email : '';

                                            $users_count_var->username = isset($users_count_var->username) && !empty($users_count_var->username) ? $users_count_var->username : '';

                                            $users_count_var->address = isset($users_count_var->address) && !empty($users_count_var->address) ? $users_count_var->address : '';

                                            $users_count_var->dob = isset($users_count_var->dob) && !empty($users_count_var->dob) ? $users_count_var->dob : '';

                                            $users_count_var->office_address = isset($users_count_var->office_address) && !empty($users_count_var->office_address) ? $users_count_var->office_address : '';

                                            $users_count_var->other_address = isset($users_count_var->other_address) && !empty($users_count_var->other_address) ? $users_count_var->other_address : '';

                                            $users_count_var->mobile_number = isset($users_count_var->mobile_number) && !empty($users_count_var->mobile_number) ? $users_count_var->mobile_number : '';


                                            $users_count_var->mobile_number = isset($users_count_var->mobile_number) && !empty($users_count_var->mobile_number) ? $users_count_var->mobile_number : '';

                                            $users_count_var->landline_number = isset($users_count_var->landline_number) && !empty($users_count_var->landline_number) ? $users_count_var->landline_number : '';

                                            $users_count_var->office_number = isset($users_count_var->office_number) && !empty($users_count_var->office_number) ? $users_count_var->office_number : '';

                                            $users_count_var->profile_description = isset($users_count_var->profile_description) && !empty($users_count_var->profile_description) ? $users_count_var->profile_description : '';


                                            $settingEntity = DB::table('settings')->where('user_id',$userEntity->id)->first(); 

                                            if($settingEntity)
                                            {
                                                $users_count_var->lang =$settingEntity->app_language;
                                            }else
                                            {
                                                $users_count_var->lang =0;
                                            }
                                            if(!empty($users_count_var->avatar_location))
                                            {
                                             $path='/img/contractor/profile/';
                                             $users_count_var->avatar_location = url($path.$users_count_var->avatar_location);
                                            }
                                            else
                                            {
                                             $users_count_var->avatar_location ="";
                                            }


                                        $datasocial=array();
                                        $socialData=DB::table('social_networks')->where('user_id',$userEntity->id)->whereRaw("(status = 1 AND deleted_at IS null )")->first(); 
                                        if(!empty($socialData))
                                        {
                                            $datasocial['facebook_url'] = isset($socialData->facebook_url) && !empty($socialData->facebook_url) ? $socialData->facebook_url : '';

                                            $datasocial['insta_url'] = isset($socialData->instagram_url) && !empty($socialData->instagram_url) ? $socialData->instagram_url : '';

                                            $datasocial['linkedin_url'] = isset($socialData->linkedin_url) && !empty($socialData->linkedin_url) ? $socialData->linkedin_url : '';
                                            $datasocial['twitter_url'] = isset($socialData->twitter_url) && !empty($socialData->twitter_url) ? $socialData->twitter_url : '';
                                            $datasocial['other_url'] = isset($socialData->other) && !empty($socialData->other) ? $socialData->other : '';
                                           $datasocial['created_at'] = isset($socialData->created_at) && !empty($socialData->created_at) ? $socialData->created_at : '';
                                        }

                                            if(!empty($datasocial))
                                             {
                                               $users_count_var->social_url[] = $datasocial;
                                             }
                                             else
                                             {
                                                $users_count_var->social_url=[];
                                             }



                                           $srname="";
                                          if($lang=='es')
                                            {$srname='services.es_name AS service_name';}
                                        else{$srname='services.en_name AS service_name';}


                                          $servicesOffered = DB::table('services_offered')
                                        ->leftjoin('services', 'services_offered.service_id', '=', 'services.id')
                                        ->select('services_offered.id','services_offered.user_id','services_offered.service_id',$srname,'services_offered.zipcode','services_offered.created_at')
                                        ->where('services_offered.user_id',$userEntity->id)->whereRaw("(services_offered.deleted_at IS null )")->get()->toArray();

                                             if(!empty($servicesOffered))
                                             {
                                               $users_count_var->services_offered = $servicesOffered;
                                             }
                                             else
                                             {
                                                $users_count_var->services_offered=[];
                                             }


                                             $usersPayMethod=DB::table('user_payment_methods')->select('id','user_id','payment_method_id','status','created_at')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($usersPayMethod))
                                             {
                                               $users_count_var->payment_methods = $usersPayMethod;
                                             }
                                             else
                                             {
                                                $users_count_var->payment_methods=[];
                                             }


                                              $totalEmployee=DB::table('workers')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                              if(!empty($totalEmployee))
                                             {
                                               $users_count_var->total_employee = count($totalEmployee);
                                             }
                                             else
                                             {
                                                $users_count_var->total_employee="";
                                             }
                                         

                                            $resultArray['status']='1'; 
                                            $resultArray['userData'] = $users_count_var;        
                                            $resultArray['message']=trans('data found successfully.');
                                            $resultArray['session_key']=$session_key;
                                            echo json_encode($resultArray); exit;

                                        }
                                        //End Contractor
                                        else
                                        {
                                            $resultArray['status']='0';
                                            $resultArray['message']=trans('apimessage.Invalid user.');
                                            echo json_encode($resultArray); exit;  
                                        }

                                    }
                                    else 
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('apimessage.Invalid user.');
                                        echo json_encode($resultArray); exit;
                                    }

                                }

                            }
                            else 
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid parameter.');
                                echo json_encode($resultArray); exit;
                            }
                               
                 }


 /* ------------------------------------------------------------------------------------------------ */

        public function getNewOpportunities(Request $request)
        {

            $access_token=123456;
             $allData=array();
            $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

             $validator = Validator::make($request->all(), [
                    'userid' => 'required',
                    'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                if(!empty($userid) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                        if($check_auth['status']!=1)
                        {
                         echo json_encode($check_auth); exit;
                        }
                        else
                        {
                            $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($userEntity))
                            {

                            $servicesOffered=DB::table('services_offered')->select('service_id')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                if(!empty($servicesOffered))
                                {
                                    $contractorServices=array();
                                    foreach ($servicesOffered as $val) 
                                    {
                                      $contractorServices[]=$val->service_id;
                                    }

                                    // $servicesRequested=DB::table('service_request')->select('id','service_id','location','username','status','mobile_verify','created_at')->where('mobile_verify',1)->where('status','0')->whereRaw("(deleted_at IS null )")->whereIN('service_id',$contractorServices)->get();

                                      $servicesRequested = DB::table('service_request')
                                        ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                                        ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.mobile_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image', 'services.price')
                                       ->where('service_request.mobile_verify',1)->where('service_request.status','0')->whereRaw("(service_request.deleted_at IS null )")->whereIN('service_request.service_id',$contractorServices)->get(); 


                                   if(!empty($servicesRequested))
                                   {

                                    $data1=array();
                                    foreach ($servicesRequested as $key => $vall) 
                                    {

                                        $data1['id'] = $vall->id;
                                        $data1['service_id'] = $vall->service_id;
                                         if($lang=='es')
                                                {$service_name=$vall->es_name;}
                                            else{$service_name=$vall->en_name;}

                                        $data1['service_name'] = $service_name;
                                        $data1['service_image'] = url('/img/'.$vall->image);
                                        $data1['service_price'] = $vall->price;
                                        $data1['location'] = $vall->location;
                                        $data1['username'] = $vall->username;
                                        $data1['status'] = $vall->status;
                                        $data1['mobile_verify'] = $vall->mobile_verify;
                                        $data1['created_at'] = $vall->created_at;

                                        //$servicesRequestedQues=DB::table('service_request_questions')->select('id','service_request_id','question_id','option_id')->where('service_request_id',$vall->id)->get()->toArray();

                                        $servicesRequestedQues = DB::table('service_request_questions')
                                        ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                                         ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                                        ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option')
                                        ->whereRaw("(service_request_questions.service_request_id = '".$vall->id."')")->get()->toArray(); 

                                        $options=array();

                                        foreach ($servicesRequestedQues as $key => $que) 
                                        {
                                           $data2['id'] = $que->id;
                                           $data2['service_request_id'] = $que->service_request_id;
                                           $data2['question_id'] = $que->question_id;

                                            if($lang=='es')
                                                {$question=$que->es_title;}
                                            else{$question=$que->en_title;}

                                           $data2['question'] = $question;

                                           $data2['option_id'] = $que->option_id;

                                            if($lang=='es')
                                                {$option=$que->es_option;}
                                            else{$option=$que->en_option;}

                                           $data2['option'] = $option;

                                            array_push($options, $data2);
                                        }

                                        $data1['question_options']=$options ;
                                        array_push($allData, $data1);

                                    }
                                    
                                        $resultArray['status']='1';   
                                        $resultArray['message']=trans('Opportunities List Found Successfully.!');
                                        $resultArray['data'] = $allData; 
                                        echo json_encode($resultArray); exit;

                                   }
                                   else
                                   {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Opportunities not found.');
                                    echo json_encode($resultArray); exit;
                                   }

                                }
                                else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Please update your profile for your offerd services.');
                                    echo json_encode($resultArray); exit;  
                                }
                                
                            }
                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid user.');
                                echo json_encode($resultArray); exit;  
                            }

                        }
                    } 
                    else 
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameter.');
                        echo json_encode($resultArray); exit;
                    }

        }

/* ------------------------------------------------------------------------------------------------ */
                
                
        /* --------------------signOut Api Start-------------------- */

        public function signOut(Request $request)
        {
            $access_token=123456;
            $id = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

            if(!empty($id))
            {

                $users_count_var = DB::table('users')->whereRaw("(id = '".$id."' AND deleted_at IS null)")->first(); 

                if(isset($users_count_var) && !empty($users_count_var))
                {
                    $check_auth = $this->checkToken($access_token, $users_count_var->id);

                    if($check_auth['status']!=1)
                    {
                        echo json_encode($check_auth); exit;
                    }
                    else
                    {

                        $deviceId = isset($request->device_id) && !empty($request->device_id) ? $request->device_id : '' ;

                        if(isset($users_count_var->id) && !empty($users_count_var->id))
                        {
                           
                            $update_Arr['session_key'] = '';
                            DB::table('mobile_session')->where('user_id', $users_count_var->id)->update($update_Arr);

                            if(!empty($deviceId))
                            {
                                DB::table('user_devices')->where('device_id', '=', $deviceId)->where('user_id', '=', $users_count_var->id)->delete();
                            }
                            
                            $resultArray['status']='1';     
                            $resultArray['message']=__('signOut successfully.');
                            echo json_encode($resultArray); exit;
                        }

                    }               
                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=__('Invalid Email.');
                    echo json_encode($resultArray); exit;
                }
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=__('Invalid parameters.');
                echo json_encode($resultArray); exit;
            }
        }

        /* --------------------signOut Api END-------------------- */



        /* --------------------Opportunity Buy Api Start-------------------- */


        public function buyOpportunity(Request $request)
        {


            $access_token=123456;
            $allData=array();
            $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            $user_group_id = !empty($request->user_group_id) ? $request->user_group_id : '' ;
            $opportunity_id = !empty($request->opportunity_id) ? $request->opportunity_id : '' ;
            $tranx_id = !empty($request->tranx_id) ? $request->tranx_id : '' ;
            $tranx_status = !empty($request->tranx_status) ? $request->tranx_status : '' ;
            $currency = !empty($request->currency) ? $request->currency : '' ;
            $amount = !empty($request->amount) ? $request->amount : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

             $validator = Validator::make($request->all(), [
                    'userid' => 'required',
                    'session_key' => 'required',
                    'user_group_id' => 'required',
                    'opportunity_id' => 'required',
                    'tranx_status' => 'required',
                    'amount' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 
 
  
                 if(!empty($userid) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                        if($check_auth['status']!=1)
                        {
                         echo json_encode($check_auth); exit;
                        }
                        else
                        {

                             $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($userEntity))
                            {

                             $opportunity = DB::table('service_request')
                            ->whereRaw("(id = '".$opportunity_id."' AND deleted_at IS null )")
                            ->first();

                                if($opportunity)
                                {

                                    if($opportunity->status!=='0')
                                    {

                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Requested opportunity is already recived by another professionals.');
                                        echo json_encode($resultArray); exit;

                                    }else
                                    {

                                        $insert['user_id'] = $userid;    
                                        $insert['requested_service_id'] = $opportunity_id;
                                        $insert['tranx_id'] = $tranx_id;
                                        $insert['tranx_status'] = $tranx_status;
                                        $insert['currency'] = $currency;
                                        $insert['amount'] = $amount;
                                        $insert['created_at'] = Carbon::now();  
                                        $lastId=DB::table('buy_requested_services')->insertGetId($insert);

                                        if($tranx_status=='1')
                                        {
                                            $update_Arr['status'] = '1';
                                            DB::table('service_request')->where('id', $opportunity_id)->update($update_Arr);

                                            $resultArray['status']='1';
                                            $resultArray['message']=trans('Opportunity Buy Successfully.!');
                                            echo json_encode($resultArray); exit;
                                        }
                                        else
                                        {
                                            $resultArray['status']='0';
                                            $resultArray['message']=trans('Payment Failed.!');
                                            echo json_encode($resultArray); exit;  
                                        }

                                    }

                                }else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Requested opportunity id not found.!');
                                    echo json_encode($resultArray); exit;
                                }


                            }
                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid user.');
                                echo json_encode($resultArray); exit;
                            }


                        }
                    }
                    else 
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameter.');
                        echo json_encode($resultArray); exit;
                    }
  



        }


        

        /* --------------------Opportunity Buy Api END-------------------- */

        /* --------------------Job LIST Api Start-------------------- */


        public function jobList(Request $request)
        {
            $access_token=123456;
            $allData=array();
            $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

            $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'session_key' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 

              if(!empty($userid) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                        if($check_auth['status']!=1)
                        {
                         echo json_encode($check_auth); exit;
                        }
                        else
                        {
                            $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($userEntity))
                            {
                                 $servicesbuy = DB::table('buy_requested_services')
                                ->leftjoin('service_request', 'buy_requested_services.requested_service_id', '=', 'service_request.id')
                                ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                                ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.mobile_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image', 'services.price','buy_requested_services.tranx_status','buy_requested_services.tranx_id')
                                ->where('buy_requested_services.user_id',$userid)
                                ->where('buy_requested_services.tranx_status','1')
                                ->where('service_request.status','1')
                                ->whereRaw("(service_request.deleted_at IS null )")->get();



                                 if(!empty($servicesbuy))
                                   {

                                    $data1=array();
                                    foreach ($servicesbuy as $key => $vall) 
                                    {

                                        $data1['id'] = $vall->id;
                                        $data1['service_id'] = $vall->service_id;
                                         if($lang=='es')
                                                {$service_name=$vall->es_name;}
                                            else{$service_name=$vall->en_name;}

                                        $data1['service_name'] = $service_name;
                                        $data1['service_image'] = url('/img/'.$vall->image);
                                        $data1['service_price'] = $vall->price;
                                        $data1['location'] = $vall->location;
                                        $data1['username'] = $vall->username;
                                        $data1['status'] = $vall->status;
                                        $data1['mobile_verify'] = $vall->mobile_verify;
                                        $data1['tranx_id'] = $vall->tranx_id;
                                        $data1['tranx_status'] = $vall->tranx_status;
                                        $data1['created_at'] = $vall->created_at;



                                        $servicesRequestedQues = DB::table('service_request_questions')
                                        ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                                         ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                                        ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option')
                                        ->whereRaw("(service_request_questions.service_request_id = '".$vall->id."')")->get()->toArray(); 

                                        $options=array();

                                        foreach ($servicesRequestedQues as $key => $que) 
                                        {
                                           $data2['id'] = $que->id;
                                           $data2['service_request_id'] = $que->service_request_id;
                                           $data2['question_id'] = $que->question_id;

                                            if($lang=='es')
                                                {$question=$que->es_title;}
                                            else{$question=$que->en_title;}

                                           $data2['question'] = $question;

                                           $data2['option_id'] = $que->option_id;

                                            if($lang=='es')
                                                {$option=$que->es_option;}
                                            else{$option=$que->en_option;}

                                           $data2['option'] = $option;

                                            array_push($options, $data2);
                                        }

                                        $data1['question_options']=$options ;
                                        array_push($allData, $data1);

                                    }

                                    $resultArray['status']='1';   
                                    $resultArray['message']=trans('Job List Found Successfully.!');
                                    $resultArray['data'] = $allData; 
                                    echo json_encode($resultArray); exit;
                                }

                             else
                               {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('List no found.!');
                                    echo json_encode($resultArray); exit;
                               }
       
                            }
                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid user.');
                                echo json_encode($resultArray); exit; 
                            }

                        }
                    }else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameter.');
                        echo json_encode($resultArray); exit;
                    }

        }


         /* --------------------Job LIST Api End-------------------- */


         /********************  END  **************************/

}
