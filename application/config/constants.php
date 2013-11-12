<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('SHA_SECRET',							'OraNgerti');

define('BRAND',									'brand');
define('CONFIGURATION',							'configuration');
define('CATEGORY',								'category');
define('CATEGORY_SUB',							'category_sub');
define('ITEM',									'item');
define('PAGE_STATIC',							'page_static');
define('SCRAPE',								'scrape');
define('SCRAPE_PAGE',							'scrape_page');
define('SUBSCRIBE',								'subscribe');
define('TAG',									'tag');
define('USER',									'user');
define('USER_TYPE',								'user_type');