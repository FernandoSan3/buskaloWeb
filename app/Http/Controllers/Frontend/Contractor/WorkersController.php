<?php

namespace App\Http\Controllers\Frontend\Contractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
/**
 * Class WorkersController.
 */
class WorkersController extends Controller
{
    public function index()
    {
       
        $userId= auth()->user()->id;
        $workers = DB::table('workers')->whereRaw("(user_id = '".$userId."')")->whereRaw("(status = 1)")->whereRaw("(deleted_at IS NULL)")->get();

        return view('frontend.contractor.workers')->withWorkers($workers);
    }


    public function createWorker(Request $request)
    {
            $profile_pic = isset($request->profile_pic) && !empty($request->profile_pic) ? $request->profile_pic : '' ;
            $username = !empty($request->username) ? $request->username : '' ;
            $email = !empty($request->email) ? $request->email : '' ;
            $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
            $address = !empty($request->address) ? $request->address : '' ;

       	
		         $userid= auth()->user()->id;

                $validator = Validator::make($request->all(), [
                'username' => 'required',
                'mobile_number' => 'required',
                'profile_pic' => 'required',
                'email' => 'required',
                'address' => 'required',
                ]);

                if($validator->fails())
                {
                	return redirect()->route('frontend.contractor.workers')->withFlashDanger(__('Invalid parameter.'));
                }

                  	 $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")->whereRaw("(confirmed=1)")->whereRaw("(is_verified=1)")
                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();  

                    if(!empty($userEntity))
                    {

                        $workerdata['username'] =  $username;
                        $workerdata['user_id'] =  $userEntity->id;
                        $workerdata['mobile_number'] =  $mobile_number;
                        $workerdata['email'] =  $email;
                        $workerdata['address'] =  $address;
                        $workerdata['created_at'] = Carbon::now()->toDateTimeString();
                        $workerdata['updated_at'] = Carbon::now()->toDateTimeString();

                        $workerId=DB::table('workers')->insertGetId($workerdata);

                        if($workerId)
                    	 {
                            $profile = "";

                        if(isset($_FILES['profile_pic']['name']) && !empty($_FILES['profile_pic']['name']))
                            {
                                $extq = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                $filename = $workerId.'.'.$extq;

                                $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
                                
                                $fmove = move_uploaded_file($_FILES['profile_pic']['tmp_name'],public_path() . '/img/worker/profile/'.$filename);
                                
                                 $profile = $filename;

                                $wdata['profile_pic'] =  $profile;
                                DB::table('workers')->where('id',$workerId)->update($wdata);
                            }
                        }


						return redirect()->route('frontend.contractor.workers')->withFlashSuccess(__('alerts.frontend.constractor.workers.profile_created_successfully')); 
                    }
                    else
                    {
                      return redirect()->route('frontend.contractor.workers')->withFlashDange(__('alerts.frontend.constractor.workers.invalid_user')); 

                    }
    }
}
