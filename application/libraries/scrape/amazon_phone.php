<?php

class amazon_phone {
    function __construct() {
        $this->CI =& get_instance();
		$this->use_curl = true;
    }
	
	/*	region list page */
	
	function scrape_page($param) {
		// base url
		$base_url = preg_replace('/.com\/.+$/i', '.com', $param['link']);
		
		// set to localhost
		// $param['link'] = 'http://localhost/amazon/trunk/i.txt';
		/*
		echo $param['link']."<br />";
		$param['link'] = 'http://www.amazon.com/gp/search/ref=sr_pg_6?rh=n%3A2335752011%2Cn%3A%212335753011%2Cn%3A7072561011%2Cn%3A2407747011&page=6&ie=UTF8&qid=1384324398';
		echo $param['link']."<br />";
		exit;
		/*	*/
		
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
		if ($pos_first) {
			$content = substr($content, $pos_first, strlen($content) - $pos_first);
		}
		
		// remove end offset
		$offset = '<div id="centerBottom">';
		$pos_end = strpos($content, $offset);
		if ($pos_end) {
			$content = substr($content, 0, $pos_end);
		}
		
		return $content;
	}
	
	function get_array_item($content) {
		$content = preg_replace('/ (onload)="[^\"]+"/i', '', $content);
		
		preg_match_all('/href="([^\"]+)"><div class="imageBox">\s*<img +src="[^"]+" class="productImage" alt="Product Details"/i', $content, $match);
		$array_item = (isset($match[1])) ? $match[1] : array();
		
		return $array_item;
	}
	
	function get_array_page($content, $base_page) {
		$array_link = array();
		
		return $array_link;
	}
	
	/*	end region list page */
	
	/*	region item page */
	
	function scrape_item($param) {
		// base url
		$base_url = preg_replace('/.com\/.+$/i', '.com', $param['link']);
		
		// set to localhost
		// $param['link'] = 'http://localhost/amazon/trunk/item.txt';
		
		// get page
		$curl = new curl();
		$page = ($this->use_curl) ? $curl->get($param['link']) : file_get_contents($param['link']);
		$page_clean = $this->clean_item($page);
		
		$result['code'] = $this->get_code_item($param['link']);
		$result['name'] = $this->get_name_item($page_clean);
		$result['brand_name'] = $this->get_brand_item($page_clean);
		$result['desc'] = $this->get_desc_item($page_clean);
		$result['image'] = $this->get_image_item($page_clean);
		$result['status_stock'] = $this->get_status_stock_item($page_clean);
		
		// get price
		$price = $this->get_price_item($page_clean);
		$result = array_merge($result, $price);
		
		return $result;
	}
	
	function clean_item($content) {
		// remove start offset
		$offset = '<table border="0" cellpadding="0" cellspacing="0"  width="280" class="productImageGrid" align="left">';
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = '<h2 style="font-size: 18px;" class="orange" id="customerReviewsHeader">';
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		return $content;
	}
	
	function get_code_item($link) {
		preg_match('/dp\/([a-z0-9]+)\/ref/i', $link, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		return $result;
	}
	
	function get_name_item($content) {
		preg_match('/parseasinTitle ">\s*<span id="btAsinTitle" +>([^<]+)<\/span>/i', $content, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		return $result;
	}
	
	function get_brand_item($content) {
		preg_match('/<span id="amsPopoverTrigger"><a href="[^"]+">([^<]+)<\/a>/i', $content, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		if (empty($result)) {
			preg_match('/"btAsinTitle" +>[^<]+<\/span>\s*<\/h1>\s*<span >\s*by[^<]+<a href="[^\"]+">([^<]+)</i', $content, $match);
			$result = (isset($match[1])) ? $match[1] : $result;
		}
		
		return $result;
	}
	
	function get_desc_item($content) {
		// remove start offset
		$offset = '<div class="aplus"';
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = '<div class="emptyClear">';
		$pos_end = strpos($content, $offset);
		$content = trim(substr($content, 0, $pos_end));
		
		// set content
		$content = trim(get_length_char(strip_tags($content), 1000, ' ...'));
		
		return $content;
	}
	
	function get_image_item($content) {
		preg_match('/src="([^"]+)" id="prodImage"/i', $content, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		return $result;
	}
	
	function get_price_item($content) {
		preg_match('/<span id="current-price" style="display: inline">([^<]+)<\/span>/i', $content, $match);
		$temp_price = (isset($match[1])) ? $match[1] : '';
		$temp_price = preg_replace('/(&#36;|&ndash;)/i', '', $temp_price);
		$temp_price = preg_replace('/ +/i', ' ', $temp_price);
		$temp_price = trim($temp_price);
		
		$array_price = explode(' ', $temp_price);
		
		if (count($array_price) > 1) {
			$result['price_show'] = $array_price[1];
			$result['price_range'] = $array_price[0].' - '.$array_price[1];
		} else {
			$result['price_show'] = $array_price[0];
		}
		
		return $result;
	}
	
	function get_status_stock_item($content) {
		preg_match('/<span class="availGreen">([^<]+)<\/span>/i', $content, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		return $result;
	}
	
	/*	end region item page */
}