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
			jQuery('#form-category').submit(function (event) {
				
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
			jQuery('#form-category').submit(function (event) {
				
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

<div class="category-edit front-end-edit">
	<?php if (!empty($this->item->id)): ?>
		<h1>Edit <?php echo $this->item->id; ?></h1>
	<?php else: ?>
		<h1>Add</h1>
	<?php endif; ?>

	<form id="form-category"
		  action="<?php echo JRoute::_('index.php?option=com_wcs_pim&task=category.save'); ?>"
		  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

	<?php if(empty($this->item->created_by)): ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
	<?php endif; ?>
	<?php if(empty($this->item->modified_by)): ?>
		<input type="hidden" name="jform[modified_by]" value="<?php echo JFactory::getUser()->id; ?>" />
	<?php else: ?>
		<input type="hidden" name="jform[modified_by]" value="<?php echo $this->item->modified_by; ?>" />
	<?php endif; ?>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('cat_title'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('cat_title'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('cat_name'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('cat_name'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('lang'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('lang'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('short_des'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('short_des'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('long_des'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('long_des'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('thumb_img'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('thumb_img'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('full_img'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('full_img'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('keyword'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('keyword'); ?></div>
	</div>
	<div class="control-group">
		<div class="control-label"><?php echo $this->form->getLabel('parent_id'); ?></div>
		<div class="controls"><?php echo $this->form->getInput('parent_id'); ?></div>
	</div>
	<?php foreach((array)$this->item->parent_id as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="parent_id" name="jform[parent_idhidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />';
		<?php endif; ?>
	<?php endforeach; ?>				<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','wcs_pim')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>
				<?php if (!JFactory::getUser()->authorise('core.admin','wcs_pim')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-category").appendChild(input);
                    });
                </script>
             <?php endif; ?>
		<div class="control-group">
			<div class="controls">

				<?php if ($this->canSave): ?>
					<button type="submit" class="validate btn btn-primary">
						<?php echo JText::_('JSUBMIT'); ?>
					</button>
				<?php endif; ?>
				<a class="btn"
				   href="<?php echo JRoute::_('index.php?option=com_wcs_pim&task=categoryform.cancel'); ?>"
				   title="<?php echo JText::_('JCANCEL'); ?>">
					<?php echo JText::_('JCANCEL'); ?>
				</a>
			</div>
		</div>

		<input type="hidden" name="option" value="com_wcs_pim"/>
		<input type="hidden" name="task"
			   value="categoryform.save"/>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
