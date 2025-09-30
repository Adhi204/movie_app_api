<?php

namespace App\Library\Traits\Enums;

/**
 * Allows an enum to have empty values
 */

trait HasEmptyValues
{
    //returns all empty cases in the enum
    public static function emptyCases(): array
    {
        return array_filter(static::cases(), fn($case) => empty($case->value));
    }

    //returns all non empty cases in the enum
    public static function nonEmptyCases(): array
    {
        return array_filter(static::cases(), fn($case) => !empty($case->value));
    }
}
