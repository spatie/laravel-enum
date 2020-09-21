<?php

namespace Spatie\Enum\Laravel\Tests\Rules;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Spatie\Enum\Laravel\Rules\EnumRule;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

final class EnumRuleTest extends TestCase
{
    /** @test */
    public function it_will_validate_a_name()
    {
        $rule = new EnumRule(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'draft'));
        $this->assertFalse($rule->passes('attribute', 'drafted'));
    }

    /** @test */
    public function it_will_validate_a_value()
    {
        $rule = new EnumRule(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'stored archive'));
        $this->assertFalse($rule->passes('attribute', 'stored draft'));
    }

    /** @test */
    public function it_returns_default_validation_message()
    {
        $rule = new EnumRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('The attribute field is not a valid '.StatusEnum::class.'.', $rule->message());
    }

    /** @test */
    public function it_passes_attribute_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':attribute',
        ], Lang::getLocale(), 'enum');

        $rule = new EnumRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('attribute', $rule->message());
    }

    /** @test */
    public function it_passes_value_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':value',
        ], Lang::getLocale(), 'enum');

        $rule = new EnumRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('foobar', $rule->message());
    }

    /** @test */
    public function it_passes_enum_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':enum',
        ], Lang::getLocale(), 'enum');

        $rule = new EnumRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals(StatusEnum::class, $rule->message());
    }

    /** @test */
    public function it_passes_other_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':other',
        ], Lang::getLocale(), 'enum');

        $rule = new EnumRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('draft, published, stored archive', $rule->message());
    }

    /** @test */
    public function it_passes_translated_other_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum' => ':other',
            'validation.enums.'.StatusEnum::class => [
                'draft' => 'entwurf',
                'published' => 'veröffentlicht',
                'stored_archive' => 'archiviert',
            ],
        ], Lang::getLocale(), 'enum');

        $rule = new EnumRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('entwurf, veröffentlicht, archiviert', $rule->message());
    }

    /** @test */
    public function it_can_resolve_validator_extension_and_pass_validation()
    {
        $validator = Validator::make([
            'attribute' => 'draft',
        ], [
            'attribute' => 'enum:'.StatusEnum::class,
        ]);

        $this->assertTrue($validator->passes());

        $this->assertTrue($validator->validateEnum('attribute', 'draft', [StatusEnum::class], $validator));
        $this->assertTrue($validator->validateEnum('attribute', 'stored archive', [StatusEnum::class], $validator));
    }

    /** @test */
    public function it_can_resolve_validator_extension_and_fail_validation()
    {
        $validator = Validator::make([
            'attribute' => 5,
        ], [
            'attribute' => 'enum:'.StatusEnum::class,
        ]);

        $this->assertFalse($validator->passes());

        $this->assertFalse($validator->validateEnum('attribute', 5, [StatusEnum::class], $validator));
        $this->assertFalse($validator->validateEnum('attribute', 'drafted', [StatusEnum::class], $validator));
        $this->assertFalse($validator->validateEnum('attribute', 'stored draft', [StatusEnum::class], $validator));
    }
}
