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
use DB, Mail, Redirect, Response, Session;
use Hash, File;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

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
              if(Auth::attempt(['email' => $email, 'password' => $password, 'user_group_id' =>[2,3]]))
                { 
                    //$users_count_var = Auth::user();
                     $users_count_var = DB::table('users')->select('id','first_name','last_name','mobile_number','email','profile_description','address','landline_number','mobile_number','active','is_verified','user_group_id','verify_code','created_at','updated_at','avatar_location')->whereRaw("(email = '".$email."')")->first(); 


                    $socailData=DB::table('social_networks')->where('user_id',$users_count_var->id)->first(); 
                    $users_count_var->facebook_url = $socailData->facebook_url;
                    $users_count_var->insta_url = $socailData->instagram_url;
                    $users_count_var->snapchat_url = $socailData->snap_chat_url;
                    $users_count_var->twitter_url = $socailData->twitter_url;
                    $users_count_var->youtube_url = $socailData->youtube_url;

                }
                else
                {
                  $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid login credential.');
                    echo json_encode($resultArray); exit;
                } 

                if($users_count_var->is_verified==0)
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Your account is not verified please verify your account using otp code.');
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
                $settingEntity = DB::table('Settings')->select('app_language')->whereRaw("(user_id = '".$users_count_var->id."')")->first();  

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

                    }

                    if(!empty($users_count_var->avatar_location))
                    {
                        if($users_count_var->user_group_id=='3')
                        { $path='/img/contractor/profile/'; }else
                        { $path='/img/user/profile/'; }

                        $users_count_var->avatar_location = $path.$users_count_var->avatar_location;
                    }
                    else
                    {
                     $users_count_var->avatar_location ="";
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
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'mobile_number' => 'required',
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

        $firstname = isset($request->first_name) && !empty($request->first_name) ? $request->first_name : '' ;
        $lastname = isset($request->last_name) && !empty($request->last_name) ? $request->last_name : '' ;
        $mobile_number = isset($request->mobile_number) && !empty($request->mobile_number) ? $request->mobile_number : '' ;
       
        $email = isset($request->email) && !empty($request->email) ? $request->email : '' ;

        $password = isset($request->password) && !empty($request->password) ? $request->password : '';

        $lang = isset($request->lang) && !empty($request->lang) ? $request->lang : 'en';

        App::setLocale($lang);

        $device_id = isset($request->device_id) && !empty($request->device_id) ? $request->device_id : '';
        $device_type = isset($request->device_type) && !empty($request->device_type) ? $request->device_type : '';

        if(!empty($firstname) && !empty($lastname) && !empty($mobile_number) && !empty($email) && !empty($password) &&  !empty($device_id) && !empty($device_type) && !empty($type))
         {
      
        $emailexist = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null )")->first();
        $mobileexist = DB::table('users')->whereRaw("(mobile_number = '".$mobile_number."' AND deleted_at IS null )")->first();

        if($mobile_number) 
        {
            if($this->validateMobile($mobile_number) == 0)
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid mobile number format.');
                echo json_encode($resultArray); exit;
            }
        }

        if($mobileexist)
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Mobile Number Already Exist.');
            echo json_encode($resultArray); exit;
        }


        if($emailexist)
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Email Already Exist.');
            echo json_encode($resultArray); exit;
        }


        $registerArr['uuid'] = Uuid::uuid4()->toString();
        $registerArr['first_name'] = $firstname;
        $registerArr['last_name'] = $lastname;
        $registerArr['email'] = $email;
        $registerArr['mobile_number'] = $mobile_number;
        $registerArr['active'] = 0;
        $registerArr['confirmed'] = 0;
        $registerArr['is_verified'] =0;
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
                //Send OTP to MOBILE NUMBER;
                $getotp = $this->sendOTP($n=6);
                $update_otp_Arr['verify_code'] = $getotp; 
                DB::table('users')->where('id', $userId)->update($update_otp_Arr);

                  $users_count_var = DB::table('users')->select('id','first_name','last_name','mobile_number','email','mobile_number','active','is_verified','user_group_id','verify_code','created_at','updated_at')->whereRaw("(id = '".$userId."')")->first();
              
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
                $resultArray['message']=trans('apimessage.Your account has been created successfully,please check sms in your given mobile number and enter verification code for further process of profile completion.');
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
          * SERVICE LIST API START HERE
          */
        public function getAllServiceList(Request $request)
        {
            $access_token=123456;
            $serviceArray = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            App::setLocale($lang);
            $services = Services::all();
            foreach($services as $service) 
            {
                //if($lang=='es'){$name=$service->es_name;}else{$name=$service->en_name;}
            array_push($serviceArray,array('id'=>$service->id, 'en_name'=>$service->en_name,'es_name'=>$service->es_name,'status'=>$service->status,'created_at'=>$service->created_at,'updated_at'=>$service->updated_at));
            }
            if($services)
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('apimessage.service list fount successfully.!');
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

         /*
          * UPDATE PROFILE API START HERE
          */
            public function updateProfile(Request $request)
            {
                $access_token=123456;
                $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
                $session_key = !empty($request->session_key) ? $request->session_key : '' ;
                $first_name = !empty($request->first_name) ? $request->first_name : '' ;
                $last_name = !empty($request->last_name) ? $request->last_name : '' ;
                $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
                $profile_pic = !empty($request->profile_pic) ? $request->profile_pic : '' ;
                $address = !empty($request->address) ? $request->address : '' ;
                $profile_description = !empty($request->profile_description) ? $request->profile_description : '' ;
                //Social Networks
                $facebook_url = !empty($request->facebook_url) ? $request->facebook_url : '' ;
                $insta_url = !empty($request->insta_url) ? $request->insta_url : '' ;
                $snapchat_url = !empty($request->snapchat_url) ? $request->snapchat_url : '' ;
                $twitter_url = !empty($request->twitter_url) ? $request->twitter_url : '' ;
                $youtube_url = !empty($request->youtube_url) ? $request->youtube_url : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;

                App::setLocale($lang);

                $validator = Validator::make($request->all(), [
                'userid' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_number' => 'required',
                'profile_description' => 'required',
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
                    ->whereRaw("(confirmed=1)")
                    ->whereRaw("(is_verified=1)")
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
                                $fmove="";

                                if($userEntity->user_group_id=='2')
                                {
                                $fmove = move_uploaded_file($_FILES['profile_pic']['tmp_name'],public_path() . '/img/user/profile/'.$filename);
                                }
                                else if($userEntity->user_group_id=='3')
                                {
                                $fmove = move_uploaded_file($_FILES['profile_pic']['tmp_name'],public_path() . '/img/contractor/profile/'.$filename);
                                }
                                 $profile = $filename;
                            }
                           
                        $userData['avatar_location'] =  $profile;
                        $userData['first_name'] =  $first_name;
                        $userData['last_name'] =  $last_name;
                        $userData['mobile_number'] =  $mobile_number;
                        $userData['landline_number'] =  $landline_number;
                        $userData['address'] =  $address;
                        $userData['profile_description'] =  $profile_description;


                            DB::table('users')->where('id',$userEntity->id)->update($userData);
                            
                                $socialNetw['facebook_url'] = $facebook_url;
                                $socialNetw['instagram_url'] = $insta_url;
                                $socialNetw['snap_chat_url'] = $snapchat_url;
                                $socialNetw['twitter_url'] = $twitter_url;
                                $socialNetw['youtube_url'] = $youtube_url;
                                $socialNetw['updated_at'] = Carbon::now()->toDateTimeString();
                                $socialNetw['user_id'] = $userEntity->id;
                                $socialNetw['created_at'] = Carbon::now()->toDateTimeString();
                                DB::table('social_networks')->update($socialNetw);
                             
                             $users_count_var=DB::table('users')->select('users.id','users.user_group_id','users.email','users.first_name','users.last_name','users.profile_description','users.address','users.landline_number','users.mobile_number','users.is_verified','users.active','avatar_location')->where('id',$userEntity->id)->first();

                             $socailData=DB::table('social_networks')->where('user_id',$userEntity->id)->first(); 

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
                                if($users_count_var->user_group_id=='3')
                                { $path='/img/contractor/profile/'; }else
                                { $path='/img/user/profile/'; }
                                
                             $users_count_var->avatar_location = $path.$users_count_var->avatar_location;
                            }
                            else
                            {
                             $users_count_var->avatar_location ="";
                            }

                            $users_count_var->facebook_url = $socailData->facebook_url;
                            $users_count_var->insta_url = $socailData->instagram_url;
                            $users_count_var->snapchat_url = $socailData->snap_chat_url;
                            $users_count_var->twitter_url = $socailData->twitter_url;
                            $users_count_var->youtube_url = $socailData->youtube_url;

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
                $resultArray['message ']=trans('apimessage.document types list fount successfully.!');
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
                $documents = !empty($request->documents) ? $request->documents : '' ;
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
                'documents'=>'required',
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
                        }else{

                            $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(confirmed=1)")
                            ->whereRaw("(is_verified=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first(); 

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


                                if(!empty($workerId) && !empty($documents))
                                {
                                    foreach ($documents as $value) 
                                    {
                                        echo $value['doc_name']['tmp_name'];
                                        // if(isset($_FILES['profile_pic']['name']) && !empty($_FILES['profile_pic']['name']))
                                        // {
                                        //     $extq = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                        //     $filename = mt_rand(1000,9999).'.'.$extq;
                                        //     $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                         
                                        //     $fmove = move_uploaded_file($_FILES['profile_pic']['tmp_name'],public_path() . '/img/worker/profile/'.$filename);
                                            
                                        //      $profile = $filename;
                                        // }
                                    }
                                }
die;
                                $workerEntity = DB::table('workers')->select('id', 'user_id', 'email', 'password', 'first_name', 'last_name', 'profile_pic', 'mobile_number', 'address', 'status', 'created_at', 'updated_at')
                                ->whereRaw("(id = '".$workerId."' AND deleted_at IS null )")
                                ->first();
                                
                                $path='/img/worker/profile/';
                                $workerEntity->profile_pic = $path.$workerEntity->profile_pic;

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

}
