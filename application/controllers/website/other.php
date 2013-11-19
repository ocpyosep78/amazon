<?php

class other extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		// default param
		$category = $category_sub = array();
		
		$alias_temp_1 = (isset($this->uri->segments[1])) ? $this->uri->segments[1] : '';
		if (!empty($alias_temp_1)) {
			$category = $this->Category_model->get_by_id(array( 'alias' => $alias_temp_1 ));
		}
		$alias_temp_2 = (isset($this->uri->segments[2])) ? $this->uri->segments[2] : '';
		if (!empty($alias_temp_2)) {
			$category_sub = $this->Category_Sub_model->get_by_id(array( 'alias' => $alias_temp_2 ));
		}
		
		if (count($category) > 0 || count($category_sub) > 0) {
			$this->load->view( 'website/home', array( 'category' => $category, 'category_sub' => $category_sub ) );
		} else {
			// it's item
			$this->load->view( 'website/item' );
		}
    }
}