<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Notifications\Channels\TwilioChannel;


class RemainingBalanceNotification extends Notification // implements ShouldQueue
{
    use Queueable;
    protected $reservation;

    public function __construct(Reservation $reservation)
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
            ->subject('Remaining Balance Reminder')
            ->greeting('Hello ' . $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name . '!')
            ->line('You still have a remaining balance of ₱' . number_format($this->reservation->remaining_balance, 2) . ' for your reservation.')
            ->line('Accommodation: ' . $this->reservation->accommodation->name)
            ->line('Desired Date: ' . Carbon::parse($this->reservation->desired_date)->format('F j, Y'))
            ->line('Please make your payment as soon as possible.');
    }

    public function toTwilio($notifiable)
    {
        $date = Carbon::parse($this->reservation->desired_date)->format('F j, Y');

        return
            'Hello ' . $this->reservation->user->first_name . '! ' .
            'You still have a remaining balance of ₱' . number_format($this->reservation->remaining_balance, 2) .
            ' for your reservation at ' . $this->reservation->accommodation->name .
            ' on ' . $date . '. Please make your payment as soon as possible.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Remaining Balance Reminder',
            'message' =>
                'Hello ' . $this->reservation->user->first_name .' ' . $this->reservation->user->last_name .
                ' You have a remaining balance of ₱' . number_format($this->reservation->remaining_balance, 2) .
                ' for ' . $this->reservation->accommodation->name .
                ' on ' . Carbon::parse($this->reservation->desired_date)->format('F j, Y') . '.',
            'time' => now()->format('F j, Y h:i A'),
        ];
    }
}
