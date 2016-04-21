<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProSales extends salesPro {
    public $_table = '#__spr_sales';
    public $saleid = 0;
    public $_vars = array(
        'id' => array('int', 11),
        'hash' => array('string', 6),
        'date' => array('datetime', 0),
        'ip' => array('string', 20),
        'user_id' => array('int', 11),
        'status' => array('int', 4), 
        'note' => array('string'),
        'quantity' => array('int', 11),
        'price' => array('float'),
        'f_price' => array('string', 20),
        'grandtotal' => array('float'),
        'f_grandtotal' => array('string', 20),
        'weight' => array('float'),
        'height' => array('float'),
        'width' => array('float'),
        'depth' => array('float'),
        'currency_details' => array('json'),
        'payment_details' => array('json'),
        'shipping_details' => array('json'),
        'tax_details' => array('json'),
        'user_email' => array('string', 100),
        'user_bill_name' => array('string', 100),
        'user_bill_address' => array('string', 100), 
        'user_bill_address2' => array('string', 100),
        'user_bill_town' => array('string', 100), 
        'user_bill_state' => array('int', 6),
        'user_bill_country' => array('int', 6),
        'user_bill_postcode' => array('string', 20), 
        'user_bill_region_id' => array('int', 11), 
        'user_bill_phone' => array('string', 20), 
        'user_del_name' => array('string', 100), 
        'user_del_address' => array('string', 100), 
        'user_del_address2' => array('string', 100),
        'user_del_town' => array('string', 100), 
        'user_del_state' => array('int', 6),
        'user_del_country' => array('int', 6),
        'user_del_postcode' => array('string', 20), 
        'user_del_region_id' => array('int', 11),
        'user_del_phone' => array('string', 20) 
    );
    public $_statusOptions = array(
        '1' => 'SPR_SALE_COMPLETED',
        '2' => 'SPR_SALE_REFUNDED',
        '3' => 'SPR_SALE_CANCELLED',
        '4' => 'SPR_SALE_PENDING',
        '5' => 'SPR_SALE_PARTIALREFUND',
        '6' => 'SPR_SALE_SHIPPED',
        '9' => 'SPR_SALE_ABANDONED'
    );
    public $_searchTerms = array(
        'status',
        'start',
        'end',
        'customer'
    );
    public $order = array('sort' => 'z.id', 'dir' => 'DESC', 'limit' => 20, 'page' => 0, 'total' => 0);
    function __construct() {
        parent::__construct();
        foreach($this->_statusOptions as $n=>$o) {
            $this->_statusOptions[$n] = JText::_($o);
        }
    }
    function getSearch($table = '', $where = array(), $joins = array(), $order = array()) {
        //SET UP THE SEARCH TERMS
        foreach ($this->_searchTerms as $s) {
            $this->search[$s] = '';
        }
        //UPDATE THE ORDERING / PAGE LIMIT
        $this->getOrder();
        $order = $this->order;
        $joins = array();
        $joins[] = array('left', '#__users', 'c', 'z.user_id', 'c.id');
        foreach ($this->_searchTerms as $field) {
            if (isset($_POST['spr_search_clear'])) continue;
            if(isset($where[$field])) {
                $val = $where[$field];
                unset($where[$field]);
            } else if(isset($_POST['spr_search_'.$field])) {
                $val = $_POST['spr_search_'.$field];
            } else {
                continue;
            }
            if (is_array($val)) continue;
            if (strlen($val) === 0) continue;
            $val = htmlspecialchars(urlencode($val));
            switch ($field) {
                    case 'status':
                        $where['z.status'] = $val;
                        break;
                    case 'start':
                        $where['z.date']['from'] = $val;
                        break;
                    case 'end':
                        $where['z.date']['to'] = $val;
                        break;
                    case 'customer':
                        $where[] = array('c.email' => "%{$val}%", 'c.name' => "%{$val}%");
                        break;
            }
            $this->search[$field] = $val;
        }

        //GET THE SEARCH RESULTS
        $this->_searchRes = parent::getSearch($table, $where, $joins, $order);
        //GET COUNT OF ALL POTENTIAL RESULTS
        $this->order['total'] = parent::getCount($table, $where, $joins);
    }
    function getSales($userid = 0) {
        $object = array();
        if($userid > 0) {
            $object = $this->db->getObjList($this->_table,'id',array('user_id'=>$userid));
            if(count($object)>0) foreach($object as $n=>$o) {
                $object[$n] = $this->getSale($o->id);
            }
        } else {
            if (count($this->_searchRes) < 1) $this->getSearch();
            foreach ($this->_searchRes as $id) {
                $object[] = $this->getSale($id);
            }
        }
        return $object;
    }
    function getSale($id = 0, $fulldetails = 1) {
        if ($id === 0) $id = $this->saleid;
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) > 0) {

            //PARSE PAYMENT OPTION DETAILS
            $object->payment = json_decode($object->payment_details);
            if(!isset($object->payment->id)) {
                $object->payment = sprPaymentOptions::_default();
            }
            
            //PARSE SHIPPING METHOD DETAILS
            $object->shipping = json_decode($object->shipping_details);
            if(!isset($object->shipping->id)) {
                $class = new salesProShipping;
                $object->shipping = $class->getDefaultObject();
            }
            
            //PARSE CURRENCY METHOD DETAILS
            $object->currency = json_decode($object->currency_details);
            if(!isset($object->currency->id)) {
                $object->currency = sprCurrency::_getDefault();
            }
            
            //PARSE TAX DETAILS
            $object->tax = json_decode($object->tax_details);
            
            //GET OTHER DETAILS
            if($fulldetails === 1) {
            
                //GET USER DETAILS
                $users = new salesProUsers;
                if ($object->user_id == '0' || !$user = $users->getUser($object->user_id)) {
                    $user = array('name' => JText::_('SPR_SALE_GUESTACCOUNT'), 'email' => $object->user_email);
                    $object->user_id = 0;
                }
                $object->user = (object)$user;
                
                //GET SALE ITEMS
                $sales_items = new salesProSalesItems;
                $object->items = $sales_items->getItems($object->id);
            }
        }
        else {
            $object = $this->getDefaultObject();
        }
        return $object;
    }
    function addSale() {
        //ERROR CODE 1 = User not logged in
        //ERROR CODE 2 = User missing details (e.g. billing address)
        //ERROR CODE 3 = Payment option is not valid
        //ERROR CODE 4 = Payment option is not active
        //ERROR CODE 5 = Cart is empty
        //ERROR CODE 6 = Delivery region is not valid
        //ERROR CODE 7 = Delivery region is not active
        //ERROR CODE 8 = Currency is not active
        //ERROR CODE 9 = Shipping method is not valid for this region
        //ERROR CODE 10 = Advanced shipping method is not correctly set up
        //ERROR CODE 11 = Payment option class cannot be found
        //ERROR CODE 12 = Terms and conditions not accepted
        //START THE SALE DATA
        $save = array(
            'ip' => $_SERVER['REMOTE_ADDR'], 
            'date' => $this->_dateTime, 
            'status' => 9,
        );

        //ADD THE SALES NOTE
        if(isset($_POST['note'])) {
            $save['note'] = $_POST['note'];
        }

        //VALIDATE THE LOGGED IN USER MADE THIS PURCHASE
        $users = new salesProUsers;
        if (!$user = $users->getActiveUser()) return 1;

        //VALIDATE THE CART
        $cart = new salesProCart;
        $cart->buildCart();
        if($cart->totals->quantity < 1 || $cart->totals->xe_grandtotal < 0.00) {
            return 5;
        }
        $save['hash'] = $cart->_hash;

        //VALIDATE THE TERMS & CONDITIONS
        if($cart->tc !== 0) {
            if(!isset($_POST['tandc']) || $_POST['tandc'] !== 'yes') return 12;
        }
        
        //BUILD THE SALE DATA - CART TOTALS
        foreach ($cart->totals as $field => $val) {
            if (array_key_exists($field, $this->_vars)) {
                $save[$field] = $val;
            }
        }

        //VALIDATE THE CURRENCY
        $currency = sprCurrencies::_getActive();
        if ($currency->status !== '1') return 8;
        $save['currency_details'] = (array) $currency;
                
        //VALIDATE THE PAYMENT OPTION
        $payment = $cart->payment;
        if (!isset($payment->status) || $payment->status !== '1') return 4;
        $save['payment_details'] = (array) $payment;

        //VALIDATE THE SHIPPING METHOD
        $save['shipping_details'] = array();
        if($cart->virtual === 0) {

            //VALIDATE USER DETAILS
            if (!$users->checkUserDetails()) return 2;

            //VALIDATE THE REGION
            if ($user->region->status !== '1') return 7;
    
            //VALIDATE THE SHIPPING METHOD
            if(!isset($_POST['shipping_method'])) return 9;
            $ship_method = (int)$_POST['shipping_method'];
            if ((int)$cart->shipping->id !== $ship_method) return 9;
            
            //GET THE SHIPPING DETAILS
            $save['shipping_details'] = (array) $cart->shipping;
        }
        
        //BUILD THE SALE DATA - CART TAX
        $tax = array();
        if(count($cart->tax_details)>0) foreach($cart->tax_details as $t) {
            $tax[] = $t;
        }
        $save['tax_details'] = (array) $tax;

        //BUILD THE USER SALE DATA
        foreach ($user as $field => $val) {
            if (array_key_exists('user_' . $field, $this->_vars)) {
                $save['user_' . $field] = $val;
            }
        }
        
        foreach ($this->_vars as $field => $data) {
            if (!array_key_exists($field, $save)) {
                if ($field !== 'id') {
                    echo $field;
                    die();
                    return 2; //FIELDS ARE INCORRECT... USUALLY USER DETAILS
                }
            }
        }
        
        //CHECK TO SEE IF THIS SALE IS ALREADY IN THE DATABASE
        $id = 0;
        $id = (int)$this->db->getResult($this->_table, 'id', array('hash' => $cart->_hash));
        if($id > 0) $this->db->deleteData($this->_table,array('id'=>$id));

        //SET UP THE SALE
        $sale_id = $this->saveData(0, $save);
        //GET & SAVE EACH SALE ITEM
        $items = new salesProSalesItems;
        foreach ($cart->items as $item) {
            $items->addSale($sale_id, $cart->_hash, $item);
        }
        
        //REDIRECT TO THE PAYMENT GATEWAY ACCORDING TO CHOSEN PAYMENT OPTION
        $class = $payment->method->class;
        if (class_exists($class)) {
            $myclass = new $class($payment->id);
            return $myclass->beginPayment($sale_id, $user, $cart);
        }
        else {
            return 11;
        }
    }
    
    function saveData($id='',$data=array()) {
        if (count($data) < 1) {
            if (count($_POST) > 0)
                foreach ($_POST as $field => $val) {
                    $field = str_replace('spr_sales_', '',  $field);
                    $data[$field] = $val;
                }
        }
        if($id === '') {
            if(isset($_POST['spr_id'])) $id = (int) $_POST['spr_id'];
            else return FALSE;
        }
        //UPDATE STATUS EMAIL PLUGIN
        if($id > 0) {
            $sale = $this->db->getObj($this->_table,'status',array('id'=>$id));
            if(isset($sale->status) && (int) $sale->status > 0 && (int)$sale->status !== (int)$data['status']) {
                $emails = new salesProEmails;
                switch($data['status']) {
                    case '1': //SALE IS COMPLETED
                        if($sale->status == '9') $emails->sendEmailNotification(0,$id); //STATUS WENT FROM ABANDONED TO COMPLETE - PAYPAL ETC
                        $emails->sendEmailNotification(2,$id);
                        break;
                    case '2': //SALE IS REFUNDED
                        $emails->sendEmailNotification(3,$id);
                        break;
                    case '3': //SALE IS CANCELLED
                        $emails->sendEmailNotification(4,$id);
                        break;
                    case '4': //SALE IS PENDING (i.e. order was just placed but needs verification - cash / cheque etc. payment)
                        $emails->sendEmailNotification(0,$id);
                        break;
                    case '5': //SALE IS PARTIALLY REFUNDED
                        //DONT DO ANYTHING... NO EMAIL CURRENTLY HANDLES THIS
                        break;
                    case '6': //SALE IS SHIPPED
                        $emails->sendEmailNotification(1,$id);
                        break;
                    case '9': //SALE IS ABANDONED
                        //DONT DO ANYTHING... NO EMAIL CURRENTLY HANDLES THIS
                        break;
                }
            }
        }

        //CALCULATE THE REGION IDS FOR BILLING AND DELIVERY ADDRESSES
        $array = array('bill' => array('state', 'country'), 'del' => array('state', 'country'));
        foreach ($array as $a => $b) {
            foreach ($b as $c) {
                $field = 'user_' . $a . '_' . $c;
                if (isset($_POST[$field])) {
                    $reg = (int)$_POST[$field];
                    if ($reg > 0) {
                        $data['user_' . $a . '_region_id'] = $reg;
                        break;
                    }
                }
            }
        }
        return parent::saveData($id,$data);
    }
}

