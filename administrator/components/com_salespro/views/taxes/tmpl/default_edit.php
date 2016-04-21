<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_(JText::_('COM_SALESPRO').': '.JText::_('SPR_TAX_HEADING')), 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#18"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<div id="spr_main">

<div class="spr_tip">
<p><?php echo JText::_('SPR_TAX_EDIT_TIP'); ?></p>
</div>

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

    <fieldset class="spr_fieldset">
    
    <div class="spr_field">
    <label for="spr_payment_methods_id"><?php echo JText::_('SPR_TAX_ID'); ?></label>
    <span id="spr_payment_methods_id"><strong><?php echo $this->tax->id; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_taxes_name"><?php echo JText::_('SPR_TAX_NAME'); ?></label>
    <input id="spr_taxes_name" name="spr_taxes_name" type="text" value="<?php echo $this->tax->name; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_taxes_type"><?php echo JText::_('SPR_TAX_TYPE'); ?></label>
    <select id="spr_taxes_type" name="spr_taxes_type">
    <?php echo $this->class->selectOptions($this->tax->type,$this->class->_taxOptions); ?>    
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_taxes_value"><?php echo JText::_('SPR_TAX_VALUE'); ?></label>
    <input id="spr_taxes_value" name="spr_taxes_value" type="text" value="<?php echo $this->tax->value; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_taxes_status"><?php echo JText::_('SPR_TAX_ACCEPTED'); ?></label>
    <select id="spr_taxes_status" name="spr_taxes_status">
        <?php echo sprConfig::_options($this->tax->status,'yesno'); ?>
    </select>
    </div>

    <div class="spr_field">
    <label for="spr_taxes_regions"><?php echo JText::_('SPR_TAX_REGIONS'); ?></label>

    <select id="spr_taxes_regions" name="spr_taxes_regions[]" multiple="multiple" class="spr_input" style="height: 200px;;">
    <?php if(count($this->regions)>0) foreach($this->regions as $id=>$region) {
        $states = sprRegions::_getStates($id);
        $dis = (count($states)>0) ? array($id) : array();
        echo sprRegions::_options($this->tax->regions,array($id=>$region),0,$dis);
        if(count($states)>0) foreach($states as $s) {
            $array = array($s->id=>'&mdash; '.$s->name);
            echo sprRegions::_options($this->tax->regions,$array);
        }
    } ?>
    </select>
    </div>
    
    </fieldset>
<input type="hidden" name="spr_table" value="taxes" />
<input type="hidden" name="spr_id" id="spr_id" value="<?php echo $this->tax->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="taxes" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>
</div>
</div>