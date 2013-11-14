<?php

class combo extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$action = (!empty($_POST['action'])) ? $_POST['action'] : '';
		
		$array = array();
		if ($action == 'brand') {
			$array = $this->Brand_model->get_array(array( ));
		} else if ($action == 'category') {
			$array = $this->Category_model->get_array(array( ));
		} else if ($action == 'category_sub') {
			$array = $this->Category_Sub_model->get_array($_POST);
		} else if ($action == 'post_type') {
			$array = $this->Post_Type_model->get_array(array( ));
		} else if ($action == 'scrape') {
			$array = $this->Scrape_model->get_array(array( ));
		} else if ($action == 'user_type') {
			$array = $this->User_Type_model->get_array(array( ));
		}
		
		echo json_encode($array);
		exit;
	}
}                                                