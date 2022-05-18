<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\WorkWithUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class WorkWithUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $work_with_us = DB::table('work_with_us')->where('id', '1')->first();
        
        return view('backend.work_with_us.index',compact('work_with_us'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function updateWorkWithUs(Request $request)
    {
       
       $updateArr['description_cons'] = $request->description_cons;
       $updateArr['description_comp'] = $request->description_comp;
       $updateArr['updated_at'] = Carbon::now();
       $newdata = DB::table('work_with_us')->update($updateArr);
      
       return redirect()->route('admin.work_with_us.index')->with('success','updated successfully.');
    }
}