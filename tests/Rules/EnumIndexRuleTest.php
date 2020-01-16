<?php

namespace Spatie\Enum\Laravel\Tests\Rules;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Spatie\Enum\Laravel\Rules\EnumIndexRule;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

final class EnumIndexRuleTest extends TestCase
{
    /** @test */
    public function it_will_validate_an_index()
    {
        $rule = new EnumIndexRule(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 1));
        $this->assertFalse($rule->passes('attribute', 5));
    }

    /** @test */
    public function it_will_fail_with_a_name()
    {
        $rule = new EnumIndexRule(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 'draft'));
    }

    /** @test */
    public function it_will_fail_with_a_value()
    {
        $rule = new EnumIndexRule(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 'stored archive'));
    }

    /** @test */
    public function it_passes_other_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum_index' => ':other',
        ], Lang::getLocale(), 'enum');

        $rule = new EnumIndexRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('0, 1, 2', $rule->message());
    }

    /** @test */
    public function it_can_resolve_validator_extension_and_pass_validation()
    {
        $validator = Validator::make([
            'attribute' => 1,
        ], [
            'attribute' => 'enum_index:'.StatusEnum::class,
        ]);

        $this->assertTrue($validator->passes());

        $this->assertTrue($validator->validateEnumIndex('attribute', 1, [StatusEnum::class], $validator));
    }

    /** @test */
    public function it_can_resolve_validator_extension_and_fail_validation()
    {
        $validator = Validator::make([
            'attribute' => 5,
        ], [
            'attribute' => 'enum_index:'.StatusEnum::class,
        ]);

        $this->assertFalse($validator->passes());

        $this->assertFalse($validator->validateEnumIndex('attribute', 5, [StatusEnum::class], $validator));
    }
}
