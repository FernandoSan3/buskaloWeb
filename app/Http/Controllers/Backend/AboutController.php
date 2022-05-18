<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $about = DB::table('about_us')->where('id', '1')->first();
        
        return view('backend.about_us.index',compact('about'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function updateabout(Request $request)
    {
       //dd($request->all());
       $updateArr['description_cons'] = $request->description_cons;
       $updateArr['description_comp'] = $request->description_comp;
       $updateArr['updated_at'] = Carbon::now();
       $newdata = DB::table('about_us')->update($updateArr);
      
       return redirect()->route('admin.about_us.index')->with('success','updated successfully.');
    }
}