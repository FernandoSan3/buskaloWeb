<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Contact\SendContactRequest;
use App\Mail\Frontend\Newsletter\SendNewsletter;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;


/**
 * Class ContactController.
 */
class NewsletterController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    /*public function index()
    {
        return view('frontend.contact-us');
    }
*/
    /**
     * @param SendContactRequest $request
     *
     * @return mixed
     */
    public function subscribeNewsletter(Request $request){

      $checkemail= DB::table('newslettre_subscriber')->where('email',$request->email)->first();

       if(isset($checkemail) && !empty($checkemail))
       {
          return redirect()->back()->withFlashDanger('you have already subscribed');
       }

            $news_letter['email'] = $request->email;
            $news_letter['created_at']     = Carbon::now();
            $news_letter['updated_at'] = Carbon::now();


            $newsletter_data= DB::table('newslettre_subscriber')->insert($news_letter);

            Mail::send(new SendNewsletter($request));

            return redirect()->back()->withFlashSuccess('Thanks for subscribing');
    }
}
