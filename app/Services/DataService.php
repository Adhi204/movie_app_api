<?php

namespace App\Services;

class DataService
{
    const BASE_PATH = 'initial_data';

    /** Return all timezones */
    public static function getTimezones(): array
    {
        $csvFile = database_path(self::BASE_PATH . '/timezones.csv');
        return file($csvFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}
