<?php

namespace Spatie\Enum\Laravel\Tests\Rules;

use Illuminate\Support\Facades\Lang;
use Spatie\Enum\Laravel\Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Spatie\Enum\Laravel\Rules\EnumNameRule;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

final class EnumNameRuleTest extends TestCase
{
    /** @test */
    public function it_will_validate_a_name()
    {
        $rule = new EnumNameRule(StatusEnum::class);

        $this->assertTrue($rule->passes('attribute', 'draft'));
        $this->assertFalse($rule->passes('attribute', 'drafted'));
    }

    /** @test */
    public function it_will_fail_with_an_index()
    {
        $rule = new EnumNameRule(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 1));
    }

    /** @test */
    public function it_will_fail_with_a_value()
    {
        $rule = new EnumNameRule(StatusEnum::class);

        $this->assertFalse($rule->passes('attribute', 'stored archive'));
    }

    /** @test */
    public function it_passes_other_to_the_validation_message()
    {
        Lang::addLines([
            'validation.enum_name' => ':other',
        ], Lang::getLocale(), 'enum');

        $rule = new EnumNameRule(StatusEnum::class);
        $rule->passes('attribute', 'foobar');
        $this->assertEquals('DRAFT, PUBLISHED, ARCHIVED', $rule->message());
    }

    /** @test */
    public function it_can_resolve_validator_extension_and_pass_validation()
    {
        $validator = Validator::make([
            'attribute' => 'draft',
        ], [
            'attribute' => 'enum_name:'.StatusEnum::class,
        ]);

        $this->assertTrue($validator->passes());

        $this->assertTrue($validator->validateEnumName('attribute', 'draft', [StatusEnum::class], $validator));
    }

    /** @test */
    public function it_can_resolve_validator_extension_and_fail_validation()
    {
        $validator = Validator::make([
            'attribute' => 'drafted',
        ], [
            'attribute' => 'enum_name:'.StatusEnum::class,
        ]);

        $this->assertFalse($validator->passes());

        $this->assertFalse($validator->validateEnumName('attribute', 'drafted', [StatusEnum::class], $validator));
    }
}
