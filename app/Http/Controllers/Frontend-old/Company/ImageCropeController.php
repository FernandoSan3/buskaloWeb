<?php

namespace App\Http\Controllers\Frontend\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
/**
 * Class ProfileController.
 */
class ImageCropeController extends Controller
{

 public function imageupload(){

      return view('frontend.company.image1');
 }
public function imageuploadpost1(Request $request)
            {
                $avatar_location = !empty($request->avatar_location) ? $request->avatar_location : '' ;
                $userid= auth()->user()->id;

                $userEntity = DB::table('users')
                    ->whereRaw("(active=1)")->whereRaw("(confirmed=1)")->whereRaw("(is_verified=1)")
                    ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();

                    if(!empty($userEntity))
                    {
                        $profile = $userEntity->avatar_location;

                        if(isset($_FILES['avatar_location']['name']) && !empty($_FILES['avatar_location']['name']))
                            {
                                $extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
                                $filename = $userid.'.'.$extq;

                                $ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);

                                 $image_array_1 = explode(";", $avatar_location);
      $image_array_2 = explode(",", $image_array_1[1]);
      $data = base64_decode($image_array_2[1]);
      $image_name = time() . '.png';
      $upload_path = public_path('img/company/profile/' .time().".jpg");
      file_put_contents($upload_path, $data);

                               // $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/company/profile/'.$filename);

                                 $profile = $filename;
                            }

                        $userData['avatar_location'] =  $profile;
                        $userData['updated_at'] = Carbon::now()->toDateTimeString();
                        DB::table('users')->where('id',$userEntity->id)->update($userData);

                       return redirect()->route('frontend.company.company_profile')->withFlashSuccess(__('Profile Picture Updated Successfully.!'));
                    }
                    else
                    {
                      return redirect()->route('frontend.company.company_profile')->withFlashDanger(__('Invalid user.'));

                    }
            }
   public function imageuploadpost(Request $request)
   {

      $avatar_location = !empty($request->avatar_location) ? $request->avatar_location : '' ;

      $userid= auth()->user()->id;

      $userEntity = DB::table('users')
                  ->whereRaw("(active=1)")->whereRaw("(confirmed=1)")->whereRaw("(is_verified=1)")
                  ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();

      if(!empty($userEntity))
      {
          $profile = $userEntity->avatar_location;

            if(isset($request->avatar_location) && !empty($avatar_location))

             {
                                //$extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
                               // $filename = $userid.'.'.$extq;

                                //$ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);

                               // $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/company/profile/'.$filename);
                  $image_data = $request->avatar_location;

                  $image_array_1 = explode(";", $image_data);
                  $image_array_2 = explode(",", $image_array_1[1]);

                 $data = base64_decode($image_array_2[1]);
                 $image_name = time() . '.png';

                 $upload_path = public_path('img/company/profile/' . $userid.".jpg");

                 file_put_contents($upload_path, $data);

                  $profile = $userid.".jpg";
             }

            $userData['avatar_location'] =  $profile;
            $userData['updated_at'] = Carbon::now()->toDateTimeString();

             DB::table('users')->where('id',$userEntity->id)->update($userData);
    }

      //$extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);

     // $filename = $userid.'.'.$extq;


       //return redirect()->route('frontend.company.company_profile')->withFlashSuccess(__('Profile Picture Updated Successfully.!'));

   }

}
