<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ucs_users
 * @author     UCS Praveen <UCS@cloud.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::register('Ucs_usersFrontendHelper', JPATH_COMPONENT . '/helpers/ucs_users.php');

// Execute the task.
$controller = JControllerLegacy::getInstance('Ucs_users');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
