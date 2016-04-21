<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_REG_HEADING'), 'salespro');
JToolBarHelper::custom( 'create', 'new', 'new', JText::_('SPR_NEW'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#17"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_REG_COUNTRIES'); ?></h1>
</div>

<div id="spr_main">

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

<fieldset class="spr_fieldset">

<table class="spr_table" id="regionList">
<thead>
<tr>
<th align="left"><?php echo JText::_('SPR_REG_COUNTRY'); ?></th>
<th align="left" class="nowrap center"><?php echo JText::_('SPR_REG_CODE'); ?></th>
<th align="left" class="nowrap center"><?php echo JText::_('SPR_REG_CODE2'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_REG_ENABLED'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_REG_DEFAULT'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_REG_ACTIONS'); ?></th>
</tr>
</thead>
<tbody>
<?php
foreach($this->regions as $r) {
    $ayes = ($r->status == '1') ? 'yes' : 'no';
    $dyes = ($r->default == '1') ? 'yes' : 'no';
    $accepted = "<a class='spr_icon spr_icon_{$ayes}' id='status_{$r->id}' onclick='status({$r->id});' style='margin:0 auto;'>&nbsp;</a>";
    $default = "<a class='spr_icon spr_icon_star{$dyes}' id='region_default_{$r->id}' onclick='regionDefault({$r->id});' style='margin:0 auto;'>&nbsp;</a>";
    $name = '';
    echo "<tr>
        <td><a href='#' onclick='edit({$r->id})'>{$r->name}</a></td>
        <td class='nowrap center'>{$r->code_2}</td>
        <td class='nowrap center'>{$r->code_3}</td>
        <td class='nowrap center'>{$accepted}</td>
        <td class='nowrap center'>{$default}</td>
        <td class='nowrap center' width='1%'><a href='#' onclick='edit({$r->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='del({$r->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
    </tr>";
} ?>
</tbody>
</table>
</fieldset>

<input type="hidden" name="spr_table" id="spr_table" value="regions" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="regions" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
</div>
</div>