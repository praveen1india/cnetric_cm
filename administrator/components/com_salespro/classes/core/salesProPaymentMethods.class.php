<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProPaymentMethods extends salesPro {
    public $_table = '#__spr_payment_methods';
    public $id = 0;
    public $_vars = array(
        'id' => array('int', 4),
        'name' => array('string', 50),
        'alias' => array('string', 50),
        'class' => array('string', 255),
        'params' => array('string'),
        'about' => array('string'),
    );
    public $defaultParams = array();
    function __construct() {
        parent::__construct();
    }
    function getPaymentMethods($status = 0) {
        $where = ($status === 0) ? array() : array('status' => '1');
        $object = $this->db->getObjList($this->_table, 'id', $where, $this->order);
        if (sizeof($object) > 0) {
            foreach($object as $n=>$o) {
                $object[$n] = $this->getPaymentMethod($o->id);
            }
            return $object;
        }
        else {
            return array();
        }
    }
    function getPaymentMethod($id = 0) {
        if ($id === 0) $id = $this->id;
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) < 1) {
            $object = $this->getDefaultObject();
        }
        $params = (array)json_decode($object->params);
        $object->params = $params;
        return $object;
    }
    function saveData($id = '', $array = array()) {
        $str = 'spr_payment_methods_params';
        if (isset($_POST[$str])) $_POST[$str] = json_encode($_POST[$str]);
        parent::saveData($id, $array);
    }
}

class sprPaymentMethods implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($id=0) {
        static $methods = NULL;
        $class = new salesProPaymentMethods;
        if($id > 0) return $class->getPaymentMethod($id);        
        else {
            if(NULL === $methods) {
                $methods = $class->getPaymentMethods();
            }
            return $methods;
        }
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProPaymentMethods;
        return $class->selectOptions($selected,$options,$text);
    }
}