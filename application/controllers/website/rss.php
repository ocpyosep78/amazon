<?php

class rss extends CI_Controller {
    function __construct() {
        parent::__construct();
    }
    
    function index() {
		$param_item['sort'] = '[{"property":"date_update","direction":"DESC"},{"property":"id","direction":"DESC"}]';
		$array_item = $this->Item_model->get_array($param_item);
		
		$rss_param['link'] = base_url('rss');
		$rss_param['title'] = 'Megashop - Latest Item';
		$rss_param['array_item'] = $array_item;
		$rss_param['description'] = 'Lastest Item from our Shop';
		
		$this->load->view( 'website/rss', $rss_param );
    }
}