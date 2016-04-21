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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_wcs_pim/assets/css/wcs_pim.css');
$document->addStyleSheet(JUri::root() . 'media/com_wcs_pim/css/list.css');

$user      = JFactory::getUser();
$userId    = $user->get('id');

?>
<script type="text/javascript">
	Joomla.orderTable = function () {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	};

	jQuery(document).ready(function () {
		jQuery('#clear-search-button').on('click', function () {
			jQuery('#filter_search').val('');
			jQuery('#adminForm').submit();
		});
	});
</script>

<?php

// Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar))
{
	$this->sidebar .= $this->extra_sidebar;
}

?>
<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<form id="adminForm" name="adminForm" method="post" action="/projects_c/jshop/administrator/index.php?option=com_wcs_pim&view=categories" novalidate="">
	<table class="table table-striped" id="categoryList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
						</th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
	<?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
</th>
					<?php endif; ?>

									<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_WCS_PIM_CATEGORIES_ID', 'a.`id`'); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'Product_ID', 'a.`cat_title`'); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'Product Name_Eng', 'a.`cat_name`'); ?>
				</th>


				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php /*echo $this->pagination->getListFooter();*/ ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					?>
					<?php
//					$db = JFactory::getDbo();
//					$query ="Select * from catgrpdesc CATDES " .
//							"LEFT JOIN catgrprel CATRPRL ON CATDES.catgroup_id = CATRPRL.catgroup_id_child " .
//							"where CATRPRL.catgroup_id_child = ".$item->catgroup_id;
//					$db->setQuery($query);
//					$Parents = $db->loadObject();
//					//echo "<pre>";
//					//print_r($Parents);
					 ?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif; ?>
									<span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
										  title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
									<input type="text" style="display:none" name="order[]" size="5"
										   value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
								<?php else : ?>
									<span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td class="hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->catentry_id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
	<?php echo JHtml::_('jgrid.published', $item->state, $i, 'categories.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

				<td>

					<?php echo $item->catentry_id; ?>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', $canCheckin); ?>
				<?php endif; ?>
					<a href="<?php echo JRoute::_('index.php?option=com_wcs_pim&view=pimproduct&layout=edit&id='.(int) $item->catentry_id); ?>">
					<?php echo (isset($Parents->catentry_id))? ' - - - ':''; echo $this->escape($item->catentry_id); ?></a>
				</td>
				<td>

					<?php
					$db = JFactory::getDbo();
					$query ="Select * from catentdesc where catentry_id = ". $item->catentry_id." and language_id= -1";
					$db->setQuery($query);
					$results = $db->loadObject();
					echo ($results->name)? $results->name:''; ?>
				</td>


					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

	<input type="hidden" value="pimproduct.add" name="task">
	</form>
	</div>



