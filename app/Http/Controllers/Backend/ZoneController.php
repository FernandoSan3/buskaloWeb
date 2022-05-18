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

class ZoneController extends Controller
{

    public function index()
    {
        $zones = Zone::latest()->where('deleted_at',NULL)->paginate(25);
        return view('backend.zone.index',compact('zones'));
    }


    public function create()
    {
        $latLngStr = '';
        return view('backend.zone.create',compact('latLngStr'));
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

            return redirect()->route('admin.zone.index')->with('success','Zone created successfully.');
        } else {

            return redirect()->route('admin.zone.index')->with('error','Something Went Wrong.');
        }
    }



    public function edit($zone_id)
    {
        $geoFenc = Zone::where('id',$zone_id)->first();

        $old_geofenc = $geoFenc->latlng;

              //$latLngStr = '';
        $dummy_arr = array();
        if(!empty($geoFenc)){
            $old_geofenc = json_encode($geoFenc->latlng);
            $this->request->data['GeoFencings'] = $geoFenc;
            //$ay = '['.$geoFenc['latlng'].']';
            $ay = '[[[26.919937053921046,75.78758986553727],[26.919745728561914,75.78672082981645],[26.91929611269099,75.7876864250618],[26.919468306214934,75.78833015522538],[26.9198222587445,75.78837307056962]],[[26.92106586693673,75.78658135494767],[26.920750183078294,75.78767569622575],[26.920252740054423,75.7868388470131],[26.920051848980837,75.78595908245622],[26.920903242029052,75.78594835362016]]]';
           
             $latLngArr = json_decode($ay,true);
             
            foreach ($latLngArr as $key => $value) {
                $latLngStr_zone = '';
                $latLngStr = '';
                foreach ($value as $ke => $val) {
                    $lt = !empty($val[0])?$val[0]:'';
                    $ln = !empty($val[1])?$val[1]:'';
                    $latLngStr .= $lt.' '.$ln.',';
                }

                $latLngStr_zone = rtrim($latLngStr,',');
                $dummy_arr[$key] = $latLngStr_zone;
                
            }



    $ay12 = '[[[26.920568425304598,75.78905971607743],[26.92028143875003,75.78871639332353],[26.9199466201805,75.78917773327409],[26.920204908878866,75.78961761555253]],[[26.921429380588744,75.78926356396256],[26.921037168440634,75.788802224012],[26.92075974926879,75.78949959835587],[26.921027602273647,75.78996093830644]]]';
           
             $latLngArr12 = json_decode($ay12,true);
             
            foreach ($latLngArr12 as $key12 => $value12) {
                $latLngStr_zone12 = '';
                $latLngStr12 = '';
                foreach ($value12 as $ke12 => $val12) {
                    $lt12 = !empty($val12[0])?$val12[0]:'';
                    $ln12 = !empty($val12[1])?$val12[1]:'';
                    $latLngStr12 .= $lt12.' '.$ln12.',';
                }

                $latLngStr_zone12 = rtrim($latLngStr12,',');
                $dummy_arr12[$key12] = $latLngStr_zone12;
                
            }


    $ay12 = '[[[26.920568425304598,75.78905971607743],[26.92028143875003,75.78871639332353],[26.9199466201805,75.78917773327409],[26.920204908878866,75.78961761555253]]]';
           
             $latLngArr12 = json_decode($ay12,true);
             
            foreach ($latLngArr12 as $key12 => $value12) {
                $latLngStr_zone12 = '';
                $latLngStr12 = '';
                foreach ($value12 as $ke12 => $val12) {
                    $lt12 = !empty($val12[0])?$val12[0]:'';
                    $ln12 = !empty($val12[1])?$val12[1]:'';
                    $latLngStr12 .= $lt12.' '.$ln12.',';
                }

                $latLngStr_zone12 = rtrim($latLngStr12,',');
                $dummy_arr12[$key12] = $latLngStr_zone12;
                
            }

    $ay24 = '[[[26.921429380588744,75.78926356396256],[26.921037168440634,75.788802224012],[26.92075974926879,75.78949959835587],[26.921027602273647,75.78996093830644]]]';
           
             $latLngArr24 = json_decode($ay24,true);
             
            foreach ($latLngArr24 as $key24 => $value24) {
                $latLngStr_zone24 = '';
                $latLngStr24 = '';
                foreach ($value24 as $ke24 => $val24) {
                    $lt24 = !empty($val24[0])?$val24[0]:'';
                    $ln24 = !empty($val24[1])?$val24[1]:'';
                    $latLngStr24 .= $lt24.' '.$ln24.',';
                }

                $latLngStr_zone24 = rtrim($latLngStr24,',');
                $dummy_arr24[$key24] = $latLngStr_zone24;
                
            }












        }

        //echo "<pre>"; print_r($dummy_arr12); die;
      
       return view('backend.zone.edit',compact('geoFenc','dummy_arr','dummy_arr12','dummy_arr24','old_geofenc','tes'));

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

        $geoFenc = Zone::where('id',$request->zone_id)->first();
        $old_geofenc = $geoFenc->latlng;

        $new_geofrnce = $old_geofenc.",";
        
        $update_zone['area_type'] = $request->area_type;
        $update_zone['title'] = $request->title;
        $update_zone['center_lat'] = $request->center_lat;
        $update_zone['center_long'] = $request->center_long;
        $update_zone['address'] = $request->address;
        $update_zone['latlng'] = $new_geofrnce.$request->latlng;
        $update_zone['updated_at'] = Carbon::now();


        $response = DB::table('zone')->where('id',$request->zone_id)->update($update_zone);

        if($response) {

            return redirect()->route('admin.zone.index')->with('success','Zone updated successfully.');
        } else {

            return redirect()->route('admin.zone.index')->with('error','Something Went Wrong.');
        }

    }


    public function remove($zone_id)
    {
        $geoFenc = Zone::where('id',$zone_id)->first();

        $old_geofenc = $geoFenc->latlng;

              //$latLngStr = '';
        $dummy_arr = array();
        if(!empty($geoFenc)){
            $old_geofenc = json_encode($geoFenc->latlng);
            $this->request->data['GeoFencings'] = $geoFenc;
            $ay = '['.$geoFenc['latlng'].']';
            
             $latLngArr = json_decode($ay,true);
            
            foreach ($latLngArr as $key => $value) {
                $latLngStr_zone = '';
                $latLngStr = '';

                foreach ($value as $ke => $val) {

                    $lt = !empty($val[0])?$val[0]:'';
                    $ln = !empty($val[1])?$val[1]:'';
                    //$latLngStr .= $lt.' '.$ln.',';
                    $latLngStr .= $lt.' '.$ln.',';

                }

                $latLngStr_zone = rtrim($latLngStr,',');
                $dummy_arr[$key] = $latLngStr_zone;
                //$latLngStr = rtrim($latLngStr,',');
            }
        }

        //echo "<pre>"; print_r(json_encode($dummy_arr)); die;
        
      // return view('backend.zone.edit',compact('latLngStr','geoFenc','dummy_arr','latLngStr2','new_arr'));
       return view('backend.zone.add_polygon',compact('geoFenc','dummy_arr','old_geofenc','tes'));

    }

    public function removePolygon(Request $request)
    { 

        $request->validate([
            'zone_id' => 'required',
            'title' => 'required',
            'address' => 'required',
            'latlng' => 'required',
            'removed_index' => 'required',
        ]);


        $geoFenc = Zone::where('id',$request->zone_id)->first();
        $old_geofenc = $geoFenc->latlng;
        $old_geofenc_arr = explode("]],",$old_geofenc);       
        for ($i=0; $i <count($old_geofenc_arr) ; $i++) { 
            if($i != count($old_geofenc_arr)-1){
                $old_geofenc_arr[$i] .= "]],";
            }
        }

        $removed_index = $request->removed_index;
        $removed_index_exp = explode(",",$removed_index);
        $cou = count($old_geofenc_arr);

         for ($i=0; $i <$cou ; $i++) {                
                 
            if (in_array($i, $removed_index_exp))
            {               
                unset($old_geofenc_arr[$i]);
            }
          
        }
        
        $new_var = '';
        $new_geofenc_arr = array_values($old_geofenc_arr);

        foreach ($new_geofenc_arr as $key => $value) {
            if(count($new_geofenc_arr) == $key+1) {                
                $val_len = strlen($value);                
                $last_str = substr($value,$val_len-1); 
                if($last_str == ',') {
                    $new_val = substr($value,'0',$val_len-1);
                    $new_var.= $new_val;
                } else {
                    $new_var.= $value;
                }               
            } else {

                $val_len = strlen($value); 
                $last_str = substr($value,$val_len-1); 
                if($last_str != ',') {
                    $new_var.= $value.",";
                } else{
                    $new_var.= $value;
               }
            }
        }
        

        $update_zone['area_type'] = $request->area_type;
        $update_zone['title'] = $request->title;
        $update_zone['center_lat'] = $request->center_lat;
        $update_zone['center_long'] = $request->center_long;
        $update_zone['address'] = $request->address;
        $update_zone['latlng'] = $new_var;
        $update_zone['updated_at'] = Carbon::now();


        $response = DB::table('zone')->where('id',$request->zone_id)->update($update_zone);

       
        if($response) {

            return redirect()->route('admin.zone.index')->with('success','Zone updated successfully.');
        } else {

            return redirect()->route('admin.zone.index')->with('error','Something Went Wrong.');
        }

    }


    public function destroy($zone_id)
    {
        $updateArr['zone_id'] = Carbon::now();
        Zone::where('id',$zone_id)->delete($updateArr);

        return redirect()->route('admin.zone.index')->with('success','Zone deleted successfully');
    }



}