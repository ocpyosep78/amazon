<?php

class alibaba {
    function __construct() {
        $this->CI =& get_instance();
		$this->is_index = true;
    }
    
	function get_array($scrape) {
		$curl = new curl();
		$array_item = array();
		$content = ($this->is_index) ? file_get_contents($scrape['link']) : $curl->get($scrape['link']);
		$array_post = $this->get_array_clear($content);
		
		$array_result = array();
		foreach ($array_post as $array) {
			$array['title'] = trim($array['title']);
			
			// content already exist
			$check = $this->CI->Scrape_Content_model->get_by_id(array( 'link_source' => $array['link'] ));
			if (count($check) > 0) {
				continue;
			}
			
			// make content clean
			$content_item = ($this->is_index) ? file_get_contents($array['link']) : $curl->get($array['link']);
			$content_item = preg_replace('/[^\x20-\x7E|\x0A]/i', '', $content_item);
			
			// collect data
			$desc = $this->get_desc($content_item);
			$download = $this->get_download($content_item);
			$image_source = $this->get_image($content_item);
			
			// set to array
			$temp = array();
			$temp['name'] = $array['title'];
			$temp['desc'] = $desc;
			$temp['download'] = $download;
			$temp['image_source'] = $image_source;
			$temp['link_source'] = $array['link'];
			$temp['category_id'] = $scrape['category_id'];
			$temp['post_type_id'] = $scrape['post_type_id'];
			$temp['scrape_master_id'] = $scrape['id'];
			$temp['scrape_time'] = $this->CI->config->item('current_datetime');
			$array_result[] = $temp;
			
			// add limit
			if (count($array_result) >= 5) {
				break;
			}
		}
		
		return $array_result;
	}
	
	function get_array_clear($content) {
		$array_result = array();
		
		/*
		// add link here
		$array_result[] = array('title' => 'Rou Kyuu Bu! SS Episode 12 Subtitle Indonesia [Final]', 'link' => 'http://www.alibabasub.net/2013/10/rou-kyuu-bu-ss-episode-12-subtitle.html');
		$array_result[] = array('title' => 'Tamayura: More Aggressive Episode 11 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/09/tamayura-more-aggressive-episode-11.html');
		$array_result[] = array('title' => 'Kami nomi zo Shiru Sekai: Megami-hen Episode 11 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/09/kami-nomi-zo-shiru-sekai-megami-hen.html');
		$array_result[] = array('title' => 'Inu to Hasami wa Tsukaiyou Episode 12 Subtitle Indonesia [Final]', 'link' => 'http://www.alibabasub.net/2013/09/inu-to-hasami-wa-tsukaiyou-episode-12.html');
		$array_result[] = array('title' => 'Shingeki no Kyojin Episode 24 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/09/shingeki-no-kyojin-episode-24-subtitle.html');
		$array_result[] = array('title' => 'Choujigen Game Neptune: The Animation Episode 11 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/09/choujigen-game-neptune-animation.html');
		$array_result[] = array('title' => 'One Piece Episode 614 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/09/one-piece-episode-614-subtitle-indonesia.html');
		$array_result[] = array('title' => 'Mushibugyou Episode 24 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/09/mushibugyou-episode-24-subtitle.html');
		$array_result[] = array('title' => 'Servant x Service Episode 13 Subtitle Indonesia [Final]', 'link' => 'http://www.alibabasub.net/2013/09/servant-x-service-episode-13-subtitle.html');
		$array_result[] = array('title' => 'Gin no Saji Episode 11 Subtitle Indonesia [Final]', 'link' => 'http://www.alibabasub.net/2013/09/gin-no-saji-episode-11-subtitle.html');
		/*	*/
		
		if (isset($this->is_index) && $this->is_index) {
			// remove start offset
			$offset = "<div class='post hentry'>";
			$pos_first = strpos($content, $offset);
			$content = substr($content, $pos_first, strlen($content) - $pos_first);
			
			// remove end offset
			$offset = "<div class='blog-pager' id='blog-pager'>";
			$pos_end = strpos($content, $offset);
			$content = substr($content, 0, $pos_end);
			
			preg_match_all('/h2 (class)=\'[^\']+\'>\s*<a href=\'([^\']+)\'>([^\<]+)<\/a>/i', $content, $match);
			foreach ($match[0] as $key => $value) {
				$link = trim($match[2][$key]);
				$title = trim($match[3][$key]);
				$array_result[] = array('title' => $title, 'link' => $link);
			}
		} else {
			$array_content = new SimpleXmlElement($content);
			foreach ($array_content->channel->item as $array_temp) {
				$array_temp = (array)$array_temp;
				unset($array_temp['category']);
				unset($array_temp['description']);
				
				$array_result[] = (array)$array_temp;
			}
		}
		
		return $array_result;
	}
	
