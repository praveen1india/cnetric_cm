CREATE TABLE IF NOT EXISTS `#__spr_prodtypes` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `protected` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

REPLACE INTO `#__spr_prodtypes` (`id`, `name`, `params`, `protected`) VALUES
(1, 'Standard', '{"name":"Standard","del":"1","dl":"2","tc":"0"}', 1),
(2, 'Virtual', '{"name":"Virtual","del":"2","dl":"2","tc":"0"}', 1),
(3, 'Downloadable', '{"name":"Downloads","del":"2","dl":"1","tc":"0"}', 1);