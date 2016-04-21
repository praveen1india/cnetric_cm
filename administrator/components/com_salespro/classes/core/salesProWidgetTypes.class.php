<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProWidgetTypes extends salesPro {
    public $_table = '#__spr_widget_types';
    public $_vars = array(
        'type' => array('string', 255),
        'about' => array('string')
    );
    function __construct() {
        parent::__construct();
    }
}
class sprWidgetTypes {
    public static function _getTypes() {
        $class = new salesProWidgetTypes;
        $object = $class->db->getObjList($class->_table,$class->getVars(),array(),array('sort'=>'type','dir'=>'ASC'));
        return $object;
    }
    public static function _getType($type = '') {
        $class = new salesProWidgetTypes;
        $object = $class->db->getObj($class->_table,$class->getVars(),array('type'=>$type),array('sort'=>'type','dir'=>'ASC'));
        return $object;
    }
}