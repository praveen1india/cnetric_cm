<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProCart extends salesPro {
    public $_table = '#__spr_carts';
    public $_cartid = 0;
    public $_hash = '';
    public $_ip = '';
    public $_vars = array(
        'id' => array('int', 11),
        'hash' => array('string', 6),
        'date' => array('datetime'), 
        'ip' => array('string', 20)
    );
    public $virtual = 0;
    public $tc = 0;
    public $ship_methods = array();
    public $pay_options = array();
    public $tax_details = array();
    public $payment = array();
    public $shipping = array();

    function __construct($hash = '', $id = 0) {
        parent::__construct();
        $this->startCart($hash, $id);
    }
    private function startCart($hash = '', $id = 0) {

        //START A NEW COOKIE
        sprCookies::_start();

        //CHECK CART IF HASH IS GIVEN
        if ($hash !== '') {
            if ($id === 0) $cart = $this->db->getResult($this->_table, 'id', array('hash' => $hash));
            if ((int)$cart > 0) {
                $this->_cartid = $cart;
                $this->_hash = $hash;
                return TRUE;
            }
        }
        //OTHERWISE CHECK AGAINST ACTIVE COOKIE
        $this->_cartid = sprCookies::_getVar('cartid');
        $this->_hash = sprCookies::_getVar('carthash');
        //CHECK THE ACTIVE CART IS VALID
        if ($this->_cartid !== false && $this->_hash !== false) {
            $cart = $this->db->getResult($this->_table, 'id', array('hash' => $this->_hash));
            if ((int)$cart === (int)$this->_cartid) {
                return TRUE;
            }
        }
        //ELSE CREATE THE NEW CART DATA
        $this->_hash = $this->uniqId('salesProCart', 6);
        $data = array('hash' => $this->_hash, 'date' => $this->_dateTime, 'ip' => $_SERVER['REMOTE_ADDR']);
        $this->_cartid = (int)$this->db->insertData($this->_table, $data);
        sprCookies::_setVar('carthash', $this->_hash);
        sprCookies::_setVar('cartid', $this->_cartid);
        return TRUE;
    }
    function buildCart() { //POPULATE THE CART OBJECT WITH ALL KNOWN DATA

        //GET ALL THE ITEMS
        $this->items = $this->getItems();
        
        //GET THE CART TOTALS
        $this->totals = $this->getTotals();

        //GET THE USER (IF LOGGED IN) OR RETURN
        $users = new salesProUsers;
        if (!$user = $users->getActiveUser()) {
            $this->formatTotals();
            return;
        }

        //GET THE TAX DETAILS
        $this->tax_details = $this->getTaxDetails($user->del_region_id);
        
        //GET THE AVAILABLE PAYMENT OPTIONS
        $this->pay_options = sprPaymentOptions::_loadActive();

        //VALIDATE THE PAYMENT OPTIONS
        if(count($this->pay_options)>0) foreach($this->pay_options as $n=>$p) {
            if($p->method->alias === 'freecheckout' && $this->totals->price > 0.00) unset($this->pay_options[$n]);
            //CHECK THE SELECTED PAYMENT OPTION
            if(isset($_POST['payment_option'])) {
                $pay_option = (int)$_POST['payment_option'];
                if(!isset($this->pay_options[$n])) continue;
                $temp = (int)$this->pay_options[$n]->id;
                if($pay_option === $temp) {
                    $this->payment = (array)$p;
                    break;
                }
            }
        }
        $this->payment = (object)$this->payment; //QUICK FIX TO BREAK OBJECT REFERENCE
        
        //GET VALID SHIPPING METHODS
        if($this->virtual === 0) {
            $shippings = new salesProShipping;
            $this->ship_methods = $shippings->getValidShippingMethods($user->del_region_id,$this->totals,$this->pay_options);

            //CHECK THE SELECTED SHIPPING METHOD & PRICE
            $ship_method = (isset($_POST['shipping_method'])) ? (int)$_POST['shipping_method'] : 0;
            $this->shipping = $shippings->validateShippingMethod($ship_method, $user->del_region_id, $this->totals);
        }

        //CALCULATE THE CURRENCY ADJUSTED TOTALS
        $this->formatTotals();
        
        //CHECK IF T AND C ARE REQUIRED
        foreach($this->items as $i) {
            if($i->data->params->tc === '1') $this->tc = 1;
        }
    }

    private function getTaxDetails($region_id = 0) {

        $this->totals->tax = 0;
        
        $tax_details = array();

        if(count($this->items)>0) foreach($this->items as $i) {
            $taxes = json_decode($i->data->taxes);
            $i->tax = 0;
            if(count($taxes) > 0) {
                if((int)$region_id !== 0 && $item_tax = sprTaxes::getTaxesByRegion($taxes,$region_id,$i->price)) foreach($item_tax as $tax) {
                    if(isset($tax_details[$tax->id])) {
                        $tax_details[$tax->id]->tax += $i->quantity * $tax->tax;
                    } else {
                        $tax_details[$tax->id] = $tax;
                        $tax_details[$tax->id]->tax = $i->quantity * $tax->tax;
                    }
                    $i->tax_details[] = $tax;
                    $i->tax += $tax->tax;
                } else {
                    $i->tax_details = array();
                    $i->tax = 0;
                }
            }
        }
        return $tax_details;
    }
    
    private function getTotals() {
        $totals = array(
            'quantity' => 0,
            'price' => 0,
            'xe_price' => 0,
            'f_price' => 0,
            'grandtotal' => 0,
            'weight' => 0,
            'height' => 0,
            'width' => 0,
            'depth' => 0
        );
        foreach ($this->items as $i) {
            $totals['quantity'] += (int) $i->quantity;
            $totals['price'] += (float) ($i->quantity * $i->price);
            $totals['weight'] += (float) ($i->quantity * $i->data->weight);
            $area = array($i->data->height,$i->data->width,$i->data->depth);
            sort($area);
            if($area[0] > $totals['height']) $totals['height'] = (int)$area[0];
            if($area[1] > $totals['width']) $totals['width'] = (int)$area[1];
            if($area[2] > 0) $totals['depth'] += (int)$area[2];
        }
        $totals['grandtotal'] = $totals['price'];
        return (object) $totals;
    }

    function formatTotals() {

        //FORMAT ALL PAYMENT OPTION PRICES
        if(count($this->pay_options)>0) foreach($this->pay_options as $p) {
            $p->xe_fee = sprCurrencies::_toXe($p->fee);
            $p->f_fee = sprCurrencies::_format($p->xe_fee);
        }
        
        //FORMAT ALL SHIPPING METHOD PRICES
        if(count($this->ship_methods)>0) foreach($this->ship_methods as $s) {
            $s->xe_price = sprCurrencies::_toXe($s->price);
            $s->f_price = sprCurrencies::_format($s->xe_price);
        }

        //FORMAT SELECTED SHIPPING METHOD PRICE
        if (isset($this->shipping->price)) {
            $this->shipping->xe_price = sprCurrencies::_toXe($this->shipping->price);
            $this->shipping->f_price = sprCurrencies::_format($this->shipping->xe_price);
            $this->totals->grandtotal += $this->shipping->price;
        }
        
        //FORMAT SELECTED PAYMENT OPTION PRICE
        if (isset($this->payment->fee)) {
            $this->payment->xe_fee = sprCurrencies::_toXe($this->payment->fee);
            $this->payment->f_fee = sprCurrencies::_format($this->payment->xe_fee);
            $this->totals->grandtotal += $this->payment->fee;
        }
        
        //FORMAT RELEVANT TAX DETAILS
        if(count($this->tax_details)>0) foreach($this->tax_details as $t) {
            $t->xe_tax = sprCurrencies::_toXe($t->tax);
            $t->f_tax = sprCurrencies::_format($t->xe_tax);
            if(sprConfig::_load('core')->taxes === '2') $this->totals->grandtotal += $t->tax;
        }
        
        //FORMAT OTHER PRICES AND TOTALS        
        $this->totals->xe_price = sprCurrencies::_toXe($this->totals->price);
        $this->totals->f_price = sprCurrencies::_format($this->totals->xe_price);
        $this->totals->xe_grandtotal = sprCurrencies::_toXe($this->totals->grandtotal);
        $this->totals->f_grandtotal = sprCurrencies::_format($this->totals->xe_grandtotal);
    }
    
    function addItem($item = 0, $variant = 0, $quantity = 1) {
        $item = (int)$item;
        $variant = (int)$variant;
        $quantity = (int)$quantity;
        $cart_items = new salesProCartItems;
        $ret = $cart_items->addItem($item, $variant, $quantity, $this->_cartid, $this->_hash);
        $this->buildCart();
        return $ret;
    }
    function removeCartItem($id = 0) {
        $id = (int)$id;
        $cart_items = new salesProCartItems;
        $ret = $cart_items->removeCartItem($id, $this->_cartid, $this->_hash);
        $this->buildCart();
    }
    function getItems() {
        $object = array();
        $cart_items = new salesProCartItems;
        $object = $cart_items->getItems($this->_cartid, $this->_hash);
        if(sizeof($object)>0) foreach($object as $o) {
            if($o->data->params->del == '2') $this->virtual = 1;
        }
        return (object)$object;
    }
    function setQuantity($id = 0, $quantity = 0) {
        $cart_items = new salesProCartItems;
        $cart_items->setQuantity($id, $quantity, $this->_cartid, $this->_hash);
        $this->buildCart();
    }
    function emptyCart() {
        $cart_items = new salesProCartItems;
        $cart_items->emptyCart($this->_cartid, $this->_hash);
        $this->buildCart();
    }
    function destroyCart() {
        $this->emptyCart();
        $this->db->deleteData($this->_table, array('hash' => $this->_hash, 'id' => $this->_cartid));
        sprCookies::_delVar('carthash');
        sprCookies::_delVar('cartid');
        $this->buildCart();
    }
}
