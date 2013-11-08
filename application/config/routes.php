<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
$is_website = true;
$is_other_page = false;

$url_arg = preg_replace('/(^\/|\/$)/i', '', $_SERVER['REDIRECT_QUERY_STRING']);
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
	$route['(anime|film|tv-serial|cartoon)'] = "website/category";
	$route['(anime|film|tv-serial|cartoon)/(:any)'] = "website/category";
	$route['(:num)/(:num)/(:any)'] = "website/detail";
	$route['tag'] = "website/tag";
	$route['tag/(:any)'] = "website/tag";
	$route['ajax'] = "website/ajax";
	$route['ajax/(:any)'] = "website/ajax";
	$route['widget/(:any)'] = "website/widget";
	$route['logout'] = "website/logout";
	
	// list post
	$route['rss'] = "website/rss";
	$route['rss/(:any)'] = "website/rss";
	$route['search'] = "website/search";
	$route['search/(:any)'] = "website/search";
	$route['sitemap'] = "website/sitemap";
	$route['sitemap/(:any)'] = "website/sitemap";
	
	// form
	$route['submit'] = "website/submit";
	$route['submit/(:any)'] = "website/submit";
	$route['request'] = "website/request";
	$route['request/(:any)'] = "website/request";
	$route['contact'] = "website/contact";
	$route['contact/(:any)'] = "website/contact";
	
	// comment
	$route['comment'] = "comment/comment";
}

if ($is_other_page) {
	$route['(:any)'] = "website/other";
}

$route['panel'] = "panel/home";

$route['default_controller'] = "website/home";
$route['404_override'] = '';