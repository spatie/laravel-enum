<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Http\Requests\TransformsEnums;

class StatusFormRequest extends FormRequest
{
    use TransformsEnums;

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
