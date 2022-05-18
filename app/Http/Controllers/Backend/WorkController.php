<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contactus;
use App\Models\Work;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      $how_it_is_work = DB::table('how_it_is_work')->where('id',1)->first();
      $how_it_is_work1 = DB::table('how_it_is_work')->where('id',2)->first();

        return view('backend.work.index',compact('how_it_is_work','how_it_is_work1'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function updatework(Request $request)
    {

      //echo '<pre>'; print_r($request->all());exit;
        $imagename=""; $storeName="";

        if(!empty($request->image))
        {
          $destinationPath = public_path('img/frontend/work');

           if($request->image=="")
           {
              $imagename="";
            }
            else
            {
                $image = $request->image;
                $imagename = 'howitiswork.'.$request->image->extension();

                $storeName=  $imagename;

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
             $updateArr['image'] = $storeName;
        }

      
       $updateArr['search'] = $request->search;
       $updateArr['search_descriptiom'] = $request->search_descriptiom;
       $updateArr['compare'] = $request->compare;
       $updateArr['compare_description'] = $request->compare_description;
       $updateArr['hire'] = $request->hire;
       $updateArr['hire_description'] = $request->hire_description;
       $updateArr['description'] = $request->description;
       $updateArr['updated_at'] = Carbon::now();


       $newdata = DB::table('how_it_is_work')->where('id',$request->id)->update($updateArr);

       return redirect()->route('admin.work.index')->with('success','updated successfully.');
    }


}