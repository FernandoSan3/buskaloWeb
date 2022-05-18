<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\Subservices;
use App\Models\Questions;
use App\Models\QuestionOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Backend\StoreContractorRequest;
use App\Http\Requests\Backend\StoreWorkerRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Ramsey\Uuid\Uuid;



class CompanyController extends Controller
{

    public function index()
    {
        //die('companyt index');
        $users = DB::table('users')->latest()->where('deleted_at',NULL)->where('user_group_id',4)->where('approval_status',1)->get();

        foreach ($users as $key => $value) {
        $value->total_service_requests = DB::table('service_request')
        ->join('category','service_request.category_id','=','category.id')
        ->join('services','service_request.service_id','=','services.id')
        ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->where('service_request.user_id',$value->id)
        ->count();

      }
        return view('backend.company.index',compact('users'));
    }


    public function create()
    {
       // die('create');
        return view('backend.company.create');
    }

    public function paymentInfo($id)
    {
        $paymentinfo = DB::table('users')->where('id',$id)->first();
        return view('backend.company.show_payment', compact('paymentinfo'));
    }
    public function store(StoreContractorRequest $request)
    {
        $password = $request->password;
        if (
            (\strlen($password) === 60 && preg_match('/^\$2y\$/', $password)) ||
            (\strlen($password) === 95 && preg_match('/^\$argon2i\$/', $password))
        ) {
            $hash = $password;
        } else {
            $hash = Hash::make($password);
        }


        $imagename=""; $storeName="";

        if(!empty($request->avatar_location))
        {
          $destinationPath = public_path('/img/company/profile');

           if($request->avatar_location=="")
           {
              $imagename="";
            }
            else
            {
                $image = $request->avatar_location;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'company/profile/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                 if(file_exists(public_path().$destinationPath.$imagename))
                 {
                    unlink(public_path().$destinationPath.$imagename);
                    $image->move($destinationPath, $imagename);
                }
                 else
                 {
                    $image->move($destinationPath, $imagename);
                }
            }
        }

        $insert_arr['uuid'] = Uuid::uuid4()->toString();
        $insert_arr['username'] = $request->username;
        $insert_arr['email'] = $request->email;
        $insert_arr['password'] = $hash;
        $insert_arr['active'] = 1;
        $insert_arr['avatar_location'] = $storeName;
        $insert_arr['confirmed'] = 1;
        $insert_arr['is_verified'] = 1;
        $insert_arr['user_group_id'] = 4;
        $insert_arr['ruc_no'] = $request->ruc_no;
        $insert_arr['year_of_constitution'] = $request->year_of_constitution;
        $insert_arr['legal_representative'] = $request->legal_representative;
        $insert_arr['website_address'] = $request->website_address;
        $insert_arr['dob'] = $request->dob;
        $insert_arr['mobile_number'] = $request->mobile_number;
        $insert_arr['landline_number'] = $request->landline_number;
        $insert_arr['office_number'] = $request->office_number;
        $insert_arr['address'] = $request->address;
        $insert_arr['office_address'] = $request->office_address;
        $insert_arr['other_address'] = $request->other_address;
        $insert_arr['address'] = $request->address;
        $insert_arr['profile_description'] = $request->profile_description;
        $insert_arr['created_at'] = Carbon::now();
        $insert_arr['updated_at'] = Carbon::now();

        $user_id = DB::table('users')->insertGetId($insert_arr);

        if($user_id) {
            $bonus_arr['user_id'] = $user_id;
            $bonus_arr['debit'] = 20;
            $bonus_arr['credit'] = 0;
            $bonus_arr['current_balance'] = 20;
            $bonus_arr['expire_status'] = 0;
            $bonus_arr['transaction_date'] = Carbon::now();
            $bonus_arr['updated_at'] = Carbon::now();

            DB::table('bonus')->insertGetId($bonus_arr);

            $social_arr['user_id'] = $user_id;
            $social_arr['facebook_url'] = $request->facebook_url;
            $social_arr['instagram_url'] = $request->instagram_url;
            $social_arr['snap_chat_url'] = $request->snap_chat_url;
            $social_arr['twitter_url'] = $request->twitter_url;
            $social_arr['youtube_url'] = $request->youtube_url;
            $social_arr['status'] = 1;
            $social_arr['created_at'] = Carbon::now();
            $social_arr['updated_at'] = Carbon::now();

            DB::table('social_networks')->insertGetId($social_arr);

        }

        return redirect()->route('admin.company.index')->with('success','company created successfully.');
    }


    public function createWorker($user_id)
    {
        $document_types = DB::table('document_types')->where('deleted_at',NULL)->first();
        return view('backend.company.create_worker',compact('user_id','document_types'));
    }


