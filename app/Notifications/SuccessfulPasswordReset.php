<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\TwilioChannel;

class SuccessfulPasswordReset extends Notification // implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {

    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Reset Successful')
            ->greeting('Greetings!')
            ->line('Your password was successfully reset.')
            ->line('If you did not perform this action, please contact support or reset it again immediately.')
            ->salutation('Thank you for using ' . config('app.name') . '!');
    }

    public function toTwilio(object $notifiable): string
    {
        return
            'Hi ' . $notifiable->name . ', your password has been successfully reset. '
             . 'If this wasn’t you, please contact support or reset your password again immediately.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Password Reset Successful',
            'message' => 'Your password was reset successfully. If this was not you, take immediate action.',
            'time' => now()->format('F j, Y h:i A'),
        ];
    }
}