class sprSales implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($userid=0) {
        static $sales = NULL;
        $class = new salesProSales;
        if($userid > 0) return $class->getSales($userid);
        else {
            if(NULL === $sales) {
                $sales = $class->getSales();
            }
            return $sales;
        }
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProSales;
        return $class->selectOptions($selected,$options,$text);
    }
    
    public static function _getSalesByDateRange($start='',$end='') {
        $class = new salesProSales;
        $where = array('start'=>$start,'end'=>$end,'status'=>1);
        $class->getSearch($class->_table,$where);
        $mysales = array();
        $now = strtotime($start);
        $last = strtotime($end);
        while($now <= $last ) {
            $thedate = date('Y-m-d', $now);
            $mysales[$thedate] = array('quantity' => 0, 'value' => 0);
            $now = strtotime('+1 day', $now);
        }
        if(count($class->_searchRes)<1) return $mysales;
        foreach($class->_searchRes as $id) {
            $sale = $class->getSale($id,0);
            $xe = $sale->currency->xe;
            $value = sprCurrencies::_toXe($sale->gross_price,$xe);
            $date = date("Y-m-d", strtotime($sale->date));
            if(!isset($mysales[$date])) {
                $mysales[$date]['value'] = $value;
                $mysales[$date]['quantity'] = $sale->quantity;
            }
            else {
                $mysales[$date]['value'] += $value;
                $mysales[$date]['quantity'] += $sale->quantity;
            }
        }
        return $mysales;
    }
}