    public function storeWorker(StoreWorkerRequest $request)
    {

        $password = $request->password;
        if (
            (\strlen($password) === 60 && preg_match('/^\$2y\$/', $password)) ||
            (\strlen($password) === 95 && preg_match('/^\$argon2i\$/', $password))
        ) {
            $hash = $password;
        } else {
            $hash = Hash::make($password);
        }


        $imagename=""; $storeName="";

        if(!empty($request->profile_pic))
        {
          $destinationPath = public_path('/img/worker/profile');

           if($request->profile_pic=="")
           {
              $imagename="";
            }
            else
            {
                $image = $request->profile_pic;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'worker/profile/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                 if(file_exists(public_path().$destinationPath.$imagename))
                 {
                    unlink(public_path().$destinationPath.$imagename);
                    $image->move($destinationPath, $imagename);
                }
                 else
                 {
                    $image->move($destinationPath, $imagename);
                }
            }
        }


        $insert_arr['user_id'] = $request->user_id;
        $insert_arr['username'] = $request->username;
        $insert_arr['email'] = $request->email;
        $insert_arr['password'] = $hash;
        $insert_arr['profile_pic'] = $storeName;
        $insert_arr['status'] = 1;
        $insert_arr['mobile_number'] = $request->mobile_number;
        $insert_arr['address'] = $request->address;
        $insert_arr['created_at'] = Carbon::now();
        $insert_arr['updated_at'] = Carbon::now();

        $worker_id = DB::table('workers')->insertGetId($insert_arr);


        if($worker_id) {


            $doc_name = $request->file('doc_name');
            if(isset($doc_name) && !empty($doc_name)) {
                //die('jgfg');
                foreach ($doc_name as $key => $doc_name_new) {

                    $imagename_doc=""; $storeDocName="";
                    $destinationPath = public_path('/img/worker/document');
                    $image = $doc_name_new;
                    $imagename_doc = date('Y-m-d').$key.time().'-img'.'.' . $image->getClientOriginalExtension();
                    //echo $image->getClientOriginalExtension(); die;

                    $storeDocName=  'worker/document/'.$imagename_doc;
                    if(file_exists(public_path().$destinationPath.$imagename_doc))
                    {
                        unlink(public_path().$destinationPath.$imagename_doc);
                        $image->move($destinationPath, $imagename_doc);
                    }
                     else
                    {
                        $image->move($destinationPath, $imagename_doc);
                    }

                    $doc_arr_arr['user_id'] = $worker_id;
                    $doc_arr_arr['doc_name'] = $storeDocName;
                    $doc_arr_arr['doc_type'] = $image->getClientOriginalExtension();
                    $doc_arr_arr['document_id'] = 1;
                    $doc_arr_arr['is_verified'] = 1;
                    $doc_arr_arr['status'] = 1;
                    $doc_arr_arr['created_at'] = Carbon::now();
                    $doc_arr_arr['updated_at'] = Carbon::now();

                    DB::table('workers_document')->insertGetId($doc_arr_arr);
                }
            }

            }


        return redirect()->route('admin.company.index')->with('success','worker created successfully.');
    }

    public function editWorker($worker_id)
    {

        $document_types = DB::table('document_types')->where('deleted_at',NULL)->first();
        $worker_details = DB::table('workers')->where('id',$worker_id)->first();
       return view('backend.company.edit_worker',compact('worker_id','document_types','worker_details'));

    }


    public function updateWorker(Request $request)
    {
        $request->validate([
            'worker_id' => 'required',
            'username' => 'required',
            'mobile_number' => 'required',
            'address' => 'required',
        ]);

        $worker_details = DB::table('workers')->where('id',$request->worker_id)->first();

        $imagename=""; $storeName="";

        if(!empty($request->profile_pic))
        {
          $destinationPath = public_path('/img/worker/profile');

           if($request->profile_pic=="")
           {
              $imagename="";
            }
            else
            {
                $image = $request->profile_pic;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'worker/profile/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                 if(file_exists(public_path().$destinationPath.$imagename))
                 {
                    unlink(public_path().$destinationPath.$imagename);
                    $image->move($destinationPath, $imagename);
                }
                 else
                 {
                    $image->move($destinationPath, $imagename);
                }
            }
        } else {
            $storeName = $worker_details->profile_pic;
        }




        $update_arr['username'] = $request->username;
        $update_arr['mobile_number'] = $request->mobile_number;
        $update_arr['profile_pic'] = $storeName;
        $update_arr['address'] = $request->address;
        $update_arr['updated_at'] = Carbon::now();

        $update_worker = DB::table('workers')->where('id',$request->worker_id)->update($update_arr);


        if($update_worker) {


            $doc_name = $request->file('doc_name');
            if(isset($doc_name) && !empty($doc_name)) {
                foreach ($doc_name as $key => $doc_name_new) {

                    $imagename_doc=""; $storeDocName="";
                    $destinationPath = public_path('/img/worker/document');
                    $image = $doc_name_new;
                    $imagename_doc = date('Y-m-d').$key.time().'-img'.'.' . $image->getClientOriginalExtension();
                    //echo $image->getClientOriginalExtension(); die;

                    $storeDocName=  'worker/document/'.$imagename_doc;

                    if(file_exists(public_path().$destinationPath.$imagename_doc))
                    {
                        unlink(public_path().$destinationPath.$imagename_doc);
                        $image->move($destinationPath, $imagename_doc);
                    }
                     else
                    {
                        $image->move($destinationPath, $imagename_doc);
                    }

                    $doc_arr_arr['user_id'] = $request->worker_id;
                    $doc_arr_arr['doc_name'] = $storeDocName;
                    $doc_arr_arr['doc_type'] = $image->getClientOriginalExtension();
                    $doc_arr_arr['document_id'] = 1;
                    $doc_arr_arr['is_verified'] = 1;
                    $doc_arr_arr['status'] = 1;
                    $doc_arr_arr['created_at'] = Carbon::now();
                    $doc_arr_arr['updated_at'] = Carbon::now();

                    DB::table('workers_document')->insertGetId($doc_arr_arr);
                }
            }

        }

        return redirect()->route('admin.company.index')->with('success','worker updated successfully.');
    }


    // public function show($question_id)
    // {
    //     $question_details = Questions::join('services','questions.services_id','=','services.id')->join('sub_services','questions.sub_services_id','=','sub_services.id')->select('questions.*','sub_services.en_name as en_subservice_name','sub_services.es_name as es_subservice_name','services.en_name as en_service_name','services.es_name as es_services_name')->where('questions.id',$question_id)->first();

    //     $question_details->options = QuestionOptions::where('question_id',$question_id)->where('deleted_at',NULL)->get();

