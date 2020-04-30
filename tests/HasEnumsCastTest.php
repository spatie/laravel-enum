<?php

namespace Spatie\Enum\Laravel\Tests;

use InvalidArgumentException;
use Spatie\Enum\Enumerable;
use Spatie\Enum\Laravel\Exceptions\ExpectsArrayOfEnumsField;
use Spatie\Enum\Laravel\Exceptions\InvalidEnumError;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField;
use Spatie\Enum\Laravel\Tests\Extra\InvalidNullablePost;
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

        $this->assertTrue($model->status->isEqual(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
    }

    /** @test */
    public function it_saves_the_index_of_an_enum()
    {
        $model = new class extends Post {
            protected $casts = [
                'status' => 'int',
            ];
        };

        $model->status = StatusEnum::draft();
        $model->save();

        $model->refresh();

        $this->assertTrue($model->status->isEqual(StatusEnum::draft()));
        $this->assertEquals(0, $model->getOriginal('status'));
    }

    /** @test */
    public function an_invalid_class_throws_an_error()
    {
        $this->expectException(InvalidEnumError::class);

        Post::create([
            'status' => new stdClass(),
        ]);
    }

    /** @test */
    public function an_enum_value_can_be_mapped()
    {
        $model = Post::create([
            'status' => StatusEnum::archived(),
        ]);

        $this->assertEquals(
            StatusEnum::MAP_VALUE['archived'],
            $model->getAttributes()['status']
        );

        $model->refresh();

        $this->assertTrue($model->status->isEqual(StatusEnum::archived()));
    }

    /** @test */
    public function a_textual_value_is_cast_to_the_enum_object()
    {
        $post = new Post();

        $post->status = 'published';

        $this->assertInstanceOf(StatusEnum::class, $post->status);
        $this->assertTrue($post->status->isEqual(StatusEnum::published()));
    }

    /** @test */
    public function an_index_is_cast_to_the_enum_object()
    {
        $post = new Post();

        $post->status = 1;

        $this->assertInstanceOf(StatusEnum::class, $post->status);
        $this->assertTrue($post->status->isEqual(StatusEnum::published()));
    }

    /** @test */
    public function a_mapped_textual_value_is_cast_to_the_enum_object()
    {
        $post = new Post();

        $post->status = 'archived';

        $this->assertInstanceOf(StatusEnum::class, $post->status);
        $this->assertTrue($post->status->isEqual(StatusEnum::archived()));

        $post->status = 'stored archive';

        $this->assertInstanceOf(StatusEnum::class, $post->status);
        $this->assertTrue($post->status->isEqual(StatusEnum::archived()));
    }

    /** @test */
    public function textual_value_from_fill_and_create()
    {
        $post = Post::create([
            'status' => 'published',
        ]);

        $this->assertInstanceOf(StatusEnum::class, $post->status);
        $this->assertTrue($post->status->isEqual(StatusEnum::published()));

        $post->fill([
            'status' => 'draft',
        ]);

        $this->assertTrue($post->status->isEqual(StatusEnum::draft()));
    }

    /** @test */
    public function throws_exception_if_enum_class_is_not_enumerable()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected '.Post::class.' to implement '.Enumerable::class);

        $post = new Post();
        $post->invalid_enum = 'unknown';
    }

    /** @test */
    public function it_saves_a_null_nullable_enum()
    {
        $model = Post::create([
            'status' => StatusEnum::draft(),
            'nullable_enum' => null,
        ]);

        $model->refresh();

        $this->assertTrue($model->status->isEqual(StatusEnum::draft()));
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

        $this->assertTrue($model->status->isEqual(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
        $this->assertTrue($model->nullable_enum->isEqual(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('nullable_enum'));
    }

    /** @test */
    public function throws_exception_if_nullable_enum_is_misspelled()
    {
        $this->expectException(NotNullableEnumField::class);
        $this->expectExceptionMessage('Field invalid_nullable_enum on model Spatie\Enum\Laravel\Tests\Extra\InvalidNullablePost is not nullable');

        $post = new InvalidNullablePost();
        $post->invalid_nullable_enum = null;
    }

    /** @test */
    public function it_saves_an_enum_of_array_of_enums()
    {
        $model = Post::create([
            'status' => StatusEnum::draft(),
            'array_of_enums' => [StatusEnum::draft(), StatusEnum::archived()],
        ]);

        $model->refresh();

        $this->assertTrue($model->status->isEqual(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
        $this->assertIsArray($model->array_of_enums);
        $this->assertCount(2, $model->array_of_enums);
        $this->assertTrue(StatusEnum::draft()->isAny($model->array_of_enums));
        $this->assertTrue(StatusEnum::archived()->isAny($model->array_of_enums));
    }

    /** @test */
    public function it_saves_a_null_value_for_nullable_array_of_enums()
    {
        $model = Post::create([
            'status' => StatusEnum::draft(),
            'nullable_array_of_enums' => null,
        ]);

        $model->refresh();

        $this->assertTrue($model->status->isEqual(StatusEnum::draft()));
        $this->assertEquals('draft', $model->getOriginal('status'));
        $this->assertNull($model->nullable_array_of_enums);
    }

    /** @test */
    public function throws_exception_if_non_array_value_is_passed_to_array_of_enums()
    {
        $this->expectException(ExpectsArrayOfEnumsField::class);
        $this->expectExceptionMessage('Field array_of_enums on model Spatie\Enum\Laravel\Tests\Extra\Post expects an array of Spatie\Enum\Laravel\Tests\Extra\StatusEnum');

        $post = new Post();
        $post->array_of_enums = StatusEnum::draft();
    }
}
