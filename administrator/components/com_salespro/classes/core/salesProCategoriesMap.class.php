<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProCategoriesMap extends salesPro {
    public $_vars = array(
        'item' => array('int', 6),
        'category' => array('int', 6)
    );
    public $_table = '#__spr_categories_map';
    function __construct() {
        parent::__construct();
    }
}

class sprCategoriesMap {
    
    public static function _getCats($item=0) {
        $class = new salesProCategoriesMap;
        $res = array();
        $array = $class->db->getObjList($class->_table,'category',array('item'=>(int)$item));
        if(count($array)>0) foreach($array as $a) {
            $res[] = $a->category;
        }
        return $res;
    }
    public static function _getItems($category=0) {
        $class = new salesProCategoriesMap;
        $res = array();
        $array = $class->db->getObjList($class->_table,'item',array('category'=>(int)$category));
        if(count($array)>0) foreach($array as $a) {
            $res[] = $a->item;
        }
        return $res;
    }
    public static function _saveCats($item=0,$cats=array()) {
        if((int)$item < 1) return FALSE;
        sprCategoriesMap::_delCats($item);
        $class = new salesProCategoriesMap;
        if(is_array($cats) && count($cats)>0) foreach($cats as $c) {
            $data = array('item'=>$item,'category'=>$c);
            $class->saveData(0,$data);
        }
        return TRUE;
    }
    public static function _delCats($item=0) {
        $class = new salesProCategoriesMap;
        $res = $class->db->deleteData($class->_table,array('item'=>(int)$item));
        return TRUE;
    }
}