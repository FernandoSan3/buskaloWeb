<?php

namespace App\Http\Controllers\Frontend\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
use Illuminate\Support\Facades\File;
date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
/**
 * Class JobsController.
 */
class CompanyJobsController extends Controller
{
      public function index()
        {
            $allData=array();
            $userid= auth()->user()->id;
	       $lang = app()->getLocale() ;

              if(!empty($userid))
              {

                $userEntity = DB::table('users')
                ->whereRaw("(active=1)")
                ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                ->first();
               // echo "<pre>";print_r($userEntity);die;

              if(!empty($userEntity))
              {
                   $servicesbuy = DB::table('assign_service_request')
                  ->leftjoin('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')
                  ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
                  ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
                  ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
                  ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
                  ->leftjoin('cities', 'service_request.city_id', '=', 'cities.id')
                  ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','assign_service_request.request_status','service_request.email_verify','service_request.created_at','assign_service_request.tranx_status','assign_service_request.tranx_id','service_request.mobile_number','service_request.email','service_request.user_id','service_request.latitude','service_request.longitude','service_request.assigned_user_id','category.en_name AS category_en_name', 'category.es_name AS category_es_name','services.en_name AS service_en_name', 'services.es_name AS service_es_name', 'services.image','sub_services.en_name AS sub_service_en_name', 'sub_services.es_name AS sub_service_es_name','child_sub_services.en_name AS child_subservice_en_name', 'child_sub_services.es_name AS child_subservice_es_name','cities.name AS city_name','assign_service_request.job_status','assign_service_request.service_request_id','assign_service_request.user_id as proid','assign_service_request.id as asignid','assign_service_request.updated_at')
                  ->where('assign_service_request.user_id',$userid)
                 // ->where('assign_service_request.tranx_status','1')
                  ->where('assign_service_request.request_status','buy')
                  ->whereRaw("(service_request.deleted_at IS null )")
                  ->orderBy('service_request.id', 'DESC')
                  //->limit(5)
                  ->get()->toArray();

                   if(!empty($servicesbuy))
                     {

                      $data1=array();
                      foreach ($servicesbuy as $key => $vall)
                      {

                          $data1['id'] = $vall->id;
                          $data1['service_request_id'] = $vall->service_request_id;
                          $data1['user_id'] = $vall->user_id;
                          $data1['asignid'] = $vall->asignid;


                          // if(!empty($vall->assigned_user_id) && $vall->assigned_user_id!==NULL)
                          // {
                          //     $userSideAccept='accepted';
                          // }else
                          // {
                          //     $userSideAccept='not approved by user';
                          // }
                              if($vall->job_status == 1){
                                //new
                                $userSideAccept='Nueva';

                            }elseif($vall->job_status == 2){
                                //pending
                                $userSideAccept='Pendiente';

                            }
                            elseif($vall->job_status == 3){
                                //accepted
                                $userSideAccept='Aceptado';

                            }elseif($vall->job_status == 4){
                                //rejected or not accepted
                                $userSideAccept='No Aceptado';

                            }elseif($vall->job_status == 5){
                                //service performed
                                $userSideAccept='Service realizado';
                            }
                            else{

                                $userSideAccept='No Aceptado';
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
                            $data1['mobile_number'] = isset($vall->mobile_number)?$vall->mobile_number:'';
                            $data1['email'] = $vall->email;
                            $data1['request_status'] = $vall->request_status;
                            $data1['created_at'] = $vall->created_at;
                            $data1['updated_at'] = $vall->updated_at;


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

     					        if(!empty($allData))
                        {
                           return view('frontend.company.jobs')->withUser($userEntity)->withData($allData); 
                        }
                        else
                        {
                           return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.job_list_not_found'));
                        }

                      }

                     else
                       {
                          return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.job_list_not_found'));
                       }

                      }
                      else
                      {
                          return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.lnvalid_user'));
                      }
              }else
              {
                  return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.invalid_parameter'));
              }

        }
        /* --------------------Job Detail Api Start-------------------- */

        public function jobDetail($job_id)
        {

		       	$job_id = Crypt::decrypt($job_id);
		       	$allData = array();
		      	$userid= auth()->user()->id;
			      $lang = 'en' ;

              if(!empty($userid) && !empty($job_id)){
							 $userEntity = DB::table('users')
                  ->whereRaw("(active=1)")
                  ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
                  ->first();

                  if(!empty($userEntity)){
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
                      //->where('assign_service_request.tranx_status','1')
                      //->where('assign_service_request.request_status','buy')
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

                                 return view('frontend.company.job_details')->withData($data1);

                                }

	                            else
	                            {
	                                return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.job.company.list_not_found'));
	                            }

                            }
                            else
                            {
                               return redirect()->route('frontend.contractor.co-dashoard')->withFlashDanger(__('alerts.frontend.company.job.invalid_user'));
                            }
                    }else
                    {
                      return redirect()->route('frontend.contractor.co-dashoard')->withFlashDanger(__('alerts.frontend.company.job.invalid_parameter'));
                    }

        }

         /* --------------------Job Detail End-------------------- */


    public function opportunities()
    {

      	$allData=array();
		$userid= auth()->user()->id;
		$lang = app()->getLocale();
 	 	$olddate=date('Y-m-d H:i:s', strtotime('-8 days'));
      	if(!empty($userid))
      	{
          	$userEntity = DB::table('users')
          		->whereRaw("(active=1)")
          		->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
          		->first();

             	// print_r($userEntity);die;

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
                      	->select('assign_service_request.id','assign_service_request.service_request_id','assign_service_request.user_id','assign_service_request.request_status','service_request.service_id','service_request.category_id','service_request.sub_service_id','service_request.child_sub_service_id','service_request.location','service_request.username','service_request.email','service_request.status','service_request.email_verify','service_request.created_at','category.en_name AS category_en_name', 'category.es_name AS category_es_name','services.en_name AS service_en_name', 'services.es_name AS service_es_name', 'services.image','sub_services.en_name AS sub_service_en_name', 'sub_services.es_name AS sub_service_es_name','child_sub_services.en_name AS child_subservice_en_name', 'child_sub_services.es_name AS child_subservice_es_name','cities.name AS city_name','assign_service_request.credit','assign_service_request.request_not_now')
                       	->whereRaw("(assign_service_request.user_id = '".$userid."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status IS null AND service_request.deleted_at IS null)")
                      	->orderBy('assign_service_request.created_at','DESC')
                      	->where('assign_service_request.created_at', '>',$olddate)
                      	->where('service_request.status','0')
                      	->groupBy('assign_service_request.service_request_id')
                      	->get();
                 	if(!empty($allOpprtunities))
                 	{
                        $data1=array();
                        foreach ($allOpprtunities as $key => $vall)
                        {
                            $maxcount=DB::table('assign_service_request')->where('service_request_id',$vall->service_request_id)->where('request_status','buy')->count();
                            if($maxcount<3)
                            {
                                $data1['id'] = $vall->id;
                                $data1['user_id'] = $vall->user_id;
                                $data1['service_request_id'] = $vall->service_request_id;
                                $data1['request_status'] = isset($vall->request_status) && !empty($vall->request_status) ? $vall->request_status : '';
                                $data1['city_name'] = isset($vall->city_name) && !empty($vall->city_name) ? $vall->city_name : '';
    	                        $data1['category_id'] = isset($vall->category_id) && !empty($vall->category_id) ? $vall->category_id : '';
                               		if($lang=='es')
                                  	{
                                      	$category_name=$vall->category_es_name;
                                  	}
                                  	else
                              		{
                              			$category_name=$vall->category_en_name;
                              		}
                              	$data1['category_name'] = isset($category_name) && !empty($category_name) ? $category_name : '';
    	                          //End category
                                $data1['service_id'] = $vall->service_id;
                                 if($lang=='es')
                                	{
                            			$service_name=$vall->service_es_name;
                            		}
                                    else
                                	{
                                		$service_name=$vall->service_en_name;
                                	}
                                $data1['service_name'] = $service_name;
                                $data1['service_image'] = url('/img/'.$vall->image);
                                  // subService
                              	$data1['subservice_id'] = isset($vall->sub_service_id) && !empty($vall->sub_service_id) ? $vall->sub_service_id : '';
                                   if($lang=='es')
                                  	{
                                  		$subservice_name=$vall->sub_service_es_name;
                                  	}
                                  	else
                              		{
                              			$subservice_name=$vall->sub_service_en_name;
                              		}
                              	$data1['subservice_name'] = isset($subservice_name) && !empty($subservice_name) ? $subservice_name : '';
                                  //End subService
                                  // child subservice
                              	$data1['child_sub_service_id'] = isset($vall->child_sub_service_id) && !empty($vall->child_sub_service_id) ? $vall->child_sub_service_id : ''; 
                                   if($lang=='es')
                                  	{
                                  		$child_subservice_name=$vall->child_subservice_es_name;
                                  	}
                                  	else
                              		{
                              			$child_subservice_name=$vall->child_subservice_en_name;
                              		}
                              	$data1['child_sub_service_name'] = isset($child_subservice_name) && !empty($child_subservice_name) ? $child_subservice_name : '';
                                  //End child subService
                                $data1['request_not_now'] = $vall->request_not_now;
                                $data1['location'] = $vall->location;
                                $data1['username'] = $vall->username;
                                $data1['status'] = $vall->status;
                                $data1['email_verify'] = $vall->email_verify;
                             	$data1['credit'] = $vall->credit;
                                $data1['created_at'] = $vall->created_at;


                                $servicesRequestedQues = DB::table('service_request_questions')
                                ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                                 ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                                ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option','questions.question_type','service_request_questions.fileName','service_request_questions.quantity','service_request_questions.date_time')
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
                                   if($que->question_type=='text'|| $que->question_type=='quantity' &&  $que->option_id==0)
                                   {
                                      $data2['option_id'] = $que->quantity;
                                   }
                                   if($que->question_type=='date'|| $que->question_type=='date_time' &&  $que->option_id==0)
                                   {
                                      $data2['option_id'] = $que->date_time;
                                   }
                                   if($que->question_type=='file' &&  $que->option_id==0)
                                   {
                                      $data2['option_id'] = $que->fileName;
                                   }

                                    if($lang=='es')
                                        {$option=$que->es_option;}
                                    else{$option=$que->en_option;}

                                   $data2['option'] = $option;
                                   $data2['question_type'] = $que->question_type;

                                    array_push($options, $data2);
                                }
                                $data1['question_options']=$options ;
                                array_push($allData, $data1);
                            }
                        }
                        if(!empty($data1))
                        {
                             return view('frontend.company.opportunity')->withUser($userEntity)->withData($allData);
                        }
                        else
                        {
                            return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.opportunities_list_not_found'));
                        }
                 	}
                 	else
                 	{
                      return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.opportunities_not_found'));
                 	}
              	}
              	else
              	{

                 	return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.please_update_your_profile'));
              	}
          	}
          	else
          	{
                  return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.invalid_user'));
          	}
      	}
      	else
      	{
              return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.invalid_parameter'));
      	}
	}



    	function opportunityDetails($opportunity_id)
    	{

    		$opportId = Crypt::decrypt($opportunity_id);
    		$allData = array();
    		$userid= auth()->user()->id;
			$lang = app()->getLocale();
		    //echo $service_id; die;
			 $allOpprtunities = DB::table('assign_service_request')
             ->join('service_request', 'assign_service_request.service_request_id', '=', 'service_request.id')

             ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
             ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
             ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
             ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
             ->leftjoin('cities', 'service_request.city_id', '=', 'cities.id')

            ->select('assign_service_request.id','assign_service_request.service_request_id','assign_service_request.user_id','assign_service_request.request_status','service_request.service_id','service_request.category_id','service_request.sub_service_id','service_request.child_sub_service_id','service_request.location','service_request.username','service_request.email','service_request.status','service_request.email_verify','service_request.created_at','category.en_name AS category_en_name', 'category.es_name AS category_es_name','services.en_name AS service_en_name', 'services.es_name AS service_es_name', 'services.image','sub_services.en_name AS sub_service_en_name', 'sub_services.es_name AS sub_service_es_name','child_sub_services.en_name AS child_subservice_en_name', 'child_sub_services.es_name AS child_subservice_es_name','cities.name AS city_name')
             ->whereRaw("(assign_service_request.user_id = '".$userid."' AND assign_service_request.deleted_at IS null AND assign_service_request.request_status IS null AND service_request.deleted_at IS null)")
             ->where('service_request.status','0')
             ->where('assign_service_request.id',$opportId)
             ->groupBy('assign_service_request.service_request_id')
             ->get();
        //echo "<pre>";print_r($allOpprtunities);die;

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
	                    return view('frontend.company.company_profile.opportunity_details')->withData($data1);

	                }
	                else
	                {
	                    return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.opportunities_list_not_found'));
	                }

               }else
               {
               	return redirect()->route('frontend.company.company_profile.my-profile')->withFlashDanger(__('alerts.frontend.company.job.opportunities_list_not_found'));
               }


    	}

    	 /* --------------------Opportunity Buy Start-------------------- */


        public function buyOpportunity($opportunity_id)
        {

            $allData=array();
            $userid = auth()->user()->id;
            $opportunity_id = Crypt::decrypt($opportunity_id);
           // $tranx_id = '78545878';
           // $tranx_status = '1' ;
           // $currency = 'USD' ;
            //$amount = '50' ;
            $lang = app()->getLocale();
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

                            return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.company.job.requested_opportunity'));
                        }else
                        {
                            $chkUserRecivedOpprtOrNot = DB::table('assign_service_request')
                            ->whereRaw("(id = '".$opportunity_id."' AND deleted_at IS null AND user_id = '".$userid."')")
                            ->first();

                            if(!empty($chkUserRecivedOpprtOrNot))
                            {

                                if($chkUserRecivedOpprtOrNot->request_status=='buy')
                                {
                                    return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.company.job.opportunity_already_accepted'));

                                }
                                else
                                {
                                    if($userEntity->pro_credit>=$opportunity->credit)
                                    {
                                       $chkThreeUserLimit = DB::table('assign_service_request')
                                                      ->whereRaw("(service_request_id = '".$chkUserRecivedOpprtOrNot->service_request_id."' AND request_status = '".'buy'."')")
                                                      ->count();

                                        if($chkThreeUserLimit < 3)
                                        {
                                            /*Credites Buy for company*/
                                            $leftcredit= $userEntity->pro_credit-$opportunity->credit;

                                            DB::table('users')
                                            ->where('id',$userid)
                                            ->update(['pro_credit'=>$leftcredit]);
                                            $admincrdit= DB::table('users')
                                                ->where('id',1)->first();

                                            DB::table('users')
                                            ->where('id',1)
                                            ->update(['pro_credit'=>$admincrdit->pro_credit+$opportunity->credit]);
                                            /*Credites Buy for company */
                                        
                                            $chkThreeUserLimit = DB::table('assign_service_request')
                                            ->whereRaw("(service_request_id = '".$chkUserRecivedOpprtOrNot->service_request_id."' AND request_status = '".'buy'."')")->count();


                                             $serviceId=DB::table('service_request')->where('id',$chkUserRecivedOpprtOrNot->service_request_id)->first();
                                            if(!empty($serviceId))
                                            {   

                                                $userToken = DB::table('users')
                                                ->leftjoin('user_devices','user_devices.user_id','=','users.id')
                                                ->where('users.id',$serviceId->user_id)
                                                ->select('user_devices.*', 'users.*')
                                                ->first();
                                                if(!empty($userToken))
                                                {

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
                                                    $email=$userToken->email;
                                                    $userToken->logo=url('img/logo/logo-svg.png');
                                                    $userToken->footer_logo=url('img/logo/footer-logo.png');
                                                    $userToken->clientName=$userEntity->username;
                                                    $userToken->addressdata=$userEntity->address;
                                                    $userToken->mobile_numberdata=$userEntity->mobile_number;
                                                    $userToken->link=url('/company_profile/my-profile');
                                                   
                                                    Mail::send('frontend.mail.service_buy', ['userToken'=>$userToken], function($message) use($email) {
                                                    $message->to($email)->subject
                                                            ('Service buy');
                                                    $message->from(env('MAIL_FROM'));
                                                    });

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
                                                }
                                            }else
                                            {
                                                $resultArray['status']='0';
                                                $resultArray['message']=trans('Service Id not found.');
                                                echo json_encode($resultArray); exit;
                                            }
                                          //Check User Balance And Deduct Accordingly Request Amount
                                          // And Admin Commision

                                          //Amount Deduct Bonus Table

                                          //End
                                          $update_Arr['request_status'] = 'buy';
                                           $update_Arr['amount'] = $opportunity->credit;
                                           $update_Arr['assign_date'] = Carbon::now();
                                          $update_Arr['updated_at'] = Carbon::now();

                                          DB::table('assign_service_request')
                                          ->whereRaw("(id = '".$opportunity_id."' AND user_id = '".$userid."')")->update($update_Arr);

                                           return redirect()->route('frontend.company.company_profile.jobs')->withFlashSuccess(__('alerts.frontend.company.job.opportunity_buy_successfully'));
                                        }else
                                        {
                                            return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.company.job.taken_by_another'));
                                        }
                                      }
                                    else
                                    {
                                        return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.company.job.credits_not_sufficient'));
                                    }
                                }
                                //here

                            }else
                            {
                                return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.company.job.dont_have_this_opportunity'));
                            }
                        }
                    }else
                    {
                        return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.company.job.requested_opportunity_id_not_found'));
                    }
                }
                else
                {
                    return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.company.job.invalid_user'));
                }
            }
            else
            {
              	return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.company.job.invalid_parameter'));
            }
        }

        /* --------------------Opportunity Buy END-------------------- */

