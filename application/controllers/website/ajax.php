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
		} else if ($action == 'send_review') {
			if (@$_SESSION['captha'] != $_POST['captcha']) {
				$result['status'] = false;
				$result['message'] = 'Chapcha anda tidak sama.';
				echo json_encode($result);
				exit;
			}
			
			$_POST['alias'] = get_name($_POST['name']);
			$_POST['date_update'] = $this->config->item('current_datetime');
			$result = $this->Item_Review_model->update($_POST);
			if ($result['status']) {
				$result['message'] = 'Review anda berhasil disimpan pada database kami.';
			}
		} else if ($action == 'report_broken_link') {
			$_POST['date_report'] = $this->config->item('current_datetime');
			$result = $this->Broken_Link_model->update($_POST);
			
			if ($result['status']) {
				$result['message'] = 'Terima kasih, laporan Anda berhasil disimpan dan akan segera diperbaiki oleh tim kami.';
			}
		}
		
		echo json_encode($result);
    }
}