	function get_desc($content) {
		// remove start offset
		$offset = "<h1 class='post-title entry-title'>";
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = "<div id='fb-root'></div>";
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// make it clean
		$temp = trim(strip_tags($content));
		$temp = preg_replace("/\n+/i", "\n", $temp);
		$array_temp = explode("\n", $temp);
		$result = implode("<br />", $array_temp);
		
		// endfix
		if (!empty($result)) {
			$result .= '<br /><div>Sumber : Alibaba</div>';
		}
		
		return $result;
	}
	
	function get_download($content) {
		// remove start offset
		$offset = "<h1 class='post-title entry-title'>";
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = "<div id='fb-root'></div>";
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// make it consistent
		$content = preg_replace('/&nbsp;/i', ' ', $content);
		$content = preg_replace('/<strike>[a-z0-9 ]+<\/strike>\s*\|/i', '', $content);
		$content = preg_replace('/\|\s*<strike>[a-z0-9 ]+<\/strike>/i', '', $content);
		$content = preg_replace('/<\/?strong>/i', '', $content);
		$content = preg_replace('/<\/a>\s?\/\s?<a /i', '</a> / <a ', $content);
		$content = preg_replace('/(480|720)p?\s*[=:]\s*/i', "$1 ", $content);
		$content = preg_replace('/ (style|title|class|rel|target)="[a-z0-9 \-\_\:]*"/i', '', $content);
		$content = preg_replace('/<div>([a-z0-9 ]+[ \|]+)*<a/i', '<div><a', $content);
		$content = preg_replace('/<del>[a-z0-9 ]+<\/del>(\s*\|\s*)*/i', '', $content);
		$content = preg_replace('/(\s*\|\s*)*<del>[a-z0-9 ]+<\/del>/i', '', $content);
		$content = preg_replace('/\s*\|\s*[a-z0-9 ]+\s*\|\s*/i', ' | ', $content);
		
		$result = '';
		preg_match_all('/(480|720) (<a href=\"([^\"]+)\" *>([a-z0-9 ]+)<\/a>( *[\/\|] *)*)*/i', $content, $match);
		if (is_array($match[0]) && count($match[0]) > 0) {
			foreach ($match[0] as $key => $raw_value) {
				$label = $match[1][$key];
				preg_match_all('/<a href=\"([^\"]+)\" *>([a-z0-9 ]+)<\/a>/i', $raw_value, $raw_link);
				
				if (is_array($raw_link[0]) && count($raw_link[0]) > 0) {
					$result .= (empty($result)) ? $label."\n" : "\n".$label."\n";
					foreach ($raw_link[0] as $key => $raw) {
						$link = $raw_link[1][$key];
						$title = $raw_link[2][$key];
						$result .= $link.' '.$title."\n";
					}
				}
			}
		}
		
		// new design on 2013-09-09
		if (empty($result)) {
			preg_match_all('/div>\s*([^\<]+)<\/div>\s*<div>([\s\|]*<a href="[^\"]+\">[a-z0-9\/\[\] ]+<\/a>)*<\/d/i', $content, $match);
			foreach ($match[0] as $key => $value) {
				preg_match_all('/<a href="([^\"]+)\">([a-z0-9\/\[\] ]+)<\/a>/i', $value, $array_link);
				
				$label = $match[1][$key];
				$result .= (empty($result)) ? $label : "\n\n".$label;
				foreach ($array_link[0] as $key => $raw_link) {
					$link_address = $array_link[1][$key];
					$link_title = $array_link[2][$key];
					
					$result .= "\n".$link_address.' '.$link_title;
				}
			}
		}
		
		// last option from link
		if (empty($result)) {
			preg_match_all('/<a href=\"([^\"]+)\" *>([^\<]+)<\/a>/i', $content, $match);
			foreach ($match[0] as $key => $value) {
				$link = $match[1][$key];
				$title = $match[2][$key];
				
				// check link
				$array_link = parse_url($link);
				if ($array_link['host'] == 'www.alibabasub.net') {
					continue;
				}
				
				$result .= (empty($result)) ? $result : "\n";
				$result .= $link.' '.$title;
			}
		}
		
		// trim it
		$result = trim($result);
		
		return $result;
	}
	
	function get_image($content) {
		// remove start offset
		$offset = "<h1 class='post-title entry-title'>";
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = "<div id='fb-root'></div>";
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// make it consistent
		$content = preg_replace('/ (class|style|border|height|width)=[\'\"][^\"|^\']+[\'\"]/i', '', $content);
		
		preg_match('/img src="([^\"]+)\"/i', $content, $match);
		$result = (isset($match[1]) && !empty($match[1])) ? $match[1] : '';
		
		return $result;
	}
}
