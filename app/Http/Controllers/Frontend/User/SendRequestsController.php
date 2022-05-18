<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
use Hash, File;

/**
 * Class SendRequestsController.
 */
class SendRequestsController extends Controller
{
   
    /**
     * @param UpdateProfileRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */


 /*
  * ************ GET REQUEST LIST OF USER *********************
  *
  * GET REQUEST LIST API START HERE
  */

  public function projects()
  { 


    $userid= auth()->user()->id;

    $userEntity = DB::table('users')
    ->whereRaw("(active=1)")
    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
    ->first();

    $allData=array();  

    
      $allData=array();
      $lang='es';
      $servicesRequested = DB::table('service_request')
      ->leftjoin('category', 'service_request.category_id', '=', 'category.id')
      ->leftjoin('services', 'service_request.service_id', '=', 'services.id')
      ->leftjoin('sub_services', 'service_request.sub_service_id', '=', 'sub_services.id')
      ->leftjoin('child_sub_services', 'service_request.child_sub_service_id', '=', 'child_sub_services.id')
      ->leftjoin('cities', 'service_request.city_id', '=', 'cities.id')
      ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.email_verify','service_request.created_at','service_request.mobile_number','service_request.email','service_request.user_id','service_request.latitude','service_request.longitude','service_request.assigned_user_id','category.en_name AS category_en_name', 'category.es_name AS category_es_name','services.en_name AS service_en_name', 'services.es_name AS service_es_name', 'services.image','sub_services.en_name AS sub_service_en_name', 'sub_services.es_name AS sub_service_es_name','child_sub_services.en_name AS child_subservice_en_name', 'child_sub_services.es_name AS child_subservice_es_name','cities.name AS city_name')
      ->where('service_request.user_id',$userid)->whereRaw("(service_request.deleted_at IS null )")->orderBy('service_request.id', 'DESC')->get()->toArray();


       if(!empty($servicesRequested))
       {

        $data1=array();
            foreach ($servicesRequested as $key => $vall) 
            {

                $data1['id'] = $vall->id;

                $data1['city_name'] = isset($vall->city_name) && !empty($vall->city_name) ? $vall->city_name : '';

                  // category
                  $data1['category_id'] = isset($vall->category_id) && !empty($vall->category_id) ? $vall->category_id : '';
                   
                  $data1['category_name'] = isset($vall->category_es_name) && !empty($vall->category_es_name) ? $vall->category_es_name : '';
                  //End category

                  // service
                  $data1['service_id'] = isset($vall->service_id) && !empty($vall->service_id) ? $vall->service_id : '';
                  
                  $data1['service_name'] = isset($vall->service_es_name) && !empty($vall->service_es_name) ? $vall->service_es_name : '';
                  $data1['service_image'] = url('/img/'.$vall->image);
                  //End Service

                  // subService
                  $data1['subservice_id'] = isset($vall->sub_service_id) && !empty($vall->sub_service_id) ? $vall->sub_service_id : '';
                   
                  $data1['subservice_name'] = isset($vall->sub_service_es_name) && !empty($vall->sub_service_es_name) ? $vall->sub_service_es_name : '';
                  //End subService

                  // child subservice
                  $data1['child_sub_service_id'] = isset($vall->child_sub_service_id) && !empty($vall->child_sub_service_id) ? $vall->child_sub_service_id : ''; 
                  
                  $data1['child_sub_service_name'] = isset($vall->child_subservice_es_name) && !empty($vall->child_subservice_es_name) ? $vall->child_subservice_es_name : '';
                  //End child subService


                      $data1['location'] = $vall->location;
                      $data1['user_id'] = $vall->user_id;
                      $data1['username'] = $vall->username;
                      $data1['mobile_number'] = $vall->mobile_number;
                      $data1['email'] = $vall->email;
                      $data1['email_verify'] = $vall->email_verify;
                      $data1['created_at'] = $vall->created_at;


                $servicesRequestedQues = DB::table('service_request_questions')
                ->leftjoin('questions', 'service_request_questions.question_id', '=', 'questions.id')
                 ->leftjoin('question_options', 'service_request_questions.option_id', '=', 'question_options.id')
                ->select('service_request_questions.id','service_request_questions.service_request_id','service_request_questions.question_id','service_request_questions.option_id','questions.en_title','questions.es_title','question_options.en_option','question_options.es_option','questions.question_type','service_request_questions.fileName','service_request_questions.quantity','service_request_questions.date_time')
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
                   $data2['question_type'] = $que->question_type;
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

                    $proprofle=DB::table('assign_service_request')
                    ->leftjoin('users','users.id','=','assign_service_request.user_id')
                    ->select('users.username as prousername','users.avatar_location','users.user_group_id','users.id')
                    ->where('service_request_id',$vall->id)->where('request_status','buy')->get();
                    if(!empty($proprofle))
                    {   $prodata2=array();
                        foreach ($proprofle as $key => $value)
                        {       
                            $prodata2[$key]['prousername']=$value->prousername;
                             $prodata2[$key]['id']=$value->id;
                            if($value->user_group_id==3)
                            {
                                if(file_exists(public_path('img/contractor/profile/'.$value->avatar_location)))
                                {
                                    $prodata2[$key]['image']=url('img/contractor/profile/'.$value->avatar_location);
                                }
                                else
                                {
                                    $prodata2[$key]['image']=url('img/noimage.png');
                                }
                            }
                            if($value->user_group_id==4)
                            {
                                if(file_exists(public_path('img/company/profile/'.$value->avatar_location)))
                                {
                                    $prodata2[$key]['image']=url('img/company/profile/'.$value->avatar_location);
                                }
                                else
                                {
                                    $prodata2[$key]['image']=url('img/noimage.png');
                                }
                            }
                        }
                    }

                    //$prodata2['proprofile'] = $prodata;
                   $data2['option'] = $option;

                    array_push($options, $data2);
                }


                $data1['question_options']=$options ;
                $data1['proinfo']=$prodata2 ;
                array_push($allData, $data1);

            }

      }

