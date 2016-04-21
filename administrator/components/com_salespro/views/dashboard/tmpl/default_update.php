<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_DASH_UPDATEHEADING'), 'salespro');        
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#6"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_DASH_UPDATEMAN'); ?></h1>
</div>

<div id="spr_main">
<div class="spr_notab">
<fieldset class="spr_fieldset">
<?php if(!$this->update) {
    echo "<h5>".JText::_('SPR_DASH_UPTODATE')."</h5>";
} else {
    echo "<h5>".JText::_('SPR_DASH_UPDATENOW')."</h5>";
    $x = 0;
    foreach($this->updates as $n=>$update) {
        if($x++ === 0) echo "<a href='{$update->download_page}' target='_blank'><div class='spr_submit_button' style='width: auto; display: block; margin-left: 5px; float: left !important;'>".JText::_('SPR_DASH_DOWNLOADUPDATE')."</div></a>";
        echo "<fieldset style='clear: both; background: #eee; margin: 20px 0; padding: 20px; width: auto;'>";
        echo "<p><strong>".JText::_('SPR_DASH_VERSION').": {$update->version}</strong></p>";
        echo "<div class='form-horizontal'>";
        echo "<ul class='nav nav-tabs' id='updatetab-{$n}'>";
        if (strlen($update->overview)>0) echo "<li class='active'><a href='#overview-{$n}' data-toggle='tab'>".JText::_('SPR_DASH_OVERVIEW')."</a></li>";
        if (strlen($update->changelog)>0) echo "<li><a href='#changelog-{$n}' data-toggle='tab'>".JText::_('SPR_DASH_CHANGELOG')."</a></li>";
        if (strlen($update->screenshots)>0) echo "<li><a href='#screenshots-{$n}' data-toggle='tab'>".JText::_('SPR_DASH_SCREENSHOTS')."</a></li>";
        echo "</ul>";
        echo "<div class='tab-content' id='updatecontent-{$n}'>";
        if (strlen($update->overview)>0) echo "<div id='overview-{$n}' class='tab-pane active'>{$update->overview}</div>";
        if (strlen($update->changelog)>0) echo "<div id='changelog-{$n}' class='tab-pane'>{$update->changelog}</div>";
        if (strlen($update->screenshots)>0) echo "<div id='screenshots-{$n}' class='tab-pane'>{$update->screenshots}</div>";
        echo "</div>";
        echo "</fieldset>";
    }
} ?>
</fieldset>
</div>
</div>
</div>
</div>