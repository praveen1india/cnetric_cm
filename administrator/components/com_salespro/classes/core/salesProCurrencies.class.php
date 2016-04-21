<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProCurrencies extends salesPro {
    public $_table = '#__spr_currencies';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 6),
        'name' => array('string', 50),
        'code' => array('string', 5),
        'symbol' => array('string', 15),
        'status' => array('int', 3),
        'default' => array('int', 3),
        'xe' => array('float'),
        'decimals' => array('int', 3),
        'thousands' => array('string', 2),
        'separator' => array('string', 2),
        'checked' => array('date')
    );
    public $actions = array('status', 'setDefault');
    public $order = array('sort' => 'status', 'dir' => 'DESC');        
    function __construct() {
        parent::__construct();

        //UPDATE THE ACTIVE CURRENCY IF REQUIRED!
        if(isset($_POST['spr_currency_select'])) {
            $currency = (int)$_POST['spr_currency_select'];
            $this->setActiveCurrency($currency);
            $this->redirect();
        }
    }
    function getActiveCurrency() {
        static $res = NULL;
        if(NULL === $res) {
            if (!$res = sprCookies::_getVar('currency')) {
                $res = $this->setActiveCurrency();
            }
        }
        return $res;
    }
    function setActiveCurrency($curr = 0) {
        $ok = 0;
        if ($curr !== 0) {
            $currency = $this->getCurrency($curr);
            if (isset($currency->status) && $currency->status === '1') {
                $id = $currency->id;
                sprCookies::_setVar('currency', $id);
                $ok = 1;
            }
        }
        if ($ok === 0) {
            $id = $this->getDefaultCurrency();
            sprCookies::_setVar('currency', $id);
        }
        return $id;
    }
    function getCurrencies() {
        static $currencies = NULL;
        if(NULL === $currencies) {
            $currencies = $this->db->getObjList($this->_table, $this->getVars(), array(), $this->order);
            if (sizeof($currencies) <1) $currencies = array();
            return $currencies;
        }
    }
    function getCurrency($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (!isset($object->id)) {
            $object = $this->getDefaultCurrency();
        }
        return $object;
    }
    function getDefaultCurrency() {
        static $id = NULL;
        if(NULL === $id) {
            $id = (int) $this->db->getResult($this->_table, 'id', array('default' => 1));
            if ($id < 1) $id = (int) $this->db->getResult($this->_table, 'id', array('id' => '>=1'));
        }
        if ($id < 1) return FALSE;
        return $id;
    }
    function ajaxSetDefault($table = '', $goodVars = array()) {
        $id = (isset($_POST['id'])) ? (int)$_POST['id'] : 0;
        //MAKE THE USER'S COOKIE UPDATE TO USE THE NEW DEFAULT CURRENCY
        $this->setActiveCurrency($id);
        //UPDATE THE CURRENCY BASE XE TO 1
        $data = array('xe'=>'1');
        $this->db->updateData($this->_table,$data,array('id'=>$id));
        $ajax = new salesProAjax;
        $res = $ajax->setDefault($table,$goodVars);
    }
}

class sprCurrencies implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        static $currencies = NULL;
        $class = new salesProCurrencies;
        if($id > 0) return $class->getCurrency($id);
        else {
            if(NULL === $currencies) {
                $currencies = $class->getCurrencies();
            }
            return $currencies;
        }
    }
    
    /* FACTORY METHOD TO GET THE ACTIVE CURRENCY */
    public static function _getActive() {
        static $currency = NULL;
        if(NULL === $currency) {
            $class = new salesProCurrencies;
            $id = $class->getActiveCurrency();
            $currency = $class->getCurrency($id);
        }
        return $currency;
    }

    /* FACTORY METHOD TO SET THE ACTIVE CURRENCY */
    public static function _setActive($id=0) {
        $class = new salesProCurrencies;
        return $class->setActiveCurrency($id);
    }
    
    /* FACTORY METHOD TO GET THE DEFAULT CURRENCY */
    public static function _getDefault() {
        $class = new salesProCurrencies;
        $id = $class->getDefaultCurrency();
        return $class->getCurrency($id);
    }

    /* FACTORY METHOD TO GET AN EMPTY CURRENCY OBJECT - USE IN CREATE VIEW */
    public static function _getBlank() {
        $class = new salesProCurrencies;
        return $class->getDefaultObject();
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProCurrencies;
        return $class->selectOptions($selected,$options,$text);
    }
    
    /* FACTORY METHOD TO SAVE DATA
    // INPUT IN THE FORMAT id = record ID, $array = data */
    public static function _save($id = '', $array = array()) {
        $class = new salesProCurrencies;
        return $class->saveData($id, $array);
    }
    
    /* FACTORY METHOD TO CONVERT VALUE USING EXCHANGE RATE
    // OUTPUTS A SIMPLE VALUE * EXCHANGE RATE */ 
    public static function _toXe($value = 0, $xe = 0) {
        if($xe === 0) {
            $active = sprCurrencies::_getActive();
            if($active) $xe = $active->xe;
            else $xe = 1;
        }
        $ret = ($value * $xe);
        return $ret;
    }
    
    /* FACTORY METHOD TO FORMAT A VALUE USING THE
    // ACTIVE CURRENCY. VERY USEFUL! */
    public static function _format($value = 0, $currency_id = 0) {
        if((int)$currency_id === 0) {
            $class = new sprCurrencies;
            $currency = $class->_getActive();
        } else {
            $class = new salesProCurrencies;
            $currency = $class->getCurrency($currency_id);
        }
        if($currency) {
            $value = number_format($value, $currency->decimals, $currency->separator, $currency->thousands);
            $value = $currency->symbol . $value;
        }
        return $value;
    }
}