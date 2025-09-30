<?php

namespace App\Enums\Seat;

use App\Library\Traits\Enums\GetsValueFromName;
use App\Library\Traits\Enums\GivesValuesAsArray;

enum SeatType: string
{
    use GivesValuesAsArray, GetsValueFromName;

    case RegularClass = 'RCL';
    case FirstClass = 'FCL';
}
