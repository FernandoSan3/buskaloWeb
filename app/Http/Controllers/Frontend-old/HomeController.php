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
use Validator;
use Session;



/**
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
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
      
        //return view('frontend.index',compact('services','cities','categories'));
        return view('frontend.first_screen',compact('services','cities'));
    }

     public function homePage()
    {   
        $services = DB::table('services')->where('deleted_at',NULL)->limit(12)->get();
        foreach ($services as $key => $value) 
        {
            
            $subservices = array();
            $subservices = DB::table('sub_services')->where('services_id',$value->id)->where('deleted_at',NULL)->get();
            $value->subservices = $subservices;
        }

        $cities = DB::table('cities')->where('deleted_at',NULL)->get();
        $categories = DB::table('category')->where('status',1)->where('deleted_at',NULL)->get();
      
        return view('frontend.index',compact('services','cities','categories'));
        //return view('frontend.first_screen',compact('services','cities'));
    }

    public function userSelection(Request $request)
    {
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

    public function redirectRegister()
    {   
        $user_group_id =  Session::get('user_group_id');       
        return view('frontend.auth.register',compact('user_group_id'));
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

                $avtar =  $userId . '.png';
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

                $avtar =  $userId . '.png';
                $file = $folderPath . $avtar;
           
                file_put_contents($file, $image_base64);

                $userData['avatar_location'] =  $avtar;
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
            return redirect()->route('frontend.auth.login')->withFlashSuccess(__('Su paso de registro ya se completó.!'));

        } else  {
             return view('frontend.contractor_profile')
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

                $avtar =  $userId . '.png';
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
            return redirect()->route('frontend.auth.login')->withFlashSuccess(__('Su paso de registro ya se completó.!'));

        }else  {
            return view('frontend.company_profile')
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
        //$mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
        $website_address = !empty($request->website_address) ? $request->website_address : '' ;

 
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
        //$userData['mobile_number'] = $mobile_number;
        $userData['website_address'] = $website_address;
        $userData['is_verified'] =1;
        $userData['confirmed'] = 0;
        $userData['is_confirm_reg_step'] = 1;
        // $userData['avatar_location'] =  $profile;
        $userData['updated_at'] = Carbon::now()->toDateTimeString();
        DB::table('users')->where('id',$userId)->update($userData);



        return redirect()->route('frontend.auth.login')->withFlashSuccess(__('¡Perfil completado con éxito! Le hemos enviado un correo electrónico para confirmar su cuenta.'));

    }
    /*companyProfileCompletion function end*/

    /*contractorProfileCompletion start*/
    // Request $request
    public function contractorProfileCompletion(ContractorRegisterRequest $request)
    {
        $userData=array();
        $userId=!empty($request->user_id) ? $request->user_id : '' ;
            //Multiple
        $images_gallery=!empty($request->images_gallery) ? $request->images_gallery : '' ;
        $videos_gallery=!empty($request->videos_gallery) ? $request->videos_gallery : '' ;

        $facebook_url=!empty($request->facebook_url) ? $request->facebook_url : '' ;
        $instagram_url=!empty($request->instagram_url) ? $request->instagram_url : '' ;
        $linkedin_url=!empty($request->linkedin_url) ? $request->linkedin_url : '' ;
        $twitter_url=!empty($request->twitter_url) ? $request->twitter_url : '' ;
        $other_url=!empty($request->other) ? $request->other : '' ;

        //$username = !empty($request->username) ? $request->username : '' ;
        $identity_no= !empty($request->identity_no) ? $request->identity_no : '' ;
        $profile_title = !empty($request->profile_title) ? $request->profile_title : '' ;
        $address = !empty($request->address) ? $request->address : '' ;
        //$mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
        $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;


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
        //$userData['mobile_number'] = $mobile_number;
        $userData['landline_number'] = $landline_number;
        $userData['is_verified'] =1;
        $userData['confirmed'] = 0;
        $userData['is_confirm_reg_step'] = 1;
        // $userData['avatar_location'] =  $profile;
        $userData['updated_at'] = Carbon::now()->toDateTimeString();
        DB::table('users')->where('id',$userId)->update($userData);



        return redirect()->route('frontend.auth.login')->withFlashSuccess(__('¡Perfil completado con éxito! Le hemos enviado un correo electrónico para confirmar su cuenta.'));

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
        $city_id = Crypt::decrypt($city_id);
        $selected_type = Crypt::decrypt($selected_type);

        $user_id = 0;
        if(auth()->user()) 
        {
            $user_id = auth()->user()->id;
            $user_detail = DB::table('users')->where('id',$user_id)->first();
        }

        $getCategoryData=DB::table('category')->select('en_name')->where('id',$category_id)->first();
        $getCityData=DB::table('cities')->select('name')->where('id',$city_id)->first();
        $allServices = DB::table('services')->where('category_id',$category_id)->get();

        $allSubServices=array();
        $allChildServices=array();
        $questionArr=array();
        $firstQuestID="";

        if($selected_type=='service')
        {
          $serviceId=$category_id;

          $allSubServices = DB::table('sub_services')->where('services_id',$serviceId)->get();

          $category_id=DB::table('services')->where('id',$serviceId)->value('category_id');

          $getServicename = DB::table('services')->where('id',$serviceId)->where('deleted_at',NULL)->first();

          $servicename=""; if(!empty($getServicename)) { $servicename=$getServicename->en_name; }

          $getCategoryData=DB::table('category')->select('en_name')->where('id',$category_id)->first();

          $allServices = DB::table('services')->where('category_id',$category_id)->get();

        }
         if($selected_type=='sub_service')
        {

          $subServiceId=$category_id;

          $allChildServices = DB::table('child_sub_services')->where('sub_services_id',$subServiceId)->get()->toArray();

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

                    if(!empty($questionArr)) 
                    {
                         $firstQuestID=$questionArr->id;

                         $questionArr->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$questionArr->id."' AND deleted_at IS null )")
                        ->get()->toArray();
                    }

          }
          

          $serviceId=DB::table('sub_services')->where('id',$subServiceId)->value('services_id');

          $allSubServices = DB::table('sub_services')->where('services_id',$serviceId)->get();

          $category_id=DB::table('services')->where('id',$serviceId)->value('category_id');

          $getServicename = DB::table('services')->where('id',$serviceId)->where('deleted_at',NULL)->first();
          $servicename=""; if(!empty($getServicename)) { $servicename=$getServicename->en_name; }


          $getSubServicename = DB::table('sub_services')->where('id',$subServiceId)->where('deleted_at',NULL)->first();
          $subservicename=""; if(!empty($getSubServicename)) { $subservicename=$getSubServicename->en_name; }

          $getCategoryData=DB::table('category')->select('en_name')->where('id',$category_id)->first();

          $allServices = DB::table('services')->where('category_id',$category_id)->get();

        }

         if($selected_type=='child_sub_service')
        {
                $child_sub_serviceId=$category_id;

                    //Get 
                     $getallTypeId = DB::table('child_sub_services')
                    ->select('category_id','services_id','sub_services_id')
                    ->whereRaw("(status=1)")
                    ->whereRaw("(id = '".$child_sub_serviceId."' AND deleted_at IS null )")
                    ->first();

                    $catId=""; $servId=""; $subServId=""; $childSubServId="";

                    if(!empty($getallTypeId))
                    {
                       $catId = $getallTypeId->category_id;
                       $servId =  $getallTypeId->services_id;
                       $subServId = $getallTypeId->sub_services_id;
                       $childSubServId = $child_sub_serviceId;
                    }

                    $questionArr = DB::table('questions')
                    ->whereRaw("(questions.status=1)")
                    ->whereRaw("(questions.question_order=1)")
                    ->whereRaw("(questions.is_related=0)")
                    ->whereRaw("(questions.category_id = '".$catId."')")
                    ->whereRaw("(questions.services_id = '".$servId."')")
                    ->whereRaw("(questions.sub_services_id = '".$subServId."')")
                    ->whereRaw("(questions.child_sub_service_id = '".$childSubServId."' AND deleted_at IS null )")
                    ->first();

                    if(!empty($questionArr)) 
                    {
                         $firstQuestID=$questionArr->id;

                         $questionArr->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$questionArr->id."' AND deleted_at IS null )")
                        ->get()->toArray();
                    }

          $subServiceId=DB::table('child_sub_services')->where('id',$child_sub_serviceId)->value('sub_services_id');

          $allChildServices = DB::table('child_sub_services')->where('sub_services_id',$subServiceId)->get();

          $getchildname = DB::table('child_sub_services')->where('id',$child_sub_serviceId)->where('deleted_at',NULL)->first();
          $childsubservicename=""; if(!empty($getchildname)) { $childsubservicename=$getchildname->en_name; }

          $serviceId=DB::table('sub_services')->where('id',$subServiceId)->value('services_id');

          $allSubServices = DB::table('sub_services')->where('services_id',$serviceId)->get();

          $category_id=DB::table('services')->where('id',$serviceId)->value('category_id');

          $getServicename = DB::table('services')->where('id',$serviceId)->where('deleted_at',NULL)->first();
          $servicename=""; if(!empty($getServicename)) { $servicename=$getServicename->en_name; }

          $getSubServicename = DB::table('sub_services')->where('id',$subServiceId)->where('deleted_at',NULL)->first();
          $subservicename=""; if(!empty($getSubServicename)) { $subservicename=$getSubServicename->en_name; }

          $getCategoryData=DB::table('category')->select('en_name')->where('id',$category_id)->first();

          $allServices = DB::table('services')->where('category_id',$category_id)->get();

        }

         return view('frontend.categories-step',compact('user_id','getCategoryData','getCityData','allServices','category_id','city_id','selected_type','allSubServices','servicename','serviceId',
            'subServiceId','allChildServices','subservicename','childsubservicename','child_sub_serviceId','questionArr','firstQuestID'));
    }



   public function ajaxGetSubservice(Request $request) 
         {  

                $serviceId = $request->input('serviceId');
                $categoryId = $request->input('categoryId');

                $getServicename = DB::table('services')->where('id',$serviceId)->where('deleted_at',NULL)->first();
                 $servicename="";
                if(!empty($getServicename))
                {
                    $servicename=$getServicename->en_name;
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
               
                 $subservicename="";
                if(!empty($getSubServicename))
                {
                    $subservicename=$getSubServicename->en_name;
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

                            if(!empty($question)) 
                            {
                                 $question->options = DB::table('question_options')
                                ->select('id','en_option','es_option','created_at','status')
                                ->whereRaw("(status=1)")
                                ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                                ->get()->toArray();
                            }

                      }
                      


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
                     echo json_encode(['success' => true,'childservices'=> $childservices, 'questionData'=> $question,'message' => $msg, 'subservicename' => $subservicename]);
                }
                else
                {
                     echo json_encode(['success' => false,'childservices'=> '', 'message' => 'notFoundChildservice','questionData'=> '', 'subservicename' => '']);
                }
                
        }


         public function ajaxGetQuestions(Request $request) 
         {  

            $categoryId = $request->input('categoryId');
            $serviceId = $request->input('serviceId');
            $subserviceId = $request->input('subserviceId');
            $childsubserviceId = $request->input('childsubserviceId');

             $getChildSubServicename = DB::table('child_sub_services')->where('id',$childsubserviceId)->where('deleted_at',NULL)->first();
                 $childsubservicename="";
                if(!empty($getChildSubServicename))
                {
                    $childsubservicename=$getChildSubServicename->en_name;
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

                    
                    if(!empty($question))
                    {
                         $question->options = DB::table('question_options')
                        ->select('id','en_option','es_option','created_at','status')
                        ->whereRaw("(status=1)")
                        ->whereRaw("(question_id = '".$question->id."' AND deleted_at IS null )")
                        ->get();

                        return response()->json(['success' => true,'questionData'=> $question, 'childsubservicename'=> $childsubservicename, 'message' => 'Question found']);
                    }else
                    {
                       return response()->json(['success' => true,'questionData'=> '', 'childsubservicename'=> '', 'message' => 'Question Not found']); 
                    }
                    
                }

                else
                {
                     return response()->json(['success' => true,'questionData'=> '', 'childsubservicename'=> '', 'message' => 'Question Not found']);
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
                  return response()->json(['success' => false, 'message' => 'Mobile Number Already Exists.']); die;
                }else
                {
                 return response()->json(['success' => true, 'message' => 'Mobile Number Not Exists.']); die;
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

              $getQuestioName = DB::table('questions')->where('id',$question_id)->where('deleted_at',NULL)->first();
                 $questionname="";
                if(!empty($getQuestioName))
                {
                    $questionname=$getQuestioName->en_title;
                }

                 $getQuestionOptionName = DB::table('question_options')->where('id',$option_id)->where('deleted_at',NULL)->first();
                 $questionoptionname="";
                if(!empty($getQuestionOptionName))
                {
                    $questionoptionname=$getQuestionOptionName->en_option;
                }


                if(!empty($catId) && !empty($servId) && !empty($subServId) && !empty($childSubServId) && !empty($question_id) && !empty($option_id))
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
                            return response()->json(['success' => true,'nextQuestionData'=> $question,'message' => 'Question found','questionname'=>$questionname,'questionoptionname'=> $questionoptionname]);
                        }else
                        {
                           return response()->json(['success' => false,'nextQuestionData'=> '','message' => 'Question Not found','questionname'=>'','questionoptionname'=>'']);
                        }

                    
                }


                else
                {
                    return response()->json(['success' => false,'nextQuestionData'=> '','message' => 'Question Not found','questionname'=>'','questionoptionname'=>'']);
                }
                
        }

     public function storeServiceRequest(Request $request) 
    {   
        //die('stores service request');

        //echo "<pre>"; print_r($request->all()); die('request');

            $user_arr['uuid'] = Uuid::uuid4()->toString();
            $user_arr['username'] = $request->username;
            $user_arr['user_group_id'] = 2;
            $user_arr['mobile_number'] = $request->mobile_number;
            $user_arr['email'] = $request->email;            
            $user_arr['address'] = $request->address;
            $user_arr['confirmed'] = 1;
            $user_arr['is_verified'] = 1;
            $user_arr['created_at'] = Carbon::now();

            $user_id =  DB::table('users')->insertGetId($user_arr);

            if($user_id) {

                $service_request_arr['user_id'] = $user_id;
                $service_request_arr['category_id'] = $request->getcategory_id;
                $service_request_arr['service_id'] = $request->getservice_id;
                $service_request_arr['sub_service_id'] = $request->getsubservice_id;
                $service_request_arr['child_sub_service_id'] = $request->getchildservice_id;
                $service_request_arr['city_id'] = $request->getcity_id;
                $service_request_arr['location'] = $request->address;
                $service_request_arr['username'] = $request->username;
                $service_request_arr['email'] = $request->email;
                //$service_request_arr['otp'] = $request->otpcode;
                $service_request_arr['status'] = 1;
                $service_request_arr['email_verify'] = 1;
                $service_request_arr['mobile_number'] = $request->mobile_number;
                $service_request_arr['created_at'] = Carbon::now();

                $service_request_id = DB::table('service_request')->insertGetId($service_request_arr);
            
                if($service_request_id) 
                {
                

                    foreach ($request->questions as $value1) 
                    {
                        
                        $question_arr['service_request_id'] = $service_request_id;
                        $question_arr['question_id'] = $value1;

                        foreach ($request->options as $value2) 
                        {
                          $question_arr['option_id'] = $value2;
                        }
                        
                        $question_arr['created_at'] = Carbon::now();
                        
                        DB::table('service_request_questions')->insert($question_arr);
                    }

                }
                 //auth()->loginUsingId($user_id,true);

                return redirect()->route('frontend.request_success')->withFlashSuccess(__('Your Request send Successfully.'));

            } else {
                return redirect()->route('frontend.index')->withFlashDanger('success','Something went wrong.');

            }
    
    }


    public function RequestSuccess() {

        return view('frontend.request-success');

    }

    public function serviceOnline()
    {   
         $services = DB::table('services')->where('deleted_at',NULL)->get();
         //return view('frontend.index',compact('services'));
         return view('frontend.service_online',compact('services'));
    }


    public function sendOtpMail(Request $request) {  

        //  $request->validate(['required', 'email', 'max:255', Rule::unique('users')]);

        $email = $request->input('email');

        $already_exists = DB::table('users')->where('email',$email)->first();
        
        if($already_exists){
            return response()->json(['success' => false, 'message' => 'Email Already Exists']);
        } else {
            
            $digits = 4;
            $otpcode= rand(pow(10, $digits-1), pow(10, $digits)-1);
            
            $objDemo = new \stdClass();
            $objDemo->otpcode = 'Your unique code is: '.$otpcode;
            $objDemo->message = 'Thank You for your service request in buskalo, please use this otp for complete your request.';
            $objDemo->sender = 'Buskalo';
            $objDemo->receiver = $email;
            
            Mail::to($email)->send(new ServiceRequestOtp($objDemo));

            return response()->json(['success' => true,'otpcode'=> $otpcode, 'message' => 'Otp is sent on your email id, PLease check your email']);
        }

       
    }

    public function getQuestion(Request $request) {  

      

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


    public function uploadCropImage(Request $request) {

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






}
