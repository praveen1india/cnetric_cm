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
JToolBarHelper::custom( 'create', 'new', 'new', JText::_('SPR_NEW'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#18"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_TAX_HEADING'); ?></h1>
</div>

<div id="spr_main">

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

<fieldset class="spr_fieldset">
<table class="spr_table" id="taxList">
<thead>
<tr>
<th align="left"><?php echo JText::_('SPR_TAX_NAME'); ?></th>
<th align="left"><?php echo JText::_('SPR_TAX_REGIONS'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_TAX_TYPE'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_TAX_VALUE'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_TAX_ENABLED'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_TAX_ACTION'); ?></th>
</tr>
</thead>
<tbody id="spr_taxes_list">
<?php foreach($this->taxes as $c) {
    $ayes = ($c->status === '1') ? 'yes':'no';
    $status = "<a class='spr_icon spr_icon_{$ayes}' id='status_{$c->id}' onclick='status({$c->id});' style='margin:0 auto;'>&nbsp;</a>";
    $type = $this->class->_taxOptions[$c->type];
    echo "
<tr id='spr_taxes_{$c->id}'>
    <td><a href='#' onclick='edit({$c->id})'>{$c->name}</a></td>
    <td>{$c->region}</td>
    <td class='nowrap center'>{$type}</td>
    <td class='nowrap center'>{$c->value}</td>
    <td class='nowrap center'>{$status}</td>
    <td class='nowrap center' width='1%'><a href='#' onclick='edit({$c->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='del({$c->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
</tr>";
} ?>
</tbody>
</table>
</fieldset>
<input type="hidden" name="spr_table" id="spr_table" value="taxes" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="view" value="taxes" />

<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
</div>
</div>