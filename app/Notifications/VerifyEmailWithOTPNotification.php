<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailWithOTPNotification extends Notification
{
    use Queueable;

    private Authenticatable $user;

    private string $otp;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Authenticatable $user, string $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Verify your email address')
                    ->greeting('Hi '.$this->user->name.',')
                    ->line('Thank you for applying for a Tech1M account.')
                    ->line('To help us confirm it’s you, please verify your email address.')
                    ->line('Your verification code is: '.$this->otp)
                    ->line('Contact us if you were not expecting this email or think you received it in error')
                    ->salutation('Thank you for using Tech1M!')
                    ->tag('otp');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
