<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subservices;
use App\Models\ChildSubservices;
use App\Models\Services;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class ChildSubservicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $child_sub_services = ChildSubservices::join('category','child_sub_services.category_id','=','category.id')
        ->join('services','child_sub_services.services_id','=','services.id')
        ->join('sub_services','child_sub_services.sub_services_id','=','sub_services.id')
        ->select('child_sub_services.*','category.es_name as category_name','sub_services.es_name as sub_services_name','services.es_name as services_name')        
        ->where('child_sub_services.deleted_at',NULL)
        ->orderBy('child_sub_services.id','DESC')
        ->get();       
        return view('backend.child_sub_services.index',compact('child_sub_services'));
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
        $subservices = Subservices::all()->where('deleted_at',NULL);
        return view('backend.child_sub_services.create',compact('categories','services','subservices'));
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
            'sub_services_id' => 'required',
           // 'price' => 'required|numeric',
            'percentage'=>'required|numeric',
            'en_name' => 'required',
            'es_name' => 'required',
        ]);

        //echo "<pre>"; print_r($request->all()); die();

         $imagename=""; $storeName="";
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/img/childsubservices');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'childsubservices/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
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
        $insert_arr['sub_services_id'] = $request->sub_services_id;
        $insert_arr['price'] = $request->price;
        $insert_arr['percentage'] = $request->percentage;
        $insert_arr['en_name'] = $request->en_name;
        $insert_arr['es_name'] = $request->es_name;
        $insert_arr['image'] = $storeName;
        $insert_arr['status'] = 1;
        $insert_arr['created_at'] = Carbon::now();

        $child_sub_services_id = DB::table('child_sub_services')->insertGetId($insert_arr);
   
        return redirect()->route('admin.childsubservices.index')
                        ->with('success','Child Sub Services created successfully.');
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
    public function edit($child_sub_service_id)
    {   
        $categories = Category::all()->where('deleted_at',NULL);
        $services = Services::all()->where('deleted_at',NULL);
        $subservices = Subservices::all()->where('deleted_at',NULL);
        $child_sub_service = ChildSubservices::find($child_sub_service_id);
        return view('backend.child_sub_services.edit',compact('child_sub_service','categories','services','subservices'));
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
        //die('asdas');
        $request->validate([
            'child_sub_service_id' => 'required',
            //'price' => 'required|numeric',
            'percentage'=>'required|numeric',
            'en_name' => 'required',
            'es_name' => 'required',
        ]);

        $childsubservice = ChildSubservices::find($request->child_sub_service_id);


        $imagename=""; $storeName=$childsubservice->image;
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/img/childsubservices');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'childsubservices/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
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
        $updateArr['image'] = $storeName;
        $updateArr['updated_at'] = Carbon::now();
        
       // echo "<pre>"; print_r($updateArr); die;
        
        $services = ChildSubservices::where('id',$request->child_sub_service_id)->update($updateArr);

         return redirect()->route('admin.childsubservices.index')
                         ->with('success','Child Sub Services updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy($child_sub_service_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();

        ChildSubservices::where('id',$child_sub_service_id)->update($updateArr);
  
        return redirect()->route('admin.childsubservices.index')->with('success','Child Sub Services deleted successfully');
    }

    public function getServices(Request $request) {   

        $category_id = $request->input('category_id');
        $services = Services::all()->where('category_id',$category_id)->where('deleted_at',NULL);
        $html = view('backend.sub_services.get_services')->with(compact('services'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function getSubServices(Request $request) {   

        $services_id = $request->input('services_id');
        $subservices = Subservices::all()->where('services_id',$services_id)->where('deleted_at',NULL);
        $html = view('backend.child_sub_services.get_sub_services')->with(compact('subservices'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

   
}