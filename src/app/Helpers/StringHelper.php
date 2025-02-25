<?php

namespace App\Helpers;

/**
 * Class StringHelper
 * @package App\Helpers
 */
final class StringHelper
{
    /**
     * StringHelper constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string|null $string
     * @return string|null
     */
    public static function transformToCamelCase(?string $string = null): ?string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }

    /**
     * @param string|null $string
     * @return string|null
     */
    public static function transformToSnakeCase(?string $string = null): ?string
    {
        return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $string));
    }

    /**
     * @param string|null $string
     * @return string|null
     */
    public static function removeIsPart(?string $string = null): ?string
    {
        return preg_replace('/(^is_)(\w+)/', '$2', $string);
    }
}
