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
			
			if($value = $_SESSION[$key]) {
				return $value;
			}else {
				global $$key;
				return $$key;
			}
		}

		public static function set($key, $value)
		{
			Session::start();
			global $$key;
			$$key = $value;
			$_SESSION[$key] = $value;
		}
		
		public static function flush($key)
		{
			Session::start();
			unset($_SESSION[$key]);
		}
	}
