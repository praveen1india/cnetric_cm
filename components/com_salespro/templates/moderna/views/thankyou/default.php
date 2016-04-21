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
<p><?php echo JText::_('SPR_WELCOME'); ?>, <?php echo $this->user->name; ?> (<a href='index.php?option=com_salespro&view=checkout&task=logout'><?php echo JText::_('SPR_NOTYOU'); ?></a>)</p>
<nav id="checkout_steps">
<ul>
<li class='done'><a href='#'><?php echo JText::_('SPR_LOGIN'); ?></a></li>
<li class='done'><a href='#'><?php echo JText::_('SPR_BILLING'); ?></a></li>
<li class='done'><a href='#'><?php echo JText::_('SPR_DELIVERY'); ?></a></li>
<li class='done'><a href='#'><?php echo JText::_('SPR_PAYMENT'); ?></a></li>
<li class='current'><a><?php echo JText::_('SPR_COMPLETE'); ?></a></li>
</ul>
</nav>
<div id="checkout_step3" class="checkout_step">
<div class="checkout_box">
<h3 style='text-align:center'><?php echo $this->thankyou->title; ?></h3>
<br />
<?php echo $this->thankyou->content; ?>
</div></div>
<?php echo sprWidgets::_showWidgets('thankyou'); ?>
</div>