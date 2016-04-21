<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

?>
<div id="salespro" class="salespro">

<p>
    <?php echo JText::_('SPR_WELCOME'); ?>, <?php echo $this->user->name; ?> (<a href='<?php echo JRoute::_('index.php?option=com_salespro&view=checkout&task=logout'); ?>'><?php echo JText::_('SPR_NOTYOU'); ?></a>)
</p>

<nav id="checkout_steps">
    <ul>
        <li class='done'>
            <a><?php echo JText::_('SPR_LOGIN'); ?></a>
        </li>
        <li class='done'>
            <a href='<?php echo JRoute::_('index.php?option=com_salespro&view=checkout&layout=billing'); ?>'><?php echo JText::_('SPR_BILLING'); ?></a>
        </li>
    <?php if($this->cart->virtual === 0) { ?>
        <li class='done'>
            <a href='<?php echo JRoute::_('index.php?option=com_salespro&view=checkout&layout=delivery'); ?>'><?php echo JText::_('SPR_DELIVERY'); ?></a>
        </li>
    <?php } ?>
        <li class='current'>
            <a href='<?php echo JRoute::_('index.php?option=com_salespro&view=checkout&layout=payment'); ?>'><?php echo JText::_('SPR_PAYMENT'); ?></a>
        </li>
        <li>
            <a><?php echo JText::_('SPR_COMPLETE'); ?></a>
        </li>
    </ul>
</nav>

<form action="" method="post" name="sprBasketForm" id="sprBasketForm">
<input type="hidden" name="cart_item" id="cart_item" value="" />
<input type="hidden" id="cart_quantity" name="cart_quantity" value="" />
</form>

<form action="" method="post" name="sprCheckoutForm" id="sprCheckoutForm">
<div id="checkout_step3" class="checkout_step">

<div class="checkout_box">
<h3><?php echo JText::_('SPR_CONFIRM_ORDER'); ?></h3>

<div style="float: left; margin-right: 20px;">
<p><strong><?php echo JText::_('SPR_BILLING'); ?></strong> <a href='<?php echo JRoute::_('index.php?option=com_salespro&view=checkout&layout=billing'); ?>'>[<?php echo JText::_('SPR_EDIT_DETAILS'); ?>]</a><br />
<?php
foreach($this->class->_displayFields as $a) {
    $field = 'bill_'.$a;
    if(isset($this->user->$field) && strlen($this->user->$field)>0) {
        echo $this->user->$field;
        echo ($a === 'state_name') ? ' ':'<br />';
    }
} ?>
</div>

<?php if($this->cart->virtual === 0) { ?>

<div style="float: right; margin-right: 20px; width: 45%;">
<p><strong><?php echo JText::_('SPR_DELIVERY'); ?></strong> <a href='<?php echo JRoute::_('index.php?option=com_salespro&view=checkout&layout=delivery'); ?>'>[<?php echo JText::_('SPR_EDIT_DETAILS'); ?>]</a><br />
<?php
foreach($this->class->_displayFields as $a) {
    $field = 'del_'.$a;
    if(isset($this->user->$field) && strlen($this->user->$field)>0) {
        echo $this->user->$field;
        echo ($a === 'state_name') ? ' ':'<br />';
    }
} ?>
</div>
<?php } ?>

<div class="spr_basket">
<?php

