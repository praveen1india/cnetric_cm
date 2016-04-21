<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_(JText::_('COM_SALESPRO').': '.JText::_('SPR_CON_HEADING')), 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
$editor = JFactory::getEditor();
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#17"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_CON_HEADING'); ?></h1>
</div>

<form action="" method="post" name="adminForm" id="adminForm">
<div id="spr_tabs">
    <div class="spr_tabs">
    
        <ul>
            <li><a href="#details"><?php echo JText::_('SPR_CON_GENERALTAB'); ?></a></li>
            <li><a href="#localisation"><?php echo JText::_('SPR_CON_LOCALISATIONTAB'); ?></a></li>
            <li><a href="#uploads"><?php echo JText::_('SPR_CON_IMAGESFILES'); ?></a></li>
            <li><a href="#thankyou"><?php echo JText::_('SPR_CON_THANKSTAB'); ?></a></li>
        </ul>
                
            <div id="details">
            <div class="spr_notab">
<fieldset class="spr_fieldset">
<div class="spr_field">
<label for="spr_config_core[name]"><?php echo JText::_('SPR_CON_SHOPNAME'); ?></label>
<input id="spr_config_core[name]" name="spr_config_core[name]" type="text" value="<?php echo $this->core->name; ?>" />
</div>

<div class="spr_field">
<label for="spr_config_core[hp_title]"><?php echo JText::_('SPR_CON_HOMETITLE'); ?></label>
<input id="spr_config_core[hp_title]" name="spr_config_core[hp_title]" type="text" value="<?php echo $this->core->hp_title; ?>" />
</div>

<div class="spr_field">
<label for="spr_config_core[cart_action]"><?php echo JText::_('SPR_CON_CARTBEHAVIOUR'); ?></label>
<select id="spr_config_core[cart_action]" name="spr_config_core[cart_action]" class="spr_select">
<option value='1'><?php echo JText::_('SPR_CON_CARTBEHAVIOUR1'); ?></option>
<option value='2' <?php if($this->core->cart_action === '2') echo 'selected="selected"'; ?>><?php echo JText::_('SPR_CON_CARTBEHAVIOUR2'); ?></option>
</select>
</div>

<div class="spr_field">
<label for="spr_config_core[stock_empty]"><?php echo JText::_('SPR_CON_OOS'); ?></label>
<select id="spr_config_core[stock_empty]" name="spr_config_core[stock_empty]" class="spr_select">
<?php echo sprConfig::_options($this->core->stock_empty,'stock'); ?>
</select>
</div>

<div class="spr_field">
<label for="spr_config_core[ssl]"><?php echo JText::_('SPR_CON_SSL'); ?></label>
<select id="spr_config_core[ssl]" name="spr_config_core[ssl]" class="spr_select">
<?php echo sprConfig::_options($this->core->ssl,'noyes'); ?>
</select>
</div>

<div class="spr_field">
<label for="spr_config_core[tc]"><?php echo JText::_('SPR_CON_TC'); ?></label>
<select id="spr_config_core[tc]" name="spr_config_core[tc]" class="spr_select">
<?php echo sprConfig::_options($this->core->tc,'noyes'); ?>
</select>
</div>

<div class="spr_field">
    <label for="spr_config_core[tcpage]"><?php echo JText::_('SPR_CON_TANDC_PAGE'); ?></label>
    <input class="input-medium" id="spr_config_core[tcpage]" name="spr_config_core[tcpage]" value="<?php echo $this->core->tcpage; ?>" type="hidden" />
    <input class="input-medium" id="tcpage_name" value="<?php echo $this->tcpage; ?>" disabled="disabled" type="text" style="width: 150px;" />
    <a data-original-title="<?php echo JText::_('SPR_CON_ARTICLE_SELECT'); ?>" class="modal btn hasTooltip" title="" href="index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=salesPro_selectArticle" rel="{handler: 'iframe', size: {x: 800, y: 450}}"><i class="icon-file"></i> <?php echo JText::_('SPR_CON_SELECT'); ?></a>
</div>

</fieldset>

</div>
</div>

<div id="localisation">
<div class="spr_notab">
<fieldset class="spr_fieldset">

