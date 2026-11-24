<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;
use App\Notifications\Channels\TwilioChannel;

class ReservationApprovedNotification extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Reservation is Approved')
            ->line('Good news! Your reservation has been approved.')
            ->line('Reservation ID: ' . $this->reservation->id)
            ->line('Accommodation: ' . $this->reservation->accommodation->name)
            ->line('We look forward to hosting you!');
    }

    public function toTwilio($notifiable)
    {
        return
            'Your reservation has been approved! ' .
            'Reservation ID: ' . $this->reservation->id . '. ' .
            'Accommodation: ' . $this->reservation->accommodation->name . '.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Reservation Approved',
            'message' =>
                'Your reservation #' . $this->reservation->id .
                ' for ' . $this->reservation->accommodation->name .
                ' has been approved.',
            'date' => now()->format('F j, Y g:i A'),
        ];
    }
}
