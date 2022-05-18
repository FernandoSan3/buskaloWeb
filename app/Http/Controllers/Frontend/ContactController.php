<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use App\Mail\Frontend\Contact\SendContact;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use DB;

date_default_timezone_set(isset($_COOKIE["fcookie"])?$_COOKIE["fcookie"]:'America/Guayaquil');
/**
 * Class ContactController.
 */
class ContactController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $cotactTouch=DB::table('site_settings')->first();
        return view('frontend.contact-us', compact('cotactTouch'));
    }

    /**
     * @param SendContactRequest $request
     *
     * @return mixed
     */
    public function send(SendContactRequest $request)
    {
            $contact['name'] = $request->name;
            $contact['email'] = $request->email;
            $contact['contact_number'] = $request->phone;
            $contact['address'] = $request->address;
            $contact['description'] = $request->message;
            $contact['created_at']     = Carbon::now();
            $contact['updated_at'] = Carbon::now();

            $insetcontact_data= DB::table('contact_us')->insert($contact);
             if(isset($request->subscribe) && !empty($request->subscribe))
             {

                $news_letter['email'] = $request->email;
                $news_letter['user_type'] = $request->user_type;
                $news_letter['created_at']     = Carbon::now();
                $newsletter_data= DB::table('newslettre_subscriber')->insert($news_letter);
             }


            Mail::send(new SendContact($request));

        return redirect()->back()->withFlashSuccess(__('alerts.frontend.contact.sent'));
    }
}
