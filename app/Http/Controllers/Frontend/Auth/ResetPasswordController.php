<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\ResetPasswordRequest;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use DB;
use Hash,Session;
use Illuminate\Http\Request;
use Validator;
/**
 * Class ResetPasswordController.
 */
class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ChangePasswordController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param string|null $token
     *
     * @return \Illuminate\Http\Response
     */
    public function showResetForm($token = null)
    {
        if (! $token) {
            return redirect()->route('frontend.auth.password.email');
        }

        $user = $this->userRepository->findByPasswordResetToken($token);

        if ($user && resolve('auth.password.broker')->tokenExists($user, $token)) {
            return view('frontend.auth.passwords.reset')
                ->withToken($token)
                ->withEmail($user->email);
        }

        return redirect()->route('frontend.auth.password.email')
            ->withFlashDanger(__('exceptions.frontend.auth.password.reset_problem'));
    }

    public function showResetFormApp($apptoken = null)
    {


        if (!empty($apptoken))
        {
            $checktoken=DB::table('password_resets')->where('token',$apptoken)->first();
             if(empty($checktoken))
             {
                 return redirect()->route('frontend.auth.password.email')
            ->withFlashDanger(__('exceptions.frontend.auth.password.reset_problem'));

             }
             return view('frontend.auth.passwords.resetapp')
                ->withToken($apptoken)
                ->withEmail($checktoken->email);
        }
         return redirect()->route('frontend.auth.password.email')
            ->withFlashDanger(__('exceptions.frontend.auth.password.reset_problem'));

    }
    /**
     * Reset the given user's password.
     *
     * @param  ResetPasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(ResetPasswordRequest $request)
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response === Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }

     public function resetApp(Request $request)
     {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation'=>'required_with:password|same:password',
        ]);
        if($validator->fails())
        {
            return redirect()->back()->withFlashDanger(__('alerts.frontend.auth.reset_password.invalid_parameters'));exit; 
        }
        $checktoken=DB::table('password_resets')->where('token',$request->token)->first();
            $password=Hash::make($request->password);
        DB::table('users')->where('email',$checktoken->email)->update(['password'=>$password]);
        DB::table('password_resets')->where('token',$request->token)->delete();

         return redirect()->to('/login')->withFlashSuccess(__('alerts.frontend.auth.reset_password.password_successful_update'));

     }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     */
    protected function resetPassword($user, $password)
    {
        $user->password = $password;

        $user->password_changed_at = now();

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetResponse($response)
    {
        return redirect()->route(home_route())->withFlashSuccess(e(trans($response)));
    }

    public function passwordGet()
    {
        $getemail=Session::get('useremail');
        return view('frontend.auth.passwords.passwordset', compact('getemail'));
    }
    public function passwordPost(Request $request)
    {
        //print_r($request->all());exit;
        if(!empty($request->email))
        {
            $password=Hash::make($request->password);
            DB::table('users')->where('email',$request->email)->update(['password'=>$password]);
            return redirect()->route('frontend.request_success');
        }
        else
        {
            return redirect()->route('frontend.request_success');
        }
    }
}
