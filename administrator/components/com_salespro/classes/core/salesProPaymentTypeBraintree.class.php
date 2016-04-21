<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class salesProPaymentTypeBraintree extends salesProPaymentType {
    
    public $defaultParams = array(
        'api' => '1',
        'merchant'=>'',
        'pubkey'=>'',
        'prikey'=>'',
        'paypal' => '1',
        'sboxmerchant'=>'',
        'sboxpubkey'=>'',
        'sboxprikey'=>'',
        'sboxpaypal' => '1',
        'currencies'=>array()
    );
    public $api = array();
    
    function __construct($id=0) {

        parent::__construct();
        if($id > 0) {
            $this->id = $id;
            $this->option = sprPaymentOptions::_load($id);
        }
        
        foreach($this->defaultParams as $field=>$val) {
            if(!isset($this->option->params[$field])) $this->option->params[$field] = $val;
        }
        if($this->option->params['api'] == '1') {
            $this->api = array(
                'environment'=>'sandbox',
                'merchant'=>$this->option->params['sboxmerchant'],
                'pubkey'=>$this->option->params['sboxpubkey'],
                'prikey'=>$this->option->params['sboxprikey']
            );
        } else {
            $this->api = array(
                'environment'=>'live',
                'merchant'=>$this->option->params['merchant'],
                'pubkey'=>$this->option->params['pubkey'],
                'prikey'=>$this->option->params['prikey']
            );
        }
    }
    
    function genToken() {
        require_once($this->_adminpath.'lib/Braintree/Braintree.php');
            Braintree_Configuration::environment($this->api['environment']);
            Braintree_Configuration::merchantId($this->api['merchant']);
            Braintree_Configuration::publicKey($this->api['pubkey']);
            Braintree_Configuration::privateKey($this->api['prikey']);
        return Braintree_ClientToken::generate();
    }
    
    function checkout_dialog() {
        $ret = "<div class='braintree_pay'>";
        $ret .= "<script src='https://js.braintreegateway.com/v2/braintree.js'></script><div id='dropin' style='width:  100%; border-top: 1px dashed #ccc; border-bottom: 1px dashed #ccc; margin: 20px 0; padding: 20px 0 ;'></div><script> braintree.setup('".$this->genToken()."', 'dropin', { container: 'dropin' } );</script></div>";
        return $ret;
    }

    function beginPayment($saleid,$user,$cart) {
        
        $currencies = (array) $this->option->params['currencies'];
        $myaccount = 0;
        foreach($currencies as $x=>$y) {
            if($cart->currency->id === $x) {
                $myaccount = $y;
                break;
            }
        }
        if($myaccount === 0) {
            return JText::_('SPR_BRAINTREE_ERROR').' 0: '.JText::_('SPR_BRAINTREE_BADCURRENCY');
        }

        //CONVERT TAXES & PRICES IF NEEDED - EUROPEAN STYLE ONLY
        $price = $cart->totals->price;
        $data = array (
        'merchantAccountId' => $myaccount,
        'amount' => $price,
        'orderId' => $saleid,
        
        'paymentMethodNonce' => 'nonce-from-the-client',
        'customer' => array(
            'firstName' => $user->name,
            'lastName' => '',
            'company' => '',
            'phone' => $user->bill_phone,
            'fax' => '',
            'website' => '',
            'email' => $user->email
        ),
        'billing' => array(
            'firstName' => $user->bill_name,
            'lastName' => '',
            'company' => '',
            'streetAddress' => $user->bill_address,
            'extendedAddress' => $user->bill_address2,
            'locality' => $user->bill_town,
            'region' => $user->region->name,
            'postalCode' => $user->bill_postcode,
            'countryCodeAlpha2' => $user->region->code_2
        ),
        'shipping' => array(
            'firstName' => $user->del_name,
            'lastName' => '',
            'company' => '',
            'streetAddress' => $user->del_address,
            'extendedAddress' => $user->del_address2,
            'locality' => $user->del_town,
            'region' => $user->del_state_code,
            'postalCode' => $user->del_postcode,
            'countryCodeAlpha2' => $user->del_country_code2
        ),
        'options' => array(
            'submitForSettlement' => true
        ),
            'channel' => 'SalesPro'
        );
        require_once($this->_adminpath.'lib/Braintree/Braintree.php');
        try {
            Braintree_Configuration::environment($this->api['environment']);
            Braintree_Configuration::merchantId($this->api['merchant']);
            Braintree_Configuration::publicKey($this->api['pubkey']);
            Braintree_Configuration::privateKey($this->api['prikey']);
        } catch (Exception $e) {
            return JText::_('SPR_BRAINTREE_ERROR').' 1: '.$e->getMessage();
        }
        
        try {
            $result = Braintree_Transaction::sale($data);
        } catch (Exception $e) {
            return JText::_('SPR_BRAINTREE_ERROR').' 2: '.JText::_('SPR_BRAINTREE_CURRENCYCONFIG').$e->getMessage();
        }
        if($result->success !== TRUE) {
            return JText::_('SPR_BRAINTREE_ERROR').' 3: '.$result->_attributes['message'];
        }
        return $this->completePayment($saleid,$cart->_hash);
    }
}