<?php

namespace App\Library\Console;

use Illuminate\Foundation\Console\InterfaceMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:interface')]
class MakeInterface extends InterfaceMakeCommand
{
    /**
     * Get the destination interface path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        // Remove the default namespace from the interface name
        $defaultNamespace = $this->getDefaultNamespace(trim($this->rootNamespace(), '\\'));
        $name = Str::replaceFirst($defaultNamespace . '\\', '', $name);

        return base_path('app/Library/Interfaces/' . str_replace('\\', '/', $name) . '.php');
    }


    /**
     * Get the default namespace for the interface.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Library\\Interfaces';
    }
}
