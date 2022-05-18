<?php

namespace App\Http\Controllers\Frontend\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
/**
 * Class ProfileController.
 */
class CompanyProfileController extends Controller
{ 
    public function index()
    {
        $userId= auth()->user()->id;
        $companyData=DB::table('users')->select('*')->where('id',$userId)->first();
        $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first();

        return view('frontend.company.profile')->withCompany($companyData)->withSocial($social);

    }

      public function showCodashboard()
    {
        $userId= auth()->user()->id;
        $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();
        $userdata = DB::table('users')->whereRaw("(id = '".$userId."')")->first();
        $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first();

        return view('frontend.company.profile')->withUser($userdata)->withBonus($bonus)->withSocial($social);

    }

     public function miPerfil()
          {
            $userId= auth()->user()->id;

            $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();

            $review_datas = DB::table('reviews')
               ->join('users as U1','reviews.user_id','=','U1.id')
               ->join('users as U2','reviews.to_user_id','=','U2.id')
               ->join('service_request','reviews.request_id','=','service_request.id')
               ->select('U1.username','U1.mobile_number', 'U1.avatar_location','U2.username as provider_name','reviews.*')
               ->where('reviews.deleted_at',NULL)
               ->where('reviews.to_user_id',$userId)
               ->get();

            $userdata=DB::table('users')->select('users.id','users.banner','users.approval_status','users.is_confirm_reg_step','users.legal_representative','users.identity_no','users.ruc_no','users.year_of_constitution','users.website_address','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userId)->first();
 
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
                $path='/img/company/gallery/images/'.$userId.'/';
                foreach ($allImages as $key => $value) 
                {
                    $allImages1['id']=$value->id;
                    $allImages1['user_id']=$value->user_id;
                    $allImages1['file_name']=$value->file_name;
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
               $path='/img/company/gallery/videos/'.$userId.'/';
                foreach ($allVideos as $key => $value) 
                {
                    $allVideo1['id']=$value->id;
                    $allVideo1['user_id']=$value->user_id;
                    $allVideo1['file_name']=$value->file_name;
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
                $path='/img/company/certifications/'.$userId.'/';
                foreach ($allCertificates as $key => $value) 
                {
                    $allImages1['id']=$value->id;
                    $allImages1['user_id']=$value->user_id;
                    $allImages1['file_name']=$value->file_name;
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
               $path='/img/company/police_records/'.$userId.'/';
                foreach ($allPoliceRec as $key => $value) 
                {
                    $allVideo1['id']=$value->id;
                    $allVideo1['user_id']=$value->user_id;
                    $allVideo1['file_name']=$value->file_name;
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
                        $path='/img/company/certifications/'.$userId.'/';
                        foreach ($allCertificates as $key => $value) 
                        {
                            $allImages1['id']=$value->id;
                            $allImages1['user_id']=$value->user_id;
                            $allImages1['file_name']=$value->file_name;
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
                       $path='/img/company/police_records/'.$userId.'/';
                        foreach ($allPoliceRec as $key => $value) 
                        {
                            $allVideo1['id']=$value->id;
                            $allVideo1['user_id']=$value->user_id;
                            $allVideo1['file_name']=$value->file_name;
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


                    return view('frontend.company.mi_perfil', compact('review_datas', 'userId'))
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



    public function companyProfile()
    {

        $userId= auth()->user()->id;
         if(auth()->user()->user_group_id==3)
         {
            return redirect()->route('frontend.contractor.my-profile');
         }
         if(auth()->user()->user_group_id==2)
         {
            return redirect()->to('dashboard');
         }

        $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();

        $companyData=DB::table('users')->select('*')->where('id',$userId)->first();

        if(isset($companyData)){
          $id = $companyData->identity_no;
          $id = (100*5)/100;
          $username = $companyData->username;
          $username = (100*5)/100;
          $dob = $companyData->dob;
          $dob = (100*5)/100;
          $address = $companyData->address;
          $address = (100*5)/100;
          $mobile_number = $companyData->mobile_number;
          $mobile_number = (100*5)/100;
          $total_employee = $companyData->total_employee;
          $total_employee = (100*5)/100;
          $profile = $id + $username + $dob + $address + $mobile_number + $total_employee; 

        }else{
            $profile = (100*0)/100;
        }

        if(!empty($companyData->profile_description)){

            $profile_description = !empty($companyData->profile_description)?$companyData->profile_description:'';

            $profile_description1 = $profile_description;
            $profile_description1 = (100*15)/100;
        }else{
            $profile_description1 = (100*0)/100;
        }


        $totalEmployee=DB::table('workers')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();

        $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first();

        if(!empty($social)){
                  $social1 = $social;
                  $social1 = (100*10)/100;
            }else{
                $social1 = (100*0)/100;
            }
              

       $totalEmployee=DB::table('workers')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();

       $payment_methods = DB::table('payment_methods')->where('deleted_at',NULL)->get();

       $user_payment_method = DB::table('user_payment_methods')->where('deleted_at',NULL)->where('user_id',$userId)->select('payment_method_id')->get()->toArray();

       if(!empty($user_payment_method)){
            $user_payment_method1 = $user_payment_method;
            $user_payment_method1 = (100*5)/100;
        }else{

            $user_payment_method1 = (100*0)/100;
        }

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
                $services_offered = DB::table('services_offered')->join('services','services_offered.service_id','=','services.id')->select('services.*','services_offered.service_id')->where('services_offered.user_id',$userId)->where('services_offered.deleted_at',NULL)->
                groupBy('services_offered.service_id')->get();

                if(!empty($services_offered)){
                  $services_offered1 = $services_offered;
                  $services_offered1 = (100*10)/100;
                  }else{
                      
                      $services_offered1 = (100*0)/100;
                  }

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
                    $path='/img/company/gallery/images/'.$userId.'/';
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

                    $companyData->gallery['images'] = $allImages2;
                 }
                 else
                 {
                    $companyData->gallery['images']=[];
                 }


                 $allVideos=DB::table('users_videos_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allVideos))
                 {
                   $path='/img/company/gallery/videos/'.$userId.'/';
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
                   $companyData->gallery['videos'] = $allVideo2;
                 }
                 else
                 {
                    $companyData->gallery['videos']=[];
                 }

             ///////////////////////Gallery/////////////////

            ///////////////////////users Documents/////////////////
             $allCertificates=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','0')->whereRaw("(deleted_at IS null )")->get()->toArray();

                $certi2=array();
                $policeR2=array(); 
                 if(!empty($allCertificates))
                 {
                    $path='/img/company/certifications/'.$userId.'/';
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

                    $companyData->cetifications['certification_courses'] = $certi2;
                    $allImages2 = $allImages;
                    $allImages2 = (100*10)/100;
                 }
                 else
                 {
                    $companyData->cetifications['certification_courses']=[];
                    $allImages2 = $allImages;
                    $allImages2 = (100*0)/100;
                 }


                 $allPoliceRec=DB::table('user_certifications')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->where('certification_type','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                 
                 if(!empty($allPoliceRec))
                 {
                   $path='/img/company/police_records/'.$userId.'/';
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
                   $companyData->cetifications['police_records'] = $policeR2;
                   $allVideos2 = $allVideos;
                   $allVideos2 = (100*5)/100;
                 }
                 else
                 {
                    $companyData->cetifications['police_records']=[];
                    $allVideos2 = $allVideos;
                    $allVideos2 = (100*0)/100;
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

              //show coverage area
            $user_ser_area = DB::table('users_services_area')->where('user_id',$userId)->get();
            $users_services_area1 = $user_ser_area;
            $users_services_area1 = (100*5)/100;
            $user_province_ids = array();
            $user_province_name = array();

            $user_city_ids = array();
            $user_province = DB::table('users_services_area')->where('user_id',$userId)->where('province_id','!=',NULL)->get();

            foreach ($user_province as $k_province => $v_province) {

               array_push($user_province_ids,$v_province->province_id);

               $data = DB::table('provinces')->select('name')->where('id',$v_province->province_id)->get();

               array_push($user_province_name,$data);

            }

        // echo "<pre>";print_r($companyData);die;

        $user_city = DB::table('users_services_area')->where('user_id',$userId)->where('city_id','!=',NULL)->get();
        foreach ($user_city as $k_city => $v_city) {
 
            array_push($user_city_ids,$v_city->city_id);
        }
        $user_city1 = $user_city;
        $user_city1 = (100*5)/100;


         $provinces=DB::table('provinces')->where('status','1')->whereRaw("(deleted_at IS null )")->get();

         $cities=DB::table('cities')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

          $users_services_area = DB::table('users_services_area')->join('provinces','users_services_area.province_id','=','provinces.id')->select('provinces.*','users_services_area.province_id')->where('users_services_area.user_id',$userId)->where('users_services_area.deleted_at',NULL)->
              groupBy('users_services_area.province_id')->get(); 
              $users_services_area1 = $users_services_area;
              $users_services_area1 = (100*5)/100;

              $serviceArea_ids = array();

              if(isset($users_services_area) && !empty($users_services_area)) 
              {
                  foreach ($users_services_area as $key => $value) 
                  {
                      array_push($serviceArea_ids,$value->province_id);
                  }
              }

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
                  //dd($sr1['cities']);
                  array_push($allUserAreaData, $sr1);

              }
          }

          $status_bar = $profile + $user_payment_method1 + $social1 +  $profile_description1 + $services_offered1 + $user_city1 + $allImages2 + $allVideos2;

        //dd($status_bar);
       // echo "<pre>";print_r($companyData);die;

        return view('frontend.company.company_profile',compact('provinces','cities','services','services_offered','serice_ids','userId','user_ser_area','user_province_ids','user_city_ids','status_bar','city','users_services_area'))->withCompany($companyData)->withSocial($social)->withTotalEmployee($totalEmployee)
          ->withBonus($bonus)
          ->withProvinces($provinces)
          ->withCities($cities)
          ->withServices($services)
          ->withServiceArea($users_services_area)
          ->withAllUserAreaData($allUserAreaData)
          ->withServiceOffered($services_offered)
          ->withServiceIds($serice_ids)
          ->withPaymentMethods($payment_methods)
          ->withPaymentMethodId($payment_method_id)
          ->withCombineddata($combinedData)
          ->withMixdata($allData);

    }


      public function updateProfilePicture(Request $request)
          {
              $avatar_location = !empty($request->avatar_location) ? $request->avatar_location : '' ;
              $userid= auth()->user()->id;

              $userEntity = DB::table('users')
                  ->whereRaw("(active=1)")->whereRaw("(confirmed=1)")
                  ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();
                  if(!empty($userEntity))
                  {
                      $profile = $userEntity->avatar_location;

                      if(isset($_FILES['avatar_location']['name']) && !empty($_FILES['avatar_location']['name']))
                          {
                              $extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
                              $filename = $userid.'.'.$extq;

                              $ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);

                              $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/company/profile/'.$filename);

                               $profile = $filename;
                          }

                      $userData['avatar_location'] =  $profile;
                      $userData['updated_at'] = Carbon::now()->toDateTimeString();
                      DB::table('users')->where('id',$userEntity->id)->update($userData);

                     return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.profile_picture_updated_successfully'));
                  }
                  else
                  {
                    return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user'));

                  }
          }


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
                            return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.mobile_number_already_exist'));exit;
                        }

                        $ruc_no = !empty($request->ruc_no) ? $request->ruc_no : '' ;
                        $year_of_constitution = !empty($request->year_of_constitution) ? $request->year_of_constitution : '' ;
                        $legal_representative = !empty($request->legal_representative) ? $request->legal_representative : '' ;
                        $website_address = !empty($request->website_address) ? $request->website_address : '' ;

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

                    $userData['ruc_no'] =  !empty($ruc_no) ? $ruc_no : $userEntity->ruc_no; 
                    $userData['year_of_constitution'] =  !empty($year_of_constitution) ? $year_of_constitution : $userEntity->year_of_constitution;
                    $userData['legal_representative'] =  !empty($legal_representative) ? $legal_representative : $userEntity->legal_representative;
                    $userData['website_address'] =  !empty($website_address) ? $website_address : $userEntity->website_address;

                    $userData['username'] =  !empty($username) ? $username : $userEntity->username;
                    $userData['profile_title'] =  !empty($profile_title) ? $profile_title : $userEntity->profile_title;
                    $userData['identity_no'] =  !empty($identity_no) ? $identity_no : $userEntity->identity_no;
                    $userData['dob'] =  !empty($dob) ? $dob : $userEntity->dob;
                    $userData['address'] =  !empty($address) ? $address : $userEntity->address;
                    $userData['office_address'] =  !empty($request->office_address) ? $request->office_address : '' ;
                    $userData['other_address'] =  !empty($request->other_address) ? $request->other_address : '' ;
                    
                    $userData['mobile_number'] =  !empty($mobile_number) ? $mobile_number : $userEntity->mobile_number;
                    $userData['landline_number'] =  !empty($request->landline_number) ? $request->landline_number : '' ;
                    $userData['office_number'] =  !empty($request->office_number) ? $request->office_number : '' ;
                    $userData['updated_at'] = Carbon::now()->toDateTimeString();
                    DB::table('users')->where('id',$userEntity->id)->update($userData);


                    return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.profile_updated_successfully'));
                }
              else
                {
                  return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user'));

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

                             if(!empty($fileNames) && $_FILES["images_gallery"]["error"] !== 4)
                            {
                                //Delete Old
                                $getAll = DB::table('users_images_gallery')->whereRaw("(user_id = '".$userId."' AND deleted_at IS null)")->get()->toArray();
                               /* if(!empty($getAll))
                                {
                                    DB::table('users_images_gallery')->where('user_id', '=', $userId)->delete();

                                    $deleteOld = $this->delete_directory(public_path() . '/img/company/gallery/images/'.$userId);
                                }*/
                                 //Delete Old
                                 if(empty($getAll))
                                {
                                    //delete folder
                                  $deleteOld = $this->delete_directory(public_path() . '/img/company/gallery/images/'.$userId);
                                 }
                                 if (!file_exists(public_path() . '/img/company/gallery/images/'.$userId)) {


                                //crete new folder
                                 mkdir(public_path() . '/img/company/gallery/images/'.$userId, 0777, true);
                                 }

                                $targetDir = public_path() . '/img/company/gallery/images/'.$userId.'/';

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
                                             $errorUpload .= (__('alerts.frontend.company.profile.image_not_uploaded'));
                                        }
                                    }else
                                    {
                                        $errorUploadType .=(__('alerts.frontend.company.profile.images_type_allowed_only'));
                                    }
                               }
                            }
                      }
                            

                      if(!empty($errorUpload))
                          {
                          return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUpload));exit;
                          }
                           if(!empty($errorUploadType))
                          {
                          return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUploadType));exit;
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
                                $getAll = DB::table('users_videos_gallery')->whereRaw("(user_id = '".$userId."' AND deleted_at IS null)")->get()->toArray();
                                /*if(!empty($getAll))
                                {
                                    DB::table('users_videos_gallery')->where('user_id', '=', $userId)->delete();

                                    $deleteOld = $this->delete_directory(public_path() . '/img/company/gallery/videos/'.$userId);
                                }*/
                                //Delete Old

                                 //create new folder
                                if(empty($getAll))
                                {
                                    //delete folder
                                  $deleteOld = $this->delete_directory(public_path() . '/img/company/gallery/videos/'.$userId);
                                 }

                                if (!file_exists(public_path() . '/img/company/gallery/videos/'.$userId)) {
                                   mkdir(public_path() . '/img/company/gallery/videos/'.$userId, 0777, true);
                                 }
                                $targetDir = public_path() . '/img/company/gallery/videos/'.$userId.'/';

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
                                             $errorUpload .= (__('alerts.frontend.company.profile.video_not_uploaded'));;
                                        }
                                    }else
                                    {
                                        $errorUploadType .= (__('alerts.frontend.company.profile.video_type_allowed_only'));

                                    }
                               }
                            }
                        }


                      if(!empty($errorUpload))
                          {
                          return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUpload));exit;
                          }
                           if(!empty($errorUploadType))
                          {
                          return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUploadType));exit;
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
                                //dd($saveForProvince);

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
                                // if(!empty($getData))
                                // {
                                //      DB::table('services_offered')->where('user_id', '=', $userId)->delete();
                                // }
                                foreach($serviceOfferedData as $key => $value)
                                 {
                                    $sub_service_id = NULL;
                                    $service_id = NULL;

                                    $serv_subserv = explode(",",$value);
                                    $service_id = (int)$serv_subserv[0];

                                    if(isset($serv_subserv[1])){

                                    $sub_service_id = (int)$serv_subserv[1];
                                     }

                                    $serv['user_id']        = $userId;
                                    $serv['service_id']     = $service_id;
                                    $serv['sub_service_id'] = $sub_service_id;
                                    $serv['updated_at']     = Carbon::now();

                                    // echo "<pre>"; print_r($serv); die;

                                  $saveserv = DB::table('services_offered')->insert($serv);

                                 }
                            }

                            //End Service Offered

                           //start profile description
                            if(!empty($request->profile_description))
                            {
                                $pro_desc_arr['profile_description'] = $request->profile_description;
                              $pro_desc_arr['updated_at'] = Carbon::now();
                              //dd($pro_desc_arr);
                              DB::table('users')->where('id',$userId)->update($pro_desc_arr); 
                            }
                             
                              
                            //end profile description

                            // start social Network Record

                            if(isset($request->facebook_url) || isset($request->instagram_url) || isset($request->linkedin_url) || isset($request->twitter_url) || isset($request->youtube_url) ||  isset($request->snap_chat_url) || isset($request->other)) 
                              {

                                $socialData['facebook_url'] =  !empty($request->facebook_url)?$request->facebook_url:'';
                                $socialData['instagram_url'] =  !empty($request->instagram_url)?$request->instagram_url:'';
                                $socialData['linkedin_url'] =  !empty($request->linkedin_url)?$request->linkedin_url:'';
                                $socialData['twitter_url'] =  !empty($request->twitter_url)?$request->twitter_url:'';
                                $socialData['other'] =  !empty($request->other)?$request->other:'';
                                $socialData['updated_at'] = Carbon::now();

                                $social_accounts = DB::table('social_networks')->where('user_id',$userId)->where('deleted_at',NULL)->first();
                                //dd($social_accounts);
                                if($social_accounts) {
                                    DB::table('social_networks')->where('user_id',$userId)->update($socialData);

                                } else {
                                    $socialData['user_id'] = $userId;
                                    $socialData['created_at'] = Carbon::now();
                                    DB::table('social_networks')->insert($socialData);
                                   // dd($socialData);
                                }

                              }

                       // end social network Record

                       // start Police Record

                         if(isset($request->record_type) && isset($request->police_records))
                         {

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


                                       /* if(!empty($getAll))
                                        {
                                            DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '1')->delete();

                                            $deleteOld = $this->delete_directory(public_path() . '/img/company/police_records/'.$userId);
                                        }*/
                                         //Delete Old

                                        if(empty($getAll))
                                        {

                                        //crete new folder
                                           $deleteOld = $this->delete_directory(public_path() . '/img/company/police_records/'.$userId);
                                        }
                                        if (!file_exists(public_path() . '/img/company/police_records/'.$userId)) {

                                        //crete new folder
                                          mkdir(public_path() . '/img/company/police_records/'.$userId, 0777, true);
                                        }

                                        $targetDir = public_path() . '/img/company/police_records/'.$userId.'/';

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
                                                    $insert['file_type'] = $record_type;
                                                    $insert['certification_type'] = '1';
                                                    $insert['user_id'] = $userId;
                                                    $insert['status'] = 1;
                                                    $insert['created_at'] = Carbon::now();
                                                    DB::table('user_certifications')->insertGetId($insert);
                                                }else
                                                {
                                                     $errorUpload .= (__('alerts.frontend.company.profile.record_file_not_uploaded'));
                                                }
                                            }else
                                            {
                                                $errorUploadType .= (__('alerts.frontend.company.profile.file_type_not_match'));
                                            }
                                       }
                                    }
                              }

 
                               if(!empty($errorUpload))
                                {
                                return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUpload));exit;
                                }
                                 if(!empty($errorUploadType))
                                {
                                return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUploadType));exit;
                                }
                    }

                  // End Police Record


                 // start certification courses

                if(isset($request->certification_type) && isset($request->certification_courses))
                {
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

                                    /*if(!empty($getAll))
                                    {
                                        DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '0')->delete();

                                        $directory1 = public_path('/img/company/certifications/22');
                                        $response = $this->delete_directory($directory1);



                                       // $deleteOld = delete_directory(public_path() . '/img/company/certifications/'.$userId);
                                    }*/
                                     //Delete Old

                                    if(empty($getAll))
                                    {

                                        $deleteOld = $this->delete_directory(public_path() . '/img/company/certifications/'.$userId);
                                    }
                                    //crete new folder
                                  if (!file_exists(public_path() . '/img/company/certifications/'.$userId)) {
                                       mkdir(public_path() . '/img/company/certifications/'.$userId, 0777, true);
                                    }

                                    $targetDir = public_path() . '/img/company/certifications/'.$userId.'/';

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
                                                $insert['file_type'] = $certification_type;
                                                $insert['certification_type'] = '0';
                                                $insert['user_id'] = $userId;
                                                $insert['status'] = 1;
                                                $insert['created_at'] = Carbon::now();
                                                DB::table('user_certifications')->insertGetId($insert);
                                            }else
                                            {
                                                 $errorUpload .= (__('alerts.frontend.company.profile.certification_file_not_uploaded'));
                                            }
                                        }else
                                        {
                                            $errorUploadType .=(__('alerts.frontend.company.profile.file_type_not_match'));
                                        }
                                   }
                                }
                          }


                               if(!empty($errorUpload))
                                {
                                return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUpload));exit;
                                }
                                 if(!empty($errorUploadType))
                                {
                                return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUploadType));exit;
                                }
                        }

                  // End certification courses


                return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.profile_picture_updated_successfully'));

            }


            public function deleteCertificateImage(Request $request)
             {
                  $userid= auth()->user()->id;
                  $certi_id=!empty($request->certification_id) ? $request->certification_id : '' ;

                        if(!empty($userid) && !empty($certi_id)) 
                        {

                                 $userEntity = DB::table('users')
                                ->whereRaw("(active=1)")
                                ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                                ->first();

                                if(!empty($userEntity))
                                {
                                        $getimg = DB::table('user_certifications')
                                        ->whereRaw("(id = '".$certi_id."' AND user_id = '".$userid."' AND deleted_at IS null )")->first();
                                        
                                        if(!empty($getimg))
                                        {
                                           //Profile picture
                                             $galleryPath="";

                                                $galleryPath ='/img/company/certifications/'.$userid.'/';

                                                   $targetDir = public_path() . $galleryPath;

                                                    $Your_file_path= $targetDir.$getimg->file_name;

                                                     if (file_exists($Your_file_path)) 
                                                     {
                                                         unlink($Your_file_path);
                                                     } 

                                                   DB::table('user_certifications')->whereRaw("(id = '".$certi_id."' AND user_id = '".$userid."' AND deleted_at IS null )")->delete();

                                                return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.profile_picture_updated_successfully'));
                                        }else
                                        {
                                            return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.image_not_found')); 
                                        }

                                }
                                else
                                {
                                   return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user')); 
                                }

                            
                        }else
                        {
                            return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user')); 
                        }

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
    public function reviewByCompany ()
    {
      return view('frontend.company.review');
    }


    public function ratingReview(Request $request)
    {
        
        $user_id =auth()->user()->id;
        $client_user_id =  !empty($request->prouserId) ? $request->prouserId : '' ;
        $request_id = !empty($request->serviceId) ? $request->serviceId : '' ;
             
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
                     
                                   //\\\ echo '<pre>'; print_r($serviceprform);exit;
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
                   
                        $message=$profession->username.' ha solicitado que califiques y dejes un comentario sobre tu experiencia, esta información será de mucha ayuda para que futuros clientes la/lo conozcan.';
                         $butttontext='Calificar';
                   

                    $data = array(
                            'username'=>$userToken->username,
                            'receiver'=>$userEntity->email,
                            'message'=>$message,
                            'profephoto'=>$profesionImage,
                            'profename'=>$profession->username,
                            'servicename'=>isset($serviceprform->es_name)?$serviceprform->es_name:'',
                            'servicedate'=>$serviceprform->servicedateperform,
                            'buttontext'=>$butttontext,
                            'actionurl'=>url('/company_profile/rating_review?userid='.$client_user_id.'&prouserId='.$user_id.'&serviceId='.$serviceId),
                            'logo'=>url('img/logo/logo-svg.png'),
                            'footer_logo'=>url('img/logo/footer-logo.png'),
                            'user_icon'=>$userIcon);

                      Mail::send('frontend.mail.rating_mail',  ['data' => $data], function($message) use ($user){
                         $message->to($user)->subject(__('  Calificación y comentarios', ['app_name' => app_name()]));
                         //$message->from(env('MAIL_FROM_NAME'));
                    });

                        $chatmessage='Solicitamos calificación y comentarios <br/>'.url("/company_profile/rating_review?userid=".$client_user_id."&prouserId=".$user_id."&serviceId=".$serviceId); 
                        $insert['from_userid'] =$user_id;
                        $insert['to_userid'] =  $client_user_id;
                        $insert['message'] =$chatmessage;
                        $insert['is_read'] = 0;
                        $insert['type'] = 'Rating';
                        $insert['is_starred'] = 0;
                        $insert['created_at'] = Carbon::now();  
                        $lastId=DB::table('users_chat')->insertGetId($insert);
                        return redirect()->route('frontend.company.company_profile.jobs')->withFlashSuccess(__('apimessage.request_has_been_send'));
                }
                else
                {
                    return redirect()->route('frontend.company.company_profile.jobs')->withFlashDanger(__('apimessage.request_already_send'));
                    
                }                           
            }
            else
            {
                return redirect()->route('frontend.company.company_profile.jobs')->withFlashDanger(__('apimessage.Invalid user.'));
            }

    }


    public function storeReviewByCompany(Request $request)
    {

     $validator = Validator::make($request->all(), [
            'service_request_id' => 'required',
            'to_user' => 'required',
            'review' => 'required',
            ]);

            if($validator->fails())
            {
                return Redirect::back()->withErrors($validator)->withInput();     
            } 
      $user_id= auth()->user()->id;

      $to_user = !empty($request->to_user) ? $request->to_user : '' ;

      $service_request_id = !empty($request->service_request_id) ? $request->service_request_id : '' ;

      $price_rating = !empty($request->price) ? $request->price : 0 ;

      $puntuality_rating = !empty($request->puntuality) ? $request->puntuality : 0 ;

      $service_rating = !empty($request->service) ? $request->service : 0 ;

      $quality_rating = !empty($request->quality) ? $request->quality : 0 ;

      $amiability_rating = !empty($request->amiability) ? $request->amiability : 0 ;

      $review = !empty($request->review) ? $request->review : '' ;
      $total_rating = 0;
      $total_rating = round(($price_rating + $puntuality_rating + $service_rating + $quality_rating + $amiability_rating)/5);

      //echo $total_rating; die('dsfsd');

      $check_review = DB::table('reviews')->where('user_id',$user_id)->where('request_id',$service_request_id)->first();

      if(!empty($check_review))
      {
         return redirect()->route('frontend.contractor.jobs')->withFlashDanger(__('alerts.frontend.company.profile.you_have_already_added_review'));

      }



      $review_data = array();

      $review_data['user_id'] = $user_id;
      $review_data['review_by'] = 'company';
      $review_data['to_user_id'] = $to_user;
      $review_data['request_id'] = $service_request_id;
      $review_data['rating'] = $total_rating;
      $review_data['price'] = $price_rating;
      $review_data['puntuality'] = $puntuality_rating;
      $review_data['service'] = $service_rating;
      $review_data['quality'] = $quality_rating;
      $review_data['amiability'] = $amiability_rating;
      $review_data['review'] = $review;

      $review_id = DB::table('reviews')->insertGetId($review_data);

      if($review_id){
        return redirect()->route('frontend.contractor.jobs')->withFlashSuccess(__('alerts.frontend.company.profile.review_submited_successfully'));
      }else{
        return redirect()->route('frontend.contractor.jobs')->withFlashDanger(__('alerts.frontend.company.profile.something_went_wrong'));
      }
    }

     public function myDocuments()
        {
           return view('frontend.company.documentation');
        }

     public function deletePoliceImage(Request $request)
       {
            $userid= auth()->user()->id;
            $polRecId=!empty($request->polRecId) ? $request->polRecId : '' ;

                  if(!empty($userid) && !empty($polRecId)) 
                  {

                           $userEntity = DB::table('users')
                          ->whereRaw("(active=1)")
                          ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                          ->first();

                          if(!empty($userEntity))
                          {
                                  $getimg = DB::table('user_certifications')
                                  ->whereRaw("(id = '".$polRecId."' AND user_id = '".$userid."' AND deleted_at IS null )")->first();
                                  
                                  if(!empty($getimg))
                                  {
                                     //Profile picture
                                       $galleryPath="";

                                          $galleryPath ='/img/company/police_records/'.$userid.'/';

                                             $targetDir = public_path() . $galleryPath;

                                              $Your_file_path= $targetDir.$getimg->file_name;

                                               if (file_exists($Your_file_path)) 
                                               {
                                                   unlink($Your_file_path);
                                               } 

                                             DB::table('user_certifications')->whereRaw("(id = '".$polRecId."' AND user_id = '".$userid."' AND deleted_at IS null )")->delete();

                                          return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.file_deleted_successfully'));
                                  }else
                                  {
                                      return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.image_not_found')); 
                                  }

                          }
                          else
                          {
                             return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user')); 
                          }

                      
                  }else
                  {
                      return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user')); 
                  }

       }

     public function updateCertificateImage(Request $request)
      {
         $userid= auth()->user()->id;
          //echo "<pre>"; print_r($request->all());die;

          $userEntity = DB::table('users')
          ->whereRaw("(active=1)")
          ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
          ->first(); 

         $certifiePath ='/img/company/certifications/';

           $certification_courses_img=!empty($request->certification_courses_img) ? $request->certification_courses_img : '' ;
            $certi_id=!empty($request->certification_id) ? $request->certification_id : '' ;


         // start certification courses IMAGE

               if(!empty($certification_courses_img))
                {
                  //error_reporting(0);

                       $fileNames = $_FILES['certification_courses_img']['name'];

                       $allowTypes = array('jpg','png','jpeg'); 
                       
                       $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                       if(!empty($fileNames) && $_FILES["certification_courses_img"]["error"] !== 4)
                      {
                          //Delete Old
                           $getAll = DB::table('user_certifications')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null)")->whereRaw("(certification_type = '0')")->whereRaw("(file_type = '0')")->whereRaw("(id = '".$certi_id."')")->first();
                            
                          if(!empty($getAll))
                          {
                                $targetDir = public_path() . $certifiePath .$userEntity->id.'/';
                                $Your_file_path= $targetDir.$getAll->file_name;
                                 if (file_exists($Your_file_path)) 
                                 {
                                     unlink($Your_file_path);
                                 } 

                          }
                           //Delete Old

                          //crete new folder
                        
                          $targetDir = public_path() . $certifiePath .$userid.'/';

                           $fileName = rand(0000,9999).basename($_FILES['certification_courses_img']['name']); 
                           $targetFilePath = $targetDir . $fileName;

                              // Check whether file type is valid 
                              $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                              if(in_array($fileType, $allowTypes))
                              { 
                                  // Upload file to server 
                                  if(move_uploaded_file($_FILES["certification_courses_img"]["tmp_name"], $targetFilePath))
                                  { 
                                      
                                    $update_Arr['updated_at'] = Carbon::now();
                                    $update_Arr['file_name'] = $fileName;  
                                    DB::table('user_certifications')->where('id', $certi_id)->update($update_Arr);

                                  }else
                                  { 
                                       $errorUpload .= (__('alerts.frontend.company.profile.certification_image_file_not_uploaded'));
                                  } 
                              }else
                              { 
                                  $errorUploadType .=(__('alerts.frontend.company.profile.certification_image_file_type_not_match'));
                              }  
                       
                      }  
                }


                 if(!empty($errorUpload))
                  {
                    return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUpload));exit;
                  }
                   if(!empty($errorUploadType))
                  {
                    return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUploadType));exit;
                  }

        // End certification courses IMAGE

        return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.profile_updated_successfully'));

      }

       public function updatePoliceRecordImage(Request $request)
                {
                   $userid= auth()->user()->id;
                    //echo "<pre>"; print_r($request->all());die;

                    //

                    $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")
                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                    ->first(); 

                      $policePath ='/img/company/police_records/';


                      $police_record_img=!empty($request->police_record_img) ? $request->police_record_img : '' ;
                      $polRecId=!empty($request->polRecId) ? $request->polRecId : '' ;

                   // start certification courses IMAGE

                         if(!empty($police_record_img))
                          {
                            //error_reporting(0);

                                 $fileNames = $_FILES['police_record_img']['name'];

                                 $allowTypes = array('jpg','png','jpeg'); 
                                 
                                 $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                 if(!empty($fileNames) && $_FILES["police_record_img"]["error"] !== 4)
                                {
                                    //Delete Old
                                     $getAll = DB::table('user_certifications')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null)")->whereRaw("(certification_type = '1')")->whereRaw("(file_type = '0')")->whereRaw("(id = '".$polRecId."')")->first();
                                      
                                    if(!empty($getAll))
                                    {
                                          $targetDir = public_path() . $policePath .$userid.'/';
                                          $Your_file_path= $targetDir.$getAll->file_name;
                                           if (file_exists($Your_file_path)) 
                                           {
                                               unlink($Your_file_path);
                                           } 

                                    }
                                     //Delete Old

                                    //crete new folder
                                  
                                    $targetDir = public_path() . $policePath .$userid.'/';

                                     $fileName = rand(0000,9999).basename($_FILES['police_record_img']['name']); 
                                     $targetFilePath = $targetDir . $fileName;

                                        // Check whether file type is valid 
                                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                        if(in_array($fileType, $allowTypes))
                                        { 
                                            // Upload file to server 
                                            if(move_uploaded_file($_FILES["police_record_img"]["tmp_name"], $targetFilePath))
                                            { 
                                                
                                              $update_Arr['updated_at'] = Carbon::now();
                                              $update_Arr['file_name'] = $fileName;  
                                              DB::table('user_certifications')->where('id', $polRecId)->update($update_Arr);

                                            }else
                                            { 
                                                 $errorUpload .= (__('alerts.frontend.company.profile.police_record_image_file_not_uploaded'));
                                            } 
                                        }else
                                        { 
                                            $errorUploadType .=(__('alerts.frontend.company.profile.police_record_image_file_type_not_match'));
                                        }  
                                 
                                }  
                          }


                           if(!empty($errorUpload))
                            {
                              return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUpload));exit;
                            }
                             if(!empty($errorUploadType))
                            {
                              return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUploadType));exit;
                            }

                  // End certification courses IMAGE

                  return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.profile_updated_successfully'));


                }

              public function deletePhotoVideosImage(Request $request)
               {

                  $userid= auth()->user()->id;
                  $gall_id=!empty($request->gall_id) ? $request->gall_id : '' ;
                  $video_id=!empty($request->video_id) ? $request->video_id : '' ;

                        if(!empty($userid) && !empty($gall_id)) 
                        {

                               $userEntity = DB::table('users')
                              ->whereRaw("(active=1)")
                              ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                              ->first();

                                if(!empty($userEntity))
                                {
                                        $getimg = DB::table('users_images_gallery')
                                        ->whereRaw("(id = '".$gall_id."' AND user_id = '".$userid."' AND deleted_at IS null )")->first();
                                        
                                        if(!empty($getimg))
                                        {
                                           //Profile picture

                                          $galleryPath='/img/company/gallery/images/'.$userid.'/';

                                             $targetDir = public_path() . $galleryPath;

                                              $Your_file_path= $targetDir.$getimg->file_name;

                                               if (file_exists($Your_file_path)) 
                                               {
                                                   unlink($Your_file_path);
                                               } 

                                             DB::table('users_images_gallery')->whereRaw("(id = '".$gall_id."' AND user_id = '".$userid."' AND deleted_at IS null )")->delete();

                                          return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.file_deleted_successfully'));
                                        }else
                                        {
                                            return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.image_not_found')); 
                                        }

                                }
                                else
                                {
                                   return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user')); 
                                }

                            
                              }elseif(!empty($userid) && !empty($video_id)) 
                              {

                                 $userEntity = DB::table('users')
                                ->whereRaw("(active=1)")
                                ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                                ->first();

                                if(!empty($userEntity))
                                {
                                        $getvdo = DB::table('users_videos_gallery')
                                        ->whereRaw("(id = '".$video_id."' AND user_id = '".$userid."' AND deleted_at IS null )")->first();
                                        //dd($getvdo);
                                        if(!empty($getvdo))
                                        {
                                           //Profile picture

                                                $galleryPath='/img/company/gallery/videos/'.$userid.'/';

                                                   $targetDir = public_path() . $galleryPath;

                                                    $Your_file_path= $targetDir.$getvdo->file_name;

                                                     if (file_exists($Your_file_path)) 
                                                     {
                                                         unlink($Your_file_path);
                                                     } 

                                                   DB::table('users_videos_gallery')->whereRaw("(id = '".$video_id."' AND user_id = '".$userid."' AND deleted_at IS null )")->delete();

                                                return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.file_deleted_successfully'));
                                        }else
                                        {
                                            return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.image_not_found')); 
                                        }

                                }
                                else
                                {
                                   return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user')); 
                                }

                            
                        }else
                        {
                            return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.profile.invalid_user')); 
                        }

             }


             public function updatePhotoVideosImage(Request $request)
                {
                   $userid= auth()->user()->id;
                    //echo "<pre>"; print_r($request->all());die;

                    $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")
                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                    ->first(); 

                    $path='/img/company/gallery/images/';
                    $pathv='/img/company/gallery/videos/';

                     $gallery_image=!empty($request->gallery_image) ? $request->gallery_image : '' ;
                     $gall_id=!empty($request->gall_id) ? $request->gall_id : '' ;

                     $gallery_video=!empty($request->gallery_video) ? $request->gallery_video : '' ;
                     $video_id=!empty($request->video_id) ? $request->video_id : '' ;


                   // start gallery IMAGE

                         if(!empty($gallery_image))
                          {
                            //error_reporting(0);

                                 $fileNames = $_FILES['gallery_image']['name'];

                                 $allowTypes = array('jpg','png','jpeg'); 
                                 
                                 $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                 if(!empty($fileNames) && $_FILES["gallery_image"]["error"] !== 4)
                                {
                                    //Delete Old
                                     $getAll = DB::table('users_images_gallery')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null)")->whereRaw("(id = '".$gall_id."')")->first();
                                      
                                    if(!empty($getAll))
                                    {
                                          $targetDir = public_path() . $path .$userEntity->id.'/';
                                          $Your_file_path= $targetDir.$getAll->file_name;
                                           if (file_exists($Your_file_path)) 
                                           {
                                               unlink($Your_file_path);
                                           } 

                                    }
                                     //Delete Old

                                    //crete new folder
                                  
                                    $targetDir = public_path() . $path .$userid.'/';

                                     $fileName = rand(0000,9999).basename($_FILES['gallery_image']['name']); 
                                     $targetFilePath = $targetDir . $fileName;

                                        // Check whether file type is valid 
                                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                        if(in_array($fileType, $allowTypes))
                                        { 
                                            // Upload file to server 
                                            if(move_uploaded_file($_FILES["gallery_image"]["tmp_name"], $targetFilePath))
                                            { 
                                                
                                              $update_Arr['updated_at'] = Carbon::now();
                                              $update_Arr['file_name'] = $fileName;  
                                              DB::table('users_images_gallery')->where('id', $gall_id)->update($update_Arr);

                                            }else
                                            { 
                                                 $errorUpload .= (__('alerts.frontend.company.profile.gallery_image_file_not_uploaded'));
                                            } 
                                        }else
                                        { 
                                            $errorUploadType .=(__('alerts.frontend.company.profile.gallery_image_file_type_not_match'));
                                        }  
                                 
                                }  
                          }

                          if(!empty($gallery_video))
                          {
                            //error_reporting(0);

                                 $fileNames = $_FILES['gallery_video']['name'];

                                 $allowTypes = array("webm", "mp4", "ogv");  
                                 
                                 $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                                 if(!empty($fileNames) && $_FILES["gallery_video"]["error"] !== 4)
                                {
                                    //Delete Old
                                     $getAll = DB::table('users_videos_gallery')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null)")->whereRaw("(id = '".$gall_id."')")->first();
                                      
                                    if(!empty($getAll))
                                    {
                                          $targetDir = public_path() . $pathv .$userEntity->id.'/';
                                          $Your_file_path= $targetDir.$getAll->file_name;
                                           if (file_exists($Your_file_path)) 
                                           {
                                               unlink($Your_file_path);
                                           } 

                                    }
                                     //Delete Old

                                    //crete new folder
                                  
                                    $targetDir = public_path() . $pathv .$userid.'/';

                                     $fileName = rand(0000,9999).basename($_FILES['gallery_video']['name']); 
                                     $targetFilePath = $targetDir . $fileName;

                                        // Check whether file type is valid 
                                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                                        if(in_array($fileType, $allowTypes))
                                        { 
                                            // Upload file to server 
                                            if(move_uploaded_file($_FILES["gallery_video"]["tmp_name"], $targetFilePath))
                                            { 
                                                
                                              $update_Arr['updated_at'] = Carbon::now();
                                              $update_Arr['file_name'] = $fileName;
                                           
                                              DB::table('users_videos_gallery')->where('id', $video_id)->update($update_Arr);

                                            }else
                                            { 
                                                 $errorUpload .= (__('alerts.frontend.company.profile.gallery_video_file_not_uploaded'));
                                            } 
                                        }else
                                        { 
                                            $errorUploadType .= (__('alerts.frontend.company.profile.gallery_video_file_type_not_match'));
                                        }  
                                 
                                }  
                          }

                           if(!empty($errorUpload))
                            {
                              return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUpload));exit;
                            }
                             if(!empty($errorUploadType))
                            {
                              return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__($errorUploadType));exit;
                            }

                  // End certification courses IMAGE

                  return redirect()->route('frontend.company.company_profile.my-profile')->withFlashSuccess(__('alerts.frontend.company.profile.profile_picture_updated_successfully'));


                }

                 public function insertConstractorBanner(Request $request)
                {
                   
                  $userId = $request->input('userid');
                  $base64_string = $request->input('image');
                  $data = explode(';', $base64_string);
                  $dataa = explode(',', $base64_string);
                  $part = explode("/", $data[0]);
                     
                    if (empty($part))
                      return false;
                    $file = rand(1111,9999) . '.'.$part[1];
                      if (!is_dir(public_path('img/company/banner/')))
                        mkdir(public_path('img/company/banner/'));
                      $ifp = fopen(public_path('/img/company/banner/'.$file), 'wb');
                      fwrite($ifp, base64_decode($dataa[1]));
                  // $folderPath = public_path() . '/img/company/banner/';
                  // $image_parts = explode(";base64,", $request->input('image'));
                  // $image_type_aux = explode("image/", $image_parts[0]);
                  // $image_type = $image_type_aux[1];
                  // $image_base64 = base64_decode($image_parts[1]);

                  // $f = finfo_open();
                  // $mime_type = finfo_buffer($f, $image_base64, FILEINFO_MIME_TYPE);

                  // $avtar =  $userId .'1'.'.png';
                  // $file = $folderPath . $avtar;
             
                  // file_put_contents($file, $image_base64);

                  $userData['banner'] =  $file;
                  $userData['updated_at'] = Carbon::now()->toDateTimeString();
                  DB::table('users')->where('id',$userId)->update($userData);

                  $getUserBanner = DB::table('users')->whereRaw("(id = '".$userId."' AND deleted_at IS null )")->first();
                  $banner="";
                 
                  if(isset($getUserBanner) && !empty($getUserBanner->banner))
                  {
                      $banner=url('/img/company/banner/'.$getUserBanner->banner);
                  }

                  return response()->json(['success' => true,'banner'=> $banner]);
              }


  //   public function updateBasic(Request $request)
  //   {
    // $username = isset($request->username) && !empty($request->username) ? $request->username : '' ;
    // $address = !empty($request->address) ? $request->address : '' ;
    // $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
    // $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
    // $avatar_location = !empty($request->avatar_location) ? $request->avatar_location : '' ;
    // $facebook_url = !empty($request->facebook_url) ? $request->facebook_url : '' ;
    // $instagram_url = !empty($request->instagram_url) ? $request->instagram_url : '' ;
    // $twitter_url = !empty($request->twitter_url) ? $request->twitter_url : '' ;
    // $youtube_url = !empty($request->youtube_url) ? $request->youtube_url : '' ;

    //  $userid= auth()->user()->id;

    //    $validator = Validator::make($request->all(), [
  //               'username' => 'required',
  //               'mobile_number' => 'required',
  //               ]);

  //               if($validator->fails())
  //               {
  //                return redirect()->route('frontend.contractor.profile')->withFlashDanger(__('Invalid parameter.'));
  //               }

  //                  $userEntity = DB::table('users')
  //                   ->whereRaw("(active=1)")->whereRaw("(confirmed=1)")->whereRaw("(is_verified=1)")
  //                   ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();

  //                   if(!empty($userEntity))
  //                   {
  //                    $profile = $userEntity->avatar_location;

  //                       if(isset($_FILES['avatar_location']['name']) && !empty($_FILES['avatar_location']['name']))
  //                           {
  //                               $extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
  //                               $filename = $userid.'.'.$extq;

  //                               $ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);

  //                               $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/contractor/profile/'.$filename);

  //                                $profile = $filename;
  //                           }



  //                       $userData['avatar_location'] =  $profile;
  //                       $userData['username'] =  $username;
  //                       $userData['mobile_number'] =  $mobile_number;
  //                       $userData['landline_number'] =  $landline_number;
  //                       $userData['address'] =  $address;
  //                       DB::table('users')->where('id',$userEntity->id)->update($userData);


    //        $socialNetw['facebook_url'] = $facebook_url;
    //        $socialNetw['instagram_url'] = $instagram_url;
    //        $socialNetw['twitter_url'] = $twitter_url;
    //        $socialNetw['youtube_url'] = $youtube_url;
    //        $socialNetw['updated_at'] = Carbon::now()->toDateTimeString();
    //        $socialNetw['user_id'] = $userEntity->id;
    //        $socialNetw['created_at'] = Carbon::now()->toDateTimeString();
    //        DB::table('social_networks')->update($socialNetw);

    //        return redirect()->route('frontend.contractor.profile')->withFlashSuccess(__('Profile Updated Successfully.!'));
  //                   }
  //                   else
  //                   {
  //                     return redirect()->route('frontend.contractor.profile')->withFlashDanger(__('Invalid user.'));

  //                   }

  //      }

  // public function myProfile()
  // {
  //    return view('frontend.contractor.my_profile');
  // }

  // public function myDocuments()
  // {
  //   return view('frontend.contractor.documentation');
  // }


