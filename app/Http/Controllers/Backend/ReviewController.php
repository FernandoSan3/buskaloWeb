<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;

class ReviewController extends Controller
{

    public function index()
    {
         $review_datas = DB::table('reviews')
            ->join('users as U1','reviews.user_id','=','U1.id')
            ->join('users as U2','reviews.to_user_id','=','U2.id')
            ->join('service_request','reviews.request_id','=','service_request.id')
            ->select('U1.username','U1.mobile_number','U2.username as provider_name','reviews.*')
            ->where('reviews.deleted_at',NULL)
            ->paginate(25);

          //echo "<pre>"; print_r($review_datas); die;

        return view('backend.review.index',compact('review_datas'));
    }

  public function show($request_id)
    {

      $review_detail = DB::table('reviews')
      ->join('users as U1','reviews.user_id','=','U1.id')
      ->join('users as U2','reviews.to_user_id','=','U2.id')
      ->join('service_request','reviews.request_id','=','service_request.id')
     // ->join('category','service_request.category_id','=','category.id')
      //->join('services','service_request.service_id','=','services.id')
      //->join('sub_services','service_request.sub_service_id','=','sub_services.id')
      //->join('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')

      ->select('U1.username','U1.mobile_number','U2.username as provider_name','reviews.*','reviews.to_user_id','service_request.username as request_user')
      ->where('reviews.id',$request_id)
      ->first();

       //dd($review_detail);
       if($review_detail) {

          $role_detail = DB::table('roles')
            ->where('id',$review_detail->to_user_id)
            ->select('name')
            ->first();

         }

       //echo "<pre>"; print_r($review_detail); die;
      return view('backend.review.show',compact('review_detail','role_detail'));

    }

    public function status($id=null)
    {
        $review_detail = DB::table('reviews')->where('id',$id)->first();

        if($review_detail->admin_appovel==1)
        {
            $status=0;
        }
        else
        {
            $status=1;
        }
        DB::table('reviews')->where('id',$id)->update(['admin_appovel'=>$status]);
        return redirect()->to('admin/review')->with('success','Rating status update successfully.');
    }

}