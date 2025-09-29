<?php

namespace App\Library\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'make:action')]
class MakeAction extends GeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'action';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return base_path('stubs/action.stub');
    }

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

        return base_path('app/Actions/' . str_replace('\\', '/', $name) . '.php');
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\Actions';
    }
}
