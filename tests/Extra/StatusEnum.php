<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Spatie\Enum\Enum;

/**
 * @method static self draft()
 * @method static self published()
 * @method static self archived()
 */
final class StatusEnum extends Enum
{
    const MAP_VALUE = [
        'archived' => 'stored archive',
    ];
}
