<?php
	namespace Core;

	class Session
	{
		public static function start()
		{
			if(!session_id()) {
				session_start();
			}
		}

		public static function destroy()
		{
			session_destroy();
		}

		public static function get($key)
		{
			Session::start();
			return $_SESSION[$key];
		}

		public static function set($key, $value)
		{
			Session::start();
			$_SESSION[$key] = $value;
		}
		
	}
