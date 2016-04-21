<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_IMPORT_HEADING'), 'salespro');
?>

<link rel="stylesheet" href="components/com_salespro/resources/uploadifive/uploadifive.css" type="text/css" />
<script src="components/com_salespro/resources/uploadifive/jquery.uploadifive.min.js" type="text/javascript"></script>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#16"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_IMPORT_WIZARD'); ?></h1>
</div>

<a href="index.php?option=com_salespro&view=export&task=export">Click to export CSV</a>

</div>
</div>