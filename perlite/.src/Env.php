<?php

namespace Perlite;

/**
 * Responsible for reading environment variables
 */
class Env
{
    /**
     * Return the value of an environment variable. undefined returns $default
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $value = getenv($key);
        return ($value === false) ? $default : $value;
    }

    /**
     * Return the value of a bool-style environment variable. undefined or invalid bool value returns $default
     * ref: https://www.php.net/manual/en/function.filter-var.php#121263
     * @param string $key
     * @param bool $default
     * @return bool
     */
    public static function getBool(string $key, bool $default = false): bool
    {
        return filter_var(self::get($key) ?? $default, FILTER_VALIDATE_BOOLEAN) ?? $default;
    }
}
