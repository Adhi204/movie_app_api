<?php

namespace App\Enums\Bookings;

use App\Library\Traits\Enums\GetsValueFromName;
use App\Library\Traits\Enums\GivesValuesAsArray;

enum BookingStatus: string
{
    use GivesValuesAsArray, GetsValueFromName;

    case Pending = 'PED';
    case Confirmed = 'CNF';
    case Cancelled = 'CNS';
}
