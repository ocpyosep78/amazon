<?php

class unduhfilm21 {
    function __construct() {
        $this->CI =& get_instance();
		$this->is_index = true;
    }
    
	function get_array($scrape) {
		$curl = new curl();
		$array_item = array();
		$content = $curl->get($scrape['link']);
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
			$content_item = $curl->get($array['link']);
			$content_item = preg_replace('/[^\x20-\x7E|\x0A]/i', '', $content_item);
			
			
			// skip for streaming
			$array['category'] = (isset($array['category']) && is_string($array['category'])) ? trim($array['category']) : '';
			if (strtoupper($array['category']) == 'STREAMING') {
				continue;
			}
			
			// collect data
			$desc = $this->get_desc($content_item);
			$download = $this->get_download($content_item);
			
			// set to array
			$temp = array();
			$temp['name'] = $array['title'];
			$temp['desc'] = $desc;
			$temp['download'] = $download;
			$temp['link_source'] = $array['link'];
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
		
		/*	
		// add link here
		$array_result[] = array('title' => 'Gatchaman Crowds Episode 10 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/09/gatcha10.html');
		$array_result[] = array('title' => 'Fate/Kaleid Liner PrismaIlya Episode 10 Subtitle Indonesia(FINAL)', 'link' => 'http://www.alibabasub.net/2013/09/fatekaleid-liner-prisma%e2%98%86ilya-episode-10-subtitle-indonesiafinal.html');
		$array_result[] = array('title' => 'Jigoku Sensei Nube Episode 4 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/08/jigoku-sensei-nube-episode-4-subtitle-indonesia.html');
		$array_result[] = array('title' => 'Jigoku Sensei Nube Episode 3 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/08/jigoku-sensei-nube-episode-3-subtitle-indonesia.html');
		/*	*/
		
		if (isset($this->is_index) && $this->is_index) {
			// remove start offset
			$offset = "<div class='blog-posts hfeed'>";
			$pos_first = strpos($content, $offset);
			$content = substr($content, $pos_first, strlen($content) - $pos_first);
			
			// remove end offset
			$offset = "<div class='blog-pager' id='blog-pager'>";
			$pos_end = strpos($content, $offset);
			$content = substr($content, 0, $pos_end);
			
			preg_match_all('/<h3 class=\'post-title entry-title\'>\s*<a href=\'([^\']+)\'>([^\<]+)<\/a>/i', $content, $match);
			foreach ($match[0] as $key => $value) {
				$link = trim($match[1][$key]);
				$title = trim($match[2][$key]);
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
	
	function get_desc($content, $param = array()) {
		$result = '';
		
		// remove start offset
		$offset = "<h3 class='post-title entry-title'>";
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = "<div class='addthis_toolbox addthis_default_style '>";
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// fix content
		$content = str_replace('&nbsp;', ' ', $content);
		$content = trim(strip_tags($content));
		$content = preg_replace("/[\n]+/i", "\n", $content);
		
		// get result
		$result = nl2br($content);
		
		// endfix
		$result .= '<div></div>';
		$result .= '<div>Sumber : Unduh Film 21</div>';
		
		return $result;
	}
	
	function get_download($content) {
		$result = '';
		
		// remove start offset
		$offset = "<h3 class='post-title entry-title'>";
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = "<div class='addthis_toolbox addthis_default_style '>";
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// clean content
		$content = preg_replace('/(rel|style|target)\=\"[^\"]+\"/i', '', $content);
		$content = preg_replace('/ +\>/i', '>', $content);
		$content = preg_replace('/\<\/?(b|i|span)\>/i', '', $content);
		
		preg_match_all('/([a-z0-9 ]+)<\/div>\s*<pre>(<a href\=\"([^\"]+)\"\>\[?([^\<]+)\]?\<\/a\>\s*)*/i', $content, $match);
		foreach ($match[0] as $key => $value) {
			$label = $match[1][$key];
			
			$result .= $label."\n";
			preg_match_all('/<a href\=\"([^\"]+)\"\>\[?([^\<]+)\]?\<\/a\>/i', $value, $array_link);
			foreach ($array_link[0] as $key => $value) {
				$link_href = $array_link[1][$key];
				$link_label = $array_link[2][$key];
				
				if ($link_href == $link_label) {
					$result .= $link_href."\n";
				} else {
					$result .= $link_href.' '.$link_label."\n";
				}
			}
			$result .= "\n";
		}
		
		// last option
		if (empty($result)) {
			preg_match_all('/\<a href\=\"([^\"]+)\"\>\[?([^\<]+)\]?\<\/a\>/i', $content, $match);
			if (isset($match[1])) {
				foreach ($match[1] as $key => $value) {
					$link = $match[1][$key];
					$label = $match[2][$key];
					
					// imdb checker
					preg_match('/imdb/i', $link, $imdb_check);
					if (count($imdb_check) > 0) {
						continue;
					}
					
					if ($link == $label) {
						$result .= $link."\n";
					} else {
						$result .= $link.' '.$label."\n";
					}
				}
			}
		}
		
		// trim it
		$result = trim($result);
		
		return $result;
	}
}
