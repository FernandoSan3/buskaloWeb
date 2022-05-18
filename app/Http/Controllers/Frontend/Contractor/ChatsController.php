<?php
 
namespace App\Http\Controllers\Frontend\Contractor;

use App\Http\Controllers\Controller;
use App\Models\Access\Role\Role;
use App\Models\Access\User\User;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB, Redirect, Response, Session;
use App\Models\Access\User\UserProfile;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
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
          $users = User::where('id', '!=', Auth::user()->id)
            ->where('id', '!=', 1)
            ->where('id', '!=', 3)
            ->where('users.deleted_at', '=', NULL)
            ->where('users.confirmed', '=', 1)
            ->where('users.status', '=', 1)
            ->get();
       
          $messages = DB::table('messages')
         ->where('messages.to_user',Auth::user()->id)
         ->orWhere('messages.from_user',Auth::user()->id)
         ->whereRaw("(messages.deleted_at IS null )")
         ->groupBy('messages.from_user')->orderBy('messages.created_at', 'DESC')->get(); 

        //return view('frontend.chats', compact('users'));

         if(Auth::user()->role_id==4)
         {
            return view('frontend.program-manager.chats', compact('users','messages'));
         }
         else if(Auth::user()->role_id==6)
         {
            return view('frontend.mentor.chats', compact('users','messages'));
         }
       
    }
}