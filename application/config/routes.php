<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
$is_website = true;
$is_other_page = false;

$string_link_check = (isset($_SERVER['argv']) && isset($_SERVER['argv'][0])) ? $_SERVER['argv'][0] : '';
$string_link_check = (empty($string_link_check) && isset($_SERVER['REDIRECT_QUERY_STRING'])) ? $_SERVER['REDIRECT_QUERY_STRING'] : $string_link_check;
$url_arg = preg_replace('/(^\/|\/$)/i', '', $string_link_check);
$array_arg = explode('/', $url_arg);

if (count($array_arg) >= 1) {
	$key = $array_arg[0];
	if (in_array($key, array( 'panel' ))) {
		$is_website = false;
	} else if (in_array($key, array( 'full-page', 'about-us', 'privacy-policy', 'advertising', 'referer', 'widget' ))) {
		$is_other_page = true;
	}
}

if ($is_website) {
	// common page
	$route['tag'] = "website/tag";
	$route['tag/(:any)'] = "website/tag";
	$route['ajax'] = "website/ajax";
	$route['ajax/(:any)'] = "website/ajax";
	$route['logout'] = "website/logout";
	
	// list post
	$route['rss'] = "website/rss";
	$route['rss/(:any)'] = "website/rss";
	$route['search'] = "website/home";
	$route['search/(:any)'] = "website/home";
	$route['brand'] = "website/home";
	$route['brand/(:any)'] = "website/home";
	
	// form
	$route['contact/(:any)'] = "website/contact";
	
	// last option
	$route['(:any)/(:any)/(:any)'] = "website/common";
}

if ($is_other_page) {
	$route['(:any)'] = "website/other";
}

$route['panel'] = "panel/home";

$route['default_controller'] = "website/home";
$route['404_override'] = '';