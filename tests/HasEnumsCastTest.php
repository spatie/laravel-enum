<?php

namespace Spatie\Enum\Laravel\Tests;

use ErrorException;
use Spatie\Enum\Laravel\Tests\Extra\Post;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use stdClass;

final class HasEnumsCastTest extends TestCase
{
    /** @test */
    public function it_saves_the_value_of_an_enum()
    {
        $model = Post::create([
            'status' => StatusEnum::draft(),
        ]);

        $model->refresh();

        $this->assertTrue($model->status->equals(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
    }

    /** @test */
    public function an_invalid_class_throws_an_error()
    {
        $this->expectException(ErrorException::class);

        Post::create([
            'status' => new stdClass(),
        ]);
    }

    /** @test */
    public function it_saves_a_null_nullable_enum()
    {
        $model = Post::create([
            'status' => StatusEnum::draft(),
            'nullable_enum' => null,
        ]);

        $model->refresh();

        $this->assertTrue($model->status->equals(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
        $this->assertNull($model->nullable_enum);
    }

    /** @test */
    public function it_saves_an_enum_of_nullable_enum()
    {
        $model = Post::create([
            'status' => StatusEnum::draft(),
            'nullable_enum' => StatusEnum::draft(),
        ]);

        $model->refresh();

        $this->assertTrue($model->status->equals(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
        $this->assertTrue($model->nullable_enum->equals(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('nullable_enum'));
    }

    /** @test */
    public function it_saves_an_enum_of_array_of_enums()
    {
        $model = Post::create([
            'status' => StatusEnum::draft(),
            'array_of_enums' => [StatusEnum::draft(), StatusEnum::archived()],
        ]);

        $model->refresh();

        $this->assertTrue($model->status->equals(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
        $this->assertIsArray($model->array_of_enums);
        $this->assertCount(2, $model->array_of_enums);
        $this->assertTrue(StatusEnum::draft()->equals(...$model->array_of_enums));
        $this->assertTrue(StatusEnum::archived()->equals(...$model->array_of_enums));
    }

    /** @test */
    public function it_saves_a_null_value_for_nullable_array_of_enums()
    {
        $model = Post::create([
            'status' => StatusEnum::draft(),
            'nullable_array_of_enums' => null,
        ]);

        $model->refresh();

        $this->assertTrue($model->status->equals(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
        $this->assertNull($model->nullable_array_of_enums);
    }
}
