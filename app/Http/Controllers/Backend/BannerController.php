<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        // $categories = DB::table('category')->latest()->where('deleted_at',NULL)->orderBy('id','DESC')->paginate(25);
        $banners = DB::table('banners')
                    ->join('category', 'banners.cat_id', '=', 'category.id')
                    ->select('banners.*', 'category.es_name')
                    ->orderBy('banners.cat_id','ASC')
                    ->paginate(25);

        //$categories = DB::table('category')->latest()->where('deleted_at',NULL)->paginate(25);
        //$categories = DB::table('category')->get();
        //$categories = DB::table('services')->where('deleted_at',NULL)->get();

        // dd($categories);
       
        return view('backend.banner.index',compact('banners'));
       
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all()->where('deleted_at',NULL);
        // return view('backend.banner.create');
        return view('backend.banner.create',compact('categories'));
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
            // 'banner_name' => 'required',
            'category_id' => 'required',
            //'price' => 'required',
        ]);
        
        $imagename=""; $storeName="";
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/bannerimage');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  ''.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
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

        $insert_arr['cat_id'] = $request->category_id;
        $insert_arr['banner_name'] = $storeName;
        $insert_arr['created_at'] = Carbon::now();

        $banner_id = DB::table('banners')->insertGetId($insert_arr);
   
        return redirect()->route('admin.banner.index')
                        ->with('success','Banner created successfully.');
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
        $banner = DB::table('banners')->where('id',$category_id)->first();
        $category = Category::find($banner->cat_id);
        return view('backend.banner.edit',compact('category','banner'));
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
            // 'category_id' => 'required',
        ]);
       
     
        $banner = Banner::find($request->category_id);
       
        $imagename=""; $storeName=$banner->banner_name;
                
        if(!empty($request->image))
        {
          $destinationPath = public_path('/bannerimage');
         
           if($request->image=="")
           {
              $imagename=""; 
            }
            else 
            {
                $image = $request->image;
                $imagename = date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
                $storeName=  ''.date('Y-m-d').time(). '-img'.'.' . $image->getClientOriginalExtension();
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

        $updateArr['banner_name'] = $storeName;
        $updateArr['updated_at'] = Carbon::now();
        
        if($banner->update($updateArr)) {
            return redirect()->route('admin.banner.index')
                        ->with('success','Banner updated successfully');
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
    public function destroy($banner_id)
    {    
        // delete banner image
        $banner = DB::table('banners')->where('id',$banner_id)->first();

        $destinationPath = public_path('/bannerimage/');
        if(file_exists(public_path().$destinationPath.$banner->banner_name))
        {

            unlink(public_path().$destinationPath.$banner->banner_name);
        }
        $delete = DB::table('banners')->where('id',$banner_id)->delete();

        return redirect()->route('admin.banner.index')->with('success','Banner deleted successfully' );
    }

    public function removeImage(Request $request)
    {
        $banner = DB::table('banners')->where('id',$request->id)->first();        
            $image="";
            $findinfolder="";
            if(isset($banner->banner_name))
                { $image=$banner->banner_name;
                $findinfolder=public_path().'/bannerimage/'.$banner->banner_name;
                }
            if (file_exists($findinfolder) && !empty($image))
            {   
                unlink($findinfolder);
                Banner::where('id', $request->id)->update(['banner_name'=>'']);
                echo "yes";
            }
    }

}