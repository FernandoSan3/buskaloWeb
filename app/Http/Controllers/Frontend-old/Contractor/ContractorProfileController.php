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
    public function index()
    {
      
        $userId= auth()->user()->id;
        $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();
        $userdata = DB::table('users')->whereRaw("(id = '".$userId."')")->first(); 
        $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first(); 

        return view('frontend.contractor.profile')->withUser($userdata)->withBonus($bonus)->withSocial($social);
    }


     // public function updateProfilePicture(Request $request)
     //        {
     //            $avatar_location = !empty($request->avatar_location) ? $request->avatar_location : '' ;
     //            $userid= auth()->user()->id;

     //            $userEntity = DB::table('users')
     //                ->whereRaw("(active=1)")->whereRaw("(confirmed=1)")->whereRaw("(is_verified=1)")
     //                ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();  

     //                if(!empty($userEntity))
     //                {
     //                    $profile = $userEntity->avatar_location;

     //                    if(isset($_FILES['avatar_location']['name']) && !empty($_FILES['avatar_location']['name']))
     //                        {
     //                            $extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
     //                            $filename = $userid.'.'.$extq;

     //                            $ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
                                
     //                            $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/contractor/profile/'.$filename);
                               
     //                             $profile = $filename;
     //                        }

     //                    $userData['avatar_location'] =  $profile;
     //                    $userData['updated_at'] = Carbon::now()->toDateTimeString();
     //                    DB::table('users')->where('id',$userEntity->id)->update($userData);

     //                   return redirect()->route('frontend.contractor.my-profile')->withFlashSuccess(__('Profile Picture Updated Successfully.!'));
     //                }
     //                else
     //                {
     //                  return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid user.'));

     //                }
     //        }



    public function updateProfilePicture(Request $request)
    {
         
              $image = $request->input('image');
              $userId = $request->input('userid');

                $folderPath = public_path() . '/img/contractor/profile/';
                $image_parts = explode(";base64,", $request->input('image'));
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);


                $f = finfo_open();
                $mime_type = finfo_buffer($f, $image_base64, FILEINFO_MIME_TYPE);

                $avtar =  $userId . '.png';
                $file = $folderPath . $avtar;
           
                file_put_contents($file, $image_base64);

                $userData['avatar_location'] =  $avtar;
                $userData['updated_at'] = Carbon::now()->toDateTimeString();
                DB::table('users')->where('id',$userId)->update($userData);


                $getUserProfile = DB::table('users')->whereRaw("(id = '".$userId."' AND deleted_at IS null )")->first();

                  $profile=url('/img/contractor/profile/'.$getUserProfile->avatar_location);


                  //$profile='<img id="thumbnil" style="width:10%;height: 10%;" src="'.$profile.'" class="img-fluid" alt="image">';


                  return response()->json(['success' => true,'profile'=> $profile]);

           //return response()->json(['success' => true]); 
    }



    public function updateBasic(Request $request)
    {

      $username = isset($request->username) && !empty($request->username) ? $request->username : '' ;
      $identity_no = isset($request->identity_no) && !empty($request->identity_no) ? $request->identity_no : '' ;
      $address = !empty($request->address) ? $request->address : '' ;
      $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
      $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
      $avatar_location = !empty($request->avatar_location) ? $request->avatar_location : '' ;
      $profile_title = !empty($request->profile_title) ? $request->profile_title : '' ;
      $profile_description = !empty($request->profile_description) ? $request->profile_description : '' ;


      $facebook_url = !empty($request->facebook_url) ? $request->facebook_url : '' ;
      $instagram_url = !empty($request->instagram_url) ? $request->instagram_url : '' ;
      $twitter_url = !empty($request->twitter_url) ? $request->twitter_url : '' ;
      $linkedin_url = !empty($request->linkedin_url) ? $request->linkedin_url : '' ;
      $other_url = !empty($request->other_url) ? $request->other_url : '' ;
      $total_employee = !empty($request->total_employee) ? $request->total_employee : '' ;

      $userid= auth()->user()->id;

                // $validator = Validator::make($request->all(), [
                //'userid' => 'required',
                //]);

                // if($validator->fails())
                //{
                //return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid parameter.'));
                //}

                $mobileexist = DB::table('users')->whereRaw("(mobile_number = '".$mobile_number."' AND deleted_at IS null )")->where('id', '!=' , $userid)->first();

                            if(!empty($mobileexist))
                            {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Mobile Number Already Exist.'));
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
                         $userData['username'] =  !empty($username) ? $username : $userEntity->username;
                         $userData['identity_no'] =  !empty($identity_no) ? $identity_no : $userEntity->identity_no;
                         $userData['profile_title'] =  !empty($profile_title) ? $profile_title : $userEntity->profile_title;
                        $userData['profile_description'] =  !empty($profile_description) ? $profile_description : $userEntity->profile_description;
                        $userData['mobile_number'] =  !empty($mobile_number) ? $mobile_number : $userEntity->mobile_number;
                        $userData['landline_number'] =  !empty($landline_number) ? $landline_number : $userEntity->landline_number;
                        $userData['address'] = !empty($address) ? $address : $userEntity->address;
                        $userData['total_employee'] = !empty($total_employee) ? $total_employee : $userEntity->total_employee;
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
                                ///End social;

            return redirect()->route('frontend.contractor.my-profile')->withFlashSuccess(__('Profile Updated Successfully.!'));
                    }
                    else
                    {
                      return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid user.'));

                    }

        }

        public function myProfile()
            {
                $userId= auth()->user()->id;
                $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();

                $userdata=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userId)->first();

                $totalEmployee=DB::table('workers')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();


                $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first();

                $payment_methods = DB::table('payment_methods')->where('deleted_at',NULL)->get();

                $user_payment_method = DB::table('user_payment_methods')->where('deleted_at',NULL)->where('user_id',$userId)->select('payment_method_id')->get()->toArray();

                $payment_method_id=json_decode( json_encode($user_payment_method), true);
                $payment_string="";
                foreach ($payment_method_id as $key => $value) 
                {
                    $payment_string .= ',' .$value['payment_method_id'];
                }
                     $payment_string = substr($payment_string,1);

                     $payment_string = explode(',',$payment_string);
                
                if($payment_string) 
                {
                    $payment_method_id = $payment_string;
                } else 
                {
                    $payment_string;
                }

                $services = DB::table('services')->where('deleted_at',NULL)->get(); 
                $services_offered = DB::table('services_offered')->join('services','services_offered.service_id','=','services.id')->select('services.*','services_offered.service_id','services_offered.sub_service_id')->where('services_offered.user_id',$userId)->where('services_offered.deleted_at',NULL)->
                groupBy('services_offered.service_id')->get(); 

                $serice_ids = array();

                if(isset($services_offered) && !empty($services_offered)) 
                {
                    foreach ($services_offered as $key => $value) 
                    {
                        array_push($serice_ids,$value->service_id);
                    }
                }


                 $users_services_area = DB::table('users_services_area')->join('provinces','users_services_area.province_id','=','provinces.id')->select('provinces.*','users_services_area.province_id')->where('users_services_area.user_id',$userId)->where('users_services_area.deleted_at',NULL)->
                groupBy('users_services_area.province_id')->get(); 
                $serviceArea_ids = array();

                if(isset($users_services_area) && !empty($users_services_area)) 
                {
                    foreach ($users_services_area as $key => $value) 
                    {
                        array_push($serviceArea_ids,$value->province_id);
                    }
                }


        /*User offered services Area with citeis*/

          $uServices_area = DB::table('users_services_area')->join('provinces','users_services_area.province_id','=','provinces.id')->select('provinces.*','users_services_area.province_id')->where('users_services_area.user_id',$userId)->where('users_services_area.deleted_at',NULL)->groupBy('users_services_area.province_id')->get(); 

            $sr1=array();
            $allUserAreaData=array();

            if(!empty($uServices_area))
            {
                foreach ($uServices_area as $services_area) 
                {
                    $sr1['id']=isset($services_area) && !empty($services_area->id) ? $services_area->id : '' ;
                    $sr1['name']=isset($services_area) && !empty($services_area->name) ? $services_area->name : '' ;

                       $getcity = DB::table('users_services_area')->join('cities','users_services_area.city_id','=','cities.id')->select('cities.*','users_services_area.city_id','users_services_area.province_id')
                       ->where('users_services_area.user_id',$userId)
                       ->where('users_services_area.province_id',$services_area->province_id)
                       ->where('users_services_area.deleted_at',NULL)->get(); 

                    $proCityData=array();

                    foreach ($getcity as $cities) 
                    {
                        $sr2['province_id']=isset($cities) && !empty($cities->province_id) ? $cities->province_id : '' ;
                        $sr2['city_id']=isset($cities) && !empty($cities->id) ? $cities->id : '' ;
                        $sr2['name']=isset($cities) && !empty($cities->name) ? $cities->name : '' ;

                        array_push($proCityData, $sr2);
                    }

                    $sr1['cities']=$proCityData ;
                    array_push($allUserAreaData, $sr1);

                }
            }


             /*User offered services Area with citeis*/

                $provinces=DB::table('provinces')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                $cities=DB::table('cities')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                ///////////////////////Gallery/////////////////

                  $allImages=DB::table('users_images_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();
                  $allImages2=array();
                  $allVideo2=array();
                 if(!empty($allImages))
                 {
                    $path='/img/contractor/gallery/images/'.$userId.'/';
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
                    
                    $userdata->gallery['images'] = $allImages2;
                 }
                 else
                 {
                    $userdata->gallery['images']=[];
                 }


                 $allVideos=DB::table('users_videos_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allVideos))
                 {
                   $path='/img/contractor/gallery/videos/'.$userId.'/';
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
                   $userdata->gallery['videos'] = $allVideo2;
                 }
                 else
                 {
                    $userdata->gallery['videos']=[];
                 }

             ///////////////////////Gallery/////////////////


            ///////////////////////users Documents/////////////////


             $allCertificates=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','0')->whereRaw("(deleted_at IS null )")->get()->toArray();
                                                          
                $certi2=array();
                  $policeR2=array();
                 if(!empty($allCertificates))
                 {
                    $path='/img/contractor/certifications/'.$userId.'/';
                    foreach ($allCertificates as $key => $value) 
                    {
                        $allImages1['id']=$value->id;
                        $allImages1['user_id']=$value->user_id;
                        $allImages1['file_name']=url($path.$value->file_name);
                        $allImages1['file_type']=$value->file_type;
                        $allImages1['status']=$value->status;
                        $allImages1['created_at']=$value->created_at;
                        array_push($certi2, $allImages1);
                    }
                    
                    $userdata->cetifications['certification_courses'] = $certi2;
                 }
                 else
                 {
                    $userdata->cetifications['certification_courses']=[];
                 }


                 $allPoliceRec=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allPoliceRec))
                 {
                   $path='/img/contractor/police_records/'.$userId.'/';
                    foreach ($allPoliceRec as $key => $value) 
                    {
                        $allVideo1['id']=$value->id;
                        $allVideo1['user_id']=$value->user_id;
                        $allVideo1['file_name']=url($path.$value->file_name);
                        $allVideo1['file_type']=$value->file_type;
                        $allVideo1['status']=$value->status;
                        $allVideo1['created_at']=$value->created_at;
                        array_push($policeR2, $allVideo1);
                    }
                   $userdata->cetifications['police_records'] = $policeR2;
                 }
                 else
                 {
                    $userdata->cetifications['police_records']=[];
                 }

            ///////////////////////users Documents/////////////////


            $arr=array();
            $allData=array();
            if(!empty($provinces))
            {
                foreach ($provinces as $provience) 
                {
                    $arr['id']=isset($provience) && !empty($provience->id) ? $provience->id : '' ;
                    $arr['name']=isset($provience) && !empty($provience->name) ? $provience->name : '' ;

                    $city=DB::table('cities')->where('status','1')->whereRaw("(province_id = '".$provience->id."' AND deleted_at IS null )")->orderBy('name','Asc')->get();

                    $options=array();

                    foreach ($city as $cit) 
                    {
                        $arr2['province_id']=isset($cit) && !empty($cit->province_id) ? $cit->province_id : '' ;
                        $arr2['city_id']=isset($cit) && !empty($cit->id) ? $cit->id : '' ;
                        $arr2['name']=isset($cit) && !empty($cit->name) ? $cit->name : '' ;

                        array_push($options, $arr2);
                    }

                    $arr['cities']=$options ;
                    array_push($allData, $arr);

                }
            }


                $sub_services = DB::table('sub_services')->where('status','1')
                    ->where('services_id','!=',NULL)->whereRaw("(deleted_at IS null )")
                    ->get()->toArray();

                $first_arr = array();
                $combinedData = array();
                if(!empty($services))
                {
                    foreach ($services as $service) 
                    {
                        $first_arr['id']=isset($service) && !empty($service->id) ? $service->id : '' ;
                        $first_arr['name']=isset($service) && !empty($service->es_name) ? $service->es_name : '' ;

                        $subservices=DB::table('sub_services')->where('status','1')->whereRaw("(services_id = '".$service->id."' AND deleted_at IS null )")->get();

                        $options=array();

                        foreach ($subservices as $subservice) 
                        {
                            $arr_new2['service_id']=isset($subservice) && !empty($subservice->services_id) ? $subservice->services_id : '' ;
                            $arr_new2['sub_service_id']=isset($subservice) && !empty($subservice->id) ? $subservice->id : '' ;
                            $arr_new2['name']=isset($subservice) && !empty($subservice->es_name) ? $subservice->es_name : '' ;

                            array_push($options, $arr_new2);
                        }

                        $first_arr['subservices']=$options ;
                        array_push($combinedData, $first_arr);

                    }
                } 

                 //echo "<pre>"; print_r($allUserAreaData);die;
   
                return view('frontend.contractor.my_profile')
                ->withUser($userdata)
                ->withTotalEmployee($totalEmployee)
                ->withBonus($bonus)
                ->withSocial($social)
                ->withProvinces($provinces)
                ->withCities($cities)
                ->withServices($services)
                ->withServiceArea($users_services_area)
                 ->withAllUserAreaData($allUserAreaData)
                ->withServiceOffered($services_offered)
                ->withServiceIds($serice_ids)
                ->withServiceAreaIds($serviceArea_ids)
                ->withPaymentMethods($payment_methods)
                ->withPaymentMethodId($payment_method_id)
                ->withMixdata($allData)
                ->withCombineddata($combinedData);
            }


            /***********My Perfil***************/


              public function miPerfil()
            {
                $userId= auth()->user()->id;
                $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();

                $userdata=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userId)->first();

                $totalEmployee=DB::table('workers')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();


                $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first();

                $payment_methods = DB::table('payment_methods')->where('deleted_at',NULL)->get();

                $user_payment_method = DB::table('user_payment_methods')->where('deleted_at',NULL)->where('user_id',$userId)->select('payment_method_id')->get()->toArray();

                $payment_method_id=json_decode( json_encode($user_payment_method), true);
                $payment_string="";
                foreach ($payment_method_id as $key => $value) 
                {
                    $payment_string .= ',' .$value['payment_method_id'];
                }
                     $payment_string = substr($payment_string,1);

                     $payment_string = explode(',',$payment_string);
                
                if($payment_string) 
                {
                    $payment_method_id = $payment_string;
                } else 
                {
                    $payment_string;
                }


                $services = DB::table('services')->where('deleted_at',NULL)->get(); 
                $services_offered = DB::table('services_offered')->join('services','services_offered.service_id','=','services.id')->select('services.*','services_offered.service_id','services_offered.sub_service_id')->where('services_offered.user_id',$userId)->where('services_offered.deleted_at',NULL)->
                groupBy('services_offered.service_id')->get(); 
                $serice_ids = array();

                if(isset($services_offered) && !empty($services_offered)) 
                {
                    foreach ($services_offered as $key => $value) 
                    {
                        array_push($serice_ids,$value->service_id);
                    }
                }

                $provinces=DB::table('provinces')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                $cities=DB::table('cities')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                ///////////////////////Gallery/////////////////

                  $allImages=DB::table('users_images_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();
                  $allImages2=array();
                  $allVideo2=array();
                 if(!empty($allImages))
                 {
                    $path='/img/contractor/gallery/images/'.$userId.'/';
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
                    
                    $userdata->gallery['images'] = $allImages2;
                 }
                 else
                 {
                    $userdata->gallery['images']=[];
                 }


                 $allVideos=DB::table('users_videos_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allVideos))
                 {
                   $path='/img/contractor/gallery/videos/'.$userId.'/';
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
                   $userdata->gallery['videos'] = $allVideo2;
                 }
                 else
                 {
                    $userdata->gallery['videos']=[];
                 }


                 ///////////////////////users Documents/////////////////


             $allCertificates=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','0')->whereRaw("(deleted_at IS null )")->get()->toArray();
                                                          
                $certi2=array();
                  $policeR2=array();
                 if(!empty($allCertificates))
                 {
                    $path='/img/contractor/certifications/'.$userId.'/';
                    foreach ($allCertificates as $key => $value) 
                    {
                        $allImages1['id']=$value->id;
                        $allImages1['user_id']=$value->user_id;
                        $allImages1['file_name']=url($path.$value->file_name);
                        $allImages1['file_type']=$value->file_type;
                        $allImages1['status']=$value->status;
                        $allImages1['created_at']=$value->created_at;
                        array_push($certi2, $allImages1);
                    }
                    
                    $userdata->cetifications['certification_courses'] = $certi2;
                 }
                 else
                 {
                    $userdata->cetifications['certification_courses']=[];
                 }


                 $allPoliceRec=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allPoliceRec))
                 {
                   $path='/img/contractor/police_records/'.$userId.'/';
                    foreach ($allPoliceRec as $key => $value) 
                    {
                        $allVideo1['id']=$value->id;
                        $allVideo1['user_id']=$value->user_id;
                        $allVideo1['file_name']=url($path.$value->file_name);
                        $allVideo1['file_type']=$value->file_type;
                        $allVideo1['status']=$value->status;
                        $allVideo1['created_at']=$value->created_at;
                        array_push($policeR2, $allVideo1);
                    }
                   $userdata->cetifications['police_records'] = $policeR2;
                 }
                 else
                 {
                    $userdata->cetifications['police_records']=[];
                 }

            ///////////////////////users Documents/////////////////

             ///////////////////////Gallery/////////////////


                   /*User offered services Area with citeis*/

          $uServices_area = DB::table('users_services_area')->join('provinces','users_services_area.province_id','=','provinces.id')->select('provinces.*','users_services_area.province_id')->where('users_services_area.user_id',$userId)->where('users_services_area.deleted_at',NULL)->groupBy('users_services_area.province_id')->get(); 

            $sr1=array();
            $allUserAreaData=array();

            if(!empty($uServices_area))
            {
                foreach ($uServices_area as $services_area) 
                {
                    $sr1['id']=isset($services_area) && !empty($services_area->id) ? $services_area->id : '' ;
                    $sr1['name']=isset($services_area) && !empty($services_area->name) ? $services_area->name : '' ;

                       $getcity = DB::table('users_services_area')->join('cities','users_services_area.city_id','=','cities.id')->select('cities.*','users_services_area.city_id','users_services_area.province_id')
                       ->where('users_services_area.user_id',$userId)
                       ->where('users_services_area.province_id',$services_area->province_id)
                       ->where('users_services_area.deleted_at',NULL)->get(); 

                    $proCityData=array();

                    foreach ($getcity as $cities) 
                    {
                        $sr2['province_id']=isset($cities) && !empty($cities->province_id) ? $cities->province_id : '' ;
                        $sr2['city_id']=isset($cities) && !empty($cities->id) ? $cities->id : '' ;
                        $sr2['name']=isset($cities) && !empty($cities->name) ? $cities->name : '' ;

                        array_push($proCityData, $sr2);
                    }

                    $sr1['cities']=$proCityData ;
                    array_push($allUserAreaData, $sr1);

                }
            }


             /*User offered services Area with citeis*/


            ///////////////////////users Documents/////////////////


             $allCertificates=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','0')->whereRaw("(deleted_at IS null )")->get()->toArray();
                                                          
                $certi2=array();
                  $policeR2=array();
                 if(!empty($allCertificates))
                 {
                    $path='/img/contractor/certifications/'.$userId.'/';
                    foreach ($allCertificates as $key => $value) 
                    {
                        $allImages1['id']=$value->id;
                        $allImages1['user_id']=$value->user_id;
                        $allImages1['file_name']=url($path.$value->file_name);
                        $allImages1['file_type']=$value->file_type;
                        $allImages1['status']=$value->status;
                        $allImages1['created_at']=$value->created_at;
                        array_push($certi2, $allImages1);
                    }
                    
                    $userdata->cetifications['certification_courses'] = $certi2;
                 }
                 else
                 {
                    $userdata->cetifications['certification_courses']=[];
                 }


                 $allPoliceRec=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allPoliceRec))
                 {
                   $path='/img/contractor/police_records/'.$userId.'/';
                    foreach ($allPoliceRec as $key => $value) 
                    {
                        $allVideo1['id']=$value->id;
                        $allVideo1['user_id']=$value->user_id;
                        $allVideo1['file_name']=url($path.$value->file_name);
                        $allVideo1['file_type']=$value->file_type;
                        $allVideo1['status']=$value->status;
                        $allVideo1['created_at']=$value->created_at;
                        array_push($policeR2, $allVideo1);
                    }
                   $userdata->cetifications['police_records'] = $policeR2;
                 }
                 else
                 {
                    $userdata->cetifications['police_records']=[];
                 }

            ///////////////////////users Documents/////////////////


            $arr=array();
            $allData=array();
            if(!empty($provinces))
            {
                foreach ($provinces as $provience) 
                {
                    $arr['id']=isset($provience) && !empty($provience->id) ? $provience->id : '' ;
                    $arr['name']=isset($provience) && !empty($provience->name) ? $provience->name : '' ;

                    $city=DB::table('cities')->where('status','1')->whereRaw("(province_id = '".$provience->id."' AND deleted_at IS null )")->orderBy('name','Asc')->get();

                    $options=array();

                    foreach ($city as $cit) 
                    {
                        $arr2['province_id']=isset($cit) && !empty($cit->province_id) ? $cit->province_id : '' ;
                        $arr2['city_id']=isset($cit) && !empty($cit->id) ? $cit->id : '' ;
                        $arr2['name']=isset($cit) && !empty($cit->name) ? $cit->name : '' ;

                        array_push($options, $arr2);
                    }

                    $arr['cities']=$options ;
                    array_push($allData, $arr);

                }
            }


                $sub_services = DB::table('sub_services')->where('status','1')
                    ->where('services_id','!=',NULL)->whereRaw("(deleted_at IS null )")
                    ->get()->toArray();

                $first_arr = array();
                $combinedData = array();
                if(!empty($services))
                {
                    foreach ($services as $service) 
                    {
                        $first_arr['id']=isset($service) && !empty($service->id) ? $service->id : '' ;
                        $first_arr['name']=isset($service) && !empty($service->es_name) ? $service->es_name : '' ;

                        $subservices=DB::table('sub_services')->where('status','1')->whereRaw("(services_id = '".$service->id."' AND deleted_at IS null )")->get();

                        $options=array();

                        foreach ($subservices as $subservice) 
                        {
                            $arr_new2['service_id']=isset($subservice) && !empty($subservice->services_id) ? $subservice->services_id : '' ;
                            $arr_new2['sub_service_id']=isset($subservice) && !empty($subservice->id) ? $subservice->id : '' ;
                            $arr_new2['name']=isset($subservice) && !empty($subservice->es_name) ? $subservice->es_name : '' ;

                            array_push($options, $arr_new2);
                        }

                        $first_arr['subservices']=$options ;
                        array_push($combinedData, $first_arr);

                    }
                } 

                 $users_services_area = DB::table('users_services_area')->join('provinces','users_services_area.province_id','=','provinces.id')->select('provinces.*','users_services_area.province_id')->where('users_services_area.user_id',$userId)->where('users_services_area.deleted_at',NULL)->
                groupBy('users_services_area.province_id')->get(); 
                $serviceArea_ids = array();

                if(isset($users_services_area) && !empty($users_services_area)) 
                {
                    foreach ($users_services_area as $key => $value) 
                    {
                        array_push($serviceArea_ids,$value->name);
                    }
                }


                

                return view('frontend.contractor.mi_perfil')
                ->withUser($userdata)
                ->withTotalEmployee($totalEmployee)
                ->withBonus($bonus)
                ->withSocial($social)
                ->withProvinces($provinces)
                ->withCities($cities)
                ->withServices($services)
                ->withServiceOffered($services_offered)
                ->withAllUserAreaData($allUserAreaData)
                ->withServiceIds($serice_ids)
                ->withServiceAreaIds($serviceArea_ids)
                ->withPaymentMethods($payment_methods)
                ->withPaymentMethodId($payment_method_id)
                ->withMixdata($allData)
                ->withCombineddata($combinedData);
            }


            /**********************/

            public function updateBasicInfo(Request $request) {
                
                $userId= auth()->user()->id;               
                $userEntity = DB::table('users')
                ->whereRaw("(active=1)")
                ->whereRaw("(id = '".$userId."' AND deleted_at IS null )")
                ->first();  


                if(!empty($userEntity))
                {

                         $mobileexist = DB::table('users')->whereRaw("(mobile_number = '".$userEntity->mobile_number."' AND deleted_at IS null )")->where('id', '!=' , $userId)->first();

                            if(!empty($mobileexist))
                            {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Mobile Number Already Exist.'));exit;
                            }

                        $username = !empty($request->username) ? $request->username : '' ;
                        $profile_title = !empty($request->profile_title) ? $request->profile_title : '' ;
                        $identity_no = !empty($request->identity_no) ? $request->identity_no : '' ;
                        $dob = !empty($request->dob) ? $request->dob : '' ;
                        $address = !empty($request->address) ? $request->address : '' ;
                        $office_address = !empty($request->office_address) ? $request->office_address : '' ;
                        $other_address = !empty($request->other_address) ? $request->other_address : '' ;
                        $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                        $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
                        $office_number = !empty($request->office_number) ? $request->office_number : '' ;
                        $total_employee = !empty($request->total_employee) ? $request->total_employee : '' ;
                 
                      $userData['username'] =  !empty($username) ? $username : $userEntity->username;
                      $userData['profile_title'] =  !empty($profile_title) ? $profile_title : $userEntity->profile_title;
                      $userData['identity_no'] =  !empty($identity_no) ? $identity_no : $userEntity->identity_no;
                      $userData['dob'] =  !empty($dob) ? $dob : $userEntity->dob;
                      $userData['address'] =  !empty($address) ? $address : $userEntity->address;
                      $userData['office_address'] =  !empty($office_address) ? $office_address : $userEntity->office_address;
                      $userData['other_address'] =  !empty($other_address) ? $other_address : $userEntity->other_address;
                      $userData['mobile_number'] =  !empty($mobile_number) ? $mobile_number : $userEntity->mobile_number;
                      $userData['landline_number'] =  !empty($landline_number) ? $landline_number : $userEntity->landline_number;
                      $userData['office_number'] =  !empty($office_number) ? $office_number : $userEntity->office_number;
                      $userData['total_employee'] =  !empty($total_employee) ? $total_employee : $userEntity->total_employee;
                      $userData['updated_at'] = Carbon::now()->toDateTimeString();
                      DB::table('users')->where('id',$userEntity->id)->update($userData);
                   

                    return redirect()->route('frontend.contractor.my-profile')->withFlashSuccess(__('Profile Updated Successfully.!'));
                }
              else
                {
                  return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid user.'));

                }

            }

           


            public function updateOtherInfo(Request $request) 
            {
               // echo "<pre>"; print_r($request->all());die;

                        $userId= auth()->user()->id;


                        //Multiple
                        $images_gallery=!empty($request->images_gallery) ? $request->images_gallery : '' ;
                        $videos_gallery=!empty($request->videos_gallery) ? $request->videos_gallery : '' ;

                        // Add Gallery Images
                                              
                      if(!empty($images_gallery))
                      {
                             $fileNames = array_filter($_FILES['images_gallery']['name']);
                             $allowTypes = array('jpg','png','jpeg'); 
                             $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 
                             error_reporting(0);
                             if(!empty($fileNames) && $_FILES["images_gallery"]["error"] !== 4)
                            {
                                //Delete Old
                                $getAll = DB::table('users_images_gallery')->whereRaw("(user_id = '".$userId."' AND deleted_at IS null)")->get()->toArray();
                                if(!empty($getAll))
                                {
                                    DB::table('users_images_gallery')->where('user_id', '=', $userId)->delete();

                                    $deleteOld = $this->delete_directory(public_path() . '/img/contractor/gallery/images/'.$userId);
                                }
                                 //Delete Old

                                //crete new folder
                                mkdir(public_path() . '/img/contractor/gallery/images/'.$userId, 0777, true);

                                $targetDir = public_path() . '/img/contractor/gallery/images/'.$userId.'/';

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
                                            $insert['user_id'] = $userId;
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
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__($errorUpload));exit;
                                }
                                 if(!empty($errorUploadType))
                                {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__($errorUploadType));exit;
                                }
                      // Add Gallery Images


                      // Add Gallery Videos


                      if(!empty($videos_gallery))
                      {
                               error_reporting(0);
                             $fileNames = array_filter($_FILES['videos_gallery']['name']);
                             $allowTypes = array("webm", "mp4", "ogv"); 
                             $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 
                             if(!empty($fileNames) && $_FILES["videos_gallery"]["error"] !== 4)
                            {
                                //Delete Old
                                $getAll = DB::table('users_videos_gallery')->whereRaw("(user_id = '".$userId."' AND deleted_at IS null)")->get()->toArray();
                                if(!empty($getAll))
                                {
                                    DB::table('users_videos_gallery')->where('user_id', '=', $userId)->delete();

                                    $deleteOld = $this->delete_directory(public_path() . '/img/contractor/gallery/videos/'.$userId);
                                }
                                //Delete Old

                                 //create new folder

                                mkdir(public_path() . '/img/contractor/gallery/videos/'.$userId, 0777, true);
                                $targetDir = public_path() . '/img/contractor/gallery/videos/'.$userId.'/';

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
                                            $insert['user_id'] = $userId;
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


                            if(!empty($errorUpload))
                                {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__($errorUpload));exit;
                                }
                                 if(!empty($errorUploadType))
                                {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__($errorUploadType));exit;
                                }
                        // Add Gallery Videos

                         //Services Area
                        
                        $whole_country=!empty($request->whole_country) ? $request->whole_country : '0' ;
                        $proviences=!empty($request->proviences) ? $request->proviences : '' ;

                        //services Area

                        if(!empty($whole_country) && $whole_country==1)
                        {
                           DB::table('users_services_area')->where('user_id', '=', $userId)->delete();
                            $forCountry['user_id'] = $userId; 
                            $forCountry['whole_country'] = $whole_country;
                            $forCountry['created_at'] = Carbon::now()->toDateTimeString();
                            $saveforCountry = DB::table('users_services_area')->insert($forCountry);
                        }

                        if(!empty($proviences))
                        {
                            $anotehr=array();
                            DB::table('users_services_area')->where('user_id', '=', $userId)->delete();

                            foreach ($proviences as $pps) 
                            {
                                $anotehr[]=explode(',', $pps);
                            }
                            
                            foreach ($anotehr as $key => $value) 
                            {
                                $provience_id=isset($value[0]) && !empty($value[0]) ? $value[0] : NULL ;
                                $city_id=isset($value[1]) && !empty($value[1]) ? $value[1] : NULL ;
                                                   
                                $forProvince['user_id'] = $userId; 
                                $forProvince['whole_country'] = 0;
                                $forProvince['province_id']=$provience_id;
                                $forProvince['city_id']=$city_id;
                                $forProvince['created_at'] = Carbon::now()->toDateTimeString();
                                $saveForProvince = DB::table('users_services_area')->insert($forProvince);

                            }
                        }

                        //services Area

                                //Payment Method
                                if(isset($request->payment_method_id) && !empty($request->payment_method_id)) 
                                {
                                     $paymentMeData=$request->payment_method_id;

                                       $getData = DB::table('user_payment_methods')->select('id','user_id','payment_method_id','status')->whereRaw("(user_id = '".$userId."')")->get()->toArray();

                                        if(!empty($getData))
                                        {
                                          DB::table('user_payment_methods')->where('user_id', '=', $userId)->delete();
                                        }

                                      foreach($paymentMeData as $value2) 
                                       {
                                          $paym['user_id'] = $userId;
                                          $paym['payment_method_id'] = $value2; 
                                          $paym['status'] =  1;
                                          $paym['created_at'] = Carbon::now()->toDateTimeString();
                                          $savepaym = DB::table('user_payment_methods')->insert($paym);
                                       }
                                }
                                //Payment Method

                           //Service Offerd
                            if(!empty($request->services))
                            {
                                $serviceOfferedData=$request->services;
                                $getData = DB::table('services_offered')->select('id','user_id','service_id','created_at','updated_at')->whereRaw("(user_id = '".$userId."')")->get()->toArray();
                                if(!empty($getData))
                                {
                                     DB::table('services_offered')->where('user_id', '=', $userId)->delete();
                                }
                                foreach($serviceOfferedData as $value) 
                                {

                                    $serv['user_id'] = $userId;
                                    $serv['service_id'] = $value; 
                                    $serv['created_at'] = Carbon::now();
                                    $saveserv = DB::table('services_offered')->insert($serv);
                                }
                            }

                            //End Service Offered

                    // profile description
                    if(isset($request->profile_description) && !empty($request->profile_description)) 
                    {
                        $pro_desc_arr['profile_description'] = $request->profile_description;
                        $pro_desc_arr['updated_at'] = Carbon::now();
                        DB::table('users')->where('id',$userId)->update($pro_desc_arr);                    
                    }
                    // profile description

                    // start social Network Record

                        if(isset($request->facebook_url) || isset($request->instagram_url) || isset($request->linkedin_url) || isset($request->twitter_url) || isset($request->youtube_url) ||  isset($request->snap_chat_url) || isset($request->other) ) 
                        {
                            $socialData['facebook_url'] =  $request->facebook_url;
                            $socialData['instagram_url'] =  $request->instagram_url;
                            $socialData['linkedin_url'] =  $request->linkedin_url;
                            $socialData['twitter_url'] =  $request->twitter_url;
                            $socialData['other'] =  $request->other;
                            $socialData['updated_at'] = Carbon::now();

                            $social_accounts = DB::table('social_networks')->where('user_id',$userId)->where('deleted_at',NULL)->first();
                            if($social_accounts) {
                                DB::table('social_networks')->where('user_id',$userId)->update($socialData);                    
                            } else {
                                $socialData['user_id'] = $userId;
                                $socialData['created_at'] = Carbon::now();
                                DB::table('social_networks')->insert($socialData);
                            }
                      
                        }
                     // end social network Record

                     // start Police Record

                         if(isset($request->record_type) && isset($request->police_records)) 
                        {
                           error_reporting(0);

                            $police_records=$request->police_records;
                            $record_type=$request->record_type;


                             if(!empty($police_records) && ($record_type==0 ||$record_type==1))
                              {
                               
                                     $fileNames = array_filter($_FILES['police_records']['name']);

                                     if($record_type=='0' || $record_type==0)
                                     {
                                        $allowTypes = array('jpg','png','jpeg'); 
                                     }
                                     else if($record_type=='1' || $record_type==1)
                                     {
                                        $allowTypes = array('pdf','doc','docx','txt','rtf','odf','msword');
                                     }
                                     
                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                     if(!empty($fileNames) && $_FILES["police_records"]["error"] !== 4)
                                    {
                                        //Delete Old
                                        $getAll = DB::table('user_certifications')->whereRaw("(user_id = '".$userId."' AND deleted_at IS null)")->whereRaw("(certification_type = '1')")->get()->toArray();
                                          
                                        if(!empty($getAll))
                                        {
                                            DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '1')->delete();

                                            $deleteOld = $this->delete_directory(public_path() . '/img/contractor/police_records/'.$userId);
                                        }
                                         //Delete Old

                                        //crete new folder
                                        mkdir(public_path() . '/img/contractor/police_records/'.$userId, 0777, true);

                                        $targetDir = public_path() . '/img/contractor/police_records/'.$userId.'/';

                                      foreach($_FILES['police_records']['name'] as $key=>$val) 
                                      {

                                         $fileName = rand(0000,9999).basename($_FILES['police_records']['name'][$key]); 
                                         $targetFilePath = $targetDir . $fileName;

                                            // Check whether file type is valid 
                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                            if(in_array($fileType, $allowTypes))
                                            { 
                                                // Upload file to server 
                                                if(move_uploaded_file($_FILES["police_records"]["tmp_name"][$key], $targetFilePath))
                                                { 
                                                    $insert['file_name'] = $fileName;
                                                    $insert['file_type'] = $fileType;
                                                    $insert['certification_type'] = '1';
                                                    $insert['user_id'] = $userId;
                                                    $insert['status'] = 1;
                                                    $insert['created_at'] = Carbon::now();  
                                                    DB::table('user_certifications')->insertGetId($insert); 
                                                }else
                                                { 
                                                     $errorUpload .= 'record file not uploaded.'; 
                                                } 
                                            }else
                                            { 
                                                $errorUploadType .='File Type Not Match';
                                            }  
                                       }
                                    }  
                              }


                               if(!empty($errorUpload))
                                {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__($errorUpload));exit;
                                }
                                 if(!empty($errorUploadType))
                                {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__($errorUploadType));exit;
                                }
                    }

                  // End Police Record 


                 // start certification courses

                if(isset($request->certification_type) && isset($request->certification_courses)) 
                {
                   error_reporting(0);
                    $certification_courses=$request->certification_courses;
                    $certification_type=$request->certification_type;

                             if(!empty($certification_courses) && ($certification_type==0 ||$certification_type==1))
                              {
                                     $fileNames = array_filter($_FILES['certification_courses']['name']);

                                     if($certification_type=='0' || $certification_type==0)
                                     {
                                        $allowTypes = array('jpg','png','jpeg'); 
                                     }
                                     else if($certification_type=='1' || $certification_type==1)
                                     {
                                        $allowTypes = array('pdf','doc','docx','txt','rtf','odf','msword');
                                     }
                                     
                                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                     if(!empty($fileNames) && $_FILES["certification_courses"]["error"] !== 4)
                                    {
                                        //Delete Old
                                        $getAll = DB::table('user_certifications')->whereRaw("(user_id = '".$userId."' AND deleted_at IS null)")->whereRaw("(certification_type = '0')")->get()->toArray();
                                          
                                        if(!empty($getAll))
                                        {
                                            DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '0')->delete();

                                            $deleteOld = $this->delete_directory(public_path() . '/img/contractor/certifications/'.$userId);
                                        }
                                         //Delete Old

                                        //crete new folder
                                        mkdir(public_path() . '/img/contractor/certifications/'.$userId, 0777, true);

                                        $targetDir = public_path() . '/img/contractor/certifications/'.$userId.'/';

                                      foreach($_FILES['certification_courses']['name'] as $key=>$val) 
                                      {

                                         $fileName = rand(0000,9999).basename($_FILES['certification_courses']['name'][$key]); 
                                         $targetFilePath = $targetDir . $fileName;

                                            // Check whether file type is valid 
                                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                            if(in_array($fileType, $allowTypes))
                                            { 
                                                // Upload file to server 
                                                if(move_uploaded_file($_FILES["certification_courses"]["tmp_name"][$key], $targetFilePath))
                                                { 
                                                    $insert['file_name'] = $fileName;
                                                    $insert['file_type'] = $fileType;
                                                    $insert['certification_type'] = '0';
                                                    $insert['user_id'] = $userId;
                                                    $insert['status'] = 1;
                                                    $insert['created_at'] = Carbon::now();  
                                                    DB::table('user_certifications')->insertGetId($insert); 
                                                }else
                                                { 
                                                     $errorUpload .= 'certification file not uploaded.'; 
                                                } 
                                            }else
                                            { 
                                                $errorUploadType .='File Type Not Match';
                                            }  
                                       }
                                    }  
                              }


                               if(!empty($errorUpload))
                                {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__($errorUpload));exit;
                                }
                                 if(!empty($errorUploadType))
                                {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__($errorUploadType));exit;
                                }
                        }

                  // End certification courses


                return redirect()->route('frontend.contractor.my-profile')->withFlashSuccess(__('Profile Updated Successfully.!'));

            }




              function delete_directory($dirname) 
          {
            error_reporting(0);
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









        public function myDocuments()
        {
           return view('frontend.contractor.documentation');
        }



            public function testPage()
            {
                $userId= auth()->user()->id;
                $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();

                $userdata=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at')->where('id',$userId)->first();

                $totalEmployee=DB::table('workers')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();


                $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first();

                $payment_methods = DB::table('payment_methods')->where('deleted_at',NULL)->get();

                $user_payment_method = DB::table('user_payment_methods')->where('deleted_at',NULL)->where('user_id',$userId)->select('payment_method_id')->first();
                if($user_payment_method) {

                    $payment_method_id = $user_payment_method->payment_method_id;
                } else {
                    $payment_method_id = 0;
                }


                $services = DB::table('services')->where('deleted_at',NULL)->get(); 
                $services_offered = DB::table('services_offered')->join('services','services_offered.service_id','=','services.id')->select('services.*','services_offered.service_id')->where('services_offered.user_id',$userId)->where('services_offered.deleted_at',NULL)->get(); 
                $serice_ids = array();

                if(isset($services_offered) && !empty($services_offered)) {
                    foreach ($services_offered as $key => $value) {
                        array_push($serice_ids,$value->service_id);
                    }
                }

                $provinces=DB::table('provinces')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                $cities=DB::table('cities')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                ///////////////////////Gallery/////////////////

                  $allImages=DB::table('users_images_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();
                  $allImages2=array();
                  $allVideo2=array();
                 if(!empty($allImages))
                 {
                    $path='/img/contractor/gallery/images/'.$userId.'/';
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
                    
                    $userdata->gallery['images'] = $allImages2;
                 }
                 else
                 {
                    $userdata->gallery['images']=[];
                 }


                 $allVideos=DB::table('users_videos_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allVideos))
                 {
                   $path='/img/contractor/gallery/videos/'.$userId.'/';
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
                   $userdata->gallery['videos'] = $allVideo2;
                 }
                 else
                 {
                    $userdata->gallery['videos']=[];
                 }

             ///////////////////////Gallery/////////////////


            ///////////////////////users Documents/////////////////


             $allCertificates=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','0')->whereRaw("(deleted_at IS null )")->get()->toArray();
                                                          
                $certi2=array();
                  $policeR2=array();
                 if(!empty($allCertificates))
                 {
                    $path='/img/contractor/certifications/'.$userId.'/';
                    foreach ($allCertificates as $key => $value) 
                    {
                        $allImages1['id']=$value->id;
                        $allImages1['user_id']=$value->user_id;
                        $allImages1['file_name']=url($path.$value->file_name);
                        $allImages1['file_type']=$value->file_type;
                        $allImages1['status']=$value->status;
                        $allImages1['created_at']=$value->created_at;
                        array_push($certi2, $allImages1);
                    }
                    
                    $userdata->cetifications['certification_courses'] = $certi2;
                 }
                 else
                 {
                    $userdata->cetifications['certification_courses']=[];
                 }


                 $allPoliceRec=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allPoliceRec))
                 {
                   $path='/img/contractor/police_records/'.$userId.'/';
                    foreach ($allPoliceRec as $key => $value) 
                    {
                        $allVideo1['id']=$value->id;
                        $allVideo1['user_id']=$value->user_id;
                        $allVideo1['file_name']=url($path.$value->file_name);
                        $allVideo1['file_type']=$value->file_type;
                        $allVideo1['status']=$value->status;
                        $allVideo1['created_at']=$value->created_at;
                        array_push($policeR2, $allVideo1);
                    }
                   $userdata->cetifications['police_records'] = $policeR2;
                 }
                 else
                 {
                    $userdata->cetifications['police_records']=[];
                 }

            ///////////////////////users Documents/////////////////

                // echo "<pre>"; print_r($userdata);die;

                return view('frontend.contractor.test_page')
                ->withUser($userdata)
                ->withTotalEmployee($totalEmployee)
                ->withBonus($bonus)
                ->withSocial($social)
                ->withProvinces($provinces)
                ->withCities($cities)
                ->withServices($services)
                ->withServiceOffered($services_offered)
                ->withServiceIds($serice_ids)
                ->withPaymentMethods($payment_methods)
                ->withPaymentMethodId($payment_method_id);
            }





}