<div class="spr_field">
<label for="spr_config_core[taxes]"><?php echo JText::_('SPR_CON_PRICES'); ?></label>
<select id="spr_config_core[taxes]" name="spr_config_core[taxes]" class="spr_select">
<option value='1'><?php echo JText::_('SPR_CON_PRICES1'); ?></option>
<option value='2' <?php if($this->core->taxes === '2') echo 'selected="selected"'; ?>><?php echo JText::_('SPR_CON_PRICES2'); ?></option>
</select>
</div>

<div class="spr_field">
<label for="spr_config_units[size]"><?php echo JText::_('SPR_CON_SIZE'); ?></label>
<select id="spr_config_units[size]" name="spr_config_units[size]" class="spr_select">
<?php echo sprConfig::_options($this->units->size,'size'); ?>
</select>
</div>

<div class="spr_field">
<label for="spr_config_core[weight]"><?php echo JText::_('SPR_CON_WEIGHT'); ?></label>
<select id="spr_config_core[weight]" name="spr_config_core[weight]" class="spr_select">
<?php echo sprConfig::_options($this->core->weight,'weight'); ?>
</select>
</div>
</fieldset>

</div>
</div>

<div id="uploads">
<div class="spr_notab">
<fieldset class="spr_fieldset">

<h2><?php echo JText::_('SPR_CON_IMAGES'); ?></h2>

<div class="spr_field">
<label for="spr_config_images[crop]"><?php echo JText::_('SPR_CON_IMGCROP'); ?></label>
<select id="spr_config_images[crop]" name="spr_config_images[crop]" class="spr_select">
<?php echo sprConfig::_options($this->images->crop,'imagecrop'); ?>
</select>
</div>

<div class="spr_field">
<label for="spr_config_images[bg]"><?php echo JText::_('SPR_CON_IMGBG'); ?></label>
<input id="spr_config_images[bg]" name="spr_config_images[bg]" placeholder="<?php echo JText::_('SPR_CON_IMGBG_TIP'); ?>" value="<?php echo $this->images->bg; ?>" />
</div>

<div class="spr_field">
<label for="spr_config_images[valid]"><?php echo JText::_('SPR_CON_VALIDIMGS'); ?></label>
<input id="spr_config_images[valid]" name="spr_config_images[valid]" type="text" value="<?php echo $this->images->valid; ?>" placeholder="e.g. jpg,gif,png" />
</div>

<div class="spr_field">
<label for="spr_config_images[loc]"><?php echo JText::_('SPR_CON_IMGLOC'); ?></label>
<input id="spr_config_images[loc]" name="spr_config_images[loc]" type="text" value="<?php echo $this->images->loc; ?>" placeholder="e.g. images/SalesPro/" />
</div>

<h2><?php echo JText::_('SPR_CON_FILES'); ?></h2>

<div class="spr_field">
<label for="spr_config_files[valid]"><?php echo JText::_('SPR_CON_VALIDFILES'); ?></label>
<input id="spr_config_files[valid]" name="spr_config_files[valid]" type="text" value="<?php echo $this->files->valid; ?>" placeholder="e.g. html,pdf,doc,docx" />
</div>

<div class="spr_field">
<label for="spr_config_files[loc]"><?php echo JText::_('SPR_CON_FILELOC'); ?></label>
<input id="spr_config_files[loc]" name="spr_config_files[loc]" type="text" value="<?php echo $this->files->loc; ?>" placeholder="e.g. dls/" />
</div>
</fieldset>
</div>

</div>

<div id="thankyou">
<div class="spr_notab">
<fieldset class="spr_fieldset">
<div class="spr_field">
<label for="spr_config_thankyou[title]"><?php echo JText::_('SPR_CON_THANKSTITLE'); ?></label>
<input id="spr_config_thankyou[title]" name="spr_config_thankyou[title]" type="text" value="<?php echo $this->thankyou->title; ?>" />
</div>

<div class="spr_field">
<label for="spr_config_thankyou[content]"><?php echo JText::_('SPR_CON_THANKSCONTENT'); ?></label>
<div class="editor">
<?php echo $editor->display('spr_config_thankyou[content]', $this->thankyou->content, 660, '400', '70', '15',true); ?>
</div>
</div>
</fieldset>
</div>
</div>

    </div> 
</div>                

<input type="hidden" name="spr_config_core[show_welcome]" value="<?php echo $this->core->show_welcome; ?>" />
<input type="hidden" name="spr_table" id="spr_table" value="config" />
<input type="hidden" name="spr_id" value="1" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="config" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>
