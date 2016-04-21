<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProCookies extends salesPro {
    private $_namespace = 'salespro';
    public $_cookie = '';
    public $_timeout = 86400;
    public $_table = '#__spr_cookies';
    public $_vars = array(
        'id' => array('int', 11),
        'hash' => array('string', 16),
        'ip' => array('string', 20),
        'time' => array('int', 11)
    );
    function __construct() {
        parent::__construct();
        $this->purgeCookies();
        $this->getCookie();
    }
    private function purgeCookies() {
        $time = time() - $this->_timeout;
        $cookies = $this->db->getAssocList($this->_table,$this->getVars(),array('time'=>'<'.$time));
        if(count($cookies)>0) foreach($cookies as $cookie) {
            $this->destroyCookie($cookie['hash']);
        }
    }
    private function getCookie() {
        if (array_key_exists($this->_namespace, $_COOKIE)) {
            if ($this->checkCookie() === true) return true;
        }
        $this->setCookie();
    }
    private function setCookie() {
        $cookie = $this->uniqId('salesProCookies', 16);
        $time = time();
        $timeout = ($this->_timeout > 0) ? $time + $this->_timeout : 0;
        setcookie($this->_namespace, $cookie, $timeout, '/');
        $data = array('hash' => $cookie, 'ip' => $_SERVER['REMOTE_ADDR'], 'time' => $time);
        $this->db->insertData($this->_table, $data);
        $this->_cookie = $cookie;
        $_COOKIE[$this->_namespace] = $cookie;
    }
    private function checkCookie() {
        $this->_cookie = $_COOKIE[$this->_namespace];
        $time = time() - $this->_timeout;
        $res = $this->db->getAssoc($this->_table, $this->getVars(), array('hash' => $this->_cookie));
        if (!isset($res['ip']) || $res['ip'] !== $_SERVER['REMOTE_ADDR'] || $res['time'] < $time) {
            $this->destroyCookie();
            return false;
        }
        $time = time();
        $timeout = ($this->_timeout > 0) ? $time + $this->_timeout : 0;
        setcookie($this->_namespace, $this->_cookie, $timeout, '/');
        $this->db->updateData($this->_table, array('time' => $time), array('id'=>$res['id']));
        return true;
    }
    private function destroyCookie($cookiename = '') {
        if($cookiename === '') $cookiename = $this->_cookie;
        $this->delAllCookieVars($cookiename);
        $this->db->deleteData($this->_table, array('hash' => $cookiename));
        unset($_COOKIE[$this->_namespace]);
    }
    public function delAllCookieVars($cookiename = '') {
        if($cookiename === '') $cookiename = $this->_cookie;
        $vars = new salesProCookieVars;
        return $vars->delAllCookieVars($cookiename);
    }
}

class sprCookies {
    
    /* FACTORY METHOD TO CREATE A NEW COOKIE */
    public static function _start() {
        $cookie = new salesProCookies;
    }

    /* FACTORY METHOD TO LOAD SPECIFIC COOKIE VARIABLE */
    public static function _getVar($name = '') {
        $cookies = new salesProCookies;
        $vars = new salesProCookieVars;
        return $vars->getCookieVar($cookies->_cookie, $name);
    }
    
    /* FACTORY METHOD TO SET SPECIFIC COOKIE VARIABLE */
    public static function _setVar($name = '', $value = '') {
        $cookies = new salesProCookies;
        $vars = new salesProCookieVars;
        return $vars->setCookieVar($cookies->_cookie, $name, $value);
    }
    
    /* FACTORY METHOD TO DELETE SPECIFIC COOKIE VARIABLE */
    public static function _delVar($name = '') {
        $cookies = new salesProCookies;
        $vars = new salesProCookieVars;
        return $vars->delCookieVar($cookies->_cookie, $name);
    }
    
    /* FACTORY METHOD TO DELETE ALL AVAILABLE COOKIE VARIABLES */
    public static function _delAllVars($cookiename = '') {
        $cookies = new salesProCookies;
        return $cookies->delAllCookieVars($cookiename);
    }
}