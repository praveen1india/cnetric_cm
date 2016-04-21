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
        <li class='current'>
            <a href='<?php echo JRoute::_('index.php?option=com_salespro&view=checkout&layout=delivery'); ?>'><?php echo JText::_('SPR_DELIVERY'); ?></a>
        </li>
        <li>
            <a><?php echo JText::_('SPR_PAYMENT'); ?></a>
        </li>
        <li>
            <a><?php echo JText::_('SPR_COMPLETE'); ?></a>
        </li>
    </ul>
</nav>

<form action="" method="post" name="sprCheckoutForm" id="sprCheckoutForm">
<div id="checkout_step3" class="checkout_step">

<div class="checkout_box">
<h3><?php echo JText::_('SPR_ENTER_DELIVERY'); ?></h3>
<br /><br />
<div class="spr_field">
<input type="checkbox" id="spr_users_del_usebilling" value="1" style="width:auto !important;" />&nbsp; <label for="spr_users_del_usebilling" style="width:auto !important; margin:6px 20px !important;"><?php echo JText::_('SPR_USE_BILLING'); ?>: </label>
</div>
<div class="spr_field">
<label for="spr_users_del_name"><?php echo JText::_('SPR_NAME'); ?>: </label><input type="text" name="spr_users_del_name" id="spr_users_del_name" value="<?php echo $this->user->del_name; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_del_address"><?php echo JText::_('SPR_ADDRESS'); ?>: </label><input type="text" name="spr_users_del_address" id="spr_users_del_address" value="<?php echo $this->user->del_address; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_del_address2"><?php echo JText::_('SPR_ADDRESS2'); ?>: </label><input type="text" name="spr_users_del_address2" id="spr_users_del_address2" value="<?php echo $this->user->del_address2; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_del_town"><?php echo JText::_('SPR_TOWN'); ?>: </label><input type="text" name="spr_users_del_town" id="spr_users_del_town" value="<?php echo $this->user->del_town; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_del_postcode"><?php echo JText::_('SPR_POSTCODE'); ?>: </label><input type="text" name="spr_users_del_postcode" id="spr_users_del_postcode" value="<?php echo $this->user->del_postcode; ?>" />
</div>
<div class="spr_field">
<label for="spr_users_del_country"><?php echo JText::_('SPR_COUNTRY'); ?>: </label>
<select name="spr_users_del_country" id="spr_users_del_country">
    <?php if(count($this->regions)>0) echo sprRegions::_options($this->user->del_country, $this->regions); ?>
</select>
</div>

<div class="spr_field">
<label for="spr_users_del_state"><?php echo JText::_('SPR_STATE'); ?>: </label>
<select name="spr_users_del_state" id="spr_users_del_state">
<option value="0"><?php echo JText::_('SPR_SELECT_A_COUNTRY'); ?></option>
</select>
</div>

<div class="spr_field">
<label for="spr_users_del_phone"><?php echo JText::_('SPR_PHONE'); ?>: </label><input type="text" name="spr_users_del_phone" id="spr_users_del_phone" value="<?php echo $this->user->del_phone; ?>" />
</div>

<div class="spr_field" style="max-width:100%; margin-top: 20px;">
    <a class="spr_checkout_submit"><?php echo JText::_('SPR_CONTINUE_TO_PAYMENT'); ?></a>
</div>

</div>
</div>
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value="processCheckout"  />
<input type="hidden" name="view" value="checkout" />
<input type="hidden" name="layout" value="payment" />

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
    showStates('del','unset');
    jQuery('#spr_users_del_country').change(function() {
        showStates('del',jQuery(this).val());
    });

<?php
    
    if(is_array($this->errorfields)) {
        foreach($this->errorfields as $e) {
            echo "jQuery('#spr_users_{$e}').addClass('highlight');\n";
        }
    }
?>

    jQuery('#spr_users_del_usebilling').click(function() {
        if(jQuery(this).attr('checked') === 'checked') {
            var array = new Array;
            <?php foreach($this->user as $field=>$var) {
                if(strpos($field,'bill_') !== FALSE) {
                    $field = str_replace('bill_','del_',$field);
                    $var = addslashes(htmlspecialchars($var));
                    echo "jQuery('#spr_users_{$field}').val('{$var}');\n";
                }
            } ?>
            showStates('del','unset');
            jQuery('#spr_users_del_state').val(<?php echo $this->user->bill_state; ?>);
        }
    });
});
</script>


<?php echo JHTML::_( 'form.token' ); ?>
</form>