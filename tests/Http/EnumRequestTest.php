<?php

namespace Spatie\Enum\Laravel\Tests\Http;

use Illuminate\Http\Request;
use Spatie\Enum\Laravel\Http\EnumRequest;
use Spatie\Enum\Laravel\Tests\Extra\LocaleEnum;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\TestCase;

final class EnumRequestTest extends TestCase
{
    /** @test */
    public function it_has_transform_enums_macro()
    {
        $this->assertTrue(Request::hasMacro('transformEnums'));
    }

    /** @test */
    public function it_can_transform_a_request_get_parameter_to_enum_by_name()
    {
        $request = $this->createRequest(
            [
                'status' => 'draft',
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_QUERY => [
                'status' => StatusEnum::class,
            ],
        ]);

        $this->assertInstanceOf(StatusEnum::class, $request->query->get('status'));
        $this->assertEquals('DRAFT', $request->query->get('status')->getName());
    }

    /** @test */
    public function it_can_transform_a_request_get_parameter_to_enum_by_index()
    {
        $request = $this->createRequest(
            [
                'status' => 1,
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_QUERY => [
                'status' => StatusEnum::class,
            ],
        ]);

        $this->assertInstanceOf(StatusEnum::class, $request->query->get('status'));
        $this->assertEquals('PUBLISHED', $request->query->get('status')->getName());
    }

    /** @test */
    public function it_can_transform_a_request_get_parameter_to_enum_by_value()
    {
        $request = $this->createRequest(
            [
                'status' => 'stored archive',
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_QUERY => [
                'status' => StatusEnum::class,
            ],
        ]);

        $this->assertInstanceOf(StatusEnum::class, $request->query->get('status'));
        $this->assertEquals('ARCHIVED', $request->query->get('status')->getName());
    }

    /** @test */
    public function it_does_not_transform_a_request_get_parameter_to_enum_if_no_query_rule_present()
    {
        $request = $this->createRequest(
            [
                'status' => 'draft',
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_REQUEST => [
                'status' => StatusEnum::class,
            ],
        ]);

        $this->assertIsString($request->query->get('status'));
        $this->assertEquals('draft', $request->query->get('status'));
    }

    /** @test */
    public function it_ignores_rules_if_request_get_parameter_is_not_present()
    {
        $request = $this->createRequest(
            [
                'status' => 'draft',
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_REQUEST => [
                'state' => StatusEnum::class,
            ],
        ]);

        $this->assertIsString($request->query->get('status'));
        $this->assertEquals('draft', $request->query->get('status'));
    }

    /** @test */
    public function it_can_transform_a_request_post_parameter_to_enum_by_name()
    {
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 'draft',
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_REQUEST => [
                'status' => StatusEnum::class,
            ],
        ]);

        $this->assertInstanceOf(StatusEnum::class, $request->request->get('status'));
        $this->assertEquals('DRAFT', $request->request->get('status')->getName());
    }

    /** @test */
    public function it_can_transform_a_request_post_parameter_to_enum_by_index()
    {
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 1,
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_REQUEST => [
                'status' => StatusEnum::class,
            ],
        ]);

        $this->assertInstanceOf(StatusEnum::class, $request->request->get('status'));
        $this->assertEquals('PUBLISHED', $request->request->get('status')->getName());
    }

    /** @test */
    public function it_can_transform_a_request_post_parameter_to_enum_by_value()
    {
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 'stored archive',
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_REQUEST => [
                'status' => StatusEnum::class,
            ],
        ]);

        $this->assertInstanceOf(StatusEnum::class, $request->request->get('status'));
        $this->assertEquals('ARCHIVED', $request->request->get('status')->getName());
    }

    /** @test */
    public function it_does_not_transform_a_request_post_parameter_to_enum_if_no_request_rule_present()
    {
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 'draft',
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_QUERY => [
                'status' => StatusEnum::class,
            ],
        ]);

        $this->assertIsString($request->request->get('status'));
        $this->assertEquals('draft', $request->request->get('status'));
    }

    /** @test */
    public function it_ignores_rules_if_request_post_parameter_is_not_present()
    {
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 'draft',
            ]
        );

        $request->transformEnums([
            EnumRequest::REQUEST_REQUEST => [
                'state' => StatusEnum::class,
            ],
        ]);

        $this->assertIsString($request->request->get('status'));
        $this->assertEquals('draft', $request->request->get('status'));
    }

    /** @test */
    public function it_can_transform_a_request_route_parameter_to_enum()
    {
        $request = $this->createRequest();

        $request->transformEnums([
            EnumRequest::REQUEST_ROUTE => [
                'locale' => LocaleEnum::class,
            ],
        ]);

        $this->assertInstanceOf(LocaleEnum::class, $request->route('locale'));
        $this->assertEquals('EN', $request->route('locale')->getName());
        $this->assertEquals('en', $request->route('locale')->getValue());
    }

    /** @test */
    public function it_does_not_transform_a_request_route_parameter_to_enum_if_no_route_rule_present()
    {
        $request = $this->createRequest();

        $request->transformEnums([
            EnumRequest::REQUEST_QUERY => [
                'locale' => LocaleEnum::class,
            ],
        ]);

        $this->assertIsString($request->route('locale'));
        $this->assertEquals('en', $request->route('locale'));
    }

    /** @test */
    public function it_ignores_rules_if_request_route_parameter_is_not_present()
    {
        $request = $this->createRequest();

        $request->transformEnums([
            EnumRequest::REQUEST_ROUTE => [
                'language' => LocaleEnum::class,
            ],
        ]);

        $this->assertIsString($request->route('locale'));
        $this->assertEquals('en', $request->route('locale'));
    }

    /** @test */
    public function it_can_transform_general_request_parameters_to_enum()
    {
        $request = $this->createRequest([
            'status' => 'draft',
        ]);

        $request->transformEnums([
            'locale' => LocaleEnum::class,
            'status' => StatusEnum::class,
        ]);

        $this->assertInstanceOf(LocaleEnum::class, $request['locale']);
        $this->assertEquals('EN', $request['locale']->getName());
        $this->assertEquals('en', $request['locale']->getValue());

        $this->assertInstanceOf(StatusEnum::class, $request['status']);
        $this->assertEquals('DRAFT', $request['status']->getName());
    }

    /** @test */
    public function it_ignores_rules_if_general_request__parameter_is_not_present()
    {
        $request = $this->createRequest([
            'status' => 'draft',
        ]);

        $request->transformEnums([
            'state' => StatusEnum::class,
        ]);

        $this->assertIsString($request->query->get('status'));
        $this->assertEquals('draft', $request->query->get('status'));
    }
}
