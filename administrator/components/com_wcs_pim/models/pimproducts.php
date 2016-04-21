<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Wcs_pim
 * @author     Praveen <mantu.mnt@gmail.com>
 * @copyright  2016 demo
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Wcs_pim records.
 *
 * @since  1.6
 */
class Wcs_pimModelPimproducts extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.`id`',
				'ordering', 'a.`ordering`',
				'state', 'a.`state`',
				'created_by', 'a.`created_by`',
				'modified_by', 'a.`modified_by`',
				'cat_title', 'a.`cat_title`',
				'cat_name', 'a.`cat_name`',
				'lang', 'a.`lang`',
				'short_des', 'a.`short_des`',
				'long_des', 'a.`long_des`',
				'thumb_img', 'a.`thumb_img`',
				'full_img', 'a.`full_img`',
				'keyword', 'a.`keyword`',
				'parent_id', 'a.`parent_id`',
			);
		}

		parent::__construct($config);
	}
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		$db	= $this->getDbo();
		$query	= $db->getQuery(true);

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();

		/*foreach ($items as $oneItem) {
					$oneItem->lang = JText::_('COM_WCS_PIM_CATEGORIES_LANG_OPTION_' . strtoupper($oneItem->lang));

			if (isset($oneItem->parent_id)) {
				$values = explode(',', $oneItem->parent_id);

				$textValue = array();
				foreach ($values as $value){
					if(!empty($value)){
						$db = JFactory::getDbo();
						$query = "select id from #__wcs_pim_categories HAVING select id from #__wcs_pim_categories LIKE '" . $value . "'";
						$db->setQuery($query);
						$results = $db->loadObject();
						if ($results) {
							$textValue[] = $results->id;
						}
					}
				}

			$oneItem->parent_id = !empty($textValue) ? implode(', ', $textValue) : $oneItem->parent_id;

			}
		}*/
		//Manual Data List
		$db = JFactory::getDbo();
		$query = "select * from catentry";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		//Manual Data List

		//print_r($results); exit;
		return $results;
	}

}
