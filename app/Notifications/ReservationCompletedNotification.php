<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Notifications\Channels\TwilioChannel;

class ReservationCompletedNotification extends Notification // implements ShouldQueue
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
            ->subject('Reservation Completed - Casa Alvadon')
            ->greeting('Hello ' . $this->reservation->user->first_name . ',')
            ->line('We hope you had a wonderful stay with us!')
            ->line('Here are your reservation details:')
            ->line('Accommodation: ' . $this->reservation->accommodation->name)
            ->line('Guests: ' . $this->reservation->no_of_guest)
            ->line('Check-in: ' . Carbon::parse($this->reservation->desired_date . ' ' . $this->reservation->accommodation->check_in_time)->format('F j, Y h:i A'))
            ->line('Check-out: ' . Carbon::parse($this->reservation->desired_date . ' ' . $this->reservation->accommodation->check_out_time)->addDay()->format('F j, Y h:i A'))
            ->line('Thank you for staying at Casa Alvadon. We hope to welcome you again soon!')
            ->line('We would greatly appreciate it if you could share your experience by rating us in your profile section.')
            ->salutation('Warm regards, Casa Alvadon');
    }

    public function toTwilio($notifiable)
    {
        $checkIn = Carbon::parse($this->reservation->desired_date . ' ' . $this->reservation->accommodation->check_in_time)->format('F j, Y h:i A');
        $checkOut = Carbon::parse($this->reservation->desired_date . ' ' . $this->reservation->accommodation->check_out_time)->addDay()->format('F j, Y h:i A');

        return 'Hello ' . $this->reservation->user->first_name . '! '
            . 'We hope you enjoyed your stay at ' . $this->reservation->accommodation->name . '. '
            . 'Your reservation (Check-in: ' . $checkIn . ', Check-out: ' . $checkOut . ') has been completed. '
            . 'Thank you for choosing Casa Alvadon! We hope to welcome you again soon.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Reservation Completed',
            'message' => 'Your stay at ' . $this->reservation->accommodation->name . ' has been completed. We hope you had a wonderful experience and look forward to welcoming you again!',
            'date' => now()->format('F j, Y h:i A'),
        ];
    }
}
