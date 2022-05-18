<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class newsletterMail extends Mailable
{
    use Queueable, SerializesModels;
    public $toEmail;
    public $sb_id;
    public $token;
    public $subject;
    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($toEmail,$sb_id,$token,$message,$subject)
    {
        $this->toEmail = $toEmail;
        //$this->bcc = $toEmail;
        $this->sb_id = $sb_id;
        $this->token = $token;
        $this->subject = $subject;
        $this->message = $message;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
   /* public function build()
    {
        return $this->to(config('mail.from.address'), config('mail.from.name'))
                    ->view('frontend.mail.forgot_password_mail')
                    ->text('frontend.mail.forgot_password_mail')
                    ->subject(__('Forgot Password Request', [' ' => app_name()]));

    }*/

        public function build()
       {
          return $this->view('backend.newsletter.mail')->with('data', $this->subject);
      }
}

