<?php

namespace Spatie\Enum\Laravel\Http\Requests;

trait TransformingRequest
{
    protected function prepareForValidation()
    {
        $this->transformEnums($this->enums());
    }

    abstract public function enums(): array;
}
