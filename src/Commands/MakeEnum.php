<?php

namespace Spatie\Enum\Laravel\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeEnum extends GeneratorCommand
{
    protected $name = 'make:spatie-enum';

    protected $description = 'Create a new enum (spatie/laravel-enum)';

    public function handle()
    {
        $this->type = $this->getNameInput();

        return parent::handle();
    }

    protected function getStub()
    {
        $customStub = $this->laravel->basePath('stubs/spatie/enum.stub');

        if (file_exists($customStub)) {
            return $customStub;
        }

        return __DIR__.'/../../stubs/enum.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Enums';
    }

    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $stub = str_replace('/** DummyDocBlock */', $this->getDocBlock(), $stub);

        return $stub;
    }

    protected function getDocBlock(): string
    {
        $methods = $this->option('method');

        if (! empty($methods)) {
            $docBlock = PHP_EOL.'/**';
            $docBlock .= implode('', array_map(function ($method) {
                return PHP_EOL.' * @method static self '.$method.'()';
            }, $methods));
            $docBlock .= PHP_EOL.' */';
        }

        return $docBlock ?? '';
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the enum'],
        ];
    }

    protected function getOptions()
    {
        return [
            ['method', 'm', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The method name that should be added to the enum'],
        ];
    }
}
