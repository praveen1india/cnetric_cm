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
class Wcs_pimModelPimproduct extends JModelAdmin
{
	public function getForm ($data = array(), $loadData = true)
	{
//		echo "<pre>";
//		print_r($_POST); exit;
		//Get Task And UPdate
		if(isset($_POST['task'])== 'pimproduct.apply')
		{
			$Pid = JRequest::getVar('prodcutId');

			if($Pid)
			{
				//Update data
				$data		= $this->DataUpdate();

			}
			else
			{
				//Insert the new data
				$data		= $this->DataInsert();

			}
		}
		//Get Task And UPdate

	}
	 /** Returns a reference to the a Table object, always creating it.
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
	public function DataInsert()
	{
		$ary		=array("name","shortdesc","longdesc","keyword","thumb_img","full_img");

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

		//Insert into catentry Table
			$db = JFactory::getDbo();
			$db->setQuery('SELECT MAX(catentry_id) FROM catentry');
			$max             = $db->loadResult();
			$CatEntId		 = $max + 1;

			$Catentry = new stdClass();
			$Catentry->catentry_id		= $CatEntId;
//			$Catentry->identifier		= $_POST['identifier'];
			$Catentry->partnumber		= $_POST['partnumber'];
			$Catentry->mfpartnumber		= $_POST['mfpartnumber'];
			$Catentry->mfname			= $_POST['mfname'];
			$Catentry->catenttype_id	= $_POST['catenttype'];
			$Catentry->buyable			= $_POST['buyable'];

			$result = JFactory::getDbo()->insertObject('catentry', $Catentry);
			//Insert into catentry Table

		foreach($results as $rows)
		{

			//Insert into catentdesc Table

			$CatenryDes = new stdClass();
			$CatenryDes->language_id				= $rows->value;
			$CatenryDes->catentry_id				= $CatEntId;
			$CatenryDes->name						= $rowd["name_" .$rows->code];
			$CatenryDes->shortdescription			= $rowd["shortdesc_" .$rows->code];
			$CatenryDes->longdescription	 		= $rowd["longdesc_" .$rows->code];
			$CatenryDes->keyword 					= $rowd["keyword_" .$rows->code];
			$CatenryDes->thumbnail 					= $rowd["thumb_img_" .$rows->code];
			$CatenryDes->fullimage 					= $rowd["full_img_" .$rows->code];

			$result								    = JFactory::getDbo()->insertObject('catentdesc', $CatenryDes);

			//Insert into catentdesc Table


		}
			//Insert into catgpenrel Table

			$CatGpenrl = new stdClass();
			$CatGpenrl->catgroup_id		= $_POST['parent_id'];
			$CatGpenrl->catalog_id		= '1002';
			$CatGpenrl->catentry_id		= $CatEntId;

			$result = JFactory::getDbo()->insertObject('catgpenrel', $CatGpenrl);

			//Insert into catgpenrel Table

			//Insert into price Table

			$CatGpenrl = new stdClass();
			$CatGpenrl->catentry_id		= $CatEntId;
			$CatGpenrl->currency		= $_POST['currency'];
			$CatGpenrl->listprice		= $_POST['listprice'];
			$CatGpenrl->offerprice		= $_POST['offerprice'];


			$result = JFactory::getDbo()->insertObject('price', $CatGpenrl);

			//Insert into price Table


			//Insert into CatgroupGRPREL Table (catgrprel)
			/*if($_POST['parent_id'] != '0')
			{
			$CAtoGrpRel = new stdClass();
			$CAtoGrpRel->catgroup_id_parent		= $_POST['parent_id'];
			$CAtoGrpRel->catgroup_id_child		= $CatGrpId;
			$CAtoGrpRel->catalog_id				= '1002';

			$result = JFactory::getDbo()->insertObject('catgrprel', $CAtoGrpRel);
			}*/
			//Insert into CatgroupGRPREL Table (catgrprel)


		  $mainframe = JFactory::getApplication();
		  $url ="index.php?option=com_wcs_pim&view=pimproduct&layout=edit&id=".$CatEntId;
		  $mainframe->redirect($url, $msg='Saved sucessfully.', $msgType='message');

		return 'data insert';
	}
	public function DataUpdate()
	{
		$ary		=array("name","shortdesc","longdesc","keyword","thumb_img","full_img");

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
		$ProdtId								= JRequest::getvar('prodcutId');

		foreach($results as $rows)
		{

			//Update into CatgroupDescrip Table
			$CatgroupDes = new stdClass();
			$CatgroupDes->language_id				= $rows->value;
			 $CatgroupDes->catentry_id				= $ProdtId;
			$CatgroupDes->name						= $rowd["name_" .$rows->code];
			$CatgroupDes->shortdescription			= $rowd["shortdesc_" .$rows->code];
			$CatgroupDes->longdescription	 		= $rowd["longdesc_" .$rows->code];
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
			    $db->quoteName('catentry_id') . ' = ' . $ProdtId
			);

			$query->update($db->quoteName('catentdesc'))->set($fields)->where($conditions);
	     	$db->setQuery($query);
	     	$db->execute();

			//Update into CatgroupDescrip Table


		}
			//Update into catgroup Table (catgroup)
			$Catentry = new stdClass();
			$Catentry->catentry_id		= $ProdtId;
			$Catentry->partnumber		= $_POST['partnumber'];
			$Catentry->mfpartnumber		= $_POST['mfpartnumber'];
			$Catentry->mfname			= $_POST['mfname'];
			$Catentry->catenttype_id	= $_POST['catenttype'];
			$Catentry->buyable			= $_POST['buyable'];

			$result = JFactory::getDbo()->updateObject('catentry', $Catentry,catentry_id);

			//Update into catgroup Table (catgroup)

			//Update into price Table

			$PriceTbl = new stdClass();
			$PriceTbl->catentry_id		= $ProdtId;
			$PriceTbl->currency			= $_POST['currency'];
			$PriceTbl->listprice		= $_POST['listprice'];
			$PriceTbl->offerprice		= $_POST['offerprice'];


			$result = JFactory::getDbo()->updateObject('price', $PriceTbl,catentry_id);

			//Update into price Table

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
		  $url ="index.php?option=com_wcs_pim&view=pimproduct&layout=edit&id=".$ProdtId;
		  $mainframe->redirect($url, $msg='Updated sucessfully.', $msgType='message');

		return ' data update';
	}
}
