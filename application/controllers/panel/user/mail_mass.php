<?php
class mail_mass extends XX_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$this->load->view( 'panel/user/mail_mass' );
    }
	
	function grid() {
		$result['rows'] = $this->Mail_Mass_model->get_array($_POST);
		$result['count'] = $this->Mail_Mass_model->get_count();
		
		echo json_encode($result);
	}
	
	function action() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : '';
		unset($_POST['action']);
		
		$result = array();
		if ($action == 'update') {
			if (empty($_POST['id'])) {
				$_POST['queue_max'] = $this->Subscribe_model->get_max_user();
				$_POST['date_update'] = $this->config->item('current_datetime');
			}
			
			$result = $this->Mail_Mass_model->update($_POST);
		} else if ($action == 'get_by_id') {
			$result = $this->Mail_Mass_model->get_by_id(array( 'id' => $_POST['id'] ));
		} else if ($action == 'delete') {
			$result = $this->Mail_Mass_model->delete($_POST);
		} else if ($action == 'send_mail') {
			$mail_mass = $this->Mail_Mass_model->get_by_id(array( 'id' => $_POST['id'] ));
			
			// prepare date
			$param_subscribe = array(
				'is_active' => 1,
				'sort' => '[{"property":"id","direction":"ASC"}]',
				'start' => $mail_mass['queue_no'], 'limit' => 5
			);
			$array_subscribe = $this->Subscribe_model->get_array($param_subscribe);
			
			// sent mail
			foreach ($array_subscribe as $row) {
				$param_mail['to'] = $row['email'];
				$param_mail['subject'] = $mail_mass['name'];
				$param_mail['message'] = $mail_mass['desc'];
				// sent_mail($param_mail);
				
				// update queue
				$this->Mail_Mass_model->update_queue($mail_mass);
			}
			
			// delay 5 second
			sleep(5);
			
			// renew data
			$mail_mass = $this->Mail_Mass_model->get_by_id(array( 'id' => $_POST['id'] ));
			
			// set result
			$result = $mail_mass;
			$result['status'] = true;
			$result['message'] = 'Queue : '.$mail_mass['queue_no'].' / '.$mail_mass['queue_max'];
		}
		
		echo json_encode($result);
	}
	
	function view() {
		$this->load->view( 'panel/user/popup/mail_mass' );
	}
}