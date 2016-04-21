<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Wcs_pim
 * @author     Praveen <mantu.mnt@gmail.com>
 * @copyright  2016 demo
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Pimproducts list controller class.
 *
 * @since  1.6
 */
class Wcs_pimControllerPimproducts extends Wcs_pimController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Pimproducts', $prefix = 'Wcs_pimModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
