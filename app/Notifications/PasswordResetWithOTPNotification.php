<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetWithOTPNotification extends Notification
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
            ->subject('Reset Password OTP')
            ->greeting('Hi '.$this->user->name.',')
            ->line('You requested a Password Reset Token.')
            ->line('To help us confirm it’s you, please use this token to validate.')
            ->line('This otp code is valid for 5 minutes ')
            ->line('Your Password token is: '.$this->otp)
            ->line('Please disregard if you were not expecting this email or think you received it in error')
            ->salutation('Thanks')
            ->tag('password-reset');
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
