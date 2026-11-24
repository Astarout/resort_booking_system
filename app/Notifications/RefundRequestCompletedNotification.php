<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RefundRequest;
use App\Notifications\Channels\TwilioChannel;

class RefundRequestCompletedNotification extends Notification
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
            ->subject('Your Refund Request is Completed')
            ->line('Good news! Your refund request has been processed and marked as COMPLETED.')
            ->line('Reservation ID: ' . $this->refund->reservation_id)
            ->line('Refund Amount: ₱' . number_format($this->refund->refund_amount, 2))
            ->line('Mode: ' . strtoupper($this->refund->mode))
            ->line('Remarks: ' . ($this->refund->remarks ?? 'COMPLETED'))
            ->line('Thank you for using our service!');
    }

    public function toTwilio($notifiable)
    {
        return
            'Your refund request has been COMPLETED. ' .
            'Reservation ID: ' . $this->refund->reservation_id . '. ' .
            'Amount: ₱' . number_format($this->refund->refund_amount, 2) . '.';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Refund Request Completed',
            'message' =>
                'Your refund request for reservation #' . $this->refund->reservation_id .
                ' amounting to ₱' . number_format($this->refund->refund_amount, 2) .
                ' has been marked as COMPLETED.',
            'date' => now()->format('F j, Y g:i A'),
        ];
    }
}
