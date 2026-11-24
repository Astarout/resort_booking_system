<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Notifications\Channels\TwilioChannel;

class TodayReservationNotification extends Notification // implements ShouldQueue
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

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reminder: Your Reservation with Casa Alvadon')
            ->greeting('Dear ' . $this->reservation->user->first_name . ',')
            ->line('This is a kind reminder of your upcoming reservation scheduled for today.')
            ->line('Here are your reservation details:')
            ->line('Accommodation: ' . $this->reservation->accommodation->name)
            ->line('Number of Guests: ' . $this->reservation->no_of_guest)
            ->line('Date: ' . Carbon::parse($this->reservation->desired_date)->format('F d, Y'))
            ->line(
                'Time: '
                . Carbon::parse($this->reservation->accommodation->check_in_time)->format('h:i A')
                . ' - '
                . Carbon::parse($this->reservation->accommodation->check_out_time)->format('h:i A')
            )
            ->line('We kindly ask that you arrive on time to ensure a smooth check-in process.')
            ->line('If you have any questions or changes, please don’t hesitate to contact us.')
            ->salutation('Warm regards, Casa Alvadon');
    }

    public function toTwilio(object $notifiable): string
    {
        $checkIn = Carbon::parse($this->reservation->accommodation->check_in_time)->format('h:i A');
        $checkOut = Carbon::parse($this->reservation->accommodation->check_out_time)->format('h:i A');
        $date = Carbon::parse($this->reservation->desired_date)->format('F j, Y');

        return 'Hi ' . $this->reservation->user->first_name . '! '
            . 'This is a reminder for your reservation at '
            . $this->reservation->accommodation->name . ' today (' . $date . '). '
            . 'Check-in: ' . $checkIn . ', Check-out: ' . $checkOut . '. '
            . 'Guests: ' . $this->reservation->no_of_guest . '. '
            . 'Please arrive on time. We look forward to seeing you at Casa Alvadon!';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Friendly Reservation Reminder',
            'message' => 'Dear '
                . $this->reservation->user->first_name . ', this is a kind reminder of your reservation on '
                . Carbon::parse($this->reservation->desired_date)->format('F j, Y')
                . ' at ' . Carbon::parse($this->reservation->accommodation->check_in_time)->format('h:i A')
                . ' – ' . Carbon::parse($this->reservation->accommodation->check_out_time)->format('h:i A')
                . ' at ' . $this->reservation->accommodation->name
                . ' for ' . $this->reservation->no_of_guest
                . ' guest(s). We look forward to welcoming you. Please arrive on time.',
            'time' => now()->format('F j, Y h:i A'),
        ];
    }
}
