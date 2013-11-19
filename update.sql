2013-11-19 :
- ALTER TABLE  `item` ADD  `total_view` INT NOT NULL, ADD  `total_link_out` INT NOT NULL;
- ALTER TABLE  `item` ADD  `link_replace` VARCHAR( 255 ) NOT NULL AFTER  `link_source`
- ALTER TABLE  `item` CHANGE  `date_update`  `date_update` DATETIME NOT NULL;
- CREATE TABLE IF NOT EXISTS `broken_link` ( `id` int(11) NOT NULL AUTO_INCREMENT, `link` varchar(255) NOT NULL, `is_repair` int(11) NOT NULL, `date_report` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', PRIMARY KEY (`id`) ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;