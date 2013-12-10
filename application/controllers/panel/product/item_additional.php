<?php
class item_additional extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			$result = $this->Item_Additional_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Item_Additional_model->get_by_id(array( 'item_id' => $_POST['item_id'], 'force_insert' => true ));
		} else if ($action == 'delete') {
			$result = $this->Item_Additional_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
}