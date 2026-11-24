<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;
use App\Notifications\Channels\TwilioChannel;

class ReservationCancelledNotification extends Notification
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
            ->subject('Reservation Cancelled')
            ->line('A reservation has been cancelled by a user.')
            ->line('Reservation ID: ' . $this->reservation->id)
            ->line('Customer: ' . $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name)
            ->line('Accommodation: ' . $this->reservation->accommodation->name)
            ->line('Please review this cancellation.');
    }

    public function toTwilio($notifiable)
    {
        return
            'Alert: Reservation Cancelled! ' .
            'Reservation ID: ' . $this->reservation->id . '. ' .
            'Customer: ' . $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name . '. ' .
            'Accommodation: ' . $this->reservation->accommodation->name . '.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Reservation Cancelled',
            'message' =>
                'Reservation #' . $this->reservation->id .
                ' for ' . $this->reservation->accommodation->name .
                ' has been cancelled by ' . $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name . '.',
            'date' => now()->format('F j, Y g:i A'),
        ];
    }
}
