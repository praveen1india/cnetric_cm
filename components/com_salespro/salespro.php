<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die( 'Restricted access' );

require_once JPATH_BASE.'/components/com_salespro/controller.php';

$controller = new SalesProController();
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();