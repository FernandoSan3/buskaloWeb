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

/**
 * Class ContractorChatsController.
 */
class ContractorChatsController extends Controller
{
      public function index()
    {
          $users = User::where('id', '!=', Auth::user()->id)
            ->where('id', '!=', 1)
            ->where('id', '!=', 3)
            ->where('users.deleted_at', '=', NULL)
            ->get();

             $userdata = DB::table('users')->whereRaw("(id = '".Auth::user()->id."')")->first(); 
       
             //  $messages = DB::table('messages')
             // ->where('messages.to_user',Auth::user()->id)
             // ->orWhere('messages.from_user',Auth::user()->id)
             // ->whereRaw("(messages.deleted_at IS null )")
             // ->groupBy('messages.from_user')->orderBy('messages.created_at', 'DESC')->get(); 

            return view('frontend.contractor.chats',compact('users','userdata'));
         
         
       
    }

}