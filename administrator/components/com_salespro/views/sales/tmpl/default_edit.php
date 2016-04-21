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

<form action="" method="post" name="adminForm" id="adminForm">
<div id="spr_tabs">
    <div class="spr_tabs">
        <ul>
            <li class="active"><a href="#details" data-toggle="tab"><?php echo JText::_('SPR_SL_OVERVIEWTAB'); ?></a></li>
            <li><a href="#billing" data-toggle="tab"><?php echo JText::_('SPR_SL_BILLINGTAB'); ?></a></li>
            <li><a href="#delivery" data-toggle="tab"><?php echo JText::_('SPR_SL_DELIVERYTAB'); ?></a></li>
        </ul>
    <div id="details">
    
    <div>
    
    <style>
    #sale_details .spr_field {
        padding: 0;
    }
    #sale_details label {
        font-weight: bold;
        color: #333;
    }
    </style>

    <fieldset class="spr_fieldset" style=" background: #f9f9f9;padding: 20px; margin-bottom: 20px;" id="sale_details">
    
    <div class="spr_field">
    <label for="spr_sales_id"><?php echo JText::_('SPR_SL_SALEID'); ?></label>
    <span id="spr_sales_id"><?php echo $this->sale->id; ?></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_id"><?php echo JText::_('SPR_SL_SALEDATE'); ?></label>
    <span id="spr_sales_id"><?php echo $this->sale->date; ?></span>
    </div>
    
    <div class="spr_field spr_field_thin">
    <label for="spr_sales_customer_id"><?php echo JText::_('SPR_SL_CUSTOMER'); ?></label>
    <span id="spr_sales_customer_id"><?php echo $this->sale->user->name; ?> <?php if($this->sale->user_id > 0) echo "<a href='index.php?option=com_salespro&view=users&layout=edit&id={$this->sale->user_id}; ?>'>({$this->sale->user->email})</a>";
    else if(strlen($this->sale->user->email)>0) echo "({$this->sale->user->email})"; ?>
    </span>
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_customer_id"><?php echo JText::_('SPR_SL_CUSTOMERIP'); ?></label>
    <span id="spr_sales_customer_id"><?php echo $this->sale->ip; ?></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_payment_txn_id"><?php echo JText::_('SPR_SL_PAYMETHOD'); ?></label>
    <span id="spr_sales_payment_txn_id"><?php echo $this->sale->payment->name; ?></span>
    </div>

    <div class="spr_field">
    <label for="spr_sales_currency"><?php echo JText::_('SPR_SL_SALECURRENCY'); ?></label>
    <span id="spr_sales_currency"><?php echo $this->sale->currency->name; ?> (<?php echo JText::_('SPR_SL_EXCHANGERATE'); ?>: 1:<?php echo $this->sale->currency->xe; ?>)</span>
    </div>
    
    <div class="spr_field">
    <label><?php echo JText::_('SPR_SL_SHIPMETHOD'); ?></label>
    <span><?php echo $this->sale->shipping->name; ?></span>
    </div>
    
    <div class="spr_field">
    <label><?php echo JText::_('SPR_SL_SHIPWEIGHT')." ({$this->units->weight})"; ?></label>
    <span><?php echo $this->sale->weight; ?></span>
    </div>
    
    <div class="spr_field">
    <label><?php echo JText::_('SPR_SL_SHIPSIZE')." ({$this->units->size})"; ?></label>
    <span><?php echo "{$this->sale->height} x {$this->sale->width} x {$this->sale->depth}"; ?></span>
    </div>
    
    <?php if($this->sale->note !== '') { ?>
    <div class="spr_field">
    <label for="spr_sales_note"><?php echo JText::_('SPR_SL_NOTE'); ?></label>
    <span id="spr_sales_note"><?php echo nl2br($this->sale->note); ?></span>
    </div>
    <?php } ?>
    
    <div class="spr_field">
    <label for="spr_sales_status"><?php echo JText::_('SPR_SL_ORDERSTATUS'); ?></label>
    <select id="spr_sales_status" name="spr_sales_status">
        <?php echo sprConfig::_options($this->sale->status,$this->class->_statusOptions); ?>
    </select>
    </div>
    
    </fieldset>

    <fieldset class="spr_fieldset" style="float: left; width: 49%; clear: none; margin-bottom: 20px;">
    <h4><?php echo JText::_('SPR_SL_BILLADDRESS'); ?></h4>
    
    <?php echo $this->sale->user_bill_name; ?><br />
    <?php echo $this->sale->user_bill_address; ?><br />
    <?php echo $this->sale->user_bill_address2; ?><br />
    <?php echo $this->sale->user_bill_town; ?><br />
    <?php echo $this->sale->user_bill_postcode; ?><br />
    <?php if($this->sale->user_bill_state > 0) echo sprRegions::_getName($this->sale->user_bill_state)."<br />"; ?>
    <?php echo sprRegions::_getName($this->sale->user_bill_country); ?><br />
    <?php echo $this->sale->user_bill_phone; ?><br />
    </fieldset>
    
    <fieldset class="spr_fieldset" style="float: right; width: 49%; clear: none; margin-bottom: 20px;">
    
    <h4><?php echo JText::_('SPR_SL_DELADDRESS'); ?></h4>
    
    <?php echo $this->sale->user_del_name; ?><br />
    <?php echo $this->sale->user_del_address; ?><br />
    <?php echo $this->sale->user_del_address2; ?><br />
    <?php echo $this->sale->user_del_town; ?><br />
    <?php echo $this->sale->user_del_postcode; ?><br />
    <?php if($this->sale->user_del_state > 0) echo sprRegions::_getName($this->sale->user_del_state)."<br />"; ?>
    <?php echo sprRegions::_getName($this->sale->user_del_country); ?><br />
    <?php echo $this->sale->user_del_phone; ?><br />
    
    </fieldset>
    
    <fieldset class="spr_fieldset">
    <h4><?php echo JText::_('SPR_SL_ORDERITEMS'); ?></h4>
    <table class="spr_table" id="salesList">
    <thead>
        <tr>
            <th width="1%" class="nowrap center"><?php echo JText::_('SPR_SL_ITEMID'); ?></th>
            <th align="left"><?php echo JText::_('SPR_SL_ITEMNAME'); ?></th>
            <th align="left"><?php echo JText::_('SPR_SL_ATTRIBUTES'); ?></th>
            <th align="left"><?php echo JText::_('SPR_SL_SKU'); ?></th>
            <th align="left"><?php echo JText::_('SPR_SL_CATEGORY'); ?></th>
            <th width="1%" class="nowrap center"><?php echo JText::_('SPR_SL_QUANTITY'); ?></th>
            <th width="1%" class="nowrap center"><?php echo JText::_('SPR_SL_PRICE'); ?></th>
            <th width="1%" class="nowrap center"><?php echo JText::_('SPR_SL_TAX'); ?></th>
        </tr>
    </thead>
    <tbody id="spr_sales_list">
    <?php foreach($this->sale->items as $s) {
        $attributes = json_decode($s->attributes);
        $itemopts = '';
        if(count($attributes)>0) {
            $itemopts = '<ul>';
            foreach($attributes as $attr) {
                $itemopts .= "<li>{$attr->name}: {$attr->value}</li>";
            }
            $itemopts .= '</ul>';
        }
        echo "<tr><td>{$s->id}</td><td>{$s->name}</td><td>{$itemopts}</td><td>{$s->sku}</td><td>{$s->category_name}</td><td align='center'>{$s->quantity}</td><td class='nowrap' align='right'>{$s->f_price}</td><td class='nowrap' align='right'>";

        if(count($s->tax)>0) foreach($s->taxes as $n=>$t) {
            if($n>0) echo "<br />";
            echo "{$t->name}: {$t->f_tax}";
        }
        echo "</td></tr>";
    } ?>
    <tr><th colspan='7' style='border-bottom:0 !important' align='right' nowrap='nowrap' style='border-bottom:0 !important'><?php echo JText::_('SPR_SL_SUBTOTAL'); ?></th><th align="right"><?php echo $this->sale->f_price; ?></th></tr>
    
    <?php if(count($this->sale->tax)>0) foreach($this->sale->tax as $tax) {
        echo "<tr style='border:0'><th colspan='7' align='right' style='border-bottom:0 !important' nowrap='nowrap'>{$tax->name}</th><th align='right'>{$tax->f_tax}</th></tr>";
    } ?>
    <tr style='border:0'><th colspan='7' align='right' style='border-bottom:0 !important' nowrap='nowrap'><?php echo JText::_('SPR_SL_SHIPPING'); ?></th><th align="right"><?php echo $this->sale->shipping->f_price; ?></th></tr>
    <tr style='border:0'><th colspan='7' align='right' nowrap='nowrap' style='border-bottom:0 !important'><?php echo JText::_('SPR_SL_PAYFEE'); ?></th><th align="right"><?php echo $this->sale->payment->f_fee; ?></th></tr>
    
    <tr style='border:0'><th colspan='7' th align='right' nowrap='nowrap' style='border-bottom:0 !important'><?php echo JText::_('SPR_SL_GRANDTOTAL'); ?></th><th align="right"><?php echo $this->sale->f_grandtotal; ?></th></tr>
    </tbody>
    </table>
    </fieldset>
    </div>
    </div>
    
    <div id="billing">

    <div class="spr_tip">
    <p><?php echo JText::_('SPR_SL_BILLADDRESSINFO'); ?></p>
    </div>
    
    <div class="spr_notab">
    
    <fieldset class="spr_fieldset">    
    
    <h4><?php echo JText::_('SPR_SL_BILLADDRESS'); ?></h4>    
    

    <div class="spr_field">
    <label for="spr_sales_user_bill_name"><?php echo JText::_('SPR_SL_BILLNAME'); ?></label>
    <input id="spr_sales_user_bill_name" name="spr_sales_user_bill_name" type="text" value="<?php echo $this->sale->user_bill_name; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_sales_user_bill_address"><?php echo JText::_('SPR_SL_ADDRESS'); ?></label>
    <input id="spr_sales_user_bill_address" name="spr_sales_user_bill_address" type="text" value="<?php echo $this->sale->user_bill_address; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_user_bill_address2"><?php echo JText::_('SPR_SL_ADDRESS2'); ?></label>
    <input id="spr_sales_user_bill_address2" name="spr_sales_user_bill_address2" type="text" value="<?php echo $this->sale->user_bill_address2; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_sales_user_bill_town"><?php echo JText::_('SPR_SL_TOWN'); ?></label>
    <input id="spr_sales_user_bill_town" name="spr_sales_user_bill_town" type="text" value="<?php echo $this->sale->user_bill_town; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_sales_user_bill_postcode"><?php echo JText::_('SPR_SL_POSTCODE'); ?></label>
    <input id="spr_sales_user_bill_postcode" name="spr_sales_user_bill_postcode" type="text" value="<?php echo $this->sale->user_bill_postcode; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_sales_user_bill_country"><?php echo JText::_('SPR_SL_COUNTRY'); ?></label>
    <select name="spr_sales_user_bill_country" id="spr_sales_user_bill_country">
        <?php if(count($this->regions)>0) echo sprRegions::_options($this->sale->user_bill_country, $this->regions); ?>
    </select>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_user_bill_state"><?php echo JText::_('SPR_SL_STATE'); ?></label>
    <select name="spr_sales_user_bill_state" id="spr_sales_user_bill_state">
    <option value="0">-- <?php echo JText::_('SPR_SL_SELECTCOUNTRY'); ?> --</option>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_user_bill_phone"><?php echo JText::_('SPR_SL_PHONE'); ?></label>
    <input id="spr_sales_user_bill_phone" name="spr_sales_user_bill_phone" type="text" value="<?php echo $this->sale->user_bill_phone; ?>" />
    </div>

    </fieldset>
    </div>
    </div>
    
    <div id="delivery">
    
    <div class="spr_tip">
    <p><?php echo JText::_('SPR_SL_DELADDRESSINFO'); ?></p>
    </div>
    
    <div class="spr_notab">
    <fieldset class="spr_fieldset">
    <h4><?php echo JText::_('SPR_SL_DELADDRESS'); ?></h4>

    <div class="spr_field">
    <label for="spr_sales_user_del_name"><?php echo JText::_('SPR_SL_DELNAME'); ?></label>
    <input id="spr_sales_user_del_name" name="spr_sales_user_del_name" type="text" value="<?php echo $this->sale->user_del_name; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_sales_user_del_address"><?php echo JText::_('SPR_SL_ADDRESS'); ?></label>
    <input id="spr_sales_user_del_address" name="spr_sales_user_del_address" type="text" value="<?php echo $this->sale->user_del_address; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_user_del_address2"><?php echo JText::_('SPR_SL_ADDRESS2'); ?></label>
    <input id="spr_sales_user_del_address2" name="spr_sales_user_del_address2" type="text" value="<?php echo $this->sale->user_del_address2; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_sales_user_del_town"><?php echo JText::_('SPR_SL_TOWN'); ?></label>
    <input id="spr_sales_user_del_town" name="spr_sales_user_del_town" type="text" value="<?php echo $this->sale->user_del_town; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_sales_user_del_postcode"><?php echo JText::_('SPR_SL_POSTCODE'); ?></label>
    <input id="spr_sales_user_del_postcode" name="spr_sales_user_del_postcode" type="text" value="<?php echo $this->sale->user_del_postcode; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_sales_user_del_country"><?php echo JText::_('SPR_SL_COUNTRY'); ?></label>
    <select name="spr_sales_user_del_country" id="spr_sales_user_del_country">
        <?php if(count($this->regions)>0) echo sprRegions::_options($this->sale->user_del_country, $this->regions); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_user_del_state"><?php echo JText::_('SPR_SL_STATE'); ?></label>
    <select name="spr_sales_user_del_state" id="spr_sales_user_del_state">
    <option value="0">-- <?php echo JText::_('SPR_SL_SELECTCOUNTRY'); ?> --</option>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_sales_user_del_phone"><?php echo JText::_('SPR_SL_PHONE'); ?></label>
    <input id="spr_sales_user_del_phone" name="spr_sales_user_del_phone" type="text" value="<?php echo $this->sale->user_del_phone; ?>" />
    </div>

    </fieldset>
    </div>
    </div>
