<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Http\Request;
use App\Http\Requests;
use Crypt;
use Carbon\Carbon;
use DB, Mail, Redirect, Response, Session;
use Validator;
/**
 * Class ProfileController.
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * ProfileController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UpdateProfileRequest $request
     *
     * @throws \App\Exceptions\GeneralException
     * @return mixed
     */
    public function update(UpdateProfileRequest $request)
    {
        $output = $this->userRepository->update(
            $request->user()->id,
            $request->only('first_name', 'last_name', 'email', 'avatar_type', 'avatar_location'),
            $request->has('avatar_location') ? $request->file('avatar_location') : false
        );

        // E-mail address was updated, user has to reconfirm
        if (is_array($output) && $output['email_changed']) {
            auth()->logout();

            return redirect()->route('frontend.auth.login')->withFlashInfo(__('strings.frontend.user.email_changed_notice'));
        }

        return redirect()->route('frontend.user.account')->withFlashSuccess(__('strings.frontend.user.profile_updated'));
    }

   public function updateBasicInfo(Request $request)
    {

                $userId= auth()->user()->id;

                $userEntity = DB::table('users')
                ->whereRaw("(active=1)")
                ->whereRaw("(id = '".$userId."' AND deleted_at IS null )")
                ->first();  


                if(!empty($userEntity))
                {


                    $username = !empty($request->username) ? $request->username : '' ;
                    $identity_no = !empty($request->identity_no) ? $request->identity_no : '' ;
                    $dob = !empty($request->dob) ? $request->dob : '' ;
                    $address = !empty($request->address) ? $request->address : '' ;
                    $office_address = !empty($request->office_address) ? $request->office_address : '' ;
                    $other_address = !empty($request->other_address) ? $request->other_address : '' ;
                    $mobile_number = !empty($request->mobile_number) ? $request->mobile_number : '' ;
                    $landline_number = !empty($request->landline_number) ? $request->landline_number : '' ;
                    $office_number = !empty($request->office_number) ? $request->office_number : '' ;

                 
                    $userData['username'] =  !empty($username) ? $username : $userEntity->username;
                    $userData['identity_no'] =  !empty($identity_no) ? $identity_no : $userEntity->identity_no;
                    $userData['dob'] =  !empty($dob) ? $dob : $userEntity->dob;
                    $userData['address'] =  !empty($address) ? $address : $userEntity->address;
                    $userData['office_address'] =  !empty($office_address) ? $office_address : $userEntity->office_address;
                    $userData['other_address'] =  !empty($other_address) ? $other_address : $userEntity->other_address;
                    $userData['mobile_number'] =  !empty($mobile_number) ? $mobile_number : $userEntity->mobile_number;
                    $userData['landline_number'] =  !empty($landline_number) ? $landline_number : $userEntity->landline_number;
                    $userData['office_number'] =  !empty($office_number) ? $office_number : $userEntity->office_number;
                    $userData['updated_at'] = Carbon::now()->toDateTimeString();

                    DB::table('users')->where('id',$userEntity->id)->update($userData);


                        // start social Network Record

                        if(isset($request->facebook_url) || isset($request->instagram_url) || isset($request->linkedin_url) || isset($request->twitter_url) || isset($request->youtube_url) ||  isset($request->snap_chat_url) || isset($request->other) ) 
                        {
                            $socialData['facebook_url'] =  $request->facebook_url;
                            $socialData['instagram_url'] =  $request->instagram_url;
                            $socialData['linkedin_url'] =  $request->linkedin_url;
                            $socialData['twitter_url'] =  $request->twitter_url;
                            $socialData['other'] =  $request->other;
                            $socialData['updated_at'] = Carbon::now();

                            $social_accounts = DB::table('social_networks')->where('user_id',$userEntity->id)->where('deleted_at',NULL)->first();
                            if($social_accounts) 
                            {
                                DB::table('social_networks')->where('user_id',$userEntity->id)->update($socialData);                    
                            } else 
                            {
                                $socialData['user_id'] = $userEntity->id;
                                $socialData['created_at'] = Carbon::now();
                                DB::table('social_networks')->insert($socialData);
                            }
                      
                        }
                     // end social network Record
                   

                    return redirect()->route('frontend.user.dashboard')->withFlashSuccess(__('Perfil actualizado con éxito.!'));
                }
              else
                {
                  return redirect()->route('frontend.user.dashboard')->withFlashDanger(__('Invalid user.'));

                }

            }


    public function updateUserProfilePicture(Request $request)
    {
        $avatar_location = !empty($request->avatar_location) ? $request->avatar_location : '' ;
        $userid= auth()->user()->id;

        $userEntity = DB::table('users')
            ->whereRaw("(active=1)")->whereRaw("(confirmed=1)")
            ->whereRaw("(id = '".$userid."' AND deleted_at IS null )")->first();  

        if(!empty($userEntity))
        {
            $profile = $userEntity->avatar_location;

            if(isset($_FILES['avatar_location']['name']) && !empty($_FILES['avatar_location']['name']))
                {
                    $extq = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
                    $filename = $userid.'.'.$extq;

                    $ext = pathinfo($_FILES['avatar_location']['name'], PATHINFO_EXTENSION);
                    
                    $fmove = move_uploaded_file($_FILES['avatar_location']['tmp_name'],public_path() . '/img/user/profile/'.$filename);
                   
                     $profile = $filename;
                }

            $userData['avatar_location'] =  $profile;
            $userData['updated_at'] = Carbon::now()->toDateTimeString();
            DB::table('users')->where('id',$userEntity->id)->update($userData);

           return redirect()->route('frontend.user.dashboard')->withFlashSuccess(__('Profile Picture Updated Successfully.!'));
        }
        else
        {
          return redirect()->route('frontend.user.dashboard')->withFlashDanger(__('Invalid user.'));

        }
}


}
