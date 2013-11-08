<?php

class sedot_mini {
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
			$title = trim($array['title']);
			$desc = $this->get_desc($content_html);
			$image = $this->get_image($content_html);
			$download = $this->get_download($content_html);
			
			// set to array
			$temp = array();
			$temp['name'] = $title;
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
		$array_result[] = array('title' => '[UTW] Fate Kaleid Liner Prisma Ilya  10', 'link' => 'http://www.sedotmini.com/utw-fate-kaleid-liner-prisma-ilya-10/');
		$array_result[] = array('title' => '[Doki] Chibi Devi  58', 'link' => 'http://www.sedotmini.com/doki-chibi-devi-58/');
		$array_result[] = array('title' => '[Tsuki] Hunter X Hunter  93', 'link' => 'http://www.sedotmini.com/tsuki-hunter-x-hunter-93/');
		$array_result[] = array('title' => '[HorribleSubs] Makai Ouji  Devils and Realist  11', 'link' => 'http://www.sedotmini.com/horriblesubs-makai-ouji-devils-and-realist-11/');
		$array_result[] = array('title' => '[HorribleSubs] Fantasista Doll  10', 'link' => 'http://www.sedotmini.com/horriblesubs-fantasista-doll-10/');
		$array_result[] = array('title' => '[Doki] Chibi Devi  59', 'link' => 'http://www.sedotmini.com/doki-chibi-devi-59/');
		$array_result[] = array('title' => '[Commie] Dokidoki! Precure  32', 'link' => 'http://www.sedotmini.com/commie-dokidoki-precure-32/');
		$array_result[] = array('title' => '[Vivid] Uchouten Kazoku  11', 'link' => 'http://www.sedotmini.com/vivid-uchouten-kazoku-11/');
		$array_result[] = array('title' => '[Doki] Papa no Iukoto wo Kikinasai!  OVA', 'link' => 'http://www.sedotmini.com/doki-papa-no-iukoto-wo-kikinasai-ova/');
		$array_result[] = array('title' => '[Anime-Koi] Genshiken Nidaime  10', 'link' => 'http://www.sedotmini.com/anime-koi-genshiken-nidaime-10/');
		$array_result[] = array('title' => '[Anime-Koi] Genshiken Nidaime  09', 'link' => 'http://www.sedotmini.com/anime-koi-genshiken-nidaime-09/');
		$array_result[] = array('title' => '[Anime-Koi] Genshiken Nidaime  08', 'link' => 'http://www.sedotmini.com/anime-koi-genshiken-nidaime-08/');
		$array_result[] = array('title' => '[A-Destiny] Toriko  121', 'link' => 'http://www.sedotmini.com/a-destiny-toriko-121/');
		$array_result[] = array('title' => '[Taka] Naruto Shippuuden 327-328', 'link' => 'http://www.sedotmini.com/taka-naruto-shippuuden-327-328/');
		$array_result[] = array('title' => '[HorribleSubs] Inu to Hasami wa Tsukaiyou  12', 'link' => 'http://www.sedotmini.com/horriblesubs-inu-to-hasami-wa-tsukaiyou-12/');
		$array_result[] = array('title' => '[Anime-Koi] Hakkenden Touhou Hakken Ibun  24', 'link' => 'http://www.sedotmini.com/anime-koi-hakkenden-touhou-hakken-ibun-24/');
		$array_result[] = array('title' => '[EveSenshi] Rozen Maiden Zuruckspulen  11', 'link' => 'http://www.sedotmini.com/evesenshi-rozen-maiden-zuru%cc%88ckspulen-11/');
		$array_result[] = array('title' => '[yibis] One Piece 611', 'link' => 'http://www.sedotmini.com/yibis-one-piece-611/');
		$array_result[] = array('title' => '[Doki] Chibi Devi  60', 'link' => 'http://www.sedotmini.com/doki-chibi-devi-60/');
		$array_result[] = array('title' => '[Doki] Kimi no Iru Machi  10', 'link' => 'http://www.sedotmini.com/doki-kimi-no-iru-machi-10/');
		$array_result[] = array('title' => '[Commie] Free!  11', 'link' => 'http://www.sedotmini.com/commie-free-11/');
		/*	*/
		
