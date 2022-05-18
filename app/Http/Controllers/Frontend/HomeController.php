<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests\ContractorRegisterRequest;
use App\Http\Requests\CompanyRegisterRequest;
use Carbon\Carbon;
use App\Models\Auth\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;
use App\Mail\ServiceRequestOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use App\Models\Work;
use Validator;
use Session,Hash;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;

date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $services = DB::table('services')->where('deleted_at',NULL)->get();
        foreach ($services as $key => $value) 
        {
            
            $subservices = array();
            $subservices = DB::table('sub_services')->where('services_id',$value->id)->where('deleted_at',NULL)->get();
            $value->subservices = $subservices;
        }

        $cities = DB::table('cities')->where('deleted_at',NULL)->get();
        $categories = DB::table('category')->where('status',1)->where('deleted_at',NULL)->get();

        //return redirect()->route('frontend.home_page');
        //return view('frontend.index',compact('services','cities','categories'));
        return view('frontend.first_screen',compact('services','cities'));
    }

    public function confrimApprovel()
    {   
        $user=array();
        if (Auth::user())
        {
            $user= User::where('id',auth()->user()->id)->first();
        }
        return view('frontend.approve_page',compact('user'));
    }

    public function homePage()
    {   

        $services = DB::table('services')->where('deleted_at',NULL)->whereNotNull('image')->where('image','!=','')->orderBy('id','desc')->get();
        $servicesnotonl= DB::table('services')
                    ->select('services.*')
                    ->leftjoin('category','category.id','=','services.category_id')
                    ->where('services.image','!=','')
                    ->where('services.deleted_at',NULL)
                    ->where('category.es_name','!=','SERVICIOS ONLINE')
                    ->get();
       $servicedata1=array();
        foreach ($services as $key => $service)
        {   $servicedata1[$key]['id']=$service->id;
            $servicedata1[$key]['servicetype']='service';
            $servicedata1[$key]['es_name']=$service->es_name;
           
        }
      
        $subservices = DB::table('sub_services')->where('deleted_at',NULL)->whereNotNull('image')->orderBy('id','desc')->get();
       $subservicess=array();
        foreach ($subservices as $k=> $subservice)
        {
            $subservicess[$k]['id']=$subservice->id;
            $subservicess[$k]['servicetype']='subservice';
            $subservicess[$k]['es_name']=$subservice->es_name;
        }
        $mainCatrgory1=array_merge($servicedata1,$subservicess);
      //echo '<pre>';print_r($mainCatrgory1);exit;

        $mainCatrgory = DB::table('category')->where('deleted_at',NULL)->where('status',1)->orderBy('id','asc')->get();
        foreach ($services as $key => $value) 
        {
            
            $subservices = array();
            $subservices = DB::table('sub_services')->where('services_id',$value->id)->where('deleted_at',NULL)->get();
            $value->subservices = $subservices;
        }

        $cities = DB::table('cities')->where('deleted_at',NULL)->get();
        $categories = DB::table('category')->where('status',1)->where('deleted_at',NULL)->orderBy('id', 'asc')->paginate(12);

        $work = DB::table('how_it_is_work')->where('id',1)->get();

        $review_datas = DB::table('reviews')
           ->join('users as U1','reviews.user_id','=','U1.id')
           ->join('users as U2','reviews.to_user_id','=','U2.id')
           ->join('service_request','reviews.request_id','=','service_request.id')
           ->select('U1.username','U1.mobile_number', 'U1.avatar_location','U2.username as provider_name','reviews.*')
           ->where('reviews.deleted_at',NULL)
           ->where('reviews.admin_appovel',1)
           ->get();

       
        return view('frontend.index',compact('services','cities','categories', 'work', 'review_datas','mainCatrgory','mainCatrgory1','servicesnotonl'));
        //return view('frontend.first_screen',compact('services','cities'));
    }

    public function userSelection(Request $request)
    {
        $request_data = $request->all();
        if(empty($request_data ))
        {
            return redirect()->to('profesional/register');
        }
        $user_group_id =  Session::get('user_group_id');
        $user_group_id = isset($request->user_group_id) && !empty($request->user_group_id) ? $request->user_group_id : '' ;

        return view('frontend.auth.register',compact('user_group_id'));
    }
    public function redirectRegisterType(Request $request)
    {
        $request_data = $request->all();
        if(empty($request_data ))
        {
            return redirect()->to('login');
        }
        $user_group_id =  Session::get('user_group_id');
        $user_group_id = isset($request->user_group_id) && !empty($request->user_group_id) ? $request->user_group_id : '' ;

        return view('frontend.auth.register',compact('user_group_id'));
    }

    public function userSelectionNew(Request $request)
    {   
        Session::put('user_group_id','');

        $user_group_id = isset($request->user_group_id) && !empty($request->user_group_id) ? $request->user_group_id : '3' ;

        Session::put('user_group_id', $user_group_id);

        echo json_encode(['success' => true, 'message' => 'success']);
             
    } 

    public function redirectRegister(Request $request)
    {   
        $request_data = $request->all();
            Session::forget('user_group_id');
        $user_group_id =  Session::get('user_group_id');
      
        return view('frontend.auth.register',compact('user_group_id', 'request_data'));
    }  


     /*First screen function start*/
    public function firstScreen()
    {   
        $services = DB::table('services')->where('deleted_at',NULL)->get();
        foreach ($services as $key => $value) {
            
            $subservices = array();
            $subservices = DB::table('sub_services')->where('services_id',$value->id)->where('deleted_at',NULL)->get();
            $value->subservices = $subservices;
        }

        $cities = DB::table('cities')->where('deleted_at',NULL)->get();
        
        return view('frontend.first_screen',compact('services','cities'));
        //return view('frontend.service_online',compact('services'));
    }   
    /*First screen function end*/

    /*secondScreen function start*/
    public function secondScreen()
    {
        //$userId= auth()->user()->id;
        $userId= 4;
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


        //////////////////////users Documents/////////////////


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

        return view('frontend.contractor.my_profile')
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
    /*secondScreen function end*/


    public function insertContractorProfile(Request $request)
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

                $avtar =  $userId.''.rand(1111,9999).'.png';
                $file = $folderPath . $avtar;
          
                file_put_contents($file, $image_base64);

                $userData['avatar_location'] =  $avtar;
                $userData['updated_at'] = Carbon::now()->toDateTimeString();
                DB::table('users')->where('id',$userId)->update($userData);


                $getUserProfile = DB::table('users')->whereRaw("(id = '".$userId."' AND deleted_at IS null )")->first();

                 $profile="";
                if(isset($getUserProfile) && !empty($getUserProfile->avatar_location))
                {
                     $profile=url('/img/contractor/profile/'.$getUserProfile->avatar_location);
                }

                return response()->json(['success' => true,'profile'=> $profile]);

   }

    public function insertUserProfile(Request $request)
    {
         
          $image = $request->input('image');
          $userId = $request->input('userid');

            $folderPath = public_path() . '/img/user/profile/';
            $image_parts = explode(";base64,", $request->input('image'));
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);


            $f = finfo_open();
            $mime_type = finfo_buffer($f, $image_base64, FILEINFO_MIME_TYPE);

            $avtar =  $userId.'_' .rand(1111,9999). '.png';
            $file = $folderPath . $avtar;
       
            file_put_contents($file, $image_base64);

            $userData['avatar_location'] =  $avtar;
            $userData['banner'] =  $avtar; 
            $userData['updated_at'] = Carbon::now()->toDateTimeString();
            DB::table('users')->where('id',$userId)->update($userData);

            $getUserProfile = DB::table('users')->whereRaw("(id = '".$userId."' AND deleted_at IS null )")->first();

             $profile="";
            if(isset($getUserProfile) && !empty($getUserProfile->avatar_location))
            {
                 $profile=url('/img/user/profile/'.$getUserProfile->avatar_location);
            }

            return response()->json(['success' => true,'profile'=> $profile]);

   }


    /*redirectContractor function start*/
    public function redirectContractor(Request $request,$userid)
    {
        $userid = Crypt::decrypt($userid);

        $getUser = DB::table('users')->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();
        $socialnetwork=DB::table('social_networks')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null )")->first();
        $paymentmethod=DB::table('user_payment_methods')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null )")->pluck('payment_method_id')->toArray();
        // echo '<pre>'; print_r($paymentmethod);exit;
        //dd($paymentmethod);
        $profile="";
        if(isset($getUser) && !empty($getUser->avatar_location))
        {
           $profile=url('/img/contractor/profile/'.$getUser->avatar_location);
        }
        

        $payment_methods = DB::table('payment_methods')->where('deleted_at',NULL)->get();
        $services = DB::table('services')->where('status','1')->where('deleted_at',NULL)->get();

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

        //echo "<pre>"; print_r($combinedData);die;
        
        $provinces=DB::table('provinces')->where('status','1')->whereRaw("(deleted_at IS null )")->orderBy('name','Asc')->get();

        $cities=DB::table('cities')->where('status','1')->whereRaw("(deleted_at IS null )")->orderBy('name','Asc')->get()->toArray();
        $serprovince=DB::table('users_services_area')->where('user_id',$userid)->pluck('province_id')->toArray();
         $sercity=DB::table('users_services_area')->where('user_id',$userid)->pluck('city_id')->toArray();
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

        if(isset($getUser) && !empty($getUser) && $getUser->is_confirm_reg_step==1)
        {
            return redirect()->route('frontend.auth.login')->withFlashSuccess(__('alerts.frontend.home.home.your_registration_step_has_already_been_completed'));

        } else  {
             return view('frontend.contractor_profile', compact('socialnetwork','paymentmethod','serprovince','sercity'))
            ->withCities($cities)
            ->withProfile($profile)
            ->withGetUser($getUser)
            ->withMixdata($allData)
            ->withCombineddata($combinedData)
            ->withProvinces($provinces)
            ->withServices($services)
            ->withPaymentMethods($payment_methods)
            ->withUserId($userid);
         }
    }
    /*redirectContractor*/

    public function insertCompanyProfile(Request $request)
    {

        $image = $request->input('image');
        $userId = $request->input('userid');
        $folderPath = public_path() . '/img/company/profile/';
        $image_parts = explode(";base64,", $request->input('image'));
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $f = finfo_open();
        $mime_type = finfo_buffer($f, $image_base64, FILEINFO_MIME_TYPE);

        $avtar =  $userId.''.rand(11111,99999).'.jpg';
        $file = $folderPath . $avtar;
   
        file_put_contents($file, $image_base64);

        $userData['avatar_location'] =  $avtar;
        $userData['updated_at'] = Carbon::now()->toDateTimeString();
        DB::table('users')->where('id',$userId)->update($userData);

        $getUserProfile = DB::table('users')->whereRaw("(id = '".$userId."' AND deleted_at IS null )")->first();
        $profile="";
       
        if(isset($getUserProfile) && !empty($getUserProfile->avatar_location))
        {
            $profile=url('/img/company/profile/'.$getUserProfile->avatar_location);
        }

       
        return response()->json(['success' => true,'profile'=> $profile]);
    }


    /*redirectCompany function start*/
    public function redirectCompany(Request $request,$userid)
    {
        $userid = Crypt::decrypt($userid);

        $getUser = DB::table('users')->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();
        $socialnetwork=DB::table('social_networks')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null )")->first();
        $paymentmethod=DB::table('user_payment_methods')->whereRaw("(user_id = '".$userid."' AND deleted_at IS null )")->pluck('payment_method_id')->toArray();
        $profile="";
        if(isset($getUser) && !empty($getUser->avatar_location))
        {
           $profile=url('/img/company/profile/'.$getUser->avatar_location);
        }

        $payment_methods = DB::table('payment_methods')->where('deleted_at',NULL)->get();
        $services = DB::table('services')->where('deleted_at',NULL)->get();

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
        
        $provinces=DB::table('provinces')->where('status','1')->whereRaw("(deleted_at IS null )")->orderBy('name','Asc')->get();

        $cities=DB::table('cities')->where('status','1')->whereRaw("(deleted_at IS null )")->orderBy('name','Asc')->get()->toArray();
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

        if(isset($getUser) && !empty($getUser) && $getUser->is_confirm_reg_step==1)
        {
            return redirect()->route('frontend.auth.login')->withFlashSuccess(__('alerts.frontend.home.home.your_registration_step_is_now_complete'));

        }else  {
            return view('frontend.company_profile',compact('socialnetwork','paymentmethod'))
              ->withCities($cities)
              ->withProfile($profile)
              ->withGetUser($getUser)
              ->withMixdata($allData)
              ->withCombineddata($combinedData)
              ->withProvinces($provinces)
              ->withServices($services)
              ->withPaymentMethods($payment_methods)
              ->withUserId($userid);
        }
    }
    /*redirectCompany function end*/

    /*companyProfileCompletion start*/
    //Request $request
    public function companyProfileCompletion(CompanyRegisterRequest $request) 
    {
            
        $userData=array();
        $userId=!empty($request->user_id) ? $request->user_id : '' ;

        $mobile_number = !empty($request->mobile_number) ? $request->mobile_number: '' ;
        $checkapprovl=DB::table('users')->where('id', $userId)->first();
        if($checkapprovl->approval_status==2)
        {
            DB::table('users')->where('id', $userId)->update(['approval_status'=>0]);
        }
        $mobileexist = DB::table('users')->whereRaw("(mobile_number = '".$mobile_number."' AND deleted_at IS null )")->where('id', '!=' , $userId)->first();

        // if(!empty($mobileexist))
        // {
        //     return redirect()->back()->withFlashDanger('El campo número de teléfono ya esa en uso');
        // }
            //Multiple
        $images_gallery=!empty($request->images_gallery) ? $request->images_gallery : '' ;
        $videos_gallery=!empty($request->videos_gallery) ? $request->videos_gallery : '' ;

        $facebook_url=!empty($request->facebook_url) ? $request->facebook_url : '' ;
        $instagram_url=!empty($request->instagram_url) ? $request->instagram_url : '' ;
        $linkedin_url=!empty($request->linkedin_url) ? $request->linkedin_url : '' ;
        $twitter_url=!empty($request->twitter_url) ? $request->twitter_url : '' ;
        $other_url=!empty($request->other) ? $request->other : '' ;

        //$username = !empty($request->username) ? $request->username : '' ;
        $ruc_no= !empty($request->ruc_no) ? $request->ruc_no : '' ;
        $year_of_constitution = !empty($request->year_of_constitution) ? $request->year_of_constitution : '' ;
        $legal_representative = !empty($request->legal_representative) ? $request->legal_representative : '' ;
        $address = !empty($request->address) ? $request->address : '' ;
        $website_address = !empty($request->website_address) ? $request->website_address: '' ;
        $landline_number = !empty($request->landline_number) ? $request->landline_number: '' ;
        $office_number = !empty($request->office_number) ? $request->office_number: '' ;
        $identity_no = !empty($request->identity_no) ? $request->identity_no: '' ;
        $username = !empty($request->username) ? $request->username: '' ;
        $profileTitle = !empty($request->profile_title) ? $request->profile_title: '' ;
        $office_address = !empty($request->office_address) ? $request->office_address: '' ;

 
        // $validator = Validator::make($request->all(), [
        //  'username' => 'required',
        //  'address' => 'required',
        //  'ruc_no' => 'required',
        //  'legal_representative' => 'required',
        //  'mobile_number' => 'required',
        //  'user_id' => 'required',
        // ]);

        // if($validator->fails())
        // {
        //     return redirect()->route('frontend.index')->withFlashDanger(__('Invalid Parameters'));exit;    
        // }
       
        // $profile = NULL;
        // $profile = !empty($request->avtar_name) ? $request->avtar_name : '' ;
        
        // if(isset($_FILES['avatar_location']['name']) && !empty($_FILES['avatar_location']['name']))
        // {
        //     $extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
        //     $filename = $userId.'.'.$extq;

        //     $ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
            
        //     $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/company/profile/'.$filename);
           
        //     $profile = $filename;
        // }

       

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
                $comp_gal_img_folder = public_path() . '/img/company/gallery/images/'.$userId;

                if(!empty($getAll))
                {
                    DB::table('users_images_gallery')->where('user_id', '=', $userId)->delete();
                                       
                    if(is_dir($comp_gal_img_folder)){

                        $deleteOld = $this->delete_directory(public_path() . '/img/company/gallery/images/'.$userId);
                    }

                }
                //Delete Old

                if(is_dir($comp_gal_img_folder)) {
                    $deleteOld = $this->delete_directory(public_path() . '/img/company/gallery/images/'.$userId);
                } else {
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
                             $errorUpload .= (__('alerts.frontend.home.home.image_not_uploaded'));
                        } 
                    }else
                    { 
                        $errorUploadType .= (__('alerts.frontend.home.home.images_type_allowed_only'));
                    }  
                }
            }  
        }
        
        if(!empty($errorUpload))
        {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUpload));exit;
        }
        
        if(!empty($errorUploadType))
        {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUploadType));exit;
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
                
                $comp_gal_vid_folder = public_path() . '/img/company/gallery/videos/'.$userId;

                if(!empty($getAll))
                {
                    DB::table('users_videos_gallery')->where('user_id', '=', $userId)->delete();
                    if(is_dir($comp_gal_vid_folder)){
                         //Delete Old
                        $deleteOld = $this->delete_directory(public_path() . '/img/company/gallery/videos/'.$userId);
                    }
                }
               

                if(is_dir($comp_gal_vid_folder)){
                    $deleteOld = $this->delete_directory(public_path() . '/img/company/gallery/videos/'.$userId);
                }else {
                      //create new folder
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
                             $errorUpload .= (__('alerts.frontend.home.home.video_not_uploaded'));
                        } 
                    }else
                    { 
                        $errorUploadType .= (__('alerts.frontend.home.home.video_type_allowed_only'));
                    }  
                }
            }  
        }


        if(!empty($errorUpload))
        {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUpload));exit;
        }
        if(!empty($errorUploadType))
        {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUploadType));exit;
        }
        // Add Gallery Videos

         //Services Area
        $whole_country=!empty($request->whole_country) ? $request->whole_country : '0' ;
        $proviences=!empty($request->proviences) ? $request->proviences : '' ;
        $cities=!empty($request->cities) ? $request->cities : '0' ;

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

        if(isset($facebook_url) || isset($instagram_url) || isset($linkedin_url) || isset($twitter_url) || isset($other_url) ) 
        {
            $socialData['facebook_url'] =  $facebook_url;
            $socialData['instagram_url'] =  $instagram_url;
            $socialData['linkedin_url'] =  $linkedin_url;
            $socialData['twitter_url'] =  $twitter_url;
            $socialData['other'] =  $other_url;
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

                        $comp_cert_folder = public_path() . '/img/company/certifications/'.$userId;
                                  
                        if(!empty($getAll))
                        {
                            DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '0')->delete();
                            if(is_dir($comp_cert_folder)){

                                $deleteOld = $this->delete_directory(public_path() . '/img/company/certifications/'.$userId);
                            }

                        }
                        //Delete Old

                        if(is_dir($comp_cert_folder)) {

                            $deleteOld = $this->delete_directory(public_path() . '/img/company/certifications/'.$userId);

                        } else {                            
                            //crete new folder
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
                                    $insert['certification_type'] = 0;
                                    $insert['user_id'] = $userId;
                                    $insert['status'] = 1;
                                    $insert['created_at'] = Carbon::now()->toDateTimeString(); 
                                    DB::table('user_certifications')->insertGetId($insert); 
                                }else
                                { 
                                     $errorUpload .= (__('alerts.frontend.home.home.certification_file_not_uploaded'));
                                } 
                            }else
                            { 
                                $errorUploadType .= (__('alerts.frontend.home.home.file_type_not_match'));
                            }  
                        }
                    }  
                }


            if(!empty($errorUpload))
            {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUpload));exit;
            }
            
            if(!empty($errorUploadType))
            {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUploadType));exit;
            }
        }

        // End certification courses

        //$userData['username'] =  $username;
        $userData['ruc_no'] =  $ruc_no;
        $userData['year_of_constitution'] = $year_of_constitution;
        $userData['legal_representative'] = $legal_representative;
        $userData['address'] =  $address;
        $userData['mobile_number'] = $mobile_number;
        $userData['office_number'] = $office_number;
        $userData['landline_number'] = $landline_number;
        $userData['website_address'] = $website_address;
        $userData['identity_no'] = $identity_no;
        $userData['username'] = $username;
        $userData['profile_title'] = $profileTitle;
        $userData['office_address'] = $office_address;
        $userData['is_verified'] =1;
       // $userData['confirmed'] = 0;
        $userData['is_confirm_reg_step'] = 1;
        // $userData['avatar_location'] =  $profile;
        $userData['updated_at'] = Carbon::now()->toDateTimeString();
        DB::table('users')->where('id',$userId)->update($userData);
        auth()->logout();
        Session::forget('userId');
        return redirect()->route('frontend.approvel_page')->withFlashSuccess(__('alerts.frontend.home.home.profile_completed_succefully'));

    }
    /*companyProfileCompletion function end*/

    /*contractorProfileCompletion start*/
    // Request $request
    public function contractorProfileCompletion(ContractorRegisterRequest $request,User $user)
    {
        $userData=array();
        $userId=!empty($request->user_id) ? $request->user_id : '' ;
        $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
        $checkapprovl=DB::table('users')->where('id', $userId)->first();
        if($checkapprovl->approval_status==2)
        {
            DB::table('users')->where('id', $userId)->update(['approval_status'=>0]);
        }
        $mobileexist = DB::table('users')->whereRaw("(mobile_number = '".$mobile_number."' AND deleted_at IS null )")->where('id', '!=' , $userId)->first();

        // if(!empty($mobileexist))
        // {
        //     return redirect()->back()->withFlashDanger('El campo número de teléfono ya esa en uso');
        // }
            //Multiple
        $images_gallery=!empty($request->images_gallery) ? $request->images_gallery : '' ;
        $videos_gallery=!empty($request->videos_gallery) ? $request->videos_gallery : '' ;

        $facebook_url=!empty($request->facebook_url) ? $request->facebook_url : '' ;
        $instagram_url=!empty($request->instagram_url) ? $request->instagram_url : '' ;
        $linkedin_url=!empty($request->linkedin_url) ? $request->linkedin_url : '' ;
        $twitter_url=!empty($request->twitter_url) ? $request->twitter_url : '' ;
        $other_url=!empty($request->other) ? $request->other : '' ;

        $username = !empty($request->username) ? $request->username : '' ;
        $identity_no= !empty($request->identity_no) ? $request->identity_no : '' ;
        $profile_title = !empty($request->profile_title) ? $request->profile_title : '' ;
        $address = !empty($request->address) ? $request->address : '' ;
        $dob = !empty($request->dob) ? $request->dob : '' ;
        $website_address = !empty($request->website_address) ? $request->website_address : '' ;
        $office_address = !empty($request->office_address) ? $request->office_address : '' ;
       
        $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
        $office_number = !empty($request->office_number) ? $request->office_number : '' ;


        //echo "<pre>";print_r($request->all());die;

        // $validator = Validator::make($request->all(), [
        //  'username' => 'required',
        //  'mobile_number' => 'required',
        //  'user_id' => 'required',
        // ]);

        // if($validator->fails())
        // {
        //     return redirect()->route('frontend.index')->withFlashDanger(__('Invalid Parameters'));exit;    
        // }


        // $profile = NULL;
        // if(isset($_FILES['avatar_location']['name']) && !empty($_FILES['avatar_location']['name']))
        // {
        //     $extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
        //     $filename = $userId.'.'.$extq;
        //     $ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
        //     $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/contractor/profile/'.$filename);
                               
        //     $profile = $filename;
        // }


      
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

                $contr_img_gal_folder = public_path() . '/img/contractor/gallery/images/'.$userId;

                if(!empty($getAll))
                {
                    DB::table('users_images_gallery')->where('user_id', '=', $userId)->delete();
                    if(is_dir($contr_img_gal_folder)){

                        $deleteOld = $this->delete_directory(public_path() . '/img/contractor/gallery/images/'.$userId);
                    }
                }
                 //Delete Old

                if(is_dir($contr_img_gal_folder)){
                     $deleteOld = $this->delete_directory(public_path() . '/img/contractor/gallery/images/'.$userId);
                   
                }else{
                    //crete new folder
                    mkdir(public_path() . '/img/contractor/gallery/images/'.$userId, 0777, true);

                }


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
                             $errorUpload .= (__('alerts.frontend.home.home.image_not_uploaded')); 
                        } 
                    }else
                    { 
                        $errorUploadType .= (__('alerts.frontend.home.home.images_type_allowed_only'));
                    }  
                }
            }  
        }

        if(!empty($errorUpload))
        {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUpload));exit;
        }
        if(!empty($errorUploadType))
        {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUploadType));exit;
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
                
                $contr_img_vid_folder = public_path() . '/img/contractor/gallery/videos/'.$userId;

                if(!empty($getAll))
                {
                    DB::table('users_videos_gallery')->where('user_id', '=', $userId)->delete();
                    
                    if(is_dir($contr_img_vid_folder)){

                        $deleteOld = $this->delete_directory(public_path() . '/img/contractor/gallery/videos/'.$userId);
                    }

                }
                //Delete Old

                if(is_dir($contr_img_vid_folder)){

                    $deleteOld = $this->delete_directory(public_path() . '/img/contractor/gallery/videos/'.$userId);
                }else {

                    //create new folder
                    mkdir(public_path() . '/img/contractor/gallery/videos/'.$userId, 0777, true);
                }

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
                             $errorUpload .= (__('alerts.frontend.home.home.video_not_uploaded')); 
                        } 
                    }else
                    { 
                        $errorUploadType .= (__('alerts.frontend.home.home.video_type_allowed_only'));
                    }  
                }
            }  
        }


        if(!empty($errorUpload))
        {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUpload));exit;
        }
         if(!empty($errorUploadType))
        {
            return redirect()->route('frontend.index')->withFlashDanger(__($errorUploadType));exit;
        }
        
        // Add Gallery Videos

        //Services Area
        $whole_country=!empty($request->whole_country) ? $request->whole_country : '0' ;
        $proviences=!empty($request->proviences) ? $request->proviences : '0' ;
        $cities=!empty($request->cities) ? $request->cities : '0' ;

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

        if(isset($facebook_url) || isset($instagram_url) || isset($linkedin_url) || isset($twitter_url) || isset($other_url) ) 
        {
            $socialData['facebook_url'] =  $facebook_url;
            $socialData['instagram_url'] =  $instagram_url;
            $socialData['linkedin_url'] =  $linkedin_url;
            $socialData['twitter_url'] =  $twitter_url;
            $socialData['other'] =  $other_url;
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

                    $contr_pol_rec_folder = public_path() . '/img/contractor/police_records/'.$userId;
                                          
                    if(!empty($getAll))
                    {
                        DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '1')->delete();
                        
                        if(is_dir($contr_pol_rec_folder)){

                            $deleteOld = $this->delete_directory(public_path() . '/img/contractor/police_records/'.$userId);
                        }
                    }
                     //Delete Old

                    if(is_dir($contr_pol_rec_folder)){

                        $deleteOld = $this->delete_directory(public_path() . '/img/contractor/police_records/'.$userId);
                    } else {

                        //crete new folder
                        mkdir(public_path() . '/img/contractor/police_records/'.$userId, 0777, true);
                    }


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
                                $insert['file_type'] = $record_type;
                                $insert['certification_type'] = '1';
                                $insert['user_id'] = $userId;
                                $insert['status'] = 1;
                                $insert['created_at'] = Carbon::now();  
                                DB::table('user_certifications')->insertGetId($insert); 
                            }else
                            { 
                                 $errorUpload .= (__('alerts.frontend.home.home.record_file_not_uploaded')); 
                            } 
                        }else
                        { 
                                $errorUploadType .= (__('alerts.frontend.home.home.file_type_not_match')); 
                        }  
                    }
                }  
            }


            if(!empty($errorUpload))
            {
                return redirect()->route('frontend.index')->withFlashDanger(__($errorUpload));exit;
            }
            if(!empty($errorUploadType))
            {
                return redirect()->route('frontend.index')->withFlashDanger(__($errorUploadType));exit;
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

                    $contr_cert_folder = public_path() . '/img/contractor/certifications/'.$userId;
                                      
                    if(!empty($getAll))
                    {
                        DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '0')->delete();
                        
                        if(is_dir($contr_cert_folder)){

                            $deleteOld = $this->delete_directory(public_path() . '/img/contractor/certifications/'.$userId);
                        }
                    }
                     //Delete Old

                    if(is_dir($contr_cert_folder)){

                        $deleteOld = $this->delete_directory(public_path() . '/img/contractor/certifications/'.$userId);
                    }else {

                        //crete new folder
                        mkdir(public_path() . '/img/contractor/certifications/'.$userId, 0777, true);
                    }

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
                                $insert['file_type'] = $certification_type;
                                $insert['certification_type'] = 0;
                                $insert['user_id'] = $userId;
                                $insert['status'] = 1;
                                $insert['created_at'] = Carbon::now()->toDateTimeString(); 
                                DB::table('user_certifications')->insertGetId($insert); 
                            }else
                            { 
                                 $errorUpload .= (__('alerts.frontend.home.home.certification_file_not_uploaded')); 
                            } 
                        }else
                        { 
                            $errorUploadType .= (__('alerts.frontend.home.home.file_type_not_match')); 
                        }  
                    }
                }  
            }


            if(!empty($errorUpload))
            {
                return redirect()->route('frontend.index')->withFlashDanger(__($errorUpload));exit;
            }
            if(!empty($errorUploadType))
            {
                return redirect()->route('frontend.index')->withFlashDanger(__($errorUploadType));exit;
            }
        }

        // End certification courses


          //$userData['username'] =  $username;
        $userData['identity_no'] =  $identity_no;
        $userData['profile_title'] = $profile_title;
        $userData['address'] =  $address;
        $userData['landline_number'] = $landline_number;
        $userData['dob'] = $dob;
        $userData['website_address'] = $website_address;
        $userData['username'] = $username;
        $userData['office_address'] = $office_address;
        $userData['mobile_number'] = $mobile_number;
        $userData['office_number'] = $office_number;
        $userData['is_verified'] =1;
        //$userData['confirmed'] = 0;
        $userData['is_confirm_reg_step'] = 1;
        // $userData['avatar_location'] =  $profile;
        $userData['updated_at'] = Carbon::now()->toDateTimeString();
        DB::table('users')->where('id',$userId)->update($userData);

        auth()->logout();
        Session::forget('userId');
        return redirect()->route('frontend.approvel_page')->withFlashSuccess(__('alerts.frontend.home.home.profile_completed_succefully'));

    }
    /*contractorProfileCompletion function end*/  



    /*AJAX request*/

   public function autoCompleteSearch(Request $request)
   {

      $search_key = $request->search;
      $allData=array();
      if($search_key == '')
      {
         $allData=DB::table('category')->select('id','en_name')
        ->where('status',1)
        ->whereRaw("(deleted_at IS null)")->get()->toArray();

      }else
       {
        
             $getServices=DB::table('services')->select('id','en_name')
            ->where('en_name', 'LIKE', "%$search_key%")
            ->where('status',1)
            ->whereRaw("(deleted_at IS null)")->get()->toArray(); 

             $getSubServices=DB::table('sub_services')->select('id','en_name')
            ->where('en_name', 'LIKE', "%$search_key%")
            ->where('status',1)
            ->whereRaw("(deleted_at IS null)")->get()->toArray();

             $getChildSubServices=DB::table('child_sub_services')->select('id','en_name')
            ->where('en_name', 'LIKE', "%$search_key%")
            ->where('status',1)
            ->whereRaw("(deleted_at IS null)")->get()->toArray();

                    if(!empty($getServices))
                    {
                        foreach ($getServices as $service) 
                        {
                            $arr2['id'] =  isset($service) && !empty($service->id) ? $service->id : '' ;
                            $arr2['en_name'] =  isset($service) && !empty($service->en_name) ? $service->en_name : '' ;  
                            $arr2['type'] = 'service' ;
                            array_push($allData, $arr2);
                        }
                    }

                    if(!empty($getSubServices))
                    {
                       foreach ($getSubServices as $sub) 
                        {
                            $arr3['id'] =  isset($sub) && !empty($sub->id) ? $sub->id : '' ;
                            $arr3['en_name'] =  isset($sub) && !empty($sub->en_name) ? $sub->en_name : '' ;  
                            $arr3['type'] = 'sub_service' ;
                            array_push($allData, $arr3);
                        }
                    }

                    if(!empty($getChildSubServices))
                    {
                        foreach ($getChildSubServices as $child) 
                        {
                            $arr4['id'] =  isset($child) && !empty($child->id) ? $child->id : '' ;
                            $arr4['en_name'] =  isset($child) && !empty($child->en_name) ? $child->en_name : '' ;  
                            $arr4['type'] = 'child_sub_service' ;
                            array_push($allData, $arr4);
                        }
                    }
        }

          if($allData)
          {
            echo json_encode(['success' => true,'allData'=> $allData, 'message' => 'foundCategory']);
          } 
          else
          {
            echo json_encode(['success' => true,'allData'=> '', 'message' => 'notFoundCategory']);
          }
   }



   public function stepOne(Request $request)
   {
       //dd($request->all());
       $catId = isset($request->category_id) && !empty($request->category_id) ? $request->category_id : '' ;

       $citId = isset($request->city_id) && !empty($request->city_id) ? $request->city_id : '';
       $selected_type = isset($request->selected_type) && !empty($request->selected_type) ? $request->selected_type : '';
       
       $category_id = Crypt::encrypt($catId);

       $city_id = Crypt::encrypt($citId);
       $selected_type = Crypt::encrypt($selected_type);

       return redirect()->route('frontend.category_step', ['category_id' => $category_id, 'city_id' => $city_id , 'selected_type' => $selected_type]);
   }


        public function categoryStep($category_id,$city_id,$selected_type) 
        {  

            $category_id = Crypt::decrypt($category_id);

            $bannerImages= DB::table('banners')->where('cat_id',$category_id)->get();
            $city_id = Crypt::decrypt($city_id);
            $selected_type = Crypt::decrypt($selected_type);
            DB::table('questions')->update(['question_status'=>0]);
            if(empty($selected_type))
            {
                 return redirect()->route('frontend.home_page')->withFlashDanger(__('Please select Categorías'));
            }
            // echo $category_id.'<br/>'.$city_id .'<br/>'.$selected_type; exit;
            $user_id = 0;
            if(empty($category_id))
            {
                 return redirect()->back()->withFlashDanger('Seleccione categorías');
            }
            if(auth()->user()) 
            {
                $user_id = auth()->user()->id;
                $user_detail = DB::table('users')->where('id',$user_id)->first();
            }

          
           
            if($selected_type=='category')
            {
               $getCategoryData=DB::table('category')->select('es_name')->where('id',$category_id)->first(); 
               $allServices = DB::table('services')->whereRaw("(category_id = '".$category_id."' AND deleted_at IS null )")->get();
            }
           
            $getCityData=DB::table('cities')->select('name')->where('id',$city_id)->first();
            
            $allSubServices=array();
            $allChildServices=array();
            $questionArr=array();
            $firstQuestID="";
            $servicename="";
            $serviceId="";
            $subServiceId="";
            $subservicename="";
            $childsubservicename="";
            $child_sub_serviceId="";

            if($selected_type=='service')
            {

                $getServicename = DB::table('services')->where('id',$category_id)->where('deleted_at',NULL)->first();
                $service= DB::table('services')->whereRaw("(id = '".$category_id."' AND deleted_at IS null )")->first();
                $allServices= DB::table('services')->whereRaw("(category_id = '".$service->category_id."' AND deleted_at IS null )")->get();
                $getCategoryData=DB::table('category')->select('es_name')->where('id',$service->category_id)->first();
            }

            if($selected_type=='service')
            {
              $serviceId=$category_id;

              $allSubServices = DB::table('sub_services')->where('services_id',$serviceId)->where('deleted_at',NULL)->get();

              $category_id=DB::table('services')->where('id',$serviceId)->value('category_id');

              //$getServicename = DB::table('services')->where('id',$serviceId)->where('deleted_at',NULL)->first();

              if(!empty($getServicename)) { $servicename=$getServicename->es_name; }

              //$getCategoryData=DB::table('category')->select('en_name')->where('id',$category_id)->first();

             // $allServices = DB::table('services')->whereRaw("(category_id = '".$category_id."' AND deleted_at IS null )")->get();
      
            }

            $nextdata=[];
             $dependent=[];
             $currentdata=[];
            if($selected_type=='sub_service' ||$selected_type=='subservice')
            {

                $selected_type='sub_service';
                $subServiceId=$category_id;

                $allChildServices = DB::table('child_sub_services')->where('sub_services_id',$subServiceId)->where('deleted_at',NULL)->get()->toArray();
              
                if(empty($allChildServices))
                {
                    // If array empty of child.. then show question data directoly.

                            //Get 
                     $getallTypeId = DB::table('sub_services')
                        ->select('category_id','services_id','id')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(id = '".$subServiceId."' AND deleted_at IS null )")
                        ->first();

                   
                        $catId=""; $servId=""; $subServId=""; $childSubServId="";

                        if(!empty($getallTypeId))
                        {
                           $catId = $getallTypeId->category_id;
                           $servId =  $getallTypeId->services_id;
                           $subServId = $getallTypeId->id;
                        }
                    $questionArr = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                        ->whereRaw("(questions.question_order=1)")
                        ->whereRaw("(questions.is_related=0)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                        ->first();

                    $questionArrlist = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                        //->whereRaw("(questions.question_order=1)")
                        ->whereRaw("(questions.is_related=0)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                        ->pluck('id')->toArray();
                        // echo '<pre>';print_r($questionArrlist);exit;

                    if(!empty($questionArr)) 
                    {
                         $firstQuestID=$questionArr->id;

                         $questionArr->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$questionArr->id."' AND deleted_at IS null )")
                        ->get()->toArray();
                            $currentdata= current($questionArrlist);
                            $nextdata= next($questionArrlist);
                        
                            Session::put('nextQuestion', $questionArrlist);
                              $dependent=$this->questionType($category_id,$selected_type);
                    }
                    // dd($questionArr);
                }

              

                // $allChildServices = DB::table('child_sub_services')->where('sub_services_id',$subServiceId)->where('deleted_at',NULL)->get();
                $getSubServicename = DB::table('sub_services')->where('id',$subServiceId)->where('deleted_at',NULL)->first();
                $allSubServices = DB::table('sub_services')->where('services_id',$getSubServicename->services_id)->where('deleted_at',NULL)->get();
                $getServicename = DB::table('services')->where('id',$getSubServicename->services_id)->where('deleted_at',NULL)->first();
                $allServices = DB::table('services')->where('category_id',$getSubServicename->category_id)->get();
                $category_id=DB::table('services')->where('id',$getSubServicename->services_id)->value('category_id');
                $serviceId=DB::table('sub_services')->where('id',$subServiceId)->value('services_id');
                $getCategoryData=DB::table('category')->select('es_name')->where('id', $getSubServicename->category_id)->first();
                // dd($serviceId);
                /*-----*/
        
                if(!empty($getServicename)) { $servicename=$getServicename->es_name; }

             
                if(!empty($getSubServicename)) { $subservicename=$getSubServicename->es_name; }

               
                //dd($getCategoryData );
                //$getCategoryData=DB::table('category')->select('en_name')->where('id',$category_id)->first();

                // $allServices = DB::table('services')->where('category_id',$category_id)->get();  
            }

            // if($selected_type=='child_sub_service')
            // {
            //   $child_sub_serviceId=$category_id;
            //             //Get 
            //      $getallTypeId = DB::table('child_sub_services')
            //     ->select('category_id','services_id','sub_services_id')
            //     ->whereRaw("(status=1)")
            //     ->whereRaw("(id = '".$child_sub_serviceId."' AND deleted_at IS null )")
            //     ->first();

            //     $catId=""; $servId=""; $subServId=""; $childSubServId="";

            //     if(!empty($getallTypeId))
            //     {
            //        $catId = $getallTypeId->category_id;
            //        $servId =  $getallTypeId->services_id;
            //        $subServId = $getallTypeId->sub_services_id;
            //        $childSubServId = $child_sub_serviceId;
            //     }

            //     $questionArr = DB::table('questions')
            //     ->whereRaw("(questions.status=1)")
            //     ->whereRaw("(questions.question_order=1)")
            //     ->whereRaw("(questions.is_related=0)")
            //     ->whereRaw("(questions.category_id = '".$catId."')")
            //     ->whereRaw("(questions.services_id = '".$servId."')")
            //     ->whereRaw("(questions.sub_services_id = '".$subServId."')")
            //     ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
            //     ->first();

            //     if(!empty($questionArr)) 
            //     {
            //          $firstQuestID=$questionArr->id;

            //          $questionArr->options = DB::table('question_options')
            //         ->select('id','en_option','es_option','created_at','status')
            //         ->whereRaw("(status=1)")
            //         ->whereRaw("(question_id = '".$questionArr->id."' AND deleted_at IS null )")
            //         ->get()->toArray();
            //     }

            //   $subServiceId=DB::table('child_sub_services')->where('id',$child_sub_serviceId)->value('sub_services_id');

            //   $allChildServices = DB::table('child_sub_services')->where('sub_services_id',$subServiceId)->get();

            //   $getchildname = DB::table('child_sub_services')->where('id',$child_sub_serviceId)->where('deleted_at',NULL)->first();

            //   $childsubservicename=""; if(!empty($getchildname)) { $childsubservicename=$getchildname->es_name; }

            //   $serviceId=DB::table('sub_services')->where('id',$subServiceId)->value('services_id');

            //   $allSubServices = DB::table('sub_services')->where('services_id',$serviceId)->get();

            //   $category_id=DB::table('services')->where('id',$serviceId)->value('category_id');

            //   $getServicename = DB::table('services')->where('id',$serviceId)->where('deleted_at',NULL)->first();

            //    if(!empty($getServicename)) {
            //     $servicename=$getServicename->es_name;
            //      }

            //   $getSubServicename = DB::table('sub_services')->where('id',$subServiceId)->where('deleted_at',NULL)->first();
            //   $subservicename=""; if(!empty($getSubServicename)) { $subservicename=$getSubServicename->es_name; }

            //   $getCategoryData=DB::table('category')->select('en_name')->where('id',$category_id)->first();

            //   $allServices = DB::table('services')->where('category_id',$category_id)->get();

            // }

           

             return view('frontend.categories-step',compact('user_id','getCategoryData','getCityData','allServices','category_id','city_id','selected_type','allSubServices','servicename','serviceId',
                'subServiceId','allChildServices','subservicename','childsubservicename','child_sub_serviceId','questionArr','firstQuestID','dependent','nextdata','currentdata','bannerImages'));
        }

        public function questionType($type_id=null, $type=null)
        {
            $access_token=123456;
            $arr=array();
            $type_id = $type_id;
            $type = $type;

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

                   if($type=='sub_service'||$type=='subservice')
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
                        if($questionEntity->is_related==0)
                        {
                           $arr ='No'; 
                        }
                        else
                        {
                            $arr ='Yes'; 
                        } 

                       // $arr['question_type'] =isset($questionEntity) && !empty($questionEntity->question_type) ? (string)$questionEntity->question_type : '' ;
                   
                    if(!empty($arr))
                    {
                        return $arr;
                        
                    } 
                    else
                    {
                        return;
                    }    
        }

        public function ajaxGetSubservice(Request $request) 
        {  

            $serviceId = $request->input('serviceId');
            $categoryId = $request->input('categoryId');

            $getServicename = DB::table('services')->where('id',$serviceId)->where('deleted_at',NULL)->first();
             $servicename="";
            if(!empty($getServicename))
            {
                $servicename=$getServicename->es_name;
            }

            $subservices = DB::table('sub_services')->where('category_id',$categoryId)->where('services_id',$serviceId)->where('deleted_at',NULL)->get();

            if(!empty($subservices) && count($subservices) > 0)
            {
                 echo json_encode(['success' => true,'subservices'=> $subservices, 'message' => 'foundSubservice', 'servicename' => $servicename]);
            }
            else
            {
                 echo json_encode(['success' => false,'subservices'=> '', 'message' => 'notFoundSubservice', 'servicename' => $servicename]);
            }
                
        }

        public function ajaxGetChildservice(Request $request)
        {  

               $subserviceId = $request->input('subserviceId');
               $question=array();
               $getSubServicename = DB::table('sub_services')->where('id',$subserviceId)->where('deleted_at',NULL)->first();
               $dependent='';
               $nextdata='';
               $currentdata='';
               
                 $subservicename="";
                if(!empty($getSubServicename))
                {
                    $subservicename=$getSubServicename->es_name;
                }

                $childservices = DB::table('child_sub_services')->where('sub_services_id',$subserviceId)->where('deleted_at',NULL)->get()->toArray();
                

                    if(empty($childservices))
                    {
                       
                        // If array empty of child.. then show question data directoly.
                                //Get 
                         $getallTypeId = DB::table('sub_services')
                        ->select('category_id','services_id','id')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(id = '".$subserviceId."' AND deleted_at IS null )")
                        ->first();

                        $catId=""; $servId=""; $subServId=""; 

                        if(!empty($getallTypeId))
                        {
                           $catId = $getallTypeId->category_id;
                           $servId =  $getallTypeId->services_id;
                           $subServId = $getallTypeId->id;
                        }

                         $question = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                        ->whereRaw("(questions.question_order=1)")
                        ->whereRaw("(questions.is_related=0)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                        ->first();

                        $questionArrlist = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                        //->whereRaw("(questions.question_order=1)")
                       // ->whereRaw("(questions.is_related=0)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.sub_services_id = '".$subServId."' AND deleted_at IS null)")
                        ->pluck('id')->toArray();

                        //dd($questionArrlist);
                        $nextdata=0;
                        if(count($questionArrlist)>1)
                        {
                            $currentdata=current($questionArrlist);
                            $nextdata= next($questionArrlist);
                            Session::put('nextQuestion', $questionArrlist);
                            $dependent=$this->questionType($subserviceId,'subservice');
                        }   
                

                        if(!empty($question)) 
                        {
                             $question->options = DB::table('question_options')
                            ->select('id','en_option','es_option','created_at','status')
                            ->whereRaw("(status=1)")
                            ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                            ->get()->toArray();
                        }
                    }
                    else
                    {
                        $childservices = DB::table('child_sub_services')->where('sub_services_id',$subserviceId)->where('deleted_at',NULL)->get()->toArray();
                

                        $childservices1 = DB::table('child_sub_services')->where('sub_services_id',$subserviceId)->where('deleted_at',NULL)->first();
                        if(!empty($childservices))
                        {
                           $catId = $childservices1->category_id;
                           $servId =  $childservices1->services_id;
                           $subServId = $childservices1->sub_services_id;
                           $childServId = $childservices1->id;
                        }

                    }
                       DB::table('questions')->update(['question_status'=>0]);
                    $msg="";
                    if(!empty($childservices) && count($childservices) > 0)
                    {
                        $msg ='foundChildservice';

                    } if(!empty($question))
                    {
                        $msg ='foundQuestionReplcaeChildservice';

                    }
                    if(!empty($childservices) && count($childservices) > 0 || !empty($question))
                    {

                         echo json_encode(['success' => true,'childservices'=> $childservices, 'questionData'=> $question,'message' => $msg, 'subservicename' => $subservicename,'nextdata'=>$nextdata,'dependent'=>$dependent,'currentdata'=>$currentdata]);
                    }
                    else
                    {
                         echo json_encode(['success' => false,'childservices'=> '', 'message' => 'notFoundChildservice','questionData'=> '', 'subservicename' => '','nextdata'=>$nextdata,'dependent'=>$dependent]);
                    }
        }

        public function ajaxGetQuestions(Request $request)
        {  

            $categoryId = $request->input('categoryId');
            $serviceId = $request->input('serviceId');
            $subserviceId = $request->input('subserviceId');
            $childsubserviceId = $request->input('childsubserviceId');
            $question_type = $request->input('questiontype');
            $next_question = $request->input('nextquestion');

            $getChildSubServicename = DB::table('child_sub_services')->where('id',$childsubserviceId)->where('deleted_at',NULL)->first();
            $childsubservicename="";
            if(!empty($getChildSubServicename))
            {
                $childsubservicename=$getChildSubServicename->es_name;
            }
                Session::forget('nextQuestion');
            $nexttype=Session::get('nextQuestion');
            if(!empty($nexttype))
            {
                if(!empty($next_question) && $next_question!='null')
                {
                    $nextindex = array_search($next_question, $nexttype);
                    if(end($nexttype)==$next_question) 
                    {
                       $nextdata=end($nexttype);
                        // if($question_id==$next_question)
                        // {
                            $nextdata=0;
                        // }
                    }
                    else
                    {
                        $nextdata= $nexttype[$nextindex+1];
                    }
                }
            }
          

            if(!empty($categoryId) && !empty($serviceId) && !empty($subserviceId) && !empty($childsubserviceId))
            {

                 $question = DB::table('questions')
                ->whereRaw("(questions.status=1)")
                ->whereRaw("(questions.question_order=1)")
                ->whereRaw("(questions.is_related=0)")
                ->whereRaw("(questions.category_id = '".$categoryId."')")
                ->whereRaw("(questions.services_id = '".$serviceId."')")
                ->whereRaw("(questions.sub_services_id = '".$subserviceId."')")
                ->whereRaw("(questions.child_sub_service_id = '".$childsubserviceId."' AND deleted_at IS null )")
                ->first();
                $nextdata='';
                $dependent='';
                if(!empty($question))
                {
                    $questionarray = DB::table('questions')
                    ->whereRaw("(questions.status=1)")
                    //->whereRaw("(questions.question_order=1)")
                    ->whereRaw("(questions.is_related=0)")
                    ->whereRaw("(questions.category_id = '".$categoryId."')")
                    ->whereRaw("(questions.services_id = '".$serviceId."')")
                    ->whereRaw("(questions.sub_services_id = '".$subserviceId."')")
                    ->whereRaw("(questions.child_sub_service_id = '".$childsubserviceId."' AND deleted_at IS null )")
                    ->orderBy('question_order','asc')
                    ->pluck('id')->toArray();
                   // dd($questionarray);
                    $currentdata=current($questionarray);
                    Session::put('nextQuestion',$questionarray);
                    $nextdata= next($questionarray);
                     $dependent=$this->questionType($childsubserviceId,'child_sub_service');
                    //dd($question);
                }
                     
            
            

                if(!empty($question))
                {
                     $question->options = DB::table('question_options')
                    ->select('id','en_option','es_option','created_at','status')
                    ->whereRaw("(status=1)")
                    ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                    ->get();

                    return response()->json(['success' => true,'nextQuestionData'=> $question, 'childsubservicename'=> $childsubservicename, 'message' => 'questionFound','nextdata'=>$nextdata,'dependent'=>$dependent,'currentdata'=>$currentdata]);
                }else
                {
                   return response()->json(['success' => false,'nextQuestionData'=> '', 'childsubservicename'=> '', 'message' => 'questionNotFound','nextdata'=>$nextdata,'dependent'=>$dependent]); 
                }
            }

            else
            {
                 return response()->json(['success' => true,'questionData'=> '', 'childsubservicename'=> '', 'message' => 'Question Not found','nextdata'=>$nextdata,'dependent'=>$dependent]);
            }
                
        }

        public function checkMobileAvailability(Request $request) 
        {  

            $mobile_number = $request->input('mobile_number');
            if(!empty($mobile_number))
            {

                $already_exists = DB::table('users')->where('mobile_number',$mobile_number)->first();

                if(!empty($already_exists))
                {
                  return response()->json(['success' => false, 'message' => 'El número de móvil ya existe.']); die;
                }else
                {
                 return response()->json(['success' => true, 'message' => 'El número de móvil no existe.']); die;
                } 
            }
        }

        public function ajaxGetNextQuestions(Request $request) 
        {  

            $catId = $request->input('categoryId');
            $servId = $request->input('serviceId');
            $subServId = $request->input('subserviceId');
            $childSubServId = $request->input('childsubserviceId');
            $question_id = $request->input('firstQuestID');
            $option_id = $request->input('firstOptionID');
            $question_type = $request->input('questiontype');
            $next_question = $request->input('nextquestion');
            $current = $request->input('predata');
            $formvalue = $request->input('value');
             
            if(!empty($catId) && !empty($servId) && !empty($subServId) && !empty($childSubServId))
            {
                $type='child_sub_service';
                $type_id= $childSubServId;
                $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);
               
                if($result['status']==0)
                {
                    $is_related='no';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                        $question_type='No'; 
                    }
                }
                else
                {
                    $next_question=$result['data'][0]['question_id'];
                    $is_related='yes';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                         $question_type='Yes';
                    }
                }
            }
            elseif(!empty($catId) && !empty($servId) && !empty($subServId))
            {
                $type='sub_service';
                $type_id= $subServId;
                $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);
             
                if($result['status']==0)
                {
                    $is_related='no';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                        $question_type='No'; 
                    }
                }
                else
                {
                    $next_question=$result['data'][0]['question_id'];
                    $is_related='yes';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        
                        $next_question=$nextQuestion['data']['id'];
                        $question_type='Yes';
                    }
                }
            }
            elseif(!empty($catId) && !empty($servId))
            {
                $type='service';
                $type_id= $servId;
               $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);
                if($result['status']==0)
                {
                    $is_related='no';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                        $question_type='No'; 
                    }
                }
                else
                {
                   $next_question=$result['data'][0]['question_id'];
                    $is_related='yes';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                        $question_type='Yes';
                    }
                }
            }

            $nexttype=Session::get('nextQuestion');
            $checklast=count($nexttype)-1;
            $lastdata=count($nexttype);
            if(!empty($next_question) && $next_question!='null')
            {
                $nextindex = array_search($next_question, $nexttype);
                if($nexttype[$checklast]==$next_question) 
                {  
                    if($question_id==$next_question && $next_question==$current){
                    $endquestion= end( $nexttype);
                    $nextqueton=0;
                    }else{
                        $nextqueton=$nexttype[$checklast];
                    }
                }
                else
                {
                    $nextqueton= $nexttype[$nextindex+1];
                }
            }else
            {
                 $nextqueton=0;
            }
                
              $getQuestioName = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();

                 $questionname="";
                if(!empty($getQuestioName))
                {
                    $questionname=$getQuestioName->es_title;
                    $currentdata= $getQuestioName->id;
                }
                   $getQuestionid = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();
                   if($getQuestionid->question_type=='checkbox')
                   {
                        $nextindex = array_search($question_id, $nexttype);
                        $nextqueton= $nexttype[$nextindex+1];
                        $next_question=$nextqueton;
                        $nextindex1 = array_search($nextqueton, $nexttype);
                        $nextqueton= $nexttype[$nextindex1+1];
                   }

                $getQuestiontype = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();

                $questionoptionname="";
                if(!empty($getQuestioName))
                {
                    if($getQuestiontype->question_type=='text' ||$getQuestiontype->question_type=='date_time')
                    {
                        if(!empty($question_id))
                        {
                            $getQuestioName = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();
                          $questionname=$getQuestioName->es_title; 
                          $questionoptionname='nulls'; 

                        }
                    }else
                    {
                        if(!empty($question_id))
                        {
                            $getQuestioName = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();
                          $questionname=$getQuestioName->es_title;  
                        }
                         $getQuestionOptionName = DB::table('question_options')->where('id',$option_id)->where('deleted_at',NULL)->first();
                        if(!empty($getQuestionOptionName))
                        {
                            $questionoptionname=$getQuestionOptionName->es_option;
                        }
                    }
                }
                if(!empty($catId) && !empty($servId) && !empty($subServId) && !empty($childSubServId))
                {
                    if($question_type=='Yes')
                    {
                        $question = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            ->whereRaw("(questions.is_related=1)")
                            ->whereRaw("(related_question_id = '".$question_id."')")
                            ->whereRaw("(related_option_id = '".$option_id."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->first();
                    }
                    else
                    {
                        $question = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            ->whereRaw("(id = '".$next_question."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->first();
                    }
                    if(!empty($question)) 
                    {
                        $question->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                        ->get();
                    }

                        if(!empty($question))
                        {
                            return response()->json(['success' => true,'nextQuestionData'=> $question,'message' => 'Question found','questionname'=>$questionname,'questionoptionname'=> $questionoptionname,'nextdata'=>$nextqueton,'dependent'=>$question_type,'currentdata'=>$currentdata]);
                        }else
                        {
                            return response()->json(['success' => true,'nextQuestionData'=> '','message' => 'Question Not found','questionname'=>'','questionoptionname'=>'','nextdata'=>$nextqueton,'dependent'=>$question_type]);
                        }
                }
                elseif(!empty($catId) && !empty($servId) && !empty($subServId))
                {
                    if($question_type=='Yes')
                    {
                        $question = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            //->whereRaw("(questions.is_related=1)")
                            //->whereRaw("(questions.id = '".$question_id."')")
                              ->whereRaw("(questions.related_question_id = '".$question_id."')")
                            ->whereRaw("(questions.related_option_id='".$option_id."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."'  AND deleted_at IS null )")
                            ->first();
                         
                    }
                    else
                    {
                        $question = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                        ->whereRaw("(questions.id= '".$next_question."'  AND deleted_at IS null )")
                        ->first();
                    }
                    
                    if(!empty($question)) 
                    {
                        $question->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                        ->get();
                    }

                        if(!empty($question))
                        {
                            return response()->json(['success' => true,'nextQuestionData'=> $question,'message' => 'Question found','questionname'=>$questionname,'questionoptionname'=> $questionoptionname,'nextdata'=>$nextqueton,'dependent'=>$question_type,'currentdata'=>$currentdata]);
                        }else
                        {
                           return response()->json(['success' => true,'nextQuestionData'=> '','message' => 'Question Not found','questionname'=>'','questionoptionname'=>'','nextdata'=>$nextqueton,'dependent'=>$question_type]);
                        }
                }
                elseif(!empty($catId) && !empty($servId))
                {
                    if($question_type=='Yes')
                    {
                        $question = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            //->whereRaw("(questions.is_related=1)")
                            //->whereRaw("(questions.id = '".$question_id."')")
                              ->whereRaw("(questions.related_question_id = '".$question_id."')")
                            ->whereRaw("(questions.related_option_id='".$option_id."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."'  AND deleted_at IS null )")
                            ->first();
                         
                    }
                    else
                    {
                        $question = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.id= '".$next_question."'  AND deleted_at IS null )")
                        ->first();
                    }
                    
                    if(!empty($question)) 
                    {
                        $question->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                        ->get();
                    }

                        if(!empty($question))
                        {
                            return response()->json(['success' => true,'nextQuestionData'=> $question,'message' => 'Question found','questionname'=>$questionname,'questionoptionname'=> $questionoptionname,'nextdata'=>$nextqueton,'dependent'=>$question_type,'currentdata'=>$currentdata]);
                        }else
                        {
                           return response()->json(['success' => true,'nextQuestionData'=> '','message' => 'Question Not found','questionname'=>'','questionoptionname'=>'','nextdata'=>$nextqueton,'dependent'=>$question_type]);
                        }
                }
                else
                {
                    return response()->json(['success' => true,'nextQuestionData'=> '','message' => 'questionNotFound','questionname'=>'','questionoptionname'=>'','nextdata'=>$nextqueton,'dependent'=>$question_type]);
                }
        }

        public function ajaxGetNextQuestionsMultiCheck(Request $request)
        {
           $catId = $request->input('categoryId');
            $servId = $request->input('serviceId');
            $subServId = $request->input('subserviceId');
            $childSubServId = $request->input('childsubserviceId');
            $question_id = $request->input('firstQuestID');
            $option_id = $request->input('firstOptionID');
            $question_type = $request->input('questiontype');
            $next_question = $request->input('nextquestion');
            $current = $request->input('predata');
            $formvalue = $request->input('value');


            if(!empty($catId) && !empty($servId) && !empty($subServId) && !empty($childSubServId))
            {
                $type='child_sub_service';
                $type_id= $childSubServId;
                $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);
                if($result['status']==0)
                {
                    $is_related='';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                        $question_type='No'; 
                    }
                }
                else
                {
                    $next_question=$result['data'][0]['question_id'];
                    $is_related='';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                         $question_type='Yes';
                    }
                }
            }
            elseif(!empty($catId) && !empty($servId) && !empty($subServId))
            {
                $type='sub_service';
                $type_id= $subServId;
                $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);

                if($result['status']==0)
                {
                    $is_related='';
                    $nextQuestion=  $this->checkNextQuestion($question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                        $question_type='No'; 
                    }
                }
                else
                {
                    $next_question=$result['data'][0]['question_id'];
                    $is_related='';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                         $question_type='Yes';
                    }
                }
               
            }
            elseif(!empty($catId) && !empty($servId))
            {
                $type='service';
                $type_id= $servId;
               $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);
                 if($result['status']==0)
                {
                    $is_related='';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                        $question_type='No'; 
                    }
                }
                else
                {
                    $next_question=$result['data'][0]['question_id'];
                    $is_related='';
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type, $is_related);
                    if($nextQuestion['status']==0)
                    {
                        $next_question=0;
                        $nextqueton=0;
                        $question_type='No'; 
                    }
                    else
                    {
                        $next_question=$nextQuestion['data']['id'];
                         $question_type='Yes';
                    }
                }
            }

            $nexttype=Session::get('nextQuestion');

            $checklast=count($nexttype)-1;
            $lastdata=count($nexttype);
            if(!empty($next_question) && $next_question!='null')
            {
                $nextindex = array_search($next_question, $nexttype);
                if($nexttype[$checklast]==$next_question) 
                {  
                    if($question_id==$next_question && $next_question==$current){
                    $nextqueton=0;
                    }else{
                        $nextqueton=$nexttype[$checklast];
                    }
                }
                else
                {
                    $nextqueton= $nexttype[$nextindex+1];
                }
            }else
            {
                 $nextqueton=0;
            }
                
              $getQuestioName = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();

                 $questionname="";
                if(!empty($getQuestioName))
                {
                    $questionname=$getQuestioName->es_title;
                    $currentdata= $getQuestioName->id;
                }

                 $getQuestionid = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();
   
                   $getQuestiontype = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();
                    $questionoptionname="";
                if(!empty($getQuestioName))
                {
                    if($getQuestiontype->question_type=='text' ||$getQuestiontype->question_type=='date_time')
                    {
                    }else
                    {
                        if(!empty($question_id))
                        {
                            $getQuestioName = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();
                          $questionname=$getQuestioName->es_title;  
                        }
                         $getQuestionOptionName = DB::table('question_options')->where('id',$option_id)->where('deleted_at',NULL)->first();
                        if(!empty($getQuestionOptionName))
                        {
                            $questionoptionname=$getQuestionOptionName->es_option;
                        }
                    }
                }


                 if(!empty($catId) && !empty($servId) && !empty($subServId) && !empty($childSubServId))
                {
                    if($question_type=='Yes')
                    {
                        $question = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            ->whereRaw("(related_question_id = '".$question_id."')")
                            ->whereRaw("(related_option_id = '".$option_id."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->first();
                    }
                    else
                    {
                        $question = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            ->whereRaw("(id = '".$next_question."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->first();
                          
                    }
                    if(!empty($question)) 
                    {
                        $question->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                        ->get();
                    }

                        if(!empty($question))
                        {
                            return response()->json(['success' => true,'nextQuestionData'=> $question,'message' => 'Question found','questionname'=>$questionname,'questionoptionname'=> $questionoptionname,'nextdata'=>$nextqueton,'dependent'=>$question_type,'currentdata'=>$currentdata]);
                        }else
                        {
                          return response()->json(['success' => true,'nextQuestionData'=> '','message' => 'Question Not found','questionname'=>'','questionoptionname'=>'','nextdata'=>$nextqueton,'dependent'=>$question_type]);
                        }
                }
                elseif(!empty($catId) && !empty($servId) && !empty($subServId))
                {


                    if($question_type=='Yes')
                    {
                        $question = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            //->whereRaw("(questions.is_related=1)")
                            //->whereRaw("(questions.id = '".$question_id."')")
                              ->whereRaw("(questions.related_question_id = '".$question_id."')")
                            ->whereRaw("(questions.related_option_id='".$option_id."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."'  AND deleted_at IS null )")
                            ->first();
                    }
                    else
                    {
                       
                        $question = DB::table('questions')
                        ->whereRaw("(questions.status=1)")
                        ->whereRaw("(questions.category_id = '".$catId."')")
                        ->whereRaw("(questions.services_id = '".$servId."')")
                        ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                        ->whereRaw("(questions.id= '".$next_question."'  AND deleted_at IS null )")
                        ->first();
                    }
                    
                    if(!empty($question)) 
                    {
                        $question->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                        ->get();
                    }

                        if(!empty($question))
                        {
                            return response()->json(['success' => true,'nextQuestionData'=> $question,'message' => 'Question found','questionname'=>$questionname,'questionoptionname'=> $questionoptionname,'nextdata'=>$nextqueton,'dependent'=>$question_type,'currentdata'=>$currentdata]);
                        }else
                        {
                           return response()->json(['success' => true,'nextQuestionData'=> '','message' => 'Question Not found','questionname'=>'','questionoptionname'=>'','nextdata'=>$nextqueton,'dependent'=>$question_type]);
                        }
                }
                else
                {
                    return response()->json(['success' => true,'nextQuestionData'=> '','message' => 'questionNotFound','questionname'=>'','questionoptionname'=>'','nextdata'=>$nextqueton,'dependent'=>$question_type]);
                }
        }


        public function checkNextQuestion($question_id,$type_id,$type,$is_related)
        {
            $question_id = $question_id; 
            $type_id = $type_id;
            $type = $type;
            if($type=='child_sub_service')
            {
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
                //->whereRaw("(questions.is_related=0)")
                ->whereRaw("(questions.category_id = '".$catId."')")
                ->whereRaw("(questions.services_id = '".$servId."')")
                ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                ->whereRaw("(questions.question_status =0)")
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
                //->whereRaw("(questions.is_related=0)")
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
                //->whereRaw("(questions.is_related=0)")
                ->whereRaw("(questions.category_id = '".$catId."')")
                ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                ->whereRaw("(questions.question_status =0)")
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
               // ->whereRaw("(questions.is_related=0)")
                ->whereRaw("(questions.category_id = '".$catId."' AND deleted_at IS null)")
                ->whereRaw("(questions.related_question_id = '".$question_id."')")
                ->first();
            }
                if(!empty($questionEntity))
                {

                    DB::table('questions')->where('id',$question_id)->update(['question_status'=>1]);

                    if($type=='child_sub_service')
                    {
                         $checkrel= DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            ->whereRaw("(questions.question_status=0)");
                            if($is_related=='yes')
                            {
                                $checkrel->whereRaw("(questions.is_related=1)");
                            }
                            elseif($is_related=='no')
                            {
                               $checkrel->whereRaw("(questions.is_related=0)");
                            }
                            
                          $questionEntity1=  $checkrel->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->orderBy('question_order','asc')
                            ->first();
                    }
                    if($type=='sub_service')
                    {
                       
                            $checkrel= DB::table('questions')
                            ->whereRaw("(questions.status=1)");
                             if($is_related=='yes')
                            {
                                $checkrel->whereRaw("(questions.is_related=1)");
                            }
                            elseif($is_related=='no')
                            {
                               $checkrel->whereRaw("(questions.is_related=0)");
                            }
                            $questionEntity1=$checkrel->whereRaw("(questions.question_status=0)")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                            ->orderBy('question_order','asc')
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->first();
                    }
                    if($type=='service')
                    {
                        $checkrel= DB::table('questions')
                            ->whereRaw("(questions.status=1)");
                            if($is_related=='yes')
                            {
                                $checkrel->whereRaw("(questions.is_related=1)");
                            }
                            elseif($is_related=='no')
                            {
                               $checkrel->whereRaw("(questions.is_related=0)");
                            }
                            $questionEntity1= $checkrel->whereRaw("(questions.question_status=0)")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."' AND deleted_at IS null)")
                            ->orderBy('question_order','asc')
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
                        return $resultArray;
                    }
                    else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.data_not_found');
                       return $resultArray;
                    }
                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.data_not_found');
                   return $resultArray;
                }
        }

        public function ajaxGetNextQuestionsOptions(Request $request) 
        {  

            $catId = $request->input('categoryId');
            $servId = $request->input('serviceId');
            $subServId = $request->input('subserviceId');
            $childSubServId = $request->input('childsubserviceId');
            $question_id = $request->input('firstQuestID');
            $option_id = $request->input('optionID');
            $question_type = $request->input('questiontype');
            $next_question = $request->input('nextquestion');


             if(!empty($catId) && !empty($servId) && !empty($subServId) && !empty($childSubServId))
            {
                $type='child_sub_service';
                $type_id= $childSubServId;
                $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);
               // print_r($result);exit;
            }
            elseif(!empty($catId) && !empty($servId) && !empty($subServId))
            {
                $type='sub_service';
                $type_id= $subServId;
                $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);
                $question_type='Yes';
                if($result['status']==0)
                {
                    $nextQuestion=  $this->checkNextQuestion( $question_id,$type_id,$type);
                    $next_question=$nextQuestion['data']['id'];
                    $question_type='No';
                    exit;
                }
               
            }
            elseif(!empty($catId) && !empty($servId))
            {
                $type='service';
                $type_id= $servId;
               $result=$this->getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id);
              // print_r( $result);exit;
            }

            $nexttype=Session::get('nextQuestion');
            $nextindex = array_search($next_question, $nexttype);
            $nextqueton= $nexttype[$nextindex+1]; 

                if($question_type=='Yes')
                {
                    $getQuestioName = DB::table('questions')->where('related_option_id',$option_id)->where('deleted_at',NULL)->first();
                }else
                {
                     $getQuestioName = DB::table('questions')->where('id',$next_question)->where('deleted_at',NULL)->first();
                       
                }

                // $getQuestioName = DB::table('questions')->where('related_question_id',$getQuestioName->id)->where('deleted_at',NULL)->first();
                 $questionname="";
                if(!empty($getQuestioName))
                {
                    $questionname=$getQuestioName->es_title;
                }


                 $getQuestionOptionName = DB::table('question_options')->where('question_id',$getQuestioName->id)->where('deleted_at',NULL)->first();
                 $questionoptionname="";
                if(!empty($getQuestionOptionName))
                {
                    $questionoptionname=$getQuestionOptionName->es_option;
                }


                if(!empty($catId) && !empty($servId) && !empty($subServId) && !empty($childSubServId))
                     {
                        if($question_type=='Yes')
                        {
                            $question = DB::table('questions')
                            ->whereRaw("(questions.status=1)")
                            ->whereRaw("(questions.is_related=1)")
                            ->whereRaw("(related_question_id = '".$getQuestioName->related_question_id."')")
                            ->whereRaw("(related_option_id = '".$getQuestioName->related_option_id."')")
                            ->whereRaw("(questions.category_id = '".$catId."')")
                            ->whereRaw("(questions.services_id = '".$servId."')")
                            ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                            ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                            ->first();
                        }
                        else
                        {
                            $question = DB::table('questions')
                               // ->whereRaw("(questions.status=1)")
                               // ->whereRaw("(questions.is_related=1)")
                               // ->whereRaw("(related_question_id = '".$getQuestioName->related_question_id."')")
                               // ->whereRaw("(related_option_id = '".$getQuestioName->related_option_id."')")
                                ->whereRaw("(id = '".$next_question."')")
                                ->whereRaw("(questions.category_id = '".$catId."')")
                                ->whereRaw("(questions.services_id = '".$servId."')")
                                ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                                ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                                ->first();
                        }
                        
                        
                    if(!empty($question)) 
                    {
                        $question->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                        ->get();
                    }
                   // dd($question);
                        if(!empty($question))
                        {
                            return response()->json(['success' => true,'nextQuestionData'=> $question,'message' => 'Question found','questionname'=>$questionname,'questionoptionname'=> $questionoptionname,'nextdata'=>$nextqueton,'dependent'=>$question_type]);
                        }else
                        {
                           return response()->json(['success' => false,'nextQuestionData'=> '','message' => 'Question Not found','questionname'=>'','questionoptionname'=>'']);
                        }

                    
                }


                else
                {
                    return response()->json(['success' => false,'nextQuestionData'=> '','message' => 'questionNotFound','questionname'=>'','questionoptionname'=>'']);
                }
                
        }
        public function getQuestionnaireByOptionId($type_id,$type,$question_id,$option_id)
        {
                $allData=array();$arr=array();$arr2=array();
                $type_id =$type_id; 
                $type =$type ;
                $question_id =$question_id ;
                $option_id = $option_id;

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

                            
                             $arr['question']=isset($question) && !empty($question->es_title) ? (string)$question->es_title : '' ; 
                             
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
                                    
                                    $arr2['option'] =  isset($option) && !empty($option->es_option) ? $option->es_option : '' ;  
                                      

                                    $arr2['status'] =  isset($option) && !empty($option->status) ? $option->status : '' ;

                                    $arr2['created_at'] =  isset($option) && !empty($option->created_at) ? $option->created_at : '' ;

                                 array_push($options, $arr2);

                                }
                            }
                            else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.data_not_found');
                               return $resultArray;
                            }
                              
                                $arr['options']=$options ;
                                array_push($allData, $arr);
                        }

                           if(!empty($allData) && count($allData) > 0)
                            {
                                $resultArray['status']='1';
                                $resultArray['message']=trans('apimessage.data_found_successfully');
                                $resultArray['data']=$allData;
                                return $resultArray;
                            } else
                            {
                                $resultArray['status']='0';
                                $resultArray['message']=trans('apimessage.data_not_found');
                               return $resultArray;
                            }
                    }else
                    {
                        $resultArray['status']='0';
                        $resultArray['message']=trans('apimessage.data_not_found');
                       return $resultArray;
                    }
                }
                else
                {
                    $resultArray['status']='0';
                    $resultArray['message']=trans('apimessage.Invalid parameters.');
                   return $resultArray;    
                }    
        }
        public function storeServiceRequest(Request $request) 
        {   
            //die('stores service request');
           


            if(!empty($request->address))
            {
                $address=$request->address;
            }
            elseif(!empty($request->address1))
            {
                $address=$request->address1;
            }
            elseif(!empty($request->address2))
            {
                $address=$request->address2;
            }
            elseif(!empty($request->address3))
            {
                $address=$request->address3;
            }
            elseif(!empty($request->address4))
            {
                $address=$request->address4;
            }
            elseif(!empty($request->address5))
            {
                $address=$request->address5;
            }
            elseif(!empty($request->address6))
            {
                $address=$request->address6;
            }
            elseif(!empty($request->address7))
            {
                $address=$request->address7;
            }
            elseif(!empty($request->address8))
            {
                $address=$request->address8;
            }

            // echo "<pre>"; print_r($request->all()); die('request');
                $userEntity=  DB::table('users')->where('email', $request->email)->first();
               if(!empty($userEntity))
                {
                    $usertype='old';
                    $user_id= $userEntity->id;
                }else
                {   
                    $usertype='new';
                    $password='buskalo@11';
                    $user_arr['uuid'] = Uuid::uuid4()->toString();
                    $user_arr['username'] = $request->username;
                    $user_arr['user_group_id'] = 2;
                    $user_arr['mobile_number'] = $request->mobile_number;
                    $user_arr['email'] = $request->email;            
                    $user_arr['address'] = $address;
                    $user_arr['confirmed'] = 1;
                    $user_arr['is_verified'] = 1;
                   // $user_arr['password'] = Hash::make($password);
                    $user_arr['remember_token'] = Hash::make('secret');
                    $user_arr['confirmation_code'] = md5(uniqid(mt_rand(), 1));
                    $user_arr['created_at'] = Carbon::now();
                    $mobileCheck=DB::table('users')->where('mobile_number',$request->mobile_number)->first();
                    if(!empty($mobileCheck))
                    {
                         return redirect()->route('frontend.home_page')->withFlashDanger(__('Su número de móvil ingresado ya se ha utilizado. por favor ingrese un nuevo número de teléfono celular.'));
                       //return redirect()->back('Su número de móvil ingresado ya se ha utilizado. por favor ingrese un nuevo número de teléfono celular.');
                    }
                    $user_id =  DB::table('users')->insertGetId($user_arr);
                }
                    /* Start Service amount calculate*/

                    $service_id = !empty($request->getservice_id) ? $request->getservice_id : NULL ;
                    $sub_service_id = !empty($request->getsubservice_id) ? $request->getsubservice_id : NULL ;
                    $child_sub_service_id = !empty($request->getchildservice_id) ? $request->getchildservice_id : NULL ;

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
                    $result= file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=AIzaSyB9dxIcEmcLJnl94kqhyNeq4VBfLF-b5Q4");
                    $results=json_decode($result);

                    $latitude='';
                    $longitude='';
                    if($results->status=='OK')
                    {
                        $latitude= $results->results[0]->geometry->location->lat;
                        $longitude= $results->results[0]->geometry->location->lng;
                    }
                /*end service amount calculate*/
                $latitude= isset($latitude)?$latitude:'';
                $longitude= isset($longitude)?$longitude:'';
                if(!empty($user_id)) {

                    $service_request_arr['user_id'] = $user_id;
                    $service_request_arr['category_id'] = $request->getcategory_id;
                    $service_request_arr['service_id'] = $request->getservice_id;
                    $service_request_arr['sub_service_id'] = $request->getsubservice_id;
                    $service_request_arr['child_sub_service_id'] = $request->getchildservice_id;
                    $service_request_arr['city_id'] = $request->getcity_id;
                    $service_request_arr['location'] = $address;
                    $service_request_arr['username'] = $request->username;
                    $service_request_arr['email'] = $request->email;
                    $service_request_arr['latitude'] = $latitude;
                    $service_request_arr['longitude'] = $longitude;
                   
                    $service_request_arr['status'] = 1;
                    $service_request_arr['email_verify'] = 1;
                    $service_request_arr['mobile_number'] = $request->mobile_number;
                    $service_request_arr['created_at'] = Carbon::now();
                    //$service_request_arr['otp'] = $request->otpcode;
                    //dd($service_request_arr);
                   // $service_request_id=20;
                    $service_request_id = DB::table('service_request')->insertGetId($service_request_arr);
                    $servicename=DB::table('services')->where('id',$request->getservice_id)->first();
                    
                    if($service_request_id) 
                    {   
                        $checktext=array();
                        if(!empty($request->optionsdata1))
                        {
                           
                            foreach ($request->optionsdata1 as $key => $value)
                            {
                                foreach ($value as $k => $v)
                                {
                                    if(!empty($k))
                                    {
                                        $questionoption= DB::table('question_options')->where('id',$v)->where('question_id',$k)->select('factor','id')->first();
                                               
                                            if(!empty($questionoption))
                                            {
                                                if(!empty($questionoption->factor))
                                                {
                                                    $service_credit=($service_credit*$questionoption->factor)/100;
                                                }
                                            }

                                        $question_arr['service_request_id'] = $service_request_id;
                                        $question_arr['question_id'] = $k;
                                        $question_arr['option_id']=$v;
                                        $question_arr['created_at'] = Carbon::now();
                                        DB::table('service_request_questions')->insert($question_arr);
                                        array_push($checktext, $k);
                                    }
                                }
                            }
                        }
                        // print_r($checktext);
                        //echo "<pre>"; print_r($request->all()); die('request');exit;
                        foreach($request->questions as $value1) 
                        {
                            if(!empty($value1))
                            {
                                if(!in_array($value1, $checktext))
                                {
                                    $question_arr['service_request_id'] = $service_request_id;
                                    $question_arr['question_id'] = $value1;

                                    $questionoption= DB::table('questions')->where('id',$value1)->select('question_type')->first();
                                    $question_arr['quantity']='NULL';
                                     $question_arr['date_time']='NULL';
                                    if(!empty($questionoption))
                                    {
                                        if($questionoption->question_type=='text')
                                        { 
                                            $question_arr['quantity'] = isset($request->text)?$request->text:'';
                                        }
                                        if($questionoption->question_type=='quantity')
                                        {
                                            $question_arr['quantity'] = isset($request->quantity)?$request->quantity:'';
                                        }
                                        if($questionoption->question_type=='date_time')
                                        {
                                            $question_arr['date_time'] = isset($request->date_time)?$request->date_time:'';
                                        }
                                        if($questionoption->question_type=='date')
                                        {
                                            $question_arr['date_time'] = isset($request->date)?$request->date:'';
                                        }
                                    }

                                    $question_arr['option_id']='';
                                  if(!empty($request->optionsdata))
                                    {
                                        foreach($request->optionsdata as $key=>$value2) 
                                        {

                                            if($value1==$key)
                                            {
                                                $questionoption= DB::table('question_options')->where('id',$value2)->where('question_id',$value1)->select('factor','id')->first();
                                               
                                                if(!empty($questionoption))
                                                {
                                                    $question_arr['option_id'] = isset($questionoption->id)?$questionoption->id:'';
                                                    if(!empty($questionoption->factor))
                                                    {
                                                        $service_credit=($service_credit*$questionoption->factor)/100;
                                                    }
                                                }
                                            } 
                                            
                                        }
                                    }
                                      
                                        $question_arr['created_at'] = Carbon::now();
                                      // echo '<pre>';print_r($question_arr);
                                        DB::table('service_request_questions')->insert($question_arr);
                                }
                            }
                            
                          
                            //$question_arr['created_at'] = Carbon::now();
                        
                           // DB::table('service_request_questions')->insert($question_arr);
                        }
                    }


                        $getcityzone=DB::table('zone')->where('city_id',$request->getcity_id)->get();
                        if(count($getcityzone)>0)
                        {   
                            if(!empty($latitude) && !empty($longitude))
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
                        }
                        else
                        {
                            $service_credit=($service_credit*75)/100;
                        }
                        $serviceName=  $this->sendApportunityNotification($service_request_id,$service_credit);



                    //  $getAllContCompny= DB::table('users')
                    //         ->select('users.username','user_devices.device_id','user_devices.device_type')
                    //         ->leftjoin('user_devices', 'users.id', '=', 'user_devices.user_id')
                    //         ->leftjoin('assign_service_request','assign_service_request.user_id','=','users.id')
                    //         ->where('assign_service_request.service_request_id',$service_request_id)->get();
                    // foreach ($getAllContCompny as $key => $getuser)
                    // {

                    //     $title='¡Nueva Oportunidad!';
                    //     $message='Alguién está buscando de tus servicios, ingresa a OPORTUNIDADES y obtén su información ahora!';
                        
                        
                    //     $userId=0;
                    //     $prouserId=0;
                    //     $serviceId=0;
                    //     $senderId=0;
                    //     $reciverId=0;
                    //     $chatType=0;
                    //     $senderName=$getuser->username;
                    //     $notify_type='new_opportunity';
                    //     $device_id=isset($getuser->device_id)?$getuser->device_id:'';
                    //     $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
                    // }


                    $userdata=DB::table('users')->where('id', $user_id)->first();
                     $userIcon = url('img/logo/logo.jpg');
                    if($userdata->user_group_id==2)
                    {
                        if(isset($userdata->avatar_location) && !empty($userdata->avatar_location))
                        {
                            $userIcon=url('img/user/profile/'.$userdata->avatar_location);
                        }
                    }
                    elseif($userdata->user_group_id==3)
                    {
                        if(isset($userdata->avatar_location) && !empty($userdata->avatar_location))
                        {
                            $userIcon=url('img/contractor/profile/'.$userdata->avatar_location);
                        }
                    }
                    elseif($userdata->user_group_id==4)
                    {
                        if(isset($userdata->avatar_location) && !empty($userdata->avatar_location))
                        {
                            $userIcon=url('img/company/profile/'.$userdata->avatar_location);
                        }
                    }

                    $logo =  url('img/logo/logo-svg.png');
                    $services=isset($servicename->es_name)?$servicename->es_name:'';
                   $usermail=array('email'=>$request->email,'username'=>$request->username, 'avatar_location'=>$userIcon,'logo'=>$logo,'servicess'=>$services);

                    Mail::send('frontend.mail.requestsuccess', ['user' => $usermail], function ($m) use ($usermail) {
                    $m->from(env('MAIL_FROM'));

                    $m->to($usermail['email'])->subject('Solicitud de servicio');
                 });

                  $usermail=array('email'=>$request->email,'username'=>$request->username, 'avatar_location'=>$userIcon,'logo'=> $logo,'servicess'=>$services);

                //    Mail::send('frontend.mail.clientreference', ['user' => $usermail], function ($m) use ($usermail) {
                //     $m->from(env('MAIL_FROM'));
                //     $m->to($usermail['email'])->subject('Referencias positivas');
                // });
                     //auth()->loginUsingId($user_id,true);

                    if($usertype=='new')
                    {
                        Session::put('useremail',$request->email);
                       return redirect()->route('frontend.auth.password.get')->withFlashSuccess(__('alerts.frontend.home.home.your_request_send_successfully')); 
                    }
                    return redirect()->route('frontend.request_success')->withFlashSuccess(__('alerts.frontend.home.home.your_request_send_successfully'));

                } else {
                    return redirect()->route('frontend.index')->withFlashDanger(__('alerts.frontend.home.home.something_went_wrong'));

                }
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

        public function serviceNotification()
        {
            $getAllContCompny= DB::table('users')
                    ->select('users.username','user_devices.device_id','user_devices.device_type','assign_service_request.id','assign_service_request.service_request_id','assign_service_request.user_id')
                    ->leftjoin('user_devices', 'users.id', '=', 'user_devices.user_id')
                    ->leftjoin('assign_service_request','assign_service_request.user_id','=','users.id')
                    ->where('assign_service_request.notification',0)
                    ->orderBy('assign_service_request.id','asc')
                    //->where('assign_service_request.service_request_id',$service_request_id)
                    ->paginate(10);
                    //echo '<pre>'; print_r($getAllContCompny);exit;
            foreach ($getAllContCompny as $key => $getuser)
            {

                $title='¡Nueva Oportunidad!';
                $message='Alguién está buscando de tus servicios, ingresa a OPORTUNIDADES y obtén su información ahora!';
                
                $userId=0;
                $prouserId=0;
                $serviceId=0;
                $senderId=0;
                $reciverId=0;
                $chatType=0;
                $senderName=$getuser->username;
                $notify_type='new_opportunity';
                $device_id=isset($getuser->device_id)?$getuser->device_id:'';
               $result= $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
               DB::table('assign_service_request')->where('id',$getuser->id)->update(['notification'=>1]);
            }
        }
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
                            $insert['notification'] =0; 
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
                    return redirect()->back()->withFlashDanger(__('apimessage.invalid_request_id')); exit;
                }     
        }
        public function uniqueAssocArray($array, $uniqueKey)
        {
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


        public function RequestSuccess()
        {

            return view('frontend.request-success');
            //return view('frontend.auth.passwords.passwordset');
        }

        public function serviceOnline()
        {   
            $servicesonline2 = DB::table('services')
                        ->select('services.*')
                        ->leftjoin('category','category.id','=','services.category_id')
                        ->where('services.deleted_at',NULL)->where('category.es_name','SERVICIOS ONLINE')
                        ->get();
            $services = DB::table('services')->where('deleted_at',NULL)->get();
            $cities = DB::table('cities')->where('deleted_at',NULL)->get();
            $mainCatrgory = DB::table('category')->where('deleted_at',NULL)->where('status',1)->orderBy('id','asc')->get();

            $services = DB::table('services')->where('deleted_at',NULL)->whereNotNull('image')->where('image','!=','')->orderBy('id','desc')->get();
           $servicedata1=array();
            foreach ($services as $key => $service)
            {   $servicedata1[$key]['id']=$service->id;
                $servicedata1[$key]['servicetype']='service';
                $servicedata1[$key]['es_name']=$service->es_name;
               
            }
          
            $subservices = DB::table('sub_services')->where('deleted_at',NULL)->whereNotNull('image')->where('image','!=','')->orderBy('id','desc')->get();
           $subservicess=array();
            foreach ($subservices as $k=> $subservice)
            {
                $subservicess[$k]['id']=$subservice->id;
                $subservicess[$k]['servicetype']='subservice';
                $subservicess[$k]['es_name']=$subservice->es_name;
            }
            $mainCatrgory1=array_merge($servicedata1,$subservicess);

            $servicesonline = DB::table('services')
                        ->select('services.*')
                        ->leftjoin('category','category.id','=','services.category_id')
                        ->where('services.deleted_at',NULL)->where('category.es_name','SERVICIOS ONLINE')
                        ->get();
            $mainCatrgory1=array();
            foreach ($servicesonline as $k=> $online)
            {
                $mainCatrgory1[$k]['id']=$online->id;
                $mainCatrgory1[$k]['servicetype']='service';
                $mainCatrgory1[$k]['es_name']=$online->es_name;
            }
         // echo '<pre>';print_r($mainCatrgory1);exit;
             //return view('frontend.index',compact('services'));
             return view('frontend.service_online',compact('services','cities','mainCatrgory','mainCatrgory1','servicesonline2'));
        }

        public function sendOtpMail(Request $request)
        {  
            //  $request->validate(['required', 'email', 'max:255', Rule::unique('users')]);

            $email = $request->input('email');

            $already_exists = DB::table('users')->where('email',$email)->first();
            
            // if($already_exists){
            //     return response()->json(['success' => false, 'message' => 'Email Already Exists']);
            // } else {
                
                $digits = 4;
                $otpcode= rand(pow(10, $digits-1), pow(10, $digits)-1);
                
                $objDemo = new \stdClass();
                $objDemo->otpcode = $otpcode;
                $objDemo->message = 'Gracias por su solicitud de servicio en Búskalo, utilice este código para completar su solicitud.';
                $objDemo->sender = 'Buskalo';
                $objDemo->receiver = $email;
                $objDemo->username =isset($already_exists->username)?$already_exists->username:'';
                $objDemo->footer_logo = url('img/logo/footer-logo.png');
                $objDemo->logo = url('img/logo/logo-svg.png');
                $objDemo->user_icon = url('img/logo/logo.jpg');
                if(isset($already_exists->username) && !empty($already_exists->username))
                {
                    if($already_exists->user_group_id==2)
                    {
                        if(isset($already_exists->avatar_location) && !empty($already_exists->avatar_location))
                        {
                            $objDemo->user_icon=url('img/user/profile/'.$already_exists->avatar_location);
                        }
                    }
                    elseif($already_exists->user_group_id==3)
                    {
                        if(isset($already_exists->avatar_location) && !empty($already_exists->avatar_location))
                        {
                            $objDemo->user_icon=url('img/contractor/profile/'.$already_exists->avatar_location);
                        }
                    }
                    elseif($already_exists->user_group_id==4)
                    {
                        if(isset($already_exists->avatar_location) && !empty($already_exists->avatar_location))
                        {
                            $objDemo->user_icon=url('img/company/profile/'.$already_exists->avatar_location);
                        }
                    }
                }
                
                Mail::to($email)->send(new ServiceRequestOtp($objDemo));

                return response()->json(['success' => true,'otpcode'=> $otpcode, 'message' => 'Un código OTP ha sido enviado a su correo.']);
            //}
        }

        public function getQuestion(Request $request)
        {  
            // /echo "<pre>"; print_r($request->all()); die;
            $service_id = $request->input('service_id');
            $sub_service_id = $request->input('subservice_id');
            

            $question = DB::table('questions')->where('services_id',$service_id)->where('sub_services_id',$sub_service_id)->where('is_related',0)->where('deleted_at',NULL)->first();

            if($question) {

                $question->options = DB::table('question_options')->where('question_id',$question->id)->where('deleted_at',NULL)->get();
            }

           // echo "<pre>"; print_r($question); die('sdfsd');

            $html = view('frontend.get_question')->with(compact('question'))->render();
            
            //return response()->json(['success' => false, 'message' => 'Email Already Exists']);

            return response()->json(['success' => true,'html'=> $html, 'message' => 'Otp is sent on your email id, PLease check your email']);
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

        public function uploadCropImage(Request $request)
        {
            //echo "<pre>"; print_r($request->all()); die;
            if(isset($_POST['image']))
            {
                $data = $_POST['image'];

                $image_array_1 = explode(";", $data);

                $image_array_2 = explode(",", $image_array_1[1]);
               
                $data = base64_decode($image_array_2[1]);

                $url_name = env('APP_URL');
                $time_str = time();
                $image_url = $url_name.'/buskalo/www/public/img/crop/' . $time_str . '.png';

                $targetDir = public_path() . '/img/crop/';

                $image_name = public_path().'/img/crop/' . $time_str . '.png';

                file_put_contents($image_name, $data);

                // echo $image_url;

                $avtar_name = $time_str.'.png';

                $img_data['success'] = 'true';
                $img_data['avtar_name'] = $avtar_name;
                $img_data['image_url'] = $image_url;

                return json_encode($img_data);
                //echo env('APP_URL'); die('current url');

                //http://localhost/buskalo/www/public/upload_crop_image
            }
        }


        public function mail()
        {

            return view('frontend.mail');
        }
        public function Characteristics_conditions()
        {

            $user_group_id =  Session::get('user_group_id');
            if($user_group_id==3)
            {
                 $term_n_condition = DB::table('term_and_condition')->select('description_cons')->first();
            }
            elseif($user_group_id==4)
            {
                $term_n_condition = DB::table('term_and_condition')->select('description_comp')->first();
            }
            else
            {
               $term_n_condition = DB::table('term_and_condition')->select('description_user')->first(); 
            }


            $user_group_id =  Session::get('user_group_id');

            
           return view('frontend.contractor.characteristics-conditions', compact('term_n_condition','user_group_id'));
        }

        public function work_with_us()
        {

           $work_with_us = DB::table('work_with_us')->where('id',1)->get()->toArray();
           $user_group_id =  Session::get('user_group_id');

            
           return view('frontend.work_with_us', compact('work_with_us','user_group_id'));
        }

        public function about_us()
        {

           $about_us = DB::table('about_us')->where('id',1)->get()->toArray();
           $user_group_id =  Session::get('user_group_id');
            //dd($user_group_id);
           return view('frontend.about_us', compact('about_us','user_group_id'));
        }

        public function how_does_it_work($slug=null)
        {

            if($slug=='user')
            {
                 $how_does_it_work = DB::table('how_it_is_work')->where('id',1)->get()->toArray();
            }
            if($slug=='pro')
            {
                 $how_does_it_work = DB::table('how_it_is_work')->where('id',2)->get()->toArray();
            }
            
           $user_group_id =  Session::get('user_group_id');

           return view('frontend.how_does_it_work', compact('how_does_it_work','user_group_id'));
        }

        public function review_payment_security_policies()
        {

           $review_payment_security_policies = DB::table('security_policy')->where('id',1)->get()->toArray();
           $user_group_id =  Session::get('user_group_id');

           return view('frontend.review_payment_security_policies', compact('review_payment_security_policies','user_group_id'));
        }

        public function paymentApp($id = null, $slug = null, $userId=null)
        {
            
            if(isset($userId) && !empty($userId))
            {
                $userId= $userId;
                 $userdata=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userId)->first();

                 $package = Package::where('deleted_at',NULL)->where('id',$id)->first(); 
                return view('frontend.payment_app', compact('userdata', 'package','userId'));
            }
            else
            {
                return redirect()->to('login');
            }
        }

        public function paymentStore(Request $request)
        {
            $creditPak=DB::table('package')->where('id',$request->packageid)->first();
            $insert['user_id']=$request->userid;
            $insert['trans_id']=$request->response['transaction']['id'];
            $insert['amount']=$creditPak->price;
            $insert['payment_type']='online';
            $insert['credits']=$creditPak->credit;
            $insert['package_id']=$request->packageid;
            $insert['status']='success';
            $userdata= DB::table('users')->where('id',$request->userid)->first();
            $newCredit= $userdata->pro_credit+$creditPak->credit;
            DB::table('users')->where('id',$request->userid)->update(['pro_credit'=>$newCredit]);
            DB::table('payment_history')->insert($insert);
             echo 'success';
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
                'message'=>'Tu compra de recarga de créditos ha sido exitosa.',
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
        }

        public function servicePaymnt(Request $request)
        {

           $userid= isset($_REQUEST['userid'])?$_REQUEST['userid']:'';
           $prouserId= isset($_REQUEST['prouserId'])?$_REQUEST['prouserId']:'';
           $serviceId= isset($_REQUEST['serviceId'])?$_REQUEST['serviceId']:'';

           $checkpaymet=DB::table('user_payment_history')->where('service_id',$serviceId)->where('user_id',$userid)->where('status','success')->first();

            if(isset($checkpaymet) && !empty($checkpaymet))
            {
                return redirect()->to('service/payment?status=paid');
            }
            else
            {
                 $paidrequest=DB::table('user_payment_history')->where('service_id',$serviceId)->where('user_id',$userid)->where('status','pending')->first();

                if(!empty($paidrequest))
                {
                    $payamount= $paidrequest->amount;
                    return view('frontend.payment_request', compact('serviceId', 'prouserId','userid','payamount'));
                }
                else
                {
                     return redirect()->to('service/payment?status=failed');
                    // return redirect()->to('login')->withFlashDanger(__('alerts.frontend.home.home.data_not_found'));
                }
            }
        }

        public function servicePaymentStore(Request $request)
        {

            $insertdata['trans_id']=$request->response['transaction']['id'];
            $insertdata['status']='success';
            $insertdata['updated_at']=now();
            DB::table('assign_service_request')->where('service_request_id',$request->serviceId)->where('user_id',$request->proid)->update(['job_status'=>5,'updated_at'=>now()]);

            DB::table('user_payment_history')->where('user_id',$request->userid)->where('service_id',$request->serviceId)->update($insertdata);
            
             echo 'success';

            $userToken=DB::table('user_devices')
                        ->leftjoin('users','users.id','=','user_devices.user_id')
                        ->select('user_devices.*','users.email')
                        ->where('user_id',$request->proid)->first();
            $userMail=DB::table('users')->where('id',$request->userid)->first();
             if($userMail->user_group_id==3)
            {
                $image= url('img/contractor/profile/'.$userMail->avatar_location);
            }
            if($userMail->user_group_id==4)
            {
                $image= url('img/company/profile/'.$userMail->avatar_location);
            }
            if($userMail->user_group_id==2)
            {
                $image= url('img/user/profile/'.$userMail->avatar_location);
            }

            $device_id=$userToken->device_id;
            $title='Pagado exitosamente';
            $message='El importe del servicio solicitado ha sido abonado con éxito por el usuario '.$userMail->username;
            $userid=$request->userid;
            $prouserId=0;
            $serviceId=0;
            $senderid=0;
            $reciverid=0;
            $chattype='';
            $senderName='';
            $notify_type='';

            if($userToken->device_type=='android')
            {
                $this->postpushnotification($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
            }
            if($userToken->device_type=='ios')
            {
                $this->iospush($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
            }

            //client mail send
            $user=$userMail->email;
            $payment= DB::table('user_payment_history')->where('user_id',$request->userid)->where('service_id',$request->serviceId)->first();
            $data = array(
                'username'=>$userMail->username,
                'receiver'=>$userMail->email,
                'message'=>'Su pago se ha completado correctamente.',
                'iva'=>number_format($payment->iva,2),
                'subtotal'=>number_format($payment->subtotal,2),
                'total'=>number_format($payment->amount,2),
                'transactionID'=>$request->response['transaction']['id'],
                'date'=>date('d-m-Y :H:i:s'),
                'logo'=>url('img/logo/logo-svg.png'),
                'footer_logo'=>url('img/logo/footer-logo.png'),
                'user_icon'=>$image
                );
              Mail::send('frontend.mail.user_confirm_payment',  ['data' => $data], function($message) use ($user){
                 $message->to($user)->subject(__('Pago realizado con éxito', ['app_name' => app_name()]));
            });

              //Pro mail send

            
            $payment= DB::table('user_payment_history')
                            ->select('user_payment_history.*','users.username','users.email','users.avatar_location','users.user_group_id')
                            ->leftjoin('users','users.id','=','user_payment_history.pro_id')
                            ->where('pro_id',$request->proid)
                            ->where('service_id',$request->serviceId)
                            ->first();
            $servicname=DB::table('service_request')
                        ->select('services.es_name')
                        ->leftjoin('services','services.id','=','service_request.service_id')
                        ->where('service_request.id',$request->serviceId)
                        ->first();
            $promail=$payment->email;
            if($payment->user_group_id==3)
            {
                $image= url('img/contractor/profile/'.$payment->avatar_location);
            }
            if($payment->user_group_id==4)
            {
                $image= url('img/company/profile/'.$payment->avatar_location);
            }
            if($payment->user_group_id==2)
            {
                $image= url('img/user/profile/'.$payment->avatar_location);
            }
            $data = array(
                'username'=>$payment->username,
                'receiver'=>$payment->email,
                'message'=>'Se ha pagado correctamente el importe del servicio solicitado.',
                'total'=>number_format($payment->amount,2),
                'clientName'=> $userMail->username,
                'servicename'=>$servicname->es_name,
                'date'=>date('d-m-Y H:i:s'),
                'logo'=>url('img/logo/logo-svg.png'),
                'footer_logo'=>url('img/logo/footer-logo.png'),
                'user_icon'=>$image
                );
              Mail::send('frontend.mail.pro_confirm_payment',  ['data' => $data], function($message) use ($promail){
                 $message->to($promail)->subject(__('Pago realizado con éxito', ['app_name' => app_name()]));
            });
        }

        public function servicePaymntWeb(Request $request)
        {

           $userid= isset($_REQUEST['userid'])?$_REQUEST['userid']:'';
           $prouserId= isset($_REQUEST['prouserId'])?$_REQUEST['prouserId']:'';
           $serviceId= isset($_REQUEST['serviceId'])?$_REQUEST['serviceId']:'';

           $checkpaymet=DB::table('user_payment_history')->where('service_id',$serviceId)->where('user_id',$userid)->where('status','success')->first();

            if(isset($checkpaymet) && !empty($checkpaymet))
            {
                return redirect()->route('frontend.dashboard')->withFlashDanger(__('Payment already paid'));
            }
            else
            {
                 $paidrequest=DB::table('user_payment_history')->where('service_id',$serviceId)->where('user_id',$userid)->where('status','pending')->first();

                if(!empty($paidrequest))
                {
                    $payamount= $paidrequest->amount;
                    return view('frontend.payment_request_web', compact('serviceId', 'prouserId','userid','payamount'));
                }
                else
                {
                     //return redirect()->to('service/payment?status=failed');
                    return redirect()->to('login')->withFlashDanger(__('alerts.frontend.home.home.data_not_found'));
                }
            }
        }

        public function servicePaymentStoreWeb(Request $request)
        {

            $insertdata['trans_id']=$request->response['transaction']['id'];
            $insertdata['status']='success';
            $insertdata['updated_at']=now();
            DB::table('assign_service_request')->where('service_request_id',$request->serviceId)->where('user_id',$request->proid)->update(['job_status'=>5,'updated_at'=>now()]);

            DB::table('user_payment_history')->where('user_id',$request->userid)->where('service_id',$request->serviceId)->update($insertdata);
            
             echo 'success';

            $userToken=DB::table('user_devices')
                        ->leftjoin('users','users.id','=','user_devices.user_id')
                        ->select('user_devices.*','users.email')
                        ->where('user_id',$request->proid)->first();
            $userMail=DB::table('users')->where('id',$request->userid)->first();
             if($userMail->user_group_id==3)
            {
                $image= url('img/contractor/profile/'.$userMail->avatar_location);
            }
            if($userMail->user_group_id==4)
            {
                $image= url('img/company/profile/'.$userMail->avatar_location);
            }
            if($userMail->user_group_id==2)
            {
                $image= url('img/user/profile/'.$userMail->avatar_location);
            }

            $device_id=$userToken->device_id;
            $title='Pagado exitosamente';
            $message='El importe del servicio solicitado ha sido abonado con éxito por el usuario '.$userMail->username;
            $userid=$request->userid;
            $prouserId=0;
            $serviceId=0;
            $senderid=0;
            $reciverid=0;
            $chattype='';
            $senderName='';
            $notify_type='';

            if($userToken->device_type=='android')
            {
                $this->postpushnotification($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
            }
            if($userToken->device_type=='ios')
            {
                $this->iospush($device_id,$title,$message,$userid,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
            }

            //client mail send
            $user=$userMail->email;
            $payment= DB::table('user_payment_history')->where('user_id',$request->userid)->where('service_id',$request->serviceId)->first();
            $data = array(
                'username'=>$userMail->username,
                'receiver'=>$userMail->email,
                'message'=>'Su pago se ha completado correctamente.',
                'iva'=>number_format($payment->iva,2),
                'subtotal'=>number_format($payment->subtotal,2),
                'total'=>number_format($payment->amount,2),
                'transactionID'=>$request->response['transaction']['id'],
                'date'=>date('d-m-Y :H:i:s'),
                'logo'=>url('img/logo/logo-svg.png'),
                'footer_logo'=>url('img/logo/footer-logo.png'),
                'user_icon'=>$image
                );
              Mail::send('frontend.mail.user_confirm_payment',  ['data' => $data], function($message) use ($user){
                 $message->to($user)->subject(__('Pago realizado con éxito', ['app_name' => app_name()]));
            });

              //Pro mail send

            
            $payment= DB::table('user_payment_history')
                            ->select('user_payment_history.*','users.username','users.email','users.avatar_location','users.user_group_id')
                            ->leftjoin('users','users.id','=','user_payment_history.pro_id')
                            ->where('pro_id',$request->proid)
                            ->where('service_id',$request->serviceId)
                            ->first();
            $servicname=DB::table('service_request')
                        ->select('services.es_name')
                        ->leftjoin('services','services.id','=','service_request.service_id')
                        ->where('service_request.id',$request->serviceId)
                        ->first();
            $promail=$payment->email;
            if($payment->user_group_id==3)
            {
                $image= url('img/contractor/profile/'.$payment->avatar_location);
            }
            if($payment->user_group_id==4)
            {
                $image= url('img/company/profile/'.$payment->avatar_location);
            }
            if($payment->user_group_id==2)
            {
                $image= url('img/user/profile/'.$payment->avatar_location);
            }
            $data = array(
                'username'=>$payment->username,
                'receiver'=>$payment->email,
                'message'=>'Se ha pagado correctamente el importe del servicio solicitado.',
                'total'=>number_format($payment->amount,2),
                'clientName'=> $userMail->username,
                'servicename'=>$servicname->es_name,
                'date'=>date('d-m-Y H:i:s'),
                'logo'=>url('img/logo/logo-svg.png'),
                'footer_logo'=>url('img/logo/footer-logo.png'),
                'user_icon'=>$image
                );
              Mail::send('frontend.mail.pro_confirm_payment',  ['data' => $data], function($message) use ($promail){
                 $message->to($promail)->subject(__('Pago realizado con éxito', ['app_name' => app_name()]));
            });
            
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
             // print_r($result);//die;
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
            $tokenLength = strlen($device_id);
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
        }

        public function faqPage($slug=null)
        {
            $faqLists=[];
            if($slug=='user')
            {
                 $faqLists=DB::table('faq')->where('status',1)->where('question_type','1')->get();
            }
            if($slug=='pro')
            {
                 $faqLists=DB::table('faq')->where('status',1)->where('question_type','2')->get();
            }
           
            return view('frontend.faq_page', compact('faqLists'));
        }

        public function userRefundget()
        {
            return view('frontend.user.users-refund');
        }

         public function proRefundget()
        {
            return view('frontend.user.professional-refund');
        }

        public function refundRequest(Request $request)
        {
            if(Auth::user())
            {
                    $transID=DB::table('payment_history')->where('user_id',Auth::user()->id)->where('trans_id',$request->transaction_id)->first();
                    if(empty($transID))
                    {
                        return redirect()->back()->withFlashDanger(__('transaction id does not exit'));
                    }
                    $refund=array('user_id'=>Auth::user()->id,
                            'user_group_id'=>Auth::user()->user_group_id,
                            'name'=>$request->name,
                            'email'=>$request->email,
                            'pro_amount'=>$transID->amount,
                            'refund_resion'=>$request->refund_resion,
                            'pro_comany_name'=>$request->pro_company,
                            'payment_date'=>isset($request->payment_date)?$request->payment_date:'Y-m-d H:i:s',
                            'amount_total'=>$request->amount_total,
                            'transaction_id'=>$request->transaction_id,
                            'amount_parcial'=>$request->amount_parcial,
                            );
                    DB::table('refund_requests')->insert($refund);   
                    $userdata= DB::table('users')->where('id',Auth::user()->id)->first();
                    
                     $image='';
                    if($userdata->user_group_id==3)
                    {
                        $image= url('img/contractor/profile/'.$userdata->avatar_location);
                    }
                    elseif($userdata->user_group_id==4)
                    {
                        $image= url('img/company/profile/'.$userdata->avatar_location);
                    }
                    else
                    {
                         $image= url('img/user/profile/'.$userdata->avatar_location);
                    }
                    $email=Auth::user()->email;
                    $data = array(
                        'username'=>Auth::user()->username,
                        'receiver'=>Auth::user()->email,
                        'message'=>'Hemos recibido tu solicitud de reembolso.<br/>Pronto te contactaremos para verificar información o confirmar la devolución.',
                        'logo'=>url('img/logo/logo-svg.png'),
                        'footer_logo'=>url('img/logo/footer-logo.png'),
                        'user_icon'=>$image
                        );
                      Mail::send('frontend.mail.refund_request',  ['data' => $data], function($message) use ($email){
                         $message->to($email)->subject(__('Solicitud de reembolso', ['app_name' => app_name()]));
                    });
                     return redirect()->back()->withFlashSuccess(__('Payment Refund request send successfully.'));

            }else
            {
                return redirect()->back()->withFlashDanger(__('PLease Login first after submit request'));
            }
        }

        public function servicesOnline(Request $request)
        {
              $servicesonline2 = DB::table('services')
                        ->select('services.*')
                        ->leftjoin('category','category.id','=','services.category_id')
                        ->where('services.deleted_at',NULL)->where('category.es_name','SERVICIOS ONLINE')
                        ->get();
                         $cities = DB::table('cities')->where('deleted_at',NULL)->get();
                        return view('frontend.allserviceonline', compact('servicesonline2','cities'));
        }

        public function proOrCompanyRating(Request $request)
        {
            return view('frontend.user.review');
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
             return redirect()->route('frontend.user.dashboard')->withFlashDanger(__('alerts.frontend.company.profile.you_have_already_added_review'));

          }



          $review_data = array();

          $review_data['user_id'] = $user_id;
          $review_data['review_by'] = 'company';
          $review_data['to_user_id'] = $to_user;
          $review_data['request_id'] = $service_request_id;
          $review_data['rating'] = number_format($total_rating,1);
          $review_data['price'] = $price_rating;
          $review_data['puntuality'] = $puntuality_rating;
          $review_data['service'] = $service_rating;
          $review_data['quality'] = $quality_rating;
          $review_data['amiability'] = $amiability_rating;
          $review_data['review'] = $review;

          $review_id = DB::table('reviews')->insertGetId($review_data);

          if($review_id){
            return redirect()->route('frontend.user.dashboard')->withFlashSuccess(__('alerts.frontend.company.profile.review_submited_successfully'));
          }else{
            return redirect()->route('frontend.user.dashboard')->withFlashDanger(__('alerts.frontend.company.profile.something_went_wrong'));
          }
        }
}
