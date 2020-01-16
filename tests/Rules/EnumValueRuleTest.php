<?php

namespace Spatie\Enum\Laravel\Tests\Rules;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Spatie\Enum\Laravel\Rules\EnumValueRule;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

final class EnumValueRuleTest extends TestCase
{
    /** @test */
    public function it_will_validate_a_value()
    {
        $rule = new EnumValueRule(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'stored archive'));
        $this->assertFalse($rule->passes('attribute', 'stored draft'));
    }

    /** @test */
    public function it_will_fail_with_an_index()
    {
        $rule = new EnumValueRule(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 1));
    }

    /** @test */
    public function it_will_fail_with_a_name()
    {
        $rule = new EnumValueRule(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 'archived'));
    }

    /** @test */
    public function it_passes_other_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum_value' => ':other',
        ], Lang::getLocale(), 'enum');

        $rule = new EnumValueRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('draft, published, stored archive', $rule->message());
    }

    /** @test */
    public function it_can_resolve_validator_extension_and_pass_validation()
    {
        $validator = Validator::make([
            'attribute' => 'stored archive',
        ], [
            'attribute' => 'enum_value:'.StatusEnum::class,
        ]);

        $this->assertTrue($validator->passes());

        $this->assertTrue($validator->validateEnumValue('attribute', 'stored archive', [StatusEnum::class], $validator));
    }

    /** @test */
    public function it_can_resolve_validator_extension_and_fail_validation()
    {
        $validator = Validator::make([
            'attribute' => 'stored draft',
        ], [
            'attribute' => 'enum_value:'.StatusEnum::class,
        ]);

        $this->assertFalse($validator->passes());

        $this->assertFalse($validator->validateEnumValue('attribute', 'stored draft', [StatusEnum::class], $validator));
    }
}
