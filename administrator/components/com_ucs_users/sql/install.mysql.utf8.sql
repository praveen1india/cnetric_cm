CREATE TABLE IF NOT EXISTS `#__ucs_users` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`company` VARCHAR(255)  NOT NULL ,
`email` VARCHAR(255)  NOT NULL ,
`address` TEXT NOT NULL ,
`first_name` VARCHAR(255)  NOT NULL ,
`phone` VARCHAR(255)  NOT NULL ,
`partner` VARCHAR(255)  NOT NULL ,
`city` VARCHAR(255)  NOT NULL ,
`states` VARCHAR(255)  NOT NULL ,
`zip` INT(11)  NOT NULL ,
`last_name` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'User','com_ucs_users.user','{"special":{"dbtable":"#__ucs_users","key":"id","type":"User","prefix":"UCS RegistrationTable"}}', '{"formFile":"administrator\/components\/com_ucs_users\/models\/forms\/user.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"address"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_ucs_users.user')
) LIMIT 1;
