<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\TwilioChannel;

class RescheduleApprovedNotification extends Notification // implements ShouldQueue
{
    use Queueable;

    protected $reservation;
    protected $rescheduleRequest;

    public function __construct($reservation, $rescheduleRequest)
    {
        $this->reservation = $reservation;
        $this->rescheduleRequest = $rescheduleRequest;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reschedule Request Approved')
            ->greeting('Hello ' . $notifiable->first_name . ',')
            ->line('Your reschedule request for reservation #' . $this->reservation->id . ' has been approved.')
            ->line(
                'New Date: ' .
                Carbon::parse($this->rescheduleRequest->new_check_in_date_time)->format('F j, Y g:i A')
                . ' - ' .
                Carbon::parse($this->rescheduleRequest->new_check_in_date_time)->format('F j, Y g:i A'))
            ->line('Thank you for using our service!');
    }

    public function toTwilio($notifiable)
    {
        $checkIn  = Carbon::parse($this->rescheduleRequest->new_check_in_date_time)
            ->format('F j, Y g:i A');

        $checkOut = Carbon::parse($this->rescheduleRequest->new_check_out_date_time)
            ->format('F j, Y g:i A');

        return 'Hello ' . $notifiable->first_name . '! '
            . 'Good news — your reschedule request for reservation #'
            . $this->reservation->id . ' at '
            . $this->reservation->accommodation->name
            . ' has been approved. '
            . 'Your new schedule is from ' . $checkIn . ' to ' . $checkOut . '.';
    }

    public function toArray(object $notifiable): array
    {
        $newCheckIn = Carbon::parse($this->rescheduleRequest->new_check_in_date_time)
            ->format('F j, Y g:i A');

        $newCheckOut = Carbon::parse($this->rescheduleRequest->new_check_out_date_time)
            ->format('F j, Y g:i A');

        return [
            'title' => 'Reschedule Request Approved',
            'message' => 'Your reschedule request for reservation #'
                . $this->reservation->id
                . ' at ' . $this->reservation->accommodation->name
                . ' has been approved. Your new schedule is from '
                . $newCheckIn . ' to ' . $newCheckOut . '.',
            'date' => now()->format('F j, Y h:i A'),
        ];
    }
}
