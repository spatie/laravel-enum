<?php

namespace Spatie\Enum\Laravel\Http\Requests;

/**
 * @method void transformEnums(array $transformations)
 */
trait TransformsEnums
{
    protected function passedValidation()
    {
        $this->transformEnums($this->enums());
    }

    abstract public function enums(): array;
}
