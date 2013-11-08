<?php

class awsubs {
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
			
			$content_html = $this->get_content($link_source);
			$desc = $this->get_desc($content_html);
			$download = $this->get_download($content_html);
			$image_source = $this->get_image($content_html);
			
			// set to array
			$temp = array();
			$temp['name'] = $array['title'];
			$temp['desc'] = $desc;
			$temp['download'] = $download;
			$temp['link_source'] = $link_source;
			$temp['image_source'] = $image_source;
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
		$array_result[] = array('title' => 'Little Busters! Refrain Episode 1 Subtitle Indonesia', 'link' => 'http://www.wardhanime.net/2013/10/little-buster-refrain-episode-01.html');
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
		$curl = new curl();
		$content = $curl->get($link_source);
		$content = preg_replace('/[^\x20-\x7E|\x0A]/i', '', $content);
		
		// remove start offset
		$offset = "<div class='post-body entry-content'>";
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = "<div id='area-bawah'>";
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		return $content;
	}
	
	function get_desc($content) {
		$content = strip_tags($content);
		$content = preg_replace('/(480|720).+/i', '', $content);
		$content = preg_replace("/[\n]+/i", "\n", $content);
		$content = str_replace('&nbsp;', ' ', $content);
		$content = trim($content);
		
		$result = '';
		$array_temp = explode("\n", $content);
		foreach ($array_temp as $line) {
			$line = (empty($line)) ? '&nbsp;' : $line;
			$result .= '<div>'.$line.'</div>';
		}
		
		// endfix
		$result .= '<div></div>';
		$result .= '<div>Sumber : AWSubs</div>';
		
		return $result;
	}
	
	function get_download($content) {
		$result = '';
		
		// make it clean
		$content = str_replace('&nbsp;', ' ', $content);
		$content = preg_replace('/ (style|class|target|rel)\=\"[^\"]+\"/i', '', $content);
		$content = preg_replace('/<\/?(b|span)([^\>]+)?>/i', '', $content);
		
		// remove start offset
		$offset = '<div dir="ltr" trbidi="on">';
		$pos_first = strpos($content, $offset);
		$content = '<div '.substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = "<center>";
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// remove empty link
		$content = preg_replace('/\| <strike>[^\<]+<\/strike>/i', '', $content);
		$content = preg_replace('/<strike>[^\<]+<\/strike> \|/i', '', $content);
		
		// get common link
		preg_match_all('/div>([^\<]+)<\/div>\s<div>\s*(<a href="[^"]+"\>[^<]+<\/a>[ \|]*)+/i', $content, $match);
		foreach ($match[0] as $key => $value) {
			$label = trim($match[1][$key]);
			preg_match_all('/<a href="([^"]+)"\>([^<]+)<\/a>/i', $value, $array_link);
			
			// validation
			if (count($array_link[0]) == 0) {
				continue;
			}
			
			$result .= (empty($result)) ? "$label\n" : "\n$label\n";
			foreach ($array_link[0] as $counter => $temp) {
				$link_href = $array_link[1][$counter];
				$link_title = $array_link[2][$counter];
				
				$result .= $link_href.' '.$link_title."\n";
			}
		}
		
		// get multiplart link
		preg_match_all('/div>([^\<]+)<\/div>\s<div>\s([a-z0-9 ]+[=]\s*(<a href="[^"]+"\>[a-z]+<\/a>[ \|]*)*\s*)*/i', $content, $match);
		foreach ($match[0] as $key => $raw_html) {
			// check link
			$raw_link = $match[2][$key];
			if (empty($raw_link)) {
				continue;
			}
			
			// primary label
			$label_file = trim($match[1][$key]);
			
			$temp_result = '';
			preg_match_all('/([a-z0-9 ]+)[=]\s*(<a href="[^"]+"\>[a-z]+<\/a>[ \|]*)*/i', $raw_html, $raw_link);
			foreach ($raw_link[0] as $key => $value) {
				$temp_part = '';
				$label_part = trim($raw_link[1][$key]);
				
				preg_match_all('/<a href="([^"]+)"\>([a-z]+)<\/a>/i', $value, $array);
				foreach ($array[0] as $i => $j) {
					$temp_part .= $array[1][$i].' '.$array[2][$i]."\n";
				}
				
				$temp_result .= trim($label_part."\n".$temp_part)."\n\n";
			}
			
			// trim it
			$temp_result = trim($temp_result);
			
			// write label once
			if (!empty($label_file)) {
				$result .= "\n\n$label_file\n\n";
				$label_file = '';
			}
			
			// write part link
			$result .= $temp_result;
		}
		
		// trim it
		$result = trim($result);
		
		return $result;
	}
	
	function get_image($content) {
		$content = preg_replace('/(border|height|width)\=\"\d+\"/i', '', $content);
		preg_match('/<img +src=\"([^\"]+)\"/i', $content, $match);
		$result = (isset($match[1]) && !empty($match[1])) ? $match[1] : '';
		
		return $result;
	}
}