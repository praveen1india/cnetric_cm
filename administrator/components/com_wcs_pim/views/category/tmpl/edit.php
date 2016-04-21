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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
ini_set('error_reporting', E_ALL);
ini_set('display_errors','OFF');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'media/com_wcs_pim/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {

	js('input:hidden.parent_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('parent_idhidden')){
			js('#jform_parent_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_parent_id").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'category.cancel') {
			Joomla.submitform(task, document.getElementById('category-form'));
		}
		else {

			if (task != 'category.cancel' && document.formvalidator.isValid(document.id('category-form'))) {

				Joomla.submitform(task, document.getElementById('category-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_wcs_pim&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="category-form" class="form-validate">

	<div class="form-horizontal">
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('parent_id'); ?></div>
				<!--<div class="controls"><?php echo $this->form->getInput('parent_id'); ?></div>-->
				<div class="controls">
				<?php
				$db = JFactory::getDbo();
				$query ="Select * from catgroup";
				$db->setQuery($query);
				$results = $db->loadObjectList();

				$CatgrpId	= JRequest::getvar('id');
				?>
				<select name="parent_id">
				<option value="0">---- Top Level ----</option>
				<?php
				foreach($results as $options){

				$db = JFactory::getDbo();
				$query ="Select * from catgrpdesc CATDES " .
						"LEFT JOIN catgrprel CATRPRL ON CATDES.catgroup_id = CATRPRL.catgroup_id_child " .
						"where CATRPRL.catgroup_id_child = ".$options->catgroup_id;
				$db->setQuery($query);
				$Parents = $db->loadObject();
				 ?>
					<option value="<?php echo $options->catgroup_id;?>" <?php echo($CatgrpId==$options->catgroup_id)?'selected':'';?>><?php echo (isset($Parents->catgroup_id))? ' - - - ':''; echo $options->identifier;?></option>
				<?php } ?>
				</select>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label">Identifier</div>
				<div class="controls"><input type="text" class="required"  name="identifier" value="<?php echo $this->Data_Roews->identifier; ?>" /></div>
			</div>
		<?php //echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php /*echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_WCS_PIM_TITLE_CATEGORY', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

									<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php }
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>
				<?php if(empty($this->item->modified_by)){ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php }
				else{ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo $this->item->modified_by; ?>" />

				<?php } ?>			<div class="control-group">
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


			<?php
				foreach((array)$this->item->parent_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="parent_id" name="jform[parent_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>

					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>

		<?php echo JHtml::_('bootstrap.endTab'); */?>

		<?php /*if (JFactory::getUser()->authorise('core.admin','wcs_pim')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('JGLOBAL_ACTION_PERMISSIONS_LABEL', true)); ?>
		<?php echo $this->form->getInput('rules'); ?>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; */?>
	<?php
		$db = JFactory::getDbo();
		$query ="Select * from #__pim_language";
		$db->setQuery($query);
		$results = $db->loadObjectList();

		$query1 ="Select * from #__pim_language";
		$db->setQuery($query1);
		$rres = $db->loadRow();

		echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => $rres[1]));
		foreach($results as $lang) {

			if($id= JRequest::getvar('id')){
			$Data_catgrpdesc					= Wcs_pimHelpersWcs_pim::GetLangData($lang->value);
			}
	?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', $lang->language, $lang->language); ?>
		<?php echo $lang->language; ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
				<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php }
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>
				<?php if(empty($this->item->modified_by)){ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php }
				else{ ?>
					<input type="hidden" name="jform[modified_by]" value="<?php echo $this->item->modified_by; ?>" />

				<?php } ?>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('cat_name'); ?></div>
				<div class="controls"><input type="text" class="required"  name="cat_name_<?php echo $lang->code;?>" value="<?php echo $Data_catgrpdesc->name;?>" /></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('short_des'); ?></div>
				<div class="controls"><textarea  name="short_des_<?php echo $lang->code;?>"><?php echo $Data_catgrpdesc->shortdescription;?></textarea></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('long_des'); ?></div>
				<div class="controls"><textarea  name="long_des_<?php echo $lang->code;?>"><?php echo $Data_catgrpdesc->longdescription;?></textarea></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('thumb_img'); ?></div>
				<div class="controls">
				<div class="input-prepend input-append">
							<div class="media-preview add-on">
							<span title="" class="hasTipPreview"><span class="icon-eye"></span></span>
							</div>
								<input type="text" class="input-small hasTipImgpath" title="" readonly="readonly" value="<?php echo $Data_catgrpdesc->thumbnail;?>" id="jform_thumb_img_<?php echo $lang->code;?>" name="thumb_img_<?php echo $lang->code;?>">
							<a rel="{handler: 'iframe', size: {x: 800, y: 500}}" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_wcs_pim&amp;author=&amp;fieldid=jform_thumb_img_<?php echo $lang->code;?>&amp;folder=" title="Select" class="modal btn">
							Select</a><a onclick="
							jInsertFieldValue('', 'jform_thumb_img_<?php echo $lang->code;?>');
							return false;
							" href="#" title="" class="btn hasTooltip" data-original-title="Clear">
							<span class="icon-remove"></span></a>
							</div>
							<?php if($Data_catgrpdesc->thumbnail) { ?><img src="<?php echo JURI::Root().$Data_catgrpdesc->thumbnail;?>"  style="width: 30px;"><?php }?>



				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('full_img'); ?></div>
				<div class="controls" style="display: none;">
				<?php echo $this->form->getInput('full_img'); ?>
				</div>
				<div class="controls">
					<div class="input-prepend input-append">
					<div class="media-preview add-on">
					<span title="" class="hasTipPreview"><span class="icon-eye"></span></span>
					</div>
						<input type="text" class="input-small hasTipImgpath" title="" readonly="readonly" value="<?php echo $Data_catgrpdesc->fullimage;?>" id="jform_full_imgs_<?php echo $lang->code;?>" name="full_img_<?php echo $lang->code;?>">
					<a rel="{handler: 'iframe', size: {x: 800, y: 500}}" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_wcs_pim&amp;author=&amp;fieldid=jform_full_imgs_<?php echo $lang->code;?>&amp;folder=" title="Select" class="modal btn">
					Select</a><a onclick="
					jInsertFieldValue('', 'jform_full_imgs_<?php echo $lang->code;?>');
					return false;
					" href="#" title="" class="btn hasTooltip" data-original-title="Clear">
					<span class="icon-remove"></span></a>
					</div>
					<?php if($Data_catgrpdesc->fullimage) { ?><img src="<?php echo JURI::Root().$Data_catgrpdesc->fullimage;?>"  style="width: 30px;"><?php }?>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('keyword'); ?></div>
				<div class="controls"><textarea id="jform_keyword" name="keyword_<?php echo $lang->code;?>"><?php echo $Data_catgrpdesc->keyword;?></textarea></div>
			</div>


			<?php
				foreach((array)$this->item->parent_id as $value):
					if(!is_array($value)):
						echo '<input type="hidden" class="parent_id" name="jform[parent_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>

					<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
					<?php endif; ?>
				</fieldset>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php }
	 ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="catgrp_id" value="<?php echo $this->Data_Roews->catgroup_id; ?>" />
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
