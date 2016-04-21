<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProPaymentOptions extends salesPro {
    public $_table = '#__spr_payment_options';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 4),
        'payment_method' => array('int', 6),
        'name' => array('string', 100),
        'sort' => array('int', 4),
        'status' => array('int', 4),
        'params' => array('string'),
        'fee' => array('float',12),
        'fee_type' => array('int',4),
        'info' => array('string')
    );
    public $actions = array('resort', 'status');
    public $order = array('sort' => 'sort', 'dir' => 'ASC', 'limit' => 20, 'page' => 0, 'total' => 0);
    public $defaultParams = array();
    function __construct() {
        parent::__construct();
    }
    function getPaymentOptions($status = 0) {
        $where = ($status === 0) ? array() : array('status' => '1');
        $object = $this->db->getObjList($this->_table, 'id', $where, $this->order);
        if (sizeof($object) > 0) {
            foreach($object as $n=>$o) {
                $object[$n] = $this->getPaymentOption($o->id);
            }
            return $object;
        }
        else {
            return array();
        }
    }
    function getPaymentOption($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) < 1) {
            $object = $this->getDefaultObject();
        }
        //GET THE ASSOCIATED PAYMENT METHOD
        $object->method = sprPaymentMethods::_load($object->payment_method);
        if($object->name === '') $object->name = $object->method->name;
        //SET THE PARAMETERS FOR CORRECT PAYMENT METHOD
        $object->params = (array)json_decode($object->params);
        foreach($object->method->params as $field=>$val) {
            if(!isset($object->params[$field])) $object->params[$field] = $val;
        }
        return $object;
    }
    function saveData($id = '', $array = array()) {
        $str = 'spr_payment_options_params';
        if (isset($_POST[$str])) $_POST[$str] = json_encode($_POST[$str]);
        $id = parent::saveData($id, $array);
        if(isset($_POST['spr_payment_options_payment_method'])) {
            $this->redirect('index.php?option=com_salespro&view=payment_options&layout=edit&id='.$id);
        }
    }
}

class sprPaymentOptions implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        static $options = NULL;
        $class = new salesProPaymentOptions;
        if($id > 0) return $class->getPaymentOption($id);        
        else {
            if(NULL === $options) {
                $options = $class->getPaymentOptions();
            }
            return $options;
        }
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProPaymentOptions;
        return $class->selectOptions($selected,$options,$text);
    }
    
    /* FACTORY METHOD TO LOAD ALL ACTIVE PAYMENT OPTIONS */
    public static function _loadActive() {
        static $options = NULL;
        $class = new salesProPaymentOptions;
        if(NULL === $options) {
            $options = $class->getPaymentOptions(1);
        }
        return $options;
    }
    
    public static function _default() {
        static $default = NULL;
        if(NULL === $default) {
            $class = new salesProPaymentOptions;
            $default = $class->getDefaultObject();
            $default->formatted_fee = '0.00';
        }
        return $default;
    }
}