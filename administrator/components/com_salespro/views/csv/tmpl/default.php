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
    <h1><?php echo JText::_('SPR_CSV_TITLE'); ?></h1>
</div>

<div id="spr_tabs">

<div class="spr_tabs">
<ul>
    <li><a href="#about"><?php echo JText::_('SPR_CSV_ABOUT'); ?></a></li>
    <li><a href="#import"><?php echo JText::_('SPR_CSV_IMPORT'); ?></a></li>
    <li><a href="#export"><?php echo JText::_('SPR_CSV_EXPORT'); ?></a></li>
</ul>

<div id="about" style="min-height: 220px;">
<div class="spr_notab">
<img src="components/com_salespro/resources/images/csv.png" style="width: 220px; position: absolute;" />
<div style="display: inline-block; margin-left: 220px;">
<h4><?php echo JText::_('SPR_CSV_WELCOME'); ?></h4>
<p><?php echo JText::_('SPR_CSV_ABOUT1'); ?></p>
<p><?php echo JText::_('SPR_CSV_ABOUT2'); ?></p>
<p><?php echo JText::_('SPR_CSV_ABOUT3'); ?></p>
<p><?php echo JText::_('SPR_CSV_ABOUT4'); ?>.</p>
<p><?php echo JText::_('SPR_CSV_ABOUT5'); ?><strong> <a href='http://www.sales-pro.co.uk/index.php?option=com_kunena&view=category&layout=list&catid=0' target='_blank'>http://sales-pro.co.uk</a></strong>.</p>
</div>
</div>
</div>

<!-- CSV IMPORT -->
<div id="import">
<div class="spr_notab" style="min-height: 140px;">
<img src="components/com_salespro/resources/images/csv.png" style="width: 220px; position: absolute;" /><div style="display: inline-block; margin-left: 220px;">
<h4><?php echo JText::_('SPR_CSV_IMPORT_TITLE'); ?></h4>

<p style="clear:both; overflow: auto;"><strong><?php echo JText::_('SPR_IMPORT_STEP1'); ?>: </strong><?php echo JText::_('SPR_CSV_IMPORT1'); ?><strong><a href='index.php?option=com_salespro&view=csv'>[<?php echo JText::_('SPR_IMPORT_DL'); ?>]</a></strong></p>
<p><strong>
<?php echo JText::_('SPR_IMPORT_STEP2'); ?>: </strong><?php echo JText::_('SPR_CSV_IMPORT2'); ?></p>
<p><strong><?php echo JText::_('SPR_IMPORT_STEP3'); ?>: </strong><?php echo JText::_('SPR_CSV_IMPORT3'); ?></p>
<p><strong><?php echo JText::_('SPR_IMPORT_STEP4'); ?>: </strong><?php echo JText::_('SPR_CSV_IMPORT4'); ?></p>
<div id="csv_import_button">
    <input id="csv_upload" name="file_upload" type="file" multiple="false" />
    <div id="csv_queue"></div>
</div>

<p><strong><?php echo JText::_('SPR_CSV_NOTES'); ?></strong>
<p><?php echo JText::_('SPR_CSV_NOTES1'); ?></p>
<p><?php echo JText::_('SPR_CSV_NOTES2'); ?></p>
<p><?php echo JText::_('SPR_CSV_NOTES3'); ?></p> 
<p><?php echo JText::_('SPR_CSV_NOTES4'); ?>
<ul><li><?php echo JText::_('SPR_CSV_NOTESA'); ?></li><li><?php echo JText::_('SPR_CSV_NOTESB'); ?></li><li><?php echo JText::_('SPR_CSV_NOTESC'); ?></li><li><?php echo JText::_('SPR_CSV_NOTESD'); ?></li></ul></p>

</div>

</div>
</div>
<!-- END OF CSV IMPORT -->

<!-- CSV EXPORT -->
<div id="export">
<div class="spr_notab" style="min-height: 140px;">
<img src="components/com_salespro/resources/images/csv.png" style="width: 220px; position: absolute;" />
<div style="display: inline-block; margin-left: 220px;">
<h4><?php echo JText::_('SPR_CSV_EXPORT_TITLE'); ?></h4>

<p style="clear:both; overflow: auto;">
<p><?php echo JText::_('SPR_CSV_EXPORT1'); ?></p>
<p><?php echo JText::_('SPR_CSV_EXPORT2'); ?></p>
<p><?php echo JText::_('SPR_CSV_EXPORT3'); ?></p>

<a href='index.php?option=com_salespro&view=csv&task=makeCSV'><div class="spr_submit_button" id="createOptionBtn" style="clear: none; margin-left: 10px; float: left;"><?php echo JText::_('SPR_CSV_EXP_BTN'); ?></div></a>
</div>
</div>
</div>
<!-- CSV EXPORT -->

</div>
</div>
</div>
</div>