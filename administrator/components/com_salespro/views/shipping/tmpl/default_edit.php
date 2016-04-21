<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_(JText::_('COM_SALESPRO').': '.JText::_('SPR_SHP_HEADING')), 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#19"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<div id="spr_main">

<div class="spr_tip">
<p><?php echo JText::_('SPR_SHP_EDIT_TIP'); ?></p>
</div>

<div class="spr_notab">

<form action="" method="post" name="adminForm" id="adminForm">

    <fieldset class="spr_fieldset">
    
    <div class="spr_field">
    <label for="spr_shipping_id"><?php echo JText::_('SPR_SHP_ID'); ?></label>
    <span id="spr_shipping_id"><strong><?php echo $this->ship->id; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_shipping_alias"><?php echo JText::_('SPR_SHP_ALIAS'); ?></label>
    <span id="spr_shipping_alias"><strong><?php echo $this->ship->alias; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_shipping_name"><?php echo JText::_('SPR_SHP_NAME'); ?></label>
    <input id="spr_shipping_name" name="spr_shipping_name" type="text" value="<?php echo $this->ship->name; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_shipping_status"><?php echo JText::_('SPR_SHP_ENABLED'); ?></label>
    <select id="spr_shipping_status" name="spr_shipping_status">
        <?php echo sprConfig::_options($this->ship->status,'yesno'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_shipping_paymentoptions[]"><?php echo JText::_('SPR_SHP_PAYMETHODS'); ?></label>
    <select id="spr_shipping_paymentoptions[]" name="spr_shipping_paymentoptions[]" multiple="multiple">
        <?php echo sprConfig::_options($this->ship->paymentoptions,$this->payoptions); ?>
    </select>

    </div>
    
    <div class="spr_field">
        <label for="spr_shipping_info" style="margin-bottom: 20px;"><?php echo JText::_('SPR_SHP_EXTRAINFO'); ?></label>
        <textarea id="spr_shipping_info" name="spr_shipping_info" placeholder="<?php echo JText::_('SPR_SHP_EXTRAINFO2'); ?>"><?php echo $this->ship->info; ?></textarea>
    </div>
    
    <div id="shipping_rules_2" class="shipping_rules" style="display:inline">
    
    <fieldset class="spr_fieldset" id="createRuleFields" style="display:none; background: #f3f4f5; padding: 20px; margin: 20px 0;">
        <h4><?php echo JText::_('SPR_SHP_ADDSHIPPRICE'); ?></h4>
        <input type="hidden" id="r_id" value="" />
        <div class="spr_field">
            <label for="r_price"><?php echo JText::_('SPR_SHP_PRICE')." ({$this->currency->symbol})"; ?></label>
            <input id="r_price" type="text" value="" />
        </div>
        <div class="spr_field">
            <label for="r_regions"><?php echo JText::_('SPR_SHP_REGIONS'); ?></label>
            <select id="r_regions" name="r_regions[]" multiple="multiple" class="spr_input" style="height: 200px">
            <?php if(count($this->regions)>0) foreach($this->regions as $r) {
                $array = array($r->id=>$r->name);
                echo sprRegions::_options(0,$array);
                $states = sprRegions::_getStates($r->id);
                if(count($states)>0) foreach($states as $s) {
                    $array = array($s->id=>'&mdash; '.$s->name);
                    echo sprRegions::_options(0,$array);
                }
            } ?>
            </select>
        </div>
        <hr />
        <h4><?php echo JText::_('SPR_SHP_RULES'); ?></h4>
        <p><?php echo JText::_('SPR_SHP_RULES_INFO'); ?></p>
        <div class="spr_field">
            <label><?php echo JText::_('SPR_SHP_BASKETITEMS'); ?></label>
            <label for="r_start_items" style="min-width: 40px; width: 40px; margin-right: 10px;"><?php echo JText::_('SPR_SHP_BASKETMIN'); ?></label>
            <input id="r_start_items" type="text" value="" style="width: 40px; float: left;" />
            <label for="r_end_items" style="min-width: 40px; width: 40px; margin-left: 40px; margin-right: 10px; "><?php echo JText::_('SPR_SHP_BASKETMAX'); ?></label>
            <input id="r_end_items" type="text" value="" style="width: 40px; float: left;" />
        </div>
        <div class="spr_field">
            <label><?php echo JText::_('SPR_SHP_BASKETPRICE')." ({$this->currency->symbol})"; ?></label>
            <label for="r_start_price" style="min-width: 40px; width: 40px; margin-right: 10px;"><?php echo JText::_('SPR_SHP_BASKETMIN'); ?></label>
            <input id="r_start_price" type="text" value="" style="width: 40px; float: left;" />
            <label for="r_end_price" style="min-width: 40px; width: 40px; margin-left: 40px; margin-right: 10px; "><?php echo JText::_('SPR_SHP_BASKETMAX'); ?></label>
            <input id="r_end_price" type="text" value="" style="width: 40px; float: left;" />
        </div>
        <div class="spr_field">
            <label><?php echo JText::_('SPR_SHP_WEIGHT')." ({$this->weightunit})"; ?></label>
            <label for="r_start_weight" style="min-width: 40px; width: 40px; margin-right: 10px;"><?php echo JText::_('SPR_SHP_BASKETMIN'); ?></label>
            <input id="r_start_weight" type="text" value="" style="width: 40px; float: left;" />
            <label for="r_end_weight" style="min-width: 40px; width: 40px; margin-left: 40px; margin-right: 10px; "><?php echo JText::_('SPR_SHP_BASKETMAX'); ?></label>
            <input id="r_end_weight" type="text" value="" style="width: 40px; float: left;" />
        </div>
        <div class="spr_field">
            <label><?php echo JText::_('SPR_SHP_HEIGHT')." ({$this->sizeunit})"; ?></label>
            <label for="r_height" style="min-width: 40px; width: 40px; margin-left: 140px; margin-right: 10px; "><?php echo JText::_('SPR_SHP_BASKETMAX'); ?></label>
            <input id="r_height" type="text" value="" style="width: 40px; float: left;" />
        </div>
        <div class="spr_field">
            <label><?php echo JText::_('SPR_SHP_WIDTH')." ({$this->sizeunit})"; ?></label>
            <label for="r_width" style="min-width: 40px; width: 40px; margin-left: 140px; margin-right: 10px; "><?php echo JText::_('SPR_SHP_BASKETMAX'); ?></label>
            <input id="r_width" type="text" value="" style="width: 40px; float: left;" />
        </div>
        <div class="spr_field">
            <label><?php echo JText::_('SPR_SHP_DEPTH')."( {$this->sizeunit})"; ?></label>
            <label for="r_depth" style="min-width: 40px; width: 40px; margin-left: 140px; margin-right: 10px; "><?php echo JText::_('SPR_SHP_BASKETMAX'); ?></label>
            <input id="r_depth" type="text" value="" style="width: 40px; float: left;" />
        </div>

        <div class="spr_confirm_buttons">
            <label>&nbsp;</label>
            <a href="#" onclick="cancelRule();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
            <a href="#" onclick="saveRule();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
       </div>
    </fieldset>
    
    <hr style="clear:both" />
    <div class="spr_big_button" id="createRule" style="float:right"><?php echo JText::_('SPR_SHP_ADDPRICE'); ?></div>
    <h4><?php echo JText::_('SPR_SHP_PRICES'); ?></h4>
    <table class="spr_table" id="ruleList" style="clear:both">
        <thead>
            <tr>
                <th align="left"><?php echo JText::_('SPR_SHP_PRICE'); ?></th>
                <th align="left"><?php echo JText::_('SPR_SHP_REGIONS'); ?></th>
                <th align="left" width="1%" class="nowrap"><?php echo JText::_('SPR_SHP_ITEMS'); ?></th>
                <th align="left" width="1%" class="nowrap"><?php echo JText::_('SPR_SHP_PRICE'); ?></th>
                <th align="left" width="1%" class="nowrap"><?php echo JText::_('SPR_SHP_WEIGHT'); ?></th>
                <th align="left" width="1%" class="nowrap"><?php echo JText::_('SPR_SHP_HEIGHT'); ?></th>
                <th align="left" width="1%" class="nowrap"><?php echo JText::_('SPR_SHP_WIDTH'); ?></th>
                <th align="left" width="1%" class="nowrap"><?php echo JText::_('SPR_SHP_DEPTH'); ?></th>
                <th width="1%" class="nowrap center"><?php echo JText::_('SPR_SHP_ACTIONS'); ?></th>
            </tr>
        </thead>
        <tbody id="spr_shipping_rules_list">
        <?php
         foreach($this->ship->rules as $r) {
            echo $r->string;
         } ?>
        </tbody>
    </table>
    
    </div>
    
    </fieldset>
<input type="hidden" name="spr_table" value="shipping" />
<input type="hidden" name="spr_id" id="spr_id" value="<?php echo $this->ship->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="shipping" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
</div>
</div>