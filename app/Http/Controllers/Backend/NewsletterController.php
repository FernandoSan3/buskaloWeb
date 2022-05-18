<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contactus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Repositories\Backend\Auth\SendmailRepository;


class NewsletterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $linkSendmailRepository;

    public function __construct(SendmailRepository $linkSendmailRepository)
    {
      $this->linkSendmailRepository = $linkSendmailRepository;
    }


    public function index()
    {

      $newsletter_detail = DB::table('newslettre_subscriber')->get();
      //->paginate(25);
     return view('backend.newsletter.index',compact('newsletter_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($mail_id)
    {
        $mail_id = DB::table('newslettre_subscriber')
        ->where('id',$mail_id)
        ->first();

        return view('backend.newsletter.create',compact('mail_id'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



    /**
     * Display the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Services  $services
     * @return \Illuminate\Http\Response
     */



public function SendmailtToSubscribers(Request $request) {
       $validatedData = $request->validate([
        'tomail' => 'required',
        'subject' => 'required',
        'message' => 'required',

    ]);


      $tomail =  $request->tomail;
      $subject1 =  $request->subject;

     //echo $tomail, $subject1;die;

       $data = array(
             'mail'      =>  $request->tomail,
             'mailsubject'   =>   $request->subject,
             'message'   =>   $request->message

          );



        $data = $this->linkSendmailRepository->sendMailToSubscribers($data);
            return redirect()->route('admin.newsletter.index')->withFlashSuccess(__('Send Success'));


          if($data == 'Error')
          {
              return redirect()->route('admin.newsletter.index')->withFlashSuccess(__('Mail Not Send'));
          }

    }



    public function SendmailtToAllshow(Request $request) {

        $validatedData = $request->validate([
            'check' => 'required',
        ],

        [ 'check.required' => 'Mail ids required']);

         $mail_id =  $request->check;


       return view('backend.newsletter.sendmailtoall',compact('mail_id'));
  }

    public function postSendMailall(Request $request) {
        $validatedData = $request->validate([
            'tomail' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $allmailid=$request->tomail;



        $requestmail=explode(',', $allmailid);

        foreach ($requestmail as  $value) {

            $data = array(
             'mail'      =>  $value,
             'mailsubject'   => $request->subject,
             'message'   =>   $request->message,
             'bcc' => $value
            );

            $this->linkSendmailRepository->sendToAll($data);

        }

        return redirect()->route('admin.newsletter.index')->withFlashSuccess(__('Send Success'));
    }


}
