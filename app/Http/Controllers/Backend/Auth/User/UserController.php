<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Events\Backend\Auth\User\UserDeleted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\User\ManageUserRequest;
use App\Http\Requests\Backend\Auth\User\StoreUserRequest;
use App\Http\Requests\Backend\Auth\User\UpdateUserRequest;
use App\Models\Auth\User;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\UserRepository;
use DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Class UserController.
 */
class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ManageUserRequest $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ManageUserRequest $request)
    {

       $users = $this->userRepository->getActivePaginated(25, 'id', 'asc');
       //echo "<pre>"; print_r($name->toArray());die;
        //return view('backend.auth.user.index')
         //   ->withUsers($this->userRepository->getActivePaginated(25, 'id', 'asc'));

       foreach ($users as $key => $value) {
        $value->total_service_requests = DB::table('service_request')
        ->join('category','service_request.category_id','=','category.id')
        ->join('services','service_request.service_id','=','services.id')
        ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->where('service_request.user_id',$value->id)
        ->count();


    }
      // echo "<pre>"; print_r($users->toArray());die;
     return view('backend.auth.user.index',compact('users'));
    //   ->withUsers($this->userRepository->getActivePaginated(25, 'id', 'asc'));

}

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     *
     * @return mixed
     */
    public function create(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        return view('backend.auth.user.create')
            ->withRoles($roleRepository->with('permissions')->get(['id', 'name']))
            ->withPermissions($permissionRepository->get(['id', 'name']));
    }

    /**
     * @param StoreUserRequest $request
     *
     * @throws \Throwable
     * @return mixed
     */
    public function store(StoreUserRequest $request)
    {
        //die('asdasda');

        $this->userRepository->create($request->only(
            'username',
            'email',
            'password',
            'active',
            'confirmed',
            'confirmation_email',
            'roles',
            'permissions'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.created'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @return mixed
     */
    public function show(ManageUserRequest $request, User $user)
    {


        $user_details = DB::table('social_networks')
        ->select('facebook_url','instagram_url','snap_chat_url','twitter_url','youtube_url')
        ->where('user_id',$user->id)
        ->first();

        // echo "<pre>"; print_r($user->picture); die;


        return view('backend.auth.user.show',compact('user_details'))
            ->withUser($user);
    }

    /**
     * @param ManageUserRequest    $request
     * @param RoleRepository       $roleRepository
     * @param PermissionRepository $permissionRepository
     * @param User                 $user
     *
     * @return mixed
     */
    public function edit(ManageUserRequest $request, RoleRepository $roleRepository, PermissionRepository $permissionRepository, User $user)
    {
        return view('backend.auth.user.edit')
            ->withUser($user)
            ->withRoles($roleRepository->get())
            ->withUserRoles($user->roles->pluck('name')->all())
            ->withPermissions($permissionRepository->get(['id', 'name']))
            ->withUserPermissions($user->permissions->pluck('name')->all());
    }

    /**
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @throws \App\Exceptions\GeneralException
     * @throws \Throwable
     * @return mixed
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userRepository->update($user, $request->only(
            'username',
            'email',
            'roles',
            'permissions'
        ));

        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated'));
    }

    /**
     * @param ManageUserRequest $request
     * @param User              $user
     *
     * @throws \Exception
     * @return mixed
     */
    public function destroy(ManageUserRequest $request, User $user)
    {
        $this->userRepository->deleteById($user->id);

        event(new UserDeleted($user));

        return redirect()->route('admin.auth.user.deleted')->withFlashSuccess(__('alerts.backend.users.deleted'));
    }


     public function editUserDetails($user)
    {

        $user_details = DB::table('users')->leftjoin('social_networks','users.id','=','social_networks.user_id')
        ->select('users.*','facebook_url','instagram_url','snap_chat_url','twitter_url','youtube_url')
        ->where('users.id',$user->id)->first();


        //echo "<pre>"; print_r($user_details); die;

        return view('backend.auth.user.edit_user_details',compact('user_details'));

    }



    public function updateUserDetails(request $request)
    {
       // die('updateeee');
       $request->validate([
            'user_id' => 'required',
            'username' => 'required',
            // 'mobile_number' => 'required',
            // 'landline_number' => 'required',
            // 'address' => 'required',
            // 'profile_description' => 'required',
            // 'facebook_url' => 'required',
            // 'instagram_url' => 'required',
            // 'snap_chat_url' => 'required',
            // 'twitter_url' => 'required',
            // 'youtube_url' => 'required',
        ]);



       $user_details = DB::table('users')->leftjoin('social_networks','users.id','=','social_networks.user_id')
        ->select('users.*','facebook_url','instagram_url','snap_chat_url','twitter_url','youtube_url')
        ->where('users.id',$request->user_id)->first();



        $imagename=""; $storeName="";

        if(!empty($request->avatar_location))
        {

          $ext = $request->avatar_location->getClientOriginalExtension();
          $store_imagename = $request->user_id.'.'.$ext;

          $destinationPath = public_path('/img/user/profile');

           if($request->avatar_location=="")
           {
              $imagename="";
            }
            else
            {
                $image = $request->avatar_location;
                $imagename =$request->user_id.'.' . $image->getClientOriginalExtension();

                $storeName=  $store_imagename;

                 if(file_exists(public_path().$destinationPath.$imagename))
                 {
                    unlink(public_path().$destinationPath.$imagename);
                    $image->move($destinationPath, $store_imagename);
                }
                 else
                 {
                    $image->move($destinationPath, $store_imagename);
                }
            }
        } else {
            $storeName = $user_details->avatar_location;
        }


        $update_arr['username'] = $request->username;
        $update_arr['avatar_location'] = $storeName;
        $update_arr['mobile_number'] = $request->mobile_number;
        $update_arr['landline_number'] = $request->landline_number;
        $update_arr['office_number'] = $request->office_number;
        $update_arr['address'] = $request->address;
        $update_arr['office_address'] = $request->office_address;
        $update_arr['dob'] = $request->dob;
        $update_arr['other_address'] = $request->other_address;
        $update_arr['profile_description'] = $request->profile_description;
        $update_arr['updated_at'] = Carbon::now();

        $user_id = DB::table('users')->where('id',$request->user_id)->update($update_arr);

        if($user_id) {

            //die('njkjk');
            $social_detail = DB::table('social_networks')->where('user_id',$request->user_id)->first();

            if($social_detail) {
                $social_arr['facebook_url'] = $request->facebook_url;
                $social_arr['instagram_url'] = $request->instagram_url;
                $social_arr['snap_chat_url'] = $request->snap_chat_url;
                $social_arr['twitter_url'] = $request->twitter_url;
                $social_arr['youtube_url'] = $request->youtube_url;
                $social_arr['updated_at'] = Carbon::now();

                DB::table('social_networks')->where('user_id',$request->user_id)->update($social_arr);

            } else {
                $social_arr['user_id'] = $request->user_id;
                $social_arr['facebook_url'] = $request->facebook_url;
                $social_arr['instagram_url'] = $request->instagram_url;
                $social_arr['snap_chat_url'] = $request->snap_chat_url;
                $social_arr['twitter_url'] = $request->twitter_url;
                $social_arr['youtube_url'] = $request->youtube_url;
                $social_arr['updated_at'] = Carbon::now();


                DB::table('social_networks')->insert($social_arr);
            }




        }


        return redirect()->route('admin.auth.user.index')->withFlashSuccess(__('alerts.backend.users.updated'));


    }

    public function userServiceRequest($user){
        $user_id = $user->id;

        $service_requests = DB::table('service_request')
        ->join('category','service_request.category_id','=','category.id')
        ->join('services','service_request.service_id','=','services.id')
        ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name')
        ->where('service_request.user_id',$user_id)
        ->paginate(25);

        //echo "<pre>"; print_r($service_requests);die;


       return view('backend.auth.user.service_request',compact('service_requests','user_id'));


    }



      public function showServiceRequest($request_id)
    {
        //echo "<pre>";print_r($request_id);die;
         //$request_id = 148;

       $show_service = DB::table('service_request')
        ->join('services','service_request.service_id','=','services.id')
        ->leftjoin('sub_services','service_request.sub_service_id','=','sub_services.id')
        ->leftjoin('category','service_request.category_id','=','category.id')
        ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
        ->select('service_request.*','sub_services.en_name as en_subservice_name','sub_services.es_name as es_subservice_name','services.en_name as en_service_name','services.es_name as es_service_name','category.en_name as en_category_name','child_sub_services.es_name as es_child_subservice_name')
        ->where('service_request.id',$request_id)
        ->first();


        if($show_service) {

            $show_service->question_detail = DB::table('service_request_questions')
            ->join('questions','service_request_questions.question_id','=','questions.id')
            ->join('question_options','service_request_questions.option_id','=','question_options.id')
            ->select('service_request_questions.*','questions.en_title as en_question_title','questions.es_title as es_question_title','question_options.en_option as en_option_name','question_options.es_option as es_option_name')
            ->where('service_request_questions.deleted_at',NULL)
            ->where('service_request_questions.service_request_id',$request_id)
            ->get();

      }


       $user_details = DB::table('buy_requested_services')
            ->join('users','buy_requested_services.user_id','=','users.id')
            ->select('users.username','buy_requested_services.amount','buy_requested_services.tranx_id')
            ->where('buy_requested_services.requested_service_id',$request_id)
            ->get();

        //echo "<pre>"; print_r($user_details);die;


      return view('backend.auth.user.show_service_request',compact('show_service','user_details'));

    }

     public function allRequestsByStatus($status,$user_id)
    {
        //echo $status; echo "<br>"; echo $user_id; die('here');
        if($status == 'all') {

            $service_requests = DB::table('service_request')
            ->join('category','service_request.category_id','=','category.id')
            ->join('services','service_request.service_id','=','services.id')
            ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
            ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
            ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name')
            //->paginate(25);
            //->where('service_request.status',$status)
            ->where('service_request.user_id',$user_id)
            ->get();

        } else {
            $service_requests = DB::table('service_request')
            ->join('category','service_request.category_id','=','category.id')
            ->join('services','service_request.service_id','=','services.id')
            ->join('sub_services','service_request.sub_service_id','=','sub_services.id')
            ->leftjoin('child_sub_services','service_request.child_sub_service_id','=','child_sub_services.id')
            ->select('service_request.*','category.es_name as es_category_name','services.es_name as es_service_name','sub_services.es_name as es_subservice_name','child_sub_services.es_name as es_childsubservices_name')
            ->where('service_request.status',$status)
            ->where('service_request.user_id',$user_id)
            ->paginate(25);
        }

       //echo "<pre>"; print_r($service_requests);die;

        return view('backend.auth.user.servicerequest_status',compact('service_requests','user_id'));

    }


}
