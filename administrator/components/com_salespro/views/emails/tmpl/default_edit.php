<?php

/* -------------------------------------------
Component: com_SalesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
$editor = JFactory::getEditor();
JToolBarHelper::title(JText::_('COM_SALESPRO').': '.JText::_('SPR_EMAIL_HEADING'), 'salespro');
JToolBarHelper::custom( 'cancel', 'cancel', 'cancel', JText::_('SPR_CANCEL'), 0,0 );
JToolBarHelper::custom( 'save', 'save', 'save', JText::_('SPR_SAVE'), 0,0 );
?>

<div id="spr_container">
<?php echo JHtmlSidebar::render(); ?>

<div id="spr_content">

<div id="spr_header">
    <a href="#" onclick="showPanel();"><img src="components/com_salespro/resources/css/images/icons/icon-menu.png" class="spr_menu_icon" /></a>
    <a target="_blank" href="components/com_salespro/salespro.pdf#16"><img src="components/com_salespro/resources/images/question-mark-icon.png" class="spr_help_icon" /></a>
    <h1><?php echo $this->title; ?></h1>
</div>

<div id="spr_main">

<div class="spr_tip">
<p><?php echo JText::_('SPR_EMAIL_STYLING'); ?></p>
</div>

<div class="spr_tab_right">
    <p><?php echo JText::_('SPR_EMAIL_VARIABLES'); ?>:</p>
    <table class="spr_table">
    <tr><th align="left">Variable</th><th align="left"><?php echo JText::_('SPR_EMAIL_DESC'); ?></th></tr>
    <tr><td>{user_name}</td><td><?php echo JText::_('SPR_EMAIL_UNAME'); ?></td></tr>
    <tr><td>{user_email}</td><td><?php echo JText::_('SPR_EMAIL_UEMAIL'); ?></td></tr>
    <tr><td>{note}</td><td><?php echo JText::_('SPR_EMAIL_NOTE'); ?></td></tr>
    <tr><td>{order_details}</td><td><?php echo JText::_('SPR_EMAIL_ORDERDETAILS'); ?></td></tr>
    <tr><td>{order_date}</td><td><?php echo JText::_('SPR_EMAIL_ORDERDATE'); ?></td></tr>
    <tr><td>{order_status}</td><td><?php echo JText::_('SPR_EMAIL_ORDERSTATUS'); ?></td></tr>
    <tr><td>{total_quantity}</td><td><?php echo JText::_('SPR_EMAIL_ORDERCOUNT'); ?></td></tr>
    <tr><td>{total_weight}</td><td><?php echo JText::_('SPR_EMAIL_ORDERWEIGHT'); ?></td></tr>
    <tr><td>{net_price}</td><td><?php echo JText::_('SPR_EMAIL_ORDERPRICE'); ?></td></tr>
    <tr><td>{shipping_type}</td><td><?php echo JText::_('SPR_EMAIL_ORDERSMETHOD'); ?></td></tr>
    <tr><td>{shipping_price}</td><td><?php echo JText::_('SPR_EMAIL_ORDERSPRICE'); ?></td></tr>
    <tr><td>{taxes}</td><td><?php echo JText::_('SPR_EMAIL_ORDERTAXES'); ?></td></tr>
    <tr><td>{grand_total}</td><td><?php echo JText::_('SPR_EMAIL_ORDERGROSS'); ?></td></tr>
    <tr><td>{billing_name}</td><td><?php echo JText::_('SPR_EMAIL_BILLNAME'); ?></td></tr>
    <tr><td>{delivery_name}</td><td><?php echo JText::_('SPR_EMAIL_DELNAME'); ?></td></tr>
    <tr><td>{billing_address}</td><td><?php echo JText::_('SPR_EMAIL_BILLADDRESS'); ?></td></tr>
    <tr><td>{delivery_address}</td><td><?php echo JText::_('SPR_EMAIL_DELADDRESS'); ?></td></tr>
    <tr><td>{items}</td><td><?php echo JText::_('SPR_EMAIL_ORDERITEMS'); ?></td></tr>
    <tr><td>{download_link}</td><td>If the order includes downloadable files, this gives a link to the downloads area.</td></tr>
    
    </table>
    <p>&nbsp;</p>
</div>

<div class="spr_tab_left">
    <form action="" method="post" name="adminForm" id="adminForm">

    <fieldset class="spr_fieldset">

    <div class="spr_field">
    <label for="spr_emails_subject"><?php echo JText::_('SPR_EMAIL_SUBJECT'); ?></label>
    <input id="spr_emails_subject" name="spr_emails_subject" type="text" value="<?php echo $this->email->subject; ?>" />
    </div>

    <div class="spr_field">
    <label for="spr_emails_from"><?php echo JText::_('SPR_EMAIL_ADDRESS'); ?></label>
    <input id="spr_emails_from" name="spr_emails_from" type="text" value="<?php echo $this->email->from; ?>" placeholder="e.g. your@email.com" />
    </div>
    
    <div class="spr_field">
    <label for="spr_emails_trigger"><?php echo JText::_('SPR_EMAIL_TRIGGER'); ?></label>
    <select id="spr_emails_trigger" name="spr_emails_trigger">
        <?php echo $this->class->selectOptions($this->email->trigger,'emailtrigger'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_emails_prodtypes[]"><?php echo JText::_('SPR_EMAIL_PRODTYPES'); ?></label>
    <select id="spr_emails_prodtypes[]" name="spr_emails_prodtypes[]" multiple="multiple">
        <?php echo $this->class->selectOptions($this->email->prodtypes,$this->prodtypes); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_emails_copy"><?php echo JText::_('SPR_EMAIL_COPY'); ?></label>
    <select id="spr_emails_copy" name="spr_emails_copy">
        <?php echo $this->class->selectOptions($this->email->copy,'yesno'); ?>
    </select>
    </div>
    
    <div class="spr_field">
    <label for="spr_emails_status"><?php echo JText::_('SPR_EMAIL_ENABLE'); ?></label>
    <select id="spr_emails_status" name="spr_emails_status">
        <?php echo $this->class->selectOptions($this->email->status,'yesno'); ?>
    </select>
    </div>
   
    <div class="spr_field">
    <label for="spr_emails_content"><?php echo JText::_('SPR_EMAIL_TEMPLATE'); ?></label>
    </div>
    <div class="editor">
    <?php echo $editor->display('spr_emails_content', $this->email->content, '100%', '400', '70', '15',false); ?>
    <div>

    
    </div>
    </div>
    </div>
    </fieldset>

<input type="hidden" name="spr_table" value="emails" />
<input type="hidden" name="spr_id" value="<?php echo $this->email->id; ?>" />
<input type="hidden" name="extension" value="com_salespro" />
<input type="hidden" name="task" value=""  />
<input type="hidden" name="view" value="emails" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div>
</div>
</div>
</div>