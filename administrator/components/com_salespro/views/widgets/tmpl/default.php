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
JToolBarHelper::custom( 'create', 'new', 'new', JText::_('SPR_NEW'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_WIDGET_MANAGER'); ?></h1>
</div>

<div id="spr_main">


<form action="" method="post" name="adminForm" id="adminForm">
<fieldset class="spr_fieldset">

<table class="spr_table" id="widgetList">
<thead>
<tr>
<th><?php echo JText::_('SPR_WIDGET_ORDER'); ?><span class="spr-sort-up">&nbsp;</span></th>
<th align="left"><?php echo JText::_('SPR_WIDGET_NAME'); ?></th>
<th align="left"><?php echo JText::_('SPR_WIDGET_TYPE'); ?></th>
<th width="1%"><?php echo JText::_('SPR_WIDGET_ENABLED'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_WIDGET_ACTION'); ?></th>
</tr>
</thead>
<tbody id="spr_widgets_list">
<?php foreach($this->widgets as $w) {
    $ayes = ($w->status == '1') ? 'yes':'no';
    $status = "<a class='spr_icon spr_icon_{$ayes}' id='status_{$w->id}' onclick='status({$w->id});' style='margin:0 auto;'>&nbsp;</a>";
    echo "
<tr id='spr_widgets_{$w->id}'>
    <td width='1%' class='nowrap center'><span class='ui-icon ui-icon-arrowthick-2-n-s' style='margin: 0 10px;'>&nbsp;</span></td>
    <td><a href='#' onclick='edit({$w->id})'>{$w->name}</a></td>
    <td>{$w->type}</td>
    <td class='nowrap center'>{$status}</td>
    <td class='nowrap center' width='1%'><a href='#' onclick='edit({$w->id})' class='spr_icon spr_icon_edit'>&nbsp;</a><a href='#' onclick='del({$w->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
</tr>";
} ?>
</tbody>
</table>
</fieldset>

<input type="hidden" name="spr_table" id="spr_table" value="widgets" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="widgets" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>

</div>