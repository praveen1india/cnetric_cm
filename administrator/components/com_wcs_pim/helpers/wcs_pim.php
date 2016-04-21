<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Wcs_pim
 * @author     Praveen <mantu.mnt@gmail.com>
 * @copyright  2016 demo
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

/**
 * Wcs_pim helper.
 *
 * @since  1.6
 */
class Wcs_pimHelpersWcs_pim
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  string
	 *
	 * @return void
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_WCS_PIM_TITLE_CATEGORIES'),
			'index.php?option=com_wcs_pim&view=categories',
			$vName == 'categories'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_WCS_PIM_TITLE_PIMPRODUCTS'),
			'index.php?option=com_wcs_pim&view=pimproducts',
			$vName == 'pimproducts'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_WCS_PIM_BLK'),
			'index.php?option=com_wcs_pim&view=import',
			$vName == 'import'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return    JObject
	 *
	 * @since    1.6
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;

		$assetName = 'com_wcs_pim';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action)
		{
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
	public  static function GetData()
	{
		$id			= JRequest::getvar('id');
		$catgroup	='';
		if(isset($id)){
		$db 		= JFactory::getDbo();
		$query	    ="Select * from catgroup where catgroup_id = ".$id;
		$db->setQuery($query);
		$catgroup 	= $db->loadObject();
		}
		return $catgroup;

	}
	public  static function GetLangData($lang)
	{
		$id			= JRequest::getvar('id');
		$catgrpdesc	='';
		if(isset($id)){
		$db 		= JFactory::getDbo();
		$query	    ="Select * from catgrpdesc where language_id = ".$lang. " and catgroup_id = ".$id;
		$db->setQuery($query);
		$catgrpdesc 	= $db->loadObject();
		}
		return $catgrpdesc;

	}
	public  static function GetCatentryDescLangData($lang)
	{
		$id			= JRequest::getvar('id');
		$catentrydesc	='';
		if(isset($id)){
		$db 		= JFactory::getDbo();
		$query	    ="Select * from catentdesc where language_id = ".$lang. " and catentry_id = ".$id;
		$db->setQuery($query);
		$catentrydesc 	= $db->loadObject();
		}
		return $catentrydesc;

	}
	public  static function GetCatentryData()
	{
		$id			= JRequest::getvar('id');
		$catgentry	='';
		if(isset($id)){
		$db 		= JFactory::getDbo();
		$query	    ="Select * from catentry where catentry_id = ".$id;
		$db->setQuery($query);
		$catgentry 	= $db->loadObject();
		}
		return $catgentry;

	}
	public  static function GetpriceData()
	{
		$id			= JRequest::getvar('id');
		$price	='';
		if(isset($id)){
		$db 		= JFactory::getDbo();
		$query	    ="Select * from price where catentry_id = ".$id;
		$db->setQuery($query);
		$price 	= $db->loadObject();
		}
		return $price;

	}
}
