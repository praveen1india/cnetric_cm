<?php
/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

$next_layout = ($this->cart->virtual === 0) ? 'delivery' : 'payment';
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
        <li class='current'>
            <a href='<?php echo JRoute::_('index.php?option=com_salespro&view=checkout&layout=billing'); ?>'><?php echo JText::_('SPR_BILLING'); ?></a>
        </li>
        <?php if($this->cart->virtual === 0) { ?>
        <li>
            <a><?php echo JText::_('SPR_DELIVERY'); ?></a>
        </li>
        <?php } ?>        
        <li>
            <a><?php echo JText::_('SPR_PAYMENT'); ?></a>
        </li>
        <li>
            <a><?php echo JText::_('SPR_COMPLETE'); ?></a>
        </li>
    </ul>
</nav>

<form action="" method="post" name="sprCheckoutForm" id="sprCheckoutForm">
<div id="checkout_step2" class="checkout_step">
<h3><?php echo JText::_('SPR_ENTER_BILLING'); ?></h3>
<br /><br />
<div class="checkout_box">
<div class="spr_field">
<label for="spr_users_spr_users_bill_name"><?php echo JText::_('SPR_NAME'); ?>: </label><input type="text" name="spr_users_bill_name" id="spr_users_bill_name" value="<?php echo $this->user->bill_name; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_bill_address"><?php echo JText::_('SPR_ADDRESS'); ?>: </label><input type="text" name="spr_users_bill_address" id="spr_users_bill_address" value="<?php echo $this->user->bill_address; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_bill_address2"><?php echo JText::_('SPR_ADDRESS2'); ?>: </label><input type="text" name="spr_users_bill_address2" id="spr_users_bill_address2" value="<?php echo $this->user->bill_address2; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_bill_town"><?php echo JText::_('SPR_TOWN'); ?>: </label><input type="text" name="spr_users_bill_town" id="spr_users_bill_town" value="<?php echo $this->user->bill_town; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_bill_postcode"><?php echo JText::_('SPR_POSTCODE'); ?>: </label><input type="text" name="spr_users_bill_postcode" id="spr_users_bill_postcode" value="<?php echo $this->user->bill_postcode; ?>" />
</div>

<div class="spr_field">
<label for="spr_users_bill_country"><?php echo JText::_('SPR_COUNTRY'); ?>: </label>
<select name="spr_users_bill_country" id="spr_users_bill_country">
    <?php if(count($this->regions)>0) echo sprRegions::_options($this->user->bill_country, $this->regions); ?>
</select>
</div>

<div class="spr_field">
<label for="spr_users_bill_state">State: </label>
<select name="spr_users_bill_state" id="spr_users_bill_state">
<option><?php echo JText::_('SPR_SELECT_A_COUNTRY'); ?></option>
</select>
</div>

<div class="spr_field">
<label for="spr_users_bill_phone"><?php echo JText::_('SPR_PHONE'); ?>: </label><input type="text" name="spr_users_bill_phone" id="spr_users_bill_phone" value="<?php echo $this->user->bill_phone; ?>" />
</div>

<div class="spr_field" style="max-width:100%; margin-top: 20px;">
    <a class="spr_checkout_submit"><?php echo JText::_('SPR_CONTINUE_TO_DELIVERY'); ?></a>
</div>

</div>
</div>
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value="processCheckout"  />
<input type="hidden" name="view" value="checkout" />
<input type="hidden" name="layout" value="<?php echo $next_layout; ?>" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

<script>
var states = new Array;
var billstate = '<?php echo $this->user->bill_state; ?>';
var delstate = '<?php echo $this->user->del_state; ?>';
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
jQuery(document).ready(function() {
    showStates('bill','unset');
    jQuery('#spr_users_bill_country').change(function() {
        showStates('bill',jQuery(this).val());
    });
<?php
    if(is_array($this->errorfields)) {
        foreach($this->errorfields as $e) {
            echo "jQuery('#spr_users_{$e}').addClass('highlight');\n";
        }
    }
?>

});
</script>