		foreach ($array_content->channel->item as $array_temp) {
			$array_temp = (array)$array_temp;
			unset($array_temp['category']);
			unset($array_temp['description']);
			unset($array_temp['comments']);
			unset($array_temp['pubDate']);
			unset($array_temp['guid']);
			
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
		$offset = '<div class="entry">';
		$pos_first = strpos($content_html, $offset);
		$content_html = substr($content_html, $pos_first, strlen($content_html) - $pos_first);
		
		// remove end offset
		$offset = '<p class="postmetadata">';
		$pos_end = strpos($content_html, $offset);
		$content_html = substr($content_html, 0, $pos_end);
		
		return $content_html;
	}
	
	function get_desc($content) {
		// remove start offset
		$offset = '</div';
		$pos_first = strpos($content, $offset);
		$content = substr($content, $pos_first, strlen($content) - $pos_first);
		
		// remove end offset
		$content = preg_replace('/Preview Sedot[\x20-\x7E|\x0A]+/i', '', $content);
		$content = strip_tags($content);
		$content = str_replace(array( '&#8211;', '&#8220;' , '&#8221;', '&#8217;', '&#8216;', '&nbsp;' ), array( '-', '"', '"', "'", "'", ' ' ), $content);
		$content = trim(preg_replace('/[\n]+/i', "\n", $content));
		
		$result = '';
		$array_temp = explode("\n", $content);
		foreach ($array_temp as $line) {
			$line = (empty($line)) ? '&nbsp;' : $line;
			$result .= '<div>'.trim($line).'</div>';
		}
		
		// endfix
		$result .= '<div></div>';
		$result .= '<div>Sumber : Sedot Mini</div>';
		
		return $result;
	}
	
	function get_image($content) {
		$content = preg_replace('/(border|height|width|class|alt)\=\"[\d\w\ \-\[\]]+\"/i', ' ', $content);
		$content = preg_replace('/[ ]+/i', ' ', $content);
		preg_match('/\<img src\=\"([^\"]+)\"/i', $content, $match);
		
		$image = (isset($match[1]) && !empty($match[1])) ? $match[1] : '';
		
		return $image;
	}
	
	function get_download($content) {
		$result = '';
		
		// clean content
		$content = preg_replace('/ (style|target|rel|class|title)\=\"[^\"]*\"/i', '', $content);
		$content = preg_replace('/ +\>/i', '>', $content);
		$content = preg_replace('/\<\/?b\>/i', '', $content);
		preg_match_all('/([a-z0-9 ]+)<\/strong\>\<\/h3\>\n\<p\>(\<a href\=\"[^\"]+\"\>[\w\d ]+\<\/a\>(\<br \/\>\n)*)*/i', $content, $match);
		if (isset($match[0])) {
			foreach ($match[0] as $raw) {
				// get title
				preg_match('/^([a-z0-9 ]+)/i', $raw, $match_title);
				$title = (isset($match_title[1])) ? $match_title[1] : '';
				
				// get link
				$array_link = array();
				preg_match_all('/href=\"([^\"]+)\"\>([0-9a-z ]+)/i', $raw, $match_link);
				if (isset($match_link[2])) {
					foreach ($match_link[2] as $key => $temp) {
						$array_link[] = $match_link[1][$key].' '.$match_link[2][$key];
					}
				}
				
				if (count($array_link) > 0) {
					if (!empty($result)) {
						$result .= "\n";
					}
					
					$result .= $title."\n";
					foreach ($array_link as $link) {
						$result .= $link."\n";
					}
				}
			}
		}
		
		// trim it
		$result = trim($result);
		
		return $result;
	}
}
