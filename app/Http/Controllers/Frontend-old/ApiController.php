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
use Illuminate\Support\Facades\Storage;
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
            $type = isset($request->user_type) && !empty($request->user_type) ? $request->user_type : '' ;

            $resultArray = [];
            App::setLocale($lang);

            if(!empty($email) && !empty($password) && !empty($device_id) && !empty($device_type))
            {

              $users_count_var="";
              if(Auth::attempt(['email' => $email, 'password' => $password, 'user_group_id' =>[2,3,4]]))
                { 
                    //$users_count_var = Auth::user();
                    if(!empty($type))
                    {
                         $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at')->whereRaw("(user_group_id = '".$type."')")->whereRaw("(email = '".$email."')")->first(); 
                     }else
                     {
                        $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at')->whereRaw("(email = '".$email."')")->first();
                     }
                    
                    
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
  
 /* ------------------------------------------------------------------------------------------------ */

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

            $msg = trans('apimessage.Your account has been created successfully.');


            $userdevice['user_id'] = $userId;
            $userdevice['device_id'] = $device_id;
            $userdevice['device_type'] = $device_type;
            DB::table('user_devices')->insert($userdevice);

            $socialNetw['user_id'] = $userId;
            $socialNetw['created_at'] = Carbon::now()->toDateTimeString();
            DB::table('social_networks')->insert($socialNetw);

                if($type==3 || $type==4)
                {
                    $addCredits['user_id'] = $userId;
                    $addCredits['transaction_date'] = Carbon::now()->toDateTimeString();
                    $addCredits['debit'] = '20';
                    $addCredits['credit'] = '0';
                    $addCredits['current_balance'] = '20';
                    $addCredits['updated_at'] = Carbon::now()->toDateTimeString();
                    $addCreditsToContractor = DB::table('bonus')->insert($addCredits);
        
                    $msg=trans('Your account has been created successfully., and you have recived bonus of 20 credits.!');
                }



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
                $resultArray['message']=$msg;
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

                        $addCredits['user_id'] = $getuser->id;
                        $addCredits['transaction_date'] = Carbon::now()->toDateTimeString();
                        $addCredits['debit'] = '20';
                        $addCredits['credit'] = '0';
                        $addCredits['current_balance'] = '20';
                        $addCredits['updated_at'] = Carbon::now()->toDateTimeString();
                        $addCreditsToContractor = DB::table('bonus')->insert($addCredits);
            
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
          * CATEGORY LIST API START HERE
          */
        public function getAllCategoryList(Request $request)
        {
            $access_token=123456;
            $categoryArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            App::setLocale($lang);
             $categories = DB::table('category')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(deleted_at IS null)")
                            ->get(); 
            foreach($categories as $category) 
            {
                if($lang=='es'){$name=$category->es_name;}else{$name=$category->en_name;}
               $image= url('/img/'.$category->image);
               array_push($categoryArray,array('id'=>$category->id, 'name'=>$name,'image'=>$image,'status'=>$category->status,'created_at'=>$category->created_at));

            }
            if($categories && !empty($categoryArray))
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('category list found successfully.!');
                $resultArray['data']=$categoryArray;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('Data not found.!');
                echo json_encode($resultArray); exit;
            }
        }
         /*
          * CATEGORY LIST API END HERE
          */

/* ------------------------------------------------------------------------------------------------ */

         /*
          *
          * GET All SERVICE LIST API START HERE
          */
        public function getAllServiceList(Request $request)
        {
            $access_token=123456;
            $serviceArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            $category_id = !empty($request->category_id) ? $request->category_id : '';
            App::setLocale($lang);

             $serv = DB::table('services')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(deleted_at IS null)")
                            ->where('category_id',$category_id)
                            ->get(); 
            foreach($serv as $service) 
            {
                if($lang=='es'){$name=$service->es_name;}else{$name=$service->en_name;}
               $image= url('/img/'.$service->image);
               array_push($serviceArray,array('id'=>$service->id,'category_id'=>$service->category_id, 'name'=>$name,'image'=>$image,'status'=>$service->status,'created_at'=>$service->created_at));

            }
            if($serv && !empty($serviceArray))
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('service list found successfully.!');
                $resultArray['data']=$serviceArray;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('Data not found.!');
                echo json_encode($resultArray); exit;
            }
        }
         
/* ------------------------------------------------------------------------------------------------ */

         /*
          *
          * GET All SERVICE LIST FOR HOME PAGE API START HERE
          */
        public function getAllHomePageServiceList(Request $request)
        {
            $access_token=123456;
            $serviceArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            
            App::setLocale($lang);

             $serv = DB::table('services')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(deleted_at IS null)")
                            ->get(); 
            foreach($serv as $service) 
            {
                if($lang=='es'){$name=$service->es_name;}else{$name=$service->en_name;}
               $image= url('/img/'.$service->image);
               array_push($serviceArray,array('id'=>$service->id,'category_id'=>$service->category_id, 'name'=>$name,'image'=>$image,'status'=>$service->status,'type' => 'service','created_at'=>$service->created_at));
 
            }
            if($serv && !empty($serviceArray))
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('all services list found successfully.!');
                $resultArray['data']=$serviceArray;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('Data not found.!');
                echo json_encode($resultArray); exit;
            }
        }
         

