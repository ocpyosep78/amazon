<?php

class amazon_phone {
    function __construct() {
        $this->CI =& get_instance();
		$this->use_curl = false;
    }
	
	function scrape_page($param) {
		// base url
		$base_url = preg_replace('/.com\/.+$/i', '.com', $param['link']);
		
		// set to localhost
		$param['link'] = 'http://localhost/amazon/trunk/i.txt';
		
		// get page
		$curl = new curl();
		$page = ($this->use_curl) ? $curl->get($param['link']) : file_get_contents($param['link']);
		$page_clean = $this->clean_page($page);
		
		$result['array_item'] = $this->get_array_item($page_clean);
		$result['array_page'] = $this->get_array_page($page_clean, $base_url);
		
		return $result;
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
	
	function get_array_item($content) {
		preg_match_all('/href="([^\"]+)"><div class="imageBox">\s*<img +src="[^"]+" class="productImage" alt="Product Details"/i', $content, $match);
		$array_item = (isset($match[1])) ? $match[1] : array();
		return $array_item;
	}
	
	function get_array_page($content, $base_page) {
		$array_link = array();
		
		preg_match_all('/class="pagnLink"><a href="([^\"]+)"/i', $content, $match);
		foreach ($match[0] as $key => $value) {
			$temp_link = $match[1][$key];
			$array_link[] = $base_page.$temp_link;
		}
		
		return $array_link;
	}
}