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
define('USER_TYPE_ADMINISTRATOR',				1);
define('ITEM_STATUS_INCOMPLETE',				1);
define('ITEM_STATUS_REVIEW',					2);
define('ITEM_STATUS_APPROVE',					3);
define('ITEM_STATUS_UNPUBLISH',					4);

define('BRAND',									'brand');
define('BROKEN_LINK',							'broken_link');
define('CONFIGURATION',							'configuration');
define('CATEGORY',								'category');
define('CATEGORY_SUB',							'category_sub');
define('ITEM',									'item');
define('ITEM_COMPARE',							'item_compare');
define('ITEM_MULTI_TITLE',						'item_multi_title');
define('ITEM_REVIEW',							'item_review');
define('ITEM_STATUS',							'item_status');
define('ITEM_TAG',								'item_tag');
define('MAIL_MASS',								'mail_mass');
define('PAGE_STATIC',							'page_static');
define('SCRAPE',								'scrape');
define('SCRAPE_ITEM',							'scrape_item');
define('SCRAPE_PAGE',							'scrape_page');
define('SUBSCRIBE',								'subscribe');
define('TAG',									'tag');
define('USER',									'user');
define('USER_TYPE',								'user_type');