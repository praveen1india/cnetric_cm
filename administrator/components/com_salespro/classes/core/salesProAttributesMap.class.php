<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProAttributesMap extends salesPro {
    public $_vars = array(
        'attribute' => array('int', 6),
        'category' => array('int', 6)
    );
    public $_table = '#__spr_attributes_map';
    function __construct() {
        parent::__construct();
    }
}

class sprAttributesMap {
    public static function _getCats($attr=0) {
        $class = new salesProAttributesMap;
        $res = array();
        $array = $class->db->getObjList($class->_table,'category',array('attribute'=>(int)$attr));
        if(count($array)>0) foreach($array as $a) {
            $res[] = $a->category;
        }
        return $res;
    }
    public static function _getAtts($catid=0) {
        $class = new salesProAttributesMap;
        $res = array();
        $where = ((int)$catid > 0) ? array('category'=>(int)$catid) : 0;
        $array = $class->db->getObjList($class->_table,'attribute',$where);
        if(count($array)>0) foreach($array as $a) {
            $res[] = $a->attribute;
        }
        return $res;
    }
    public static function _saveCats($att=0,$cats=array()) {
        if((int)$att < 1) return FALSE;
        
        sprAttributesMap::_delCats($att);
        $class = new salesProAttributesMap;
        if(is_array($cats) && count($cats)>0) foreach($cats as $c) {
            $data = array('attribute'=>$att,'category'=>$c);
            $class->saveData(0,$data);
        }
        return TRUE;
    }
    public static function _delCats($att=0) {
        $class = new salesProAttributesMap;
        $res = $class->db->deleteData($class->_table,array('attribute'=>(int)$att));
        return TRUE;
    }
}