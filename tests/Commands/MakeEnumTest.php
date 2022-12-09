<?php

namespace Spatie\Enum\Laravel\Tests\Commands;

use Spatie\Enum\Laravel\Tests\TestCase;

class MakeEnumTest extends TestCase
{
    public const WEEKDAY_PATH = __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Enums/WeekDay.php';

    protected $content;

    public function setUp(): void
    {
        parent::setUp();
        @unlink(self::WEEKDAY_PATH);
        $this->content = null;
    }

    /** @test */
    public function it_can_run_the_make_command(): void
    {
        $this->runMakeCommand('weekdays.empty.stub.php');
    }

    /** @test */
    public function it_can_make_an_enum_with_methods(): void
    {
        $methods = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];

        $this->runMakeCommand('weekdays.methods.stub.php', [
            '--method' => $methods,
        ]);
    }

    private function runMakeCommand(string $stub, array $arguments = []): void
    {
        $artisan = $this->artisan('make:enum', array_merge([
            'name' => 'WeekDay',
        ], $arguments));


        $artisan->assertExitCode(0);

        if (version_compare($this->app->version(), '9.0.0', '<')) {
            $artisan->expectsOutput('Enum created successfully.');
        }

        $artisan->run();

        $this->assertTrue(file_exists(self::WEEKDAY_PATH));
        $this->content = file_get_contents(self::WEEKDAY_PATH);
        $this->assertEquals($this->getStub($stub), $this->content);
    }
}