/*****************************************************************************************/


    public function ignoreOpportunity($opportunity_id)
    {

        $allData=array();
        $userid = auth()->user()->id;
        $opportunity_id = Crypt::decrypt($opportunity_id);
        $lang = app()->getLocale();

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
                                     return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.constractor.job.requested_opportunity')); 

                                }else
                                {

                                    $chkUserRecivedOpprtOrNot = DB::table('assign_service_request')
                                    ->whereRaw("(service_request_id = '".$opportunity_id."' AND deleted_at IS null AND user_id = '".$userid."')")
                                    ->first();

                                    if(!empty($chkUserRecivedOpprtOrNot))
                                     {   

                                        if($chkUserRecivedOpprtOrNot->request_status=='buy')
                                        {

                                          return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.constractor.job.opportunity_already_accepted')); 

                                        }
                                        else if($chkUserRecivedOpprtOrNot->request_status=='ignore')
                                        {
                                       
                                           return redirect()->route('frontend.company.company_profile.opportunities')->withFlashDanger(__('alerts.frontend.constractor.job.opportunity_already_ignored')); 
                                        }
                                        else
                                        {   $update_Arr['request_status'] = NULL; 
                                            $update_Arr['job_status'] =2;
                                            $update_Arr['rejected_by'] ='pro';   
                                            $update_Arr['request_not_now'] =1;   
                                            $update_Arr['updated_at'] = Carbon::now();
                                            // $update_Arr['request_status'] = 'ignore';    
                                            //  $update_Arr['updated_at'] = Carbon::now();  
                                            
                                          DB::table('assign_service_request')
                                            ->whereRaw("(service_request_id = '".$opportunity_id."' AND user_id = '".$userid."')")->update($update_Arr);

                                           return redirect()->route('frontend.company.company_profile.opportunities')->withFlashSuccess(__('alerts.frontend.constractor.job.opportunity_already_ignored')); 
                                        }

                                   }else
                                   {
                                            return redirect()->route('frontend.company.company_profile.opportunities')->withFlashSuccess(__('alerts.frontend.constractor.job.services_offered_to_obtain'));   
                                   }

                                }

                            }else
                            {
                              
                                return redirect()->route('frontend.company.company_profile.opportunities')->withFlashSuccess(__('alerts.frontend.constractor.job.requested_opportunity_id_not_found'));     
                            }


                        }
                        else
                        {
                         
                            return redirect()->route('frontend.company.company_profile.opportunities')->withFlashSuccess(__('alerts.frontend.constractor.job.invalid_user'));
                        }


                    }
                else 
                {
                    return redirect()->route('frontend.contractor.opportunities')->withFlashSuccess(__('alerts.frontend.constractor.job.invalid_parameter')); 
                }
    }
}








