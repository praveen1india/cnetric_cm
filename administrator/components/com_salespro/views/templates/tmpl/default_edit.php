<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('SalesPro: template'), 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('Cancel'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('Save'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#19"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<div id="spr_main">

<div class="spr_tip">
<p><?php echo JText::_('SPR_TPL_EDIT_TIP1'); ?> /salespro/templates/<?php echo $this->template->alias; ?>/colours/. <?php echo JText::_('SPR_TPL_EDIT_TIP2'); ?></p>
</div>

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

    <fieldset class="spr_fieldset">
    
    <div class="spr_field">
    <label for="spr_templates_id"><?php echo JText::_('SPR_TPL_ID'); ?></label>
    <span id="spr_templates_id"><strong><?php echo $this->template->id; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_templates_alias"><?php echo JText::_('SPR_TPL_ALIAS'); ?></label>
    <span id="spr_templates_alias"><strong><?php echo $this->template->alias; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_templates_name"><?php echo JText::_('SPR_TPL_NAME'); ?></label>
    <input id="spr_templates_name" name="spr_templates_name" type="text" value="<?php echo $this->template->name; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_templates_params[color]"><?php echo JText::_('SPR_TPL_COLOURSCHEME'); ?></label>
    <select id="spr_templates_params[color]" name="spr_templates_params[color]">
        <?php echo $this->class->selectOptions($this->template->params->color,$this->template->colors,1); ?>
    </select>
    </div>
    
    </fieldset>

<input type="hidden" name="spr_table" value="templates" />
<input type="hidden" name="spr_id" value="<?php echo $this->template->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="templates" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
</div>
</div>