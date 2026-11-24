<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class PasswordResetNotification extends Notification // implements ShouldQueue
{
    use Queueable;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::route('password.reset', ['token' => $this->token, 'email' => $this->email]);

        return (new MailMessage)
            ->subject('User Password Notification')
            ->greeting('Good Day')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Password Reset Requested',
            'message' => 'A password reset link was sent to your email address.',
            'time' => now()->format('F j, Y h:i A'),
        ];
    }
}
