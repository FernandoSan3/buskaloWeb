<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class WelcomeNewUser extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $demo;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo)
    {
        $this->demo = $demo;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(config('mail.from.address'), config('mail.from.name'))
                    ->view('frontend.mail.welcome_new_user')
                    ->text('frontend.mail.welcome_new_user')
                    ->subject(__('', ['app_name' => app_name()]));
                    // ->with(
                    //   [
                    //         'testVarOne' => '1',
                    //         'testVarTwo' => '2',
                    //   ])
                    //   ->attach(public_path('/img/frontend/').'/logo.png', [
                    //           'as' => 'logo.png',
                    //           'mime' => 'image/png',
                    //   ]);
    }
}

