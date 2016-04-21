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
        <li><a href="#normal"><?php echo JText::_('SPR_PM_API'); ?></a></li>
        <li><a href="#sandbox"><?php echo JText::_('SPR_PM_APISANDBOX'); ?></a></li>
    </ul>

    <div id="setup">
    
    <div class="spr_tip">
        <p><?php echo JText::_('SPR_PM_PP_SBOXTIP'); ?></p>
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
    
    <div id="normal">
    
    <div class="spr_tip">
        <p><?php echo JText::_('SPR_PM_PP_USE'); ?> <a href='http://www.paypal.com' target='_blank'>http://www.paypal.com</a></p>
    </div>
    
    <div class="spr_notab">

        <fieldset class="spr_fieldset">
        
        <div class="spr_field">
        <label for="spr_payment_options_params[apiurl]"><?php echo JText::_('SPR_PM_URL'); ?></label>
        <input id="spr_payment_options_params[apiurl]" name="spr_payment_options_params[apiurl]" type="text" value="<?php echo $this->option->params['apiurl']; ?>" placeholder="e.g. https://www.paypal.com/cgi-bin/webscr" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[apiseller]"><?php echo JText::_('SPR_PM_PPSELLER'); ?></label>
        <input id="spr_payment_options_params[apiseller]" name="spr_payment_options_params[apiseller]" type="text" value="<?php echo $this->option->params['apiseller']; ?>" placeholder="e.g. you@yoursite.com" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[apititle]"><?php echo JText::_('SPR_PM_PPTITLE'); ?></label>
        <input id="spr_payment_options_params[apititle]" name="spr_payment_options_params[apititle]" type="text" value="<?php echo $this->option->params['apititle']; ?>" placeholder="e.g. <?php echo JText::_('SPR_PM_CHECKOUT'); ?>" />
        </div>
        
        </fieldset>
    </div>
    </div>
    
    <div id="sandbox">
    
    <div class="spr_tip">
        <p><?php echo JText::_('SPR_PM_PP_SANDBOX'); ?> <a href='https://developer.paypal.com/webapps/developer/applications/accounts' target='_blank'>https://developer.paypal.com/webapps/developer/applications/accounts</a></p>
        <p><?php echo JText::_('SPR_PM_PP_SANDBOX2'); ?></p>
    </div>
    <div class="spr_notab">
        <fieldset class="spr_fieldset">
        
        <div class="spr_field">
        <label for="spr_payment_options_params[sboxurl]"><?php echo JText::_('SPR_PM_URL'); ?></label>
        <input id="spr_payment_options_params[sboxurl]" name="spr_payment_options_params[sboxurl]" type="text" value="<?php echo $this->option->params['sboxurl']; ?>" placeholder="e.g. https://www.sandbox.paypal.com/cgi-bin/webscr" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[sboxseller]"><?php echo JText::_('SPR_PM_PPSELLER'); ?></label>
        <input id="spr_payment_options_params[sboxseller]" name="spr_payment_options_params[sboxseller]" type="text" value="<?php echo $this->option->params['sboxseller']; ?>" placeholder="e.g. you@yoursite.com" />
        </div>
        
        <div class="spr_field">
        <label for="spr_payment_options_params[sboxtitle]"><?php echo JText::_('SPR_PM_PPTITLE'); ?></label>
        <input id="spr_payment_options_params[sboxtitle]" name="spr_payment_options_params[sboxtitle]" type="text" value="<?php echo $this->option->params['sboxtitle']; ?>" placeholder="e.g. <?php echo JText::_('SPR_PM_CHECKOUT'); ?>" />
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