<?php
/**
* @version      3.19.0 10.09.2014
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
JTable::addIncludePath(JPATH_COMPONENT.'/tables');
jimport('joomla.application.component.model');
JModel::addIncludePath(JPATH_COMPONENT.'/models');
require_once(JPATH_COMPONENT_SITE."/lib/factory.php");
$controller = getJsFrontRequestController();
require("loadparams.php");

if (file_exists(JPATH_COMPONENT.'/controllers/'.$controller.'.php'))
    require_once(JPATH_COMPONENT.'/controllers/'.$controller.'.php');
else
    JError::raiseError(403, JText::_('Access Forbidden'));

$classname = 'JshoppingController'.$controller;
$controller = new $classname();
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
displayTextJSC();
?>