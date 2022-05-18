<?php

namespace App\Notifications\Frontend\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class UserNeedsPasswordReset.
 */
class UserNeedsPasswordReset extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * UserNeedsPasswordReset constructor.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $notifiable->buskalologo=url('img/logo/logo-svg.png');
        $notifiable->user_icon=url('img/logo/logo.jpg');
       // echo '<pre>'; print_r($notifiable); exit;
        return (new MailMessage())
            ->subject(__('strings.emails.auth.password_reset_subject'))
            ->line(__('Hemos recibido una solicitud para reestablecer la contraseña de tu cuenta.<br/>
                Haz un clic en el botón o en el link <a href="'.route('frontend.auth.password.reset.form', $this->token).'">aquí</a> para restablecerla.'))
            //->line(__('strings.emails.auth.password_cause_of_email'))
            ->action(__('buttons.emails.auth.reset_password'), route('frontend.auth.password.reset.form', $this->token))
            ->email($notifiable->email)
            ->username($notifiable->username)
            ->avatar_location($notifiable->avatar_location)
            ->user_group_id($notifiable->user_group_id)
            ->buskalologo($notifiable->buskalologo)
            ->user_icon($notifiable->user_icon);
            //->line(__('strings.emails.auth.password_if_not_requested'));
    }
}
