<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Events\Frontend\Auth\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use App\Models\Bonus;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;


/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(home_route());
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        abort_unless(config('access.registration'), 404);

        return view('frontend.auth.register');
    }

    public function otpVerify(Request $request,$userid)
    {

         $userids = Crypt::decrypt($userid);
         return view('frontend.auth.otp_verify')->withUserid($userids);
    }

    public function verifyOtp(Request $request)
    {
        
         $userid = isset($request->userid) && !empty($request->userid) ? $request->userid : '' ;
         $otpvalue = isset($request->otpvalue) && !empty($request->otpvalue) ? $request->otpvalue : '' ;
         $otp=implode('', $otpvalue);
        if($otp==1111) 
        {
            $updateOtp['active'] = 1;
            $updateOtp['is_verified'] =1;
            $updateOtp['confirmed'] = 1;
            $updateOtp['updated_at'] = Carbon::now()->toDateTimeString();
            if(DB::table('users')->where('id',$userid)->update($updateOtp))
            {
                $addBonus['user_id'] = $userid;
                $addBonus['transaction_date'] = Carbon::now()->toDateTimeString();
                $addBonus['debit'] = '20';
                $addBonus['credit'] = '0';
                $addBonus['current_balance'] = '20';
                $addBonus['updated_at'] = Carbon::now()->toDateTimeString();
                $addBonusToContractor = DB::table('bonus')->insert($addBonus);
            }

            auth()->loginUsingId($userid,true);

            return redirect($this->redirectPath())->withFlashSuccess(__('Your profile created succesfully and bonus added in your wallet.'));
        }
        else
        {
            $userid = Crypt::encrypt($userid);
            return redirect()->route('frontend.auth.register.otpVerify', [$userid])->withFlashDanger(__('Your otp is wrong'));
            
        }
            
    }

    /**
     * @param RegisterRequest $request
     *
     * @throws \Throwable
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(RegisterRequest $request)
    {   
               

        abort_unless(config('access.registration'), 404);

        
        $user = $this->userRepository->create($request->only('username', 'email', 'mobile_number', 'password','user_group_id'));
        
        // If the user must confirm their email or their account requires approval,
        // create the account but don't log them in.
        if (config('access.users.confirm_email') || config('access.users.requires_approval')) 
        {
            event(new UserRegistered($user));
            
            // return redirect($this->redirectPath())->withFlashSuccess(
            //     config('access.users.requires_approval') ?
            //         __('exceptions.frontend.auth.confirmation.created_pending') :
            //         __('exceptions.frontend.auth.confirmation.created_confirm')
            // );
        }

        // If the user must confirm their mobbile or their account requires approval,
        // create the account but don't log them in.
        // if (config('access.users.confirm_otp')) 
        // {
        //     //return redirect()->route('frontend.auth.register.otpVerify')->with(['userid' => $user->id]);
        //     $userid = Crypt::encrypt($user->id);
        //     return redirect()->route('frontend.auth.register.otpVerify', [$userid]);
        // }

            $updateOtp['active'] = 1;
            $updateOtp['is_verified'] =0;
            $updateOtp['confirmed'] = 0;
            $updateOtp['updated_at'] = Carbon::now()->toDateTimeString();
            if(DB::table('users')->where('id',$user->id)->update($updateOtp))
            {
                $addBonus['user_id'] = $user->id;
                $addBonus['transaction_date'] = Carbon::now()->toDateTimeString();
                $addBonus['debit'] = '20';
                $addBonus['credit'] = '0';
                $addBonus['current_balance'] = '20';
                $addBonus['updated_at'] = Carbon::now()->toDateTimeString();
                $addBonusToContractor = DB::table('bonus')->insert($addBonus);
            }

            $user_group_id=$user->user_group_id;
            $userId = Crypt::encrypt($user->id);
           

            if($user->user_group_id=='3')
            {
                return redirect()->route('frontend.redirect_contractor', [$userId]);

            }
            else if($user->user_group_id=='4')
            {
                return redirect()->route('frontend.redirect_company', [$userId]);
            }
        // auth()->login($user);

        // event(new UserRegistered($user));

        // return redirect($this->redirectPath());



    }
}
