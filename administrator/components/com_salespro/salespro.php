<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die;
if (!JFactory::getUser()->authorise('core.manage', 'com_salespro')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// fetch the view
$view = JRequest::getVar( 'view' , 'dashboard' );

//GET THE VIEW CONTROLLER
$controller_class = JPATH_COMPONENT.'/controllers/'.$view.'.php';
if(file_exists($controller_class)) {
    $controller_name = 'salesPro'.ucfirst($view).'Controller';
    require_once($controller_class);
} else {
    $controller_name = 'salesProController';
    $controller_class = JPATH_COMPONENT.'/controllers/controller.php';
    require_once($controller_class);
}
$controller = new $controller_name;
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();