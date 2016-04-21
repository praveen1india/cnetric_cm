<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_PM_HEADINGS'), 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );

$currencies = sprCurrencies::_load();
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#18"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<form action="" method="post" name="adminForm" id="adminForm">

<div id="spr_tabs">
    <div class="spr_tabs">
    <ul>
        <li><a href="#setup"><?php echo JText::_('SPR_PM_SETUP'); ?></a></li>
        <li><a href="#currencies"><?php echo JText::_('SPR_PM_CURRENCIES'); ?></a></li>
        <li><a href="#normal"><?php echo JText::_('SPR_PM_API'); ?></a></li>
        <li><a href="#sandbox"><?php echo JText::_('SPR_PM_APISANDBOX'); ?></a></li>
    </ul>

    <div id="setup">
    
    <div class="spr_tip">
        <p><?php echo JText::_('SPR_BRAINTREE_ABOUT').' <a href="https://developers.braintreepayments.com/javascript+php/reference/general/currencies" target="_blank">'.JText::_('SPR_PM_OKCURRENCIES').'</a>'; ?></p>
    </div>
    
    <div class="spr_notab">

    <fieldset class="spr_fieldset">
    
    <div class="spr_field">
    <label for="spr_payment_options_id"><?php echo JText::_('SPR_PM_ID'); ?></label>
    <span id="spr_payment_options_id"><strong><?php echo $this->option->id; ?></strong></span>
    </div>
    
    <div class="spr_field"> 
    <label for="spr_payment_options_type"><?php echo JText::_('SPR_PM_TYPE'); ?></label>
    <span id="spr_payment_options_type"><strong><?php echo $this->option->method->name; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_payment_options_class"><?php echo JText::_('SPR_PM_PCLASS'); ?></label>
    <span id="spr_payment_options_class"><strong><?php echo $this->option->method->class; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_payment_options_name"><?php echo JText::_('SPR_PM_NAME'); ?></label>
    <input id="spr_payment_options_name" name="spr_payment_options_name" type="text" value="<?php echo $this->option->name; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_payment_options_status"><?php echo JText::_('SPR_PM_ACCEPTED'); ?></label>
    <select id="spr_payment_options_status" name="spr_payment_options_status">
        <?php echo sprConfig::_options($this->option->status,'yesno'); ?>
    </select>
    </div>
    
        
    <div class="spr_field">
    <label for="spr_payment_options_params[api]"><?php echo JText::_('SPR_PM_APIACTIVE'); ?></label>
    <select id="spr_payment_options_params[api]" name="spr_payment_options_params[api]">
        <?php echo sprConfig::_options($this->option->params['api'],'api'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_payment_options_fee_type"><?php echo JText::_('SPR_PM_EXTRA_FEE'); ?></label>
    <select id="spr_payment_options_fee_type" name="spr_payment_options_fee_type">
        <?php echo sprConfig::_options($this->option->fee_type,array(JText::_('SPR_PM_FREE'),JText::_('SPR_PM_FIXEDCOST'))); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_payment_options_fee"><?php echo JText::_('SPR_PM_FEE'); ?></label>
    <input id="spr_payment_options_fee" name="spr_payment_options_fee" type="text" value="<?php echo $this->option->fee; ?>" style="width: 150px;" />
    </div>
    
    <div class="spr_field">
        <label for="spr_payment_options_info" style="margin-bottom: 20px;"><?php echo JText::_('SPR_PM_INFO'); ?></label>
        <textarea id="spr_payment_options_info" name="spr_payment_options_info" placeholder="E.g. '<?php echo JText::_('SPR_PM_PP_SAMPLE'); ?>'"><?php echo $this->option->info; ?></textarea>
    </div>
    
    <?php if(strlen($this->option->method->about)>0) { ?>
    <div class="spr_field">
        <label for="spr_payment_options_about" style="margin-bottom: 20px;"><?php echo JText::_('SPR_PM_ABOUT'); ?></label>
        <span id="spr_payment_options_about"><?php echo JText::_($this->option->method->about); ?></span>
    </div>
    <?php } ?>
    
    </fieldset>
    </div>
    </div>
    
    <div id="currencies">
    
    <div class="spr_tip"><p><?php echo JText::_('SPR_BRAINTREE_CURRENCYSETUP'); ?></p></div>
    
    <div class="spr_tab_right" id="createCurrencies" style="display: none;">
    
    <fieldset class="spr_fieldset">
        <h4><?php echo JText::_('SPR_PM_ADDCURRENCY'); ?></h4>
        <input type="hidden" id="r_id" value="" />
        <div class="spr_field">
            <label for="b_currency"><?php echo JText::_('SPR_PM_CURRENCY'); ?></label>
            <select id="b_currency" name="b_currency">
            <?php if(count($currencies)>0) foreach($currencies as $c) {
                if($c->status !== '1') continue;
                $array = array($c->id=>$c->name);
                echo sprCurrencies::_options(0,$array);
            } ?>
            </select>
        </div>
        <div class="spr_field">
            <label for="b_mercid"><?php echo JText::_('SPR_BRAINTREE_MERCACCT'); ?></label>
            <input id="b_mercid" style="width: 148px;" type="text" />
        </div>

        <div class="spr_confirm_buttons">
            <a href="#" onclick="cancelCurrency();" class="spr_cancel_button"><?php echo JText::_('SPR_CANCEL'); ?></a>
            <a href="#" onclick="saveCurrency();" class="spr_submit_button"><?php echo JText::_('SPR_SAVE'); ?></a>
        </div>    
    </fieldset>
    </div>
    
    <div class="spr_tab_left">

    <div class="spr_big_button" id="createCurrency" style="float:right"><?php echo JText::_('SPR_PM_ADDCURRENCY'); ?></div>
    <h4><br /><?php echo JText::_('SPR_PM_ACCCURRENCIES'); ?></h4>

    <table class="spr_table" id="ruleList" style="clear:both">
        <thead>
            <tr>
                <th align="left"><?php echo JText::_('SPR_PM_CURRENCYNAME'); ?></th>
                <th align="left"><?php echo JText::_('SPR_BRAINTREE_MERCACCT'); ?></th>
                <th width="1%" class="nowrap center"><?php echo JText::_('SPR_SHP_ACTIONS'); ?></th>
            </tr>
        </thead>
        <tbody id="spr_currencies_list">
        <?php
         foreach($this->option->params['currencies'] as $id=>$acct) {
            $currency = sprCurrencies::_load($id);
            echo "<tr id='spr_currency_{$id}' b_currency='{$id}' b_mercid='{$acct}'>
                <td>{$currency->name}</td>
                <td>{$acct}</td>
                <td width='1%' class='nowrap center'><input type='hidden' name='spr_payment_options_params[currencies][{$id}]' value='{$acct}' /><a href='#' onclick='editCurrency({$id})' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteCurrency({$id})' class='spr_icon spr_icon_delete'>&nbsp;</a></td>
                </tr>";
            }
        ?>
        </tbody>
    </table>
    
    </div>
    </div>
    
    <div id="normal">
    
    <div class="spr_tip">
        <p><?php echo JText::_('SPR_BRAINTREE_APIKEYS'); ?> <a href="https://www.braintreegateway.com/" target="_blank">Braintree &gt; Account &gt; My user &gt; Authorization &gt; API Keys</a></p>
    </div>
    
    <div class="spr_notab">

        <fieldset class="spr_fieldset">
        
        <div class="spr_field">
        <label for="spr_payment_options_params[merchant]"><?php echo JText::_('SPR_BRAINTREE_MERCID'); ?></label>
        <input id="spr_payment_options_params[merchant]" name="spr_payment_options_params[merchant]" type="text" value="<?php echo $this->option->params['merchant']; ?>" placeholder="my_main_braintree_merchant_id" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[pubkey]"><?php echo JText::_('SPR_BRAINTREE_PUBKEY'); ?></label>
        <input id="spr_payment_options_params[pubkey]" name="spr_payment_options_params[pubkey]" type="text" value="<?php echo $this->option->params['pubkey']; ?>" placeholder="my_braintree_public_key" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[prikey]"><?php echo JText::_('SPR_BRAINTREE_PRIKEY'); ?></label>
        <input id="spr_payment_options_params[prikey]" name="spr_payment_options_params[prikey]" type="text" value="<?php echo $this->option->params['prikey']; ?>" placeholder="my_braintree_private_key" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[paypal]"><?php echo JText::_('SPR_BRAINTREE_ACCPP'); ?></label>
        <select id="spr_payment_options_params[paypal]" name="spr_payment_options_params[paypal]">
        <?php echo sprPaymentOptions::_options($this->option->params['paypal'], 'yesno'); ?>
        </select>
        </div>
        
        </fieldset>
    </div>
    </div>
    
    <div id="sandbox">
    
    <div class="spr_tip">
        <p><?php echo JText::_('SPR_BRAINTREE_SBOXAPIKEYS'); ?> <a href="https://sandbox.braintreegateway.com/" target="_blank">Braintree Sandbox &gt; Account &gt; My user &gt; Authorization &gt; API Keys</a></p>
    </div>
    <div class="spr_notab">
        <fieldset class="spr_fieldset">
        
        <div class="spr_field">
        <label for="spr_payment_options_params[sboxmerchant]"><?php echo JText::_('SPR_BRAINTREE_MERCID'); ?></label>
        <input id="spr_payment_options_params[sboxmerchant]" name="spr_payment_options_params[sboxmerchant]" type="text" value="<?php echo $this->option->params['sboxmerchant']; ?>" placeholder="my_braintree_sandbox_merchant_id" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[sboxpubkey]"><?php echo JText::_('SPR_BRAINTREE_PUBKEY'); ?></label>
        <input id="spr_payment_options_params[sboxpubkey]" name="spr_payment_options_params[sboxpubkey]" type="text" value="<?php echo $this->option->params['sboxpubkey']; ?>" placeholder="my_braintree_sandbox_public_key" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[sboxprikey]"><?php echo JText::_('SPR_BRAINTREE_PRIKEY'); ?></label>
        <input id="spr_payment_options_params[sboxprikey]" name="spr_payment_options_params[sboxprikey]" type="text" value="<?php echo $this->option->params['sboxprikey']; ?>" placeholder="my_braintree_sandbox_private_key" />
        </div>

        <div class="spr_field">
        <label for="spr_payment_options_params[sboxpaypal]"><?php echo JText::_('SPR_BRAINTREE_ACCPP'); ?></label>
        <select id="spr_payment_options_params[sboxpaypal]" name="spr_payment_options_params[sboxpaypal]">
        <?php echo sprPaymentOptions::_options($this->option->params['sboxpaypal'], 'yesno'); ?>
        </select>
        </div>
        
        </fieldset>
    </div>
    </div>

</div>
</div>

<input type="hidden" name="spr_table" value="payment_options" />
<input type="hidden" name="spr_id" id="spr_id" value="<?php echo $this->option->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="payment_options" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>


<script>
/* /// CURRENCIES /// */
jQuery(document).ready(function() {
    jQuery('#createCurrency').click(function() {
        jQuery('#createCurrencies').fadeIn();
    });
});

function cancelCurrency() {
    jQuery('#createCurrencies').fadeOut();
}

function editCurrency($id) {
    var rule = jQuery("#spr_currency_"+$id);
    var array = new Array('b_currency','b_mercid');
    jQuery.each(array,function(a,b){
        jQuery('#'+b).val(rule.attr(b));
    });
    jQuery('#createCurrencies').fadeIn();
}

function saveCurrency() {
    var b_currencyid = jQuery('#b_currency').val();
    var b_currencyname = jQuery('#b_currency option:selected').html();
    var b_mercid = jQuery('#b_mercid').val();
    var string = "<tr id='spr_currency_"+b_currencyid+"' b_currency='"+b_currencyid+"' b_mercid='"+b_mercid+"'><td>"+b_currencyname+"</td><td>"+b_mercid+"</td><td width='1%' class='nowrap center'><input type='hidden' name='spr_payment_options_params[currencies]["+b_currencyid+"]' value='"+b_mercid+"' /><a href='#' onclick='editCurrency("+b_currencyid+")' class='spr_icon spr_icon_edit'>&nbsp;</a> <a href='#' onclick='deleteCurrency("+b_currencyid+")' class='spr_icon spr_icon_delete'>&nbsp;</a></td></tr>";
    if(jQuery('#spr_currency_'+b_currencyid).length > 0) {
        jQuery('#spr_currency_'+b_currencyid).before(string).remove();
    } else {
        jQuery('#spr_currencies_list').append(string);
    }
    jQuery('#b_mercid').val('');
}


function deleteCurrency($id) {
    var id = $id;
    jQuery("#spr_currency_"+$id).remove();
}
</script>