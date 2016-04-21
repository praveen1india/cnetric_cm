<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProAttributesValues extends salesPro {
    public $_vars = array(
        'id' => array('int', 11),
        'attribute_id' => array('int', 6),
        'value' => array('string', 100)
    );
    public $actions = array(
        'save',
        'delete',
        'loadValues',
    );
    public $_table = '#__spr_attributes_values';
    function __construct() {
        parent::__construct();
    }
    
    function getAttributes($attribute_id = 0) {
        $object = $this->db->getObjList($this->_table, $this->getVars(), array('attribute_id'=>$attribute_id));
        if(count($object)>0) foreach($object as $o) {
            $o->html = "<div class='spr_field' id='att_value_{$o->id}' value='{$o->value}'><label style='min-width: 228px;'><a href='#' onclick='editValue({$o->id})'>{$o->value}</a></label><a href='#' onclick='editValue({$o->id})' class='spr_icon spr_icon_edit'>&nbsp;</a><a href='#' onclick='deleteValue({$o->id})' class='spr_icon spr_icon_delete'>&nbsp;</a></div>";
        }
        return $object;
    }
    
    function saveData($id = '', $array = array()) {
        if(isset($_POST['value'])) $_POST['value'] = ucfirst(strtolower($_POST['value']));
        return parent::saveData($id, $array);
    }
    
    function ajaxSave() {
        $att = (int) $_POST['attribute_id'];
        $ajax = new salesProAjax;
        $ajax->save($this->_table, $this->getVars(), 0);
        $string = '';
        $values = $this->getAttributes($att);
        if(count($values)>0) foreach($values as $v) $string .= $v->html;
        $ajax->json['string'] = $string;
        $ajax->saveJson();
    }
    
    function ajaxDelete() {
        $attribute = (int)$_POST['attribute'];
        $ajax = new salesProAjax;
        $id = $ajax->delete($this->_table, $this->getVars(), 0);
        //DELETE ATTRIBUTE VALUES FROM ITEMS!
        sprItemVariantsMap::_deleteValue($id);
        //RETURN STRING
        $string = '';
        $values = $this->getAttributes($attribute);
        if(count($values)>0) foreach($values as $v) $string .= $v->html;
        $ajax->json['string'] = $string;
        $ajax->saveJson();
    }
    
    function ajaxLoadValues() {
        $attribute = (int)$_POST['attribute'];
        $input = ucfirst(strtolower($_POST['input']));
        if($attribute < 1) return FALSE;
        $ajax = new salesProAjax;
        $string = '';
        $values = $this->getAttributes($attribute);
        $n = 0;
        if(count($values)>0) {
            foreach($values as $v) {
                $value = ucfirst(strtolower($v->value));
                if(strlen($input)>0 && stripos($value,$input)=== FALSE) continue;
                if(strlen($input)>0 && $value === $input) continue;
                $string .= "<li>{$value}</li>";
                if($n++ > 20) break;
            }
        }
        if(strlen($string)>0) $string = '<ul>'.$string.'</ul>';
        $ajax->json['string'] = $string;
        $ajax->saveJson();
    }
}

class sprAttributesValues {

    public static function _load($id=0) {
        $class = new salesProAttributesValues;
        return $class->getAttributes($id);
    }

    public static function _saveAttr($id=0) {
        $class = new salesProAttributesValues;
        $data = array('attribute_id'=>$id);
        $class->db->updateData($class->_table,$data,array('attribute_id'=>'0'));
    }
    
    public static function _saveValue($attr=0,$value='') {
        if((int)$attr < 1 || $value === '') return FALSE;
        $class = new salesProAttributesValues;
        $data = array('attribute_id'=>$attr,'value'=>$value);
        $id = $class->saveData(0,$data);
        return $id;
    }
    
    public static function _getValue($id=0) {
        $class = new salesProAttributesValues;
        return $class->getVar('value',$id);
    }
    
    public static function _getValues($attr=0) {
        $class = new salesProAttributesValues;
        return $class->db->getObjList($class->_table,array('id','value'),array('attribute_id'=>$attr));
    }
    
    public static function _delValues($att=0) {
        $class = new salesProAttributesValues;
        $res = $class->db->deleteData($class->_table,array('attribute_id'=>(int)$att));
        return TRUE;
    }
    
    public static function _checkValue($att=0,$value='') {
        if($value === '' || (int) $att === 0) return FALSE;
        $value = ucfirst($value);
        $class = new salesProAttributesValues;
        $res = $class->db->getObj($class->_table,'id',array('attribute_id'=>$att, 'value'=>$value));
        if(isset($res->id)) return (int)$res->id;
        
        $id = sprAttributesValues::_saveValue($att,$value);
        return (int)$id;
    }
}