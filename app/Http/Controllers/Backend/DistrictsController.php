<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subservices; 
use App\Models\services;
use App\Models\Cities;
use App\Models\Districts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class DistrictsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $districts = Districts::join('cities','districts.city_id','=','cities.id')
        ->select('cities.name as city_name','districts.*')        
        ->where('districts.deleted_at',NULL)
        ->orderBy('districts.id','DESC')
        ->paginate(5);       
        return view('backend.districts.index',compact('districts'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $cities = Cities::all()->where('deleted_at',NULL);
        return view('backend.districts.create',compact('cities'));
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
            'city_id' => 'required',
            'name' => 'required',
            'zipcode' => 'required',
        ]);

        
        $insert_arr['city_id'] = $request->city_id;
        $insert_arr['name'] = $request->name;
        $insert_arr['zipcode'] = $request->zipcode;
        $insert_arr['status'] = 1;
        $insert_arr['created_at'] = Carbon::now();

        $districts_id = DB::table('districts')->insertGetId($insert_arr);
   
        return redirect()->route('admin.districts.index')
                        ->with('success','district created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function show(Services $services)
    {
        return view('backend.sub_services.show',compact('services'));
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function edit($district_id)
    {   
        
        $districts = Districts::find($district_id);
        return view('backend.districts.edit',compact('districts'));
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
            'district_id' => 'required',
            'name' => 'required',
            'zipcode' => 'required',
        ]);
        
        $updateArr['name'] = $request->name;
        $updateArr['zipcode'] = $request->zipcode;
        $updateArr['updated_at'] = Carbon::now();
        $district = Districts::where('id',$request->district_id)->update($updateArr);

        return redirect()->route('admin.districts.index')
                        ->with('success','Districts updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy($distrisct_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();

        $districts = Districts::where('id',$distrisct_id)->update($updateArr);

  
        return redirect()->route('admin.districts.index')->with('success','Districts deleted successfully');
    }
}