<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
    	$userGroupId= auth()->user()->user_group_id;

        //Company
        if($userGroupId==4)
        {
            return redirect()->route('frontend.company.company_profile');
            
        }
        //Contractor(Professional)
        else if($userGroupId==3)
        {
            
            return redirect()->route('frontend.contractor.my-profile');
            //return view('frontend.contractor.profile');
           
        }
        else
        {
             $userId= auth()->user()->id;
             $userdata=DB::table('users')->select('users.id','users.identity_no','users.user_group_id','users.email','users.username','users.profile_title','users.address','users.dob','users.office_address','users.other_address','users.mobile_number','users.landline_number','users.office_number','users.is_verified','users.active','avatar_location','profile_description','created_at','updated_at')->where('id',$userId)->first();

                 $social = DB::table('social_networks')->whereRaw("(user_id = '".$userId."')")->first();

            return view('frontend.user.dashboard')->withUser($userdata)->withSocial($social);
        }
        
    }
}
