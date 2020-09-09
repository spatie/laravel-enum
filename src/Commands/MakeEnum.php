<?php

namespace Spatie\Enum\Laravel\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeEnum extends GeneratorCommand
{
    protected $name = 'make:enum';

    protected $description = 'Create a new enum';

    public function handle()
    {
        $this->type = $this->getNameInput();

        return parent::handle();
    }

    protected function getStub()
    {
        return __DIR__.'/../../stubs/enum.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Enums';
    }

    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $stub = str_replace('DummyDocBlock', $this->getDocBlock(), $stub);
        $stub = str_replace('DummyValueMapConst', $this->getValueMapConst(), $stub);

        return $stub;
    }

    protected function getDocBlock(): string
    {
        $methods = array_merge($this->option('method'), $this->option('value'));

        if (! empty($methods)) {
            $docBlock = PHP_EOL.'/**';
            $docBlock .= implode('', array_map(function ($method) {
                return PHP_EOL.' * @method static self '.$this->formatValueToMethod($method).'()';
            }, $methods));
            $docBlock .= PHP_EOL.' */';
        }

        return $docBlock ?? '';
    }

    protected function getValueMapConst(): string
    {
        $values = $this->option('value');

        if (! empty($values)) {
            $tab = str_repeat(' ', 4);
            $constant = $tab.'const MAP_VALUE = [';

            foreach ($values as $value) {
                $constant .= PHP_EOL.$tab.$tab.'\''.$this->formatValueToMethod($value).'\' => \''.$value.'\',';
            }

            $constant .= PHP_EOL.$tab.'];';
        }

        return $constant ?? '';
    }

    protected function formatValueToMethod(string $value): string
    {
        switch ($this->option('formatter')) {
            case 'const':
                return strtoupper(Str::snake($value));
            case 'snake':
                return Str::snake($value);
            case 'studly':
                return Str::studly($value);
            case 'camel':
            default:
                return Str::camel($value);
        }
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
            ['method', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The method name that should be added to the enum'],
            ['value', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The value that should be added to the enum'],
            ['formatter', null, InputOption::VALUE_REQUIRED, 'The formatter to use for the value to method conversion (snake, const, studly, camel)', 'camel'],
        ];
    }
}
