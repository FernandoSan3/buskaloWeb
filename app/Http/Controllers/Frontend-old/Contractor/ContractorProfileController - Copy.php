<?php

namespace App\Http\Controllers\Frontend\Contractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;

/**
 * Class ProfileController.
 */
class ContractorProfileController extends Controller
{
    
    public function zdfas() {

        $identity_no = !empty($request->identity_no) ? $request->identity_no : '' ;
        $dob = !empty($request->dob) ? $request->dob : '' ;
        $address = !empty($request->address) ? $request->address : '' ;
        $office_address = !empty($request->office_address) ? $request->office_address : '' ;
        $other_address = !empty($request->other_address) ? $request->other_address : '' ;
        $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
        $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
        $office_number = !empty($request->office_number) ? $request->office_number : '' ;

        if() {

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

            $fields_data = json_decode($service_offered);
            $serviceOfferedData = json_decode( json_encode($fields_data), true);


            //Multiple
            $payment_method=!empty($request->payment_method) ? $request->payment_method : '' ;
            //$payment_method=1,2,3,4;

            $facebook_url=!empty($request->facebook_url) ? $request->facebook_url : '' ;
            $instagram_url=!empty($request->instagram_url) ? $request->instagram_url : '' ;
            $linkedin_url=!empty($request->linkedin_url) ? $request->linkedin_url : '' ;
            $twitter_url=!empty($request->twitter_url) ? $request->twitter_url : '' ;
            $other_url=!empty($request->other_url) ? $request->other_url : '' ;

            $profile_description=!empty($request->profile_description) ? $request->profile_description : '' ;


             $validator = Validator::make($request->all(), [
                            'userid' => 'required',
                            'mobile_number' => 'required',
                            'address' => 'required',
                            'payment_method' => 'required',
                            'service_offered'=>'required'
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
                                    //->whereRaw("(confirmed=1)")
                                    //->whereRaw("(is_verified=1)")
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

                                        ///End social


                                        //Service Offerd

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
                                        //End Service Offered

                                         //Payment Method

                                         $payment_methods=explode(',', $payment_method);

                                          $getData = DB::table('user_payment_methods')->select('id','user_id','payment_method_id','status')->whereRaw("(user_id = '".$userid."')")->get()->toArray();

                                          foreach($payment_methods as $value) 
                                         {
                                           
                                           if(!empty( $getData))
                                           {
                                            foreach ($getData as $oldvalue) 
                                            {
                                               if($oldvalue==$value)
                                               {

                                               }else
                                               {
                                                   
                                               }
                                             }
                                            }
                                            else
                                            {
                                                  $paym['user_id'] = $userid;
                                                    $paym['payment_method_id'] = $value; 
                                                    $paym['status'] =  1;
                                                    $paym['created_at'] = Carbon::now()->toDateTimeString();
                                                    $savepaym = DB::table('user_payment_methods')->insert($paym);
                                            }


                                         }

                                         //End Payment Method



                                         $users_count_var=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at')->where('id',$userEntity->id)->first();

                                           
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
                                        $datasocial['facebook_url'] = $socialData->facebook_url;
                                        $datasocial['insta_url'] = $socialData->instagram_url;
                                        $datasocial['linkedin_url'] = $socialData->linkedin_url;
                                        $datasocial['other_url'] = $socialData->other;
                                        $datasocial['created_at'] = $socialData->other;
                                        }

                                            if(!empty($datasocial))
                                             {
                                               $users_count_var->social_url[] = $datasocial;
                                             }
                                             else
                                             {
                                                $users_count_var->social_url[]="";
                                             }



                                          $servicesOffered=DB::table('services_offered')->select('id','user_id','service_id','zipcode','created_at')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($servicesOffered))
                                             {
                                               $users_count_var->services_offered[] = $servicesOffered;
                                             }
                                             else
                                             {
                                                $users_count_var->services_offered[]="";
                                             }


                                              $usersPayMethod=DB::table('user_payment_methods')->select('id','user_id','payment_method_id','status','created_at')->where('user_id',$userEntity->id)->whereRaw("(deleted_at IS null )")->get()->toArray();

                                             if(!empty($usersPayMethod))
                                             {
                                               $users_count_var->payment_methods[] = $usersPayMethod;
                                             }
                                             else
                                             {
                                                $users_count_var->payment_methods[]="";
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


    }



    public function index()
    {
              

        $userId= auth()->user()->id;
        $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();
        $userdata = DB::table('users')->whereRaw("(id = '".$userId."')")->first(); 
        $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first(); 

        return view('frontend.contractor.profile')->withUser($userdata)->withBonus($bonus)->withSocial($social);
    }


    public function updateBasic(Request $request)
    {
		$username = isset($request->username) && !empty($request->username) ? $request->username : '' ;
		$address = !empty($request->address) ? $request->address : '' ;
		$mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
		$landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
		$avatar_location = !empty($request->avatar_location) ? $request->avatar_location : '' ;
		$facebook_url = !empty($request->facebook_url) ? $request->facebook_url : '' ;
		$instagram_url = !empty($request->instagram_url) ? $request->instagram_url : '' ;
		$twitter_url = !empty($request->twitter_url) ? $request->twitter_url : '' ;
		$youtube_url = !empty($request->youtube_url) ? $request->youtube_url : '' ;
			
			$userid= auth()->user()->id;

				$validator = Validator::make($request->all(), [
                'username' => 'required',
                'mobile_number' => 'required',
                ]);

                if($validator->fails())
                {
                	return redirect()->route('frontend.contractor.profile')->withFlashDanger(__('Invalid parameter.'));
                }

                  	$userEntity = DB::table('users')
                    ->whereRaw("(active=1)")->whereRaw("(confirmed=1)")->whereRaw("(is_verified=1)")
                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();  

                    if(!empty($userEntity))
                    {
                    	$profile = $userEntity->avatar_location;

                        if(isset($_FILES['avatar_location']['name']) && !empty($_FILES['avatar_location']['name']))
                            {
                                $extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
                                $filename = $userid.'.'.$extq;

                                $ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
                                
                                $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/contractor/profile/'.$filename);
                               
                                 $profile = $filename;
                            }



                        $userData['avatar_location'] =  $profile;
                        $userData['username'] =  $username;
                        $userData['mobile_number'] =  $mobile_number;
                        $userData['landline_number'] =  $landline_number;
                        $userData['address'] =  $address;
                        DB::table('users')->where('id',$userEntity->id)->update($userData);


						$socialNetw['facebook_url'] = $facebook_url;
						$socialNetw['instagram_url'] = $instagram_url;
						$socialNetw['twitter_url'] = $twitter_url;
						$socialNetw['youtube_url'] = $youtube_url;
						$socialNetw['updated_at'] = Carbon::now()->toDateTimeString();
						$socialNetw['user_id'] = $userEntity->id;
						$socialNetw['created_at'] = Carbon::now()->toDateTimeString();
						DB::table('social_networks')->update($socialNetw);

						return redirect()->route('frontend.contractor.profile')->withFlashSuccess(__('Profile Updated Successfully.!'));
                    }
                    else
                    {
                      return redirect()->route('frontend.contractor.profile')->withFlashDanger(__('Invalid user.'));

                    }

    		}

    		public function myProfile()
    		{
 				return view('frontend.contractor.my_profile');
    		}

    		public function myDocuments()
    		{
    			 return view('frontend.contractor.documentation');
    		}





}
