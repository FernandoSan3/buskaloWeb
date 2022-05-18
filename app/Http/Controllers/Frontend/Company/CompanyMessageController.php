<?php

namespace App\Http\Controllers\Frontend\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
/**
 * Class MessageController.
 */
class CompanyMessageController extends Controller
{
      public function index()
    {
          $users = User::where('id', '!=', Auth::user()->id)
            ->where('id', '!=', 1)
            ->where('id', '!=', 3)
            ->where('users.deleted_at', '=', NULL)
            ->get();

          $messages = DB::table('messages')
         ->where('messages.to_user',Auth::user()->id)
         ->orWhere('messages.from_user',Auth::user()->id)
         ->whereRaw("(messages.deleted_at IS null )")
         ->groupBy('messages.from_user')->orderBy('messages.created_at', 'DESC')->get();

        //return view('frontend.chats', compact('users'));

         if(Auth::user()->role_id==4)
         {
            //return view('frontend.program-manager.chats',compact('users','messages'));
            return view('frontend.company.company_profile.message',compact('users','messages'));
         }
         else if(Auth::user()->role_id==6)
         {
            //return view('frontend.mentor.chats', compact('users','messages'));
            return view('frontend.company.company_profile.message',compact('users','messages'));
         }

    }

}