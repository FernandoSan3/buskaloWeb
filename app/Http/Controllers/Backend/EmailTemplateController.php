<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('backend.email_template.index');
    }

    public function create()
    {
        return view('backend.email_template.create');
    }

    public function store(Request $request)
    {
        
        $insert_arr['slug'] = $request->slug;
        $insert_arr['subject'] = $request->subject;
        $insert_arr['mail_content'] = $request->mail_content;
        $insert_arr['status'] = 1;
        $insert_arr['created_at'] = Carbon::now();
        //dd($insert_arr);
        $email_template = DB::table('email_template')->insertGetId($insert_arr);

        return redirect()->route('admin.email_template.index')
                        ->with('success','Email Template created successfully.');
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  
}