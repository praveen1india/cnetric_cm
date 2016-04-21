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

<?php if($this->origin === 'checkout') { ?>
<nav id="checkout_steps">
    <ul>
        <li class='current'>
            <a><?php echo JText::_('SPR_LOGIN'); ?></a>
        </li>
        <li>
            <a><?php echo JText::_('SPR_BILLING'); ?></a>
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

<br /><br />

<?php } ?>

<h3><?php echo $this->title; ?></h3>

<div id="spr_login_view">

<div id="spr_login_form">
<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" name="sprCheckoutForm" id="sprCheckoutForm">
<input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />

<div class="spr_field">
<label for="spr_login_username"><?php echo JText::_('SPR_USERNAME'); ?>: </label><input type="text" name="username" id="spr_login_username" value="" autocomplete="off" />
</div>

<div class="spr_field">
<label for="spr_login_password"><?php echo JText::_('SPR_PASSWORD'); ?>: </label><input type="password" name="password" id="spr_login_password" value="" autocomplete="off" />
</div>

<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
<?php echo JHTML::_( 'form.token' ); ?>

<div class="spr_field" style='padding-top: 10px;'>
    <a class="spr_checkout_submit" style="float:right; margin-right: 5px;"><?php echo JText::_('SPR_LOGIN'); ?></a>
    
    <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset&return='.$this->redirectUrl); ?>" style="float:left"><?php echo JText::_('SPR_FORGOT_PASSWORD'); ?></a><br /><a href="<?php echo JRoute::_('index.php?option=com_users&view=remind&return='.$this->redirectUrl); ?>" style="float: left"><?php echo JText::_('SPR_FORGOT_USERNAME'); ?></a>
</div>

</form>
</div>

<div id="spr_login_register">
<h3><?php echo JText::_('SPR_NEED_ACCOUNT'); ?></h4>
<br />
<a href='<?php echo JRoute::_('index.php?option=com_users&view=registration&return='.$this->redirectUrl); ?>' class="spr_button moderna_background2 moderna_color2"><?php echo JText::_('SPR_REGISTER'); ?></a>
</div>

</div>

</fieldset>
</div>