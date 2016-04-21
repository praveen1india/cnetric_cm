CREATE TABLE IF NOT EXISTS `#__wcs_pim_categories` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`modified_by` INT(11)  NOT NULL ,
`cat_title` VARCHAR(255)  NOT NULL ,
`cat_name` VARCHAR(255)  NOT NULL ,
`lang` VARCHAR(255)  NOT NULL ,
`short_des` TEXT NOT NULL ,
`long_des` TEXT NOT NULL ,
`thumb_img` VARCHAR(255)  NOT NULL ,
`full_img` VARCHAR(255)  NOT NULL ,
`keyword` TEXT NOT NULL ,
`parent_id` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'category','com_wcs_pim.category','{"special":{"dbtable":"#__wcs_pim_categories","key":"id","type":"category","prefix":"Wcs PimTable"}}', '{"formFile":"administrator\/components\/com_wcs_pim\/models\/forms\/category.xml", "hideFields":["checked_out","checked_out_time","params","language" ,"keyword"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_wcs_pim.category')
) LIMIT 1;
