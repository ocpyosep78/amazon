<?php

class other extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		// default param
		$category = $category_sub = $page_static = array();
		
		// alias 1
		$alias_temp_1 = (isset($this->uri->segments[1])) ? $this->uri->segments[1] : '';
		if (!empty($alias_temp_1)) {
			// it's category
			$category = $this->Category_model->get_by_id(array( 'alias' => $alias_temp_1 ));
			
			// it's page static
			if (count($category) == 0) {
				$page_static = $this->Page_Static_model->get_by_id(array( 'alias' => $alias_temp_1 ));
			}
		}
		
		// alias 2
		$alias_temp_2 = (isset($this->uri->segments[2])) ? $this->uri->segments[2] : '';
		if (!empty($alias_temp_2)) {
			$category_sub = $this->Category_Sub_model->get_by_id(array( 'alias' => $alias_temp_2 ));
		}
		
		if (count($category) > 0 || count($category_sub) > 0) {
			$this->load->view( 'website/search', array( 'category' => $category, 'category_sub' => $category_sub ) );
		} else if (count($page_static) > 0) {
			$this->load->view( 'website/page_static', array( 'page_static' => $page_static ) );
		} else {
			// it's item
			$this->load->view( 'website/item' );
		}
    }
}