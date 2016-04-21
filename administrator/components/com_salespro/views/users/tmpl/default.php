<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_(JText::_('COM_SALESPRO').': '.JText::_('SPR_USR_HEADING')), 'salespro');
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#18"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo JText::_('SPR_USR_HEADING'); ?></h1>
</div>

<div id="spr_main">

<div class="spr_tip"><p><?php echo JText::_('SPR_USR_TIP'); ?></p></div>

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">
    
<!-- SEARCH BOX
<div class="spr_filter">
    <div id="spr_search_box">
        <div class="spr_field spr_search_field">
            <label for="spr_search_name"><?php echo JText::_('SPR_SEARCH'); ?>:</label>
            <input id="spr_search_name" name="spr_search_name" type="text" placeholder="<?php echo JText::_('SPR_SEARCH'); ?>: <?php echo JText::_('SPR_USR_FIRSTNAME'); ?>" value="<?php echo $this->search['name']; ?>" />
            <div style="float:left;" rel="spr_search_box" class="spr_icon spr_icon_plus" onclick="upDown(this);">&nbsp;</div>
        </div>
        
        <div class="spr_field spr_search_field">
            <label for="spr_search_surname"><?php echo JText::_('SPR_USR_SURNAME'); ?>:</label>
            <input id="spr_search_surname" name="spr_search_surname" type="text" placeholder="<?php echo JText::_('SPR_SEARCH'); ?>: <?php echo JText::_('SPR_USR_SURNAME'); ?>" value="<?php echo $this->search['surname']; ?>" />
        </div>
        
        <div class="spr_field spr_search_field">
            <label for="spr_search_region_id"><?php echo JText::_('SPR_USR_REGION'); ?>:</label>
            <select id="spr_search_region_id" name="spr_search_region_id">
            <option value="">-- <?php echo JText::_('SPR_USR_ALL_REGIONS'); ?> --</option>
            <?php if(count($this->regions)>0) foreach($this->regions as $r) {
                if($r->status !== '1') continue;
                $array = array($r->id=>$r->name);
                echo sprRegions::_options($this->tax->regions,$array);
                $states = sprRegions::_getStates($r->id);
                if(count($states)>0) foreach($states as $s) {
                    $array = array($s->id=>'&mdash; '.$s->name);
                    echo sprRegions::_options($this->search['region_id'],$array);
                }
            } ?>
            </select>
        </div>

        <div class="spr_field spr_search_field">
            <label for="spr_search_status"><?php echo JText::_('SPR_USR_STATUS'); ?>:</label>
            <select id="spr_search_status" name="spr_search_status">
            <option value="">-- <?php echo JText::_('SPR_USR_ALL_STATUSES'); ?> --</option>
                <?php echo sprConfig::_options($this->search['status'],$this->class->_statusOptions); ?>
            </select>
        </div>
        
        <div class="spr_field spr_search_field">
            <label for="spr_search_start"><?php echo JText::_('SPR_USR_START_DATE'); ?>:</label>
            <input id="spr_search_start" name="spr_search_start" type="text" value="<?php echo $this->search['start']; ?>" placeholder="<?php echo JText::_('SPR_USR_START_DATE'); ?>" class="spr_date" />
        </div>
        <div class="spr_field spr_search_field">
            <label for="spr_search_end"><?php echo JText::_('SPR_USR_END_DATE'); ?>:</label>
            <input id="spr_search_end" name="spr_search_end" type="text" value="<?php echo $this->search['end']; ?>" placeholder="<?php echo JText::_('SPR_USR_END_DATE'); ?>" class="spr_date" />
        </div>

        <div class="spr_search_buttons">
            <input type="submit" name="spr_search_submit" class="spr_input spr_search_button" value="<?php echo JText::_('SPR_GO'); ?>" />
            <input type="submit" name="spr_search_clear" class="spr_input spr_search_button" value="<?php echo JText::_('SPR_CLEAR'); ?>" />
        </div>
    </div>
</div>
 END OF SEARCH BOX -->
 
<fieldset class="spr_fieldset">
<table class="spr_table" id="customerList">
<thead>
<tr>
<th align="left"><?php echo JText::_('SPR_USR_CUSTOMER'); ?></th>
<th align="left"><?php echo JText::_('SPR_USR_EMAIL'); ?></th>
<th><?php echo JText::_('SPR_USR_REGION'); ?></th>
<th><?php echo JText::_('SPR_USR_ADDED'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_USR_STATUS'); ?></th>
<th width="1%" class="nowrap center"><?php echo JText::_('SPR_USR_ACTION'); ?></th>
</tr>
</thead>
<tbody>
<?php

if(count($this->users)>0) foreach($this->users as $c) {        
    $ayes = ($c->block === '0') ? 'yes' : 'no';
    $status = "<span class='spr_icon spr_icon_{$ayes}' id='status_{$c->id}' onclick='status({$c->id});'>&nbsp;</span>";
    echo "
<tr>
    <td><a href='#' onclick='edit({$c->id})'>{$c->name}</a></td>
    <td>{$c->email}</td>
    <td class='nowrap center'>{$c->region->name}</td>
    <td class='nowrap center'>{$c->registerDate}</td>
    <td class='nowrap center'>{$status}</td>
    <td class='nowrap center' width='1%'><a href='#' onclick='edit({$c->id})' class='spr_icon spr_icon_edit'>&nbsp;</a></td>
</tr>";
} ?>
</tbody>
</table>

</fieldset>

<?php echo $this->class->pageControls(); ?>

<input type="hidden" name="spr_table" id="spr_table" value="users" />
<input type="hidden" name="spr_id" id="spr_id" value=""  />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value=""  />
<input type="hidden" name="view" value="users" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
</div>
</div>