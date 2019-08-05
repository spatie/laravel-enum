<?php

namespace Spatie\Enum\Laravel\Tests\Rules;

use Spatie\Enum\Laravel\Rules\Enum;
use Spatie\Enum\Laravel\Tests\TestCase;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

final class EnumTest extends TestCase
{
    /** @test */
    public function it_will_validate_an_index()
    {
        $rule = new Enum(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 1));
        $this->assertFalse($rule->passes('attribute', 5));
    }

    /** @test */
    public function it_will_validate_a_name()
    {
        $rule = new Enum(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'draft'));
        $this->assertFalse($rule->passes('attribute', 'drafted'));
    }

    /** @test */
    public function it_will_validate_a_value()
    {
        $rule = new Enum(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'stored archive'));
        $this->assertFalse($rule->passes('attribute', 'stored draft'));
    }
}
