<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification // implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user)
    {

    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = URL::signedRoute('verification.verify', [
            'id' => $this->user->getKey(),
            'hash' => sha1($this->user->getEmailForVerification()),
        ]);

        return (new MailMessage)
            ->subject('Verify User Account')
            ->line('Please click the button below to verify your email address. Failed attempt to verify email within 24 hours will cause the deletion of your newly registered account')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Account Email Verification',
            'message' => 'Account verification link was sent to your email address.',
            'time' => now()->format('F j, Y h:i A'),
        ];
    }
}
