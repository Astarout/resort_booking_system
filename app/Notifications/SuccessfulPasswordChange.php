<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\TwilioChannel;

class SuccessfulPasswordChange extends Notification // implements ShouldQueue
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
            ->subject('Your Password Has Been Changed')
            ->greeting('Greetings!')
            ->line('This is to notify you that your account password was successfully changed.')
            ->line('If you did not perform this change, please contact support immediately.')
            ->salutation('Regards, ' . config('app.name'));
    }

    public function toTwilio(object $notifiable): string
    {
        return "
            Hello {$notifiable->name}, your password has been changed successfully.
            If this wasn't you, please contact support immediately.
        ";
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Password Changed',
            'message' => 'Your account password was successfully updated.',
            'time' => now()->format('F j, Y h:i A'),
        ];
    }
}
