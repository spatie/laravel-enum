<?php

namespace Spatie\Enum\Laravel\Tests;

use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

final class WireableTest extends TestCase
{
    /** @test */
    public function it_can_be_cast_to_and_from_livewire()
    {
        $enum = StatusEnum::draft();

        $castEnum = StatusEnum::fromLivewire($enum->toLivewire());

        $this->assertTrue($castEnum->equals($enum));
    }
}
