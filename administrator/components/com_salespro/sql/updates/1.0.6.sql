ALTER TABLE `#__spr_emails` CHANGE `type` `trigger` TINYINT(4) NOT NULL;

ALTER TABLE `#__spr_emails` ADD `prodtypes` TEXT NOT NULL AFTER `trigger`;

UPDATE `#__spr_emails` SET `trigger` = '0' WHERE `trigger` = '1';

UPDATE `#__spr_emails` SET `trigger` = '1' WHERE `trigger` = '2';

UPDATE `#__spr_emails` SET `trigger` = '2' WHERE `trigger` = '3';

UPDATE `#__spr_emails` SET `trigger` = '3' WHERE `trigger` = '1';

