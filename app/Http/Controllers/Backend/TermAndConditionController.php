<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TermAndCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class TermAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terms_and_condition = DB::table('term_and_condition')->where('id', '1')->first();
        
        return view('backend.terms_and_condition.index',compact('terms_and_condition'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function updateTermAndCondition(Request $request)
    {
       
       $updateArr['description_cons'] = $request->description_cons;
       $updateArr['description_comp'] = $request->description_comp;
       $updateArr['description_user'] = $request->description_user;
       $updateArr['description_purchase'] = $request->description_purchase;
       $updateArr['updated_at'] = Carbon::now();
       $newdata = DB::table('term_and_condition')->update($updateArr);

       return redirect()->route('admin.terms_and_condition.index')->with('success','updated successfully.');
    }
}