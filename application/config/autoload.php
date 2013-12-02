<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$autoload['packages'] = array();
$autoload['libraries'] = array('database', 'session');
$autoload['helper'] = array( 'date', 'common', 'url', 'mcrypt' );
$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array(
	'User_model', 'User_Type_model', 'Category_model', 'Category_Sub_model', 'Page_Static_model', 'Subscribe_model', 'Configuration_model',
	'Scrape_model', 'Brand_model', 'Item_model', 'Scrape_Page_model', 'Item_Status_model', 'Item_Multi_Title_model', 'Tag_model', 'Item_Tag_model',
	'Item_Review_model', 'Broken_Link_model', 'Mail_Mass_model', 'Item_Compare_model', 'Scrape_Item_model'
);
