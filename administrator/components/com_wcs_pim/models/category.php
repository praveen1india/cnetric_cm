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

jimport('joomla.application.component.modeladmin');

/**
 * Wcs_pim model.
 *
 * @since  1.6
 */
class Wcs_pimModelCategory extends JModelAdmin
{
	/**
	 * @var      string    The prefix to use with controller messages.
	 * @since    1.6
	 */
	protected $text_prefix = 'COM_WCS_PIM';

	/**
	 * @var   	string  	Alias to manage history control
	 * @since   3.2
	 */
	public $typeAlias = 'com_wcs_pim.category';

	/**
	 * @var null  Item data
	 * @since  1.6
	 */
	protected $item = null;

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return    JTable    A database object
	 *
	 * @since    1.6
	 */
	public function getTable($type = 'Category', $prefix = 'Wcs_pimTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      An optional array of data for the form to interogate.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since    1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{


		// Initialise variables.
		$app = JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm(
			'com_wcs_pim.category', 'category',
			array('control' => 'jform',
				'load_data' => $loadData
			)
		);

		//Get Task And UPdate
		if(isset($_POST['task'])== 'category.apply')
		{
			$id = JRequest::getVar('catgrp_id');

			if($id)
			{
				//Update data
				$data		= $this->DataUpdate();
				//echo $data; exit;

			}
			else
			{
				//Insert the new data
				$data		= $this->DataInsert();
				//echo $data; exit;


			}
		}
		//Get Task And UPdate



		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return   mixed  The data for the form.
	 *
	 * @since    1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_wcs_pim.edit.category.data', array());

		if (empty($data))
		{
			if ($this->item === null)
			{
				$this->item = $this->getItem();
			}

			$data = $this->item;

			// Support for multiple or not foreign key field: parent_id
			$array = array();
			foreach((array)$data->parent_id as $value):
				if(!is_array($value)):
					$array[] = $value;
				endif;
			endforeach;
			$data->parent_id = implode(',',$array);
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed    Object on success, false on failure.
	 *
	 * @since    1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			// Do any procesing on fields here if needed
		}
		return $item;
	}

	/**
	 * Method to duplicate an Category
	 *
	 * @param   array  &$pks  An array of primary key IDs.
	 *
	 * @return  boolean  True if successful.
	 *
	 * @throws  Exception
	 */
	public function duplicate(&$pks)
	{
		$user = JFactory::getUser();

		// Access checks.
		if (!$user->authorise('core.create', 'com_wcs_pim'))
		{
			throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
		}

		$dispatcher = JEventDispatcher::getInstance();
		$context    = $this->option . '.' . $this->name;

		// Include the plugins for the save events.
		JPluginHelper::importPlugin($this->events_map['save']);

		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if ($table->load($pk, true))
			{
				// Reset the id to create a new record.
				$table->id = 0;

				if (!$table->check())
				{
					throw new Exception($table->getError());
				}


				// Trigger the before save event.
				$result = $dispatcher->trigger($this->event_before_save, array($context, &$table, true));

				if (in_array(false, $result, true) || !$table->store())
				{
					throw new Exception($table->getError());
				}

				// Trigger the after save event.
				$dispatcher->trigger($this->event_after_save, array($context, &$table, true));
			}
			else
			{
				throw new Exception($table->getError());
			}
		}

