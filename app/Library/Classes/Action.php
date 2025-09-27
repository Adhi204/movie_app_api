<?php

namespace App\Library\Classes;

class Action
{
    /**
     * Handle the action invocation.
     *
     * @param  mixed  ...$args
     * @return mixed
     */
    public static function run(...$args)
    {
        $instance = new static();
        return $instance(...$args);
    }
}
