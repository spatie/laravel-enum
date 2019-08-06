<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Spatie\Enum\Laravel\Http\EnumRequest;

final class StatusFormPostRequest extends StatusFormRequest
{
    public function enums(): array
    {
        return [
            EnumRequest::REQUEST_REQUEST => [
                'status' => StatusEnum::class,
            ],
        ];
    }
}
