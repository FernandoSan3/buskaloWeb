<?php
 
namespace App\Http\Controllers\Frontend\Contractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;

 date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
class ContractorChatsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $users = User::where('users.id', '!=', Auth::user()->id)
                ->leftjoin('service_request','service_request.user_id','=','users.id')
                ->leftjoin('assign_service_request','assign_service_request.service_request_id','=','service_request.id')
                ->where('assign_service_request.request_status','buy')
                ->where('assign_service_request.user_id',Auth::user()->id)
                ->where('users.id', '!=', 1)
                ->where('users.id', '!=', 3)
                ->where('users.user_group_id', '!=', 1)
                ->where('users.user_group_id', '!=',3)
                ->where('users.user_group_id', '!=',4)
                ->where('users.deleted_at', '=', NULL)
                ->groupBY('service_request.user_id')
                ->select('users.*')
                ->get();
            //echo '<pre>'; print_r($users);exit;
        return view('frontend.contractor.chats', compact('users'));
    }
     public function companyChat()
    {
            $users = User::where('users.id', '!=', Auth::user()->id)
                ->leftjoin('service_request','service_request.user_id','=','users.id')
                ->leftjoin('assign_service_request','assign_service_request.service_request_id','=','service_request.id')
                ->where('assign_service_request.request_status','buy')
                ->where('assign_service_request.user_id',Auth::user()->id)
                ->where('users.id', '!=', 1)
                ->where('users.id', '!=', 3)
                ->where('users.user_group_id', '!=', 1)
                ->where('users.user_group_id', '!=',3)
                ->where('users.user_group_id', '!=',4)
                ->where('users.deleted_at', '=', NULL)
                ->groupBY('service_request.user_id')
                ->select('users.*')
                ->get();
            //echo '<pre>'; print_r($users);exit;
        return view('frontend.company.chats', compact('users'));
    }


}