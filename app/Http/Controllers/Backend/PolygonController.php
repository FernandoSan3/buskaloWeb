<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\PriceRange;
use App\Models\Subservices;
use App\Models\Zone;
use App\Models\Questions;
use App\Models\QuestionOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class PolygonController extends Controller
{

    public function index()
    {
        $zones = Zone::latest()->where('deleted_at',NULL)->paginate(25);
        return view('backend.polygon.index',compact('zones'));
    }


    public function create()
    {


       // $total_zones = Zone::where('deleted_at',NULL)->count();
        //echo $total_zones; die;
        //if($total_zones <= 2) {

         //  $latLngStr = '';
         //  return view('backend.zone.create',compact('latLngStr'));
       // } else {
          //  return redirect()->route('admin.zone.index')->with('success','You can Add Only Three Zones');
        //}

         $latLngStr = '';
        return view('backend.polygon.create',compact('latLngStr'));

    }


    public function store(Request $request)
    {

      // echo "<pre>"; print_r($request->all()); die;

        $request->validate([
            'title' => 'required',
            'address' => 'required',
            'latlng' => 'required',
        ]);

        $insert_zone['area_type'] = $request->area_type;
        $insert_zone['title'] = $request->title;
        $insert_zone['center_lat'] = $request->center_lat;
        $insert_zone['center_long'] = $request->center_long;
        $insert_zone['address'] = $request->address;
        $insert_zone['latlng'] = $request->latlng;
        $insert_zone['created_at'] = Carbon::now();

        $zone_id = DB::table('zone')->insertGetId($insert_zone);

        if($zone_id) {

            return redirect()->route('admin.polygon.index')->with('success','polygon created successfully.');
        } else {

            return redirect()->route('admin.polygon.index')->with('error','Something Went Wrong.');
        }
    }



    public function edit($zone_id)
    {
        $geoFenc = Zone::where('id',$zone_id)->first();
        $latLngStr = '';
        if(!empty($geoFenc)){
            $this->request->data['GeoFencings'] = $geoFenc;

            //echo "<pre>"; print_r($geoFenc['latlng']); die('bbay');

            $ay = '[[[26.921429380588744,75.78733237347184],[26.92096063908204,75.78681738934098],[26.92069278591821,75.7878473576027],[26.92126675620499,75.78805120548783]],[[26.921113697747316,75.78953178486405],[26.920511028052076,75.79027207455216],[26.921190227002118,75.79049738010941]],[[26.92031970366614,75.78882368168412],[26.91949700511003,75.7880082901436],[26.919439607312516,75.78922064861833],[26.919927487660807,75.78988583645402]]]';

            //$latLngArr = json_decode($geoFenc['latlng'],true);
            $latLngArr = json_decode($ay,true);

            //echo "<pre>"; print_r($latLngArr); die('bbay');

            foreach ($latLngArr as $key => $value) {
               // if($key == 1){

                    //echo "<pre>"; print_r($value); die('abcd');
                    foreach ($value as $ke => $val) {
                       //echo "<pre>"; print_r($val); die('efgh');
                        //foreach($val as $l){
                            $lt = !empty($val[0])?$val[0]:'';
                            $ln = !empty($val[1])?$val[1]:'';
                            $latLngStr .= $lt.' '.$ln.',';
                        //}
                    }
                        $latLngStr = rtrim($latLngStr,',');

                         //echo "<pre>"; print_r($latLngStr); die('ayus');
               // }
            }


            // foreach($latLngArr as $l){
            //     $lt = !empty($l[0])?$l[0]:'';
            //     $ln = !empty($l[1])?$l[1]:'';
            //     $latLngStr .= $lt.' '.$ln.',';
            // }
             //echo "<pre>"; print_r($latLngStr); die('bbay');

            //$latLngStr = rtrim($latLngStr,',');

             //echo "<pre>"; print_r($latLngStr); die('ay');
        }
        $dummy_arr = array();
        $latLngStr = '26.921429380589 75.787332373472,26.920960639082 75.786817389341,26.920692785918 75.787847357603,26.921266756205 75.788051205488';
        $dummy_arr[0] = $latLngStr;

        $latLngStr2 = '26.921113697747 75.789531784864,26.920511028052 75.790272074552,26.921190227002 75.790497380109';
        $dummy_arr[1] = $latLngStr2;
        //echo "<pre>"; print_r($dummy_arr); die('ay');
      //  echo $latLngStr; die;
       return view('backend.polygon.edit',compact('latLngStr','geoFenc','dummy_arr','latLngStr2'));

    }


    public function update(Request $request)
    {
         //echo "<pre>"; print_r($request->all()); die;

         $request->validate([
            'zone_id' => 'required',
            'title' => 'required',
            'address' => 'required',
            'latlng' => 'required',
        ]);

        $update_zone['area_type'] = $request->area_type;
        $update_zone['title'] = $request->title;
        $update_zone['center_lat'] = $request->center_lat;
        $update_zone['center_long'] = $request->center_long;
        $update_zone['address'] = $request->address;
        $update_zone['latlng'] = $request->latlng;
        $update_zone['updated_at'] = Carbon::now();


        $response = DB::table('zone')->where('id',$request->zone_id)->update($update_zone);

        if($response) {

            return redirect()->route('admin.polygon.index')->with('success','Polygon updated successfully.');
        } else {

            return redirect()->route('admin.polygon.index')->with('error','Something Went Wrong.');
        }

    }


    public function destroy($zone_id)
    {
        $updateArr['zone_id'] = Carbon::now();
        Zone::where('id',$zone_id)->delete($updateArr);

        return redirect()->route('admin.zone.index')->with('success','Zone deleted successfully');
    }



}