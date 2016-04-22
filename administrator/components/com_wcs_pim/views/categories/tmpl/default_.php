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




<div id="content">

<div class="text-center">
	<a class="quick-btn" href="index.php?option=com_wcs_pim&view=pimproducts">
		<i class="fa fa-product-hunt fa-2x"></i>
		<span>Product management</span>
		<span class="label label-default">15000</span>
	</a>
	
	<a class="quick-btn" href="#">
		<i class="fa fa-folder fa-2x"></i>
		<span>Category management</span>
		<span class="label label-default">120</span>
	</a>
	
	<a class="quick-btn" href="#">
		<i class="fa fa-tasks fa-2x"></i>
		<span>Manage Attributes</span>
		<span class="label label-default">120</span>
	</a>
	
	<a class="quick-btn" href="#">
		<i class="fa fa-upload	 fa-2x"></i>
		<span>Bulk upload</span>
		<span class="label label-default">120</span>
	</a>
</div>

</div>
