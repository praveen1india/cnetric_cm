<?php
/* -------------------------------------------
Module: mod_salespro_search
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';

require_once JPATH_ADMINISTRATOR . '/components/com_salespro/classes/core/salesPro.class.php';

$helper = new ModSalesProMenuHelper;
$list = $helper->getList();

$uri = JFactory::getURI();
$query = $uri->getQuery();
$activeurl = $uri->toString();

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_salespro_menu', $params->get('layout', 'default'));