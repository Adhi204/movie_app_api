<?php

namespace App\Library\Console;

use Illuminate\Foundation\Console\ClassMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:class')]
class MakeClass extends ClassMakeCommand
{
    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        // Remove the default namespace from the class name
        $defaultNamespace = $this->getDefaultNamespace(trim($this->rootNamespace(), '\\'));
        $name = Str::replaceFirst($defaultNamespace . '\\', '', $name);

        return base_path('app/Library/Classes/' . str_replace('\\', '/', $name) . '.php');
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Library\\Classes';
    }
}
