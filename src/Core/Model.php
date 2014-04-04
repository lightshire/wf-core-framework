<?php
	namespace Core;
	use \Core\TwigCore;
	use \Core\DoctrineCore;
	use \Core;

	class Model
	{
		/**
		 * A Private instance of the object
		 * @var Model
		 */
		private $query = null;
		

		/**
		 * The Model column attributes
		 * @var array
		 */
		private $attributes = array();

		/**
		 * Kaylangan ko pa ba sabihin ano to?
		 * @param array $args
		 */
		public function __construct(array $attr = array())
		{
			
			$this->attributes = $attr;
		}
		

		/**
		 * Instance of the model class
		 * this is a wrapper of the WP_Query
		 * @param  array  $args The configuration or array of the
		 * arguments given and created for WP_Query
		 * @return Model     
		 */
		private static function make(array $args = array())
		{
	
			return new self($args);		

		}



		/**
		 * Database Queries
		 * -------------------/
		 */


		public function save()
		{
			global $wpdb;
			
			$table = self::getTableName();
			$data  = $this->attributes;

			if(!$this->attrIsset("id")) {
				$this->id = $wpdb->insert($table, $data);
			}else {
				$wpdb->update($table, $data, array('id' => $this->id));
			}

			return $this;
		}


		/**
		 * Get all the models
		 * @return array
		 */
		public static function all()
		{
			global $wpdb;

			$table = self::getTableName();
			$query = "select * from {$table}";

			$result 	= $wpdb->get_results($query, ARRAY_A);
			$results 	= array();
			$className 	= get_called_class();

			foreach($result as $r) {
				$results[] = new $className($r);
			}

			return $results;
		}


		/**
		 * Get a singular model instance
		 * @param  id $id the model id from the server
		 * @return Model 
		 */
		public static function find($id)
		{
			global $wpdb;

			$table = self::getTableName();
			$className = get_called_class();

			$query = "select * from {$table} where id = {$id}";

			$result = $wpdb->get_row($query, ARRAY_A);

			return new $className($result);

		}



		/**
		 * The table name w/ the wordpress prefix
		 * @return string the generated wordpress site
		 */
		public static function getTableName()
		{
			global $wpdb;

			$table = get_called_class();
			$table = self::pluralize($table);
			$table = $wpdb->prefix.$table;

			return $table;
		}


		/**
		 * Pluralization Algorithm - taken form the internet
		 * courtesy of http://paulosman.me/2007/03/03/php-pluralize-method.html
		 * @param  string $string the string / database name to 
		 * pluralize
		 * @return string         the pluralized format
		 */
		public static function pluralize( $string ) 
	    {

	        $plural = array(
	            array( '/(quiz)$/i',               "$1zes"   ),
		        array( '/^(ox)$/i',                "$1en"    ),
		        array( '/([m|l])ouse$/i',          "$1ice"   ),
		        array( '/(matr|vert|ind)ix|ex$/i', "$1ices"  ),
		        array( '/(x|ch|ss|sh)$/i',         "$1es"    ),
		        array( '/([^aeiouy]|qu)y$/i',      "$1ies"   ),
		        array( '/([^aeiouy]|qu)ies$/i',    "$1y"     ),
	            array( '/(hive)$/i',               "$1s"     ),
	            array( '/(?:([^f])fe|([lr])f)$/i', "$1$2ves" ),
	            array( '/sis$/i',                  "ses"     ),
	            array( '/([ti])um$/i',             "$1a"     ),
	            array( '/(buffal|tomat)o$/i',      "$1oes"   ),
	            array( '/(bu)s$/i',                "$1ses"   ),
	            array( '/(alias|status)$/i',       "$1es"    ),
	            array( '/(octop|vir)us$/i',        "$1i"     ),
	            array( '/(ax|test)is$/i',          "$1es"    ),
	            array( '/s$/i',                    "s"       ),
	            array( '/$/',                      "s"       )
	        );

	        $irregular = array(
		        array( 'move',   'moves'    ),
		        array( 'sex',    'sexes'    ),
		        array( 'child',  'children' ),
		        array( 'man',    'men'      ),
		        array( 'person', 'people'   )
	        );

	        $uncountable = array( 
		        'sheep', 
		        'fish',
		        'series',
		        'species',
		        'money',
		        'rice',
		        'information',
		        'equipment'
	        );

	        // save some time in the case that singular and plural are the same
	        if ( in_array( strtolower( $string ), $uncountable ) )
	        	return $string;

	        // check for irregular singular forms
	        foreach ( $irregular as $noun ) {
		        if ( strtolower( $string ) == $noun[0] )
		            return $noun[1];
	        }

	        // check for matches using regular expressions
	        foreach ( $plural as $pattern ) {
		        if ( preg_match( $pattern[0], $string ) )
		            return preg_replace( $pattern[0], $pattern[1], $string );
	        }
	    
	        return $string;
	    }

	

		public function __get($attr)
		{
			if(array_key_exists($attr, $this->attributes)) {
				return $this->attributes[$attr];
			}

			$trace = debug_backtrace();
	        trigger_error(
	            'Undefined property via __get(): ' . $name .
	            ' in ' . $trace[0]['file'] .
	            ' on line ' . $trace[0]['line'],
	            E_USER_NOTICE);
	        return null;

		}

		public function __set($attr, $val)
		{
			$this->attributes[$attr] = $val;
		}

		public function attrIsset($attr)
		{
			return array_key_exists($attr, $this->attributes);
		}




	}