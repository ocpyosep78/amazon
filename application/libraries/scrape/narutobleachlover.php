<?php

class narutobleachlover {
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
			
			// collect data
			$desc = $this->get_desc($content_item);
			$download = $this->get_download($content_item);
			$image_source = $this->get_image($content_item);
			
			// set to array
			$temp = array();
			$temp['name'] = $array['title'];
			$temp['desc'] = $desc;
			$temp['download'] = $download;
			$temp['link_source'] = $array['link'];
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
		
		/*	
		// add link here
		$array_result[] = array('title' => 'Gatchaman Crowds Episode 10 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/09/gatcha10.html');
		$array_result[] = array('title' => 'Fate/Kaleid Liner PrismaIlya Episode 10 Subtitle Indonesia(FINAL)', 'link' => 'http://www.alibabasub.net/2013/09/fatekaleid-liner-prisma%e2%98%86ilya-episode-10-subtitle-indonesiafinal.html');
		$array_result[] = array('title' => 'Jigoku Sensei Nube Episode 4 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/08/jigoku-sensei-nube-episode-4-subtitle-indonesia.html');
		$array_result[] = array('title' => 'Jigoku Sensei Nube Episode 3 Subtitle Indonesia', 'link' => 'http://www.alibabasub.net/2013/08/jigoku-sensei-nube-episode-3-subtitle-indonesia.html');
		/*	*/
		
		if (isset($this->is_index) && $this->is_index) {
			// remove start offset
			$offset = '<div class="content">';
			$pos_first = strpos($content, $offset);
			$content = substr($content, $pos_first, strlen($content) - $pos_first);
			
			// remove end offset
			$offset = '<ul class="pager" role="navigation">';
			$pos_end = strpos($content, $offset);
			$content = substr($content, 0, $pos_end);
			
			preg_match_all('/<a href="([^\"]+)" title="[^\"]+" rel="bookmark">([^\<]+)</i', $content, $match);
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
		$offset = '<div class="entry-content">';
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = '<footer class="entry-footer">';
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// get result
		$result = nl2br(trim(strip_tags($content)));
		
		// endfix
		if (!empty($result)) {
			$result .= '<br /><br /><div>Sumber : Naruto Bleach Lover</div>';
		}
		
		return $result;
	}
	
	function get_download($content) {
		$result = '';
		
		// remove start offset
		$offset = '<div class="entry-content">';
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = '<footer class="entry-footer">';
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// clean content
		$content = preg_replace('/ (rel|style|target|title|data-[a-z\-]+)\=\"[^\"]+\"/i', '', $content);
		$content = preg_replace('/<\/?(span|strong)>/i', '', $content);
		$content = preg_replace('/\s*\|\s*/i', '|', $content);
		
		// option #1
		preg_match_all('/(SD|HD)[\s\=]*(\|*<a href="[^\"]+">[^\<]+<\/a>)*/i', $content, $match);
		if (count($match[0]) > 0) {
			foreach ($match[0] as $key => $value) {
				$label = trim($match[1][$key]);
				preg_match_all('/<a href="([^\"]+)">([^\<]+)<\/a>/i', $value, $raw_link);
				if (count($raw_link[0]) > 0) {
					$result .= (empty($result)) ? $label : "\n\n".$label;
					foreach ($raw_link[0] as $key => $temp) {
						$link = trim($raw_link[1][$key]);
						$title = trim($raw_link[2][$key]);
						$result .= "\n".$link.' '.$title;
					}
				}
			}
		}
		
		// option #2
		if (empty($result)) {
			preg_match_all('/\<a href\=\"([^\"]+)\"\>\[?([\w\s]+)\]?\<\/a\>/i', $content, $match);
			if (isset($match[1])) {
				foreach ($match[1] as $key => $value) {
					$link = $match[1][$key];
					$label = $match[2][$key];
					
					// check link
					$array_link = parse_url($link);
					if (isset($array_link['host']) && $array_link['host'] == 'www.narutobleachlover.net') {
						continue;
					}
					
					$result .= $link.' '.$label."\n";
				}
			}
		}
		
		// trim it
		$result = trim($result);
		
		return $result;
	}
	
	function get_image($content) {
		// remove start offset
		$offset = '<div class="entry-content">';
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$offset = '<footer class="entry-footer">';
		$pos_end = strpos($content, $offset);
		$content = substr($content, 0, $pos_end);
		
		// make it consistent
		$content = preg_replace('/ (alt|class|style|border|height|width)=[\'\"][^\"|^\']+[\'\"]/i', '', $content);
		
		preg_match('/img src="([^\"]+)\"/i', $content, $match);
		$result = (isset($match[1]) && !empty($match[1])) ? $match[1] : '';
		
		return $result;
	}
}
