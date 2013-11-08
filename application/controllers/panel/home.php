<?php

class home extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$this->load->view( 'panel/home');
	}
	
	function check() {
		$is_login = $this->User_model->is_login(true);
		
		$result['success'] = false;
		if ($is_login) {
			$result['success'] = true;
			$result['menu'] = $this->User_model->get_menu();
		}
		
		echo json_encode($result);
		exit;
	}
	
	function login() {
		$user = $this->User_model->get_by_id(array( 'email' => $_POST['email'] ));
		
		$result = array( 'success' => false, 'message' => '' );
		if (count($user) == 0) {
			$result['message'] = 'Maaf, user anda tidak ditemukan';
		} else if ($user['is_active'] == 0) {
			$result['message'] = 'Maaf, user anda tidak aktif';
		} else if ($user['passwd'] == EncriptPassword($_POST['passwd'])) {
			$result['success'] = true;
			$result['menu'] = $this->User_model->get_menu();
			$this->User_model->set_session($user);
			
			/*
			// update last login
			$param['id'] = $user['id'];
			$param['login_last_date'] = $this->config->item('current_datetime');
			$this->User_model->update($param);
			/*	*/
		}
		
		echo json_encode($result);
		exit;
	}
	
	function logout() {
		$this->User_model->del_session();
		header("Location: ".base_url());
		exit;
	}
	
	function dashboard() {
		$this->load->view( 'panel/dashboard');
	}
}