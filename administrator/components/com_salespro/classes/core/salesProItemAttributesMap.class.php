<?php

/* -------------------------------------------
Component: com_salesPro
Author: Barnaby V. Dixon
Email: barnaby@php-web-design.com
Copywrite: Copywrite (C) 2014 Barnaby Dixon. All Rights Reserved.
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
---------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

class salesProItemAttributesMap extends salesPro {
    public $_table = '#__spr_item_attributes_map';
    public $_vars = array(
        'item' => array('int', 6),
        'attribute' => array('int', 6)
    );
    public $actions = array('save','delete','load','getOptions','getFields');
    function __construct($itemid = 0) {
        parent::__construct();
    }
    
    function saveDefaultAttributes($item = 0,$category = 0) {
        //AUTOMATICALLY SAVE DEFAULT CATEGORY ATTRIBUTES TO THIS ITEM!
        $attributes = sprAttributes::_byCategory($category);
        if(count($attributes)>0) foreach($attributes as $a) {
            $data = array(
                'attribute' => $a->id,
                'item' => $item
            );
            $this->db->deleteData($this->_table,$data);
            $this->db->insertData($this->_table,$data);
        }
        return $attributes;
    }
    
    function ajaxLoad() {
        $item_id = (isset($_POST['item_id'])) ? (int) $_POST['item_id'] : 0;
        $catid = (isset($_POST['catid'])) ? (int)$_POST['catid'] : 0;
        $catchange = (isset($_POST['catchange'])) ? (int)$_POST['catchange'] : 0;

        //UPDATE DEFAULT ATTRIBUTES ON CATEGORY CHANGE
        $attributes = sprItemAttributesMap::_loadMap($item_id);
        if($catchange === 1 || count($attributes)<1) {
            $variants = sprItemVariants::_load($item_id);
            if(count($variants)<1) {
                $this->db->deleteData($this->_table,array('item'=>$item_id));
                $x = $this->saveDefaultAttributes($item_id,$catid);
                $attributes = sprItemAttributesMap::_loadMap($item_id);
            }
        }
        
        $string = '';
        $ajax = new salesProAjax;
        if(count($attributes)>0) foreach($attributes as $id) {
            $attribute = sprAttributes::_load($id);
            if(!isset($attribute->name)) continue;
            $string .= "<div class='spr_field' id='item_att_{$id}' name='{$attribute->name}'><label style='width: 252px;'>{$attribute->name}</label><a href='#' onclick='deleteAtt({$id})' class='spr_icon spr_icon_delete'>&nbsp;</a></div>";
        } else {
            $string = '<p>You have not selected any attributes</p>';
        }
        $ajax->json['string'] = $string;
        $ajax->saveJson();
    }
    
    function ajaxGetOptions() {
        $item_id = (isset($_POST['item_id'])) ? (int) $_POST['item_id'] : 0;
        $att_name = (isset($_POST['att_name'])) ? $_POST['att_name'] : '';
        
        //GET OPTIONS ALREADY ASSIGNED TO THIS ITEM
        $attributes = sprItemAttributesMap::_loadMap($item_id);
        
        //GET ALL POTENTIAL OPTIONS
        $string = '';
        $potentials = sprAttributes::_load();
        if(count($potentials)>0) foreach($potentials as $n=>$p) {
            if(in_array($p->id,$attributes)) {
                unset($potentials[$n]);
                continue;
            }
            $name = ucfirst(strtolower($p->name));
            if(!empty($att_name) && stripos($name,$att_name) === FALSE) continue;
            if(strlen($att_name)>0 && $name === $att_name) continue;
            $string .= "<li>{$name}</li>";
            if($n++ > 20) break;
        }
        $ajax = new salesProAjax;
        $ajax->json['string'] = $string;
        $ajax->saveJson();
    }
    
    function ajaxSave() {
        $item_id = (isset($_POST['item_id'])) ? (int) $_POST['item_id'] : 0;
        $att_name = (isset($_POST['att_name'])) ? $_POST['att_name'] : '';
        
        //CHECK IF ATTRIBUTE EXISTS
        $class = new salesProAttributes;
        $atts = $class->db->getObj($class->_table,'id',array('name'=>$att_name));
        
        if(isset($atts->id)) {
            $attribute = (int)$atts->id;
        } else {
            //OR SAVE A NEW ATTRIBUTE
            $attribute = $class->saveData(0,array('name' => $att_name));
        }
        
        //SAVE A NEW ATTRIBUTE TO THIS ITEM
        $data = array(
            'attribute' => $attribute,
            'item' => $item_id
        );
        $this->db->deleteData($this->_table,$data);
        $this->db->insertData($this->_table,$data);
        $this->ajaxLoad();
    }
    
    function ajaxDelete() {
        $item_id = (isset($_POST['item_id'])) ? (int) $_POST['item_id'] : 0;
        $attribute = (isset($_POST['att'])) ? (int) $_POST['att'] : 0;
        
        //DELETE ATTRIBUTE
        $data = array('item'=>$item_id,'attribute'=>$attribute);
        $this->db->deleteData($this->_table,$data);
        
        //DELETE VALUES FROM ITEM VARIANTS
        $variants = sprItemVariants::_load($item_id);
        if(count($variants)>0) foreach($variants as $v) sprItemVariantsMap::_deleteValues($v->id,$attribute);

        $this->ajaxLoad();
    }
    function ajaxGetFields() {
        $catid = (int)$_POST['catid'];
        $item_id = (int)$_POST['item_id'];

        $attributes = sprItemAttributesMap::_loadMap($item_id);
        $string = '';
        $ajax = new salesProAjax;
        if(count($attributes)>0) foreach($attributes as $id) {
            $a = sprAttributes::_load($id);
            if($a !== FALSE) {
                $string .= "<div class='spr_field'>
                    <label for='attr_{$a->id}'>{$a->name}</label>
                    <input type='text' id='attr_{$a->id}' class='attribute variant_field' value='' attr='{$a->id}' autocomplete='off' />
                    <div class='spr_field_options' attr='{$a->id}'></div>
                </div>";
            }
        }
        $ajax->json['string'] = $string;
        $ajax->saveJson();
    }
}

class sprItemAttributesMap {
    
    public static function _loadMap($item) {
        $class = new salesProItemAttributesMap;
        $object = $class->db->getObjList($class->_table,array('attribute'),array('item'=>$item));
        $ret = array();
        if(sizeof($object)>0) {
            foreach($object as $o) {
                $ret[] = (int)$o->attribute;
            }
        }
        return $ret;
    }
}