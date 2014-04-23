<?php
	namespace Core;
	use \Core\TwigCore;
	use \Core\DoctrineCore;
	use \Core;
	use \Core\Pagination;
	class Model
	{
		/**
		 * A public instance of the object
		 * @var Model
		 */
		protected $query = null;

		/**
		 * The where query generator for the chaining method
		 * 
		 * @var array
		 */
		protected $whereQuery = array();


		/**
		* The default attributes to match specific 
		* value requests
		* @var array
		*/

		protected $defaults = array();

		/**
		 * The Model column attributes
		 * @var array
		 */
		protected $attributes = array();

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
		public static function make(array $args = array())
		{
			$class = get_called_class();
			return new $class($args);		

		}



		/**
		 * Database Queries
		 * -------------------/
		 */


		public function where($attr, $value, $selector = "=")
		{
			$where = "{$attr} {$selector} '".mysql_real_escape_string($value)."'";
			$this->whereQuery[] = array(
					'query' 	=> $where,
					'selector' 	=> $selector,
					'merge' 	=> 'AND'
				);

			return $this;
		}

		public function orWhere($attr, $value, $selector = "=")
		{
			$where = "{$attr} {$selector} '".mysql_real_escape_string($value)."'";

			$this->whereQuery[] = array(
					'query' 	=> $where,
					'selector' 	=> $selector,
					'merge' 	=> 'OR'
				);

			return $this;
		}

		public function whereRaw($details)
		{
			$this->whereQuery[] = array(
					'query' 	=> $details,
					'selector' 	=> null,
					'merge' 	=> 'AND'				
				);

			return $this;
		}

		public function oRwhereRaw($details)
		{
			$this->whereQuery[] = array(
					'query' 	=> $details,
					'selector' 	=> null,
					'merge' 	=> 'OR' 
				);

			return $this;
		}

		public function whereBetween($attr, $firstValue, $secondValue)
		{
			$query = "{$attr} between {$firstValue} and {$secondValue}";

			$this->whereQuery[] = array(
					'query' 	=> $query,
					'selector'	=> null,
					'merge' 	=> 'AND'
				);

			return $this;
		}

		public function orWhereBetween($attr, $firstValue, $secondValue)
		{
			$query = "{$attr} between {$firstValue} and {$secondValue}";

			$this->whereQuery[] = array(
					'query' 	=> $query,
					'selector' 	=> null,
					'merge' 	=> 'OR'
				);

			return $this;
		}

		public function first()
		{
			global $wpdb;

			$class = get_class($this);

			$table = self::getTableName();

			$query =  "select * from {$table} ".$this->generateWhere()." limit 1";

			$row  =  $wpdb->get_row($query, ARRAY_A);

			return new $class($row);
		}


		public function paginate($per_page = 10, $page_identifier = "page_id")
		{
			global $wpdb;

			$table 		= self::getTableName();
			$className 	= get_called_class();
			$models 	= array();
			$class 		= get_class($this);
			$count 		= 0;

			$page = isset($_GET[$page_identifier]) ? $_GET[$page_identifier] : 1;

			$offset = ($page-1)*$per_page;

			$query = "select * from {$table} ".$this->generateWhere();
			$countQuery = "select count(id) from {$table} ".$this->generateWhere();

			$query = rtrim($query, "where ");
			$countQuery = rtrim($countQuery, "where ");
			
			$query .= " limit {$per_page} offset {$offset}";

			// dd($query);

			$results = $wpdb->get_results($query, ARRAY_A);
			$count 	 = $wpdb->get_var($countQuery, 0, 0);

			foreach($results as $result) {
				$models[] = new $class($result);
			}
			

			return Pagination::make($models, $page, ceil($count / $per_page), $page_identifier);
		}

		public function get()
		{
			$models = array();
			$class = get_class($this);

			global $wpdb;

			$table = self::getTableName();

			$query =  "select * from {$table} ".$this->generateWhere();
			$query = rtrim($query, "where ");

			$results = $wpdb->get_results($query, ARRAY_A);
			// dd($query);

			foreach($results as $result) {

				$models[] = new $class($result);
			}

			return $models;
		}

		protected function generateWhere()
		{
			$whereQuery = "";
			foreach($this->whereQuery as $query)
			{
				$whereQuery .= " ".$query["merge"];

				$whereQuery .= " ".$query['query'];


			}

			$whereQuery = ltrim($whereQuery, " OR");
			$whereQuery = ltrim($whereQuery, " AND");

		
			$whereQuery = "where ".$whereQuery;
			return $whereQuery;
		}

		// public function where

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
			// dd($wpdb);
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

			$query = "select * from {$table} where id = {$id} limit 1";
			
			$result = $wpdb->get_row($query, ARRAY_A);

			if(!is_array($result)) {
				return null;
			}

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

			return strtolower($table);
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

		public function __isset($attr)
		{
			return array_key_exists($attr, $this->attributes);
		}

		public function attrIsset($attr)
		{
			return array_key_exists($attr, $this->attributes);
		}


		public function validate(array $input)
		{
			$defaults = $this->defaults;
			foreach($defaults as $default) {
				if(!isset($input[$default]) || $input[$default] == "") {
					return false;
				}
			}
			// return $defaults == array_keys($input);
			return true;
		}

		public function validateAndSave(array $input)
		{
			
			if($this->validate($input)) {

				$this->attributes = $input;
				return $this->save();

			}else {
				return false;
			}
		}

		




	}