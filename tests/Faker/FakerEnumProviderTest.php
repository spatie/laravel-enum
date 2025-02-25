<?php

namespace Spatie\Enum\Laravel\Tests\Faker;

use Faker\Generator as FakerGenerator;
use Spatie\Enum\Laravel\Faker\FakerEnumProvider;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

final class FakerEnumProviderTest extends TestCase
{
    /** @var FakerGenerator|FakerEnumProvider */
    protected FakerGenerator $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->app->make(FakerGenerator::class);

        FakerEnumProvider::register();
    }

    /** @test */
    public function it_can_register_itself(): void
    {
        $providers = array_map('get_class', $this->faker->getProviders());

        $this->assertTrue(in_array(FakerEnumProvider::class, $providers));
    }

    /**
     * @test
     * @dataProvider repeatHundredTimes
     */
    public function it_can_generate_random_enum_instances(): void
    {
        $enum = $this->faker->randomEnum(StatusEnum::class);

        $this->assertInstanceOf(StatusEnum::class, $enum);
    }

    /**
     * @test
     * @dataProvider repeatHundredTimes
     */
    public function it_can_generate_random_enum_values(): void
    {
        $value = $this->faker->randomEnumValue(StatusEnum::class);

        $this->assertIsString($value);
        $this->assertInstanceOf(StatusEnum::class, StatusEnum::make($value));
        $this->assertTrue(in_array($value, array_keys(StatusEnum::toArray()), true));
    }

    /**
     * @test
     * @dataProvider repeatHundredTimes
     */
    public function it_can_generate_random_enum_labels(): void
    {
        $label = $this->faker->randomEnumLabel(StatusEnum::class);

        $this->assertIsString($label);
        $this->assertInstanceOf(StatusEnum::class, StatusEnum::make($label));
        $this->assertTrue(in_array($label, array_values(StatusEnum::toArray()), true));
    }

    public static function repeatHundredTimes(): iterable
    {
        for ($i = 0; $i < 100; $i++) {
            yield [];
        }
    }
}
