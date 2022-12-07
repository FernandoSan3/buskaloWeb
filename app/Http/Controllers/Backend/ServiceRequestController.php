<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\Subservices;
use App\Models\Questions;
use App\Models\QuestionOptions;
use App\Mail\NewOpportunity;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class ServiceRequestController extends Controller
{

    public function index()
    {

      $quesry = DB::table('service_request')
        ->join('category','service_request.category_id','=','category.id')
        ->join('cities','cities.id','=','service_request.city_id')
        ->join('services','service_request.service_id','=','services.id')
        ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name','cities.name as cityname')
        ->where('service_request.deleted_at',NULL);

        if(isset($_REQUEST['from']) && !empty($_REQUEST['from'] && isset($_REQUEST['to'])&& !empty($_REQUEST['to'])))
        {
            $quesry->whereBetween('service_request.created_at', [$_REQUEST['from']." 00:00:00",$_REQUEST['to']." 23:59:59"]);
        }
       $service_requests  =$quesry->orderBy('service_request.id','desc')
        ->get();





        if($service_requests) {
            foreach ($service_requests as $key => $value) {

                $value->question_detail = DB::table('service_request_questions')
                ->join('questions','service_request_questions.question_id','=','questions.id')
                ->join('question_options','service_request_questions.option_id','=','question_options.id')
                ->select('service_request_questions.*','questions.en_title as en_question_title','questions.es_title as es_question_title','question_options.en_option as en_option_name','question_options.es_option as es_option_name')
                ->where('service_request_questions.deleted_at',NULL)
                ->where('service_request_questions.service_request_id',$value->id)
                ->get();

            }
        }

        return view('backend.service_requests.index',compact('service_requests'));
    }


    public function create()
    {
        $subservices = array();
        $services = Services::all()->where('deleted_at',NULL);
        return view('backend.questions.create',compact('subservices','services'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'services_id' => 'required',
            'en_title' => 'required',
            'es_title' => 'required',
            'sub_services_id' => 'required',
        ]);

        $insert_qns['services_id'] = $request->services_id;
        $insert_qns['sub_services_id'] = $request->sub_services_id;
        $insert_qns['en_title'] = $request->en_title;
        $insert_qns['es_title'] = $request->es_title;
        $insert_qns['created_at'] = Carbon::now();
        $insert_qns['updated_at'] = Carbon::now();

        $question_id = DB::table('questions')->insertGetId($insert_qns);

        $en_ans_arr = $request->ans['en'];
        $es_ans_arr = $request->ans['es'];
        $new_arr = array();
        $new_arr = array_combine($en_ans_arr,$es_ans_arr);

        foreach ($new_arr as $key => $value) {

            $insert_ans['question_id'] = $question_id;
            $insert_ans['en_option'] = $key;
            $insert_ans['es_option'] = $value;
            $insert_ans['created_at'] = Carbon::now();
            $insert_ans['updated_at'] = Carbon::now();

            QuestionOptions::insert($insert_ans);
        }

        return redirect()->route('admin.questions.index')->with('success','Questions created successfully.');
    }

    public function forward($request_id)
    {
        $service_requests = DB::table('service_request')
        ->join('services','service_request.service_id','=','services.id')
        ->leftjoin('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('category','service_request.category_id','=','category.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->select('service_request.*','sub_services.en_name as en_subservice_name','sub_services.es_name as es_subservice_name','services.en_name as en_service_name','services.es_name as es_service_name','category.en_name as en_category_name','child_sub_services.es_name as es_child_subservice_name')
        ->where('service_request.id',$request_id)
        ->first();

        $servceId=DB::table('service_request_questions')->where('service_request_id',$request_id)->get();
               
        $serviceamount=DB::table('assign_service_request')
                ->where('assign_service_request.service_request_id',$request_id)
               ->select('credit')
                ->first();
        $service_credit=isset($serviceamount->credit)?$serviceamount->credit:'';
        $latitude=isset($service_requests->latitude)?$service_requests->latitude:'';
        $longitude=isset($service_requests->longitude)?$service_requests->longitude:'';
      // if(empty($serviceamount))
       //{
            if(isset($service_requests->sub_service_id) && !empty($service_requests->sub_service_id))
            {
                 $secondservice= DB::table('sub_services')->where('id',$service_requests->sub_service_id)->first();
                  $service_credit=isset($secondservice->price)?$secondservice->price:0;
            }

            if(isset($service_requests->child_sub_service_id) && !empty($service_requests->child_sub_service_id))
            {
                 $childservice= DB::table('child_sub_services')->where('id',$service_requests->child_sub_service_id)->select('percentage')->first();
                  $service_credit= ($service_credit*$childservice->percentage)/100;
            }
            foreach ($servceId as $key => $value)
            {
                $questionoption= DB::table('question_options')->where('id',$value->option_id)->where('question_id',$value->question_id)->select('factor','id')->first();
                if(!empty($questionoption))
                {
                    if(!empty($questionoption->factor))
                    {
                        $service_credit=($service_credit*$questionoption->factor)/100;
                    }
                }
            }
                $getcityzone=DB::table('zone')->where('city_id',$service_requests->city_id)->get();
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
      // }
        $lang = ($service_requests->en_subservice_name == '.')?'es':'en';
        $serviceName=  $this->sendApportunityNotification($request_id,$service_credit);
        $sendNotificaction= $this->serviceVerifyNotification($request_id, $lang);
       return redirect()->route('admin.service_request.index')->with('success','Request forward successfully.');
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

    public function sendApportunityNotification($request_id,$serviceamount,$lang= null)
    {
        if(!empty($request_id))
        {
                
            $servicereq = DB::table('service_request')
                    ->whereRaw("(status = '0')")
                    ->whereRaw("(id = '".$request_id."' AND deleted_at IS null )")
                    ->first();


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

                $checkuser = DB::table('assign_service_request')
                    ->where('assign_service_request.service_request_id',$request_id)
                    ->get()->pluck('user_id')->toArray();
                  
                if(!empty($resultArray))
                { 
                    foreach ($resultArray as $key => $getuser) 
                    {
                        if(!in_array($getuser->id, $checkuser))
                        {
                            $insert['user_id'] = $getuser->id;    
                            $insert['service_request_id'] = $request_id;
                            $insert['credit'] = number_format($price_credit,2);
                            $insert['notification'] =0; 
                            $insert['created_at'] = Carbon::now();  
                            DB::table('assign_service_request')->insertGetId($insert);
                        }
                    }

                    return true;
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
    public function show($request_id)
    {

       $service_requests = DB::table('service_request')
        ->join('services','service_request.service_id','=','services.id')
        ->leftjoin('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('category','service_request.category_id','=','category.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->select('service_request.*','sub_services.en_name as en_subservice_name','sub_services.es_name as es_subservice_name','services.en_name as en_service_name','services.es_name as es_service_name','category.es_name as es_category_name','child_sub_services.es_name as es_child_subservice_name')
            ->where('service_request.id',$request_id)
            ->first();


        if($service_requests) {

            $service_requests->question_detail = DB::table('service_request_questions')
            ->join('questions','service_request_questions.question_id','=','questions.id')
            ->join('question_options','service_request_questions.option_id','=','question_options.id')
            ->select('service_request_questions.*','questions.en_title as en_question_title','questions.es_title as es_question_title','question_options.en_option as en_option_name','question_options.es_option as es_option_name')
            ->where('service_request_questions.deleted_at',NULL)
            ->where('service_request_questions.service_request_id',$request_id)
            ->get();

      }

        // echo "<pre>";
       // print_r($service_requests);
       // exit();

       $user_details = DB::table('assign_service_request')
            ->join('users','assign_service_request.user_id','=','users.id')
            ->select('users.username','assign_service_request.credit as amount','assign_service_request.tranx_id','assign_service_request.user_id','assign_service_request.job_status')
            ->where('assign_service_request.service_request_id',$request_id)
            ->where('assign_service_request.request_status','!=',null)
            ->get();


        foreach ($user_details as $key => $value) {

         $value->status = DB::table('service_request')->where('assigned_user_id',$value->user_id)->first();
          }

        //echo "<pre>";print_r($user_details);die;

         return view('backend.service_requests.show',compact('service_requests','user_details'));
    }


    public function edit($question_id)
    {
        $subservices = Subservices::all()->where('deleted_at',NULL);
        $question_details = Questions::where('id',$question_id)->first();
        $question_details->options = QuestionOptions::where('question_id',$question_id)->where('deleted_at',NULL)->get();

        return view('backend.questions.edit',compact('question_details','subservices'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'question_id' => 'required',
            'en_title' => 'required',
            'es_title' => 'required',
        ]);

        $update_qns['en_title'] = $request->en_title;
        $update_qns['es_title'] = $request->es_title;
        $update_qns['updated_at'] = Carbon::now();

        DB::table('questions')->where('id',$request->question_id)->update($update_qns);

        $en_ans_arr = $request->ans['en'];
        $es_ans_arr = $request->ans['es'];
        $new_arr = array();
        $new_arr = array_combine($en_ans_arr,$es_ans_arr);

        QuestionOptions::where('question_id',$request->question_id)->delete();

        foreach ($new_arr as $key => $value) {

            $insert_ans['question_id'] = $request->question_id;
            $insert_ans['en_option'] = $key;
            $insert_ans['es_option'] = $value;
            $insert_ans['created_at'] = Carbon::now();
            $insert_ans['updated_at'] = Carbon::now();

            QuestionOptions::insert($insert_ans);
        }

        return redirect()->route('admin.questions.index')->with('success','Question updated successfully');
    }


    public function destroy($id=null)
    {
        $updateArr['deleted_at'] = Carbon::now();
        DB::table('service_request')->where('id',$id)->update($updateArr);
        return redirect()->route('admin.service_request.index')->with('success','Service request deleted successfully');
    }

    public function getSubservices(Request $request) {

        $services_id = $request->input('services_id');
        $subservices = Subservices::all()->where('services_id',$services_id)->where('deleted_at',NULL);
        $html = view('backend.questions.get_sub_services')->with(compact('subservices'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function allRequestsByStatus($status)
    {
        if($status == 'all') {

            $service_requests = DB::table('service_request')
            ->join('category','service_request.category_id','=','category.id')
            ->join('services','service_request.service_id','=','services.id')
             ->join('cities','cities.id','=','service_request.city_id')
            ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
            ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
            ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name','cities.name as cityname')
            ->paginate(25);
        } else {
            $service_requests = DB::table('service_request')
            ->join('category','service_request.category_id','=','category.id')
            ->join('services','service_request.service_id','=','services.id')
            ->join('cities','cities.id','=','service_request.city_id')
            ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
            ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
            ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name','cities.name as cityname')
            ->where('service_request.status',$status)
            ->paginate(25);
        }

        return view('backend.service_requests.showservice_status',compact('service_requests'));
    }

    public function serviceVerifyNotification($request_id, $lang)
    {
        $request_id = isset($request_id) && !empty($request_id) ? $request_id : '' ;
        $lang =  !empty($lang) ? $lang : 'en' ;
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
            
            $this->postpushnotification($device_id,$title,$message,$userId,$prouserId,$serviceId,$senderId,$reciverId,$chatType,$senderName,$notify_type);
            Mail::to($email)->send(new NewOpportunity($objDemo, $objQuestion));

            echo 'send all notification';
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

}