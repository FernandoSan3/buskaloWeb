<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class NewOpportunity extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $demo;

    /**
     * The demo object instance.
     *
     * @var Question
     */
    public $question;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo, $question)
    {
        $this->demo = $demo;
        $this->question = $question;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(config('mail.from.address'), config('mail.from.name'))
        ->view('frontend.mail.new_opportunity')
        ->text('frontend.mail.new_opportunity')
        ->subject(__('Tienes una nueva oportunidad', ['app_name' => app_name()]));
                   
    }
}