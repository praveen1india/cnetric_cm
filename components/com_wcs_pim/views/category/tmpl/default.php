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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_wcs_pim.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_wcs_pim' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

	<div class="item_fields">
		<table class="table">
			<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_MODIFIED_BY'); ?></th>
			<td><?php echo $this->item->modified_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_CAT_TITLE'); ?></th>
			<td><?php echo $this->item->cat_title; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_CAT_NAME'); ?></th>
			<td><?php echo $this->item->cat_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_LANG'); ?></th>
			<td><?php echo $this->item->lang; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_SHORT_DES'); ?></th>
			<td><?php echo $this->item->short_des; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_LONG_DES'); ?></th>
			<td><?php echo $this->item->long_des; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_THUMB_IMG'); ?></th>
			<td><?php echo $this->item->thumb_img; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_FULL_IMG'); ?></th>
			<td><?php echo $this->item->full_img; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_KEYWORD'); ?></th>
			<td><?php echo $this->item->keyword; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_WCS_PIM_FORM_LBL_CATEGORY_PARENT_ID'); ?></th>
			<td><?php echo $this->item->parent_id; ?></td>
</tr>

		</table>
	</div>
	<?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_wcs_pim&task=category.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_WCS_PIM_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_wcs_pim.category.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_wcs_pim&task=category.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_WCS_PIM_DELETE_ITEM"); ?></a>
								<?php endif; ?>
	<?php
else:
	echo JText::_('COM_WCS_PIM_ITEM_NOT_LOADED');
endif;
