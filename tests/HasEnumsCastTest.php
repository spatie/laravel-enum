<?php

namespace Spatie\Enum\Laravel\Tests;

use Spatie\Enum\Laravel\Exceptions\InvalidEnumError;
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
            StatusEnum::$map['archived'],
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
}
