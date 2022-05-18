<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $services = Services::leftjoin('category','services.category_id','=','category.id')
        ->latest()
        ->select('services.*','category.es_name as category_name')
        ->where('services.deleted_at',NULL)
        ->orderBy('services.id','DESC')
        ->get();
        
        //echo "<pre>"; print_r($services->toArray()); die;
        // return view('backend.services.index',compact('services'))
        //     ->with('i', (request()->input('page', 1) - 1) * 5);
        return view('backend.services.index',compact('services'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categories = Category::all()->where('deleted_at',NULL);
        return view('backend.services.create',compact('categories'));
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
            'en_name' => 'required',
            'es_name' => 'required',
            'category_id' => 'required',
            //'price' => 'required|numeric',
        ]);
        
        $imagename=""; $storeName="";
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/img/services');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'services/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                if(file_exists(public_path().$destinationPath.$imagename))
                {
                    unlink(public_path().$destinationPath.$imagename);
                    $image->move($destinationPath, $imagename);
                }
                 else
                 {
                    $image->move($destinationPath, $imagename); 
                } 
            }
        }

        $insert_arr['en_name'] = $request->en_name;
        $insert_arr['es_name'] = $request->es_name;
        $insert_arr['category_id'] = $request->category_id;
        $insert_arr['image'] = $storeName;
        $insert_arr['price'] = isset($request->price)?$request->price:0;
        $insert_arr['status'] = 1;
        $insert_arr['created_at'] = Carbon::now();

        $services_id = DB::table('services')->insertGetId($insert_arr);
   
        return redirect()->route('admin.services.index')
                        ->with('success','Services created successfully.');
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
    public function edit($service_id)
    {   
        
        $categories = Category::all()->where('deleted_at',NULL);
        $service = Services::find($service_id);
        return view('backend.services.edit',compact('service','categories'));
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
        //dd('aa');
        $request->validate([
            'service_id' => 'required',
            'en_name' => 'required',
            'es_name' => 'required',
            //'price' => 'required|numeric',
        ]);

        $service = Services::find($request->service_id);


        $imagename=""; $storeName=$service->image;
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/img/services');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'services/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                if(file_exists(public_path().$destinationPath.$imagename))
                {
                    unlink(public_path().$destinationPath.$imagename);
                    $image->move($destinationPath, $imagename);
                }
                 else
                 {
                    $image->move($destinationPath, $imagename); 
                } 
            }
        }


        $updateArr['en_name'] = $request->en_name;
        $updateArr['es_name'] = $request->es_name;
        $updateArr['price'] = isset($request->price)?$request->price:0;
        $updateArr['image'] = $storeName;
        $updateArr['updated_at'] = Carbon::now();
        $services = Services::where('id',$request->service_id)->update($updateArr);

       
        //$services->update($request->all());
  
        return redirect()->route('admin.services.index')
                        ->with('success','Services updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy($service_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();

        $services = Services::where('id',$service_id)->update($updateArr);

  
        return redirect()->route('admin.services.index')->with('success','Services deleted successfully');
    }

    public function removeImage(Request $request)
    {
        $service=Services::where('id', $request->id)->first();
            $image="";
            $findinfolder="";
            if(isset($service->image))
            {
                $image=$service->image;
                $findinfolder=public_path().'/img/'.$service->image;
            }
            if(file_exists($findinfolder) && !empty($image)) 
            {   
                unlink($findinfolder);
                Services::where('id', $request->id)->update(['image'=>'']);
                echo "yes";
            }
    }
}