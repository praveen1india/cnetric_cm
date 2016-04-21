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

class ModSalesProBasketHelper extends salesPro {

    function __construct() {
        parent::__construct();
    }
    
    function status() {
        return true;
    }
    
	function getBasket() {

        $basket = "";
        $cart = new salesProCart;
        $cart->buildCart();
        
        if($cart->totals->quantity<1) {
            $basket = "<p>Your basket is empty</p>";
        } else {
            $basket .= "<ul class='spr_basket_items'>";
            foreach($cart->items as $item) {
                $link = sprItems::_directLink($item->item_id,$item->data->name,$item->data->alias,$item->data->category,$item->data->category_name,$item->data->category_alias);
                $basket .= "<li class='spr_basket_item'><a href='{$link}'>{$item->data->name}</a></li>";
            }
            //$basket .= "<li class='spr_basket_total'>Total: {$cart->totals->gross_price_formatted}";
            $basket .= "</ul>";
            $link = JRoute::_('index.php?option=com_salespro&view=checkout');
            $basket .= "<p class='spr_basket_action'><a href='{$link}' class='spr_basket_checkout'>Checkout Now</a></p>";
        }

        return $basket;
	}
}