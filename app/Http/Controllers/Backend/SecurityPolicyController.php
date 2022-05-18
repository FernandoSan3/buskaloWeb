<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SecurityPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class SecurityPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $security_policy = DB::table('security_policy')->where('id', '1')->first();
        
        return view('backend.review_payment_security_policies.index',compact('security_policy'));
    }

    public function updatepolicies(Request $request)
    {
       //dd($request->all());
       $updateArr['description_cons'] = $request->description_cons;
       $updateArr['description_comp'] = $request->description_comp;
       $updateArr['updated_at'] = Carbon::now();
       $newdata = DB::table('security_policy')->update($updateArr);
      
       return redirect()->back()->with('success','updated successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    
}