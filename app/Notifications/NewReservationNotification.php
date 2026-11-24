<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;
use App\Notifications\Channels\TwilioChannel;

class NewReservationNotification extends Notification
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
            ->subject('New Reservation Created')
            ->line('A new reservation has been submitted.')
            ->line('Reservation ID: ' . $this->reservation->id)
            ->line('Customer Name: ' . $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name)
            ->line('Accommodation: ' . $this->reservation->accommodation->name);
    }

    public function toTwilio($notifiable)
    {
        return
            'Admin Alert: A new reservation has been submitted. ' .
            'Reservation ID: ' . $this->reservation->id . '. ' .
            'Customer: ' . $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name .
            'Accommodation: ' . $this->reservation->accommodation->name . '.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Reservation Created',
            'message' =>
                'Reservation #' . $this->reservation->id .
                ' has been submitted by ' . $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name .
                ' for ' . $this->reservation->accommodation->name . '.',
            'date' => now()->format('F j, Y g:i A'),
        ];
    }
}
