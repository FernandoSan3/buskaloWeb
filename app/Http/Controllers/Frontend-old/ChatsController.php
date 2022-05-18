<?php
 
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB, Redirect, Response, Session;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

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

          $userid= auth()->user()->id;

          $users =  DB::table('users')
            ->where('id', '!=', $userid)
            ->where('id', '!=', 1)
            ->where('id', '!=', 2)
            ->where('user_group_id', '=', 2)
            ->where('users.deleted_at', '=', NULL)
            ->where('users.confirmed', '=', 1)
            ->where('users.active', '=', 1)
            ->get();
       
          $messages = DB::table('messages')
         ->where('messages.to_user',$userid)
         ->orWhere('messages.from_user',$userid)
         ->whereRaw("(messages.deleted_at IS null )")
         ->groupBy('messages.from_user')->orderBy('messages.created_at', 'DESC')->get(); 


         return view('frontend.chats', compact('users','messages'));
       
    }
}