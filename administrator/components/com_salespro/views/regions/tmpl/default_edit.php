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
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#17"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<div id="spr_main">

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

    <fieldset class="spr_fieldset">
    <div class="spr_field">
    <label for="spr_regions_name"><?php echo JText::_('SPR_REG_NAME'); ?></label>
    <input id="spr_regions_name" name="spr_regions_name" type="text" value="<?php echo $this->region->name; ?>" />
    </div>
    
    <div class="spr_field">
        <label for="spr_regions_code_2"><?php echo JText::_('SPR_REG_RCODE'); ?></label>
        <input id="spr_regions_code_2" name="spr_regions_code_2" type="text" value="<?php echo $this->region->code_2; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_regions_code_3"><?php echo JText::_('SPR_REG_RCODE2'); ?></label>
    <input id="spr_regions_code_3" name="spr_regions_code_3" type="text" value="<?php echo $this->region->code_3; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_regions_status"><?php echo JText::_('SPR_REG_ENABLED'); ?></label>
    <select id="spr_regions_status" name="spr_regions_status">
    <?php echo sprConfig::_options($this->region->status,'yesno'); ?>
    </select>
    </div>
    </fieldset>
    
    <fieldset class="spr_fieldset" id="createStateFields" style="display:none">
        <h4><?php echo JText::_('SPR_REG_ADDSTATE'); ?></h4>
        <input type="hidden" id="s_id" value="" />
        <input type="hidden" id="s_parent" value="<?php echo $this->region->id; ?>" />
        <div class="spr_field">
            <label for="s_name"><?php echo JText::_('SPR_REG_STATENAME'); ?></label>
            <input id="s_name" type="text" value="" />
        </div>
        <div class="spr_field">
            <label for="s_code_2"><?php echo JText::_('SPR_REG_STATECODE'); ?></label>
            <input id="s_code_2" type="text" value="" />
        </div>
        <div class="spr_field">
            <label for="s_code_3"><?php echo JText::_('SPR_REG_STATECODE2'); ?></label>
            <input id="s_code_3" type="text" value="" />
        </div>
        <div class="spr_confirm_buttons">
            <label>&nbsp;</label>
            <a href="#" onclick="cancelState();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
            <a href="#" onclick="saveState();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
       </div>                                 
    </fieldset>

    <div class="spr_big_button" id="createState" style="float:right"><?php echo JText::_('SPR_REG_ADDSTATE'); ?></div>
   
    <table class="spr_table" id="statesList" style="clear:both">
        <thead>
            <tr>
                <th align="left"><?php echo JText::_('SPR_REG_STATE'); ?></th>
                <th align="left"><?php echo JText::_('SPR_REG_CODE'); ?></th>
                <th align="left"><?php echo JText::_('SPR_REG_CODE2'); ?></th>
                <th width="1%" class="nowrap center"><?php echo JText::_('SPR_REG_ACTION'); ?></th>
            </tr>
        </thead>
        <tbody id="spr_states_list">
        <?php
         foreach($this->states as $s) {
            echo "<tr id='spr_states_{$s->id}'";
            $fields = array('name','code_2','code_3','id');
            foreach($fields as $field) echo " s_{$field}='{$s->$field}'";
            echo ">
                <td>{$s->name}</td>
                <td>{$s->code_2}</td>
                <td>{$s->code_3}</td>
                <td width='1%' class='nowrap center'><a href='#' onclick='editState({$s->id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteState({$s->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
                </tr>";
            }
        ?>
        </tbody>
    </table>
<input type="hidden" name="spr_table" value="regions" />
<input type="hidden" name="spr_id" id="spr_id" value="<?php echo $this->region->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="regions" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>
</div>
</div>