/* ------------------------------------------------------------------------------------------------ */

          /*
          *
          * SUB SERVICE LIST API START HERE
          */
        public function getAllSubServiceList(Request $request)
        {
            $access_token=123456;
            $subServiceArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            $service_id = !empty($request->service_id) ? $request->service_id : '';
            App::setLocale($lang);
             $subServices = DB::table('sub_services')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(deleted_at IS null)")
                            ->where('services_id',$service_id)
                            ->get(); 
            foreach($subServices as $subService) 
            {
                if($lang=='es'){$name=$subService->es_name;}else{$name=$subService->en_name;}
               $image= url('/img/'.$subService->image);
               array_push($subServiceArray,array('id'=>$subService->id,'services_id'=>$subService->services_id, 'name'=>$name,'image'=>$image,'status'=>$subService->status,'created_at'=>$subService->created_at));

            }
            if($subServices && !empty($subServiceArray))
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('sub service list found successfully.!');
                $resultArray['data']=$subServiceArray;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('Data not found.!');
                echo json_encode($resultArray); exit;
            }
        }
         /*
          * SUB SERVICE LIST API END HERE
          */


    /* ------------------------------------------------------------------------------------------------ */

          /*
          *
          * CHILD SUB SERVICE LIST API START HERE
          */
        public function getAllChildSubServiceList(Request $request)
        {
            $access_token=123456;
            $childArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            $sub_services_id = !empty($request->sub_services_id) ? $request->sub_services_id : '';
            App::setLocale($lang);
             $childSubServices = DB::table('child_sub_services')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(deleted_at IS null)")
                            ->where('sub_services_id',$sub_services_id)
                            ->get(); 
            foreach($childSubServices as $child) 
            {
                if($lang=='es'){$name=$child->es_name;}else{$name=$child->en_name;}
               $image= url('/img/'.$child->image);
               array_push($childArray,array('id'=>$child->id,'sub_services_id'=>$child->sub_services_id,'name'=>$name,'image'=>$image,'status'=>$child->status,'created_at'=>$child->created_at));

            }
            if($childSubServices && !empty($childArray))
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('child sub service list found successfully.!');
                $resultArray['data']=$childArray;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('Data not found.!');
                echo json_encode($resultArray); exit;
            }
        }
         /*
          * CHILD SUB SERVICE LIST API END HERE
          */



    /* ------------------------------------------------------------------------------------------- */
        
     /*
      **** GET QUESTIONS LIST BY CATEGORY,SERVICE,SUBSERVICE,CHILDSERVICE ID || USER SIDE API ****
      * QUESTIONS LIST API START HERE
      */

         public function getQuestionnaireByTypeId(Request $request)
         {
                $access_token=123456;
                $allData=array();$arr=array();$arr2=array();
                //Type Id means (CategoryId,ServiceId,SubServiceId,ChildSubServiceId)
                $type_id = !empty($request->type_id) ? $request->type_id : '' ;
                $type = !empty($request->type) ? $request->type : '' ;

                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                    App::setLocale($lang);

                    $validator = Validator::make($request->all(), [
                    'type_id' => 'required',
                    'type' => 'required',
                    ]);

                    if($validator->fails())
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    }  

                if(!empty($type_id) && !empty($type))
                {

                        $catId = NULL; $servId = NULL; $subServId = NULL; $childSubServId = NULL;
                        $questionEntity="";
                               if($type=='child_sub_service')
                               {
                                    //Get 
                                     $getallTypeId = DB::table('child_sub_services')
                                    ->select('category_id','services_id','sub_services_id')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(id = '".$type_id."' AND deleted_at IS null )")
                                    ->first();

                                    if(!empty($getallTypeId))
                                    {
                                       $catId = $getallTypeId->category_id;
                                       $servId =  $getallTypeId->services_id;
                                       $subServId = $getallTypeId->sub_services_id;
                                       $childSubServId = $type_id;
                                    }


                                     $questionEntity = DB::table('questions')
                                    ->whereRaw("(questions.status=1)")
                                    ->whereRaw("(questions.question_order=1)")
                                    ->whereRaw("(questions.is_related=0)")
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."')")
                                    ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                                    ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                                    ->get();

                               }

                               if($type=='sub_service')
                               {
                                    //Get 
                                    $getallTypeId = DB::table('sub_services')
                                    ->select('category_id','services_id')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(id = '".$type_id."' AND deleted_at IS null )")
                                    ->first();


                                    if(!empty($getallTypeId))
                                    {
                                       $catId = $getallTypeId->category_id;
                                       $servId =  $getallTypeId->services_id;
                                       $subServId = $type_id;
                                    }

                                    $questionEntity = DB::table('questions')
                                    ->whereRaw("(questions.status=1)")
                                    ->whereRaw("(questions.question_order=1)")
                                    ->whereRaw("(questions.is_related=0)")
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."')")
                                    ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                                    ->get();
                               }

                               if($type=='service')
                               {
                                    //Get 
                                    $getallTypeId = DB::table('services')
                                    ->select('category_id')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(id = '".$type_id."' AND deleted_at IS null )")
                                    ->first();

                                    if(!empty($getallTypeId))
                                    {
                                       $catId = $getallTypeId->category_id;
                                       $servId =  $type_id;
                                    }

                                     $questionEntity = DB::table('questions')
                                    ->whereRaw("(questions.status=1)")
                                    ->whereRaw("(questions.question_order=1)")
                                    ->whereRaw("(questions.is_related=0)")
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                                    ->get();

                               }
                               if($type=='category')
                               {
                                    //Get 
                                    $getallTypeId = DB::table('services')
                                    ->select('category_id')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(id = '".$type_id."' AND deleted_at IS null )")
                                    ->first();

                                    $catId =  $type_id;

                                     $questionEntity = DB::table('questions')
                                    ->whereRaw("(questions.status=1)")
                                    ->whereRaw("(questions.question_order=1)")
                                    ->whereRaw("(questions.is_related=0)")
                                    ->whereRaw("(questions.category_id = '".$catId."' AND deleted_at IS null)")
                                    ->get();

                               }
                             
                            foreach ($questionEntity as $question) 
                            {
                                $arr['question_id'] =  isset($question) && !empty($question->id) ? (string)$question->id : '' ;

                                $arr['category_id'] =  isset($question) && !empty($question->category_id) ? (string)$question->category_id : '' ;

                                $arr['services_id'] =  isset($question) && !empty($question->services_id) ? (string)$question->services_id : '' ;

                                $arr['sub_services_id'] =  isset($question) && !empty($question->sub_services_id) ? (string)$question->sub_services_id : '' ;

                                $arr['child_sub_service_id'] =  isset($question) && !empty($question->child_sub_service_id) ? (string)$question->child_sub_service_id : '' ;

                                 $arr['question_type'] =  isset($question) && !empty($question->question_type) ? (string)$question->question_type : '' ;

                                 $arr['is_related'] =  isset($question) && !empty($question->is_related) ? (string)$question->is_related : '' ;

                                 $arr['question_order'] =  isset($question) && !empty($question->question_order) ? (string)$question->question_order : '' ;

                                 if($lang=='en')
                                 {
                                   $arr['question']=isset($question) && !empty($question->en_title) ? (string)$question->en_title : '' ; 
                                 }else
                                 {
                                 $arr['question']=isset($question) && !empty($question->es_title) ? (string)$question->es_title : '' ; 
                                 }
                                  $arr['related_question_id'] =  isset($question) && !empty($question->related_question_id) ? $question->related_question_id : '' ;

                                   $arr['related_option_id'] =  isset($question) && !empty($question->related_option_id) ? $question->related_option_id : '' ;

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

                            if(!empty($allData) && count($allData) > 0)
                                {
                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('Data found Successfully.!');
                                    $resultArray['data']=$allData;
                                    echo json_encode($resultArray); exit;
                                } else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Data not found.!');
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
          * ************ GET QUESTIONS LIST BY CATEGORY ID *********************
          *
          * QUESTIONS LIST API END HERE
          */



          /*
          * ************ GET QUESTIONS LIST BY OPTION ID *********************
          *
          * RELATED QUESTIONS LIST API START HERE
          */


         public function getQuestionnaireByOptionId(Request $request)
         {
                $access_token=123456;
                $allData=array();$arr=array();$arr2=array();
                //Type Id means (CategoryId,ServiceId,SubServiceId,ChildSubServiceId)
                $type_id = !empty($request->type_id) ? $request->type_id : '' ;
                $type = !empty($request->type) ? $request->type : '' ;
                $question_id = !empty($request->question_id) ? $request->question_id : '' ;
                $option_id = !empty($request->option_id) ? $request->option_id : '' ;

                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                    App::setLocale($lang);

                    $validator = Validator::make($request->all(), [
                    'type_id' => 'required',
                    'type' => 'required',
                    'question_id' => 'required',
                    'option_id' => 'required',
                    ]);

                    if($validator->fails())
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    }  

                if(!empty($type_id) && !empty($type) && !empty($question_id) && !empty($option_id))
                {

                             $catId = NULL; $servId = NULL; $subServId = NULL; $childSubServId = NULL;
                             $questionEntity="";

                               if($type=='child_sub_service')
                               {
                                    //Get 
                                     $getallTypeId = DB::table('child_sub_services')
                                    ->select('category_id','services_id','sub_services_id')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(id = '".$type_id."' AND deleted_at IS null )")
                                    ->first();

                                    if(!empty($getallTypeId))
                                    {
                                       $catId = $getallTypeId->category_id;
                                       $servId =  $getallTypeId->services_id;
                                       $subServId = $getallTypeId->sub_services_id;
                                       $childSubServId = $type_id;
                                    }

                                    $questionEntity = DB::table('questions')
                                    ->whereRaw("(questions.status=1)")
                                    //->whereRaw("(questions.is_related=1)")
                                    ->whereRaw("(related_question_id = '".$question_id."')")
                                    ->whereRaw("(related_option_id = '".$option_id."')")
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."')")
                                    ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                                    ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                                    ->get();

                               }

                               if($type=='sub_service')
                               {
                                    //Get 
                                    $getallTypeId = DB::table('sub_services')
                                    ->select('category_id','services_id')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(id = '".$type_id."' AND deleted_at IS null )")
                                    ->first();


                                    if(!empty($getallTypeId))
                                    {
                                       $catId = $getallTypeId->category_id;
                                       $servId =  $getallTypeId->services_id;
                                       $subServId = $type_id;
                                    }

                                    $questionEntity = DB::table('questions')
                                    ->whereRaw("(questions.status=1)")
                                    //->whereRaw("(questions.is_related=1)")
                                    ->whereRaw("(related_question_id = '".$question_id."')")
                                    ->whereRaw("(related_option_id = '".$option_id."')")
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."')")
                                    ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                                    ->get();
                               }

                               if($type=='service')
                               {
                                    //Get 
                                    $getallTypeId = DB::table('services')
                                    ->select('category_id')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(id = '".$type_id."' AND deleted_at IS null )")
                                    ->first();

                                    if(!empty($getallTypeId))
                                    {
                                       $catId = $getallTypeId->category_id;
                                       $servId =  $type_id;
                                    }

                                    $questionEntity = DB::table('questions')
                                    ->whereRaw("(questions.status=1)")
                                    //->whereRaw("(questions.is_related=1)")
                                    ->whereRaw("(related_question_id = '".$question_id."')")
                                    ->whereRaw("(related_option_id = '".$option_id."')")
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                                    ->get();
                               }
                               if($type=='category')
                               {
                                    //Get 
                                    $getallTypeId = DB::table('services')
                                    ->select('category_id')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(id = '".$type_id."' AND deleted_at IS null )")
                                    ->first();

                                    $catId =  $type_id;

                                    $questionEntity = DB::table('questions')
                                    ->whereRaw("(questions.status=1)")
                                    //->whereRaw("(questions.is_related=1)")
                                    ->whereRaw("(related_question_id = '".$question_id."')")
                                    ->whereRaw("(related_option_id = '".$option_id."')")
                                    ->whereRaw("(questions.category_id = '".$catId."' AND deleted_at IS null)")
                                    ->get();
                               }

                        if(!empty($questionEntity) && count($questionEntity) > 0)
                        {
                            
                            foreach ($questionEntity as $question) 
                            {
                                $arr['question_id'] =  isset($question) && !empty($question->id) ? (string)$question->id : '' ;

                                $arr['category_id'] =  isset($question) && !empty($question->category_id) ? (string)$question->category_id : '' ;

                                $arr['services_id'] =  isset($question) && !empty($question->services_id) ? (string)$question->services_id : '' ;

                                $arr['sub_services_id'] =  isset($question) && !empty($question->sub_services_id) ? (string)$question->sub_services_id : '' ;

                                $arr['child_sub_service_id'] =  isset($question) && !empty($question->child_sub_service_id) ? (string)$question->child_sub_service_id : '' ;

                                 $arr['question_type'] =  isset($question) && !empty($question->question_type) ? (string)$question->question_type : '' ;

                                 $arr['is_related'] =  isset($question) && !empty($question->is_related) ? (string)$question->is_related : '' ;

                                 $arr['question_order'] =  isset($question) && !empty($question->question_order) ? (string)$question->question_order : '' ;

                                 if($lang=='en')
                                 {
                                   $arr['question']=isset($question) && !empty($question->en_title) ? (string)$question->en_title : '' ; 
                                 }else
                                 {
                                 $arr['question']=isset($question) && !empty($question->es_title) ? (string)$question->es_title : '' ; 
                                 }
                                  $arr['related_question_id'] =  isset($question) && !empty($question->related_question_id) ? $question->related_question_id : '' ;

                                   $arr['related_option_id'] =  isset($question) && !empty($question->related_option_id) ? $question->related_option_id : '' ;

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

                               if(!empty($allData) && count($allData) > 0)
                                {
                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('Data found Successfully.!');
                                    $resultArray['data']=$allData;
                                    echo json_encode($resultArray); exit;
                                } else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Data not found.!');
                                    echo json_encode($resultArray); exit;
                                }
                    }else
                    {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('Data not found.!');
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
          * ************ GET QUESTIONS LIST BY OPTION ID *********************
          *
          * RELATED QUESTIONS LIST API END HERE
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

                $category_id = !empty($request->category_id) ? $request->category_id : NULL ;
                $service_id = !empty($request->service_id) ? $request->service_id : NULL ;
                $sub_service_id = !empty($request->sub_service_id) ? $request->sub_service_id : NULL ;
                $child_sub_service_id = !empty($request->child_sub_service_id) ? $request->child_sub_service_id : NULL ;

                $ques_options = !empty($request->ques_options) ? $request->ques_options : '' ;

                //$ques_options=[{"question_id":"1","option_id":"2"},{"question_id":"1","option_id":"4"},{"question_id":"2","option_id":"1"}];

                $service_location = !empty($request->service_location) ? $request->service_location : '' ;
                $latitude = !empty($request->latitude) ? $request->latitude : '0' ;
                $longitude = !empty($request->longitude) ? $request->longitude : '0' ;
                $username = !empty($request->username) ? $request->username : '' ;
                $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                $email = !empty($request->email) ? $request->email : '' ;
                //$otp = !empty($request->otp) ? $request->otp : '1111' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                $city_id = !empty($request->city_id) ? $request->city_id : '0' ;



                $variable_fields_data = json_decode($ques_options);
                $queOptionData = json_decode( json_encode($variable_fields_data), true);
           
                    App::setLocale($lang);

                    $validator = Validator::make($request->all(), [
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



                            if(empty($category_id) && !empty($service_id))
                            {
                                    //Get CategoryId GFrom Service Table
                                   $getCatId=DB::table('services')
                                  ->select('category_id')
                                  ->where('id',$service_id)->first(); 
                                   
                                    if(!empty($getCatId))
                                    {
                                      $category_id = $getCatId->category_id;
                                    }

                            }
                             if(empty($category_id) && empty($service_id) && !empty($sub_service_id))
                            {

                                  //Get CategoryId AND ServiceId From SubService Table

                                   $getCatServiceId=DB::table('sub_services')
                                  ->select('category_id','services_id')
                                  ->where('id',$sub_service_id)->first(); 

                                    if(!empty($getCatServiceId))
                                    {
                                      $category_id = $getCatServiceId->category_id;
                                      $service_id = $getCatServiceId->services_id;

                                    }
                            }
                            if(empty($category_id) && empty($service_id) && empty($sub_service_id) && !empty($child_sub_service_id))
                            {
                                //Get CategoryId AND ServiceId AND SubServiceID From child_sub_service_id Table
                                   $getCatServiceSubServiceId=DB::table('child_sub_services')
                                  ->select('category_id','services_id','sub_services_id')
                                  ->where('id',$child_sub_service_id)->first(); 

                                    if(!empty($getCatServiceSubServiceId))
                                    {
                                      $category_id = $getCatServiceSubServiceId->category_id;
                                      $service_id = $getCatServiceSubServiceId->services_id;
                                      $child_sub_service_id = $getCatServiceSubServiceId->sub_services_id;
                                    }
                            }



                            $serviceReqData['user_id'] =  $userid;
                            $serviceReqData['category_id'] =  !empty($category_id) ? $category_id : NULL;
                            $serviceReqData['service_id'] =  !empty($service_id) ? $service_id : NULL;
                            $serviceReqData['sub_service_id'] =  !empty($sub_service_id) ? $sub_service_id : NULL;
                            $serviceReqData['child_sub_service_id'] =  !empty($child_sub_service_id) ? $child_sub_service_id : NULL;

                            $serviceReqData['city_id'] =  $city_id;
                            $serviceReqData['location'] =  $service_location;
                            $serviceReqData['latitude'] =  $latitude;
                            $serviceReqData['longitude'] =  $longitude;
                            $serviceReqData['username'] =  $username;
                            $serviceReqData['mobile_number'] =  $mobile_number;
                            $serviceReqData['email'] =  $email;
                            $serviceReqData['otp'] =  $otpcode;
                            //$serviceReqData['email_verify'] =  1;
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

                 $resultArray['message']=trans('Congratulations!!! We are almost done, we have sent a validation code to your email account.');
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
                           if($servicereq->email_verify==1)
                           {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('Your OTP already used for this service.');
                            echo json_encode($resultArray); exit; 
                           }
                           if($servicereq->otp==$otp AND $servicereq->email_verify==0)
                           {

                                $update_Arr['email_verify'] = 1;
                                $update_Arr['updated_at'] = Carbon::now()->toDateTimeString();                  
                                if(DB::table('service_request')->where('id', $request_id)->update($update_Arr))
                                {


                                    $this->sendApportunityNotification($request_id);

                                    $getData=array();
                                    $getData = DB::table('service_request')->select('id','user_id','service_id','location','username','mobile_number','email','email_verify AS otp_verified', 'status','created_at','updated_at')->whereRaw("(id = '".$servicereq->id."')")->first();

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


         //Send Req. to all contractor amnd Company
         public function sendApportunityNotification($request_id)
            {
                // $request_id = isset($request->request_id) && !empty($request->request_id) ? $request->request_id : '' ;
                if(!empty($request_id))
                {
                    
                  $servicereq = DB::table('service_request')
                  ->whereRaw("(status = '0')")
                  ->whereRaw("(id = '".$request_id."' AND deleted_at IS null )")
                  ->first();

                  if($servicereq)
                  {
                    $city_id=$servicereq->city_id;

                //Get All Contractor and company, according to user service Request city and contractor & company service area and according their free slot.

                  $getAllContCompny = DB::table('users')
                  ->join('services_offered', 'users.id', '=', 'services_offered.user_id')
                  ->leftjoin('users_services_area', 'users.id', '=', 'users_services_area.user_id')
                  //->leftjoin('service_request', 'users.id', '=', 'service_request.assigned_user_id')
                  ->select('users.id','users.ruc_no','users.username','users.address','users_services_area.whole_country','users_services_area.province_id','users_services_area.city_id','users.created_at')
                  ->whereIN('users_services_area.city_id',[$city_id])
                  ->whereIN('users.user_group_id',[3,4])
                  ->whereIN('services_offered.service_id',[$servicereq->service_id])
                  //->whereRaw('users_services_area.status',4)
                  ->whereRaw("(users.deleted_at IS null )")
                  ->groupBy('services_offered.user_id')->get();


                    if(!empty($getAllContCompny))
                    { 
                        foreach ($getAllContCompny as $key => $getuser) 
                        {
                            $insert['user_id'] = $getuser->id;    
                            $insert['service_request_id'] = $request_id;
                            $insert['created_at'] = Carbon::now();  
                            DB::table('assign_service_request')->insertGetId($insert);
                        }

                        return true;
                    }else
                    {
                        return false;
                    }

                  }else
                  {
                    return false;
                  }
                }else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('Invali Request ID');
                    echo json_encode($resultArray); exit;
                }
                 

            }

         /*
          * ************ GET REQUEST LIST OF USER *********************
          *
          * GET REQUEST LIST API START HERE
          */

           public function getRequestList(Request $request)
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
                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                        if($check_auth['status']!=1)
                        {
                         echo json_encode($check_auth); exit;
                        }else
                        {

                             $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(empty($userEntity))
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid user.');
                                echo json_encode($resultArray); exit; 
                            }

                            $allData=array();
                            $servicesRequested = DB::table('service_request')
                            ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                            ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image')
                            ->where('service_request.status','0')->where('service_request.user_id',$userid)->whereRaw("(service_request.deleted_at IS null )")->orderBy('service_request.id', 'DESC')->get(); 


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



                                        $data1['service_name'] = isset($service_name) && !empty($service_name) ? $service_name : '';
                                        $data1['service_image'] = url('/img/'.$vall->image);
                                        $data1['location'] = $vall->location;
                                        $data1['username'] = $vall->username;
                                        $data1['status'] = $vall->status;
                                        $data1['email_verify'] = $vall->email_verify;
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

                                           $data2['question'] = isset($question) && !empty($question) ? $question : '';

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

                                    if(!empty($allData))
                                    {
                                        $resultArray['status']='1';   
                                        $resultArray['message']=trans('Request List Found Successfully.!');
                                        $resultArray['data'] = $allData; 
                                        echo json_encode($resultArray); exit;   
                                    }
                                    else
                                    {
                                        $resultArray['status']='0';   
                                        $resultArray['message']=trans('Request List Not Found.!');
                                        echo json_encode($resultArray); exit;   
                                    }
                                    
                                        

                                   }
                                   else
                                   {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Request not found.');
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
          * ************ GET REQUEST LIST OF USER *********************
          *
          * GET REQUEST LIST OF USER API END HERE
          */

 /*--------------------------------------------------------------------------------------*/


        /*
          * ************ GET REQUEST DETAIL API OF USER *********************
          *
          * GET REQUEST DETAIL API OF USER API START HERE
          */

           public function getRequestDetail(Request $request)
           {

                $access_token=123456;
                $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
                $request_id = isset($request->request_id) && !empty($request->request_id) ? $request->request_id : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                 App::setLocale($lang);

                    $validator = Validator::make($request->all(), [
                    'userid' => 'required',
                    'session_key' => 'required',
                    'request_id' => 'required',
                    ]);

                    if($validator->fails())
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    } 


                     if(!empty($userid) && !empty($session_key) && !empty($request_id)) 
                    {
                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                        if($check_auth['status']!=1)
                        {
                         echo json_encode($check_auth); exit;
                        }else
                        {

                             $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(empty($userEntity))
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Invalid user.');
                                echo json_encode($resultArray); exit; 
                            }

                            $allData=array();
                            $servicesRequested = DB::table('service_request')
                            ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                            ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image')
                               ->where('service_request.id',$request_id)->where('service_request.user_id',$userid)->whereRaw("(service_request.deleted_at IS null )")->get(); 


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
                                        $data1['location'] = $vall->location;
                                        $data1['username'] = $vall->username;
                                        $data1['status'] = $vall->status;
                                        $data1['email_verify'] = $vall->email_verify;
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


                                        // GET ALL CONTRACTOR & COMPANY , RECIVED NOTIFICATION OF THIS REQUEST



                                         $getAllAssignedCoAndCom = DB::table('assign_service_request')
                                        ->leftjoin('users', 'assign_service_request.user_id', '=', 'users.id')
                                        ->select('users.id','users.user_group_id','users.username','users.email','users.mobile_number','users.avatar_location','assign_service_request.request_status')
                                         ->whereRaw("(assign_service_request.service_request_id = '".$request_id."')")->get()->toArray(); 

                                        $secOptions=array();

                                        foreach ($getAllAssignedCoAndCom as $key => $datas) 
                                        {

                                                if($datas->user_group_id==3)
                                                {
                                                    $profilePath ='/img/contractor/profile/';
                                                }else
                                                {
                                                    $profilePath ='/img/company/profile/';
                                                }

                                               

                                           $assData['id'] = $datas->id;
                                           $assData['username'] = $datas->username;
                                           $assData['email'] = $datas->email;
                                           $assData['mobile_number'] = $datas->mobile_number;


                                            if(file_exists(public_path($profilePath.$datas->avatar_location)))
                                            {
                                               $assData['profile']= isset($datas->avatar_location) && !empty($datas->avatar_location) ? url($profilePath.$datas->avatar_location) : '';

                                            } else 
                                            {
                                               $assData['profile']= '';
                                            }

                                           $assData['request_status'] = isset($datas->request_status) && !empty($datas->request_status) ? $datas->request_status : '';

                                            array_push($secOptions, $assData);
                                        }

                                        //END


                                        $data1['question_options']=$options ;

                                        $data1['assigned_contractor_and_companies']=$secOptions ;

                                        //array_push($allData, $data1);

                                    }

                                    if(!empty($data1))
                                    {
                                        $resultArray['status']='1';   
                                        $resultArray['message']=trans('Request Detail Found Successfully.!');
                                        $resultArray['data'] = $data1; 
                                        echo json_encode($resultArray); exit;   
                                    }
                                    else
                                    {
                                        $resultArray['status']='0';   
                                        $resultArray['message']=trans('Request Detail Not Found.!');
                                        echo json_encode($resultArray); exit;   
                                    }
                                    
                                        

                                   }
                                   else
                                   {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Request not found.');
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
          * ************ GET REQUEST DETAIL API OF USER *********************
          *
          * GET REQUEST DETAIL API OF USER API END HERE
          */
/*-------------------------------------------------------------------------------------------------*/




         /*
          * ************ Profile Comparison BETWEEN 3 USERS (contractor OR companies)****************
          * So User can decide Which user is suitable for his service.
          */

           public function profileComparisonDetail(Request $request)
           {

                $access_token=123456;
                $compPoint=array();
                $alldata=array();
                $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
                $request_id = isset($request->request_id) && !empty($request->request_id) ? $request->request_id : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                 App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'userid' => 'required',
                'session_key' => 'required',
                'request_id' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                 if(!empty($userid) && !empty($session_key) && !empty($request_id)) 
                {
                    $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                    if($check_auth['status']!=1)
                    {
                     echo json_encode($check_auth); exit;
                    }else
                    {

                         $userEntity = DB::table('users')
                        ->whereRaw("(active=1)")
                        ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                        ->first();

                        if(!empty($userEntity))
                        {

                             $checkRequestIsExist = DB::table('service_request')
                            ->whereRaw("(id = '".$request_id."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($checkRequestIsExist))
                            {

                                if($checkRequestIsExist->user_id==$userEntity->id)
                                {
                                    $presentdata=array();
                                    $datasocial=array();
                                    $options=array();
                                    $paymentData=array();

                                 $getAllBuyAppUsers = DB::table('assign_service_request')
                                 ->leftjoin('users', 'assign_service_request.user_id', '=', 'users.id')
                                 ->select('users.id','users.user_group_id','users.avatar_location','users.username','users.profile_title','users.website_address','users.address','users.year_of_constitution')
                                 ->whereRaw("(assign_service_request.service_request_id = '".$request_id."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status = 'buy')")->get();
                                     
                                    //echo "<pre>"; print_r($getAllBuyAppUsers);die;

                                        if($getAllBuyAppUsers)
                                        {
                                          
                                           foreach ($getAllBuyAppUsers as $dataa)
                                            {

                                                $presentdata['user_id']=$dataa->id;
                                                $presentdata['user_group_id']=$dataa->user_group_id;
                                                $presentdata['username']=$dataa->username;

                                                if($dataa->user_group_id==3)
                                                {
                                                    $profilePath ='/img/contractor/profile/';
                                                }else
                                                {
                                                    $profilePath ='/img/company/profile/';
                                                }


                                                if(Storage::exists($profilePath.$dataa->avatar_location)) 
                                                {
                                                   $presentdata['profile']= isset($dataa->avatar_location) && !empty($dataa->avatar_location) ? url($profilePath.$dataa->avatar_location) : '';

                                                } else {
                                                   $presentdata['profile']= '';
                                                }
                                                $presentdata['profile_title']=isset($dataa->profile_title) && !empty($dataa->profile_title) ? $dataa->profile_title : '';
                                                $presentdata['year_of_constitution']=isset($dataa->year_of_constitution) && !empty($dataa->year_of_constitution) ? $dataa->year_of_constitution : '';
                                                $presentdata['address']=isset($dataa->address) && !empty($dataa->address) ? $dataa->address : '';
                                                $presentdata['website_address']=isset($dataa->website_address) && !empty($dataa->website_address) ? $dataa->website_address : '';


                                              ///////////////////////Total Employees/////////////////

                                               $totalEmployee=DB::table('workers')->where('user_id',$dataa->id)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                            
                                               $presentdata['total_employee'] = isset($totalEmployee) && !empty($totalEmployee) ? count($totalEmployee) : '0';
                                             
                                             ///////////////////////Total Employees/////////////////
                                               
                                               ///////////////////////Social Data/////////////////
                                               $socialData=DB::table('social_networks')->where('user_id',$dataa->id)->whereRaw("(status = 1 AND deleted_at IS null )")->first(); 
                                                if(!empty($socialData))
                                                {
                                                    $fb = isset($socialData->facebook_url) && !empty($socialData->facebook_url) ? $socialData->facebook_url.',' : '';
                                                    $insta = isset($socialData->instagram_url) && !empty($socialData->instagram_url) ? $socialData->instagram_url.',' : '';
                                                    $linkedin = isset($socialData->linkedin_url) && !empty($socialData->linkedin_url) ? $socialData->linkedin_url.',' : '';
                                                    $twitter_url = isset($socialData->twitter_url) && !empty($socialData->twitter_url) ? $socialData->twitter_url.',' : '';
                                                    $other_url = isset($socialData->other) && !empty($socialData->other) ? $socialData->other : '';
                                                    $datasocial = $fb.$insta.$linkedin.$twitter_url.$other_url;
                                                }
                                                ///////////////////////Social Data/////////////////

                                                 ///////////////////////Payment Methods/////////////////

                                              $methodName="";
                                              if($lang=='es')
                                                {$methodName='payment_methods.name_es';}
                                              else{$methodName='payment_methods.name_en';}

                                              $usersPayMethod = DB::table('user_payment_methods')
                                              ->leftjoin('payment_methods', 'user_payment_methods.payment_method_id', '=', 'payment_methods.id')
                                              ->select('user_payment_methods.id')
                                               ->select(DB::raw('group_concat('.$methodName.') as method_name'))
                                              ->where('user_payment_methods.user_id',$dataa->id)->whereRaw("(user_payment_methods.deleted_at IS null )")->get()->toArray();

                                            if(!empty($usersPayMethod))
                                            {
                                                foreach ($usersPayMethod as $pMethod) 
                                                {
                                                   $spaymentData= $pMethod->method_name;
                                                }
                                            }

                                             ///////////////////////Payment Methods/////////////////

                                            ///////////////////////services offered/////////////////

                                          $srname="";
                                          if($lang=='es')
                                            {$srname='services.es_name';}
                                         else{$srname='services.en_name';}


                                            $servicesOffered = DB::table('services_offered')
                                             ->leftjoin('services', 'services_offered.service_id', '=', 'services.id')
                                            ->select('services_offered.id')
                                            ->select(DB::raw('group_concat('.$srname.') as service_name'))
                                            ->where('services_offered.user_id',$dataa->id)->whereRaw("(services_offered.deleted_at IS null )")->get()->toArray();

                                             if(!empty($servicesOffered))
                                            {
                                                foreach ($servicesOffered as $service) 
                                                {
                                                   $serviceData= $service->service_name;
                                                }
                                            }

                                        ///////////////////////services offered/////////////////

                                              $presentdata['payment_methods']=$spaymentData ;
                                              $presentdata['services_offered']=$serviceData ;
                                              $presentdata['social_url']=$datasocial ;
                                               array_push($alldata, $presentdata);


                                            }


                                            $resultArray['status']='1';
                                            $resultArray['message']=trans('Users Comparison Data.');
                                            $resultArray['data']=$alldata;
                                            echo json_encode($resultArray); exit; 
                                          

                                        }else
                                        {
                                            $resultArray['status']='0';
                                            $resultArray['message']=trans('Users Not avaliable for Comparison.');
                                            echo json_encode($resultArray); exit; 
                                        }

                                }
                                else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Invalid User For This Request Id.');
                                    echo json_encode($resultArray); exit; 
                                }
                             }
                             else
                             {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('Invalid Request Id.');
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

          /*
          * ************ GET Profile Comparison BETWEEN 3 USERS (contractor OR companies)****************
          * So User can decide Which user is suitable for his service.
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
            $resultArray['message']=trans('apimessage.Hello ').$username.trans('apimessage. we have received your password recovery request. We have sent an email to your account with instructions to recover the password.');
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
                        $address_lat = !empty($request->address_lat) ? $request->address_lat : '0' ;
                        $address_long = !empty($request->address_long) ? $request->address_long : '0' ;

                        $office_address = !empty($request->office_address) ? $request->office_address : '' ;
                        $office_address_lat = !empty($request->office_address_lat) ? $request->office_address_lat : '0' ;
                        $office_address_long = !empty($request->office_address_long) ? $request->office_address_long : '0' ;

                        $other_address = !empty($request->other_address) ? $request->other_address : '' ;
                        $other_address_lat = !empty($request->other_address_lat) ? $request->other_address_lat : '0' ;
                        $other_address_long = !empty($request->other_address_long) ? $request->other_address_long : '0' ;

                        $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                        $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
                        $office_number = !empty($request->office_number) ? $request->office_number : '' ;
                        $email = !empty($request->email) ? $request->email : '' ;
                        $username = !empty($request->username) ? $request->username : '' ;

                        $validator = Validator::make($request->all(), [
                            'userid' => 'required',
                            'user_group_id' => 'required',
                            'session_key' => 'required',
                            'lang' => 'required',
                            
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
                                $resultArray['message']=trans('Mobile Number Already Exist');
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
                                        $userData['username'] =  !empty($username) ? $username : $userEntity->username;
                                        $userData['identity_no'] =  !empty($identity_no) ? $identity_no : $userEntity->identity_no;
                                        $userData['dob'] =  !empty($dob) ? $dob : $userEntity->dob;
                                        $userData['address'] =  !empty($address) ? $address : $userEntity->address;
                                        $userData['address_lat'] =  !empty($address_lat) ? $address_lat : $userEntity->address_lat;
                                        $userData['address_long'] =  !empty($address_long) ? $address_long : $userEntity->address_long;

                                        $userData['office_address'] =  !empty($office_address) ? $office_address : $userEntity->office_address;
                                         $userData['office_address_lat'] =  !empty($office_address_lat) ? $office_address_lat : $userEntity->office_address_lat;
                                          $userData['office_address_long'] =  !empty($office_address_long) ? $office_address_long : $userEntity->office_address_long;

                                        $userData['other_address'] =  !empty($other_address) ? $other_address : $userEntity->other_address;
                                        $userData['other_address_lat'] =  !empty($other_address_lat) ? $other_address_lat : $userEntity->other_address_lat;
                                        $userData['other_address_long'] =  !empty($other_address_long) ? $other_address_long : $userEntity->other_address_long;

                                        $userData['mobile_number'] =  !empty($mobile_number) ? $mobile_number : $userEntity->mobile_number;
                                        $userData['landline_number'] =  !empty($landline_number) ? $landline_number : $userEntity->landline_number;
                                        $userData['office_number'] =  !empty($office_number) ? $office_number : $userEntity->office_number;
                                        $userData['updated_at'] = Carbon::now()->toDateTimeString();
                                        

                                        DB::table('users')->where('id',$userEntity->id)->update($userData);
                                            
                                             
                                           $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','created_at','updated_at')->where('id',$userEntity->id)->first();

                                            $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';

                                             $users_count_var->email = isset($users_count_var->email) && !empty($users_count_var->email) ? $users_count_var->email : '';

                                             $users_count_var->username = isset($users_count_var->username) && !empty($users_count_var->username) ? $users_count_var->username : '';

                                             $users_count_var->dob = isset($users_count_var->dob) && !empty($users_count_var->dob) ? $users_count_var->dob : '';

                                             $users_count_var->address = isset($users_count_var->address) && !empty($users_count_var->address) ? $users_count_var->address : '';

                                             $users_count_var->address_lat = isset($users_count_var->address_lat) && !empty($users_count_var->address_lat) ? $users_count_var->address_lat : '0';

                                             $users_count_var->address_long = isset($users_count_var->address_long) && !empty($users_count_var->address_long) ? $users_count_var->address_long : '0';

                                             $users_count_var->office_address = isset($users_count_var->office_address) && !empty($users_count_var->office_address) ? $users_count_var->office_address : '';

                                             $users_count_var->office_address_lat = isset($users_count_var->office_address_lat) && !empty($users_count_var->office_address_lat) ? $users_count_var->office_address_lat : '0';

                                             $users_count_var->office_address_long = isset($users_count_var->office_address_long) && !empty($users_count_var->office_address_long) ? $users_count_var->office_address_long : '0';
                                             $users_count_var->other_address = isset($users_count_var->other_address) && !empty($users_count_var->other_address) ? $users_count_var->other_address : '';

                                             $users_count_var->other_address_lat = isset($users_count_var->other_address_lat) && !empty($users_count_var->other_address_lat) ? $users_count_var->other_address_lat : '0';

                                             $users_count_var->other_address_long = isset($users_count_var->other_address_long) && !empty($users_count_var->other_address_long) ? $users_count_var->other_address_long : '0';

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


             // *******************************************************************************//


             // Contractor && Company Profile Update here

             if(!empty($user_group_id) && ($user_group_id==3 || $user_group_id==4))
                    {


                        if($user_group_id==4)
                        { 
                            $ruc_no = !empty($request->ruc_no) ? $request->ruc_no : '' ;
                        $year_of_constitution = !empty($request->year_of_constitution) ? $request->year_of_constitution : '' ;
                        $legal_representative = !empty($request->legal_representative) ? $request->legal_representative : '' ;
                        $website_address = !empty($request->website_address) ? $request->website_address : '' ;
                       }

                        $profile_pic = !empty($request->profile_pic) ? $request->profile_pic : '' ;
                        $username = !empty($request->username) ? $request->username : '' ;
                        $profile_title = !empty($request->profile_title) ? $request->profile_title : '' ;
                        $identity_no = !empty($request->identity_no) ? $request->identity_no : '' ;
                        $dob = !empty($request->dob) ? $request->dob : '' ;
                        $address = !empty($request->address) ? $request->address : '' ;
                        $address_lat = !empty($request->address_lat) ? $request->address_lat : '0' ;
                        $address_long = !empty($request->address_long) ? $request->address_long : '0' ;

                        $office_address = !empty($request->office_address) ? $request->office_address : '' ;
                        $office_address_lat = !empty($request->office_address_lat) ? $request->office_address_lat : '0' ;
                        $office_address_long = !empty($request->office_address_long) ? $request->office_address_long : '0' ;

                        $other_address = !empty($request->other_address) ? $request->other_address : '' ;
                        $other_address_lat = !empty($request->other_address_lat) ? $request->other_address_lat : '0' ;
                        $other_address_long = !empty($request->other_address_long) ? $request->other_address_long : '0' ;
                        $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                        $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
                        $office_number = !empty($request->office_number) ? $request->office_number : '' ;

                        $facebook_url=!empty($request->facebook_url) ? $request->facebook_url : '' ;
                        $instagram_url=!empty($request->instagram_url) ? $request->instagram_url : '' ;
                        $linkedin_url=!empty($request->linkedin_url) ? $request->linkedin_url : '' ;
                        $twitter_url=!empty($request->twitter_url) ? $request->twitter_url : '' ;
                        $other_url=!empty($request->other_url) ? $request->other_url : '' ;

                        $profile_description=!empty($request->profile_description) ? $request->profile_description : '' ;
                         //Multiple
                        $service_offered=!empty($request->service_offered) ? $request->service_offered : '' ;
                         //1,2,3,4;Before
                        //$service_offered=[{"service_id":"1","sub_service_id":"1,2,3"},{"service_id":"2","sub_service_id":"3,4,5"},{"service_id":"3","sub_service_id":""}];
                       

                        //Services Area
                        $whole_country=!empty($request->whole_country) ? $request->whole_country : '0' ;
                        //$proviences=!empty($request->proviences) ? $request->proviences : '' ;
                        //$cities=!empty($request->cities) ? $request->cities : '0' ;
                        $services_area=!empty($request->services_area) ? $request->services_area : '' ;
                         //$services_area=[{"proviences":"1","cities":"1,2,3"},{"proviences":"2","cities":"3,4,5"},{"proviences":"3","cities":""}];

                        //Multiple
                        $payment_method=!empty($request->payment_method) ? $request->payment_method : '' ;
                        //1,2,3,4;

                        //Multiple
                        $images_gallery=!empty($request->images_gallery) ? $request->images_gallery : '' ;
                        $videos_gallery=!empty($request->videos_gallery) ? $request->videos_gallery : '' ;

                        //Multiple
                        //$documents=!empty($request->documents) ? $request->documents : '' ;
                        //documents[file][0]
                        //documents[doc_id][0]

                        $certification_courses_img=!empty($request->certification_courses_img) ? $request->certification_courses_img : '' ;
                        $certification_courses_doc=!empty($request->certification_courses_doc) ? $request->certification_courses_doc : '' ;
                        //Multiple
                        //certification_courses_img[0]:abc11.jpg
                        //certification_courses_doc[0]:abc12.docx
                        
                        $police_records_img=!empty($request->police_records_img) ? $request->police_records_img : '' ;
                        $police_records_doc=!empty($request->police_records_doc) ? $request->police_records_doc : '' ;

                        $total_employee=!empty($request->total_employee) ? $request->total_employee : '' ;

                        //Multiple
                        //police_records_img[0]:abc11.jpg
                        //police_records_doc[0]:abc12.docx


                        $validator = Validator::make($request->all(), [
                            'userid' => 'required',
                            //'mobile_number' => 'required',
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
                                $resultArray['message']=trans('Mobile Number Already Exist');
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
                                        error_reporting(0);

                                        //Profile picture
                                        $profile = $userEntity->avatar_location;
                                            $profilePath=""; $policePath=""; $certifiePath="";$galleryPath="";$videoPath="";

                                        if($user_group_id==3)
                                        {
                                            $profilePath ='/img/contractor/profile/';
                                            $policePath ='/img/contractor/police_records/';
                                            $certifiePath ='/img/contractor/certifications/';
                                            $galleryPath ='/img/contractor/gallery/images/';
                                            $videoPath = '/img/contractor/gallery/videos/';
                                        }else
                                        {
                                            $profilePath ='/img/company/profile/';
                                            $policePath ='/img/company/police_records/';
                                            $certifiePath ='/img/company/certifications/';
                                            $galleryPath ='/img/company/gallery/images/';
                                            $videoPath = '/img/company/gallery/videos/';
                                        }
                                       
                                        if(isset($_FILES['profile_pic']['name']) && !empty($_FILES['profile_pic']['name']))
                                            {

                                                $extq = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                                $filename = $userEntity->id.'.'.$extq;

                                                $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);

                                                
                                               $fmove = move_uploaded_file($_FILES['profile_pic']['tmp_name'],public_path() .$profilePath .$filename);
                                                 
                                               
                                                 $profile = $filename;
                                            }

                                           //Profile picture


                                             // start Police Record IMAGE

                                             if($user_group_id==3)
                                             {
                                             if(!empty($police_records_img))
                                              {
                                               
                                                     $fileNames = array_filter($_FILES['police_records_img']['name']);

                                                     $allowTypes = array('jpg','png','jpeg'); 
                                                     
                                                     
                                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                                     if(!empty($fileNames) && $_FILES["police_records_img"]["error"] !== 4)
                                                    {
                                                        //Delete Old
                                                        $getAll = DB::table('user_certifications')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null)")->whereRaw("(certification_type = '1')")->whereRaw("(file_type = '0')")->get()->toArray();
                                                          
                                                        if(!empty($getAll))
                                                        {
                                                            DB::table('user_certifications')->where('user_id', '=', $userid)->where('certification_type', '=', '1')->where('file_type', '=', '0')->delete();

                                                            $deleteOld = $this->delete_directory(public_path() . $policePath .$userid.'/img/');
                                                        }
                                                         //Delete Old

                                                        //crete new folder
                                                        mkdir(public_path() . $policePath .$userid.'/img/', 0777, true);

                                                        $targetDir = public_path() . $policePath .$userid.'/img/';

                                                      foreach($_FILES['police_records_img']['name'] as $key=>$val) 
                                                      {

                                                         $fileName = rand(0000,9999).basename($_FILES['police_records_img']['name'][$key]); 
                                                         $targetFilePath = $targetDir . $fileName;

                                                            // Check whether file type is valid 
                                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                                            if(in_array($fileType, $allowTypes))
                                                            { 
                                                                // Upload file to server 
                                                                if(move_uploaded_file($_FILES["police_records_img"]["tmp_name"][$key], $targetFilePath))
                                                                { 
                                                                    $insert['file_name'] = $fileName;
                                                                    $insert['file_extension'] = $fileType;
                                                                    $insert['file_type'] = '0';
                                                                    $insert['certification_type'] = '1';
                                                                    $insert['user_id'] = $userid;
                                                                    $insert['status'] = 1;
                                                                    $insert['created_at'] = Carbon::now();  
                                                                    DB::table('user_certifications')->insertGetId($insert); 
                                                                }else
                                                                { 
                                                                     $errorUpload .= 'police record image file not uploaded.'; 
                                                                } 
                                                            }else
                                                            { 
                                                                $errorUploadType .='police record image File Type Not Match';
                                                            }  
                                                       }
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
                                            

                                            // End Police Record IMAGE


                                            // start Police Record DOCUMENT

                                             if(!empty($police_records_doc))
                                              {
                                               
                                                     $fileNames = array_filter($_FILES['police_records_doc']['name']);

                                                     $allowTypes = array('pdf','doc','docx','txt','rtf','odf','msword');
                                                     
                                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                                     if(!empty($fileNames) && $_FILES["police_records_doc"]["error"] !== 4)
                                                    {
                                                        //Delete Old
                                                        $getAll = DB::table('user_certifications')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null)")->whereRaw("(certification_type = '1')")->whereRaw("(file_type = '1')")->get()->toArray();
                                                          
                                                        if(!empty($getAll))
                                                        {

                                                            $targetDir = public_path() . $policePath .$userid.'/doc/';

                                                            if(is_dir($targetDir)) 
                                                            {
                                                              //echo ("$file is a directory");
                                                              $targetDir = public_path() . $policePath .$userid.'/doc/';
                                                            } else 
                                                            {
                                                              //echo ("$file is not a directory");
                                                                 mkdir(public_path() . $policePath .$userid.'/doc/', 0777, true);

                                                                $targetDir = public_path() . $policePath .$userid.'/doc/';
                                                            }


                                                             foreach($_FILES['police_records_doc']['name'] as $key=>$val) 
                                                            {

                                                                 $fileName = rand(0000,9999).basename($_FILES['police_records_doc']['name'][$key]); 
                                                                 $targetFilePath = $targetDir . $fileName;

                                                                    // Check whether file type is valid 
                                                                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                                                    if(in_array($fileType, $allowTypes))
                                                                    { 
                                                                        // Upload file to server 
                                                                        if(move_uploaded_file($_FILES["police_records_doc"]["tmp_name"][$key], $targetFilePath))
                                                                        { 
                                                                            $insert['file_name'] = $fileName;
                                                                            $insert['file_extension'] = $fileType;
                                                                            $insert['file_type'] = '1';
                                                                            $insert['certification_type'] = '1';
                                                                            $insert['user_id'] = $userid;
                                                                            $insert['status'] = 1;
                                                                            $insert['created_at'] = Carbon::now();  
                                                                            DB::table('user_certifications')->insertGetId($insert); 
                                                                        }else
                                                                        { 
                                                                             $errorUpload .= 'police record document file not uploaded.'; 
                                                                        } 
                                                                    }else
                                                                    { 
                                                                        $errorUploadType .='police record document File Type Not Match';
                                                                    }  
                                                               }


                                                            // DB::table('user_certifications')->where('user_id', '=', $userid)->where('certification_type', '=', '1')->where('file_type', '=', '1')->delete();

                                                            // $deleteOld = $this->delete_directory(public_path() . $policePath .$userid.'/doc/');
                                                        }else {
                                                         //Delete Old
                                                        

                                                        //crete new folder
                                                        mkdir(public_path() . $policePath .$userid.'/doc/', 0777, true);

                                                        $targetDir = public_path() . $policePath .$userid.'/doc/';

                                                      foreach($_FILES['police_records_doc']['name'] as $key=>$val) 
                                                      {

                                                         $fileName = rand(0000,9999).basename($_FILES['police_records_doc']['name'][$key]); 
                                                         $targetFilePath = $targetDir . $fileName;

                                                            // Check whether file type is valid 
                                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                                            if(in_array($fileType, $allowTypes))
                                                            { 
                                                                // Upload file to server 
                                                                if(move_uploaded_file($_FILES["police_records_doc"]["tmp_name"][$key], $targetFilePath))
                                                                { 
                                                                    $insert['file_name'] = $fileName;
                                                                    $insert['file_extension'] = $fileType;
                                                                    $insert['file_type'] = '1';
                                                                    $insert['certification_type'] = '1';
                                                                    $insert['user_id'] = $userid;
                                                                    $insert['status'] = 1;
                                                                    $insert['created_at'] = Carbon::now();  
                                                                    DB::table('user_certifications')->insertGetId($insert); 
                                                                }else
                                                                { 
                                                                     $errorUpload .= 'police record document file not uploaded.'; 
                                                                } 
                                                            }else
                                                            { 
                                                                $errorUploadType .='police record document File Type Not Match';
                                                            }  
                                                       }

                                                     }

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

                                            }
                                         // End Police Record DOCUMENT



                                         // start certification courses IMAGE

                                             if(!empty($certification_courses_img))
                                              {
                                               
                                                     $fileNames = array_filter($_FILES['certification_courses_img']['name']);

                                                     $allowTypes = array('jpg','png','jpeg'); 
                                                     
                                                     
                                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                                     if(!empty($fileNames) && $_FILES["certification_courses_img"]["error"] !== 4)
                                                    {
                                                        //Delete Old
                                                        $getAll = DB::table('user_certifications')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null)")->whereRaw("(certification_type = '0')")->whereRaw("(file_type = '0')")->get()->toArray();
                                                          
                                                        if(!empty($getAll))
                                                        {
                                                            DB::table('user_certifications')->where('user_id', '=', $userid)->where('certification_type', '=', '0')->where('file_type', '=', '0')->delete();

                                                            $deleteOld = $this->delete_directory(public_path() . $certifiePath .$userid.'/img/');
                                                        }
                                                         //Delete Old

                                                        //crete new folder
                                                        mkdir(public_path() . $certifiePath .$userid.'/img/', 0777, true);

                                                        $targetDir = public_path() . $certifiePath .$userid.'/img/';

                                                      foreach($_FILES['certification_courses_img']['name'] as $key=>$val) 
                                                      {

                                                         $fileName = rand(0000,9999).basename($_FILES['certification_courses_img']['name'][$key]); 
                                                         $targetFilePath = $targetDir . $fileName;

                                                            // Check whether file type is valid 
                                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                                            if(in_array($fileType, $allowTypes))
                                                            { 
                                                                // Upload file to server 
                                                                if(move_uploaded_file($_FILES["certification_courses_img"]["tmp_name"][$key], $targetFilePath))
                                                                { 
                                                                    $insert['file_name'] = $fileName;
                                                                    $insert['file_extension'] = $fileType;
                                                                    $insert['file_type'] = '0';
                                                                    $insert['certification_type'] = '0';
                                                                    $insert['user_id'] = $userid;
                                                                    $insert['status'] = 1;
                                                                    $insert['created_at'] = Carbon::now();  
                                                                    DB::table('user_certifications')->insertGetId($insert); 
                                                                }else
                                                                { 
                                                                     $errorUpload .= 'certification image file not uploaded.'; 
                                                                } 
                                                            }else
                                                            { 
                                                                $errorUploadType .='certification image File Type Not Match';
                                                            }  
                                                       }
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

                                            // End certification courses IMAGE


                                            // start certification courses  DOCUMENT

                                             if(!empty($certification_courses_doc))
                                              {
                                               
                                                     $fileNames = array_filter($_FILES['certification_courses_doc']['name']);

                                                     $allowTypes = array('pdf','doc','docx','txt','rtf','odf','msword');
                                                     
                                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                                     if(!empty($fileNames) && $_FILES["certification_courses_doc"]["error"] !== 4)
                                                    {
                                                        //Delete Old
                                                        $getAll = DB::table('user_certifications')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null)")->whereRaw("(certification_type = '0')")->whereRaw("(file_type = '1')")->get()->toArray();
                                                          
                                                        if(!empty($getAll))
                                                        {

                                                         $targetDir = public_path() . $certifiePath .$userid.'/doc/';
                                                            if(is_dir($targetDir)) 
                                                            {
                                                              //echo ("$file is a directory");
                                                             $targetDir = public_path() . $certifiePath .$userid.'/doc/';
                                                            } else 
                                                            {
                                                              //echo ("$file is not a directory");
                                                               mkdir(public_path() . $certifiePath .$userid.'/doc/', 0777, true);
                                                               $targetDir = public_path() . $certifiePath .$userid.'/doc/';
                                                            }


                                                      foreach($_FILES['certification_courses_doc']['name'] as $key=>$val) 
                                                      {

                                                         $fileName = rand(0000,9999).basename($_FILES['certification_courses_doc']['name'][$key]); 
                                                         $targetFilePath = $targetDir . $fileName;

                                                            // Check whether file type is valid 
                                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                                            if(in_array($fileType, $allowTypes))
                                                            { 
                                                                // Upload file to server 
                                                                if(move_uploaded_file($_FILES["certification_courses_doc"]["tmp_name"][$key], $targetFilePath))
                                                                { 
                                                                    $insert['file_name'] = $fileName;
                                                                    $insert['file_extension'] = $fileType;
                                                                    $insert['file_type'] = '1';
                                                                    $insert['certification_type'] = '0';
                                                                    $insert['user_id'] = $userid;
                                                                    $insert['status'] = 1;
                                                                    $insert['created_at'] = Carbon::now();  
                                                                    DB::table('user_certifications')->insertGetId($insert); 
                                                                }else
                                                                { 
                                                                     $errorUpload .= 'certification document file not uploaded.'; 
                                                                } 
                                                            }else
                                                            { 
                                                                $errorUploadType .='certification document File Type Not Match';
                                                            }  
                                                       }


                                                            // DB::table('user_certifications')->where('user_id', '=', $userid)->where('certification_type', '=', '0')->where('file_type', '=', '1')->delete();

                                                            // $deleteOld = $this->delete_directory(public_path() . $certifiePath .$userid.'/doc/');
                                                        }
                                                         //Delete Old
                                                        else
                                                        {
                                                        //crete new folder
                                                        mkdir(public_path() . $certifiePath .$userid.'/doc/', 0777, true);

                                                        $targetDir = public_path() . $certifiePath .$userid.'/doc/';

                                                      foreach($_FILES['certification_courses_doc']['name'] as $key=>$val) 
                                                      {

                                                         $fileName = rand(0000,9999).basename($_FILES['certification_courses_doc']['name'][$key]); 
                                                         $targetFilePath = $targetDir . $fileName;

                                                            // Check whether file type is valid 
                                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                                            if(in_array($fileType, $allowTypes))
                                                            { 
                                                                // Upload file to server 
                                                                if(move_uploaded_file($_FILES["certification_courses_doc"]["tmp_name"][$key], $targetFilePath))
                                                                { 
                                                                    $insert['file_name'] = $fileName;
                                                                    $insert['file_extension'] = $fileType;
                                                                    $insert['file_type'] = '1';
                                                                    $insert['certification_type'] = '0';
                                                                    $insert['user_id'] = $userid;
                                                                    $insert['status'] = 1;
                                                                    $insert['created_at'] = Carbon::now();  
                                                                    DB::table('user_certifications')->insertGetId($insert); 
                                                                }else
                                                                { 
                                                                     $errorUpload .= 'certification document file not uploaded.'; 
                                                                } 
                                                            }else
                                                            { 
                                                                $errorUploadType .='certification document File Type Not Match';
                                                            }  
                                                       }

                                                    }
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

                                         // End certification courses DOCUMENT

                                            // Add Gallery Images
                                              
                                              if(!empty($images_gallery))
                                              {
                                                     $fileNames = array_filter($_FILES['images_gallery']['name']);
                                                     $allowTypes = array('jpg','png','jpeg'); 
                                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                                     if(!empty($fileNames) && $_FILES["images_gallery"]["error"] !== 4)
                                                    {
                                                        //Delete Old
                                                        $getAll = DB::table('users_images_gallery')->whereRaw("(user_id = '".$userEntity->id."' AND deleted_at IS null)")->get()->toArray();
                                                        if(!empty($getAll))
                                                        {
                                                            DB::table('users_images_gallery')->where('user_id', '=', $userEntity->id)->delete();

                                                            $deleteOld = $this->delete_directory(public_path() . $galleryPath .$userEntity->id);
                                                        }
                                                         //Delete Old

                                                        //crete new folder
                                                        mkdir(public_path() . $galleryPath .$userEntity->id, 0777, true);

                                                        $targetDir = public_path() . $galleryPath .$userEntity->id.'/';

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
                                                                     $errorUpload .= 'Image not uploaded.'; 
                                                                } 
                                                            }else
                                                            { 
                                                                $errorUploadType .='Images Type Allowed Only (.jpg,.png,.jpeg).';
                                                            }  
                                                       }
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
                                              // Add Gallery Images


                                              // Add Gallery Videos


                                              if(!empty($videos_gallery))
                                              {
                                              
                                                     $fileNames = array_filter($_FILES['videos_gallery']['name']);

                                                     $allowTypes = array("webm", "mp4", "ogv"); 

                                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                                     if(!empty($fileNames) && $_FILES["videos_gallery"]["error"] !== 4)
                                                    {
                                                        //Delete Old
                                                        $getAll = DB::table('users_videos_gallery')->whereRaw("(user_id = '".$userEntity->id."' AND deleted_at IS null)")->get()->toArray();
                                                        if(!empty($getAll))
                                                        {

                                                            $targetDir = public_path() . $videoPath .$userEntity->id.'/';
                                                            if(is_dir($targetDir)) 
                                                            {
                                                              //echo ("$file is a directory");
                                                                $targetDir = public_path() . $videoPath .$userEntity->id.'/';
                                                            } else 
                                                            {
                                                              //echo ("$file is not a directory");
                                                                 mkdir(public_path() . $videoPath .$userEntity->id, 0777, true);
                                                                $targetDir = public_path() . $videoPath .$userEntity->id.'/';
                                                            }


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
                                                            // DB::table('users_videos_gallery')->where('user_id', '=', $userEntity->id)->delete();

                                                            // $deleteOld = $this->delete_directory(public_path() . $videoPath .$userEntity->id);

                                                        }
                                                        //Delete Old
                                                        else
                                                        {
                                                             //create new folder

                                                              mkdir(public_path() . $videoPath .$userEntity->id, 0777, true);
                                                                $targetDir = public_path() . $videoPath .$userEntity->id.'/';

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
                                        // Add Gallery Videos

                                          
                                        //Update In user Table

                                        $userData['avatar_location'] =  $profile;
                                        
                                        $userData['username'] =  !empty($username) ? $username : $userEntity->username;
                                        $userData['profile_title'] =  !empty($profile_title) ? $profile_title : $userEntity->profile_title;

                                        $userData['identity_no'] =  !empty($identity_no) ? $identity_no : $userEntity->identity_no;
                                        $userData['dob'] =  !empty($dob) ? $dob : $userEntity->dob;
                                        $userData['address'] =  !empty($address) ? $address : $userEntity->address;
                                        $userData['address_lat'] =  !empty($address_lat) ? $address_lat : $userEntity->address_lat;
                                        $userData['address_long'] =  !empty($address_long) ? $address_long : $userEntity->address_long;

                                        $userData['office_address'] =  !empty($office_address) ? $office_address : $userEntity->office_address;
                                         $userData['office_address_lat'] =  !empty($office_address_lat) ? $office_address_lat : $userEntity->office_address_lat;
                                          $userData['office_address_long'] =  !empty($office_address_long) ? $office_address_long : $userEntity->office_address_long;

                                        $userData['other_address'] =  !empty($other_address) ? $other_address : $userEntity->other_address;
                                        $userData['other_address_lat'] =  !empty($other_address_lat) ? $other_address_lat : $userEntity->other_address_lat;
                                        $userData['other_address_long'] =  !empty($other_address_long) ? $other_address_long : $userEntity->other_address_long;
                                        $userData['mobile_number'] =  !empty($mobile_number) ? $mobile_number : $userEntity->mobile_number;
                                        $userData['landline_number'] =  !empty($landline_number) ? $landline_number : $userEntity->landline_number;
                                        $userData['office_number'] =  !empty($office_number) ? $office_number : $userEntity->office_number;
                                        $userData['profile_description'] =  !empty($profile_description) ? $profile_description : $userEntity->profile_description;

                                         $userData['total_employee'] =  !empty($total_employee) ? $total_employee : $userEntity->total_employee;

                                        if($user_group_id==4)
                                        {
                                            $userData['ruc_no'] =  !empty($ruc_no) ? $ruc_no : $userEntity->ruc_no;
                                            $userData['year_of_constitution'] =  !empty($year_of_constitution) ? $year_of_constitution : $userEntity->year_of_constitution;

                                            $userData['legal_representative'] =  !empty($legal_representative) ? $legal_representative : $userEntity->legal_representative;

                                            $userData['website_address'] =  !empty($website_address) ? $website_address : $userEntity->website_address;

                                             $userData['total_employee'] =  !empty($total_employee) ? $total_employee : $userEntity->total_employee;
                                       }


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

                                            $variable_fields_data = json_decode($service_offered);
                                            $serviceOfferedData = json_decode( json_encode($variable_fields_data), true);

                                            $getData = DB::table('services_offered')->whereRaw("(user_id = '".$userid."')")->get()->toArray();

                                              if(!empty($getData))
                                              {
                                                 DB::table('services_offered')->where('user_id', '=', $userid)->delete();
                                              }
                                             foreach($serviceOfferedData as $value) 
                                             {
                                               
                                                    $service_id=$value['service_id'];
                                                    $subService=$value['sub_service_id'];

                                                    $subServiceArray=explode(',', $subService);

                                                    foreach ($subServiceArray as $key2 => $value2) 
                                                    {
                                                        $serv['user_id'] = $userid;
                                                        $serv['service_id'] = $service_id; 
                                                        $serv['sub_service_id'] = isset($value2) && !empty($value2) ? $value2 : NULL ;
                                                        $serv['created_at'] = Carbon::now()->toDateTimeString();
                                                        $saveserv = DB::table('services_offered')->insert($serv);
                                                    }
                                             }

                                        }
                                        //End Service Offered

                                       //Payment Method

                                      if(!empty($payment_method))
                                        {

                                         $paymentMeData=explode(',', $payment_method);

                                         $getData = DB::table('user_payment_methods')->select('id','user_id','payment_method_id','status')->whereRaw("(user_id = '".$userid."')")->get()->toArray();

                                          if(!empty($getData))
                                          {
                                          DB::table('user_payment_methods')->where('user_id', '=', $userid)->delete();
                                          }

                                        foreach($paymentMeData as $value2) 
                                         {
                                            $paym['user_id'] = $userid;
                                            $paym['payment_method_id'] = $value2; 
                                            $paym['status'] =  1;
                                            $paym['created_at'] = Carbon::now()->toDateTimeString();
                                            $savepaym = DB::table('user_payment_methods')->insert($paym);
                                         }
                                      }
                                         //End Payment Method


                                       //services Area

                                        if(!empty($whole_country) && $whole_country==1)
                                        {
                                           DB::table('users_services_area')->where('user_id', '=', $userid)->delete();
                                            $forCountry['user_id'] = $userid; 
                                            $forCountry['whole_country'] = $whole_country;
                                            $forCountry['created_at'] = Carbon::now()->toDateTimeString();
                                            $saveforCountry = DB::table('users_services_area')->insert($forCountry);
                                        }
                                        if(!empty($services_area))
                                        {
                                            $variable_fields_data = json_decode($services_area);
                                            $servicesProvideArea = json_decode( json_encode($variable_fields_data), true);

                                          DB::table('users_services_area')->where('user_id', '=', $userid)->delete();

                                           foreach ($servicesProvideArea as $key => $value) 
                                           {
                                               $proviences_id=$value['proviences'];
                                               $cities=$value['cities'];

                                               $citiesArray=explode(',', $cities);

                                               foreach ($citiesArray as $key2 => $value2) 
                                               {
                                                  
                                                $forProvince['user_id'] = $userid; 
                                                $forProvince['whole_country'] = 0;
                                                $forProvince['province_id']=$proviences_id;
                                                $forProvince['city_id']=isset($value2) && !empty($value2) ? $value2 : NULL ;
                                                $forProvince['created_at'] = Carbon::now()->toDateTimeString();
                                                $saveForProvince = DB::table('users_services_area')->insert($forProvince);
                                               }
                                                 
                                           }

                                        }
                                        //services Area

                                      

                                        ///////////////////Resonse Data/////////////////////////


                   


                                        if($user_group_id==4)
                                        {
                                              $users_count_var=DB::table('users')->select('users.id','users.ruc_no','users.legal_representative','users.website_address','users.year_of_constitution','users.user_group_id','users.email','users.username','users.profile_title','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userEntity->id)->first();
                                        }
                                        else
                                        {
                                            $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userEntity->id)->first();
                                        }

                                          if($user_group_id==4)
                                          {

                                           $users_count_var->ruc_no = isset($users_count_var->ruc_no) && !empty($users_count_var->ruc_no) ? $users_count_var->ruc_no : '';
                                            $users_count_var->legal_representative = isset($users_count_var->legal_representative) && !empty($users_count_var->legal_representative) ? $users_count_var->legal_representative : '';

                                             $users_count_var->website_address = isset($users_count_var->website_address) && !empty($users_count_var->website_address) ? $users_count_var->website_address : '';

                                              $users_count_var->year_of_constitution = isset($users_count_var->year_of_constitution) && !empty($users_count_var->year_of_constitution) ? $users_count_var->year_of_constitution : '';
                                          }
                                          else if($user_group_id==3)
                                          {
                                            $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';
                                          }

                                           

                                            $users_count_var->email = isset($users_count_var->email) && !empty($users_count_var->email) ? $users_count_var->email : '';

                                            $users_count_var->username = isset($users_count_var->username) && !empty($users_count_var->username) ? $users_count_var->username : '';

                                            $users_count_var->profile_title = isset($users_count_var->profile_title) && !empty($users_count_var->profile_title) ? $users_count_var->profile_title : '';

                                            $users_count_var->dob = isset($users_count_var->dob) && !empty($users_count_var->dob) ? $users_count_var->dob : '';

                                           $users_count_var->address = isset($users_count_var->address) && !empty($users_count_var->address) ? $users_count_var->address : '';

                                             $users_count_var->address_lat = isset($users_count_var->address_lat) && !empty($users_count_var->address_lat) ? $users_count_var->address_lat : '0';

                                             $users_count_var->address_long = isset($users_count_var->address_long) && !empty($users_count_var->address_long) ? $users_count_var->address_long : '0';

                                             $users_count_var->office_address = isset($users_count_var->office_address) && !empty($users_count_var->office_address) ? $users_count_var->office_address : '';

                                             $users_count_var->office_address_lat = isset($users_count_var->office_address_lat) && !empty($users_count_var->office_address_lat) ? $users_count_var->office_address_lat : '0';

                                             $users_count_var->office_address_long = isset($users_count_var->office_address_long) && !empty($users_count_var->office_address_long) ? $users_count_var->office_address_long : '0';
                                             $users_count_var->other_address = isset($users_count_var->other_address) && !empty($users_count_var->other_address) ? $users_count_var->other_address : '';

                                             $users_count_var->other_address_lat = isset($users_count_var->other_address_lat) && !empty($users_count_var->other_address_lat) ? $users_count_var->other_address_lat : '0';

                                             $users_count_var->other_address_long = isset($users_count_var->other_address_long) && !empty($users_count_var->other_address_long) ? $users_count_var->other_address_long : '0';

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
                                             $path=$profilePath;
                                             $users_count_var->avatar_location = url($path.$users_count_var->avatar_location);
                                            }
                                            else
                                            {
                                             $users_count_var->avatar_location ="";
                                            }


                                          ///////////////////////Total Employees/////////////////

                                             //  $totalEmployee=DB::table('workers')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                             //  if(!empty($totalEmployee))
                                             // {
                                                $users_count_var->total_employee = $users_count_var->total_employee;
                                             // }
                                             // else
                                             // {
                                               // $users_count_var->total_employee="0";
                                             // }
                                         
                                          ///////////////////////Total Employees/////////////////

                                          ///////////////////////Total Balance/////////////////

                                              $totalAccountBalance=DB::table('bonus')->where('user_id',$userEntity->id)->where('expire_status','0')->whereRaw("(deleted_at IS null )")->first();
                                              if(!empty($totalAccountBalance))
                                             {
                                               $users_count_var->account_balance = $totalAccountBalance->current_balance;
                                             }
                                             else
                                             {
                                                $users_count_var->account_balance="0";
                                             }
                                         
                                          ///////////////////////Total Balance/////////////////

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

                                          $srname="";$ssubrname="";
                                        if($lang=='es')
                                        {
                                            $srname='services.es_name AS service_name';
                                            $ssubrname='sub_services.es_name AS sub_service_name';
                                        }
                                        else
                                        {
                                            $srname='services.en_name AS service_name';
                                            $ssubrname='sub_services.en_name AS sub_service_name';
                                        }


                                         $servicesOffered = DB::table('services_offered')
                                        ->leftjoin('services', 'services_offered.service_id', '=', 'services.id')
                                        ->leftjoin('sub_services', 'services_offered.sub_service_id', '=', 'sub_services.id')
                                        ->select('services_offered.id','services_offered.user_id','services_offered.service_id',$srname,'services_offered.sub_service_id',$ssubrname,'services_offered.created_at')
                                        ->where('services_offered.user_id',$userEntity->id)->whereRaw("(services_offered.deleted_at IS null )")->orderBy('created_at', 'ASC')->groupBy('services_offered.service_id')->get()->toArray();

                                        if(!empty($servicesOffered))
                                        {
                                                $data1=array();
                                                $allData=array();
                                                foreach ($servicesOffered as $key => $vall) 
                                                {

                                                    $data1['id'] = $vall->id;
                                                    $data1['service_id'] = !empty($vall->service_id) ? $vall->service_id : '' ;
                                                    $data1['service_name'] = !empty($vall->service_name) ? $vall->service_name : '' ;
                                                    $data1['created_at'] =  $vall->created_at;
                                                  
                                                    $subServicesOffered = DB::table('services_offered')
                                                    ->leftjoin('sub_services', 'services_offered.sub_service_id', '=', 'sub_services.id')
                                                    ->select('services_offered.sub_service_id',$ssubrname)
                                                    ->where('services_offered.user_id',$userEntity->id)
                                                    ->where('services_offered.service_id',$vall->service_id)
                                                    ->whereRaw("(services_offered.deleted_at IS null )")
                                                    ->get()->toArray();

                                                    $options=array();
                                                    foreach ($subServicesOffered as $key => $vvalue) 
                                                    {
                                                        if(!empty($vvalue->sub_service_id) && !empty($vvalue->sub_service_name))
                                                        {
                                                            $data2['sub_service_id'] =  !empty($vvalue->sub_service_id) ? $vvalue->sub_service_id : '' ;
                                                            $data2['sub_service_name'] =  !empty($vvalue->sub_service_name) ? $vvalue->sub_service_name : '' ;

                                                            array_push($options, $data2);
                                                        }
                                                        
                                                    }
                                                    
                                                   $data1['sub_services']=$options;
                                                    array_push($allData, $data1);
                                                }

                                                $users_count_var->services_offered = $allData;
                                        }
                                         else
                                         {
                                            $users_count_var->services_offered=[];
                                         }

                                        ///////////////////////services offered/////////////////


                                         ///////////////////////Payment Methods/////////////////

                                         $methodName="";
                                          if($lang=='es')
                                            {$methodName='payment_methods.name_es AS method_name';}
                                         else{$methodName='payment_methods.name_en AS method_name';}

                                          $usersPayMethod = DB::table('user_payment_methods')
                                        ->leftjoin('payment_methods', 'user_payment_methods.payment_method_id', '=', 'payment_methods.id')
                                        ->select('user_payment_methods.id','user_payment_methods.user_id','user_payment_methods.payment_method_id',$methodName,'user_payment_methods.status','user_payment_methods.created_at')
                                        ->where('user_payment_methods.user_id',$userEntity->id)->whereRaw("(user_payment_methods.deleted_at IS null )")->get()->toArray();

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
                                              $allImages2=array();
                                              $allVideo2=array();
                                             if(!empty($allImages))
                                             {
                                                $path=$galleryPath.$userEntity->id.'/';
                                                foreach ($allImages as $key => $value) 
                                                {
                                                    $allImages1['id']=$value->id;
                                                    $allImages1['user_id']=$value->user_id;
                                                    $allImages1['file_name']=url($path.$value->file_name);
                                                    $allImages1['file_type']=$value->file_type;
                                                    $allImages1['status']=$value->status;
                                                    $allImages1['created_at']=$value->created_at;
                                                    array_push($allImages2, $allImages1);
                                                }
                                                
                                                $users_count_var->gallery['images'] = $allImages2;
                                             }
                                             else
                                             {
                                                $users_count_var->gallery['images']=[];
                                             }


                                             $allVideos=DB::table('users_videos_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($allVideos))
                                             {
                                               $path=$videoPath.$userEntity->id.'/';
                                                foreach ($allVideos as $key => $value) 
                                                {
                                                    $allVideo1['id']=$value->id;
                                                    $allVideo1['user_id']=$value->user_id;
                                                    $allVideo1['file_name']=url($path.$value->file_name);
                                                    $allVideo1['file_type']=$value->file_type;
                                                    $allVideo1['status']=$value->status;
                                                    $allVideo1['created_at']=$value->created_at;
                                                    array_push($allVideo2, $allVideo1);
                                                }
                                               $users_count_var->gallery['videos'] = $allVideo2;
                                             }
                                             else
                                             {
                                                $users_count_var->gallery['videos']=[];
                                             }

                                         ///////////////////////Gallery/////////////////


                                         ///////////////////////users Documents/////////////////

                                       
                                               $certi2=array();$certi3=array();
                                               $policeR2=array(); $policeR3=array();

                                            $allCertificatesImages=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',0)->where('file_type',0)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                            
                                             if(!empty($allCertificatesImages))
                                             {
                                                $path=$certifiePath.$userEntity->id.'/img/';
                                                foreach ($allCertificatesImages as $key => $value) 
                                                {
                                                    $allImages1['id']=$value->id;
                                                    $allImages1['user_id']=$value->user_id;
                                                    $allImages1['file_name']=url($path.$value->file_name);
                                                    $allImages1['file_extension']=$value->file_extension;
                                                    $allImages1['status']=$value->status;
                                                    $allImages1['created_at']=$value->created_at;
                                                    array_push($certi2, $allImages1);
                                                }
                                                $users_count_var->cetifications['images'] = $certi2;
                                             }
                                             else
                                             {
                                                $users_count_var->cetifications['images']=[];
                                             }


                                              $DocallCertificates=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',0)->where('file_type',1)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                            
                                             if(!empty($DocallCertificates))
                                             {
                                                $path=$certifiePath.$userEntity->id.'/doc/';
                                                foreach ($DocallCertificates as $key => $val22) 
                                                {
                                                    $allDoc['id']=$val22->id;
                                                    $allDoc['user_id']=$val22->user_id;
                                                    $allDoc['file_name']=url($path.$val22->file_name);
                                                    $allDoc['file_extension']=$val22->file_extension;
                                                    $allDoc['status']=$val22->status;
                                                    $allDoc['created_at']=$val22->created_at;
                                                    array_push($certi3, $allDoc);
                                                }
                                                
                                                $users_count_var->cetifications['documents'] = $certi3;
                                             }
                                             else
                                             {
                                                $users_count_var->cetifications['documents']=[];
                                             }

                                            /////////////////////Police Records///////////////////////////

                                             if($user_group_id==3)
                                             {
                                             $allPoliceRecImage=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',1)->where('file_type',0)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($allPoliceRecImage))
                                             {
                                               $path=$policePath.$userEntity->id.'/img/';
                                                foreach ($allPoliceRecImage as $key => $value) 
                                                {
                                                    $allVideo1['id']=$value->id;
                                                    $allVideo1['user_id']=$value->user_id;
                                                    $allVideo1['file_name']=url($path.$value->file_name);
                                                    $allVideo1['file_type']=$value->file_type;
                                                    $allVideo1['file_extension']=$value->file_extension;
                                                    $allVideo1['status']=$value->status;
                                                    $allVideo1['created_at']=$value->created_at;
                                                    array_push($policeR2, $allVideo1);
                                                }
                                               $users_count_var->police_records['images'] = $policeR2;
                                             }
                                             else
                                             {
                                                $users_count_var->police_records['images']=[];
                                             }

                                             $allPoliceRecDoc=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',1)->where('file_type',1)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($allPoliceRecDoc))
                                             {
                                               $path=$policePath.$userEntity->id.'/doc/';
                                                foreach ($allPoliceRecDoc as $key => $valll) 
                                                {
                                                    $all2['id']=$valll->id;
                                                    $all2['user_id']=$valll->user_id;
                                                    $all2['file_name']=url($path.$valll->file_name);
                                                    $all2['file_type']=$valll->file_type;
                                                    $all2['file_extension']=$valll->file_extension;
                                                    $all2['status']=$valll->status;
                                                    $all2['created_at']=$valll->created_at;
                                                    array_push($policeR3, $all2);
                                                }
                                               $users_count_var->police_records['documents'] = $policeR3;
                                             }
                                             else
                                             {
                                                $users_count_var->police_records['documents']=[];
                                             }
                                         }

                                        ///////////////////////Police Records/////////////////



                                       ///////////////////////Services Area/////////////////

                                     
                                         $allarea = DB::table('users_services_area')
                                        ->leftjoin('provinces', 'users_services_area.province_id', '=', 'provinces.id')
                                        ->leftjoin('cities','users_services_area.city_id', '=', 'cities.id')
                                        ->select('users_services_area.id','users_services_area.whole_country','users_services_area.province_id','users_services_area.city_id','users_services_area.status','users_services_area.created_at','provinces.name AS provinces_name','cities.name AS city_name')
                                        ->where('users_services_area.user_id',$userEntity->id)->whereRaw("(users_services_area.deleted_at IS null )")->groupBy('users_services_area.province_id')->get()->toArray();


                                        if(!empty($allarea))
                                        {
                                                $areaData=array();
                                                $allAreaData=array();
                                                foreach ($allarea as $key => $valuell) 
                                                {

                                                    $areaData['id'] =  $valuell->id;
                                                    $areaData['whole_country'] =  !empty($valuell->whole_country) ? $valuell->whole_country : '' ;
                                                    $areaData['province_id'] =  !empty($valuell->province_id) ? $valuell->province_id : '' ;
                                                    $areaData['provinces_name'] =  !empty($valuell->provinces_name) ? $valuell->provinces_name : '' ;
                                                    $areaData['status'] =  $valuell->status;
                                                    $areaData['created_at'] =  $valuell->created_at;


                                                  
                                                 $allCities = DB::table('users_services_area')
                                                ->leftjoin('cities','users_services_area.city_id', '=', 'cities.id')
                                                ->select('users_services_area.city_id','cities.name AS city_name')
                                                 ->where('users_services_area.user_id',$userEntity->id)
                                                 ->where('users_services_area.province_id',$valuell->province_id)
                                                 ->whereRaw("(users_services_area.deleted_at IS null )")
                                                 ->get()->toArray();


                                                    $cityOption=array();
                                                    foreach ($allCities as $keyd => $svall) 
                                                    {
                                                        if(!empty($svall->city_id) && !empty($svall->city_name))
                                                        {
                                                            $dtr['city_id'] =  !empty($svall->city_id) ? $svall->city_id : '' ;
                                                            $dtr['city_name'] =  !empty($svall->city_name) ? $svall->city_name : '' ;

                                                            array_push($cityOption, $dtr);
                                                        }
                                                     
                                                    }

                                                   
                                                   $areaData['cities']=$cityOption;
                                                   

                                                    array_push($allAreaData, $areaData);
                                                }

                                                $users_count_var->service_areas = $allAreaData;
                                        }
                                         else
                                         {
                                           $users_count_var->service_areas=[];
                                         }

                                        ///////////////////////Services Area/////////////////

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


         function delete_directory($dirname) 
         {
             if (is_dir($dirname))
                   $dir_handle = opendir($dirname);
             if (!$dir_handle)
                  return false;
             while($file = readdir($dir_handle)) {
                   if ($file != "." && $file != "..") {
                        if (!is_dir($dirname."/".$file))
                             unlink($dirname."/".$file);
                        else
                             delete_directory($dirname.'/'.$file);
                   }
             }
             closedir($dir_handle);
             rmdir($dirname);
             return true;
        }
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
          * Get STATE (provinces) LIST API START HERE
          */
        public function getProvincesList(Request $request)
        {
            $access_token=123456;
            $docArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            App::setLocale($lang);

             $allData=array();
             
             $provinces = DB::table('provinces')
            ->select('id','name','status','created_at')
            ->whereRaw("(status=1)")
            ->whereRaw("(deleted_at IS null)")
            ->get(); 

                foreach ($provinces as $key => $vall) 
                {

                    $data1['id'] = $vall->id;
                    $data1['name'] = $vall->name;
                    $data1['status'] = $vall->status;
                    $data1['created_at'] = $vall->created_at;
                     
                    array_push($allData, $data1);

                }

            if($allData)
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('Provinces list found successfully.!');
                $resultArray['data']=$allData;                  
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
          * Get STATE (provinces) LIST API END HERE
          */




         /*
          * Get city (area) LIST API START HERE
          */
        public function getCitiesList(Request $request)
        {
            $access_token=123456;
            $docArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            $province_id = !empty($request->province_id) ? $request->province_id : '';//1,2,3
            $province_ids = explode (",", $province_id);
            App::setLocale($lang);

             $allData=array();
             
                    $area = DB::table('cities')
                    ->select('id','name','province_id','status','created_at')
                    ->whereRaw("(status=1)")
                    ->whereIN('province_id', $province_ids)
                    ->whereRaw("(deleted_at IS null)")
                    ->get()->toArray(); 

                foreach ($area as $key => $vall) 
                {

                        $data1['id'] = $vall->id;
                        $data1['province_id'] = $vall->province_id;
                        $data1['name'] = $vall->name;
                        $data1['status'] = $vall->status;
                        $data1['created_at'] = $vall->created_at;
                     
                    array_push($allData, $data1);

                }

            if($allData)
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('Cities list found successfully.!');
                $resultArray['data']=$allData;                  
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
          * Get city (area) LIST API END HERE
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
                                $resultArray['message']=trans('Mobile Number Already Exist');
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





         public function moveToFolder(Request $request)
         {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'folder_id' => 'required',
                'request_id' => 'required',
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
            $folder_id = isset($request->folder_id) && !empty($request->folder_id) ? $request->folder_id : '' ;
            $request_id = isset($request->request_id) && !empty($request->request_id) ? $request->request_id : '' ;
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
                          $servicesRequested = DB::table('service_request')->where('service_request.id',$request_id)->where('service_request.user_id',$user_id)->first();

                          if($servicesRequested) 
                          {

                                $insert['folder_id'] = $folder_id;    
                                $insert['requested_service_id'] = $request_id;
                                $insert['status'] = 1;
                                $insert['created_at'] = Carbon::now();  
                                $lastId=DB::table('folder_projects')->insertGetId($insert);

                                    $resultArray['status']='1';
                                    $resultArray['message']='Your Request Moved In folder successfully.';
                                    return json_encode($resultArray);
                          }
                          else
                          {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('Invalid Request Id.');
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

                        $allData=array();
                        $data1=array();

                        foreach ($folderEntity as $key => $value) 
                        {
                           $data1['id']= $value->id;
                           $data1['name']= $value->name;
                           $data1['created_at']= $value->created_at;

                         $folderProjectCount = DB::table('folder_projects')
                        ->select('folder_projects.requested_service_id')
                        ->whereRaw("(folder_projects.folder_id = '".$value->id."')")
                        ->get()->toArray(); 

                           $options=array();

                            foreach ($folderProjectCount as $key => $pcount) 
                            {
                                $procount['request_id']=$pcount->requested_service_id;

                                 array_push($options, $procount);
                            }

                            

                            if(!empty($options))
                                 {
                                   $data1['project_in_folder']=$options ;
                                 }
                                 else
                                 {
                                    $data1['project_in_folder']=[];
                                 }
                             array_push($allData, $data1);

                        }

                        if($allData)
                        {
                            $resultArray['status']='1';
                            $resultArray['data']=$allData;
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
                                            $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','created_at','updated_at')->where('id',$userEntity->id)->first();


                                            $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';

                                            $users_count_var->email = isset($users_count_var->email) && !empty($users_count_var->email) ? $users_count_var->email : '';

                                            $users_count_var->username = isset($users_count_var->username) && !empty($users_count_var->username) ? $users_count_var->username : '';

                                            $users_count_var->dob = isset($users_count_var->dob) && !empty($users_count_var->dob) ? $users_count_var->dob : '';

                                             $users_count_var->address = isset($users_count_var->address) && !empty($users_count_var->address) ? $users_count_var->address : '';

                                             $users_count_var->address_lat = isset($users_count_var->address_lat) && !empty($users_count_var->address_lat) ? $users_count_var->address_lat : '0';

                                             $users_count_var->address_long = isset($users_count_var->address_long) && !empty($users_count_var->address_long) ? $users_count_var->address_long : '0';

                                             $users_count_var->office_address = isset($users_count_var->office_address) && !empty($users_count_var->office_address) ? $users_count_var->office_address : '';

                                             $users_count_var->office_address_lat = isset($users_count_var->office_address_lat) && !empty($users_count_var->office_address_lat) ? $users_count_var->office_address_lat : '0';

                                             $users_count_var->office_address_long = isset($users_count_var->office_address_long) && !empty($users_count_var->office_address_long) ? $users_count_var->office_address_long : '0';
                                             $users_count_var->other_address = isset($users_count_var->other_address) && !empty($users_count_var->other_address) ? $users_count_var->other_address : '';

                                             $users_count_var->other_address_lat = isset($users_count_var->other_address_lat) && !empty($users_count_var->other_address_lat) ? $users_count_var->other_address_lat : '0';

                                             $users_count_var->other_address_long = isset($users_count_var->other_address_long) && !empty($users_count_var->other_address_long) ? $users_count_var->other_address_long : '0';

                                            $users_count_var->mobile_number = isset($users_count_var->mobile_number) && !empty($users_count_var->mobile_number) ? $users_count_var->mobile_number : '';

                                            $users_count_var->landline_number = isset($users_count_var->landline_number) && !empty($users_count_var->landline_number) ? $users_count_var->landline_number : '';
                                            $users_count_var->office_number = isset($users_count_var->office_number) && !empty($users_count_var->office_number) ? $users_count_var->office_number : '';



                                            if(!empty($users_count_var->mobile_number) && !empty($users_count_var->email))
                                            {
                                                $users_count_var->is_profile_complete = true;
                                            }else
                                            {
                                                 $users_count_var->is_profile_complete = false;
                                            }



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

                                        //************************************************//

                                        //start Contractor & Company

                                        else if($userEntity->user_group_id==3 || $userEntity->user_group_id==4)
                                        {


                                        if($userEntity->user_group_id==3)
                                        {
                                            $profilePath ='/img/contractor/profile/';
                                            $policePath ='/img/contractor/police_records/';
                                            $certifiePath ='/img/contractor/certifications/';
                                            $galleryPath ='/img/contractor/gallery/images/';
                                            $videoPath = '/img/contractor/gallery/videos/';
                                        }else
                                        {
                                            $profilePath ='/img/company/profile/';
                                            $policePath ='/img/company/police_records/';
                                            $certifiePath ='/img/company/certifications/';
                                            $galleryPath ='/img/company/gallery/images/';
                                            $videoPath = '/img/company/gallery/videos/';
                                        }



                                        if($userEntity->user_group_id==4)
                                        {
                                              $users_count_var=DB::table('users')->select('users.id','users.ruc_no','users.legal_representative','users.website_address','users.year_of_constitution','users.user_group_id','users.email','users.username','users.profile_title','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userEntity->id)->first();
                                        }
                                        else
                                        {
                                             $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userEntity->id)->first();
                                        }


                                          

                                          if($userEntity->user_group_id==4)
                                          {

                                           $users_count_var->ruc_no = isset($users_count_var->ruc_no) && !empty($users_count_var->ruc_no) ? $users_count_var->ruc_no : '';
                                            $users_count_var->legal_representative = isset($users_count_var->legal_representative) && !empty($users_count_var->legal_representative) ? $users_count_var->legal_representative : '';

                                             $users_count_var->website_address = isset($users_count_var->website_address) && !empty($users_count_var->website_address) ? $users_count_var->website_address : '';

                                              $users_count_var->year_of_constitution = isset($users_count_var->year_of_constitution) && !empty($users_count_var->year_of_constitution) ? $users_count_var->year_of_constitution : '';

                                           if(!empty($users_count_var->username) && !empty($users_count_var->ruc_no) && !empty($users_count_var->legal_representative) && !empty($users_count_var->mobile_number))
                                            {
                                                $users_count_var->is_profile_complete = true;
                                            }else
                                            {
                                                 $users_count_var->is_profile_complete = false;
                                            }

                                          }
                                          else if($userEntity->user_group_id==3)
                                          {
                                             $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';

                                              if(!empty($users_count_var->username) && !empty($users_count_var->identity_no) && !empty($users_count_var->mobile_number))
                                                {
                                                    $users_count_var->is_profile_complete = true;
                                                }else
                                                {
                                                     $users_count_var->is_profile_complete = false;
                                                }
                                          }

                                            $users_count_var->email = isset($users_count_var->email) && !empty($users_count_var->email) ? $users_count_var->email : '';

                                            $users_count_var->username = isset($users_count_var->username) && !empty($users_count_var->username) ? $users_count_var->username : '';
                                            $users_count_var->profile_title = isset($users_count_var->profile_title) && !empty($users_count_var->profile_title) ? $users_count_var->profile_title : '';

                                            $users_count_var->dob = isset($users_count_var->dob) && !empty($users_count_var->dob) ? $users_count_var->dob : '';

                                            $users_count_var->address = isset($users_count_var->address) && !empty($users_count_var->address) ? $users_count_var->address : '';

                                             $users_count_var->address_lat = isset($users_count_var->address_lat) && !empty($users_count_var->address_lat) ? $users_count_var->address_lat : '0';

                                             $users_count_var->address_long = isset($users_count_var->address_long) && !empty($users_count_var->address_long) ? $users_count_var->address_long : '0';

                                             $users_count_var->office_address = isset($users_count_var->office_address) && !empty($users_count_var->office_address) ? $users_count_var->office_address : '';

                                             $users_count_var->office_address_lat = isset($users_count_var->office_address_lat) && !empty($users_count_var->office_address_lat) ? $users_count_var->office_address_lat : '0';

                                             $users_count_var->office_address_long = isset($users_count_var->office_address_long) && !empty($users_count_var->office_address_long) ? $users_count_var->office_address_long : '0';
                                             $users_count_var->other_address = isset($users_count_var->other_address) && !empty($users_count_var->other_address) ? $users_count_var->other_address : '';

                                             $users_count_var->other_address_lat = isset($users_count_var->other_address_lat) && !empty($users_count_var->other_address_lat) ? $users_count_var->other_address_lat : '0';

                                             $users_count_var->other_address_long = isset($users_count_var->other_address_long) && !empty($users_count_var->other_address_long) ? $users_count_var->other_address_long : '0';

                                            $users_count_var->mobile_number = isset($users_count_var->mobile_number) && !empty($users_count_var->mobile_number) ? $users_count_var->mobile_number : '';

                                            $users_count_var->landline_number = isset($users_count_var->landline_number) && !empty($users_count_var->landline_number) ? $users_count_var->landline_number : '';

                                            $users_count_var->office_number = isset($users_count_var->office_number) && !empty($users_count_var->office_number) ? $users_count_var->office_number : '';

                                            $users_count_var->profile_description = isset($users_count_var->profile_description) && !empty($users_count_var->profile_description) ? $users_count_var->profile_description : '';

                                            $users_count_var->total_employee = $users_count_var->total_employee;

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
                                             $path=$profilePath;
                                             $users_count_var->avatar_location = url($path.$users_count_var->avatar_location);
                                            }
                                            else
                                            {
                                             $users_count_var->avatar_location ="";
                                            }


                                          ///////////////////////Total Employees/////////////////

                                             //  $totalEmployee=DB::table('workers')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                             //  if(!empty($totalEmployee))
                                             // {
                                             //   $users_count_var->total_employee = count($totalEmployee);
                                             // }
                                             // else
                                             // {
                                             //    $users_count_var->total_employee="0";
                                             // }
                                         
                                          ///////////////////////Total Employees/////////////////

                                         ///////////////////////Total Balance/////////////////

                                              $totalAccountBalance=DB::table('bonus')->where('user_id',$userEntity->id)->where('expire_status','0')->whereRaw("(deleted_at IS null )")->first();
                                              if(!empty($totalAccountBalance))
                                             {
                                               $users_count_var->account_balance = $totalAccountBalance->current_balance;
                                             }
                                             else
                                             {
                                                $users_count_var->account_balance="0";
                                             }
                                         
                                          ///////////////////////Total Balance/////////////////
         
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

                                          $srname="";$ssubrname="";
                                        if($lang=='es')
                                        {
                                            $srname='services.es_name AS service_name';
                                            $ssubrname='sub_services.es_name AS sub_service_name';
                                        }
                                        else
                                        {
                                            $srname='services.en_name AS service_name';
                                            $ssubrname='sub_services.en_name AS sub_service_name';
                                        }


                                         $servicesOffered = DB::table('services_offered')
                                        ->leftjoin('services', 'services_offered.service_id', '=', 'services.id')
                                        ->leftjoin('sub_services', 'services_offered.sub_service_id', '=', 'sub_services.id')
                                        ->select('services_offered.id','services_offered.user_id','services_offered.service_id',$srname,'services_offered.sub_service_id',$ssubrname,'services_offered.created_at')
                                        ->where('services_offered.user_id',$userEntity->id)->whereRaw("(services_offered.deleted_at IS null )")->orderBy('created_at', 'ASC')->groupBy('services_offered.service_id')->get()->toArray();

                                        if(!empty($servicesOffered))
                                        {
                                                $data1=array();
                                                $allData=array();
                                                foreach ($servicesOffered as $key => $vall) 
                                                {

                                                    $data1['id'] = $vall->id;
                                                    $data1['service_id'] = !empty($vall->service_id) ? $vall->service_id : '' ;
                                                    $data1['service_name'] = !empty($vall->service_name) ? $vall->service_name : '' ;
                                                    $data1['created_at'] =  $vall->created_at;
                                                  
                                                    $subServicesOffered = DB::table('services_offered')
                                                    ->leftjoin('sub_services', 'services_offered.sub_service_id', '=', 'sub_services.id')
                                                    ->select('services_offered.sub_service_id',$ssubrname)
                                                    ->where('services_offered.user_id',$userEntity->id)
                                                    ->where('services_offered.service_id',$vall->service_id)
                                                    ->whereRaw("(services_offered.deleted_at IS null )")
                                                    ->get()->toArray();

                                                    $options=array();
                                                    foreach ($subServicesOffered as $key => $vvalue) 
                                                    {
                                                        if(!empty($vvalue->sub_service_id) && !empty($vvalue->sub_service_name))
                                                        {
                                                            $data2['sub_service_id'] =  !empty($vvalue->sub_service_id) ? $vvalue->sub_service_id : '' ;
                                                            $data2['sub_service_name'] =  !empty($vvalue->sub_service_name) ? $vvalue->sub_service_name : '' ;

                                                            array_push($options, $data2);
                                                        }
                                                    }
                                                   
                                                    $data1['sub_services']=$options;
                                                    array_push($allData, $data1);
                                                }

                                                $users_count_var->services_offered = $allData;
                                        }
                                         else
                                         {
                                            $users_count_var->services_offered=[];
                                         }

                                        ///////////////////////services offered/////////////////


                                         ///////////////////////Payment Methods/////////////////

                                         $methodName="";
                                          if($lang=='es')
                                            {$methodName='payment_methods.name_es AS method_name';}
                                         else{$methodName='payment_methods.name_en AS method_name';}

                                          $usersPayMethod = DB::table('user_payment_methods')
                                        ->leftjoin('payment_methods', 'user_payment_methods.payment_method_id', '=', 'payment_methods.id')
                                        ->select('user_payment_methods.id','user_payment_methods.user_id','user_payment_methods.payment_method_id',$methodName,'user_payment_methods.status','user_payment_methods.created_at')
                                        ->where('user_payment_methods.user_id',$userEntity->id)->whereRaw("(user_payment_methods.deleted_at IS null )")->get()->toArray();

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
                                              $allImages2=array();
                                              $allVideo2=array();
                                             if(!empty($allImages))
                                             {
                                                $path=$galleryPath.$userEntity->id.'/';
                                                foreach ($allImages as $key => $value) 
                                                {
                                                    $allImages1['id']=$value->id;
                                                    $allImages1['user_id']=$value->user_id;
                                                    $allImages1['file_name']=url($path.$value->file_name);
                                                    $allImages1['file_type']=$value->file_type;
                                                    $allImages1['status']=$value->status;
                                                    $allImages1['created_at']=$value->created_at;
                                                    array_push($allImages2, $allImages1);
                                                }
                                                
                                                $users_count_var->gallery['images'] = $allImages2;
                                             }
                                             else
                                             {
                                                $users_count_var->gallery['images']=[];
                                             }


                                             $allVideos=DB::table('users_videos_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($allVideos))
                                             {
                                               $path=$videoPath.$userEntity->id.'/';
                                                foreach ($allVideos as $key => $value) 
                                                {
                                                    $allVideo1['id']=$value->id;
                                                    $allVideo1['user_id']=$value->user_id;
                                                    $allVideo1['file_name']=url($path.$value->file_name);
                                                    $allVideo1['file_type']=$value->file_type;
                                                    $allVideo1['status']=$value->status;
                                                    $allVideo1['created_at']=$value->created_at;
                                                    array_push($allVideo2, $allVideo1);
                                                }
                                               $users_count_var->gallery['videos'] = $allVideo2;
                                             }
                                             else
                                             {
                                                $users_count_var->gallery['videos']=[];
                                             }

                                         ///////////////////////Gallery/////////////////


                                        ///////////////////////users Documents/////////////////

                                       
                                               $certi2=array();$certi3=array();
                                               $policeR2=array(); $policeR3=array();

                                            $allCertificatesImages=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',0)->where('file_type',0)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                            
                                             if(!empty($allCertificatesImages))
                                             {
                                                $path=$certifiePath.$userEntity->id.'/img/';
                                                foreach ($allCertificatesImages as $key => $value) 
                                                {
                                                    $allImages1['id']=$value->id;
                                                    $allImages1['user_id']=$value->user_id;
                                                    $allImages1['file_name']=url($path.$value->file_name);
                                                    $allImages1['file_extension']=$value->file_extension;
                                                    $allImages1['status']=$value->status;
                                                    $allImages1['created_at']=$value->created_at;
                                                    array_push($certi2, $allImages1);
                                                }
                                                $users_count_var->cetifications['images'] = $certi2;
                                             }
                                             else
                                             {
                                                $users_count_var->cetifications['images']=[];
                                             }


                                              $DocallCertificates=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',0)->where('file_type',1)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                            
                                             if(!empty($DocallCertificates))
                                             {
                                                $path=$certifiePath.$userEntity->id.'/doc/';
                                                foreach ($DocallCertificates as $key => $val22) 
                                                {
                                                    $allDoc['id']=$val22->id;
                                                    $allDoc['user_id']=$val22->user_id;
                                                    $allDoc['file_name']=url($path.$val22->file_name);
                                                    $allDoc['file_extension']=$val22->file_extension;
                                                    $allDoc['status']=$val22->status;
                                                    $allDoc['created_at']=$val22->created_at;
                                                    array_push($certi3, $allDoc);
                                                }
                                                
                                                $users_count_var->cetifications['documents'] = $certi3;
                                             }
                                             else
                                             {
                                                $users_count_var->cetifications['documents']=[];
                                             }

                                            /////////////////////Police Record////////////////////////////

                                             if($userEntity->user_group_id==3)
                                             {
                                             $allPoliceRecImage=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',1)->where('file_type',0)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($allPoliceRecImage))
                                             {
                                               $path=$policePath.$userEntity->id.'/img/';
                                                foreach ($allPoliceRecImage as $key => $value) 
                                                {
                                                    $allVideo1['id']=$value->id;
                                                    $allVideo1['user_id']=$value->user_id;
                                                    $allVideo1['file_name']=url($path.$value->file_name);
                                                    $allVideo1['file_type']=$value->file_type;
                                                    $allVideo1['file_extension']=$value->file_extension;
                                                    $allVideo1['status']=$value->status;
                                                    $allVideo1['created_at']=$value->created_at;
                                                    array_push($policeR2, $allVideo1);
                                                }
                                               $users_count_var->police_records['images'] = $policeR2;
                                             }
                                             else
                                             {
                                                $users_count_var->police_records['images']=[];
                                             }

                                             $allPoliceRecDoc=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',1)->where('file_type',1)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($allPoliceRecDoc))
                                             {
                                               $path=$policePath.$userEntity->id.'/doc/';
                                                foreach ($allPoliceRecDoc as $key => $valll) 
                                                {
                                                    $all2['id']=$valll->id;
                                                    $all2['user_id']=$valll->user_id;
                                                    $all2['file_name']=url($path.$valll->file_name);
                                                    $all2['file_type']=$valll->file_type;
                                                    $all2['file_extension']=$valll->file_extension;
                                                    $all2['status']=$valll->status;
                                                    $all2['created_at']=$valll->created_at;
                                                    array_push($policeR3, $all2);
                                                }
                                               $users_count_var->police_records['documents'] = $policeR3;
                                             }
                                             else
                                             {
                                                $users_count_var->police_records['documents']=[];
                                             }
                                         }

                                        ///////////////////////Police Record/////////////////



                                        ///////////////////////Services Area/////////////////

                                     
                                         $allarea = DB::table('users_services_area')
                                        ->leftjoin('provinces', 'users_services_area.province_id', '=', 'provinces.id')
                                        ->leftjoin('cities','users_services_area.city_id', '=', 'cities.id')
                                        ->select('users_services_area.id','users_services_area.whole_country','users_services_area.province_id','users_services_area.city_id','users_services_area.status','users_services_area.created_at','provinces.name AS provinces_name','cities.name AS city_name')
                                        ->where('users_services_area.user_id',$userEntity->id)->whereRaw("(users_services_area.deleted_at IS null )")->groupBy('users_services_area.province_id')->get()->toArray();


                                        if(!empty($allarea))
                                        {
                                                $areaData=array();
                                                $allAreaData=array();
                                                foreach ($allarea as $key => $valuell) 
                                                {

                                                    $areaData['id'] =  $valuell->id;
                                                    $areaData['whole_country'] =  !empty($valuell->whole_country) ? $valuell->whole_country : '' ;
                                                    $areaData['province_id'] =  !empty($valuell->province_id) ? $valuell->province_id : '' ;
                                                    $areaData['provinces_name'] =  !empty($valuell->provinces_name) ? $valuell->provinces_name : '' ;
                                                    $areaData['status'] =  $valuell->status;
                                                    $areaData['created_at'] =  $valuell->created_at;


                                                  
                                                 $allCities = DB::table('users_services_area')
                                                ->leftjoin('cities','users_services_area.city_id', '=', 'cities.id')
                                                ->select('users_services_area.city_id','cities.name AS city_name')
                                                 ->where('users_services_area.user_id',$userEntity->id)
                                                 ->where('users_services_area.province_id',$valuell->province_id)
                                                 ->whereRaw("(users_services_area.deleted_at IS null )")
                                                 ->get()->toArray();


                                                    $cityOption=array();
                                                    foreach ($allCities as $keyd => $svall) 
                                                    {
                                                        if(!empty($svall->city_id) && !empty($svall->city_name))
                                                        {
                                                            $dtr['city_id'] =  !empty($svall->city_id) ? $svall->city_id : '' ;
                                                            $dtr['city_name'] =  !empty($svall->city_name) ? $svall->city_name : '' ;

                                                            array_push($cityOption, $dtr);
                                                        }
                                    
                                                     
                                                    }

                                                   
                                                   $areaData['cities']=$cityOption;
                                                    array_push($allAreaData, $areaData);
                                                }

                                                $users_count_var->service_areas = $allAreaData;
                                        }
                                         else
                                         {
                                           $users_count_var->service_areas=[];
                                         }

                                        ///////////////////////Services Area/////////////////
                                         

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

                                        $allOpprtunities = DB::table('assign_service_request')
                                        ->join('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                                         ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                                        ->select('assign_service_request.id','assign_service_request.service_request_id','assign_service_request.user_id','assign_service_request.request_status','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image')
                                         ->whereRaw("(assign_service_request.user_id = '".$userid."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status IS null)")
                                         ->where('service_request.status','0')
                                         ->groupBy('assign_service_request.service_request_id')->get(); 


                                   if(!empty($allOpprtunities))
                                   {

                                    $data1=array();
                                    foreach ($allOpprtunities as $key => $vall) 
                                    {

                                        $data1['id'] = $vall->id;
                                        $data1['user_id'] = $vall->user_id;
                                        $data1['service_request_id'] = $vall->service_request_id;
                                        $data1['request_status'] = isset($vall->request_status) && !empty($vall->request_status) ? $vall->request_status : '';
                                        $data1['service_id'] = $vall->service_id;
                                         if($lang=='es')
                                                {$service_name=$vall->es_name;}
                                            else{$service_name=$vall->en_name;}

                                        $data1['service_name'] = $service_name;
                                        $data1['service_image'] = url('/img/'.$vall->image);
                                        $data1['location'] = $vall->location;
                                        $data1['username'] = $vall->username;
                                        $data1['status'] = $vall->status;
                                        $data1['email_verify'] = $vall->email_verify;
                                        $data1['created_at'] = $vall->created_at;

                                        
                                        $servicesRequestedQues = DB::table('service_request_questions')
                                        ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                                         ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                                        ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option')
                                        ->whereRaw("(service_request_questions.service_request_id = '".$vall->service_request_id."')")->get()->toArray(); 

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


                                    if(!empty($data1))
                                    {
                                        $resultArray['status']='1';   
                                        $resultArray['message']=trans('Opportunities List Found Successfully.!');
                                        $resultArray['data'] = $allData; 
                                        echo json_encode($resultArray); exit;   
                                    }
                                    else
                                    {
                                        $resultArray['status']='0';   
                                        $resultArray['message']=trans('Opportunities List Not Found.!');
                                        echo json_encode($resultArray); exit;   
                                    }
                                    
                                        

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
 
  
                 if(!empty($userid) && !empty($session_key) && !empty($opportunity_id)) 
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
                                        $resultArray['message']=trans('Requested opportunity is already Assigned another professionals.');
                                        echo json_encode($resultArray); exit;

                                    }else
                                    {

                                        $chkUserRecivedOpprtOrNot = DB::table('assign_service_request')
                                        ->whereRaw("(id = '".$opportunity_id."' AND deleted_at IS null AND user_id = '".$userid."')")
                                        ->first();

                                        if(!empty($chkUserRecivedOpprtOrNot))
                                         {  

                                            if($chkUserRecivedOpprtOrNot->request_status=='buy')
                                            {
                                             $resultArray['status']='0';
                                             $resultArray['message']=trans('Opportunity Already Accepted.!');
                                              echo json_encode($resultArray); exit;
                                            } else
                                             {

                                                 $chkThreeUserLimit = DB::table('assign_service_request')
                                                ->whereRaw("(service_request_id = '".$opportunity_id."' AND request_status = '".'buy'."')")->count();

                                                if($chkThreeUserLimit < 3)
                                                {
                                                    //Check User Balance And Deduct Accordingly Request Amount
                                                    // And Admin Commision

                                                    //Amount Deduct Bonus Table

                                                    //End
                                                    $update_Arr['request_status'] = 'buy';    
                                                    $update_Arr['tranx_id'] = $tranx_id;
                                                    $update_Arr['tranx_status'] = $tranx_status;
                                                    $update_Arr['currency'] = $currency;
                                                    $update_Arr['amount'] = $amount;
                                                    $update_Arr['updated_at'] = Carbon::now();  
                                                    
                                                    DB::table('assign_service_request')
                                                    ->whereRaw("(id = '".$opportunity_id."' AND user_id = '".$userid."')")->update($update_Arr);

                                                      $resultArray['status']='1';
                                                        $resultArray['message']=trans('Opportunity Buy Successfully.!');
                                                        echo json_encode($resultArray); exit;

                                                 }else
                                                 {
                                                        $resultArray['status']='0';
                                                        $resultArray['message']=trans('This Opportunity Alfready Taken By Another Three professionals OR Company.!');
                                                        echo json_encode($resultArray); exit; 
                                                 }
                                            }

                                          //here    

                                       }else
                                       {
                                                $resultArray['status']='0';
                                                $resultArray['message']=trans("You don't have this opportunity.Please update your profile offerd services to get new Opportunities.");
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



         /* --------------------Opportunity IGNORE Api Start-------------------- */


        public function ignoreOpportunity(Request $request)
        {

            $access_token=123456;
            $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            $user_group_id = !empty($request->user_group_id) ? $request->user_group_id : '' ;
            $opportunity_id = !empty($request->opportunity_id) ? $request->opportunity_id : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

             $validator = Validator::make($request->all(), [
                    'userid' => 'required',
                    'session_key' => 'required',
                    'user_group_id' => 'required',
                    'opportunity_id' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 
 
  
                 if(!empty($userid) && !empty($session_key) && !empty($opportunity_id))
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

                                    if($opportunity->status!=='0' && !empty($opportunity->assigned_user_id))
                                    {

                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Requested opportunity is already Assigned another professionals.');
                                        echo json_encode($resultArray); exit;

                                    }else
                                    {

                                        $chkUserRecivedOpprtOrNot = DB::table('assign_service_request')
                                        ->whereRaw("(service_request_id = '".$opportunity_id."' AND deleted_at IS null AND user_id = '".$userid."')")
                                        ->first();

                                        if(!empty($chkUserRecivedOpprtOrNot))
                                         {   

                                            if($chkUserRecivedOpprtOrNot->request_status=='buy')
                                            {

                                             $resultArray['status']='0';
                                             $resultArray['message']=trans('Opportunity Already Accepted. Now you can not Ignore.!');
                                              echo json_encode($resultArray); exit;

                                            }
                                            else if($chkUserRecivedOpprtOrNot->request_status=='ignore')
                                            {
                                             $resultArray['status']='0';
                                             $resultArray['message']=trans('Opportunity Already Ignored.!');
                                              echo json_encode($resultArray); exit;
                                            }
                                            else
                                            {
                                                $update_Arr['request_status'] = 'ignore';    
                                              $update_Arr['updated_at'] = Carbon::now();  
                                                
                                              DB::table('assign_service_request')
                                                ->whereRaw("(service_request_id = '".$opportunity_id."' AND user_id = '".$userid."')")->update($update_Arr);

                                              $resultArray['status']='1';
                                                $resultArray['message']=trans('Opportunity Ignore Successfully.!');
                                              echo json_encode($resultArray); exit;
                                            }

                                       }else
                                       {
                                                $resultArray['status']='0';
                                                $resultArray['message']=trans("You don't have this opportunity.Please update your profile offerd services to get new Opportunities.");
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

        //Request LIst That is Purchase By Company & contractor 
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
                                 $servicesbuy = DB::table('assign_service_request')
                                ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                                ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                                ->select('service_request.id','service_request.assigned_user_id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image','assign_service_request.tranx_status','assign_service_request.tranx_id')
                                ->where('assign_service_request.user_id',$userid)
                                ->where('assign_service_request.tranx_status','1')
                                ->where('assign_service_request.request_status','buy')
                                ->whereRaw("(service_request.deleted_at IS null )")->get();

                                 //echo "<pre>"; print_r($servicesbuy); die;

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


                                        if(!empty($vall->assigned_user_id) && $vall->assigned_user_id!==NULL)
                                        {
                                            $userSideAccept='accepted';
                                        }else
                                        {
                                            $userSideAccept='notaccepted';
                                        }

                                        $data1['service_name'] = $service_name;
                                        $data1['service_image'] = url('/img/'.$vall->image);
                                        $data1['location'] = $vall->location;
                                        $data1['username'] = $vall->username;
                                        $data1['request_status'] = $vall->request_status;
                                        $data1['job_status'] = $userSideAccept;
                                        $data1['email_verify'] = $vall->email_verify;
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



          /* --------------------Job Detail Api Start-------------------- */


        public function jobDetail(Request $request)
        {
            $access_token=123456;
            $allData=array();
            $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
            $job_id = isset($request->job_id) && !empty($request->job_id) ? $request->job_id : '' ;
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

            $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'job_id' => 'required',
            'session_key' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 

              if(!empty($userid) && !empty($session_key) && !empty($job_id)) 
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
                                $servicesbuy = DB::table('assign_service_request')
                                ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                                ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                                ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image','assign_service_request.tranx_status','assign_service_request.tranx_id','service_request.mobile_number','service_request.email','service_request.user_id','service_request.latitude','service_request.longitude')
                                ->where('service_request.id',$job_id)
                                ->where('assign_service_request.user_id',$userid)
                                ->where('assign_service_request.tranx_status','1')
                                ->where('assign_service_request.request_status','buy')
                                ->whereRaw("(service_request.deleted_at IS null )")->get()->toArray();


                                  $data1=array();
                                  if(!empty($servicesbuy))
                                   {
                                    foreach ($servicesbuy as $key => $vall) 
                                    {

                                        $data1['id'] = $vall->id;
                                        $data1['service_id'] = $vall->service_id;
                                         if($lang=='es')
                                                {$service_name=$vall->es_name;}
                                            else{$service_name=$vall->en_name;}

                                        $data1['service_name'] = $service_name;
                                        $data1['service_image'] = url('/img/'.$vall->image);
                                        $data1['location'] = $vall->location;
                                        $data1['latitude'] = $vall->latitude;
                                        $data1['longitude'] = $vall->longitude;
                                        $data1['client_id'] = $vall->user_id;
                                        $data1['username'] = $vall->username;
                                        $data1['mobile_number'] = $vall->mobile_number;
                                        $data1['email'] = $vall->email;
                                        $data1['request_status'] = $vall->request_status;
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


                                         // GET ALL CONTRACTOR & COMPANY , RECIVED NOTIFICATION OF THIS REQUEST


                                        $getAllAssignedCoAndCom = DB::table('assign_service_request')
                                        ->leftjoin('users', 'assign_service_request.user_id', '=', 'users.id')
                                        ->select('users.id','users.username','users.email','users.mobile_number','assign_service_request.request_status')
                                         ->whereRaw("(assign_service_request.service_request_id = '".$job_id."')")->get()->toArray(); 

                                        $secOptions=array();

                                        foreach ($getAllAssignedCoAndCom as $key => $datas) 
                                        {
                                           $assData['id'] = $datas->id;
                                           $assData['username'] = $datas->username;
                                           $assData['email'] = $datas->email;
                                           $assData['mobile_number'] = $datas->mobile_number;
                                           $assData['request_status'] = isset($datas->request_status) && !empty($datas->request_status) ? $datas->request_status : '';

                                            array_push($secOptions, $assData);
                                        }

                                        //END

                                        $data1['question_options']=$options ;

                                        $data1['assigned_contractor_and_companies']=$secOptions ;

                                        //array_push($allData, $data1);

                                    }

                                    $resultArray['status']='1';   
                                    $resultArray['message']=trans('Job Detail Found Successfully.!');
                                    $resultArray['data'] = $data1; 
                                    echo json_encode($resultArray); exit;
                                }

                             else
                               {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('List not found.!');
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


         /* --------------------Job Detail Api End-------------------- */


         /* --------------------CHAT (Send msg to users & contractor) Api Start-------------------- */


         public function sendMessageToUsers(Request $request)
         {
                $access_token=123456;
                $from_userid = isset($request->from_userid) && !empty($request->from_userid) ? $request->from_userid : '' ;
                $to_userid = isset($request->to_userid) && !empty($request->to_userid) ? $request->to_userid : '' ;
                $message = isset($request->message) && !empty($request->message) ? $request->message : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'from_userid' => 'required',
                'to_userid' => 'required',
                'message' =>'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($from_userid) && !empty($to_userid) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $from_userid, $session_key, $lang);
                        if($check_auth['status']!=1)
                        {
                         echo json_encode($check_auth); exit;
                        }
                        else
                        {
                            $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$from_userid."' AND deleted_at IS null )")
                            ->first();

                            $userTwoEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$to_userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($userEntity) && !empty($userTwoEntity))
                            {
                                    $insert['from_userid'] = $from_userid;    
                                    $insert['to_userid'] = $to_userid;
                                    $insert['message'] = $message;
                                    $insert['is_read'] = '0';
                                    $insert['is_starred'] = '0';
                                    $insert['created_at'] = Carbon::now();  
                                    $lastId=DB::table('users_chat')->insertGetId($insert);  

                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('Message Send Successfully.!');
                                    echo json_encode($resultArray); exit;      
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

        /* --------------------CHAT Api End-------------------- */


         /* --------------------GET COUNTING OF CHAT LIST BY USER ID Api Start-------------------- */

         public function unreadMessageCountByUserId(Request $request)
         {
                $access_token=123456;
                $allData=array();
                $msg="";
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

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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

                                /// Chat Count Start Here//

                                 $chatListCount = DB::table('users_chat')
                                ->leftjoin('users', 'users_chat.from_userid', '=', 'users.id')
                                ->select('users_chat.id','users_chat.message','users_chat.created_at','users.id AS from_user_id','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by')
                                ->where('users_chat.to_userid',$userid)
                                ->whereRaw('NOT FIND_IN_SET('.$userid.',users_chat.read_by)')
                                ->whereRaw("(users_chat.deleted_at IS null )")
                                ->groupBy('users_chat.from_userid')->get(); 

                                   /// Chat Count End Here//


                                   /// Apportunity Count Start Here ///


                                    $servicesOffered=DB::table('services_offered')->select('service_id')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                    $allOpprtunitiesCountArr=array();
                                    if(!empty($servicesOffered))
                                    {
                                        $contractorServices=array();
                                        foreach ($servicesOffered as $val) 
                                        {
                                          $contractorServices[]=$val->service_id;
                                        }

                                            $allOpprtunitiesCount = DB::table('assign_service_request')
                                            ->join('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                                             ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                                            ->select('assign_service_request.id','assign_service_request.service_request_id','assign_service_request.user_id','assign_service_request.request_status','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image')
                                             ->whereRaw("(assign_service_request.user_id = '".$userid."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status IS null)")
                                             ->where('service_request.status','0')->get(); 

                                             $allOpprtunitiesCountArr=$allOpprtunitiesCount;

                                    }
                                    
                                   /// Apportunity Count End Here ///



                                    /// Job List Count Start here ///


                                 $jobListCount = DB::table('assign_service_request')
                                ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                                ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                                ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image','assign_service_request.tranx_status','assign_service_request.tranx_id')
                                ->where('assign_service_request.user_id',$userid)
                                ->where('assign_service_request.tranx_status','1')
                                ->where('assign_service_request.request_status','buy')
                                ->whereRaw("(service_request.deleted_at IS null )")->get();


                                 if(!empty($jobListCount) || !empty($chatListCount)|| !empty($allOpprtunitiesCountArr))
                                   {

                                    $jobCount=isset($jobListCount) && !empty($jobListCount) ? count($jobListCount) : [] ;
                                    $chatCount=isset($chatListCount) && !empty($chatListCount) ? count($chatListCount) : [] ;
                                    $oppCount=isset($allOpprtunitiesCountArr) && !empty($allOpprtunitiesCountArr) ? count($allOpprtunitiesCountArr) : [] ;

                                        $resultArray['status']='1';   
                                        $resultArray['message']=trans('Count Found Successfully.!');
                                        $resultArray['data'] = array('All Job Count'=>$jobCount,'All Chat Count'=>$chatCount,'All Opportunity Count'=>$oppCount); 
                                        echo json_encode($resultArray); exit;
                                  }

                                 else
                                   {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('List no found.!');
                                        echo json_encode($resultArray); exit;
                                   }


                                    ///  Job List Count End Here ///

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

        /* -------------------GET COUNTING OF CHAT LIST BY USER ID Api End-------------------- */



        /* --------------------GET CHAT LIST BY USER ID Api Start-------------------- */


         public function getMessageListByUserId(Request $request)
         {
                $access_token=123456;
                $allData=array();
                $msg="";
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

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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
                                 $chatList = DB::table('users_chat')
                                ->leftjoin('users', 'users_chat.from_userid', '=', 'users.id')
                                ->select('users_chat.id','users_chat.message','users_chat.created_at','users.id AS from_user_id','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by')
                                ->where('users_chat.to_userid',$userid)
                                ->whereRaw("(users_chat.deleted_at IS null )")
                                ->groupBy('users_chat.from_userid')->get(); 


                                if(!empty($chatList) && count($chatList) > 0)
                                   {

                                     $data1=array();
                                     $path='/img/user/profile/';
                                    foreach ($chatList as $key => $vall)
                                    {

                                        $getSenderprofile = DB::table('users')
                                        ->select('avatar_location')
                                        ->whereRaw("(active=1)")
                                        ->whereRaw("(id = '".$vall->from_user_id."' AND deleted_at IS null )")
                                        ->first();
                                        
                                       if(!empty($getSenderprofile->avatar_location))
                                        {
                                         $from_usr_profile = url($path.$getSenderprofile->avatar_location);
                                        }else
                                        {
                                           $from_usr_profile =""; 
                                        }

                                        if(!empty($userEntity->avatar_location))
                                        {
                                         $to_usr_profile = url($path.$userEntity->avatar_location);
                                        }else
                                        {
                                           $to_usr_profile =""; 
                                        }


                                        $data1['id'] = $vall->id;
                                        $data1['from_userid'] = $vall->from_user_id;
                                        $data1['from_username'] = $vall->from_user_name;
                                        $data1['from_user_profile'] = $from_usr_profile;
                                        $data1['to_userid'] = $userEntity->id;
                                        $data1['to_username'] = $userEntity->username;
                                        $data1['to_user_profile'] = $to_usr_profile;
                                        $data1['message'] = $vall->message;
                                        $data1['is_starred'] = $vall->is_starred;
                                        $data1['is_read'] = $vall->is_read;
                                        $data1['created_at'] = $vall->created_at;
                                        
                                            if(!empty($vall->deleted_by))
                                            {
                                                $HiddenValue = explode(',',$vall->deleted_by);
                                                if (in_array($userid, $HiddenValue)) 
                                                {
                                                    $allData=[];
                                                   $msg= 'Message List not found.';
                                                } 
                                           }
                                           else
                                           {
                                             $msg= 'Message List Found Successfully.!';
                                             array_push($allData, $data1);
                                           }
                                    } 


                                    $resultArray['status']='1';   
                                    $resultArray['message']=trans($msg);
                                    $resultArray['data'] = $allData; 
                                    echo json_encode($resultArray); exit;

                                   }

                                  else
                                   {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Message List not found.!');
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

        /* -------------------GET CHAT LIST BY USER ID Api End-------------------- */



         /* --------------------GET All CHAT Of Particular USer Api Start-------------------- */


         public function getAllChatByUserId(Request $request)
         {
                $access_token=123456;
                $allData=array();
               
                $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
                $from_user_id = isset($request->from_user_id) && !empty($request->from_user_id) ? $request->from_user_id : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'userid' => 'required',
                'from_user_id' => 'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($userid) && !empty($session_key) && !empty($from_user_id) ) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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
                                
                            $chatList = DB::table('users_chat')->leftjoin('users', 'users_chat.from_userid', '=', 'users.id')->select('users_chat.id','users_chat.message','users_chat.created_at','users_chat.from_userid','users_chat.to_userid','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by')->where(function($query) use ($request,$userid,$from_user_id,$userEntity) 
                            {
                            $query->where('from_userid', $userid)->where('to_userid', $from_user_id);
                            })->orWhere(function ($query) use ($request,$userid,$from_user_id,$userEntity) 
                            {
                            $query->where('from_userid', $from_user_id)->where('to_userid', $userid);
                            })->whereRaw("(users_chat.deleted_at IS null )")->orderBy('created_at', 'ASC')->get();
                                   

                                if(!empty($chatList) && count($chatList) > 0)
                                   {

                                     $data1=array();
                                     $to_usr_profile ="";
                                     $path="";
                                     $msg="";
                                    foreach ($chatList as $key => $vall)
                                    {
                                        $getReciverprofile = DB::table('users')
                                        ->select('avatar_location','user_group_id')
                                        ->whereRaw("(active=1)")
                                        ->whereRaw("(id = '".$vall->to_userid."' AND deleted_at IS null )")
                                        ->first();

                                        $getSenderprofile = DB::table('users')
                                        ->select('avatar_location','user_group_id')
                                        ->whereRaw("(active=1)")
                                        ->whereRaw("(id = '".$vall->from_userid."' AND deleted_at IS null )")
                                        ->first();

                                         if(!empty($getSenderprofile))
                                         {

                                               if($getSenderprofile->user_group_id==3)
                                                {
                                                  $senderPath ='/img/contractor/profile/';

                                                }else if($getSenderprofile->user_group_id==4)
                                                {
                                                    $senderPath ='/img/company/profile/';

                                                }else if($getSenderprofile->user_group_id==2)
                                                {
                                                 $senderPath='/img/user/profile/';
                                                }

                                                if(file_exists(public_path($senderPath.$getSenderprofile->avatar_location)))
                                                {
                                                    $from_user_profile = url($senderPath.$getSenderprofile->avatar_location);
                                                } else 
                                                {
                                                    $from_user_profile ="";
                                                }
                                            
                                            }

                                            if(!empty($getReciverprofile))
                                             {

                                               if($getReciverprofile->user_group_id==3)
                                                {
                                                  $path ='/img/contractor/profile/';

                                                }else if($getReciverprofile->user_group_id==4)
                                                {
                                                    $path ='/img/company/profile/';

                                                }else if($getReciverprofile->user_group_id==2)
                                                {
                                                 $path='/img/user/profile/';
                                                }

                                                if(file_exists(public_path($path.$getReciverprofile->avatar_location)))
                                                {
                                                    $to_usr_profile = url($path.$getReciverprofile->avatar_location);
                                                } else 
                                                {
                                                    $to_usr_profile ="";
                                                }
                                            
                                            }
                                      
                                        $data1['id'] = $vall->id;
                                        $data1['from_userid'] = $vall->from_userid;
                                        $data1['from_username'] = $vall->from_user_name;
                                        $data1['from_user_profile'] = $from_user_profile;
                                        $data1['to_userid'] = $vall->to_userid;
                                        $data1['to_username'] = $userEntity->username;
                                        $data1['to_user_profile'] = $to_usr_profile;
                                        $data1['message'] = $vall->message;
                                        $data1['is_starred'] = $vall->is_starred;
                                        $data1['is_read'] = $vall->is_read;
                                        $data1['created_at'] = $vall->created_at;

                                       
                                        if(!empty($vall->deleted_by))
                                        {   
                                            
                                            $HiddenValue = explode(',',$vall->deleted_by);
                                            if (in_array($userid, $HiddenValue)) 
                                            {
                                                $allData=[];
                                               $msg= 'Message List not found.';
                                            } 
                                       }
                                       else
                                       { 
                                     
                                         $msg= 'Message List Found Successfully.!';
                                         array_push($allData, $data1);
                                       }


                                       
                                    }

                                    $resultArray['status']='1';   
                                    $resultArray['message']=trans($msg);
                                    $resultArray['data'] = $allData; 
                                    echo json_encode($resultArray); exit;

                                   }

                                  else
                                   {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Message List not found.!');
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

         /* --------------------GET All CHAT Of Particular USer Api End-------------------- */




         /* --------------------Mark Message to favourite * Api Start-------------------- */


         public function markMessageToStarred(Request $request)
         {

                $access_token=123456;
                $message_id = isset($request->message_id) && !empty($request->message_id) ? $request->message_id : '' ;
                $userid = !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $is_starred = !empty($request->is_starred ) ? $request->is_starred  : '0' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'message_id' => 'required',
                'userid' => 'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($userid) && !empty($message_id) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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
                         
                                    $update_Arr['is_starred'] = $is_starred;
                                    //$update_Arr['is_read'] = '1';
                                    if($is_starred==1 OR $is_starred=='1')
                                    {
                                        $msg='Message Marked To Starred Successfully.!';
                                    }
                                    else
                                    {
                                        $msg='Message Removed From Starred Successfully.!';
                                    }
                                    DB::table('users_chat')->where('id', $message_id)->where('to_userid', $userid)->update($update_Arr);

                                    $resultArray['status']='1';
                                    $resultArray['message']=trans($msg);
                                    echo json_encode($resultArray); exit;      
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

         /* --------------------Mark Message to favourite Api end-------------------- */



         /* --------------------Mark Message to As READ * Api Start-------------------- */


         public function readMessage(Request $request)
         {
            $access_token=123456;
            $from_userid = !empty($request->from_userid) ? $request->from_userid : '' ;
            $userid = !empty($request->userid) ? $request->userid : '' ;
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

            $validator = Validator::make($request->all(), [
            'from_userid' => 'required',
            'userid' => 'required',
            'session_key' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 

                if(!empty($userid) && !empty($from_userid) && !empty($session_key)) 
                {

                    $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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

                             $getUsersChat = DB::table('users_chat')
                            ->whereRaw("(from_userid = '".$from_userid."')")
                            ->whereRaw("(to_userid = '".$userid."' AND deleted_at IS null )")
                            ->get()->toArray();

                                if(!empty($getUsersChat)) 
                                {


                                   foreach ($getUsersChat as $key => $valued) 
                                    {

                                        $HiddenValue = explode(',',$valued->read_by);

                                        if(in_array($userid, $HiddenValue))
                                        {
                                                
                                        }else
                                        {
                                             if(!empty($valued->read_by) && $valued->read_by !=='0')
                                            {
                                                $update_Arr['read_by'] = $valued->read_by.','.$userid;
                                            }else
                                            {
                                                $update_Arr['read_by'] = $userid;
                                            }
                                                     
                                             DB::table('users_chat')->where('id', $valued->id)->update($update_Arr);
                                        }
                                    }

                                        $resultArray['status']='1';
                                        $resultArray['message']=trans('Message Read Successfully.!');
                                        echo json_encode($resultArray); exit;    

                                    }else
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('message id not found.!');
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

         /* --------------------Mark Message to As READ * Api End-------------------- */


          /* --------------------GET Starred CHAT LIST BY USER ID Api Start-------------------- */


         public function getStarredMessageListUserId(Request $request)
         {
                $access_token=123456;
                $msg="";
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

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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
                                 $starredList = DB::table('users_chat')
                                ->leftjoin('users', 'users_chat.from_userid', '=', 'users.id')
                                ->select('users_chat.id','users_chat.message','users_chat.created_at','users.id AS from_user_id','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by')
                                ->where('users_chat.to_userid',$userid)
                                ->where('users_chat.is_starred','1')
                                ->whereRaw("(users_chat.deleted_at IS null )")->get(); 

                                if(!empty($starredList) && count($starredList) > 0)
                                   {

                                    $data1=array();
                                    foreach ($starredList as $key => $vall)
                                    {
                                        $data1['id'] = $vall->id;
                                        $data1['from_userid'] = $vall->from_user_id;
                                        $data1['from_username'] = $vall->from_user_name;
                                        $data1['to_userid'] = $userEntity->id;
                                        $data1['to_username'] = $userEntity->username;
                                        $data1['message'] = $vall->message;
                                        $data1['is_starred'] = $vall->is_starred;
                                        $data1['is_read'] = $vall->is_read;
                                        $data1['created_at'] = $vall->created_at;
                                      
                                         if(!empty($vall->deleted_by))
                                          {
                                              $HiddenValue = explode(',',$vall->deleted_by);
                                              if (in_array($userid, $HiddenValue)) 
                                              {
                                                 $msg= 'Starred Message List not found.';
                                              } else 
                                              {
                                                $msg= 'Starred Message List Found Successfully.!';
                                               array_push($allData, $data1);
                                              } 
                                         }
                                    } 


                                    $resultArray['status']='1';   
                                    $resultArray['message']=trans($msg);
                                    $resultArray['data'] = $allData; 
                                    echo json_encode($resultArray); exit;

                                   }

                                  else
                                   {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Starred Message List not found.!');
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

        /* -------------------GET Starred CHAT LIST BY USER ID Api End-------------------- */



         /* --------------------Delete Particular mEssage Api Start-------------------- */


         public function deleteMessage(Request $request)
         {
                $access_token=123456;
                $message_id = isset($request->message_id) && !empty($request->message_id) ? $request->message_id : '' ;
                $userid = !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'message_id' => 'required',
                'userid' => 'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($userid) && !empty($message_id) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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

                                    $message_ids = explode (",", $message_id);

                                     $getUsersChat = DB::table('users_chat')
                                    ->whereRaw("(deleted_at IS null )")
                                    ->whereIN('id', $message_ids)
                                    ->get();

                                    foreach ($getUsersChat as $key => $getUsersChatss) 
                                    {
                                       
                                            $HiddenValue = explode(',',$getUsersChatss->deleted_by);
                                            if (in_array($userid, $HiddenValue)) 
                                            {
                                                $resultArray['status']='0';
                                                $resultArray['message']=trans('This message Already Deleted.');
                                                echo json_encode($resultArray); exit; 
                                            } else 
                                            {

                                            if(!empty($getUsersChatss->deleted_by))
                                            {
                                                $update_Arr['deleted_by'] = $getUsersChatss->deleted_by.','.$userid;
                                            }else
                                            {
                                                $update_Arr['deleted_by'] = $userid;
                                            }
                                                     
                                             DB::table('users_chat')->whereIN('id', $message_ids)->update($update_Arr);
                                             
                                        }     

                             }
                                            $resultArray['status']='1';
                                            $resultArray['message']=trans('Message Deleted Successfully.!');
                                            echo json_encode($resultArray); exit;

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

         /* --------------------Delete Particular mEssage Api end-------------------- */


        /* --------------------Delete All mEssage Api Start-------------------- */


         public function deleteAllMessages(Request $request)
         {
                $access_token=123456;
                $userid = !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $from_userid = !empty($request->from_userid) ? $request->from_userid : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'userid' => 'required',
                'from_userid' => 'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($userid) && !empty($session_key) && !empty($from_userid)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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

                                     $getUsersChat = DB::table('users_chat')
                                    ->whereRaw("(from_userid = '".$from_userid."' AND deleted_at IS null )")
                                    ->whereRaw("(to_userid = '".$userid."' AND deleted_at IS null )")
                                    ->first();

                                    $HiddenValue = explode(',',$getUsersChat->deleted_by);
                                    if (in_array($userid, $HiddenValue)) 
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Your Chat already Clear Successfully.');
                                        echo json_encode($resultArray); exit; 
                                    } else 
                                    {

                                            if(!empty($getUsersChat->deleted_by))
                                            {
                                                $update_Arr['deleted_by'] = $getUsersChat->deleted_by.','.$userid;
                                            }else
                                            {
                                                $update_Arr['deleted_by'] = $userid;
                                            }

                                              //$update_Arr['deleted_at'] = Carbon::now();

                                               DB::table('users_chat')->where(function($query) use ($request,$userid,$from_userid) 
                                                {
                                                $query->where('from_userid', $userid)->where('to_userid', $from_userid);
                                                })->orWhere(function ($query) use ($request,$userid,$from_userid) 
                                                {
                                                $query->where('from_userid', $from_userid)->where('to_userid', $userid);
                                                })->update($update_Arr);


                                              $resultArray['status']='1';
                                              $resultArray['message']=trans('All Messages Deleted Successfully.!');
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

        /* --------------------Delete All mEssage Api End-------------------- */



        /*
          * RATING REVIEWS TO CONTRACTOR BY USER API START HERE
          */


        public function ratingReviews(Request $request)
        {
            $access_token = 123456;

            $userid = !empty($request->userid) ? $request->userid : '' ;
            $to_user_id = !empty($request->to_user_id) ? $request->to_user_id : '' ;
            $session_key = !empty($request->session_key) ? $request->session_key : '' ;
            $rating = !empty($request->rating) ? $request->rating : '' ;
            $review = !empty($request->review) ? $request->review : '' ;
            $request_id = !empty($request->request_id) ? $request->request_id : '' ;

            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

                 $validator = Validator::make($request->all(), [
                    'userid' => 'required',
                    'to_user_id' => 'required',
                    'rating' => 'required',
                    'request_id' => 'required',
                    'session_key' => 'required',
                    ]);

                    if($validator->fails())
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    } 

            if(isset($userid) && !empty($userid) && isset($to_user_id) && !empty($to_user_id) && isset($rating) && !empty($rating) && isset($review) && !empty($review) && isset($request_id) && !empty($request_id) && isset($session_key) && !empty($session_key))
             {    

                 $user_arr = DB::table('users')
                ->select('users.*')
                ->whereRaw("(users.user_group_id=2)")
                ->whereRaw("(users.id = '".$userid."' AND deleted_at IS null )")
                ->first();  

                 $service_arr = DB::table('service_request')
                ->whereRaw("(status=4)")
                ->whereRaw("(id = '".$request_id."' AND deleted_at IS null )")
                ->first();  

                if(!empty($user_arr))
                {

                    if(!empty($service_arr))
                    {

                     $contractor_arr = DB::table('users')
                    ->select('users.*')
                    ->whereRaw("(users.user_group_id=3)")
                    ->whereRaw("(users.id = '".$to_user_id."' AND deleted_at IS null )")
                    ->first();   

                    if(!empty($contractor_arr))
                    {


                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                        if($check_auth['status']!=1)
                        {
                             return json_encode($check_auth);
                        }
                        else
                        {
                             if(!empty($user_arr))
                            {

                                $Review_rating['user_id'] = trim($userid);
                                $Review_rating['to_user_id'] = trim($to_user_id);
                                $Review_rating['rating'] = trim($rating);
                                $Review_rating['review'] = trim($review); 
                                $Review_rating['request_id'] = trim($request_id); 
                                $Review_rating['created_at'] = Carbon::now()->toDateTimeString();
                                DB::table('reviews')->insert($Review_rating);   

                                $message  = 'Hello '.$contractor_arr->username.', '.$user_arr->username. ' has given you a rating and review for your service';

                                $resultArray['status']='1';
                                $resultArray['message']=trans($message);
                                return json_encode($resultArray);                 
                            }
                        }

                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid user.');
                        return json_encode($resultArray);       
                    } 

                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('Invalid Request ID.');
                        return json_encode($resultArray);      
                    } 

                }

                  else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid user.');
                        return json_encode($resultArray);     
                    } 
                                   
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                return json_encode($resultArray);
            }
        }         


         /*
          * RATING REVIEWS TO CONTRACTOR BY USER API END HERE
          */




         /* ------------------------------------------------------------------------------------------------ */


        //   public function searchingApi(Request $request)
        // {
        //     $access_token=123456;
        //     $allData=array();
        //     $search_key = isset($request->search_key) && !empty($request->search_key) ? $request->search_key : '' ;
        //     $lang = !empty($request->lang) ? $request->lang : 'en' ;
        //     App::setLocale($lang);

        //         $validator = Validator::make($request->all(), [
        //         'search_key' => 'required',
        //         ]);

        //         if($validator->fails())
        //         {
        //             $resultArray['status']='0';
        //             $resultArray['message']=trans('apimessage.Invalid parameters.');
        //             echo json_encode($resultArray); exit;      
        //         } 

        //            if(!empty($search_key)) 
        //             {

        //                       $questionEntity = DB::table('questions')
        //                       ->whereRaw("(status=1)")
        //                       ->where(function($q) use ($search_key){
        //                           $q->where('questions.category_id', $search_key)
        //                             ->orWhere('questions.services_id', $search_key)
        //                             ->orWhere('questions.sub_services_id', $search_key)
        //                             ->orWhere('questions.child_sub_service_id', $search_key);
        //                       })->get()->toArray();

        //                         $allData=array();

        //                         foreach ($questionEntity as $key => $question) 
        //                             {
        //                                 $arr['question_id'] =  isset($question) && !empty($question->id) ? (string)$question->id : '' ;
        //                                 if($lang=='en')
        //                                 {
        //                                 $arr['question']=isset($question) && !empty($question->en_title) ? (string)$question->en_title : '' ; 
        //                                 }else
        //                                 {
        //                                 $arr['question']=isset($question) && !empty($question->es_title) ? (string)$question->es_title : '' ; 
        //                                 }
        //                                 $arr['related_question_id'] =  isset($question) && !empty($question->related_question_id) ? $question->related_question_id : '' ;

        //                                 $arr['related_option_id'] =  isset($question) && !empty($question->related_option_id) ? $question->related_option_id : '' ;
        //                                 $arr['status'] =  isset($question) && !empty($question->status) ? $question->status : '' ;
        //                                 $arr['created_at'] =  isset($question) && !empty($question->created_at) ? $question->created_at : '' ;


        //                                 $questionOptionEntity = DB::table('question_options')
        //                                 ->select('id','en_option','es_option','created_at','status')
        //                                 ->whereRaw("(status=1)")
        //                                 ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
        //                                 ->get();
        //                                 $options=array();

        //                                 foreach ($questionOptionEntity as $option) 
        //                                 {

        //                                     $arr2['option_id'] =  isset($option) && !empty($option->id) ? $option->id : '' ;
        //                                     if($lang=='en')
        //                                     {
        //                                     $arr2['option'] =  isset($option) && !empty($option->en_option) ? $option->en_option : '' ;  
        //                                     }else
        //                                     {
        //                                     $arr2['option'] =  isset($option) && !empty($option->es_option) ? $option->es_option : '' ;  
        //                                     }

        //                                     $arr2['status'] =  isset($option) && !empty($option->status) ? $option->status : '' ;

        //                                     $arr2['created_at'] =  isset($option) && !empty($option->created_at) ? $option->created_at : '' ;

        //                                     array_push($options, $arr2);

        //                                 }
                                          
        //                                    $arr['options']=$options ;
        //                                     array_push($allData, $arr);
        //                             }  

        //                             if(!empty($allData))
        //                             {
        //                                 $resultArray['status']='1';
        //                                 $resultArray['message']=trans('Data found Successfully.!');
        //                                 $resultArray['data']=$allData;
        //                                 echo json_encode($resultArray); exit;
        //                             }else
        //                             {
        //                                 $resultArray['status']='0';
        //                                 $resultArray['message']=trans('Data not found.!');
        //                                 echo json_encode($resultArray); exit;

        //                             }


        //             }
        //             else
        //             {
        //                 $resultArray['status']='0';
        //                 $resultArray['message']=trans('apimessage.Invalid parameters.');
        //                 echo json_encode($resultArray); exit;   
        //             }

        // }


        public function searchingApi(Request $request)
        {
            $access_token=123456;
            $allData=array();
            $search_key = isset($request->search_key) && !empty($request->search_key) ? $request->search_key : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'search_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                   if(!empty($search_key)) 
                    {
                            $lang_name='en_name';
                            if($lang=='es')
                            {
                                $lang_name='es_name';
                            }


                             $getCategories=DB::table('category')->select('id','en_name','es_name')
                            ->where($lang_name, 'LIKE', "%$search_key%")
                            ->where('status',1)
                            ->whereRaw("(deleted_at IS null)")->get()->toArray();

                             $getServices=DB::table('services')->select('id','en_name','es_name')
                            ->where($lang_name, 'LIKE', "%$search_key%")
                            ->where('status',1)
                            ->whereRaw("(deleted_at IS null)")->get()->toArray(); 

                            $getSubServices=DB::table('sub_services')->select('id','en_name','es_name')
                            ->where($lang_name, 'LIKE', "%$search_key%")
                            ->where('status',1)
                            ->whereRaw("(deleted_at IS null)")->get()->toArray();

                            $getChildSubServices=DB::table('child_sub_services')->select('id','en_name','es_name')
                            ->where($lang_name, 'LIKE', "%$search_key%")
                            ->where('status',1)
                            ->whereRaw("(deleted_at IS null)")->get()->toArray();



                                $allData=array();

                                    if(!empty($getCategories))
                                    {

                                        foreach ($getCategories as $category) 
                                            {

                                                $arr1['id'] =  isset($category) && !empty($category->id) ? $category->id : '' ;
                                                // if($lang=='en')
                                                // {
                                                $arr1['en_name'] =  isset($category) && !empty($category->en_name) ? $category->en_name : '' ;  
                                                // }else
                                                // {
                                                $arr1['es_name'] =  isset($category) && !empty($category->es_name) ? $category->es_name : '' ;  
                                                // }
                                                $arr1['type'] = 'category' ;

                                                array_push($allData, $arr1);

                                            }

                                      }

                                    if(!empty($getServices))
                                        {

                                            foreach ($getServices as $service) 
                                            {

                                                $arr2['id'] =  isset($service) && !empty($service->id) ? $service->id : '' ;
                                                // if($lang=='en')
                                                // {
                                                $arr2['en_name'] =  isset($service) && !empty($service->en_name) ? $service->en_name : '' ;  
                                                // }else
                                                // {
                                                $arr2['es_name'] =  isset($service) && !empty($service->es_name) ? $service->es_name : '' ;  
                                                // }
                                                $arr2['type'] = 'service' ;

                                                array_push($allData, $arr2);

                                            }
                                        }


                                        if(!empty($getSubServices))
                                        {

                                           foreach ($getSubServices as $sub) 
                                            {

                                                $arr3['id'] =  isset($sub) && !empty($sub->id) ? $sub->id : '' ;
                                                // if($lang=='en')
                                                // {
                                                $arr3['en_name'] =  isset($sub) && !empty($sub->en_name) ? $sub->en_name : '' ;  
                                                // }else
                                                // {
                                                $arr3['es_name'] =  isset($sub) && !empty($sub->es_name) ? $sub->es_name : '' ;  
                                                // }
                                                $arr3['type'] = 'sub_service' ;

                                                array_push($allData, $arr3);

                                            }
                                        }

                                        if(!empty($getChildSubServices))
                                        {

                                            foreach ($getChildSubServices as $child) 
                                            {

                                                $arr4['id'] =  isset($child) && !empty($child->id) ? $child->id : '' ;
                                                // if($lang=='en')
                                                // {
                                                $arr4['en_name'] =  isset($child) && !empty($child->en_name) ? $child->en_name : '' ;  
                                                // }else
                                                // {
                                                $arr4['es_name'] =  isset($child) && !empty($child->es_name) ? $child->es_name : '' ;  
                                                // }
                                                $arr4['type'] = 'child_sub_service' ;

                                                array_push($allData, $arr4);

                                            }
                                        }

                                    if(!empty($allData))
                                    {
                                        $resultArray['status']='1';
                                        $resultArray['message']=trans('Data found Successfully.!');
                                        $resultArray['data']=$allData;
                                        echo json_encode($resultArray); exit;
                                    }else
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Data not found.!');
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

/* ------------------------------------------------------------------------------------------------ */
                


        public function getAllArea(Request $request)
        {

            $access_token=123456;
            $allData=array();
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

             $provinces = DB::table('provinces')
            ->select('id','name','status','created_at')
            ->whereRaw("(status=1)")
            ->whereRaw("(deleted_at IS null)")
            ->get();

                if(!empty($provinces) && count($provinces) > 0)
                {
                    foreach ($provinces as $vall) 
                    {
                        
                    $arr['id'] = isset($vall->id) && !empty($vall->id) ? (string)$vall->id : '' ;
                    $arr['name'] = isset($vall->name) && !empty($vall->name) ? (string)$vall->name : '' ;
                    $arr['status'] = isset($vall->status) && !empty($vall->status) ? (string)$vall->status : '' ;
                    $arr['created_at'] = isset($vall->created_at) && !empty($vall->created_at) ? (string)$vall->created_at : '' ;

                     $area = DB::table('cities')
                    ->select('id','name','province_id','status','created_at')
                    ->whereRaw("(status=1)")
                    ->where('province_id', $vall->id)
                    ->whereRaw("(deleted_at IS null)")
                    ->get()->toArray(); 

                            $options=array();
                            foreach ($area as $areas) 
                            {

                                $arr2['id'] = isset($areas->id) && !empty($areas->id) ? (string)$areas->id : '' ;
                                $arr2['province_id'] = isset($areas->province_id) && !empty($areas->province_id) ? (string)$areas->province_id : '' ;
                                $arr2['name'] = isset($areas->name) && !empty($areas->name) ? (string)$areas->name : '' ;
                                $arr2['status'] = isset($areas->status) && !empty($areas->status) ? (string)$areas->status : '' ;
                                $arr2['created_at'] = isset($areas->created_at) && !empty($areas->created_at) ? (string)$areas->created_at : '' ;

                             array_push($options, $arr2);

                            }

                       $arr['cities']=$options ;
                       array_push($allData, $arr);
                     }

                        if(!empty($allData) && count($allData) > 0)
                        {
                            $resultArray['status']='1';
                            $resultArray['message ']=trans('Area list found successfully.!');
                            $resultArray['data']=$allData;                  
                            return json_encode($resultArray);
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message ']=trans('Area list not found.!');
                            return json_encode($resultArray);
                        }
            }else
            {
                    $resultArray['status']='0';
                    $resultArray['message ']=trans('Area list not found.!');
                    return json_encode($resultArray);
            }
        }




        public function getAllServiceSubService(Request $request)
        {

            $access_token=123456;
            $allData=array();
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

                     $services = DB::table('services')
                    ->whereRaw("(status=1)")
                    ->whereRaw("(deleted_at IS null)")
                    ->get();

                if(!empty($services) && count($services) > 0)
                {
                    foreach ($services as $service) 
                    {

                        if($lang=='es'){$name=$service->es_name;}else{$name=$service->en_name;}
                         $image= url('/img/'.$service->image);

                        $arr['id'] = isset($service->id) && !empty($service->id) ? (string)$service->id : '' ;
                        $arr['category_id'] = isset($service->category_id) && !empty($service->category_id) ? (string)$service->category_id : '' ;
                        $arr['name'] = isset($name) && !empty($name) ? (string)$name : '' ;

                        $arr['image'] = isset($image) && !empty($image) ? (string)$image : '' ;

                        $arr['status'] = isset($service->status) && !empty($service->status) ? (string)$service->status : '' ;
                        $arr['created_at'] = isset($service->created_at) && !empty($service->created_at) ? (string)$service->created_at : '' ;


                             $subServices = DB::table('sub_services')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(deleted_at IS null)")
                            ->where('services_id',$service->id)
                            ->get(); 

                            $options=array();
                            foreach ($subServices as $subService) 
                            {

                                if($lang=='es'){$name=$subService->es_name;}
                                else{$name=$subService->en_name;}
                                $image= url('/img/'.$subService->image);
               
                                $arr2['id'] = isset($subService->id) && !empty($subService->id) ? (string)$subService->id : '' ;

                                $arr2['services_id'] = isset($subService->services_id) && !empty($subService->services_id) ? (string)$subService->services_id : '' ;

                                $arr2['name'] = isset($name) && !empty($name) ? (string)$name : '' ;

                                $arr2['image'] = isset($image) && !empty($image) ? (string)$image : '' ;

                                $arr2['status'] = isset($subService->status) && !empty($subService->status) ? (string)$subService->status : '' ;

                                $arr2['created_at'] = isset($subService->created_at) && !empty($subService->created_at) ? (string)$subService->created_at : '' ;


                             array_push($options, $arr2);

                            }

                       $arr['sub_services']=$options ;
                       array_push($allData, $arr);
                     }

                        if(!empty($allData) && count($allData) > 0)
                        {
                            $resultArray['status']='1';
                            $resultArray['message ']=trans('Service and subservice list found successfully.!');
                            $resultArray['data']=$allData;                  
                            return json_encode($resultArray);
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message ']=trans('Service and subservice list not found.!');
                            return json_encode($resultArray);
                        }
            }else
            {
                    $resultArray['status']='0';
                    $resultArray['message ']=trans('Service and subservice list not found.!');
                    return json_encode($resultArray);
            }
        }


         /********************  END  **************************/



         /* --------------------Delete Particular mEssage Api Start-------------------- */


         public function deleteGalleryVideo(Request $request)
         {
                $access_token=123456;
                $video_id = isset($request->video_id) && !empty($request->video_id) ? $request->video_id : '' ;
                $userid = !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'video_id' => 'required',
                'userid' => 'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($userid) && !empty($video_id) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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

                                    
                                    $getvideo = DB::table('users_videos_gallery')
                                    ->whereRaw("(id = '".$video_id."' AND user_id = '".$userid."' AND deleted_at IS null )")
                                    ->first();
                                    
                                    if(!empty($getvideo))
                                    {

                                              //Profile picture
                                         $videoPath="";

                                        if($userEntity->user_group_id==3)
                                        {
                                            $videoPath = '/img/contractor/gallery/videos/';

                                        }if($userEntity->user_group_id==4)
                                        {
                                            $videoPath = '/img/company/gallery/videos/';
                                        }
                                        $targetDir = public_path() . $videoPath .$userEntity->id.'/';

                                        $Your_file_path= $targetDir.$getvideo->file_name;

                                             if (file_exists($Your_file_path)) 
                                             {
                                                 unlink($Your_file_path);
                                             } 

                                       DB::table('users_videos_gallery')->where('id', '=', $video_id)->delete();

                                          $resultArray['status']='1';
                                            $resultArray['message']=trans('Video Deleted Successfully.!');
                                            echo json_encode($resultArray); exit;
                                    }else
                                    {
                                         $resultArray['status']='0';
                                        $resultArray['message']=trans('Video not found.');
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

         /* --------------------Delete Particular mEssage Api end-------------------- */
         


         /* --------------------Delete Particular mEssage Api Start-------------------- */


         public function deleteCertAndPoliceRecordDoc(Request $request)
         {
                $access_token=123456;
                $doc_id = isset($request->doc_id) && !empty($request->doc_id) ? $request->doc_id : '' ;

                //0 certificate and 1 police record 

                $doc_type = isset($request->doc_type) && !empty($request->doc_type) ? $request->doc_type : '' ;

                $userid = !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'doc_id' => 'required',
                'userid' => 'required',
                'doc_type' => 'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($userid) && !empty($doc_id) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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

                                    
                                    $getdoc = DB::table('user_certifications')
                                    ->whereRaw("(id = '".$doc_id."' AND certification_type = '".$doc_type."' AND user_id = '".$userid."' AND deleted_at IS null )")->first();
                                    
                                    if(!empty($getdoc))
                                    {

                                              //Profile picture
                                         $policePath="";$certifiePath="";

                                        if($userEntity->user_group_id==3)
                                        {
                                              $policePath ='/img/contractor/police_records/'.$userid.'/doc/';
                                              $certifiePath ='/img/contractor/certifications/'.$userid.'/doc/';

                                        }else if($userEntity->user_group_id==4)
                                        {
                                             $policePath ='/img/company/police_records/'.$userid.'/doc/';
                                             $certifiePath ='/img/company/certifications/'.$userid.'/doc/';
                                        }


                                      if($doc_type==0)
                                      {
                                               $targetDir = public_path() . $certifiePath;

                                              $Your_file_path= $targetDir.$getdoc->file_name;

                                                 if (file_exists($Your_file_path)) 
                                                 {
                                                     unlink($Your_file_path);
                                                 } 

                                               DB::table('user_certifications') ->whereRaw("(id = '".$doc_id."' AND certification_type = '".$doc_type."' AND user_id = '".$userid."' )")->delete();


                                      } else if($doc_type==1)
                                      {


                                              $targetDir = public_path() . $policePath;

                                              $Your_file_path= $targetDir.$getdoc->file_name;

                                                 if (file_exists($Your_file_path)) 
                                                 {
                                                     unlink($Your_file_path);
                                                 } 

                                               DB::table('user_certifications')->whereRaw("(id = '".$doc_id."' AND certification_type = '".$doc_type."' AND user_id = '".$userid."' )")->delete();

                                      }

                                      

                                          $resultArray['status']='1';
                                            $resultArray['message']=trans('Document Deleted Successfully.!');
                                            echo json_encode($resultArray); exit;
                                    }else
                                    {
                                         $resultArray['status']='0';
                                        $resultArray['message']=trans('Document not found.');
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

         /* --------------------Delete Particular mEssage Api end-------------------- */



          /* --------------------Delete Api Start-------------------- */


         public function deleteCertAndPoliceRecordImage(Request $request)
         {
                $access_token=123456;
                $image_id = isset($request->image_id) && !empty($request->image_id) ? $request->image_id : '' ;

                //0 certificate and 1 police record 

                $image_type = isset($request->image_type) && !empty($request->image_type) ? $request->image_type : '' ;

                $userid = !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'image_id' => 'required',
                'userid' => 'required',
                'image_type' => 'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($userid) && !empty($image_id) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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

                                    
                                    $getdoc = DB::table('user_certifications')
                                    ->whereRaw("(id = '".$image_id."' AND certification_type = '".$image_type."' AND user_id = '".$userid."' AND file_type = 0 AND deleted_at IS null )")->first();
                                    
                                    if(!empty($getdoc))
                                    {

                                              //Profile picture
                                         $policePath="";$certifiePath="";

                                        if($userEntity->user_group_id==3)
                                        {
                                              $policePath ='/img/contractor/police_records/'.$userid.'/img/';
                                              $certifiePath ='/img/contractor/certifications/'.$userid.'/img/';

                                        }else if($userEntity->user_group_id==4)
                                        {
                                             $policePath ='/img/company/police_records/'.$userid.'/img/';
                                             $certifiePath ='/img/company/certifications/'.$userid.'/img/';
                                        }


                                      if($image_type==0)
                                      {
                                               $targetDir = public_path() . $certifiePath;

                                              $Your_file_path= $targetDir.$getdoc->file_name;

                                                 if (file_exists($Your_file_path)) 
                                                 {
                                                     unlink($Your_file_path);
                                                 } 

                                               DB::table('user_certifications') ->whereRaw("(id = '".$image_id."' AND certification_type = '".$image_type."' AND user_id = '".$userid."' AND file_type = 0 )")->delete();


                                      } else if($image_type==1)
                                      {


                                              $targetDir = public_path() . $policePath;

                                              $Your_file_path= $targetDir.$getdoc->file_name;

                                                 if (file_exists($Your_file_path)) 
                                                 {
                                                     unlink($Your_file_path);
                                                 } 

                                               DB::table('user_certifications')->whereRaw("(id = '".$image_id."' AND certification_type = '".$image_type."' AND user_id = '".$userid."' AND file_type = 0 )")->delete();

                                      }

                                      

                                          $resultArray['status']='1';
                                            $resultArray['message']=trans('Image Deleted Successfully.!');
                                            echo json_encode($resultArray); exit;
                                    }else
                                    {
                                         $resultArray['status']='0';
                                        $resultArray['message']=trans('Image not found.');
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

         /* --------------------Delete Api end-------------------- */


            /* --------------------Delete Api Start-------------------- */


         public function deleteGalleryImage(Request $request)
         {
                $access_token=123456;
                $image_id = isset($request->image_id) && !empty($request->image_id) ? $request->image_id : '' ;
                $userid = !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'image_id' => 'required',
                'userid' => 'required',
                'session_key' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                } 

                    if(!empty($userid) && !empty($image_id) && !empty($session_key)) 
                    {

                        $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
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

                                    
                                    $getimg = DB::table('users_images_gallery')
                                    ->whereRaw("(id = '".$image_id."' AND user_id = '".$userid."' AND deleted_at IS null )")->first();
                                    
                                    if(!empty($getimg))
                                    {

                                              //Profile picture
                                         $galleryPath="";

                                        if($userEntity->user_group_id==3)
                                        {
                                              $galleryPath ='/img/contractor/gallery/images/';

                                        }else if($userEntity->user_group_id==4)
                                        {
                                              $galleryPath ='/img/company/gallery/images/';
                                        }

                                               $targetDir = public_path() . $galleryPath;

                                                $Your_file_path= $targetDir.$getimg->file_name;

                                                 if (file_exists($Your_file_path)) 
                                                 {
                                                     unlink($Your_file_path);
                                                 } 

                                               DB::table('users_images_gallery')->whereRaw("(id = '".$image_id."' AND user_id = '".$userid."' AND deleted_at IS null )")->delete();

                                          $resultArray['status']='1';
                                            $resultArray['message']=trans('Image Deleted Successfully.!');
                                            echo json_encode($resultArray); exit;
                                    }else
                                    {
                                         $resultArray['status']='0';
                                        $resultArray['message']=trans('Image not found.');
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

         /* --------------------Delete Api end-------------------- */
}
