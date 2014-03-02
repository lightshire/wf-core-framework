<?php

	namespace Core;
	use Options\Page as OptionsPage;

	class Core
	{
		private static $instance = null;

		public static function make()
		{	
			self::$instance = new Core;
			return self::$instance;	
		}

		public function load()
		{
			add_action('init',	array($this, 'init'));
		}
		public function init()
		{
			OptionsPage::make()->load();
		}
	}