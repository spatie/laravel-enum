<?php

namespace Spatie\Enum\Laravel\Tests;

use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

class FeatureExplicitRouteBindingTest extends TestCase
{
    /** @test */
    public function it_resolves_explicit_binding(): void
    {
        Route::enum('status', StatusEnum::class);
        Route::get('posts/{status}', fn (Request $request): string => serialize($request->route('status')))
            ->middleware(SubstituteBindings::class);

        $enum = StatusEnum::draft();

        $response = $this->get('posts/'.$enum->value)
            ->assertOk()
            ->assertSee(serialize($enum), false);

        $this->assertTrue($enum->equals(unserialize($response->content())));
    }

    /** @test */
    public function it_returns_404_with_unknown_value(): void
    {
        Route::enum('status', StatusEnum::class);
        Route::get('posts/{status}', fn (Request $request): string => serialize($request->route('status')))
            ->middleware(SubstituteBindings::class);

        $this->get('posts/foobar')
            ->assertNotFound();
    }
}
