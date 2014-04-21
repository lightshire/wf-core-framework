<?php
	namespace Assets;
	class Asset
	{
		private $loc 	= "";
		private $type 	= "";

		public function __construct($loc, $type)
		{
			$this->loc 	= $loc;
			$this->type = $type;
		}


		public static function factorize($loc = 'front', $type = 'scripts')
		{
			$settings = include base_path()."/config/".$loc."_".$type."_assets.php";

			foreach($settings as $name => $values) {
				if($type == 'scripts') {
					wp_register_script($name, $values['path'], $values['deps'], $values['version']);
				}else {
					wp_register_style($name, $values['path'], $values['deps'], $values['version']);
				}
			}

			return new Asset($loc, $type);
		}

		public function load()
		{
			add_action('wp_enqueue_scripts', array($this, 'queue'));	
			return $this;	
		}

		public function queue($array = array())
		{
			if(empty($array)) {
				$array = include base_path()."/config/".$this->loc."_".$this->type."_assets.php";
			}

			foreach($array as $name => $values) {
				if($this->type == 'scripts') {
					wp_enqueue_script($name);
				}else {
					wp_enqueue_style($name);
				}
			}

			return $this;
		}

	}