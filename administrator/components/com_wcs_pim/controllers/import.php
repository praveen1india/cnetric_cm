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

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Categories list controller class.
 *
 * @since  1.6
 */
class Wcs_pimControllerImport extends JControllerAdmin
{
	/**
	 * Method to clone existing Import
	 *
	 * @return void
	 */
	public function duplicate()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_WCS_PIM_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_WCS_PIM_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_wcs_pim&view=Import');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'category', $prefix = 'Wcs_pimModel', $config = array())
	{

		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
	public function upload()
	{
			echo "<pre>";
		  //exclude the first row
			$handle = fopen($_FILES['CatUpload']['tmp_name'], "r");
			$firstRow = '1';

			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

				if($firstRow < '3')
				{ $firstRow = $firstRow+1; }
				else
				{
					//Selct If is already or not
					$db = JFactory::getDbo();
					$idfer	=$data['0'];
					$query ="Select * from catgroup where identifier = '$idfer'";
					$db->setQuery($query);
					$results = $db->loadObject();

					if($results)
					{
						//Insert into CatgroupDescrip Table

						$CatgroupDes = new stdClass();
						$CatgroupDes->language_id				= $data['11'];
						$CatgroupDes->catgroup_id				= $results->catgroup_id;
						$CatgroupDes->name						= $data['4'];
						$CatgroupDes->shortdescription			= $data['5'];
						$CatgroupDes->longdescription	 		= $data['6'];
						$CatgroupDes->keyword 					= $data['9'];
						$CatgroupDes->thumbnail 				= $data['7'];
						$CatgroupDes->fullimage 				= $data['8'];

						$result								    = JFactory::getDbo()->insertObject('catgrpdesc', $CatgroupDes);

						//Insert into CatgroupDescrip Table

						//Insert into CattoGrp Table (cattogrp)

						$CAtoGrp = new stdClass();
						$CAtoGrp->catgroup_id		= $results->catgroup_id;
						$CAtoGrp->catalog_id		= '1002';

						$result = JFactory::getDbo()->insertObject('cattogrp', $CAtoGrp);

						//Insert into CattoGrp Table (cattogrp)
					}
					else
					{
						/****** New Insrtion *******/
						//Insert into Catgroup Table
						$db = JFactory::getDbo();
						$db->setQuery('SELECT MAX(catgroup_id) FROM catgroup');
						$max             = $db->loadResult();
						$CatGrpId		 = $max + 1;

						$Catgroup = new stdClass();
						$Catgroup->catgroup_id		= $CatGrpId;
						$Catgroup->identifier		= $data['0'];

						$result = JFactory::getDbo()->insertObject('catgroup', $Catgroup);
						//Insert into Catgroup Table

						//Insert into CatgroupDescrip Table

						$CatgroupDes = new stdClass();
						$CatgroupDes->language_id				= $data['11'];
						$CatgroupDes->catgroup_id				= $CatGrpId;
						$CatgroupDes->name						= $data['4'];
						$CatgroupDes->shortdescription			= $data['5'];
						$CatgroupDes->longdescription	 		= $data['6'];
						$CatgroupDes->keyword 					= $data['9'];
						$CatgroupDes->thumbnail 				= $data['7'];
						$CatgroupDes->fullimage 				= $data['8'];

						$result								    = JFactory::getDbo()->insertObject('catgrpdesc', $CatgroupDes);

						//Insert into CatgroupDescrip Table

						//Insert into CattoGrp Table (cattogrp)

						$CAtoGrp = new stdClass();
						$CAtoGrp->catgroup_id		= $CatGrpId;
						$CAtoGrp->catalog_id		= '1002';

						$result = JFactory::getDbo()->insertObject('cattogrp', $CAtoGrp);

						//Insert into CattoGrp Table (cattogrp)

						//Insert into CatgroupGRPREL Table (catgrprel)
						if($data['1'] != 'TRUE')
						{
						//Selct Parent Id
						$db = JFactory::getDbo();
						$query ="Select * from catgroup where identifier = ".$data['2'];
						$db->setQuery($query);
						$results = $db->loadObject();

						$CAtoGrpRel = new stdClass();
						$CAtoGrpRel->catgroup_id_parent		= $results->catgroup_id;
						$CAtoGrpRel->catgroup_id_child		= $CatGrpId;
						$CAtoGrpRel->catalog_id				= '1002';

						$result = JFactory::getDbo()->insertObject('catgrprel', $CAtoGrpRel);
						}
						//Insert into CatgroupGRPREL Table (catgrprel)

					}

//					print_r($data);
				}
			}

			fclose($handle);

//		  print_r($_FILES);
		  echo "End"; exit;
	 	  $mainframe = JFactory::getApplication();
		  $url ="index.php?option=com_wcs_pim&view=import";
		  $mainframe->redirect($url, $msg='Saved sucessfully.', $msgType='message');
	}

}
