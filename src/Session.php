<?php
/**
 * Created by PhpStorm.
 * User: pfcode
 * Date: 02.08.16
 * Time: 14:41
 */

namespace pfcode\MeguminFramework;


final class Session
{
    /**
     * @var Session
     */
    private static $instance = false;

    /**
     * Get singleton instance of class
     * @return Session
     */
    public static function getInstance()
    {
        if (Session::$instance == false) {
            Session::$instance = new Session();
        }

        return Session::$instance;
    }

    /**
     * SessionController constructor.
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
    }

    /**
     * Try to override default PHP session timeout. Call this function
     * BEFORE creating instance of this class / starting session manually elsewhere.
     * @param $seconds
     */
    public static function setSessionTimeout($seconds){
        // To be sure that maximum lifetime of PHP session will be set,
        // you should change this value directly in php.ini file.
        ini_set('session.gc_maxlifetime', $seconds);

        session_set_cookie_params($seconds);
    }

    /**
     * Get value from session storage
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if (isset($_SESSION[$key]))
            return $_SESSION[$key];
        else return null;
    }

    /**
     * Set field in session storage
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Remove field from the session
     * @param $key
     */
    public function remove($key)
    {
        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
    }

    /**
     * Check if the field exists
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return isset($_SESSION[$key]);
    }
}
