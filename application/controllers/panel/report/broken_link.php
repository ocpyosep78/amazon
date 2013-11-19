<?php
class broken_link extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/report/broken_link' );
    }
	
	function grid() {
		$result['rows'] = $this->Broken_Link_model->get_array($_POST);
		$result['count'] = $this->Broken_Link_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Broken_Link_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Broken_Link_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->Broken_Link_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
}