<?php

namespace App\Library\Traits\Enums;

trait GivesValuesAsArray
{
    /**
     * Get the enum cases as an array of their values.
     *
     * @return array<string>
     */
    public static function valuesToArray(): array
    {
        return array_column(static::cases(), 'value', 'name');
    }
}
