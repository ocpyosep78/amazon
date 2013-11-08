<?php

class adfly {
    function __construct() {
        $this->CI =& get_instance();
		$this->curl = new curl();
    }
    
	function convert($record, $link) {
		$link_adfly = 'http://api.adf.ly/api.php?key='.$record['array_meta_data']['key'].'&uid='.$record['array_meta_data']['uid'].'&advert_type=int&domain=adf.ly&url='.$link;
		$result = $this->curl->get($link_adfly);
		
		return $result;
	}
}
