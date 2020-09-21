<?php

namespace Spatie\Enum\Laravel\Tests\Casts;

use Spatie\Enum\Laravel\Casts\EnumCollectionCast;
use Spatie\Enum\Laravel\Exceptions\NotNullableEnumField;
use Spatie\Enum\Laravel\Tests\Extra\Post;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

final class EnumCollectionCastTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideEnumValueAccessorValues
     */
    public function it_returns_correct_accessor_value($value, $expected, array $options = []): void
    {
        $cast = new EnumCollectionCast(StatusEnum::class, ...$options);

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
        $cast = new EnumCollectionCast(StatusEnum::class, ...$options);

        if (is_null($expected)) {
            $this->assertNull($cast->set(new Post, 'key', $value, []));
        } else {
            $this->assertJsonStringEqualsJsonString(
                json_encode($expected),
                $cast->set(new Post, 'key', $value, [])
            );
        }
    }

    /** @test */
    public function it_throws_exception_when_null_value_accessed_but_not_nullable(): void
    {
        $this->expectException(NotNullableEnumField::class);

        $cast = new EnumCollectionCast(StatusEnum::class);

        $cast->get(new Post, 'key', null, []);
    }

    public function provideEnumValueAccessorValues(): array
    {
        return [
            [null, null, ['nullable']],
            [['draft'], [StatusEnum::draft()], ['nullable']],
            ['draft', [StatusEnum::draft()], ['nullable']],
            [['draft'], [StatusEnum::draft()]],
            ['draft', [StatusEnum::draft()]],
            [['draft', 'stored archive'], [StatusEnum::draft(), StatusEnum::archived()]],
        ];
    }

    public function provideEnumValueMutatorValues(): array
    {
        return [
            [null, null, ['nullable']],
            ['draft', ['draft'], ['nullable']],
            [StatusEnum::draft(), ['draft'], ['nullable']],
            ['draft', ['draft']],
            [StatusEnum::draft(), ['draft']],
            [['draft'], ['draft'], ['nullable']],
            [[StatusEnum::draft()], ['draft'], ['nullable']],
            [['draft'], ['draft']],
            [[StatusEnum::draft()], ['draft']],
            [['draft', 'archived'], ['draft', 'stored archive'], ['nullable']],
            [[StatusEnum::draft(), StatusEnum::archived()], ['draft', 'stored archive'], ['nullable']],
            [['draft', 'archived'], ['draft', 'stored archive']],
            [[StatusEnum::draft(), StatusEnum::archived()], ['draft', 'stored archive']],
        ];
    }
}
