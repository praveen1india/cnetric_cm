ALTER TABLE `#__spr_items` CHANGE `length` `depth` FLOAT NOT NULL;

ALTER TABLE `#__spr_sales_items` CHANGE `length` `depth` FLOAT NOT NULL;

ALTER TABLE `#__spr_shippingrules` ADD `height` FLOAT NOT NULL ;
ALTER TABLE `#__spr_shippingrules` ADD `width` FLOAT NOT NULL ;
ALTER TABLE `#__spr_shippingrules` ADD `depth` FLOAT NOT NULL ;

ALTER TABLE `#__spr_sales` ADD `height` FLOAT NOT NULL AFTER `weight`, ADD `width` FLOAT NOT NULL AFTER `height`, ADD `depth` FLOAT NOT NULL AFTER `width`;

REPLACE INTO `#__spr_payment_methods` (`id`, `name`, `alias`, `class`, `params`, `about`) VALUES
(1, 'Cash', 'cash', 'salesProPaymentTypeCash', '', ''),
(2, 'PayPal', 'paypal', 'salesProPaymentTypePaypal', '{"api":"0","apiurl":"https:\\/\\/www.paypal.com\\/cgi-bin\\/webscr","apiseller":"","apititle":"","sboxurl":"https:\\/\\/www.sandbox.paypal.com\\/cgi-bin\\/webscr","sboxseller":"","sboxtitle":""}', ''),
(3, 'Bank Transfer', 'banktransfer', 'salesProPaymentTypeBanktransfer', '', ''),
(4, 'Cash on Delivery', 'cashondelivery', 'salesProPaymentTypeCash', '', ''),
(5, 'Braintree', 'braintree', 'salesProPaymentTypeBraintree', '', ''),
(6, 'Free Checkout', 'freecheckout', 'salesProPaymentTypeFreeCheckout', '', 'Free Checkout lets your users check out even if they have only free items in their cart. To use it, please ensure it is enabled, and is selected for your shipping methods where necessary');