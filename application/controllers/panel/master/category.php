<?php
class category extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/master/category' );
    }
	
	function grid() {
		$result['rows'] = $this->Category_model->get_array($_POST);
		$result['count'] = $this->Category_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Category_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Category_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->Category_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$this->load->view( 'panel/master/popup/category' );
	}
}