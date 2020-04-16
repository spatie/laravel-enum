<?php

namespace Spatie\Enum\Laravel\Tests\Commands;

use Spatie\Enum\Laravel\Tests\TestCase;

class MakeEnumTest extends TestCase
{
    const WEEKDAY_PATH = __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Enums/WeekDay.php';

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
        $this->runMakeCommand('weekdays.empty.php');
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

        $this->runMakeCommand('weekdays.camel.methods.php', [
            '--method' => $methods,
        ]);
    }

    /** @test */
    public function it_can_make_an_enum_with_methods_studly(): void
    {
        $values = [
            'Monday' => 'monday',
            'Tuesday' => 'tuesday',
            'Wednesday' => 'wednesday',
            'Thursday' => 'thursday',
            'Friday' => 'friday',
            'Saturday' => 'saturday',
            'Sunday' => 'sunday',
        ];

        $this->runMakeCommand('weekdays.studly.methods.php', [
            '--method' => array_values($values),
            '--formatter' => 'studly',
        ]);
    }

    /** @test */
    public function it_can_make_an_enum_with_methods_snake(): void
    {
        $values = [
            'mon_day' => 'mon day',
            'tues_day' => 'tues day',
            'wednes_day' => 'wednes day',
            'thurs_day' => 'thurs day',
            'fri_day' => 'fri day',
            'satur_day' => 'satur day',
            'sun_day' => 'sun day',
        ];

        $this->runMakeCommand('weekdays.snake.methods.php', [
            '--method' => array_values($values),
            '--formatter' => 'snake',
        ]);
    }

    /** @test */
    public function it_can_make_an_enum_with_methods_const(): void
    {
        $values = [
            'MON_DAY' => 'mon day',
            'TUES_DAY' => 'tues day',
            'WEDNES_DAY' => 'wednes day',
            'THURS_DAY' => 'thurs day',
            'FRI_DAY' => 'fri day',
            'SATUR_DAY' => 'satur day',
            'SUN_DAY' => 'sun day',
        ];

        $this->runMakeCommand('weekdays.const.methods.php', [
            '--method' => array_values($values),
            '--formatter' => 'const',
        ]);
    }

    /** @test */
    public function it_can_make_an_enum_with_values(): void
    {
        $values = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        $this->runMakeCommand('weekdays.camel.values.php', [
            '--value' => $values,
        ]);
    }

    /** @test */
    public function it_can_make_an_enum_with_values_studly(): void
    {
        $values = [
            'Monday' => 'monday',
            'Tuesday' => 'tuesday',
            'Wednesday' => 'wednesday',
            'Thursday' => 'thursday',
            'Friday' => 'friday',
            'Saturday' => 'saturday',
            'Sunday' => 'sunday',
        ];

        $this->runMakeCommand('weekdays.studly.values.php', [
            '--value' => $values,
            '--formatter' => 'studly',
        ]);
    }

    /** @test */
    public function it_can_make_an_enum_with_values_snake(): void
    {
        $values = [
            'mon_day' => 'mon day',
            'tues_day' => 'tues day',
            'wednes_day' => 'wednes day',
            'thurs_day' => 'thurs day',
            'fri_day' => 'fri day',
            'satur_day' => 'satur day',
            'sun_day' => 'sun day',
        ];

        $this->runMakeCommand('weekdays.snake.values.php', [
            '--value' => $values,
            '--formatter' => 'snake',
        ]);
    }

    /** @test */
    public function it_can_make_an_enum_with_values_const(): void
    {
        $values = [
            'MON_DAY' => 'mon day',
            'TUES_DAY' => 'tues day',
            'WEDNES_DAY' => 'wednes day',
            'THURS_DAY' => 'thurs day',
            'FRI_DAY' => 'fri day',
            'SATUR_DAY' => 'satur day',
            'SUN_DAY' => 'sun day',
        ];

        $this->runMakeCommand('weekdays.const.values.php', [
            '--value' => $values,
            '--formatter' => 'const',
        ]);
    }

    private function runMakeCommand(string $stub, array $arguments = []): void
    {
        $WeekDayEnum = "WeekDay";
        $artisan = $this->artisan('make:enum', array_merge([
            'name' => $WeekDayEnum,
        ], $arguments));

        $artisan->assertExitCode(0);
        $artisan->expectsOutput("{$WeekDayEnum} created successfully.");
        $artisan->run();

        $this->assertTrue(file_exists(self::WEEKDAY_PATH));
        $this->content = file_get_contents(self::WEEKDAY_PATH);
        $this->assertEquals($this->getStub($stub), $this->content);
    }
}
