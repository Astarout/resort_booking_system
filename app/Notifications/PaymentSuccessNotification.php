<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;
use App\Notifications\Channels\TwilioChannel;

class PaymentSuccessNotification extends Notification // implements ShouldQueue
{
    use Queueable;

    protected $reservation;
    protected $amount;
    protected $paymentMethod;

    public function __construct(Reservation $reservation, $amount, $paymentMethod)
    {
        $this->reservation = $reservation;
        $this->amount = $amount;
        $this->paymentMethod = $paymentMethod;
    }

    public function via(object $notifiable): array
    {
        # return ['mail', 'database', TwilioChannel::class];
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Payment Successful')
            ->greeting('Hello ' . $this->reservation->user->first_name . ' ' . $this->reservation->user->last_name . '!')
            ->line('We have received your payment of ₱' . number_format($this->amount, 2) . '.')
            ->line('Payment Method: ' . ucfirst($this->paymentMethod))
            ->line('Remaining balance: ₱' . number_format($this->reservation->remaining_balance, 2))
            ->line('Thank you for your payment!');
    }

    public function toTwilio(object $notifiable)
    {
        return
            'Hello ' . $this->reservation->user->first_name .
            '! We have received your payment of ₱' .
            number_format($this->amount, 2) .
            ' via ' . ucfirst($this->paymentMethod) .
            '. Remaining balance: ₱' .
            number_format($this->reservation->remaining_balance, 2) .
            '. Thank you for your payment!';
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment Successful',
            'message' => 'Your payment of ₱' . number_format($this->amount, 2) . ' via ' . ucfirst($this->paymentMethod) . ' has been recorded. Remaining balance: ₱' . number_format($this->reservation->remaining_balance, 2),
            'time' => now()->format('F j, Y h:i A'),
        ];
    }
}
