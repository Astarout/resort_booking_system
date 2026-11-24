<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\TwilioChannel;

class RescheduleDeniedNotification extends Notification // implements ShouldQueue
{
    use Queueable;
    protected $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reschedule Request Denied')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('Your reschedule request for reservation #' . $this->reservation->id . ' has been denied.')
            ->line('Please contact support for further assistance.');
    }

    public function toTwilio($notifiable)
    {
        return 'Hello ' . $notifiable->first_name . '! '
            . 'Unfortunately, your reschedule request for reservation #'
            . $this->reservation->id . ' at '
            . $this->reservation->accommodation->name
            . ' has been denied. Please contact our support team for assistance.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Reschedule Request Denied',
            'message' => 'Your reschedule request for reservation #'
                . $this->reservation->id
                . ' at ' . $this->reservation->accommodation->name
                . ' has been denied. Please contact support for more details.',
            'date' => now()->format('F j, Y h:i A'),
        ];
    }
}
