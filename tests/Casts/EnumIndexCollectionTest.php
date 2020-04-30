<?php

namespace Spatie\Enum\Laravel\Tests\Casts;

use InvalidArgumentException;
use Spatie\Enum\Laravel\Casts\EnumIndex;
use Spatie\Enum\Laravel\Casts\EnumIndexCollection;
use Spatie\Enum\Laravel\Casts\EnumValue;
use Spatie\Enum\Laravel\Casts\EnumValueCollection;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField;
use Spatie\Enum\Laravel\Tests\Extra\Post;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

final class EnumIndexCollectionTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideEnumValueAccessorValues
     */
    public function it_returns_correct_accessor_value($value, $expected, array $options = []): void
    {
        $cast = new EnumIndexCollection(StatusEnum::class, ...$options);

        $this->assertSameEnum(
            $expected,
            $cast->get(new Post, 'key', is_null($value) ? null : json_encode($value), [])
        );
    }
    /**
     * @test
     * @dataProvider provideEnumValueMutatorValues
     */
    public function it_returns_correct_mutator_value($value, $expected, array $options = []): void
    {
        $cast = new EnumIndexCollection(StatusEnum::class, ...$options);

        if(is_null($expected)) {
            $this->assertNull($cast->set(new Post, 'key', $value, []));
        } else {
            $this->assertJsonStringEqualsJsonString(
                json_encode($expected),
                $cast->set(new Post, 'key', $value, [])
            );
        }
    }

    /** @test */
    public function it_throws_exception_when_not_enumerable_class_passed_in(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new EnumIndexCollection(Post::class);
    }

    /** @test */
    public function it_throws_exception_when_null_value_accessed_but_not_nullable(): void
    {
        $this->expectException(NotNullableEnumField::class);

        $cast = new EnumIndexCollection(StatusEnum::class);

        $cast->get(new Post, 'key', null, []);
    }

    public function provideEnumValueAccessorValues(): array
    {
        return [
            [null, null, ['nullable']],
            [['draft'], [StatusEnum::draft()], ['nullable']],
            [['0'], [StatusEnum::draft()], ['nullable']],
            [[0], [StatusEnum::draft()], ['nullable']],

            ['draft', [StatusEnum::draft()], ['nullable']],
            ['0', [StatusEnum::draft()], ['nullable']],
            [0, [StatusEnum::draft()], ['nullable']],

            [['draft'], [StatusEnum::draft()]],
            [['0'], [StatusEnum::draft()]],
            [[0], [StatusEnum::draft()]],

            ['draft', [StatusEnum::draft()]],
            ['0', [StatusEnum::draft()]],
            [0, [StatusEnum::draft()]],

            [['draft', 'stored archive'], [StatusEnum::draft(), StatusEnum::archived()]],
            [['0', '2'], [StatusEnum::draft(), StatusEnum::archived()]],
            [[0, 2], [StatusEnum::draft(), StatusEnum::archived()]],
        ];
    }

    public function provideEnumValueMutatorValues(): array
    {
        return [
            [null, null, ['nullable']],
            ['draft', [0], ['nullable']],
            [0, [0], ['nullable']],
            [StatusEnum::draft(), [0], ['nullable']],

            ['draft', [0]],
            [0, [0]],
            [StatusEnum::draft(), [0]],

            [['draft'], [0], ['nullable']],
            [[0], [0], ['nullable']],
            [[StatusEnum::draft()], [0], ['nullable']],

            [['draft'], [0]],
            [[0], [0]],
            [[StatusEnum::draft()], [0]],

            [['draft', 'archived'], [0, 2], ['nullable']],
            [[0, 2], [0, 2], ['nullable']],
            [[StatusEnum::draft(), StatusEnum::archived()], [0, 2], ['nullable']],

            [['draft', 'archived'], [0, 2]],
            [[0, 2], [0, 2]],
            [[StatusEnum::draft(), StatusEnum::archived()], [0, 2]],
        ];
    }
}
