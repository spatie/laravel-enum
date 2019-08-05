<?php

namespace Spatie\Enum\Laravel\Tests\Rules;

use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;
use Spatie\Enum\Laravel\Rules\Enum;
use Spatie\Enum\Laravel\Tests\Extra\Post;
use Spatie\Enum\Laravel\Tests\TestCase;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

final class EnumTest extends TestCase
{
    /** @test */
    public function it_will_validate_an_index()
    {
        $rule = new Enum(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 1));
        $this->assertFalse($rule->passes('attribute', 5));
    }

    /** @test */
    public function it_will_validate_a_name()
    {
        $rule = new Enum(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'draft'));
        $this->assertFalse($rule->passes('attribute', 'drafted'));
    }

    /** @test */
    public function it_will_validate_a_value()
    {
        $rule = new Enum(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'stored archive'));
        $this->assertFalse($rule->passes('attribute', 'stored draft'));
    }

    /** @test */
    public function it_will_throw_an_exception_if_not_existing_class_passed()
    {
        $this->expectException(InvalidArgumentException::class);

        new Enum('foobar/enum');
    }

    /** @test */
    public function it_will_throw_an_exception_if_invalid_class_passed()
    {
        $this->expectException(InvalidArgumentException::class);

        new Enum(Post::class);
    }

    /** @test */
    public function it_returns_default_validation_message()
    {
        $rule = new Enum(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('The attribute field is not a valid '.StatusEnum::class.'.', $rule->message());
    }

    /** @test */
    public function it_passes_attribute_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':attribute',
        ], Lang::getLocale(), 'enum');

        $rule = new Enum(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('attribute', $rule->message());
    }

    /** @test */
    public function it_passes_value_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':value',
        ], Lang::getLocale(), 'enum');

        $rule = new Enum(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('foobar', $rule->message());
    }

    /** @test */
    public function it_passes_enum_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':enum',
        ], Lang::getLocale(), 'enum');

        $rule = new Enum(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals(StatusEnum::class, $rule->message());
    }

    /** @test */
    public function it_passes_other_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':other',
        ], Lang::getLocale(), 'enum');

        $rule = new Enum(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('draft, published, stored archive', $rule->message());
    }
}
