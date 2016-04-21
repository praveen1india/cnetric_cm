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

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_ucs_users.' . $this->item->id);
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_ucs_users' . $this->item->id)) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

	<div class="item_fields">
		<table class="table">
			<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_STATE'); ?></th>
			<td>
			<i class="icon-<?php echo ($this->item->state == 1) ? 'publish' : 'unpublish'; ?>"></i></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_CREATED_BY'); ?></th>
			<td><?php echo $this->item->created_by_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_COMPANY'); ?></th>
			<td><?php echo $this->item->company; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_EMAIL'); ?></th>
			<td><?php echo $this->item->email; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_ADDRESS'); ?></th>
			<td><?php echo $this->item->address; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_FIRST_NAME'); ?></th>
			<td><?php echo $this->item->first_name; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_PHONE'); ?></th>
			<td><?php echo $this->item->phone; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_PARTNER'); ?></th>
			<td><?php echo $this->item->partner; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_CITY'); ?></th>
			<td><?php echo $this->item->city; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_STATES'); ?></th>
			<td><?php echo $this->item->states; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_ZIP'); ?></th>
			<td><?php echo $this->item->zip; ?></td>
</tr>
<tr>
			<th><?php echo JText::_('COM_UCS_USERS_FORM_LBL_USER_LAST_NAME'); ?></th>
			<td><?php echo $this->item->last_name; ?></td>
</tr>

		</table>
	</div>
	<?php if($canEdit && $this->item->checked_out == 0): ?>
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_ucs_users&task=user.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_UCS_USERS_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_ucs_users.user.'.$this->item->id)):?>
									<a class="btn" href="<?php echo JRoute::_('index.php?option=com_ucs_users&task=user.remove&id=' . $this->item->id, false, 2); ?>"><?php echo JText::_("COM_UCS_USERS_DELETE_ITEM"); ?></a>
								<?php endif; ?>
	<?php
else:
	echo JText::_('COM_UCS_USERS_ITEM_NOT_LOADED');
endif;
