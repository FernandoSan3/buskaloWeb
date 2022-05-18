<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\Models\Areatype;

use Carbon\Carbon;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $area_details = Areatype::all();
     // echo "<pre>";print_r($area_details->toArray());die;
        return view('backend.area.index',compact('area_details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function updateAreapricePercentt(Request $request)
    {

          foreach ($request->price_percentage as $key => $percentvalue){
          $updateArr['price_percent'] = $percentvalue;
          $updateArr['updated_at'] = Carbon::now();

          $user_id = $request->id;
          $newdata = DB::table('area_type')->where('id',$user_id[$key])->update($updateArr);
          }

     return redirect()->route('admin.area.index')->with('success',' updated successfully.');
    }

    public function updateAreapricePercent(Request $request){

       $area_id = $request->area_type_id;

       $updateArr['low_resources_area_1']=$request->low_resources_area_1;
       $updateArr['low_resources_area_2'] = $request->low_resources_area_2;
       $updateArr['avg_resources_type'] = $request->avg_resources_type;
       $updateArr['high_resources_type_1'] = $request->high_resources_type_1;
       $updateArr['high_resources_type_2'] = $request->high_resources_type_2;
       $updateArr['updated_at'] = Carbon::now();


        $newdata = DB::table('area_type')->where('id',$area_id)->update($updateArr);

     return redirect()->route('admin.area.index')->with('success',' updated successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */

}