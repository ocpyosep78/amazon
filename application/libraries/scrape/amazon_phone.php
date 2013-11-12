<?php

class amazon_phone {
    function __construct() {
        $this->CI =& get_instance();
    }
	
	function scrape_page($param) {
		// base url
		$base_url = preg_replace('/\/[a-z0-9\=\&\_\?]+$/i', '/', $param['link']);
		
		// get page
		$curl = new curl();
		$page = $curl->get($param['link']);
		$page_clean = $this->clean_page($page);
		
		$array_item = $this->get_array_item($page_clean);
		$array_page = $this->get_array_page($page_clean, $base_url);
		
		echo $page_clean; exit;
	}
	
	function clean_page($content) {
		// remove start offset
		$offset = '<div id="mainResults"';
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = '<div id="centerBottom">';
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		return $content;
	}
	
	function get_array_page($content, $base_page) {
		
	}
}
