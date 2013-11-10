<?php
class subscribe extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/user/subscribe' );
    }
	
	function grid() {
		$result['rows'] = $this->Subscribe_model->get_array($_POST);
		$result['count'] = $this->Subscribe_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			// set active for new subscriber
			if (isset($_POST['id']) && empty($_POST['id'])) {
				$_POST['is_active'] = 1;
			}
			
			$result = $this->Subscribe_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Subscribe_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->Subscribe_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$this->load->view( 'panel/user/popup/subscribe' );
	}
}