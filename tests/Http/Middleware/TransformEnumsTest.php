<?php

namespace Spatie\Enum\Laravel\Tests\Http\Requests;

use Illuminate\Http\Request;
use Spatie\Enum\Laravel\Http\EnumRequest;
use Spatie\Enum\Laravel\Http\Middleware\TransformEnums;
use Spatie\Enum\Laravel\Tests\TestCase;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;

final class TransformEnumsTest extends TestCase
{
    /** @test */
    public function it_transforms_get_request_by_enum_rules()
    {
        $request = $this->createRequest([
            'status' => 'draft',
        ]);

        $middleware = new TransformEnums([
            'status' => StatusEnum::class,
        ]);

        $middleware->handle($request, function (Request $request) {
            $this->assertInstanceOf(StatusEnum::class, $request['status']);
            $this->assertEquals('DRAFT', $request['status']->getName());
        });
    }

    /** @test */
    public function it_transforms_post_request_by_enum_rules()
    {
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 'draft',
            ]
        );

        $middleware = new TransformEnums([
            'status' => StatusEnum::class,
        ]);

        $middleware->handle($request, function (Request $request) {
            $this->assertInstanceOf(StatusEnum::class, $request['status']);
            $this->assertEquals('DRAFT', $request['status']->getName());
        });
    }

    /** @test */
    public function it_transforms_request_query_by_enum_rules()
    {
        $request = $this->createRequest([
            'status' => 'draft',
        ]);

        $middleware = new TransformEnums([
            EnumRequest::REQUEST_QUERY => [
                'status' => StatusEnum::class,
            ],
        ]);

        $middleware->handle($request, function (Request $request) {
            $this->assertInstanceOf(StatusEnum::class, $request->query->get('status'));
            $this->assertEquals('DRAFT', $request->query->get('status')->getName());
        });
    }

    /** @test */
    public function it_transforms_request_body_by_enum_rules()
    {
        $request = $this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 'draft',
            ]
        );

        $middleware = new TransformEnums([
            EnumRequest::REQUEST_REQUEST => [
                'status' => StatusEnum::class,
            ],
        ]);

        $middleware->handle($request, function (Request $request) {
            $this->assertInstanceOf(StatusEnum::class, $request->request->get('status'));
            $this->assertEquals('DRAFT', $request->request->get('status')->getName());
        });
    }
}
