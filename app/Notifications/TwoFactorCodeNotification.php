<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCodeNotification extends Notification // implements ShouldQueue
{
    use Queueable;

    protected $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Login Verification Code')
            ->greeting('Hello ' . $notifiable->first_name . '!')
            ->line('Your verification code is:')
            ->line('**' . $this->code . '**')
            ->line('This code will expire in 10 minutes.')
            ->line('If you didn’t request this code, you can safely ignore this email.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Two-Factor Authentication Code',
            'message' => 'A verification code (' . $this->code . ') has been sent to your email address.',
            'time' => now()->format('F j, Y h:i A'),
        ];
    }
}
