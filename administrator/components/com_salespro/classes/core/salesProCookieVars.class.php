<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProCookieVars extends salesPro {
    public $_cookie = '';
    public $_timeout = 3600;
    public $_table = '#__spr_cookie_vars';
    public $_vars = array(
        'id' => array('int', 11),
        'cookie' => array('string', 16),
        'name' => array('string', 20),
        'value' => array('string', 255)
    );
    function __construct() {
        parent::__construct();
    }
    public function getCookieVar($cookie, $name) {
        $value = $this->db->getResult($this->_table, 'value', array('cookie' => $cookie, 'name' => $name));
        return $value;
    }
    public function setCookieVar($cookie, $name, $value) {
        $id = 0;
        $data = array('cookie' => $cookie, 'name' => $name, 'value' => $value);
        $id = (int)$this->db->getResult($this->_table, 'id', array('cookie' => $cookie, 'name' => $name));
        $this->saveData($id, $data);
    }
    public function delCookieVar($cookie, $name) {
        $this->db->deleteData($this->_table, array('cookie' => $cookie, 'name' => $name));
    }
    public function delAllCookieVars($cookie) {
        $this->db->deleteData($this->_table, array('cookie' => $cookie));
    }
}