if($this->cart->totals->quantity>0) {
    foreach($this->cart->items as $i) {
        $attributes = '';
        if(count($i->data->attributes)>0) {
            $attributes .= '<ul>';
            foreach($i->data->attributes as $attribute) {
                $attributes .= "<li>{$attribute->name}: {$attribute->value}</li>";
            }
            $attributes .= '</ul>';
        }
        $display_quantity = (($i->data->params->quantity) === '2') ? 'style="display:none !important;"' : '';
        echo "
<div class='spr_basket_item' item='{$i->id}'>
    <div class='spr_basket_item_image'>
        <img src='".salesProImage::_($i->data->image, 120, 90)."' />
    </div>
    <div class='spr_basket_item_actions'>
        <div class='spr_basket_action'>
            <h3 class='spr_basket_item_price' style='text-align:right;font-size:18px;margin:0 0 0 0;'>{$i->f_price}</h3>
        </div>
        <div class='spr_basket_action' {$display_quantity}>
            <input type='text' id='item_quantity_{$i->id}' value='{$i->quantity}' />
            <label>".JText::_('SPR_QUANTITY').": </label>
        </div>
        <div class='spr_basket_action'>
            <div class='update' style='float:right;margin:0 5px;cursor:pointer;' onclick='updateCartItem({$i->id});'>&nbsp;</div>
            <div class='delete' style='float:right;margin:0 5px;cursor:pointer;' onclick='deleteCartItem({$i->id});'>&nbsp;</div>
        </div>
    </div>
    <div class='spr_basket_item_content'>
        <h3 class='spr_basket_item_name'><a href='".sprItems::_directLink($i->data->id,$i->data->name,$i->data->alias,$i->data->category,$i->data->category_name,$i->data->category_alias)."'>{$i->data->name}</a></h3>
        {$attributes}
    </div>
</div>";
    }
} ?>
</div>

<div style="overflow: auto; clear: both;">
    <div id="shipping_info"></div>
    <div id="payment_info"></div>
</div>

<div style="float:right; width: 100%;">

<?php if($this->cart->virtual === 0) { ?>

<div class="spr_field" style="max-width: inherit !important;">
<label for="shipping_method" style="width: auto !important"><?php echo JText::_('SPR_SHIPPING_METHOD'); ?>:&nbsp;</label>
<select style="width:50% !important; min-width: 350px; float: right !important;" name="shipping_method" id="shipping_method" onchange="updatePayOptions()">
<?php
    $ship_id = (isset($_POST['shipping_method'])) ? (int)$_POST['shipping_method'] : 0;
    if (count($this->cart->ship_methods)>0) foreach($this->cart->ship_methods as $o) {
        $sel = ($o->id == $ship_id) ? 'selected="selected"':'';
        $info = (strlen($o->info)>1) ? "<p><strong>Shipping information: {$o->name}</strong></p><p>{$o->info}</p>" : "";
        echo "<option value='{$o->id}' price='{$o->xe_price}' payoptions='{$o->paymentoptions}' info='{$info}' {$sel}>{$o->name} ({$o->f_price})</option>";
    } ?>
</select>
</div>

<?php } ?>

<?php if(count($this->cart->pay_options)>0) { ?>
<div class="spr_field" style="max-width: inherit !important;">
<label for="payment_option" style="width: auto !important"><?php echo JText::_('SPR_PAYMENT_METHOD'); ?>:&nbsp;</label>
<select style="width:50% !important; min-width: 350px; float: right !important;" name="payment_option" id="payment_option" onchange="updatePaymentShipping()">
<?php 
    $pay_id = (isset($_POST['payment_option'])) ? (int)$_POST['payment_option'] : 0;
    foreach($this->cart->pay_options as $p) {
        if((int)$p->fee > 0) $fee = "(+{$p->f_fee})";
        elseif($p->fee < 0) $fee = "(-{$p->f_fee})";
        else $fee = '';
        $sel = ($p->id == $pay_id) ? 'selected="selected"':'';
        $info = (strlen($p->info)>1) ? "<p><strong>".JText::_('SPR_PAYMENT_INFORMATION').": {$p->name}</strong></p><p>{$p->info}</p>" : "";
        echo "<option value='{$p->id}' price='{$p->xe_fee}' info='{$info}' {$sel}>{$p->name} {$fee}</option>";
    } ?>
</select>
</div>

<?php } ?>

<div class="spr_field" style="max-width: inherit !important;">
    <label style="width:auto !important"><?php echo JText::_('SPR_PURCHASE_NOTE'); ?>:</label>
    <textarea style="width:50% !important; min-width: 140px; padding: 10px 0; float: right;" name="note" id="note"></textarea>
</div>

<?php if($this->cart->tc !== 0) { ?>
<div style='float: right !important; width: 50% !important;'>
    <input type="checkbox" style="width:auto !important; float: left;" id="tandc" name="tandc" value="yes" />
    <label style="width: auto!important; float: right !important;" for="tandc"><?php echo JText::_('SPR_IACCEPT'); ?> <a href="<?php echo $this->tclink; ?>" target="_blank"><?php echo JText::_('SPR_TERMSCONDITIONS'); ?></a></label>
</div>
<?php } ?>
<div class="spr_field" style="text-align: right; float: right !important;">
<?php echo JText::_('SPR_SUBTOTAL'); ?>: <?php echo $this->cart->totals->f_price; ?>
</div>

