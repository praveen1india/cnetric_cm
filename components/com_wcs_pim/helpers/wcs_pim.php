<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Wcs_pim
 * @author     Praveen <mantu.mnt@gmail.com>
 * @copyright  2016 demo
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Class Wcs_pimFrontendHelper
 *
 * @since  1.6
 */
class Wcs_pimHelpersWcs_pim
{
	/**
	 * Get an instance of the named model
	 *
	 * @param   string  $name  Model name
	 *
	 * @return null|object
	 */
	public static function getModel($name)
	{
		$model = null;

		// If the file exists, let's
		if (file_exists(JPATH_SITE . '/components/com_wcs_pim/models/' . strtolower($name) . '.php'))
		{
			require_once JPATH_SITE . '/components/com_wcs_pim/models/' . strtolower($name) . '.php';
			$model = JModelLegacy::getInstance($name, 'Wcs_pimModel');
		}

		return $model;
	}
}