    //     return view('backend.questions.show',compact('question_details'));
    // }

 public function show($user_id)
    {

        $company_details = DB::table('users')->leftjoin('social_networks','users.id','=','social_networks.user_id')
        ->select('users.*','facebook_url','instagram_url','snap_chat_url','twitter_url','youtube_url')
        ->where('users.id',$user_id)->first();

       // echo "<pre>";print_r($company_details);die;

        return view('backend.company.view_company',compact('company_details'));
    }

    public function edit($user_id)
    {

        $user_details = DB::table('users')->leftjoin('social_networks','users.id','=','social_networks.user_id')
        ->select('users.*','facebook_url','instagram_url','snap_chat_url','twitter_url','youtube_url')
        ->where('users.id',$user_id)->first();

        //echo "<pre>"; print_r($user_details); die('con');

        return view('backend.company.edit',compact('user_details'));
    }


    public function update(Request $request)
    {
        //die('update');
        $request->validate([
            'user_id' => 'required',
            'username' => 'required',
            // 'mobile_number' => 'required',
            // 'landline_number' => 'required',
            // 'address' => 'required',
            // 'profile_description' => 'required',
            // 'facebook_url' => 'required',
            // 'instagram_url' => 'required',
            // 'snap_chat_url' => 'required',
            // 'twitter_url' => 'required',
            // 'youtube_url' => 'required',
        ]);



       $user_details = DB::table('users')->leftjoin('social_networks','users.id','=','social_networks.user_id')
        ->select('users.*','facebook_url','instagram_url','snap_chat_url','twitter_url','youtube_url')
        ->where('users.id',$request->user_id)->first();



        $imagename=""; $storeName="";

        if(!empty($request->avatar_location))
        {

        $ext = $request->avatar_location->getClientOriginalExtension();

          $destinationPath = public_path('/img/company/profile');

           $store_imagename = $request->user_id.'.'.$ext;


           if($request->avatar_location=="")
           {
              $imagename="";
            }
            else
            {
                $image = $request->avatar_location;
                $imagename = $request->user_id.'.' . $image->getClientOriginalExtension();

                $storeName=  $store_imagename;

                 if(file_exists(public_path().$destinationPath.$imagename))
                 {
                    unlink(public_path().$destinationPath.$imagename);
                    $image->move($destinationPath, $imagename);
                }
                 else
                 {
                    $image->move($destinationPath, $store_imagename);
                }
            }
        } else {

            $storeName = $user_details->avatar_location;

        }

        $update_arr['year_of_constitution'] = $request->year_of_constitution;
        $update_arr['legal_representative'] = $request->legal_representative;
        $update_arr['website_address'] = $request->website_address;
        $update_arr['username'] = $request->username;
        $update_arr['avatar_location'] = $storeName;
        $update_arr['mobile_number'] = $request->mobile_number;
        $update_arr['landline_number'] = $request->landline_number;
        $update_arr['office_number'] = $request->office_number;
        $update_arr['address'] = $request->address;
        $update_arr['dob'] = $request->dob;
        $update_arr['office_address'] = $request->office_address;
        $update_arr['other_address'] = $request->other_address;
        $update_arr['profile_description'] = $request->profile_description;
        $update_arr['updated_at'] = Carbon::now();

        $user_id = DB::table('users')->where('id',$request->user_id)->update($update_arr);

        if($user_id) {

            $old_social_detail =  DB::table('social_networks')->where('user_id',$request->user_id)->where('deleted_at',NULL)->first();
            //echo "<pre>"; print_r($old_social_detail);die('asdas');
            if(isset($old_social_detail)){

                $social_arr['facebook_url'] = $request->facebook_url;
                $social_arr['instagram_url'] = $request->instagram_url;
                $social_arr['snap_chat_url'] = $request->snap_chat_url;
                $social_arr['twitter_url'] = $request->twitter_url;
                $social_arr['youtube_url'] = $request->youtube_url;
                $social_arr['updated_at'] = Carbon::now();

                DB::table('social_networks')->where('user_id',$request->user_id)->update($social_arr);


            } else {

                $social_arr['user_id'] = $request->user_id;
                $social_arr['facebook_url'] = $request->facebook_url;
                $social_arr['instagram_url'] = $request->instagram_url;
                $social_arr['snap_chat_url'] = $request->snap_chat_url;
                $social_arr['twitter_url'] = $request->twitter_url;
                $social_arr['youtube_url'] = $request->youtube_url;
                $social_arr['created_at'] = Carbon::now();

                DB::table('social_networks')->insert($social_arr);
            }

        }


        return redirect()->route('admin.company.index')->with('success','Company updated successfully.');

    }



    public function addServicesOffered($user_id)
    {

         $added_service = DB::table('services_offered')
         ->join('services','services_offered.service_id','=','services.id')
         ->select('services.en_name','services.es_name','services_offered.service_id')
         ->where('services_offered.user_id',$user_id)
         ->where('services_offered.deleted_at',NULL)
         ->get();

        $service_ids = array();
        if(isset($added_service) && count($added_service) > 0){

            foreach ($added_service as $key => $value) {

                array_push($service_ids,$value->service_id);
            }
        }


        $services = DB::table('services')->where('status',1)->where('deleted_at',NULL)->get();

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



        return view('backend.company.create_services_offered',compact('user_id','services','service_ids','combinedData'));


    }


