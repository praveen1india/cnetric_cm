<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Ucs_users
 * @author     UCS Praveen <UCS@cloud.com>
 * @copyright  Copyright (C) 2015. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * User controller class.
 *
 * @since  1.6
 */
class Ucs_usersControllerUser extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'users';
		parent::__construct();
	}
}
