DROP TABLE IF EXISTS `#__ucs_users`;

DELETE FROM `#__content_types` WHERE (type_alias LIKE 'com_ucs_users.%');