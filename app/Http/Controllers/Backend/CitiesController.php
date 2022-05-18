<?php
  
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Services;
use App\Models\Cities;
use App\Models\Zone;
use App\Models\Provinces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = Cities::join('provinces','cities.province_id','=','provinces.id')
        ->select('cities.*','provinces.name as provinces_name')        
        ->where('cities.deleted_at',NULL)
        ->orderBy('cities.id','DESC')
        ->paginate(25);       
        return view('backend.cities.index',compact('cities'));
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $provinces = Provinces::all()->where('deleted_at',NULL);
        return view('backend.cities.create',compact('provinces'));
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
            'name' => 'required',
            'province_id' => 'required',
        ]);        
        
        $insert_arr['name'] = $request->name;
        $insert_arr['province_id'] = $request->province_id;
        $insert_arr['status'] = 1;
        $insert_arr['created_at'] = Carbon::now();

        $city_id = DB::table('cities')->insertGetId($insert_arr);   
        return redirect()->route('admin.cities.index')->with('success','City created successfully.');
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


    public function polygons($city_id)
    {   
       $polygon_page_title = 'All Polygons';
       $zones = Zone::latest()->where('city_id',$city_id)->where('deleted_at',NULL)->paginate(25);

       //echo "<pre>"; print_r($zones->toArray()); die;
        return view('backend.cities.zone.index',compact('zones','city_id','polygon_page_title'));
    }


    public function createPolygon($city_id) {
      
        $latLngStr = '';
        //return view('backend.cities.zone.create',compact('latLngStr','city_id'));



        $all_zones = Zone::where('city_id',$city_id)->where('deleted_at',NULL)->get();
        // $all_low_zones = Zone::where('city_id',$city_id)->where('area_type','low_resources_area')->where('deleted_at',NULL)->get();
        // $all_avg_zones = Zone::where('city_id',$city_id)->where('area_type','avg_resources_area')->where('deleted_at',NULL)->get();
        // $all_high_zones = Zone::where('city_id',$city_id)->where('area_type','high_resources_area')->where('deleted_at',NULL)->get();

        $all_low_zones_2 = Zone::where('city_id',$city_id)->where('area_type','low_resources_area_2')->where('deleted_at',NULL)->get();
        $all_avg_zones = Zone::where('city_id',$city_id)->where('area_type','avg_resources_area')->where('deleted_at',NULL)->get();
        $all_high_zones_1 = Zone::where('city_id',$city_id)->where('area_type','high_resources_area_1')->where('deleted_at',NULL)->get();
        $all_high_zones_2 = Zone::where('city_id',$city_id)->where('area_type','high_resources_area_2')->where('deleted_at',NULL)->get();

       
        $ay_1 = '';
        $ay12_1 = '';
        $ay24_1 = '';
        $ay34_1 = '';

        if(isset($all_low_zones_2) && count($all_low_zones_2) > 0)
        {

            foreach ($all_low_zones_2 as $k_low => $v_low) {
                if($k_low == 0){

                   $ay_1 =  $v_low->latlng;
                }else{
                   $ay_1 =  $ay_1.','.$v_low->latlng;  

                }
            } 

        } 

        if(isset($all_avg_zones) && count($all_avg_zones) > 0){

            foreach ($all_avg_zones as $k_avg => $v_avg) {
                
                if($k_avg == 0){

                   $ay12_1 =  $v_avg->latlng;
                }else{
                   $ay12_1 =  $ay12_1.','.$v_avg->latlng;  

                }
                
            } 
        }


        if(isset($all_high_zones_1) && count($all_high_zones_1) > 0)
        {
            
            foreach ($all_high_zones_1 as $k_hig_1 => $v_hig_1) {
                if($k_hig_1 == 0){

                    $ay24_1 =  $v_hig_1->latlng;
                }else{
                    $ay24_1 =  $ay24_1.','.$v_hig_1->latlng;  

                }
                      
            }
        }


        if(isset($all_high_zones_2) && count($all_high_zones_2) > 0)
        {
            
            foreach ($all_high_zones_2 as $k_hig_2 => $v_hig_2) {
                if($k_hig_2 == 0){

                    $ay34_1 =  $v_hig_2->latlng;
                }else{
                    $ay34_1 =  $ay34_1.','.$v_hig_2->latlng;  

                }
                      
            }
        }

     
        $dummy_arr = array();
        $dummy_arr12 = array();
        $dummy_arr24 =  array();
        $dummy_arr34 =  array();

        if(!empty($all_zones)){

            if(isset($all_low_zones_2) && count($all_low_zones_2) > 0){

                $ay = '['.$ay_1.']';
               
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
            }
            
            if(isset($all_avg_zones) && count($all_avg_zones) > 0){

                $ay12 = '['.$ay12_1.']';
                   
               
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
            }


            if(isset($all_high_zones_1) && count($all_high_zones_1) > 0){

                $ay24 = '['.$ay24_1.']';
       
               
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

            if(isset($all_high_zones_2) && count($all_high_zones_2) > 0){

                $ay34 = '['.$ay34_1.']';
                
               
                $latLngArr34 = json_decode($ay34,true);
                 
                foreach ($latLngArr34 as $key34 => $value34) {
                    $latLngStr_zone34 = '';
                    $latLngStr34 = '';
                    foreach ($value34 as $ke34 => $val34) {
                        $lt34 = !empty($val34[0])?$val34[0]:'';
                        $ln34 = !empty($val34[1])?$val34[1]:'';
                        $latLngStr34 .= $lt34.' '.$ln34.',';
                    }

                    $latLngStr_zone34 = rtrim($latLngStr34,',');
                    $dummy_arr34[$key34] = $latLngStr_zone34;
                    
                }
            }

        }

       

      
       return view('backend.cities.zone.create',compact('dummy_arr','dummy_arr12','dummy_arr24','dummy_arr34','latLngStr','city_id'));


    //return view('backend.cities.zone.create',compact('latLngStr','city_id'));




    }

     public function storePolygon(Request $request)
    {
       // echo "<pre>"; print_r($request->all()); die;

        $request->validate([
            'area_type' => 'required',
            'title' => 'required',
            'address' => 'required',
            'latlng' => 'required',
        ]);

        $already_exist_address = DB::table('zone')->where('address',$request->address)->where('area_type',$request->area_type)->where('deleted_at',NULL)->first();

        if($already_exist_address) {
            return redirect()->route('admin.cities.create_polygon',$request->city_id)->with('success','Already Added in this Area');
        }

        $insert_zone['city_id'] = $request->city_id;
        $insert_zone['area_type'] = $request->area_type;
        $insert_zone['title'] = $request->title;
        $insert_zone['center_lat'] = $request->center_lat;
        $insert_zone['center_long'] = $request->center_long;
        $insert_zone['address'] = $request->address;
        $insert_zone['latlng'] = $request->latlng;
        $insert_zone['created_at'] = Carbon::now();

        $zone_id = DB::table('zone')->insertGetId($insert_zone);

        if($zone_id) {

            return redirect()->route('admin.cities.index')->with('success','Polygon created successfully.');
        } else {

            return redirect()->route('admin.cities.index')->with('error','Something Went Wrong.');
        }
    }

    public function polygonsByAreaType($city_id,$area_type){

        //die('polygonsByAreaType');
        $polygon_page_title = $area_type;

        $zones = Zone::latest()->where('area_type',$area_type)->where('city_id',$city_id)->where('deleted_at',NULL)->paginate(25);
        return view('backend.cities.zone.index',compact('zones','city_id','polygon_page_title'));
    }


    public function addMorePolygon($zone_id) {

        $geoFenc = Zone::where('id',$zone_id)->first();

        $city_id = $geoFenc->city_id;
        $old_geofenc = $geoFenc->latlng;
              
        // $dummy_arr = array();
        // if(!empty($geoFenc)){
        //     $old_geofenc = json_encode($geoFenc->latlng);
        //     $this->request->data['GeoFencings'] = $geoFenc;
        //     $ay = '['.$geoFenc['latlng'].']';          
           
        //     $latLngArr = json_decode($ay,true);
             
        //     foreach ($latLngArr as $key => $value) {
        //         $latLngStr_zone = '';
        //         $latLngStr = '';
        //         foreach ($value as $ke => $val) {
        //             $lt = !empty($val[0])?$val[0]:'';
        //             $ln = !empty($val[1])?$val[1]:'';
        //             $latLngStr .= $lt.' '.$ln.',';
        //         }

        //         $latLngStr_zone = rtrim($latLngStr,',');
        //         $dummy_arr[$key] = $latLngStr_zone;
                
        //     }
        // }


              
       // return view('backend.cities.zone.edit',compact('geoFenc','dummy_arr','old_geofenc','city_id'));



       $all_zones = Zone::where('city_id',$city_id)->where('deleted_at',NULL)->get();
        // $all_low_zones = Zone::where('city_id',$city_id)->where('area_type','low_resources_area')->where('deleted_at',NULL)->get();
        // $all_avg_zones = Zone::where('city_id',$city_id)->where('area_type','avg_resources_area')->where('deleted_at',NULL)->get();
        // $all_high_zones = Zone::where('city_id',$city_id)->where('area_type','high_resources_area')->where('deleted_at',NULL)->get();

        $all_low_zones_2 = Zone::where('city_id',$city_id)->where('area_type','low_resources_area_2')->where('deleted_at',NULL)->get();
        $all_avg_zones = Zone::where('city_id',$city_id)->where('area_type','avg_resources_area')->where('deleted_at',NULL)->get();
        $all_high_zones_1 = Zone::where('city_id',$city_id)->where('area_type','high_resources_area_1')->where('deleted_at',NULL)->get();
        $all_high_zones_2 = Zone::where('city_id',$city_id)->where('area_type','high_resources_area_2')->where('deleted_at',NULL)->get();

        // echo "<pre>"; print_r($all_zones); echo "<br>";
        // echo "<pre>"; print_r($all_low_zones); echo "<br>";
        // echo "<pre>"; print_r($all_avg_zones); echo "<br>";
        // echo "<pre>"; print_r($all_high_zones); echo "<br>"; die('here');

        $ay_1 = '';
        $ay12_1 = '';
        $ay24_1 = '';
        $ay34_1 = '';

        if(isset($all_low_zones_2) && count($all_low_zones_2) > 0)
        {

            foreach ($all_low_zones_2 as $k_low => $v_low) {
                if($k_low == 0){

                   $ay_1 =  $v_low->latlng;
                }else{
                   $ay_1 =  $ay_1.','.$v_low->latlng;  

                }
            } 

        } 

        if(isset($all_avg_zones) && count($all_avg_zones) > 0){

            foreach ($all_avg_zones as $k_avg => $v_avg) {
                
                if($k_avg == 0){

                   $ay12_1 =  $v_avg->latlng;
                }else{
                   $ay12_1 =  $ay12_1.','.$v_avg->latlng;  

                }
                
            } 
        }


        if(isset($all_high_zones_1) && count($all_high_zones_1) > 0)
        {
            
            foreach ($all_high_zones_1 as $k_hig_1 => $v_hig_1) {
                if($k_hig_1 == 0){

                    $ay24_1 =  $v_hig_1->latlng;
                }else{
                    $ay24_1 =  $ay24_1.','.$v_hig_1->latlng;  

                }
                      
            }
        }


        if(isset($all_high_zones_2) && count($all_high_zones_2) > 0)
        {
            
            foreach ($all_high_zones_2 as $k_hig_2 => $v_hig_2) {
                if($k_hig_2 == 0){

                    $ay34_1 =  $v_hig_2->latlng;
                }else{
                    $ay34_1 =  $ay34_1.','.$v_hig_2->latlng;  

                }
                      
            }
        }

     
        $dummy_arr = array();
        $dummy_arr12 = array();
        $dummy_arr24 =  array();
        $dummy_arr34 =  array();

        if(!empty($all_zones)){

            if(isset($all_low_zones_2) && count($all_low_zones_2) > 0){

                $ay = '['.$ay_1.']';
               
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
            }
            
            if(isset($all_avg_zones) && count($all_avg_zones) > 0){

                $ay12 = '['.$ay12_1.']';
                   
               
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
            }


            if(isset($all_high_zones_1) && count($all_high_zones_1) > 0){

                $ay24 = '['.$ay24_1.']';
       
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

            if(isset($all_high_zones_2) && count($all_high_zones_2) > 0){

                $ay34 = '['.$ay34_1.']';
               
                $latLngArr34 = json_decode($ay34,true);
                 
                foreach ($latLngArr34 as $key34 => $value34) {
                    $latLngStr_zone34 = '';
                    $latLngStr34 = '';
                    foreach ($value34 as $ke34 => $val34) {
                        $lt34 = !empty($val34[0])?$val34[0]:'';
                        $ln34 = !empty($val34[1])?$val34[1]:'';
                        $latLngStr34 .= $lt34.' '.$ln34.',';
                    }

                    $latLngStr_zone34 = rtrim($latLngStr34,',');
                    $dummy_arr34[$key34] = $latLngStr_zone34;
                    
                }
            }
        }
      
       return view('backend.cities.zone.edit',compact('geoFenc','dummy_arr','dummy_arr12','dummy_arr24','dummy_arr34','old_geofenc','city_id'));

       //return view('backend.cities.zone.edit',compact('geoFenc','dummy_arr','old_geofenc','city_id'));
    }


    public function updateZone(request $request) {

        $request->validate([
            'zone_id' => 'required',
            'title' => 'required',
            'address' => 'required',
            'latlng' => 'required',
        ]);

        $geoFenc = Zone::where('id',$request->zone_id)->first();
        $old_geofenc = $geoFenc->latlng;
        //echo "<pre>"; print_r($geoFenc->toArray()); die('zone');
        $new_geofrnce = $old_geofenc.",";
        if($geoFenc->area_type == $request->area_type)
        {   
            //die('123');
            $update_zone['title'] = $request->title;
            $update_zone['center_lat'] = $request->center_lat;
            $update_zone['center_long'] = $request->center_long;
            $update_zone['address'] = $request->address;
            $update_zone['latlng'] = $new_geofrnce.$request->latlng;
            $update_zone['updated_at'] = Carbon::now();

            $response = DB::table('zone')->where('id',$request->zone_id)->update($update_zone);

            if($response) {

                return redirect()->route('admin.cities.index')->with('success','polygon updated successfully.');
            } else {

                return redirect()->route('admin.cities.index')->with('error','Something Went Wrong.');
            }

        }else{

            $geoFencByAreaType = Zone::where('city_id',$geoFenc->city_id)->where('area_type',$request->area_type)->first();

            //echo "<pre>"; print_r($geoFencByAreaType); die('999');
            //echo "<pre>"; print_r($geoFencByAreaType->toArray()); die('999');

            if(isset($geoFencByAreaType) && !empty($geoFencByAreaType) > 0) {

                $update_zone['title'] = $request->title;
                $update_zone['latlng'] =$geoFencByAreaType['latlng'].','.$request->latlng;
                $update_zone['updated_at'] = Carbon::now();


                $response = DB::table('zone')->where('id',$geoFencByAreaType['id'])->update($update_zone);

                if($response) {

                    return redirect()->route('admin.cities.index')->with('success','polygon updated successfully.');
                } else {

                    return redirect()->route('admin.cities.index')->with('error','Something Went Wrong.');
                }

               }else{

                 //die('123333');

                $insert_zone['city_id'] = $geoFenc->city_id;
                $insert_zone['area_type'] = $request->area_type;
                $insert_zone['title'] = $request->title;
                $insert_zone['center_lat'] = $request->center_lat;
                $insert_zone['center_long'] = $request->center_long;
                $insert_zone['address'] = $request->address;
                $insert_zone['latlng'] = $request->latlng;
                $insert_zone['created_at'] = Carbon::now();

                $zone_id = DB::table('zone')->insertGetId($insert_zone);

                if($zone_id) {

                    return redirect()->route('admin.cities.index')->with('success','Polygon updated successfully.');
                } else {

                    return redirect()->route('admin.cities.index')->with('error','Something Went Wrong.');
                }
            }
        }
    }


    public function remove($zone_id) {

        $geoFenc = Zone::where('id',$zone_id)->first();
        $old_geofenc = $geoFenc->latlng;             
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
                    $latLngStr .= $lt.' '.$ln.',';

                }

                $latLngStr_zone = rtrim($latLngStr,',');
                $dummy_arr[$key] = $latLngStr_zone;
                
            }
        }
       
       return view('backend.cities.zone.remove_polygon',compact('geoFenc','dummy_arr','old_geofenc','tes'));
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
        

        //$update_zone['area_type'] = $request->area_type;
        $update_zone['title'] = $request->title;
        $update_zone['center_lat'] = $request->center_lat;
        $update_zone['center_long'] = $request->center_long;
        $update_zone['address'] = $request->address;
        $update_zone['latlng'] = $new_var;
        $update_zone['updated_at'] = Carbon::now();


        $response = DB::table('zone')->where('id',$request->zone_id)->update($update_zone);

       
        if($response) {

            return redirect()->route('admin.cities.index')->with('success','Polygon updated successfully.');
        } else {

            return redirect()->route('admin.cities.index')->with('error','Something Went Wrong.');
        }

    }

    public function allZoneByCity($city_id) {
        //die('allZoneByCity');

        $all_zones = Zone::where('city_id',$city_id)->where('deleted_at',NULL)->get();
        // $all_low_zones = Zone::where('city_id',$city_id)->where('area_type','low_resources_area')->where('deleted_at',NULL)->get();
        // $all_avg_zones = Zone::where('city_id',$city_id)->where('area_type','avg_resources_area')->where('deleted_at',NULL)->get();
        // $all_high_zones = Zone::where('city_id',$city_id)->where('area_type','high_resources_area')->where('deleted_at',NULL)->get();


        $all_low_zones_2 = Zone::where('city_id',$city_id)->where('area_type','low_resources_area_2')->where('deleted_at',NULL)->get();
        $all_avg_zones = Zone::where('city_id',$city_id)->where('area_type','avg_resources_area')->where('deleted_at',NULL)->get();
        $all_high_zones_1 = Zone::where('city_id',$city_id)->where('area_type','high_resources_area_1')->where('deleted_at',NULL)->get();
        $all_high_zones_2 = Zone::where('city_id',$city_id)->where('area_type','high_resources_area_2')->where('deleted_at',NULL)->get();

        // echo "<pre>"; print_r($all_zones->toArray()); echo "<br>";
        // echo "<pre>"; print_r($all_low_zones->toArray()); echo "<br>";
        // echo "<pre>"; print_r($all_avg_zones->toArray()); echo "<br>";
         //echo "<pre>"; print_r($all_high_zones_2->toArray()); echo "<br>"; die('here');

        $ay_1 = '';
        $ay12_1 = '';
        $ay24_1 = '';
        $ay34_1 = '';

        if(isset($all_low_zones_2) && count($all_low_zones_2) > 0)
        {

            foreach ($all_low_zones_2 as $k_low => $v_low) {
                if($k_low == 0){

                   $ay_1 =  $v_low->latlng;
                }else{
                   $ay_1 =  $ay_1.','.$v_low->latlng;  

                }
            } 

        } 

        if(isset($all_avg_zones) && count($all_avg_zones) > 0){

            foreach ($all_avg_zones as $k_avg => $v_avg) {
                
                if($k_avg == 0){

                   $ay12_1 =  $v_avg->latlng;
                }else{
                   $ay12_1 =  $ay12_1.','.$v_avg->latlng;  

                }
                
            } 
        }


        if(isset($all_high_zones_1) && count($all_high_zones_1) > 0)
        {
            
            foreach ($all_high_zones_1 as $k_hig_1 => $v_hig_1) {
                if($k_hig_1 == 0){

                    $ay24_1 =  $v_hig_1->latlng;
                }else{
                    $ay24_1 =  $ay24_1.','.$v_hig_1->latlng;  

                }
                      
            }
        }


        if(isset($all_high_zones_2) && count($all_high_zones_2) > 0)
        {
            
            foreach ($all_high_zones_2 as $k_hig_2 => $v_hig_2) {
                if($k_hig_2 == 0){

                    $ay34_1 =  $v_hig_2->latlng;
                }else{
                    $ay34_1 =  $ay34_1.','.$v_hig_2->latlng;  

                }
                      
            }
        }

     
        $dummy_arr = array();
        $dummy_arr12 = array();
        $dummy_arr24 =  array();
        $dummy_arr34 =  array();

        if(!empty($all_zones)){

            if(isset($all_low_zones_2) && count($all_low_zones_2) > 0){

                $ay = '['.$ay_1.']';
               
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
            }
            
            if(isset($all_avg_zones) && count($all_avg_zones) > 0){

                $ay12 = '['.$ay12_1.']';
                   
               
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
            }


            if(isset($all_high_zones_1) && count($all_high_zones_1) > 0){

                $ay24 = '['.$ay24_1.']';
       
               
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

            if(isset($all_high_zones_2) && count($all_high_zones_2) > 0){ 

                $ay34 = '['.$ay34_1.']';
                
               
                $latLngArr34 = json_decode($ay34,true);
                 
                foreach ($latLngArr34 as $key34 => $value34) {
                    $latLngStr_zone34 = '';
                    $latLngStr34 = '';
                    foreach ($value34 as $ke34 => $val34) {
                        $lt34 = !empty($val34[0])?$val34[0]:'';
                        $ln34 = !empty($val34[1])?$val34[1]:'';
                        $latLngStr34 .= $lt34.' '.$ln34.',';
                    }

                    $latLngStr_zone34 = rtrim($latLngStr34,',');
                    $dummy_arr34[$key34] = $latLngStr_zone34;
                    
                }
            }



        }

        //  echo "<pre>"; print_r($dummy_arr); echo "<br>";
        // echo "<pre>"; print_r($dummy_arr12); echo "<br>";
        // echo "<pre>"; print_r($dummy_arr24); echo "<br>";
        //  echo "<pre>"; print_r($dummy_arr34); echo "<br>";
        // die('here33');

        // echo "<pre>"; print_r($dummy_arr24); die;

      
       return view('backend.cities.zone.all_zone_view_by_city',compact('dummy_arr','dummy_arr12','dummy_arr24','dummy_arr34'));

    }


    public function allZones() {
       

        $all_zones = Zone::where('deleted_at',NULL)->get();
        
        // $all_low_zones = Zone::where('area_type','low_resources_area')->where('deleted_at',NULL)->get();
        // $all_avg_zones = Zone::where('area_type','avg_resources_area')->where('deleted_at',NULL)->get();
        // $all_high_zones = Zone::where('area_type','high_resources_area')->where('deleted_at',NULL)->get();

        $all_low_zones_2 = Zone::where('area_type','low_resources_area_2')->where('deleted_at',NULL)->get();
        $all_avg_zones = Zone::where('area_type','avg_resources_area')->where('deleted_at',NULL)->get();
        $all_high_zones_1 = Zone::where('area_type','high_resources_area_1')->where('deleted_at',NULL)->get();        
        $all_high_zones_2 = Zone::where('area_type','high_resources_area_2')->where('deleted_at',NULL)->get();

        //echo "<pre>"; print_r($all_low_zones); die;

        $ay_1 = '';
        $ay12_1 = '';
        $ay24_1 = '';
        $ay34_1 = '';

        if(isset($all_low_zones_2) && count($all_low_zones_2) > 0)
        {

            foreach ($all_low_zones_2 as $k_low => $v_low) {
                if($k_low == 0){

                   $ay_1 =  $v_low->latlng;
                }else{
                   $ay_1 =  $ay_1.','.$v_low->latlng;  

                }
            }
        }


        if(isset($all_avg_zones) && count($all_avg_zones) > 0)
        {
            
            foreach ($all_avg_zones as $k_avg => $v_avg) {
                
                if($k_avg == 0){

                   $ay12_1 =  $v_avg->latlng;

                }else{

                   $ay12_1 =  $ay12_1.','.$v_avg->latlng;  

                }
                
            } 
        } 


        if(isset($all_high_zones_1) && count($all_high_zones_1) > 0)
        {
            
            foreach ($all_high_zones_1 as $k_hig_1 => $v_hig_1) {
                if($k_hig_1 == 0){

                    $ay24_1 =  $v_hig_1->latlng;
                }else{
                    $ay24_1 =  $ay24_1.','.$v_hig_1->latlng;  

                }
                      
            }
        }


        if(isset($all_high_zones_2) && count($all_high_zones_2) > 0)
        {
            
            foreach ($all_high_zones_2 as $k_hig_2 => $v_hig_2) {
                if($k_hig_2 == 0){

                    $ay34_1 =  $v_hig_2->latlng;
                }else{
                    $ay34_1 =  $ay34_1.','.$v_hig_2->latlng;  

                }
                      
            }
        }

        $dummy_arr = array();
        $dummy_arr12 = array();
        $dummy_arr24 =  array();
        $dummy_arr34 =  array();
        if(!empty($all_zones)){
            
            if(isset($all_low_zones_2) && count($all_low_zones_2) > 0){

                $ay = '['.$ay_1.']';
               
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
            }            

            if(isset($all_avg_zones) && count($all_avg_zones) > 0){

                $ay12 = '['.$ay12_1.']';
                   
               
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
            }

   
            if(isset($all_high_zones_1) && count($all_high_zones_1) > 0){

                $ay24 = '['.$ay24_1.']';
                
               
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

            if(isset($all_high_zones_2) && count($all_high_zones_2) > 0){

                $ay34 = '['.$ay34_1.']';
                
                $latLngArr34 = json_decode($ay34,true);
                 
                foreach ($latLngArr34 as $key34 => $value34) {
                    $latLngStr_zone34 = '';
                    $latLngStr34 = '';
                    foreach ($value34 as $ke34 => $val34) {
                        $lt34 = !empty($val34[0])?$val34[0]:'';
                        $ln34 = !empty($val34[1])?$val34[1]:'';
                        $latLngStr34 .= $lt34.' '.$ln34.',';
                    }

                    $latLngStr_zone34 = rtrim($latLngStr34,',');
                    $dummy_arr34[$key34] = $latLngStr_zone34;
                    
                }
            }


            //echo "<pre>"; print_r($dummy_arr24); die('sdf');
        }
      
       return view('backend.cities.zone.all_zone_view',compact('dummy_arr','dummy_arr12','dummy_arr24','dummy_arr34'));

    }

    public function destroyZone($zone_id)
    {
        $updateArr['zone_id'] = Carbon::now();
        Zone::where('id',$zone_id)->delete($updateArr);

        return redirect()->route('admin.cities.index')->with('success','Polygon deleted successfully');
    }

    

}