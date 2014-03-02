<?php
	namespace Options;

	class Page 
	{
		private static $instance = null;

		public static function make()
		{
			self::$instance = new Page;
			return self::$instance;
		}

		public function load()
		{
			add_action('admin_menu', array($this, 'displayPluginsPage'));
		}

		public function displayPluginsPage()
		{
			$settings = add_options_page(
					'Wordpress Factory',
					'Options Title',
					'manage_options',
					'wordpress-factory',
					array($this, 'createAdminpage')
				);
		}

		public function createAdminpage()
		{

		}


	}