  //echo "<pre>"; print_r($allData);die;

    if(!empty($allData))
    {  
      return view('frontend.user.projects')->withData($allData);
    }
    else
    {
     return redirect()->route('frontend.user.dashboard');
    }
                         
  }



  public function index()
  { 


    $userid= auth()->user()->id;

    $userEntity = DB::table('users')
    ->whereRaw("(active=1)")
    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
    ->first();

    $allData=array();  

     $servicesRequested = DB::table('service_request as SER')
      ->join('category AS C1','SER.category_id','C1.id')
      ->join('services as S1','SER.service_id','S1.id') 
      ->join('sub_services as S2','SER.sub_service_id','S2.id')
      ->leftjoin('child_sub_services as S3','SER.child_sub_service_id','S3.id')   
      ->select('SER.id','SER.created_at','C1.es_name as category_name','S1.es_name as service_name')    
      ->where('SER.user_id',$userid)->whereRaw("(SER.deleted_at IS null )")->get();  

   

    //echo "<pre>"; print_r($servicesRequested); die;    

    if(!empty($servicesRequested))
    {  
      return view('frontend.user.all_requests')->withData($servicesRequested);
    }
    else
    {
     return redirect()->route('frontend.user.dashboard');
    }
                         
  }


  /*
  * ************ GET REQUEST LIST OF USER *********************
  *
  * GET REQUEST LIST OF USER API END HERE
  */

  public function allPendingRequests()
  {

    $userid= auth()->user()->id;

    $userEntity = DB::table('users')
    ->whereRaw("(active=1)")
    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
    ->first();


    $allData=array();   

    $servicesRequested = DB::table('service_request')
    ->join('category AS C1','service_request.category_id','C1.id')
    ->join('services as S1','service_request.service_id','S1.id')
    ->join('sub_services as S2','service_request.sub_service_id','S2.id')
    ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')
    ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','C1.es_name as category_name','S1.es_name as service_name','S2.es_name as sub_service_name','S3.es_name as child_subservice_name')    
    ->where('service_request.status','0')->where('service_request.user_id',$userid)->whereRaw("(service_request.deleted_at IS null )")->get();     

    if(!empty($servicesRequested))
    {  
      return view('frontend.user.all_pending_requests')->withData($servicesRequested);
    }
    else
    {
     return redirect()->route('frontend.user.dashboard');
    }
                         
  }

  public function allAcceptedRequests()
  {

    $userid= auth()->user()->id;

    $userEntity = DB::table('users')
    ->whereRaw("(active=1)")
    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
    ->first();

    $allData=array();   

    $servicesRequested = DB::table('service_request')
    ->join('category AS C1','service_request.category_id','C1.id')
    ->join('services as S1','service_request.service_id','S1.id')
    ->join('sub_services as S2','service_request.sub_service_id','S2.id')
    ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')
    ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','C1.es_name as category_name','S1.es_name as service_name','S2.es_name as sub_service_name','S3.es_name as child_subservice_name')    
    ->where('service_request.status','1')->where('service_request.user_id',$userid)->whereRaw("(service_request.deleted_at IS null )")->get();     

    if(!empty($servicesRequested))
    {  
      return view('frontend.user.all_accepted_requests')->withData($servicesRequested);
    }
    else
    {
     return redirect()->route('frontend.user.dashboard');
    }
                         
  }


  public function allRejectedRequest()
  {

    $userid= auth()->user()->id;

    $userEntity = DB::table('users')
    ->whereRaw("(active=1)")
    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
    ->first();

    $allData=array();   

    $servicesRequested = DB::table('service_request')
    ->join('category AS C1','service_request.category_id','C1.id')
    ->join('services as S1','service_request.service_id','S1.id')
    ->join('sub_services as S2','service_request.sub_service_id','S2.id')
    ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')
    ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','C1.es_name as category_name','S1.es_name as service_name','S2.es_name as sub_service_name','S3.es_name as child_subservice_name')    
    ->where('service_request.status','3')->where('service_request.user_id',$userid)->whereRaw("(service_request.deleted_at IS null )")->get();     

    if(!empty($servicesRequested))
    {  
      return view('frontend.user.all_rejected_requests')->withData($servicesRequested);
    }
    else
    {
     return redirect()->route('frontend.user.dashboard');
    }
                         
  }

  public function allInprogressRequests()
  {

    $userid= auth()->user()->id;

    $userEntity = DB::table('users')
    ->whereRaw("(active=1)")
    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
    ->first();

    $allData=array();   

    $servicesRequested = DB::table('service_request')
    ->join('category AS C1','service_request.category_id','C1.id')
    ->join('services as S1','service_request.service_id','S1.id')
    ->join('sub_services as S2','service_request.sub_service_id','S2.id')
    ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')
    ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','C1.es_name as category_name','S1.es_name as service_name','S2.es_name as sub_service_name','S3.es_name as child_subservice_name')    
    ->where('service_request.status','2')->where('service_request.user_id',$userid)->whereRaw("(service_request.deleted_at IS null )")->get();     

    if(!empty($servicesRequested))
    {  
      return view('frontend.user.all_inprogress_requests')->withData($servicesRequested);
    }
    else
    {
     return redirect()->route('frontend.user.dashboard');
    }
                         
  }

  public function allCompletedRequests()
  {

    $userid= auth()->user()->id;

    $userEntity = DB::table('users')
    ->whereRaw("(active=1)")
    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")
    ->first();

    $allData=array();   

    $servicesRequested = DB::table('service_request')
    ->join('category AS C1','service_request.category_id','C1.id')
    ->join('services as S1','service_request.service_id','S1.id')
    ->join('sub_services as S2','service_request.sub_service_id','S2.id')
    ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')
    ->select('service_request.id','service_request.service_id','service_request.location','service_request.username','service_request.status','service_request.email_verify','service_request.created_at','C1.es_name as category_name','S1.es_name as service_name','S2.es_name as sub_service_name','S3.es_name as child_subservice_name')    
    ->where('service_request.status','4')->where('service_request.user_id',$userid)->whereRaw("(service_request.deleted_at IS null )")->get();     

    if(!empty($servicesRequested))
    {  
      return view('frontend.user.all_completed_requests')->withData($servicesRequested);
    }
    else
    {
     return redirect()->route('frontend.user.dashboard');
    }

      
                         
  }


  public function serviceDetails($s_id)
  {
    
    $service_request_id = Crypt::decrypt($s_id);

    //echo "<pre>"; print_r($service_request_id); die('as');
    //echo $service_id; die;

    $service_detail = DB::table('service_request')
    ->join('category AS C1','service_request.category_id','C1.id')
    ->join('services as S1','service_request.service_id','S1.id')
    ->join('sub_services as S2','service_request.sub_service_id','S2.id')
    ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')
    ->select('service_request.*','C1.es_name as category_name','S1.es_name as service_name','S2.es_name as sub_service_name','S3.es_name as child_subservice_name')
    ->where('service_request.id',$service_request_id)
    ->first();

    $question_detail = array();

    $question_detail = DB::table('service_request_questions')
    ->join('questions','service_request_questions.question_id','questions.id')
    ->leftjoin('question_options','service_request_questions.option_id','question_options.id')
    ->select('questions.es_title as question_title','questions.question_type','question_options.*','service_request_questions.option_id')
    ->where('service_request_questions.service_request_id',$service_request_id)
    ->get();

    $service_buyers = DB::table('buy_requested_services')
    ->join('users','buy_requested_services.user_id','users.id')
    ->select('buy_requested_services.*','users.username')
    ->where('buy_requested_services.requested_service_id',$service_request_id)
    ->get();

    //echo "<pre>"; print_r($service_buyers); die;
    return view('frontend.user.service_details',compact('service_detail','question_detail','service_buyers'));
  }


  public function profileComprasion($s_id) {

    $service_request_id = Crypt::decrypt($s_id);

    $service_buyers = DB::table('buy_requested_services as B1')
    ->join('users','B1.user_id','users.id')
    ->join('roles','users.user_group_id','roles.id')   
    ->join('social_networks as S1','users.id','S1.user_id')   
    ->select('users.*','B1.user_id','B1.requested_service_id','B1.amount','B1.tranx_id','B1.tranx_status','B1.currency','B1.created_at','roles.name as user_role','S1.facebook_url','S1.instagram_url','S1.snap_chat_url','S1.linkedin_url','S1.youtube_url','S1.other')
    ->where('B1.requested_service_id',$service_request_id)
    ->get();
    //echo "<pre>"; print_r($service_buyers); die;
    foreach ($service_buyers as $key => $value) {
      if($value->user_group_id == 4){
        $value->total_workers = DB::table('workers')->where('user_id',$value->id)->where('deleted_at',NULL)->count();
      }else {
        $value->total_workers = 0;
      }
      $value->user_payment_methods = array();
      $value->user_payment_methods = array();
      $value->user_payment_methods = DB::table('user_payment_methods')->join('payment_methods','user_payment_methods.payment_method_id','payment_methods.id')->where('user_payment_methods.user_id',$value->id)->select('payment_methods.name_es')->get();

      $value->services_offered = array();
      $value->services_offered = DB::table('services_offered')->join('services','services_offered.service_id','services.id')->select('services.es_name')->where('services_offered.user_id',$value->id)->get();

      $ratings = DB::table('reviews')->where('to_user_id',$value->id)->select('reviews.*')->get();
      

      $total_rating = 0;
      $total_price_rating = 0;
      $total_puntuality_rating = 0;
      $total_service_rating = 0;
      $total_quality_rating = 0;
      $total_amiability_rating = 0;
      $value->total_rate_count = 0;
      $value->is_rated = 'No';
      $value->average_rating = 0;
      $value->average_price_rating = 0;
      $value->average_puntuality_rating = 0;
      $value->average_service_rating = 0;
      $value->average_quality_rating = 0;
      $value->average_amiability_rating = 0;
      
      if(isset($ratings) && count($ratings) > 0){
        $value->is_rated = 'Yes';
        $value->total_rate_count = count($ratings);
        foreach ($ratings as $k_ratings => $v_ratings) {
          $total_rating += $v_ratings->rating;
          $total_price_rating += $v_ratings->price;
          $total_puntuality_rating += $v_ratings->puntuality;
          $total_service_rating += $v_ratings->service;
          $total_quality_rating += $v_ratings->quality;
          $total_amiability_rating += $v_ratings->amiability;
        }
        
          $value->average_rating = round($total_rating / $value->total_rate_count);
          $value->average_price_rating = round($total_price_rating / $value->total_rate_count);
          $value->average_puntuality_rating = round($total_puntuality_rating / $value->total_rate_count);
          $value->average_service_rating = round($total_service_rating / $value->total_rate_count);
          $value->average_quality_rating = round($total_quality_rating / $value->total_rate_count);
          $value->average_amiability_rating = round($total_amiability_rating / $value->total_rate_count);
        
      }

     
    }

    //echo "<pre>"; print_r($service_buyers); die;

    return view('frontend.user.profile_comprasion',compact('service_buyers'));
  }

  public function searchRequest(Request $request) {

    $userid= auth()->user()->id;
    $search_key = $request->search;
    
    $request_status = $request->request_status;
    $allData=array();
    if($search_key == '')
    {        
      $allData = DB::table('service_request as SER')
      ->join('category AS C1','SER.category_id','C1.id')
      ->join('services as S1','SER.service_id','S1.id') 
      ->join('sub_services as S2','SER.sub_service_id','S2.id')
      ->leftjoin('child_sub_services as S3','SER.child_sub_service_id','S3.id')   
      ->select('SER.id','SER.created_at','C1.es_name as category_name','S1.es_name as service_name')     
      ->where('SER.user_id',$userid)->whereRaw("(SER.deleted_at IS null )")->get();  
      
    } else {

    	if($request->request_status == 'all'){

	      $allData = DB::table('service_request as SER')
	      ->join('category AS C1','SER.category_id','C1.id')
	      ->join('services as S1','SER.service_id','S1.id') 
	      ->join('sub_services as S2','SER.sub_service_id','S2.id')
	      ->leftjoin('child_sub_services as S3','SER.child_sub_service_id','S3.id')   
	      ->select('SER.id','SER.created_at','C1.es_name as category_name','S1.es_name as service_name')
	      ->where('C1.es_name', 'LIKE', "%$search_key%")	        
	      ->where('SER.user_id',$userid)->get();

    	} else{

	      $allData = DB::table('service_request as SER')
	      ->join('category AS C1','SER.category_id','C1.id')
	      ->join('services as S1','SER.service_id','S1.id') 
	      ->join('sub_services as S2','SER.sub_service_id','S2.id')
	      ->leftjoin('child_sub_services as S3','SER.child_sub_service_id','S3.id')   
	      ->select('SER.id','SER.created_at','C1.es_name as category_name','S1.es_name as service_name')
	      ->where('C1.es_name', 'LIKE', "%$search_key%")
	      ->where('SER.status',$request_status)    
	      ->where('SER.user_id',$userid)->get();

	      //echo "<pre>"; print_r($allData); die('allData');
    	}
      


    }

    //echo "<pre>"; print_r($allData); die;

    $html = view('frontend.user.get_ajax_search_request')->with(compact('allData'))->render();
    return response()->json(['success' => true, 'html' => $html]);

  }


  public function review() {

    return view('frontend.user.review');
  }

  public function storeReview(Request $request) {

    //echo "<pre>"; print_r($request->all()); die;
    
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

    $review_data = array();

    $review_data['user_id'] = $user_id;
    $review_data['review_by'] = 'user';
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
      return redirect()->route('frontend.user.dashboard')->withFlashSuccess(__('alerts.frontend.users.send_request.review_submited_successfully')); 
    }else{
      return redirect()->route('frontend.user.dashboard')->withFlashDanger(__('alerts.frontend.users.send_request.something_went_wrong')); 
    }


  }

  public function userChat(){
    //die('userChat');
     return view('frontend.user.user_chat');
  }

  public function hierServiceProvider($r_id, $sp_id) {

    $request_id = Crypt::decrypt($r_id);

    $service_provider_id = Crypt::decrypt($sp_id);

    $hier_data = array();

    $hier_data['status'] = '2';
    $hier_data['assigned_user_id'] = $service_provider_id;
    $hier_data['updated_at'] = Carbon::now();

    $review_id = DB::table('service_request')->where('id',$request_id)->update($hier_data);

    if($review_id){
      return redirect()->route('frontend.user.dashboard')->withFlashSuccess(__('alerts.frontend.users.send_request.hire_successfully')); 
    }else{
      return redirect()->route('frontend.user.dashboard')->withFlashDanger(__('alerts.frontend.users.send_request.something_went_wrong')); 
    }


  } 
  public function proInformation($proid=null,$serviceid=null)
  {
            $userId = Crypt::decrypt($proid);
            //$userId=$request->proid;
           $serviceId=Crypt::decrypt($serviceid);
            $bonus = DB::table('bonus')->select('current_balance')->whereRaw("(user_id = '".$userId."')")->whereRaw("(expire_status = 0)")->first();

            $review_datas = DB::table('reviews')
               ->join('users as U1','reviews.user_id','=','U1.id')
               ->join('users as U2','reviews.to_user_id','=','U2.id')
               ->join('service_request','reviews.request_id','=','service_request.id')
               ->select('U1.username','U1.mobile_number', 'U1.avatar_location','U2.username as provider_name','reviews.*')
               ->where('reviews.deleted_at',NULL)
               ->where('reviews.to_user_id', $userId)
               ->get();

            $userdata=DB::table('users')->select('users.ruc_no','users.year_of_constitution','users.website_address','users.approval_status','users.is_confirm_reg_step','users.id','users.banner','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at','users.total_employee')->where('id',$userId)->first();
 
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
               $path='/img/contractor/gallery/videos/'.$userId.'/';
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
                        $path='/img/contractor/certifications/'.$userId.'/';
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
                       $path='/img/contractor/police_records/'.$userId.'/';
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

                    $proservice=DB::table('assign_service_request')->where('user_id',$userId)->where('service_request_id',$serviceId)->first();
                    // echo '<pre>'; print_r($proservice);exit;

                    return view('frontend.user.mi_perfil', compact('review_datas', 'userId','serviceId','proservice'))
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
    public function HireProOrCompany(Request $request)
    {
        $pro_user_id = !empty($request->pro_user_id) ? $request->pro_user_id : '' ;
        $user_id = auth()->user()->id;
        $request_id = !empty($request->request_id) ? $request->request_id : '' ;
//dd($request->all());
            if(!empty($user_id) && !empty($pro_user_id))
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
                                    $update_Arr['request_status'] = 'buy';
                                    //$update_Arr['job_status'] = 3;
                                    $update_jobArr['request_status'] = 'ignore';
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
                                    return redirect()->to('projects')->withFlashSuccess(__('apimessage.hire_successfully'));     
                                }
                                else
                                {
                                    return redirect()->to('projects')->withFlashDanger(__('apimessage.hire_already_exist'));
                                }
                            }
                            else
                            {   
                                return redirect()->to('projects')->withFlashDanger(__('not found.!'));
                                
                            }
                    }
                    else
                    {
                        return redirect()->to('projects')->withFlashDanger(__('apimessage.Invalid user.'));
                    }
            }
            else
            {
                return redirect()->to('projects')->withFlashDanger(__('apimessage.Invalid parameter.'));
            }
    }

    public function manageRequestStatus(Request $request)
    {
        $access_token=123456;
        $client_user_id = !empty($request->client_user_id) ? $request->client_user_id : '' ;
        $user_id = auth()->user()->id;
        $request_id = !empty($request->request_id) ? $request->request_id : '' ;
        $status_type = !empty($request->status_type) ? $request->status_type : '' ;
        $session_key = !empty($request->session_key) ? $request->session_key : '' ;
        $lang = !empty($request->lang) ? $request->lang : 'en' ;
         
            if(!empty($user_id) && !empty($client_user_id) && !empty($request_id)) 
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

                            // $userDevice=DB::table('user_devices')->where('user_id',$client_user_id)->first();
                            // $device_id=$userDevice->device_id;
                            // $device_type=$userDevice->device_type;
                            // $userId=$client_user_id;
                            // $prouserId=$user_id;
                            // $serviceId=$request_id;
                            // $senderid=0;
                            // $reciverid=0;
                            // $chattype=0;
                          
                            $senderName=isset($userEntity->username)?$userEntity->username:'';
                            // if($userDevice->device_type=='android')
                            // {
                                // $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderid,$reciverid,$chattype,$senderName,$notify_type);
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
            }else
            {
                $resultArray['status']='0';
                $resultArray['message']=trans('apimessage.Invalid parameter.');
                echo json_encode($resultArray); exit;
            }
    }
            
}
