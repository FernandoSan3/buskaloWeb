<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
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
use Redirect, Response, Session;
use Hash, File;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use App\Mail\WelcomeNewUser;
use App\Mail\NewUserVerify;
use App\Mail\ServiceRequestOtp;
use App\Mail\forgotPasswordMail;
use App\Mail\NewOpportunity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Models\Auth\User;
use DB;
date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
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
        $userDeviceCheck = null;
        App::setLocale($lang);

        if(!empty($email) && !empty($password) && !empty($device_id) && !empty($device_type))
        {
            $user= DB::table('users')->where('email',$email)->first();
            if($user->deleted_at !== null){

                $dayinactive = 30;
                $date1 = date_create(date('Y-m-d H:i:s', strtotime(Carbon::now())));
                $date2 = date_create(date('Y-m-d H:i:s', strtotime($user->deleted_at)));
                $diff = date_diff($date1,$date2);
                $days = $diff->format("%a");
                $days = $dayinactive - $days;

                if($days <= 0 ){
                    $message = 'Su cuenta ha sido eliminada por favor registrese nuevamente';
                    

                }else {
                    $message = 'Su cuenta se encuentra inactiva, por '.$days.' días. Por favor, comuníquese con el administrador para activar su cuenta.';
                   
                }

            } else {

                $users_count_var="";
                if(Auth::attempt(['email' => $email, 'password' => $password, 'user_group_id' =>[2,3,4]]))
                { 
                    $users_count_var = Auth::user();

                    if(!empty($type))
                    {
                        $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','created_at','updated_at','deleted_at','confirmed','confirmation_code','approval_status')->whereRaw("(user_group_id = '".$type."')")->whereRaw("(email = '".$email."')")->first(); 
                    }else
                    {
                        $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','created_at','updated_at','deleted_at','confirmed','confirmation_code','approval_status')->whereRaw("(email = '".$email."')")->first();
                    }
                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid login credential.');
                    echo json_encode($resultArray); exit;
                } 
            }
            if(empty($users_count_var))
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid login credential.');
                echo json_encode($resultArray); exit;
            }
            // if($users_count_var->approval_status==0 && $users_count_var->user_group_id!=2)
            // {
            //     $resultArray['status']='0';
            //     $resultArray['message']=trans('your account not approvel');
            //     echo json_encode($resultArray); exit;

            // }

            if($users_count_var->confirmed==0)
            {
                if($lang=='en')
                {
                   $mess= 'Revisa tu correo incluyendo la carpeta de "no deseados" para confirmar tu cuenta.';
                }
                if($lang=='es')
                {
                   $mess= 'Revisa tu correo incluyendo la carpeta de "no deseados" para confirmar tu cuenta.';
                }
                $resultArray['status']='0';
                $resultArray['message']= $mess;
                echo json_encode($resultArray); exit;
            }
           
            if(isset($users_count_var) && $users_count_var->confirmed==1 && !empty($users_count_var))
            {
                $settingEntity = DB::table('settings')->select('app_language')->whereRaw("(user_id = '".$users_count_var->id."')")->first();  
                    if($settingEntity)
                    {
                    $users_count_var->lang =$settingEntity->app_language;
                    }else
                    {
                    $users_count_var->lang = 0;
                    }
                //$check_auth = $this->checkToken($access_token,$users_count_var->id);
                if(1!=1)
                {
                    //echo json_encode($check_auth); exit;
                }
                else
                {
                    $userDeviceCheck = DB::table('user_devices')->where('user_id',$users_count_var->id)->first();

                    if(isset($userDeviceCheck) && !empty($userDeviceCheck))
                    {
                        $userdevice= array('device_id'=>$device_id,'device_type'=>$device_type);
                        DB::table('user_devices')->where('user_id',$users_count_var->id)->update($userdevice);
                        $users_count_var->device_id =$device_id;
                        $logincount=isset($userDeviceCheck->login_count)?$userDeviceCheck->login_count:0;
                        if(($logincount==0) && ($users_count_var->user_group_id==3 || $users_count_var->user_group_id==4))
                        {
                            $freecredit=DB::table('site_settings')->where('id',1)->first();
                            $useradd=isset($freecredit->free_credit)?$freecredit->free_credit:'20';
                            if($lang='es')
                            {
                                $message='Su perfil se activó con éxito y ha recibido una bonificación de'. $useradd.' monedas.';
                            }else
                            {
                                $message='Your profile is activated successfully, and you have recived bonus of'. $useradd.' coins.!';
                            }
                            
                            //$message=trans('apimessage.Your profile is activated successfully, and you have recived bonus of 20 credits.!');
                            DB::table('user_devices')->where('user_id',$users_count_var->id)->update(['login_count'=>'1']);
                        }
                        else
                        {
                            $message=trans('apimessage.Successfully login.');
                        }
                    }else
                    {
                        $userdevice= array('user_id' => $users_count_var->id ,'device_id'=>$device_id,'device_type'=>$device_type);
                        DB::table('user_devices')->insert($userdevice);
                        $users_count_var->device_id =$device_id;
                            $logincount = isset($this->$userDeviceCheck->login_count)? $this->$userDeviceCheck->login_count : 0;
                            if(($logincount==0) && ($users_count_var->user_group_id==3 || $users_count_var->user_group_id==4))
                        {
                            // if(($userDeviceCheck->login_count==0) && ($users_count_var->user_group_id==3 || $users_count_var->user_group_id==4))
                            //  {
                            $freecredit=DB::table('site_settings')->where('id',1)->first();
                            $useradd=isset($freecredit->free_credit)?$freecredit->free_credit:'20';
                            if($lang='es')
                            {
                                $message='Su perfil se activó con éxito y ha recibido una bonificación de'. $useradd.' monedas.';
                            }else
                            {
                                $message='Your profile is activated successfully, and you have recived bonus of'. $useradd.' coins.!';
                            }
                            // $message=trans('Your profile is activated successfully, and you have recived bonus of'. $useradd.' credits.!');
                                DB::table('user_devices')->where('user_id',$users_count_var->id)->update(['login_count'=>1]);
                        }
                        else
                        {
                            $message=trans('apimessage.Successfully login.');
                        }
                    }
                

                    /*-------------------*/ 
                    $resultArray['status']='1'; 
                    //$resultArray['userdata'] = $users_count_var;     
                    $resultArray['message']=$message;
                    $resultArray['session_key']='';//$check_auth['Data']['randnumber'];
                    $resultdata= $this->intToString($users_count_var );
                    $resultArray['userData']=$resultdata;
                    echo json_encode($resultArray); exit;
                }
            }
            else
            {
            $resultArray['status']='0';
            // $resultArray['message']=trans('apimessage.Invalid login credential.');
            $resultArray['message'] =trans('Su cuenta ha esta confirmada.');
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

    public function socialLogin(Request $request)
    {
        $access_token=123456;
        $validator = Validator::make($request->all(), [
            'provider'=>'required',
            'provider_id'=>'required',
            'deviceID'=>'required',
            'deviceType'=>'required',
            'email'=>'required',
            'username'=>'required',
            //'image'=>'required',
            'user_group_id'=>'required',
        ]);

        if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            }
            $access_token=123456;
            $userId = isset($request->user_id) && !empty($request->user_id) ? $request->user_id: '';
            $email = isset($request->email) && !empty($request->email) ? $request->email: '';
            $lang = isset($request->lang) && !empty($request->lang) ? $request->lang : 'en';
            App::setLocale($lang);
             $adminaprovel = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null AND approval_status='0')")->first();
            if(!empty($adminaprovel))
            {
                if($adminaprovel->user_group_id==3||$adminaprovel->user_group_id==4)
                {
                    //$check_auth = $this->checkToken($access_token,$adminaprovel->id);
                    $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at','approval_status')->whereRaw("(id = '".$adminaprovel->id."')")->first();
					$checkdeviceId=DB::table('user_devices')->where('user_id',$adminaprovel->id)->first();
					if(empty($checkdeviceId))
					{
						DB::table('user_devices')->insert(['user_id'=>$adminaprovel->id,'device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);
					}
					else
					{
						DB::table('user_devices')->where('user_id',$adminaprovel->id)->update(['device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);
					}
                    $resultArray['status']=1;        
                    $resultArray['type']='pending';        
                    $resultArray['message']=trans('admin not approvel.');
                    //$resultArray['session_key']=$check_auth['Data']['randnumber'];
                    $resultdata= $this->intToString($users_count_var );
                    $resultArray['userData']=$resultdata;
                    echo json_encode($resultArray); exit;
                }
            }

            $adminaprovel = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null AND approval_status='1')")->first();
            if(!empty($adminaprovel))
            {
                if($adminaprovel->user_group_id==3||$adminaprovel->user_group_id==4)
                {
                    //$check_auth = $this->checkToken($access_token,$adminaprovel->id);
                    $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at','approval_status')->whereRaw("(id = '".$adminaprovel->id."')")->first();
					
					$checkdeviceId=DB::table('user_devices')->where('user_id',$adminaprovel->id)->first();
					if(empty($checkdeviceId))
					{
						DB::table('user_devices')->insert(['user_id'=>$adminaprovel->id,'device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);
					}
					else
					{
						DB::table('user_devices')->where('user_id',$adminaprovel->id)->update(['device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);
					}
					
                    $resultArray['status']=1;        
                    $resultArray['type']='login';        
                    $resultArray['message']=trans('apimessage.Email Already Exist.');
                    //$resultArray['session_key']=$check_auth['Data']['randnumber'];
                    $resultdata= $this->intToString($users_count_var );
                    $resultArray['userData']=$resultdata;
                    echo json_encode($resultArray); exit; 
                }
            }
            $adminaprovelreject = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null AND approval_status='2')")->first();
            if(!empty($adminaprovelreject))
            {
                if($adminaprovelreject->user_group_id==3||$adminaprovelreject->user_group_id==4)
                {
                    DB::table('users')->where('id',$adminaprovelreject->id)->update(['approval_status'=>0]);
                    //$check_auth = $this->checkToken($access_token,$adminaprovelreject->id);
                    $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at','approval_status')->whereRaw("(id = '".$adminaprovelreject->id."')")->first();
					$checkdeviceId=DB::table('user_devices')->where('user_id',$adminaprovelreject->id)->first();
					if(empty($checkdeviceId))
					{
						DB::table('user_devices')->insert(['user_id'=>$adminaprovelreject->id,'device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);
					}
					else
					{
						DB::table('user_devices')->where('user_id',$adminaprovelreject->id)->update(['device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);
					}
					
                    $resultArray['status']=1;        
                    $resultArray['type']='update';        
                    $resultArray['message']='update information';
                    //$resultArray['session_key']=$check_auth['Data']['randnumber'];
                    $resultdata= $this->intToString($users_count_var );
                    $resultArray['userData']=$resultdata;
                    echo json_encode($resultArray); exit;
                }
            }
            // $checkemail=DB::table('users')->where('email',$request->email)->first();
            // if(!empty($checkemail))
            // {
            //     $resultArray['status']='0';

            //     $resultArray['message']=trans('email already exits.');
            //     echo json_encode($resultArray); exit;  
            // }
       // $checkRegister=DB::table('social_accounts')->where('provider_id',$request->provider_id)->where('provider',$request->provider)->first();
        //$userdatacheck='';
        // if(!empty($checkRegister))
        // {
        //      $userdatacheck= DB::table('users')->where('email',$checkRegister->user_id)->first();
        // }
        $userdatacheck=DB::table('users')->where('email',$request->email)->first();

        if(isset($userdatacheck) && !empty($userdatacheck))
        {
            if($userdatacheck->email==$request->email && $userdatacheck->user_group_id!=$request->user_group_id)
            {
                $resultArray['status']='0';            
                $resultArray['message']=trans('apimessage.Email Already Exist.');
                echo json_encode($resultArray); exit;
            }
        }
        if(empty($userdatacheck))
        {
            $fileName='';
            if(!empty($request->image))
            {
                $content = file_get_contents($request->image);
                $rand=rand(11111,99999);
                if($request->user_group_id==3)
                {
                     file_put_contents('img/contractor/profile/'.$rand.'.jpg', $content);
                }
                if($request->user_group_id==4)
                {
                     file_put_contents('img/company/profile/'.$rand.'.jpg', $content);
                }
                if($request->user_group_id==2)
                {
                     file_put_contents('img/user/profile/'.$rand.'.jpg', $content);
                }
                 $fileName=$rand.'.jpg';
            }
                $freecredit=DB::table('site_settings')->where('id',1)->first();
                $user= new User;
                 $user->user_group_id=$request->user_group_id;
                 $user->username=$request->username;
                 $user->email=$request->email;
                 $user->confirmed=1;
                 $user->active=1;
                 $user->avatar_location= $fileName;
                 $user->confirmation_code=md5(uniqid(mt_rand(), true));
                 $user->password=bcrypt('123456');
                 $user->pro_credit=isset($freecredit->free_credit)?$freecredit->free_credit:'20';
                 $user->save();
            //$check_auth = $this->checkToken($access_token,$user->id);
            $social=array('user_id'=>$user->id,'provider'=>$request->provider,'provider_id'=>$request->provider_id);
            DB::table('social_accounts')->insert($social);  
            DB::table('user_devices')->insert(['user_id'=>$user->id,'device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);  
        }
        else
        {
            $user= DB::table('users')->where('id',$userdatacheck->id)->first();
            if(isset($request->image) && !empty($request->image))
            {
                    $content = file_get_contents($request->image);
                    $rand=rand(11111,99999);
                    if($request->user_group_id==3)
                    {
                         file_put_contents('img/contractor/profile/'.$rand.'.jpg', $content);
                         if(!empty($user->avatar_location) && file_exists(public_path('img/contractor/profile/'.$user->avatar_location)))
                        {
                            unlink(public_path('img/contractor/profile/'.$user->avatar_location));
                        }
                    }
                    if($request->user_group_id==4)
                    {
						file_put_contents('img/company/profile/'.$rand.'.jpg', $content);
						if(!empty($user->avatar_location) && file_exists(public_path('img/company/profile/'.$user->avatar_location)))
                        {
                            unlink(public_path('img/company/profile/'.$user->avatar_location));
                        }
                    }
                    if($request->user_group_id==2)
                    {
                         file_put_contents('img/user/profile/'.$rand.'.jpg', $content);
                        if(!empty($user->avatar_location) && file_exists(public_path('img/user/profile/'.$user->avatar_location)))
                        {
                            unlink(public_path('img/user/profile/'.$user->avatar_location));
                        }
                    }
                    $fileName=$rand.'.jpg';    
            }
			else
            {
                $fileName=isset($user->avatar_location)?$user->avatar_location:'NULL';
            }

            $userupdate=array('user_group_id'=>$request->user_group_id,
                                'username'=>$request->username,
                                'email'=>$request->email,
                                'avatar_location'=>$fileName
                                    );

            DB::table('users')->where('id',$userdatacheck->id)->update($userupdate);
            //$check_auth = $this->checkToken($access_token,$userdatacheck->id);
            $social=array('provider'=>$request->provider,'provider_id'=>$request->provider_id);
            DB::table('social_accounts')->where('user_id',$userdatacheck->id)->update($social);
				$checkdeviceId=DB::table('user_devices')->where('user_id',$userdatacheck->id)->first();
				if(empty($checkdeviceId))
				{
					DB::table('user_devices')->insert(['user_id'=>$userdatacheck->id,'device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);
				}
				else
				{
					DB::table('user_devices')->where('user_id',$userdatacheck->id)->update(['device_type'=>$request->deviceType,'device_id'=>$request->deviceID]);
				}
        }
			// echo '<pre>'; print_r($user);exit;
            // print_r($checkRegister);exit;
            $user= DB::table('users')->where('id',$user->id)->first();
            $devicetoken=DB::table('user_devices')->where('user_id',$user->id)->first();
            $user->device_id= isset($devicetoken->device_id)?$devicetoken->device_id:'';
            $resultdata= $this->intToString($user );
            $resultArray['status']=1;
            $resultArray['type']='new';
            $resultArray['message']='Social login successfully.';
            $resultArray['session_key']='';//$check_auth['Data']['randnumber'];
            $resultArray['userData']=$resultdata;
            return response()->json($resultArray); exit;
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
        $validator = Validator::make($request->all(),
        [   
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
            $user= DB::table('users')->where('email',$email)->first();
            if($user->deleted_at !== null){
                // update account
                
                $dayinactive = 30;
                $date1 = date_create(date('Y-m-d H:i:s', strtotime(Carbon::now())));
                $date2 = date_create(date('Y-m-d H:i:s', strtotime($user->deleted_at)));
                $diff = date_diff($date1,$date2);
                $days = $diff->format("%a");
                $days = $dayinactive - $days;

                if($days <= 0 ){

                    $userupdate = array(
                    'user_group_id'=>$type,
                    'username'=>$username,
                    'email'=>$email,
                    'password'=>Hash::make($password),
                    'updated_at'=>date('Y-m-d H:i:s'),
                    'deleted_at'=>null,
                    );
                    DB::table('users')->where('id',$user->id)->update($userupdate);
                    $checkdeviceId=DB::table('user_devices')->where('user_id',$user->id)->first();
                    if(empty($checkdeviceId))
                    {
                        DB::table('user_devices')->insert(['user_id'=>$user->id,'device_type'=>$device_type,'device_id'=>$device_id]);
                    }
                    else
                    {
                        DB::table('user_devices')->where('user_id',$user->id)->update(['device_type'=>$device_type,'device_id'=>$device_id]);
                    }

                }else {
                    $message = 'Su cuenta se encuentra inactiva, por '.$days.' días. Por favor, comuníquese con el administrador para activar su cuenta.';
                    $resultArray['status']='1';   
                    $resultArray['message']=$message;
                    echo json_encode($resultArray); exit;
                }

            } else {
                // create account
                $adminaprovel = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null AND approval_status='1')")->first();
                if(!empty($adminaprovel))
                {   
                    if($adminaprovel->user_group_id==3||$adminaprovel->user_group_id==4)
                    {
                        $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at','approval_status')->whereRaw("(id = '".$adminaprovel->id."')")->first();
                        $resultArray['status']='1';        
                        $resultArray['message']=trans('apimessage.Email Already Exist.');
                        $resultArray['session_key']='';//$check_auth['Data']['randnumber'];
                        $resultdata= $this->intToString($users_count_var );
                        $resultArray['userData']=$resultdata;
                        echo json_encode($resultArray); exit;
                    }
                }
                $adminnotapprovel = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null AND approval_status='0')")->first();
                if(!empty($adminnotapprovel))
                {
                    if( $adminnotapprovel->user_group_id==3||$adminnotapprovel->user_group_id==4)
                    {
                        // $check_auth = $this->checkToken($access_token,$adminnotapprovel->id);
                        $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at','approval_status')->whereRaw("(id = '".$adminnotapprovel->id."')")->first();
                        $resultArray['status']='1';        
                        $resultArray['message']=trans('apimessage.Email Already Exist.');
                        $resultArray['session_key']='';//$check_auth['Data']['randnumber'];
                        $resultdata= $this->intToString($users_count_var );
                        $resultArray['userData']=$resultdata;
                        echo json_encode($resultArray); exit;
                    }
                }
                $adminaprovelreject = DB::table('users')->whereRaw("(email = '".$email."' AND deleted_at IS null AND approval_status='2')")->first();
                if(!empty($adminaprovelreject))
                {
                    if($adminaprovelreject->user_group_id==3||$adminaprovelreject->user_group_id==4)
                    {
                        DB::table('users')->where('id',$adminaprovelreject->id)->update(['approval_status'=>0]);
                        //$check_auth = $this->checkToken($access_token,$adminaprovelreject->id);
                        $users_count_var = DB::table('users')->select('id','user_group_id','username','email','active','user_group_id','created_at','updated_at','approval_status')->whereRaw("(id = '".$adminaprovelreject->id."')")->first();
                        $resultArray['status']='1';        
                        $resultArray['message']='update information';
                        $resultArray['session_key']='';//$check_auth['Data']['randnumber'];
                        $resultdata= $this->intToString($users_count_var );
                        $resultArray['userData']=$resultdata;
                        echo json_encode($resultArray); exit;
                    }
                }

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
                $registerArr['confirmed'] = 0;
                $registerArr['is_verified'] = 1;
                $registerArr['password'] = Hash::make($password);
                $registerArr['user_group_id'] = $type;
                $registerArr['confirmation_code'] = md5(uniqid(mt_rand(), 1));
                $registerArr['created_at'] = Carbon::now()->toDateTimeString();
                $registerArr['updated_at'] = Carbon::now()->toDateTimeString();
                $registerArr['remember_token'] = Hash::make('secret');
                $msg='';
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
                            $freecredit=DB::table('site_settings')->where('id',1)->first();
                            $addCredits['user_id'] = $userId;
                            $addCredits['transaction_date'] = Carbon::now()->toDateTimeString();
                            $addCredits['debit'] = isset($freecredit->free_credit)?$freecredit->free_credit:'20';
                            $addCredits['credit'] = '0';
                            $addCredits['current_balance'] = isset($freecredit->free_credit)?$freecredit->free_credit:'20';
                            $addCredits['updated_at'] = Carbon::now()->toDateTimeString();
                            $useradd=isset($freecredit->free_credit)?$freecredit->free_credit:'20';
                                DB::table('users')->where('id',$userId)->update(['pro_credit'=>$useradd]);

                            $addCreditsToContractor = DB::table('bonus')->insert($addCredits);
                        }
                        //  if($type==2)
                        // {
                            $objDemo = new \stdClass();
                            if($lang=='en')
                            {
                                $objDemo->message = 'Click here to verify your account.';
                            }
                            else
                            {
                                $objDemo->message = 'Pulse aquí para verificar su cuenta.';
                            }
                            $objDemo->link = url('/account/confirm/'.$registerArr['confirmation_code']);
                            $objDemo->sender = 'Buskalo';
                            $objDemo->receiver = $email;
                            $objDemo->level = '';
                            $objDemo->username = $username;
                            $objDemo->logo=url('img/logo/logo-svg.png');
                            $objDemo->footer_logo=url('img/logo/footer-logo.png');
                            $objDemo->user_icon=url('img/logo/logo.jpg');
                            
                            Mail::to($email)->send(new NewUserVerify($objDemo));

                            $msg=trans('exceptions.frontend.auth.confirmation.created_confirm');
                        // }
                        // $check_auth = $this->checkToken($access_token,$userId);
                    if(1!=1)
                    {
                        //echo json_encode($check_auth); exit;
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
                        // $resultArray['userData'] = $users_count_var;     
                        $resultArray['message']=$msg;
                        $resultArray['session_key']='';//$check_auth['Data']['randnumber'];
                            $resultdata= $this->intToString($users_count_var );
                            $resultArray['userData']=$resultdata;
                        echo json_encode($resultArray); exit;
                    } 
                }   
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Sorry some problem occurs please try again.');
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
                $resultArray['message ']=trans('apimessage.category_list_found_successfully');
               // $resultArray['data']=$categoryArray;
               $resultdata= $this->intToString($categoryArray);
               $resultArray['data']=$resultdata;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
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
                $resultArray['message ']=trans('apimessage.service_list_found_successfully');
                $dateresult= $this->intToString($serviceArray);
                $resultArray['data']=$dateresult;                  
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
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
            $serviceImage = array();
            $lang = !empty($request->lang) ? $request->lang : 'en';
            
            App::setLocale($lang);

             // $serv = DB::table('services')
             //                ->whereRaw("(status=1)")
             //                ->whereRaw("(deleted_at IS null)")
             //                ->get(); 
            $serv = DB::table('services')
                    ->select('services.*')
                    ->leftjoin('category','category.id','=','services.category_id')
                    ->where('services.image','!=','')
                    ->where('services.deleted_at',NULL)
                    ->where('category.es_name','!=','SERVICIOS ONLINE')
                    ->get();
            foreach($serv as $service) 
            {
                if($lang=='es'){$name=$service->es_name;}else{$name=$service->en_name;}
                if(file_exists(public_path('img/'.$service->image)) && !empty($service->image))
                {
                    $image= url('/img/'.$service->image);
                }
                else
                {
                    $image=url('img/services/Alquiler.jpg');
                }
              
               array_push($serviceArray,array('id'=>$service->id,'category_id'=>$service->category_id, 'name'=>$name,'image'=>$image,'status'=>$service->status,'type' => 'service','created_at'=>$service->created_at));
            }
            foreach ($serv as $key => $value)
            {
               if(file_exists(public_path('img/'.$value->image)) && !empty($value->image))
                {
                    $servicename=isset($value->es_name)?$value->es_name:$value->en_name;
                    $serviceimage= url('/img/'.$value->image);
                    array_push($serviceImage,array('id'=>$value->id,'category_id'=>$value->category_id,'name'=>$servicename,'image'=>$serviceimage,'status'=>$value->status,'created_at'=>$value->created_at));
                }
            }
            if($serv && !empty($serviceArray))
            {
                $resultArray['status']='1';
                $resultArray['message ']=trans('apimessage.all_service_list_found_successfully');
               // $resultArray['data']=$serviceArray;  
                $resultdata= $this->intToString($serviceArray);
                $resultArray['data'] = $resultdata;              
                $resultArray['serviceimage'] = $serviceImage;              
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
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
                $resultArray['message ']=trans('apimessage.sub_service_list_found_successfully');
                //$resultArray['data']=$subServiceArray;  
                $dateresult= $this->intToString($subServiceArray);
                $resultArray['data']=$dateresult;              
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
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
                $resultArray['message ']=trans('apimessage.child_sub_service_list_found_successfully');
               //$resultArray['data']=$childArray;  
                $dateresult= $this->intToString($childArray);
                $resultArray['data']=$dateresult;                
                return json_encode($resultArray);
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
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
                $question_id = !empty($request->question_id) ? $request->question_id : '' ;
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

                    if(empty($question_id))
                    {
                         DB::table('questions')->update(['question_status'=>0]);
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
                                    
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."')")
                                    ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                                    ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )");
                                    if(!empty($question_id))
                                    {
                                        //$questionEntity->whereRaw("(questions.related_question_id = '".$question_id."')")
                                         $questionEntity->whereRaw("(questions.question_status=0)")
                                          ->whereRaw("(questions.id = '".$question_id."')");
                                    }else
                                    {
                                         $questionEntity->whereRaw("(questions.question_order=1)")
                                        ->whereRaw("(questions.is_related=0)");
                                    }

                                   $questionEntity11= $questionEntity->first();

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
                                    //->whereRaw("(questions.question_order=1)")
                                    //->whereRaw("(questions.is_related=0)")
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."')")
                                    ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)");
                                     if(!empty($question_id))
                                    {
                                       
                                          $questionEntity->whereRaw("(questions.question_status=0)")
                                          ->whereRaw("(questions.id = '".$question_id."')");
                                    }else
                                    {
                                         $questionEntity->whereRaw("(questions.question_order=1)")
                                        ->whereRaw("(questions.is_related=0)");
                                    }
                                   $questionEntity11= $questionEntity->first();
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
                                   // ->whereRaw("(questions.question_order=1)")
                                    //->whereRaw("(questions.is_related=0)")
                                    ->whereRaw("(questions.category_id = '".$catId."')")
                                    ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)");
                                    if(!empty($question_id))
                                    {
                                         $questionEntity->whereRaw("(questions.question_status=0)")
                                          ->whereRaw("(questions.id = '".$question_id."')");
                                    }else
                                    {
                                         $questionEntity->whereRaw("(questions.question_order=1)")
                                        ->whereRaw("(questions.is_related=0)");
                                    }
                                   $questionEntity11= $questionEntity->first();

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
                                    //->whereRaw("(questions.question_order=1)")
                                    //->whereRaw("(questions.is_related=0)")
                                    ->whereRaw("(questions.category_id = '".$catId."' AND deleted_at IS null)");
                                     if(!empty($question_id))
                                    {
                                         $questionEntity->whereRaw("(questions.question_status=0)")
                                          ->whereRaw("(questions.id = '".$question_id."')");
                                    }else
                                    {
                                         $questionEntity->whereRaw("(questions.question_order=1)")
                                        ->whereRaw("(questions.is_related=0)");
                                    }
                                   $questionEntity11= $questionEntity->first();

                               }
                            if(!empty($questionEntity11))
                            {
                                //echo '<pre>'; print_r($questionEntity11);exit;
                                //echo '<pre>'; print_r($questionOptionEntity);exit;

                                // foreach ($questionEntity11 as $question) 
                                // {
                                    $arr['question_id'] =  isset($questionEntity11) && !empty($questionEntity11->id) ? (string)$questionEntity11->id : '' ;

                                    $arr['category_id'] =  isset($questionEntity11) && !empty($questionEntity11->category_id) ? (string)$questionEntity11->category_id : '' ;

                                    $arr['services_id'] =  isset($questionEntity11) && !empty($questionEntity11->services_id) ? (string)$questionEntity11->services_id : '' ;

                                    $arr['sub_services_id'] =  isset($questionEntity11) && !empty($questionEntity11->sub_services_id) ? (string)$questionEntity11->sub_services_id : '' ;

                                    $arr['child_sub_service_id'] =  isset($questionEntity11) && !empty($questionEntity11->child_sub_service_id) ? (string)$questionEntity11->child_sub_service_id : '' ;

                                     $arr['question_type'] = $questionEntity11->question_type;

                                     $arr['is_related'] =  isset($questionEntity11) && !empty($questionEntity11->is_related) ? (string)$questionEntity11->is_related : '' ;

                                     $arr['questionEntity11_order'] =  isset($questionEntity11) && !empty($questionEntity11->question_order) ? (string)$questionEntity11->question_order : '' ;

                                     if($lang=='en')
                                     {
                                       $arr['question']=isset($questionEntity11) && !empty($questionEntity11->en_title) ? (string)$questionEntity11->en_title : '' ; 
                                     }else
                                     {
                                     $arr['question']=isset($questionEntity11) && !empty($questionEntity11->es_title) ? (string)$questionEntity11->es_title : '' ; 
                                     }
                                      $arr['related_question_id'] =  isset($questionEntity11) && !empty($questionEntity11->related_question_id) ? $questionEntity11->related_question_id : '' ;

                                       $arr['related_option_id'] =  isset($questionEntity11) && !empty($questionEntity11->related_option_id) ? $questionEntity11->related_option_id : '' ;

                                     $arr['status'] =  isset($questionEntity11) && !empty($questionEntity11->status) ? $questionEntity11->status : '' ;

                                    $arr['created_at'] =  isset($questionEntity11) && !empty($questionEntity11->created_at) ? $questionEntity11->created_at : '' ;


                                     $questionOptionEntity = DB::table('question_options')
                                    ->select('id','en_option','es_option','created_at','status')
                                    ->whereRaw("(status=1)")
                                    ->whereRaw("(question_id = '".$questionEntity11->id."' AND deleted_at IS null )")
                                    ->get();
                                     //echo '<pre>'; print_r($questionOptionEntity);exit;

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

                               // }

                                if(!empty($allData) && count($allData) > 0)
                                {
                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('apimessage.data_found_successfully');
                                   // $resultArray['data']=$allData;
                                    $dateresult= $this->intToString($allData);
                                    $resultArray['data']=$dateresult; 
                                    echo json_encode($resultArray); exit;
                                } 
                                else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('apimessage.data_not_found');
                                    echo json_encode($resultArray); exit;
                                }
                            }
                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.data_not_found');
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
                //'option_id' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                }  

            if(!empty($type_id) && !empty($type) && !empty($question_id) )
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

                    $query= DB::table('questions')
                    ->whereRaw("(questions.status=1)")
                    //->whereRaw("(questions.is_related=1)")
                    ->whereRaw("(related_question_id = '".$question_id."')");
                   if(!empty($option_id))
                    {
                         $query->whereRaw("(related_option_id = '".$option_id."')");
                    }
                   $questionEntity =$query->whereRaw("(questions.category_id = '".$catId."')")
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

                    $query = DB::table('questions')
                    ->whereRaw("(questions.status=1)")
                    //->whereRaw("(questions.is_related=1)")
                    ->whereRaw("(related_question_id = '".$question_id."')");
                    if(!empty($option_id))
                    {
                         $query->whereRaw("(related_option_id = '".$option_id."')");
                    }
                    $questionEntity=$query->whereRaw("(questions.category_id = '".$catId."')")
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

                     $query= DB::table('questions')
                    ->whereRaw("(questions.status=1)")
                    //->whereRaw("(questions.is_related=1)")
                    ->whereRaw("(related_question_id = '".$question_id."')");
                    if(!empty($option_id))
                    {
                         $query->whereRaw("(related_option_id = '".$option_id."')");
                    }
                   $questionEntity = $query->whereRaw("(questions.category_id = '".$catId."')")
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

                    $query= DB::table('questions')
                    ->whereRaw("(questions.status=1)")
                    //->whereRaw("(questions.is_related=1)")
                    ->whereRaw("(related_question_id = '".$question_id."')");
                     if(!empty($option_id))
                    {
                         $query->whereRaw("(related_option_id = '".$option_id."')");
                    }
                     $questionEntity= $query->whereRaw("(questions.category_id = '".$catId."' AND deleted_at IS null)")
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
                           if(count($questionOptionEntity)>0)
                           {
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
                            }
                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.data_not_found');
                                echo json_encode($resultArray); exit;
                            }
                              
                           $arr['options']=$options ;
                            array_push($allData, $arr);
                        }

                           if(!empty($allData) && count($allData) > 0)
                            {
                                $resultArray['status']='1';
                                $resultArray['message']=trans('apimessage.data_found_successfully');
                                $resultArray['data']=$allData;
                                echo json_encode($resultArray); exit;
                            } else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.data_not_found');
                                echo json_encode($resultArray); exit;
                            }
                }else
                {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.data_not_found');
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

        public function questionType(Request $request)
        {
            $access_token=123456;
            $arr=array();
            $type_id = !empty($request->type_id)?$request->type_id:'' ;
            $type = !empty($request->type)?$request->type: '' ;
            $servicetype  = !empty($request->servicetype ) ? $request->servicetype : '' ;
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

                         $questionEntity1 = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                        //->whereRaw("(questions.question_order=2)")
                        //->whereRaw("(questions.is_related=0)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                        ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                        ->get();
                        if(count($questionEntity1)>1)
                        {
                             $data=$questionEntity1[1];
                           $questionEntity = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                           // ->whereRaw("(questions.question_order=2)")
                            //->whereRaw("(questions.is_related=0)")
                             ->whereRaw("(questions.id='".$data->id."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->first(); 
                        }else
                        {
                            $questionEntity = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            ->whereRaw("(questions.question_order=1)")
                            //->whereRaw("(questions.is_related=0)")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->first(); 
                        }

                         

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

                        $questionEntity1 = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                         //->whereRaw("(questions.question_order=2)")
                        //->whereRaw("(questions.is_related=0)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                        ->get();

                        if(count($questionEntity1)>1)
                        {   
                            $data=$questionEntity1[1];
                            $questionEntity = DB::table('questions')
                                ->whereRaw("(questions.status=1)")
                                // ->whereRaw("(questions.question_order=2)")
                                ->whereRaw("(questions.id='".$data->id."')")
                                ->whereRaw("(questions.category_id = '".$catId."')")
                                ->whereRaw("(questions.services_id = '".$servId."')")
                                ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                                ->first();
                                 //print_r($data);exit;
                        }
                        else
                        {
                             $questionEntity = DB::table('questions')
                                ->whereRaw("(questions.status=1)")
                                 ->whereRaw("(questions.question_order=1)")
                                //->whereRaw("(questions.is_related=0)")
                                ->whereRaw("(questions.category_id = '".$catId."')")
                                ->whereRaw("(questions.services_id = '".$servId."')")
                                ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                                ->first();

                        }
                        

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

                        $questionEntity1 = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                       //->whereRaw("(questions.question_order=2)")
                        //->whereRaw("(questions.is_related=0)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                        ->get();
                        if(count($questionEntity1)>1)
                        {
                            $data=$questionEntity1[1];
                            $questionEntity = DB::table('questions')
                                ->whereRaw("(questions.status=1)")
                                ->whereRaw("(questions.id='".$data->id."')")
                                //->whereRaw("(questions.question_order=2)")
                                //->whereRaw("(questions.is_related=0)")
                                ->whereRaw("(questions.category_id = '".$catId."')")
                                ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                                ->first();
                        }
                        else
                        {
                            $questionEntity = DB::table('questions')
                                ->whereRaw("(questions.status=1)")
                                ->whereRaw("(questions.question_order=1)")
                                //->whereRaw("(questions.is_related=0)")
                                ->whereRaw("(questions.category_id = '".$catId."')")
                                ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                                ->first();
                        }
                         

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
                        //->whereRaw("(questions.is_related=0)")
                        ->whereRaw("(questions.category_id = '".$catId."' AND deleted_at IS null)")
                        ->first();

                   }

                // if(count($questionEntity)>0)
                // {
                   
                    // foreach ($questionEntity as $key=> $question) 
                    // {
                         if($questionEntity->is_related==0)
                        {
                           $arr['dependency'] ='No'; 
                        }
                        else
                        {
                            $arr['dependency'] ='Yes'; 
                        } 

                        $arr['question_type'] =isset($questionEntity) && !empty($questionEntity->question_type) ? (string)$questionEntity->question_type : '' ;
                    //}
                    if(!empty($arr))
                    {
                        $resultArray['status']='1';
                        $resultArray['message']=trans('apimessage.data_found_successfully');
                       // $resultArray['data']=$allData;
                        $dateresult= $this->intToString($arr);
                        $resultArray['data']=$dateresult; 
                        echo json_encode($resultArray); exit;
                    } 
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.data_not_found');
                        echo json_encode($resultArray); exit;
                    }
                // }
                // else
                // {
                //     $resultArray['status']='0';
                //     $resultArray['message']=trans('apimessage.data_not_found');
                //     echo json_encode($resultArray); exit;

                // }     
        }

        public function multipleQuestion(Request $request)
        {
            $access_token=123456;
            $allData=array();$arr=array();$arr2=array();
            $question_type = !empty($request->question_type) ? $request->question_type :'';
            $type_id = !empty($request->type_id) ? $request->type_id :'';
            $type = !empty($request->type) ? $request->type :'';
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);
            if($question_type=='undependency')
            {   

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
                    ->whereRaw("(questions.is_related=0)")
                    //->whereRaw("(questions.question_type='checkbox')")
                    ->whereRaw("(questions.category_id = '".$catId."')")
                    ->whereRaw("(questions.services_id = '".$servId."')")
                    ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                    ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                    ->orderBy('question_order','asc')
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
                    ->whereRaw("(questions.is_related=0)")
                    //->whereRaw("(questions.question_type='checkbox')")
                    ->whereRaw("(questions.category_id = '".$catId."')")
                    ->whereRaw("(questions.services_id = '".$servId."')")
                    ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                    ->orderBy('question_order','asc')
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
                    //->whereRaw("(questions.question_order=1)")
                    ->whereRaw("(questions.is_related=0)")
                    //->whereRaw("(questions.question_type='checkbox')")
                    ->whereRaw("(questions.category_id = '".$catId."')")
                    ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                    ->orderBy('question_order','asc')
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
                    ->whereRaw("(questions.is_related=0)")
                    //->whereRaw("(questions.question_type='checkbox')")
                    ->whereRaw("(questions.category_id = '".$catId."' AND deleted_at IS null)")
                    ->orderBy('question_order','asc')
                    ->get();
                }

                        // $questionEntity = DB::table('questions')
                        //         ->whereRaw("(questions.status=1)")
                        //         ->whereRaw("(questions.question_order=1)")
                        //         ->whereRaw("(questions.is_related=0)")
                        //         ->where('')
                        //         ->get();
                    if(count($questionEntity)>0)
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
                            $resultArray['message']=trans('apimessage.data_found_successfully');
                           // $resultArray['data']=$allData;
                            $dateresult= $this->intToString($allData);
                            $resultArray['data']=$dateresult; 
                            echo json_encode($resultArray); exit;
                        } 
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.data_not_found');
                            echo json_encode($resultArray); exit;
                        }
                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.data_not_found');
                        echo json_encode($resultArray); exit;
                    }
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.invalid_question_dependency');
                echo json_encode($resultArray); exit;
            }
        }

        public function checkNextQuestion(Request $request)
        {
            $question_id = !empty($request->question_id) ? $request->question_id :'';
            $type_id = !empty($request->type_id) ? $request->type_id :'';
            $type = !empty($request->type) ? $request->type :'';
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
                App::setLocale($lang);

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
                ->whereRaw("(questions.is_related=1)")
                ->whereRaw("(questions.category_id = '".$catId."')")
                ->whereRaw("(questions.services_id = '".$servId."')")
                ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                ->whereRaw("(questions.related_question_id = '".$question_id."')")
                ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                ->first();
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
                ->whereRaw("(questions.is_related=0)")
                ->whereRaw("(questions.category_id = '".$catId."')")
                ->whereRaw("(questions.services_id = '".$servId."')")
                ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                ->whereRaw("(questions.question_status =0)")
                ->first();
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
                //->whereRaw("(questions.question_order=1)")
                ->whereRaw("(questions.is_related=0)")
                ->whereRaw("(questions.category_id = '".$catId."')")
                ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                ->whereRaw("(questions.related_question_id = '".$question_id."')")
                ->first();
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
                ->whereRaw("(questions.is_related=0)")
                ->whereRaw("(questions.category_id = '".$catId."' AND deleted_at IS null)")
                ->whereRaw("(questions.related_question_id = '".$question_id."')")
                ->first();
            }
                if(!empty($questionEntity))
                {
                    DB::table('questions')->where('id',$question_id)->update(['question_status'=>1]);
                    if($type=='child_sub_service')
                    {
                        $questionEntity1 = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            ->whereRaw("(questions.question_status=0)")
                            ->whereRaw("(questions.is_related=0)")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->first();
                    }
                     if($type=='sub_service')
                    {
                        $questionEntity1 = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            //->whereRaw("(questions.question_order=1)")
                            ->whereRaw("(questions.is_related=0)")
                            ->whereRaw("(questions.question_status=0)")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                             ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->first();
                    }
                     if($type=='service')
                    {
                        $questionEntity1 = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            //->whereRaw("(questions.question_order=1)")
                            ->whereRaw("(questions.is_related=0)")
                            ->whereRaw("(questions.question_status=0)")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                            ->first();
                    }
                     if(!empty($questionEntity1))
                     {
                        $dateresult['id']=$questionEntity1->id;
                        $dateresult['type_id']=$type_id;
                        $dateresult['type']=$type;
                        $resultArray['status']='1';
                        $resultArray['message']=trans('apimessage.data_found_successfully');
                        $resultArray['data']=$dateresult; 
                        echo json_encode($resultArray); exit;
                     }
                     else
                     {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.data_not_found');
                        echo json_encode($resultArray); exit;
                     }
                   
                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.data_not_found');
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
                $question_type=!empty($request->question_type) ? $request->question_type : NULL ;

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
                $file_image = !empty($request->file_image) ? $request->file_image : '' ;
                $deviceId = !empty($request->device_id) ? $request->device_id : '' ;
                $deviceType = !empty($request->device_type) ? $request->device_type : '' ;

                $variable_fields_data = json_decode($ques_options);
                $queOptionData = json_decode(json_encode($variable_fields_data), true);
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
                           
                            if(!empty($service_id) && !empty($sub_service_id) )
                            {  
                                $secondservice= DB::table('sub_services')->where('id',$sub_service_id)->first();
                                $service_credit=isset($secondservice->price)?$secondservice->price:0;
                            }
                            if(!empty($child_sub_service_id))
                            {
                                $childservice= DB::table('child_sub_services')->where('id',$child_sub_service_id)->select('percentage')->first();
                                $service_credit= ($service_credit*$childservice->percentage)/100;
                            }


                            if(!empty($userEntity))
                            {  
                                $registerData='old';
                                $userid= $userEntity->id;
                            }
                            else
                            {
                                $registerData='new';
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

                                $mobileCheck=DB::table('users')->where('mobile_number',$mobile_number)->first();
                                
                                if(!empty($mobileCheck))
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('Su número de móvil ingresado ya se ha utilizado. por favor ingrese un nuevo número de teléfono celular.');
                                        echo json_encode($resultArray);exit;
                                }
                                $userId = DB::table('users')->insertGetId($registerArr);
                                if(!empty($userId)) 
                                {
                                    //Start Send Mail to new User
                                    $objDemo = new \stdClass();
                                    $objDemo->password = $password;
                                    if($lang=='en')
                                    {
                                        $objDemo->message = 'Thank You for your service request in buskalo, we create your account with default password please login with this password.';
                                    }
                                    else
                                    {
                                        $objDemo->message = 'Gracias por su solicitud de servicio en buskalo, creamos su cuenta con contraseña predeterminada por favor inicie sesión con esta contraseña.';
                                    }
                                    
                                    
                                    $objDemo->sender = 'Buskalo';
                                    $objDemo->receiver = $email;
                                    $objDemo->username = $username;
                                    $objDemo->logo=url('img/logo/logo-svg.png');
                                    $objDemo->footer_logo=url('img/logo/footer-logo.png');
                                    if(isset($this->$userEntity->avatar_location) && !empty($this->$userEntity->avatar_location))
                                    {
                                        $objDemo->user_icon=url('img/user/profile/'.$this->$userEntity->avatar_location);
                                    }
                                    else
                                    {
                                        $objDemo->user_icon=url('img/logo/logo.jpg');
                                    }
                                    //Mail::to($email)->send(new WelcomeNewUser($objDemo));
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

                            if(!empty($deviceId) && !empty($deviceType))
                            {
                                $userdevicecheck=DB::table('user_devices')->where('user_id',$userid)->first();
                                if(empty($userdevicecheck))
                                {
                                    DB::table('user_devices')->insert(['user_id'=>$userid,'device_id'=>$deviceId,'device_type'=>$deviceType]);
                                }
                                else
                                {
                                     DB::table('user_devices')->where('user_id',$userid)->update(['device_id'=>$deviceId,'device_type'=>$deviceType]);
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
                                if($lang=='en')
                                {
                                    $objDemo->otpcode = 'Your unique code is: '.$otpcode;
                                    $objDemo->message = 'Thank You for your service request in buskalo, please use this otp for complete your request.';
                                }
                                else
                                {
                                    $objDemo->otpcode = $otpcode;
                                    $objDemo->message = 'Gracias por su solicitud de servicio en Búskalo, utilice este código para completar su solicitud.';
                                }
                                $objDemo->sender = 'Buskalo';
                                $objDemo->receiver = $email;
                                $objDemo->username = $username;
                                if(isset($userEntity->avatar_location) && !empty($userEntity->avatar_location))
                                {   
                                    if(file_exists(public_path('img/user/profile/'.$userEntity->avatar_location)))
                                    {
                                       $objDemo->user_icon=url('img/user/profile/'.$userEntity->avatar_location); 
                                    }
                                    
                                }
                                else
                                {
                                    $objDemo->user_icon = url('img/logo/logo.jpg');
                                }
                                
                                $objDemo->footer_logo = url('img/logo/footer-logo.png');
                                $objDemo->logo = url('img/logo/logo-svg.png');
                                Mail::to($email)->send(new ServiceRequestOtp($objDemo));
                                //End send Mail
                                $userToken = DB::table('users')
                                    ->leftjoin('user_devices','user_devices.user_id','=','users.id')
                                    ->where('users.email',$email)
                                    ->select('user_devices.*')
                                    ->first();
                                    $device_id=$userToken->device_id;
                                    $title='Código de confirmación';
                                    $message= $otpcode.' es tu código de confirmación, ingresalo en tu app de Buskalo';
                                    $userid=0;
                                    $prouserId=0;
                                    $serviceId=0;
                                    $senderId=0;
                                    $reciverId=0;
                                    $chatType=0;
                                    $senderName=0;
                                    $notify_type='otp_code';
                                // if($userToken->device_type=='android')
                                // {
                                    $this->postpushnotification($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                                // }
                                // if($userToken->device_type=='ios')
                                // {
                                //      $this->iospush($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                                // }
                            }
                                $targetDir=public_path('img/services/');
                                if(!empty($file_image))
                                {
                                    foreach($_FILES['file_image']['name'] as $key=>$val) 
                                    {
                                        $fileName =basename($_FILES['file_image']['name'][$key]); 
                                        $targetFilePath = $targetDir . $fileName;
                                       
                                          move_uploaded_file($_FILES["file_image"]["tmp_name"][$key], $targetFilePath);
                                    }
                                }   
                               
                                foreach($queOptionData as $key => $value) 
                                {
                                    $QuestOptions['service_request_id'] = $serviceRequestId;
                                    $QuestOptions['question_id'] = $value['question_id']; 
                                    $QuestOptions['date_time'] =  $value['date_time'];
                                    $QuestOptions['fileName'] =  $value['fileName'];
                                    $QuestOptions['quantity'] =  $value['quantity'];
                                    $multioption[$key]=$value['option_id'];

                                    if(count($multioption[$key])>0)
                                    {
                                        foreach ($multioption[$key] as $m => $mq)
                                        {
                                            $QuestOptions['option_id']=$mq;
                                            $questionPrice= DB::table('question_options')->where('id',$mq)->select('factor')->first();
                                            if(!empty($questionPrice->factor))
                                            {
                                                $service_credit=($service_credit*$questionPrice->factor)/100;
                                            }
                                            
                                           $saveQuestOptions = DB::table('service_request_questions')->insert($QuestOptions);
                                            
                                        } 
                                    }else
                                    {
                                        $QuestOptions['option_id'] = '';
                                        $saveQuestOptions = DB::table('service_request_questions')->insert($QuestOptions);
                                    } 
                                }

                                    $getcityzone=DB::table('zone')->where('city_id',$city_id)->get();
                                    if(count($getcityzone)>0)
                                    {
                                        foreach ($getcityzone as $key => $value)
                                        {
                                          $zone=json_decode($value->latlng);
                                        
                                          $datalat=array();
                                          $datalong=array();
                                            if(!empty($zone))
                                            {
                                                foreach ($zone as $key => $polygon)
                                                {

                                                   $lat['lat']=$polygon[0];
                                                   $long['long']=$polygon[1];
                                                   array_push($datalat, $polygon[0]);
                                                   array_push($datalong, $polygon[1]);

                                                }
                                            }
                                            $vertices_x=$datalat;
                                            $vertices_y=$datalong;
                                            $points_polygon = count($vertices_x)-1;
                                            $longitude_x =$latitude; 
                                            $latitude_y =$longitude;  
                                            if ($this->is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y))
                                            {
                                                if($value->area_type=='low_resources_area_2')
                                                {

                                                 $service_credit=($service_credit*88)/100;
                                                }
                                                if($value->area_type=='avg_resources_area')
                                                {

                                                  $service_credit=($service_credit*100)/100;
                                                }
                                                if($value->area_type=='high_resources_area_1')
                                                {

                                                  $service_credit=($service_credit*113)/100;
                                                }
                                                if($value->area_type=='high_resources_area_2')
                                                {

                                                  $service_credit=($service_credit*125)/100;
                                                }
                                                else
                                                {
                                                    $service_credit=($service_credit*75)/100;
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $service_credit=($service_credit*75)/100;
                                    }

                                 

                       
                $resultArray['status']='1';  

                 $resultArray['message']=trans('apimessage.your_email_account');
                 $resultArray['request_id']=$serviceRequestId;
                 $resultArray['service_amount']=strval($service_credit);
                 $resultArray['register_status']=$registerData;
                echo json_encode($resultArray);exit;

         }
         function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
            {
              $i = $j = $c = 0;
              for ($i = 0, $j = $points_polygon-1 ; $i < $points_polygon; $j = $i++) {
                if ( (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) ) 
                    $c = !$c;
              }
              return $c;
            }

            public function verifyServiceRequestOTP(Request $request)
            {
               
                $otp = isset($request->otp) && !empty($request->otp) ? $request->otp : '' ;
                $request_id = isset($request->request_id) && !empty($request->request_id) ? $request->request_id : '' ;
                $serviceAmount = isset($request->service_amount) && !empty($request->service_amount) ? $request->service_amount : '' ;
                $lang = !empty($request->lang) ? $request->lang : 'en' ;
                 App::setLocale($lang);

                $servicereq = DB::table('service_request')
                ->whereRaw("(id = '".$request_id."')")
                ->first(); 

                    if(!empty($servicereq))
                    {  
                       if($servicereq->email_verify==1)
                       {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.your_OTP_already_used_for_this_service');
                        echo json_encode($resultArray); exit; 
                       }
                       if($servicereq->otp==$otp AND $servicereq->email_verify==0)
                       {

                            //$update_Arr['email_verify'] = 1;
                            $update_Arr['updated_at'] = Carbon::now()->toDateTimeString();                  
                            if(DB::table('service_request')->where('id', $request_id)->update($update_Arr))
                            {


                                $serviceName=  $this->sendApportunityNotification($request_id,$serviceAmount);

                                $getData=array();
                                $getData = DB::table('service_request')->select('id','user_id','service_id','location','username','mobile_number','email','email_verify AS otp_verified', 'status','created_at','updated_at')->whereRaw("(id = '".$servicereq->id."')")->first();

                                $getquestionData = DB::table('service_request_questions')->select('question_id','option_id')->whereRaw("(service_request_id = '".$servicereq->id."')")->get();

                                $getData->questions_options = $getquestionData;
                                $getData->request_id = $request_id;

                                //Welcome to the new era!.
                                $resultArray['status']='1';     
                                $resultArray['message']=trans('apimessage.Congratulations! Your application has been approved; Within 24 hours you will receive the information up to 3 professionals who meet with your requirements and who are interested to help you.');
                                $resultArray['data'] = $getData;
                                echo json_encode($resultArray);
                                exit;
                                

                               // $getAllContCompny= DB::table('users')
                               //          ->select('users.username','user_devices.device_id','user_devices.device_type')
                               //          ->leftjoin('user_devices', 'users.id', '=', 'user_devices.user_id')
                               //          ->leftjoin('assign_service_request','assign_service_request.user_id','=','users.id')
                               //          ->where('assign_service_request.service_request_id',$request_id)->get();
                               //  foreach ($getAllContCompny as $key => $getuser)
                               //  {

                               //      $title='¡Nueva Oportunidad!';
                               //      if($lang=='en')
                               //      {
                               //         // $message='Great News: You have a new job opportunity for'.$serviceName.', check the details in your professional profile. At Buskalo we make your life easier.';
                               //          $message='Someone is looking for your services, enter OPPORTUNIDADES and get their information now!.';
                               //      }
                               //      else
                               //      {
                               //          $message='Alguién está buscando de tus servicios, ingresa a OPORTUNIDADES y obtén su información ahora!';
                               //      }
                                    
                               //      $userId=0;
                               //      $prouserId=0;
                               //      $serviceId=0;
                               //      $senderId=0;
                               //      $reciverId=0;
                               //      $chatType=0;
                               //      $senderName=$getuser->username;
                               //      $notify_type='new_opportunity';
                               //      $device_id=isset($getuser->device_id)?$getuser->device_id:'';
                               //      if(!empty($getuser->device_type) && $getuser->device_type=='android')
                               //      {
                               //          $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                               //      }
                               //      if(!empty($getuser->device_type) && $getuser->device_type=='ios')
                               //      {
                               //          $this->iospush($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                               //      }
                               //  }

                                 exit;
                            }
                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.something_went_wrong');
                                echo json_encode($resultArray); exit;
                            }
                       }
                       else
                       {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.Invalid OTP.');
                            echo json_encode($resultArray); exit; 
                       }
                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.requested_id_not_exist_in_our_database');
                        echo json_encode($resultArray); exit; 
                    }
            }


        public function serviceVerifyNotification(Request $request)
        {
            $request_id = isset($request->request_id) && !empty($request->request_id) ? $request->request_id : '' ;
            $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);

            $getAllContCompny= DB::table('users')
            ->select('users.username','users.email','users.avatar_location','users.user_group_id','user_devices.device_id','user_devices.device_type', 'assign_service_request.service_request_id','cities.name')
            ->join('user_devices', 'users.id', '=', 'user_devices.user_id')
            ->join('assign_service_request','assign_service_request.user_id','=','users.id')
            ->join('service_request','service_request.id','=','assign_service_request.service_request_id')
            ->join('cities','cities.id','=','service_request.city_id')
            ->where('assign_service_request.service_request_id',$request_id)->get();
            
            $servicesRequestedQues = DB::table('service_request_questions')
            ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
            ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
            ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option')
            ->whereRaw("(service_request_questions.service_request_id = '".$request_id."')")->get()->toArray(); 
            $options=array();
            $objDemo = new \stdClass();
            $objQuestion= new \stdClass();

            foreach ($servicesRequestedQues as $key => $que) 
            {
                $question=$que->es_title;
                $data2['question'] = isset($question) && !empty($question) ? $question : '';
                $option=$que->es_option;
                $data2['option'] = $option;
                array_push($options, $data2);
            }
            // Remove two last elements
            array_pop($options);
            array_pop($options);

            foreach ($getAllContCompny as $key => $getuser)
            {

                $title='¡Nueva Oportunidad!';
                if($lang=='en')
                {
                    // $message='Great News: You have a new job opportunity for'.$serviceName.', check the details in your professional profile. At Buskalo we make your life easier.';
                    $message='Someone is looking for your services, enter OPPORTUNIDADES and get their information now!.';
                }
                else
                {
                    $message='Alguién está buscando de tus servicios, ingresa a OPORTUNIDADES y obtén su información ahora!';
                }
                
                $userId=0;
                $prouserId=0;
                $serviceId=0;
                $senderId=0;
                $reciverId=0;
                $chatType=0;
                $senderName=$getuser->username;
                $notify_type='new_opportunity';
                $device_id=isset($getuser->device_id)?$getuser->device_id:'';
                $email = isset($getuser->email)?$getuser->email:'';
                $avatar_location = isset($getuser->avatar_location)?$getuser->avatar_location:'';
                $objDemo->avatar_location=$avatar_location;
                $objDemo->user_group_id = isset($getuser->user_group_id)?$getuser->user_group_id:'';
                $objDemo->city_name = isset($getuser->name)?$getuser->name:'';
                $objDemo->email = $email;
                $objDemo->username = $senderName;
                $objQuestion = $options;
                //if(!empty($getuser->device_type) && $getuser->device_type=='android')
                // {
                    $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                    Mail::to($email)->send(new NewOpportunity($objDemo, $objQuestion));

                // }
                // if(!empty($getuser->device_type) && $getuser->device_type=='ios')
                // {
                //     $this->iospush($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                // }
                echo 'send all notification';
            }
        } 

    // GET ALL NOTIFICATION
        
    public function getNotificaton( $id){
        $service_request = $id;
        $olddate=date('Y-m-d H:i:s', strtotime('-8 days'));

        $maxcount=DB::table('assign_service_request')->where('service_request_id',$service_request)->where('request_status','buy')->count();
        if($maxcount > 3)
        {
            $ent = 'es menor';
        }else
        {
            $ent = 'es mayor';
        }
            
        $resultArray['status']='1';
        $resultArray['message']= $id;
        $resultArray['ent']= $ent;
        $resultArray['dolddateata']=$olddate;
        $resultArray['data']=$maxcount;

        echo json_encode($resultArray); exit;
    }


         //Send Req. to all contractor amnd Company
        public function sendApportunityNotification($request_id,$serviceamount, $lang= null)
        {
                // $request_id = isset($request->request_id) && !empty($request->request_id) ? $request->request_id : '' ;
                if(!empty($request_id))
                {
                    
                    $servicereq = DB::table('service_request')
                        ->whereRaw("(status = '0')")
                        ->whereRaw("(id = '".$request_id."' AND deleted_at IS null )")
                        ->first();

                if(!empty($servicereq->service_id) && !empty($servicereq->sub_service_id) && !empty($servicereq->child_sub_service_id))
                {
                   $thirdservice= DB::table('child_sub_services')->where('id',$servicereq->child_sub_service_id)->first();

                    $service_credit=isset($thirdservice->price)?$thirdservice->price:0;
                    if($lang=='en')
                    {
                        $serviceName=$thirdservice->en_name;
                    }
                    else
                    {
                        $serviceName=$thirdservice->es_name;
                    }
                   

                }
                elseif(!empty($servicereq->service_id) && !empty($servicereq->sub_service_id) )
                {  
                    $secondservice= DB::table('sub_services')->where('id',$servicereq->sub_service_id)->first();
                    $service_credit=isset($secondservice->price)?$secondservice->price:0;
                    if($lang=='en')
                    {
                        $serviceName=$secondservice->en_name;
                    }
                    else
                    {
                        $serviceName=$secondservice->es_name;
                    }
                }
                elseif(!empty($servicereq->service_id))
                {   
                    $service= DB::table('services')->where('id',$servicereq->service_id)->first();
                    $service_credit= isset($service->price)?$service->price:0;
                     if($lang=='en')
                    {
                        $serviceName=$service->en_name;
                    }
                    else
                    {
                        $serviceName=$service->es_name;
                    }
                }

                    $getservicePrice= DB::table('price_range')->where('deleted_at', null)->get();
                    $price_credit=0;
                    foreach ($getservicePrice as $key => $price)
                    {
                        if(($price->start_price<=$serviceamount) && ($price->end_price>=$serviceamount))
                        {
                            $price_credits= ($serviceamount*$price->percentage)/100;
                            $price_credit=round($price_credits);
                        }
                    }
                    
                  if($servicereq)
                  {
                    $city_id=$servicereq->city_id;

                    //Get All Contractor and company, according to user service Request city and contractor & company service area and according their free slot.

                $getAllContCompny1 = DB::table('users')
                  ->join('services_offered', 'users.id', '=', 'services_offered.user_id')
                  ->leftjoin('users_services_area', 'users.id', '=', 'users_services_area.user_id')
                  ->leftjoin('user_devices', 'users.id', '=', 'user_devices.user_id')
                  //->leftjoin('service_request', 'users.id', '=', 'service_request.assigned_user_id')
                  ->select('users.id','users.ruc_no','users.username','users.address','users_services_area.whole_country','users_services_area.province_id','users_services_area.city_id','users.created_at','user_devices.device_id','user_devices.device_type')
                  ->whereIN('users_services_area.city_id',[$city_id])
                  ->whereIN('users.user_group_id',[3,4])
                  ->whereIN('services_offered.service_id',[$servicereq->service_id])
                  //->where('users.approval_status',1)
                  //->whereRaw('users_services_area.status',4)
                  ->whereRaw("(users.deleted_at IS null )")
                    ->groupBy('services_offered.user_id')->get()->toArray();

                $getAllContCompny2 = DB::table('users')
                    ->join('services_offered', 'users.id', '=', 'services_offered.user_id')
                    ->leftjoin('users_services_area', 'users.id', '=', 'users_services_area.user_id')
                    ->leftjoin('user_devices', 'users.id', '=', 'user_devices.user_id')
                    ->select('users.id','users.ruc_no','users.username','users.address','users_services_area.whole_country','users_services_area.province_id','users_services_area.city_id','users.created_at','user_devices.device_id','user_devices.device_type')
                    ->whereIN('users_services_area.whole_country',[1])
                     ->where('users.approval_status',1)
                    ->whereIN('users.user_group_id',[3,4])
                    ->whereIN('services_offered.service_id',[$servicereq->service_id])
                    ->whereRaw("(users.deleted_at IS null )")
                    ->groupBy('services_offered.user_id')->get()->toArray();
                    $getAllContCompny= array_merge($getAllContCompny1,$getAllContCompny2);
                   // echo '<pre>'; print_r($getAllContCompny);exit;
                    $resultArray =$this->uniqueAssocArray($getAllContCompny, 'id');


                    if(!empty($resultArray))
                    { 
                        foreach ($resultArray as $key => $getuser) 
                        {
                            $insert['user_id'] = $getuser->id;    
                            $insert['service_request_id'] = $request_id;
                            $insert['credit'] = isset($price_credit)?$price_credit:0;
                            $insert['created_at'] = Carbon::now();  
                            DB::table('assign_service_request')->insertGetId($insert);
                           
                        }

                        return $serviceName;
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
                    $resultArray['message']=trans('apimessage.invalid_request_id');
                    echo json_encode($resultArray); exit;
                }     
        }


        public function uniqueAssocArray($array, $uniqueKey) {
            if (!is_array($array)) {
                return array();
            }
            $uniqueKeys = array();
            foreach ($array as $key => $item) {
                $groupBy=$item->id;
                if (isset( $uniqueKeys[$groupBy]))
                {
                    //compare $item with $uniqueKeys[$groupBy] and decide if you 
                    //want to use the new item
                    $replace= '';
                }
                else
                {
                    $replace=true;
                }
                if ($replace) $uniqueKeys[$groupBy] = $item;   
            }
            return $uniqueKeys;
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
                    //'session_key' => 'required',
                    ]);

                    if($validator->fails())
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    } 


                    if(!empty($userid)) 
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
                                $resultArray['message']=trans('apimessage.request_list_found_successfully');
                                $resultdata= $this->intToString($allData);
                                $resultArray['data'] =$resultdata; 
                                echo json_encode($resultArray); exit;   
                            }
                            else
                            {
                                $resultArray['status']='0';   
                                $resultArray['message']=trans('apimessage.request_list_not_found');
                                echo json_encode($resultArray); exit;   
                            }         
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.request_not_found');
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
                $lang1 = !empty($request->lang) ? $request->lang : 'en' ;
                 App::setLocale($lang);

                    $validator = Validator::make($request->all(), [
                    'userid' => 'required',
                    //'session_key' => 'required',
                    'request_id' => 'required',
                    ]);

                    if($validator->fails())
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.Invalid parameters.');
                        echo json_encode($resultArray); exit;      
                    } 

                    if(!empty($userid) && !empty($request_id)) 
                    {
                        //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                        if(1!=1)
                        {
                            $check_auth='';
                         //echo json_encode($check_auth); exit;
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
                             ->leftjoin('assign_service_request', 'service_request.id', '=', 'assign_service_request.service_request_id')
                            ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                            ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
                             ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
                             ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
                             ->leftjoin('cities', 'cities.id', '=', 'service_request.city_id')
                             ->select('service_request.id','service_request.service_id','service_request.category_id','service_request.sub_service_id','service_request.child_sub_service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.mobile_number', 'assign_service_request.hire_status','service_request.created_at','services.en_name', 'services.es_name', 'services.image','category.en_name as en_catname', 'category.es_name as es_catname', 'sub_services.en_name as en_sub_servicesname', 'sub_services.es_name as es_sub_servicesname', 'child_sub_services.en_name as en_child_sub_servicessname', 'child_sub_services.es_name as es_child_sub_servicessname','cities.name')
                               ->where('service_request.id',$request_id)
                               ->where('service_request.user_id',$userid)
                               ->whereRaw("(service_request.deleted_at IS null )")
                               ->get(); 


                            if(!empty($servicesRequested))
                                   {
                                    
                                    //echo "<pre>"; print_r($servicesRequested); die;
                                    $data1=array();
                                    foreach ($servicesRequested as $key => $vall) 
                                    {

                                        $ratingData= DB::table('reviews')->where('request_id',$request_id)->where('user_id',$userid)->first();
                                        if(isset($ratingData) && !empty($ratingData))
                                        {

                                             $is_rate_status = $ratingData->is_rate_status; 
                                        }
                                        else
                                        {
                                            $is_rate_status='false';
                                        }

                                        $data1['id'] = $vall->id;
                                        $data1['service_id'] = $vall->service_id;
                                        $data1['category_id'] = !empty($vall->category_id)?$vall->category_id:'';
                                         if($lang=='es')
                                                {$service_name=$vall->es_name;}
                                            else{$service_name=$vall->en_name;}
                                       
                                         if($lang1=='es')
                                                {$category_name=!empty($vall->es_catname)?$vall->es_catname:'';}
                                            else{$category_name=!empty($vall->en_catname)?$vall->en_catname:'';}

                                         if($lang1=='es')
                                                {$sub_servicesname=!empty($vall->es_sub_servicesname)?$vall->es_sub_servicesname:'';}
                                            else{$sub_servicesname=!empty($vall->en_sub_servicesname)?$vall->en_sub_servicesname:'';}
                                            
                                         if($lang=='es')
                                                {!empty($child_sub_services=$vall->es_child_sub_servicessname)?$child_sub_services=$vall->es_child_sub_servicessname:'';}
                                            else{!empty($child_sub_services=$vall->en_child_sub_servicessname)?$child_sub_services=$vall->en_child_sub_servicessname:'';}

                                        $data1['is_rate_status'] = $is_rate_status;
                                        $data1['city_name'] = isset($vall->name)?$vall->name:'';
                                        $data1['service_name'] = $service_name;
                                        $data1['category_name'] = $category_name;
                                        $data1['sub_servicesname'] = $sub_servicesname;
                                        $data1['child_sub_services'] = $child_sub_services;
                                        $data1['service_image'] = url('/img/'.$vall->image);
                                        $data1['location'] = $vall->location;
                                        $data1['username'] = $vall->username;
                                        $data1['status'] = $vall->status;
                                        $data1['email_verify'] = $vall->email_verify;
                                        $data1['mobile_number'] = $vall->mobile_number;
                                        $data1['created_at'] = $vall->created_at;

                                        $servicesRequestedQues = DB::table('service_request_questions')
                                        ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                                         ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                                        ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','service_request_questions.date_time','service_request_questions.fileName','service_request_questions.quantity','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option','questions.question_type')
                                        ->whereRaw("(service_request_questions.service_request_id = '". $vall->id."')")->get()->toArray(); 

                                       
                                        $options=array();

                                        foreach ($servicesRequestedQues as $key => $que) 
                                        {
                                           $data2['id'] = $que->id;
                                           $data2['service_request_id'] = $que->service_request_id;
                                           $data2['question_id'] = $que->question_id;
                                           $data2['question_type'] = $que->question_type;

                                            if($lang=='es')
                                                {$question=$que->es_title;}
                                            else{$question=$que->en_title;}

                                           $data2['question'] = $question;

                                           $data2['option_id'] = isset($que->option_id)?$que->option_id:'';
                                           $data2['date_time'] = isset($que->date_time)?$que->date_time:'';
                                           $data2['file_name'] = isset($que->fileName)? url('img/services/'.$que->fileName):'';
                                           $data2['quantity'] = isset($que->quantity)?$que->quantity:'';

                                            if($lang=='es')
                                                {$option=$que->es_option;}
                                            else{$option=$que->en_option;}

                                           $data2['option'] = $option;

                                            array_push($options, $data2);
                                        }
                                        // GET ALL CONTRACTOR & COMPANY , RECIVED NOTIFICATION OF THIS REQUEST
                                    //$a = DB::table('assign_service_request')->where('hire_status', '=', '1')->count();
                                      $a = DB::table('assign_service_request')->where('service_request_id',$vall->id)->count();
                                    //print_r($a); die;
                                    if(!empty($a)){
                                        //print_r($a); die;

                                         $getAllAssignedCoAndCom = DB::table('assign_service_request')
                                        ->leftjoin('users', 'assign_service_request.user_id', '=', 'users.id')
                                        ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id' )
                                        ->select('users.id','users.user_group_id','users.username','users.email','users.mobile_number','users.avatar_location','assign_service_request.request_status', 'assign_service_request.hire_status','assign_service_request.job_status')
                                         ->where('request_status','buy')
                                          //->where('assign_service_request.hire_status','0')
                                         ->whereRaw("(assign_service_request.service_request_id = '".$request_id."')")->get()->toArray(); 

                                       // echo '<pre>'; print_r($getAllAssignedCoAndCom);exit;

                                    }else{
                                         $getAllAssignedCoAndCom = DB::table('assign_service_request')
                                        ->leftjoin('users', 'assign_service_request.user_id', '=', 'users.id')
                                        ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id' )
                                        ->select('users.id','users.user_group_id','users.username','users.email','users.mobile_number','users.avatar_location','assign_service_request.request_status', 'assign_service_request.hire_status','assign_service_request.job_status')
                                         ->where('request_status','buy')
                                         
                                         ->whereRaw("(assign_service_request.service_request_id = '".$request_id."')")->get()->toArray(); 

                                    }

                                    
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

                                            if (DB::table('assign_service_request')->where('hire_status', '=', '1')->count() > 0) {

                                           $assData['id'] = $datas->id;
                                           $assData['username'] = $datas->username;
                                           $assData['email'] = $datas->email;
                                           $assData['hire_status'] = $datas->hire_status;
                                           //$assData['job_status'] = 'accepted';
                                            $assData['job_status'] = '';
                                           if($datas->job_status==1)
                                           {
                                             $assData['job_status'] = '1';
                                           }
                                           elseif($datas->job_status==2)
                                           {
                                             $assData['job_status'] = '2';
                                           }
                                           elseif($datas->job_status==3)
                                           {
                                             $assData['job_status'] = '3';
                                           }
                                           elseif($datas->job_status==4)
                                           {
                                             $assData['job_status'] = '4';
                                           }
                                           elseif($datas->job_status==5)
                                           {
                                             $assData['job_status'] = '5';
                                           }
                                           $assData['mobile_number'] = $datas->mobile_number;


                                            if(file_exists(public_path($profilePath.$datas->avatar_location)))
                                            {
                                               $assData['profile']= isset($datas->avatar_location) && !empty($datas->avatar_location) ? url($profilePath.$datas->avatar_location) : '';

                                            } else 
                                            {
                                               $assData['profile']= '';
                                            }
                                               
                                            }

                                       else{

                                           $assData['id'] = $datas->id;
                                           $assData['username'] = $datas->username;
                                           $assData['email'] = $datas->email;
                                           $assData['hire_status'] = 0;
                                           $assData['job_status'] = 'pending';
                                           $assData['mobile_number'] = $datas->mobile_number;


                                            if(file_exists(public_path($profilePath.$datas->avatar_location)))
                                            {
                                               $assData['profile']= isset($datas->avatar_location) && !empty($datas->avatar_location) ? url($profilePath.$datas->avatar_location) : '';

                                            } else 
                                            {
                                               $assData['profile']= '';
                                            }

                                          }

                                           $assData['request_status'] = isset($datas->request_status) && !empty($datas->request_status) ? $datas->request_status : '';

                                            array_push($secOptions, $assData);
                                        }

                                        //END


                                        $data1['question_options']=$options ;

                                        $data1['assigned_contractor_and_companies']=$secOptions ;

                                        array_push($allData, $data1);
                                    }

                                    if(!empty($data1))
                                    {
                                        $resultArray['status']='1';   
                                        $resultArray['message']=trans('apimessage.request_detail_found_success');
                                        $resultArray['data'] = $data1; 
                                        echo json_encode($resultArray); exit;   
                                    }
                                    else
                                    {
                                        $resultArray['status']='0';   
                                        $resultArray['message']=trans('apimessage.request_detail_not_found');
                                        echo json_encode($resultArray); exit;   
                                    }
                                    
                                        

                                   }
                                   else
                                   {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('apimessage.request_not_found');
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
        //'session_key' => 'required',
        'request_id' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

        if(!empty($userid) && !empty($request_id)) 
        {
            //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
            if(1!=1)
            {
                $check_auth='';
                //echo json_encode($check_auth); exit;
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
                            ->leftjoin('service_request','assign_service_request.service_request_id', '=', 'service_request.id')
                            ->select('users.id','users.user_group_id','users.avatar_location','users.username','users.profile_title','users.website_address','users.address','users.year_of_constitution', 'users.total_employee', 'users.dob')
                            ->whereRaw("(assign_service_request.service_request_id = '".$request_id."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status = 'buy')")
                            ->get();
                            //echo "<pre>"; print_r($getAllBuyAppUsers);die;

                            if($getAllBuyAppUsers)
                            {

                                foreach ($getAllBuyAppUsers as$k=> $dataa)
                                {

                                    $getAllReview2 = DB::table('reviews')
                                                ->select('reviews.id','reviews.user_id','reviews.to_user_id','reviews.request_id','reviews.rating','reviews.price','reviews.puntuality','reviews.service','reviews.quality', 'reviews.amiability', 'reviews.review', DB::raw('sum(rating) as total_rating'), DB::raw('COUNT(id) as total_rating_count'),DB::raw('sum(price) as total_price'), DB::raw('sum(puntuality) as total_puntuality'), DB::raw('sum(reviews.service) as total_service'), DB::raw('sum(quality) as total_quality'), DB::raw('sum(amiability) as total_amiability'))
                                                ->where('reviews.to_user_id',$dataa->id)
                                                ->groupBy('to_user_id')
                                                ->whereRaw("(reviews.deleted_at IS null )")
                                                ->get();
                                    $price = $puntuality = $quality =$amiability = $service_review =$total_rating1=$total_rating_count1=0;
                                    if(count($getAllReview2)>0)
                                    {
                                        foreach ($getAllReview2 as $key => $value)
                                        {
                                            $price+= isset($value->total_price)?$value->total_price:0;
                                            $puntuality+= isset($value->total_puntuality)?$value->total_puntuality:0;
                                            $quality+= isset($value->total_quality)?$value->total_quality:0;
                                            $amiability+= isset($value->total_amiability)?$value->total_amiability:0;
                                            $service_review+= isset($value->total_service)?$value->total_service:0;
                                            $total_rating1=!empty($value->total_rating)?$value->total_rating:0;
                                            $total_rating_count1=!empty($value->total_rating_count)?$value->total_rating_count:0;
                                         }
                                    }
                                            if(!empty($price))
                                            {
                                                $presentdata['qualify']['total_price']= round($price/$total_rating_count1, 2);
                                            }
                                            else{
                                                $presentdata['qualify']['total_price']=0;
                                            }
                                            if(!empty($puntuality))
                                            {
                                                $presentdata['qualify']['puntuality']=round($puntuality/$total_rating_count1, 2);
                                            }
                                            else{
                                                $presentdata['qualify']['puntuality']=0;
                                            }
                                            if(!empty($quality))
                                            {
                                                $presentdata['qualify']['quality']=round($quality/$total_rating_count1, 2);
                                            }
                                            else{
                                                $presentdata['qualify']['quality']=0;
                                            }
                                            if(!empty($service_review))
                                            {
                                                $presentdata['qualify']['service_review']= round($service_review/$total_rating_count1, 2);
                                            }
                                            else{
                                                $presentdata['qualify']['service_review']=0;
                                            }
                                            if(!empty($amiability))
                                            {
                                                $presentdata['qualify']['amiability']=round($amiability/$total_rating_count1, 2);
                                            }else
                                            {
                                                $presentdata['qualify']['amiability']=0;
                                            }

                                    $presentdata['user_id']=$dataa->id;
                                    $presentdata['user_group_id']=$dataa->user_group_id;
                                    $presentdata['username']=$dataa->username;

                                    // if(!empty($price) && !empty($total_rating_count1)){
                                    // $presentdata['qualify']
                                    // ['total_price']=round($price/$total_rating_count1, 2);
                                    // }

                                    // if (!empty($puntuality) && !empty($total_rating_count1) ) {
                                    //     $presentdata['qualify']['puntuality']=round($puntuality/$total_rating_count1,2);
                                    // }

                                    // if(!empty($quality)&& !empty($total_rating_count1)){
                                    //     $presentdata['qualify']['quality']=round($quality/$total_rating_count1, 2);
                                    // }
                                    // if(!empty($amiability)&& !empty($total_rating_count1)){
                                    //     $presentdata['qualify']['amiability']=round($amiability/$total_rating_count1, 2);
                                    // }

                                    // if(!empty($service_review)&& !empty($total_rating_count1)){

                                    //     $presentdata['qualify']['service_review']=round($service_review/$total_rating_count1, 2);
                                    // }

                                    if($dataa->user_group_id==3)
                                    {
                                        $profilePath ='/img/contractor/profile/';
                                        $certifiePath ='/img/contractor/certifications/';
                                        $policePath ='/img/contractor/police_records/';
                                    }else
                                    {
                                        $profilePath ='/img/company/profile/';
                                        $certifiePath ='/img/company/certifications/';
                                        $policePath ='/img/company/police_records/';
                                    }

                                    if(!empty($profilePath.$dataa->avatar_location)) 
                                    {
                                        $presentdata['profile']= isset($dataa->avatar_location) && !empty($dataa->avatar_location) ? url($profilePath.$dataa->avatar_location) : '';
                                    }else
                                    {
                                        $presentdata['profile']= '';
                                    }
                                    $presentdata['profile_title']=isset($dataa->profile_title) && !empty($dataa->profile_title) ? $dataa->profile_title : '';
                                    if($dataa->user_group_id==4)
                                    {
                                        $presentdata['year_of_constitution']=isset($dataa->year_of_constitution) && !empty($dataa->year_of_constitution) ? $dataa->year_of_constitution : '';
                                    }

                                    if($dataa->user_group_id==3)
                                    {
                                        $presentdata['year_of_constitution']=isset($dataa->dob) && !empty($dataa->dob) ? $dataa->dob : '';
                                    }
                                        $presentdata['address']=isset($dataa->address) && !empty($dataa->address) ? $dataa->address : '';
                                        $presentdata['website_address']=isset($dataa->website_address) && !empty($dataa->website_address) ? $dataa->website_address : '';

                                        $presentdata['total_employee'] = $dataa->total_employee;

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
                                            { $srname='services.es_name AS service_name' ;}
                                            else{$srname='services.en_name AS service_name';}

                                            $servicesOffered = DB::table('services_offered')
                                            ->leftjoin('services', 'services_offered.service_id', '=', 'services.id')
                                            //->select('services_offered.id')
                                            ->select($srname)
                                            // ->select(DB::raw('group_concat('.$srname.') as service_name'))
                                            ->where('services_offered.user_id',$dataa->id)
                                            ->groupBy('services_offered.service_id')
                                            ->whereRaw("(services_offered.deleted_at IS null )")
                                            ->orderBy('services_offered.id','desc')
                                            ->get()->toArray();

                                            //print_r($servicesOffered); die;
                                            $serviceData=array();
                                            if(!empty($servicesOffered))
                                            {
                                                foreach ($servicesOffered as $service) 
                                                {
                                                    $serviceData[]= $service->service_name;
                                                }
                                            }

                                            $serviceData1 =implode(',', $serviceData);
                                            $total_rating_count = 0;
                                            $getAllReview = DB::table('reviews')
                                            ->select(DB::raw('sum(rating) as total_rating'), DB::raw('COUNT(id) as total_rating_count'), 'reviews.review')
                                            ->groupBy('to_user_id')
                                            ->whereRaw("(reviews.to_user_id = '".$dataa->id."' AND reviews.deleted_at IS null )")
                                            ->get()->toArray();

                                            if(!empty($getAllReview))
                                            {
                                                foreach ($getAllReview as $review) 
                                                {
                                                    //$presentdata1['rating']= $review->rating;
                                                    $total_rating=!empty($review->total_rating)?$review->total_rating:0;
                                                    $total_rating_count=!empty($review->total_rating_count)?$review->total_rating_count:0;
                                                    $review=!empty($review->review)?$review->review:0;
                                                }
                                            }


                                            // if(!empty($price) && !empty($total_rating_count)){
                                            // $presentdata['qualify']
                                            // ['total_price']=round($price/$total_rating_count, 2);
                                            // }

                                            // if (!empty($puntuality) && !empty($total_rating_count) ) {
                                            //     $presentdata['qualify']['total_puntuality']=round($puntuality/$total_rating_count,2);
                                            // }

                                            // if(!empty($quality)&& !empty($total_rating_count)){
                                            //     $presentdata['qualify']['total_quality']=round($quality/$total_rating_count, 2);
                                            // }
                                            // if(!empty($amiability)&& !empty($total_rating_count)){
                                            //     $presentdata['qualify']['total_amiability']=round($amiability/$total_rating_count, 2);
                                            // }

                                            // if(!empty($service_review)&& !empty($total_rating_count)){

                                            //     $presentdata['qualify']['service_rating']=round($service_review/$total_rating_count, 2);
                                            // }

                                                //echo "<pre>"; print_r($count); die;

                                                ///////////////////////services offered/////////////////
                                                if(!empty($review))
                                                {
                                                    $presentdata['review']=$review;
                                                }

                                                $presentdata['rating_count']=$total_rating_count ;

                                                $presentdata['global_rating']=0;

                                                if(!empty($total_rating) && !empty($total_rating_count))
                                                {
                                                    $presentdata['global_rating']=round($total_rating/$total_rating_count,2);
                                                }

                                                $presentdata['payment_methods']=$spaymentData ;
                                                $presentdata['services_offered']=$serviceData1 ;
                                                $presentdata['social_url']=$datasocial ;

                                                $getAllReview1 = DB::table('reviews')
                                                ->select('reviews.id','reviews.user_id','reviews.to_user_id','reviews.request_id','reviews.rating','reviews.price','reviews.puntuality','reviews.service','reviews.quality', 'reviews.amiability', 'reviews.review', DB::raw('sum(price) as total_price'), DB::raw('sum(puntuality) as total_puntuality'), DB::raw('sum(reviews.service) as total_service'), DB::raw('sum(quality) as total_quality'), DB::raw('sum(amiability) as total_amiability'))
                                                ->where('reviews.to_user_id',$dataa->id)
                                                ->groupBy('to_user_id')
                                                ->whereRaw("(reviews.deleted_at IS null )")
                                                ->get()->toArray();
                                                // echo $getAllReview1->to_user_id; 
                                               // $options=array();
                                                
                                                if(!empty($getAllReview1))
                                                {
                                                   // print_r($getAllReview1);
                                                    foreach ($getAllReview1 as $key => $qualification) 
                                                    {
                                                        
                                                        $price+= !empty($qualification->total_price)?$qualification->total_price:0;
                                                        $puntuality+= !empty($qualification->total_puntuality)?$qualification->total_puntuality:0;
                                                        $quality+= !empty($qualification->total_quality)?$qualification->total_quality:0;
                                                        $amiability+= !empty($qualification->total_amiability)?$qualification->total_amiability:0;
                                                        $service_review+= !empty($qualification->total_service)?$qualification->total_service:0;
                                                        $reviewDataqualify['amiability']=!empty($qualification->amiability)?$qualification->amiability:0;
                                                        $reviewDataqualify['review']=!empty($qualification->review)?$qualification->review:0;
                                                    }
                                                        // if(!empty($price)){
                                                        // $presentdata['qualify']
                                                        // ['total_price']=round($price/$total_rating_count, 2);
                                                        // }

                                                        // if (!empty($puntuality)) {
                                                        //     $presentdata['qualify']['total_puntuality']=round($puntuality/$total_rating_count,2);
                                                        // }

                                                        // if(!empty($quality)){
                                                        //     $presentdata['qualify']['total_quality']=round($quality/$total_rating_count, 2);
                                                        // }

                                                        // if(!empty($amiability)){
                                                        //     $presentdata['qualify']['total_amiability']=round($amiability/$total_rating_count, 2);

                                                        // }

                                                        // if(!empty($service_review)){

                                                        //     $presentdata['qualify']['service_rating']=round($service_review/$total_rating_count, 2);

                                                        // }
                                                }
                                                    $certi2=array();
                                                    $allCertificatesImages=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$dataa->id)->where('certification_type',0)->where('file_type',0)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                                    if(!empty($allCertificatesImages))
                                                    {
                                                        $path=$certifiePath.$dataa->id.'/img/';
                                                        foreach ($allCertificatesImages as $key => $value) 
                                                        {
                                                            $allImages1['id']=$value->id;
                                                            $allImages1['user_id']=$value->user_id;
                                                            $allImages1['file_name']=url($path.$value->file_name);
                                                            $allImages1['file_extension']=!empty($value->file_extension)?$value->file_extension:'';
                                                            $allImages1['status']=!empty($value->status)?$value->status:'';
                                                            $allImages1['created_at']=$value->created_at;
                                                            array_push($certi2, $allImages1);
                                                        }
                                                        $presentdata['cetifications']['images'] = $certi2;
                                                    }
                                                    else
                                                    {
                                                        $presentdata['cetifications']['images']=[];
                                                    }


                                                    $certi3=array();
                                                    $DocallCertificates=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','status','created_at')->where('user_id',$dataa->id)->where('certification_type',0)->where('file_type',1)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                                    if(!empty($DocallCertificates))
                                                    {
                                                        $path=$certifiePath.$dataa->id.'/doc/';
                                                        foreach ($DocallCertificates as $key => $val22) 
                                                        {
                                                            $allDoc['id']=$val22->id;
                                                            $allDoc['user_id']=$val22->user_id;
                                                            $allDoc['file_name']=url($path.$val22->file_name);
                                                            $allDoc['file_extension']=!empty($val22->file_extension)?$val22->file_extension:'';
                                                            $allDoc['status']=!empty($val22->status)?$val22->status:'';
                                                            $allDoc['created_at']=$val22->created_at;
                                                            array_push($certi3, $allDoc);
                                                        }
                                                        $presentdata['cetifications']['documents'] = $certi3;
                                                    }
                                                    else
                                                    {
                                                        $presentdata['cetifications']['documents']=[];
                                                    }
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
                        $resultArray['message']=trans('apimessage.invalid_request_id');
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
                    //$check_auth = $this->checkToken($access_token, $user_arr->id);
                    if(1!=1)
                    {
                    //echo json_encode($check_auth); exit;
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
                       // $check_auth = $this->checkToken($access_token,$user_arr->id);
                    if(1!=1)
                    {
                        //return json_encode($check_auth);
                    }
                    else
                    {    
                        $username = isset($user_arr->username) && !empty($user_arr->username) ? $user_arr->username:'';

                        // $confirm_code = md5(uniqid(mt_rand(0,999), true));
                        // $token = Hash::make($confirm_code);
                       
                        $token =bin2hex(random_bytes(20));
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

                            $lastId=DB::table('password_resets')->where('email', $email)->insertGetId($insertToken);
                            $getnew = DB::table('password_resets')->where('id',$lastId)->first();
                            $lastInsertedToken=$getnew->token;
                        }

                            $encryptedEmailId = Crypt::encrypt($email);
                            //Start Send Forgot password ON Mail to User
                            $objDemo = new \stdClass();
                            $objDemo->username=$username;
                            if($lang=='en')
                            {
                                $objDemo->message = 'We have received a password reset request for your account, please click on this link for password reset request.';
                            }
                            else
                            {
                                $objDemo->message = 'Hemos recibido una solicitud para restablecer la contraseña de tu cuenta, haz un clic en el boton o en el link <a href="#">aquí</a> para restablecer.';
                            }
                            
                            $objDemo->link = url('/password/resetapp/'.$lastInsertedToken);
                            $objDemo->sender = 'Buskalo';
                            $objDemo->receiver = $email;
                            $objDemo->level = '';
                            $objDemo->logo=url('img/logo/logo-svg.png');
                            $objDemo->footer_logo=url('img/logo/footer-logo.png');
                            if(isset($user_arr->avatar_location) && !empty($user_arr->avatar_location))
                            {
                                $objDemo->user_icon=url('img/user/profile/'.$user_arr->avatar_location);
                            }
                            else
                            {
                                $objDemo->user_icon=url('img/logo/logo.jpg');
                            }
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
                    $resultArray['message']=trans('apimessage.Invalid mail');
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
        

    /* ------------------------------------------------------------------------------------------------ */


    public function updateForgotPassword(Request $request)
    {    
        error_reporting(0);

        $access_token = 123456;

        $email = !empty($request->email) ? $request->email : '' ;
        $token = !empty($request->token) ? $request->token : '' ;
        $password = !empty($request->password) ? $request->password : '' ;
        $confirm_password = !empty($request->confirm_password) ? $request->confirm_password : '' ;

        
        if(isset($email) && !empty($email) && isset($token) && !empty($token) && isset($password) && !empty($password) && isset($confirm_password) && !empty($confirm_password))
        {
            $user_arr = DB::table('users')->where('email',$email)->first();
            if(!empty($user_arr))
            {
                $ChkTokn  = DB::table('password_resets')
                ->where('email',$email)
                ->where('token',$token)
                ->first();

                if($ChkTokn->token==$token)
                {

                    if($ChkTokn->email==$email)
                    {

                        if($ChkTokn->is_updated=='1')
                        {
                            $resultArray['status']='0';
                            $resultArray['message']='Please Request for another token, this token is already used.';
                            return json_encode($resultArray);
                        }
                        else
                        {
                           // $check_auth = $this->checkToken($access_token);
                            if(1!=1)
                            {
                                //return json_encode($check_auth);
                            }
                            else
                            {
                                if($password===$confirm_password) 
                                {   

                                    $username = isset($user_arr->username) && !empty($user_arr->username) ? $user_arr->username:'';

                                    $updatePass['password'] = trim(Hash::make($password));
                                    $updatePass['updated_at'] = Carbon::now()->toDateTimeString();

                                    $gd=DB::table('users')->where('id',$user_arr->id)->update($updatePass);

                                        if($gd==1 OR $gd==true)
                                        {
                                        $updateUser_arr['is_updated'] = "1";
                                        $updateUser_arr['updated_at'] = Carbon::now()->toDateTimeString();

                                        DB::table('password_resets')
                                        ->where('token',$ChkTokn->token)
                                        ->where('email',$ChkTokn->email)
                                        ->update($updateUser_arr);
                                        }

                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('apimessage.Password update successfully.');
                                    return json_encode($resultArray);

                                }
                                else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('apimessage.Do not match new password and confirm password.');
                                    return json_encode($resultArray);
                                }

                            }

                        }
                    }
                    else
                    {

                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid mail');
                    return json_encode($resultArray);

                    }

                }
                else
                {

                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid token!');
                return json_encode($resultArray);

                }

            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid mail');
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
               // 'session_key' => 'required',
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
                $resultArray['message']=trans('apimessage.Mobile Number Already Exist.');
                echo json_encode($resultArray); exit;
            }

            if(!empty($userid)) 
            {
                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                if(1!=1)
                {
                    $check_auth='';
                    // echo json_encode($check_auth); exit;
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
                            $resultArray['session_key']='';//$session_key;
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
                        $resultArray['message']=trans('El número de contacto ya existe');
                        echo json_encode($resultArray); exit;
                    }

                    if(!empty($userid)) 
                    {
                        //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                        if(1!=1)
                        {
                            $check_auth='';
                            //echo json_encode($check_auth); exit;
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
                                                    }
                                                    else
                                                    { 
                                                        $errorUpload .= 'police record image file not uploaded.'; 
                                                    } 
                                                }
                                                else
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
                                                    $targetDir = public_path() . $policePath .$userid.'/doc/';
                                                }
                                                else 
                                                {
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
                                                        }
                                                        else
                                                        { 
                                                            $errorUpload .= 'police record document file not uploaded.'; 
                                                        } 
                                                    }
                                                    else
                                                    { 
                                                        $errorUploadType .='police record document File Type Not Match';
                                                    }  
                                                }


                                                    // DB::table('user_certifications')->where('user_id', '=', $userid)->where('certification_type', '=', '1')->where('file_type', '=', '1')->delete();

                                                    // $deleteOld = $this->delete_directory(public_path() . $policePath .$userid.'/doc/');
                                            }
                                            else
                                            {
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
                                                    }
                                                    else
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
                                            }
                                            else
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
                                                $targetDir = public_path() . $certifiePath .$userid.'/doc/';
                                            }
                                            else 
                                            {
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
                                                    }
                                                    else
                                                    { 
                                                        $errorUpload .= 'certification document file not uploaded.'; 
                                                    } 
                                                }
                                                else
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
                                                    }
                                                    else
                                                    { 
                                                        $errorUpload .= 'certification document file not uploaded.'; 
                                                    } 
                                                }
                                                else
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
                                                    }
                                                    else
                                                    { 
                                                        $errorUpload .= 'Image not uploaded.'; 
                                                    } 
                                                }
                                                else
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
                                                }
                                                else 
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
                                $userData['is_confirm_reg_step'] =  1;

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
                                    $resultArray['session_key']='';//$session_key;
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
                $resultArray['message ']=trans('apimessage.provinces_list_found_successfully');
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
                $resultArray['message ']=trans('apimessage.cities_list_found_successfully');
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
                $resultArray['message ']=trans('apimessage.payment_methods_list_found_successfully');
                 $resultdata= $this->intToString($paymentsArray);
                $resultArray['data']= $resultdata;  
                //$resultArray['data']=$paymentsArray;                  
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
                //'session_key'=>'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_number' => 'required',
                'email' => 'required',
                'password' => 'required',
                'address' => 'required',
                ]);

                if($validator->fails())
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                    echo json_encode($resultArray); exit;      
                }  

                    if(!empty($userid)) 
                    {
                        //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                        if(1!=1)
                        {
                            $check_auth ='';
                            //echo json_encode($check_auth); exit;
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
                                $resultArray['message']=trans('apimessage.Email Already Exist.');
                                echo json_encode($resultArray); exit;      
                            } 

                            $workerMobileEntity = DB::table('workers')
                            ->whereRaw("(mobile_number = '".$mobile_number."')")
                            ->whereRaw("(user_id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($workerMobileEntity))
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.Mobile Number Already Exist.');
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

                                $workerEntity = DB::table('workers')->select('id', 'user_id', 'email', 'password', 'first_name', 'last_name', 'profile_pic', 'mobile_number', 'address', 'status', 'created_at', 'updated_at')
                                ->whereRaw("(id = '".$workerId."' AND deleted_at IS null )")
                                ->first();
                                
                                $path='/img/worker/profile/';
                                $workerEntity->profile_pic = url($path.$workerEntity->profile_pic);

                                $resultArray['status']='1';   
                                $resultArray['userData'] = $workerEntity;     
                                $resultArray['message']=trans('apimessage.Worker profile has been created successfully.');
                                $resultArray['session_key']='';//$session_key;
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
                        $resultArray['status']='1';
                        $resultArray['message']=trans('apimessage.Invalid session.');
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
                //'session_key' => 'required',
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

            if(!empty($user_id))
            {
                    $user_arr = DB::table('users')->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")->first();

                if(!empty($user_arr))
                {
                   // $check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);
                    if(1!=1)
                    {
                        $check_auth='';
                        //echo json_encode($check_auth); exit;
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
                            $resultArray['message']=trans('apimessage.your_folder_created_successfully');
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
                //'session_key' => 'required',
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

           
            if(!empty($user_id))
            {
                $user_arr = DB::table('users')->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")->first();

                if(!empty($user_arr))
                {
                    //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);
                    if(1!=1)
                    {
                        $check_auth='';
                        //echo json_encode($check_auth); exit;
                    }
                    else
                    {
                        $servicesRequested = DB::table('service_request')->where('service_request.id',$request_id)->where('service_request.user_id',$user_id)->first();

                        $dataalready =  DB::table('folder_projects')->where('folder_id', $folder_id )->where('requested_service_id', $request_id)->first();

                        if(!empty($dataalready))
                        {

                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.request_has_already_exist');
                            echo json_encode($resultArray); exit;
                        }

                        if($servicesRequested) 
                        {

                            $insert['folder_id'] = $folder_id;    
                            $insert['requested_service_id'] = $request_id;
                            $insert['status'] = 1;
                            $insert['created_at'] = Carbon::now();  
                            $lastId=DB::table('folder_projects')->insertGetId($insert);

                            DB::table('folder_projects')->where('folder_id', '!=',$folder_id )->where('requested_service_id', $request_id)->delete();

                                $resultArray['status']='1';
                                $resultArray['message']=trans('apimessage.your_request_moved_in_folder_successfully');
                                return json_encode($resultArray);
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.invalid_request_id');
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
                //'session_key' => 'required',
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

            if(!empty($user_id))
            {
                $user_arr = DB::table('users')->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")->first();

                if(!empty($user_arr))
                {
                     //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);
                    if(1!=1)
                    {
                        $check_auth ='';
                        //echo json_encode($check_auth); exit;
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
                            $resultdata=$this->intToString($allData);
                            $resultArray['data']=$resultdata;
                            $resultArray['message']=trans('apimessage.folder_list');
                            return json_encode($resultArray);
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.data_not_found');
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
                    ///'session_key' => 'required',
                ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

        if(!empty($userid)) 
        {

            //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
            if(1!=1)
            {
                $check_auth='';
                //echo json_encode($check_auth); exit;
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
                        $users_count_var=DB::table('users')
                        
                        ->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','users.created_at','users.updated_at','users.approval_status')->where('users.id',$userEntity->id)->first();

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
                        // $resultArray['userData'] = $users_count_var;     
                         $resultdata=$this->intToString($users_count_var);
                        $resultArray['userData'] = $resultdata;
                               
                        $resultArray['message']=trans('data found successfully.');
                        $resultArray['session_key']='';//$session_key;
                        echo json_encode($resultArray); exit;
                    }
                    //End Normal user

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
                            $userPath = '/img/user/profile/';
                        }else
                        {
                            $profilePath ='/img/company/profile/';
                            $policePath ='/img/company/police_records/';
                            $certifiePath ='/img/company/certifications/';
                            $galleryPath ='/img/company/gallery/images/';
                            $videoPath = '/img/company/gallery/videos/';
                            $userPath = '/img/user/profile/';
                        }

                        if($userEntity->user_group_id==4)
                        {
                            $users_count_var=DB::table('users')
                              ->leftjoin('reviews', 'users.id', '=', 'reviews.to_user_id')
                              ->select('users.id','users.ruc_no','users.legal_representative','users.website_address','users.year_of_constitution','users.user_group_id','users.email','users.username','users.profile_title','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','users.created_at','users.updated_at','users.total_employee', 'reviews.rating','users.pro_credit','users.approval_status')->where('users.id',$userEntity->id)->first();
                        }
                        else
                        {
                            $users_count_var=DB::table('users')
                            ->leftjoin('reviews', 'users.id', '=', 'reviews.to_user_id')
                            ->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','users.created_at','users.updated_at','users.total_employee','users.pro_credit','users.approval_status')->where('users.id',$userEntity->id)->first();
                        }
         
                        if($userEntity->user_group_id==4)
                        {
                            $packageId= DB::table('payment_history')
                                ->leftjoin('package','package.id','=','payment_history.package_id')
                                ->where('user_id',$userid)
                                ->orderBy('payment_history.id','DESC')->first();
                            $users_count_var->subscription_id=isset($packageId->package_id)?$packageId->package_id:0;
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

                             $getAllReview = DB::table('reviews')
                                 ->select(DB::raw('sum(rating) as total_rating'), DB::raw('COUNT(id) as total_rating_count'))
                                 ->groupBy('to_user_id')
                                 ->whereRaw("(reviews.to_user_id = '".$userid."' AND reviews.deleted_at IS null )")
                                ->get()->toArray();

                            
                            if(!empty($getAllReview))
                            {
                                foreach ($getAllReview as $review) 
                                {
                                     $total=!empty($review->total_rating)?$review->total_rating:0;
                                     $total_rating_count=!empty($review->total_rating_count)?$review->total_rating_count:0;

                                }
                            }

                            if(!empty($total) && !empty($total_rating_count))
                            {

                                $users_count_var->total_rating = $total_rating_count;
                                $users_count_var->global_avg_rating = $total/$total_rating_count;
                            }
                        
                            $getAllReview1 = DB::table('users')
                             ->leftjoin('reviews', 'users.id', '=', 'reviews.user_id')
                             ->select('users.id','users.user_group_id','users.avatar_location','users.username', 'reviews.rating', 'reviews.review')
                             ->whereRaw("(reviews.to_user_id = '".$userid."')")
                             ->where('user_group_id', 2)
                             ->limit(3)
                             ->orderBy('reviews.id', 'desc')
                             ->get()->toArray();

                               $options=array();
                                $reviewData = array();
                                $reviewData2 = array();
                               
                                foreach ($getAllReview1 as $key => $qualification) 
                                {                      
                                    $user_id=$qualification->id;
                                    $reviewData['user_id'] = $qualification->id;
                                    $reviewData['user_group_id']=$qualification->user_group_id;
                                    if(!empty( $qualification->username))
                                    {
                                        $reviewData['username']=$qualification->username;
                                    }
                                    if($qualification->user_group_id==2)
                                    {
                                        $userprofilePath ='/img/user/profile/';
                                           
                                    }
                                    if(!empty($profilePath.$qualification->avatar_location)) 
                                    {
                                            
                                        $reviewData['profile']= isset($qualification->avatar_location) && !empty($qualification->avatar_location) ? url($userprofilePath.$qualification->avatar_location) : '';
                                    } 
                                    if(!empty($qualification->review)){
                                       $reviewData['review']=$qualification->review;  
                                    }

                                    if(!empty($qualification->rating))
                                    {
                                        $reviewData['rating']=$qualification->rating;
                                    }
                                       
                                        $reviewData2[] = $reviewData;
                                }
                                   
                                if(!empty($reviewData2))
                                {
                                    $data1['sub_services']=$options;
                                    $users_count_var->review_list = $reviewData2;
                                }
                        }
                        else if($userEntity->user_group_id==3)
                        {
                           $packageId= DB::table('payment_history')
                                ->leftjoin('package','package.id','=','payment_history.package_id')
                                ->where('user_id',$userid)
                                ->orderBy('payment_history.id','DESC')->first();
                            $users_count_var->subscription_id=isset($packageId->package_id)?$packageId->package_id:0;

                             $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';
                             $users_count_var->pro_credit = isset($users_count_var->pro_credit) && !empty($users_count_var->pro_credit) ? $users_count_var->pro_credit :0;

                              if(!empty($users_count_var->username) && !empty($users_count_var->identity_no) && !empty($users_count_var->mobile_number))
                                {
                                    $users_count_var->is_profile_complete = true;
                                }else
                                {
                                     $users_count_var->is_profile_complete = false;
                                }

                                 $getAllReview = DB::table('reviews')
                                 ->select(DB::raw('sum(rating) as total'),  DB::raw('COUNT(id) as total_rating_count'))
                                 ->groupBy('to_user_id')
                                 ->whereRaw("(reviews.to_user_id = '".$userid."' AND reviews.deleted_at IS null )")
                                ->get()->toArray();


                                if(!empty($getAllReview))
                                {
                                    foreach ($getAllReview as $review) 
                                    {
                                       //$presentdata1['rating']= $review->rating;
                                        
                                         $total=!empty($review->total)?$review->total:0;
                                         $total_rating_count=!empty($review->total_rating_count)?$review->total_rating_count:0;

                                       //$alldata[]=$total;

                                    }

                                }

                            if(!empty($total) && !empty($total_rating_count)){

                                $users_count_var->total_rating = $total_rating_count;
                                $users_count_var->global_avg_rating = $total/$total_rating_count;

                            }


                             $getAllReview1 = DB::table('users')
                             ->leftjoin('reviews', 'users.id', '=', 'reviews.user_id')
                             ->select('users.id','users.user_group_id','users.avatar_location','users.username', 'reviews.rating', 'reviews.review')
                             ->whereRaw("(reviews.to_user_id = '".$userid."')")
                             ->where('user_group_id', 2)
                             ->limit(3)
                             ->orderBy('reviews.id', 'desc')
                             ->get()->toArray();

                                // echo $getAllReview1->to_user_id; 
                               $options=array();
                               //echo "<pre>"; print_r($getAllReview1); die;
                                $reviewData = array();
                                $reviewData2 = array();
                               
                                    foreach ($getAllReview1 as $key => $qualification) 
                                    {

                                          
                                        $user_id=$qualification->id;
                                        $reviewData['user_id'] = $qualification->id;
                                        $reviewData['user_group_id']=$qualification->user_group_id;
                                        if(!empty( $qualification->username)){
                                             $reviewData['username']=$qualification->username;
                                        }
                                       
                                        
                                        if($qualification->user_group_id==2)
                                        {
                                            $userprofilePath ='/img/user/profile/';
                                           
                                        }
                                        if(!empty($profilePath.$qualification->avatar_location)) 
                                        {
                                            
                                          $reviewData['profile']= isset($qualification->avatar_location) && !empty($qualification->avatar_location) ? url($userprofilePath.$qualification->avatar_location) : '';

                                        } 
                                        // else {
                                        //    $reviewData['profile']= '';
                                        // } 

                                        if(!empty($qualification->review)){
                                           $reviewData['review']=$qualification->review;  
                                        }

                                       if(!empty($qualification->rating)){
                                         $reviewData['rating']=$qualification->rating;
                                       }
                                       
                                        $reviewData2[] = $reviewData;
                                    }
                                   
                               //print_r($reviewData2);die;

                                if(!empty($reviewData2)){
                                $data1['sub_services']=$options;
                                    //array_push($allData, $reviewData2);
                                $users_count_var->review_list = $reviewData2;
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

                                        $allCertificatesImages=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','is_verified','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',0)->where('file_type',0)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                       
                                         if(!empty($allCertificatesImages))
                                         {
                                            $path=$certifiePath.$userEntity->id.'/img/';
                                            foreach ($allCertificatesImages as $key => $value) 
                                            {
                                                $allImages1['id']=$value->id;
                                                $allImages1['user_id']=$value->user_id;
                                                $allImages1['file_name']=url($path.$value->file_name);
                                                $allImages1['file_extension']=!empty($value->file_extension)?$value->file_extension:'';
                                                $allImages1['status']=!empty($value->status)?$value->status:0;
                                                $allImages1['is_verified']=!empty($value->is_verified)?$value->is_verified:0;
                                                $allImages1['created_at']=$value->created_at;
                                                array_push($certi2, $allImages1);
                                            }
                                            $users_count_var->cetifications['images'] = $certi2;
                                         }
                                         else
                                         {
                                            $users_count_var->cetifications['images']=[];
                                         }
                                          //print_r($allCertificatesImages); die;


                                          $DocallCertificates=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','is_verified','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',0)->where('file_type',1)->whereRaw("(deleted_at IS null )")->get()->toArray();
                                        
                                         if(!empty($DocallCertificates))
                                         {
                                            $path=$certifiePath.$userEntity->id.'/doc/';
                                            foreach ($DocallCertificates as $key => $val22) 
                                            {
                                                $allDoc['id']=$val22->id;
                                                $allDoc['user_id']=$val22->user_id;
                                                $allDoc['file_name']=url($path.$val22->file_name);
                                                $allDoc['file_extension']=!empty($val22->file_extension)?$val22->file_extension:'';
                                                $allDoc['status']=!empty($val22->status)?$val22->status:'';
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
                                         $allPoliceRecImage=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','is_verified','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',1)->where('file_type',0)->whereRaw("(deleted_at IS null )")->get()->toArray();

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
                                                $allVideo1['is_verified']=$value->is_verified;
                                                $allVideo1['created_at']=$value->created_at;
                                                array_push($policeR2, $allVideo1);
                                            }
                                           $users_count_var->police_records['images'] = $policeR2;
                                           $users_count_var->police_records_verified['verifiedI'] = $value->is_verified;
                                         }
                                         else
                                         {
                                            $users_count_var->police_records['images']=[];
                                            $users_count_var->police_records_verified['verifiedI'] = 2;
                                         }

                                         $allPoliceRecDoc=DB::table('user_certifications')->select('id','user_id','file_name','certification_type','file_type','file_extension','is_verified','status','created_at')->where('user_id',$userEntity->id)->where('certification_type',1)->where('file_type',1)->whereRaw("(deleted_at IS null )")->get()->toArray();

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
                                                $all2['is_verified']=$valll->is_verified;
                                                $all2['created_at']=$valll->created_at;
                                                array_push($policeR3, $all2);
                                            }
                                           $users_count_var->police_records['documents'] = $policeR3;
                                           $users_count_var->police_records_verified['verifiedD'] = $value->is_verified;
                                         }
                                         else
                                         {
                                            $users_count_var->police_records['documents']=[];
                                            $users_count_var->police_records_verified['verifiedD'] = 2;
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
                                        // $resultArray['userData'] = $users_count_var;     
                                         $resultdata=$this->intToString($users_count_var);
                                        $resultArray['userData'] = $resultdata;        
                                        $resultArray['message']=trans('apimessage.data_found_successfully');
                                        $resultArray['session_key']='';//$session_key;
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

    /* GET NEW OPPORTUNITIES API START */

    public function getNewOpportunities(Request $request)
    {
        $allData=array();
        $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang);

        $olddate=date('Y-m-d H:i:s', strtotime('-8 days'));
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);
        
        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

        if(!empty($userid)) 
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
                    ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
                    ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
                    ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
                    ->leftjoin('cities', 'cities.id', '=', 'service_request.city_id')
                    ->select('assign_service_request.id','assign_service_request.request_not_now','assign_service_request.is_read','assign_service_request.service_request_id','assign_service_request.user_id','assign_service_request.request_status','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image', 'assign_service_request.assign_date', 'assign_service_request.credit','child_sub_services.en_name as childen','child_sub_services.es_name as childes','sub_services.en_name as suben','sub_services.es_name as subes','category.en_name as caten','category.es_name as cates','.assign_service_request.is_read','cities.name','assign_service_request.updated_at')
                    ->whereRaw("(assign_service_request.user_id = '".$userid."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status IS null AND service_request.deleted_at IS null)")
                    ->where('service_request.status','0')
                    ->where('assign_service_request.created_at', '>',$olddate)
                    ->orderBy('assign_service_request.created_at','DESC')
                    ->groupBy('assign_service_request.service_request_id')->get();

                    if(!empty($allOpprtunities))
                    {
                        $data1=array();
                        foreach ($allOpprtunities as $key => $vall) 
                        {

                            // $maxcount=DB::table('assign_service_request')->where('service_request_id',$vall->service_request_id)->where('request_status','buy')->count();
                            $hirereqst=DB::table('assign_service_request')->where('service_request_id',$vall->service_request_id)->where('request_status','buy')->where('hire_status',1)->where('user_id',$vall->user_id)->first();
                            
                            if(empty($hirereqst))
                            {
                                // if($maxcount<3)
                                // {
                                    $data1['id'] = $vall->id;
                                    $data1['user_id'] = $vall->user_id;
                                    $data1['is_read'] = $vall->is_read;
                                    $data1['request_not_now'] = $vall->request_not_now;
                                    $data1['service_request_id'] = $vall->service_request_id;
                                    $data1['request_status'] = isset($vall->request_status) && !empty($vall->request_status) ? $vall->request_status : '';
                                    $data1['service_id'] = $vall->service_id;
                                    if($lang=='es')
                                    {
                                        $catname=$vall->cates;
                                        $service_name=$vall->es_name;
                                        $sub_service_name=isset($vall->subes)?$vall->subes:'';
                                        $child_service_name=isset($vall->childes)?$vall->childes:'';
                                    }
                                    else
                                    {   
                                        $catname=$vall->caten;
                                        $service_name=$vall->en_name;
                                        $sub_service_name=isset($vall->suben)?$vall->suben:'';
                                        $child_service_name=isset($vall->childen)?$vall->childen:'';
                                    }
                                    $data1['city_name'] = isset($vall->name)?$vall->name:'';
                                    $data1['category_name'] = $catname;
                                    $data1['service_name'] = $service_name;
                                    $data1['sub_service_name'] = $sub_service_name;
                                    $data1['child_service_name'] = $child_service_name;
                                    $data1['service_image'] = url('/img/'.$vall->image);
                                    $data1['location'] = $vall->location;
                                    $data1['username'] = $vall->username;
                                    $data1['approval_status'] = $userEntity->approval_status;
                                    $data1['status'] = $vall->status;
                                    $data1['email_verify'] = $vall->email_verify;
                                    $data1['accept_date'] = date('d-m-Y', strtotime($vall->assign_date));
                                    $data1['accept_time'] = date('H:i', strtotime($vall->assign_date));
                                    $data1['credit'] = $vall->credit;
                                    $data1['created_at'] = $vall->created_at;
                                    $data1['updated_at'] = isset($vall->updated_at)?$vall->updated_at:'';
                                    $data1['is_read'] =$vall->is_read;

                                    $servicesRequestedQues = DB::table('service_request_questions')
                                    ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                                    ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                                    ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','service_request_questions.date_time','service_request_questions.fileName','service_request_questions.quantity','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option','questions.question_type')
                                    ->whereRaw("(service_request_questions.service_request_id = '".$vall->service_request_id."')")
                                    ->get()->toArray();
                                
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

                                        $data2['option_id'] = isset($que->option_id)?$que->option_id:'';
                                        $data2['question_type'] = $que->question_type;
                                        $data2['date_time'] = isset($que->date_time)?$que->date_time:'';
                                        $data2['file_name'] = isset($que->fileName)? url('img/services/'.$que->fileName):'';
                                        $data2['quantity'] = isset($que->quantity)?$que->quantity:'';

                                        if($lang=='es')
                                            {$option=$que->es_option;}
                                        else{$option=$que->en_option;}

                                        $data2['option'] = $option;

                                        array_push($options, $data2);
                                    }

                                    $data1['question_options']=$options ;
                                    array_push($allData, $data1);
                                // }
                            }
                        }

                        if(!empty($data1))
                        {
                            $resultArray['status']='1';   
                            $resultArray['message']=trans('Lista de oportunidades encontrada correctamente.!');
                            $resultdata= $this->intToString($allData);
                            $resultArray['data'] =$resultdata; 
                            echo json_encode($resultArray); exit;   
                        }
                        else
                        {
                            $resultArray['status']='0';   
                            $resultArray['message']=trans('Lista de oportunidades no encontrada.!');
                            echo json_encode($resultArray); exit;   
                        }
                    
                    }
                    else
                    {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('Oportunidades no encontradas.');
                    echo json_encode($resultArray); exit;
                    }

                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('Actualice su perfil para los servicios que ofrece.');
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
        else 
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameter.');
            echo json_encode($resultArray); exit;
        }

    }

    /* GET NEW OPPORTUNITIES API END */

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
                //$check_auth = $this->checkToken($access_token, $users_count_var->id);

                if(1!=1)
                {
                    //echo json_encode($check_auth); exit;
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
                        $resultArray['message']=__('cerrar sesión con éxito.');
                        echo json_encode($resultArray); exit;
                    }

                }               
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=__('Email inválido.');
                echo json_encode($resultArray); exit;
            }
        }
        else
        {
            $resultArray['status']='0';
            $resultArray['message']=__('parámetros inválidos.');
            echo json_encode($resultArray); exit;
        }
    }

        /* --------------------signOut Api END-------------------- */



    /* --------------------Opportunity Buy Api Start-------------------- */

    public function buyOpportunity(Request $request)
    {
        $access_token=123456;
        $allData=array();
        $userid = !empty($request->userid) ? $request->userid : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $user_group_id = !empty($request->user_group_id) ? $request->user_group_id : '' ;
        $opportunity_id = !empty($request->opportunity_id) ? $request->opportunity_id : '' ;
        $service_request = !empty($request->service_request) ? $request->service_request : '' ;
        $hire_amount = !empty($request->hire_amount) ? $request->hire_amount : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang);

        $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'user_group_id' => 'required',
            'opportunity_id' => 'required',
            'service_request' => 'required',
            'hire_amount' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

        if(!empty($userid) && !empty($opportunity_id)) 
        {
            $userEntity = DB::table('users')
            ->whereRaw("(active=1)")
            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
            ->first();
            
            if(!empty($userEntity))
            {
                $opportunity = DB::table('assign_service_request')
                ->whereRaw("(id = '".$opportunity_id."' AND deleted_at IS null )")
                ->first();

                if($opportunity)
                {
                    if($opportunity->hire_status!='0')
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('Requested opportunity is already Assigned another professionals.');
                        echo json_encode($resultArray); exit;
                    }
                    else
                    {
                        $chkUserRecivedOpprtOrNot = DB::table('assign_service_request')
                        ->whereRaw("(id = '".$opportunity_id."' AND deleted_at IS null AND user_id = '".$userid."')")
                        ->first();

                        if(!empty($chkUserRecivedOpprtOrNot))
                        {  

                            if($chkUserRecivedOpprtOrNot->request_status=='buy')
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.opportunity_already_accepted');
                                echo json_encode($resultArray); exit;
                            }
                            else
                            {
                                $maxcount=DB::table('assign_service_request')->where('service_request_id',$chkUserRecivedOpprtOrNot->service_request_id)->where('request_status','buy')->count();

                                if($maxcount < 3)
                                {
                                    $leftcredit = ($userEntity->pro_credit - $request->hire_amount);

                                    if ($leftcredit >= 0) 
                                    {
                                        // Reduce user credit
                                        $updateArrPro['pro_credit'] = $leftcredit;
                                        DB::table('users')->where('id', $userid)->update($updateArrPro);
                                    
                                        // Add admin credit
                                        $admincrdit= DB::table('users')
                                        ->where('id',1)->first();

                                        DB::table('users')
                                        ->where('id',1)
                                        ->update(['pro_credit'=>$admincrdit->pro_credit+$request->hire_amount]);
                                    } 
                                    else 
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('apimessage.hire_amount');
                                        echo json_encode($resultArray); exit;
                                    }

                                    $update_Arr['request_status'] ='buy'; 
                                    $update_Arr['amount'] = $hire_amount;
                                    $update_Arr['assign_date'] = Carbon::now();
                                    $update_Arr['updated_at'] = Carbon::now();  
                                    
                                    DB::table('assign_service_request')
                                    ->where('id',$opportunity_id)
                                    ->where('user_id',$userid)
                                    ->update($update_Arr);

                                    $serviceId=DB::table('service_request')->where('id',$chkUserRecivedOpprtOrNot->service_request_id)->first();
                                    if(!empty($serviceId))
                                    {   
                                        $userToken = DB::table('users')
                                        ->leftjoin('user_devices','user_devices.user_id','=','users.id')
                                        ->where('users.id',$serviceId->user_id)
                                        ->select('user_devices.*', 'users.*')
                                        ->first();
                                        $email=$userToken->email;
                                        $userToken->logo= url('img/logo/logo-svg.png');
                                        $userToken->footer_logo=url('img/logo/footer-logo.png');
                                        if(isset($userEntity->avatar_location) && !empty($userEntity->avatar_location))
                                        {
                                            if($userEntity->user_group_id==3)
                                            {
                                                $userToken->user_icon=url('img/contractor/profile/'.$userEntity->avatar_location);
                                            }
                                            if($userEntity->user_group_id==4)
                                            {
                                                $userToken->user_icon=url('img/company/profile/'.$userEntity->avatar_location);
                                            }
                                            
                                        }
                                        else
                                        {
                                            $userToken->user_icon=url('img/logo/logo.jpg');
                                        }
                                        
                                        if($lang=='en')
                                        {
                                            $userToken->message='Your service has been buyed by';
                                            $userEntity->message='You are successfully bought this service request';
                                        }
                                        else
                                        {
                                            $userToken->message='Este profesional cumple con tus requisitos y está dispuesto a ayudarte con lo que necesitas';//'Tu servicio ha sido comprado por';
                                            $userEntity->message='Aquí está la información de una persona que puede llegar a ser tu mejor cliente';
                                        }
                                        //Pro buy mail send

                                        $usermail=$userEntity->email;
                                        $userEntity->clientName=$userToken->username;
                                        $userEntity->addressdata=$userToken->address;
                                        $userEntity->mobile_numberdata=$userToken->mobile_number;
                                        $userEntity->useremail=$userToken->email;
                                        $userEntity->logo= url('img/logo/logo-svg.png');
                                        $userEntity->footer_logo=url('img/logo/footer-logo.png');
                                        if($userEntity->user_group_id==3)
                                        {
                                            $userEntity->link=url('dashboard');
                                        }else
                                        {
                                            $userEntity->link=url('dashboard'); 
                                        }
                                        
                                        if(isset($userToken->avatar_location) && !empty($userToken->avatar_location))
                                        {
                                            $userEntity->user_icon=url('img/user/profile/'.$userToken->avatar_location);
                                        }
                                        else
                                        {
                                            $userEntity->user_icon=url('img/logo/logo.jpg');
                                        }

                                        Mail::send('frontend.mail.pro_service_buy', ['userToken'=>$userEntity], function($message) use($usermail) {
                                        $message->to($usermail)->subject('Un nuevo cliente esperando por tí ');
                                        $message->from(env('MAIL_FROM'));
                                            });

                                        //Pro mail end

                                        //user Mail send
                                        $userToken->clientName=$userEntity->username;
                                        $userToken->addressdata=$userEntity->address;
                                        $userToken->mobile_numberdata=$userEntity->mobile_number;
                                        $userToken->link=url('/company_profile/my-profile');
                                            Mail::send('frontend.mail.service_buy', ['userToken'=>$userToken], function($message) use($email) {
                                            $message->to($email)->subject
                                                    ('Tenemos a alguien que quiere ayudarte ');
                                            $message->from(env('MAIL_FROM'));
                                            });
                                            //End mail
                                            $device_id=$userToken->device_id;
                                            $device_type=$userToken->device_type;
                                            //$title='Service buy';
                                            $title='¡Tenemos a un profesional dispuesto a ayudarte!';
                                            $message='Ingresa a la App y en la sección de PROYECTOS revisa su perfil completo y le puedes contactar directamente.';
                                            $userid= 0;
                                            $prouserId=0; 
                                            $serviceId=$service_request;
                                            $notify_type='service_buy';
                                            $senderId=0;
                                            $reciverId=0;
                                            $chatType=0;
                                            $senderName=$userEntity->username;
                                            
                                            $this->postpushnotification($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                                            
                                        // USER MAIL END
                                    }else
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Service Id not found.');
                                        echo json_encode($resultArray); exit;

                                    }
 
                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('apimessage.opportunity_success');
                                    echo json_encode($resultArray); exit;
                                }
                                else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('apimessage.This Opportunity Already Taken By Another Three professionals OR Company');
                                    echo json_encode($resultArray); exit; 
                                }
                            }   
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans("You don't have this opportunity.Please update your profile offerd services to get new Opportunities.");
                            echo json_encode($resultArray); exit;  
                        }
                    }
                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.opportunity_id_not_found');
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
                //'session_key' => 'required',
                'user_group_id' => 'required',
                'opportunity_id' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 


                if(!empty($userid) && !empty($opportunity_id))
                {

                    //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                    if(1!=1)
                    {
                        //echo json_encode($check_auth); exit;
                    }
                    else
                    {
                         $userEntity = DB::table('users')
                        ->whereRaw("(active=1)")
                        ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                        ->first();

                        if(!empty($userEntity))
                        {

                            $opportunity = DB::table('assign_service_request')
                            ->whereRaw("(id = '".$opportunity_id."' AND deleted_at IS null )")
                            ->first();

                            if($opportunity)
                            {

                                if($opportunity->job_status==3)
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
                                         $resultArray['message']=trans('Opportunity Already Accepted. Now you can not Ignore.!');
                                          echo json_encode($resultArray); exit;

                                        }
                                        else if($chkUserRecivedOpprtOrNot->request_status=='ignore')
                                        {
                                         $resultArray['status']='0';
                                         $resultArray['message']=trans('apimessage.opportunity_already_ignore');
                                          echo json_encode($resultArray); exit;
                                        }
                                        else
                                        {
                                            $update_Arr['request_status'] = NULL; 
                                            $update_Arr['job_status'] =2;
                                            $update_Arr['rejected_by'] ='pro';   
                                            $update_Arr['request_not_now'] =1;   
                                            $update_Arr['updated_at'] = Carbon::now();  
                                            
                                          DB::table('assign_service_request')
                                            ->whereRaw("(id = '".$opportunity_id."' AND user_id = '".$userid."')")->update($update_Arr);

                                          $resultArray['status']='1';
                                            $resultArray['message']=trans('apimessage.opportunity_success_ignore');
                                          echo json_encode($resultArray); exit;
                                        }
                                    }
                                    else
                                    {
                                            $resultArray['status']='0';
                                            $resultArray['message']=trans("You don't have this opportunity.Please update your profile offerd services to get new Opportunities.");
                                            echo json_encode($resultArray); exit;  
                                    }

                                }
                            }else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.opportunity_id_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid)) 
            {
               // $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );

                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                    $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")
                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                    ->first();

                    if(!empty($userEntity))
                    {
                        $servicesbuy = DB::table('assign_service_request')->orderBy('assign_service_request.updated_at','desc')
                            ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                            ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                            ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
                            ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
                            ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
                            ->leftjoin('cities', 'cities.id', '=', 'service_request.city_id')
                            ->select('service_request.id','service_request.status','service_request.assigned_user_id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','service_request.email_verify', 'assign_service_request.hire_status', 'service_request.created_at','services.en_name', 'services.es_name', 'category.en_name as en_catname', 'category.es_name as es_catname', 'sub_services.en_name as en_sub_servicesname', 'sub_services.es_name as es_sub_servicesname', 'child_sub_services.en_name as en_child_sub_servicessname', 'child_sub_services.es_name as es_child_sub_servicessname','services.image','assign_service_request.tranx_status','assign_service_request.tranx_id', 'assign_service_request.updated_at as accepted_date','assign_service_request.job_status','cities.name','assign_service_request.rejected_by')

                            ->where('assign_service_request.user_id',$userid)
                            //->where('assign_service_request.tranx_status','1')
                           //->where('assign_service_request.request_status','buy')
                             ->whereRaw("(assign_service_request.request_status='buy' OR assign_service_request.request_status='ignore')")
                            ->whereRaw("(service_request.deleted_at IS null )")->get();

                            if(!empty($servicesbuy))
                            {

                                $data1=array();
                                foreach ($servicesbuy as $key => $vall) 
                                {
                                    $serviceaccept= DB::table('assign_service_request')->where('service_request_id',$vall->id)->where('request_status','buy')->count();
                                    $serviceaccept= DB::table('assign_service_request')->where('service_request_id',$vall->id)->where('request_status','buy')->count();
                                    if(($serviceaccept==3) || ($vall->job_status==5))
                                    {
                                        $data1['show_status'] ='false';
                                    }
                                    if(($serviceaccept<3) || ($vall->job_status!=5))
                                    {
                                         $data1['show_status'] ='true';
                                    }
                                  
                                    $data1['id'] = $vall->id;
                                    $data1['service_id'] = $vall->service_id;
                                     if($lang=='es')
                                            {$service_name=$vall->es_name;}
                                        else{$service_name=$vall->en_name;}

                                    if($lang=='es')
                                    {
                                        $category_name=$vall->es_catname;
                                    }
                                    else
                                    {
                                        $category_name=$vall->en_catname;
                                    }

                                    if($lang=='es')
                                    {$sub_servicesname=!empty($vall->es_sub_servicesname)?$vall->es_sub_servicesname:'';}
                                        else{$sub_servicesname=!empty($vall->en_sub_servicesname)?$vall->en_sub_servicesname:'';}
                                        
                                    if($lang=='es')
                                            {!empty($child_sub_services=$vall->es_child_sub_servicessname)?$child_sub_services=$vall->es_child_sub_servicessname:'';}
                                        else{!empty($child_sub_services=$vall->en_child_sub_servicessname)?$child_sub_services=$vall->en_child_sub_servicessname:'';}

                                    if($vall->job_status == 1){
                                        //new
                                        $userSideAccept='1';

                                    }elseif($vall->job_status == 2){
                                        //pending
                                        $userSideAccept='2';

                                    }
                                    elseif($vall->job_status == 3){
                                        //accepted
                                        $userSideAccept='3';

                                    }elseif($vall->job_status == 4){
                                        //rejected or not accepted
                                        $userSideAccept='4';

                                    }elseif($vall->job_status == 5){
                                        //service performed
                                        $userSideAccept='5';
                                    }
                                    else{

                                        $userSideAccept='notaccepted';
                                    }

                                    $data1['city_name'] = isset($vall->name)?$vall->name:'';
                                    $data1['rejected_by'] = isset($vall->rejected_by)?$vall->rejected_by:'';
                                    $data1['category_name'] = $category_name;
                                    $data1['service_name'] = $service_name;
                                    $data1['sub_servicesname'] = $sub_servicesname;
                                    $data1['child_sub_services'] = $child_sub_services;
                                    $data1['service_image'] = url('/img/'.$vall->image);
                                    $data1['service_image'] = url('/img/'.$vall->image);
                                    $data1['location'] = $vall->location;
                                    $data1['username'] = $vall->username;
                                    $data1['request_status'] = $vall->request_status;
                                    $data1['job_status'] = $userSideAccept;
                                    $data1['hire_status'] = $vall->hire_status;
                                    $data1['email_verify'] = $vall->email_verify;
                                    $data1['tranx_id'] = $vall->tranx_id;
                                    $data1['tranx_status'] = $vall->tranx_status;
                                    $data1['assign_date'] = date('d-m-Y', strtotime($vall->created_at));
                                    $data1['assign_time'] = date('H:i', strtotime($vall->created_at));
                                    if(isset($vall->accepted_date) && !empty($vall->accepted_date))
                                    {
                                       $data1['accepted_date'] = date('d-m-Y', strtotime($vall->accepted_date));
                                        $data1['accepted_time'] = date('H:i', strtotime($vall->accepted_date)); 
                                    }
                                    else
                                    {
                                        $data1['accepted_date']='';
                                        $data1['accepted_time']='';
                                    }
                                    
                                    $data1['created_at'] =$vall->created_at;


                                    //print_r($updateStatus); die;

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
                                $resultArray['message']=trans('apimessage.job_detail_found_successfully');
                                $resultdata= $this->intToString($allData);
                                $resultArray['data'] = $resultdata; 
                                echo json_encode($resultArray); exit;
                            }

                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.job_list_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($job_id)) 
            {

                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang );
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                        ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
                        ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
                        ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
                        ->leftjoin('users', 'service_request.user_id', '=', 'users.id')
                        ->leftjoin('cities', 'cities.id', '=', 'service_request.city_id')
                        ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','assign_service_request.job_status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'category.en_name as en_catname', 'category.es_name as es_catname', 'sub_services.en_name as en_sub_servicesname', 'sub_services.es_name as es_sub_servicesname', 'child_sub_services.en_name as en_child_sub_servicessname', 'child_sub_services.es_name as es_child_sub_servicessname','services.image','assign_service_request.tranx_status','assign_service_request.tranx_id','assign_service_request.updated_at as accepted_date','service_request.mobile_number','service_request.email','service_request.user_id','service_request.latitude','service_request.longitude', 'users.landline_number', 'users.office_number','cities.name','users.avatar_location')
                        ->where('service_request.id',$job_id)
                        ->where('assign_service_request.user_id',$userid)
                        //->where('assign_service_request.tranx_status','1')
                        //->where('assign_service_request.request_status','buy')
                        ->whereRaw("(assign_service_request.request_status='buy' OR assign_service_request.request_status='ignore')")
                        ->whereRaw("(service_request.deleted_at IS null )")->get()->toArray();


                          $data1=array();
                          if(!empty($servicesbuy))
                           {
                            foreach ($servicesbuy as $key => $vall) 
                            {

                                $data1['id'] = $vall->id;
                                $data1['service_id'] = $vall->service_id;
                                if($lang=='es')
                                {
                                    $service_name=isset($vall->es_name)?$vall->es_name:$vall->en_name;
                                }
                                else
                                {
                                    $service_name=$vall->en_name;
                                }
                                if($lang=='es')
                                {
                                    $category_name=isset($vall->es_catname)?$vall->es_catname:$vall->en_catname;
                                }
                                else
                                {
                                    $category_name=$vall->en_catname;
                                }
                                if($lang=='es')
                                {
                                    $sub_servicesname=!empty($vall->es_sub_servicesname)?$vall->es_sub_servicesname:$vall->en_sub_servicesname;
                                }
                                else
                                {
                                    $sub_servicesname=!empty($vall->en_sub_servicesname)?$vall->en_sub_servicesname:'';
                                }
                                if($lang=='es')
                                {
                                    !empty($child_sub_services=$vall->es_child_sub_servicessname)?$child_sub_services=$vall->es_child_sub_servicessname:$vall->en_child_sub_servicessname;
                                }
                                else
                                {
                                    !empty($child_sub_services=$vall->en_child_sub_servicessname)?$child_sub_services=$vall->en_child_sub_servicessname:'';
                                }
                                if($vall->job_status==5)
                                {
                                    $ratingData= DB::table('reviews')->where('request_id',$job_id)->where('to_user_id',$userid)->first();
                                    $data1['rate_count'] = isset($ratingData->rating)?$ratingData->rating:''; 
                                    $data1['is_rate_status'] = isset($ratingData->is_rate_status)?$ratingData->is_rate_status:'false';
                                }
                                $data1['job_status'] = $vall->job_status; 
                                $data1['city_name'] = isset($vall->name)?$vall->name:''; 
                                $data1['category_name'] = $category_name;
                                $data1['service_name'] = $service_name;
                                $data1['sub_servicesname'] = $sub_servicesname;
                                $data1['child_sub_services'] = $child_sub_services;
                                $data1['service_image'] = url('/img/'.$vall->image);
                               
                                $data1['location'] = $vall->location;
                                $data1['latitude'] = $vall->latitude;
                                $data1['longitude'] = $vall->longitude;
                                $data1['client_id'] = $vall->user_id;
                                $data1['username'] = $vall->username;
                                $data1['mobile_number'] = $vall->mobile_number;
                                $data1['landline_number'] = $vall->landline_number;
                                $data1['office_number'] = $vall->office_number;
                                $data1['email'] = $vall->email;
                                // if($userEntity->user_group_id==3)
                                // {
                                if(file_exists(public_path('img/user/profile/'.$vall->avatar_location)) && !empty($vall->avatar_location))
                                {
                                    $data1['image'] = url('img/user/profile/'.$vall->avatar_location); 
                                }

                                    
                                // }
                                // if($userEntity->user_group_id==4)
                                // {
                                //     $data1['image'] = url('img/contractor/profile/'.$vall->avatar_location);
                                // }
                                
                                $data1['request_status'] = $vall->request_status;
                                if(isset($vall->accepted_date) && !empty($vall->accepted_date))
                                {
                                   $data1['accepted_date'] = date('d-m-Y', strtotime($vall->accepted_date));
                                    $data1['accepted_time'] = date('H:i', strtotime($vall->accepted_date)); 
                                }
                                else
                                {
                                    $data1['accepted_date']='';
                                    $data1['accepted_time']='';
                                }
                                
                                $data1['assign_date'] = date('d-m-Y', strtotime($vall->created_at));
                                $data1['assign_time'] = date('H:i', strtotime($vall->created_at));
                                //$data1['accepted_date'] = date('d-m-Y', strtotime($vall->accepted_date));
                                //$data1['accepted_time'] = date('H:i', strtotime($vall->accepted_date));
                                $data1['created_at'] = $vall->created_at;
                              
                                $servicesRequestedQues = DB::table('service_request_questions')
                                ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                                 ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                                ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','questions.question_type','question_options.en_option','question_options.es_option','service_request_questions.date_time','service_request_questions.fileName','service_request_questions.quantity')
                                ->whereRaw("(service_request_questions.service_request_id = '".$vall->id."')")->get()->toArray(); 

                                $options=array();

                                foreach ($servicesRequestedQues as $key => $que) 
                                {
                                   $data2['id'] = $que->id;
                                   $data2['service_request_id'] = $que->service_request_id;
                                   $data2['question_id'] = $que->question_id;
                                   $data2['question_type'] = $que->question_type;

                                    if($lang=='es')
                                        {$question=$que->es_title;}
                                    else{$question=$que->en_title;}

                                    $data2['question'] = $question;

                                    $data2['option_id'] = $que->option_id;
                                    $data2['date_time'] = isset($que->date_time)?$que->date_time:'';
                                    $data2['file_name'] = isset($que->fileName)? url('img/services/'.$que->fileName):'';
                                    $data2['quantity'] = isset($que->quantity)?$que->quantity:'';

                                    if($lang=='es')
                                        {$option=$que->es_option;}
                                    else{$option=$que->en_option;}

                                   $data2['option'] = $option;

                                    array_push($options, $data2);
                                }


                                 // GET ALL CONTRACTOR & COMPANY , RECIVED NOTIFICATION OF THIS REQUEST

                                $getAllAssignedCoAndCom = DB::table('assign_service_request')
                                ->leftjoin('users', 'assign_service_request.user_id', '=', 'users.id')
                                ->select('users.id','users.username','users.email','users.mobile_number','users.landline_number','users.office_number','assign_service_request.request_status','users.avatar_location')
                                 ->whereRaw("(assign_service_request.service_request_id = '".$job_id."')")->get()->toArray(); 

                                $secOptions=array();

                                foreach ($getAllAssignedCoAndCom as $key => $datas) 
                                {
                                   $assData['id'] = $datas->id;
                                   $assData['username'] = $datas->username;
                                   $assData['email'] = $datas->email;
                                   $assData['mobile_number'] = $datas->mobile_number;
                                   $assData['image'] = url('img/user/prifile/'.$datas->avatar_location);
                                   $assData['request_status'] = isset($datas->request_status) && !empty($datas->request_status) ? $datas->request_status : '';

                                    array_push($secOptions, $assData);
                                }

                                //END

                                $data1['question_options']=$options ;

                                $data1['assigned_contractor_and_companies']=$secOptions ;

                                //array_push($allData, $data1);

                            }

                            $resultArray['status']='1';   
                            $resultArray['message']=trans('apimessage.job_detail_found_successfully');
                            $resultdata= $this->intToString($data1);
                            $resultArray['data'] = $resultdata; 
                            echo json_encode($resultArray); exit;
                        }

                     else
                       {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.job_list_not_found');
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


    public function getRequestListByFolderId(Request $request)
    {
        $access_token=123456;
        $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
        $folderid = isset($request->folder_id) && !empty($request->folder_id) ? $request->folder_id : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
         App::setLocale($lang);

            $validator = Validator::make($request->all(), [
            'userid' => 'required',
            //'session_key' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 


                if(!empty($userid) && !empty($folderid)) 
                {
                   // $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                    if(1!=1)
                    {
                     //echo json_encode($check_auth); exit;
                    }else
                    {

                        $userEntity = DB::table('folder_projects')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(folder_id = '".$folderid."' AND deleted_at IS null )")
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
                        ->leftjoin('folder_projects', 'service_request.id', '=', 'folder_projects.requested_service_id','folder_projects.folder_id','=',$folderid)
                        ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image')
                        ->where('service_request.status','0')->where('service_request.user_id',$userid)->where('folder_projects.folder_id',$folderid)->whereRaw("(service_request.deleted_at IS null )")->orderBy('service_request.id', 'DESC')->get(); 


                        if(!empty($servicesRequested))
                        {

                            $data1=array();
                            foreach ($servicesRequested as $key => $vall) 
                            {

                                $data1['id'] = $vall->id;
                                $data1['service_id'] = $vall->service_id;
                                if($lang=='es')
                                {
                                    $service_name=$vall->es_name;
                                }
                                else
                                {
                                    $service_name=$vall->en_name;
                                }



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
                                $resultArray['message']=trans('apimessage.request_list_found_successfully');
                                $resultdata= $this->intToString($allData);
                                $resultArray['data'] =$resultdata; 
                                echo json_encode($resultArray); exit;   
                            }
                            else
                            {
                                $resultArray['status']='0';   
                                $resultArray['message']=trans('apimessage.request_list_not_found');
                                echo json_encode($resultArray); exit;   
                            }  
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.request_not_found');
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


    public function getAssignedContractorAndCompaniesProfileDetailsById(Request $request)
    {
        $access_token=123456;
        $pro_user_id = isset($request->pro_user_id) && !empty($request->pro_user_id) ? $request->pro_user_id : '' ;
      
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang);

        $validator = Validator::make($request->all(), [
                'pro_user_id' => 'required',
            ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

        if(!empty($pro_user_id)) 
        {
            $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")
                    //->whereRaw("(is_verified=1)")
                    ->whereRaw("(id = '".$pro_user_id."' AND deleted_at IS null )")
                    ->first();
            if(!empty($userEntity))
            {
                //Normal user 
             
                //************************************************//

                //start Contractor & Company

                if($userEntity->user_group_id==3 || $userEntity->user_group_id==4)
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
                        $users_count_var=DB::table('users')
                          ->leftjoin('reviews', 'users.id', '=', 'reviews.to_user_id')->select('users.id','users.ruc_no','users.legal_representative','users.website_address','users.year_of_constitution','users.user_group_id','users.email','users.username','users.profile_title','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','users.created_at','users.updated_at','users.total_employee')->where('users.id',$userEntity->id)->first();
                    }
                    else
                    {
                        $users_count_var=DB::table('users')->
                         leftjoin('reviews', 'users.id', '=', 'reviews.to_user_id')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.dob','users.address','users.address_lat','users.address_long','users.office_address','users.office_address_lat','users.office_address_long','users.other_address','users.other_address_lat','users.other_address_long','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','users.created_at','users.updated_at','users.total_employee')->where('users.id',$userEntity->id)->first();
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

                        $getAllReview = DB::table('reviews')
                         ->select(DB::raw('sum(rating) as total_rating'), DB::raw('COUNT(id) as total_rating_count'))
                         ->groupBy('to_user_id')
                         ->whereRaw("(reviews.to_user_id = '".$pro_user_id."' AND reviews.deleted_at IS null )")
                        ->get()->toArray();

                        if(!empty($getAllReview))
                        {
                            foreach ($getAllReview as $review) 
                            {
                               //$presentdata1['rating']= $review->rating;
                                $total=!empty($review->total_rating)?$review->total_rating:0;
                                $total_rating_count=!empty($review->total_rating_count)?$review->total_rating_count:0;
                               //$alldata[]=$total;
                            }

                        }

                        if(!empty($total) && !empty($total_rating_count))
                        {

                            $users_count_var->total_rating_count = $total_rating_count;
                            $users_count_var->global_avg_rating = $total/$total_rating_count;
                        }

                        $getAllReview1 = DB::table('users')
                        ->leftjoin('reviews', 'users.id', '=', 'reviews.user_id')
                        ->select('users.id','users.user_group_id','users.avatar_location','users.username', 'reviews.rating', 'reviews.review')
                        ->whereRaw("(reviews.to_user_id = '".$pro_user_id."')")
                        ->where('user_group_id', 2)
                        ->limit(3)
                        ->orderBy('reviews.id', 'desc')
                        ->get()->toArray();

                        // echo $getAllReview1->to_user_id; 
                        $options=array();
                        //echo "<pre>"; print_r($getAllReview1); die;
                        $reviewData = array();
                        $reviewData2 = array();

                        foreach ($getAllReview1 as $key => $qualification) 
                        {

                            $user_id=$qualification->id;
                            $reviewData['user_id'] = $qualification->id;
                            $reviewData['user_group_id']=$qualification->user_group_id;
                            if(!empty( $qualification->username)){
                                 $reviewData['username']=$qualification->username;
                            }
                           
                            
                            if($qualification->user_group_id==2)
                            {
                                $userprofilePath ='/img/user/profile/';
                               
                            }
                            if(!empty($profilePath.$qualification->avatar_location)) 
                            {
                                
                              $reviewData['profile']= isset($qualification->avatar_location) && !empty($qualification->avatar_location) ? url($userprofilePath.$qualification->avatar_location) : '';

                            } 
                            // else {
                            //    $reviewData['profile']= '';
                            // } 

                            if(!empty($qualification->review)){
                               $reviewData['review']=$qualification->review;  
                            }

                           if(!empty($qualification->rating)){
                             $reviewData['rating']=$qualification->rating;
                           }
                           
                            $reviewData2[] = $reviewData;
                        }
                                                   
                       //print_r($reviewData2);die;

                        if(!empty($reviewData2))
                        {
                            $data1['sub_services']=$options;
                            //array_push($allData, $reviewData2);
                            $users_count_var->review_list = $reviewData2;
                        }
                    }
                    else if($userEntity->user_group_id==3)
                    {
                        $users_count_var->identity_no = isset($users_count_var->identity_no) && !empty($users_count_var->identity_no) ? $users_count_var->identity_no : '';

                        if(!empty($users_count_var->username) && !empty($users_count_var->identity_no) && !empty($users_count_var->mobile_number))
                        {
                            $users_count_var->is_profile_complete = true;
                        }
                        else
                        {
                            $users_count_var->is_profile_complete = false;
                        }
                                          
                        $getAllReview = DB::table('reviews')
                         ->select(DB::raw('sum(rating) as total_rating'), DB::raw('COUNT(id) as total_rating_count1'))
                         ->groupBy('to_user_id')
                         ->whereRaw("(reviews.to_user_id = '".$pro_user_id."' AND reviews.deleted_at IS null )")
                        ->get()->toArray();
 
                        if(!empty($getAllReview))
                        {
                            foreach ($getAllReview as $review) 
                            {
                               //$presentdata1['rating']= $review->rating;
                                
                                 $total=!empty($review->total_rating)?$review->total_rating:0;
                                 $total_rating_count1=!empty($review->total_rating_count1)?$review->total_rating_count1:0;

                               //$alldata[]=$total;

                            }
                        }
                        if(!empty($total_rating_count1))
                        {

                            $users_count_var->total_rating_count = $total_rating_count1;

                        }

                        if(!empty($total))
                        {
                            $users_count_var->global_avg_rating = $total/$total_rating_count1;
                        }
                                           
                        $getAllReview1 = DB::table('users')
                            ->leftjoin('reviews', 'users.id', '=', 'reviews.user_id')
                            ->select('users.id','users.user_group_id','users.avatar_location','users.username', 'reviews.rating', 'reviews.review')
                            ->whereRaw("(reviews.to_user_id = '".$pro_user_id."')")
                            ->where('user_group_id', 2)
                            ->limit(3)
                            ->orderBy('reviews.id', 'desc')
                            ->get()->toArray();

                            // echo $getAllReview1->to_user_id; 
                            $options=array();
                            //echo "<pre>"; print_r($getAllReview1); die;
                            $reviewData = array();
                            $reviewData2 = array();
                                               
                            foreach ($getAllReview1 as $key => $qualification) 
                            {
                                $user_id=$qualification->id;
                                $reviewData['user_id'] = $qualification->id;
                                $reviewData['user_group_id']=$qualification->user_group_id;
                                if(!empty( $qualification->username))
                                {
                                    $reviewData['username']=$qualification->username;
                                }
                               
                                if($qualification->user_group_id==2)
                                {
                                    $userprofilePath ='/img/user/profile/';
                                   
                                }
                                if(!empty($profilePath.$qualification->avatar_location)) 
                                {
                                    
                                  $reviewData['profile']= isset($qualification->avatar_location) && !empty($qualification->avatar_location) ? url($userprofilePath.$qualification->avatar_location) : '';

                                } 
                                // else {
                                //    $reviewData['profile']= '';
                                // } 

                                if(!empty($qualification->review))
                                {
                                   $reviewData['review']=$qualification->review;  
                                }

                               if(!empty($qualification->rating)){
                                 $reviewData['rating']=$qualification->rating;
                               }
                               
                                $reviewData2[] = $reviewData;
                            }
                                               
                           //print_r($reviewData2);die;

                            if(!empty($reviewData2))
                            {
                                $data1['sub_services']=$options;
                                //array_push($allData, $reviewData2);
                                $users_count_var->review_list = $reviewData2;
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
                        {
                            $methodName='payment_methods.name_es AS method_name';
                        }
                         else
                        {
                            $methodName='payment_methods.name_en AS method_name';
                        }

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
                            $resultArray['message']=trans('apimessage.data_found_successfully');
                           
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
            else 
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {

            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 


            if(!empty($from_userid) && !empty($to_userid)) 
            {
             

                //$check_auth = $this->checkToken($access_token, $from_userid, $session_key, $lang);
                
                
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                    $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")
                    ->whereRaw("(id = '".$from_userid."' AND deleted_at IS null )")
                    ->first();

                    // $userTwoEntity = DB::table('users')
                    // ->whereRaw("(active=1)")
                    // ->whereRaw("(id = '".$to_userid."' AND deleted_at IS null )")
                    // ->first();

                    if(!empty($userEntity) )
                    {
                        $userToken = DB::table('users')
                            ->leftjoin('user_devices','user_devices.user_id','=','users.id')
                            ->where('users.id',$to_userid)
                            ->select('user_devices.*', 'users.email','users.username','users.mobile_number','users.user_group_id')
                            ->first();
                           // print_r($userToken);exit;
                            $device_id=isset($userToken->device_id)?$userToken->device_id:'';
                            $device_type=$userToken->device_type;
                            $title='Mensaje nuevo';
                            $messagechat='Tienes un mensaje nuevo en el Chat de Búskalo.';
                            $userid= $from_userid;
                            $prouserId=0; 
                            $serviceId= 0;
                            $senderId=$from_userid;
                            $reciverId=$to_userid;
                            $notify_type='chat_message';
                            $senderName=isset($userEntity->username)?$userEntity->username:$userEntity->first_name;
                            if($userToken->user_group_id==2)
                            {
                                $chatType='user';
                            }
                            elseif($userToken->user_group_id==3 || $userToken->user_group_id==4)
                            {
                                $chatType='pro';
                            }
                            else
                            {
                                 $chatType='';  
                            }

                            $insert['from_userid'] = $from_userid;    
                            $insert['to_userid'] = $to_userid;
                            $insert['message'] = $message;
                            $insert['is_read'] = 0;
                            $insert['is_starred'] = 0;
                            $insert['created_at'] = Carbon::now();  
                            $lastId=DB::table('users_chat')->insertGetId($insert);  
                            $resultArray['status']='1';
                            $resultArray['message']=trans('apimessage.message_send_success');
                            echo json_encode($resultArray);
                            // if($userToken->device_type=='android')
                            // {
                                $this->postpushnotification($device_id,$title,$messagechat,$userid,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                            // }
                            // if($userToken->device_type=='ios')
                            // {
                            //     $this->iospush($device_id,$title,$messagechat,$userid,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type );
                            // }

                            exit;      
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
        $olddate=date('Y-m-d H:i:s', strtotime('-8 days'));
        $validator = Validator::make($request->all(), [
        'userid' => 'required',
       // 'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid)) 
            {

                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                        ->where('users_chat.is_read',0)
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
                                    ->whereRaw("(assign_service_request.user_id = '".$userid."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status IS null AND service_request.deleted_at IS null)")
                                    ->where('service_request.status','0')
                                    ->where('assign_service_request.created_at', '>',$olddate)
                                    ->where('assign_service_request.is_read',0)
                                    ->groupBy('assign_service_request.service_request_id')
                                    ->get(); 

                                     $allOpprtunitiesCountArr=$allOpprtunitiesCount;

                            }
                          
                           /// Apportunity Count End Here ///
                            /// Job List Count Start here ///

                         $jobListCount = DB::table('assign_service_request')
                        ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                        ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                        ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','service_request.email_verify','service_request.created_at','services.en_name', 'services.es_name', 'services.image','assign_service_request.tranx_status','assign_service_request.tranx_id')
                        ->where('assign_service_request.user_id',$userid)
                        ->where('assign_service_request.job_status','1')
                        ->where('assign_service_request.request_status','buy')
                        ->whereRaw("(service_request.deleted_at IS null )")->get();


                         if(!empty($jobListCount) || !empty($chatListCount)|| !empty($allOpprtunitiesCountArr))
                           {

                            $jobCount=isset($jobListCount) && !empty($jobListCount) ? count($jobListCount) : [] ;
                            $chatCount=isset($chatListCount) && !empty($chatListCount) ? count($chatListCount) : [] ;
                            $oppCount=isset($allOpprtunitiesCountArr) && !empty($allOpprtunitiesCountArr) ? count($allOpprtunitiesCountArr) : [] ;

                                $resultArray['status']='1';   
                                $resultArray['message']=trans('apimessage.count_found_success');
                                $resultArray['data'] = array('All Job Count'=>$jobCount,'All Chat Count'=>$chatCount,'All Opportunity Count'=>$oppCount); 
                                echo json_encode($resultArray); exit;
                          }
                         else
                           {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.data_not_found');
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


    public function unreadMessageCountByProId(Request $request)
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid)) 
            {

                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                        ->leftjoin('users', 'users_chat.to_userid', '=', 'users.id')
                        ->select('users_chat.id','users_chat.message','users_chat.created_at','users.id AS from_user_id','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by')
                        ->where('users_chat.to_userid',$userid)
                        ->whereRaw('NOT FIND_IN_SET('.$userid.',users_chat.read_by)')
                        ->whereRaw("(users_chat.deleted_at IS null )")
                        ->where('users_chat.is_read',0)
                        ->groupBy('users_chat.from_userid')->get(); 
                       // echo '<pre>'; print_r($chatListCount);exit();

                           /// Chat Count End Here//

                           /// Apportunity Count Start Here ///
                        $allOpprtunitiesCount = DB::table('service_request')
                                    ->leftjoin('assign_service_request','assign_service_request.service_request_id','=','service_request.id')
                                    ->where('service_request.user_id',$userid)
                                    ->where('assign_service_request.request_status','buy')
                                    ->where('service_request.status','0')
                                    ->where('assign_service_request.is_pro_read',0)
                                    ->whereRaw("(service_request.deleted_at IS null )")
                                    ->get();

                            $allOpprtunitiesCountArr=$allOpprtunitiesCount;
                        if(!empty($chatListCount)|| !empty($allOpprtunitiesCountArr))
                        {
                            $chatCount=isset($chatListCount) && !empty($chatListCount) ? count($chatListCount) : [] ;
                            $oppCount=isset($allOpprtunitiesCountArr) && !empty($allOpprtunitiesCountArr) ? count($allOpprtunitiesCountArr) : 0 ;

                            $resultArray['status']='1';   
                            $resultArray['message']=trans('apimessage.count_found_success');
                            $resultArray['data'] = array('All Chat Count'=>$chatCount,'All Pro Service Buy '=>$oppCount); 
                            echo json_encode($resultArray); exit;
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.data_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid)) 
            {

                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                    $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")
                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                    ->first();
                   // print_r($userEntity); die;
                    if(!empty($userEntity))
                    {
                      if($userEntity->user_group_id==3){
                         $chatList = DB::table('users_chat')
                        ->leftjoin('users', 'users_chat.from_userid', '=', 'users.id')
                        ->select('users_chat.id','users_chat.message','users_chat.created_at','users.id AS from_user_id','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by','users_chat.to_userid','users_chat.is_starred_pro')
                        ->where('users_chat.from_userid',$userEntity->id)
                        ->whereRaw("(users_chat.deleted_by IS null )")
                        ->whereRaw("(users.id IS NOT null )")
                        ->groupBy('users_chat.to_userid')
                        ->orderBy('users_chat.created_at', 'DESC')
                        ->get();
                        //->unique('to_userid');
                       }

                       if($userEntity->user_group_id==4){
                         $chatList = DB::table('users_chat')
                        ->leftjoin('users', 'users_chat.from_userid', '=', 'users.id')
                        ->select('users_chat.id','users_chat.message','users_chat.created_at','users.id AS from_user_id','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by','users_chat.to_userid','users_chat.is_starred_pro')
                        ->where('users_chat.from_userid',$userEntity->id)
                        ->whereRaw("(users_chat.deleted_by IS null )")
                        ->whereRaw("(users.id IS NOT null )")
                        ->groupBy('users_chat.to_userid')
                        ->orderBy('users_chat.created_at', 'DESC')
                        ->get();
                        //->unique('to_userid');
                       }

                       if($userEntity->user_group_id==2){
                         $chatList = DB::table('users_chat')
                       
                        ->leftjoin('users', 'users_chat.from_userid', '=', 'users.id')
                        ->select('users_chat.id','users_chat.message','users_chat.created_at','users.id AS from_user_id','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by','users_chat.to_userid','users_chat.is_starred_pro')
                        ->where('users_chat.to_userid',$userEntity->id)
                        ->whereRaw("(users_chat.deleted_by IS null )")
                        ->whereRaw("(users.id IS NOT null )")
                        ->groupBy('users_chat.from_userid')
                        ->orderBy('users_chat.created_at', 'DESC')->get();
                       // ->unique('to_userid');
                        
                       }
                        // print_r($chatList); die;
                        /*$date_time = DB::table('users_chat')
                         ->leftjoin('users', 'users_chat.from_userid', '=', 'users.id')
                         ->select('users_chat.created_at')

                         ->whereRaw("(users_chat.deleted_at IS null )")
                         ->orderBy('users_chat.created_at', 'desc')->get()->toArray();*/
                          //    print_r($chatList);die;
                       
                        if(!empty($chatList) && count($chatList) > 0)
                        {
                            $data1=array();
                            $path='/img/user/profile/';
                            foreach ($chatList as $key => $vall)
                            {

                                $getRecieverprofile = DB::table('users')
                                ->select('avatar_location','username')
                                ->whereRaw("(active=1)")
                                ->whereRaw("(id = '".$vall->from_user_id."' AND deleted_at IS null )")
                                ->first();

                                $getSenderprofile = DB::table('users')
                                ->select('avatar_location', 'username')
                                ->whereRaw("(active=1)")
                                ->whereRaw("(id = '".$vall->to_userid."' AND deleted_at IS null )")
                                ->first();

                                // $date_time = DB::table('users_chat')
                                // ->where('from_userid',$vall->to_userid)
                                // ->where('to_userid',$vall->from_user_id)
                                //  ->select('users_chat.created_at as date_time_created_at')
                                //  ->orderBy('users_chat.id', 'DESC')->first();

                                if($userEntity->user_group_id==2)
                                {
                                    $date_time = DB::table('users_chat')
                                    ->where('from_userid',$vall->from_user_id)
                                    ->where('to_userid',$vall->to_userid)
                                    ->select('users_chat.created_at as date_time_created_at','users_chat.is_read as isread')
                                    ->orderBy('users_chat.id', 'DESC')->first();
                                }
                                if($userEntity->user_group_id==3)
                                {  
                                    $date_time = DB::table('users_chat')
                                    ->where('from_userid',$vall->to_userid)
                                    ->where('to_userid',$vall->from_user_id)
                                    ->select('users_chat.created_at as date_time_created_at','users_chat.is_read as isread')
                                    ->orderBy('users_chat.id', 'DESC')->first();
                                }
                                if($userEntity->user_group_id==4)
                                {  
                                    $date_time = DB::table('users_chat')
                                    ->where('from_userid',$vall->to_userid)
                                    ->where('to_userid',$vall->from_user_id)
                                    ->select('users_chat.created_at as date_time_created_at','users_chat.is_read as isread')
                                    ->orderBy('users_chat.id', 'DESC')->first();
                                }
                                 
                                //print_r($date_time);die;
                               if(!empty($getSenderprofile->avatar_location))
                                {
                                 $from_usr_profile = url($path.$getSenderprofile->avatar_location);
                                //  $to_usr_name = !empty($getRecieverprofile->username)?$getRecieverprofile->username:'';

                                }else
                                {
                                   $from_usr_profile =""; 
                                //    $to_usr_name ="";
                                   
                                }

                                if(!empty($getRecieverprofile->avatar_location))
                                {
                                 $to_usr_profile = url($path.$getRecieverprofile->avatar_location);
                                 $to_usr_name = !empty($getSenderprofile->username)?$getSenderprofile->username:'';
                                }else
                                {
                                   $to_usr_name = !empty($getSenderprofile->username)?$getSenderprofile->username:'';;
                                   $to_usr_profile ="";
                                  
                                }


                                $data1['id'] = $vall->id;
                                $data1['from_userid'] = $vall->to_userid;
                                $data1['from_username'] = $to_usr_name;
                                $data1['from_user_profile'] = $from_usr_profile;

                                $data1['to_userid'] = $vall->from_user_id;
                                $data1['to_username'] = $vall->from_user_name;
                                $data1['to_user_profile'] = $to_usr_profile;

                                $data1['message'] = $vall->message;
                                $data1['is_starred'] = $vall->is_starred;
                                $data1['is_starred_pro'] = $vall->is_starred_pro;
                                $data1['is_read'] = !empty($date_time->isread)?$date_time->isread:0;//$vall->is_read;
                                $data1['created_at'] = $vall->created_at;
                                $data1['date_time'] = !empty($date_time->date_time_created_at)?$date_time->date_time_created_at:'';
                              
                                if(!empty($vall->deleted_by))
                                {
                                    $HiddenValue = explode(',',$vall->deleted_by);
                                    if (in_array($userid, $HiddenValue)) 
                                    {
                                       $allData=[];
                                       $msg= trans('apimessage.message_list_not_found');
                                    } 
                                }
                                else
                                {
                                 $msg= trans('apimessage.message_list_found');
                                 array_push($allData, $data1);
                                }
                            } 
                                $resultArray['status']='1';   
                                $resultArray['message']=trans($msg);
                                $resultdata= $this->intToString($allData);
                                $resultArray['data'] =$resultdata; 
                                echo json_encode($resultArray); exit;
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.message_list_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($from_user_id) ) 
            {

               // $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                    //echo json_encode($check_auth); exit;
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
                    ->select('users_chat.id','users_chat.message','users_chat.created_at','users_chat.from_userid','users_chat.to_userid','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by','users_chat.type','users_chat.is_starred_pro')
                    ->where(function($query) use ($request,$userid,$from_user_id,$userEntity) 
                    {
                        $query->where('from_userid', $userid)->where('to_userid', $from_user_id);
                    })->orWhere(function ($query) use ($request,$userid,$from_user_id,$userEntity) 
                    {  
                        $query->where('from_userid', $from_user_id)->where('to_userid', $userid);
                    })
                    //->orWhere('users_chat.deleted_by', '!=' , 'NULL')
                        ->orderBy('created_at', 'ASC')
                        ->get();

                       //echo '<pre>';print_r($chatList); die;  

                        if(!empty($chatList) && count($chatList) > 0)
                        {

                             $data1=array();
                             $to_usr_profile ="";
                             $path="";
                             $msg="";
                            foreach ($chatList as $key => $vall)
                            {
                                $getReciverprofile = DB::table('users')
                                ->select('avatar_location','user_group_id', 'username')
                                ->whereRaw("(active=1)")
                                ->whereRaw("(id = '".$vall->to_userid."' AND deleted_at IS null )")
                                ->first();

                                //print_r($getReciverprofile); die;

                                $getSenderprofile = DB::table('users')
                                ->select('avatar_location','user_group_id', 'username')
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

                                $to_usr_username ="";
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
                                        $to_usr_username = $getReciverprofile->username;
                                    } else 
                                    {
                                        $to_usr_profile ="";
                                        $to_usr_username ="";
                                    }
                                }
                               
                                $data1['id'] = $vall->id;
                                $data1['from_userid'] = $vall->from_userid;
                                $data1['from_username'] = $vall->from_user_name;
                                $data1['from_user_profile'] = isset($from_user_profile)?$from_user_profile:'';
                                $data1['to_userid'] = $vall->to_userid;
                                $data1['to_username'] = $to_usr_username;
                                $data1['to_user_profile'] = isset($to_usr_profile)?$to_usr_profile:'';
                                $data1['message'] = $vall->message;
                                $data1['is_starred'] = $vall->is_starred;
                                $data1['is_starred_pro'] = $vall->is_starred_pro;
                                $data1['is_read'] = $vall->is_read;
                                $data1['chat_type'] = $vall->type;
                                $data1['created_at'] = $vall->created_at;

                                 if(!empty($vall->deleted_by))
                                {
                                      $HiddenValue = explode(',',$vall->deleted_by);
                                    if (in_array($userid, $HiddenValue)) 
                                    {
                                        $msg= trans('apimessage.starred_message_list_not_found');
                                    } else 
                                    {
                                        $msg= trans('apimessage.starred_message_list_found');
                                        array_push($allData, $data1);
                                    } 
                                }
                                 else
                               { 
                                 $msg= trans('apimessage.message_list_found');
                                 array_push($allData, $data1);
                               }
                              
                               //  if(!empty($vall->deleted_by))
                               //  {   
                                  
                               //      $HiddenValue = explode(',',$vall->deleted_by);

                               //      if (in_array($userid, ['".$vall->deleted_by."'])) 
                               //      {

                               //          $allData=[];
                               //         $msg= trans('apimessage.message_list_not_found');
                                   
                               //      }
                               // }
                               // else
                               // { 
                               //   $msg= trans('apimessage.message_list_found');
                               //   array_push($allData, $data1);
                               // }
                            }

                            $resultArray['status']='1';   
                            $resultArray['message']=trans($msg);
                            $resultdata= $this->intToString($allData);
                            $resultArray['data'] =$resultdata; 
                            echo json_encode($resultArray); exit;
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.message_list_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($message_id)) 
            {

               // $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                    $userEntity = DB::table('users')
                        ->whereRaw("(active=1)")
                        ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                        ->first();

                    if(!empty($userEntity))
                    {
                 
                        
                            //$update_Arr['is_read'] = '1';
                        if($is_starred==1 OR $is_starred=='1')
                        {
                            $msg=trans('apimessage.message_marked_to_starred');
                        }
                        else
                        {
                            $msg=trans('apimessage.message_remove_from_starred');
                        }
                        if($userEntity->user_group_id==2)
                        {
                            $update_Arr['is_starred'] = $is_starred;
                            DB::table('users_chat')->where('id', $message_id)->where('to_userid', $userid)->update($update_Arr);
                        }
                        if($userEntity->user_group_id==3 ||$userEntity->user_group_id==4)
                        {
                             $update_Arr1['is_starred_pro'] = $is_starred;
                             DB::table('users_chat')->where('id', $message_id)->where('from_userid', $userid)->update($update_Arr1);
                        }
                            // DB::table('users_chat')->where('id', $message_id)->where('to_userid', $userid)->update($update_Arr);

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
        $from_user_id = !empty($request->from_user_id) ? $request->from_user_id : '' ;
        $userid = !empty($request->userid) ? $request->userid : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang);

        $validator = Validator::make($request->all(), [
        'from_user_id' => 'required',
        'userid' => 'required',
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($from_user_id)) 
            {

                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                            ->whereRaw("(from_userid = '".$from_user_id."')")
                            ->whereRaw("(to_userid = '".$userid."' AND deleted_at IS null )")
                            ->get()->toArray();
                        // $getProUsersChat = DB::table('users_chat')  
                        //     ->whereRaw("(from_userid = '".$userid."')")
                        //     ->whereRaw("(to_userid = '".$from_user_id."' AND deleted_at IS null )")
                        //     ->get()->toArray();

                            if(!empty($getUsersChat)) 
                            {

                               foreach ($getUsersChat as $key => $valued) 
                                {
                                    $HiddenValue = explode(',',$valued->read_by);

                                    if(in_array($userid, $HiddenValue))
                                    {
                                            
                                    }else
                                    {
                                        if(!empty($valued->read_by))
                                        {
                                            $update_Arr['read_by'] = $valued->read_by.','.$userid;
                                            $update_Arr['is_read'] = '1';
                                        }else
                                        {
                                           $update_Arr['read_by'] = $userid;
                                            $update_Arr['is_read'] = '1';
                                        }
                                                 
                                         DB::table('users_chat')->where('id', $valued->id)->update($update_Arr);
                                    }

                                    if(in_array($from_user_id, $HiddenValue))
                                    {
                                            
                                    }else
                                    {

                                        if(!empty($valued->read_by))
                                        {
                                            $update_Arr['read_by'] = $valued->read_by.','.$from_user_id;
                                            $update_Arr['is_readbypro'] ='1';
                                        }else
                                        {
                                           $update_Arr['read_by'] = $from_user_id;
                                            $update_Arr['is_readbypro'] ='1';
                                        }
                                                 
                                         DB::table('users_chat')->where('id', $valued->id)->update($update_Arr);

                                    }
                                }

                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('apimessage.message_read_success');
                                    echo json_encode($resultArray); exit;    
                            }else if(!empty($getProUsersChat)){

                                foreach ($getProUsersChat as $key => $valued) 
                                {
                                    $HiddenValue = explode(',',$valued->read_by);

                                    if(in_array($userid, $HiddenValue))
                                    {
                                            
                                    }else
                                    {
                                        if(!empty($valued->read_by))
                                        {
                                            $update_Arr['read_by'] = $valued->read_by.','.$userid;
                                            $update_Arr['is_read'] = '1';
                                        }else
                                        {
                                           $update_Arr['read_by'] = $userid;
                                            $update_Arr['is_read'] = '1';
                                        }
                                                 
                                         DB::table('users_chat')->where('id', $valued->id)->update($update_Arr);
                                    }

                                    if(in_array($from_user_id, $HiddenValue))
                                    {
                                            
                                    }else
                                    {
                                        if(!empty($valued->read_by))
                                        {
                                            $update_Arr['read_by'] = $valued->read_by.','.$from_user_id;
                                            $update_Arr['is_readbypro'] ='1';
                                        }else
                                        {
                                           $update_Arr['read_by'] = $from_user_id;
                                            $update_Arr['is_readbypro'] ='1';
                                        }
                                                 
                                         DB::table('users_chat')->where('id', $valued->id)->update($update_Arr);

                                    }
                                }

                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('apimessage.message_read_success');
                                    echo json_encode($resultArray); exit;


                            }else 
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.message_id_nor_found');
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

    public function proReadMessage(Request $request)
    {
        $access_token=123456;
        $userid = !empty($request->userid) ? $request->userid : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang);

        $validator = Validator::make($request->all(), [
        'userid' => 'required',
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid)) 
            {

                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                     $userEntity = DB::table('users')
                        ->whereRaw("(active=1)")
                        ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                        ->first();
                    if(!empty($userEntity))
                    {
                        $allOpprtunitiesCount = DB::table('assign_service_request')
                                    ->join('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                                     ->where("service_request.user_id",$userid)
                                     ->where('service_request.status','0')
                                     ->update(['assign_service_request.is_pro_read'=>'1']);

                            $resultArray['status']='1';
                            $resultArray['message']=trans('Pro read request successfully');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid)) 
            {

               // $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                        ->select('users_chat.id','users_chat.message','users_chat.created_at','users.id AS from_user_id','users.username AS from_user_name','users_chat.is_read','users_chat.is_starred','users_chat.deleted_by','users_chat.is_starred_pro')
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
                                $data1['is_starred_pro'] = $vall->is_starred_pro;
                                $data1['is_read'] = $vall->is_read;
                                $data1['created_at'] = $vall->created_at;
                              
                                if(!empty($vall->deleted_by))
                                {
                                      $HiddenValue = explode(',',$vall->deleted_by);
                                    if (in_array($userid, $HiddenValue)) 
                                    {
                                        $msg= trans('apimessage.starred_message_list_not_found');
                                    } else 
                                    {
                                        $msg= trans('apimessage.starred_message_list_found');
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
                            $resultArray['message']=trans('apimessage.starred_message_list_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($message_id)) 
            {

               // $check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                                    $resultArray['message']=trans('apimessage.this_message_already_delete');
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
                                $resultArray['message']=trans('apimessage.message_delete_success');
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
            //'session_key' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 

                if(!empty($userid) && !empty($from_userid)) 
                {

                    //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                    if(1!=1)
                    {
                     //echo json_encode($check_auth); exit;
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
                        $HiddenValue=[];
                         if(!empty($getUsersChat->deleted_by))
                         {
                            $HiddenValue = explode(',',$getUsersChat->deleted_by);
                         }
                        
                        if (in_array($userid, $HiddenValue)) 
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.your_chat_already_clear_successfully');
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
                                  $resultArray['message']=trans('apimessage.all_message_delete_success');
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
        $price = !empty($request->price_rating) ? $request->price_rating : '' ;
        $puntuality = !empty($request->puntuality_rating) ? $request->puntuality_rating : '' ;
        $service = !empty($request->service_rating) ? $request->service_rating : '' ;
        $quality = !empty($request->quality_rating) ? $request->quality_rating : '' ;
        $amiability = !empty($request->amiability_rating) ? $request->amiability_rating : '' ;
        $request_id = !empty($request->request_id) ? $request->request_id : '' ;

        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang);

         $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'to_user_id' => 'required',
            'rating' => 'required',
            'request_id' => 'required',
            //'session_key' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 

        if(isset($userid) && !empty($userid) && isset($to_user_id) && !empty($to_user_id) && isset($rating) && !empty($rating) && isset($review) && !empty($review) && isset($request_id) && !empty($request_id))
        {    
             $user_arr = DB::table('users')
                ->select('users.*')
                ->whereRaw("(users.user_group_id=2)")
                ->whereRaw("(users.id = '".$userid."' AND deleted_at IS null )")
                ->first();  

                $service_arr = DB::table('service_request')
                // ->whereRaw("(status=4)")
                ->whereRaw("(id = '".$request_id."' AND deleted_at IS null )")
                ->first();  

            if(!empty($user_arr))
            {
                if(!empty($service_arr))
                {
                    $contractor_arr = DB::table('users')
                        ->select('users.*')
                        // ->whereRaw("(users.user_group_id=3)")
                        ->whereRaw("(users.id = '".$to_user_id."' AND deleted_at IS null )")
                        ->first();   
                    if(!empty($contractor_arr))
                    {
                        //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                        if(1!=1)
                        {
                             //return json_encode($check_auth);
                        }
                        else
                        {
                             if(!empty($user_arr))
                            {
                                $reatingCheckUser=DB::table('reviews')->where('user_id',$userid)->where('request_id',$request_id)->first();
                                if(empty($reatingCheckUser))
                                {
                                    $Review_rating['user_id'] = trim($userid);
                                    $Review_rating['to_user_id'] = trim($to_user_id);
                                    $Review_rating['price'] = trim($price);
                                    $Review_rating['puntuality'] = trim($puntuality);
                                    $Review_rating['service'] = trim($service);
                                    $Review_rating['quality'] = trim($quality);
                                    $Review_rating['amiability'] = trim($amiability);
                                    $Review_rating['rating'] = trim($rating);
                                    $Review_rating['review'] = trim($review); 
                                    $Review_rating['request_id'] = trim($request_id); 
                                    $Review_rating['created_at'] = Carbon::now()->toDateTimeString();
                                    $Review_rating['is_rate_status'] ='true';
                                    DB::table('reviews')->insert($Review_rating);   

                                    if($lang=='en')
                                    {
                                      $message  = 'Hello '.$contractor_arr->username.', ' .$user_arr->username.' has given you a rating and review for your service';  
                                    }
                                    if($lang=='es')
                                    {
                                        $message  = 'Hola '.$contractor_arr->username.', ' .$user_arr->username.' le ha dado una calificación y revisión por su servicio'; 
                                    }
                                    

                                    $resultArray['status']='1';
                                    $resultArray['message']=trans($message);

                                    $userToken = DB::table('users')
                                    ->leftjoin('user_devices','user_devices.user_id','=','users.id')
                                    ->where('users.id',$to_user_id)
                                    ->select('user_devices.*', 'users.email','users.username','users.mobile_number','users.user_group_id')
                                    ->first();
                                    // print_r($userToken);exit;
                                    $device_id=isset($userToken->device_id)?$userToken->device_id:'';
                                    $device_type=$userToken->device_type;
                                    $title='Mensaje nuevo';
                                    $messagechat='Tienes un mensaje nuevo en el Chat de Búskalo.';
                                    $userid= $userid;
                                    $prouserId=0; 
                                    $serviceId= 0;
                                    $senderId=$userid;
                                    $reciverId=$to_user_id;
                                    $notify_type='qualification';
                                    $senderName=isset($contractor_arr->username)?$contractor_arr->username:$contractor_arr->first_name;
                                    

                                    $insert['from_userid'] = $userid;    
                                    $insert['to_userid'] = $to_user_id;
                                    $insert['message'] = $message;
                                    $insert['is_read'] = 0;
                                    $insert['is_starred'] = 0;
                                    $insert['created_at'] = Carbon::now();  
                                    $lastId=DB::table('users_chat')->insertGetId($insert);  
                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('apimessage.qualification_send_success');
                                    echo json_encode($resultArray);
                                    // if($userToken->device_type=='android')
                                    // {
                                        $this->postpushnotification($device_id,$title,$messagechat,$userid,$prouserId,$serviceId,$senderId,$reciverId,$senderName,$notify_type);
                                    // }
                                    // if($userToken->device_type=='ios')
                                    // {
                                    //     $this->iospush($device_id,$title,$messagechat,$userid,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type );
                                    // }

                                    exit;      
                                    return json_encode($resultArray);
                                }
                                else
                                {   
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('apimessage.rating_already');
                                    return json_encode($resultArray);

                                }                 
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
                    $resultArray['message']=trans('apimessage.invalid_request_id');
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

                    $getServices=DB::table('services')
                        ->where($lang_name, 'LIKE', "%$search_key%")
                        ->where('status',1)
                        ->whereRaw("(deleted_at IS null)")->get()->toArray(); 

                    $getSubServices=DB::table('sub_services')
                        ->where($lang_name, 'LIKE', "%$search_key%")
                        ->where('status',1)
                        ->whereRaw("(deleted_at IS null)")->get()->toArray();

                    $getChildSubServices=DB::table('child_sub_services')
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
                                $arr2['category_id'] =  isset($service) && !empty($service->category_id) ? $service->category_id : '' ;  
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
                                $arr3['category_id'] =  isset($sub) && !empty($sub->category_id) ? $sub->category_id : '' ;  
                                $arr3['service_id'] =  isset($sub) && !empty($sub->services_id) ? $sub->services_id : '' ;  
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
                                $arr4['category_id'] =  isset($child) && !empty($child->category_id) ? $child->category_id : '' ;  
                                $arr4['service_id'] =  isset($child) && !empty($child->services_id) ? $child->services_id : '' ;  
                                $arr4['sub_service_id'] =  isset($child) && !empty($child->sub_services_id) ? $child->sub_services_id : '';  
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
                            $resultArray['message']=trans('apimessage.data_found_successfully');
                            $resultArray['data']=$allData;
                            echo json_encode($resultArray); exit;
                        }else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.data_not_found');
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
                        $resultArray['message ']=trans('apimessage.area_list_found');
                        $resultArray['data']=$allData;                  
                        return json_encode($resultArray);
                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message ']=trans('apimessage.area_list_not_found');
                        return json_encode($resultArray);
                    }
        }else
        {
                $resultArray['status']='0';
                $resultArray['message ']=trans('apimessage.area_list_not_found');
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
                        $resultArray['message ']=trans('apimessage.service_and_subservice_list_found');
                        $resultArray['data']=$allData;                  
                        return json_encode($resultArray);
                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message ']=trans('apimessage.service_and_subservice_list_not_found');
                        return json_encode($resultArray);
                    }
        }else
        {
                $resultArray['status']='0';
                $resultArray['message ']=trans('apimessage.service_and_subservice_list_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($video_id)) 
            {
                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                    //echo json_encode($check_auth); exit;
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
                                    $resultArray['message']=trans('apimessage.video_deleted_successfully');
                                    echo json_encode($resultArray); exit;
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.video_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($doc_id)) 
            {

                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                                    $resultArray['message']=trans('apimessage.document_deleted_successfully');
                                    echo json_encode($resultArray); exit;
                            }else
                            {
                                 $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.document_not_found');
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
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($image_id)) 
            {

                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                            }
                            else if($userEntity->user_group_id==4)
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
                            }
                            else if($image_type==1)
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
                                $resultArray['message']=trans('apimessage.image_deleted_successfully');
                                echo json_encode($resultArray); exit;
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.image_not_found');
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
       // 'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($userid) && !empty($image_id)) 
            {
                //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
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
                            }
                            else if($userEntity->user_group_id==4)
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
                                $resultArray['message']=trans('apimessage.image_deleted_successfully');
                                echo json_encode($resultArray); exit;
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.image_not_found');
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

         /* --------------------Delete Api end-------------------- */

    public function getReviewListByUserId(Request $request)
    {

        $access_token=123456;
        $compPoint=array();
        $alldata=array();
        $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
      
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
         App::setLocale($lang);

        $validator = Validator::make($request->all(), [
        'userid' => 'required',
        //'session_key' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

         if(!empty($userid)) 
        { 
            //$check_auth = $this->checkToken($access_token, $userid, $session_key, $lang);
            if(1!=1)
            {
             //echo json_encode($check_auth); exit;
            }else
            {
                 $userEntity = DB::table('users')
                ->whereRaw("(active=1)")
                ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                ->first();

                if(!empty($userEntity))
                {

                    $presentdata=array();
                            
                     $getAllBuyAppUsers = DB::table('users')
                     ->leftjoin('reviews', 'users.id', '=', 'reviews.user_id')

                     ->select('users.id','users.user_group_id','users.avatar_location','users.username', 'reviews.rating', 'reviews.review', 'reviews.user_id')
                     ->whereRaw("(reviews.to_user_id = '".$userid."')")
                     ->get();
                             
                    if($getAllBuyAppUsers)
                    {
                      
                       foreach ($getAllBuyAppUsers as $dataa)
                        {

                            $presentdata['user_id']=$dataa->user_id;
                            $presentdata['user_group_id']=$dataa->user_group_id;
                            $presentdata['username']=!empty($dataa->username)?$dataa->username:'';

                            if($dataa->user_group_id==3)
                            {
                                $profilePath ='/img/contractor/profile/';
                                $certifiePath ='/img/contractor/certifications/';
                                $policePath ='/img/contractor/police_records/';
                            }elseif($dataa->user_group_id==2){
                                $profilePath ='/img/user/profile/';
                            }else
                            {
                                $profilePath ='/img/company/profile/';
                                $certifiePath ='/img/company/certifications/';
                                $policePath ='/img/company/police_records/';
                            }
                           

                            if(!empty($profilePath.$dataa->avatar_location)) 
                            {
                               $presentdata['profile']= isset($dataa->avatar_location) && !empty($dataa->avatar_location) ? url($profilePath.$dataa->avatar_location) : '';

                            } else {
                               $presentdata['profile']= '';
                            }
                           
                        
                         $presentdata['Reviews']=$dataa->review;
                         $presentdata['Rating']=$dataa->rating;
                         $getAllReview = DB::table('reviews')
                             ->select('reviews.rating')
                             ->whereRaw("(reviews.deleted_at IS null )")->get()->toArray();
                             array_push($alldata, $presentdata);
                        }


                        $resultArray['status']='1';
                        $resultArray['message']=trans('apimessage.users_review_list_found');
                        $resultArray['data']=$alldata;
                        echo json_encode($resultArray); exit; 

                    }else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.users_review_list_not_found');
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

    public function GetReviewListByProUserId(Request $request)
    {

        $access_token=123456;
        $compPoint=array();
        $alldata=array();
        $pro_user_id = isset($request->pro_user_id) && !empty($request->pro_user_id) ? $request->pro_user_id : '' ;
      
       
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
         App::setLocale($lang);

        $validator = Validator::make($request->all(), [
        'pro_user_id' => 'required',
        
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

         if(!empty($pro_user_id)) 
        { 

             $userEntity = DB::table('users')
            ->whereRaw("(active=1)")
            ->whereRaw("(id = '".$pro_user_id."' AND deleted_at IS null )")
            ->first();

            if(!empty($userEntity))
            {
                 $presentdata=array();
                 $getAllBuyAppUsers = DB::table('users')
                 ->leftjoin('reviews', 'users.id', '=', 'reviews.user_id')
                 ->select('users.id','users.user_group_id','users.avatar_location','users.username', 'reviews.rating', 'reviews.review', 'reviews.user_id')
                 ->whereRaw("(reviews.to_user_id = '".$pro_user_id."')")
                 ->get();
                         
                if($getAllBuyAppUsers)
                {
                  
                   foreach ($getAllBuyAppUsers as $dataa)
                    {
                        $presentdata['user_id']=$dataa->user_id;
                        $presentdata['user_group_id']=$dataa->user_group_id;
                        $presentdata['username']=!empty($dataa->username)?$dataa->username:'';

                        if($dataa->user_group_id==3)
                        {
                            $profilePath ='/img/contractor/profile/';
                            $certifiePath ='/img/contractor/certifications/';
                            $policePath ='/img/contractor/police_records/';
                        }elseif($dataa->user_group_id==2){
                            $profilePath ='/img/user/profile/';
                        }else
                        {
                            $profilePath ='/img/company/profile/';
                            $certifiePath ='/img/company/certifications/';
                            $policePath ='/img/company/police_records/';
                        }
                       
                        if(!empty($profilePath.$dataa->avatar_location)) 
                        {
                           $presentdata['profile']= isset($dataa->avatar_location) && !empty($dataa->avatar_location) ? url($profilePath.$dataa->avatar_location) : '';

                        } else {
                           $presentdata['profile']= '';
                        }
                    
                     $presentdata['Reviews']=$dataa->review;
                     $presentdata['Rating']=$dataa->rating;
                     $getAllReview = DB::table('reviews')
                         ->select('reviews.rating')
                         ->whereRaw("(reviews.deleted_at IS null )")->get()->toArray();
                         array_push($alldata, $presentdata);
                    }

                    $resultArray['status']='1';
                    $resultArray['message']=trans('apimessage.users_review_list_found');
                    $resultArray['data']=$alldata;
                    echo json_encode($resultArray); exit; 

                }else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.users_review_list_not_found');
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
        else 
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameter.');
            echo json_encode($resultArray); exit;
        }
    }

    public function GetSubscriptionPackage(Request $request)
    {
        $access_token=123456;
        $packageArray = array();
        $lang = !empty($request->lang) ? $request->lang : 'en';
        App::setLocale($lang);
         $packages = DB::table('package')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(deleted_at IS null)")
                        ->get(); 
       
        foreach($packages as $package) 
        {
            if($lang=='es'){$name=$package->es_name;}else{$name=$package->en_name;}
         
           array_push($packageArray,array('id'=>$package->id, 'name'=>$name, 'price'=>$package->price, 'credit'=>$package->credit, 'discount'=>$package->discount, 'status'=>$package->status,'created_at'=>$package->created_at));

        }

        if($packages && !empty($packageArray))
        {
            $resultArray['status']='1';
            $resultArray['message ']=trans('apimessage.subscription_package_list_found_successfully');
            //$resultArray['data']=$packageArray;
          $resultdata=  $this->intToString($packageArray);
            $resultArray['data']=$resultdata;

            return json_encode($resultArray);
        }
        else
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.data_not_found');
            echo json_encode($resultArray); exit;
        }
    }


    public function HireProOrCompany(Request $request)
    {
        $access_token=123456;
        $pro_user_id = !empty($request->pro_user_id) ? $request->pro_user_id : '' ;
        $user_id = !empty($request->user_id) ? $request->user_id : '' ;
        $request_id = !empty($request->request_id) ? $request->request_id : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang);

        $validator = Validator::make($request->all(), [
        'pro_user_id' => 'required',
        //'session_key' => 'required',
        'user_id' => 'required',
        'request_id' => 'required',
        ]);

        if($validator->fails())
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            echo json_encode($resultArray); exit;      
        } 

            if(!empty($user_id) && !empty($pro_user_id)) 
            {

                //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);

                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }

                else
                {
                     $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")
                    ->whereRaw("(id = '".$pro_user_id."' AND deleted_at IS null )")
                    ->first();


                    if(!empty($userEntity))
                    {

                       $hireProOrCompany = DB::table('assign_service_request')
                        ->where('assign_service_request.service_request_id', $request_id)
                        ->whereRaw("(user_id = '".$pro_user_id."' AND deleted_at IS null )")
                        ->first();
                       // ->get()->toArray();
                        //echo '<pre>'; print_r($hireProOrCompany);exit;

                            if(!empty($hireProOrCompany)) 
                            {

                                if($hireProOrCompany->hire_status==0)
                                {
                                    $update_Arr['hire_status'] = 1;
                                    $update_Arr['is_pro_read'] = 1;
                                    $update_Arr['request_status'] = 'buy';
                                    //$update_Arr['job_status'] = 3;
                                    $update_jobArr['request_status'] = 'ignore';
                                    $update_jobArr['is_pro_read'] = 1;
                                    $update_jobArr['hire_status'] = 2;
                                    $update_jobArr['job_status'] = 4;
                                    $update_jobArr['rejected_by'] = 'user';
                                                
                                           //print_r($update_Arr);die;  
                                        $hire_status =  DB::table('assign_service_request')->where('user_id', $pro_user_id)->where('service_request_id',$request_id)->update($update_Arr);


                                        $job_status =  DB::table('assign_service_request')->where('user_id', '!=',$pro_user_id)->where('service_request_id',$request_id)->where('request_status','buy')->update($update_jobArr);

                                    $userDeviceHire=DB::table('user_devices')
                                        ->leftjoin('assign_service_request','assign_service_request.user_id','=','user_devices.user_id')
                                       // ->where('assign_service_request.user_id', $pro_user_id)
                                        ->where('assign_service_request.service_request_id',$request_id)
                                        ->where('user_devices.user_id',$pro_user_id)
                                        ->first();

                                        $device_id=$userDeviceHire->device_id;
                                        $device_type=$userDeviceHire->device_type;
                                        $title='¡Felicidades te tenemos noticias!';
                                        $message='Al usuario le encantó tu perfil y te contrató.';
                                        $userId=$pro_user_id;
                                        $prouserId=$user_id;
                                        $serviceId=$request_id;
                                        $senderid=0;
                                        $reciverid=0;
                                        $chattype=0;
                                        $notify_type='hire_pro';
                                        $senderName=isset($userEntity->username)?$userEntity->username:'';
                                        // if($userDeviceHire->device_type=='android')
                                        // {
                                            $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                                        // }
                                        // if($userDeviceHire->device_type=='ios')
                                        // {
                                        //     $this->iospush($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                                        // }
                                    $userDeviceReject=DB::table('user_devices')
                                        ->leftjoin('assign_service_request','assign_service_request.user_id','=','user_devices.user_id')
                                        ->where('assign_service_request.user_id','!=',$pro_user_id)
                                        ->where('assign_service_request.service_request_id',$request_id)
                                        ->where('user_devices.user_id',$pro_user_id)
                                        ->get();

                                    foreach ($userDeviceReject as $key => $userreject)
                                    {
                                       $device_id=$userreject->device_id;
                                        $device_type=$userreject->device_type;
                                        $title='Service rejected';
                                        $message='Your service request rejected.';
                                        $userId=$pro_user_id;
                                        $prouserId=$user_id;
                                        $serviceId=$request_id;
                                        $senderid=0;
                                        $reciverid=0;
                                        $chattype=0;
                                        $notify_type='service_rejected';
                                        $senderName=isset($userEntity->username)?$userEntity->username:'';
                                        // if($userreject->device_type=='android')
                                        // {
                                            $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                                        // }
                                        // if($userreject->device_type=='ios')
                                        // {
                                        //     $this->iospush($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                                        // }
                                    }
                                        $resultArray['status']='1';
                                        $resultArray['message']=trans('apimessage.hire_successfully');
                                        echo json_encode($resultArray); exit;       
                                }
                                else{
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('apimessage.hire_already_exist');
                                        echo json_encode($resultArray); exit;
                                }
                            }else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('not found.!');
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

    public function manageRequestStatus(Request $request)
    {
        $access_token=123456;
        $client_user_id = !empty($request->client_user_id) ? $request->client_user_id : '' ;
        $user_id = !empty($request->user_id) ? $request->user_id : '' ;
        $request_id = !empty($request->request_id) ? $request->request_id : '' ;
        $status_type = !empty($request->status_type) ? $request->status_type : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang); 
          $validator = Validator::make($request->all(), [
            'client_user_id' => 'required',
            //'session_key' => 'required',
            'user_id' => 'required',
            'request_id' => 'required',
            'status_type' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            }   
                if(!empty($user_id) && !empty($client_user_id) && !empty($request_id)) 
                {
                    //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);

                    if(1!=1)
                    {
                    //echo json_encode($check_auth); exit;
                    }

                    else
                    {
                         $userEntity = DB::table('users')
                        ->whereRaw("(active=1)")
                        ->whereRaw("(id = '".$client_user_id."' AND deleted_at IS null )")
                        ->first();
                        
                        if(!empty($userEntity))
                        {   
                            $assignuserlist = DB::table('assign_service_request')
                                ->where('assign_service_request.service_request_id', $request_id)
                                ->whereRaw("(user_id = '".$user_id."' AND deleted_at IS null )")
                                ->first();
                            if(!empty($assignuserlist))
                            {
                                if($status_type==5)
                                {
                                    $title='Servicio Terminado';
                                    $message='El profesional ha notificado que ha terminado su servicio, no olvides dejar tu calificación y comentario.';
                                     $msg=trans('apimessage.service_performed_successfully');
                                    $update_Arr['job_status']='5';
                                    $notify_type='service_performed';

                                    $userDevice=DB::table('user_devices')->where('user_id',$client_user_id)->first();
                                    $device_id=$userDevice->device_id;
                                    $device_type=$userDevice->device_type;
                                    $userId=$client_user_id;
                                    $prouserId=$user_id;
                                    $serviceId=$request_id;
                                    $senderid=0;
                                    $reciverid=0;
                                    $chattype=0;
                                  
                                    $senderName=isset($userEntity->username)?$userEntity->username:'';
                                    // if($userDevice->device_type=='android')
                                    // {
                                        $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                                    // }
                                    // if($userDevice->device_type=='ios')
                                    // {
                                    //     $this->iospush($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                                    // }
                                }
                                if($status_type==3)
                                {
                                    $msg=trans('apimessage.service_request_successfully');
                                    $update_Arr['job_status']='3';
                                }
                               
                                $read_status =  DB::table('assign_service_request')->where('user_id', $user_id)->where('service_request_id',$request_id)->update($update_Arr);

                                $resultArray['status']='1';
                                $resultArray['message']=$msg;
                                echo json_encode($resultArray); exit;
                            }
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
                }else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameter.');
                    echo json_encode($resultArray); exit;
                }
    }

    function postpushnotification($device_id,$title,$message,$userId=null,$prouserId=null,$serviceId=null,$senderid=null,$reciverid=null,$chattype=null,$senderName=null,$notify_type=null,$urlToken=null)
    {
        if(!empty($device_id))
        {
          $fields = array(
             'to' => $device_id,
              'data' =>array('title' => $title, 'message' => $message,'urlToken' => $urlToken,'userId'=>$userId,'prouserId'=>$prouserId,'serviceId'=>$serviceId, 'senderId'=>$senderid,'reciverId'=>$reciverid,'chatType'=>$chattype,'sendername'=>$senderName,'notify_type'=>$notify_type,'sound'=>'default'),
            'notification'=>array('title'=>$title,'body'=>$message,'sound'=>'default')
            );

            $response = $this->sendPushNotification($fields);
            return true;
        }

    }

    function sendPushNotification($fields = array(), $usertype=Null)
    {
         //echo '<pre>';print_r($fields); //exit;
          $API_ACCESS_KEY = 'AAAAY4m_HMI:APA91bFYmFGdtenBYXUG3JSgVpnpHeX0M-c2Mx27rqFOOAN1_B3VnIhIi_xzc2jTAHTjaITaHp0YlinWa6Vzb_TE7shnxErycGn9tyFYXpbPR4bOmrKoqggpVB4-sVSYO1X8FHEbn-24';

          $headers = array
          (
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
          );

          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
          // Execute post
          $result = curl_exec($ch);
          //print_r($result);//die;
          sleep(5);
          if ($result === FALSE) {
              die('Curl failed: ' . curl_error($ch));
          }
          // Close connection
          curl_close($ch);
          return $result;    
    }

    function iospush($device_id,$title,$message,$userId=null,$prouserId=null,$serviceId=null,$senderid=null,$reciverid=null,$chattype=null,$senderName=null,$notify_type=null,$urlToken = null)
    {
        if(!empty($device_id))
        {
            $fields = array(
            'to' => $device_id,
            'data' =>array('title' => $title, 'message' => $message,'urlToken' => $urlToken,'userId'=>$userId,'prouserId'=>$prouserId,'serviceId'=>$serviceId, 'senderId'=>$senderid,'reciverId'=>$reciverid,'chatType'=>$chattype,'sendername'=>$senderName,'notify_type'=>$notify_type),
            'notification'=>array('title'=>$title,'body'=>$message)
            );
            //echo '<pre>';print_r($fields); //exit;
            $API_ACCESS_KEY = 'AAAAY4m_HMI:APA91bFYmFGdtenBYXUG3JSgVpnpHeX0M-c2Mx27rqFOOAN1_B3VnIhIi_xzc2jTAHTjaITaHp0YlinWa6Vzb_TE7shnxErycGn9tyFYXpbPR4bOmrKoqggpVB4-sVSYO1X8FHEbn-24';

            $headers = array
            (
            'Authorization: key=' . $API_ACCESS_KEY,
            'Content-Type: application/json'
            );
            $ch = curl_init();
            curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
            curl_setopt( $ch,CURLOPT_POST, true );
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
            // Execute post
            $result = curl_exec($ch);
            //print_r($result);//die;
            sleep(5);
            if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
            return $result; 
        }
        // $tokenLength = strlen($device_id);
        // if (!empty($device_id)) {
        //     $apnsHost = 'gateway.push.apple.com';
        //     //$apnsHost = 'gateway.sandbox.push.apple.com';
        //    //$apnsCert = public_path('buskalock.pem');
        //     $apnsCert = public_path('pushcertBuskalo.pem');
        //     $sound = 'default';
        //     $apnsPort = 2195;
        //     $apnsPass = '';
        //     $token = $device_id;
        //     $payload['aps'] = array('title' => $title, 'alert' => $message, 'badge' => '1', 'sound' => 'default','notify_type'=>$notify_type,'userid'=>$userId,'prouserId'=>$prouserId,'serviceId'=>$serviceId,'reciverid'=>$reciverid,'chattype'=>$chattype,'senderName'=>$senderName);
        //     $output = json_encode($payload);
        //     $token = pack('H*', str_replace(' ', '', $token));
        //     $apnsMessage = chr(0) . chr(0) . chr(32) . $token . chr(0) . chr(strlen($output)) . $output;
        //     $streamContext = stream_context_create();
        //     stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
        //     stream_context_set_option($streamContext, 'ssl', 'passphrase', $apnsPass);
        //     $apns = @stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
        //     //fwrite($apns, $apnsMessage);
        //     $result = @fwrite($apns, $apnsMessage, strlen($apnsMessage)); //fwrite($apns, $apnsMessage);
        //     @fclose($apns);
        //     if (!$result) {
        //         $a = 'Message not delivered' . PHP_EOL;
        //     } else {
        //         $a = 'Message successfully delivered' . PHP_EOL;
        //     }
        //     $log = "User: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
        //         "Attempt: " . (!empty($result) ? 'Success' : 'Failed') . PHP_EOL .
        //         "Pass: " . $result . PHP_EOL .
        //         "apns: " . $apns . PHP_EOL .
        //         "apnsMessage: " . $apnsMessage . PHP_EOL .
        //         "device_id:" .$device_id . PHP_EOL .
        //         "Pass: " . $a . PHP_EOL .
        //         "-------------------------" . PHP_EOL;
        //       //  return $result; 
        //    // echo "<pre>"; print_r($apns); die;
        //     return $log;
        //     //Save string to log, use FILE_APPEND to append.
        //     //file_put_contents(content_url().'/log/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
        //     //exit;
        // }
    }

    public function readJobList(Request $request)
    {
        $access_token=123456;
        $user_id = !empty($request->user_id) ? $request->user_id : '' ;
        $request_id = !empty($request->request_id) ? $request->request_id : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang); 
          $validator = Validator::make($request->all(), [
            //'session_key' => 'required',
            'user_id' => 'required',
            'request_id' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            }   
                if(!empty($user_id) && !empty($request_id)) 
                {
                    //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);

                    if(1!=1)
                    {
                     //echo json_encode($check_auth); exit;
                    }
                    else
                    {
                        $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")
                            ->first();
                        if(!empty($userEntity))
                        {   
                            $readornot = DB::table('assign_service_request')
                                ->where('assign_service_request.service_request_id', $request_id)
                                ->whereRaw("(user_id = '".$user_id."' AND deleted_at IS null )")
                                ->first();
                            if(!empty($readornot))
                            {
                                if($readornot->job_status==1)
                                {
                                    $update_Arr['job_status']=2;
                                     $read_status =  DB::table('assign_service_request')->where('user_id', $user_id)->where('service_request_id',$request_id)->update($update_Arr);

                                    $resultArray['status']='1';
                                    $resultArray['message']=trans('apimessage.read_jobList_successfully');
                                    echo json_encode($resultArray); exit;
                                }
                                else
                                {
                                    $resultArray['status']='0';
                                    $resultArray['message']=trans('apimessage.read_jobList_already_exist');
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
    public function termAndCondition(Request $request)
    {
        $access_token=123456;
        $type = !empty($request->type) ? $request->type : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang); 
          $validator = Validator::make($request->all(), [
            'type' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 
            if($type=='company')
            {
                $userterms= DB::table('term_and_condition')
                            ->select('description_comp')
                            ->first();
                    $termsdata=$userterms->description_comp;
            }
            if($type=='pro')
            {
                $userterms = DB::table('term_and_condition')
                            ->select('description_cons')
                            ->first();
                    $termsdata=$userterms->description_cons;
            }
            if($type=='user')
            {
                $userterms = DB::table('term_and_condition')
                            ->select('description_user')
                            ->first();
                    $termsdata=$userterms->description_user;
            }
            if($type=='purchase')
            {
                $userterms = DB::table('term_and_condition')
                            ->select('description_purchase')
                            ->first();
                    $termsdata=$userterms->description_purchase;
            }

            if(isset($userterms) && !empty($userterms))
            {

                $resultArray['status']='1';
                $resultArray['message']=trans('apimessage.data_found_successfully');
                    $resultArray['data']=$termsdata;
                echo json_encode($resultArray); exit;
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
                echo json_encode($resultArray); exit;
            }
    }

    public function sendRatingReviewRequest(Request $request)
    {
        $access_token=123456;
        $user_id = !empty($request->user_id) ? $request->user_id : '' ;
        $client_user_id = !empty($request->client_user_id) ? $request->client_user_id : '' ;
        $request_id = !empty($request->request_id) ? $request->request_id : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang); 
          $validator = Validator::make($request->all(), [
            //'session_key' => 'required',
            'user_id' => 'required',
            'client_user_id' => 'required',
            'request_id' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 


                //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);

                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                     $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$client_user_id."' AND deleted_at IS null )")
                            ->first();
                    if(!empty($userEntity))
                    {
                        $prousercheck = DB::table('reviews')
                                ->where('reviews.request_id', $request_id)
                                ->whereRaw("(user_id = '".$client_user_id."' AND deleted_at IS null )")
                                ->first();
                        if(empty($prousercheck))
                        {
                            $userToken = DB::table('users')
                                            ->leftjoin('user_devices','user_devices.user_id','=','users.id')
                                            ->where('users.id',$client_user_id)
                                            ->select('user_devices.*', 'users.email','users.username','users.mobile_number')
                                            ->first();
                            $profession = DB::table('users')->where('users.id',$user_id)
                                            ->select('username','avatar_location','user_group_id')
                                            ->first();
                            $serviceprform=DB::table('assign_service_request')
                                            ->leftjoin('service_request','service_request.id','=','assign_service_request.service_request_id')
                                            ->leftjoin('services','services.id','=','service_request.service_id')
                                            ->where('assign_service_request.service_request_id',$request_id)
                                            ->where('assign_service_request.job_status',5)
                                            ->select('services.es_name','services.en_name','assign_service_request.updated_at as servicedateperform')
                                            ->first();

                                    if(empty($serviceprform))
                                    {
                                        $resultArray['status']='0';
                                        $resultArray['message']=trans('Service not completed');
                                        echo json_encode($resultArray); exit; 
                                    }
                             
                                           // echo '<pre>'; print_r($serviceprform);exit;
                                           // echo $userEntity->email;exit;
                                if($profession->user_group_id==3)
                                {
                                    $profesionImage=url('img/logo/logo.jpg');
                                    if(file_exists(public_path('img/contractor/profile/'.$profession->avatar_location)) && !empty($profession->avatar_location))
                                    {
                                        $profesionImage=url('img/contractor/profile/'.$profession->avatar_location);
                                    }
                                }
                                if($profession->user_group_id==4)
                                {   
                                    $profesionImage=url('img/logo/logo.jpg');
                                   if(file_exists(public_path('img/company/profile/'.$profession->avatar_location)) && !empty($profession->avatar_location))
                                    {
                                        $profesionImage=url('img/company/profile/'.$profession->avatar_location);
                                    }
                                }

                            $device_id=$userToken->device_id;
                            $device_type=$userToken->device_type;
                            $title='Reseña y calificación';
                            $message='Por favor déjanos tu comentario y calificación para que otros usuarios conozcan de tu experiencia con nosotros.';
                            $userid= $client_user_id;
                            $prouserId=$user_id; 
                            $serviceId= $request_id;
                            $senderid=0;
                            $reciverid=0;
                            $chattype=0;
                            $notify_type='rating_request';
                            $senderName=isset($userEntity->username)?$userEntity->username:'';
                            // if($userToken->device_type=='android')
                            // {
                                $this->postpushnotification($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                            // }
                            // if($userToken->device_type=='ios')
                            // {
                            //     $this->iospush($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                            // }
                            $user=$userToken->email;

                            if(isset($userEntity->avatar_location) && !empty($userEntity->avatar_location))
                            {
                                $userIcon= url('img/user/profile/'.$userEntity->avatar_location);
                            }
                            else
                            {
                                 $userIcon= url('img/logo/logo.jpg');
                            }
                            if($lang=='en')
                            {
                                $message='Thank You for your service rating in buskalo.';
                                $butttontext='Qualify';
                            }
                            else
                            {
                                $message=$profession->username.' ha solicitado que califiques y dejes un comentario sobre tu experiencia, esta información será de mucha ayuda para que futuros clientes la/lo conozcan.';
                                 $butttontext='Calificar';
                            }

                            $data = array(
                                    'username'=>$userToken->username,
                                    'receiver'=>$userEntity->email,
                                    'message'=>$message,
                                    'profephoto'=>$profesionImage,
                                    'profename'=>$profession->username,
                                    'servicename'=>isset($serviceprform->es_name)?$serviceprform->es_name:'',
                                    'servicedate'=>$serviceprform->servicedateperform,
                                    'buttontext'=>$butttontext,
                                    'actionurl'=>url('/user/rating_review?userid='.$client_user_id.'&prouserId='.$user_id.'&serviceId='.$serviceId),
                                    'logo'=>url('img/logo/logo-svg.png'),
                                    'footer_logo'=>url('img/logo/footer-logo.png'),
                                    'user_icon'=>$userIcon);

                              Mail::send('frontend.mail.rating_mail',  ['data' => $data], function($message) use ($user){
                                 $message->to($user)->subject(__('  Calificación y comentarios', ['app_name' => app_name()]));
                                 //$message->from(env('MAIL_FROM_NAME'));
                            });

                                $chatmessage='Solicitamos calificación y comentarios <br/>'.url("/user/rating_review?userid=".$client_user_id."&prouserId=".$user_id."&serviceId=".$serviceId); 
                                $insert['from_userid'] =$user_id;
                                $insert['to_userid'] =  $client_user_id;
                                $insert['message'] =$chatmessage;
                                $insert['is_read'] = 0;
                                $insert['type'] = 'Rating';
                                $insert['is_starred'] = 0;
                                $insert['created_at'] = Carbon::now();  
                                $lastId=DB::table('users_chat')->insertGetId($insert);

                            $resultArray['status']='1';
                            $resultArray['message']=trans('apimessage.request_has_been_send');
                            echo json_encode($resultArray); exit;
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.request_already_send');
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

    public function receivedPaymentByCreditCard(Request $request)
    {
        $access_token=123456;
        $user_id = !empty($request->user_id) ? $request->user_id : '' ;
        $subscription_id = !empty($request->subscription_id) ? $request->subscription_id : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $pay_amount = !empty($request->pay_amount) ? $request->pay_amount : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang); 
          $validator = Validator::make($request->all(), [
            //'session_key' => 'required',
            'user_id' => 'required',
            'pay_amount'=>'required',
            'subscription_id'=>'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 
                 //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);

                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                    $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")
                            ->first();
                    if(!empty($userEntity))
                    {
                        $url= url('payments/'.$subscription_id.'/Profesional/'.$user_id);
                        $resultArray['status']='1';
                        $resultArray['data']= $url;
                        $resultArray['message']=trans('apimessage.payment_success');
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

    public function intToString($data=null,$indent='')
    {
        $userdata= array();
        if ($data) {
            foreach ($data as $key=> $value)
            {
                if (is_array($value))
                {
                    $userdata[$key]= $this->intToString($value,$indent);
                }
                else
                {   
                    if(is_numeric($value))
                    {
                        $userdata[$key]=strval($value);
                    }
                    else
                    {
                        $userdata[$key]= $value;
                    }
                }
            }
        }
             return $userdata;
            exit;
    }

    public function paymentRequestBuyPro(Request $request)
    {
        $access_token=123456;
        $user_id = !empty($request->user_id) ? $request->user_id : '' ;
        $client_user_id = !empty($request->client_user_id) ? $request->client_user_id : '' ;
        $service_amount = !empty($request->service_amount) ? $request->service_amount : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $request_id = !empty($request->request_id) ? $request->request_id : '' ;
        $subtotal = !empty($request->subtotal) ? $request->subtotal : '' ;
        $iva = !empty($request->iva) ? $request->iva : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
        App::setLocale($lang); 
          $validator = Validator::make($request->all(), [
            //'session_key' => 'required',
            'user_id' => 'required',
            'client_user_id' => 'required',
            'service_amount' => 'required',
            'request_id' => 'required',
            'subtotal'=>'required',
            'iva'=>'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            } 


                //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);

                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                     $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$client_user_id."' AND deleted_at IS null )")
                            ->first();

                    $profesionName = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")
                            ->first();
                    if(!empty($userEntity))
                    {
                        $serviceRequest = DB::table('assign_service_request')
                                ->where('assign_service_request.service_request_id', $request_id)
                                ->whereRaw("(user_id = '".$user_id."' AND deleted_at IS null )")
                                ->first();

                        if(!empty($serviceRequest))
                        {

                            $prousercheck = DB::table('user_payment_history')
                                ->where('service_id', $request_id)
                                ->where('user_id',$client_user_id)
                                ->where('status','success')
                                ->first();
                            if(empty($prousercheck))
                            {
                                $userToken = DB::table('users')
                                                ->leftjoin('user_devices','user_devices.user_id','=','users.id')
                                                ->where('users.id',$client_user_id)
                                                ->select('user_devices.*', 'users.email','users.username','users.mobile_number','users.avatar_location','users.user_group_id')
                                                ->first();
                                       // print_r($userToken);exit;
                                    $image='';
                                    if($userToken->user_group_id==3)
                                    {
                                        $image= url('img/contractor/profile/'.$userToken->avatar_location);
                                    }
                                    if($userToken->user_group_id==4)
                                    {
                                        $image= url('img/company/profile/'.$userToken->avatar_location);
                                    }
                                    if($userToken->user_group_id==2)
                                    {
                                        $image= url('img/user/profile/'.$userToken->avatar_location);
                                    }

                                    $paymentinsert['user_id']=$client_user_id;
                                    $paymentinsert['pro_id']=$user_id;
                                    $paymentinsert['service_id']=$request_id;
                                    $paymentinsert['amount']=$service_amount;
                                    $paymentinsert['subtotal']=$subtotal;
                                    $paymentinsert['iva']=$iva;
                                    $paymentinsert['status']='pending';

                                    $prousercheck = DB::table('user_payment_history')
                                    ->where('service_id', $request_id)
                                    ->where('user_id',$client_user_id)
                                    ->first();
                                    if(empty($prousercheck))
                                    {
                                        DB::table('user_payment_history')->insert($paymentinsert);
                                    }
                                    else
                                    {
                                      DB::table('user_payment_history')
                                            ->where('service_id', $request_id)
                                            ->where('user_id',$client_user_id)
                                            ->update(['amount'=>$service_amount,'subtotal'=>$subtotal,'iva'=>$iva]); 
                                    }
                                    
                                $device_id=$userToken->device_id;
                                $device_type=$userToken->device_type;
                                $title='Solicitud de pago';
                                $message='Ahora puedes realizar un PAGO SEGURO a través de nuestra plataforma.';
                                $userid= $client_user_id;
                                $prouserId=$user_id; 
                                $serviceId= $request_id;
                                $senderid=0;
                                $reciverid=0;
                                $chattype=0;
                                $notify_type='payment_request';
                                $senderName=isset($userEntity->username)?$userEntity->username:'';
                                // if($userToken->device_type=='android')
                                // {
                                    $this->postpushnotification($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                                // }
                                // if($userToken->device_type=='ios')
                                // {
                                //     $this->iospush($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                                // }

                                    $chatmessage='Solicitamos el pago de $'.$service_amount.' <br/>'.url("/service/payment?userid=".$client_user_id."&prouserId=".$user_id."&serviceId=".$serviceId); 

                                    $insertchat['from_userid'] =$user_id;
                                    $insertchat['to_userid'] = $client_user_id ;
                                    $insertchat['message'] =$chatmessage;
                                    $insertchat['is_read'] = 0;
                                    $insertchat['type'] = 'Payment';
                                    $insertchat['is_starred'] = 0;
                                    $insertchat['created_at'] = Carbon::now();  
                                    DB::table('users_chat')->insert($insertchat);
                                    if($lang=='en')
                                    {
                                        $msg="Thank you for choosing Buskalo, the professional ".$profesionName->username." has sent you a payment request with the following detail:";
                                        $submsg='To make your payment click here';
                                    }else
                                    {
                                        $msg='Gracias por preferir Buskalo, el profesional ' .$profesionName->username.' te ha enviado una solicitud de pago con El servicioiguiente detalle:';
                                        $submsg='Para realizar su pago haca clic aqui';
                                    }

                                    $user=$userToken->email;
                                    $data = array(
                                        'username'=>$userToken->username,
                                        'receiver'=>$userToken->email,
                                        'message'=>$msg,
                                        'subtotal'=>number_format($subtotal,2),
                                        'iva'=>number_format($iva,2),
                                        'total'=>number_format($service_amount,2),
                                        'messagesub'=>$submsg,
                                        'actionurl'=>url('/service/payment?userid='.$client_user_id.'&prouserId='.$user_id.'&serviceId='.$serviceId),
                                        'logo'=>url('img/logo/logo-svg.png'),
                                        'footer_logo'=>url('img/logo/footer-logo.png'),
                                        'user_icon'=>$image
                                        );
                                  Mail::send('frontend.mail.payment_request',  ['data' => $data], function($message) use ($user, $profesionName){
                                     $message->to($user)->from(env('MAIL_FROM'), $profesionName->username)->subject(__('Solicitud de pago ', ['app_name' => app_name()]));
                                });

                                $resultArray['status']='1';
                                $resultArray['message']=trans('apimessage.payment_request');
                                echo json_encode($resultArray); exit;
                            }
                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.payment_paid');
                                echo json_encode($resultArray); exit; 
                            }  
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.invalid_service_request');
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



    public function readOpportunity(Request $request)
    {
        $access_token=123456;
        $user_id = !empty($request->user_id) ? $request-> user_id :'';
        $session_key = !empty($request->session_key) ? $request->session_key :'';
        $opportunity_id = !empty($request->opportunity_id) ? $request->opportunity_id :'';
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);
             $validator = Validator::make($request->all(), [
            //'session_key' => 'required',
            'user_id' => 'required',
            'opportunity_id' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            }
                //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);

                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                    $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")
                            ->first();
                    if(!empty($userEntity))
                    {
                        $serviceRequest = DB::table('assign_service_request')
                                ->where('id', $opportunity_id)
                                ->whereRaw("(user_id = '".$user_id."' AND deleted_at IS null )")
                                ->first();
                        if(!empty($serviceRequest))
                        {

                            $updateopp['is_read']=1;

                            DB::table('assign_service_request')
                                ->where('id',$opportunity_id)
                                ->where('user_id',$user_id)
                                ->update($updateopp);

                            $resultArray['status']='1';
                            $resultArray['message']=trans('apimessage.opportunity_read_successfully');
                            echo json_encode($resultArray); exit;
                        }
                        else
                        {
                            $resultArray['status']='0';
                            $resultArray['message']=trans('apimessage.invalid_service_request');
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
    public function refundRequest(Request $request)
    {
        $access_token=123456;
        $user_id = !empty($request->user_id) ? $request->user_id :'';
        $name = !empty($request->name) ? $request->name :'';
        $email = !empty($request->email) ? $request->email :'';
        $session_key = !empty($request->session_key) ? $request->session_key :'';
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);
             $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            //'session_key' => 'required',
            'refund_resion' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            }
                //$check_auth = $this->checkToken($access_token, $user_id, $session_key, $lang);

                if(1!=1)
                {
                 //echo json_encode($check_auth); exit;
                }
                else
                {
                    $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$user_id."' AND deleted_at IS null )")
                            ->first();
                    if(!empty($userEntity))
                    { 
                         $refund=array('user_id'=>$user_id,
                        'user_group_id'=>$userEntity->user_group_id,
                        'name'=>$request->name,
                        'email'=>$request->email,
                        'pro_amount'=>$request->amount,
                        'refund_resion'=>$request->refund_resion,
                        'pro_comany_name'=>$request->pro_company,
                        'payment_date'=>isset($request->payment_date)?$request->payment_date:'Y-m-d H:i:s',
                        'amount_total'=>$request->amount_total,
                        'transaction_id'=>$request->transaction_id,
                        'amount_parcial'=>$request->amount_parcial,
                        );
                         DB::table('refund_requests')->insert($refund); 
                         $email=$userEntity->email;
                          $image='';
                            if($userEntity->user_group_id==3)
                            {
                                $image= url('img/contractor/profile/'.$userEntity->avatar_location);
                            }
                            elseif($userEntity->user_group_id==4)
                            {
                                $image= url('img/company/profile/'.$userEntity->avatar_location);
                            }
                            else
                            {
                                 $image= url('img/user/profile/'.$userEntity->avatar_location);
                            }
                            $data = array(
                                'username'=>$userEntity->username,
                                'receiver'=>$userEntity->email,
                                'message'=>'Hemos recibido tu solicitud de reembolso.<br/>Pronto te contactaremos para verificar información o confirmar la devolución.',
                                'logo'=>url('img/logo/logo-svg.png'),
                                'footer_logo'=>url('img/logo/footer-logo.png'),
                                'user_icon'=>$image
                                );
                              Mail::send('frontend.mail.refund_request',  ['data' => $data], function($message) use ($email){
                                 $message->to($email)->subject(__('Solicitud de reembolso', ['app_name' => app_name()]));
                            });
                              
                            $resultArray['status']='1';
                            $resultArray['message']=trans('Solicitud de reembolso enviada con éxito.');
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
    public function refundFaq(Request $request)
    {

        $access_token=123456;
        $user_type  = !empty($request->user_type ) ? $request->user_type  :'';
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);
             $validator = Validator::make($request->all(), [
            'user_type' => 'required'
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            }
            $faqlist=array();
            if($request->user_type=='user')
            {
                $faqlist=DB::table('faq')->where('question_type',1)->where('status',1)->get();
            }
            if($request->user_type=='pro')
            {
                $faqlist=DB::table('faq')->where('question_type',2)->where('status',1)->get();
            }

            if(count($faqlist)>0)
            {
                $faqdata=array();
                foreach ($faqlist as $key => $value)
                {
                    $faqdata[$key]['question']=$value->question;
                    $faqdata[$key]['answer']=$value->answer;
                }

                    $resultArray['status']='1';
                    $resultArray['message']=trans('Lista de preguntas frecuentes con éxito');
                    $resultArray['data']=$faqdata;
                    echo json_encode($resultArray); exit;
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
                echo json_encode($resultArray); exit; 
            }
            
    }

    public function requestProfileUpdate(Request $request)
    {
        $access_token=123456;
        $email  = !empty($request->email ) ? $request->email  :'';
        $password  = !empty($request->password ) ? $request->password  :'';
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);
             $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            }

            $userCheck=DB::table('users')->where('email',$email)->first();
            if(!empty($userCheck))
            {
                DB::table('users')->where('id',$userCheck->id)->update(['password'=>Hash::make($password)]);
                $resultArray['status']='1';
                $resultArray['message']=trans('Contraseña actualizada correctamente. Inicie sesión para poder consultar los perfiles de PRO.');
                echo json_encode($resultArray); exit; 
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
                echo json_encode($resultArray); exit; 
            }
    }

    public function sendVerifyApprovelRequest(Request $request)
    {
        $access_token=123456;
        $userId  = !empty($request->user_id) ? $request->user_id:'';
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
            App::setLocale($lang);
             $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            ]);

            if($validator->fails())
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameters.');
                echo json_encode($resultArray); exit;      
            }

            $emailCheck=DB::table('users')->where('id',$userId)->first();
            if(!empty($emailCheck))
            {    $msg='';
                if($emailCheck->confirmed==0)
                {
                    $email= $emailCheck->email;
                    $objDemo = new \stdClass();
                    if($lang=='en')
                    {
                        $objDemo->message = 'Click here to verify your account.';
                    }
                    else
                    {
                        $objDemo->message = 'Pulse aquí para verificar su cuenta.';
                    }
                    // if($emailCheck->user_group_id==3)
                    // {
                    //     $userIcon=url('img/contractor/profile/'.$emailCheck->avatar_location);
                    // }
                    // else($emailCheck->user_group_id==4)
                    // {
                    //     $userIcon=url('img/company/profile/'.$emailCheck->avatar_location);
                    // }
                    $userIcon='';
                    $objDemo->link = url('/account/confirm/'.$emailCheck->confirmation_code);
                    $objDemo->sender = 'Buskalo';
                    $objDemo->receiver = $emailCheck->email;
                    $objDemo->level = '';
                    $objDemo->username = isset($emailCheck->username)?$emailCheck->username:'';
                    $objDemo->logo=url('img/logo/logo-svg.png');
                    $objDemo->footer_logo=url('img/logo/footer-logo.png');
                    $objDemo->user_icon=$userIcon;
                    Mail::to($email)->send(new NewUserVerify($objDemo));
                    $msg=trans('exceptions.frontend.auth.confirmation.created_confirm');
                }
                if($emailCheck->approval_status==2)
                {
                    DB::table('users')->where('id',$userId)->update(['approval_status'=>0]);
                }
                DB::table('users')->where('id',$userId)->update(['is_confirm_reg_step'=>1]);
                $resultArray['status']=1;
                $resultArray['message']=$msg;
                echo json_encode($resultArray); exit; 
            }
            else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.data_not_found');
                echo json_encode($resultArray); exit; 
            }
    }

    public function updateSql(Request $request)
    {

         if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
 echo $ip;
;exit;
        //$getdata= DB::table('users_chat')->whereIn('id',['37'])->delete();
        //$getdata1= DB::table('questions')->orderBy('id','desc')->get();
        $getdata= DB::table('assign_service_request')
		->join('service_request','service_request.id','=','assign_service_request.service_request_id')
			 ->whereRaw("(assign_service_request.user_id = 1868 AND assign_service_request.deleted_at IS null AND assign_service_request.request_status IS null AND service_request.deleted_at IS null)")
			 ->select('assign_service_request.*')
        //->leftjoin('user_devices','user_devices.user_id','=','assign_service_request.user_id')
        //->select('assign_service_request.*','user_devices.device_id','user_devices.user_id as userId','user_devices.device_type')
       // ->where('assign_service_request.notification','0')
        ->get();
        //$getdata1= DB::table('service_request')->where('id',55)->update(['deleted_at'=>null]);
       //$getdata= DB::table('users')->where('id',2084)->update(['confirmed'=>1]);
        $getdata= DB::table('users')->get();
         //$device_id='1fb79aad8d517bb96f8ee78369e544134771706cafd01f5fc8a65b7cf7be2d3e';
         $device_id='6cd23376e9e2a96701c4b9bbe49fd4eed8206a35d4ca0003833a66cab0baeb9f';
        $title= isset($request->title)?$request->title:'Chatapp';
        $message='Chat app new message send.';
        $result=$this->iospushtest($device_id,$title,$message);
       // print_r($result);exit;
            $resultArray['status']=1;
            $resultArray['data']= $result;
            return response()->json($resultArray); exit;
       
    }

    function iospushtest($device_id,$title,$message,$userId=null,$prouserId=null,$serviceId=null,$senderid=null,$reciverid=null,$chattype=null,$senderName=null,$notify_type=null,$urlToken = null,$badge=1)
    {
        $tokenLength = strlen($device_id);
        if(!empty($device_id))
        {

            $token =$device_id;
            if (!defined('CURL_HTTP_VERSION_2_0')) {
              define('CURL_HTTP_VERSION_2_0', 3);
            }
            // open connection 
            $http2ch = curl_init();
            curl_setopt($http2ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
            // send push
            $apple_cert = public_path('VOIP.pem');
           // print_r( $apple_cert);exit;
            $message = '{"aps":{"action":"message","title":"your_title","body":"your_message_body"}}';
            $http2_server = 'https://api.development.push.apple.com'; // or 'api.push.apple.com' if production
            $app_bundle_id = 'com.ASTHA.ChatApp.voip';
            $status = $this->sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token);
            echo $status;
            // close connection
            curl_close($http2ch);
            exit;
        }
    }

    function sendHTTP2Push($http2ch, $http2_server, $apple_cert, $app_bundle_id, $message, $token) 
    {
        // url (endpoint)
        $url = "{$http2_server}/3/device/{$token}";
        $cert = realpath($apple_cert);
        // headers
        $headers = array(
            "apns-topic: {$app_bundle_id}",
            "User-Agent: My Sender"
        );
        curl_setopt_array($http2ch, array(
            CURLOPT_URL => $url,
            CURLOPT_PORT => 443,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $message,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSLCERT => $cert,
            CURLOPT_HEADER => 1
        ));
        $result = curl_exec($http2ch);
        if ($result === FALSE) {
          throw new Exception("Curl failed: " .  curl_error($http2ch));
        }
        // get response
        $status = curl_getinfo($http2ch, CURLINFO_HTTP_CODE);
        if($status=="200")
        echo "SENT|NA";
        else
        echo "FAILED|$status";
    }

    public function deleteAccount(Request $request){
        $id = $request->id;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;

        App::setLocale($lang);
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            $resultArray['status'] = 0;
            $resultArray['message']=trans('apimessage.Invalid parameters.');
            return response()->json($resultArray); exit;
        }

        $userCheck=DB::table('users')->where('id',$id)->first();

        if(!empty($userCheck))
        {
            $update_Arr['deleted_at']= Carbon::now();
            DB::table('users')->where('id',$userCheck->id)->update($update_Arr);
            $resultArray['status']='1';
            $resultArray['message']=trans('Su cuenta ha sido eliminada con éxito. Esperamos verte pronto.');
            echo json_encode($resultArray); exit; 

        } else
        {
            $resultArray['status']='0';
            $resultArray['message']=trans('apimessage.data_not_found');
            echo json_encode($resultArray); exit; 
        }

    }


}
