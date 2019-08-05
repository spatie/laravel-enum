<?php

namespace Spatie\Enum\Laravel\Tests\Rules;

use Spatie\Enum\Laravel\Tests\TestCase;
use Spatie\Enum\Laravel\Rules\EnumIndex;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

final class EnumIndexTest extends TestCase
{
    /** @test */
    public function it_will_validate_an_index()
    {
        $rule = new EnumIndex(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 1));
        $this->assertFalse($rule->passes('attribute', 5));
    }

    /** @test */
    public function it_will_fail_with_a_name()
    {
        $rule = new EnumIndex(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 'draft'));
    }

    /** @test */
    public function it_will_fail_with_a_value()
    {
        $rule = new EnumIndex(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 'stored archive'));
    }
}