<?php if(count($this->cart->tax_details)>0) foreach($this->cart->tax_details as $tax) {
    echo "<div class='spr_field' style='text-align: right; float: right !important;'>{$tax->name}: {$tax->f_tax}</div>";
} ?>

<div class="spr_field" style='text-align: right; float: right !important;'><?php echo JText::_('SPR_TOTAL'); ?>: <span id="spr_total" price="<?php echo $this->cart->totals->xe_grandtotal; ?>"><?php echo $this->cart->totals->f_grandtotal; ?></span>
</div>

</div>

</div>
</div>
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" id="spr_task" value="completeCheckout"  />
<input type="hidden" name="view" value="checkout" />
<input type="hidden" name="layout" value="payment" />

<?php echo JHTML::_( 'form.token' ); ?>

<div style="display: block; overflow: auto; margin-right: 10px;">
    <a class="spr_checkout_submit"><?php echo JText::_('SPR_COMPLETE_PURCHASE'); ?></a>
</div>

</form>

<?php echo sprWidgets::_showWidgets('checkout'); ?>

</div>

<style>
.spr_checkout_submit {
    margin: 10px 0 !important;
    clear: both;
}
</style>

<script>
function localiseCurrency(price) {
    var symbol = '<?php echo sprCurrencies::_getActive()->symbol; ?>';
    var code = '<?php echo sprCurrencies::_getActive()->code; ?>';
    var name = '<?php echo sprCurrencies::_getActive()->name; ?>';
    var decimals = '<?php echo sprCurrencies::_getActive()->decimals; ?>';
    var thousands = '<?php echo sprCurrencies::_getActive()->thousands; ?>';
    var separator = '<?php echo sprCurrencies::_getActive()->separator; ?>';
    price = price.toFixed(decimals);
    var price_split = price.split('.');
    var main_price = price_split[0];
    var split_price = price_split[1];
    main_price = main_price.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1"+thousands);
    price = symbol+main_price+separator+split_price;
    return price;
}
function updatePayOptions() {
    var PayOptions = new Array();
<?php
    $string = '';
    if(count($this->cart->pay_options)>0) foreach($this->cart->pay_options as $p) {
        if($p->xe_fee < 0) $fee = "(-{$p->f_fee})";
        elseif($p->xe_fee > 0) $fee = "(+{$p->f_fee})";
        else $fee = '';
        $string .= "PayOptions[{$p->id}]='";
        $p->info =  trim(preg_replace('/\s\s+/', ' ', nl2br($p->info)));
        $info = (strlen($p->info)>1) ? "<p><strong>Payment information: {$p->name}</strong></p><p>{$p->info}</p>" : "";
        $string .= "<option value=\'{$p->id}\' price=\'{$p->xe_fee}\' info=\'{$info}\' {$sel}>{$p->name} {$fee}</option>";
        $string .= "';\r\n";
    }
    echo $string;
?>
    var ShipMethod = '';
    if(jQuery('#shipping_method option:selected').length > 0) ShipMethod = jQuery('#shipping_method option:selected').attr('payoptions');
    ShipMethod = jQuery.parseJSON(ShipMethod);
    var myPay = '';
    if(ShipMethod !== null && ShipMethod.length > 0) jQuery.each(ShipMethod,function(a,b) {
        myPay += PayOptions[b];
    });
    jQuery('#payment_option').html(myPay);
    updatePaymentShipping();
}
<?php if($this->cart->virtual === 0) echo 'updatePayOptions();'; ?>


//DELETE CART ITEM FUNCTION
function deleteCartItem($id) {
    var r = confirm('<?php echo JText::_('SPR_BASKET_REMOVEITEM'); ?>');
    if(r === true) {
        jQuery('#cart_item').val($id);
        jQuery('#cart_quantity').val(0);
        jQuery('#sprBasketForm').submit();
    }
}
</script>