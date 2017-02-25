<?php
/**
 * Created by PhpStorm.
 * User: pfcode
 * Date: 25.02.17
 * Time: 14:56
 */

namespace pfcode\MeguminFramework\Http;

class Cookies
{
    /**
     * @var bool
     */
    private static $allowed = true;

    public static function disallow(): void
    {
        self::$allowed = false;
    }

    public static function allow(): void
    {
        self::$allowed = true;
    }

    /**
     * @return bool
     */
    public static function allowed(): bool
    {
        return self::$allowed;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public static function get($key, $default = null): mixed
    {
        if (self::exists($key)) {
            return $_COOKIE[$key];
        }

        return $default;
    }

    /**
     * @param $key
     * @param null $value
     * @param null $expirationTimestamp
     * @param null $path
     * @param null $domain
     * @param null $secure
     * @param null $httpOnly
     * @return bool
     */
    public static function set($key, $value = null, $expirationTimestamp = null, $path = null, $domain = null, $secure = null, $httpOnly = null): bool
    {
        return self::allowed() && setcookie($key, $value, $expirationTimestamp, $path, $domain, $secure, $httpOnly);
    }


    /**
     * @param $key
     * @return bool
     */
    public static function exists($key): bool
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * @return array
     */
    public static function all(): array
    {
        return $_COOKIE;
    }

    /**
     * @param $key
     * @return bool
     */
    public static function delete($key): bool
    {
        if (!self::exists($key)) {
            return false;
        }

        self::set($key, null, -1);
        unset($_COOKIE[$key]);

        return true;
    }
}