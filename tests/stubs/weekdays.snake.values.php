<?php

namespace App\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self mon_day()
 * @method static self tues_day()
 * @method static self wednes_day()
 * @method static self thurs_day()
 * @method static self fri_day()
 * @method static self satur_day()
 * @method static self sun_day()
 */
final class WeekDay extends Enum
{
    const MAP_VALUE = [
        'mon_day' => 'mon day',
        'tues_day' => 'tues day',
        'wednes_day' => 'wednes day',
        'thurs_day' => 'thurs day',
        'fri_day' => 'fri day',
        'satur_day' => 'satur day',
        'sun_day' => 'sun day',
    ];
}
