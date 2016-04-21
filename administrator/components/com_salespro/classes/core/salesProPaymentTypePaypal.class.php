<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class salesProPaymentTypePaypal extends salesProPaymentType {
    
    public $defaultParams = array(
        'api'=>'0',
        'apiurl'=>'https://www.paypal.com/cgi-bin/webscr',
        'apiseller'=>'',
        'apititle'=>'Checkout',
        'sboxurl'=>'https://www.sandbox.paypal.com/cgi-bin/webscr',
        'sboxseller'=>'',
        'sboxtitle'=>'Sandbox Checkout',
    );
    
    //STANDARD PAYPAL VARIABLES + DB VARIABLES
    public $_vars = array(
        'id'=>array('int',11),
        'address_city'=>array('string',40),
        'address_country'=>array('string',64),
        'address_country_code'=>array('string',2),
        'address_name'=>array('string',128),
        'address_state'=>array('string',40),
        'address_status'=>array('string',20),
        'address_street'=>array('string',200),
        'address_zip'=>array('string',20),
        'btn_id'=>array('int',11),
        'business'=>array('string',127),
        'charset'=>array('string',50),
        'contact_phone'=>array('string',20),
        'custom'=>array('string',255),
        'discount'=>array('float',13),
        'exchange_rate'=>array('string',10),
        'first_name'=>array('string',64),
        'handling_amount'=>array('string',13),
        'invoice'=>array('string',127),
        'insurance_amount'=>array('string',13),
        'ipn_track_id'=>array('string',20),
        'item_name'=>array('string',127),
        'item_number'=>array('string',6),
        'last_name'=>array('string',64),
        'mc_currency'=>array('string',5),
        'mc_fee'=>array('float',13),
        'mc_gross'=>array('float',13),
        'mc_handling'=>array('float',13),
        'mc_shipping'=>array('float',13),
        'memo'=>array('string',255),
        'notify_version'=>array('string',5),
        'num_cart_items'=>array('int',6),
        'parent_txn_id'=>array('string',20),
        'payer_business_name'=>array('string',127),
        'payer_email'=>array('string',127),
        'payer_id'=>array('string',13),
        'payer_status'=>array('string',20),
        'payment_date'=>array('string',11),
        'payment_status'=>array('string',20),
        'payment_type'=>array('string',20),
        'pending_reason'=>array('string',50),
        'protection_eligibility'=>array('string',30),
        'quantity'=>array('int',6),
        'reason_code'=>array('string',50),
        'receiver_email'=>array('string',127),
        'receiver_id'=>array('string',13),
        'receipt_id'=>array('string',50),
        'resend'=>array('string',50),
        'residence_country'=>array('string',2),
        'shipping'=>array('float',13),
        'shipping_discount'=>array('float',13),
        'shipping_method'=>array('string',127),
        'tax'=>array('float',13),
        'test_ipn'=>array('int',1),
        'transaction_entity'=>array('string',50),
        'transaction_subject'=>array('int',11),
        'txn_id'=>array('string',20),
        'txn_type'=>array('string',20),
        'verify_sign'=>array('string',50)
    );
    
    public $_table = '#__spr_paypal_txn';
    private $txn = array(
        'id' => ''
    );
    
    function __construct($id=0) {
        parent::__construct();
        
        if($id > 0) $this->option = sprPaymentOptions::_load($id);
        
        foreach($this->defaultParams as $field=>$val) {
            if(!isset($this->option->params[$field])) $this->option->params[$field] = $val;
        }
        
        if($this->option->params['api'] == '1') {
            $this->api = array(
                'url'=>$this->option->params['sboxurl'],
                'seller'=>$this->option->params['sboxseller'],
                'title'=>$this->option->params['sboxtitle']
            );
        } else {
            $this->api = array(
                'url'=>$this->option->params['apiurl'],
                'seller'=>$this->option->params['apiseller'],
                'title'=>$this->option->params['apititle']
            );
        }
        if(isset($_POST['txn_id'])) $this->processTxn();
    }

    function beginPayment($sale_id,$user,$cart) {

        $paymethod = (int) $cart->payment->id;
        
        $notifyUrl = JUri::base().'index.php?option=com_salespro&task=processPayment&method='.$paymethod.'&format=raw';
        $returnUrl = JUri::base().'index.php?option=com_salespro&view=thankyou';
        $cancelUrl = JUri::base().'index.php?option=com_salespro&view=basket';
        
        //CONVERT TAXES & PRICES IF NEEDED - EUROPEAN STYLE ONLY
        $price = $cart->totals->price;
        $tax = $cart->totals->tax;
        if(sprConfig::_load('core')->taxes != '2') {
            $price = $price - $tax;
        }
        
        $currency = sprCurrencies::_getActive();

        $data = array (
            'cmd' => '_ext-enter',
            'redirect_cmd' => '_xclick',
            'business' => $this->api['seller'],
            'quantity' => '1',
            'item_name' => $this->api['title'],
            'item_number' => $sale_id,
            'amount' => $price,
            'shipping' => $cart->shipping->price,
            'handling' => $cart->payment->fee,
            'tax' => $tax,
            'no_note' => '1',
            'custom' => $cart->_hash,
            'notify_url' => $notifyUrl,
            'return' => $returnUrl,
            'cancel_return' => $cancelUrl,
            'image_url' => '',
            'cs' => '0',
            'currency_code' => $currency->code,
            'no_note' => '1',
            'email' => $user->email
        );
        
        if($cart->virtual === 0) $data += array(
            'address_override' => '1',
            'first_name' => $user->name,
            'last_name' => '',
            'address1' => $user->del_address,
            'address2' => $user->del_address2,
            'city' => $user->del_town,
            'state' => $user->del_state_code2,
            'country' => $user->del_country_code2,
            'zip' => $user->del_postcode
        );
        $string = '';
        foreach($data as $var=>$val) $string .= '&'.$var.'='.urlencode($val);
        header("location:{$this->api['url']}?{$string}");
        die();
    }
    
    function processTxn() {
        $op = ob_start();
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval) {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value) {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
                $value = urlencode(stripslashes($value)); 
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }
        $ch = curl_init($this->api['url']);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
        if(!($res = curl_exec($ch))) {
            curl_close($ch);
            exit;
        }
        curl_close($ch);
        if (strcmp ($res, "VERIFIED") == 0) {
            $this->savePPTxn();
        } else {
             $this->logProblem();
        }
    }
    
    private function savePPTxn() {
        /* /// SAVE PAYPAL TRANSACTION TO DATABASE /// */
       	foreach($this->_vars as $field=>$val) {
       		if(isset($_POST[$field])) {
                $val = iconv($_POST['charset'], "UTF-8", $_POST[$field]);
                $this->txn[$field]=$val;
       		}
       	}
        $id = $this->db->insertData($this->_table,$this->txn);
        $txn = json_encode($this->txn);        
        $this->processPayment($this->txn['item_number'],$this->txn['custom']);
    }
    
    private function processPayment($saleid=0,$hash='') {
        if(!isset($this->txn['payment_status'])) $this->logProblem();
        switch($this->txn['payment_status']) {
            case 'Canceled_Reversal': $this->completePayment($saleid,$hash);
                break;
            case 'Completed': $this->completePayment($saleid,$hash);
                break;
            case 'Created': $this->completePayment($saleid,$hash);
                break;
            case 'Denied': $this->cancelPayment($saleid,$hash);
                break;
            case 'Expired': $this->cancelPayment($saleid,$hash);
                break;
            case 'Failed': $this->cancelPayment($saleid,$hash);
                break;
            case 'Pending': $this->pendingPayment($saleid,$hash);
                break;
            case 'Refunded': $this->refundPayment($saleid,$hash);
                break;
            case 'Reversed': $this->refundPayment($saleid,$hash);
                break;
            case 'Processed': $this->completePayment($saleid,$hash);
                break;
            case 'Voided': $this->cancelPayment($saleid,$hash);
                break;
            default: $this->logProblem();
                break;
        }
    }

    private function logProblem() {
        $address = $this->api['seller'];
        $subject = ".JText::_('SPR_PP_FAILED').";
        $msg = "<p><strong>.JText::_('SPR_PP_PROBLEM').</strong></p>";
        $msg .= "<p>.JText::_('SPR_PP_REVIEW').</p>";
        $msg .= "<p>".JText::_('SPR_PP_DETAILS').":</p><p>";
        foreach($_POST as $a=>$b) {
            $msg .= "<strong>{$a}:</strong> {$b}<br />";
        }
        $msg .= "</p>";
        $mail = new salesProMailer;
        if(!$mail->setFrom($address)) return FALSE;
        if(!$mail->setTo($address)) return FALSE;
        if(!$mail->setSubject($subject)) return FALSE;
        if(!$mail->setBody($msg)) return FALSE;
        try {
            $res = $mail->send();
        }
        catch (Exception $e) {
            //JUST CONTINUE
        }
    }
}