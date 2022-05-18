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
 * Class ServicesController.
 */
class ServicesController extends Controller
{
    public function index()
    {

    	 ///////////////////////services offered/////////////////

                $userid= auth()->user()->id;
                $lang = 'en' ;

                        if(!empty($userid)) 
                            {
	                            $srname="";$ssubrname="";
	                            if($lang=='es')
	                            {
	                                $srname='services.es_name AS service_name';
	                                $ssubrname='sub_services.es_name AS sub_service_name';
	                            }
	                            else
	                            {
	                                $srname='services.en_name AS service_name';
	                                $ssubrname='sub_services.en_name AS sub_service_name';
	                            }

	                             $servicesOffered = DB::table('services_offered')
	                            ->leftjoin('services', 'services_offered.service_id', '=', 'services.id')
	                            ->leftjoin('sub_services', 'services_offered.sub_service_id', '=', 'sub_services.id')
	                            ->select('services_offered.id','services_offered.user_id','services_offered.service_id',$srname,'services_offered.sub_service_id',$ssubrname,'services_offered.created_at')
	                            ->where('services_offered.user_id',$userid)->whereRaw("(services_offered.deleted_at IS null )")->orderBy('created_at', 'ASC')->groupBy('services_offered.service_id')->get()->toArray();

	                            if(!empty($servicesOffered))
	                            {
	                                    $data1=array();
	                                    $allData=array();
	                                    foreach ($servicesOffered as $key => $vall) 
	                                    {
	                                        $data1['id'] = $vall->id;
	                                        $data1['service_id'] = !empty($vall->service_id) ? $vall->service_id : '' ;
	                                        $data1['service_name'] = !empty($vall->service_name) ? $vall->service_name : '' ;
	                                        $data1['created_at'] =  $vall->created_at;
	                                      
	                                        $subServicesOffered = DB::table('services_offered')
	                                        ->leftjoin('sub_services', 'services_offered.sub_service_id', '=', 'sub_services.id')
	                                        ->select('services_offered.sub_service_id',$ssubrname)
	                                        ->where('services_offered.user_id',$userid)
	                                        ->where('services_offered.service_id',$vall->service_id)
	                                        ->whereRaw("(services_offered.deleted_at IS null )")
	                                        ->get()->toArray();

	                                        $options=array();
	                                        foreach ($subServicesOffered as $key => $vvalue) 
	                                        {
	                                            if(!empty($vvalue->sub_service_id) && !empty($vvalue->sub_service_name))
	                                            {
	                                                $data2['sub_service_id'] =  !empty($vvalue->sub_service_id) ? $vvalue->sub_service_id : '' ;
	                                                $data2['sub_service_name'] =  !empty($vvalue->sub_service_name) ? $vvalue->sub_service_name : '' ;

	                                                array_push($options, $data2);
	                                            }
	                                        }
	                                        $data1['sub_services']=$options;
	                                        array_push($allData, $data1);
	                                    }

	                                    return view('frontend.contractor.services')->withData($allData);
	                             }
	                             else
	                             {
	                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Services not found.!'));
	                             }

                            }
                            //End Contractor
                            else
                            {
                        
                                return redirect()->route('frontend.contractor.my-profile')->withFlashDanger(__('Invalid user.!'));
                            }


                    }

                   
        ///////////////////////services offered/////////////////


}