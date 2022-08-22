<?php 

namespace app\session;

class Session
{
    public static function inicializar()
    {
        if(session_status() === PHP_SESSION_DISABLED)
        {
            session_start();
        }
    }

    public static function setSession($prop,$value)
    {
        $_SESSION[$prop] = $value;
    }

    public static function getSession($prop,$key = null)
    {
        if(isset($key))
        {
            if(!isset($_SESSION[$prop][$key]))
            {
                return false;
            }

            return $_SESSION[$prop][$key];
        }
        
        return $_SESSION[$prop];
    }

    public static function destroy()
    {
        if(session_status() === PHP_SESSION_ACTIVE)
        {
            session_destroy();
            return true;
        }
        
        return false;
    }

    public static function unset()
    {
        if(session_status() === PHP_SESSION_ACTIVE)
        {
            session_unset();
            return true;
        }

        return false;
    }
}