<?php
	
	namespace Core;
	use \Twig;
	use \Core\Core as Core;

	class TwigCore
	{
		public static $loader 	= null;
		public static $twig 	= null;

		public static function factorize()
		{
			self::$loader 	= new \Twig_Loader_Filesystem(base_path().'/views');
			self::$twig 	= new \Twig_Environment(self::$loader, Core::config('twig'));
			return self::$twig;
		}
	}