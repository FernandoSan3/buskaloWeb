<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $categories = DB::table('category')->latest()->where('deleted_at',NULL)->orderBy('id','DESC')->paginate(25);
        //$categories = DB::table('category')->latest()->where('deleted_at',NULL)->paginate(25);
        //$categories = DB::table('category')->get();
        //$categories = DB::table('services')->where('deleted_at',NULL)->get();

        // dd($categories);
       
        return view('backend.category.index',compact('categories'));
       
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.category.create');
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
            //'price' => 'required',
        ]);
        
        $imagename=""; $storeName="";
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/img/category');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'category/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
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
        $insert_arr['image'] = $storeName;
        $insert_arr['icon'] = $request->icon;
        //$insert_arr['price'] = $request->price;
        $insert_arr['status'] = isset($request->status)?$request->status:0;
        $insert_arr['created_at'] = Carbon::now();

        $category_id = DB::table('category')->insertGetId($insert_arr);
   
        return redirect()->route('admin.category.index')
                        ->with('success','Category created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('backend.category.show',compact('services'));
    }
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function edit($category_id)
    {   
        $bannerImages = DB::table('banners')->where('cat_id',$category_id)->get();
        $category = Category::find($category_id);
        return view('backend.category.edit',compact('category','bannerImages'));
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
            'category_id' => 'required',
            'en_name' => 'required',
            'es_name' => 'required',
            //'price' => 'required',
        ]);

     
        $category = Category::find($request->category_id);

        if(!empty($request->bannerimage))
        {
            foreach ($request->bannerimage as $key => $image)
            {
                $imagename= rand(111,999).time(). '-img'.'.' . $image->getClientOriginalExtension();
                $destinationPath= public_path('bannerimage/');
                $image->move($destinationPath, $imagename);
                DB::table('banners')->insert(['cat_id'=>$request->category_id,'banner_name'=>$imagename]); 
            }
        }
        $imagename=""; $storeName=$category->image;
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/img/category');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  'category/'.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
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


        $status=0;
        if(isset($request->status) && !empty($request->status))
        {
            $status=1;
        }
        $updateArr['en_name'] = $request->en_name;
        $updateArr['es_name'] = $request->es_name;
        $updateArr['icon'] = $request->icon;
        //$updateArr['price'] = $request->price;
        $updateArr['image'] = $storeName;
        $updateArr['status'] =$status;
        $updateArr['updated_at'] = Carbon::now();
        $category_update = Category::where('id',$request->category_id)->update($updateArr);
        
        if($category_update) {
            return redirect()->route('admin.category.index')
                        ->with('success','Category updated successfully');
        } else {
            return redirect()->back();
        }
  
        
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();

        $category_delete = Category::where('id',$category_id)->update($updateArr);

  
        return redirect()->route('admin.category.index')->with('success','Category deleted successfully');
    }


    public function status(Request $request, $id=null)
    {
         $category = Category::find($id);

         if( $category->status==1)
         {
            $status=0;
         }
         else
         {
            $status=1;
         }
         Category::where('id',$id)->update(['status'=>$status]);
            return redirect()->route('admin.category.index')->with('success','Category status update successfully');
    } 

    public function removeImageCat(Request $request)
    {
        $service=Category::where('id', $request->id)->first();
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
                Category::where('id', $request->id)->update(['image'=>'']);
                echo "yes";
            }
    }
}