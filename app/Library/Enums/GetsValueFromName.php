<?php

namespace App\Library\Traits\Enums;

trait GetsValueFromName
{
    /**
     * Returns value of an enum from its name
     */
    public static function fromName(string $name): ?string
    {
        foreach (self::cases() as $cases) {
            if ($name === $cases->name) {
                return $cases->value;
            }
        }

        return null;
    }
}
