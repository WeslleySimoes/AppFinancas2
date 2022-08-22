<?php 

	namespace app\session;

	class Session
	{
		protected static function get($index)
		{
			return isset($_SESSION[$index]) ? $_SESSION[$index] : null;
		}

		protected static function add($index,$value)
		{
			$_SESSION[$index] = $value;
		}

		protected static function remove($index)
		{
			unset($_SESSION[$index]);
		}

		protected static function sessionDestroy()
		{
			session_destroy();
		}
	}