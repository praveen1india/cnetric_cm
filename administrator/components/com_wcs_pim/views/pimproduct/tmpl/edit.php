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
JHTML::_('behavior.modal');

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
		if (task == 'pimproduct.cancel') {
			Joomla.submitform(task, document.getElementById('pimproduct-form'));
		}
		else {

			if (task != 'pimproduct.cancel' && document.formvalidator.isValid(document.id('pimproduct-form'))) {

				Joomla.submitform(task, document.getElementById('pimproduct-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
	function jInsertFieldValue(value, id) {
		var $ = jQuery.noConflict();
		var old_value = $("#" + id).val();
		if (old_value != value) {
			var $elem = $("#" + id);
			$elem.val(value);
			$elem.trigger("change");
			if (typeof($elem.get(0).onchange) === "function") {
				$elem.get(0).onchange();
			}
			function jModalClose() {
			SqueezeBox.close();
		}
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_wcs_pim&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="pimproduct-form" class="form-validate">

	<div class="form-horizontal">

	<div class="control-group">
				<div class="control-label">Category</div>
				<div class="controls">
				<?php
				$db = JFactory::getDbo();
				$query ="Select * from catgroup";
				$db->setQuery($query);
				$results = $db->loadObjectList();


				//get cat rel
				$CatentryId	= JRequest::getvar('id');
				if($CatentryId){
				$query ="Select catgroup_id from catgpenrel where catentry_id=".$CatentryId;
				$db->setQuery($query);
				$Catgrp = $db->loadObject();
				}
				?>
				<select name="parent_id">
				<option value="0">---- Select Category ----</option>
				<?php
				foreach($results as $options){

				$db = JFactory::getDbo();
				$query ="Select * from catgrpdesc CATDES " .
						"LEFT JOIN catgrprel CATRPRL ON CATDES.catgroup_id = CATRPRL.catgroup_id_child " .
						"where CATRPRL.catgroup_id_child = ".$options->catgroup_id;
				$db->setQuery($query);
				$Parents = $db->loadObject();
				 ?>
					<option value="<?php echo $options->catgroup_id;?>" <?php echo($Catgrp->catgroup_id==$options->catgroup_id)?'selected':'';?>><?php echo (isset($Parents->catgroup_id))? ' - - - ':''; echo $options->identifier;?></option>
				<?php } ?>
				</select>
				</div>
			</div>

	<div class="control-group">
				<div class="control-label">PartNumber</div>
				<div class="controls"><input type="text" value="<?php echo $this->Data_Roews->partnumber; ?>" name="partnumber" class="required" aria-required="true" required="required"></div>
	</div>
	<div class="control-group">
				<div class="control-label">Manufacture PartNumber</div>
				<div class="controls"><input type="text" value="<?php echo $this->Data_Roews->mfpartnumber; ?>" name="mfpartnumber" class="required"  required="required"></div>
	</div>
	<div class="control-group">
				<div class="control-label">Manufacture Name</div>
				<div class="controls"><input type="text" value="<?php echo $this->Data_Roews->mfname; ?>" name="mfname" class="" ></div>
	</div>
	<div class="control-group">
				<div class="control-label">Catentrytype Id</div>
				<div class="controls"><input type="text" value="<?php echo $this->Data_Roews->catenttype_id; ?>" name="catenttype" class=""></div>
	</div>
	<div class="control-group">
				<div class="control-label">Mark for delete</div>
				<div class="controls"><input type="text" value="<?php echo $this->Data_Roews->markfordelete; ?>" name="delete" class="" ></div>
	</div>
	<div class="control-group">
				<div class="control-label">IS buyable</div>
				<div class="controls">
				<input type="radio" name="buyable" value="1" <?php echo ($this->Data_Roews->buyable =='1')?'checked':'';?>> Yes
  				<input type="radio" name="buyable" value="0" <?php echo ($this->Data_Roews->buyable =='0')?'checked':'';?>> No<br>
				</div>
	</div>

	<?php
	if($id= JRequest::getvar('id')){
	$Data_price					= Wcs_pimHelpersWcs_pim::GetpriceData();
	}
	?>
	<div class="control-group">
				<div class="control-label">List Price</div>
				<div class="controls"><input type="text" value="<?php echo $Data_price->listprice;?>" name="listprice" class=""></div>
	</div>
	<div class="control-group">
				<div class="control-label">Offer Price</div>
				<div class="controls"><input type="text" value="<?php echo $Data_price->offerprice;?>" name="offerprice" class=""></div>
	</div>
	<div class="control-group">
				<div class="control-label">Currency</div>
				<div class="controls"><input type="text" value="USD" name="currency" class=""></div>
	</div>

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
				$Data_catentrydesc					= Wcs_pimHelpersWcs_pim::GetCatentryDescLangData($lang->value);
				}
	?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', $lang->language, $lang->language); ?>
		<?php echo $lang->language; ?>

	<div class="control-group">
				<div class="control-label">Name</div>
				<div class="controls"><input type="text" value="<?php echo $Data_catentrydesc->name; ?>" name="name_<?php echo $lang->code?>" class=""></div>
	</div>
	<div class="control-group">
				<div class="control-label"> Thumbail Image</div>
				<div class="controls">
				<div class="input-prepend input-append">
							<div class="media-preview add-on">
							<span title="" class="hasTipPreview"><span class="icon-eye"></span></span>
							</div>
								<input type="text" class="input-small hasTipImgpath" title="" readonly="readonly" value="<?php echo $Data_catentrydesc->thumbnail; ?>" id="thumb_img_<?php echo $lang->code?>" name="thumb_img_<?php echo $lang->code?>">
							<a rel="{handler: 'iframe', size: {x: 800, y: 500}}" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_wcs_pim&amp;author=&amp;fieldid=thumb_img_<?php echo $lang->code?>&amp;folder=" title="Select" class="modal btn">
							Select</a><a onclick="
							jInsertFieldValue('', 'thumb_img_<?php echo $lang->code?>');
							return false;
							" href="#" title="" class="btn hasTooltip" data-original-title="Clear">
							<span class="icon-remove"></span></a>
							</div>
							<?php if($Data_catentrydesc->fullimage) { ?><img src="<?php echo JURI::Root().$Data_catentrydesc->thumbnail;?>"  style="width: 30px;"><?php }?>
				</div>
			</div>
		<div class="control-group">
				<div class="control-label"> Full Image</div>
				<div class="controls">
				<div class="input-prepend input-append">
							<div class="media-preview add-on">
							<span title="" class="hasTipPreview"><span class="icon-eye"></span></span>
							</div>
								<input type="text" class="input-small hasTipImgpath" title="" readonly="readonly" value="<?php echo $Data_catentrydesc->fullimage; ?>" id="full_img_<?php echo $lang->code?>" name="full_img_<?php echo $lang->code?>">
							<a rel="{handler: 'iframe', size: {x: 800, y: 500}}" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_wcs_pim&amp;author=&amp;fieldid=full_img_<?php echo $lang->code?>&amp;folder=" title="Select" class="modal btn">
							Select</a><a onclick="
							jInsertFieldValue('', 'full_img_<?php echo $lang->code?>');
							return false;
							" href="#" title="" class="btn hasTooltip" data-original-title="Clear">
							<span class="icon-remove"></span></a>
							</div>
							<?php if($Data_catentrydesc->fullimage) { ?><img src="<?php echo JURI::Root().$Data_catentrydesc->fullimage;?>"  style="width: 30px;"><?php }?>
				</div>
		</div>
	<div class="control-group">
				<div class="control-label">Short Description</div>
				<div class="controls"><input type="text" value="<?php echo $Data_catentrydesc->shortdescription; ?>" name="shortdesc_<?php echo $lang->code?>" class=""></div>
	</div>
	<div class="control-group">
				<div class="control-label">Long Description</div>
				<div class="controls">
				<textarea name="longdesc_<?php echo $lang->code?>"><?php echo $Data_catentrydesc->longdescription; ?></textarea>
				</div>
	</div>

	<div class="control-group">
				<div class="control-label">Keyword</div>
				<div class="controls">
				<textarea name="keyword_<?php echo $lang->code?>"><?php echo $Data_catentrydesc->keyword; ?></textarea></div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); }
	echo JHtml::_('bootstrap.endTabSet');  ?>

		<input type="hidden" name="prodcutId" value="<?php echo $id;?>"/>
		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
