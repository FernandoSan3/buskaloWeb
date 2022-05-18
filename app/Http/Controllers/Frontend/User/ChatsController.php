<?php
 
namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;

 
class ChatsController extends Controller
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
                    ->leftjoin('assign_service_request','assign_service_request.user_id','=','users.id')
                    ->where('assign_service_request.request_status','buy')
                    ->where('assign_service_request.hire_status',1)
                    ->where('users.id', '!=', 1)
                    ->where('users.id', '!=', 3)
                    ->where('users.user_group_id', '!=', 1)
                    ->where('users.user_group_id', '!=',2)
                    ->where('users.deleted_at', '=', NULL)
                    ->groupBy('assign_service_request.user_id')
                    ->select('users.*')
                    ->get();
                //echo '<pre>';print_r($users);exit;
        return view('frontend.user.chats', compact('users'));
    }
}