<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

//INTERFACE TO DEFINE PAYMENT TYPES - IMPLEMENT AS BASIS OF A NEW PAYMENT TYPE
//PLEASE NOTE: SALES STATUS TYPES ARE DEFINED IN salesProClassSales
abstract class salesProPaymentType extends salesPro {
    function __construct() {
        parent::__construct();
    }
    function completePayment($saleid, $hash) {
        //THIS PAYMENT IS AUTOMATICALLY ACCEPTED AND UPDATES THE SALE STATUS TO COMPLETE (E.G. PAYPAL CHECKOUT)
        $sales = new salesProSales;
        $sale = $sales->getSale($saleid);
        //VALIDATION CHECK(!)
        if ($sale->hash !== $hash) return false;
        //REMOVE QUANTITY OF ITEMS FROM STOCK
        foreach ($sale->items as $item) {
            sprItems::removeStock($item->item_id, $item->quantity);
        }
        //REMOVE CART FROM DB
        $cart = new salesProCart($hash);
        $cart->destroyCart();
        //UPDATE THE STATUS
        $data = array('status' => '1');
        $sales->saveData($sale->id, $data);
        //REDIRECT TO THANK YOU PAGE
        $this->redirect(JURI::root().'index.php?option=com_salespro&view=thankyou');
        return true;
    }
    function refundPayment($saleid, $hash) {
        $sales = new salesProSales;
        $sale = $sales->getSale($saleid);
        //VALIDATION CHECK(!)
        if ($sale->hash !== $hash) return false;
        //RESTORE ITEMS TO STOCK
        foreach ($sale->items as $item) {
            sprItems::addStock($item->item_id,$item->quantity,$item->variant_id);
        }
        //BUILD THE DATA
        $data = array('status' => '2');
        $sales->saveData($sale->id, $data);
        return true;
    }
    function cancelPayment($saleid, $hash) {
        $sales = new salesProSales;
        $sale = $sales->getSale($saleid);
        //VALIDATION CHECK(!)
        if ($sale->hash !== $hash) return false;
        //BUILD THE DATA
        $data = array('status' => '3');
        $sales->saveData($sale->id, $data);
        return true;
    }
    function pendingPayment($saleid, $hash) {
        //THIS PAYMENT NEEDS INDEPENDANT VERIFICATION E.G. CASH / CHEQUE / BANK TRANSFER
        $sales = new salesProSales;
        $sale = $sales->getSale($saleid);
        //VALIDATION CHECK(!)
        if ($sale->hash !== $hash) return false;
        //REMOVE QUANTITY OF ITEMS FROM STOCK
        foreach ($sale->items as $item) {
            sprItems::removeStock($item->item_id,$item->quantity,$item->variant_id);
        }
        //REMOVE CART FROM DB
        $cart = new salesProCart($hash);
        $cart->destroyCart();
        //UPDATE THE STATUS
        $data = array('status' => '4');
        $sales->saveData($sale->id, $data);
        //REDIRECT TO THANKYOU PAGE
        $this->redirect(JURI::root().'index.php?option=com_salespro&view=thankyou');
        return true;
    }
    function partialRefund($saleid, $hash) {
        $sales = new salesProSales;
        $sale = $sales->getSale($saleid);
        //VALIDATION CHECK(!)
        if ($sale->hash !== $hash) return false;
        //BUILD THE DATA
        $data = array('status' => '5');
        $sales->saveData($sale->id, $data);
        return true;
    }
}