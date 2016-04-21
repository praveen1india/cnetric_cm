CREATE TABLE IF NOT EXISTS `#__spr_item_dls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` smallint(6) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `filename` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `ext` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `times` smallint(6) NOT NULL,
  `days` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__spr_item_dls_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` mediumint(9) NOT NULL,
  `dl_id` mediumint(9) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `expiry` int(11) NOT NULL,
  `hash` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `dls` int(11) NOT NULL,
  `last_ip` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

ALTER TABLE `#__spr_sales` ADD `paymentfee` FLOAT NOT NULL AFTER `shipping` ;

ALTER TABLE `#__spr_sales` CHANGE `payment_option_id` `payment_id` SMALLINT( 6 ) NOT NULL ;

ALTER TABLE `#__spr_sales` ADD `shipping_id` SMALLINT NOT NULL AFTER `payment_details` ;

ALTER TABLE `#__spr_sales` ADD `tax_id` SMALLINT NOT NULL AFTER `shipping_details` ;

ALTER TABLE `#__spr_sales` ADD `discount_id` SMALLINT NOT NULL AFTER `tax_details` ;

ALTER TABLE `#__spr_sales_items` DROP `formatted_price`;

ALTER TABLE `#__spr_sales` ADD `user_email` VARCHAR(100) NOT NULL AFTER `discount_details` ;