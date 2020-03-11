<?php

namespace Spatie\Enum\Laravel\Http\Requests;

trait TransformsEnums
{
    protected function passedValidation()
    {
        $this->transformEnums($this->enums());
    }

    abstract public function enums(): array;
}
