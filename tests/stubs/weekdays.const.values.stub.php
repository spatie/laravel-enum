<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self MON_DAY()
 * @method static self TUES_DAY()
 * @method static self WEDNES_DAY()
 * @method static self THURS_DAY()
 * @method static self FRI_DAY()
 * @method static self SATUR_DAY()
 * @method static self SUN_DAY()
 */
final class WeekDay extends Enum
{
    const MAP_VALUE = [
        'MON_DAY' => 'mon day',
        'TUES_DAY' => 'tues day',
        'WEDNES_DAY' => 'wednes day',
        'THURS_DAY' => 'thurs day',
        'FRI_DAY' => 'fri day',
        'SATUR_DAY' => 'satur day',
        'SUN_DAY' => 'sun day',
    ];
}
