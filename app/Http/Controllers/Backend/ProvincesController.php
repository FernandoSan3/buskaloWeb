<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\Cities;
use App\Models\Provinces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class ProvincesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $provinces = provinces::latest()->where('deleted_at',NULL)->paginate(25);
        return view('backend.provinces.index',compact('provinces'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.provinces.create');
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);        
        
        $insert_arr['name'] = $request->name;
        $insert_arr['status'] = 1;
        $insert_arr['created_at'] = Carbon::now();

        $city_id = DB::table('provinces')->insertGetId($insert_arr);   
        return redirect()->route('admin.provinces.index')->with('success','Province created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function show(Services $services)
    {
        //return view('backend.services.show',compact('services'));
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function edit($province_id)
    {   
        
        $province = Provinces::find($province_id);
        return view('backend.provinces.edit',compact('province'));
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'province_id' => 'required',
        ]);       

        $updateArr['name'] = $request->name;
        $updateArr['updated_at'] = Carbon::now();
        $services = Provinces::where('id',$request->province_id)->update($updateArr);       
        return redirect()->route('admin.provinces.index')->with('success','Province updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy($province_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();
        $services = Provinces::where('id',$province_id)->update($updateArr); 
        return redirect()->route('admin.provinces.index')->with('success','Province deleted successfully');
    }
}