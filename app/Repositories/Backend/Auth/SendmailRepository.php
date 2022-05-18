<?php

namespace App\Repositories\Backend\Auth;

use App\Events\Backend\Auth\Role\RoleCreated;
use App\Events\Backend\Auth\Role\RoleUpdated;
use App\Exceptions\GeneralException;
use App\Models\Auth\Role;
use App\Mail\newsletterMail;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


/**
 * Class RoleRepository.
 */
class SendmailRepository extends BaseRepository
{
    /**
     * RoleRepository constructor.
     *
     * @param  Role  $model
     */


    /**
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Throwable
     * @return Role
     */
    public function sendMailToSubscribers($data)
    {



        $ins['token'] = md5(uniqid(mt_rand(), true));
        $toEmail=$data['mail'];
      //  $bcc=$data['bcc'];
        //print_r($bcc);die;
        $sb_id=3;
        $subject=$data['mailsubject'];
        $message=$data['message'];
        $token = $ins['token'];


            Mail::to($toEmail)->send(new newsletterMail($toEmail,$sb_id,$token,$subject,$message));
            return $subject;


    }

      public function sendToAll($data)
     {

       // echo $data;die;
        //($data);die;
      //  print_r($data);die;


       // $ins['program_manager_id'] = Crypt::decrypt($data['pm']);
        // $ins['mail'] = $data['mail'];;
        // $ins['mailsubject'] = $data['mailsubject'];
        // $ins['message'] = $data['message'];

        $ins['token'] = md5(uniqid(mt_rand(), true));
       // $ins['status'] = 0;
       // $ins['created_at'] = Carbon::now();
        //$datain = DB::table('link_mentee_by_program_manager')->insert($ins);

      // $token = $ins['token'];
        $toEmail=$data['mail'];
        $sb_id=3;
        $subject=$data['mailsubject'];
        $message=$data['message'];
        $token = $ins['token'];

       // if($datain)
        //{
            Mail::to($toEmail)->send(new newsletterMail($toEmail,$sb_id,$token,$subject,$message));
            return $subject;
        //}

    }

    /**
     * @param Role  $role
     * @param array $data
     *
     * @throws GeneralException
     * @throws \Throwable
     * @return mixed
     */


    /**
     * @param $name
     *
     * @return bool
     */

}
