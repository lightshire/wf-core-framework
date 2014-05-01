<?php
	namespace Core;

	class Config
	{
		public static function get($namespace)
		{
			return include config_path()."/".$namespace."/config.php";
		}

		public static function create(){} //blueprint maker needed
	}