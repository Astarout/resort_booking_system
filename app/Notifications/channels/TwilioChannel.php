<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toTwilio')) {
            return;
        }

        $message = $notification->toTwilio($notifiable);

        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');
        $messagingServiceSid = config('services.twilio.messaging_service_sid');

        if (!$sid || !$token) {
            Log::error('Twilio configuration missing: SID or AUTH TOKEN not found.', [
                'sid' => $sid,
                'token' => $token,
            ]);
            throw new \Exception('Missing Twilio configuration. Please check your .env and config/services.php');
        }

        $client = new Client($sid, $token);

        $to = $notifiable->routeNotificationForTwilio($notification);

        $payload = [
            'body' => $message,
        ];

        // Use Messaging Service SID if available
        if ($messagingServiceSid) {
            $payload['messagingServiceSid'] = $messagingServiceSid;
        } else {
            $payload['from'] = $from;
        }

        try {
            $client->messages->create($to, $payload);
            Log::info('Twilio SMS sent successfully', ['to' => $to, 'payload' => $payload]);
        } catch (\Exception $e) {
            Log::error('Twilio SMS sending failed', [
                'error' => $e->getMessage(),
                'to' => $to,
                'payload' => $payload
            ]);
        }
    }
}
