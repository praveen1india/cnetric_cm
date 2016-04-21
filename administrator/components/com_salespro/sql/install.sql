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
-- Table structure for table `#__spr_carts`
--

CREATE TABLE IF NOT EXISTS `#__spr_carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_cart_items`
--

CREATE TABLE IF NOT EXISTS `#__spr_cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `hash` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` smallint(6) NOT NULL,
  `variant_id` smallint(6) NOT NULL,
  `quantity` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_categories`
--

CREATE TABLE IF NOT EXISTS `#__spr_categories` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `parent` smallint(6) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `meta_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keys` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_categories_map`
--

CREATE TABLE IF NOT EXISTS `#__spr_categories_map` (
  `item` smallint(6) NOT NULL,
  `category` smallint(6) NOT NULL,
  UNIQUE KEY `item` (`item`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
-- Table structure for table `#__spr_cookies`
--

CREATE TABLE IF NOT EXISTS `#__spr_cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_cookie_vars`
--

CREATE TABLE IF NOT EXISTS `#__spr_cookie_vars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cookie` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cookie` (`cookie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_currencies`
--

CREATE TABLE IF NOT EXISTS `#__spr_currencies` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `symbol` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `default` tinyint(4) NOT NULL,
  `xe` float NOT NULL,
  `decimals` tinyint(4) NOT NULL DEFAULT '2',
  `thousands` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT ',',
  `separator` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '.',
  `checked` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_emails`
--

CREATE TABLE IF NOT EXISTS `#__spr_emails` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL,
  `trigger` tinyint(4) NOT NULL,
  `prodtypes` text COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `copy` tinyint(4) NOT NULL,
  `from` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`trigger`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_items`
--

CREATE TABLE IF NOT EXISTS `#__spr_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `sort` smallint(6) NOT NULL,
  `category` smallint(6) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tagline` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `featured` tinyint(1) NOT NULL,
  `taxes` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `type` tinyint(4) NOT NULL,
  `sku` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `weight` float NOT NULL,
  `stock` smallint(6) NOT NULL,
  `sale` float NOT NULL,
  `onsale` tinyint(1) NOT NULL,
  `mini_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `full_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `height` float NOT NULL,
  `width` float NOT NULL,
  `depth` float NOT NULL,
  `manufacturer` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `origin` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `specification` text COLLATE utf8_unicode_ci NOT NULL,
  `tab1_active` tinyint(4) NOT NULL,
  `tab1_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tab2_active` tinyint(4) NOT NULL,
  `tab2_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tab3_active` tinyint(4) NOT NULL,
  `tab3_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tab4_active` tinyint(4) NOT NULL,
  `tab4_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tab5_active` tinyint(4) NOT NULL,
  `tab5_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keys` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `added` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
-- Table structure for table `#__spr_item_dls`
--

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

--
-- Table structure for table `#__spr_item_dls_links`
--

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_item_faqs`
--

CREATE TABLE IF NOT EXISTS `#__spr_item_faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` smallint(6) NOT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_item_images`
--

CREATE TABLE IF NOT EXISTS `#__spr_item_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` smallint(6) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ext` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `desc` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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

--
-- Table structure for table `#__spr_item_videos`
--

CREATE TABLE IF NOT EXISTS `#__spr_item_videos` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `item_id` smallint(6) NOT NULL,
  `hash` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `height` smallint(6) NOT NULL,
  `width` smallint(6) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_log`
--

CREATE TABLE IF NOT EXISTS `#__spr_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `page` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`seen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_payment_methods`
--

CREATE TABLE IF NOT EXISTS `#__spr_payment_methods` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `about` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_payment_options`
--

CREATE TABLE IF NOT EXISTS `#__spr_payment_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method` smallint(6) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` smallint(11) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `fee` float NOT NULL,
  `fee_type` tinyint(4) NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_paypal_txn`
--

CREATE TABLE IF NOT EXISTS `#__spr_paypal_txn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address_city` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `address_country` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `address_country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `address_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `address_state` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `address_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `address_street` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `address_zip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `btn_id` int(11) NOT NULL,
  `business` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `charset` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `contact_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `custom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `discount` float(10,2) NOT NULL,
  `exchange_rate` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `handling_amount` float(10,2) NOT NULL,
  `invoice` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `insurance_amount` float(10,2) NOT NULL,
  `ipn_track_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `item_name` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `item_number` smallint(6) NOT NULL,
  `last_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mc_currency` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `mc_fee` float(10,2) NOT NULL,
  `mc_gross` float(10,2) NOT NULL,
  `mc_handling` float(10,2) NOT NULL,
  `mc_shipping` float(10,2) NOT NULL,
  `memo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notify_version` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `num_cart_items` smallint(6) NOT NULL,
  `parent_txn_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payer_business_name` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `payer_email` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `payer_id` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `payer_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payment_date` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `payment_status` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pending_reason` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `protection_eligibility` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` smallint(6) NOT NULL,
  `reason_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `receiver_email` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `receiver_id` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `receipt_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `resend` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `residence_country` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `shipping` float(10,2) unsigned NOT NULL,
  `shipping_discount` float(10,2) NOT NULL,
  `shipping_method` varchar(127) COLLATE utf8_unicode_ci NOT NULL,
  `tax` float(10,2) NOT NULL,
  `test_ipn` tinyint(1) NOT NULL,
  `transaction_entity` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `transaction_subject` int(11) NOT NULL,
  `txn_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `txn_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `verify_sign` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_prodtypes`
--

CREATE TABLE IF NOT EXISTS `#__spr_prodtypes` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_regions`
--

CREATE TABLE IF NOT EXISTS `#__spr_regions` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `level` tinyint(4) NOT NULL,
  `parent` smallint(6) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `code_2` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `code_3` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `default` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_sales`
--

CREATE TABLE IF NOT EXISTS `#__spr_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hash` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` float NOT NULL,
  `f_price` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `grandtotal` float NOT NULL,
  `f_grandtotal` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `weight` float NOT NULL,
  `height` float NOT NULL,
  `width` float NOT NULL,
  `depth` float NOT NULL,
  `currency_details` text COLLATE utf8_unicode_ci NOT NULL,
  `payment_details` text COLLATE utf8_unicode_ci NOT NULL,
  `shipping_details` text COLLATE utf8_unicode_ci NOT NULL,
  `tax_details` text COLLATE utf8_unicode_ci NOT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_bill_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_bill_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_bill_address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_bill_town` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_bill_state` smallint(6) NOT NULL,
  `user_bill_country` smallint(6) NOT NULL,
  `user_bill_postcode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_bill_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_bill_region_id` int(11) NOT NULL,
  `user_del_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_del_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_del_address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_del_town` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_del_state` smallint(6) NOT NULL,
  `user_del_country` smallint(6) NOT NULL,
  `user_del_postcode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_del_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_del_region_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_sales_items`
--

CREATE TABLE IF NOT EXISTS `#__spr_sales_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `sales_hash` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` smallint(6) NOT NULL,
  `variant_id` smallint(6) NOT NULL,
  `quantity` smallint(6) NOT NULL,
  `category` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `f_price` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `onsale` tinyint(1) NOT NULL,
  `sku` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tax` float NOT NULL,
  `tax_details` text COLLATE utf8_unicode_ci NOT NULL,
  `attributes` text COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `weight` float NOT NULL,
  `height` float NOT NULL,
  `width` float NOT NULL,
  `depth` float NOT NULL,
  `manufacturer` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `origin` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_shipping`
--

CREATE TABLE IF NOT EXISTS `#__spr_shipping` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `paymentoptions` text COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_shippingrules`
--

CREATE TABLE IF NOT EXISTS `#__spr_shippingrules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regions` text COLLATE utf8_unicode_ci NOT NULL,
  `shipping_id` smallint(6) NOT NULL,
  `start_weight` float NOT NULL,
  `end_weight` float NOT NULL,
  `start_items` int(11) NOT NULL,
  `end_items` int(11) NOT NULL,
  `start_price` float NOT NULL,
  `end_price` float NOT NULL,
  `price` float NOT NULL,
  `height` float NOT NULL,
  `width` float NOT NULL,
  `depth` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `shipping_id` (`shipping_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_taxes`
--

CREATE TABLE IF NOT EXISTS `#__spr_taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `regions` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `value` float NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_templates`
--

CREATE TABLE IF NOT EXISTS `#__spr_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `default` tinyint(4) NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_uniques`
--

CREATE TABLE IF NOT EXISTS `#__spr_uniques` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL,
  `hash` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_users`
--

CREATE TABLE IF NOT EXISTS `#__spr_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bill_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bill_address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bill_town` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `bill_state` smallint(6) NOT NULL,
  `bill_country` smallint(6) NOT NULL,
  `bill_postcode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `bill_region_id` int(11) NOT NULL,
  `bill_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `del_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `del_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `del_address2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `del_town` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `del_state` smallint(6) NOT NULL,
  `del_country` smallint(6) NOT NULL,
  `del_postcode` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `del_region_id` int(11) NOT NULL,
  `del_phone` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__spr_widgets`
--

CREATE TABLE IF NOT EXISTS `#__spr_widgets` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `views` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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

INSERT INTO `#__spr_config` (`name`, `params`) VALUES
('core', '{"name":"SalesPro","hp_title":"Welcome to SalesPro","cart_action":"1","ssl":"2","tc":"2","tcpage":"0","taxes":"1","show_welcome":"1","stock_empty":"1","updates":[],"updates_checked":1432217402,"timer":0,"weight":"grams"}'),
('files', '{"valid":"pdf,html","loc":"dls\\/"}'),
('images', '{"crop":"1","bg":"","valid":"jpg,png,gif","loc":"images\\/salesPro\\/"}'),
('thankyou', '{"title":"Thank you for your purchase","content":"<p>Please check your email for your order confirmation<\\/p>"}'),
('units', '{"size":"mm","weight":"grams"}');

-- --------------------------------------------------------

REPLACE INTO `#__spr_payment_methods` (`id`, `name`, `alias`, `class`, `params`, `about`) VALUES
(1, 'Cash', 'cash', 'salesProPaymentTypeCash', '', ''),
(2, 'PayPal', 'paypal', 'salesProPaymentTypePaypal', '{"api":"0","apiurl":"https:\\/\\/www.paypal.com\\/cgi-bin\\/webscr","apiseller":"","apititle":"","sboxurl":"https:\\/\\/www.sandbox.paypal.com\\/cgi-bin\\/webscr","sboxseller":"","sboxtitle":""}', ''),
(3, 'Bank Transfer', 'banktransfer', 'salesProPaymentTypeBanktransfer', '', ''),
(4, 'Cash on Delivery', 'cashondelivery', 'salesProPaymentTypeCash', '', ''),
(5, 'Braintree', 'braintree', 'salesProPaymentTypeBraintree', '', ''),
(6, 'Free Checkout', 'freecheckout', 'salesProPaymentTypeFreeCheckout', '', 'Free Checkout lets your users check out even if they have only free items in their cart. To use it, please ensure it is enabled, and is selected for your shipping methods where necessary');

-- --------------------------------------------------------

INSERT INTO `#__spr_payment_options` (`id`, `payment_method`, `name`, `sort`, `status`, `params`, `fee`, `fee_type`, `info`) VALUES
(1, 1, 'Cash', 2, 1, '', 0, 1, ''),
(2, 2, 'PayPal', 1, 2, '{"api":"0","apiurl":"https:\\/\\/www.paypal.com\\/cgi-bin\\/webscr","apiseller":"","apititle":"","sboxurl":"https:\\/\\/www.sandbox.paypal.com\\/cgi-bin\\/webscr","sboxseller":"","sboxtitle":""}', 0, 1, ''),
(3, 3, 'Bank Transfer', 3, 1, '', 0, 1, 'To make a bank transfer, please send your payment using the details below\r\n\r\nSort Code: 01-23-45\r\nA/C Number: 987654321\r\nA/C Name: MR A Non'),
(4, 3, 'Cash on Delivery', 4, 2, '', 5, 2, ''),
(5, 5, 'Braintree', 0, 2, '{"api":"0","currencies":"","merchant":"","pubkey":"","prikey":"","paypal":"0","sboxmerchant":"","sboxpubkey":"","sboxprikey":"","sboxpaypal":"0"}', 0, 0, ''),
(6, 6, 'Free Checkout', 0, 1, '', 0, 0, '');

-- --------------------------------------------------------

INSERT INTO `#__spr_templates` (`id`, `name`, `alias`, `default`, `params`) VALUES
(1, 'Moderna', 'moderna', 1, '{"color":"red"}');

-- --------------------------------------------------------

INSERT INTO `#__spr_emails` (`id`, `status`, `trigger`, `prodtypes`, `subject`, `copy`, `from`, `content`, `params`) VALUES
(1, 1, 0, '["1","2","3"]', 'Thank you for your order', 1, 'my@email.com', '<p>Dear {user_name},</p>\r\n<p> </p>\r\n<p>Thank you for your order, placed on {order_date}.</p>\r\n<p>Your order details are as follows:</p>\r\n<p>{order_details}</p>\r\n<p> </p>\r\n<p>Your order is currently {order_status}</p>\r\n<p> </p>\r\n<p>Sincerely,</p>\r\n<p>Your friendly SalesPro Team</p>', ''),
(2, 1, 1, '["1"]', 'Your order has been shipped', 1, 'your@email.com', '<p>Dear {user_name}</p>\r\n<p>Your order has been shipped using {shipping_type}</p>\r\n<p>Your order details are as follows:</p>\r\n<p>{order_details}</p>\r\n<p>Thank you for shopping at SalesPro</p>\r\n<p>Sincerely,</p>\r\n<p>Your friendly SalesPro Team</p>', ''),
(3, 1, 3, '["1","2","3"]', 'Your order has been refunded', 1, 'your@email.com', '<p>Dear {user_name}</p>\r\n<p>Your recent order has been refunded in full.</p>\r\n<p>Your order status is now: {order_status}</p>\r\n<p>Your original order was as follows:</p>\r\n<p>{order_details}</p>\r\n<p>Thanks for using SalesPro!</p>\r\n<p><br />Sincerely,</p>\r\n<p>Your friendly SalesPro Team</p>', ''),
(4, 1, 0, '["3"]', 'Your downloads are ready', 1, 'my@email.com', '<p>Dear {user_name}</p>\r\n<p>Your downloads are ready to access.</p>\r\n<p>Please visit this page to download your items: {download_link}</p>\r\n<p>Sincerely,</p>\r\n<p>The SalesPro Team</p>', '');

-- --------------------------------------------------------

REPLACE INTO `#__spr_prodtypes` (`id`, `name`, `params`) VALUES
(1, 'Standard', '{"name":"Standard","var":"2","del":"1","sm":"2","dl":"2","tc":"0","quantity":"1"}'),
(2, 'Virtual', '{"name":"Virtual","var":"2","del":"2","sm":"2","dl":"2","tc":"0","quantity":"2"}'),
(3, 'Downloadable', '{"name":"Downloadable","var":"2","del":"2","sm":"2","dl":"1","tc":"0","quantity":"2"}'),
(4, 'Advanced', '{"name":"Advanced","var":"1","del":"1","sm":"1","dl":"2","tc":"0","quantity":"1"}');

-- --------------------------------------------------------

INSERT INTO `#__spr_regions` (`id`, `level`, `parent`, `name`, `code_2`, `code_3`, `status`, `default`) VALUES
(1, 0, 0, 'Afghanistan', 'AF', 'AFG', 2, 0),
(2, 0, 0, 'Albania', 'AL', 'ALB', 2, 0),
(3, 0, 0, 'Algeria', 'DZ', 'DZA', 2, 0),
(4, 0, 0, 'American Samoa', 'AS', 'ASM', 2, 0),
(5, 0, 0, 'Andorra', 'AD', 'AND', 2, 0),
(6, 0, 0, 'Angola', 'AO', 'AGO', 2, 0),
(7, 0, 0, 'Anguilla', 'AI', 'AIA', 2, 0),
(8, 0, 0, 'Antarctica', 'AQ', 'ATA', 2, 0),
(9, 0, 0, 'Antigua and Barbuda', 'AG', 'ATG', 2, 0),
(10, 0, 0, 'Argentina', 'AR', 'ARG', 2, 0),
(11, 0, 0, 'Armenia', 'AM', 'ARM', 2, 0),
(12, 0, 0, 'Aruba', 'AW', 'ABW', 2, 0),
(13, 0, 0, 'Australia', 'AU', 'AUS', 2, 0),
(14, 0, 0, 'Austria', 'AT', 'AUT', 1, 0),
(15, 0, 0, 'Azerbaijan', 'AZ', 'AZE', 2, 0),
(16, 0, 0, 'Bahamas', 'BS', 'BHS', 2, 0),
(17, 0, 0, 'Bahrain', 'BH', 'BHR', 2, 0),
(18, 0, 0, 'Bangladesh', 'BD', 'BGD', 2, 0),
(19, 0, 0, 'Barbados', 'BB', 'BRB', 2, 0),
(20, 0, 0, 'Belarus', 'BY', 'BLR', 2, 0),
(21, 0, 0, 'Belgium', 'BE', 'BEL', 1, 0),
(22, 0, 0, 'Belize', 'BZ', 'BLZ', 2, 0),
(23, 0, 0, 'Benin', 'BJ', 'BEN', 2, 0),
(24, 0, 0, 'Bermuda', 'BM', 'BMU', 2, 0),
(25, 0, 0, 'Bhutan', 'BT', 'BTN', 2, 0),
(26, 0, 0, 'Bolivia', 'BO', 'BOL', 2, 0),
(27, 0, 0, 'Bosnia and Herzegowina', 'BA', 'BIH', 2, 0),
(28, 0, 0, 'Botswana', 'BW', 'BWA', 2, 0),
(29, 0, 0, 'Bouvet Island', 'BV', 'BVT', 2, 0),
(30, 0, 0, 'Brazil', 'BR', 'BRA', 2, 0),
(31, 0, 0, 'British Indian Ocean Territory', 'IO', 'IOT', 2, 0),
(32, 0, 0, 'Brunei Darussalam', 'BN', 'BRN', 2, 0),
(33, 0, 0, 'Bulgaria', 'BG', 'BGR', 2, 0),
(34, 0, 0, 'Burkina Faso', 'BF', 'BFA', 2, 0),
(35, 0, 0, 'Burundi', 'BI', 'BDI', 2, 0),
(36, 0, 0, 'Cambodia', 'KH', 'KHM', 2, 0),
(37, 0, 0, 'Cameroon', 'CM', 'CMR', 2, 0),
(38, 0, 0, 'Canada', 'CA', 'CAN', 1, 0),
(39, 0, 0, 'Cape Verde', 'CV', 'CPV', 2, 0),
(40, 0, 0, 'Cayman Islands', 'KY', 'CYM', 2, 0),
(41, 0, 0, 'Central African Republic', 'CF', 'CAF', 2, 0),
(42, 0, 0, 'Chad', 'TD', 'TCD', 2, 0),
(43, 0, 0, 'Chile', 'CL', 'CHL', 2, 0),
(44, 0, 0, 'China', 'CN', 'CHN', 2, 0),
(45, 0, 0, 'Christmas Island', 'CX', 'CXR', 2, 0),
(46, 0, 0, 'Cocos (Keeling) Islands', 'CC', 'CCK', 2, 0),
(47, 0, 0, 'Colombia', 'CO', 'COL', 2, 0),
(48, 0, 0, 'Comoros', 'KM', 'COM', 2, 0),
(49, 0, 0, 'Congo', 'CG', 'COG', 2, 0),
(50, 0, 0, 'Cook Islands', 'CK', 'COK', 2, 0),
(51, 0, 0, 'Costa Rica', 'CR', 'CRI', 2, 0),
(52, 0, 0, 'Cote D''Ivoire', 'CI', 'CIV', 2, 0),
(53, 0, 0, 'Croatia', 'HR', 'HRV', 2, 0),
(54, 0, 0, 'Cuba', 'CU', 'CUB', 2, 0),
(55, 0, 0, 'Cyprus', 'CY', 'CYP', 2, 0),
(56, 0, 0, 'Czech Republic', 'CZ', 'CZE', 2, 0),
(57, 0, 0, 'Denmark', 'DK', 'DNK', 1, 0),
(58, 0, 0, 'Djibouti', 'DJ', 'DJI', 2, 0),
(59, 0, 0, 'Dominica', 'DM', 'DMA', 2, 0),
(60, 0, 0, 'Dominican Republic', 'DO', 'DOM', 2, 0),
(61, 0, 0, 'East Timor', 'TP', 'TMP', 2, 0),
(62, 0, 0, 'Ecuador', 'EC', 'ECU', 2, 0),
(63, 0, 0, 'Egypt', 'EG', 'EGY', 2, 0),
(64, 0, 0, 'El Salvador', 'SV', 'SLV', 2, 0),
(65, 0, 0, 'Equatorial Guinea', 'GQ', 'GNQ', 2, 0),
(66, 0, 0, 'Eritrea', 'ER', 'ERI', 2, 0),
(67, 0, 0, 'Estonia', 'EE', 'EST', 2, 0),
(68, 0, 0, 'Ethiopia', 'ET', 'ETH', 2, 0),
(69, 0, 0, 'Falkland Islands (Malvinas)', 'FK', 'FLK', 2, 0),
(70, 0, 0, 'Faroe Islands', 'FO', 'FRO', 2, 0),
(71, 0, 0, 'Fiji', 'FJ', 'FJI', 2, 0),
(72, 0, 0, 'Finland', 'FI', 'FIN', 2, 0),
(73, 0, 0, 'France', 'FR', 'FRA', 1, 0),
(74, 0, 0, 'France, Metropolitan', 'FX', 'FXX', 2, 0),
(75, 0, 0, 'French Guiana', 'GF', 'GUF', 2, 0),
(76, 0, 0, 'French Polynesia', 'PF', 'PYF', 2, 0),
(77, 0, 0, 'French Southern Territories', 'TF', 'ATF', 2, 0),
(78, 0, 0, 'Gabon', 'GA', 'GAB', 2, 0),
(79, 0, 0, 'Gambia', 'GM', 'GMB', 2, 0),
(80, 0, 0, 'Georgia', 'GE', 'GEO', 2, 0),
(81, 0, 0, 'Germany', 'DE', 'DEU', 1, 0),
(82, 0, 0, 'Ghana', 'GH', 'GHA', 2, 0),
(83, 0, 0, 'Gibraltar', 'GI', 'GIB', 2, 0),
(84, 0, 0, 'Greece', 'GR', 'GRC', 1, 0),
(85, 0, 0, 'Greenland', 'GL', 'GRL', 2, 0),
(86, 0, 0, 'Grenada', 'GD', 'GRD', 2, 0),
(87, 0, 0, 'Guadeloupe', 'GP', 'GLP', 2, 0),
(88, 0, 0, 'Guam', 'GU', 'GUM', 2, 0),
(89, 0, 0, 'Guatemala', 'GT', 'GTM', 2, 0),
(90, 0, 0, 'Guinea', 'GN', 'GIN', 2, 0),
(91, 0, 0, 'Guinea-bissau', 'GW', 'GNB', 2, 0),
(92, 0, 0, 'Guyana', 'GY', 'GUY', 2, 0),
(93, 0, 0, 'Haiti', 'HT', 'HTI', 2, 0),
(94, 0, 0, 'Heard and Mc Donald Islands', 'HM', 'HMD', 2, 0),
(95, 0, 0, 'Honduras', 'HN', 'HND', 2, 0),
(96, 0, 0, 'Hong Kong', 'HK', 'HKG', 2, 0),
(97, 0, 0, 'Hungary', 'HU', 'HUN', 2, 0),
(98, 0, 0, 'Iceland', 'IS', 'ISL', 2, 0),
(99, 0, 0, 'India', 'IN', 'IND', 2, 0),
(100, 0, 0, 'Indonesia', 'ID', 'IDN', 2, 0),
(101, 0, 0, 'Iran (Islamic Republic of)', 'IR', 'IRN', 2, 0),
(102, 0, 0, 'Iraq', 'IQ', 'IRQ', 2, 0),
(103, 0, 0, 'Ireland', 'IE', 'IRL', 2, 0),
(104, 0, 0, 'Israel', 'IL', 'ISR', 2, 0),
(105, 0, 0, 'Italy', 'IT', 'ITA', 1, 0),
(106, 0, 0, 'Jamaica', 'JM', 'JAM', 2, 0),
(107, 0, 0, 'Japan', 'JP', 'JPN', 2, 0),
(108, 0, 0, 'Jordan', 'JO', 'JOR', 2, 0),
(109, 0, 0, 'Kazakhstan', 'KZ', 'KAZ', 2, 0),
(110, 0, 0, 'Kenya', 'KE', 'KEN', 2, 0),
(111, 0, 0, 'Kiribati', 'KI', 'KIR', 2, 0),
(112, 0, 0, 'Korea, Democratic People''s Republic of', 'KP', 'PRK', 2, 0),
(113, 0, 0, 'Korea, Republic of', 'KR', 'KOR', 2, 0),
(114, 0, 0, 'Kuwait', 'KW', 'KWT', 2, 0),
(115, 0, 0, 'Kyrgyzstan', 'KG', 'KGZ', 2, 0),
(116, 0, 0, 'Lao People''s Democratic Republic', 'LA', 'LAO', 2, 0),
(117, 0, 0, 'Latvia', 'LV', 'LVA', 2, 0),
(118, 0, 0, 'Lebanon', 'LB', 'LBN', 2, 0),
(119, 0, 0, 'Lesotho', 'LS', 'LSO', 2, 0),
(120, 0, 0, 'Liberia', 'LR', 'LBR', 2, 0),
(121, 0, 0, 'Libyan Arab Jamahiriya', 'LY', 'LBY', 2, 0),
(122, 0, 0, 'Liechtenstein', 'LI', 'LIE', 2, 0),
(123, 0, 0, 'Lithuania', 'LT', 'LTU', 2, 0),
(124, 0, 0, 'Luxembourg', 'LU', 'LUX', 2, 0),
(125, 0, 0, 'Macau', 'MO', 'MAC', 2, 0),
(126, 0, 0, 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD', 2, 0),
(127, 0, 0, 'Madagascar', 'MG', 'MDG', 2, 0),
(128, 0, 0, 'Malawi', 'MW', 'MWI', 2, 0),
(129, 0, 0, 'Malaysia', 'MY', 'MYS', 2, 0),
(130, 0, 0, 'Maldives', 'MV', 'MDV', 2, 0),
(131, 0, 0, 'Mali', 'ML', 'MLI', 2, 0),
(132, 0, 0, 'Malta', 'MT', 'MLT', 2, 0),
(133, 0, 0, 'Marshall Islands', 'MH', 'MHL', 2, 0),
(134, 0, 0, 'Martinique', 'MQ', 'MTQ', 2, 0),
(135, 0, 0, 'Mauritania', 'MR', 'MRT', 2, 0),
(136, 0, 0, 'Mauritius', 'MU', 'MUS', 2, 0),
(137, 0, 0, 'Mayotte', 'YT', 'MYT', 2, 0),
(138, 0, 0, 'Mexico', 'MX', 'MEX', 2, 0),
(139, 0, 0, 'Micronesia, Federated States of', 'FM', 'FSM', 2, 0),
(140, 0, 0, 'Moldova, Republic of', 'MD', 'MDA', 2, 0),
(141, 0, 0, 'Monaco', 'MC', 'MCO', 2, 0),
(142, 0, 0, 'Mongolia', 'MN', 'MNG', 2, 0),
(143, 0, 0, 'Montserrat', 'MS', 'MSR', 2, 0),
(144, 0, 0, 'Morocco', 'MA', 'MAR', 2, 0),
(145, 0, 0, 'Mozambique', 'MZ', 'MOZ', 2, 0),
(146, 0, 0, 'Myanmar', 'MM', 'MMR', 2, 0),
(147, 0, 0, 'Namibia', 'NA', 'NAM', 2, 0),
(148, 0, 0, 'Nauru', 'NR', 'NRU', 2, 0),
(149, 0, 0, 'Nepal', 'NP', 'NPL', 2, 0),
(150, 0, 0, 'Netherlands', 'NL', 'NLD', 1, 0),
(151, 0, 0, 'Netherlands Antilles', 'AN', 'ANT', 2, 0),
(152, 0, 0, 'New Caledonia', 'NC', 'NCL', 2, 0),
(153, 0, 0, 'New Zealand', 'NZ', 'NZL', 2, 0),
(154, 0, 0, 'Nicaragua', 'NI', 'NIC', 2, 0),
(155, 0, 0, 'Niger', 'NE', 'NER', 2, 0),
(156, 0, 0, 'Nigeria', 'NG', 'NGA', 2, 0),
(157, 0, 0, 'Niue', 'NU', 'NIU', 2, 0),
(158, 0, 0, 'Norfolk Island', 'NF', 'NFK', 2, 0),
(159, 0, 0, 'Northern Mariana Islands', 'MP', 'MNP', 2, 0),
(160, 0, 0, 'Norway', 'NO', 'NOR', 2, 0),
(161, 0, 0, 'Oman', 'OM', 'OMN', 2, 0),
(162, 0, 0, 'Pakistan', 'PK', 'PAK', 2, 0),
(163, 0, 0, 'Palau', 'PW', 'PLW', 2, 0),
(164, 0, 0, 'Panama', 'PA', 'PAN', 2, 0),
(165, 0, 0, 'Papua New Guinea', 'PG', 'PNG', 2, 0),
(166, 0, 0, 'Paraguay', 'PY', 'PRY', 2, 0),
(167, 0, 0, 'Peru', 'PE', 'PER', 2, 0),
(168, 0, 0, 'Philippines', 'PH', 'PHL', 2, 0),
(169, 0, 0, 'Pitcairn', 'PN', 'PCN', 2, 0),
(170, 0, 0, 'Poland', 'PL', 'POL', 2, 0),
(171, 0, 0, 'Portugal', 'PT', 'PRT', 1, 0),
(172, 0, 0, 'Puerto Rico', 'PR', 'PRI', 2, 0),
(173, 0, 0, 'Qatar', 'QA', 'QAT', 2, 0),
(174, 0, 0, 'Reunion', 'RE', 'REU', 2, 0),
(175, 0, 0, 'Romania', 'RO', 'ROM', 2, 0),
(176, 0, 0, 'Russian Federation', 'RU', 'RUS', 2, 0),
(177, 0, 0, 'Rwanda', 'RW', 'RWA', 2, 0),
(178, 0, 0, 'Saint Kitts and Nevis', 'KN', 'KNA', 2, 0),
(179, 0, 0, 'Saint Lucia', 'LC', 'LCA', 2, 0),
(180, 0, 0, 'Saint Vincent and the Grenadines', 'VC', 'VCT', 2, 0),
(181, 0, 0, 'Samoa', 'WS', 'WSM', 2, 0),
(182, 0, 0, 'San Marino', 'SM', 'SMR', 2, 0),
(183, 0, 0, 'Sao Tome and Principe', 'ST', 'STP', 2, 0),
(184, 0, 0, 'Saudi Arabia', 'SA', 'SAU', 2, 0),
(185, 0, 0, 'Senegal', 'SN', 'SEN', 2, 0),
(186, 0, 0, 'Seychelles', 'SC', 'SYC', 2, 0),
(187, 0, 0, 'Sierra Leone', 'SL', 'SLE', 2, 0),
(188, 0, 0, 'Singapore', 'SG', 'SGP', 2, 0),
(189, 0, 0, 'Slovakia (Slovak Republic)', 'SK', 'SVK', 2, 0),
(190, 0, 0, 'Slovenia', 'SI', 'SVN', 2, 0),
(191, 0, 0, 'Solomon Islands', 'SB', 'SLB', 2, 0),
(192, 0, 0, 'Somalia', 'SO', 'SOM', 2, 0),
(193, 0, 0, 'South Africa', 'ZA', 'ZAF', 2, 0),
(194, 0, 0, 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', 2, 0),
(195, 0, 0, 'Spain', 'ES', 'ESP', 1, 0),
(196, 0, 0, 'Sri Lanka', 'LK', 'LKA', 2, 0),
(197, 0, 0, 'St. Helena', 'SH', 'SHN', 2, 0),
(198, 0, 0, 'St. Pierre and Miquelon', 'PM', 'SPM', 2, 0),
(199, 0, 0, 'Sudan', 'SD', 'SDN', 2, 0),
(200, 0, 0, 'Suriname', 'SR', 'SUR', 2, 0),
(201, 0, 0, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', 2, 0),
(202, 0, 0, 'Swaziland', 'SZ', 'SWZ', 2, 0),
(203, 0, 0, 'Sweden', 'SE', 'SWE', 2, 0),
(204, 0, 0, 'Switzerland', 'CH', 'CHE', 1, 0),
(205, 0, 0, 'Syrian Arab Republic', 'SY', 'SYR', 2, 0),
(206, 0, 0, 'Taiwan', 'TW', 'TWN', 2, 0),
(207, 0, 0, 'Tajikistan', 'TJ', 'TJK', 2, 0),
(208, 0, 0, 'Tanzania, United Republic of', 'TZ', 'TZA', 2, 0),
(209, 0, 0, 'Thailand', 'TH', 'THA', 2, 0),
(210, 0, 0, 'Togo', 'TG', 'TGO', 2, 0),
(211, 0, 0, 'Tokelau', 'TK', 'TKL', 2, 0),
(212, 0, 0, 'Tonga', 'TO', 'TON', 2, 0),
(213, 0, 0, 'Trinidad and Tobago', 'TT', 'TTO', 2, 0),
(214, 0, 0, 'Tunisia', 'TN', 'TUN', 2, 0),
(215, 0, 0, 'Turkey', 'TR', 'TUR', 2, 0),
(216, 0, 0, 'Turkmenistan', 'TM', 'TKM', 2, 0),
(217, 0, 0, 'Turks and Caicos Islands', 'TC', 'TCA', 2, 0),
(218, 0, 0, 'Tuvalu', 'TV', 'TUV', 2, 0),
(219, 0, 0, 'Uganda', 'UG', 'UGA', 2, 0),
(220, 0, 0, 'Ukraine', 'UA', 'UKR', 2, 0),
(221, 0, 0, 'United Arab Emirates', 'AE', 'ARE', 2, 0),
(222, 0, 0, 'United Kingdom', 'GB', 'GBR', 1, 1),
(223, 0, 0, 'United States', 'US', 'USA', 1, 0),
(224, 0, 0, 'United States Minor Outlying Islands', 'UM', 'UMI', 2, 0),
(225, 0, 0, 'Uruguay', 'UY', 'URY', 2, 0),
(226, 0, 0, 'Uzbekistan', 'UZ', 'UZB', 2, 0),
(227, 0, 0, 'Vanuatu', 'VU', 'VUT', 2, 0),
(228, 0, 0, 'Vatican City State (Holy See)', 'VA', 'VAT', 2, 0),
(229, 0, 0, 'Venezuela', 'VE', 'VEN', 2, 0),
(230, 0, 0, 'Viet Nam', 'VN', 'VNM', 2, 0),
(231, 0, 0, 'Virgin Islands (British)', 'VG', 'VGB', 2, 0),
(232, 0, 0, 'Virgin Islands (U.S.)', 'VI', 'VIR', 2, 0),
(233, 0, 0, 'Wallis and Futuna Islands', 'WF', 'WLF', 2, 0),
(234, 0, 0, 'Western Sahara', 'EH', 'ESH', 2, 0),
(235, 0, 0, 'Yemen', 'YE', 'YEM', 2, 0),
(236, 0, 0, 'Serbia', 'RS', 'SRB', 2, 0),
(237, 0, 0, 'The Democratic Republic of Congo', 'DC', 'DRC', 2, 0),
(238, 0, 0, 'Zambia', 'ZM', 'ZMB', 2, 0),
(239, 0, 0, 'Zimbabwe', 'ZW', 'ZWE', 2, 0),
(240, 0, 0, 'East Timor', 'XE', 'XET', 2, 0),
(241, 0, 0, 'Jersey', 'XJ', 'XJE', 2, 0),
(242, 0, 0, 'St. Barthelemy', 'XB', 'XSB', 2, 0),
(243, 0, 0, 'St. Eustatius', 'XU', 'XSE', 2, 0),
(244, 0, 0, 'Canary Islands', 'XC', 'XCA', 2, 0),
(245, 0, 0, 'Montenegro', 'ME', 'MNE', 2, 0),
(246, 1, 223, 'Alabama', 'AL', 'ALA', 1, 0),
(247, 1, 223, 'Alaska', 'AK', 'ALK', 1, 0),
(248, 1, 223, 'Arizona', 'AZ', 'ARZ', 1, 0),
(249, 1, 223, 'Arkansas', 'AR', 'ARK', 1, 0),
(250, 1, 223, 'California', 'CA', 'CAL', 1, 0),
(251, 1, 223, 'Colorado', 'CO', 'COL', 1, 0),
(252, 1, 223, 'Connecticut', 'CT', 'CCT', 1, 0),
(253, 1, 223, 'Delaware', 'DE', 'DEL', 1, 0),
(254, 1, 223, 'District Of Columbia', 'DC', 'DOC', 1, 0),
(255, 1, 223, 'Florida', 'FL', 'FLO', 1, 0),
(256, 1, 223, 'Georgia', 'GA', 'GEA', 1, 0),
(257, 1, 223, 'Hawaii', 'HI', 'HWI', 1, 0),
(258, 1, 223, 'Idaho', 'ID', 'IDA', 1, 0),
(259, 1, 223, 'Illinois', 'IL', 'ILL', 1, 0),
(260, 1, 223, 'Indiana', 'IN', 'IND', 1, 0),
(261, 1, 223, 'Iowa', 'IA', 'IOA', 1, 0),
(262, 1, 223, 'Kansas', 'KS', 'KAS', 1, 0),
(263, 1, 223, 'Kentucky', 'KY', 'KTY', 1, 0),
(264, 1, 223, 'Louisiana', 'LA', 'LOA', 1, 0),
(265, 1, 223, 'Maine', 'ME', 'MAI', 1, 0),
(266, 1, 223, 'Maryland', 'MD', 'MLD', 1, 0),
(267, 1, 223, 'Massachusetts', 'MA', 'MSA', 1, 0),
(268, 1, 223, 'Michigan', 'MI', 'MIC', 1, 0),
(269, 1, 223, 'Minnesota', 'MN', 'MIN', 1, 0),
(270, 1, 223, 'Mississippi', 'MS', 'MIS', 1, 0),
(271, 1, 223, 'Missouri', 'MO', 'MIO', 1, 0),
(272, 1, 223, 'Montana', 'MT', 'MOT', 1, 0),
(273, 1, 223, 'Nebraska', 'NE', 'NEB', 1, 0),
(274, 1, 223, 'Nevada', 'NV', 'NEV', 1, 0),
(275, 1, 223, 'New Hampshire', 'NH', 'NEH', 1, 0),
(276, 1, 223, 'New Jersey', 'NJ', 'NEJ', 1, 0),
(277, 1, 223, 'New Mexico', 'NM', 'NEM', 1, 0),
(278, 1, 223, 'New York', 'NY', 'NEY', 1, 0),
(279, 1, 223, 'North Carolina', 'NC', 'NOC', 1, 0),
(280, 1, 223, 'North Dakota', 'ND', 'NOD', 1, 0),
(281, 1, 223, 'Ohio', 'OH', 'OHI', 1, 0),
(282, 1, 223, 'Oklahoma', 'OK', 'OKL', 1, 0),
(283, 1, 223, 'Oregon', 'OR', 'ORN', 1, 0),
(284, 1, 223, 'Pennsylvania', 'PA', 'PEA', 1, 0),
(285, 1, 223, 'Rhode Island', 'RI', 'RHI', 1, 0),
(286, 1, 223, 'South Carolina', 'SC', 'SOC', 1, 0),
(287, 1, 223, 'South Dakota', 'SD', 'SOD', 1, 0),
(288, 1, 223, 'Tennessee', 'TN', 'TEN', 1, 0),
(289, 1, 223, 'Texas', 'TX', 'TXS', 1, 0),
(290, 1, 223, 'Utah', 'UT', 'UTA', 1, 0),
(291, 1, 223, 'Vermont', 'VT', 'VMT', 1, 0),
(292, 1, 223, 'Virginia', 'VA', 'VIA', 1, 0),
(293, 1, 223, 'Washington', 'WA', 'WAS', 1, 0),
(294, 1, 223, 'West Virginia', 'WV', 'WEV', 1, 0),
(295, 1, 223, 'Wisconsin', 'WI', 'WIS', 1, 0),
(296, 1, 223, 'Wyoming', 'WY', 'WYO', 1, 0),
(297, 1, 38, 'Alberta', 'AB', 'ALB', 1, 0),
(298, 1, 38, 'British Columbia', 'BC', 'BRC', 1, 0),
(299, 1, 38, 'Manitoba', 'MB', 'MAB', 1, 0),
(300, 1, 38, 'New Brunswick', 'NB', 'NEB', 1, 0),
(301, 1, 38, 'Newfoundland and Labrador', 'NL', 'NFL', 1, 0),
(302, 1, 38, 'Northwest Territories', 'NT', 'NWT', 1, 0),
(303, 1, 38, 'Nova Scotia', 'NS', 'NOS', 1, 0),
(304, 1, 38, 'Nunavut', 'NU', 'NUT', 1, 0),
(305, 1, 38, 'Ontario', 'ON', 'ONT', 1, 0),
(306, 1, 38, 'Prince Edward Island', 'PE', 'PEI', 1, 0),
(307, 1, 38, 'Quebec', 'QC', 'QEC', 1, 0),
(308, 1, 38, 'Saskatchewan', 'SK', 'SAK', 1, 0),
(309, 1, 38, 'Yukon', 'YT', 'YUT', 1, 0),
(314, 1, 13, 'Australian Capital Territory', 'AC', 'ACT', 1, 0),
(315, 1, 13, 'New South Wales', 'NS', 'NSW', 1, 0),
(316, 1, 13, 'Northern Territory', 'NT', 'NOT', 1, 0),
(317, 1, 13, 'Queensland', 'QL', 'QLD', 1, 0),
(318, 1, 13, 'South Australia', 'SA', 'SOA', 1, 0),
(319, 1, 13, 'Tasmania', 'TS', 'TAS', 1, 0),
(320, 1, 13, 'Victoria', 'VI', 'VIC', 1, 0),
(321, 1, 13, 'Western Australia', 'WA', 'WEA', 1, 0);

-- --------------------------------------------------------

INSERT INTO `#__spr_currencies` (`id`, `name`, `code`, `symbol`, `status`, `default`, `xe`, `decimals`, `thousands`, `separator`, `checked`) VALUES
(2, 'United Arab Emirates Dirham', 'AED', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(3, 'Afghanistan Afghani', 'AFA', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(4, 'Albanian Lek', 'ALL', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(5, 'Netherlands Antillian Guilder', 'ANG', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(6, 'Angolan Kwanza', 'AOK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(7, 'Argentine Peso', 'ARS', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(9, 'Australian Dollar', 'AUD', '$', 1, 0, 1.78, 2, ',', '.', '0000-00-00'),
(10, 'Aruban Florin', 'AWG', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(11, 'Barbados Dollar', 'BBD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(12, 'Bangladeshi Taka', 'BDT', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(14, 'Bulgarian Lev', 'BGL', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(15, 'Bahraini Dinar', 'BHD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(16, 'Burundi Franc', 'BIF', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(17, 'Bermudian Dollar', 'BMD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(18, 'Brunei Dollar', 'BND', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(19, 'Bolivian Boliviano', 'BOB', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(20, 'Brazilian Real', 'BRL', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(21, 'Bahamian Dollar', 'BSD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(22, 'Bhutan Ngultrum', 'BTN', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(23, 'Burma Kyat', 'BUK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(24, 'Botswanian Pula', 'BWP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(25, 'Belize Dollar', 'BZD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(26, 'Canadian Dollar', 'CAD', '$', 1, 0, 1.8, 2, ',', '.', '0000-00-00'),
(27, 'Swiss Franc', 'CHF', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(28, 'Chilean Unidades de Fomento', 'CLF', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(29, 'Chilean Peso', 'CLP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(30, 'Yuan (Chinese) Renminbi', 'CNY', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(31, 'Colombian Peso', 'COP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(32, 'Costa Rican Colon', 'CRC', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(33, 'Czech Koruna', 'CZK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(34, 'Cuban Peso', 'CUP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(35, 'Cape Verde Escudo', 'CVE', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(36, 'Cyprus Pound', 'CYP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(40, 'Danish Krone', 'DKK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(41, 'Dominican Peso', 'DOP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(42, 'Algerian Dinar', 'DZD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(43, 'Ecuador Sucre', 'ECS', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(44, 'Egyptian Pound', 'EGP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(46, 'Ethiopian Birr', 'ETB', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(47, 'Euro', 'EUR', '&euro;', 1, 0, 1.265, 2, '.', ',', '0000-00-00'),
(49, 'Fiji Dollar', 'FJD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(50, 'Falkland Islands Pound', 'FKP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(52, 'British Pound', 'GBP', '&pound;', 1, 1, 1, 2, ',', '.', '0000-00-00'),
(53, 'Ghanaian Cedi', 'GHC', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(54, 'Gibraltar Pound', 'GIP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(55, 'Gambian Dalasi', 'GMD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(56, 'Guinea Franc', 'GNF', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(58, 'Guatemalan Quetzal', 'GTQ', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(59, 'Guinea-Bissau Peso', 'GWP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(60, 'Guyanan Dollar', 'GYD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(61, 'Hong Kong Dollar', 'HKD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(62, 'Honduran Lempira', 'HNL', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(63, 'Haitian Gourde', 'HTG', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(64, 'Hungarian Forint', 'HUF', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(65, 'Indonesian Rupiah', 'IDR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(66, 'Irish Punt', 'IEP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(67, 'Israeli Shekel', 'ILS', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(68, 'Indian Rupee', 'INR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(69, 'Iraqi Dinar', 'IQD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(70, 'Iranian Rial', 'IRR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(73, 'Jamaican Dollar', 'JMD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(74, 'Jordanian Dinar', 'JOD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(75, 'Japanese Yen', 'JPY', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(76, 'Kenyan Shilling', 'KES', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(77, 'Kampuchean (Cambodian) Riel', 'KHR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(78, 'Comoros Franc', 'KMF', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(79, 'North Korean Won', 'KPW', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(80, '(South) Korean Won', 'KRW', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(81, 'Kuwaiti Dinar', 'KWD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(82, 'Cayman Islands Dollar', 'KYD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(83, 'Lao Kip', 'LAK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(84, 'Lebanese Pound', 'LBP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(85, 'Sri Lanka Rupee', 'LKR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(86, 'Liberian Dollar', 'LRD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(87, 'Lesotho Loti', 'LSL', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(89, 'Libyan Dinar', 'LYD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(90, 'Moroccan Dirham', 'MAD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(91, 'Malagasy Franc', 'MGF', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(92, 'Mongolian Tugrik', 'MNT', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(93, 'Macau Pataca', 'MOP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(94, 'Mauritanian Ouguiya', 'MRO', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(95, 'Maltese Lira', 'MTL', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(96, 'Mauritius Rupee', 'MUR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(97, 'Maldive Rufiyaa', 'MVR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(98, 'Malawi Kwacha', 'MWK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(99, 'Mexican Peso', 'MXP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(100, 'Malaysian Ringgit', 'MYR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(101, 'Mozambique Metical', 'MZM', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(102, 'Nigerian Naira', 'NGN', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(103, 'Nicaraguan Cordoba', 'NIC', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(105, 'Norwegian Kroner', 'NOK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(106, 'Nepalese Rupee', 'NPR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(107, 'New Zealand Dollar', 'NZD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(108, 'Omani Rial', 'OMR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(109, 'Panamanian Balboa', 'PAB', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(110, 'Peruvian Nuevo Sol', 'PEN', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(111, 'Papua New Guinea Kina', 'PGK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(112, 'Philippine Peso', 'PHP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(113, 'Pakistan Rupee', 'PKR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(114, 'Polish Zloty', 'PLN', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(116, 'Paraguay Guarani', 'PYG', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(117, 'Qatari Rial', 'QAR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(118, 'Romanian Leu', 'RON', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(119, 'Rwanda Franc', 'RWF', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(120, 'Saudi Arabian Riyal', 'SAR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(121, 'Solomon Islands Dollar', 'SBD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(122, 'Seychelles Rupee', 'SCR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(123, 'Sudanese Pound', 'SDP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(124, 'Swedish Krona', 'SEK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(125, 'Singapore Dollar', 'SGD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(126, 'St. Helena Pound', 'SHP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(127, 'Sierra Leone Leone', 'SLL', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(128, 'Somali Shilling', 'SOS', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(129, 'Suriname Guilder', 'SRG', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(130, 'Sao Tome and Principe Dobra', 'STD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(131, 'Russian Ruble', 'RUB', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(132, 'El Salvador Colon', 'SVC', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(133, 'Syrian Potmd', 'SYP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(134, 'Swaziland Lilangeni', 'SZL', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(135, 'Thai Bath', 'THB', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(136, 'Tunisian Dinar', 'TND', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(137, 'Tongan Pa''anga', 'TOP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(138, 'East Timor Escudo', 'TPE', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(139, 'Turkish Lira', 'TRY', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(140, 'Trinidad and Tobago Dollar', 'TTD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(141, 'Taiwan Dollar', 'TWD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(142, 'Tanzanian Shilling', 'TZS', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(143, 'Uganda Shilling', 'UGS', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(144, 'US Dollar', 'USD', '$', 1, 0, 1.66, 2, ',', '.', '0000-00-00'),
(145, 'Uruguayan Peso', 'UYP', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(146, 'Venezualan Bolivar', 'VEB', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(147, 'Vietnamese Dong', 'VND', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(148, 'Vanuatu Vatu', 'VUV', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(149, 'Samoan Tala', 'WST', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(150, 'Democratic Yemeni Dinar', 'YDD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(151, 'Yemeni Rial', 'YER', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(152, 'Dinar', 'RSD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(153, 'South African Rand', 'ZAR', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(154, 'Zambian Kwacha', 'ZMK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(155, 'Zaire Zaire', 'ZRZ', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(156, 'Zimbabwe Dollar', 'ZWD', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(157, 'Slovak Koruna', 'SKK', '', 0, 0, 1, 2, ',', '.', '0000-00-00'),
(158, 'Armenian Dram', 'AMD', '', 0, 0, 1, 2, ',', '.', '0000-00-00');

-- --------------------------------------------------------

INSERT INTO `#__spr_widgets` (`id`, `name`, `type`, `params`, `views`, `sort`, `status`) VALUES
(1, 'Showcase items', 'showcase', '{"showtitle":"2","btn":"1","count":"6"}', '{"home":"1","category":"2","item":"2","basket":"2","checkout":"2","thankyou":"2"}', 0, 1),
(2, 'Featured items', 'featured', '{"showtitle":"1","btn":"1","layout":"1","cols":"3","count":"9"}', '{"home":"1","category":"1","item":"1","basket":"2","checkout":"2","thankyou":"2"}', 2, 1),
(3, 'New items', 'new', '{"showtitle":"1","btn":"1","layout":"0","cols":"3","count":"9"}', '{"home":"1","category":"2","item":"2","basket":"2","checkout":"2","thankyou":"2"}', 3, 1),
(4, 'All categories', 'categories', '{"showtitle":"1","btn":"1","layout":"0","cols":"4","count":"6"}', '{"home":"1","category":"2","item":"2","basket":"2","checkout":"2","thankyou":"2"}', 1, 1),
(5, 'Especially for you', 'random', '{"showtitle":"1","btn":"2","layout":"1","cols":"3","count":"6"}', '{"home":"1","category":"2","item":"2","basket":"1","checkout":"2","thankyou":"1"}', 4, 1);

-- --------------------------------------------------------

INSERT INTO `#__spr_widget_types` (`type`, `about`) VALUES
('categories', 'This widget displays all the available categories'),
('featured', 'This widget displays featured items'),
('new', 'This widget displays items sorted by newest first'),
('random', 'This widget shows random products from all your categories'),
('showcase', 'This widget displays featured items in a showcase box');