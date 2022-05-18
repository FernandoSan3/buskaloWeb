<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $package = Package::latest()->where('deleted_at',NULL)->orderBy('id','DESC')->paginate(25);

        return view('backend.package.index', compact('package'));
    }

    public function create()
    {
        
        return view('backend.package.create');
    }

    public function store(Request $request)
    {  
        $request->validate([
            'en_name' => 'required',
            'es_name' => 'required',
            'price' => 'required',
        ]);
        
        $insert_arr['en_name'] = $request->en_name;
        $insert_arr['es_name'] = $request->es_name;
        $insert_arr['price'] = $request->price;
        $insert_arr['credit'] = $request->credit;
        $insert_arr['discount'] = $request->discount;
        $insert_arr['status'] = 1;
        $insert_arr['created_at'] = Carbon::now();

        $package_id = DB::table('package')->insertGetId($insert_arr);

        return redirect()->route('admin.package.index')
                        ->with('success','Package created successfully.');
    }

    public function edit($id)
    {   
        $package = Package::find($id);
        return view('backend.package.edit',compact('package'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'en_name' => 'required',
            'es_name' => 'required',
            'price' => 'required',
        ]);

        $package = Package::find($request->package_id);

        $updateArr['en_name'] = $request->en_name;
        $updateArr['es_name'] = $request->es_name;
        $updateArr['price'] = $request->price;
        $updateArr['credit'] = $request->credit;
        $updateArr['discount'] = $request->discount;
        $updateArr['status'] = 1;
        $updateArr['updated_at'] = Carbon::now();

        $package_update = Package::where('id',$request->package_id)->update($updateArr);
        
        if($package_update) {
            return redirect()->route('admin.package.index')
                        ->with('success','Package updated successfully');
        } else {
            return redirect()->back();
        }
  
        
    }

    public function destroy($package_id)
    {    
        $updateArr['deleted_at'] = Carbon::now();

        $package_delete = Package::where('id',$package_id)->update($updateArr);

        return redirect()->route('admin.package.index')->with('success','Package deleted successfully');
    }
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  
}