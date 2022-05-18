<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\PriceRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class PriceRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $price_ranges = PriceRange::latest()->where('deleted_at',NULL)->paginate(25);
        // return view('backend.services.index',compact('services'))
        //     ->with('i', (request()->input('page', 1) - 1) * 5);
        return view('backend.price_range.index',compact('price_ranges'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.price_range.create');
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
            'start_price' => 'required',
            'end_price' => 'required',
            'percentage' => 'required',
        ]);

        $insert_arr['start_price'] = $request->start_price;
        $insert_arr['end_price'] = $request->end_price;
        $insert_arr['percentage'] = $request->percentage;
        $insert_arr['created_at'] = Carbon::now();

        $price_range_id = DB::table('price_range')->insertGetId($insert_arr);
   
        return redirect()->route('admin.price_range.index')
                        ->with('success','price range created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function show(Services $services)
    {
        return view('backend.services.show',compact('services'));
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function edit($range_id)
    {   
        
        $price_range = PriceRange::find($range_id);
        return view('backend.price_range.edit',compact('price_range'));
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
            'price_range_id' => 'required',
            'start_price' => 'required',
            'end_price' => 'required',
            'percentage' => 'required',
        ]);

        $service = PriceRange::find($request->price_range_id);

        $updateArr['start_price'] = $request->start_price;
        $updateArr['end_price'] = $request->end_price;
        $updateArr['percentage'] = $request->percentage;
        $updateArr['updated_at'] = Carbon::now();
        $services = PriceRange::where('id',$request->price_range_id)->update($updateArr);

        
        return redirect()->route('admin.price_range.index')
                        ->with('success','Price range updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy($range_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();

        $services = PriceRange::where('id',$range_id)->update($updateArr);

  
        return redirect()->route('admin.price_range.index')->with('success','Price Range deleted successfully');
    }
}