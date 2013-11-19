<?php
class item_review extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/product/item_review' );
    }
	
	function grid() {
		$result['rows'] = $this->Item_Review_model->get_array($_POST);
		$result['count'] = $this->Item_Review_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$_POST['date_update'] = $this->config->item('current_datetime');
			$result = $this->Item_Review_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Item_Review_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->Item_Review_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$this->load->view( 'panel/product/popup/item_review' );
	}
}