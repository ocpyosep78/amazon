<?php

class XX_Controller extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->User_model->required_login();
    }
}