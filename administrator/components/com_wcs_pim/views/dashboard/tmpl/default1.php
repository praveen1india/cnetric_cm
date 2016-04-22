<link href='https://fonts.googleapis.com/css?family=Jaldi:400,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="<?php echo JURI::Root();?>multi-level-accordion-menu/css/style.css"> <!-- Resource style -->
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
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_wcs_pim');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_wcs_pim&task=categories.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
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

<form action="<?php echo JRoute::_('index.php?option=com_wcs_pim&view=categories'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		<ul class="cd-accordion-menu animated">
				<!-- Acordion Mneu-->
				<?php

				$db = JFactory::getDbo();
				$query ="Select CatGprp.*,CATRPRL.* from catgroup  CatGprp " .
						"LEFT JOIN catgrprel CATRPRL ON CatGprp.catgroup_id = CATRPRL.catgroup_id_child " .
						"where CATRPRL.catgroup_id_child IS NULL ";
				$db->setQuery($query);
				$Parents_1 = $db->loadObjectlist();
		//		print_r($item);
		//		$item->catgroup_id;
				foreach ($Parents_1 as $i => $item_1) {
				?>

				<li class="has-children">
				<input type="checkbox" name ="<?php echo $item_1->catgroup_id;?>" id="<?php echo $item_1->catgroup_id;?>">
					<label for="<?php echo $item_1->catgroup_id;?>"><?php echo $item_1->identifier;?></label>
					<ul>
					<?php
					$db = JFactory::getDbo();
					$query ="Select CatGprp.*,CATRPRL.* from catgroup CatGprp LEFT JOIN catgrprel CATRPRL ON CatGprp.catgroup_id = CATRPRL.catgroup_id_child where CATRPRL.catgroup_id_parent =".$item_1->catgroup_id;
					$db->setQuery($query);
					$Parents_2 = $db->loadObjectlist();
					foreach ($Parents_2 as $i => $item_2) {
					?>
					<li class="has-children">
					<input type="checkbox" name ="<?php echo $item_2->catgroup_id;?>" id="<?php echo $item_2->catgroup_id;?>">
					<label for="<?php echo $item_2->catgroup_id;?>"><?php echo $item_2->identifier;?></label>
					<ul>
					<?php
					$db = JFactory::getDbo();
					$query ="Select CatGprp.*,CATRPRL.* from catgroup CatGprp LEFT JOIN catgrprel CATRPRL ON CatGprp.catgroup_id = CATRPRL.catgroup_id_child where CATRPRL.catgroup_id_parent =".$item_2->catgroup_id;
					$db->setQuery($query);
					$Parents_3 = $db->loadObjectlist();

					foreach ($Parents_3 as $i => $item_3) {
					?>
					<li><a href="#0"><?php echo $item_3->identifier;?></a></li>
					<?php } ?>
					</ul>
				</li>
					<?php } ?>
					</ul>
					<?php } ?>
					</li>
			</ul>


		<!-- Acordion Mneu-->

	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

			<div id="filter-bar" class="btn-toolbar">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search"
						   class="element-invisible">
						<?php echo JText::_('JSEARCH_FILTER'); ?>
					</label>
					<input type="text" name="filter_search" id="filter_search"
						   placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
						   value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
						   title="<?php echo JText::_('JSEARCH_FILTER'); ?>"/>
				</div>
				<div class="btn-group pull-left">
					<button class="btn hasTooltip" type="submit"
							title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
						<i class="icon-search"></i></button>
					<button class="btn hasTooltip" id="clear-search-button" type="button"
							title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>">
						<i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="limit"
						   class="element-invisible">
						<?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
				<div class="btn-group pull-right hidden-phone">
					<label for="directionTable"
						   class="element-invisible">
						<?php echo JText::_('JFIELD_ORDERING_DESC'); ?>
					</label>
					<select name="directionTable" id="directionTable" class="input-medium"
							onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
						<option value="asc" <?php echo $listDirn == 'asc' ? 'selected="selected"' : ''; ?>>
							<?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?>
						</option>
						<option value="desc" <?php echo $listDirn == 'desc' ? 'selected="selected"' : ''; ?>>
							<?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?>
						</option>
					</select>
				</div>
				<div class="btn-group pull-right">
					<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
					<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
						<option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
						<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
					</select>
				</div>
			</div>
			<div class="clearfix"></div>
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
				<?php echo JHtml::_('grid.sort',  'COM_WCS_PIM_CATEGORIES_ID', 'a.`id`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'Cat_Idendifier', 'a.`cat_title`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'Cat Name_Eng', 'a.`cat_name`', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_WCS_PIM_CATEGORIES_THUMB_IMG', 'a.`thumb_img`', $listDirn, $listOrder); ?>
				</th>


				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_wcs_pim');
					$canEdit    = $user->authorise('core.edit', 'com_wcs_pim');
					$canCheckin = $user->authorise('core.manage', 'com_wcs_pim');
					$canChange  = $user->authorise('core.edit.state', 'com_wcs_pim');
					?>
					<?php
					$db = JFactory::getDbo();
					$query ="Select * from catgrpdesc CATDES " .
							"LEFT JOIN catgrprel CATRPRL ON CATDES.catgroup_id = CATRPRL.catgroup_id_child " .
							"where CATRPRL.catgroup_id_child = ".$item->catgroup_id;
					$db->setQuery($query);
					$Parents = $db->loadObject();
					//echo "<pre>";
					//print_r($Parents);
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
							<?php echo JHtml::_('grid.id', $i, $item->catgroup_id); ?>
						</td>
						<?php if (isset($this->items[0]->state)): ?>
							<td class="center">
	<?php echo JHtml::_('jgrid.published', $item->state, $i, 'categories.', $canChange, 'cb'); ?>
</td>
						<?php endif; ?>

										<td>

					<?php echo $item->catgroup_id; ?>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'categories.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_wcs_pim&task=category.edit&id='.(int) $item->catgroup_id); ?>">
					<?php echo (isset($Parents->catgroup_id))? ' - - - ':''; echo $this->escape($item->identifier); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->identifier); ?>
				<?php endif; ?>
				</td>
				<td>

					<?php
					$db = JFactory::getDbo();
					$query ="Select * from catgrpdesc where catgroup_id = ". $item->catgroup_id." and language_id= -1";
					$db->setQuery($query);
					$results = $db->loadObject();
					echo ($results->name)? $results->name:''; ?>
				</td>
				<td>
					<?php 	if($results->thumbnail) { ?><img src="<?php echo JURI::Root().$results->thumbnail;?>"  style="width: 30px;"><?php }?>
					<?php /*echo $item->thumb_img;*/ ?>
				</td>


					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
</form>
