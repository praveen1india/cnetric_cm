<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_(JText::_('COM_SALESPRO').': '.JText::_('SPR_TPL_HEADING')), 'salespro');

//JToolBarHelper::custom( 'create', 'new', 'new', JText::_('SPR_NEW'), 0,0 ); FEATURE IS IN BETA TESTING
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#19"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_TPL_HEADING'); ?></h1>
</div>

<div id="spr_main">

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

<fieldset class="spr_fieldset">

<table class="spr_table" id="templateList">
<thead>
<tr>
<th align="left"><?php echo JText::_('SPR_TPL_TEMPLATE'); ?></th>
<th align="left"><?php echo JText::_('SPR_TPL_ALIAS'); ?></th>
<th align="left"><?php echo JText::_('SPR_TPL_COLOURSCHEME'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_TPL_DEFAULT'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_TPL_ACTION'); ?></th>
</tr>
</thead>
<tbody>
<?php foreach($this->templates as $t) {
    $dyes = ($t->default === '1') ? 'yes':'no';
    $default = "<a class='spr_icon spr_icon_star{$dyes}' id='default_{$t->id}' onclick='setDefault({$t->id});' style='margin:0 auto;'>&nbsp;</a>";
    echo "
<tr>
    <td><a href='#' onclick='edit({$t->id})'>{$t->name}</a></td>
    <td>{$t->alias}</td>
    <td>{$t->color}</td>
    <td class='nowrap center'>{$default}</td>
    <td class='nowrap center' width='1%'><a href='#' onclick='edit({$t->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='del({$t->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
</tr>";
} ?>
</tbody>
</table>

</fieldset>

<input type="hidden" name="spr_table" id="spr_table" value="templates" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="templates" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
</div>
</div>