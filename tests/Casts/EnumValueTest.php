<?php

namespace Spatie\Enum\Laravel\Tests\Casts;

use InvalidArgumentException;
use Spatie\Enum\Laravel\Casts\EnumIndex;
use Spatie\Enum\Laravel\Casts\EnumValue;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField;
use Spatie\Enum\Laravel\Tests\Extra\Post;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

final class EnumValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideEnumValueAccessorValues
     */
    public function it_returns_correct_accessor_value($value, $expected, array $options = []): void
    {
        $cast = new EnumValue(StatusEnum::class, ...$options);

        $this->assertSameEnum(
            $expected,
            $cast->get(new Post, 'key', $value, [])
        );
    }
    /**
     * @test
     * @dataProvider provideEnumValueMutatorValues
     */
    public function it_returns_correct_mutator_value($value, $expected, array $options = []): void
    {
        $cast = new EnumValue(StatusEnum::class, ...$options);

        $this->assertSameEnum(
            $expected,
            $cast->set(new Post, 'key', $value, [])
        );
    }

    /** @test */
    public function it_throws_exception_when_not_enumerable_class_passed_in(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new EnumValue(Post::class);
    }

    /** @test */
    public function it_throws_exception_when_null_value_accessed_but_not_nullable(): void
    {
        $this->expectException(NotNullableEnumField::class);

        $cast = new EnumValue(StatusEnum::class);

        $cast->get(new Post, 'key', null, []);
    }

    public function provideEnumValueAccessorValues(): array
    {
        return [
            [null, null, ['nullable']],
            ['draft', StatusEnum::draft(), ['nullable']],
            [0, StatusEnum::draft(), ['nullable']],

            ['draft', StatusEnum::draft()],
            [0, StatusEnum::draft()],
        ];
    }

    public function provideEnumValueMutatorValues(): array
    {
        return [
            [null, null, ['nullable']],
            ['draft', 'draft', ['nullable']],
            [0, 'draft', ['nullable']],
            [StatusEnum::draft(), 'draft', ['nullable']],

            ['draft', 'draft'],
            [0, 'draft'],
            [StatusEnum::draft(), 'draft'],
        ];
    }
}
