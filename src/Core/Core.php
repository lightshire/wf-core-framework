<?php

	namespace Core;
	use \Core\TwigCore;
	use \Core\DoctrineCore;
	class Core
	{	
		/**
		 * A Private instance of the object
		 * @var Core
		 */
		private static $instance = null;

		/**
		 * The Configuration File (found at app.php)
		 * @var array
		 */
		private $config = array();


		/**
		 * Instance generator of the Object
		 * @param  array  $config the config file found at app.php
		 * @return Core         The generated config instance
		 */
		public static function make($config = array())
		{	
			self::$instance = new Core;
			self::$instance->config = $config;

			return self::$instance;	
		}


		/**
		 * The Main Hook loader (Hooks to 'init') 
		 * @return Core the generated instance
		 */
		public function load()
		{
			add_action('init',	array($this, 'init'));
			return $this;
		}
		
		/**
		 * Core Foundation Loader - this loads the core requirements
		 * of the framework. 
		 * @return Object The generated instance
		 */
		public function init()
		{
			TwigCore::factorize();
			return $this;
		}
	
		/**
		 * loads all the hooks of all the classes required by the plugin
		 * this hook loader only requires the class name, since static
		 * call is going to be created, two functions are required, the
		 * `make` and the `load`. It should be of type static and non-static
		 * respectively.
		 * 		
		 * @return Core The generated instance
		 */
		public function launchHookLoaders()
		{
			$hookLoaders = include base_path()."/hooks.php";
			foreach($hookLoaders as $class) {
				$class::make()->load();
			}
			return $this;
		}
		
		/**
		 * Getsthe config setting from the main config repository
		 * @param  mixed $config_key The key to the array
		 * @return mixed             The returned configuration setting
		 */
		public static function config($config_key)
		{
			return self::$instance->config[$config_key];
		}
	}