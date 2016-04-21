<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_WIDGET_HEADING'), 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_WIDGET_CREATE'); ?></h1>
</div>

<div id="spr_main">

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

    <fieldset class="spr_fieldset">
    
    <div class="spr_field">
    <h5><?php echo JText::_('SPR_WIDGET_CREATE_TYPE'); ?></h5>
    <?php
    $types = sprWidgetTypes::_getTypes();
    if(count($types)>0) {
        echo "<table class='spr_table'><tr><th>&nbsp;</th><th align='left'>".JText::_('SPR_WIDGET_TYPE')."</th><th align='left'>".JText::_('SPR_WIDGET_ABOUT')."</th></tr>";
        foreach($types as $t) {
            echo "<tr><td><input type='radio' name='spr_widgets_type' id='spr_widgets_type_{$t->type}' value='{$t->type}' /></td><td><label for='spr_widgets_type_{$t->type}'>{$t->type}</td><td><label for='spr_widgets_type_{$t->type}'>{$t->about}</label></td></tr>";
        }
    } ?>
    </div>

    </fieldset>

<input type="hidden" name="spr_table" value="widgets" />
<input type="hidden" name="spr_id" id="spr_id" value="0" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="widgets" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>
</div>
</div>