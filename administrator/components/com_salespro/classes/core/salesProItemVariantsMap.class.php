<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItemVariantsMap extends salesPro {
    public $_table = '#__spr_item_variants_map';
    public $itemid = 0;
    public $_vars = array(
        'variant_id' => array('int', 6),
        'attribute' => array('int', 6),
        'attribute_value' => array('int', 6)
    );
    function __construct($itemid = 0) {
        parent::__construct();
    }
}

class sprItemVariantsMap {
    
    public static function _loadAttributes($variant) {
        $class = new salesProItemVariantsMap;
        $object = $class->db->getObjList($class->_table,array('attribute','attribute_value'),array('variant_id'=>$variant));
        $ret = array();
        if(sizeof($object)>0) {
            foreach($object as $o) {
                $ret[] = (object) array('id'=>$o->attribute,'name'=>sprAttributes::_getName($o->attribute),'val_id'=>$o->attribute_value,'value'=>sprAttributesValues::_getValue($o->attribute_value));
            }
        }
        return $ret;
    }
    
    public static function _deleteValues($variant,$attribute=0) {
        $class = new salesProItemVariantsMap;
        $where = array('variant_id'=>$variant);
        if((int) $attribute > 0) $where['attribute']=$attribute;
        $class->db->deleteData($class->_table, $where);
        return TRUE;
    }

    public static function _deleteValue($attribute_value=0) {
        //DELETE ALL ATTRIBUTE VALUES FROM ALL VARIANTS WHERE VALUE = x 
        $class = new salesProItemVariantsMap;
        $where = array('attribute_value'=>$attribute_value);
        $class->db->deleteData($class->_table, $where);
        return TRUE;
    }
    
    public static function _saveValue($variant,$attribute,$value) {

        $variant = (int)$variant;
        $attribute = (int)$attribute;
        $value = (int)$value;
        if($variant === 0 || $attribute === 0 || $value === 0) return FALSE;
        
        sprItemVariantsMap::_deleteValues($variant,$attribute);
        
        $class = new salesProItemVariantsMap;
        $class->saveData(0,array('variant_id'=>$variant,'attribute'=>$attribute,'attribute_value'=>$value));
        return TRUE;
    }
}