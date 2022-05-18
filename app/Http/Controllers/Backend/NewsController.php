<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = DB::table('news')->where('id', '1')->first();
        
        return view('backend.news.index',compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function updatenews(Request $request)
    {
       //dd($request->all());
       $updateArr['description'] = $request->description;
       $updateArr['updated_at'] = Carbon::now();
       $newdata = DB::table('news')->update($updateArr);
      
       return redirect()->route('admin.news.index')->with('success','updated successfully.');
    }
}