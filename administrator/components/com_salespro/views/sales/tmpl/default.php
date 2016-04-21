<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_SL_HEADING'), 'salespro');
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#17"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_SL_HEADING'); ?></h1>
</div>

<div id="spr_main">

<!-- SEARCH BOX -->
<div class="spr_filter">
    <div id="spr_search_box">
        <div class="spr_field spr_search_field">
            <label for="spr_search_customer"><?php echo JText::_('SPR_SL_SEARCH'); ?>:</label>
            <input id="spr_search_customer" name="spr_search_customer" type="text" placeholder="<?php echo JText::_('SPR_SL_SEARCH'); ?>: customer@email.com" value="<?php echo $this->search['customer']; ?>" />
            <div rel="spr_search_box" class="spr_icon spr_icon_plus" onclick="upDown(this);">&nbsp;</div>
        </div>
        <div class="spr_field spr_search_field">
            <label for="spr_search_status"><?php echo JText::_('SPR_SL_STATUS'); ?>:</label>
            <select id="spr_search_status" name="spr_search_status">
            <option value="">-- <?php echo JText::_('SPR_SL_ALLSTATUS'); ?> --</option>
            <?php echo sprConfig::_options($this->search['status'],$this->class->_statusOptions); ?></select>
        </div>
        <div class="spr_field spr_search_field">
            <label for="spr_search_start"><?php echo JText::_('SPR_SL_STARTDATE'); ?>:</label>
            <input id="spr_search_start" name="spr_search_start" type="text" class="spr_date" value="<?php echo $this->search['start']; ?>" placeholder="<?php echo JText::_('SPR_SL_STARTDATE'); ?>" />
        </div>
        <div class="spr_field spr_search_field">
            <label for="spr_search_end"><?php echo JText::_('SPR_SL_ENDDATE'); ?>:</label>
            <input id="spr_search_end" name="spr_search_end" type="text" class="spr_date" value="<?php echo $this->search['end']; ?>" placeholder="<?php echo JText::_('SPR_SL_ENDDATE'); ?>" />
        </div>
        <div class="spr_search_buttons">
            <input type="submit" name="spr_search_submit" class="spr_input spr_search_button" value="<?php echo JText::_('SPR_GO'); ?>" />
            <input type="submit" name="spr_search_clear" class="spr_input spr_search_button" value="<?php echo JText::_('SPR_CLEAR'); ?>" />
        </div>
    </div>
</div>
<!-- END OF SEARCH BOX -->

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

<fieldset class="spr_fieldset">
<table class="spr_table" id="salesList">
<thead>
<tr>
<th width="1%" class="center nowrap"><a href="#" onclick="sort('z.id')"><?php echo JText::_('SPR_SL_SALEID'); ?></a><?php if($this->class->order['sort'] === 'z.id') echo $this->icon; ?></th>
<th align="left"><a href="#" onclick="sort('z.date')"><?php echo JText::_('SPR_SL_DATE'); ?></a>
<?php if($this->class->order['sort'] === 'z.date') echo $this->icon; ?></th>
<th align="left"><a href="#" onclick="sort('c.name')"><?php echo JText::_('SPR_SL_CUSTOMER'); ?></a>
<?php if($this->class->order['sort'] === 'c.name') echo $this->icon; ?></th>
<th align="left"><a href="#" onclick="sort('z.status')"><?php echo JText::_('SPR_SL_STATUS'); ?></a><?php if($this->class->order['sort'] === 'z.status') echo $this->icon; ?></th>
<th align="left"><?php echo JText::_('SPR_SL_TRANSACTION'); ?></th>
<th class="nowrap center"><?php echo JText::_('SPR_SL_ITEMS'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_SL_PRICE'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_SL_GRANDTOTAL'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_CAT_ACTION'); ?></th>
</tr>

</thead>
<tbody id="spr_sales_list">
<?php
if(count($this->sales)>0) foreach($this->sales as $s) {
    $items = count($s->items);
    $date = date("Y-m-j", strtotime($s->date));
    $time = date("H:i:s", strtotime($s->date));
    $status = $this->class->_statusOptions[$s->status];
    echo "<tr><td align='center'><a href='#' onclick='edit({$s->id})'>{$s->id}</a></td>
    <td align='left'><a href='#' onclick='edit({$s->id})'>{$s->date}</a></td>
    <td align='left'>{$s->user->name}<br /><a href='index.php?option=com_salespro&view=users&layout=edit&id={$s->user_id}'><span class='spr_small'>{$s->user->email}</span></a></td>
    <td align='left'>{$status}</td>
    <td align='left'>{$s->payment->name}</td>
    <td align='center'>{$items}</td>
    <td align='right'>{$s->f_price}</td>
    <td align='right'>{$s->f_grandtotal}</td>
    <td class='nowrap center' width='1%'><a href='#' onclick='edit({$s->id})' class='spr_icon spr_icon_edit'>&nbsp;</a>  <a href='#' onclick='del({$s->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
    </tr>";
} ?></tbody>
</table>

<style>
span.spr_small {
    color: #666;
    font-size: 10px;
}
</style>

</fieldset>

<?php echo $this->class->pageControls(); ?>

<input type="hidden" name="spr_table" id="spr_table" value="sales" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="sales" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
</div>
</div>