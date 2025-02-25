<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

/**
 * Class DateHelper
 * @package App\Helpers
 */
final class DateHelper
{
    const DATE_FORMAT = "Y-m-d h:i:s";

    /**
     * @param $data
     * @param $key
     * @return bool
     */
    public static function validateDate($data, $key)
    {
        $validator = Validator::make($data, [
            $key => 'date_format:'.self::DATE_FORMAT,
        ]);
        if ($validator->fails()) {
            return false;
        }
        return true;
    }
}