</div>
</div>
<input type="hidden" name="spr_table" value="sales" />
<input type="hidden" name="spr_id" id="spr_id" value="<?php echo $this->sale->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="sales" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>

<script>
var states = new Array;
var billstate = '<?php echo $this->sale->user_bill_state; ?>';
var delstate = '<?php echo $this->sale->user_del_state; ?>';
<?php
    $string = '';
    if(count($this->regions)>0) foreach($this->regions as $id=>$region) {
        $string .= "states[{$id}]=[";
        $states = sprRegions::_getStates($id);
        if(count($states)>0) {
            $x = 0;
            foreach($states as $state) {
                if($x++ > 0) $string .= ',';
                $mystate = addslashes($state->name);
                $string .= "['{$state->id}','{$mystate}']";
            }
        }
        $string .= "];\r\n";
    }
    echo $string;
?>
function showStates($area='bill',$region='unset') {
    if($region === 'unset') {
        $region = jQuery('#spr_sales_user_'+$area+'_country').val();
    }
    var stateid = '';
    var statename = '';
    var stateoptions = '<option value="0">N/A</option>';
    var selected = 'selected="selected"';
    if(typeof states[$region] != 'undefined') {
        state = states[$region];
        if(state.length > 0) {
            stateoptions = '';
            jQuery.each(state,function(a,b) {
                jQuery.each(b,function(c,d) {
                    if(c === 0) stateid = d;
                    else if(c === 1) statename = d;
                });
                stateoptions += '<option value="'+stateid+'"';
                if(($area === 'bill' && stateid == billstate) || ($area === 'del' && stateid == delstate)) stateoptions += selected;
                stateoptions +='>'+statename+'</option>';
            });
        }
    }
    jQuery('#spr_sales_user_'+$area+'_state').html(stateoptions);
}
jQuery(document).ready(function() {
    showStates('del');
    showStates('bill');
    jQuery('#spr_sales_user_del_country').change(function() {
        showStates('del',jQuery(this).val());
    });
    jQuery('#spr_sales_user_bill_country').change(function() {
        showStates('bill',jQuery(this).val());
    });
});
</script>