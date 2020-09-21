<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self draft()
 * @method static self published()
 * @method static self archived()
 */
final class StatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'archived' => 'stored archive',
        ];
    }
}
