<?php
	namespace Core;


	/**
	 *  An Interface Class implementation of the `Page` class.
	 *  The Page class would be the skeleton of all classes 
	 *  that are pages in the plugin
	 */
	interface Page
	{
		/**
		 * Make generates an instance
		 * @return Page generated instance
		 */
		public static function make();

		/**
		 * Load - loads all the hooks to the wordpress framework
		 * @return Page generated instance
		 */
		public function load();

		/**
		 * post - a required function that catched all posts done by the functions
		 */

		public static function post();

	}