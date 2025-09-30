<?php

namespace App\Library\Traits\Enums;

/**
 * Allows an enum to pick a random value from its cases
 */

trait PicksRandomValue
{
    /**
     * Returns a random case from the enum
     * 
     * @return static
     */
    public static function random(): static
    {
        $cases = static::cases();
        $randomKey = array_rand($cases);

        return $cases[$randomKey];
    }
}
