<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contactus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use DB;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;


class ContactUSController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $cities = Cities::latest()->where('deleted_at',NULL)->paginate(5);
        // return view('backend.cities.index',compact('cities'));

        $contacts = Contactus::
         all();
        //->paginate(25);
        return view('backend.contactus.index',compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
    public function show($contactus_id)
    {
        $contact_descriptions = DB::table('contact_us')
        ->where('id',$contactus_id)
        ->first();

        return view('backend.contactus.show',compact('contact_descriptions'));
    }


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

   public function sendMailToUsers(Request $request)
    {
         $this->validate($request, [
        'pm' => 'required',
        'program_id' => 'required',
        'mentee_type' => 'required',
        'mentor_id' => 'required',
        'email' =>'required'
        //'email' => ['max:255', Rule::unique('users')]
         ]);

        $toEmail =  $request->email;
        $pm_id =  $request->pm;
        $mentor_id =  $request->mentor_id;

        $mentee_type =  $request->mentee_type;
        $mentee_id =  $request->mentee_id;

        if($mentee_type == 'new_mentee') {
          if(!empty($toEmail) && !empty($pm_id) && !empty($mentor_id))
          {

            $data = $this->linkMenteeByPm->sendMailToLinkMentee($request);
            return redirect()->route('frontend.prg.program-manager.dashboard')->withFlashSuccess(__('Send Success'));
          }

        } else {

          $data = $this->linkMenteeByPm->sendMailToLinkMenteeExisting($request);

          if($data == 'Error')
          {
            return redirect()->route('frontend.prg.program-manager.link-to-mentee')->withFlashSuccess(__('Mentee Already Exist In this Program'));
          } else {
             return redirect()->route('frontend.prg.program-manager.dashboard')->withFlashSuccess(__('Send Success'));

          }

        }

}

public function basic_email() {
        $data['title'] = "This is Test Mail w3path";

        Mail::send('backend.contactus.sendmail', $data, function($message) {

            $message->to('ritusharma3108@gmail.com', 'Receiver Name')

                    ->subject('Test Mail');
        });

             echo "sucess";
    }

}
