ALTER TABLE `#__spr_users` ADD `added` DATETIME NOT NULL ;

INSERT INTO `#__spr_payment_methods` (`id`, `name`, `alias`, `class`, `params`) VALUES
(5, 'Braintree', 'braintree', 'salesProPaymentTypeBraintree', '');

INSERT INTO `#__spr_payment_options` (`payment_method`, `name`, `sort`, `status`, `params`, `fee`, `fee_type`, `info`) VALUES
(5, 'Braintree', 0, 2, '{"api":"0","currencies":"","merchant":"","pubkey":"","prikey":"","paypal":"0","sboxmerchant":"","sboxpubkey":"","sboxprikey":"","sboxpaypal":"0"}', 0, 0, '');
