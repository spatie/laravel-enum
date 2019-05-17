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
        $this->runMakeCommand();
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

        $this->runMakeCommand([
            '--method' => $methods,
        ]);

        foreach($methods as $method) {
            $this->assertMethodDocTag($method);
        }
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

        $this->runMakeCommand([
            '--method' => array_values($values),
            '--formatter' => 'studly',
        ]);

        foreach($values as $method => $value) {
            $this->assertMethodDocTag($method);
        }
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

        $this->runMakeCommand([
            '--method' => array_values($values),
            '--formatter' => 'snake',
        ]);

        foreach($values as $method => $value) {
            $this->assertMethodDocTag($method);
        }
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

        $this->runMakeCommand([
            '--method' => array_values($values),
            '--formatter' => 'const',
        ]);

        foreach($values as $method => $value) {
            $this->assertMethodDocTag($method);
        }
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

        $this->runMakeCommand([
            '--value' => $values,
        ]);

        $this->assertStringContainsString('const MAP_VALUE', $this->content);
        foreach($values as $method => $value) {
            $this->assertMethodDocTag($method);
            $this->assertValueMap($method, $value);
        }
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

        $this->runMakeCommand([
            '--value' => $values,
            '--formatter' => 'studly',
        ]);

        $this->assertStringContainsString('const MAP_VALUE = [', $this->content);
        foreach($values as $method => $value) {
            $this->assertMethodDocTag($method);
            $this->assertValueMap($method, $value);
        }
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

        $this->runMakeCommand([
            '--value' => $values,
            '--formatter' => 'snake',
        ]);

        $this->assertStringContainsString('const MAP_VALUE', $this->content);
        foreach($values as $method => $value) {
            $this->assertMethodDocTag($method);
            $this->assertValueMap($method, $value);
        }
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

        $this->runMakeCommand([
            '--value' => $values,
            '--formatter' => 'const',
        ]);

        $this->assertStringContainsString('const MAP_VALUE', $this->content);
        foreach($values as $method => $value) {
            $this->assertMethodDocTag($method);
            $this->assertValueMap($method, $value);
        }
    }

    protected function assertMethodDocTag(string $method): void
    {
        $this->assertStringContainsString('* @method static self '.$method.'()', $this->content);
    }

    protected function assertValueMap(string $method, string $value): void
    {
        $this->assertStringContainsString('\''.$method.'\' => \''.$value.'\',', $this->content);
    }

    private function runMakeCommand( array $arguments = []): void
    {
        $artisan = $this->artisan('make:enum', array_merge([
            'name' => 'WeekDay',
        ], $arguments));

        $artisan->assertExitCode(0);
        $artisan->expectsOutput(' created successfully.');
        $artisan->run();

        $this->assertTrue(file_exists(self::WEEKDAY_PATH));
        $this->content = file_get_contents(self::WEEKDAY_PATH);
        $this->assertStringStartsWith('<?php', $this->content);
        $this->assertStringContainsString('namespace App\Enums;', $this->content);
        $this->assertStringContainsString('use Spatie\Enum\Enum;', $this->content);
        $this->assertStringContainsString('final class WeekDay extends Enum'.PHP_EOL.'{', $this->content);
        $this->assertStringEndsWith('}'.PHP_EOL, $this->content);
    }
}
