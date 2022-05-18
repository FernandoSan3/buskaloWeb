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

            $users = User::where('id', '!=', Auth::user()->id)
            ->where('id', '!=', 1)
            ->where('id', '!=', 3)
            ->where('user_group_id', '!=', 1)
            ->where('user_group_id', '!=',2)
            ->where('users.deleted_at', '=', NULL)
            ->get();

        return view('frontend.user.chats', compact('users'));
    }
}