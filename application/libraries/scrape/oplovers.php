<?php

class oplovers {
    function __construct() {
        $this->CI =& get_instance();
    }
    
	function get_array($scrape) {
		$curl = new curl();
		$array_item = array();
		$content = $curl->get($scrape['link']);
		$array_post = $this->get_array_clear($content);
		
		$array_result = array();
		foreach ($array_post as $array) {
			$array['title'] = trim($array['title']);
			$link_source = $array['link'];
			
			// content already exist
			$check = $this->CI->Scrape_Content_model->get_by_id(array( 'link_source' => $link_source ));
			if (count($check) > 0) {
				continue;
			}
			
			// collect
			$content_html = $this->get_content($link_source);
			$desc = $this->get_desc($content_html);
			$image = $this->get_image($content_html);
			$download = $this->get_download($content_html);
			
			// set to array
			$temp = array();
			$temp['name'] = $array['title'];
			$temp['desc'] = $desc;
			$temp['download'] = $download;
			$temp['link_source'] = $link_source;
			$temp['image_source'] = $image;
			$temp['category_id'] = $scrape['category_id'];
			$temp['post_type_id'] = $scrape['post_type_id'];
			$temp['scrape_master_id'] = $scrape['id'];
			$temp['scrape_time'] = $this->CI->config->item('current_datetime');
			$array_result[] = $temp;
			
			// add limit
			if (count($array_result) >= 10) {
				break;
			}
		}
		
		return $array_result;
	}
	
	function get_array_clear($content) {
		$array_result = array();
		$array_content = new SimpleXmlElement($content);
		
		/*	
		// add link here
		$array_result[] = array('title' => 'Shingeki no Kyojin Episode 22 Subtitle Indonesia', 'link' => 'http://www.oploverz.net/2013/09/shingeki-no-kyojin-episode-22-subtitle.html');
		/*	*/
		
		foreach ($array_content->channel->item as $array_temp) {
			$array_temp = (array)$array_temp;
			unset($array_temp['category']);
			unset($array_temp['description']);
			
			$array_result[] = (array)$array_temp;
		}
		
		return $array_result;
	}
	
	function get_content($link_source) {
		// get html content
		$curl = new curl();
		$content_html = $curl->get($link_source);
		$content_html = preg_replace('/[^\x20-\x7E|\x0A]/i', '', $content_html);
		
		// remove start offset
		$offset = 'post-body-';
		$pos_first = strpos($content_html, $offset);
		$content_html = "<div class='".substr($content_html, $pos_first, strlen($content_html) - $pos_first);
		
		// remove end offset
		$offset = "<div class='post-footer'>";
		$pos_end = strpos($content_html, $offset);
		$content_html = substr($content_html, 0, $pos_end);
		
		return $content_html;
	}
	
	function get_desc($content) {
		$content = preg_replace('/\<blink[\x20-\x7E|\x0A]+/i', '', $content);
		$content = preg_replace('/\Jadwal Rilis OPLOVERZ bisa dilihat di[\x20-\x7E|\x0A]+/i', '', $content);
		$content = strip_tags($content);
		$content = str_replace('&nbsp;', ' ', $content);
		$content = trim(preg_replace('/[\n]+/i', "\n", $content));
		
		$result = '';
		$array_temp = explode("\n", $content);
		foreach ($array_temp as $line) {
			$line = (empty($line)) ? '&nbsp;' : $line;
			$result .= '<div>'.trim($line).'</div>';
		}
		
		// endfix
		$result .= '<br /><div>Sumber : Oplovers</div>';
		
		return $result;
	}
	
	function get_image($content) {
		$content = preg_replace('/(border|height|width)\=\"\d+\"/i', ' ', $content);
		$content = preg_replace('/[ ]+/i', ' ', $content);
		preg_match('/\<img src\=\"([^\"]+)\"/i', $content, $match);
		
		$image = (isset($match[1]) && !empty($match[1])) ? $match[1] : '';
		
		return $image;
	}
	
	function get_download($content) {
		$result = '';
		
		// clean content
		$content = preg_replace('/(rel|style|target)\=\"[^\"]+\"/i', '', $content);
		$content = preg_replace('/ +\>/i', '>', $content);
		$content = preg_replace('/\<\/?(b|span|dwn)\>/i', '', $content);
		
		preg_match_all('/\<a href\=\"([^\"]+)\"\>\[?([\w\s]+)\]?\<\/a\>/i', $content, $match);
		if (isset($match[1])) {
			foreach ($match[1] as $key => $value) {
				$link = $match[1][$key];
				$label = $match[2][$key];
				
				$result .= $link.' '.$label."\n";
			}
		}
		
		// trim it
		$result = trim($result);
		
		return $result;
	}
}
