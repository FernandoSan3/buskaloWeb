<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contactus;
use App\Models\SiteSetting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class SiteSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    $sitesetting = DB::table('site_settings')->where('id',1)->first();

        return view('backend.site_setting.index',compact('sitesetting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function updatesitesetting(Request $request)
    {
         $imagename=""; $storeName="";

        if(!empty($request->logo_image))
        {
          $destinationPath = public_path('/img/logo');

           if($request->logo_image=="")
           {
              $imagename="";
            }
            else
            {
                $image = $request->logo_image;
                $imagename = 'logo.'.$request->logo_image->extension();

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
        }
           $updateArr['logo'] = $storeName;
           $updateArr['company_address'] = $request->address;
           $updateArr['company_email'] = $request->email;
           $updateArr['company_contact'] = $request->contact;
           $updateArr['copyright_text'] = $request->copyrighttext;
           $updateArr['footer_text'] = $request->footertext;
           $updateArr['facebook'] = $request->facebookurl;
           $updateArr['linkedin'] = $request->linkedinurl;
           $updateArr['twitter'] = $request->twitterurl;
           $updateArr['instagram'] = $request->instagram;
           $updateArr['google'] = $request->googleurl;
           $updateArr['youtube'] = $request->youtube;
           $updateArr['terms'] = $request->terms;
           $updateArr['disclaimer'] = $request->disclaimer;
           $updateArr['free_credit'] = $request->free_credit;
           $updateArr['updated_at'] = Carbon::now();

           $newdata = DB::table('site_settings')->where('id',1)->update($updateArr);


     return redirect()->route('admin.site_setting.index')->with('success',' updated successfully.');
    }


 public function socialstore(Request $request)
    {
        $request->validate([
            'facebookurl' => 'required',
            'linkedinurl' => 'required',
            'twitterurl' => 'required',
            'googleurl' => 'required',
            //'sitesetting_id' =>'required'

        ]);

        $updateArr['facebook'] = $request->facebookurl;
        $updateArr['linkedin'] = $request->linkedinurl;
        $updateArr['twitter'] = $request->twitterurl;
        $updateArr['google'] = $request->googleurl;
        $updateArr['updated_at'] = Carbon::now();



    $socialdata = SiteSetting::where('id',2)->update($updateArr);

   return redirect()->route('admin.site_setting.index')->with('success','created successfully');
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
    public function edit($city_id)
    {

        $city = Cities::find($city_id);
        $provinces = Provinces::all()->where('deleted_at',NULL);
        return view('backend.cities.edit',compact('city','provinces'));
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
            'city_id' => 'required',
        ]);

        $updateArr['name'] = $request->name;
        $updateArr['updated_at'] = Carbon::now();
        $services = Cities::where('id',$request->city_id)->update($updateArr);
        return redirect()->route('admin.cities.index')->with('success','City updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */
    public function destroy($city_id)
    {
        $updateArr['deleted_at'] = Carbon::now();
        $services = Cities::where('id',$city_id)->update($updateArr);
        return redirect()->route('admin.cities.index')->with('success','City deleted successfully');
    }
}