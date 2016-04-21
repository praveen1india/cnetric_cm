ALTER TABLE `#__spr_items` ADD `added` INT NOT NULL ;

-- --------------------------------------------------------

ALTER TABLE `#__spr_payment_methods` ADD `about` TEXT NOT NULL ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__spr_widgets` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `views` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `about` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

REPLACE INTO `#__spr_widgets` (`id`, `name`, `alias`, `params`, `views`, `sort`, `status`, `about`) VALUES
(1, 'Showcase items', 'showcase', '{"showtitle":"1","btn":"1","count":"0"}', '{"home":"1"}', 0, 1, 'This widget displays featured items in a showcase box'),
(2, 'Featured items', 'featured', '{"showtitle":"1","btn":"1","cols":"3","count":"5"}', '{"home":"1"}', 2, 1, 'This widget displays featured items'),
(3, 'New items', 'new', '{"showtitle":"1","btn":"1","cols":"4","count":"0"}', '{"home":"1"}', 3, 1, 'This widget displays items sorted by newest first'),
(4, 'All categories', 'categories', '{"showtitle":"1","btn":"1","cols":"4","count":"4"}', '{"home":"1"}', 1, 1, 'This widget displays all the available categories');

-- --------------------------------------------------------