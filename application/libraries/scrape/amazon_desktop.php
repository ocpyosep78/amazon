<?php

class amazon_desktop {
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
		
		// scrape it for debug purpose
		// Write('item.txt', $page); echo 'done'; exit;
		
		$result['code'] = $this->get_code_item($param['link']);
		$result['name'] = $this->get_name_item($page_clean);
		$result['brand_name'] = $this->get_brand_item($page_clean);
		$result['desc'] = $this->get_desc_item($page_clean);
		$result['image'] = $this->get_image_item($page);
		$result['status_stock'] = $this->get_status_stock_item($page_clean);
		$result['desc_show'] = 1;
		
		// get price
		$price = $this->get_price_item($page_clean);
		$result = array_merge($result, $price);
		
		// print_r($result); exit;
		
		return $result;
	}
	
	function clean_item($content) {
		// remove start offset
		$offset = '<form method="post" id="handleBuy"';
		$pos_first = strpos($content, $offset);
		if ($pos_first) {
			$content = substr($content, $pos_first, strlen($content) - $pos_first);
		}
		
		// remove end offset
		$offset = '<div id="ask-btf_feature_div">';
		$pos_end = strpos($content, $offset);
		if ($pos_end) {
			$content = substr($content, 0, $pos_end);
		}
		
		return $content;
	}
	
	function get_code_item($link) {
		preg_match('/dp\/([a-z0-9]+)\/ref/i', $link, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		return $result;
	}
	
	function get_name_item($content) {
		// method #1
		preg_match('/id="title" class="[^"]+">([^<]+)<\/h1>/i', $content, $match);
		$result = (isset($match[1])) ? trim($match[1]) : '';
		
		// method #2
		if (empty($result)) {
			preg_match('/id="btAsinTitle"( style="[^\"]+")* *>([^<]+)<\/span>/i', $content, $match);
			$result = (isset($match[2])) ? trim($match[2]) : '';
		}
		
		return $result;
	}
	
	function get_brand_item($content) {
		// method #1
		preg_match('/id="brand" class="[^"]+" href="[^"]+">([^<]+)<\/a>/i', $content, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		// method #2
		if (empty($result)) {
			preg_match('/id="amsPopoverTrigger"><a href="[^\"]+">([^<]+)<\/a>/i', $content, $match);
			$result = (isset($match[1])) ? trim($match[1]) : '';
		}
		
		// method #3
		if (empty($result)) {
			preg_match('/id="btAsinTitle"( style="[^\"]+")* *>[^<]+<\/span>\s*<\/h1>\s*<span >\s*by&#160;<a href="[^\"]+">([^<]+)<\/a>/i', $content, $match);
			$result = (isset($match[2])) ? trim($match[2]) : '';
		}
		
		// method #4
		if (empty($result)) {
			preg_match('/class="bissBy">\s*By <a href="[^\"]+">([^<]+)<\/a>/i', $content, $match);
			$result = (isset($match[1])) ? trim($match[1]) : '';
		}
		
		// method #5
		if (empty($result)) {
			preg_match('/class="buying">\s*<span *>\s*by&#160;<a href="[^\"]+">([^<]+)<\/a>/i', $content, $match);
			$result = (isset($match[1])) ? trim($match[1]) : '';
		}
		
		return $result;
	}
	
	function get_desc_item($content) {
		// remove start offset
		$offset = '<div class="productDescriptionWrapper">';
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = '<div class="emptyClear">';
		$pos_end = strpos($content, $offset);
		$content = trim(substr($content, 0, $pos_end));
		
		// remove html tag
		$content = preg_replace('/<\/?(div|img|h4|h5|p)[^>]*>/i', '', $content);
		$content = trim(get_length_char(strip_tags($content), 1000, ' ...'));
		
		return $content;
	}
	
	function get_image_item($content) {
		// method #1
		preg_match('/src="([^"]+)" data-old/i', $content, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		// method #2
		if (empty($result)) {
			preg_match('/id="main-image" src="([^"]+)"/i', $content, $match);
			$result = (isset($match[1])) ? trim($match[1]) : '';
		}
		
		// method #3
		if (empty($result)) {
			preg_match('/src="([^"]+)" id="prodImage"/i', $content, $match);
			$result = (isset($match[1])) ? trim($match[1]) : '';
		}
		
		return $result;
	}
	
	function get_price_item($content) {
		// method 1
		preg_match('/<span id="(priceblock_ourprice|priceblock_saleprice)" class="[^"]+">([^<]+)<\/span>/i', $content, $match);
		$temp_price = (isset($match[2])) ? $match[2] : '';
		
		// method #2
		if (empty($temp_price)) {
			preg_match('/<span id="actualPriceValue"><b class="priceLarge">([^<]+)<\/b><\/span>/i', $content, $match);
			$temp_price = (isset($match[1])) ? trim($match[1]) : '';
		}
		
		// method #3
		if (empty($temp_price)) {
			preg_match('/List Price:\s*<\/td>\s*<td class="[^"]+\">\s*([0-9\$\.]+)\s*<\//i', $content, $match);
			$temp_price = (isset($match[1])) ? trim($match[1]) : '';
		}
		
		// remove '$' & ',' sign
		$temp_price = preg_replace('/\$/i', '', $temp_price);
		$temp_price = preg_replace('/[,]/i', '', $temp_price);
		
		$result['price_show'] = 0;
		if (!empty($temp_price)) {
			$result['price_show'] = $temp_price;
		}
		
		return $result;
	}
	
	function get_status_stock_item($content) {
		// method #1
		preg_match('/id="availability" class="[^"]+">\s*<span class="[^"]+">([^<]+)<\/span>/i', $content, $match);
		$result = (isset($match[1])) ? $match[1] : '';
		
		// method #2
		if (empty($result)) {
			preg_match('/<span class="availGreen">([^<]+)<\/span>/i', $content, $match);
			$result = (isset($match[1])) ? trim($match[1]) : '';
		}
		
		// clean space
		$result = trim(preg_replace('/\s+/i', ' ', $result));
		
		return $result;
	}
	
	/*	end region item page */
}