<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Http\Requests\TransformingRequest;

final class StatusFormRequest extends FormRequest
{
    use TransformingRequest;

    public function authorize()
    {
        return true;
    }

    public function rules()
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
