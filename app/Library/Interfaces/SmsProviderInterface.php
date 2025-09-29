<?php

namespace App\Library\Interfaces;

use App\Library\Classes\SmsMessage;

interface SmsProviderInterface
{
    /**
     * Send an SMS message.
     *
     * @param SmsMessage $smsMessage
     */
    public function send(SmsMessage $smsMessage): void;
}
