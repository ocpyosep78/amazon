2013-11-19 :
- ALTER TABLE  `item` ADD  `total_view` INT NOT NULL, ADD  `total_link_out` INT NOT NULL;
- ALTER TABLE  `item` ADD  `link_replace` VARCHAR( 255 ) NOT NULL AFTER  `link_source`
- ALTER TABLE  `item` CHANGE  `date_update`  `date_update` DATETIME NOT NULL;
- CREATE TABLE IF NOT EXISTS `broken_link` ( `id` int(11) NOT NULL AUTO_INCREMENT, `link` varchar(255) NOT NULL, `is_repair` int(11) NOT NULL, `date_report` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

2013-11-20 :
- CREATE TABLE  `amazon_db`.`item_compare` ( `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY , `name` VARCHAR( 255 ) NOT NULL , `desc` LONGTEXT NOT NULL , `price` INT NOT NULL , `url` VARCHAR( 255 ) NOT NULL ) ENGINE = MYISAM ;
- ALTER TABLE  `item_compare` ADD  `item_id` INT NOT NULL AFTER  `id`

2013-11-27 :
- ALTER TABLE `category` ADD `image` VARCHAR( 50 ) NOT NULL ;
- ALTER TABLE `item` ADD `show_desc` INT NOT NULL AFTER `desc` ;
- ALTER TABLE `item` CHANGE `show_desc` `show_desc` INT( 11 ) NOT NULL DEFAULT '1';
- ALTER TABLE `item` CHANGE `show_desc` `desc_show` INT( 11 ) NOT NULL DEFAULT '1';
- ALTER TABLE `item_multi_title` ADD `desc_short` LONGTEXT NOT NULL , ADD `desc_long_1` LONGTEXT NOT NULL , ADD `desc_long_2` LONGTEXT NOT NULL , ADD `link_aff` VARCHAR( 255 ) NOT NULL , ADD `sign` VARCHAR( 255 ) NOT NULL ;
- CREATE TABLE IF NOT EXISTS `scrape_item` ( `id` int(11) NOT NULL AUTO_INCREMENT, `item_id` int(11) NOT NULL, `is_finish` int(11) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

2013-12-09 :
- ALTER TABLE `item` ADD `rating` FLOAT NOT NULL AFTER `store`;
- CREATE TABLE IF NOT EXISTS `item_additional` ( `id` int(11) NOT NULL AUTO_INCREMENT, `item_id` int(11) NOT NULL, `desc_short` longtext NOT NULL, `desc_long_1` longtext NOT NULL, `desc_long_2` longtext NOT NULL, `link_aff` varchar(255) NOT NULL, `sign` varchar(255) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

2013-12-15 :
- ALTER TABLE `category_sub` ADD `force_link` VARCHAR( 255 ) NOT NULL AFTER `tag` ;