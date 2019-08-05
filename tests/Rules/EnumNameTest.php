<?php

namespace Spatie\Enum\Laravel\Tests\Rules;

use Spatie\Enum\Laravel\Rules\EnumName;
use Spatie\Enum\Laravel\Tests\TestCase;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

final class EnumNameTest extends TestCase
{
    /** @test */
    public function it_will_validate_a_name()
    {
        $rule = new EnumName(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'draft'));
        $this->assertFalse($rule->passes('attribute', 'drafted'));
    }

    /** @test */
    public function it_will_fail_with_an_index()
    {
        $rule = new EnumName(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 1));
    }

    /** @test */
    public function it_will_fail_with_a_value()
    {
        $rule = new EnumName(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 'stored archive'));
    }
}
