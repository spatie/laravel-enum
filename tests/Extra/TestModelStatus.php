<?php

namespace Spatie\Enum\Laravel\Tests\Extra;

use Spatie\Enum\Enum;

/**
 * @method static self draft()
 * @method static self published()
 * @method static self archived()
 */
final class TestModelStatus extends Enum
{
    public static $map = [
        'archived' => 'stored archive',
    ];
}
