<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_PM_HEADING'), 'salespro');
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

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

    <fieldset class="spr_fieldset">
    
    <div class="spr_field">
    <label for="spr_payment_options_payment_method" style="width:auto; margin-right: 10px;"><?php echo JText::_('SPR_PM_CREATE_TYPE'); ?>:</label>
    <select id="spr_payment_options_payment_method" name="spr_payment_options_payment_method">
        <?php echo sprConfig::_options(0,$this->payment_methods); ?>
    </select>
    </div>

    </fieldset>

<input type="hidden" name="spr_table" value="payment_options" />
<input type="hidden" name="spr_id" id="spr_id" value="0" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="payment_options" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>
</div>
</div>