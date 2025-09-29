<?php

namespace App\Library\Classes;

use App\Library\Interfaces\SmsProviderInterface;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    protected SmsProviderInterface $provider;

    public function __construct(SmsProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Send the notification.
     * 
     * @param mixed $notifiable
     * @param Notification $notification
     */
    public function send($notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toSms')) {
            return;
        }

        $smsMessage = $notification->toSms($notifiable);

        $this->provider->send($smsMessage);
    }
}
