<?php

namespace App\Services;

use Illuminate\Http\Request;

class RequestService
{
    public function __construct(protected Request $request) {}

    /**
     * Detect platform/OS from the 'User-Agent' header.
     *
     * @return string
     */
    public function getPlatform(): string
    {
        // List of platforms and their corresponding regular expressions
        $matches = [
            'Windows' => '/windows|win32/i',
            'Mac' => '/macintosh|mac os x/i',
            'Linux' => '/linux/i',
            'iPhone' => '/iphone/i',
            'iPad' => '/ipad/i',
            'Android' => '/android/i',
            'Postman' => '/postmanruntime/i',
            'Android (okhttp)' => '/okhttp/i',
            'CLI (curl)' => '/curl/i',
            'CLI (wget)' => '/wget/i',
        ];

        $userAgent = $this->request->header('User-Agent');

        foreach ($matches as $platform => $regex) {
            if (preg_match($regex, $userAgent)) {
                return $platform;
            }
        }

        return 'Unknown';
    }

    /**
     * Detect browser from the 'User-Agent' header.
     *
     * @return string
     */
    public function getBrowser(): string
    {
        // List of browsers and their corresponding regular expressions
        $matches = [
            'Opera' => '/Opera|OPR/i',
            'Edge' => '/Edge/i',
            'Chrome' => '/Chrome/i',
            'Safari' => '/Safari/i',
            'Firefox' => '/Firefox/i',
            'MSIE' => '/MSIE/i',
            'Postman' => '/postmanruntime/i',
            'Mobile App' => '/okhttp/i',
            'curl' => '/curl/i',
            'wget' => '/wget/i',
        ];

        $userAgent = $this->request->header('User-Agent');

        foreach ($matches as $browser => $regex) {
            if (preg_match($regex, $userAgent)) {
                return $browser;
            }
        }

        return 'Unknown';
    }

    /**
     * Generate a device name from the platform and browser.
     *
     * @return string
     */
    public function generateDeviceName($maxLength = 64, $includeTimestamp = true): string
    {
        $platform = $this->getPlatform();
        $browser = $this->getBrowser();

        $deviceName = "$platform|$browser";

        // Get a short string for timestamp
        $timestampString = $includeTimestamp ? base_convert(now()->timestamp, 10, 36) : '';

        // Max device name length to accommodate timestamp string in the generated device name
        $maxDeviceNameLength = $maxLength - strlen($timestampString) - 1;

        return substr($deviceName, 0, $maxDeviceNameLength) . "|{$timestampString}";
    }
}