    public function storeServicesOffered(Request $request)
    {

        $request->validate([
            'services' => 'required',
        ]);

        $userId = $request->user_id;


         if(!empty($request->services))
        {
            $serviceOfferedData=$request->services;
            $getData = DB::table('services_offered')->select('id','user_id','service_id','created_at','updated_at')->whereRaw("(user_id = '".$userId."')")->get()->toArray();
              // if(!empty($getData))
              // {
              //    DB::table('services_offered')->where('user_id', '=', $userId)->delete();
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
            $serv['created_at']     = Carbon::now();

            $saveserv = DB::table('services_offered')->insert($serv);


        }
    }

        return redirect()->route('admin.company.show_services_offered', ['id' => $userId]);
    }

      public function editServicesOffered($user_id)
    {

     $services_details = DB::table('services_offered')
         ->join('services','services_offered.service_id','=','services.id')
         ->select('services.en_name','services.es_name','services_offered.service_id','services_offered.sub_service_id')
         ->where('services_offered.user_id',$user_id)
         ->where('services_offered.deleted_at',NULL)
         ->get();

     $service_ids = array();
        if(isset($services_details) && count($services_details) > 0){

            foreach ($services_details as $key => $value) {

                array_push($service_ids,$value->service_id);
            }
        }

             $sub_service_ids = array();
        if(isset($services_details) && count($services_details) > 0){

            foreach ($services_details as $key => $value) {

                array_push($sub_service_ids,$value->sub_service_id);
            }
        }

        //echo "<pre>"; print_r($sub_service_ids); die;

        $services = DB::table('services')->where('status',1)->where('deleted_at',NULL)->get();

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


   // echo "<pre>"; print_r($combinedData); die('con');

        return view('backend.company.edit_services_offered',compact('services','service_ids','combinedData','services_details','sub_service_ids','user_id'));
    }


     public function updateServicesOffered(Request $request)
    {

       $userId = $request->user_id;

        $request->validate([
            'services' => 'required',
        ]);


    if(!empty($request->services))
    {
        $serviceOfferedData=$request->services;
        $getData = DB::table('services_offered')->select('id','user_id','service_id','created_at','updated_at')->whereRaw("(user_id = '".$userId."')")->get()->toArray();
        if(!empty($getData))
        {
            DB::table('services_offered')->where('user_id', '=', $userId)->delete();
        }


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

            $saveserv = DB::table('services_offered')->insert($serv);


        }
    }
       return redirect()->route('admin.company.show_services_offered', ['id' => $userId]);

        //return redirect()->route('admin.company.index')->with('success','services updated successfully.');

    }

     public function showServicesOffered($user_id)
    {


     $services_details = DB::table('services_offered')
         ->join('services','services_offered.service_id','=','services.id')
         ->join('users','services_offered.user_id','=','users.id')
         ->select('services.en_name','services.es_name','services_offered.service_id','services_offered.sub_service_id', 'services_offered.user_id', 'users.first_name', 'users.last_name', 'users.user_group_id')
         ->where('services_offered.user_id',$user_id)
         ->where('services_offered.deleted_at',NULL)
         ->get();


      $service_ids = array();
        if(isset($services_details) && count($services_details) > 0){

            foreach ($services_details as $key => $value) {

                array_push($service_ids,$value->service_id);
            }
        }

     $sub_service_ids = array();
        if(isset($services_details) && count($services_details) > 0){

            foreach ($services_details as $key => $value) {

                array_push($sub_service_ids,$value->sub_service_id);
            }
        }

        //echo "<pre>"; print_r($sub_service_ids); die;

        $services = DB::table('services')->where('status',1)->where('deleted_at',NULL)->get();

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

   // echo "<pre>"; print_r($combinedData); die('con');

        return view('backend.company.edit_show_offered_services',compact('services','service_ids','combinedData','services_details','sub_service_ids','user_id'));
    }

    public function addContractorDocuments($user_id)
    {
        return view('backend.contractors.add_contractor_documents',compact('user_id'));
    }

    public function storeContractorDocuments(Request $request)
    {

        $request->validate([
            'user_id' => 'required',
        ]);

        $doc_name = $request->doc_name;
        if(isset($doc_name) && !empty($doc_name)) {
            foreach ($doc_name as $doc_name) {

                $imagename_doc=""; $storeDocName="";
                $destinationPath = public_path('/img/contractor/docs');
                $image = $doc_name;
                $imagename_doc = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                //echo $image->getClientOriginalExtension(); die;

                $storeDocName=  'contractor/docs/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                if(file_exists(public_path().$destinationPath.$imagename_doc))
                {
                    unlink(public_path().$destinationPath.$imagename_doc);
                    $image->move($destinationPath, $imagename_doc);
                }
                 else
                {
                    $image->move($destinationPath, $imagename_doc);
                }

                $doc_arr_arr['user_id'] = $request->user_id;
                $doc_arr_arr['doc_name'] = $storeDocName;
                $doc_arr_arr['doc_type'] = $image->getClientOriginalExtension();
                $doc_arr_arr['document_id'] = 1;
                $doc_arr_arr['is_verified'] = 1;
                $doc_arr_arr['status'] = 1;
                $doc_arr_arr['created_at'] = Carbon::now();
                $doc_arr_arr['updated_at'] = Carbon::now();

                DB::table('users_document')->insertGetId($doc_arr_arr);
            }
        }


        return redirect()->route('admin.contractors.index')->with('success','services added successfully.');
    }

    public function allContractorDocuments($user_id)
    {
        $all_documents = DB::table('users_document')->where('user_id',$user_id)->where('deleted_at',NULL)->get();
        return view('backend.contractors.all_contractor_documents',compact('all_documents'));
    }

    public function allWorkers($user_id)
    {
        //die('AllWorkers');
        $all_workers = '';
        $all_workers = DB::table('workers')->where('user_id',$user_id)->where('deleted_at',NULL)->get();
        //echo "<pre>"; print_r($all_workers); die;
        return view('backend.company.all_workers',compact('user_id','all_workers'));
    }

     public function viewWorker($worker_id)
    {
        //die('asdasd');
        $worker_details = DB::table('workers')->where('id',$worker_id)->first();
        $worker_details->document = DB::table('workers_document')->where('user_id',$worker_id)->where('deleted_at',NULL)->get();


        return view('backend.company.view_worker',compact('worker_details'));
    }


    public function destroyWorker($worker_id)
    {
        $updateArr['deleted_at'] = Carbon::now();
        DB::table('workers')->where('id',$worker_id)->update($updateArr);
        return redirect()->route('admin.company.index')->with('success','Worker deleted successfully');
    }

    public function destroy($user_id)
    {
        $updateArr['deleted_at'] = Carbon::now();
        DB::table('users')->where('id',$user_id)->update($updateArr);
        return redirect()->route('admin.company.deleted')->with('success','Company deleted successfully');
    }

    public function get_districts(Request $request) {

        $city_id = $request->input('city_id');
        $districts = DB::table('districts')->where('city_id',$city_id)->where('deleted_at',NULL)->get();
        $html = view('backend.company.get_districts')->with(compact('districts'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function editCoverageArea($userId) {

        $user_ser_country = DB::table('users_services_area')->where('user_id',$userId)->where('whole_country',1)->count();
        if($user_ser_country == 1){
            $whole_country = 'Yes';

        } else {
            $whole_country = 'No';

        }

        $user_ser_area = DB::table('users_services_area')->where('user_id',$userId)->get();
        $user_province_ids = array();
        $user_city_ids = array();
        $user_province = DB::table('users_services_area')->where('user_id',$userId)->where('province_id','!=',NULL)->get();

        foreach ($user_province as $k_province => $v_province) {

            array_push($user_province_ids,$v_province->province_id);
        }

        //print_r($user_province_ids);die;

        $user_city = DB::table('users_services_area')->where('user_id',$userId)->where('city_id','!=',NULL)->get();
        foreach ($user_city as $k_city => $v_city) {

            array_push($user_city_ids,$v_city->city_id);
        }

        $services = DB::table('services')->where('deleted_at',NULL)->get();
        $services_offered = DB::table('services_offered')->join('services','services_offered.service_id','=','services.id')->select('services.*','services_offered.service_id')->where('services_offered.user_id',$userId)->where('services_offered.deleted_at',NULL)->get();
        $serice_ids = array();

        if(isset($services_offered) && !empty($services_offered)) {
            foreach ($services_offered as $key => $value) {
                array_push($serice_ids,$value->service_id);
            }
        }

        //$provinces=DB::table('provinces')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();


        $cities=DB::table('cities')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

        //echo "<pre>"; print_r($user_details); die('con');

         $provinces=DB::table('provinces')->where('status','1')->whereRaw("(deleted_at IS null )")->get();

                $cities=DB::table('cities')->where('status','1')->whereRaw("(deleted_at IS null )")->get()->toArray();
                $arr=array();
                $allData=array();
                if(!empty($provinces))
                {
                  foreach ($provinces as $provience)
                    {
                            $arr['id']=isset($provience) && !empty($provience->id) ? $provience->id : '' ;
                            $arr['name']=isset($provience) && !empty($provience->name) ? $provience->name : '' ;

                          $city=DB::table('cities')->where('status','1')->whereRaw("(province_id = '".$provience->id."' AND deleted_at IS null )")->get();

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


        return view('backend.company.edit_coverage_area',compact('provinces','cities','services','services_offered','serice_ids','userId','whole_country','user_ser_area','user_province_ids','user_city_ids'))->withMixdata($allData);;
    }

    public function updateCoverageArea(request $request) {


        $userId = $request->user_id;
        // $whole_country=!empty($request->whole_country) ? $request->whole_country : '0' ;
        // $proviences=!empty($request->proviences) ? $request->proviences : '' ;
        // $cities=!empty($request->cities) ? $request->cities : '0' ;

        // if(!empty($whole_country) && $whole_country==1)
        // {
        //    DB::table('users_services_area')->where('user_id', '=', $userId)->delete();
        //     $forCountry['user_id'] = $userId;
        //     $forCountry['whole_country'] = $whole_country;
        //     $forCountry['created_at'] = Carbon::now()->toDateTimeString();
        //     $saveforCountry = DB::table('users_services_area')->insert($forCountry);
        // }

        // if(!empty($proviences) && empty($cities))
        // {
        //    DB::table('users_services_area')->where('user_id', '=', $userId)->delete();

        //    foreach($proviences as $key => $value)
        //     {
        //         $forProvince['user_id'] = $userId;
        //         $forProvince['whole_country'] = 0;
        //         $forProvince['province_id']=$value;
        //         $forProvince['created_at'] = Carbon::now()->toDateTimeString();
        //         $saveForProvince = DB::table('users_services_area')->insert($forProvince);
        //     }
        // }

        // if(!empty($cities) && !empty($proviences))
        // {
        //    DB::table('users_services_area')->where('user_id', '=', $userId)->delete();

        //     foreach($proviences as $prov)
        //     {
        //         $forProvince['user_id'] = $userId;
        //         $forProvince['whole_country'] = 0;
        //         $forProvince['province_id']=$prov;
        //         $forProvince['city_id']=NULL;
        //         $forProvince['created_at'] = Carbon::now()->toDateTimeString();
        //         $saveForProvince = DB::table('users_services_area')->insert($forProvince);
        //     }

        //    foreach($cities as $cit)
        //     {
        //         $forProvince['user_id'] = $userId;
        //         $forProvince['whole_country'] = 0;
        //         $forProvince['city_id']=$cit;
        //         $forProvince['province_id']=NULL;
        //         $forProvince['created_at'] = Carbon::now()->toDateTimeString();
        //         $saveForProvince = DB::table('users_services_area')->insert($forProvince);
        //     }
        // }


        // if(!empty($cities) && empty($proviences))
        // {
        //    DB::table('users_services_area')->where('user_id', '=', $userId)->delete();

        //    foreach($cities as $key => $value)
        //     {
        //         $forProvince['user_id'] = $userId;
        //         $forProvince['whole_country'] = 0;
        //         $forProvince['city_id']=$value;
        //         $forProvince['created_at'] = Carbon::now()->toDateTimeString();
        //         $saveForProvince = DB::table('users_services_area')->insert($forProvince);
        //     }
        // }

        $whole_country=!empty($request->whole_country) ? $request->whole_country : '0' ;
        $proviences=!empty($request->proviences) ? $request->proviences : '' ;

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

        return redirect()->back()->with('success','Updated successfully');

        return redirect()->back()->with('success','Updated successfully');

    }


    public function editPaymentMethod($userId) {
        //die('here');
        $paymentMethods = DB::table('payment_methods')->where('deleted_at',NULL)->get();
        $user_payment_methods = DB::table('user_payment_methods')->where('deleted_at',NULL)->where('user_id',$userId)->get();

        $user_pay_menthod_ids = array();
        foreach ($user_payment_methods as $key => $value) {
            array_push($user_pay_menthod_ids,$value->payment_method_id);
        }

        //echo "<pre>"; print_r($user_pay_menthod_ids); die('con');


        return view('backend.company.edit_payment_method',compact('paymentMethods','user_pay_menthod_ids','userId'));
    }

    public function updatePaymentMethods(request $request) {

        if(isset($request->payment_method_id) && !empty($request->payment_method_id))
        {
            DB::table('user_payment_methods')->where('user_id',$request->user_id)->delete();

            foreach($request->payment_method_id as $pay_method)
            {
                $payment_method_arr['status'] = 1;
                $payment_method_arr['user_id'] = $request->user_id;
                $payment_method_arr['payment_method_id'] = $pay_method;
                $payment_method_arr['created_at'] = Carbon::now();
                DB::table('user_payment_methods')->insert($payment_method_arr);
            }

        }

        return redirect()->back()->with('success','Updated successfully');

    }

    public function addCompanyCertificate($user_id) {

        return view('backend.company.add_company_certifications',compact('user_id'));
    }

    public function storeCompanyCertificate(request $request){
         // start certification courses



        $userId = $request->user_id;
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

                    if(!empty($getAll))
                    {
                        //Delete Old

                        // DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '0')->delete();

                        // $deleteOld = $this->delete_directory(public_path() . '/img/contractor/certifications/'.$userId);
                    }

                    $check_folder_exist = public_path() . '/img/company/certifications/'.$userId;

                    if(!is_dir($check_folder_exist)) {

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
                                //$insert['file_type'] = $fileType;
                                $insert['file_type'] = $certification_type;
                                $insert['file_extension'] = $fileType;
                                $insert['is_verified'] = 1;
                                $insert['certification_type'] = '0';
                                $insert['user_id'] = $userId;
                                $insert['status'] = 1;
                                $insert['created_at'] = Carbon::now();
                                DB::table('user_certifications')->insertGetId($insert);
                            }else
                            {
                                 $errorUpload .= 'certification file not uploaded.';
                            }
                        }else {
                                $errorUploadType .='File Type Not Match';
                        }
                    }
                }
            }


            if(!empty($errorUpload))
            {
                return redirect()->route('admin.company.index')->withFlashDanger(__($errorUpload));exit;
            }

            if(!empty($errorUploadType))
            {
                return redirect()->route('admin.company.index')->withFlashDanger(__($errorUploadType));exit;
            }

            if(empty($errorUpload) && empty($errorUploadType)){
                 return redirect()->route('admin.company.index')->with('success','Add Successfully');
            }
        } else {

            return redirect()->route('admin.company.index')->with('success','Please add Files');
        }


      // End certification courses
    }

    function delete_directory($dirname)
    {
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

    public function allCompanyCertificates($user_id)
    { 
        //die('asfa');
        $userId = $user_id;
        $allCertificates=DB::table('user_certifications')->select('id','user_id','file_name','file_type','is_verified','status','created_at')->where('user_id',$userId)->where('certification_type','0')->whereRaw("(deleted_at IS null )")->get()->toArray();
        $cetifications = array();
        $certi2=array();
        //$policeR2=array();
        if(!empty($allCertificates))
        {
            $path='/img/company/certifications/'.$userId.'/';
            foreach ($allCertificates as $key => $value)
            {
                $allImages1['id']=$value->id;
                $allImages1['user_id']=$value->user_id;
                $allImages1['file_name']=url($path.$value->file_name);
                $allImages1['is_verified']=$value->is_verified;
                $allImages1['file_type']=$value->file_type;
                $allImages1['status']=$value->status;
                $allImages1['created_at']=$value->created_at;
                array_push($certi2, $allImages1);
            }

            $cetifications = $certi2;
        }
        else
        {
            $cetifications=[];
        }
        //echo "<pre>"; print_r($cetifications);die;
        //$all_certificates = DB::table('user_certifications')->where('user_id',$user_id)->where('deleted_at',NULL)->get();
        
        return view('backend.company.all_company_certificates',compact('cetifications','user_id'));
    }

    public function addCompanyPoliceRecords($user_id) {
        //die('asdas');

        return view('backend.company.add_company_police_record',compact('user_id'));
    }

    public function storeCompanyPoliceRecords(request $request) {


        $userId = $request->user_id;
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

                    if(!empty($getAll))
                    {
                        //Delete Old
                        // DB::table('user_certifications')->where('user_id', '=', $userId)->where('certification_type', '=', '1')->delete();

                        // $deleteOld = $this->delete_directory(public_path() . '/img/contractor/police_records/'.$userId);
                    }

                    $check_folder_exist = public_path() . '/img/company/police_records/'.$userId;

                    if(!is_dir($check_folder_exist)) {

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
                                //$insert['file_type'] = $fileType;
                                $insert['is_verified'] = 1;
                                $insert['file_type'] = $record_type;
                                $insert['file_extension'] = $fileType;
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
                return redirect()->route('admin.company.index')->withFlashDanger(__($errorUpload));exit;
            }

            if(!empty($errorUploadType))
            {
                return redirect()->route('admin.company.index')->withFlashDanger(__($errorUploadType));exit;
            }

            if(empty($errorUpload) && empty($errorUploadType)){
                 return redirect()->route('admin.company.index')->with('success','add Successfully');
            }
        } else {
            return redirect()->route('admin.company.index')->with('success','Please add File');
        }

    // End Police Record
    }

    public function allCompanyPoliceRecords($user_id)
    {
        //die('allContractorPoliceRecords');
        $userId = $user_id;
        $police_records = array();
        $policeR2=array();
        $allPoliceRec=DB::table('user_certifications')->select('id','user_id','file_name','file_type','is_verified','status','created_at')->where('user_id',$userId)->where('certification_type','1')->whereRaw("(deleted_at IS null )")->get()->toArray();

                 if(!empty($allPoliceRec))
                 {
                   $path='/img/company/police_records/'.$userId.'/';
                    foreach ($allPoliceRec as $key => $value)
                    {
                        $allVideo1['id']=$value->id;
                        $allVideo1['user_id']=$value->user_id;
                        $allVideo1['file_name']=url($path.$value->file_name);
                        $allVideo1['is_verified']=$value->is_verified;
                        $allVideo1['file_type']=$value->file_type;
                        $allVideo1['status']=$value->status;
                        $allVideo1['created_at']=$value->created_at;
                        array_push($policeR2, $allVideo1);
                    }
                   $police_records = $policeR2;
                 }
                 else
                 {
                    $police_records=[];
                 }

        return view('backend.company.all_company_police_records',compact('police_records','user_id'));
    }

    public function addCompanyGallery($user_id) {
        //die('addContractorGallery');
         return view('backend.company.add_company_gallery',compact('user_id'));
    }

    public function storeCompanyGallery(request $request) {
       //die('storeContractorGallery');

       $userId= $request->user_id;
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
                if(!empty($getAll))
                {
                    //Delete Old
                    // DB::table('users_images_gallery')->where('user_id', '=', $userId)->delete();

                    // $deleteOld = $this->delete_directory(public_path() . '/img/contractor/gallery/images/'.$userId);
                }

                $check_folder_exist = public_path() . '/img/company/gallery/images/'.$userId;

                if(!is_dir($check_folder_exist)) {

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
        return redirect()->route('admin.company.index')->withFlashDanger(__($errorUpload));exit;
        }
         if(!empty($errorUploadType))
        {
        return redirect()->route('admin.company.index')->withFlashDanger(__($errorUploadType));exit;
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
                if(!empty($getAll))
                {
                    //Delete Old
                    // DB::table('users_videos_gallery')->where('user_id', '=', $userId)->delete();

                    // $deleteOld = $this->delete_directory(public_path() . '/img/contractor/gallery/videos/'.$userId);
                }

                $check_folder_exist = public_path() . '/img/company/gallery/videos/'.$userId;

                if(!is_dir($check_folder_exist)) {

                    //crete new folder
                     mkdir(public_path() . '/img/company/gallery/videos/'.$userId, 0777, true);
                }


                //create new folder


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
        return redirect()->route('admin.company.index')->withFlashDanger(__($errorUpload));exit;
        }
        if(!empty($errorUploadType))
        {
        return redirect()->route('admin.company.index')->withFlashDanger(__($errorUploadType));exit;
        }
                // Add Gallery Videos

        return redirect()->back();

    }

    public function allCompanyImagesGallery($userId) {
        //die('allContractorImagesGallery');
        //$userId = $user_id;
        $allImages=DB::table('users_images_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();
        $allImages2=array();
        $gallery = array();

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

            $gallery = $allImages2;
        }
         else
        {
            $gallery=[];
        }


        return view('backend.company.all_company_images_gallery', compact('gallery','userId'));
    }

    public function allCompanyVideosGallery($userId) {

        //die('allContractorVideosGallery');

        $allVideos=DB::table('users_videos_gallery')->select('id','user_id','file_name','file_type','status','created_at')->where('user_id',$userId)->whereRaw("(deleted_at IS null )")->get()->toArray();
        $allVideo2=array();
        $gallery = array();

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
               $gallery = $allVideo2;
        } else {
            $gallery=[];
        }

        return view('backend.company.all_company_videos_gallery',compact('gallery','userId'));
    }

    public function verifyCertificate($certificate_id) {

        $old_detail = DB::table('user_certifications')->where('id',$certificate_id)->first();

        if(isset($old_detail) > 0) {
            if($old_detail->is_verified == 1) {

                $update_arr['is_verified'] = 0;
            }else {
                $update_arr['is_verified'] = 1;
            }

            $update_arr['updated_at'] = Carbon::now();
            DB::table('user_certifications')->where('id',$certificate_id)->update($update_arr);
            //return redirect()->route('admin.contractors.all_contractor_certificates',$old_detail->user_id)->with('success','Succefully updated');
            return redirect()->back();
        } else{

            return redirect()->back();
        }

        //return redirect()->back();
    }

    public function deleteCertificate($certificate_id) {

        $update_arr['deleted_at'] = Carbon::now();
        $update_arr['updated_at'] = Carbon::now();
        DB::table('user_certifications')->where('id',$certificate_id)->update($update_arr);
        return redirect()->back();

        //return redirect()->route('admin.contractors.all_contractor_certificates',$old_detail->user_id)->with('success','Succefully updated');

    }

    public function deleteGalleryImage($id) {

        $update_arr['deleted_at'] = Carbon::now();
        $update_arr['updated_at'] = Carbon::now();
        DB::table('users_images_gallery')->where('id',$id)->update($update_arr);
        return redirect()->back();

        //return redirect()->route('admin.contractors.all_contractor_certificates',$old_detail->user_id)->with('success','Succefully updated');

    }

    public function deleteGalleryVideo($id) {

        $update_arr['deleted_at'] = Carbon::now();
        $update_arr['updated_at'] = Carbon::now();
        DB::table('users_videos_gallery')->where('id',$id)->update($update_arr);
        return redirect()->back();

        //return redirect()->route('admin.contractors.all_contractor_certificates',$old_detail->user_id)->with('success','Succefully updated');

    }


     public function serviceRequests($user_id) {


    $service_requests = DB::table('service_request')
        ->join('category','service_request.category_id','=','category.id')
        ->join('services','service_request.service_id','=','services.id')
        ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name')
        ->where('service_request.user_id',$user_id)
        ->paginate(25);

      return view('backend.company.service_request',compact('service_requests','user_id'));
    }

    public function allRequestsByStatus($status,$user_id)
    {
        if($status == 'all') {

            $service_requests = DB::table('service_request')
            ->join('category','service_request.category_id','=','category.id')
            ->join('services','service_request.service_id','=','services.id')
            ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
            ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
            ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name')
            //->paginate(25);
            ->where('service_request.user_id',$user_id)
            ->get();

        } else {
            $service_requests = DB::table('service_request')
            ->join('category','service_request.category_id','=','category.id')
            ->join('services','service_request.service_id','=','services.id')
            ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
            ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
            ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name')
            ->where('service_request.status',$status)
            ->where('service_request.status',$user_id)
            ->paginate(25);
        }


        return view('backend.company.showservice_status',compact('service_requests','user_id'));

    }

       public function showServiceRequest($request_id)
     {

       $show_service = DB::table('service_request')
        ->join('services','service_request.service_id','=','services.id')
        ->leftjoin('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('category','service_request.category_id','=','category.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->select('service_request.*','sub_services.en_name as en_subservice_name','sub_services.es_name as es_subservice_name','services.en_name as en_service_name','services.es_name as es_service_name','category.en_name as en_category_name','child_sub_services.es_name as es_child_subservice_name')
        ->where('service_request.id',$request_id)
        ->first();


        if($show_service) {

            $show_service->question_detail = DB::table('service_request_questions')
            ->join('questions','service_request_questions.question_id','=','questions.id')
            ->join('question_options','service_request_questions.option_id','=','question_options.id')
            ->select('service_request_questions.*','questions.en_title as en_question_title','questions.es_title as es_question_title','question_options.en_option as en_option_name','question_options.es_option as es_option_name')
            ->where('service_request_questions.deleted_at',NULL)
            ->where('service_request_questions.service_request_id',$request_id)
            ->get();

      }


       $user_details = DB::table('buy_requested_services')
            ->join('users','buy_requested_services.user_id','=','users.id')
            ->select('users.username','buy_requested_services.amount','buy_requested_services.tranx_id')
            ->where('buy_requested_services.requested_service_id',$request_id)
            ->get();

      return view('backend.company.show_service_request',compact('show_service','user_details'));

    }

      public function getDeleted() {

     $users = DB::table('users')->latest()->whereNotNull('deleted_at')
             ->where('user_group_id',4)->paginate(25);

        return view('backend.company.deleted',compact('users'));
    }


     public function restore($user_id) {

        $restore_arr['updated_at'] = Carbon::now();
        $restore_arr['deleted_at'] = null;

        $restore_contractor = DB::table('users')->where('id',$user_id)->update($restore_arr);

       return redirect()->route('admin.company.index')->with('success','Company restored successfully.');
   }

    public function delete($user_id)
    {

         DB::table('users')->where('id', '=', $user_id)->delete();


        return redirect()->route('admin.company.index')->with('success','Company deleted successfully.');
    }
    public function creditPackage($id=null)
    {
        $id=$id;
        $packages= DB::table('package')->get();
        return view('backend.package.credit1',compact('id','packages'));
    }

    public function creditPackageStore(Request $request)
    {
        $checkpackeage=DB::table('package')->where('id',$request->package_id)->first();

        if(!empty($checkpackeage))
        {
            $datainsert=array('user_id'=>$request->user_id,'trans_id'=>$request->trans_id,'amount'=>$request->price,'payment_type'=>'online','credits'=>$checkpackeage->credit,'package_id'=>$checkpackeage->id,'status'=>'success');
                DB::table('payment_history')->insert($datainsert);
                $user= DB::table('users')->where('id',$request->user_id)->first();
                $newamount=  $user->pro_credit+$checkpackeage->credit;
                DB::table('users')->where('id',$request->user_id)->update(['pro_credit'=> $newamount]);
            return redirect()->route('admin.company.index')->withFlashSuccess('Package create successfully');
        }else
        {
            return redirect()->route('admin.company.index')->withFlashDanger('Package  Not match');
        }
    }

}