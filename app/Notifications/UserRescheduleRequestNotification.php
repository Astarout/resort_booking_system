<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use carbon\Carbon;
use App\Notifications\Channels\TwilioChannel;

class UserRescheduleRequestNotification extends Notification // implements ShouldQueue
{
    use Queueable;

    protected $reservation;
    protected $rescheduleRequest;
    protected $user;

    public function __construct($reservation, $rescheduleRequest, $user)
    {
        $this->reservation = $reservation;
        $this->rescheduleRequest = $rescheduleRequest;
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Reschedule Request Submitted')
            ->greeting('Hello Admin,')
            ->line('A new reschedule request has been submitted by ' . $this->user->first_name . ' ' . $this->user->last_name . '.')
            ->line('Reservation ID: ' . $this->reservation->id)
            ->line('Accommodation: ' . $this->reservation->accommodation->name)
            ->line('Before Date: '
                . Carbon::parse($this->reservation->check_in_date_time)->format('F j, Y g:i A')
                . ' - '
                . Carbon::parse($this->reservation->check_out_date_time)->format('F j, Y g:i A')
            )
            ->line('Requested New Date: '
                . Carbon::parse($this->rescheduleRequest->new_check_in_date_time)->format('F j, Y g:i A')
                . ' - '
                . Carbon::parse($this->rescheduleRequest->new_check_out_date_time)->format('F j, Y g:i A')
            )
            ->line('Reason: ' . $this->rescheduleRequest->reason)
            ->action('View Request', url('/admin/reschedule-request'))
            ->line('Please review and take action.');
    }

    public function toTwilio(object $notifiable): string
    {
        $currentCheckIn = Carbon::parse($this->reservation->check_in_date_time)
            ->format('F j, Y g:i A');

        $currentCheckOut = Carbon::parse($this->reservation->check_out_date_time)
            ->format('F j, Y g:i A');

        $newCheckIn = Carbon::parse($this->rescheduleRequest->new_check_in_date_time)
            ->format('F j, Y g:i A');

        $newCheckOut = Carbon::parse($this->rescheduleRequest->new_check_out_date_time)
            ->format('F j, Y g:i A');

        return
            'Admin Alert: ' . $this->user->first_name . ' ' . $this->user->last_name .
            ' requested to reschedule reservation #' . $this->reservation->id .
            ' (' . $this->reservation->accommodation->name . '). ' .
            'From: ' . $currentCheckIn . ' to ' . $currentCheckOut .
            '. New: ' . $newCheckIn . ' to ' . $newCheckOut .
            '. Please review.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Reschedule Request',
            'message' => $this->user->first_name . ' ' . $this->user->last_name
                . ' has requested to reschedule their reservation #'
                . $this->reservation->id
                . ' at ' . $this->reservation->accommodation->name
                . ' from '
                . Carbon::parse($this->reservation->check_in_date_time)->format('F j, Y g:i A')
                . ' - '
                . Carbon::parse($this->reservation->check_out_date_time)->format('F j, Y g:i A')
                . ' to '
                . Carbon::parse($this->rescheduleRequest->new_check_in_date_time)->format('F j, Y g:i A')
                . ' - '
                . Carbon::parse($this->rescheduleRequest->new_check_out_date_time)->format('F j, Y g:i A')
                . '.',
            'date' => now()->format('F j, Y g:i A'),
        ];
    }
}
