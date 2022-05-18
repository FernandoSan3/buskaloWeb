<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subservices;
use App\Models\Services;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class SubservicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $sub_services = Subservices::leftjoin('services','sub_services.services_id','=','services.id')
        ->leftjoin('category','sub_services.category_id','=','category.id')
        ->select('services.en_name as service_name_en','services.es_name as service_name_es','sub_services.*','category.es_name as category_name')
        ->where('sub_services.deleted_at',NULL)
        ->orderBy('sub_services.id','DESC')
        ->get();       
        return view('backend.sub_services.index',compact('sub_services'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $categories = Category::all()->where('deleted_at',NULL);
        $services = Services::all()->where('deleted_at',NULL);
        return view('backend.sub_services.create',compact('services','categories'));
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
            'category_id' => 'required',
            'services_id' => 'required',
            'price' => 'required',
            'en_name' => 'required',
            'es_name' => 'required',            
        ]);

        //echo "<pre>"; print_r($request->all()); die();

         $imagename=""; $storeName="";
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/img/subservices');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'subservices/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
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

        $insert_arr['category_id'] = $request->category_id;
        $insert_arr['services_id'] = $request->services_id;
        $insert_arr['en_name'] = $request->en_name;
        $insert_arr['es_name'] = $request->es_name;
        $insert_arr['image'] = $storeName;
        $insert_arr['price'] = $request->price;
        $insert_arr['percentage'] = $request->percentage;
        $insert_arr['quantity'] = $request->quantity;
        $insert_arr['status'] = 1;
        $insert_arr['created_at'] = Carbon::now();

        //echo "<pre>"; print_r($insert_arr); die;
        $sub_services_id = DB::table('sub_services')->insertGetId($insert_arr);
   
        return redirect()->route('admin.subservices.index')
                        ->with('success','Sub Services created successfully.');
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
    public function edit($sub_service_id)
    {   
        
        $categories = Category::all()->where('deleted_at',NULL);
        $services = Services::all()->where('deleted_at',NULL);
        $sub_service = Subservices::find($sub_service_id);
        return view('backend.sub_services.edit',compact('sub_service','categories','services'));
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
            'sub_service_id' => 'required',
            'price' => 'required',
            'en_name' => 'required',
            'es_name' => 'required',
        ]);

        $subservice = Subservices::find($request->sub_service_id);


        $imagename=""; $storeName=$subservice->image;
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/img/subservices');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'subservices/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
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
        $updateArr['price'] = $request->price;
        $updateArr['percentage'] = $request->percentage;
        $updateArr['quantity'] = $request->quantity;
        $updateArr['image'] = $storeName;
        $updateArr['updated_at'] = Carbon::now();
        $services = Subservices::where('id',$request->sub_service_id)->update($updateArr);

        return redirect()->route('admin.subservices.index')
                        ->with('success','Sub Services updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy($sub_service_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();

        $services = Subservices::where('id',$sub_service_id)->update($updateArr);

  
        return redirect()->route('admin.subservices.index')->with('success','Sub Services deleted successfully');
    }

    public function getServices(Request $request) {   

        $category_id = $request->input('category_id');
        $services = Services::all()->where('category_id',$category_id)->where('deleted_at',NULL);
        $html = view('backend.sub_services.get_services')->with(compact('services'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function removeImageSub(Request $request)
    {
        $service=Subservices::where('id', $request->id)->first();
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
                Subservices::where('id', $request->id)->update(['image'=>'']);
                echo "yes";
            }
    }


}