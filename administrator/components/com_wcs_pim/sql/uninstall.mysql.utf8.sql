DROP TABLE IF EXISTS `#__wcs_pim_categories`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_wcs_pim.%');