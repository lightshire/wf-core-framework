<?php
	namespace Mail;

	class Mail
	{

		private static $instance = null;
		private $args 	= array();


		public function __construct($args = array(
				'is_smtp' 	=> false,
				'host' 		=> 'smtp.gmail.com',
				'smtp_auth' => true,
				'port' 		=> 465,
				'username' 	=> '',
				'password' 	=> '',
				'smtp_secure' => 'ssl',
				'from' 		=> 'admin@site.com',
				'from_name' => 'sourcescript'
			))
		{
			$this->args = $args;
		}



		public static function factorize($args = array())
		{
			$mail = new Mail($args);
			return $mail;
		}

		public function load()
		{
			add_action('phpmailer_init', array($this, 'mailer'));
			return $this;
		}

		public function mailer(PHPMailer $phpmailer)
		{
			if($args['is_smtp']) {
				$phpmailer->isSMTP();
			}

			$phpmailer->Host 		= $args['host'];
			$phpmailer->SMTPAuth 	= $args['smtp_auth'];
			$phpmailer->Port 		= $args['port'];
			$phpmailer->Username 	= $args['username'];
		    $phpmailer->Password 	= $args['password'];
		    $phpmailer->SMTPSecure 	= $args['smtp_secure'];
		    $phpmailer->From 		= $args['from'];
		    $phpmailer->FromName 	= $args['from_name'];
		    return $this;
		}

		public static function send($email, $title = "", $body = "", $headers = null) {
			return wp_mail($email, $title, $body, $headers);
		}


	}