<?php
class user extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/user/user' );
    }
	
	function grid() {
		$result['rows'] = $this->User_model->get_array($_POST);
		$result['count'] = $this->User_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			if (isset($_POST['passwd'])) {
				if (empty($_POST['passwd'])) {
					unset($_POST['passwd']);
				} else {
					$_POST['passwd'] = EncriptPassword($_POST['passwd']);
				}
			}
			
			$result = $this->User_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->User_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->User_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$this->load->view( 'panel/user/popup/user' );
	}
}