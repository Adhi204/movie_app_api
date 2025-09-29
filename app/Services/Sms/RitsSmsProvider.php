<?php

namespace App\Services\Sms;

use App\Library\Classes\SmsMessage;
use App\Library\Interfaces\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RitsSmsProvider implements SmsProviderInterface
{
    protected string $baseUrl = 'https://sms.arssoftech.com/api/sms/v1.0/';
    protected string $requestFor = 'send-sms';

    // Configuration array
    protected array $config;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config = config('sms.providers.rits');
    }

    /**
     * Send an SMS message.
     *
     * @param SmsMessage $smsMessage
     */
    public function send(SmsMessage $smsMessage): void
    {
        $data = $this->getOptions();

        $data['recipients'] = $smsMessage->phoneNumber();

        //Get the message content, replace new lines with spaces, and trim it
        $messageContent = $smsMessage->message();
        $messageContent = preg_replace("/\r\n|\r|\n/", ' ', $messageContent);

        $data['messageContent'] =  trim($messageContent);

        // Determine the route and set the country code if international
        $countryCode = $smsMessage->countryCode();
        $data['route'] = $countryCode === '91' ?
            ($smsMessage->route ??  $this->config['route']) : 'international';

        if ($data['route'] === 'international') {
            $data['countryCode'] = $countryCode;
        }

        // Set content type and template ID
        $data['contentType'] = $smsMessage->contentType ??  $this->config['content_type'];
        $data['templateId'] = $smsMessage->templateId;

        // Set webhook ID if available  
        $webHookId = $smsMessage->webHookId ?? $this->config['webhook_id'];
        if ($webHookId) {
            $data['webHookId'] = $webHookId;
        }

        $url = $this->baseUrl . $this->requestFor;

        Log::info('SMS send request', [
            'url' => $url,
            'data' => $data,
        ]);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($url, $data);

        if ($response->failed()) {
            Log::error('SMS sending failed', [
                'response' => $response->body(),
                'data' => $data,
            ]);
        }
    }

    /**
     * Get the common options for the SMS request.
     * 
     * @return array
     */
    protected function getOptions(): array
    {
        $expire = now()->addMinutes(5)->timestamp;

        return [
            'accessToken' => $this->config['access_token'],
            'expire' => $expire,
            'authSignature' => $this->generateAuthSignature($expire),

            'smsHeader' => $this->config['sms_header'],
            'entityId' => $this->config['entity_id'],
        ];
    }

    /**
     * Generate the authentication signature.
     * 
     * @param int $expire
     * @return string
     */
    protected function generateAuthSignature(int $expire): string
    {
        $accessToken = $this->config['access_token'];
        $accessTokenKey = $this->config['access_token_key'];

        $timeKey = md5($this->requestFor . "sms@rits-v1.0" . $expire);
        $timeAccessTokenKey = md5($accessToken . $timeKey);
        $signature = md5($timeAccessTokenKey . $accessTokenKey);

        return $signature;
    }
}