public function updateBannerCompany(Request $request)
          {
           $userid= auth()->user()->id;
            //echo "<pre>"; print_r($request->all());die;
             $userEntity = DB::table('users')
            ->whereRaw("(active=1)")
            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
            ->first(); 

              $bannerPath ='/img/company/profile/';
              $banner_img=!empty($request->banner_img) ? $request->banner_img : '' ;
              $userid=!empty($request->user_id) ? $request->user_id : '' ;
                 // start certification courses IMAGE

                 if(!empty($banner_img))
                  {
                    //error_reporting(0);

                     $fileNames = $_FILES['banner_img']['name'];

                     $allowTypes = array('jpg','png','jpeg'); 
                     
                     $statusMsg = $errorMsg =  $errorUpload = $errorUploadType = ''; 

                     if(!empty($fileNames) && $_FILES["banner_img"]["error"] !== 4)
                    {
                        
                        if(!empty($userEntity->banner))
                        {
                              $targetDir = public_path() . $bannerPath;
                             $Your_file_path= $targetDir.$userEntity->banner;
                               if (file_exists($Your_file_path)) 
                               {
                                   unlink($Your_file_path);
                               } 

                        }
                         //Delete Old

                        //crete new folder
                      
                        $targetDir = public_path() . $bannerPath;
                        $userId = $request->input('userid');
                         $fileName = $userid .'1'.'.png';
                         /*$fileName = 'banner_'.$userid.'_'.basename($_FILES['banner_img']['name']);*/
                         $targetFilePath = $targetDir . $fileName;

                            // Check whether file type is valid 
                            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                            if(in_array($fileType, $allowTypes))
                            { 
                                // Upload file to server 
                                if(move_uploaded_file($_FILES["banner_img"]["tmp_name"], $targetFilePath))
                                { 
                                    
                                  $update_Arr['updated_at'] = Carbon::now();
                                  //$update_Arr['banner'] = $fileName;  
                                  //print_r($userid);exit;
                                  DB::table('users')->where('id', $userid)->update($update_Arr);

                                }else
                                { 
                                     $errorUpload .= (__('alerts.frontend.constractor.profile.banner_image_file_not_uploaded'));
                                } 
                            }else
                            { 
                                $errorUploadType .= (__('alerts.frontend.constractor.profile.banner_image_file_type_not_match'));
                            }  
                         
                        }  
                  }


                   if(!empty($errorUpload))
                    {

                      return redirect()->route('frontend.company.company_profile.mi-perfil')->withFlashDanger(__($errorUpload));exit;
                    }
                     if(!empty($errorUploadType))
                    {
                      
                      return redirect('company_profile/mi-perfil')->withFlashDanger(__($errorUploadType));exit;
                    }

                  // End certification courses IMAGE

                  return redirect('company_profile/mi-perfil')->withFlashSuccess(__('alerts.frontend.constractor.profile.profile_updated_successfully'));


                }
    public function paymentRequest(Request $request)
    {
       // echo '<pre>'; print_r($request->all());exit;

        // $paymentPrice=DB::table('assign_service_request')->where('service_request_id',$_REQUEST['serviceId'])->where('user_id',$_REQUEST['prouserId'])->where('id',$_REQUEST['requestid'])->first();
        //   $userId=DB::table('service_request')->where('id',$_REQUEST['serviceId'])->first();
        $user_id = auth()->user()->id;
        $client_user_id = !empty($request->prouserId) ? $request->prouserId : '' ;
        $service_amount =$request->amount;//isset($paymentPrice->credit)?$paymentPrice->credit:0;
        $request_id = !empty($request->requestid) ? $request->requestid : '' ;
        $subtotal = $service_amount;

        $sub_total_amount = $service_amount / 1.12;
                    $IVA = ($sub_total_amount * 12) / 100;
        $iva =$IVA;
       
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
                        ->where('assign_service_request.id', $request_id)
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
                            $paymentinsert['amount']=number_format($service_amount,2);
                            $paymentinsert['subtotal']=number_format($subtotal,2);
                            $paymentinsert['iva']=number_format($iva,2);
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
                                    ->update(['amount'=>number_format($service_amount,2),'subtotal'=>number_format($subtotal,2),'iva'=>number_format($iva,2)]); 
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
                            $this->postpushnotification($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
                     

                            $chatmessage='Solicitamos el pago de $'.$service_amount.' <br/>'.url("/service/payment?userid=".$client_user_id."&prouserId=".$user_id."&serviceId=".$serviceId); 

                            $insertchat['from_userid'] =$user_id;
                            $insertchat['to_userid'] = $client_user_id ;
                            $insertchat['message'] =$chatmessage;
                            $insertchat['is_read'] = 0;
                            $insertchat['type'] = 'Payment';
                            $insertchat['is_starred'] = 0;
                            $insertchat['created_at'] = Carbon::now();  
                            DB::table('users_chat')->insert($insertchat);
                          
                                $msg='Gracias por preferir Buskalo, el profesional ' .$profesionName->username.' te ha enviado una solicitud de pago con el siguiente detalle:';
                                $submsg='Para realizar su pago haca clic aqui';
                           

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

                            return redirect()->route('frontend.company.company_profile.jobs')->withFlashSuccess(__('apimessage.payment_request'));
                        
                    }
                    else
                    {
                        return redirect()->route('frontend.company.company_profile.jobs')->withFlashDanger(__('apimessage.payment_paid'));
                    }  
                }
                else
                {
                    return redirect()->route('frontend.company.company_profile.jobs')->withFlashDanger(__('apimessage.invalid_service_request'));
                }
            }
            else
            {
                return redirect()->route('frontend.contractor.jobs')->withFlashDanger(__('apimessage.Invalid user.'));
            }  
    }

    public function paymentRequestStore(Request $request)
    {
        $insert['user_id']=$request->userid;
        $insert['trans_id']=$request->response['transaction']['id'];
        $insert['service_id']=$request->serviceId;
        $insert['amount']=$request->response['transaction']['amount'];
        $insert['pro_id']=auth()->user()->id;
        $insert['status']='success';
        $userdata= DB::table('users')->where('id',auth()->user()->id)->first();
        $newCredit= $userdata->pro_credit+$request->response['transaction']['amount'];

        DB::table('users')->where('id',auth()->user()->id)->update(['pro_credit'=>$newCredit]);

        DB::table('user_payment_history')->insert($insert);
         echo 'success';
    }
   function postpushnotification($device_id,$title,$message,$userId=null,$prouserId=null,$serviceId=null,$senderid=null,$reciverid=null,$chattype=null,$senderName=null,$notify_type=null,$urlToken=null)
    {
        if(!empty($device_id))
        {
          $fields = array(
             'to' => $device_id,
              'data' =>array('title' => $title, 'message' => $message,'urlToken' => $urlToken,'userId'=>$userId,'prouserId'=>$prouserId,'serviceId'=>$serviceId, 'senderId'=>$senderid,'reciverId'=>$reciverid,'chatType'=>$chattype,'sendername'=>$senderName,'notify_type'=>$notify_type),
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
         // print_r($result);//die;
          sleep(5);
          if ($result === FALSE) {
              die('Curl failed: ' . curl_error($ch));
          }
          // Close connection
          curl_close($ch);
          return $result;    
    }


}
