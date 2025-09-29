<?php

namespace App\Library\Console;

use Illuminate\Foundation\Console\TraitMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:trait')]
class MakeTrait extends TraitMakeCommand
{
    /**
     * Get the destination trait path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        // Remove the default namespace from the trait name
        $defaultNamespace = $this->getDefaultNamespace(trim($this->rootNamespace(), '\\'));
        $name = Str::replaceFirst($defaultNamespace . '\\', '', $name);

        return base_path('app/Library/Traits/' . str_replace('\\', '/', $name) . '.php');
    }


    /**
     * Get the default namespace for the trait.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Library\\Traits';
    }
}
