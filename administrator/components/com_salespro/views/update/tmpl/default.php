<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_UPD_HEADING'), 'salespro');
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_UPD_HEADING'); ?></h1>
</div>

<div id="spr_main">

<div class="spr_notab">

<fieldset class="spr_fieldset">

<p><?php echo JText::_('SPR_UPD_INFO'); ?></p>
<p><?php echo JText::_('SPR_UPD_INFO2'); ?> <a href='http://sales-pro.co.uk' target='_blank'>http://sales-pro.co.uk</a></p>
<p><?php echo JText::_('SPR_UPD_INFO3'); ?></p>

</fieldset>

</div>
</div>
</div>
</div>