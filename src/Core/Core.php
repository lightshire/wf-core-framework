<?php
	require_once plugin_path()."/vendor/symphony/src/Symphony/Component/ClassLoader/UniversalClassLoader.php";
	use Symphony\Component\ClassLoader\UniversalClassLoader;
	namespace Core;

	$loader = new UniversalClassLoader();
	$loader->register();


	function base_path()
	{
		return dirname(__FILE__);
	}

	function storage_path()
	{
		return base_path()."/storage";
	}

	function cache_path()
	{
		return storage_path()."/cache";
	}
	function base_url()
	{
		return site_url('/');
	}
	function plugin_url()
	{
		return plugins_url().'/wordpress-factory/';
	}
	function dd($value)
	{
		var_dump($value);
		die();
	}

	class Core
	{
		
	}