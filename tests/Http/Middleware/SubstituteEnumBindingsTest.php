<?php

namespace Spatie\Enum\Laravel\Tests\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Spatie\Enum\Laravel\Http\Middleware\SubstituteEnumBindings;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

class SubstituteEnumBindingsTest extends TestCase
{
    /** @test */
    public function it_can_resolve_the_implicit_route_binding_for_the_given_route(): void
    {
        Route::get('posts/{status}', fn (StatusEnum $status): string => serialize($status))
            ->middleware(SubstituteEnumBindings::class);

        $enum = StatusEnum::draft();

        $response = $this->get('posts/'.$enum->value)
            ->assertOk()
            ->assertSee(serialize($enum), false);

        $this->assertTrue($enum->equals(unserialize($response->content())));
    }

    /** @test */
    public function it_returns_404_with_unknown_value(): void
    {
        Route::get('posts/{status}', fn (StatusEnum $status): string => serialize($status))
            ->middleware(SubstituteEnumBindings::class);

        $this->get('posts/foobar')
            ->assertNotFound();
    }
}
