<?php
class page_static extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/master/page_static' );
    }
	
	function grid() {
		$result['rows'] = $this->Page_Static_model->get_array($_POST);
		$result['count'] = $this->Page_Static_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			if (isset($_POST['desc'])) {
				$_POST['desc'] = clean_html_style($_POST['desc']);
			}
			
			$result = $this->Page_Static_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Page_Static_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->Page_Static_model->delete($_POST);
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$this->load->view( 'panel/master/popup/page_static' );
	}
}