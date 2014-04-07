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


			//loading functions
			self::$twig->addFunction('sanitized_current_url', new \Twig_Function_Function('sanitized_current_url'));
			self::$twig->addFunction('base_path', new \Twig_Function_Function('base_path'));
			self::$twig->addFunction('cache_path', new \Twig_Function_Function('cache_path'));
			self::$twig->addFunction('base_url', new \Twig_Function_Function('base_url'));
			self::$twig->addFunction('plugin_url', new \Twig_Function_Function('plugin_url'));
			self::$twig->addFunction('assets_url', new \Twig_Function_Function('assets_url'));
			self::$twig->addFunction('dd', new \Twig_Function_Function('dd'));
			self::$twig->addFunction('posts_url', new \Twig_Function_Function('posts_url'));
			self::$twig->addFunction('current_url', new \Twig_Function_Function('current_url'));
			

			return self::$twig;
		}
	}