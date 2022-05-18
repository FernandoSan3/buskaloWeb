<?php

namespace App\Http\Controllers\Frontend\Contractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
use Illuminate\Support\Facades\File;

/**
 * Class JobsController.
 */
class JobsController extends Controller
{
    public function index()
        {
            $allData=array();
            $userid= auth()->user()->id;
			$lang = 'en' ;

              if(!empty($userid)) 
                    {
                       
                            $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($userEntity))
                            {
                                 $servicesbuy = DB::table('assign_service_request')
                                ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')

                                ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
                                ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                                ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
                                ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
                                ->leftjoin('cities', 'service_request.city_id', '=', 'cities.id')

                                ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','service_request.email_verify','service_request.created_at','assign_service_request.tranx_status','assign_service_request.tranx_id','service_request.mobile_number','service_request.email','service_request.user_id','service_request.latitude','service_request.longitude','service_request.assigned_user_id','category.en_name AS category_en_name', 'category.es_name AS category_es_name','services.en_name AS service_en_name', 'services.es_name AS service_es_name', 'services.image','sub_services.en_name AS sub_service_en_name', 'sub_services.es_name AS sub_service_es_name','child_sub_services.en_name AS child_subservice_en_name', 'child_sub_services.es_name AS child_subservice_es_name','cities.name AS city_name')
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

                                        if(!empty($vall->assigned_user_id) && $vall->assigned_user_id!==NULL)
                                        {
                                            $userSideAccept='accepted';
                                        }else
                                        {
                                            $userSideAccept='not approved by user';
                                        }
                                        
                                      $data1['job_status'] = $userSideAccept;  
                                     $data1['city_name'] = isset($vall->city_name) && !empty($vall->city_name) ? $vall->city_name : '';

                            // category
                            $data1['category_id'] = isset($vall->category_id) && !empty($vall->category_id) ? $vall->category_id : '';
                             if($lang=='es')
                                    {$category_name=$vall->category_es_name;}
                                else{$category_name=$vall->category_en_name;}

                            $data1['category_name'] = isset($category_name) && !empty($category_name) ? $category_name : '';
                            //End category

                            // service
                            $data1['service_id'] = isset($vall->service_id) && !empty($vall->service_id) ? $vall->service_id : '';
                             if($lang=='es')
                                    {$service_name=$vall->service_es_name;}
                                else{$service_name=$vall->service_en_name;}

                            $data1['service_name'] = isset($service_name) && !empty($service_name) ? $service_name : '';
                            $data1['service_image'] = url('/img/'.$vall->image);
                            //End Service

                            // subService
                            $data1['subservice_id'] = isset($vall->sub_service_id) && !empty($vall->sub_service_id) ? $vall->sub_service_id : '';
                             if($lang=='es')
                                    {$subservice_name=$vall->sub_service_es_name;}
                                else{$subservice_name=$vall->sub_service_en_name;}

                            $data1['subservice_name'] = isset($subservice_name) && !empty($subservice_name) ? $subservice_name : '';
                            //End subService

                            // child subservice
                            $data1['child_sub_service_id'] = isset($vall->child_sub_service_id) && !empty($vall->child_sub_service_id) ? $vall->child_sub_service_id : ''; 
                             if($lang=='es')
                                    {$child_subservice_name=$vall->child_subservice_es_name;}
                                else{$child_subservice_name=$vall->child_subservice_en_name;}
                            $data1['child_sub_service_name'] = isset($child_subservice_name) && !empty($child_subservice_name) ? $child_subservice_name : '';
                            //End child subService


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
                                        ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option','questions.question_type')
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
                                           $data2['question_type'] = $que->question_type;
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
			                               return view('frontend.contractor.jobs')->withUser($userEntity)->withData($allData); 
			                            }
			                            else
			                            {
			                               return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('job List Not Found.!'));   
			                            }

                                }

                             else
                               {
                                     return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('job List Not Found.!'));   
                               }
       
                            }
                            else
                            {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid user')); 
                            }
                    }else
                    {
                        return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid parameter'));
                    }

        }



        /* --------------------Job Detail Api Start-------------------- */


        public function jobDetail($job_id)
        {

			$job_id = Crypt::decrypt($job_id);
			$allData = array();
			$userid= auth()->user()->id;
			$lang = 'en' ;


              if(!empty($userid) && !empty($job_id)) 
                    {
							 $userEntity = DB::table('users')
                            ->whereRaw("(active=1)")
                            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                            ->first();

                            if(!empty($userEntity))
                            {
                                $servicesbuy = DB::table('assign_service_request')
                                ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')

								->leftjoin('category', 'service_request.category_id', '=', 'category.id')
								->leftjoin('services', 'service_request.service_id', '=', 'services.id')
								->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
								->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
								->leftjoin('cities', 'service_request.city_id', '=', 'cities.id')

                                ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','service_request.email_verify','service_request.created_at','assign_service_request.tranx_status','assign_service_request.tranx_id','service_request.mobile_number','service_request.email','service_request.user_id','service_request.latitude','service_request.longitude','service_request.assigned_user_id','category.en_name AS category_en_name', 'category.es_name AS category_es_name','services.en_name AS service_en_name', 'services.es_name AS service_es_name', 'services.image','sub_services.en_name AS sub_service_en_name', 'sub_services.es_name AS sub_service_es_name','child_sub_services.en_name AS child_subservice_en_name', 'child_sub_services.es_name AS child_subservice_es_name','cities.name AS city_name')
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

                                        if(!empty($vall->assigned_user_id) && $vall->assigned_user_id!==NULL)
                                        {
                                            $userSideAccept='accepted';
                                        }else
                                        {
                                            $userSideAccept='not approved by user';
                                        }
                                        
                                      $data1['job_status'] = $userSideAccept;  
                                     $data1['city_name'] = isset($vall->city_name) && !empty($vall->city_name) ? $vall->city_name : '';

				                    // category
				                    $data1['category_id'] = isset($vall->category_id) && !empty($vall->category_id) ? $vall->category_id : '';
				                     if($lang=='es')
				                            {$category_name=$vall->category_es_name;}
				                        else{$category_name=$vall->category_en_name;}

				                    $data1['category_name'] = isset($category_name) && !empty($category_name) ? $category_name : '';
				                    //End category

				                    // service
				                    $data1['service_id'] = isset($vall->service_id) && !empty($vall->service_id) ? $vall->service_id : '';
				                     if($lang=='es')
				                            {$service_name=$vall->service_es_name;}
				                        else{$service_name=$vall->service_en_name;}

				                    $data1['service_name'] = isset($service_name) && !empty($service_name) ? $service_name : '';
				                    $data1['service_image'] = url('/img/'.$vall->image);
				                    //End Service

				                    // subService
				                    $data1['subservice_id'] = isset($vall->sub_service_id) && !empty($vall->sub_service_id) ? $vall->sub_service_id : '';
				                     if($lang=='es')
				                            {$subservice_name=$vall->sub_service_es_name;}
				                        else{$subservice_name=$vall->sub_service_en_name;}

				                    $data1['subservice_name'] = isset($subservice_name) && !empty($subservice_name) ? $subservice_name : '';
				                    //End subService

				                    // child subservice
				                    $data1['child_sub_service_id'] = isset($vall->child_sub_service_id) && !empty($vall->child_sub_service_id) ? $vall->child_sub_service_id : ''; 
				                     if($lang=='es')
				                            {$child_subservice_name=$vall->child_subservice_es_name;}
				                        else{$child_subservice_name=$vall->child_subservice_en_name;}
				                    $data1['child_sub_service_name'] = isset($child_subservice_name) && !empty($child_subservice_name) ? $child_subservice_name : '';
				                    //End child subService


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
                                        ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option','questions.question_type')
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
                                           $data2['question_type'] = $que->question_type;
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

                                    //echo "<pre>"; print_r($data1);die;

                                     return view('frontend.contractor.job_details')->withData($data1); 

                                }

	                            else
	                            {
	                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('List not found.!')); 
	                            }
       
                            }
                            else
                            {
                               return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid user.!')); 
                            }
                    }else
                    {
                      return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid parameter.!')); 
                    }

        }


         /* --------------------Job Detail End-------------------- */





    public function opportunities()
    {

            $allData=array();
			$userid= auth()->user()->id;
			$lang = 'en' ;
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

                               ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
                               ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                               ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
                               ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
                               ->leftjoin('cities', 'service_request.city_id', '=', 'cities.id')

                              ->select('assign_service_request.id','assign_service_request.service_request_id','assign_service_request.user_id','assign_service_request.request_status','service_request.service_id','service_request.category_id','service_request.sub_service_id','service_request.child_sub_service_id','service_request.location','service_request.username','service_request.email','service_request.status','service_request.email_verify','service_request.created_at','category.en_name AS category_en_name', 'category.es_name AS category_es_name','services.en_name AS service_en_name', 'services.es_name AS service_es_name', 'services.image','sub_services.en_name AS sub_service_en_name', 'sub_services.es_name AS sub_service_es_name','child_sub_services.en_name AS child_subservice_en_name', 'child_sub_services.es_name AS child_subservice_es_name','cities.name AS city_name')
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

                                      $data1['city_name'] = isset($vall->city_name) && !empty($vall->city_name) ? $vall->city_name : '';

                                      // category
                                      $data1['category_id'] = isset($vall->category_id) && !empty($vall->category_id) ? $vall->category_id : '';
                                       if($lang=='es')
                                              {$category_name=$vall->category_es_name;}
                                          else{$category_name=$vall->category_en_name;}

                                      $data1['category_name'] = isset($category_name) && !empty($category_name) ? $category_name : '';
                                      //End category

                                      // service
                                      $data1['service_id'] = isset($vall->service_id) && !empty($vall->service_id) ? $vall->service_id : '';
                                       if($lang=='es')
                                              {$service_name=$vall->service_es_name;}
                                          else{$service_name=$vall->service_en_name;}

                                      $data1['service_name'] = isset($service_name) && !empty($service_name) ? $service_name : '';
                                      $data1['service_image'] = url('/img/'.$vall->image);
                                      //End Service

                                      // subService
                                      $data1['subservice_id'] = isset($vall->sub_service_id) && !empty($vall->sub_service_id) ? $vall->sub_service_id : '';
                                       if($lang=='es')
                                              {$subservice_name=$vall->sub_service_es_name;}
                                          else{$subservice_name=$vall->sub_service_en_name;}

                                      $data1['subservice_name'] = isset($subservice_name) && !empty($subservice_name) ? $subservice_name : '';
                                      //End subService

                                      // child subservice
                                      $data1['child_sub_service_id'] = isset($vall->child_sub_service_id) && !empty($vall->child_sub_service_id) ? $vall->child_sub_service_id : ''; 
                                       if($lang=='es')
                                              {$child_subservice_name=$vall->child_subservice_es_name;}
                                          else{$child_subservice_name=$vall->child_subservice_en_name;}
                                      $data1['child_sub_service_name'] = isset($child_subservice_name) && !empty($child_subservice_name) ? $child_subservice_name : '';
                                      //End child subService

                                      $data1['location'] = $vall->location;
                                      $data1['username'] = $vall->username;
                                      $data1['email'] = $vall->email;
                                      $data1['status'] = $vall->status;
                                      $data1['email_verify'] = $vall->email_verify;
                                      $data1['created_at'] = $vall->created_at;

                                      
                                      $servicesRequestedQues = DB::table('service_request_questions')
                                      ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                                       ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                                      ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option','questions.question_type')
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
                                         $data2['question_type'] = $que->question_type;

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
                                return view('frontend.contractor.opportunity')->withUser($userEntity)->withData($allData);
                            }
                            else
                            {
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Opportunities List Not Found.!'));   
                            }

                           }
                           else
                           {
                            return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Opportunities Not Found.!'));
                           }

                        }
                        else
                        {
                           
                           return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Please update your profile for your offerd services.'));  
                        }
                        
                    }
                    else
                    {
                        return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid user.!'));  
                    }

                } 
                else 
                {
                    return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid parameter.!'));  
                }

		
    	}



    	function opportunityDetails($opportunity_id)
    	{

    		$opportId = Crypt::decrypt($opportunity_id);
    		$allData = array();
    		$userid= auth()->user()->id;
			$lang = 'en' ;
		    //echo $service_id; die;
			 $allOpprtunities = DB::table('assign_service_request')
             ->join('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')

             ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
             ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
             ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
             ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
             ->leftjoin('cities', 'service_request.city_id', '=', 'cities.id')

            ->select('assign_service_request.id','assign_service_request.service_request_id','assign_service_request.user_id','assign_service_request.request_status','service_request.service_id','service_request.category_id','service_request.sub_service_id','service_request.child_sub_service_id','service_request.location','service_request.username','service_request.email','service_request.status','service_request.email_verify','service_request.created_at','category.en_name AS category_en_name', 'category.es_name AS category_es_name','services.en_name AS service_en_name', 'services.es_name AS service_es_name', 'services.image','sub_services.en_name AS sub_service_en_name', 'sub_services.es_name AS sub_service_es_name','child_sub_services.en_name AS child_subservice_en_name', 'child_sub_services.es_name AS child_subservice_es_name','cities.name AS city_name')
             ->whereRaw("(assign_service_request.user_id = '".$userid."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status IS null)")
             ->where('service_request.status','0')
             ->where('assign_service_request.id',$opportId)
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

                    $data1['city_name'] = isset($vall->city_name) && !empty($vall->city_name) ? $vall->city_name : '';

                    // category
                    $data1['category_id'] = isset($vall->category_id) && !empty($vall->category_id) ? $vall->category_id : '';
                     if($lang=='es')
                            {$category_name=$vall->category_es_name;}
                        else{$category_name=$vall->category_en_name;}

                    $data1['category_name'] = isset($category_name) && !empty($category_name) ? $category_name : '';
                    //End category

                    // service
                    $data1['service_id'] = isset($vall->service_id) && !empty($vall->service_id) ? $vall->service_id : '';
                     if($lang=='es')
                            {$service_name=$vall->service_es_name;}
                        else{$service_name=$vall->service_en_name;}

                    $data1['service_name'] = isset($service_name) && !empty($service_name) ? $service_name : '';
                    $data1['service_image'] = url('/img/'.$vall->image);
                    //End Service

                    // subService
                    $data1['subservice_id'] = isset($vall->sub_service_id) && !empty($vall->sub_service_id) ? $vall->sub_service_id : '';
                     if($lang=='es')
                            {$subservice_name=$vall->sub_service_es_name;}
                        else{$subservice_name=$vall->sub_service_en_name;}

                    $data1['subservice_name'] = isset($subservice_name) && !empty($subservice_name) ? $subservice_name : '';
                    //End subService

                    // child subservice
                    $data1['child_sub_service_id'] = isset($vall->child_sub_service_id) && !empty($vall->child_sub_service_id) ? $vall->child_sub_service_id : ''; 
                     if($lang=='es')
                            {$child_subservice_name=$vall->child_subservice_es_name;}
                        else{$child_subservice_name=$vall->child_subservice_en_name;}
                    $data1['child_sub_service_name'] = isset($child_subservice_name) && !empty($child_subservice_name) ? $child_subservice_name : '';
                    //End child subService

                    $data1['location'] = $vall->location;
                    $data1['username'] = $vall->username;
                    $data1['email'] = $vall->email;
                    $data1['status'] = $vall->status;
                    $data1['email_verify'] = $vall->email_verify;
                    $data1['created_at'] = $vall->created_at;

                    
                    $servicesRequestedQues = DB::table('service_request_questions')
                    ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                     ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                    ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option','questions.question_type')
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
                       $data2['question_type'] = $que->question_type;

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
	                    return view('frontend.contractor.opportunity_details')->withData($data1); 

	                }
	                else
	                {
	                    return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Opportunities List Not Found.!'));   
	                }

               }else
               {
               	return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Opportunities List Not Found.!'));
               }


    	}

    	 /* --------------------Opportunity Buy Start-------------------- */


        public function buyOpportunity($opportunity_id)
        {

            $allData=array();
            $userid = auth()->user()->id;
            $opportunity_id = Crypt::decrypt($opportunity_id);
            $tranx_id = '78545878';
            $tranx_status = '1' ;
            $currency = 'USD' ;
            $amount = '50' ;
            $lang = 'en' ;
     
 
  
                 if(!empty($userid) && !empty($opportunity_id)) 
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

                                        return redirect()->route('frontend.contractor.opportunities')->withFlashDanger(__('Requested opportunity is already Assigned another professionals.'));   

                                    }else
                                    {

                                        $chkUserRecivedOpprtOrNot = DB::table('assign_service_request')
                                        ->whereRaw("(id = '".$opportunity_id."' AND deleted_at IS null AND user_id = '".$userid."')")
                                        ->first();

                                        if(!empty($chkUserRecivedOpprtOrNot))
                                         {  

                                            if($chkUserRecivedOpprtOrNot->request_status=='buy')
                                            {

                                            
                                              return redirect()->route('frontend.contractor.opportunities')->withFlashDanger(__('Opportunity Already Accepted.!')); 

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

                                                     return redirect()->route('frontend.contractor.jobs')->withFlashSuccess(__('Opportunity Buy Successfully.!')); 

                                                 }else
                                                 {
                                                    return redirect()->route('frontend.contractor.opportunities')->withFlashDanger(__('This Opportunity Alfready Taken By Another Three professionals OR Company.!'));  
                                                 }
                                            }

                                          //here    

                                       }else
                                       {
                                                 return redirect()->route('frontend.contractor.opportunities')->withFlashDanger(__("You don't have this opportunity.Please update your profile offerd services to get new Opportunities.")); 
                                       }

                                    }

                                }else
                                {
                                    return redirect()->route('frontend.contractor.opportunities')->withFlashDanger(__("Requested opportunity id not found.!")); 
                                }

                            }
                            else
                            {
                                return redirect()->route('frontend.contractor.opportunities')->withFlashDanger(__("Invalid user.!")); 
                            }

                    }
                    else 
                    {
                    	return redirect()->route('frontend.contractor.opportunities')->withFlashDanger(__("Invalid parameter.!"));

                    }

        }

        /* --------------------Opportunity Buy END-------------------- */




         /* --------------------Opportunity IGNORE Api Start-------------------- */


        public function ignoreOpportunity($opportunity_id)
        {

            $allData=array();
            $userid = auth()->user()->id;
            $opportunity_id = Crypt::decrypt($opportunity_id);
            $lang = 'en' ;
  
                 if(!empty($userid) && !empty($opportunity_id))
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
                                         return redirect()->route('frontend.contractor.opportunity')->withFlashDanger(__('Requested opportunity is already Assigned another professionals.'));

                                    }else
                                    {

                                        $chkUserRecivedOpprtOrNot = DB::table('assign_service_request')
                                        ->whereRaw("(service_request_id = '".$opportunity_id."' AND deleted_at IS null AND user_id = '".$userid."')")
                                        ->first();

                                        if(!empty($chkUserRecivedOpprtOrNot))
                                         {   

                                            if($chkUserRecivedOpprtOrNot->request_status=='buy')
                                            {

                                              return redirect()->route('frontend.contractor.opportunity')->withFlashDanger(__('Opportunity Already Accepted. Now you can not Ignore.!'));

                                            }
                                            else if($chkUserRecivedOpprtOrNot->request_status=='ignore')
                                            {
                                           
                                               return redirect()->route('frontend.contractor.opportunity')->withFlashDanger(__('Opportunity Already Ignored.!'));
                                            }
                                            else
                                            {
                                                $update_Arr['request_status'] = 'ignore';    
                                                 $update_Arr['updated_at'] = Carbon::now();  
                                                
                                              DB::table('assign_service_request')
                                                ->whereRaw("(service_request_id = '".$opportunity_id."' AND user_id = '".$userid."')")->update($update_Arr);

                                               return redirect()->route('frontend.contractor.my-profile')->withFlashSuccess(__('Opportunity Ignore Successfully.!'));
                                            }

                                       }else
                                       {
                                                return redirect()->route('frontend.contractor.opportunity')->withFlashSuccess(__("You don't have this opportunity.Please update your profile offerd services to get new Opportunities."));  
                                       }

                                    }

                                }else
                                {
                                  
                                    return redirect()->route('frontend.contractor.opportunity')->withFlashSuccess(__('Requested opportunity id not found.!'));  
                                }


                            }
                            else
                            {
                             
                                return redirect()->route('frontend.contractor.opportunity')->withFlashSuccess(__('Invalid user.!')); 
                            }


                        }
                    else 
                    {
                        return redirect()->route('frontend.contractor.opportunity')->withFlashSuccess(__('Invalid parameter.!')); 
                    }


        }

        /* --------------------Ignore Opportunity Api END-------------------- */



/*****************************************************************************************/





}