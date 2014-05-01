<?php
	namespace Mail;

	class Mail
	{

		private static $instance = null;
		private static $args 	= array();


		public function __construct($args = array())
		{
			self::$args = $args;

		}



		public static function factorize()
		{
			$args = \Core\Config::get("mail");

			$mail = new Mail($args);
			return $mail;
		}

		public function load()
		{
			add_action('phpmailer_init', array($this, 'mailer'));
			add_filter('wp_mail_content_type', array($this, 'mailContentType'));
			return $this;
		}

		public function mailContentType()
		{
			return "text/html";	
		}

		public function mailer(\PHPMailer $phpmailer)
		{
			if(self::$args['is_smtp']) {
				$phpmailer->isSMTP();
			}

			$phpmailer->Host 		= self::$args['host'];
			$phpmailer->SMTPAuth 	= self::$args['smtp_auth'];
			$phpmailer->Port 		= self::$args['port'];
			$phpmailer->Username 	= self::$args['username'];
		    $phpmailer->Password 	= self::$args['password'];
		    $phpmailer->SMTPSecure 	= self::$args['smtp_secure'];
		    $phpmailer->From 		= self::$args['from'];
		    $phpmailer->FromName 	= self::$args['from_name'];

		  
		    return $this;
		}

		public static function send($email, $title = "", $body = "", $headers = null) {
			return wp_mail($email, $title, $body, $headers);
		}


	}