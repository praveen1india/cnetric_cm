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
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
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
    <li><a href="#details"><?php echo JText::_('SPR_USR_DETAILS_TAB'); ?></a></li>
    <li><a href="#billing"><?php echo JText::_('SPR_USR_BILLING_TAB'); ?></a></li>
    <li><a href="#delivery"><?php echo JText::_('SPR_USR_DELIVERY_TAB'); ?></a></li>
</ul>

<div id="details">

    <div class="spr_notab">
    <fieldset class="spr_fieldset">
    <h4><?php echo JText::_('SPR_USR_DETAILS'); ?></h4>
    <div class="spr_field">
    <label><?php echo JText::_('SPR_USR_NAME'); ?></label>
    <span><strong><?php echo $this->user->name; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_users_email"><?php echo JText::_('SPR_USR_EMAIL'); ?></label>
    <span><strong><?php echo $this->user->email; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_users_added"><?php echo JText::_('SPR_USR_DATEADDED'); ?></label>
    <span><strong><?php echo $this->user->registerDate; ?></strong></span>
    </div>
    
    <div class="spr_field">
    <label for="spr_users_status"><?php echo JText::_('SPR_USR_ENABLED'); ?></label>
    <span><strong><div class='spr_icon spr_icon_<?php echo ($this->user->block === '0') ? 'yes' : 'no'; ?>' style='float: left;'>&nbsp;</div></strong></span>
    </select>
    </div>
    <a href='index.php?option=com_users&task=user.edit&id=<?php echo $this->user->id; ?>'><strong><?php echo JText::_('SPR_USR_EDITTHIS'); ?></strong></a>
    
    </fieldset>
    </div>
    </div>
    
    <div id="billing">
    <div class="spr_notab">
    <fieldset class="spr_fieldset">
    <h4><?php echo JText::_('SPR_USR_BILLING'); ?></h4>

    <div class="spr_field">
    <label for="spr_users_bill_name"><?php echo JText::_('SPR_USR_BILLNAME'); ?></label>
    <input id="spr_users_bill_name" name="spr_users_bill_name" type="text" value="<?php echo $this->user->bill_name; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_users_bill_address"><?php echo JText::_('SPR_USR_ADDRESS'); ?></label>
    <input id="spr_users_bill_address" name="spr_users_bill_address" type="text" value="<?php echo $this->user->bill_address; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_users_bill_address2"><?php echo JText::_('SPR_USR_ADDRESS2'); ?></label>
    <input id="spr_users_bill_address2" name="spr_users_bill_address2" type="text" value="<?php echo $this->user->bill_address2; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_users_bill_town"><?php echo JText::_('SPR_USR_TOWN'); ?></label>
    <input id="spr_users_bill_town" name="spr_users_bill_town" type="text" value="<?php echo $this->user->bill_town; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_users_bill_postcode"><?php echo JText::_('SPR_USR_POSTCODE'); ?></label>
    <input id="spr_users_bill_postcode" name="spr_users_bill_postcode" type="text" value="<?php echo $this->user->bill_postcode; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_users_bill_country"><?php echo JText::_('SPR_USR_COUNTRY'); ?></label>
    <select name="spr_users_bill_country" id="spr_users_bill_country">
        <?php if(count($this->regions)>0) echo sprRegions::_options($this->user->bill_country, $this->regions); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_users_bill_state"><?php echo JText::_('SPR_USR_STATE'); ?></label>
    <select name="spr_users_bill_state" id="spr_users_bill_state">
    <option value="0">-- <?php echo JText::_('SPR_USR_SELECTCOUNTRY'); ?> --</option>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_users_bill_phone"><?php echo JText::_('SPR_USR_PHONE'); ?></label>
    <input id="spr_users_bill_phone" name="spr_users_bill_phone" type="text" value="<?php echo $this->user->bill_phone; ?>" />
    </div>

    </fieldset>
    </div>
    </div>
    
    <div id="delivery">
    <div class="spr_notab">
    <fieldset class="spr_fieldset">
    <h4><?php echo JText::_('SPR_USR_DELIVERY'); ?></h4>

    <div class="spr_field">
    <label for="spr_users_del_name"><?php echo JText::_('SPR_USR_DELNAME'); ?></label>
    <input id="spr_users_del_name" name="spr_users_del_name" type="text" value="<?php echo $this->user->del_name; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_users_del_address"><?php echo JText::_('SPR_USR_ADDRESS'); ?></label>
    <input id="spr_users_del_address" name="spr_users_del_address" type="text" value="<?php echo $this->user->del_address; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_users_del_address2"><?php echo JText::_('SPR_USR_ADDRESS2'); ?></label>
    <input id="spr_users_del_address2" name="spr_users_del_address2" type="text" value="<?php echo $this->user->del_address2; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_users_del_town"><?php echo JText::_('SPR_USR_TOWN'); ?></label>
    <input id="spr_users_del_town" name="spr_users_del_town" type="text" value="<?php echo $this->user->del_town; ?>" />
    </div>
    
    <div class="spr_field">
    <label for="spr_users_del_postcode"><?php echo JText::_('SPR_USR_POSTCODE'); ?></label>
    <input id="spr_users_del_postcode" name="spr_users_del_postcode" type="text" value="<?php echo $this->user->del_postcode; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_users_del_country"><?php echo JText::_('SPR_USR_COUNTRY'); ?></label>
    <select name="spr_users_del_country" id="spr_users_del_country">
        <?php if(count($this->regions)>0) echo sprRegions::_options($this->user->del_country, $this->regions); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_users_del_state"><?php echo JText::_('SPR_USR_STATE'); ?></label>
    <select name="spr_users_del_state" id="spr_users_del_state">
    <option value="0">-- <?php echo JText::_('SPR_USR_SELECTCOUNTRY'); ?> --</option>
    </select>
    </div>

    <div class="spr_field">
    <label for="spr_users_del_phone"><?php echo JText::_('SPR_USR_PHONE'); ?></label>
    <input id="spr_users_del_phone" name="spr_users_del_phone" type="text" value="<?php echo $this->user->del_phone; ?>" />
    </div>

    </fieldset>
    </div>
    </div>
</div>
</div>
    
<input type="hidden" name="spr_table" value="users" />
<input type="hidden" name="spr_id" value="<?php echo $this->user->id; ?>" />
<input type="hidden" name="view" value="users" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>

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
    showStates('bill');
    jQuery('#spr_users_bill_country').change(function() {
        showStates('bill',jQuery(this).val());
    });
    showStates('del');
    jQuery('#spr_users_del_country').change(function() {
        showStates('del',jQuery(this).val());
    });
});

function showStates($area='bill',$region='unset') {
    if($region === 'unset') {
        $region = jQuery('#spr_users_'+$area+'_country').val();
    }
    var stateid = '';
    var statename = '';
    var stateoptions = '<option value="0"><?php echo JText::_('SPR_NA'); ?></option>';
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
    jQuery('#spr_users_'+$area+'_state').html(stateoptions);
}
</script>

