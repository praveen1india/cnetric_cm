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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_wcs_pim', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_wcs_pim/js/form.js');

/**/
?>
<script type="text/javascript">
	if (jQuery === 'undefined') {
		document.addEventListener("DOMContentLoaded", function (event) {
			jQuery('#form-pimproduct').submit(function (event) {
				
			});

			
			jQuery('input:hidden.parent_id').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('parent_idhidden')){
					jQuery('#jform_parent_id option[value="' + jQuery(this).val() + '"]').attr('selected', 'selected');
				}
			});
					jQuery("#jform_parent_id").trigger("liszt:updated");
		});
	} else {
		jQuery(document).ready(function () {
			jQuery('#form-pimproduct').submit(function (event) {
				
			});

			
			jQuery('input:hidden.parent_id').each(function(){
				var name = jQuery(this).attr('name');
				if(name.indexOf('parent_idhidden')){
					jQuery('#jform_parent_id option[value="' + jQuery(this).val() + '"]').attr('selected', 'selected');
				}
			});
					jQuery("#jform_parent_id").trigger("liszt:updated");
		});
	}
</script>

<div class="pimproduct-edit front-end-edit">
	<?php if (!empty($this->item->id)): ?>
		<h1>Edit <?php echo $this->item->id; ?></h1>
	<?php else: ?>
		<h1>Add</h1>
	<?php endif; ?>

	<form id="form-pimproduct"
		  action="<?php echo JRoute::_('index.php?option=com_wcs_pim&task=pimproduct.save'); ?>"
		  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		
		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
					<button type="submit" class="validate btn btn-primary">
						<?php echo JText::_('JSUBMIT'); ?>
					</button>
				<?php endif; ?>
				<a class="btn"
				   href="<?php echo JRoute::_('index.php?option=com_wcs_pim&task=pimproductform.cancel'); ?>"
				   title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>

		<input type="hidden" name="option" value="com_wcs_pim"/>
		<input type="hidden" name="task"
			   value="pimproductform.save"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
