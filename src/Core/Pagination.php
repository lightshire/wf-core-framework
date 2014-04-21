<?php
	namespace Core;

	class Pagination
	{
		private $rows = array();

		private $page_id  = 0;

		private $total 	  = 0;

		protected $page_identifier = "page_id";


		public static function make($rows, $page_id, $total, $page_identifier = "page_id")
		{
			$pagination = new Pagination;
			$pagination->rows 		= $rows;
			$pagination->page_id 	= $page_id;
			$pagination->total 		= $total;
			$pagination->page_identifier = $page_identifier;

			return $pagination;
		}

		public function links()
		{
			$paginate =  paginate_links(array(
					'base' 		=> add_query_arg($this->page_identifier, '%#%'),
					'format'    => "?".$this->page_identifier."=%#%",
					'prev_text' => __('&laquo;'),
					'next_text' => __('&raquo;'),
					'total' 	=> $this->total,
					'current' 	=> $this->page_id,
					'type' 		=> 'array'
				));
			

			$content = "";

			if($paginate == null) {
				return $content;
			}
			$content = "<ul class='pagination'>";

			foreach($paginate as $p) {
				if(strpos($p, 'current') > 0) {
					$active = "class='active'";
					$p = str_replace('current', 'active', $p);	
				}else {
					$active = "";
				}

				$content .= "<li {$active}>{$p}</li>";
			}

			$content .= "</ul>";

			return $content;
		}

		public function rows()
		{
			return $this->rows;
		}

	}