<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Http\Requests\TransformingRequest;

class StatusFormRequest extends FormRequest
{
    use TransformingRequest;

    public function rules(): array
    {
        return [];
    }

    public function enums(): array
    {
        return [
            'status' => StatusEnum::class,
        ];
    }
}
