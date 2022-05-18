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

                   $data2['question'] = isset($question) && !empty($question) ? $question : '';
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
      return redirect()->route('frontend.user.dashboard')->withFlashSuccess(__('Review Submited Successfully.!'));
    }else{
      return redirect()->route('frontend.user.dashboard')->withFlashDanger(__('Something Went Wrong.'));
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
      return redirect()->route('frontend.user.dashboard')->withFlashSuccess(__('hire Successfully.!'));
    }else{
      return redirect()->route('frontend.user.dashboard')->withFlashDanger(__('Something Went Wrong.'));
    }


  }

            
}
