<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_CUR_HEADING'), 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#16"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<form action="" method="post" name="adminForm" id="adminForm">

<div id="spr_main">
    <div class="spr_tip">
        <p><?php echo JText::_('SPR_CUR_TIP'); ?></p>
    </div>
    
    <div class="spr_notab">

    <fieldset class="spr_fieldset">
    <div class="spr_field">
    <label for="spr_currencies_name"><?php echo JText::_('SPR_CUR_NAME'); ?></label>
    <input id="spr_currencies_name" name="spr_currencies_name" type="text" value="<?php echo $this->currency->name; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_currencies_xe"><?php echo JText::_('SPR_CUR_XE'); ?></label>
    <input id="spr_currencies_xe" name="spr_currencies_xe" type="text" value="<?php echo $this->currency->xe; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_currencies_status"><?php echo JText::_('SPR_CUR_ACCEPTED'); ?></label>
    <select id="spr_currencies_status" name="spr_currencies_status">
        <?php echo sprConfig::_options($this->currency->status,'yesno'); ?>
    </select>
    </div>
    </fieldset>
    
    <br />
    <fieldset class="spr_fieldset">
    <h5>Currency setup</h5>
    <div class="spr_field">
    <label for="spr_currencies_code"><?php echo JText::_('SPR_CUR_CCODE'); ?></label>
    <input id="spr_currencies_code" name="spr_currencies_code" type="text" value="<?php echo $this->currency->code; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_currencies_symbol"><?php echo JText::_('SPR_CUR_CSYMBOL'); ?></label>
    <input id="spr_currencies_symbol" name="spr_currencies_symbol" type="text" value="<?php echo $this->currency->symbol; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_currencies_decimals"><?php echo JText::_('SPR_CUR_CDECIMALS'); ?></label>
    <input id="spr_currencies_decimals" name="spr_currencies_decimals" type="text" value="<?php echo $this->currency->decimals; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_currencies_thousands"><?php echo JText::_('SPR_CUR_CTHOUSANDS'); ?></label>
    <input id="spr_currencies_thousands" name="spr_currencies_thousands" type="text" value="<?php echo $this->currency->thousands; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_currencies_separator"><?php echo JText::_('SPR_CUR_CSEPARATOR'); ?></label>
    <input id="spr_currencies_separator" name="spr_currencies_separator" type="text" value="<?php echo $this->currency->separator; ?>" />
    </div>
    </fieldset>
</div>

<input type="hidden" name="spr_table" value="currencies" />
<input type="hidden" name="spr_id" value="<?php echo $this->currency->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="currencies" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>