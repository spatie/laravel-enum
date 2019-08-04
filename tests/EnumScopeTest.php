<?php

namespace Spatie\Enum\Laravel\Tests;

use Spatie\Enum\Laravel\Tests\Extra\Post;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Exceptions\NoSuchEnumField;

final class EnumScopeTest extends TestCase
{
    /** @test */
    public function scope_where_enum_invalid_enum_field_throws_exception()
    {
        $this->expectException(NoSuchEnumField::class);

        Post::whereEnum('unknown', StatusEnum::draft())->count();
    }

    /** @test */
    public function scope_or_where_enum_invalid_enum_field_throws_exception()
    {
        $this->expectException(NoSuchEnumField::class);

        Post::orWhereEnum('unknown', StatusEnum::draft())->count();
    }

    /** @test */
    public function scope_where_not_enum_invalid_enum_field_throws_exception()
    {
        $this->expectException(NoSuchEnumField::class);

        Post::whereNotEnum('unknown', StatusEnum::draft())->count();
    }

    /** @test */
    public function scope_or_where_not_enum_invalid_enum_field_throws_exception()
    {
        $this->expectException(NoSuchEnumField::class);

        Post::orWhereNotEnum('unknown', StatusEnum::draft())->count();
    }

    /** @test */
    public function scope_where_enum()
    {
        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        $this->assertEquals(1, Post::whereEnum('status', StatusEnum::draft())->count());
        $this->assertEquals(0, Post::whereEnum('status', StatusEnum::published())->count());
    }

    /** @test */
    public function scope_where_enum_with_array()
    {
        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        Post::create([
            'status' => StatusEnum::published(),
        ]);

        Post::create([
            'status' => StatusEnum::archived(),
        ]);

        $this->assertEquals(2, Post::whereEnum('status', [StatusEnum::draft(), StatusEnum::published()])->count());
    }

    /** @test */
    public function scope_or_where_enum()
    {
        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        $this->assertEquals(
            1,
            Post::query()
                ->whereEnum('status', StatusEnum::published())
                ->orWhereEnum('status', StatusEnum::draft())
                ->count()
        );
        $this->assertEquals(
            0,
            Post::query()
                ->whereEnum('status', StatusEnum::published())
                ->orWhereEnum('status', StatusEnum::archived())
                ->count()
        );
    }

    /** @test */
    public function scope_or_where_enum_with_array()
    {
        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        $this->assertEquals(
            2,
            Post::query()
                ->whereEnum('status', [StatusEnum::published(), StatusEnum::archived()])
                ->orWhereEnum('status', [StatusEnum::published(), StatusEnum::draft()])
                ->count()
        );
    }

    /** @test */
    public function scope_where_not_enum()
    {
        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        $this->assertEquals(1, Post::whereNotEnum('status', StatusEnum::published())->count());
        $this->assertEquals(0, Post::whereNotEnum('status', StatusEnum::draft())->count());
    }

    /** @test */
    public function scope_where_not_enum_with_array()
    {
        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        $this->assertEquals(1, Post::whereNotEnum('status', [StatusEnum::published(), StatusEnum::archived()])->count());
        $this->assertEquals(0, Post::whereNotEnum('status', [StatusEnum::published(), StatusEnum::draft()])->count());
    }

    /** @test */
    public function scope_or_where_not_enum()
    {
        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        $this->assertEquals(
            1,
            Post::query()
                ->whereNotEnum('status', StatusEnum::draft())
                ->orWhereNotEnum('status', StatusEnum::published())
                ->count()
        );
    }

    /** @test */
    public function scope_or_where_not_enum_with_array()
    {
        Post::create([
            'status' => StatusEnum::draft(),
        ]);

        $this->assertEquals(
            1,
            Post::query()
                ->whereNotEnum('status', [StatusEnum::draft(), StatusEnum::archived()])
                ->orWhereNotEnum('status', [StatusEnum::published(), StatusEnum::archived()])
                ->count()
        );
    }

    /** @test */
    public function scope_with_value_input()
    {
        Post::create([
            'status' => StatusEnum::archived(),
        ]);

        $this->assertEquals(1, Post::whereEnum('status', 'archived')->count());
    }

    /** @test */
    public function scope_with_index_input()
    {
        Post::create([
            'status' => StatusEnum::archived(),
        ]);

        $this->assertEquals(1, Post::whereEnum('status', 2)->count());
    }

    /** @test */
    public function scope_with_mapped_input()
    {
        Post::create([
            'status' => StatusEnum::archived(),
        ]);

        $this->assertEquals(1, Post::whereEnum('status', StatusEnum::MAP_VALUE['archived'])->count());
        $this->assertEquals(1, Post::whereEnum('status', StatusEnum::archived())->count());
    }
}
