<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Wcs_pim
 * @author     Praveen <mantu.mnt@gmail.com>
 * @copyright  2016 demo
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Wcs_pim', JPATH_COMPONENT);

// Execute the task.
$controller = JControllerLegacy::getInstance('Wcs_pim');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
