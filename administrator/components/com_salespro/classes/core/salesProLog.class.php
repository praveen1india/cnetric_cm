<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProLog extends salesPro {
    public $_table = '#__spr_log';
    public $_vars = array(
        'id' => array('int', 11),
        'date' => array('datetime'),
        'seen' => array('int', 1),
        'title' => array('string', 255),
        'page' => array('string', 255)
    );
    function __construct($msg = '',$page = '') {
        parent::__construct();
        if($msg !== '') {
            $this->log($msg,$page);
        }
    }
    function log($msg,$page='') {
        $data = array(
            'date'=>$this->_dateTime,
            'title'=>$msg,
            'page'=>$page
        );
        $this->saveData(0,$data);
    }
}

class sprLog {
    
    public static function _log($msg='',$page='') {
        $class = new salesProLog;
        $class->log($msg,$page);
    }
}