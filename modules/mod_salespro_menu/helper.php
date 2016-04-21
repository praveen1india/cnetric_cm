<?php
/* -------------------------------------------
Module: mod_salespro_search
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die;

require_once JPATH_ADMINISTRATOR . '/components/com_salespro/classes/core/salesPro.class.php';

class ModSalesProMenuHelper extends salesPro {

    function __construct() {
        parent::__construct();
    }
    
	function getList($currencies=array()) {
        $list = array();

        $list[] = JText::_('MOD_SALESPRO_MENU_WELCOME')." <strong>".sprConfig::_load('core')->name."</strong>";
        
        //$list[] = "<a href='index.php?option=com_salespro&view=account'>Your Account</a>";
        //$list[] = "<a href='index.php?option=com_salespro&view=help'>Help</a>";
        
        //GET CURRENCIES
        $currencies = sprCurrencies::_load();
        
        //GET ACTIVE CURRENCY
        $currency = (int) sprCurrencies::_getActive()->id;
        
        if(count($currencies)>0) {
            $string = "<select name='spr_currency_select' class='currency_select'>";
            foreach($currencies as $c) {
                if($c->status !== '1') continue;
                $sel = ($currency == $c->id) ? "selected='selected'":"";
                $string .= "<option value='{$c->id}' {$sel}>$c->symbol $c->code</option>";
            }
            $string .= "</select>";
    
            $list[] = $string;
        }
        
        $cart = new salesProCart;
        $cart->buildCart();
        $link = JRoute::_('index.php?option=com_salespro&view=basket');
        $list[] = "<a href='{$link}'><img src='".JURI::base()."modules/mod_salespro_menu/basket.png'>&nbsp;{$cart->totals->quantity} ".JText::_('MOD_SALESPRO_MENU_ITEMS')."</a>";
        return $list;
	}
}