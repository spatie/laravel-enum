<?php

namespace Spatie\Enum\Laravel\Http\Requests;

trait TransformingRequest
{
    protected function prepareForValidation()
    {
        $this->transformEnums($this->enumRules());
    }

    public function enumRules(): array
    {
        return [];
    }
}
