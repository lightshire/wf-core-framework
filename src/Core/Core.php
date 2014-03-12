<?php

	namespace Core;
	use \Core\TwigCore;
	use \Core\DoctrineCore;
	class Core
	{
		private static $instance = null;
		private $config = array();

		public static function make($config = array())
		{	
			self::$instance = new Core;
			self::$instance->config = $config;

			return self::$instance;	
		}

		public function load()
		{
			add_action('init',	array($this, 'init'));
			return $this;
		}
		
		public function init()
		{
			TwigCore::factorize();
			return $this;
		}
	
		public function launchHookLoaders()
		{
			$hookLoaders = include base_path()."/hooks.php";
			foreach($hookLoaders as $class) {
				$class::make()->load();
			}
			return $this;
		}
		
		public static function config($config_key)
		{
			return self::$instance->config[$config_key];
		}
	}