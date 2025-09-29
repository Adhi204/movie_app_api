<?php

namespace App\Library\Classes;

abstract class SmsMessage
{
    public object $notifiable;

    /**
     * SmsMessage constructor.
     * @param object $notifiable
     */
    public function __construct(object $notifiable)
    {
        $this->notifiable = $notifiable;
    }

    /**
     * Magic getter to access options as properties.
     *
     * @param string $name
     */
    public function __get(string $name)
    {
        $options = $this->options();
        return $options[$name] ?? null;
    }

    /**
     * Get the phone number to which the SMS should be sent.
     * Assumes the format "countryCode-phoneNumber".
     *
     * @return string
     */
    abstract public function to(): string;

    /**
     * Get the SMS message content.
     *
     * @return string
     */
    abstract public function message(): string;

    /**
     * Get the options for the SMS message.
     *
     * @return array
     */
    abstract public function options(): array;

    /**
     * Get the country code from the phone number.
     *
     * @return string
     */
    public function countryCode(): string
    {
        $parts = explode('-', $this->to());

        return $parts[0] ?? '';
    }

    /**
     * Get the absolute phone number excluding the country code from the phone number.
     *
     * @return string
     */
    public function phoneNumber(): string
    {
        $parts = explode('-', $this->to());

        return $parts[1] ?? '';
    }
}
