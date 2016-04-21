UPDATE `#__extensions` SET `enabled` = '1' WHERE `name` = 'plg_salespro' LIMIT 1;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_attributes`
--

CREATE TABLE IF NOT EXISTS `#__spr_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_attributes_map`
--

CREATE TABLE IF NOT EXISTS `#__spr_attributes_map` (
  `attribute` smallint(6) NOT NULL,
  `category` smallint(6) NOT NULL,
  UNIQUE KEY `identifier` (`attribute`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_attributes_values`
--

CREATE TABLE IF NOT EXISTS `#__spr_attributes_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` smallint(6) NOT NULL,
  `value` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_id` (`attribute_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_cart_items` 
--

ALTER TABLE `#__spr_cart_items` CHANGE `options` `variant_id` SMALLINT NOT NULL;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_categories_map`
--

CREATE TABLE IF NOT EXISTS `#__spr_categories_map` (
  `item` smallint(6) NOT NULL,
  `category` smallint(6) NOT NULL,
  UNIQUE KEY `item` (`item`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_config`
--

CREATE TABLE IF NOT EXISTS `#__spr_config` (
  `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_items`
--

ALTER TABLE `#__spr_items` DROP `show_options`;
ALTER TABLE `#__spr_items` DROP `stock`;
ALTER TABLE `#__spr_items` DROP `stock_empty`;
ALTER TABLE `#__spr_items` DROP `stock_date`;
ALTER TABLE `#__spr_items` DROP `stock_end`;
ALTER TABLE `#__spr_items` DROP `start`;
ALTER TABLE `#__spr_items` DROP `end`;
ALTER TABLE `#__spr_items` DROP `sale_start`;
ALTER TABLE `#__spr_items` DROP `sale_end`;
ALTER TABLE `#__spr_items` DROP `show_attributes`;

ALTER TABLE `#__spr_items` CHANGE `stock_level` `stock` SMALLINT NOT NULL;

ALTER TABLE `#__spr_items` ADD `onsale` BOOLEAN NOT NULL AFTER `sale`;
ALTER TABLE `#__spr_items` ADD `category` INT NOT NULL AFTER `category_id`;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_item_attributes_map`
--

CREATE TABLE IF NOT EXISTS `#__spr_item_attributes_map` (
  `item` smallint(6) NOT NULL,
  `attribute` smallint(6) NOT NULL,
  UNIQUE KEY `item` (`item`,`attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_item_optiongroups`
--

DROP TABLE `#__spr_item_optiongroups`;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_item_options`
--

DROP TABLE `#__spr_item_options`;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_item_variants`
--

CREATE TABLE IF NOT EXISTS `#__spr_item_variants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(6) NOT NULL,
  `price` float NOT NULL,
  `sku` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(6) NOT NULL,
  `sale` float NOT NULL,
  `onsale` tinyint(1) NOT NULL DEFAULT '2',
  `stock` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_item_variants_map`
--

CREATE TABLE IF NOT EXISTS `#__spr_item_variants_map` (
  `variant_id` int(11) NOT NULL,
  `attribute` int(11) NOT NULL,
  `attribute_value` int(11) NOT NULL,
  UNIQUE KEY `item_id_2` (`variant_id`,`attribute`,`attribute_value`),
  KEY `item_id` (`variant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

INSERT INTO `#__spr_payment_options` (`id`, `payment_method`, `name`, `sort`, `status`, `params`, `fee`, `fee_type`, `info`) VALUES
('', 6, 'Free Checkout', 0, 1, '', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_prodtypes`
--

ALTER TABLE `#__spr_prodtypes` DROP `protected`;
REPLACE INTO `#__spr_prodtypes` (`id`, `name`, `params`) VALUES
(1, 'Standard', '{"name":"Standard","var":"2","del":"1","sm":"2","dl":"2","tc":"0","quantity":"1"}'),
(2, 'Virtual', '{"name":"Virtual","var":"2","del":"2","sm":"2","dl":"2","tc":"0","quantity":"2"}'),
(3, 'Downloadable', '{"name":"Downloadable","var":"2","del":"2","sm":"2","dl":"1","tc":"0","quantity":"2"}'),
(4, 'Advanced', '{"name":"Advanced","var":"1","del":"1","sm":"1","dl":"2","tc":"0","quantity":"1"}');

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_sales`
--

ALTER TABLE `#__spr_sales` DROP `discount`;
ALTER TABLE `#__spr_sales` DROP `tax`;
ALTER TABLE `#__spr_sales` DROP `shipping`;
ALTER TABLE `#__spr_sales` DROP `paymentfee`;
ALTER TABLE `#__spr_sales` DROP `currency_id`;
ALTER TABLE `#__spr_sales` DROP `payment_id`;
ALTER TABLE `#__spr_sales` DROP `shipping_id`;
ALTER TABLE `#__spr_sales` DROP `tax_id`;
ALTER TABLE `#__spr_sales` DROP `discount_id`;
ALTER TABLE `#__spr_sales` DROP `discount_details`;

ALTER TABLE `#__spr_sales` CHANGE `gross_price` `f_price` VARCHAR(20) NOT NULL;
ALTER TABLE `#__spr_sales` CHANGE `grand_total` `f_grandtotal` VARCHAR(20) NOT NULL;

ALTER TABLE `#__spr_sales` ADD `grandtotal` FLOAT NOT NULL AFTER `f_price`;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_sales_items`
--

ALTER TABLE `#__spr_sales_items` DROP `options`;
ALTER TABLE `#__spr_sales_items` DROP `option_details`;
ALTER TABLE `#__spr_sales_items` DROP `type`;
ALTER TABLE `#__spr_sales_items` DROP `color`;
ALTER TABLE `#__spr_sales_items` ADD `variant_id` SMALLINT NOT NULL AFTER `item_id`;
ALTER TABLE `#__spr_sales_items` CHANGE `price` `f_price` VARCHAR(20) NOT NULL;
ALTER TABLE `#__spr_sales_items` CHANGE `category_id` `category` INT(6) NOT NULL;
ALTER TABLE `#__spr_sales_items` ADD `price` FLOAT NOT NULL AFTER `f_price`;
ALTER TABLE `#__spr_sales_items` ADD `onsale` BOOLEAN NOT NULL AFTER `price`;
ALTER TABLE `#__spr_sales_items` ADD `attributes` TEXT NOT NULL AFTER `tax_details`;
ALTER TABLE `#__spr_sales_items` ADD `params` TEXT NOT NULL AFTER `attributes`;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_widgets`
--

ALTER TABLE `#__spr_widgets` DROP `about`;
ALTER TABLE `#__spr_widgets` CHANGE `alias` `type` VARCHAR(255) NOT NULL;



-- --------------------------------------------------------

--
-- Table structure for table `#__spr_widget_types`
--

CREATE TABLE IF NOT EXISTS `#__spr_widget_types` (
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `about` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

INSERT INTO `#__spr_widget_types` (`type`, `about`) VALUES
('categories', 'This widget displays all the available categories'),
('featured', 'This widget displays featured items'),
('new', 'This widget displays items sorted by newest first'),
('random', 'This widget shows random products from all your categories'),
('showcase', 'This widget displays featured items in a showcase box');