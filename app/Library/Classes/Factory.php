<?php

namespace App\Library\Classes;

use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\Factory as ModelFactory;

abstract class Factory extends ModelFactory
{
    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return config('app.env') === 'production' ? null : Container::getInstance()->make(Generator::class);
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    abstract public function definition();
}
