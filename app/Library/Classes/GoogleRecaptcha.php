<?php

namespace App\Library\Classes;

use Illuminate\Support\Facades\Http;

class GoogleRecaptcha
{
    protected const API_URL = "https://recaptchaenterprise.googleapis.com/v1/projects/[PROJECT_ID]/assessments?key=[API_KEY]";
    protected const MIN_PASS_SCORE = 0.7;

    protected string $apiUrl;

    /** Constructor */
    public function __construct(
        protected string $projectName,
        protected string $apiKey,
        protected string $siteKey,
        protected float $minPassScore = self::MIN_PASS_SCORE
    ) {
        $this->apiUrl = str_replace(['[PROJECT_ID]', '[API_KEY]'], [$this->projectName, $this->apiKey], self::API_URL);
    }

    /**
     * Verify a google reCaptcha token
     *
     * @param string $token
     * @param string $expectedAction
     * @return bool
     */
    public function verify(string $token, string $expectedAction): bool
    {
        if (!$token) {
            return false;
        }

        $response = $this->getResponse($token, $expectedAction);

        return $response['riskAnalysis']['score'] >= $this->minPassScore;
    }

    /**
     * Try and verify the google reCaptcha token using call to API
     *
     * @param string $token
     * @param string $expectedAction
     * @return array
     */
    protected function getResponse(string $token, string $expectedAction): array
    {
        $postData = [
            'event' => [
                'token' => $token,
                'expectedAction' => $expectedAction,
                'siteKey' => $this->siteKey,
            ],
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post($this->apiUrl, $postData);

        return json_decode($response->getBody(), true);
    }
}
