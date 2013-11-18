<?php

class ajax extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$action = (isset($_POST['action'])) ? $_POST['action'] : "";
		
		$result = array();
		if ($action == 'mail_subscriber') {
			$email = $this->Subscribe_model->get_by_id(array( 'email' => $_POST['email'] ));
			
			if (count($email) == 0) {
				$param_subscribe['email'] = $_POST['email'];
				$param_subscribe['is_active'] = 1;
				$result = $this->Subscribe_model->update($param_subscribe);
			} else if (count($email) > 0 && $email['is_active'] == 0) {
				$param_subscribe['id'] = $email['id'];
				$param_subscribe['is_active'] = 1;
				$result = $this->Subscribe_model->update($param_subscribe);
			} else if (count($email) > 0 && $email['is_active'] == 1) {
				$result['status'] = false;
				$result['message'] = 'Email anda sudah terdaftar.';
			}
			
			if ($result['status']) {
				$result['message'] = 'Email anda berhasil disimpan pada database kami.';
			}
		}
		
		echo json_encode($result);
    }
}