		// Clean cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable  $table  Table Object
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id))
		{
			// Set ordering to the last item if not set
			if (@$table->ordering === '')
			{
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__wcs_pim_categories');
				$max             = $db->loadResult();
				$table->ordering = $max + 1;
			}
		}
	}

	public function DataUpdate()
	{
		$ary		=array("cat_name","short_des","long_des","keyword","thumb_img","full_img");

		$db = JFactory::getDbo();
		$query ="Select * from #__pim_language";
		$db->setQuery($query);
		$results = $db->loadObjectList();

		foreach($results as $rows)
		{
			$row_name	= $rows->code;
			foreach($ary as $row)
			{
				$rowd[$row."_".$row_name]= $_POST[$row."_".$row_name];
			}
		}
		//print_r($rowd); exit;
		$CatGrpId								= JRequest::getvar('catgrp_id');

		foreach($results as $rows)
		{

			//Update into CatgroupDescrip Table
			$CatgroupDes = new stdClass();
			$CatgroupDes->language_id				= $rows->value;
			 $CatgroupDes->catgroup_id				= $CatGrpId;
			$CatgroupDes->name						= $rowd["cat_name_" .$rows->code];
			$CatgroupDes->shortdescription			= $rowd["short_des_" .$rows->code];
			$CatgroupDes->longdescription	 		= $rowd["long_des_" .$rows->code];
			$CatgroupDes->keyword 					= $rowd["keyword_" .$rows->code];
			$CatgroupDes->thumbnail 				= $rowd["thumb_img_" .$rows->code];
			$CatgroupDes->fullimage 				= $rowd["full_img_" .$rows->code];


			//print_r($CatgroupDes); exit;

			//$result								    = JFactory::getDbo()->updateObject('catgrpdesc', $CatgroupDes,array('catgroup_id','language_id'));
			$db	= JFactory::getDbo();

			$query = $db->getQuery(true);
	     	// Fields to update.
			$fields = array(
			    $db->quoteName('name') . ' = ' . $db->quote($CatgroupDes->name),
			    $db->quoteName('shortdescription') . ' = '.$db->quote($CatgroupDes->shortdescription),
			    $db->quoteName('longdescription') . ' = '.$db->quote($CatgroupDes->longdescription),
			    $db->quoteName('keyword') . ' = '.$db->quote($CatgroupDes->keyword),
			    $db->quoteName('thumbnail') . ' = '.$db->quote($CatgroupDes->thumbnail),
			    $db->quoteName('fullimage') . ' = '.$db->quote($CatgroupDes->fullimage)
			);

			// Conditions for which records should be updated.
			$conditions = array(
			    $db->quoteName('language_id') . ' = '.$rows->value,
			    $db->quoteName('catgroup_id') . ' = ' . $CatGrpId
			);

			$query->update($db->quoteName('catgrpdesc'))->set($fields)->where($conditions);
			//echo $query; exit;
	     	$db->setQuery($query);
	     	$db->execute();

			//Update into CatgroupDescrip Table


		}
			//Update into catgroup Table (catgroup)
			$Catgroup = new stdClass();
			$Catgroup->catgroup_id		= $CatGrpId;
			$Catgroup->identifier		= $_POST['identifier'];

			$result = JFactory::getDbo()->updateObject('catgroup', $Catgroup,catgroup_id);

			//Update into catgroup Table (catgroup)


			//Update into CatgroupGRPREL Table (catgrprel)
			/*if(isset($_POST['jform']['parent_id']) != '0')
			{
			$CAtoGrpRel = new stdClass();
			$CAtoGrpRel->catgroup_id_parent		= $_POST['jform']['parent_id'];
			$CAtoGrpRel->catgroup_id_child		= $CatGrpId;
			$CAtoGrpRel->catalog_id				= '1002';

			$result = JFactory::getDbo()->updateObject('catgrprel', $CAtoGrpRel,catgroup_id_child);
			}*/

			//Update into CatgroupGRPREL Table (catgrprel)


		  $mainframe = JFactory::getApplication();
		  $url ="index.php?option=com_wcs_pim&view=category&layout=edit&id=".$CatGrpId;
		  $mainframe->redirect($url, $msg='Updated sucessfully.', $msgType='message');

		return ' data update';
	}
	public function DataInsert()
	{
		$ary		=array("cat_name","short_des","long_des","keyword","thumb_img","full_img");

		$db = JFactory::getDbo();
		$query ="Select * from #__pim_language";
		$db->setQuery($query);
		$results = $db->loadObjectList();

		foreach($results as $rows)
		{
			$row_name	= $rows->code;
			foreach($ary as $row)
			{
				$rowd[$row."_".$row_name]= $_POST[$row."_".$row_name];
			}
		}
		//print_r($rowd); exit;
		 //print_r($_POST); exit;

		//Insert into Catgroup Table
			$db = JFactory::getDbo();
			$db->setQuery('SELECT MAX(catgroup_id) FROM catgroup');
			$max             = $db->loadResult();
			$CatGrpId		 = $max + 1;

			$Catgroup = new stdClass();
			$Catgroup->catgroup_id		= $CatGrpId;
			$Catgroup->identifier		= $_POST['identifier'];

			$result = JFactory::getDbo()->insertObject('catgroup', $Catgroup);
			//Insert into Catgroup Table

		foreach($results as $rows)
		{

			//Insert into CatgroupDescrip Table

			$CatgroupDes = new stdClass();
			$CatgroupDes->language_id				= $rows->value;
			$CatgroupDes->catgroup_id				= $CatGrpId;
			$CatgroupDes->name						= $rowd["cat_name_" .$rows->code];
			$CatgroupDes->shortdescription			= $rowd["short_des_" .$rows->code];
			$CatgroupDes->longdescription	 		= $rowd["long_des_" .$rows->code];
			$CatgroupDes->keyword 					= $rowd["keyword_" .$rows->code];
			$CatgroupDes->thumbnail 				= $rowd["thumb_img_" .$rows->code];
			$CatgroupDes->fullimage 				= $rowd["full_img_" .$rows->code];

			$result								    = JFactory::getDbo()->insertObject('catgrpdesc', $CatgroupDes);

			//Insert into CatgroupDescrip Table


		}
			//Insert into CattoGrp Table (cattogrp)

			$CAtoGrp = new stdClass();
			$CAtoGrp->catgroup_id		= $CatGrpId;
			$CAtoGrp->catalog_id		= '1002';

			$result = JFactory::getDbo()->insertObject('cattogrp', $CAtoGrp);

			//Insert into CattoGrp Table (cattogrp)


			//Insert into CatgroupGRPREL Table (catgrprel)
			if($_POST['parent_id'] != '0')
			{
			$CAtoGrpRel = new stdClass();
			$CAtoGrpRel->catgroup_id_parent		= $_POST['parent_id'];
			$CAtoGrpRel->catgroup_id_child		= $CatGrpId;
			$CAtoGrpRel->catalog_id				= '1002';

			$result = JFactory::getDbo()->insertObject('catgrprel', $CAtoGrpRel);
			}
			//Insert into CatgroupGRPREL Table (catgrprel)


		  $mainframe = JFactory::getApplication();
		  $url ="index.php?option=com_wcs_pim&view=category&layout=edit&id=".$CatGrpId;
		  $mainframe->redirect($url, $msg='Saved sucessfully.', $msgType='message');

		return 'data insert';
	}


}
