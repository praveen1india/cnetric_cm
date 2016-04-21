<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProUsers extends salesPro {
    public $_table = '#__spr_users';
    public $id = 0;
    public $_vars = array(
        'bill_name' => array('string', 100),
        'bill_address' => array('string', 100),
        'bill_address2' => array('string', 100),
        'bill_town' => array('string', 100),
        'bill_postcode' => array('string', 20),
        'bill_state' => array('int', 6),
        'bill_country' => array('int', 6),
        'bill_region_id' => array('int', 11),
        'bill_phone' => array('string', 20),
        'del_name' => array('string', 100),
        'del_address' => array('string', 100),
        'del_address2' => array('string', 100),
        'del_town' => array('string', 100),
        'del_postcode' => array('string', 20),
        'del_state' => array('int', 6),
        'del_country' => array('int', 6),
        'del_region_id' => array('int', 11),
        'del_phone' => array('string', 20),
        'added' => array('date'),
    );
    public $_requiredFields = array(
        'bill_name',
        'bill_address',
        'bill_town',
        'bill_postcode',
        'bill_region_id',
        'del_name',
        'del_address',
        'del_town',
        'del_postcode',
        'del_region_id'
    );
    public $_displayFields = array(
        'name',
        'address',
        'address2',
        'town',
        'state_name',
        'postcode',
        'country_name'
    );
    public $_registerFields = array(
        'name',
        'surname',
        'email',
        'pass'
    );
    public $_statusOptions = array(
        '0' => 'SPR_USER_STATUS_PENDING',
        '1' => 'SPR_USER_STATUS_INACTIVE',
        '2' => 'SPR_USER_STATUS_ACTIVE',
        '3' => 'SPR_USER_STATUS_BANNED'
    );
    public $_searchTerms = array(
        'name',
        'surname',
        'email',
        'region_id',
        'start',
        'end',
        'status'
    );
    public $actions = array('activated');
    function __construct() {
        parent::__construct();
        foreach($this->_statusOptions as $n=>$o) {
            $this->_statusOptions[$n] = JText::_($o);
        }
    }
    function getSearch($table = '', $where = array(), $joins = array(), $order =
        array()) {
        //SET UP THE SEARCH TERMS
        foreach ($this->_searchTerms as $s) {
            $this->search[$s] = '';
        }
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
                    case 'name':
                        $where['z.name'] = $val;
                        break;
                    case 'surname':
                        $where['z.surname'] = $val;
                        break;
                    case 'region_id':
                        $where['z.region_id'] = $val;
                        break;
                    case 'start':
                        $where['z.added']['from'] = $val;
                        break;
                    case 'end':
                        $where['z.added']['to'] = $val;
                        break;
                    case 'status':
                        $where['z.status'] = $val;
                        break;
            }
            $this->search[$field] = $val;
        }
        //ADD THE ORDER VARIABLES
        foreach ($this->order as $var => $val) {
            if (isset($_POST['spr_' . $var])) $this->order[$var] = $_POST['spr_' . $var];
        }
        //GET THE SEARCH RESULTS
        $this->_searchRes = parent::getSearch($table, $where, $joins, $this->order);
        //GET COUNT OF ALL POTENTIAL RESULTS
        $this->order['total'] = parent::getCount($table, $where, $joins);
    }
    function getUserDetails($id = 0) {
        $object = $this->db->getObj($this->_table, $this->getVars(), array('id' => $id));
        if (sizeof($object) < 1) return $this->getDefaultObject();
        return $object;
    }
    function getUsers() {
        $res = array();
        if (count($this->_searchRes) < 1) $this->getSearch('#__users');
        foreach ($this->_searchRes as $id) {
            $res[] = $this->getUser($id);
        }
        return $res;
    }
    function getUser($id = 0) {
        if ($id === 0) $id = $this->id;
        $table = '#__users';
        $fields = array('id', 'name', 'username', 'email', 'block', 'sendEmail', 'registerDate', 'lastvisitDate', 'activation', 'params');
        $object = $this->db->getObj($table, $fields, array('id' => $id));
        if (sizeof($object) < 1) return false;
        $details = $this->getUserDetails($object->id);
        $object = (object)array_merge((array )$object, (array )$details);
        if ($details->bill_name === '') $object->bill_name = $object->name;
        if ($details->del_name === '') $object->del_name = $object->name;
        $object->bill_state_name = sprRegions::_getName($object->bill_state);
        $object->bill_country_name = sprRegions::_getName($object->bill_country);
        $object->del_state_name = sprRegions::_getName($object->del_state);
        $object->del_state_code = sprRegions::_getCode($object->del_state);
        $object->del_state_code2 = sprRegions::_getCode2($object->del_state);
        $object->del_country_name = sprRegions::_getName($object->del_country);
        $object->del_country_code = sprRegions::_getCode($object->del_country);
        $object->del_country_code2 = sprRegions::_getCode2($object->del_country);
        foreach($object as $o) $o = htmlentities($o, ENT_QUOTES, "UTF-8");
        if($object->del_region_id > 0) $object->region = sprRegions::_load($object->del_region_id);
        else $object->region = sprRegions::_getBlank();
        return $object;
    }
    function getActiveUser() {
        $user = JFactory::getUser();
        if ($user->guest === 1) return false;
        return $this->getUser($user->id);
    }
    function checkUserDetails() {
        $errors = array();
        if (!$user = $this->getActiveUser()) {
            return false;
        }
        foreach ($this->_requiredFields as $field) {
            if (!isset($user->$field) || strlen($user->$field) < 1 || $user->$field === 0) {
                $errors[] = $field;
            }
        }
        if (count($errors) > 0) return $errors;
        return TRUE;
    }
    function login() {
        $login = new salesProLogin;
        return $login->login();
    }
    function logOut() {
        $login = new salesProLogin;
        return $login->logOut();
    }
    function checkMe() {
        $login = new salesProLogin;
        return $login->checkMe();
    }

    function checkPost() {
        foreach ($this->_registerFields as $field) {
            $ret[$field] = '';
        }
        if (count($_POST) > 0)
            foreach ($_POST as $field => $var) {
                $tempfield = str_replace('spr_users_', '', $field);
                if (array_key_exists($tempfield, $this->_vars)) {
                    $var = $this->sanitize($var, $this->_vars[$tempfield][0]);
                    $ret[$tempfield] = $var;
                }
            }
        return (object)$ret;
    }
    function saveData($id = '', $array = array()) {
        //CHECK IF THE USER EXISTS ALREADY
        if (!$res = $this->db->getResult($this->_table, 'id', array('id' => $id))) {
            $date = date("Y-m-j");
            $data = array('id' => $id, 'added' => $date);
            $this->db->insertData($this->_table, $data);
        }
        //CALCULATE THE REGION IDS FOR BILLING AND DELIVERY ADDRESSES
        $array = array('bill' => array('state', 'country'), 'del' => array('state','country'));
        foreach ($array as $a => $b) {
            foreach ($b as $c) {
                $field = 'spr_users_' . $a . '_' . $c;
                if (isset($_POST[$field])) {
                    $reg = (int)$_POST[$field];
                    if ($reg > 0) {
                        $_POST['spr_users_' . $a . '_region_id'] = $reg;
                        break;
                    }
                }
            }
        }
        $id = parent::saveData($id);
        return $id;
    }
}

class sprUsers implements salesProFactory {

    /* FACTORY METHOD TO LOAD AN INSTANCE
    // AND RETURN AN OBJECT WITH ALL VARIABLES */
    public static function _load($userid=0) {
        static $users = NULL;
        $class = new salesProUsers;
        if($userid > 0) return $class->getUser($userid);
        else {
            if(NULL === $users) {
                $users = $class->getUsers();
            }
            return $users;
        }
    }
    
    /* FACTORY METHOD TO OUTPUT DROPDOWN SELECT OPTIONS
    // $options CAN HOLD AN ARRAY, OR ELSE POINTS TO $options.'Options'
    // CLASS SPECIFIC OPTION ARRAYS ARE SET IN $class
    // GLOBAL OPTION ARRAYS ARE SET IN THE salesPro PARENT CLASS*/
    public static function _options($selected = '',$options = '',$text = 0) {
        $class = new salesProUsers;
        return $class->selectOptions($selected,$options,$text);
    }
    
    public static function _getUserCountByDateRange($start='',$end='') {
        $class = new salesProUsers;
        $where = array('start'=>$start,'end'=>$end);
        $class->getSearch($class->_table,$where);
        return count($class->_searchRes);
    }
}