<?php

namespace Spatie\Enum\Laravel\Tests\Http\Requests;

use Illuminate\Http\Request;
use Spatie\Enum\Laravel\Tests\Extra\StatusEnum;
use Spatie\Enum\Laravel\Tests\Extra\StatusFormGetRequest;
use Spatie\Enum\Laravel\Tests\Extra\StatusFormPostRequest;
use Spatie\Enum\Laravel\Tests\Extra\StatusFormRequest;
use Spatie\Enum\Laravel\Tests\TestCase;

final class TransformsEnumsTest extends TestCase
{
    /** @test */
    public function it_transforms_get_request_by_enum_rules()
    {
        $request = StatusFormRequest::createFrom($this->createRequest([
            'status' => 'draft',
        ]));
        $request->setContainer($this->app)->validateResolved();

        $this->assertInstanceOf(StatusEnum::class, $request['status']);
        $this->assertTrue(StatusEnum::draft()->equals($request['status']));
    }

    /** @test */
    public function it_transforms_post_request_by_enum_rules()
    {
        $request = StatusFormRequest::createFrom($this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 'draft',
            ]
        ));
        $request->setContainer($this->app)->validateResolved();

        $this->assertInstanceOf(StatusEnum::class, $request['status']);
        $this->assertTrue(StatusEnum::draft()->equals($request['status']));
    }

    /** @test */
    public function it_transforms_request_query_by_enum_rules()
    {
        $request = StatusFormGetRequest::createFrom($this->createRequest([
            'status' => 'draft',
        ]));
        $request->setContainer($this->app)->validateResolved();

        $this->assertInstanceOf(StatusEnum::class, $request->query->get('status'));
        $this->assertTrue(StatusEnum::draft()->equals($request->query->get('status')));
    }

    /** @test */
    public function it_transforms_request_body_by_enum_rules()
    {
        $request = StatusFormPostRequest::createFrom($this->createRequest(
            [],
            Request::METHOD_POST,
            [
                'status' => 'draft',
            ]
        ));
        $request->setContainer($this->app)->validateResolved();

        $this->assertInstanceOf(StatusEnum::class, $request->request->get('status'));
        $this->assertTrue(StatusEnum::draft()->equals($request->request->get('status')));
    }
}
