<?php
class item_compare extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
	
	function grid() {
		$result['rows'] = $this->Item_Compare_model->get_array($_POST);
		$result['count'] = $this->Item_Compare_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Item_Compare_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Item_Compare_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->Item_Compare_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
}