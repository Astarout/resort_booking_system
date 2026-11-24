<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RefundRequest;
use App\Notifications\Channels\TwilioChannel;

class RefundRequestSubmittedNotification extends Notification
{
    use Queueable;

    public $refund;

    public function __construct(RefundRequest $refund)
    {
        $this->refund = $refund;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Refund Request Submitted')
            ->line('A new refund request has been submitted by a user.')
            ->line('Reservation ID: ' . $this->refund->reservation_id)
            ->line('Customer: ' . $this->refund->reservation->user->first_name . ' ' . $this->refund->reservation->user->last_name)
            ->line('Refund Amount: ₱' . number_format($this->refund->refund_amount, 2))
            ->line('Mode: ' . strtoupper($this->refund->mode))
            ->line('Please review this refund request.');
    }

    public function toTwilio($notifiable)
    {
        return
            'Alert: New Refund Request! ' .
            'Reservation ID: ' . $this->refund->reservation_id . '. ' .
            'Customer: ' . $this->refund->reservation->user->first_name . ' ' . $this->refund->reservation->user->last_name . '. ' .
            'Amount: ₱' . number_format($this->refund->refund_amount, 2) . '.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Refund Request Submitted',
            'message' =>
                'Refund request for reservation #' . $this->refund->reservation_id .
                ' by ' . $this->refund->reservation->user->first_name . ' ' . $this->refund->reservation->user->last_name .
                ' amounting to ₱' . number_format($this->refund->refund_amount, 2) .
                ' has been submitted.',
            'date' => now()->format('F j, Y g:i A'),
        ];
    }
}
