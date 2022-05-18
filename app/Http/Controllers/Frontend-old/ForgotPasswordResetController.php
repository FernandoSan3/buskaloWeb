<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use App\Mail\Frontend\Contact\SendContact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB, Redirect, Response, Session;
use Hash, File;
/**
 * Class forgotPasswordController.
 */
class ForgotPasswordResetController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index($emailId,$token)
    {
        
        return view('frontend.forgot_password')
            ->withEmailId($emailId)
            ->withToken($token);

    }

    public function resetPassword(Request $request)
    {

        $token = !empty($request->token) ? $request->token : '' ;
        $password = !empty($request->password) ? $request->password : '' ;
        $confirm_password = !empty($request->password_confirmation) ? $request->password_confirmation : '' ;
        $emailId = !empty($request->emailId) ? $request->emailId : '' ;
        $email = Crypt::decrypt($emailId);


        if(isset($email) && !empty($email) && isset($token) && !empty($token) && isset($password) && !empty($password) && isset($confirm_password) && !empty($confirm_password))
        {

            

            $user_arr = DB::table('users')->where('email',$email)->first();
            if(!empty($user_arr))
            {
            $ChkTokn  = DB::table('password_resets')
            ->where('email',$email)
            ->where('token',$token)
            ->first();

            if($ChkTokn->token==$token)
            {

                if($ChkTokn->email==$email)
                {

                if($ChkTokn->is_updated=='1')
                {
                
               return redirect()->route('frontend.index')->withFlashDanger(__('Please Request for another token, this token is already used.'));
                }
                else
                {
               
                    if($password===$confirm_password) 
                    {   

                    $username = isset($user_arr->username) && !empty($user_arr->username) ? $user_arr->username:'';

                    $updatePass['password'] = trim(Hash::make($password));
                    $updatePass['updated_at'] = Carbon::now()->toDateTimeString();

                    $gd=DB::table('users')->where('id',$user_arr->id)->update($updatePass);

                        if($gd==1 OR $gd==true)
                        {
                        $updateUser_arr['is_updated'] = "1";
                        $updateUser_arr['updated_at'] = Carbon::now()->toDateTimeString();

                        DB::table('password_resets')
                        ->where('token',$ChkTokn->token)
                        ->where('email',$ChkTokn->email)
                        ->update($updateUser_arr);
                        }
                    
                    return redirect()->route('frontend.index')->withFlashSuccess(__('Your Password Updated successfully.'));

                    }
                    else
                    {
                    return redirect()->back()->withFlashDanger(__('Password and confirm password does not similar.'));
                    }

                }

                }
                else
                {

                return redirect()->route('frontend.index')->withFlashDanger(__('Email-id not exist in our database.'));

                }

            }
            else
            {

             return redirect()->route('frontend.index')->withFlashDanger(__('Invalid Token.'));

            }

            }
            else
            {
              return redirect()->route('frontend.index')->withFlashDanger(__('Email-id not exist in our database.'));
            }
        }
        else
        {
             return redirect()->route('frontend.index')->withFlashDanger(__('Invalid parameter.'));
        }

    
    }
    
}
?>