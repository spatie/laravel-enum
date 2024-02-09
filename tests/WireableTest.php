<?php

namespace Spatie\Enum\Laravel\Tests;

use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

final class WireableTest extends TestCase
{
    /** @test */
    public function it_can_be_cast_to_livewire()
    {
        $enum = StatusEnum::draft();

        $this->assertEquals('draft', $enum->toLivewire());
    }

    /** @test */
    public function it_can_be_cast_for_livewire()
    {
        $this->assertTrue(
            StatusEnum::fromLivewire('draft')->equals(StatusEnum::draft())
        );
    }
}
